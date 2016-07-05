<?php

  namespace Tests\App\Ci\Commands;

  use App\Ci\Commands\CommandExecutor;
  use App\Ci\Commands\CommandInterface;
  use App\Ci\Configuration\RunConfig;
  use App\Models\Commit;
  use Psr\Log\NullLogger;

  /**
   *
   */
  class CommandExecutorTest extends \Tests\TestCase {

    public function testSimpleRun() {

      $command = new TestDemoCommand();
      self::assertFalse($command->getIsExecutionEnded());

      $this->execute($command);

      self::assertTrue($command->getIsExecutionEnded());
    }


    public function testSuccessCallback() {

      $successCommand = new TestDemoCommand();
      $failureCommand = new TestDemoCommand();

      $command = new TestDemoCommand();
      $command->onSuccess($successCommand);
      $command->onFailure($failureCommand);

      self::assertFalse($command->getIsExecutionEnded());
      self::assertFalse($successCommand->getIsExecutionEnded());
      self::assertFalse($failureCommand->getIsExecutionEnded());

      $this->execute($command);

      self::assertTrue($command->getIsExecutionEnded());
      self::assertTrue($successCommand->getIsExecutionEnded());
      self::assertFalse($failureCommand->getIsExecutionEnded());

    }


    public function testFailureCallback() {

      $successCommand = new TestDemoCommand();
      $failureCommand = new TestDemoCommand();

      $command = new TestDemoCommand(function () {
        throw new \Exception('Test');
      });
      $command->onSuccess($successCommand);
      $command->onFailure($failureCommand);

      self::assertFalse($command->getIsExecutionStarted());
      self::assertFalse($command->getIsExecutionEnded());
      self::assertFalse($successCommand->getIsExecutionEnded());
      self::assertFalse($failureCommand->getIsExecutionEnded());

      $exception = null;
      try {
        $this->execute($command);
      } catch (\Exception $exception) {

      }

      self::assertNotEmpty($exception);

      self::assertTrue($command->getIsExecutionStarted());
      self::assertFalse($command->getIsExecutionEnded());
      self::assertFalse($successCommand->getIsExecutionEnded());
      self::assertTrue($failureCommand->getIsExecutionEnded());

    }


    private function execute(CommandInterface $command) {
      (new CommandExecutor())->execute($command, new RunConfig(new Commit(), new NullLogger(), '/tmp'));
    }


  }
