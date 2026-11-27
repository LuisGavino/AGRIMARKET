<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['type', 'name', 'slug'])]
class Category extends Model
{
    use HasFactory;

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }
}
