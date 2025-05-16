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
        'foto5'
    ];

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'penginapan_id');
    }
}
