<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'nama_asset' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'location_id' => Location::factory(),
            'serial_number' => fake()->unique()->bothify('SN-####-????'),
            'merk' => fake()->randomElement(['Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Samsung']),
            'model' => fake()->bothify('Model-????'),
            'kondisi' => fake()->randomElement(['baik', 'cukup', 'rusak_ringan', 'rusak_berat']),
            'status' => fake()->randomElement(['available', 'borrowed', 'maintenance', 'broken', 'lost']),
            'lokasi' => fake()->address(),
            'tanggal_pembelian' => fake()->dateTimeBetween('-3 years', 'now'),
            'harga' => fake()->numberBetween(500000, 50000000),
            'supplier' => fake()->company(),
            'deskripsi' => fake()->sentence(),
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'available']);
    }

    public function borrowed(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'borrowed']);
    }
}
