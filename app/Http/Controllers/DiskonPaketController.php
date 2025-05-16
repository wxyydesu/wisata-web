<?php
namespace App\Http\Controllers;

use App\Models\DiskonPaket;
use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiskonPaketController extends Controller
{
    public function index()
    {
        // Nonaktifkan diskon yang sudah lewat tanggal akhir
        DiskonPaket::whereNotNull('tanggal_akhir')
            ->where('tanggal_akhir', '<', Carbon::today())
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        $greeting = $this->getGreeting();
        $paket = PaketWisata::all();

        // Ambil hanya diskon yang aktif dan valid tanggalnya
        $today = Carbon::today()->format('Y-m-d');
        $diskon = DiskonPaket::with('paket')
            ->where('aktif', 1)
            ->where(function($q) use ($today) {
                $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $today);
            })
            ->where(function($q) use ($today) {
                $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $today);
            })
            ->get()
            ->keyBy('paket_id');

        return view('be.diskon.index', compact('paket', 'diskon', 'greeting'));
    }

    // Tambahkan method updateAll untuk batch update diskon
    public function updateAll(Request $request)
    {
        // Nonaktifkan diskon yang sudah lewat tanggal akhir
        DiskonPaket::whereNotNull('tanggal_akhir')
            ->where('tanggal_akhir', '<', Carbon::today())
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        foreach ($request->paket_id as $id) {
            $tanggalMulai = $request->tanggal_mulai[$id] ?? null;
            $tanggalAkhir = $request->tanggal_akhir[$id] ?? null;

            // Otomatis aktif jika tanggal mulai dan akhir terisi
            $aktif = (!empty($tanggalMulai) && !empty($tanggalAkhir)) ? 1 : (in_array($id, $request->aktif ?? []) ? 1 : 0);

            DiskonPaket::updateOrCreate(
                ['paket_id' => $id],
                [
                    'aktif' => $aktif,
                    'persen' => $request->persen[$id] ?? 0,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_akhir' => $tanggalAkhir,
                ]
            );
        }
        return back()->with('success', 'Diskon berhasil diperbarui!');
    }

    private function getGreeting()
    {
        $hour = Carbon::now()->hour;
        
        if ($hour < 12) {
            return 'Good Morning';
        } elseif ($hour < 18) {
            return 'Good Afternoon';
        } else {
            return 'Good Evening';
        }
    }
}