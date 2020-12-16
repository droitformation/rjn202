<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArretGroupesTable extends Migration
{
    public function up()
    {
        Schema::create('arret_groupes', function (Blueprint $table) {
            $table->id();
            $table->integer('groupe_id');
            $table->integer('arret_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('arret_groupes');
    }
}
