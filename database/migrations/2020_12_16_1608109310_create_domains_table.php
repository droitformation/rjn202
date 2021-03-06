<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {

            $table->id();
            $table->string('title');
            $table->integer('droit');
            $table->integer('sorting')->default(0);

        });
    }

    public function down()
    {
        Schema::dropIfExists('domains');
    }
}
