<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\PaketWisata;
use App\Models\User;
use App\Models\Bank;
use App\Models\DiskonPaket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReservasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request('status'); // Ambil status dari query string

        // Untuk admin melihat semua reservasi
        if (Auth::check() && in_array(Auth::user()->level, ['admin', 'bendahara', 'owner'])) {
            $query = Reservasi::with(['pelanggan', 'paketWisata'])->latest();
            if ($status) {
                $query->where('status_reservasi', $status);
            }
            $reservasi = $query->paginate(10);
        } 
        // Untuk pelanggan hanya melihat reservasi mereka sendiri
        elseif (Auth::check() && Auth::user()->level === 'pelanggan') {
            $query = Auth::user()->pelanggan->reservasis()->with('paketWisata')->latest();
            if ($status) {
                $query->where('status_reservasi', $status);
            }
            $reservasi = $query->paginate(10);
        }
        // Untuk guest (jika diperlukan)
        else {
            $reservasi = collect()->paginate(10); // Empty paginated collection
        }

        $greeting = $this->getGreeting();
        
        return view('be.reservasi.index', [
            'title' => 'Reservasi Management',
            'reservasi' => $reservasi,
            'greeting' => $greeting
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Untuk pelanggan hanya bisa membuat reservasi untuk diri sendiri
        if (Auth::user()->level === 'pelanggan') {
            $pelanggan = Pelanggan::where('id_user', Auth::id())->get();
        } 
        // Untuk admin bisa memilih pelanggan
        else {
            $pelanggan = Pelanggan::all();
        }

        // Pastikan mengambil field harga_per_pack
        $paket = PaketWisata::with(['reservasiAktif'])
                    ->select('id', 'nama_paket', 'harga_per_pack') // Pastikan field ini ada
                    ->get();
        
        // Ambil diskon aktif untuk setiap paket
        $diskonAktif = [];
        foreach ($paket as $p) {
            $diskon = DiskonPaket::where('paket_id', $p->id)
                ->where('aktif', 1)
                ->where(function($q) {
                    $today = now()->format('Y-m-d');
                    $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $today);
                })
                ->where(function($q) {
                    $today = now()->format('Y-m-d');
                    $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $today);
                })
                ->orderByDesc('persen')
                ->first();
            $diskonAktif[$p->id] = $diskon ? $diskon->persen : 0;
        }

        $bankList = Bank::all();
        $greeting = $this->getGreeting();
        
        return view('be.reservasi.create', [
            'title' => 'Create Reservasi',
            'pelanggan' => $pelanggan,
            'bankList' => $bankList,
            'paket' => $paket,
            'diskonAktif' => $diskonAktif,
            'greeting' => $greeting
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    try {
        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'id_paket' => 'required|exists:paket_wisatas,id',
            'tgl_mulai' => 'required|date|after_or_equal:today',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status_reservasi' => 'required|in:pesan,dibayar,ditolak,selesai',
        ]);

        // Dapatkan harga dari paket wisata
        $paket = PaketWisata::findOrFail($validated['id_paket']);
        $validated['harga'] = $paket->harga_per_pack;

        // Hitung lama reservasi
        $tglMulai = Carbon::parse($validated['tgl_mulai']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);
        $validated['lama_reservasi'] = $tglAkhir->diffInDays($tglMulai) + 1;
        
        // Format tanggal untuk database
        $validated['tgl_mulai'] = $tglMulai->format('Y-m-d');
        $validated['tgl_akhir'] = $tglAkhir->format('Y-m-d');
        // Perbaiki: tgl_reservasi diisi dengan tanggal mulai reservasi
        $validated['tgl_reservasi'] = $validated['tgl_mulai'];

        // Hitung total bayar
        $validated['total_bayar'] = $validated['harga'] * $validated['jumlah_peserta'];
        
        // Ambil diskon otomatis dari tabel diskon jika ada
        $diskon = DiskonPaket::where('paket_id', $validated['id_paket'])
            ->where('aktif', 1)
            ->where(function($q) use ($validated) {
                $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $validated['tgl_mulai']);
            })
            ->where(function($q) use ($validated) {
                $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $validated['tgl_mulai']);
            })
            ->orderByDesc('persen')
            ->first();

        if ($diskon) {
            $validated['diskon'] = $diskon->persen;
        } else {
            $validated['diskon'] = $request->input('diskon', 0);
        }

        // Hitung diskon jika ada
        if ($validated['diskon'] > 0) {
            $validated['nilai_diskon'] = $validated['total_bayar'] * $validated['diskon'] / 100;
            $validated['total_bayar'] -= $validated['nilai_diskon'];
        } else {
            $validated['nilai_diskon'] = 0;
            $validated['diskon'] = 0;
        }

        // Handle file upload
        if ($request->hasFile('file_bukti_tf')) {
            $path = $request->file('file_bukti_tf')->store('bukti_transfer', 'public');
            $validated['file_bukti_tf'] = $path; // Simpan path relatif tanpa 'public/'
        }
        else {
            $validated['file_bukti_tf'] = null; // Atau bisa diisi dengan nilai default
        }
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $validated['id_pelanggan'] != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        Reservasi::create($validated);

        return redirect()->route('reservasi.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Data Reservasi berhasil dibuat',
            'timer' => 1500
        ]);
    } catch (\Exception $e) {
            \Log::error('Delete failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal Membuat Reservasi',
                'timer' => 3000
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservasi $reservasi)
    {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $reservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        $greeting = $this->getGreeting();
        
        return view('be.reservasi.show', [
            'title' => 'Detail Reservasi',
            'reservasi' => $reservasi->load(['pelanggan.user', 'paketWisata']),
            'greeting' => $greeting
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservasi $reservasi)
    {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $reservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        if (Auth::user()->level === 'pelanggan') {
            $pelanggan = Pelanggan::where('id_user', Auth::id())->get();
        } else {
            $pelanggan = Pelanggan::all();
        }

        // Pastikan field harga_per_pack ikut di-select agar data-diskon di view tetap jalan
        $paket = PaketWisata::select('id', 'nama_paket', 'harga_per_pack')->get();

        // Ambil diskon aktif untuk setiap paket (sama seperti create)
        $diskonAktif = [];
        foreach ($paket as $p) {
            $diskon = DiskonPaket::where('paket_id', $p->id)
                ->where('aktif', 1)
                ->where(function($q) {
                    $today = now()->format('Y-m-d');
                    $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $today);
                })
                ->where(function($q) {
                    $today = now()->format('Y-m-d');
                    $q->whereNull('tanggal_akhir')->orWhere('tanggal_akhir', '>=', $today);
                })
                ->orderByDesc('persen')
                ->first();
            $diskonAktif[$p->id] = $diskon ? $diskon->persen : 0;
        }

        $greeting = $this->getGreeting();
        
        return view('be.reservasi.edit', [
            'title' => 'Edit Reservasi',
            'reservasi' => $reservasi->load(['pelanggan', 'paketWisata']),
            'pelanggan' => $pelanggan,
            'paket' => $paket,
            'diskonAktif' => $diskonAktif,
            'greeting' => $greeting
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservasi $reservasi)
    {
    try {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $reservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id',
            'id_paket' => 'required|exists:paket_wisatas,id',
            'tgl_mulai' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_mulai',
            'jumlah_peserta' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'status_reservasi' => 'required|in:pesan,dibayar,ditolak,selesai',
        ]);

        // Dapatkan harga dari paket wisata
        $paket = PaketWisata::findOrFail($validated['id_paket']);
        $validated['harga'] = $paket->harga_per_pack;

        // Hitung lama reservasi
        $tglMulai = Carbon::parse($validated['tgl_mulai']);
        $tglAkhir = Carbon::parse($validated['tgl_akhir']);
        $validated['lama_reservasi'] = $tglAkhir->diffInDays($tglMulai) + 1;

        // Format tanggal untuk database
        $validated['tgl_mulai'] = $tglMulai->format('Y-m-d');
        $validated['tgl_akhir'] = $tglAkhir->format('Y-m-d');

        // Hitung total bayar
        $validated['total_bayar'] = $validated['harga'] * $validated['jumlah_peserta'];
        
        // Hitung diskon jika ada
        if (isset($validated['diskon']) && $validated['diskon'] > 0) {
            $validated['nilai_diskon'] = $validated['total_bayar'] * $validated['diskon'] / 100;
            $validated['total_bayar'] -= $validated['nilai_diskon'];
        } else {
            $validated['nilai_diskon'] = 0;
            $validated['diskon'] = 0;
        }

        // Handle file upload
        if ($request->hasFile('file_bukti_tf')) {
            // Hapus file lama jika ada
            if ($reservasi->file_bukti_tf) {
                Storage::delete('public/' . $reservasi->file_bukti_tf);
            }
            
            $path = $request->file('file_bukti_tf')->store('public/bukti_transfer');
            $validated['file_bukti_tf'] = str_replace('public/', '', $path);
        } else {
            // Pertahankan file yang ada jika tidak diupdate
            $validated['file_bukti_tf'] = $reservasi->file_bukti_tf;
        }

        $reservasi->update($validated);

        return redirect()->route('reservasi.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Data Reservasi berhasil diupdate',
            'timer' => 1500
        ]);
    } catch (\Exception $e) {
            \Log::error('Delete failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal Mengupdate Reservasi',
                'timer' => 3000
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservasi $reservasi)
    {
    try {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $reservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus file bukti transfer jika ada
        if ($reservasi->file_bukti_tf) {
            Storage::delete('public/' . $reservasi->file_bukti_tf);
        }

        $reservasi->delete();
        return redirect()->route('reservasi.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Data Reservasi berhasil dihapus',
            'timer' => 1500
        ]);
    } catch (\Exception $e) {
            \Log::error('Delete failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal Menghapus Reservasi',
                'timer' => 3000
            ]);
        }
    }

    /**
     * API untuk mendapatkan data reservasi pending (digunakan di navbar)
     */
    public function getPendingReservations()
    {
        if (!Auth::check() || Auth::user()->level !== 'pelanggan') {
            return response()->json([]);
        }

        $reservasis = Auth::user()->pelanggan->reservasis()
            ->where('status_reservasi', 'pesan')
            ->with('paketWisata')
            ->get();

        return response()->json($reservasis);
    }

    /**
     * API detail reservasi untuk modal (pastikan route-nya mengarah ke sini)
     */
    public function detail(Reservasi $reservasi)
    {

        $reservasi->load(['pelanggan', 'paketWisata', 'bank']);
        return response()->json($reservasi);
    }

    /**
     * Get greeting based on current time
     */
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