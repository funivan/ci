<?

  namespace App\Ci\Commands;

  use App\Ci\Configuration\RunConfig;
  use App\Ci\Configuration\RunConfigAwareInterface;
  use App\Ci\StateHandler\StateHandlerInterface;

  /**
   *
   */
  class CommandExecutor {

    public function execute(CommandInterface $command, RunConfig $config) {

      if ($command instanceof RunConfigAwareInterface) {
        $command->setRunConfig($config);
      }

      if ($command instanceof StateHandlerInterface) {
        try {
          $command->execute();
          foreach ($command->getSuccessHandlers() as $successHandler) {
            $this->execute($successHandler, $config);
          }

        } catch (\Exception $e) {
          foreach ($command->getFailureHandlers() as $failureHandler) {
            $this->execute($failureHandler, $config);
          }
          throw $e;
        }
        return;
      }

      $command->execute();
    }

  }
