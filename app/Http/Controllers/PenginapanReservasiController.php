<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\PenginapanReservasi;
use App\Models\Pelanggan;
use App\Models\Penginapan;
use App\Models\User;
use App\Models\Bank;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PenginapanReservasiController extends Controller
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
        $status = request('status');

        // Untuk admin melihat semua reservasi
        if (Auth::check() && in_array(Auth::user()->level, ['admin', 'bendahara', 'owner'])) {
            $query = PenginapanReservasi::with(['pelanggan', 'penginapan'])->latest();
            if ($status) {
                $query->where('status_reservasi', $status);
            }
            $reservasi = $query->paginate(10);
        } 
        // Untuk pelanggan hanya melihat reservasi mereka sendiri
        elseif (Auth::check() && Auth::user()->level === 'pelanggan') {
            $query = Auth::user()->pelanggan->penginapanReservasis()->with('penginapan')->latest();
            if ($status) {
                $query->where('status_reservasi', $status);
            }
            $reservasi = $query->paginate(10);
        }
        // Untuk guest
        else {
            $reservasi = collect()->paginate(10);
        }

        $greeting = $this->getGreeting();
        
        return view('be.penginapan_reservasi.index', [
            'title' => 'Penginapan Reservasi Management',
            'reservasi' => $reservasi,
            'greeting' => $greeting
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->level === 'pelanggan') {
            $pelanggan = Pelanggan::where('id_user', Auth::id())->get();
        } else {
            $pelanggan = Pelanggan::all();
        }

        $penginapan = Penginapan::select('id', 'nama_penginapan', 'harga_per_malam', 'kapasitas')->get();
        $bankList = Bank::all();
        $greeting = $this->getGreeting();
        
        return view('be.penginapan_reservasi.create', [
            'title' => 'Create Penginapan Reservasi',
            'pelanggan' => $pelanggan,
            'bankList' => $bankList,
            'penginapan' => $penginapan,
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
                'id_penginapan' => 'required|exists:penginapans,id',
                'tgl_check_in' => 'required|date|after_or_equal:today',
                'tgl_check_out' => 'required|date|after:tgl_check_in',
                'jumlah_kamar' => 'required|integer|min:1',
                'diskon' => 'nullable|numeric|min:0|max:100',
                'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status_reservasi' => 'required|in:menunggu konfirmasi,booking,batal,selesai',
            ]);

            // Dapatkan data penginapan
            $penginapan = Penginapan::findOrFail($validated['id_penginapan']);
            $validated['harga_per_malam'] = $penginapan->harga_per_malam;

            // Hitung lama malam
            $tglCheckIn = Carbon::parse($validated['tgl_check_in']);
            $tglCheckOut = Carbon::parse($validated['tgl_check_out']);
            $validated['lama_malam'] = $tglCheckOut->diffInDays($tglCheckIn);
            
            $validated['tgl_check_in'] = $tglCheckIn->format('Y-m-d');
            $validated['tgl_check_out'] = $tglCheckOut->format('Y-m-d');
            $validated['tgl_reservasi'] = $validated['tgl_check_in'];

            // Hitung total bayar
            $validated['total_bayar'] = $validated['harga_per_malam'] * $validated['lama_malam'] * $validated['jumlah_kamar'];
            
            // Hitung diskon jika ada
            $validated['diskon'] = $request->input('diskon', 0);
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
                $validated['file_bukti_tf'] = $path;
            } else {
                $validated['file_bukti_tf'] = null;
            }

            // Authorization check
            if (Auth::user()->level === 'pelanggan' && $validated['id_pelanggan'] != Auth::user()->pelanggan->id) {
                abort(403, 'Unauthorized action.');
            }

            PenginapanReservasi::create($validated);

            return redirect()->route('penginapan_reservasi.index')->with('swal', [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Data Reservasi Penginapan berhasil dibuat',
                'timer' => 1500
            ]);
        } catch (\Exception $e) {
            Log::error('Penginapan Reservasi creation failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal Membuat Reservasi Penginapan',
                'timer' => 3000
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PenginapanReservasi $penginapanReservasi)
    {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        $greeting = $this->getGreeting();
        
        return view('be.penginapan_reservasi.show', [
            'title' => 'Detail Penginapan Reservasi',
            'reservasi' => $penginapanReservasi->load(['pelanggan.user', 'penginapan']),
            'greeting' => $greeting
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenginapanReservasi $penginapanReservasi)
    {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        if (Auth::user()->level === 'pelanggan') {
            $pelanggan = Pelanggan::where('id_user', Auth::id())->get();
        } else {
            $pelanggan = Pelanggan::all();
        }

        $penginapan = Penginapan::select('id', 'nama_penginapan', 'harga_per_malam', 'kapasitas')->get();
        $greeting = $this->getGreeting();
        
        return view('be.penginapan_reservasi.edit', [
            'title' => 'Edit Penginapan Reservasi',
            'reservasi' => $penginapanReservasi->load(['pelanggan', 'penginapan']),
            'pelanggan' => $pelanggan,
            'penginapan' => $penginapan,
            'greeting' => $greeting
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenginapanReservasi $penginapanReservasi)
    {
        try {
            // Authorization check
            if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
                abort(403, 'Unauthorized action.');
            }

            $validated = $request->validate([
                'id_pelanggan' => 'required|exists:pelanggans,id',
                'id_penginapan' => 'required|exists:penginapans,id',
                'tgl_check_in' => 'required|date',
                'tgl_check_out' => 'required|date|after:tgl_check_in',
                'jumlah_kamar' => 'required|integer|min:1',
                'diskon' => 'nullable|numeric|min:0|max:100',
                'file_bukti_tf' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
                'status_reservasi' => 'required|in:menunggu konfirmasi,booking,batal,selesai',
            ]);

            // Dapatkan data penginapan
            $penginapan = Penginapan::findOrFail($validated['id_penginapan']);
            $validated['harga_per_malam'] = $penginapan->harga_per_malam;

            // Hitung lama malam
            $tglCheckIn = Carbon::parse($validated['tgl_check_in']);
            $tglCheckOut = Carbon::parse($validated['tgl_check_out']);
            $validated['lama_malam'] = $tglCheckOut->diffInDays($tglCheckIn);
            
            $validated['tgl_check_in'] = $tglCheckIn->format('Y-m-d');
            $validated['tgl_check_out'] = $tglCheckOut->format('Y-m-d');

            // Hitung total bayar
            $validated['total_bayar'] = $validated['harga_per_malam'] * $validated['lama_malam'] * $validated['jumlah_kamar'];
            
            // Hitung diskon jika ada
            $validated['diskon'] = $request->input('diskon', 0);
            if ($validated['diskon'] > 0) {
                $validated['nilai_diskon'] = $validated['total_bayar'] * $validated['diskon'] / 100;
                $validated['total_bayar'] -= $validated['nilai_diskon'];
            } else {
                $validated['nilai_diskon'] = 0;
                $validated['diskon'] = 0;
            }

            // Handle file upload
            if ($request->hasFile('file_bukti_tf')) {
                if ($penginapanReservasi->file_bukti_tf) {
                    Storage::disk('public')->delete($penginapanReservasi->file_bukti_tf);
                }
                $path = $request->file('file_bukti_tf')->store('bukti_transfer', 'public');
                $validated['file_bukti_tf'] = $path;
            }

            $penginapanReservasi->update($validated);

            return redirect()->route('penginapan_reservasi.index')->with('swal', [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Data Reservasi Penginapan berhasil diubah',
                'timer' => 1500
            ]);
        } catch (\Exception $e) {
            Log::error('Penginapan Reservasi update failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal Mengubah Reservasi Penginapan',
                'timer' => 3000
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenginapanReservasi $penginapanReservasi)
    {
        try {
            // Authorization check
            if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
                abort(403, 'Unauthorized action.');
            }

            if ($penginapanReservasi->file_bukti_tf) {
                Storage::disk('public')->delete($penginapanReservasi->file_bukti_tf);
            }

            $penginapanReservasi->delete();

            return redirect()->route('penginapan_reservasi.index')->with('swal', [
                'icon' => 'success',
                'title' => 'Berhasil',
                'text' => 'Data Reservasi Penginapan berhasil dihapus',
                'timer' => 1500
            ]);
        } catch (\Exception $e) {
            Log::error('Penginapan Reservasi deletion failed: '.$e->getMessage());
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal Menghapus Reservasi Penginapan',
                'timer' => 3000
            ]);
        }
    }

    /**
     * Show payment page with Midtrans Snap
     */
    public function payment(PenginapanReservasi $penginapanReservasi)
    {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow payment for unpaid reservations
        if (!in_array($penginapanReservasi->status_reservasi, ['menunggu konfirmasi', 'booking'])) {
            abort(403, 'Reservasi tidak dapat dibayar');
        }

        $penginapanReservasi->load(['pelanggan', 'penginapan']);
        $banks = Bank::all();
        
        return view('be.penginapan_reservasi.payment', [
            'title' => 'Payment Penginapan Reservasi',
            'reservasi' => $penginapanReservasi,
            'banks' => $banks,
            'midtrans_client_key' => config('midtrans.client_key')
        ]);
    }

    /**
     * Process Midtrans payment via AJAX
     */
    public function processPayment(Request $request, PenginapanReservasi $penginapanReservasi)
    {
        try {
            $validated = $request->validate([
                'payment_type' => 'required|string',
            ]);

            // Create Midtrans Service and get snap token
            $midtransService = new MidtransService();
            $snapToken = $midtransService->createToken($penginapanReservasi, $penginapanReservasi->pelanggan, null, 'penginapan');

            $penginapanReservasi->midtrans_order_id = 'PEN-' . $penginapanReservasi->id . '-' . time();
            $penginapanReservasi->save();

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $penginapanReservasi->midtrans_order_id
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans token generation failed: '.$e->getMessage());
            return response()->json(['error' => 'Gagal membuat snap token'], 500);
        }
    }

    /**
     * Handle Midtrans Callback
     */
    public function callback(Request $request)
    {
        try {
            $midtransService = new MidtransService();

            // Parse notification
            $notif = $midtransService->parseNotification(json_encode($request->all()));

            $status = $notif->transaction_status ?? null;
            $transactionId = $notif->transaction_id ?? null;

            if (!$transactionId) {
                return response()->json(['message' => 'Invalid notification'], 400);
            }

            // Extract order ID to get reservasi
            // Order ID format: PEN-{id}-{timestamp}
            $orderIdParts = explode('-', $notif->order_id);
            if (count($orderIdParts) >= 2 && $orderIdParts[0] === 'PEN') {
                $reservasiId = $orderIdParts[1];
                
                // Update reservasi with Midtrans data
                $penginapanReservasi = PenginapanReservasi::findOrFail($reservasiId);
                $penginapanReservasi->midtrans_transaction_id = $transactionId;
                $penginapanReservasi->midtrans_status = $status;
                $penginapanReservasi->midtrans_payment_type = $notif->payment_type ?? null;
                
                // Map Midtrans status to application status
                $penginapanReservasi->status_reservasi = $midtransService->mapStatus($status);
                $penginapanReservasi->save();

                Log::info('Penginapan Reservasi payment callback processed', [
                    'reservasi_id' => $reservasiId,
                    'status' => $status,
                    'transaction_id' => $transactionId
                ]);
            }

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            Log::error('Payment callback error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getGreeting()
    {
        $hour = now()->hour;
        if ($hour < 12) {
            return 'Pagi';
        } elseif ($hour < 15) {
            return 'Siang';
        } elseif ($hour < 18) {
            return 'Sore';
        } else {
            return 'Malam';
        }
    }

    /**
     * Customer Checkout - Show checkout page
     */
    public function customerCheckout(Request $request, Penginapan $penginapan)
    {
        try {
            $checkIn = $request->query('check_in') ?: $request->session()->get('booking_data.check_in');
            $checkOut = $request->query('check_out') ?: $request->session()->get('booking_data.check_out');
            $jumlahKamar = $request->query('jumlah_kamar') ?: $request->session()->get('booking_data.jumlah_kamar') ?: 1;

            if (!$checkIn || !$checkOut) {
                return redirect()->route('penginapan.detail', $penginapan->id)
                    ->with('error', 'Data pemesanan tidak lengkap. Silakan isi form pemesanan kembali.');
            }

            $checkInDate = Carbon::parse($checkIn);
            $checkOutDate = Carbon::parse($checkOut);
            
            if ($checkOutDate->lessThanOrEqualTo($checkInDate)) {
                return redirect()->route('penginapan.detail', $penginapan->id)
                    ->with('error', 'Tanggal check-out harus setelah tanggal check-in.');
            }

            $lamaMalam = $checkOutDate->diffInDays($checkInDate);
            $hargaPerMalam = $penginapan->harga_per_malam;
            $total = $hargaPerMalam * $lamaMalam * $jumlahKamar;

            $bookingData = [
                'check_in' => $checkInDate->format('d/m/Y'),
                'check_out' => $checkOutDate->format('d/m/Y'),
                'jumlah_kamar' => $jumlahKamar,
                'lama_malam' => $lamaMalam,
                'total' => $total,
            ];

            return view('fe.penginapan.checkout', compact('penginapan', 'bookingData'));
        } catch (\Exception $e) {
            Log::error('Customer checkout error: '.$e->getMessage());
            return redirect()->route('penginapan.detail', $penginapan->id)
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Customer Store - Save accommodation reservation
     */
    public function customerStore(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'penginapan_id' => 'required|exists:penginapans,id',
                'tgl_check_in' => 'required|date|after_or_equal:today',
                'tgl_check_out' => 'required|date|after:tgl_check_in',
                'jumlah_kamar' => 'required|integer|min:1',
            ]);

            // Get current user's pelanggan
            $user = Auth::user();
            
            // Auto-create pelanggan profile if it doesn't exist
            if (!$user->pelanggan) {
                Pelanggan::create([
                    'id_user' => $user->id,
                    'nama_lengkap' => $user->name,
                    'no_hp' => $user->no_hp,
                    'alamat' => $user->alamat,
                    'foto' => $user->foto,
                ]);
                
                // Refresh user to get the newly created pelanggan
                $user = $user->fresh();
            }

            $penginapan = Penginapan::findOrFail($request->penginapan_id);
            
            // Validate penginapan price
            if (!$penginapan->harga_per_malam || $penginapan->harga_per_malam <= 0) {
                throw new \Exception('Harga penginapan tidak valid: ' . ($penginapan->harga_per_malam ?? 'null'));
            }
            
            // Calculate duration and total
            $checkIn = Carbon::parse($request->tgl_check_in);
            $checkOut = Carbon::parse($request->tgl_check_out);
            $lamaMalam = $checkOut->diffInDays($checkIn);
            
            // Validate duration
            if ($lamaMalam <= 0) {
                throw new \Exception('Durasi menginap harus minimal 1 malam. Tanggal check-in: ' . $checkIn->format('Y-m-d') . ', Check-out: ' . $checkOut->format('Y-m-d'));
            }
            
            $hargaPerMalam = $penginapan->harga_per_malam;
            $jumlahKamar = $request->jumlah_kamar;
            $subtotal = $hargaPerMalam * $lamaMalam * $jumlahKamar;
            
            // Validate subtotal
            if ($subtotal <= 0) {
                throw new \Exception('Total harga harus lebih besar dari 0. Harga/malam: ' . $hargaPerMalam . ', Malam: ' . $lamaMalam . ', Kamar: ' . $jumlahKamar);
            }
            
            // Create reservation
            $reservasi = PenginapanReservasi::create([
                'id_pelanggan' => $user->pelanggan->id,
                'id_penginapan' => $request->penginapan_id,
                'tgl_reservasi' => Carbon::now(),
                'tgl_check_in' => $request->tgl_check_in,
                'tgl_check_out' => $request->tgl_check_out,
                'lama_malam' => $lamaMalam,
                'harga_per_malam' => $hargaPerMalam,
                'jumlah_kamar' => $jumlahKamar,
                'diskon' => 0,
                'nilai_diskon' => 0,
                'total_bayar' => $subtotal,
                'status_reservasi' => 'menunggu konfirmasi',
            ]);

            // Store booking data in session for checkout view
            $request->session()->put('booking_data', [
                'check_in' => $checkIn->format('d/m/Y'),
                'check_out' => $checkOut->format('d/m/Y'),
                'jumlah_kamar' => $jumlahKamar,
                'lama_malam' => $lamaMalam,
                'total' => $subtotal,
                'reservasi_id' => $reservasi->id,
            ]);

            // Prepare checkout URL
            $checkoutUrl = route('checkout.penginapan', [
                'penginapan' => $penginapan->id,
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d'),
                'jumlah_kamar' => $jumlahKamar,
            ]);

            // Return JSON for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Reservasi berhasil dibuat!',
                    'redirect_url' => $checkoutUrl
                ]);
            }

            // Redirect for regular form submission
            return redirect()->to($checkoutUrl)->with('success', 'Reservasi berhasil dibuat! Silakan lanjutkan ke pembayaran.');
        } catch (\Exception $e) {
            Log::error('Customer booking error: '.$e->getMessage());
            $errorMsg = 'Gagal membuat reservasi. ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }
    }

    /**
     * Customer Payment Page - Display customer-facing payment page
     */
    public function customerPayment(PenginapanReservasi $penginapanReservasi)
    {
        // Authorization check
        if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow payment for unpaid reservations
        if (!in_array($penginapanReservasi->status_reservasi, ['menunggu konfirmasi', 'booking'])) {
            abort(403, 'Reservasi tidak dapat dibayar');
        }

        $penginapanReservasi->load(['pelanggan', 'penginapan']);
        $banks = Bank::all();
        
        return view('fe.penginapan.payment', [
            'reservasi' => $penginapanReservasi,
            'banks' => $banks,
            'midtrans_client_key' => config('midtrans.client_key')
        ]);
    }

    /**
     * Get Snap Token for customer payment
     */
    public function customerSnapToken(Request $request, PenginapanReservasi $penginapanReservasi)
    {
        try {
            // Authorization check
            if (Auth::user()->level === 'pelanggan' && $penginapanReservasi->id_pelanggan != Auth::user()->pelanggan->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Load relationships
            $penginapanReservasi->load(['pelanggan', 'penginapan']);

            // Verify pelanggan exists
            if (!$penginapanReservasi->pelanggan) {
                Log::error('Pelanggan not found for reservasi ID: ' . $penginapanReservasi->id);
                return response()->json(['success' => false, 'message' => 'Data pelanggan tidak ditemukan'], 422);
            }

            // Recalculate total_bayar if it's negative or invalid
            if (!$penginapanReservasi->total_bayar || $penginapanReservasi->total_bayar <= 0) {
                Log::warning('Invalid total_bayar for reservasi ' . $penginapanReservasi->id . ': ' . ($penginapanReservasi->total_bayar ?? 'null'));
                
                // Recalculate from reservation data
                $subtotal = $penginapanReservasi->harga_per_malam * $penginapanReservasi->lama_malam * $penginapanReservasi->jumlah_kamar;
                $total = $subtotal - $penginapanReservasi->nilai_diskon;
                
                if ($total <= 0) {
                    throw new \Exception('Total pembayaran tidak valid setelah perhitungan ulang. Subtotal: ' . $subtotal . ', Diskon: ' . $penginapanReservasi->nilai_diskon);
                }
                
                // Update the reservation with correct total
                $penginapanReservasi->update(['total_bayar' => $total]);
                $penginapanReservasi->refresh();
            }

            $midtransService = new MidtransService();
            // Call createToken with correct parameters: (reservasi, pelanggan, banks, type)
            $token = $midtransService->createToken($penginapanReservasi, $penginapanReservasi->pelanggan, [], 'penginapan');

            return response()->json(['success' => true, 'token' => $token]);
        } catch (\Exception $e) {
            Log::error('Snap token error: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan token pembayaran: ' . $e->getMessage()], 422);
        }
    }
}
