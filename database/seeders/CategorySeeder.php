<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Komputer', 'prefix' => 'KOM', 'description' => 'Komputer Desktop'],
            ['name' => 'Laptop', 'prefix' => 'LAP', 'description' => 'Laptop/Notebook'],
            ['name' => 'Printer', 'prefix' => 'PRT', 'description' => 'Printer dan Scanner'],
            ['name' => 'Monitor', 'prefix' => 'MON', 'description' => 'Monitor/Display'],
            ['name' => 'Proyektor', 'prefix' => 'PRY', 'description' => 'Proyektor/Projector'],
            ['name' => 'Kamera', 'prefix' => 'KAM', 'description' => 'Kamera dan Peralatan Foto'],
            ['name' => 'Furniture', 'prefix' => 'FUR', 'description' => 'Meja, Kursi, Lemari'],
            ['name' => 'Networking', 'prefix' => 'NET', 'description' => 'Router, Switch, Access Point'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['prefix' => $category['prefix']],
                $category
            );
        }
    }
}
