<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  /**
   *
   * @package App\Console\Commands
   */
  class Scheduler extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all scheduled commits';


    /**
     * Execute the console command
     *
     * @return mixed
     */
    public function handle() {

      $commit = Commit::where('status', '=', Commit::STATUS_PENDING)->get()->get(0);
      if (empty($commit)) {
        $this->getOutput()->writeln('done');
        return;
      }

      $checker = new \App\Checker\ScheduledCommitChecker();
      $checker->check($commit);
    }
  }
