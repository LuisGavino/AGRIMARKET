<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

/** A signed-in user manages only their own listings. */
class ListingController extends Controller
{
    public function index(Request $request): View
    {
        return view('seller.listings', [
            'listings' => $request->user()->listings()->with('category')->latest('id')->get(),
        ]);
    }

    public function create(string $type): View
    {
        return view('seller.create', [
            'type' => $type,
            'categories' => Category::where('type', $type)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        abort_unless(array_key_exists($type, Listing::TYPES), 404);

        $fields = array_keys(Listing::TYPES[$type]['fields']);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', $type)],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'location' => ['nullable', 'string', 'max:255'],
            'photos' => ['nullable', 'array', 'max:4'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];

        foreach ($fields as $field) {
            $rules[$field] = match ($field) {
                'harvest_date' => ['nullable', 'date'],
                'weight' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
                default => ['nullable', 'string', 'max:255'],
            };
        }

        $data = $request->validate($rules, [
            'photos.*.image' => 'Each photo must be an image.',
            'photos.*.mimes' => 'Photos must be JPG, PNG or WebP files.',
            'photos.*.max' => 'Each photo may not be larger than 4 MB.',
        ]);

        $path = null;

        try {
            $photo = collect($request->file('photos', []))->filter()->first();
            $path = $photo?->store('listings', 'public');

            $listing = new Listing(collect($data)->except('photos')->all());
            $listing->type = $type;
            $listing->image_path = $path;
            $listing->seller_id = $request->user()->id;
            $listing->save();
        } catch (Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            Log::error('Seller listing create failed', ['exception' => $e]);

            return back()->withInput()->withErrors(['name' => 'Your listing could not be saved. Please try again.']);
        }

        return redirect()->route('seller.listings')->with('status', 'Listing created.');
    }

    public function markSold(Request $request, Listing $listing): RedirectResponse
    {
        abort_unless($listing->seller_id === $request->user()->id, 403);

        $listing->update(['status' => 'sold']);

        return back()->with('status', 'Listing marked as sold.');
    }
}
