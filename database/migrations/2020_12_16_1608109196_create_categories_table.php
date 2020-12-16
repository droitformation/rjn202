<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {

            $table->id();
            $table->integer('pid')->default(1);
            $table->integer('domain_id');
            $table->string('title');
            $table->text('image');
            $table->integer('deleted')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
