<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  class Rerun extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:rerun {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add commit back to the queue';


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

      $commit->status = Commit::STATUS_PENDING;
      $commit->save();
    }
  }
