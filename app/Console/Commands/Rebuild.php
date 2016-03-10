<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  /**
   *
   * @package App\Console\Commands
   */
  class Rebuild extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ci:rebuild {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add build to schedule';


    /**
     * Execute the console command
     *
     * @return mixed
     */
    public function handle() {

      /** @var Commit $build */
      $build = Commit::query()->where('id', '=', $this->argument('id'))->get()->first();
      if (!empty($build)) {
        $this->getOutput()->writeln('Cant find build with id: ' . $this->argument('id'));
        return;
      }

      $build->status = Commit::STATUS_PENDING;
      $build->save();
    }

  }
