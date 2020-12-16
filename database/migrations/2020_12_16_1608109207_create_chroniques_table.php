<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChroniquesTable extends Migration
{
    public function up()
    {
        Schema::create('chroniques', function (Blueprint $table) {

            $table->id();
            $table->integer('pid');
            $table->integer('volume_id');
            $table->integer('domain_id');
            $table->integer('page');
            $table->integer('sorting');
            $table->string('titre');
            $table->datetime('pub_date');
            $table->text('faits');
            $table->text('commentaires');
            $table->text('citations');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('chroniques');
    }
}
