<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;

  class MakeHashColumnToNonUniquie extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
      Schema::table('commit', function (Blueprint $table) {
        $table->dropUnique('commit_hash_unique');
      });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
      Schema::table('commit', function (Blueprint $table) {
        $table->unique('hash', 'commit_hash_unique');
      });
    }
  }
