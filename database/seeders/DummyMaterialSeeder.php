<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class DummyMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['material_name' => 'Semen Portland', 'unit_price' => 75000, 'unit' => 'sak'],
            ['material_name' => 'Pasir Beton', 'unit_price' => 300000, 'unit' => 'ton'],
            ['material_name' => 'Batu Split', 'unit_price' => 270000, 'unit' => 'kubik'],
            ['material_name' => 'Besi Beton', 'unit_price' => 10000, 'unit' => 'batang'],
            ['material_name' => 'Kayu Balok', 'unit_price' => 85000, 'unit' => 'kubik'],
            ['material_name' => 'Batu Bata Merah', 'unit_price' => 500, 'unit' => 'buah'],
            ['material_name' => 'Keramik Lantai 40x40', 'unit_price' => 45000, 'unit' => 'lembar'],
            ['material_name' => 'Cat Tembok Putih', 'unit_price' => 120000, 'unit' => 'liter'],
            ['material_name' => 'Paku 5 cm', 'unit_price' => 15000, 'unit' => 'kg'],
            ['material_name' => 'Pipa PVC 1/2 inch', 'unit_price' => 20000, 'unit' => 'meter'],
        ];

        foreach ($materials as $item) {
            Material::create([
                'material_name' => $item['material_name'],
                'unit_price' => $item['unit_price'],
                'stock_quantity' => rand(150, 850), 
                'image' => null,
                'unit' => $item['unit'],  
            ]);
        }
    }
}