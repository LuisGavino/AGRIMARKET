<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_list_update_delete(): void
    {
        $admin = $this->actingAs($this->makeAdmin());

        $admin->post(route('admin.categories.store'), ['name' => 'Seeds', 'type' => 'produce'])->assertRedirect(route('admin.categories'));
        $category = Category::where('name', 'Seeds')->firstOrFail();
        $this->assertSame('seeds', $category->slug);

        $admin->get(route('admin.categories'))->assertOk()->assertSee('Seeds');

        $admin->put(route('admin.categories.update', $category), ['name' => 'Grains', 'type' => 'produce'])->assertRedirect(route('admin.categories'));
        $this->assertSame('Grains', $category->fresh()->name);

        $admin->delete(route('admin.categories.destroy', $category))->assertRedirect(route('admin.categories'));
        $this->assertModelMissing($category);
    }

    public function test_duplicate_and_invalid_categories_are_rejected(): void
    {
        Category::factory()->create(['name' => 'Seeds', 'type' => 'produce']);
        $admin = $this->actingAs($this->makeAdmin());

        $admin->post(route('admin.categories.store'), ['name' => 'Seeds', 'type' => 'produce'])->assertSessionHasErrors('name');
        $admin->post(route('admin.categories.store'), ['name' => 'Seeds', 'type' => 'livestock'])->assertSessionDoesntHaveErrors();
        $admin->post(route('admin.categories.store'), ['name' => '', 'type' => 'bogus'])->assertSessionHasErrors(['name', 'type']);
    }

    public function test_category_in_use_cannot_be_deleted_or_change_type(): void
    {
        $category = Category::factory()->create(['type' => 'produce']);
        $listing = Listing::factory()->create(['category_id' => $category->id]);
        $admin = $this->actingAs($this->makeAdmin());

        $admin->delete(route('admin.categories.destroy', $category))->assertSessionHasErrors('delete');
        $this->assertModelExists($category);

        $admin->put(route('admin.categories.update', $category), ['name' => $category->name, 'type' => 'livestock'])->assertSessionHasErrors('type');
        $this->assertSame($category->id, $listing->fresh()->category_id);
    }

    public function test_customers_cannot_manage_categories(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->makeCustomer())->post(route('admin.categories.store'), ['name' => 'X', 'type' => 'produce'])->assertForbidden();
        $this->actingAs($this->makeCustomer())->delete(route('admin.categories.destroy', $category))->assertForbidden();
        $this->assertModelExists($category);
    }
}
