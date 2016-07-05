<?php

  namespace App\Ci;

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
     * @return array
     */
    public function getProfiles() {
      return $this->profiles;
    }

  }