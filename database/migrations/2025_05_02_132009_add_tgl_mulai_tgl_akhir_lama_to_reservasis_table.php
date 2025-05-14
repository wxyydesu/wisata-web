<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('reservasis', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_akhir')->nullable();
            $table->integer('lama_reservasi')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('reservasis', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn(['tgl_mulai', 'tgl_akhir', 'lama_reservasi']);
        });
    }
};
