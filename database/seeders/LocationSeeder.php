<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Kantor Pusat', 'building' => 'Gedung A', 'floor' => 'Lt. 1', 'room' => 'Ruang Admin'],
            ['name' => 'Kantor Pusat', 'building' => 'Gedung A', 'floor' => 'Lt. 2', 'room' => 'Ruang IT'],
            ['name' => 'Kantor Pusat', 'building' => 'Gedung A', 'floor' => 'Lt. 3', 'room' => 'Ruang Meeting'],
            ['name' => 'Kantor Cabang', 'building' => 'Gedung B', 'floor' => 'Lt. 1', 'room' => 'Ruang Operasional'],
            ['name' => 'Gudang', 'building' => 'Gedung C', 'floor' => 'Lt. 1', 'room' => 'Gudang Utama'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
