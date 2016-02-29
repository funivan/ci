<?php

  namespace App\Ci;

  use App\Models\Commit;
  use Symfony\Component\Yaml\Yaml;

  /**
   * @package App\Ci
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  class Config {

    /**
     * @var array
     */
    private $profiles = [];


    /**
     * @param array $profiles
     */
    public function __construct(array $profiles) {
      $this->profiles = $profiles;
    }


    /**
     * @param string $config
     * @param Commit $commit
     * @return Config
     * @throws \Exception
     */
    public static function createFromString($config, Commit $commit = null) {


      $values = [];
      if ($commit !== null) {
        $values = $commit->getAttributes();
      }

      # prepare_variables
      foreach ($values as $name => $value) {
        $config = str_replace('%commit.' . $name . '%', $value, $config);
      }

      $data = Yaml::parse($config);

      if (empty($data['profiles'])) {
        throw new \Exception('Invalid configuration format. Empty profiles');
      }


      return new self($data['profiles']);
    }


    /**
     * @return array
     */
    public function getProfiles() {
      return $this->profiles;
    }

  }