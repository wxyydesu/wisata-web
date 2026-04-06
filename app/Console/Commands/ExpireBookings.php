<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservasi;
use App\Models\PenginapanReservasi;
use Carbon\Carbon;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire {--type=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire bookings that have passed the end date. Type: all, reservasi, penginapan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $today = Carbon::now()->toDateString();

        $count = 0;

        // Expire Reservasi Paket Wisata
        if ($type === 'all' || $type === 'reservasi') {
            $expiredReservasi = Reservasi::where('status_reservasi', 'booking')
                ->where('tgl_akhir', '<', $today)
                ->get();

            foreach ($expiredReservasi as $reservasi) {
                $reservasi->update(['status_reservasi' => 'selesai']);
                $count++;
            }

            $this->info("Marked {$count} expired reservasi paket as selesai.");
        }

        // Expire Reservasi Penginapan
        if ($type === 'all' || $type === 'penginapan') {
            $expiredPenginapan = PenginapanReservasi::where('status_reservasi', 'booking')
                ->where('tgl_check_out', '<', $today)
                ->get();

            $countPenginapan = 0;
            foreach ($expiredPenginapan as $reservasi) {
                $reservasi->update(['status_reservasi' => 'selesai']);
                $countPenginapan++;
            }

            $this->info("Marked {$countPenginapan} expired reservasi penginapan as selesai.");
            $count += $countPenginapan;
        }

        $this->info("Total: {$count} bookings expired.");
    }
}
