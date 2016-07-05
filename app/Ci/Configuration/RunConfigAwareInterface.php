<?


  namespace App\Ci\Configuration;

  /**
   *
   */
  interface RunConfigAwareInterface {

    public function setRunConfig(RunConfig $config);


    public function getRunConfig() : RunConfig;

  }