<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {

            $table->id();
            $table->string('titre')->nullable();
            $table->text('contenu');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->string('slug')->nullable();
            $table->enum('type',['pub','texte','soutien']);
            $table->enum('position',['sidebar','home-bloc','home-colonne']);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
