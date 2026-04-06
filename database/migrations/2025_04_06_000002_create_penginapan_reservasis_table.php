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
        Schema::create('penginapan_reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_penginapan')->references('id')->on('penginapans')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('tgl_reservasi')->nullable();
            $table->date('tgl_check_in')->nullable();
            $table->date('tgl_check_out')->nullable();
            $table->integer('lama_malam')->nullable();
            $table->decimal('harga_per_malam', 12, 2)->nullable();
            $table->integer('jumlah_kamar');
            $table->decimal('diskon', 10, 2)->nullable();
            $table->decimal('nilai_diskon', 12, 2)->nullable();
            $table->bigInteger('total_bayar');
            $table->text('file_bukti_tf')->nullable();
            $table->enum('status_reservasi', ['menunggu konfirmasi', 'booking', 'batal', 'selesai'])->default('menunggu konfirmasi');
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_status')->nullable();
            $table->string('midtrans_payment_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penginapan_reservasis');
    }
};
