<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_admin_routes(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_to_login_when_accessing_peserta_routes(): void
    {
        $response = $this->get('/peserta/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_peserta_cannot_access_admin_routes(): void
    {
        $peserta = User::factory()->create([
            'role' => 'peserta',
        ]);

        $response = $this->actingAs($peserta)->get('/admin/dashboard');
        $response->assertStatus(403);

        $response2 = $this->actingAs($peserta)->get('/admin/transactions');
        $response2->assertStatus(403);
    }

    public function test_admin_cannot_access_peserta_routes(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/peserta/dashboard');
        $response->assertStatus(403);
    }
}
