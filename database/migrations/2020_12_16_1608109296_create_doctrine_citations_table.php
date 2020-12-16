<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctrineCitationsTable extends Migration
{
    public function up()
    {
        Schema::create('doctrine_citations', function (Blueprint $table) {
            $table->id();
            $table->integer('doctrine_id');
            $table->integer('chronique_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctrine_citations');
    }
}
