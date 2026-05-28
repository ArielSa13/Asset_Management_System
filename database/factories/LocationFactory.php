<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'building' => 'Gedung ' . fake()->randomLetter(),
            'floor' => 'Lt. ' . fake()->numberBetween(1, 5),
            'room' => 'Ruang ' . fake()->numberBetween(101, 505),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
