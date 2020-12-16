<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArretCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('arret_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('arret_id')->unsigned();
            $table->integer('categorie_id')->unsigned();
            $table->integer('sorting')->nullable()->default(null);
        });
    }

    public function down()
    {
       // Schema::dropIfExists('arret_categories');
    }
}
