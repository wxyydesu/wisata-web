<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'nama_bank' => 'Bank Central Asia',
                'kode_bank' => 'bca',
                'no_rekening' => '0123456789',
                'atas_nama' => 'Perusahaan Wisata',
                'aktif' => true
            ],
            [
                'nama_bank' => 'Bank Mandiri',
                'kode_bank' => 'mandiri',
                'no_rekening' => '9876543210',
                'atas_nama' => 'Perusahaan Wisata',
                'aktif' => true
            ],
            [
                'nama_bank' => 'Bank BNI',
                'kode_bank' => 'bni',
                'no_rekening' => '1122334455',
                'atas_nama' => 'Perusahaan Wisata',
                'aktif' => true
            ],
        ];

        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['kode_bank' => $bank['kode_bank']],
                $bank
            );
        }
    }
}
