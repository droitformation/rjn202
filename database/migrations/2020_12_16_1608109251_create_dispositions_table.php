<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositionsTable extends Migration
{
    public function up()
    {
        Schema::create('dispositions', function (Blueprint $table) {

            $table->id();
            $table->integer('loi_id');
            $table->string('cote');
            $table->text('content');
            $table->string('subdivision')->nullable();
            $table->integer('page');
            $table->integer('volume_id');

        });
    }

    public function down()
    {
        Schema::dropIfExists('dispositions');
    }
}
