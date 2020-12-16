<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersRolesTable extends Migration
{
    public function up()
    {
        Schema::create('users_roles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('role_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_roles');
    }
}
