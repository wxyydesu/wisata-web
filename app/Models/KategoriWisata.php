<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriWisata extends Model
{
    use HasFactory;

    protected $table = 'kategori_wisatas';
    
    protected $fillable = ['kategori_wisata'];

    public function obyekWisata()
    {
        return $this->hasMany(ObyekWisata::class, 'id_kategori_wisata');
    }
}
