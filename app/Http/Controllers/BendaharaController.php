<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use App\Models\PaketWisata;
use App\Models\Bank;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use PDF;
use App\Models\Penginapan;
use App\Models\Berita;
use App\Models\ObyekWisata;

class BendaharaController extends Controller
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
        $pendapatanBulanan = Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi, "%Y-%m") as bulan, SUM(total_bayar) as total')
        ->whereIn('status_reservasi', ['dibayar', 'selesai'])
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        $bulanRequest = $request->get('bulan', date('F'));
        $bulanMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4, 'Mei' => 5, 'Juni' => 6,
            'Juli' => 7, 'Agustus' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
        ];
        $bulanAngka = $bulanMap[$bulanRequest] ?? date('n');

        // Filter pendapatanBulanan sesuai bulan jika ada filter
        if ($request->has('bulan') && isset($bulanMap[$bulanRequest])) {
            $pendapatanBulanan = $pendapatanBulanan->filter(function($item) use ($bulanAngka) {
                try {
                    return \Carbon\Carbon::createFromFormat('Y-m', $item->bulan)->month == $bulanAngka;
                } catch (\Exception $e) {
                    return false;
                }
            })->values();
        }

        $now = Carbon::now();
        $bank = Bank::all();

        $greeting = '';

        if ($now->hour >= 5 && $now->hour < 12) {
            $greeting = 'Good Morning';
        } elseif ($now->hour >= 12 && $now->hour < 18) {
            $greeting = 'Good Evening';
        } else {
            $greeting = 'Good Night';
        }

        // Notifikasi: ambil 2 terbaru dari tiap tipe, lalu gabung dan urutkan
        $notifications = collect([]);

        foreach (Reservasi::latest()->limit(2)->get() as $r) {
            $notifications->push([
                'type' => 'reservasi',
                'title' => 'Reservasi Baru',
                'desc' => 'Reservasi oleh ' . ($r->pelanggan->nama_lengkap ?? 'Pelanggan') . ' dibuat.',
                'created_at' => $r->created_at,
            ]);
        }
        foreach (PaketWisata::latest()->limit(2)->get() as $p) {
            $notifications->push([
                'type' => 'paket',
                'title' => 'Paket Baru',
                'desc' => 'Paket "' . $p->nama_paket . '" telah dibuat.',
                'created_at' => $p->created_at,
            ]);
        }
        foreach (Penginapan::latest()->limit(2)->get() as $p) {
            $notifications->push([
                'type' => 'penginapan',
                'title' => 'Penginapan Baru',
                'desc' => 'Penginapan "' . $p->nama_penginapan . '" telah dibuat.',
                'created_at' => $p->created_at,
            ]);
        }
        foreach (Berita::latest()->limit(2)->get() as $b) {
            $notifications->push([
                'type' => 'berita',
                'title' => 'Berita Baru',
                'desc' => 'Berita "' . $b->judul . '" telah diposting.',
                'created_at' => $b->created_at,
            ]);
        }
        foreach (ObyekWisata::latest()->limit(2)->get() as $o) {
            $notifications->push([
                'type' => 'obyek',
                'title' => 'Obyek Wisata Baru',
                'desc' => 'Obyek wisata "' . $o->nama_wisata . '" telah dibuat.',
                'created_at' => $o->created_at,
            ]);
        }
        $notifications = $notifications->sortByDesc('created_at')->take(5)->values();

        return view('be.users.bendahara.index', [
            'title' => 'Bendahara',
            'greeting' => $greeting,
            'totalPendapatan' => $totalPendapatan,
            'totalReservasiDibayar' => $totalReservasiDibayar,
            'totalReservasiMenunggu' => $totalReservasiMenunggu,
            'totalReservasiSelesai' => $totalReservasiSelesai,
            'paketLaris' => $paketLaris,
            'reservasi' => $reservasi,
            'paket' => $paket,
            'bank' => $bank,
            'pendapatanBulanan' => $pendapatanBulanan,
            'notifications' => $notifications,
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