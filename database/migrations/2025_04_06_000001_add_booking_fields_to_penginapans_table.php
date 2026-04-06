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
        Schema::table('penginapans', function (Blueprint $table) {
            $table->decimal('harga_per_malam', 12, 2)->after('fasilitas')->default(0);
            $table->integer('kapasitas')->after('harga_per_malam')->default(1);
            $table->string('lokasi', 255)->nullable()->after('kapasitas');
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia')->after('lokasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penginapans', function (Blueprint $table) {
            $table->dropColumn(['harga_per_malam', 'kapasitas', 'lokasi', 'status']);
        });
    }
};
