<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'category_id', 'type', 'name', 'description', 'price', 'quantity', 'unit', 'harvest_date',
    'weight', 'purpose', 'service_area', 'availability', 'location', 'image_path',
    'is_featured', 'status',
])]
class Listing extends Model
{
    use HasFactory;

    public const TYPE_PRODUCE = 'produce';

    public const TYPE_LIVESTOCK = 'livestock';

    public const TYPE_SERVICE = 'service';

    public const STATUSES = ['active', 'inactive', 'sold'];

    /**
     * Per-type settings: admin page title, the admin route-name prefix (these names already
     * exist in the sidebar) and the two type-specific fields of the shared listing form.
     */
    public const TYPES = [
        self::TYPE_PRODUCE => [
            'label' => 'Produce',
            'route' => 'admin.produce',
            'fields' => ['unit' => 'Unit', 'harvest_date' => 'Harvest Date'],
        ],
        self::TYPE_LIVESTOCK => [
            'label' => 'Livestock',
            'route' => 'admin.livestock',
            'fields' => ['weight' => 'Weight', 'purpose' => 'Purpose'],
        ],
        self::TYPE_SERVICE => [
            'label' => 'Service Provider',
            'route' => 'admin.service-providers',
            'fields' => ['service_area' => 'Service Area', 'availability' => 'Availability'],
        ],
    ];

    /** Placeholder used when a listing has no image. */
    public const PLACEHOLDER_IMAGE = 'images/products/product-green-apple.png';

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'weight' => 'decimal:2',
            'harvest_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        return $term === ''
            ? $query
            : $query->where(function (Builder $q) use ($term) {
                $like = '%'.addcslashes($term, '%_\\').'%';
                $q->where('name', 'like', $like)->orWhere('description', 'like', $like);
            });
    }

    /** True when the image is a file we uploaded (not a bundled design asset). */
    public function hasUploadedImage(): bool
    {
        return $this->image_path !== null && ! str_starts_with($this->image_path, 'images/');
    }

    /** Public URL of the image. Bundled design assets live in public/, uploads on the public disk. */
    public function imageUrl(): string
    {
        if ($this->image_path === null) {
            return asset(self::PLACEHOLDER_IMAGE);
        }

        return $this->hasUploadedImage()
            ? Storage::disk('public')->url($this->image_path)
            : asset($this->image_path);
    }

    /** Deletes the uploaded file (never bundled assets) from storage. */
    public function deleteImageFile(): void
    {
        if ($this->hasUploadedImage()) {
            Storage::disk('public')->delete($this->image_path);
        }
    }
}
