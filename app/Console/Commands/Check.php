<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;
  use Monolog\Logger;

  /**
   * @package App\Console\Commands
   */
  class Check extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:check  {hash}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check specific commit';

    /**
     * @var Logger
     */
    private $loger = null;

    /**
     * @var string
     */
    private $repoPath;


    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle() {
      /** @var Commit $commit */
      $hash = $this->argument('hash');

      $commit = Commit::where('hash', '=', $hash)->get()->get(0);
      if (empty($commit)) {
        $this->getOutput()->writeln('<error>Cant find commit with hash:' . $hash . '</error>');
        return;
      }

      $checker = new \App\Checker\ScheduledCommitChecker();
      $checker->check($commit);

    }


  }
