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

      /** @var Commit $inProgressCommit */
      $inProgressCommit = Commit::query()->where('status', '=', Commit::STATUS_IN_PROGRESS)->get()->first();
      if (!empty($inProgressCommit)) {
        $this->getOutput()->writeln('Already check commit: ' . $inProgressCommit->hash);
        return;
      }

      /** @var Commit $commit */
      $commit = Commit::query()->where('status', '=', Commit::STATUS_PENDING)->get()->first();
      if (empty($commit)) {
        $this->getOutput()->writeln('done');
        return;
      }


      $checker = new \App\Ci\Checker\ScheduledCommitChecker();
      $checker->check($commit);
    }
  }
