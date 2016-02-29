<?php

  namespace Tests\Ci;

  use App\Ci\Config;
  use App\Models\Commit;
  use Tests\TestCase;

  /**
   * @package Tests\Ci
   * @author Ivan Shcherbak <dev@funivan.com> 2016
   */
  class ConfigTest extends TestCase {


    public function testParseConfig() {
      $commit = new Commit();
      $commit->hash = 'ch3434';

      $config = Config::createFromString('
profiles :
  test :
    - ./custom-tool %commit.hash%
', $commit);

      $this->assertCount(1, $config->getProfiles());
      $this->assertEquals('./custom-tool ch3434', $config->getProfiles()['test'][0]);

    }
  }
