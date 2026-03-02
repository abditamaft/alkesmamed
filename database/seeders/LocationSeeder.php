<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
use App\Models\City;
use App\Models\ShippingRate; // Panggil model ongkirnya

class LocationSeeder extends Seeder
{
    public function run()
    {
        // 1. Bersihkan data lama dengan aman
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ShippingRate::truncate(); // Bersihkan ongkir dulu
        City::truncate();
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Baca File CSV
        $csvFile = fopen(base_path("database/data/data_ongkir.csv"), "r");
        $isFirstLine = true;
        
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if ($isFirstLine) {
                $isFirstLine = false;
                continue;
            }

            $kotaTujuan = $data[1];
            $provinsiTujuan = $data[2];
            $hargaOngkir = $data[3];

            if(!empty($provinsiTujuan) && !empty($kotaTujuan)) {
                
                // 3. Masukkan ke tabel `provinces`
                $province = Province::firstOrCreate([
                    'name' => trim($provinsiTujuan)
                ]);

                // Deteksi tipe (Kota/Kabupaten)
                $type = 'Kota';
                $cityName = trim($kotaTujuan);
                if (strpos($cityName, 'KAB.') !== false || strpos($cityName, 'KAB ') !== false) {
                    $type = 'Kabupaten';
                    $cityName = trim(str_replace(['KAB.', 'KAB '], '', $cityName));
                }

                // 4. Masukkan ke tabel `cities`
                $city = City::firstOrCreate([
                    'province_id' => $province->id,
                    'name' => $cityName,
                ], [
                    'type' => $type
                ]);

                // 5. Masukkan ke tabel `shipping_rates` (Ambil ID kotanya!)
                ShippingRate::updateOrCreate(
                    ['city_id' => $city->id], // Cari berdasarkan ID Kota
                    ['cost' => $hargaOngkir]  // Masukkan/Update harganya
                );
            }
        }

        fclose($csvFile);
        $this->command->info('Data Provinsi, Kota, dan Ongkir (Shipping Rates) sukses di-seed Bos!');
    }
}