<?

  namespace App\Ci\Logger;

  use App\Models\Commit;
  use Monolog\Formatter\JsonFormatter;
  use Monolog\Handler\StreamHandler;
  use Monolog\Logger;

  /**
   *
   */
  class LoggerFactory {

    /**
     * @param Commit $commit
     * @return Logger
     */
    public static function createLogger(\App\Models\Commit $commit) {
      $file = $commit->getLogFilePath();
      if (is_file($file)) {
        unlink($file);
      }

      # create a log channel
      $logger = new Logger('commit.check');
      $defaultLoggerStream = new StreamHandler($file, \Monolog\Logger::DEBUG);
      $defaultLoggerStream->setFormatter(new JsonFormatter());
      $logger->pushHandler($defaultLoggerStream);
      return $logger;
    }
  }
