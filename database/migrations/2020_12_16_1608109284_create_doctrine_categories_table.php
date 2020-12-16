<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctrineCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('doctrine_categories', function (Blueprint $table) {

            $table->id();
            $table->integer('doctrine_id')->unsigned();
            $table->integer('categorie_id')->unsigned();
            $table->integer('sorting')->default(0);

        });
    }

    public function down()
    {
        Schema::dropIfExists('doctrine_categories');
    }
}
