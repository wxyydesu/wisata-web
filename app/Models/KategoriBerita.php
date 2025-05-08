<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBerita extends Model
{
    use HasFactory;

    protected $table = 'kategori_beritas';
    
    protected $fillable = [
        'kategori_berita'
    ];

    public function beritas()
    {
        return $this->hasMany(Berita::class, 'id_kategori_berita');
    }
}
