<?php

namespace Database\Seeders;

use App\Models\KategoriBerita;
use Illuminate\Database\Seeder;

class KategoriBeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            ['kategori_berita' => 'Destinasi Wisata'],
            ['kategori_berita' => 'Event & Promo'],
            ['kategori_berita' => 'Tips & Trik Traveling'],
            ['kategori_berita' => 'Berita Umum'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriBerita::updateOrCreate(
                ['kategori_berita' => $kategori['kategori_berita']],
                $kategori
            );
        }
    }
}
