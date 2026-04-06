<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get kategori IDs
        $kategoriDestinasi = KategoriBerita::where('kategori_berita', 'Destinasi Wisata')->first()?->id ?? 1;
        $kategoriEvent = KategoriBerita::where('kategori_berita', 'Event & Promo')->first()?->id ?? 2;
        $kategoriTips = KategoriBerita::where('kategori_berita', 'Tips & Trik Traveling')->first()?->id ?? 3;

        $beritas = [
            [
                'judul' => 'Kecantikan Tersembunyi Danau Kristal di Pegunungan',
                'berita' => 'Danau Kristal adalah sebuah permata tersembunyi yang terletak di tengah pegunungan yang megah. Air danau yang jernih memungkinkan pengunjung melihat hingga kedalaman 20 meter. Destinasi ini sempurna untuk fotografi alam, piknik keluarga, dan camping di alam bebas. Akses menuju danau ini cukup mudah dengan jalur hiking yang nyaman selama 2 jam dari parkir terdekat.',
                'tgl_post' => Carbon::now()->subDays(15),
                'id_kategori_berita' => $kategoriDestinasi,
                'foto' => 'berita/danau1.jpg',
            ],
            [
                'judul' => 'Promo Spesial Liburan Sekolah: Diskon hingga 40% untuk Semua Paket',
                'berita' => 'Merayakan liburan sekolah dengan penawaran istimewa! Kami memberikan diskon hingga 40% untuk semua paket wisata mulai dari tanggal 15 Juni hingga 15 Juli. Paket family mendapat bonus gratis snack dan dokumentasi foto profesional. Pesan sekarang dan dapatkan bonus voucher makan gratis senilai Rp 200.000 di restoran partner kami. Persediaan terbatas, jangan sampai terlewatkan!',
                'tgl_post' => Carbon::now()->subDays(5),
                'id_kategori_berita' => $kategoriEvent,
                'foto' => 'berita/promo1.jpg',
            ],
            [
                'judul' => '5 Tips Packing Efisien untuk Perjalanan Petualangan',
                'berita' => 'Packing yang efisien adalah kunci kenyamanan perjalanan. Pertama, buat list lengkap barang yang akan dibawa. Kedua, pilih pakaian yang versatile dan bisa dipadukan. Ketiga, gunakan compression bag untuk menghemat tempat. Keempat, letakkan barang berat di bagian bawah tas. Kelima, selalu sediakan ruang cadangan untuk oleh-oleh. Dengan tips ini, Anda bisa travel dengan nyaman tanpa membawa beban berlebihan.',
                'tgl_post' => Carbon::now()->subDays(8),
                'id_kategori_berita' => $kategoriTips,
                'foto' => 'berita/tips1.jpg',
            ],
        ];

        foreach ($beritas as $berita) {
            Berita::updateOrCreate(
                ['judul' => $berita['judul']],
                $berita
            );
        }
    }
}
