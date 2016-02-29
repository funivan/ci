<?php
  namespace Tests;

  class ExampleTest extends \Tests\TestCase {

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample() {
      $this->visit('/')
        ->see('No commits');
    }
  }
