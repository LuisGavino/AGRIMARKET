<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    private const CUSTOMER_PAGES = [
        '/home', '/search', '/notifications', '/messages', '/wishlist', '/checkout',
        '/settings/account', '/settings/security', '/seller/listings', '/seller/listings/new',
        '/seller/listings/new/produce', '/seller/listings/new/livestock', '/seller/listings/new/service',
    ];

    private const ADMIN_PAGES = [
        '/admin/dashboard', '/admin/users', '/admin/produce', '/admin/livestock',
        '/admin/service-providers', '/admin/settings/account', '/admin/settings/security',
    ];

    public function test_public_pages_stay_public(): void
    {
        foreach (['/', '/about', '/products', '/login', '/signup', '/admin/login'] as $page) {
            $this->get($page)->assertOk();
        }
    }

    public function test_guests_are_sent_to_the_matching_login_page(): void
    {
        foreach (self::CUSTOMER_PAGES as $page) {
            $this->get($page)->assertRedirect(route('login'));
        }

        foreach (self::ADMIN_PAGES as $page) {
            $this->get($page)->assertRedirect(route('admin.login'));
        }
    }

    public function test_customers_can_use_customer_pages_but_not_admin_pages(): void
    {
        $this->actingAs(User::factory()->create(['name' => 'Maria Santos']));

        foreach (self::CUSTOMER_PAGES as $page) {
            $this->get($page)->assertOk();
        }

        $this->get('/home')->assertSee('Maria Santos')->assertSee('action="'.route('logout').'"', false);

        foreach (self::ADMIN_PAGES as $page) {
            $this->get($page)->assertForbidden();
        }
    }

    public function test_admins_can_open_every_admin_page(): void
    {
        $this->actingAs(User::factory()->create()->forceFill(['role' => User::ROLE_ADMIN]));

        foreach (self::ADMIN_PAGES as $page) {
            $this->get($page)->assertOk()->assertSee('action="'.route('logout').'"', false);
        }
    }

    public function test_signed_in_users_are_redirected_away_from_login(): void
    {
        $this->actingAs(User::factory()->create())->get('/login')->assertRedirect(route('home'));
    }

    public function test_protected_pages_are_not_cached_by_the_browser(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/home');

        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }

    public function test_the_make_admin_command_creates_an_administrator(): void
    {
        $this->artisan('agrimarket:make-admin')
            ->expectsQuestion('Admin email', 'boss@example.com')
            ->expectsQuestion('Admin name', 'Boss')
            ->expectsQuestion('Password', 'Str0ng!pass')
            ->expectsQuestion('Confirm password', 'Str0ng!pass')
            ->assertSuccessful();

        $this->assertTrue(User::where('email', 'boss@example.com')->firstOrFail()->isAdmin());
    }
}
