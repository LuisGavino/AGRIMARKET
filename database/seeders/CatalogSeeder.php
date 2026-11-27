<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Listing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Loads the sample items from the original static admin pages so the new database-backed
 * pages are not empty. Safe to run again: it only creates what is missing.
 * Run with: php artisan db:seed --class=CatalogSeeder
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'produce' => [
                'Fruits & Vegetables' => [['Watermelon Seeds', 'watermelon-seeds'], ['Sunflower', 'sunflower'], ['Banana', 'banana'], ['Apple', 'apple'], ['Grapes', 'grapes'], ['Hemp Seeds', 'hemp-seeds']],
            ],
            'livestock' => [
                'Livestock' => [['Chicken', 'chicken'], ['Ducks', 'ducks'], ['Cows', 'cows'], ['Pigs', 'pigs'], ['Goat', 'goat'], ['Rabbit', 'rabbit']],
            ],
            'service' => [
                'Farm Services' => [['Tractor & Farm Equipment Rental', 'tractor-rental'], ['Crop Planting Services', 'crop-planting'], ['Harvesting Services', 'harvesting'], ['Veterinary Services', 'veterinary'], ['Agricultural Transport', 'transport'], ['Pest Control', 'pest-control']],
            ],
        ];

        foreach ($catalog as $type => $categories) {
            foreach ($categories as $categoryName => $items) {
                $category = Category::firstOrCreate(
                    ['type' => $type, 'name' => $categoryName],
                    ['slug' => Str::slug($categoryName)],
                );

                foreach ($items as [$name, $image]) {
                    Listing::firstOrCreate(
                        ['type' => $type, 'name' => $name],
                        [
                            'category_id' => $category->id,
                            'price' => 100,
                            'quantity' => 20,
                            'image_path' => 'images/products/'.$image.'.jpg',
                            'is_featured' => in_array($name, ['Apple', 'Banana', 'Chicken', 'Cows'], true),
                            'status' => 'active',
                        ],
                    );
                }
            }
        }
    }
}
