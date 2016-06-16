<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  class CleanLogs extends Command {

    /**
     * @var string
     */
    protected $signature = 'ci:clean-logs {--dry-run}';

    /**
     * @var string
     */
    protected $description = 'Clean old logs';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
      // older than 10 days
      $builder = Commit::query();
      $builder->where('end_time', '<', time() - (10 * 24 * 3600));
      $commits = $builder->get();
      /** @var Commit $commit */
      foreach ($commits as $commit) {
        $file = $commit->getLogFilePath();

        $this->info('Remove:' . $file);

        if ($this->option('dry-run')) {
          continue;
        }

        if (is_file($file)) {
          unlink($file);
        }
      }

    }
  }
