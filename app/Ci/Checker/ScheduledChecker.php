<?php

  namespace App\Ci\Checker;

  use App\Ci\Commands\CommandExecutor;
  use App\Ci\Commands\Internal\GitCheckoutSpecificVersionCommand;
  use App\Ci\Commands\Internal\GitFetchCommitInfo;
  use App\Ci\Configuration\ConfigurationInterface;
  use App\Ci\Configuration\Profile\Profile;
  use App\Ci\Configuration\RunConfig;
  use App\Ci\Configuration\RunConfigAwareInterface;
  use App\Models\Commit;
  use Monolog\Formatter\JsonFormatter;
  use Monolog\Handler\HandlerInterface;
  use Monolog\Handler\StreamHandler;
  use Monolog\Logger;


  /**
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  class ScheduledChecker implements CheckerInterface {

    /**
     * @var Logger
     */
    private $logger;


    /**
     * @param Commit $commit
     * @throws \Exception
     */
    public function check(\App\Models\Commit $commit, HandlerInterface $customLogHandler = null) {


      $file = $commit->getLogFilePath();
      if (is_file($file)) {
        unlink($file);
      }

      # create a log channel
      $this->logger = new Logger('commit.check');
      $defaultLoggerStream = new StreamHandler($file, \Monolog\Logger::DEBUG);
      $defaultLoggerStream->setFormatter(new JsonFormatter());
      $this->logger->pushHandler($defaultLoggerStream);

      if ($customLogHandler !== null) {
        $this->logger->pushHandler($customLogHandler);
      }


      $commit->start_time = time();
      $commit->end_time = 0;
      $commit->status = Commit::STATUS_IN_PROGRESS;
      $commit->save();

      $this->logger->debug('start time:' . $commit->start_time);

      $profile = null;
      try {


        $repositoryPath = config('ci.repo');

        if (empty($repositoryPath)) {
          throw new \Exception('Empty repository path');
        }

        if (!is_dir($repositoryPath)) {
          throw new \Exception('Repository directory does not exist: ' . $repositoryPath);
        }


        $runConfiguration = new RunConfig($commit, $this->logger, $repositoryPath);


        $commandExecutor = new CommandExecutor();
        $commandExecutor->execute(new GitCheckoutSpecificVersionCommand(), $runConfiguration);
        $commandExecutor->execute(new GitFetchCommitInfo(), $runConfiguration);


        $config = $this->createConfig($runConfiguration);


        foreach ($config->getLogHandlers() as $handler) {
          $this->logger->pushHandler($handler);
        }


        $profile = $this->getProfile($commit, $config);
        if ($profile instanceof RunConfigAwareInterface) {
          $profile->setRunConfig($runConfiguration);
        }




        foreach ($profile->getCommands() as $command) {
          $commandExecutor->execute($command, $runConfiguration);
        }

        $result = true;

        $profile->fireSuccess();

      } catch (\Exception $ex) {

        $this->logger->emergency('exception:' . $ex->getMessage());
        throw $ex;

      } finally {

        $commit->end_time = time();

        if (isset($result)) {
          $commit->status = Commit::STATUS_OK;
          $this->logger->info('status: ok');
        } else {
          $commit->status = Commit::STATUS_FAILURE;
          $this->logger->emergency('status: failure');
        }
        $this->logger->emergency('end time:' . $commit->end_time);
        $commit->save();

        if ($profile instanceof Profile) {
          $profile->fireFailure();
        }

      }

    }


    private function getProfile(Commit $commit, ConfigurationInterface $config) : Profile {
      $profiles = $config->getProfiles();

      if (count($profiles) == 0) {
        throw new \RuntimeException('Expect at least one profile');
      }

      foreach ($profiles as $profile) {
        if ($profile->getName() == $commit->profile) {
          return $profile;
        }
      }

      return reset($profiles);
    }


    /**
     * @param $filePath
     * @return ConfigurationInterface
     */
    private function createConfig(RunConfig $runConfig) {

      $filePath = $runConfig->getRepositoryDirectory() . '/ci.php';
      if (!is_file($filePath)) {
        throw new \Exception('Can not detect ci.php configuration file');
      }

      /** @var ConfigurationInterface $runConfig */
      $runConfig = include $filePath;
      if (!$runConfig instanceof ConfigurationInterface) {
        throw new \RuntimeException('Invalid configuration file. Return value must be an object of the ' . ConfigurationInterface::class);
      }
      return $runConfig;
    }

  }