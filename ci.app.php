<?php


  /*
  |--------------------------------------------------------------------------
  | Path to repository dir
  |--------------------------------------------------------------------------
  | In this dir script will automatically execute following git commands
  |
  | - pull
  | - merge
  | - reset
  | - checkout
  |
  |
  */

  config(['ci.repo' => __DIR__ . '/build']);

  /*
  |--------------------------------------------------------------------------
  | Path to logs dir
  |--------------------------------------------------------------------------
  */
  config(['ci.logs' => __DIR__ . '/logs']);


