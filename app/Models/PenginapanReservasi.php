<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenginapanReservasi extends Model
{
    use HasFactory;

    protected $table = 'penginapan_reservasis';
    
    protected $fillable = [
        'id_pelanggan',
        'id_penginapan',
        'tgl_reservasi',
        'tgl_check_in',
        'tgl_check_out',
        'lama_malam',
        'harga_per_malam',
        'jumlah_kamar',
        'diskon',
        'nilai_diskon',
        'total_bayar',
        'file_bukti_tf',
        'status_reservasi',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_status',
        'midtrans_payment_type'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function penginapan()
    {
        return $this->belongsTo(Penginapan::class, 'id_penginapan');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'penginapan_reservasi_id');
    }

    /**
     * Check if booking is expired
     */
    public function isExpired()
    {
        return $this->status_reservasi === 'booking' 
            && \Carbon\Carbon::now()->toDateString() > $this->tgl_check_out;
    }

    /**
     * Get remaining days for the booking
     */
    public function getRemainingDays()
    {
        return \Carbon\Carbon::parse($this->tgl_check_out)->diffInDays(\Carbon\Carbon::now());
    }
}
