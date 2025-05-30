<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Reservasi;
use App\Models\PaketWisata;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\Penginapan;
use App\Models\Berita;
use App\Models\ObyekWisata;

class OwnerController extends Controller
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

        return view('be.users.owner.index', [
            'title' => 'Owner',
            'greeting' => $greeting,
            'totalPendapatan' => $totalPendapatan,
            'totalReservasiDibayar' => $totalReservasiDibayar,
            'totalReservasiMenunggu' => $totalReservasiMenunggu,
            'totalReservasiSelesai' => $totalReservasiSelesai,
            'paketLaris' => $paketLaris,
            'reservasi' => $reservasi,
            'paket' => $paket,
            'pendapatanBulanan' => $pendapatanBulanan,
            'notifications' => $notifications,
        ]);
    }

    public function confirm(Reservasi $reservasi)
    {
        try {
            $reservasi->update(['status_reservasi' => 'dibayar']);
            return redirect()->back()->with('success', 'Reservasi berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengkonfirmasi reservasi');
        }
    }

    public function reject(Reservasi $reservasi)
    {
        try {
            $reservasi->update(['status_reservasi' => 'ditolak']);
            return redirect()->back()->with('success', 'Reservasi berhasil ditolak!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak reservasi');
        }
    }

    public function showReservasi(Reservasi $reservasi)
    {
        return response()->json([
            'pelanggan' => $reservasi->pelanggan,
            'paket_wisata' => $reservasi->paketWisata,
            'tgl_reservasi_wisata' => $reservasi->tgl_reservasi_wisata,
            'jumlah_peserta' => $reservasi->jumlah_peserta,
            'total_bayar' => $reservasi->total_bayar,
            'status_reservasi' => $reservasi->status_reservasi,
            'metode_pembayaran' => $reservasi->metode_pembayaran,
            'created_at' => $reservasi->created_at
        ]);
    }

    public function showPaket(PaketWisata $paket)
    {
        return response()->json([
            'nama_paket' => $paket->nama_paket,
            'harga_per_pack' => $paket->harga_per_pack,
            'minimal_orang' => $paket->minimal_orang,
            'deskripsi' => $paket->deskripsi,
            'fasilitas' => $paket->fasilitas,
            'foto1' => $paket->foto1,
            'foto2' => $paket->foto2,
            'foto3' => $paket->foto3
        ]);
    }

    public function exportPdf()
    {
        $data = [
            'totalPendapatan' => Reservasi::whereIn('status_reservasi', ['dibayar', 'selesai'])->sum('total_bayar'),
            'paketLaris' => Reservasi::selectRaw('id_paket, COUNT(*) as jumlah')
                ->whereIn('status_reservasi', ['dibayar', 'selesai'])
                ->groupBy('id_paket')
                ->orderByDesc('jumlah')
                ->with('paketWisata')  // Changed from 'paket'
                ->first(),
            'statistikPeserta' => Reservasi::selectRaw('id_paket, SUM(jumlah_peserta) as total_peserta')
                ->whereIn('status_reservasi', ['dibayar', 'selesai'])
                ->groupBy('id_paket')
                ->with('paketWisata')  // Changed from 'paket'
                ->get(),
            'grafikPendapatan' => Reservasi::selectRaw('DATE_FORMAT(tgl_reservasi, "%Y-%m") as bulan, SUM(total_bayar) as total')
                ->whereIn('status_reservasi', ['dibayar', 'selesai'])
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get(),
            'reservasi' => Reservasi::with(['pelanggan', 'paketWisata'])  // Changed from 'paket'
                ->whereIn('status_reservasi', ['dibayar', 'selesai'])
                ->orderByDesc('created_at')
                ->get(),
            'tanggalLaporan' => Carbon::now()->translatedFormat('d F Y'),
            'waktuCetak' => Carbon::now()->translatedFormat('d F Y H:i'),
            'user' => auth()->user()
        ];

        $pdf = Pdf::loadView('be.users.owner.pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOption([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'helvetica'
            ]);

        return $pdf->download('laporan-keuangan-'.Carbon::now()->format('Ymd-His').'.pdf');
    }
    public function exportExcel()
    {
        $reservasi = Reservasi::with(['pelanggan', 'paketWisata'])
            ->whereIn('status_reservasi', ['dibayar', 'selesai'])
            ->orderByDesc('created_at')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Pelanggan');
        $sheet->setCellValue('C1', 'Paket Wisata');
        $sheet->setCellValue('D1', 'Tgl Reservasi');
        $sheet->setCellValue('E1', 'Harga');
        $sheet->setCellValue('F1', 'Jumlah Peserta');
        $sheet->setCellValue('G1', 'Diskon');
        $sheet->setCellValue('H1', 'Total Bayar');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Dibuat');

        $row = 2;
        foreach ($reservasi as $i => $r) {
            $sheet->setCellValue('A'.$row, $i+1);
            $sheet->setCellValue('B'.$row, $r->pelanggan->nama_lengkap ?? '-');
            $sheet->setCellValue('C'.$row, $r->paketWisata->nama_paket ?? '-');
            $sheet->setCellValue('D'.$row, $r->tgl_reservasi);
            $sheet->setCellValue('E'.$row, $r->harga);
            $sheet->setCellValue('F'.$row, $r->jumlah_peserta);
            if ($r->diskon) {
                $sheet->setCellValue('G'.$row, $r->diskon . '% (Rp' . number_format($r->nilai_diskon,0,',','.') . ')');
            } else {
                $sheet->setCellValue('G'.$row, '-');
            }
            $sheet->setCellValue('H'.$row, $r->total_bayar);
            $status = '-';
            if ($r->status_reservasi == 'pesan') $status = 'Pesan';
            elseif ($r->status_reservasi == 'dibayar') $status = 'Dibayar';
            elseif ($r->status_reservasi == 'selesai') $status = 'Selesai';
            $sheet->setCellValue('I'.$row, $status);
            $sheet->setCellValue('J'.$row, \Carbon\Carbon::parse($r->created_at)->format('d-m-Y'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-keuangan.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
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