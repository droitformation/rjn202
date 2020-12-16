<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArretsTable extends Migration
{
    public function up()
    {
        Schema::create('arrets', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->integer('pid');
            $table->integer('volume_id');
            $table->integer('domain_id');
            $table->integer('page');
            $table->integer('deleted')->nullable();
            $table->string('cotes');
            $table->datetime('pub_date');
            $table->text('sommaire');
            $table->text('portee')->nullable();
            $table->text('faits')->nullable();
            $table->text('considerant')->nullable();
            $table->text('droit')->nullable();
            $table->text('conclusion')->nullable();
            $table->text('note')->nullable();
            $table->integer('groupe')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arrets');
    }
}
