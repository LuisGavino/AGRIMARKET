<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** Produce, livestock and service providers share one controller, so each is tested with the same cases. */
class ListingManagementTest extends TestCase
{
    use RefreshDatabase;

    public static function types(): array
    {
        return [
            'produce' => ['produce', 'produce', ['unit' => 'kg', 'harvest_date' => '2026-01-15']],
            'livestock' => ['livestock', 'livestock', ['weight' => '12.5', 'purpose' => 'Meat']],
            'service' => ['service-providers', 'service', ['service_area' => 'Surigao', 'availability' => 'Weekdays']],
        ];
    }

    private function payload(array $extra = [], ?Category $category = null): array
    {
        return array_merge([
            'name' => 'Test Item', 'description' => 'Fresh', 'price' => '99.50', 'quantity' => 5,
            'status' => 'active', 'category_id' => $category?->id,
        ], $extra);
    }

    #[DataProvider('types')]
    public function test_create_read_update_delete(string $path, string $type, array $extra): void
    {
        $admin = $this->makeAdmin();
        $category = Category::factory()->create(['type' => $type]);

        $this->actingAs($admin)->post("/admin/$path", $this->payload($extra, $category))->assertRedirect("/admin/$path");
        $listing = Listing::where('name', 'Test Item')->firstOrFail();
        $this->assertSame($type, $listing->type);
        $this->assertSame($admin->id, $listing->seller_id);

        $this->actingAs($admin)->get("/admin/$path")->assertOk()->assertSee('Test Item');
        $this->actingAs($admin)->get("/admin/$path/{$listing->id}")->assertOk()->assertSee('Test Item');

        $this->actingAs($admin)->put("/admin/$path/{$listing->id}", $this->payload(['name' => 'Updated Item', 'price' => '10'] + $extra, $category))
            ->assertRedirect("/admin/$path");
        $this->assertSame('Updated Item', $listing->fresh()->name);

        $this->actingAs($admin)->delete("/admin/$path/{$listing->id}")->assertRedirect("/admin/$path");
        $this->assertModelMissing($listing);
    }

    #[DataProvider('types')]
    public function test_validation_rejects_bad_input(string $path, string $type, array $extra): void
    {
        $admin = $this->actingAs($this->makeAdmin());
        $otherType = Category::factory()->create(['type' => $type === 'produce' ? 'livestock' : 'produce']);

        $admin->post("/admin/$path", $this->payload(['name' => '']))->assertSessionHasErrors('name');
        $admin->post("/admin/$path", $this->payload(['price' => '-1']))->assertSessionHasErrors('price');
        $admin->post("/admin/$path", $this->payload(['quantity' => 'abc']))->assertSessionHasErrors('quantity');
        $admin->post("/admin/$path", $this->payload(['status' => 'bogus']))->assertSessionHasErrors('status');
        $admin->post("/admin/$path", $this->payload([], $otherType))->assertSessionHasErrors('category_id');
        $this->assertDatabaseCount('listings', 0);
    }

    public function test_type_cannot_be_forced_from_the_form_or_a_wrong_url(): void
    {
        $admin = $this->actingAs($this->makeAdmin());
        $admin->post('/admin/produce', $this->payload(['type' => 'livestock']))->assertRedirect();
        $this->assertSame('produce', Listing::firstOrFail()->type);

        $livestock = Listing::factory()->livestock()->create();
        $admin->get("/admin/produce/{$livestock->id}")->assertNotFound();
        $admin->delete("/admin/produce/{$livestock->id}")->assertNotFound();
        $this->assertModelExists($livestock);
    }

    public function test_image_upload_is_stored_validated_replaced_and_removed(): void
    {
        Storage::fake('public');
        $admin = $this->actingAs($this->makeAdmin());

        $admin->post('/admin/produce', $this->payload(['image' => UploadedFile::fake()->image('a.jpg')]))->assertRedirect();
        $listing = Listing::firstOrFail();
        Storage::disk('public')->assertExists($listing->image_path);
        $first = $listing->image_path;

        // Update without a new file keeps the existing image.
        $admin->put("/admin/produce/{$listing->id}", $this->payload(['name' => 'Same Image']))->assertRedirect();
        $this->assertSame($first, $listing->fresh()->image_path);
        Storage::disk('public')->assertExists($first);

        // Replacing the image deletes the old file.
        $admin->put("/admin/produce/{$listing->id}", $this->payload(['image' => UploadedFile::fake()->image('b.png')]))->assertRedirect();
        $second = $listing->fresh()->image_path;
        $this->assertNotSame($first, $second);
        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($second);

        // Deleting the listing deletes its file.
        $admin->delete("/admin/produce/{$listing->id}")->assertRedirect();
        Storage::disk('public')->assertMissing($second);
    }

    public function test_invalid_image_files_are_rejected(): void
    {
        Storage::fake('public');
        $admin = $this->actingAs($this->makeAdmin());

        $admin->post('/admin/produce', $this->payload(['image' => UploadedFile::fake()->create('x.pdf', 10, 'application/pdf')]))->assertSessionHasErrors('image');
        $admin->post('/admin/produce', $this->payload(['image' => UploadedFile::fake()->image('big.jpg')->size(5000)]))->assertSessionHasErrors('image');
        $this->assertDatabaseCount('listings', 0);
        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_bundled_design_images_are_never_deleted_from_storage(): void
    {
        Storage::fake('public');
        $listing = Listing::factory()->create(['image_path' => 'images/products/apple.jpg']);

        $this->actingAs($this->makeAdmin())->delete("/admin/produce/{$listing->id}")->assertRedirect();

        $this->assertModelMissing($listing);
        $this->assertFileExists(public_path('images/products/apple.jpg'));
    }

    public function test_search_and_filters(): void
    {
        Listing::factory()->create(['name' => 'Alpha Apple', 'is_featured' => true]);
        Listing::factory()->create(['name' => 'Beta Banana', 'quantity' => 0]);
        $admin = $this->actingAs($this->makeAdmin());

        $admin->get('/admin/produce?q=Alpha')->assertSee('Alpha Apple')->assertDontSee('Beta Banana');
        $admin->get('/admin/produce?filter=out-of-stock')->assertSee('Beta Banana')->assertDontSee('Alpha Apple');
        $admin->get('/admin/produce?filter=featured')->assertSee('Alpha Apple')->assertDontSee('Beta Banana');
    }

    public function test_pagination_shows_eight_per_page(): void
    {
        Listing::factory()->count(10)->create();

        $this->actingAs($this->makeAdmin())->get('/admin/produce')->assertViewHas('items', fn ($items) => $items->count() === 8 && $items->total() === 10);
    }

    public function test_missing_listing_returns_404(): void
    {
        $this->actingAs($this->makeAdmin())->get('/admin/produce/999')->assertNotFound();
    }

    #[DataProvider('types')]
    public function test_customers_and_guests_cannot_change_listings(
        string $path,
        string $type,
        array $extra
    ): void
    {
        $listing = Listing::factory()->create(['type' => 'produce']);

        $this->post("/admin/$path", $this->payload())->assertRedirect();
        $this->actingAs($this->makeCustomer())->post("/admin/$path", $this->payload())->assertForbidden();
        $this->actingAs($this->makeCustomer())->put("/admin/produce/{$listing->id}", $this->payload())->assertForbidden();
        $this->actingAs($this->makeCustomer())->delete("/admin/produce/{$listing->id}")->assertForbidden();
        $this->assertModelExists($listing);
        $this->assertDatabaseCount('listings', 1);
    }
}
