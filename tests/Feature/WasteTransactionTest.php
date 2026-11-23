<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WasteCategory;
use App\Models\WasteGrade;
use App\Models\WasteTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WasteTransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $peserta;
    private User $admin;
    private WasteCategory $botol;
    private WasteCategory $kaleng;
    private WasteGrade $gradeA;

    protected function setUp(): void
    {
        parent::setUp();

        $this->peserta = User::factory()->create([
            'role' => 'peserta',
            'total_points' => 0,
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->botol = WasteCategory::create([
            'name' => 'Botol',
            'unit_type' => 'pcs',
            'unit_divisor' => 1,
            'base_point' => 1,
        ]);

        $this->kaleng = WasteCategory::create([
            'name' => 'Kaleng',
            'unit_type' => 'gram',
            'unit_divisor' => 100,
            'base_point' => 1,
        ]);

        $this->gradeA = WasteGrade::create([
            'grade_code' => 'A',
            'label' => 'Sangat Bagus',
            'bonus_point' => 4,
        ]);
    }

    public function test_peserta_can_submit_botol_waste(): void
    {
        $response = $this->actingAs($this->peserta)->post('/peserta/transactions', [
            'category_id' => $this->botol->id,
            'quantity_or_weight' => 20,
            'notes' => 'Botol plastik',
        ]);

        $response->assertRedirect(route('peserta.transactions.index'));

        // Transaksi berstatus pending, earned_points = 0
        $this->assertDatabaseHas('waste_transactions', [
            'user_id' => $this->peserta->id,
            'category_id' => $this->botol->id,
            'quantity_or_weight' => 20,
            'earned_points' => 0,
            'status' => 'pending',
        ]);

        // STRICT: Saldo poin peserta TIDAK BOLEH bertambah sebelum approved
        $this->assertEquals(0, $this->peserta->fresh()->total_points);
    }

    public function test_peserta_can_upload_waste_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('limbah_botol.jpg');

        $response = $this->actingAs($this->peserta)->post('/peserta/transactions', [
            'category_id' => $this->botol->id,
            'quantity_or_weight' => 15,
            'notes' => 'Botol mineral dengan foto',
            'image' => $file,
        ]);

        $response->assertRedirect(route('peserta.transactions.index'));

        $tx = WasteTransaction::where('user_id', $this->peserta->id)->latest()->first();
        $this->assertNotNull($tx->image_path);
        Storage::disk('public')->assertExists($tx->image_path);
        $this->assertNotNull($tx->image_url);
    }

    public function test_peserta_cannot_submit_kaleng_below_100_grams(): void
    {
        $response = $this->actingAs($this->peserta)->post('/peserta/transactions', [
            'category_id' => $this->kaleng->id,
            'quantity_or_weight' => 50, // di bawah 100 gram
        ]);

        $response->assertSessionHasErrors('quantity_or_weight');
        $this->assertEquals(0, WasteTransaction::count());
    }

    public function test_peserta_cannot_submit_prohibited_waste_category(): void
    {
        $kertas = WasteCategory::create([
            'name' => 'Kertas',
            'unit_type' => 'pcs',
            'unit_divisor' => 1,
            'base_point' => 1,
        ]);

        $response = $this->actingAs($this->peserta)->post('/peserta/transactions', [
            'category_id' => $kertas->id,
            'quantity_or_weight' => 10,
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertEquals(0, WasteTransaction::count());
    }

    public function test_admin_approval_calculates_points_and_increments_user_balance(): void
    {
        // 10 botol (base: 10 poin)
        $tx = WasteTransaction::create([
            'user_id' => $this->peserta->id,
            'category_id' => $this->botol->id,
            'quantity_or_weight' => 10,
            'earned_points' => 0,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/transactions/{$tx->id}/verify", [
            'action' => 'approve',
            'grade_id' => $this->gradeA->id, // Grade A bonus +4
            'notes' => 'Sangat bersih',
        ]);

        $response->assertRedirect(route('admin.transactions.index'));

        // Expected earned points = 10 + 4 = 14
        $tx->refresh();
        $this->assertEquals('approved', $tx->status);
        $this->assertEquals(14, $tx->earned_points);
        $this->assertEquals($this->gradeA->id, $tx->grade_id);
        $this->assertEquals($this->admin->id, $tx->verified_by);

        // Peserta total_points must increment to 14
        $this->assertEquals(14, $this->peserta->fresh()->total_points);
    }

    public function test_admin_rejection_does_not_increment_user_balance(): void
    {
        $tx = WasteTransaction::create([
            'user_id' => $this->peserta->id,
            'category_id' => $this->kaleng->id,
            'quantity_or_weight' => 500,
            'earned_points' => 0,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/transactions/{$tx->id}/verify", [
            'action' => 'reject',
            'notes' => 'Kaleng berkarat dan tercampur kotoran oli',
        ]);

        $response->assertRedirect(route('admin.transactions.index'));

        $tx->refresh();
        $this->assertEquals('rejected', $tx->status);
        $this->assertEquals(0, $tx->earned_points);

        // Peserta balance remains 0
        $this->assertEquals(0, $this->peserta->fresh()->total_points);
    }
}
