<?php

  use App\Ci\Commands\MailCommand;
  use App\Ci\Commands\ShellCommand;

  /** @var \App\Ci\Configuration\RunConfig $runConfig */

  $config = new \App\Ci\Configuration\BaseConfig();

  $testProfile = $config->createProfile('test');

  $testProfile->addCommand(new ShellCommand('composer install'));
  $testProfile->addCommand(new ShellCommand('./vendor/bin/phpunit --stop-on-error'));

  $testProfile->onFailure(new MailCommand($commit->author_email, 'Build failure', 'See:' . route('viewCommitInfo', ['id'=>$runConfig->getCommit()->id])));
