<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  class AddCommit extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:add-commit {branch} {hash}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add specific commit to the queue';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
      $commit = new Commit();
      $commit->status = Commit::STATUS_PENDING;
      $commit->branch = $this->argument('branch');
      $commit->hash = $this->argument('hash');
      $commit->start_time = time();
      $commit->end_time = 0;
      $commit->save();

      $this->getOutput()->writeln('Added. Id: ' . $commit->id);
    }
  }
