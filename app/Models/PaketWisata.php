<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWisata extends Model
{
    use HasFactory;

    protected $table = 'paket_wisatas';
    
    protected $fillable = [
        'nama_paket',
        'deskripsi',
        'fasilitas',
        'harga_per_pack',
        'foto1',
        'foto2',
        'foto3',
        'foto4',
        'foto5'
    ];

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_paket');
    }
    public function reservasiAktif()
    {
        return $this->hasMany(Reservasi::class, 'id_paket')
            ->whereNotIn('status_reservasi', ['ditolak', 'selesai']);
    }

    public function diskonAktif()
    {
        return $this->hasOne(DiskonPaket::class, 'paket_id')
            ->where('aktif', 1)
            ->where(function($q) {
                $q->whereNull('tanggal_mulai')
                ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('tanggal_akhir')
                ->orWhere('tanggal_akhir', '>=', now());
            });
    }
}
