<?php

namespace Database\Factories;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Listing> */
class ListingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => Listing::TYPE_PRODUCE,
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 10, 500),
            'quantity' => fake()->numberBetween(1, 100),
            'unit' => 'kg',
            'status' => 'active',
            'is_featured' => false,
        ];
    }

    public function livestock(): static
    {
        return $this->state(['type' => Listing::TYPE_LIVESTOCK, 'unit' => null, 'weight' => 12.5, 'purpose' => 'Meat']);
    }

    public function service(): static
    {
        return $this->state(['type' => Listing::TYPE_SERVICE, 'unit' => null, 'service_area' => 'Surigao City', 'availability' => 'Weekdays']);
    }
}
