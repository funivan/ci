<?

  namespace App\Ci\Configuration;

  use App\Models\Commit;
  use Psr\Log\LoggerInterface;

  /**
   *
   */
  class RunConfig {

    /**
     * @var Commit
     */
    private $commit;

    /**
     * @var string
     */
    private $repositoryDirectory;

    /**
     * @var LoggerInterface
     */
    private $logger;


    public function __construct(Commit $commit, LoggerInterface $logger, string $repositoryDirectory) {
      $this->commit = $commit;
      $this->repositoryDirectory = $repositoryDirectory;
      $this->logger = $logger;
    }


    public function getCommit() : Commit {
      return $this->commit;
    }


    public function getLogger() : LoggerInterface {
      return $this->logger;
    }


    public function getRepositoryDirectory()  : string {
      return $this->repositoryDirectory;
    }


  }

