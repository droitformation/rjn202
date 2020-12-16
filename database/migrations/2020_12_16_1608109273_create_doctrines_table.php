<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctrinesTable extends Migration
{
    public function up()
    {
        Schema::create('doctrines', function (Blueprint $table) {

            $table->id();
            $table->integer('pid');
            $table->string('titre');
            $table->integer('volume_id');
            $table->integer('domain_id');
            $table->integer('page');
            $table->datetime('pub_date');
            ;
            $table->text('bibliographie');
            $table->text('notes');
            $table->text('citations');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('doctrines');
    }
}
