<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListingRequest;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * One controller for the Produce, Livestock and Service Provider admin pages. The listing
 * type is a route default (see routes/web.php), so it cannot be changed from the browser.
 */
class ListingController extends Controller
{
    public function index(Request $request): View
    {
        $type = $this->type($request);
        $filter = $request->query('filter');

        $query = Listing::ofType($type)->with('category')->search($request->query('q'))->latest('id');

        match ($filter) {
            'featured' => $query->where('is_featured', true),
            'out-of-stock' => $query->where('quantity', 0),
            'inactive' => $query->where('status', '!=', 'active'),
            default => null,
        };

        $base = Listing::ofType($type);

        return view('admin.listings.index', [
            'type' => $type,
            'config' => Listing::TYPES[$type],
            'items' => $query->paginate(8)->withQueryString(),
            'filter' => in_array($filter, ['featured', 'out-of-stock', 'inactive'], true) ? $filter : null,
            'search' => (string) $request->query('q', ''),
            'counts' => [
                'all' => (clone $base)->count(),
                'week' => (clone $base)->where('created_at', '>=', now()->subDays(7))->count(),
                'today' => (clone $base)->whereDate('created_at', today())->count(),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $type = $this->type($request);

        return view('admin.listings.form', $this->formData($type, new Listing(['type' => $type, 'status' => 'active'])));
    }

    public function store(ListingRequest $request): RedirectResponse
    {
        $type = $request->listingType();
        $data = $request->safe()->except(['image', 'remove_image']);
        $data['type'] = $type;
        $data['is_featured'] = $request->boolean('is_featured');

        $path = null;

        try {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('listings', 'public');
                $data['image_path'] = $path;
            }

            $listing = new Listing($data);
            $listing->seller_id = $request->user()->id;
            $listing->save();
        } catch (Throwable $e) {
            if ($path) {
                \Storage::disk('public')->delete($path);
            }
            Log::error('Listing create failed', ['exception' => $e]);

            return back()->withInput()->withErrors(['name' => 'The listing could not be saved. Please try again.']);
        }

        return redirect()->route(Listing::TYPES[$type]['route'])->with('status', 'Listing created.');
    }

    public function show(Request $request, Listing $listing): View
    {
        $type = $this->type($request);
        $this->ensureType($listing, $type);

        return view('admin.listings.show', [
            'listing' => $listing->load(['category', 'seller']),
            'type' => $type,
            'config' => Listing::TYPES[$type],
        ]);
    }

    public function edit(Request $request, Listing $listing): View
    {
        $type = $this->type($request);
        $this->ensureType($listing, $type);

        return view('admin.listings.form', $this->formData($type, $listing));
    }

    public function update(ListingRequest $request, Listing $listing): RedirectResponse
    {
        $type = $request->listingType();
        $this->ensureType($listing, $type);

        $data = $request->safe()->except(['image', 'remove_image']);
        $data['is_featured'] = $request->boolean('is_featured');

        $newPath = null;
        $oldImage = $listing->hasUploadedImage() ? $listing->image_path : null;

        try {
            DB::transaction(function () use ($request, $listing, &$data, &$newPath) {
                if ($request->hasFile('image')) {
                    $newPath = $request->file('image')->store('listings', 'public');
                    $data['image_path'] = $newPath;
                } elseif ($request->boolean('remove_image')) {
                    $data['image_path'] = null;
                }
                // No new file and no removal: image_path is left untouched.

                $listing->update($data);
            });
        } catch (Throwable $e) {
            if ($newPath) {
                \Storage::disk('public')->delete($newPath);
            }
            Log::error('Listing update failed', ['exception' => $e]);

            return back()->withInput()->withErrors(['name' => 'The listing could not be updated. Please try again.']);
        }

        // Remove the previous upload only after the database change succeeded.
        if ($oldImage && $listing->image_path !== $oldImage) {
            \Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route(Listing::TYPES[$type]['route'])->with('status', 'Listing updated.');
    }

    public function destroy(Request $request, Listing $listing): RedirectResponse
    {
        $type = $this->type($request);
        $this->ensureType($listing, $type);

        try {
            $listing->delete();
        } catch (Throwable $e) {
            Log::error('Listing delete failed', ['exception' => $e]);

            return back()->withErrors(['delete' => 'The listing could not be deleted.']);
        }

        $listing->deleteImageFile();

        return redirect()->route(Listing::TYPES[$type]['route'])->with('status', 'Listing deleted.');
    }

    private function type(Request $request): string
    {
        return (string) $request->route()->defaults['type'];
    }

    /** A produce URL must not open or change a livestock listing. */
    private function ensureType(Listing $listing, string $type): void
    {
        abort_unless($listing->type === $type, 404);
    }

    private function formData(string $type, Listing $listing): array
    {
        return [
            'type' => $type,
            'config' => Listing::TYPES[$type],
            'listing' => $listing,
            'categories' => Category::where('type', $type)->orderBy('name')->get(),
        ];
    }
}
