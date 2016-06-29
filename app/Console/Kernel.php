<?php

  namespace App\Console;

  use Illuminate\Console\Scheduling\Schedule;
  use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

  class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      Commands\AddCommit::class,

      Commands\Check::class,
      Commands\DeleteCommit::class,
      Commands\Rerun::class,

      Commands\CleanLogs::class,
      Commands\CleanCommits::class,
      Commands\Scheduler::class,
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
      $schedule->command('ci:scheduler')
        ->everyMinute();

      $schedule->command('ci:clean-logs')
        ->everyThirtyMinutes();

      $schedule->command('ci:clean-commits')
        ->twiceDaily();

    }
  }
