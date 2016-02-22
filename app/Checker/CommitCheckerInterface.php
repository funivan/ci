<?php

  namespace App\Checker;

  /**
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  interface CommitCheckerInterface {

    /**
     * @param \App\Models\Commit $commit
     */
    public function check(\App\Models\Commit $commit);

  }