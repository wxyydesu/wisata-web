<?php

namespace Database\Seeders;

use App\Models\Penginapan;
use Illuminate\Database\Seeder;

class PenginapanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penginapans = [
            [
                'nama_penginapan' => 'Hotel Bukit Panorama',
                'deskripsi' => 'Hotel mewah dengan pemandangan bukit yang menakjubkan, berlokasi strategis di tengah kota wisata. Dilengkapi dengan fasilitas resort kelas dunia dan layanan prima 24 jam.',
                'fasilitas' => 'Wi-Fi gratis, AC, TV 42 inch, Mini bar, Kolam renang, Spa, Gym, Restoran, Bar, Concierge 24 jam, Parking',
                'foto1' => 'penginapan/bukit1.jpg',
                'foto2' => 'penginapan/bukit2.jpg',
                'foto3' => 'penginapan/bukit3.jpg',
                'foto4' => 'penginapan/bukit4.jpg',
                'foto5' => 'penginapan/bukit5.jpg',
                'harga_per_malam' => 450000,
                'kapasitas' => 2,
                'lokasi' => 'Jl. Raya Bukit No. 123, Kota Wisata',
                'status' => 'tersedia',
            ],
            [
                'nama_penginapan' => 'Resort Pantai Sentosa',
                'deskripsi' => 'Resort tepi pantai dengan akses langsung ke pasir putih, menawarkan pengalaman liburan pantai yang sempurna dengan pemandangan laut biru yang indah.',
                'fasilitas' => 'Kamar dengan balkon, AC, Pemanas air, Beach access, Restoran seafood, Bar pantai, Kursi pantai gratis, Pijat tradisional, Keamanan 24 jam',
                'foto1' => 'penginapan/pantai1.jpg',
                'foto2' => 'penginapan/pantai2.jpg',
                'foto3' => 'penginapan/pantai3.jpg',
                'foto4' => 'penginapan/pantai4.jpg',
                'foto5' => 'penginapan/pantai5.jpg',
                'harga_per_malam' => 550000,
                'kapasitas' => 3,
                'lokasi' => 'Pantai Sentosa, Jl. Pesisir No. 45',
                'status' => 'tersedia',
            ],
            [
                'nama_penginapan' => 'Villa Pedesaan Hijau',
                'deskripsi' => 'Villa tradisional dengan konsep ramah lingkungan di tengah sawah hijau, menawarkan suasana tenang dan asri jauh dari keramaian kota.',
                'fasilitas' => 'Ruang tamu luas, Kamar tidur nyaman, Dapur bersama, Taman pribadi, Kolam air alami, Bebas Wi-Fi, Pemandu lokal, Aktivitas pertanian pagi, Makan siang tradisional',
                'foto1' => 'penginapan/villa1.jpg',
                'foto2' => 'penginapan/villa2.jpg',
                'foto3' => 'penginapan/villa3.jpg',
                'foto4' => 'penginapan/villa4.jpg',
                'foto5' => 'penginapan/villa5.jpg',
                'harga_per_malam' => 350000,
                'kapasitas' => 6,
                'lokasi' => 'Dusun Pedesaan, Jl. Sawah km 5',
                'status' => 'tersedia',
            ],
        ];

        foreach ($penginapans as $penginapan) {
            Penginapan::updateOrCreate(
                ['nama_penginapan' => $penginapan['nama_penginapan']],
                $penginapan
            );
        }
    }
}
