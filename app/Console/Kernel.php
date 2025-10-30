<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Daftarkan command Anda di sini
        \App\Console\Commands\FetchMarketPrices::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // Di sinilah Anda menambahkan jadwal dari jawaban sebelumnya
        
        $schedule->command('app:fetch-market-prices')
             ->everyTenMinutes()
             ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}