<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'banks';

    protected $fillable = [
        'nama_bank',
        'kode_bank',
        'no_rekening',
        'atas_nama',
        'aktif',
    ];

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_bank');
    }
}