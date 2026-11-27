<?php

namespace App\Http\Requests\Admin;

use App\Models\Listing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates a listing for any type. The "type" comes from the route (never from the form),
 * and only the two fields that belong to that type are accepted.
 */
class ListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Route middleware ("auth" + "admin") is the real gate; this is a second check.
        return (bool) $this->user()?->isAdmin();
    }

    public function listingType(): string
    {
        return (string) $this->route()->defaults['type'];
    }

    public function rules(): array
    {
        $type = $this->listingType();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', $type)],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(Listing::STATUSES)],
            'is_featured' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_image' => ['sometimes', 'boolean'],
        ];

        return $rules + match ($type) {
            Listing::TYPE_PRODUCE => ['unit' => ['nullable', 'string', 'max:50'], 'harvest_date' => ['nullable', 'date']],
            Listing::TYPE_LIVESTOCK => ['weight' => ['nullable', 'numeric', 'min:0', 'max:999999.99'], 'purpose' => ['nullable', 'string', 'max:255']],
            default => ['service_area' => ['nullable', 'string', 'max:255'], 'availability' => ['nullable', 'string', 'max:255']],
        };
    }

    public function messages(): array
    {
        return [
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a JPG, PNG or WebP file.',
            'image.max' => 'The image may not be larger than 4 MB.',
            'image.uploaded' => 'The image could not be uploaded. Please try again.',
        ];
    }
}
