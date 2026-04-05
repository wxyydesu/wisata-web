<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = DB::connection()->getDriverName();
        
        if ($connection === 'mysql') {
            // First, change the column to VARCHAR to allow updates
            DB::statement("ALTER TABLE reservasis MODIFY status_reservasi VARCHAR(255)");
            
            // Update existing records with old status values to new status values
            DB::statement("UPDATE reservasis SET status_reservasi = 'menunggu konfirmasi' WHERE status_reservasi = 'pesan'");
            DB::statement("UPDATE reservasis SET status_reservasi = 'booking' WHERE status_reservasi = 'dibayar'");
            DB::statement("UPDATE reservasis SET status_reservasi = 'canceled' WHERE status_reservasi = 'ditolak'");
            
            // Finally, modify back to enum with new values
            DB::statement("ALTER TABLE reservasis MODIFY status_reservasi ENUM('menunggu konfirmasi', 'booking', 'canceled', 'selesai') DEFAULT 'menunggu konfirmasi'");
        } elseif ($connection === 'sqlite') {
            // SQLite doesn't support enum, use string instead
            DB::statement("ALTER TABLE reservasis RENAME TO reservasis_old");
            
            Schema::create('reservasis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade')->onUpdate('cascade');
                $table->foreignId('id_paket')->references('id')->on('paket_wisatas')->onDelete('cascade')->onUpdate('cascade');
                $table->dateTime('tgl_reservasi')->nullable();
                $table->date('tgl_mulai')->nullable();
                $table->date('tgl_akhir')->nullable();
                $table->integer('lama_reservasi')->nullable();
                $table->integer('harga');
                $table->integer('jumlah_peserta');
                $table->decimal('diskon', 10, 0)->nullable();
                $table->float('nilai_diskon')->nullable();
                $table->bigInteger('total_bayar');
                $table->text('file_bukti_tf')->nullable();
                $table->string('status_reservasi')->default('menunggu konfirmasi');
                $table->timestamps();
            });
            
            DB::statement("INSERT INTO reservasis SELECT * FROM reservasis_old");
            DB::statement("DROP TABLE reservasis_old");
            
            // Update statuses
            DB::statement("UPDATE reservasis SET status_reservasi = 'menunggu konfirmasi' WHERE status_reservasi = 'pesan'");
            DB::statement("UPDATE reservasis SET status_reservasi = 'booking' WHERE status_reservasi = 'dibayar'");
            DB::statement("UPDATE reservasis SET status_reservasi = 'canceled' WHERE status_reservasi = 'ditolak'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = DB::connection()->getDriverName();
        
        if ($connection === 'mysql') {
            // Revert back to old enum
            DB::statement("UPDATE reservasis SET status_reservasi = 'pesan' WHERE status_reservasi = 'menunggu konfirmasi'");
            DB::statement("UPDATE reservasis SET status_reservasi = 'dibayar' WHERE status_reservasi = 'booking'");
            DB::statement("UPDATE reservasis SET status_reservasi = 'ditolak' WHERE status_reservasi = 'canceled'");
            DB::statement("ALTER TABLE reservasis MODIFY status_reservasi ENUM('pesan', 'dibayar', 'ditolak', 'selesai') DEFAULT 'pesan'");
        }
    }
};
