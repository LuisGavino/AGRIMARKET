<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Public browsing of active listings (read only). */
class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');
        $term = $request->query('q');

        $listings = Listing::active()
            ->when(is_string($type) && array_key_exists($type, Listing::TYPES), fn ($q) => $q->ofType($type))
            ->search(is_string($term) ? $term : null)
            ->orderByDesc('is_featured')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $searching = (is_string($term) && trim($term) !== '') || is_string($type);

        return view('pages.products', [
            'products' => $listings,
            'searching' => $searching,
            'term' => is_string($term) ? $term : '',
        ]);
    }

    public function show(Listing $listing): View
    {
        // Sold or inactive listings are not shown to the public.
        abort_unless($listing->status === 'active', 404);

        return view('pages.product-show', ['listing' => $listing->load(['category', 'seller:id,name'])]);
    }
}
