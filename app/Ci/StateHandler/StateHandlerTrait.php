<?php

  namespace App\Ci\StateHandler;

  use App\Ci\Commands\CommandInterface;

  /**
   *
   */
  trait StateHandlerTrait {


    /**
     * @var CommandInterface[]
     */
    private $failHandlers = [];

    /**
     * @var CommandInterface[]
     */
    private $successHandlers = [];


    public function onFailure(CommandInterface $command) {
      $this->failHandlers[] = $command;
      return $this;
    }


    public function onSuccess(CommandInterface $command) {
      $this->successHandlers[] = $command;
      return $this;
    }


    public function fireFailure() {
      foreach ($this->failHandlers as $failHandler) {
        $failHandler->execute();
      }
    }


    public function fireSuccess() {
      foreach ($this->successHandlers as $successHandler) {
        $successHandler->execute();
      }
    }

  }