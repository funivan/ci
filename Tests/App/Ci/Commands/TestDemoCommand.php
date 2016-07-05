<?

  namespace Tests\App\Ci\Commands;

  use App\Ci\Commands\BaseCommand;

  /**
   *
   */
  class TestDemoCommand extends BaseCommand {

    /**
     * @var callable
     */
    private $callback;

    private $isExecutionEnded = false;

    private $isExecutionStarted = false;


    /**
     * @param callable $callback
     */
    public function __construct(callable $callback = null) {
      if ($callback === null) {
        $callback = function () {
        };
      }
      $this->callback = $callback;
    }


    public function execute() {
      $this->isExecutionStarted = true;

      $this->isStartExecution = true;
      $callback = $this->callback;
      $callback();

      $this->isExecutionEnded = true;
    }


    public function getIsExecutionEnded()  : bool {
      return $this->isExecutionEnded;
    }


    public function getIsExecutionStarted()  : bool {
      return $this->isExecutionStarted;
    }


  }
