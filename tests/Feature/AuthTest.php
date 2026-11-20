<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Buat Akun Peserta');
    }

    public function test_new_user_can_register_as_peserta(): void
    {
        $response = $this->post('/register', [
            'name' => 'Peserta Baru',
            'email' => 'pesertabaru@test.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('peserta.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'pesertabaru@test.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('peserta', $user->role);
        $this->assertEquals(0, $user->total_points);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_peserta_login_redirects_to_peserta_dashboard(): void
    {
        $peserta = User::factory()->create([
            'email' => 'peserta@test.com',
            'role' => 'peserta',
        ]);

        $response = $this->post('/login', [
            'email' => 'peserta@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('peserta.dashboard'));
        $this->assertAuthenticatedAs($peserta);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
