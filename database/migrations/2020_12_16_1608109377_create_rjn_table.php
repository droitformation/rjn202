<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRjnTable extends Migration
{
    public function up()
    {
        Schema::create('rjn', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('publication_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rjn');
    }
}
