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
        Schema::table('paket_wisatas', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->integer('durasi')->default(1)->after('harga_per_pack');
        });
    }
    public function down()
    {
        Schema::table('paket_wisatas', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('durasi');
        });
    }
};
