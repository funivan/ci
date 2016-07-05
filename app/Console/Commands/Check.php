<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;
  use Monolog\Handler\PsrHandler;
  use Symfony\Component\Console\Logger\ConsoleLogger;
  use Symfony\Component\Console\Style\OutputStyle;

  /**
   * @package App\Console\Commands
   */
  class Check extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:check {--id=}';

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

      $id = $this->option('id');

      /** @var Commit $commit */
      $query = Commit::query();
      $query = $query->where('id', '=', $id);
      $commit = $query->get()->first();

      if (empty($commit)) {
        $this->getOutput()->writeln('<error>Cant find commit:' . $id . '</error>');
        return;
      }

      $checker = new \App\Ci\Checker\ScheduledChecker();
      /** @var OutputStyle $output */
      $output = $this->getOutput();


      $checker->check($commit, new PsrHandler(new ConsoleLogger($output)));

    }


  }
