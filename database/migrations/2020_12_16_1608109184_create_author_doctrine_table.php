<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorDoctrineTable extends Migration
{
    public function up()
    {
        Schema::create('author_doctrine', function (Blueprint $table) {

            $table->id();
            $table->integer('author_id');
            $table->integer('doctrine_id');

        });
    }

    public function down()
    {
        Schema::dropIfExists('author_doctrine');
    }
}
