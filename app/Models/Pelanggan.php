<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';
    
    protected $fillable = [
        'nama_lengkap',
        'no_hp',
        'alamat',
        'foto',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_pelanggan');
    }
}