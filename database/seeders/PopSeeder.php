<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pop;
use Faker\Factory as Faker;

class PopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan Faker dengan lokalisasi Indonesia
        $faker = Faker::create('id_ID');

        // Daftar Kota/Kabupaten untuk variasi data
        $kotaKabupaten = [
            'Kota Jambi', 
            'Muaro Jambi', 
            'Tanjung Jabung Barat', 
            'Tanjung Jabung Timur', 
            'Batanghari', 
            'Bungo', 
            'Tebo', 
            'Sarolangun', 
            'Merangin', 
            'Kerinci', 
            'Kota Sungai Penuh'
        ];

        $typePops = ['POP-A', 'POP-B', 'POP-SB', 'POP-D'];
        
        // Diubah menjadi buildingList
        $buildingList = ['Shelter Permanent', 'Shelter Outdoor', 'Gedung PLN', 'Ruang Server Khusus', 'GI Shelter'];

        for ($i = 0; $i < 50; $i++) {
            Pop::create([
                'provinsi'       => 'Jambi',
                'kota_kabupaten' => $faker->randomElement($kotaKabupaten),
                'kode_pop'       => 'POP_' . strtoupper($faker->lexify('????')) . $faker->numerify('#####'),
                'nama_pop'       => strtoupper($faker->words(2, true)) . ' ' . $faker->numberBetween(1, 5),
                'jenis_bangunan'       => $faker->randomElement($buildingList), // Dimasukkan ke kolom building
                'tipe_pop'       => $faker->randomElement($typePops),
            ]);
        }
    }
}