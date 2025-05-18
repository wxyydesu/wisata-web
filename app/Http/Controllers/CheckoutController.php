<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Bank;
use App\Models\DiskonPaket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CheckoutMail;
use App\Models\PaketWisata;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $users = Auth::user();
        $paket = PaketWisata::with('diskonAktif')->findOrFail($id);
        
        // Calculate total price based on request parameters
        $request = request();
        $totalBayar = $paket->harga_per_pack * $request->jumlah_peserta;
        $diskon = 0;
        $nilaiDiskon = 0;

        if ($paket->diskonAktif) {
            $diskon = $paket->diskonAktif->persen;
            $nilaiDiskon = $totalBayar * $diskon / 100;
        }

        $banks = Bank::all();

        return view('fe.checkout.index', [
            'title' => 'Checkout',
            'paket' => $paket,
            'user' => $users,
            'request' => $request,
            'totalBayar' => $totalBayar,
            'diskon' => $diskon,
            'nilaiDiskon' => $nilaiDiskon,
            'banks' => $banks
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    // public function checkout(Request $request)
    // {
    //     if (!Auth::check()) {
    //         return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
    //     }

    //     $request->validate([
    //         'id_paket' => 'required|exists:paket_wisatas,id',
    //         'tgl_mulai' => 'required|date|after_or_equal:today',
    //         'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
    //         'jumlah_peserta' => 'required|integer|min:1'
    //     ]);

    //     $paket = PaketWisata::findOrFail($request->id_paket);
        
    //     // Calculate total price
    //     $totalBayar = $paket->harga_per_pack * $request->jumlah_peserta;
    //     $diskon = 0;
    //     $nilaiDiskon = 0;

    //     // Check for active discount
    //     $diskonAktif = DiskonPaket::where('paket_id', $paket->id)
    //         ->where('aktif', 1)
    //         ->where(function($q) use ($request) {
    //             $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $request->tgl_mulai);
    //         })
    //         ->where(function($q) use ($request) {
    //             $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $request->tgl_mulai);
    //         })
    //         ->orderByDesc('persen')
    //         ->first();

    //     if ($diskonAktif) {
    //         $diskon = $diskonAktif->persen;
    //         $nilaiDiskon = $totalBayar * $diskon / 100;
    //     }

    //     $users = Auth::user();
    //     $banks = Bank::all();

    //     return view('fe.checkout.index', [
    //         'title' => 'Checkout',
    //         'user' => $users,
    //         'paket' => $paket,
    //         'totalBayar' => $totalBayar,
    //         'diskon' => $diskon,
    //         'nilaiDiskon' => $nilaiDiskon,
    //         'banks' => $banks
    //     ]);
    // }

//    public function success($id)
//     {
//         $reservasi = Reservasi::with(['paketWisata', 'bank'])
//                     ->where('id_pelanggan', Auth::id())
//                     ->findOrFail($id);

//         return view('fe.checkout-success', [
//             'title' => 'Reservasi Berhasil',
//             'reservasi' => $reservasi
//         ]);
//     }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_paket' => 'required|exists:paket_wisatas,id',
            'tgl_mulai' => 'required|date|after_or_equal:today',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'bank_id' => 'required|exists:banks,id',
            'file_bukti_tf' => 'required|file|mimes:jpg,png,pdf|max:2048'
        ]);

        try {

            $pelanggan = Pelanggan::where('id_user', Auth::id())->first();
            if (!$pelanggan) {
            $user = Auth::user();
                $pelanggan = Pelanggan::create([
                    'id_user' => $user->id,
                    'nama_lengkap' => $user->name,
                    'no_hp' => $user->no_hp ?? '',
                    'alamat' => $user->alamat ?? '',
                    'foto' => null
                ]);
            }
            // Process payment and create reservation
            $paket = PaketWisata::findOrFail($request->id_paket);
            
            // Calculate total price
            $totalBayar = $paket->harga_per_pack * $request->jumlah_peserta;
            $diskon = 0;
            $nilaiDiskon = 0;

            // Check for active discount
            if ($paket->diskonAktif) {
                $diskon = $paket->diskonAktif->persen;
                $nilaiDiskon = $totalBayar * $diskon / 100;
            }

            // Upload payment proof
            $filePath = $request->file('file_bukti_tf')->store('payment_proofs', 'public');

            // Create reservation
            $reservasi = Reservasi::create([
                'id_pelanggan' => $pelanggan->id,
                'id_paket' => $request->id_paket,
                'tgl_mulai' => $request->tgl_mulai,
                'tgl_akhir' => $request->tgl_akhir,
                'jumlah_peserta' => $request->jumlah_peserta,
                'id_bank' => $request->bank_id,
                'file_bukti_tf' => $filePath,
                'total_bayar' => $totalBayar - $nilaiDiskon,
                'diskon' => $diskon,
                'nilai_diskon' => $nilaiDiskon,
                'harga' => $paket->harga_per_pack,
                'status_reservasi' => 'pesan',
                'lama_reservasi' => $paket->durasi,
                // Perbaiki: tgl_reservasi diisi dengan tanggal mulai reservasi
                'tgl_reservasi' => $request->tgl_mulai
            ]);

            // Redirect to success page
            return redirect()->route('pesanan.index')->with('success', 'Reservasi berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
