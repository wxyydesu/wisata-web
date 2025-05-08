<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne; // Import HasOne

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'foto',
        'alamat',
        'email_verified_at',
        'no_hp',
        'password',
        'level', 
        'aktif', 
        'remember_token'
    ];
    

    public function pelanggan(): HasOne
    {
        return $this->hasOne(Pelanggan::class, 'id_user', 'id');
    }

    public function karyawan(): HasOne
    {
        return $this->hasOne(Karyawan::class, 'id_user', 'id');
    }

    public function pendingReservations()
    {
        if ($this->pelanggan) {
            return $this->pelanggan->reservasis()->where('status_reservasi', 'pesan');
        }
        
        return collect(); // Return empty collection jika bukan pelanggan
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
