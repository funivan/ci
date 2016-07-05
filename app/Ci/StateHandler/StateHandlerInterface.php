<?

  namespace App\Ci\StateHandler;

  use App\Ci\Commands\CommandInterface;

  /**
   *
   */
  interface StateHandlerInterface {

    public function onFailure(CommandInterface $command);


    public function onSuccess(CommandInterface $command);


    public function getFailureHandlers();


    public function getSuccessHandlers();

  }
