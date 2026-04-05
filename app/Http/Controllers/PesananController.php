<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $query = Reservasi::with(['paketWisata', 'bank'])
            ->where('id_pelanggan', $user->pelanggan->id);

        if (request('status')) {
            $query->where('status_reservasi', request('status'));
        }

        $reservasis = $query->latest()->paginate(10)->appends(request()->all());

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
     * Print all reservations for the current user.
     */
    public function printAll()
    {
        $user = Auth::user();
        $query = Reservasi::with(['paketWisata', 'bank', 'pelanggan'])
            ->where('id_pelanggan', $user->pelanggan->id);

        if (request('status')) {
            $query->where('status_reservasi', request('status'));
        }

        $reservasis = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('fe.pesanan.invoice-all', [
            'reservasis' => $reservasis,
            'title' => 'Invoice Semua Pesanan'
        ]);

        return $pdf->stream('invoice-semua-pesanan.pdf');
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

    /**
     * Show payment page for a specific order
     */
    public function payment($id)
    {
        $reservasi = Reservasi::with(['paketWisata', 'pelanggan.user'])
                    ->where('id_pelanggan', Auth::user()->pelanggan->id)
                    ->findOrFail($id);

        // Only allow payment for unpaid orders (status 'menunggu konfirmasi')
        if ($reservasi->status_reservasi === 'booking') {
            return redirect()->route('pesanan.detail', $id)->with('info', 'Pesanan ini sudah dibayar');
        }

        return view('fe.pesanan.payment', [
            'title' => 'Pembayaran Pesanan',
            'reservasi' => $reservasi,
            'midtrans_client_key' => config('midtrans.client_key')
        ]);
    }

    /**
     * Get Snap Token via AJAX
     */
    public function getSnapToken($id)
    {
        try {
            $user = Auth::user();
            
            // Ensure pelanggan exists
            if (!$user->pelanggan) {
                return response()->json([
                    'error' => 'Pelanggan tidak ditemukan',
                    'message' => 'Data pelanggan tidak terkait dengan akun Anda'
                ], 400);
            }
            
            $reservasi = Reservasi::with(['paketWisata', 'pelanggan.user'])
                        ->where('id_pelanggan', $user->pelanggan->id)
                        ->findOrFail($id);

            // Get active banks
            $banks = Bank::where('aktif', true)->get();

            // Create Midtrans Service and get snap token
            $midtransService = new \App\Services\MidtransService();
            $snapToken = $midtransService->createToken($reservasi, $reservasi->pelanggan, $banks);

            // Store order ID for tracking if not already set
            if (!$reservasi->midtrans_order_id) {
                $reservasi->midtrans_order_id = 'RES-' . $reservasi->id . '-' . time();
                $reservasi->save();
            }

            return response()->json([
                'snap_token' => $snapToken,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get snap token: ' . $e->getMessage() . ' | ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'error' => 'Gagal membuat token pembayaran',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify payment status from Midtrans and update if needed
     */
    public function verifyPayment($id)
    {
        try {
            $user = Auth::user();
            $reservasi = Reservasi::where('id_pelanggan', $user->pelanggan->id)
                        ->findOrFail($id);

            // If already paid, no need to verify
            if ($reservasi->status_reservasi === 'booking') {
                return response()->json([
                    'status' => 'booking',
                    'message' => 'Pembayaran sudah dikonfirmasi'
                ]);
            }

            // If no order ID, cannot verify
            if (!$reservasi->midtrans_order_id) {
                return response()->json([
                    'status' => $reservasi->status_reservasi,
                    'message' => 'Belum ada data pembayaran'
                ]);
            }

            // Check status from Midtrans
            $midtransService = new \App\Services\MidtransService();
            $transactionStatus = $midtransService->getStatus($reservasi->midtrans_order_id);

            if ($transactionStatus) {
                // Convert to object if array
                if (is_array($transactionStatus)) {
                    $transactionStatus = (object)$transactionStatus;
                }
                
                $transactionMidtransStatus = $transactionStatus->transaction_status;
                
                // Update reservasi if status changed
                if ($reservasi->midtrans_status !== $transactionMidtransStatus) {
                    $reservasi->midtrans_status = $transactionMidtransStatus;
                    $reservasi->midtrans_transaction_id = $transactionStatus->transaction_id ?? $transactionStatus->id ?? $reservasi->midtrans_transaction_id;
                    $reservasi->midtrans_payment_type = $transactionStatus->payment_type ?? $reservasi->midtrans_payment_type;
                    
                    // Map and update app status
                    $appStatus = $midtransService->mapStatus($transactionMidtransStatus);
                    $reservasi->status_reservasi = $appStatus;
                    $reservasi->save();

                    Log::info('Verified and updated payment status', [
                        'reservasi_id' => $id,
                        'order_id' => $reservasi->midtrans_order_id,
                        'transaction_status' => $transactionMidtransStatus,
                        'app_status' => $appStatus
                    ]);
                }

                return response()->json([
                    'status' => $reservasi->status_reservasi,
                    'midtrans_status' => $transactionMidtransStatus,
                    'message' => 'Status telah diverifikasi'
                ]);
            }

            return response()->json([
                'status' => $reservasi->status_reservasi,
                'message' => 'Belum ada status pembayaran dari Midtrans'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to verify payment: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal memverifikasi pembayaran',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}