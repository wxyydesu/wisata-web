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
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
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
    public function createToken($reservasi, $pelanggan, $banks = [])
    {
        $orderId = 'RES-' . $reservasi->id . '-' . time();

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => (int)$reservasi->total_bayar,
        ];

        // Item details
        $items = [
            [
                'id' => $reservasi->paketWisata->id,
                'price' => (int)($reservasi->harga * $reservasi->jumlah_peserta),
                'quantity' => $reservasi->jumlah_peserta,
                'name' => $reservasi->paketWisata->nama_paket,
            ]
        ];

        // If discount applied, add it as negative item
        if ($reservasi->nilai_diskon > 0) {
            $items[] = [
                'id' => 'discount',
                'price' => -$reservasi->nilai_diskon,
                'quantity' => 1,
                'name' => 'Diskon',
            ];
        }

        $customer_details = [
            'first_name' => $pelanggan->user->name,
            'email' => $pelanggan->user->email,
            'phone' => $pelanggan->no_telp ?? '',
        ];

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
