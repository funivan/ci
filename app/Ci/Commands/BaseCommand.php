<?

  namespace App\Ci\Commands;

  use App\Ci\Configuration\RunConfig;
  use App\Ci\Configuration\RunConfigAwareInterface;
  use App\Ci\StateHandler\StateHandlerInterface;
  use App\Ci\StateHandler\StateHandlerTrait;

  /**
   *
   */
  abstract class BaseCommand implements CommandInterface, StateHandlerInterface, RunConfigAwareInterface {

    use StateHandlerTrait;

    /**
     * @var RunConfig
     */
    private $runConfiguration;


    public function setRunConfig(RunConfig $config) {
      $this->runConfiguration = $config;
    }


    public function getRunConfig() : RunConfig {
      return $this->runConfiguration;
    }


  }
