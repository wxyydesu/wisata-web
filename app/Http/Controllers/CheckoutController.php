<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\PaketWisata;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $paket = PaketWisata::with('diskonAktif')->findOrFail($id);
        return view('fe.checkout.index', compact('paket'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'tanggal_berangkat' => 'required|date|after:today',
            'jumlah_peserta' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'paket_id' => 'required|exists:paket_wisata,id',
        ]);

        DB::beginTransaction();
        
        try {
            $paket = PaketWisata::findOrFail($request->paket_id);
            $diskon = $paket->diskonAktif;
            
            $hargaNormal = $paket->harga_per_pack;
            $hargaDiskon = $diskon && $diskon->persen > 0 
                ? $hargaNormal - ($hargaNormal * $diskon->persen / 100)
                : $hargaNormal;
            
            $totalHarga = $hargaDiskon * $request->jumlah_peserta;
            
            // Simpan ke tabel reservasi, bukan pemesanan
            $reservasi = Reservasi::create([
                'kode_booking' => 'WB-' . time(),
                'id_paket' => $paket->id,
                'nama_pemesan' => $request->nama,
                'email' => $request->email,
                'no_telepon' => $request->telepon,
                'tgl_mulai' => $request->tanggal_berangkat,
                'jumlah_peserta' => $request->jumlah_peserta,
                'total_bayar' => $totalHarga,
                'status_reservasi' => 'pesan',
                'catatan' => $request->catatan,
                // Tambahkan field lain jika diperlukan sesuai struktur tabel reservasis
            ]);
            
            DB::commit();
            
            return redirect()->route('checkout.success')->with([
                'kode_booking' => $reservasi->kode_booking,
                'total_harga' => $totalHarga
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
