<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register all custom artisan commands
     */
    protected $commands = [
        \App\Console\Commands\ProcessPhoneColumn::class,   // 🔥 custom command registered
    ];

    /**
     * Define scheduled tasks
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example (if you want auto run command)
        // $schedule->command('phone:process')->daily();
    }

    /**
     * Register commands directory
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
