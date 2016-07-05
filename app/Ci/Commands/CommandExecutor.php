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
          $command->fireSuccess();
        } catch (\Exception $e) {
          $command->fireFailure();
          throw $e;
        }
        return;
      }

      $command->execute();
    }

  }
