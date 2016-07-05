<?

  namespace App\Ci\Commands;

  use Symfony\Component\Process\Process;

  /**
   *
   */
  class ShellCommand extends BaseCommand {

    /**
     * @var int
     */
    private $timeout = 7 * 60;

    /**
     * @var string
     */
    private $command;

    /**
     * @var Process
     */
    private $process;


    /**
     * @param string $command
     */
    public function __construct(string $command) {
      $this->command = $command;
    }


    public function execute() {
      $logger = $this->getRunConfig()->getLogger();

      $this->process = new Process($this->command);
      $logger->info('Start command:' . $this->command);
      $this->process->setTimeout($this->timeout);
      $this->process->setWorkingDirectory($this->getRunConfig()->getRepositoryDirectory());
      $this->process->run();


      $output = trim($this->process->getOutput());
      $logger->info($output);

      if (!$this->process->isSuccessful()) {
        $logger->emergency($this->process->getErrorOutput());
        throw new \RuntimeException('Command not successful:' . $this->command);
      }

    }


    public function getProcess() : Process {
      return $this->process;
    }


    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout) {
      $this->timeout = $timeout;
      return $this;
    }

  }
