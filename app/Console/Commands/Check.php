<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  /**
   * @package App\Console\Commands
   */
  class Check extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:check {--profile?} {hash}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check specific commit';


    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle() {
      $hash = $this->argument('hash');

      /** @var Commit $commit */
      $commit = Commit::query()->where('hash', '=', $hash)->get()->first();
      if (empty($commit)) {
        $this->getOutput()->writeln('<error>Cant find commit with hash:' . $hash . '</error>');
        return;
      }

      $checker = new \App\Checker\ScheduledCommitChecker();
      $checker->check($commit);

    }


  }
