<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Droit\User\Entities\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'     => $faker->name,
        'email'    => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'role'     => 'abonne',
        'numero'   => $faker->randomDigit
    ];
});

$factory->define(App\Droit\Code\Entities\Code::class, function (Faker\Generator $faker) {

    return [
        'code'      => $faker->randomLetter,
        'valid_at'  => \Carbon\Carbon::tomorrow()->toDateString(),
        'used'      => null,
        'user_id'   => null,
    ];
});

$factory->define(App\Droit\User\Entities\Role::class, function (Faker\Generator $faker) {
    return ['name' => $faker->name];
});


$factory->define(App\Droit\Arret\Entities\Arret::class, function (Faker\Generator $faker) {
    return [
        'pid'         => 1,
        'designation' => $faker->sentence(10),
        'volume_id'   => 6,
        'groupe'      => null,
        'domain_id'   => 1,
        'page'        => $faker->randomNumber(3),
        'pub_date'    => $faker->dateTime,
        'cotes'       => 'Art. 123 al. 45',
        'sommaire'    => $faker->text(300),
        'portee'      => $faker->text(300),
        'faits'       => null,
        'considerant' => null,
        'droit'       => null,
        'conclusion'  => null,
        'note'        => null,
    ];
});

$factory->define(App\Droit\Chronique\Entities\Chronique::class, function (Faker\Generator $faker) {
    return [
        'pid'          => 1,
        'domain_id'    => 1,
        'sorting'      => 1,
        'volume_id'    => 6,
        'page'         => $faker->randomNumber(3),
        'pub_date'     => $faker->dateTime,
        'titre'        => $faker->name,
        'faits'        => null,
        'sommaire'     => $faker->text(300),
        'commentaires' => $faker->sentence(10),
        'citations'    => null,
    ];
});

$factory->define(App\Droit\Disposition\Entities\Disposition::class, function (Faker\Generator $faker) {
    return [
        'volume_id'    => 6,
        'page'         => $faker->randomNumber(3),
        'loi_id'       => $faker->randomNumber(3),
        'cote'         => $faker->name,
        'content'      => null,
        'subdivision'  => null,
    ];
});

$factory->define(App\Droit\Disposition\Entities\Disposition_page::class, function (Faker\Generator $faker) {
    return [
        'volume_id'      => 6,
        'page'           => $faker->randomNumber(3),
        'alinea'         => $faker->name,
        'chiffre'        => $faker->name,
        'lettre'         => $faker->name,
        'disposition_id' => 1,
    ];
});

$factory->define(App\Droit\Domain\Entities\Domain::class, function (Faker\Generator $faker) {
    return [
        'title'   => $faker->name,
        'droit'   => 1,
        'sorting' => 1,
    ];
});

$factory->define(App\Droit\Loi\Entities\Loi::class, function (Faker\Generator $faker) {
    return [
        'name'    => $faker->name,
        'sigle'   => $faker->name,
        'droit' => 1,
    ];
});

$factory->define(App\Droit\Matiere\Entities\Matiere::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
    ];
});

$factory->define(App\Droit\Matiere\Entities\Matiere_note::class, function (Faker\Generator $faker) {
    return [
        'matiere_id'     => 6,
        'volume_id'      => 6,
        'content'        => $faker->name,
        'page'           => $faker->randomNumber(3),
        'domaine'        => $faker->name,
        'confer_externe' => $faker->name,
        'confer_interne' => $faker->name,
    ];
});

$factory->define(App\Droit\Matiere\Entities\Matiere_note::class, function (Faker\Generator $faker) {
    return [
        'note_id'     => 6,
        'volume_id'   => 6,
        'page'        => $faker->randomNumber(3),
    ];
});

$factory->define(App\Droit\Rjn\Entities\Rjn::class, function (Faker\Generator $faker) {
    return [
        'title' => 'RJN',
        'publication_at' => \Carbon\Carbon::today()->toDateString()
    ];
});

