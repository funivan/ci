<?

  namespace App\Ci\Commands\Internal;

  use App\Ci\Commands\BaseCommand;
  use App\Ci\Commands\CommandExecutor;
  use App\Ci\Commands\ShellCommand;

  /**
   *
   */
  class GitCheckoutSpecificVersionCommand extends BaseCommand {


    public function execute() {
      $config = $this->getRunConfig();
      $commit = $config->getCommit();

      $executor = new CommandExecutor();

      $executor->execute(
        new ShellCommand('git fetch --all && git reset --hard origin/' . $commit->branch),
        $config
      );

      $executor->execute(
        new ShellCommand('git checkout ' . $commit->hash),
        $config
      );;


      $commitFetcherCommand = new ShellCommand('git rev-parse HEAD');
      $executor->execute($commitFetcherCommand, $config);

      $commitId = trim($commitFetcherCommand->getProcess()->getOutput());

      if (trim($commitId) != $commit->hash) {
        throw new \Exception('Can not checkout specific commit:' . $commit->hash);
      }

    }

  }
