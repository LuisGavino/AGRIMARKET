<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private array $valid = ['name' => 'New Person', 'email' => 'New@Example.com', 'role' => 'customer', 'password' => 'Str0ng!Passw0rd', 'password_confirmation' => 'Str0ng!Passw0rd'];

    public function test_admin_can_list_and_search_users(): void
    {
        User::factory()->create(['name' => 'Findable Person']);
        User::factory()->create(['name' => 'Someone Else']);

        $this->actingAs($this->makeAdmin())->get(route('admin.users', ['q' => 'Findable']))
            ->assertOk()->assertSee('Findable Person')->assertDontSee('Someone Else');
    }

    public function test_admin_can_create_user_with_hashed_password_and_lowercase_email(): void
    {
        $this->actingAs($this->makeAdmin())->post(route('admin.users.store'), $this->valid)->assertRedirect(route('admin.users'));

        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertNotSame('Str0ng!Passw0rd', $user->password);
        $this->assertSame('customer', $user->role);
    }

    public function test_create_rejects_duplicate_email_and_invalid_input(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin)->post(route('admin.users.store'), ['email' => $admin->email] + $this->valid)->assertSessionHasErrors('email');
        $this->actingAs($admin)->post(route('admin.users.store'), ['role' => 'root'] + $this->valid)->assertSessionHasErrors('role');
        $this->actingAs($admin)->post(route('admin.users.store'), ['password' => 'short', 'password_confirmation' => 'short'] + $this->valid)->assertSessionHasErrors('password');
    }

    public function test_admin_can_update_user_and_blank_password_keeps_existing(): void
    {
        $user = User::factory()->create();
        $hash = $user->password;

        $this->actingAs($this->makeAdmin())->put(route('admin.users.update', $user), ['name' => 'Renamed', 'email' => $user->email, 'role' => 'customer', 'password' => ''])
            ->assertRedirect(route('admin.users'));

        $this->assertSame('Renamed', $user->fresh()->name);
        $this->assertSame($hash, $user->fresh()->password);
    }

    public function test_admin_can_delete_a_customer_but_not_self_last_admin_or_user_with_orders(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $withOrder = $this->makeCustomer();
        Order::factory()->create(['user_id' => $withOrder->id]);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $customer))->assertRedirect(route('admin.users'));
        $this->assertModelMissing($customer);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('delete');
        $this->assertModelExists($admin);

        $this->actingAs($admin)->delete(route('admin.users.destroy', $withOrder))->assertSessionHasErrors('delete');
        $this->assertModelExists($withOrder);
    }

    public function test_password_hash_is_never_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->makeAdmin())->get(route('admin.users'))->assertDontSee($user->password, false);
        $this->actingAs($this->makeAdmin())->get(route('admin.users.edit', $user))->assertDontSee($user->password, false);
    }

    public function test_customers_and_guests_cannot_manage_users(): void
    {
        $target = $this->makeCustomer();

        $this->get(route('admin.users'))->assertRedirect(route('admin.login'));
        $this->actingAs($this->makeCustomer())->get(route('admin.users'))->assertForbidden();
        $this->actingAs($this->makeCustomer())->post(route('admin.users.store'), $this->valid)->assertForbidden();
        $this->actingAs($this->makeCustomer())->put(route('admin.users.update', $target), $this->valid)->assertForbidden();
        $this->actingAs($this->makeCustomer())->delete(route('admin.users.destroy', $target))->assertForbidden();
        $this->assertModelExists($target);
    }
}
