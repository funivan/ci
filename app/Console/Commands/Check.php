<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;
  use Monolog\Formatter\JsonFormatter;
  use Monolog\Formatter\LineFormatter;
  use Monolog\Handler\StreamHandler;
  use Monolog\Logger;
  use Symfony\Component\Process\Process;

  class Check extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all scheduled commits';

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
      //$commit = Commit::where('status', '=', Commit::STATUS_PENDING)->get()->get(0);
      $commit = Commit::where('id', '=', 2)->get()->get(0);
      if (empty($commit)) {
        $this->getOutput()->writeln("Nothing to check");
        return;
      }


      $file = $commit->getLogFilePath();

      # create a log channel
      $log = new Logger('name');
      $stream = new StreamHandler($file, config('ci.logLevel'));
      $stream->setFormatter(new JsonFormatter());
      $log->pushHandler($stream);

      $this->loger = $log;


      $commit->start_time = time();
      $this->loger->debug('start time:' . $commit->start_time);

      $commit->end_time = 0;
      $commit->status = Commit::STATUS_IN_PROGRESS;
      $commit->save();
      try {

        $this->repoPath = config('ci.repo');

        if (empty($this->repoPath)) {
          throw new \Exception('Empty repo path');
        }

        if (!is_dir($this->repoPath)) {
          throw new \Exception('Directory does  not exist: ' . $this->repoPath);
        }


        $result = $this->perform($commit);
      } catch (\Exception $ex) {
        $this->loger->emergency('exception:' . $ex->getMessage());
        throw $ex;
      } finally {

        $commit->end_time = time();

        if (isset($result)) {
          $commit->status = Commit::STATUS_OK;
          $this->loger->info('status: ok');
        } else {
          $commit->status = Commit::STATUS_FAILURE;
          $this->loger->emergency('status: failure');
        }
        $this->loger->emergency('end time:' . $commit->end_time);
        $commit->save();
      }

    }


    private function command($command) {
      $this->loger->info('command:' . $command);
      $process = new Process($command);
      $process->setTimeout(7 * 60);
      $process->setWorkingDirectory($this->repoPath);
      $process->run();

      $output = trim($process->getOutput());
      if (!$process->isSuccessful()) {
        $this->loger->emergency($process->getErrorOutput());
        $this->loger->emergency($output);
        throw new \Exception('Command not successful:' . $command);
      }

      $this->loger->debug('command output:' . $output);

      return $process->getOutput();
    }


    private function perform(Commit $commit) {

      # navigate to specific commit
      $this->command('git fetch --all && git reset --hard origin/' . $commit->branch . ' && git checkout ' . $commit->hash);
      $commitId = $this->command('git rev-parse HEAD');

      if (trim($commitId) != $commit->hash) {
        throw new \Exception('Cant checkout specific commit:' . $commit->hash);
      }

      # run script
      foreach (config('ci.commands') as $command) {
        $this->command($command);
      }

    }

  }
