<?php

namespace App\Console\Commands;

use App\Models\TempSeatReservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupExpiredSeatReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seats:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired temporary seat reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of expired seat reservations...');

        try {
            $count = TempSeatReservation::expired()->count();

            if ($count > 0) {
                TempSeatReservation::cleanupExpired();
                $this->info("Successfully cleaned up {$count} expired seat reservations.");
                Log::info("Cleaned up {$count} expired seat reservations.");
            } else {
                $this->info('No expired seat reservations found.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error cleaning up expired seat reservations: ' . $e->getMessage());
            Log::error('Error cleaning up expired seat reservations: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
