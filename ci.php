<?php

  use App\Ci\Commands\MailCommand;
  use App\Ci\Commands\ShellCommand;

  /** @var \App\Ci\Configuration\RunConfig $runConfig */
  $commit = $runConfig->getCommit();

  $config = new \App\Ci\Configuration\BaseConfig();

  $testProfile = $config->createProfile('test');

  $testProfile->addCommand(new ShellCommand('composer install'));
  $testProfile->addCommand(new ShellCommand('./vendor/bin/phpunit --stop-on-error --tap'));

  $testProfile->onFailure(new MailCommand($commit->author_email, 'Build failure', 'See:' . route('viewCommitInfo', ['id' => $commit->id])));

  return $config;