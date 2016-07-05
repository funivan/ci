<?

  namespace App\Ci\Commands\Internal;

  use App\Ci\Commands\BaseCommand;
  use App\Ci\Commands\CommandExecutor;
  use App\Ci\Commands\ShellCommand;

  /**
   *
   */
  class GitFetchCommitInfo extends BaseCommand {

    public function execute() {

      # store info from commit

      $config = $this->getRunConfig();
      $commit = $config->getCommit();


      $gitRetrieveInfo = new ShellCommand('git log --format=%ae:::::%an:::::%s -n 1 ' . $commit->hash);

      (new CommandExecutor())->execute($gitRetrieveInfo, $config);

      $info = trim($gitRetrieveInfo->getProcess()->getOutput());
      list($email, $name, $message) = explode(":::::", $info);

      $commit->message = $message;
      $commit->author_email = $email;
      $commit->author_name = $name;
      $commit->save();

    }

  }
