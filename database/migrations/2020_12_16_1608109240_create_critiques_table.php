<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCritiquesTable extends Migration
{
    public function up()
    {
        Schema::create('critiques', function (Blueprint $table) {

            $table->id();
            $table->enum('type',['arret','article','chronique']);
            $table->integer('item_id');
            $table->integer('author_id');
            $table->string('titre');

        });
    }

    public function down()
    {
        Schema::dropIfExists('critiques');
    }
}
