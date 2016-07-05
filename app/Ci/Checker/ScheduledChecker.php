<?php

  namespace App\Ci\Checker;

  use App\Ci\Commands\CommandExecutor;
  use App\Ci\Commands\Internal\CheckProfileCommand;
  use App\Ci\Commands\Internal\GitCheckoutSpecificVersionCommand;
  use App\Ci\Commands\Internal\GitFetchCommitInfo;
  use App\Ci\Configuration\ConfigurationInterface;
  use App\Ci\Configuration\Profile\Profile;
  use App\Ci\Configuration\RunConfig;
  use App\Ci\Logger\LoggerFactory;
  use App\Models\Commit;
  use Monolog\Handler\HandlerInterface;


  /**
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  class ScheduledChecker implements CheckerInterface {


    /**
     * @param Commit $commit
     * @throws \Exception
     */
    public function check(\App\Models\Commit $commit, HandlerInterface $customLogHandler = null) {

      $logger = LoggerFactory::createLogger($commit);
      if ($customLogHandler !== null) {
        $logger->pushHandler($customLogHandler);
      }

      $commit->setStartValues();

      $logger->debug('start time:' . $commit->start_time);


      $commandExecutor = new CommandExecutor();

      try {


        $repositoryPath = config('ci.repo');

        if (empty($repositoryPath)) {
          throw new \Exception('Empty repository path');
        }

        if (!is_dir($repositoryPath)) {
          throw new \Exception('Repository directory does not exist: ' . $repositoryPath);
        }


        $runConfiguration = new RunConfig($commit, $logger, $repositoryPath);


        $commandExecutor->execute(new GitCheckoutSpecificVersionCommand(), $runConfiguration);
        $commandExecutor->execute(new GitFetchCommitInfo(), $runConfiguration);

        $config = $this->createConfig($runConfiguration);


        foreach ($config->getLogHandlers() as $handler) {
          $logger->pushHandler($handler);
        }


        $profile = $this->getProfile($commit, $config);

        $command = new CheckProfileCommand($profile, $commandExecutor);

        $commandExecutor->execute($command, $runConfiguration);

        $commit->status = Commit::STATUS_OK;
        $logger->info('status: ok');
      } catch (\Exception $ex) {

        $commit->status = Commit::STATUS_FAILURE;
        $logger->emergency('exception:' . $ex->getMessage());
        $logger->emergency('status: failure');
        throw $ex;

      } finally {

        $logger->info('end time:' . $commit->end_time);

        $commit->end_time = time();
        $commit->save();

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