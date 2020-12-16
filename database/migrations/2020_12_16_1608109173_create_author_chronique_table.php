<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorChroniqueTable extends Migration
{
    public function up()
    {
        Schema::create('author_chronique', function (Blueprint $table) {

            $table->id();
            $table->integer('author_id');
            $table->integer('chronique_id');
            $table->enum('role',['editeur','auteur','contributeur']);

        });
    }

    public function down()
    {
        Schema::dropIfExists('author_chronique');
    }
}
