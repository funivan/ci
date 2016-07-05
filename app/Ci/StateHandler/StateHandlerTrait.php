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


    public function getFailureHandlers() {
      return $this->failHandlers;
    }


    public function getSuccessHandlers() {
      return $this->successHandlers;
    }

  }