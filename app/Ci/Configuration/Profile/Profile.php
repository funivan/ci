<?

  namespace App\Ci\Configuration\Profile;

  use App\Ci\Commands\CommandInterface;
  use App\Ci\Configuration\RunConfig;
  use App\Ci\Configuration\RunConfigAwareInterface;
  use App\Ci\StateHandler\StateHandlerInterface;
  use App\Ci\StateHandler\StateHandlerTrait;

  /**
   *
   */
  class Profile implements StateHandlerInterface, RunConfigAwareInterface {

    use StateHandlerTrait;

    /**
     * @var string
     */
    private $name;


    /**
     *
     * @var CommandInterface[]
     */
    private $commands = [];

    /**
     * @var RunConfig
     */
    private $runConfig;


    /**
     * @param string $name
     */
    public function __construct(string $name) {
      $this->name = $name;
    }


    public function getName() : string {
      return $this->name;
    }


    /**
     * @return \App\Ci\Commands\CommandInterface[]
     */
    public function getCommands() {
      return $this->commands;
    }


    /**
     * @param CommandInterface $command
     * @return $this
     */
    public function addCommand(CommandInterface $command) {
      $this->commands[] = $command;
      return $this;
    }


    public function setRunConfig(RunConfig $config) {
      $this->runConfig = $config;
      return $this;
    }


    public function getRunConfig() : RunConfig {
      return $this->runConfig;
    }

  }
