<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;

  class AuthorRename extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
      Schema::table('commit', function (Blueprint $table) {
        $table->renameColumn('author', 'author_email');
      });

      Schema::table('commit', function (Blueprint $table) {
        $table->string('author_name')->default('');
      });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
      Schema::table('commit', function (Blueprint $table) {
        $table->dropColumn('author_name');
      });
      Schema::table('commit', function (Blueprint $table) {
        $table->renameColumn('author_email', 'author');
      });
    }
  }
