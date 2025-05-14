<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('diskon_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket_wisatas')->onDelete('cascade');
            $table->boolean('aktif')->default(false);
            $table->integer('persen')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('diskon_pakets');
    }
};