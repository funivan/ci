<?

  namespace App\Ci\Configuration;

  use App\Ci\Configuration\Profile\Profile;
  use Monolog\Handler\HandlerInterface;

  /**
   *
   */
  class BaseConfig implements ConfigurationInterface {

    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    /**
     * @var Profile
     */
    private $profiles;


    public function addLogHandler(HandlerInterface $handler) : BaseConfig {
      $this->handlers[] = $handler;
      return $this;
    }


    /**
     * @return HandlerInterface[]
     */
    public function getLogHandlers() {
      return $this->handlers;
    }


    public function createProfile(string $name)  : Profile {
      $profile = new Profile($name);
      $this->addProfile($profile);
      return $profile;
    }


    public function addProfile(Profile $profile)  : BaseConfig {
      $this->profiles[] = $profile;
      return $this;
    }


    public function getProfiles() {
      return $this->profiles;
    }


  }
