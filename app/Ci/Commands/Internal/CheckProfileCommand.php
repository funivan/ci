<?

  namespace App\Ci\Commands\Internal;

  use App\Ci\Commands\BaseCommand;
  use App\Ci\Commands\CommandExecutor;
  use App\Ci\Configuration\Profile\Profile;

  /**
   *
   */
  class CheckProfileCommand extends BaseCommand {

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var CommandExecutor
     */
    private $executor;


    /**
     * @param Profile $profile
     */
    public function __construct(Profile $profile, CommandExecutor $executor) {
      $this->profile = $profile;

      foreach ($profile->getSuccessHandlers() as $successHandler) {
        $this->onSuccess($successHandler);
      }

      foreach ($profile->getFailureHandlers() as $failureHandler) {
        $this->onFailure($failureHandler);
      }

      $this->executor = $executor;
    }


    public function execute() {
      $config = $this->getRunConfig();

      foreach ($this->profile->getCommands() as $command) {
        $this->executor->execute($command, $config);
      }

    }

  }
