<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodeTest extends TestCase
{
    public function testNotLoggued()
    {
        $response = $this->get('matiere');
        $response->assertRedirect('login');
    }

    public function testUserWithNoValidCode()
    {
        $user = factory(\App\Droit\User\Entities\User::class)->create();
        $code = factory(\App\Droit\Code\Entities\Code::class)->create([
            'user_id'  => $user->id,
            'valid_at' => \Carbon\Carbon::yesterday()->toDateString(),
        ]);

        $this->actingAs($user);

        $response = $this->get( 'matiere');
        $response->assertRedirect('activate');
    }

    public function testUserWithValidCode()
    {
        $arret = factory(\App\Droit\Arret\Entities\Arret::class)->create();
        $user  = factory(\App\Droit\User\Entities\User::class)->create();
        $code  = factory(\App\Droit\Code\Entities\Code::class)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get('arret/'.$arret);

        $response->assertStatus(200);
    }

    public function testRegisterWithCodeOk()
    {
        \DB::table('users')->truncate();
        \DB::table('codes')->truncate();

        $code = factory(\App\Droit\Code\Entities\Code::class)->create(['code' => 'cindy']);

        $response = $this->call('GET', 'code')->assertStatus(200);
        $response = $this->call('POST', 'postCode', ['code' => 'cindy', 'name' => 'Cindy Leschaud', 'email' => 'info@leschaud.ch', 'password' => 'wsdew23cdds']);

        $this->assertDatabaseHas('users', [
            'name'  => 'Cindy Leschaud',
            'email' => 'info@leschaud.ch'
        ]);

        $this->assertDatabaseHas('codes', [
            'id'   => $code->id,
            'used' => 1
        ]);

        $response->assertRedirect('/');
    }

    public function testReactiveAccount()
    {
        \DB::table('users')->truncate();
        \DB::table('codes')->truncate();

        $user = factory(\App\Droit\User\Entities\User::class)->create([
            'email' => 'info@leschaud.ch',
            'password' => 'wsdew23cdds'
        ]);

        $code = factory(\App\Droit\Code\Entities\Code::class)->create(['code' => 'cindy']);

        $response = $this->call('GET', 'activate')->assertStatus(200);
        $response = $this->call('POST', 'postActivate', ['code' => 'cindy', 'email' => 'info@leschaud.ch', 'password' => 'wsdew23cdds']);

        $this->assertDatabaseHas('codes', [
            'id'   => $code->id,
            'used' => 1
        ]);

    }
}
