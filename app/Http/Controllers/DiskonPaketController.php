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
        try {
            // Validasi input
            $request->validate([
                'paket_id' => 'nullable|array',
                'paket_id.*' => 'integer|exists:paket_wisatas,id',
                'persen' => 'nullable|array',
                'persen.*' => 'numeric|min:0|max:100',
                'tanggal_mulai' => 'nullable|array',
                'tanggal_akhir' => 'nullable|array',
                'aktif' => 'nullable|array',
            ]);

            // Nonaktifkan diskon yang sudah lewat tanggal akhir
            DiskonPaket::whereNotNull('tanggal_akhir')
                ->where('tanggal_akhir', '<', Carbon::today())
                ->where('aktif', 1)
                ->update(['aktif' => 0]);

            $paketIds = $request->paket_id ?? [];
            $aktivList = $request->aktif ?? [];

            foreach ($paketIds as $id) {
                $persen = (float) ($request->persen[$id] ?? 0);
                $tanggalMulai = $request->tanggal_mulai[$id] ?? null;
                $tanggalAkhir = $request->tanggal_akhir[$id] ?? null;

                // Tentukan status aktif dari checkbox
                $aktif = in_array($id, $aktivList) ? 1 : 0;

                // Jika tanggal tidak lengkap, non-aktifkan
                if (empty($tanggalMulai) || empty($tanggalAkhir)) {
                    $aktif = 0;
                }

                DiskonPaket::updateOrCreate(
                    ['paket_id' => $id],
                    [
                        'persen' => $persen,
                        'tanggal_mulai' => $tanggalMulai,
                        'tanggal_akhir' => $tanggalAkhir,
                        'aktif' => $aktif,
                    ]
                );
            }

            return back()->with('success', 'Diskon berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan diskon: ' . $e->getMessage());
        }
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