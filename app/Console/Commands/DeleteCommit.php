<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  class DeleteCommit extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:delete-commit {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove commit from the queue';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
      parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
      $id = $this->argument('id');
      /** @var Commit $commit */
      $commit = Commit::query()->where('id', '=', $id)->get()->first();
      if (empty($commit)) {
        $this->getOutput()->writeln('<error>Cant find commit with id:' . $id . '</error>');
        return;
      }
      $commit->delete();
    }
  }
