<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatiereNotesTable extends Migration
{
    public function up()
    {
        Schema::create('matiere_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('matiere_id');
            $table->text('content');
            $table->integer('page')->nullable();
            $table->integer('volume_id');
            $table->string('domaine')->nullable();
            $table->string('confer_externe')->nullable();
            $table->string('confer_interne')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matiere_notes');
    }
}
