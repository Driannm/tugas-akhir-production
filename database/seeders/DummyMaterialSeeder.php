<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DummyMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            ['material_name' => 'Semen Portland', 'unit_price' => 75000, 'unit' => 'sak', 'image' => 'images/dummy-images/semen.png'],
            ['material_name' => 'Pasir Beton', 'unit_price' => 300000, 'unit' => 'ton', 'image' => 'images/dummy-images/pasir-beton.png'],
            ['material_name' => 'Batu Split', 'unit_price' => 270000, 'unit' => 'kubik', 'image' => 'images/dummy-images/batu-split.png'],
            ['material_name' => 'Besi Beton', 'unit_price' => 10000, 'unit' => 'batang', 'image' => 'images/dummy-images/besi-beton.png'],
            ['material_name' => 'Kayu Balok', 'unit_price' => 85000, 'unit' => 'kubik', 'image' => 'images/dummy-images/kayu-balok.png'],
            ['material_name' => 'Batu Bata Merah', 'unit_price' => 500, 'unit' => 'buah', 'image' => 'images/dummy-images/batu-bata-merah.png'],
            ['material_name' => 'Keramik Lantai 40x40', 'unit_price' => 45000, 'unit' => 'lembar', 'image' => 'images/dummy-images/keramik-lantai.png'],
            ['material_name' => 'Cat Tembok Putih', 'unit_price' => 120000, 'unit' => 'liter', 'image' => 'images/dummy-images/cat-tembok-putih.png'],
            ['material_name' => 'Paku 5 cm', 'unit_price' => 15000, 'unit' => 'kg', 'image' => 'images/dummy-images/paku.jpg'],
            ['material_name' => 'Pipa PVC 1/2 inch', 'unit_price' => 20000, 'unit' => 'meter', 'image' => 'images/dummy-images/pipa-pvc.jpg'],
            
            ['material_name' => 'Triplek 9mm', 'unit_price' => 125000, 'unit' => 'lembar', 'image' => 'images/dummy-images/triplek-9mm.png'],
            ['material_name' => 'Asbes Gelombang 6 Feet', 'unit_price' => 65000, 'unit' => 'lembar', 'image' => 'images/dummy-images/asbes-gelombang.png'],
            ['material_name' => 'Kabel Listrik NYA 2.5mm', 'unit_price' => 9500, 'unit' => 'meter', 'image' => 'images/dummy-images/kabel-nya.png'],
            ['material_name' => 'Besi Hollow 4x4', 'unit_price' => 70000, 'unit' => 'batang', 'image' => 'images/dummy-images/besi-hollow.png'],
            ['material_name' => 'Kawat Bendrat 1kg', 'unit_price' => 22000, 'unit' => 'kg', 'image' => 'images/dummy-images/kawat-bendrat.png'],
            ['material_name' => 'Plafon Gypsum 9mm', 'unit_price' => 58000, 'unit' => 'lembar', 'image' => 'images/dummy-images/plafon-gypsum.png'],
        ];

        foreach ($materials as $item) {
            $sourcePath = public_path($item['image']); // Ambil file dari public
            $fileName = basename($item['image']);
            $storagePath = 'material/images/' . $fileName;

            if (!Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->put($storagePath, file_get_contents($sourcePath));
            }

            Material::create([
                'material_name' => $item['material_name'],
                'unit_price' => $item['unit_price'],
                'stock_quantity' => rand(350, 900),
                'image' => $storagePath, // Simpan path relatif dari storage
                'unit' => $item['unit'],
            ]);
        }
    }
}