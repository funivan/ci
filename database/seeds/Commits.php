<?php

  use App\Models\Commit;
  use Faker\Factory;
  use Illuminate\Database\Seeder;

  class Commits extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

      for ($i = 0; $i < 10; $i++) {
        $faker = Factory::create();
        $commit = new Commit();
        $commit->status = $faker->randomElement([
          Commit::STATUS_PENDING,
          Commit::STATUS_OK,
          Commit::STATUS_IN_PROGRESS,
          Commit::STATUS_FAILURE,
        ]);
        $commit->hash = $faker->hexColor;
        $commit->branch = $faker->randomElement(['master', 'u-text']);
        $commit->profile = $faker->randomElement(['test', 'consistency', '']);
        $commit->author_email = $faker->email;
        $commit->start_time = $faker->unixTime;
        $commit->message = $faker->realText(random_int(10, 60));
        $commit->end_time = $faker->unixTime;
        $commit->save();
      }

    }
  }
