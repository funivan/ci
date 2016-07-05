<?
  namespace App\Ci\Configuration;

  use App\Ci\Configuration\Profile\Profile;
  use Monolog\Handler\HandlerInterface;


  /**
   *
   */
  interface ConfigurationInterface {

    /**
     * @return HandlerInterface[]
     */
    public function getLogHandlers();


    /**
     * @return Profile[]
     */
    public function getProfiles();

  }