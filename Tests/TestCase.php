<?php
  namespace Tests;

  use Illuminate\Support\Facades\DB;

  class TestCase extends \Illuminate\Foundation\Testing\TestCase {

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication() {
      $app = require __DIR__ . '/../bootstrap/app.php';
      /** @var \Illuminate\Foundation\Application $app */

      /** @var \Illuminate\Contracts\Console\Kernel $kernel */
      $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
      /** @noinspection PhpUndefinedMethodInspection */
      $kernel->bootstrap();


      if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
        $database = DB::connection()->getDatabaseName();
        if (file_exists($database)) {
          unlink($database);
        }

        touch($database);
        DB::reconnect(config('database.default'));
      }

      $kernel->call('migrate');

      return $app;
    }
  }
