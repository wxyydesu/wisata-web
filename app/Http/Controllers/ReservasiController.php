<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::with(['pelanggan', 'paketWisata'])->get();
        $greeting = $this->getGreeting();
        
        return view('be.reservasi.index', [
            'title' => 'Reservasi Management',
            'reservasis' => $reservasis,
            'greeting' => $greeting
        ]);
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $pakets = PaketWisata::all();
        $greeting = $this->getGreeting();
        
        return view('be.reservasi.create', [
            'title' => 'Create Reservasi',
            'pelanggans' => $pelanggans,
            'pakets' => $pakets,
            'greeting' => $greeting
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id',
            'id_paket' => 'required|exists:paket_wisata,id',
            'reservasi_wisata' => 'required|date',
            'harga' => 'required|integer',
            'jumlah_peserta' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric',
            'nilai_diskon' => 'nullable|numeric',
            'file_buku_if' => 'nullable|string',
            'status_reservasi_wisata' => 'required|in:pesan,dibayar,selesai'
        ]);

        $validated['total_bayar'] = $validated['harga'] * $validated['jumlah_peserta'];
        if ($validated['nilai_diskon']) {
            $validated['total_bayar'] -= $validated['nilai_diskon'];
        }

        Reservasi::create($validated);

        return redirect()->route('reservasi.index')->with('success', 'Reservasi created successfully.');
    }

    public function show(Reservasi $reservasi)
    {
        $greeting = $this->getGreeting();
        
        return view('be.reservasi.show', [
            'title' => 'Detail Reservasi',
            'reservasi' => $reservasi,
            'greeting' => $greeting
        ]);
    }

    public function edit(Reservasi $reservasi)
    {
        $pelanggans = Pelanggan::all();
        $pakets = PaketWisata::all();
        $greeting = $this->getGreeting();
        
        return view('be.reservasi.edit', [
            'title' => 'Edit Reservasi',
            'reservasi' => $reservasi,
            'pelanggans' => $pelanggans,
            'pakets' => $pakets,
            'greeting' => $greeting
        ]);
    }

    public function update(Request $request, Reservasi $reservasi)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id',
            'id_paket' => 'required|exists:paket_wisata,id',
            'reservasi_wisata' => 'required|date',
            'harga' => 'required|integer',
            'jumlah_peserta' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric',
            'nilai_diskon' => 'nullable|numeric',
            'file_buku_if' => 'nullable|string',
            'status_reservasi_wisata' => 'required|in:pesan,dibayar,selesai'
        ]);

        $validated['total_bayar'] = $validated['harga'] * $validated['jumlah_peserta'];
        if ($validated['nilai_diskon']) {
            $validated['total_bayar'] -= $validated['nilai_diskon'];
        }

        $reservasi->update($validated);

        return redirect()->route('reservasi.index')->with('success', 'Reservasi updated successfully.');
    }

    public function destroy(Reservasi $reservasi)
    {
        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('success', 'Reservasi deleted successfully.');
    }

    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        
        if ($hour < 12) {
            return 'Selamat Pagi';
        } elseif ($hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }
}