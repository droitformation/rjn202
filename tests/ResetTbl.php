<?php namespace Tests;

trait ResetTbl
{

    function reset_all(){

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('users')->truncate();
        \DB::table('codes')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
