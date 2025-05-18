<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\PaketWisata;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use PDF;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $totalPendapatan = Reservasi::whereIn('status_reservasi', ['dibayar', 'selesai'])->sum('total_bayar');
        $totalReservasiDibayar = Reservasi::whereIn('status_reservasi', ['dibayar', 'selesai'])->count();
        $totalReservasiMenunggu = Reservasi::where('status_reservasi', 'pesan')->count();
        $totalReservasiSelesai = Reservasi::where('status_reservasi', 'selesai')->count();

        $paketLaris = Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
            ->whereIn('status_reservasi', ['dibayar', 'selesai'])
            ->groupBy('id_paket')
            ->orderByDesc('jumlah')
            ->with('paketWisata')
            ->first();
        
        $reservasi = Reservasi::with(['pelanggan', 'paketWisata'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        $paket = PaketWisata::all();

        // Ambil pendapatan hanya untuk bulan berjalan
        $now = Carbon::now();
        $pendapatanBulanan = Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi, "%Y-%m") as bulan, SUM(total_bayar) as total')
            ->whereIn('status_reservasi', ['dibayar', 'selesai'])
            ->whereMonth('tgl_reservasi', $now->month)
            ->whereYear('tgl_reservasi', $now->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Jika tidak ada data, buat data kosong agar grafik tetap muncul
        if ($pendapatanBulanan->isEmpty()) {
            $pendapatanBulanan = collect([
                (object)[
                    'bulan' => $now->format('Y-m'),
                    'total' => 0
                ]
            ]);
        }

        $greeting = '';

        if ($now->hour >= 5 && $now->hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($now->hour >= 12 && $now->hour < 18) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        return view('be.users.admin.index', [
            'title' => 'Admin',
            'greeting' => $greeting,
            'totalPendapatan' => $totalPendapatan,
            'totalReservasiDibayar' => $totalReservasiDibayar,
            'totalReservasiMenunggu' => $totalReservasiMenunggu,
            'totalReservasiSelesai' => $totalReservasiSelesai,
            'paketLaris' => $paketLaris,
            'reservasi' => $reservasi,
            'paket' => $paket,
            'pendapatanBulanan' => $pendapatanBulanan,
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