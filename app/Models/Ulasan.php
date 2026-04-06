<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'penginapan_id',
        'paket_wisata_id',
        'user_id',
        'reservasi_id',
        'penginapan_reservasi_id',
        'rating',
        'komentar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penginapan()
    {
        return $this->belongsTo(Penginapan::class, 'penginapan_id');
    }

    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id');
    }

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id');
    }

    public function penginapanReservasi()
    {
        return $this->belongsTo(PenginapanReservasi::class, 'penginapan_reservasi_id');
    }
}
