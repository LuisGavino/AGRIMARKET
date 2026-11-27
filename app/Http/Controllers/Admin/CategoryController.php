<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('listings')->orderBy('type')->orderBy('name')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new Category]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        try {
            Category::create($data + ['slug' => Str::slug($data['name'])]);
        } catch (Throwable $e) {
            Log::error('Category create failed', ['exception' => $e]);

            return back()->withInput()->withErrors(['name' => 'The category could not be saved.']);
        }

        return redirect()->route('admin.categories')->with('status', 'Category created.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, $category);

        // Changing the type would leave listings in a category of another type.
        if ($data['type'] !== $category->type && $category->listings()->exists()) {
            return back()->withInput()->withErrors(['type' => 'The type cannot change while listings use this category.']);
        }

        try {
            $category->update($data + ['slug' => Str::slug($data['name'])]);
        } catch (Throwable $e) {
            Log::error('Category update failed', ['exception' => $e]);

            return back()->withInput()->withErrors(['name' => 'The category could not be updated.']);
        }

        return redirect()->route('admin.categories')->with('status', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->listings()->exists()) {
            return back()->withErrors(['delete' => 'This category is still used by listings and cannot be deleted.']);
        }

        try {
            $category->delete();
        } catch (Throwable $e) {
            Log::error('Category delete failed', ['exception' => $e]);

            return back()->withErrors(['delete' => 'The category could not be deleted.']);
        }

        return redirect()->route('admin.categories')->with('status', 'Category deleted.');
    }

    /** @return array{type: string, name: string} */
    private function validated(Request $request, ?Category $category = null): array
    {
        $type = $request->input('type');

        return $request->validate([
            'type' => ['required', Rule::in(array_keys(Listing::TYPES))],
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('categories', 'name')->where('type', is_string($type) ? $type : '')->ignore($category?->id),
            ],
        ], ['name.unique' => 'This category already exists for that type.']);
    }
}
