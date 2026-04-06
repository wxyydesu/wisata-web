<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        
        if (!$serverKey || !$clientKey) {
            throw new \Exception('Midtrans credentials not configured properly. Check your .env file.');
        }
        
        Config::$serverKey = $serverKey;
        Config::$clientKey = $clientKey;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if (config('midtrans.is_production')) {
            Config::$isProduction = true;
        } else {
            Config::$isProduction = false;
        }
    }

    /**
     * Create Snap token for payment
     */
    public function createToken($reservasi, $pelanggan, $banks = [], $type = 'paket')
    {
        if ($type === 'penginapan') {
            $orderId = 'PEN-' . $reservasi->id . '-' . time();
        } else {
            $orderId = 'RES-' . $reservasi->id . '-' . time();
        }

        // Validate total_bayar
        if (!isset($reservasi->total_bayar) || (int)$reservasi->total_bayar <= 0) {
            throw new \Exception('Total pembayaran harus lebih besar dari 0. Current: ' . ($reservasi->total_bayar ?? 'null'));
        }

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => (int)$reservasi->total_bayar,
        ];

        // Item details
        if ($type === 'penginapan') {
            if (!isset($reservasi->penginapan) || !$reservasi->penginapan) {
                throw new \Exception('Data penginapan tidak ditemukan untuk reservasi ini');
            }
            
            $items = [
                [
                    'id' => (string)$reservasi->penginapan->id,
                    'price' => (int)($reservasi->harga_per_malam * $reservasi->jumlah_kamar),
                    'quantity' => $reservasi->lama_malam,
                    'name' => $reservasi->penginapan->nama_penginapan . ' (' . $reservasi->jumlah_kamar . ' kamar)',
                ]
            ];
        } else {
            $items = [
                [
                    'id' => (string)$reservasi->paketWisata->id,
                    'price' => (int)($reservasi->harga * $reservasi->jumlah_peserta),
                    'quantity' => $reservasi->jumlah_peserta,
                    'name' => $reservasi->paketWisata->nama_paket,
                ]
            ];
        }

        // If discount applied, add it as negative item
        if ($reservasi->nilai_diskon > 0) {
            $items[] = [
                'id' => 'discount',
                'price' => -(int)$reservasi->nilai_diskon,
                'quantity' => 1,
                'name' => 'Diskon',
            ];
        }

        // Validate customer details
        if (!$pelanggan || !isset($pelanggan->user)) {
            throw new \Exception('Data pelanggan atau user tidak ditemukan');
        }

        $customer_details = [
            'first_name' => $pelanggan->user->name ?? $pelanggan->nama_lengkap,
            'email' => $pelanggan->user->email ?? '',
            'phone' => $pelanggan->no_hp ?? '',
        ];

        if (!$customer_details['email']) {
            throw new \Exception('Email pelanggan tidak ditemukan');
        }

        // Build enabled payments
        $enabled_payments = ['credit_card', 'gcg_wallet'];

        // Add bank transfer if banks provided
        if (!empty($banks)) {
            $enabled_payments[] = 'bank_transfer';
        }

        $param = [
            'transaction_details' => $transaction_details,
            'item_details' => $items,
            'customer_details' => $customer_details,
            'enabled_payments' => $enabled_payments,
        ];

        // Add bank transfer specific config if banks provided
        if (!empty($banks)) {
            $bank_transfer = [];
            foreach ($banks as $bank) {
                $bank_transfer[] = [
                    'bank' => $bank->kode_bank ?? strtolower(str_replace(' ', '_', $bank->nama_bank)),
                ];
            }
            $param['bank_transfer'] = $bank_transfer;
        }

        try {
            $snapToken = Snap::getSnapToken($param);
            return $snapToken;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Snap token: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction status
     * @return object|array|null
     */
    public function getStatus($transactionId)
    {
        try {
            $status = Transaction::status($transactionId);
            return $status;
        } catch (\Exception $e) {
            throw new \Exception('Failed to get transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Parse Midtrans notification
     */
    public function parseNotification()
    {
        try {
            $notif = new Notification();
            return $notif;
        } catch (\Exception $e) {
            throw new \Exception('Failed to parse notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle payment notification callback
     */
    public function handleCallback($notification)
    {
        $orderId = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $transactionId = $notification->transaction_id;
        $paymentType = $notification->payment_type ?? null;

        return [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'transaction_id' => $transactionId,
            'payment_type' => $paymentType,
        ];
    }

    /**
     * Map Midtrans status to application status
     */
    public function mapStatus($midtransStatus)
    {
        $mapping = [
            'capture' => 'booking',           // Payment captured/successful
            'settlement' => 'booking',        // Payment settled
            'pending' => 'menunggu konfirmasi', // Waiting for payment confirmation
            'deny' => 'canceled',              // Payment denied
            'expire' => 'canceled',            // Payment expired
            'cancel' => 'canceled',            // Payment canceled
        ];

        return $mapping[$midtransStatus] ?? 'menunggu konfirmasi';
    }
}
