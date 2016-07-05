<?

  namespace App\Ci\Configuration;

  use App\Ci\Configuration\Profile\Profile;
  use Monolog\Handler\HandlerInterface;

  /**
   *
   */
  class NullableConfig implements ConfigurationInterface {

    /**
     * @return HandlerInterface[]
     */
    public function getLogHandlers() {
      return [];
    }


    /**
     * @return Profile[]
     */
    public function getProfiles() {
      return [];
    }

  }
