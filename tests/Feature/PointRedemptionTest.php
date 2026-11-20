<?php

namespace Tests\Feature;

use App\Models\PointRedemption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointRedemptionTest extends TestCase
{
    use RefreshDatabase;

    private User $peserta;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'total_points' => 35, // 35 poin: greedy = 1x20 (1000) + 1x10 (500) + 5x1 (500) = Rp 2.000
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    public function test_peserta_can_request_redemption_with_valid_points(): void
    {
        $response = $this->actingAs($this->peserta)->post('/peserta/redemptions', [
            'points_used' => 25, // greedy: 1x20 (1000) + 0x10 + 5x1 (500) = 1500
            'payment_info' => 'BCA 123456789 a/n Budi',
        ]);

        $response->assertRedirect(route('peserta.redemptions.index'));

        // Saldo peserta dipotong 25 -> sisa 10
        $this->assertEquals(10, $this->peserta->fresh()->total_points);

        $this->assertDatabaseHas('point_redemptions', [
            'user_id' => $this->peserta->id,
            'points_used' => 25,
            'rupiah_amount' => 1500,
            'status' => 'pending',
            'payment_info' => 'BCA 123456789 a/n Budi',
        ]);
    }

    public function test_peserta_cannot_redeem_more_points_than_owned(): void
    {
        // Punya 35, coba tukar 40
        $response = $this->actingAs($this->peserta)->post('/peserta/redemptions', [
            'points_used' => 40,
            'payment_info' => 'DANA 0812345678',
        ]);

        $response->assertSessionHasErrors('points_used');
        $this->assertEquals(35, $this->peserta->fresh()->total_points);
        $this->assertEquals(0, PointRedemption::count());
    }

    public function test_admin_can_complete_redemption(): void
    {
        $redemption = PointRedemption::create([
            'user_id' => $this->peserta->id,
            'points_used' => 20,
            'rupiah_amount' => 1000,
            'status' => 'pending',
            'payment_info' => 'GoPay 0812345678',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/redemptions/{$redemption->id}/complete", [
            'notes' => 'Transfer berhasil via GoPay transfer ID #99182',
        ]);

        $response->assertRedirect(route('admin.redemptions.index'));

        $redemption->refresh();
        $this->assertEquals('completed', $redemption->status);
        $this->assertStringContainsString('Transfer berhasil', $redemption->notes);
    }

    public function test_admin_rejecting_redemption_refunds_points_to_peserta(): void
    {
        // Awalnya peserta punya 35 poin, lalu 20 poin diajukan klaim sehingga tersisa 15 poin
        $this->peserta->update(['total_points' => 15]);

        $redemption = PointRedemption::create([
            'user_id' => $this->peserta->id,
            'points_used' => 20,
            'rupiah_amount' => 1000,
            'status' => 'pending',
            'payment_info' => 'Rekening Salah 000000',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/redemptions/{$redemption->id}/reject", [
            'notes' => 'Nomor rekening tidak ditemukan pada sistem perbankan.',
        ]);

        $response->assertRedirect(route('admin.redemptions.index'));

        $redemption->refresh();
        $this->assertEquals('rejected', $redemption->status);

        // Poin harus di-refund: 15 + 20 = 35 poin!
        $this->assertEquals(35, $this->peserta->fresh()->total_points);
    }
}
