<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HukumPortalBypassTest extends TestCase
{
    use RefreshDatabase;

    private $adminUser;
    private $hukumUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = Admin::create([
            'name' => 'Admin E-Riset',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $this->hukumUser = Admin::create([
            'name' => 'Hukum E-Riset',
            'username' => 'hukum',
            'email' => 'hukum@test.com',
            'role' => 'hukum',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_hukum_user_cannot_access_portal_and_is_redirected_to_dashboard(): void
    {
        $response = $this->actingAs($this->hukumUser)->get('/admin/portal');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_user_can_access_portal(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/portal');

        $response->assertStatus(200);
        $response->assertViewIs('admin.portal.index');
    }

    public function test_hukum_user_login_redirects_to_dashboard(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'hukum',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->hukumUser);
    }

    public function test_admin_user_login_redirects_to_portal(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'admin',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/portal');
        $this->assertAuthenticatedAs($this->adminUser);
    }

    public function test_authenticated_hukum_user_accessing_login_page_redirects_to_dashboard(): void
    {
        $response = $this->actingAs($this->hukumUser)->get('/admin/login');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_admin_user_accessing_login_page_redirects_to_portal(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/login');

        $response->assertRedirect(route('admin.portal'));
    }

    public function test_admin_dashboard_shows_stats_but_no_recent_submissions_table(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('Total Permohonan');
        $response->assertDontSee('Permohonan Terbaru');
    }

    public function test_hukum_dashboard_shows_stats_and_recent_submissions_table(): void
    {
        $response = $this->actingAs($this->hukumUser)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('Total Permohonan');
        $response->assertSee('Permohonan Terbaru');
    }
}
