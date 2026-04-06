<?php

namespace Database\Seeders;

use App\Models\PaketWisata;
use Illuminate\Database\Seeder;

class PaketWisataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pakets = [
            [
                'nama_paket' => 'Petualangan Gunung Bromo 3 Hari',
                'deskripsi' => 'Paket wisata petualangan ke Gunung Bromo dengan pemandu berpengalaman, mencakup sunrise tour, savana, dan berbagai destinasi wisata alam spektakuler.',
                'fasilitas' => 'Akomodasi bintang 3, Pemandu wisata profesional, Transportasi lengkap, Makanan 3x sehari, Asuransi perjalanan, Perlengkapan outdoor',
                'harga_per_pack' => 1500000,
                'kapasitas_orang' => 15,
                'foto1' => 'paket/bromo1.jpg',
                'foto2' => 'paket/bromo2.jpg',
                'foto3' => 'paket/bromo3.jpg',
                'foto4' => 'paket/bromo4.jpg',
                'foto5' => 'paket/bromo5.jpg',
            ],
            [
                'nama_paket' => 'Pantai Tropika & Snorkeling 2 Hari',
                'deskripsi' => 'Nikmati keindahan pantai tropis dengan aktivitas snorkeling di karang dan penyelaman untuk melihat kehidupan laut yang eksotis.',
                'fasilitas' => 'Akomodasi tepi pantai, Peralatan snorkeling lengkap, Instruktur bersertifikat, Paket makan, Transportasi, Dokumentasi foto bawah air',
                'harga_per_pack' => 1200000,
                'kapasitas_orang' => 20,
                'foto1' => 'paket/pantai1.jpg',
                'foto2' => 'paket/pantai2.jpg',
                'foto3' => 'paket/pantai3.jpg',
                'foto4' => 'paket/pantai4.jpg',
                'foto5' => 'paket/pantai5.jpg',
            ],
            [
                'nama_paket' => 'Eksplorasi Budaya Tradisional 4 Hari',
                'deskripsi' => 'Pelajari kekayaan budaya lokal melalui kunjungan ke desa tradisional, workshop kerajinan tangan, dan pertunjukan budaya autentik.',
                'fasilitas' => 'Akomodasi homestay tradisional, Workshop dengan pengrajin lokal, Pertunjukan budaya, Makanan tradisional, Pemandu budaya, Sertifikat peserta',
                'harga_per_pack' => 950000,
                'kapasitas_orang' => 12,
                'foto1' => 'paket/budaya1.jpg',
                'foto2' => 'paket/budaya2.jpg',
                'foto3' => 'paket/budaya3.jpg',
                'foto4' => 'paket/budaya4.jpg',
                'foto5' => 'paket/budaya5.jpg',
            ],
        ];

        foreach ($pakets as $paket) {
            PaketWisata::updateOrCreate(
                ['nama_paket' => $paket['nama_paket']],
                $paket
            );
        }
    }
}
