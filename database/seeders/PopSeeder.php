<?php
namespace Database\Seeders;

use App\Models\Pop;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PopSeeder extends Seeder
{
    public function run()
    {
        // Menggunakan Faker dengan lokal Indonesia
        $faker = Faker::create('id_ID');

        // Daftar Kabupaten/Kota di Jambi untuk variasi data
        $daftarKabupaten = [
            'Kota Jambi', 'Bungo', 'Tebo', 'Merangin', 'Sarolangun', 
            'Batanghari', 'Muaro Jambi', 'Tanjung Jabung Barat', 
            'Tanjung Jabung Timur', 'Kerinci', 'Kota Sungai Penuh'
        ];
        $daftarBangunan = ['Shelter', 'ODC', 'Mini POP'];
        $daftarMerk = ['Vertiv', 'Huawei', 'ZTE', 'Delta'];

        // Kita buat 20 data POP secara acak
        for ($i = 1; $i <= 20; $i++) {
            
            $kabupaten = $faker->randomElement($daftarKabupaten);
            $bangunan = $faker->randomElement($daftarBangunan);
            $kodePop = 'POP_' . strtoupper($faker->bothify('?#??###'));

            // 1. Create POP
            $pop = Pop::create([
                'kode_pop'       => $kodePop,
                'nama_pop'       => $kodePop . ' ' . strtoupper($kabupaten) . ' ' . strtoupper($bangunan),
                'provinsi'       => 'Jambi',
                'kota_kabupaten' => $kabupaten,
                'tipe_pop'       => 'pop-' . $faker->randomElement(['a', 'b', 'sb']),
                'jenis_bangunan' => $bangunan,
                'lokasi'         => 'Kec. ' . $faker->citySuffix . ', ' . $kabupaten,
            ]);

            // 2. Setiap POP kita beri 1 sampai 2 Rectifier secara acak
            $jumlahRectifier = rand(1, 2);
            for ($j = 1; $j <= $jumlahRectifier; $j++) {
                
                $kapasitasSlot = rand(4, 6);
                
                $rectifier = $pop->rectifiers()->create([
                    'nama_alias'     => 'Rectifier ' . $j,
                    'deskripsi'      => 'Backup daya utama gedung ' . $bangunan,
                    'merk'           => $faker->randomElement($daftarMerk),
                    'type'           => 'NetSure ' . $faker->randomNumber(4, true),
                    'sn_rectifier'   => strtoupper($faker->bothify('???-########')),
                    'kapasitas_slot' => $kapasitasSlot,
                ]);

                // 3. Setiap Rectifier kita beri beberapa Modul (maksimal sejumlah slot)
                $jumlahModul = rand(1, $kapasitasSlot - 1); // Disisakan slot kosong agar realistis
                for ($k = 1; $k <= $jumlahModul; $k++) {
                    $rectifier->modules()->create([
                        'sn_modul'         => strtoupper($faker->bothify('MOD-####??')),
                        'kapasitas_ampere' => $faker->randomElement(['30 A DC / 10 A AC', '40 A DC / 13 A AC', '50 A DC / 15 A AC']),
                    ]);
                }

                // 4. Setiap Rectifier kita beri beberapa MCB Output
                $jumlahOutput = rand(2, 4);
                for ($l = 1; $l <= $jumlahOutput; $l++) {
                    $rectifier->outputs()->create([
                        'merk_mcb'      => $faker->randomElement(['Schneider', 'ABB', 'Chint']),
                        'kapasitas_mcb' => $faker->randomElement([10, 16, 20, 32]),
                        'peruntukan'    => 'Arah Server ' . strtoupper($faker->lexify('?')),
                    ]);
                }
            }
        }
    }
}
