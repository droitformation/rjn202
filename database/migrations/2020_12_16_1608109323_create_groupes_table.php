<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupesTable extends Migration
{
    public function up()
    {
        Schema::create('groupes', function (Blueprint $table) {

            $table->id();
            $table->string('titre');
            $table->integer('domain_id');
            $table->integer('categorie_id')->nullable();
            $table->integer('volume_id');

        });
    }

    public function down()
    {
        Schema::dropIfExists('groupes');
    }
}
