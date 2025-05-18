<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{PaketWisata, Penginapan, KategoriWisata, Reservasi, Berita, ObyekWisata, Bank, DiskonPaket, Ulasan, KategoriBerita};

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Optimized slider data fetching
        $sliderItems = $this->getSliderItems();
        
        // Cached data for better performance
        $paketWisata = cache()->remember('home_paket_wisata', now()->addHours(1), function() {
            return PaketWisata::latest()->paginate(8);
        });
        
        $penginapan = cache()->remember('home_penginapan', now()->addHours(1), function() {
            return Penginapan::latest()->take(8)->get();
        });
        
        $kategoriWisata = cache()->remember('home_kategori_wisata', now()->addHours(1), function() {
            return KategoriWisata::with(['obyekWisata' => function($q) {
                $q->latest()->take(8);
            }])->where('aktif', 1)->get();
        });
        
        $topPaket = cache()->remember('top_paket', now()->addHours(1), function() {
            return PaketWisata::orderBy('created_at', 'desc')->take(5)->get();
        });
        
        $topPenginapan = cache()->remember('top_penginapan', now()->addHours(1), function() {
            return Penginapan::orderBy('created_at', 'desc')->take(3)->get();
        });

        // Ambil 3 berita terbaru untuk related berita
        $relatedBerita = Berita::with('kategoriBerita')
            ->latest('tgl_post')
            ->take(3)
            ->get();

        return view('fe.home.index', compact(
            'user', 'sliderItems', 'paketWisata', 'penginapan', 
            'kategoriWisata', 'topPaket', 'topPenginapan', 'relatedBerita'
        ))->with('title', 'Home');
    }

    protected function getSliderItems()
    {
        return cache()->remember('slider_items', now()->addHours(1), function() {
            $packages = PaketWisata::latest()->take(3)->get()->map(function($item) {
                $item->type = 'package';
                $item->route = route('paket.detail', $item->id);
                return $item;
            });
            
            $accommodations = Penginapan::latest()->take(3)->get()->map(function($item) {
                $item->type = 'accommodation';
                $item->route = route('detail.penginapan', $item->id);
                return $item;
            });
            
            $attractions = ObyekWisata::latest()->take(3)->get()->map(function($item) {
                $item->type = 'attraction';
                $item->route = route('detail.obyekwisata', $item->id);
                return $item;
            });
            
            return $packages->merge($accommodations)->merge($attractions)->shuffle();
        });
    }

    public function paketWisata()
    {
        $paketWisata = PaketWisata::with('diskonAktif')->paginate(9);
        return view('fe.paket_wisata.index', [
            'title' => 'Paket Wisata',
            'user' => Auth::user(),
            'paketWisata' => $paketWisata
        ]);
    }

    public function detailPaket($id)
    {
        $paket = PaketWisata::with(['diskonAktif'])->findOrFail($id);
        
        return view('fe.detail_paket.index', [
            'title' => $paket->nama_paket,
            'paket' => $paket,
            'user' => Auth::user(),
            'related' => $this->getRelatedPackages($paket)
        ]);
    }

    protected function getRelatedPackages($currentPackage)
    {
        return PaketWisata::where('id', '!=', $currentPackage->id)
            ->take(4)
            ->get();
    }

    public function penginapan()
    {
        $user = Auth::user();
        $penginapan = Penginapan::paginate(9);
        
        return view('fe.penginapan.index', [
            'title' => 'Penginapan',
            'user' => $user,
            'penginapan' => $penginapan
        ]);
    }

    public function detailPenginapan($id)
    {
        $user = Auth::user();
        $penginapan = Penginapan::findOrFail($id);

        return view('fe.penginapan.detail', [
            'title' => $penginapan->nama_penginapan,
            'user' => $user,
            'penginapan' => $penginapan,
            'related' => Penginapan::where('id', '!=', $id)
                // ->where('id_kategori_penginapan', $penginapan->id_kategori_penginapan) // Hapus baris ini jika kolom tidak ada
                ->take(4)
                ->get()
        ]);
    }

    public function berita()
    {
        return view('fe.berita.index', [
            'title' => 'Berita Wisata',
            'user' => Auth::user(),
            'berita' => Berita::with('kategoriBerita')
                ->latest('tgl_post')
                ->paginate(9)
        ]);
    }

    public function detailBerita($id)
    {
        $user = Auth::user();
    $berita = Berita::with('kategoriBerita')->findOrFail($id);
        
        // Get popular news - ordered by most recent first
        $popularNews = Berita::with('kategoriBerita')
            ->latest('tgl_post')
            ->take(4)
            ->get();
        
        // Get news categories with count
        $newsCategories = KategoriBerita::withCount('beritas')->get();
        
        return view('fe.berita.detail', [
            'title' => $berita->judul,
            'berita' => $berita,
            'popularNews' => $popularNews,
            'newsCategories' => $newsCategories,
            'user' => $user,
            'related' => Berita::where('id', '!=', $id)
                ->where('id_kategori_berita', $berita->id_kategori_berita)
                ->latest('tgl_post')
                ->take(4)
                ->get()
        ]);
    }

    public function objekWisata()
    {
        return view('fe.objek-wisata', [
            'title' => 'Objek Wisata',
            'kategoriWisata' => KategoriWisata::with(['obyekWisata' => function($query) {
                $query->where('aktif', 1);
            }])->where('aktif', 1)->get()
        ]);
    }

    public function detailObjekWisata($id)
    {
        $obyekWisata = ObyekWisata::with(['kategoriWisata', 'fasilitas'])->findOrFail($id);
        
        return view('fe.detail-obyek-wisata', [
            'title' => $obyekWisata->nama_obyek_wisata,
            'obyekWisata' => $obyekWisata,
            'nearby' => ObyekWisata::where('id_kategori_wisata', $obyekWisata->id_kategori_wisata)
                ->where('id', '!=', $id)
                ->take(4)
                ->get()
        ]);
    }

    public function checkoutSuccess($id)
    {
        $reservasi = Reservasi::with(['paketWisata', 'pelanggan'])
            ->where('id', $id)
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();

        return view('fe.checkout-success', [
            'title' => 'Checkout Success',
            'reservasi' => $reservasi
        ]);
    }
}