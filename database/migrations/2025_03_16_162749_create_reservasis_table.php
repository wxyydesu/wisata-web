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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_paket')->references('id')->on('paket_wisatas')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('tgl_reservasi');
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->integer('lama_reservasi')->nullable();
            $table->integer('harga');
            $table->integer('jumlah_peserta');
            $table->decimal('diskon', 10, 0)->nullable();
            $table->float('nilai_diskon')->nullable();
            $table->bigInteger('total_bayar');
            $table->text('file_bukti_tf')->nullable();
            $table->enum('status_reservasi', ['pesan', 'dibayar','ditolak', 'selesai'])->default('pesan'); // Perbaikan default enum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
