<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositionPagesTable extends Migration
{
    public function up()
    {
        Schema::create('disposition_pages', function (Blueprint $table) {

            $table->id();
            $table->string('alinea')->nullable();
            $table->string('chiffre')->nullable();
            $table->string('lettre')->nullable();
            $table->integer('disposition_id');
            $table->integer('volume_id');
            $table->integer('page');

        });
    }

    public function down()
    {
        Schema::dropIfExists('disposition_pages');
    }
}
