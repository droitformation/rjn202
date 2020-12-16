<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbreviationsTable extends Migration
{
    public function up()
    {
        Schema::create('abreviations', function (Blueprint $table) {
            $table->id();
            $table->string('sigle');
            $table->string('title');
        });
    }

    public function down()
    {
        Schema::dropIfExists('abreviations');
    }
}
