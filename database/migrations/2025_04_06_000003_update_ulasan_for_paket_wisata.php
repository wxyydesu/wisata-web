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
        Schema::table('ulasan', function (Blueprint $table) {
            // Tambah kolom untuk paket wisata (optional)
            $table->foreignId('paket_wisata_id')->nullable()->references('id')->on('paket_wisatas')->onDelete('cascade')->after('penginapan_id');
            // Tambah kolom untuk reservasi paket
            $table->foreignId('reservasi_id')->nullable()->references('id')->on('reservasis')->onDelete('cascade')->after('paket_wisata_id');
            // Tambah kolom untuk reservasi penginapan
            $table->foreignId('penginapan_reservasi_id')->nullable()->references('id')->on('penginapan_reservasis')->onDelete('cascade')->after('reservasi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ulasan', function (Blueprint $table) {
            $table->dropForeign(['paket_wisata_id']);
            $table->dropColumn(['paket_wisata_id']);
            $table->dropForeign(['reservasi_id']);
            $table->dropColumn(['reservasi_id']);
            $table->dropForeign(['penginapan_reservasi_id']);
            $table->dropColumn(['penginapan_reservasi_id']);
        });
    }
};
