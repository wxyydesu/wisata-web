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
        $diskon = DiskonPaket::with('paket')->get()->keyBy('paket_id');
        return view('be.diskon.index', compact('paket', 'diskon', 'greeting'));
    }

    public function update(Request $request)
    {
        // Nonaktifkan diskon yang sudah lewat tanggal akhir
        DiskonPaket::whereNotNull('tanggal_akhir')
            ->where('tanggal_akhir', '<', Carbon::today())
            ->where('aktif', 1)
            ->update(['aktif' => 0]);

        foreach ($request->paket_id as $id) {
            DiskonPaket::updateOrCreate(
                ['paket_id' => $id],
                [
                    'aktif' => in_array($id, $request->aktif ?? []) ? 1 : 0,
                    'persen' => $request->persen[$id] ?? 0,
                    'tanggal_mulai' => $request->tanggal_mulai[$id] ?? null,
                    'tanggal_akhir' => $request->tanggal_akhir[$id] ?? null,
                ]
            );
        }
        return back()->with('success', 'Diskon berhasil diperbarui!');
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
            DiskonPaket::updateOrCreate(
                ['paket_id' => $id],
                [
                    'aktif' => in_array($id, $request->aktif ?? []) ? 1 : 0,
                    'persen' => $request->persen[$id] ?? 0,
                    'tanggal_mulai' => $request->tanggal_mulai[$id] ?? null,
                    'tanggal_akhir' => $request->tanggal_akhir[$id] ?? null,
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