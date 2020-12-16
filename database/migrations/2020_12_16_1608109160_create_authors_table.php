<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorsTable extends Migration
{
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {

            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('occupation');

        });
    }

    public function down()
    {
        Schema::dropIfExists('authors');
    }
}
