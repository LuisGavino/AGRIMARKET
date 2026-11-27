<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @param array<string, mixed> $overrides */
    private function form(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => 'Str0ng!pass',
            'password_confirmation' => 'Str0ng!pass',
            'terms' => '1',
        ], $overrides);
    }

    public function test_signup_page_has_a_csrf_protected_form(): void
    {
        $this->get('/signup')->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('name="password_confirmation"', false);
    }

    public function test_a_customer_can_register_and_the_password_is_hashed(): void
    {
        $this->post('/signup', $this->form())
            ->assertRedirect(route('home'))
            ->assertSessionHas('status');

        $user = User::where('email', 'maria@example.com')->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame(User::ROLE_CUSTOMER, $user->role);
        $this->assertNotSame('Str0ng!pass', $user->password);
        $this->assertTrue(Hash::check('Str0ng!pass', $user->password));
    }

    public function test_the_role_cannot_be_chosen_by_the_visitor(): void
    {
        $this->post('/signup', $this->form(['role' => 'admin']));

        $this->assertSame(User::ROLE_CUSTOMER, User::firstOrFail()->role);
    }

    public function test_required_fields_are_validated(): void
    {
        $this->post('/signup', [])->assertSessionHasErrors(['name', 'email', 'password', 'terms']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_a_duplicate_email_is_rejected_regardless_of_case(): void
    {
        User::factory()->create(['email' => 'maria@example.com']);

        $this->post('/signup', $this->form(['email' => 'MARIA@example.com']))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_a_mismatched_confirmation_is_rejected(): void
    {
        $this->post('/signup', $this->form(['password_confirmation' => 'Different1!']))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_weak_passwords_are_rejected(): void
    {
        foreach (['Ab1!', 'alllowercase!', 'NoSymbolsHere'] as $weak) {
            $this->post('/signup', $this->form(['password' => $weak, 'password_confirmation' => $weak]))
                ->assertSessionHasErrors('password');
        }

        $this->assertDatabaseCount('users', 0);
    }
}
