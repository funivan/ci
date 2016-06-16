<?php

  namespace App\Console\Commands;

  use App\Models\Commit;
  use Illuminate\Console\Command;

  class CleanBuilds extends Command {

    const DAYS = 60;

    /**
     * @var string
     */
    protected $signature = 'ci:clean-builds {--dry-run}';

    /**
     * @var string
     */
    protected $description = 'Clean old build';


    /**
     * @inheritdoc
     */
    public function handle() {
      // older than 10 days
      $builder = Commit::query();
      $builder->where('end_time', '<', time() - (self::DAYS * 24 * 3600));
      $builder->where('end_time', '!=', '0');
      $commits = $builder->get();
      /** @var Commit $commit */
      foreach ($commits as $commit) {

        if ($this->option('dry-run')) {
          $this->comment('Remove:' . $commit->id);
          continue;
        }

        $this->info('Remove:' . $commit->id);
      }

    }
  }
