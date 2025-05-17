<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PaketWisata;
use App\Models\Penginapan;
use App\Models\KategoriWisata;
use App\Models\Reservasi;
use App\Models\Berita;
use App\Models\ObyekWisata;
use App\Models\Bank;
use App\Models\DiskonPaket;
use App\Models\Ulasan;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get data for slider - latest 3 items from each category
        $sliderPackages = PaketWisata::latest()->take(3)->get();
        $sliderAccommodations = Penginapan::latest()->take(3)->get();
        $sliderAttractions = ObyekWisata::latest()->take(3)->get();
        
        // Merge all slider items and shuffle for variety
        $sliderItems = collect()
            ->merge($sliderPackages->map(function($item) { 
                $item->type = 'package'; 
                return $item; 
            }))
            ->merge($sliderAccommodations->map(function($item) { 
                $item->type = 'accommodation'; 
                return $item; 
            }))
            ->merge($sliderAttractions->map(function($item) { 
                $item->type = 'attraction'; 
                return $item; 
            }))
            ->shuffle();

        // Existing data for other sections
        $paketWisata = PaketWisata::latest()->paginate(8);
        $penginapan = Penginapan::latest()->take(8)->get();
        $kategoriWisata = KategoriWisata::where('aktif', 1)
            ->with(['obyekWisata' => function($q) {
                $q->latest()->take(8);
            }])->get();
        $topPaket = PaketWisata::orderBy('created_at', 'desc')->take(5)->get();

        return view('fe.home.index', [
            'title' => 'Home',
            'user' => $user,
            'sliderItems' => $sliderItems,
            'paketWisata' => $paketWisata,
            'penginapan' => $penginapan,
            'kategoriWisata' => $kategoriWisata,
            'topPaket' => $topPaket
        ]);
    }

    public function paketWisata()
    {
        $user = Auth::user();
        $paketWisata = PaketWisata::paginate(9);
        return view('fe.paket_wisata.index', [
            'title' => 'Paket Wisata',
            'user' => $user,
            'paketWisata' => $paketWisata
        ]);
    }

    public function detailPaket($id)
    {
        $user = Auth::user();
        $paket = PaketWisata::findOrFail($id);
        $related = PaketWisata::where('id', '!=', $id)
                    ->take(4) // Hapus where('aktif', 1)
                    ->get();
                    
        return view('fe.detail_paket.index', [
            'title' => $paket->nama_paket,
            'paket' => $paket,
            'user' => $user,
            'related' => $related
        ]);
    }

    public function penginapan()
    {
        $penginapan = Penginapan::paginate(9);
        return view('fe.penginapan.index', [
            'title' => 'Penginapan',
            'penginapan' => $penginapan
        ]);
    }

    public function detailPenginapan($id)
    {
        $penginapan = Penginapan::with(['ulasan.user'])->findOrFail($id); // Ensure reviews and user data are loaded
        $related = Penginapan::where('id', '!=', $id)
                    ->take(4)
                    ->get(); // Fetch related accommodations

        return view('fe.penginapan.detail', [
            'title' => $penginapan->nama_penginapan,
            'penginapan' => $penginapan,
            'related' => $related
        ]);
    }

        public function berita()
    {
        $user = Auth::user();
        $berita = Berita::with('kategori')
                    ->orderBy('tgl_post', 'desc')
                    ->paginate(9);
                    
        return view('fe.berita.index', [
            'title' => 'Berita Wisata',
            'user' => $user,
            'berita' => $berita
        ]);
    }

    public function detailBerita($id)
    {
        $berita = Berita::with('kategori')->findOrFail($id);
        $related = Berita::where('id', '!=', $id)
                    ->where('id_kategori_berita', $berita->id_kategori_berita)
                    ->orderBy('tgl_post', 'desc')
                    ->take(4)
                    ->get();
                    
        return view('fe.detail_berita.index', [
            'title' => $berita->judul,
            'berita' => $berita,
            'related' => $related
        ]);
    }

    public function objekWisata()
    {
        $kategoriWisata = KategoriWisata::where('aktif', 1)->with('obyekWisata')->get();
        return view('fe.objek-wisata', [
            'title' => 'Objek Wisata',
            'kategoriWisata' => $kategoriWisata
        ]);
    }

    public function detailObjekWisata($id)
    {
        $obyekWisata = ObyekWisata::with('kategoriWisata')->findOrFail($id);
        return view('fe.detail-obyek-wisata', [
            'title' => $obyekWisata->nama_obyek_wisata,
            'obyekWisata' => $obyekWisata
        ]);
    }

    public function checkoutSuccess($id)
    {
        $reservasi = Reservasi::with('paketWisata')->findOrFail($id);
        return view('fe.checkout-success', [
            'title' => 'Checkout Success',
            'reservasi' => $reservasi
        ]);
    }
}