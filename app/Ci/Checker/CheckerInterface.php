<?php

  namespace App\Ci\Checker;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  interface CheckerInterface {

    /**
     * @param \App\Models\Commit $commit
     */
    public function check(\App\Models\Commit $commit);

  }