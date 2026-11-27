<?php

namespace Tests\Feature\Customer;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomerInterfaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_browse_and_search_active_listings_only(): void
    {
        Listing::factory()->create(['name' => 'Visible Apple']);
        Listing::factory()->livestock()->create(['name' => 'Visible Cow']);
        Listing::factory()->create(['name' => 'Hidden Sold', 'status' => 'sold']);

        $this->get(route('products'))->assertOk()->assertSee('Visible Apple')->assertSee('Visible Cow')->assertDontSee('Hidden Sold');
        $this->get(route('products', ['q' => 'Apple']))->assertSee('Visible Apple')->assertDontSee('Visible Cow');
        $this->get(route('products', ['type' => 'livestock']))->assertSee('Visible Cow')->assertDontSee('Visible Apple');
    }

    public function test_product_detail_shows_provider_and_hides_inactive_listings(): void
    {
        $seller = $this->makeCustomer();
        $listing = Listing::factory()->create(['name' => 'Detail Apple']);
        $listing->seller_id = $seller->id;
        $listing->save();
        $hidden = Listing::factory()->create(['status' => 'inactive']);

        $this->get(route('products.show', $listing))->assertOk()->assertSee('Detail Apple')->assertSee($seller->name);
        $this->get(route('products.show', $hidden))->assertNotFound();
    }

    public function test_static_landing_and_about_pages_still_work(): void
    {
        $this->get('/')->assertOk();
        $this->get('/about')->assertOk();
    }

    public function test_customer_sees_only_their_own_orders(): void
    {
        $me = $this->makeCustomer();
        $mine = Order::factory()->create(['user_id' => $me->id]);
        $theirs = Order::factory()->create(['user_id' => $this->makeCustomer()->id]);

        $this->actingAs($me)->get(route('orders.index'))->assertOk()->assertSee('Order #'.$mine->id)->assertDontSee('Order #'.$theirs->id);
        $this->actingAs($me)->get(route('orders.show', $mine))->assertOk();
        $this->actingAs($me)->get(route('orders.show', $theirs))->assertForbidden();
        $this->get(route('orders.index'))->assertRedirect(route('login'));
    }

    public function test_customer_cannot_reach_admin_area(): void
    {
        $this->actingAs($this->makeCustomer())->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_seller_creates_own_listing_with_image_and_cannot_touch_others(): void
    {
        Storage::fake('public');
        $me = $this->makeCustomer();
        $category = Category::factory()->create(['type' => 'produce']);

        $this->actingAs($me)->post(route('seller.store', 'produce'), [
            'name' => 'My Corn', 'price' => '300', 'quantity' => 4, 'unit' => 'kg', 'category_id' => $category->id,
            'photos' => [UploadedFile::fake()->image('corn.jpg')],
            'seller_id' => 999, 'status' => 'sold',
        ])->assertRedirect(route('seller.listings'));

        $mine = Listing::where('name', 'My Corn')->firstOrFail();
        $this->assertSame($me->id, $mine->seller_id);
        $this->assertSame('active', $mine->status);
        Storage::disk('public')->assertExists($mine->image_path);

        $this->actingAs($me)->get(route('seller.listings'))->assertOk()->assertSee('My Corn');

        $someoneElses = Listing::factory()->create();
        $this->actingAs($me)->patch(route('seller.sold', $someoneElses))->assertForbidden();
        $this->assertSame('active', $someoneElses->fresh()->status);

        $this->actingAs($me)->patch(route('seller.sold', $mine))->assertRedirect();
        $this->assertSame('sold', $mine->fresh()->status);
    }

    public function test_seller_form_validates_input(): void
    {
        $this->actingAs($this->makeCustomer())->post(route('seller.store', 'produce'), ['name' => '', 'price' => 'x', 'quantity' => -1])
            ->assertSessionHasErrors(['name', 'price', 'quantity']);
        $this->assertDatabaseCount('listings', 0);
    }
}
