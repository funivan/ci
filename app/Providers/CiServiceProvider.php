<?php

  namespace App\Providers;


  /**
   * @package App\Providers
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  class CiServiceProvider extends AppServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
      /** @noinspection PhpIncludeInspection */
      require_once realpath(base_path('/ci.app.php'));
    }

  }