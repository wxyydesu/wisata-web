<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiskonPaket extends Model
{
    protected $table = 'diskon_pakets';
    protected $fillable = [
        'paket_id', 
        'aktif', 
        'persen', 
        'tanggal_mulai',
        'tanggal_akhir'
    ];

    public function paket()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_id');
    }
}