<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penginapan extends Model
{
    use HasFactory;

    protected $table = 'penginapans';
    
    protected $fillable = [
        'nama_penginapan',
        'deskripsi',
        'fasilitas',
        'foto1',
        'foto2',
        'foto3',
        'foto4',
        'foto5',
        'harga_per_malam',
        'kapasitas',
        'lokasi',
        'status'
    ];

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'penginapan_id');
    }

    public function reservasiPenginapan()
    {
        return $this->hasMany(PenginapanReservasi::class, 'id_penginapan');
    }

    public function reservasiPenginapanAktif()
    {
        return $this->hasMany(PenginapanReservasi::class, 'id_penginapan')
            ->whereNotIn('status_reservasi', ['batal', 'selesai']);
    }
}
