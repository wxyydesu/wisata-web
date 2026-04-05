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
            
            // Update existing records
            // The previous migration updated: pesan→menunggu konfirmasi, dibayar→booking, ditolak→canceled
            // Now just ensure the format is consistent
            
            // Finally, modify to enum with all needed values
            // Using the new scheme from the update migration
            DB::statement("ALTER TABLE reservasis MODIFY status_reservasi ENUM('menunggu konfirmasi', 'booking', 'canceled', 'selesai') DEFAULT 'menunggu konfirmasi'");
        } elseif ($connection === 'sqlite') {
            // SQLite doesn't support enum, use string instead
            // No changes needed for SQLite
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback to previous enum state
        $connection = DB::connection()->getDriverName();
        
        if ($connection === 'mysql') {
            DB::statement("ALTER TABLE reservasis MODIFY status_reservasi ENUM('pesan', 'dibayar','ditolak', 'selesai') DEFAULT 'pesan'");
        }
    }
};
