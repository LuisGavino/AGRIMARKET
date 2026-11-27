<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginLogoutTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create()->forceFill(['role' => User::ROLE_ADMIN]);
    }

    public function test_both_login_pages_post_to_the_shared_handler(): void
    {
        foreach (['/login', '/admin/login'] as $page) {
            $this->get($page)->assertOk()
                ->assertSee('action="'.route('login.store').'"', false)
                ->assertSee('name="_token"', false);
        }
    }

    public function test_a_customer_logs_in_and_lands_on_the_customer_home(): void
    {
        $user = User::factory()->create();

        $this->withSession(['_token' => 'before-login'])
            ->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
        $this->assertNotSame('before-login', session()->token());
    }

    public function test_an_admin_logs_in_and_lands_on_the_dashboard(): void
    {
        $admin = $this->admin();
        $admin->save();

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_credentials_show_an_error(): void
    {
        $user = User::factory()->create();

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_inputs_are_required(): void
    {
        $this->post('/login', [])->assertSessionHasErrors(['email', 'password']);
    }

    public function test_repeated_failures_are_throttled(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_ends_the_session_and_renews_the_token(): void
    {
        $this->actingAs(User::factory()->create())
            ->withSession(['_token' => 'old-token', 'marker' => 'kept?'])
            ->post('/logout')
            ->assertRedirect(route('landing'))
            ->assertSessionMissing('marker');

        $this->assertGuest();
        $this->assertNotSame('old-token', session()->token());
    }

    public function test_an_admin_returns_to_the_admin_login_after_logout(): void
    {
        $this->actingAs($this->admin())->post('/logout')->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }

    public function test_logout_is_not_available_by_get(): void
    {
        $this->actingAs(User::factory()->create())->get('/logout')->assertStatus(405);
    }
}
