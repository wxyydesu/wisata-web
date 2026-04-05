<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->string('kode_bank', 50)->nullable()->after('nama_bank')->comment('Midtrans bank code (bca, mandiri, bni, etc)');
            $table->boolean('aktif')->default(true)->after('atas_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn('kode_bank');
            $table->dropColumn('aktif');
        });
    }
};
