<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriWisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $kategoris = [
            [
                'kategori_wisata' => 'Alam',
                'deskripsi' => 'Wisata alam seperti gunung, pantai, danau, dll.',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_wisata' => 'Budaya',
                'deskripsi' => 'Wisata budaya seperti candi, museum, desa adat, dll.',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_wisata' => 'Kuliner',
                'deskripsi' => 'Wisata kuliner dan makanan khas daerah.',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_wisata' => 'Religi',
                'deskripsi' => 'Wisata religi seperti tempat ibadah dan ziarah.',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kategori_wisata' => 'Buatan',
                'deskripsi' => 'Wisata buatan manusia seperti taman hiburan, waterpark, dll.',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert data ke database
        DB::table('kategori_wisatas')->insert($kategoris);
    }
}
