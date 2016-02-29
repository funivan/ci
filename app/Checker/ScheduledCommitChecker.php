<?php

  namespace App\Checker;

  use App\Ci\Config;
  use App\Models\Commit;
  use Monolog\Formatter\JsonFormatter;
  use Monolog\Handler\StreamHandler;
  use Monolog\Logger;
  use Symfony\Component\Process\Process;


  /**
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  class ScheduledCommitChecker implements CommitCheckerInterface {

    /**
     * @var Logger
     */
    private $loger;

    /**
     * @var string
     */
    private $repositoryPath;


    /**
     * @param Commit $commit
     * @throws \Exception
     */
    public function check(\App\Models\Commit $commit) {
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

        $this->repositoryPath = config('ci.repo');

        if (empty($this->repositoryPath)) {
          throw new \Exception('Empty repo path');
        }

        if (!is_dir($this->repositoryPath)) {
          throw new \Exception('Directory does  not exist: ' . $this->repositoryPath);
        }

        # navigate to specific commit
        $this->command('git fetch --all && git reset --hard origin/' . $commit->branch . ' && git checkout ' . $commit->hash);
        $commitId = $this->command('git rev-parse HEAD');

        if (trim($commitId) != $commit->hash) {
          throw new \Exception('Cant checkout specific commit:' . $commit->hash);
        }


        $this->runTests($commit);

        $result = true;

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


    /**
     * @param string $command
     * @return string
     * @throws \Exception
     */
    private function command($command) {
      $this->loger->info('command:' . $command);
      $process = new Process($command);
      $process->setTimeout(7 * 60);
      $process->setWorkingDirectory($this->repositoryPath);
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


    /**
     * @param Commit $commit
     * @throws \Exception
     */
    private function runTests(Commit $commit) {
      $filePath = $this->repositoryPath . '/ci.yml';

      if (!is_file($filePath)) {
        throw new \Exception('Cant detect ci.yml configuration file');
      }

      $config = Config::createFromString(file_get_contents($filePath), $commit);

      /** @var string $name */
      $name = config('ci.defaultProfile');
      if (empty($name)) {
        throw new \Exception('Empty default profile name. Edit ci.app.php');
      }

      $profiles = $config->getProfiles();
      if (empty($profiles[$name])) {
        throw new \Exception('Empty default profile configuration');
      }


      /** @var array $checkCommands */
      $checkCommands = (array) $profiles[$name];
      foreach ($checkCommands as $command) {
        $this->command($command);
      }

    }


  }