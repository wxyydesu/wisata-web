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
use App\Models\Ulasan;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $paketWisata = PaketWisata::paginate(9);
        $penginapan = Penginapan::latest()->take(6)->get();
        $kategoriWisata = KategoriWisata::where('aktif', 1)->get();
        
        return view('fe.home.index', [
            'title' => 'Home',
            'user' => $user,
            'paketWisata' => $paketWisata,
            'penginapan' => $penginapan,
            'kategoriWisata' => $kategoriWisata
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
        $penginapan = Penginapan::with('ulasan.user')->findOrFail($id);
        $related = Penginapan::where('id', '!=', $id)->take(4)->get();
                    
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

    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        $request->validate([
            'id_paket' => 'required|exists:paket_wisatas,id',
            'tgl_reservasi' => 'required|date|after_or_equal:today',
            'jumlah_peserta' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:500'
        ]);

        $paket = PaketWisata::findOrFail($request->id_paket);
        $totalBayar = $paket->harga_per_pack * $request->jumlah_peserta;

        $reservasi = Reservasi::create([
            'id_pelanggan' => Auth::id(),
            'id_paket' => $paket->id,
            'tgl_reservasi' => $request->tgl_reservasi,
            'harga' => $paket->harga_per_pack,
            'jumlah_peserta' => $request->jumlah_peserta,
            'total_bayar' => $totalBayar,
            'status_reservasi' => 'pesan',
            'catatan' => $request->catatan
        ]);

        return redirect()->route('checkout.success', $reservasi->id);
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