<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Rodjendan;
use App\Console\Commands\Lijecnicki;
use App\Console\Commands\Godisnjica;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      //  \App\Console\Commands\Inspire::class,
		\App\Console\Commands\Rodjendan::class,
		\App\Console\Commands\Godisnjica::class,
		\App\Console\Commands\Probni::class,
		\App\Console\Commands\Probni1::class,
		\App\Console\Commands\Probni2::class,
		\App\Console\Commands\Lijecnicki::class,
		\App\Console\Commands\Lijecnicki1::class,
		\App\Console\Commands\Lijecnicki2::class,
		\App\Console\Commands\Odjava::class,
		\App\Console\Commands\GO::class,
		\App\Console\Commands\Anketa::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
    */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:Rodjendan')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Godisnjica')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Lijecnicki')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Lijecnicki1')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Lijecnicki2')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Probni')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Probni1')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Probni2')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:GO')
				  ->dailyAt('8:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Odjava')
				  ->dailyAt('16:00')
				  ->evenInMaintenanceMode();
		$schedule->command('email:Anketa')
				  ->dailyAt('08:00')
				  ->evenInMaintenanceMode();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
    */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
