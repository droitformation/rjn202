<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoisTable extends Migration
{
    public function up()
    {
        Schema::create('lois', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sigle')->nullable();
            $table->integer('droit');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lois');
    }
}
