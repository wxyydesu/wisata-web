<?php

namespace Database\Seeders;

use App\Models\ObyekWisata;
use App\Models\KategoriWisata;
use Illuminate\Database\Seeder;

class ObyekWisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create categories
        $kategoriAlam = KategoriWisata::firstOrCreate(
            ['kategori_wisata' => 'Alam'],
            ['deskripsi' => 'Destinasi wisata alam dan petualangan outdoor', 'aktif' => 1]
        )->id;

        $kategoriKuliner = KategoriWisata::firstOrCreate(
            ['kategori_wisata' => 'Kuliner'],
            ['deskripsi' => 'Destinasi wisata kuliner dan gastronomi', 'aktif' => 1]
        )->id;

        $kategoriSejarah = KategoriWisata::firstOrCreate(
            ['kategori_wisata' => 'Sejarah & Budaya'],
            ['deskripsi' => 'Situs bersejarah dan warisan budaya', 'aktif' => 1]
        )->id;

        $obyekWisatas = [
            [
                'nama_wisata' => 'Air Terjun Siluman',
                'deskripsi_wisata' => 'Air terjun yang indah dengan ketinggian 85 meter di tengah hutan hijau. Spot fotografi yang sempurna dengan kolam renang alami di bagian bawah. Akses mudah dengan trek hiking yang nyaman selama 30 menit dari area parkir.',
                'id_kategori_wisata' => $kategoriAlam,
                'fasilitas' => 'Jalur hiking, Area parkir, Warung makan, Toilet, Tempat penjualan oleh-oleh, Pemandu lokal, Perlengkapan piknik',
                'foto1' => 'wisata/airTerjun1.jpg',
                'foto2' => 'wisata/airTerjun2.jpg',
                'foto3' => 'wisata/airTerjun3.jpg',
                'foto4' => 'wisata/airTerjun4.jpg',
                'foto5' => 'wisata/airTerjun5.jpg',
            ],
            [
                'nama_wisata' => 'Pasar Tradisional Kuno',
                'deskripsi_wisata' => 'Pasar tradisional berusia 200 tahun dengan arsitektur klasik yang masih terawat. Menjual berbagai kerajinan tangan lokal, produk pertanian segar, dan makanan tradisional autentik. Pengalaman berbelanja yang unik dengan atmosfer budaya yang kental.',
                'id_kategori_wisata' => $kategoriKuliner,
                'fasilitas' => 'Area parkir, Restoran tradisional, Toilet bersih, Toko oleh-oleh, Pemandu wisata bersejarah, WiFi, Aula rapat',
                'foto1' => 'wisata/pasar1.jpg',
                'foto2' => 'wisata/pasar2.jpg',
                'foto3' => 'wisata/pasar3.jpg',
                'foto4' => 'wisata/pasar4.jpg',
                'foto5' => 'wisata/pasar5.jpg',
            ],
            [
                'nama_wisata' => 'Candi Purba Peninggalan Majapahit',
                'deskripsi_wisata' => 'Situs arkeologi candi berusia lebih dari 700 tahun dari era Kerajaan Majapahit. Bangunan bersejarah dengan patung dan ukiran relief yang menakjubkan. Museum situs yang menampilkan artefak-artefak berharga dari periode klasik.',
                'id_kategori_wisata' => $kategoriSejarah,
                'fasilitas' => 'Area parkir luas, Museum, Pemandu wisata berpengetahuan, Toilet, Ruang istirahat, Cafeteria, Toko souvenir, Area edukasi',
                'foto1' => 'wisata/candi1.jpg',
                'foto2' => 'wisata/candi2.jpg',
                'foto3' => 'wisata/candi3.jpg',
                'foto4' => 'wisata/candi4.jpg',
                'foto5' => 'wisata/candi5.jpg',
            ],
        ];

        foreach ($obyekWisatas as $objek) {
            ObyekWisata::updateOrCreate(
                ['nama_wisata' => $objek['nama_wisata']],
                $objek
            );
        }
    }
}
