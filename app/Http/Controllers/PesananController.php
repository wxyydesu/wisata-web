<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\PaketWisata;
use App\Models\Bank;
use App\Models\Pelanggan;
use Barryvdh\DomPDF\Facade\Pdf;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $reservasis = Reservasi::with(['paketWisata', 'bank'])
                      ->where('id_pelanggan', Auth::user()->pelanggan->id)
                      ->latest()
                      ->paginate(10);

        return view('fe.pesanan.index', [
            'title' => 'Pesanan Saya',
            'reservasis' => $reservasis,
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show($id)
    {
        $user = Auth::user();
        $reservasi = Reservasi::with(['paketWisata', 'bank'])
                    ->where('id_pelanggan', Auth::user()->pelanggan->id)
                    ->findOrFail($id);

        return view('fe.pesanan.detail', [
            'title' => 'Detail Pesanan',
            'reservasi' => $reservasi,
            'user' => $user,
        ]);
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

    /**
     * Display the invoice for printing.
     */
    public function print($id)
    {
        $reservasi = Reservasi::with(['paketWisata', 'bank', 'pelanggan'])
                    ->where('id_pelanggan', Auth::user()->pelanggan->id)
                    ->findOrFail($id);

        $pdf = Pdf::loadView('fe.pesanan.invoice', [
            'reservasi' => $reservasi,
            'title' => 'Invoice #' . $reservasi->id
        ]);

        return $pdf->stream('invoice-' . $reservasi->id . '.pdf');
    }

    /**
     * Show the transfer proof in modal.
     */
    public function showTransferProof($id)
    {
        $reservasi = Reservasi::where('id_pelanggan', Auth::user()->pelanggan->id)
                    ->findOrFail($id);

        if (!$reservasi->file_bukti_tf) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $reservasi->file_bukti_tf));
    }
}