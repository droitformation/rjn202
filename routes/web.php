<?php

Route::get('/', 'Frontend\HomeController@index');
Route::get('contact', 'Frontend\HomeController@contact');
Route::get('historique', 'Frontend\HomeController@historique');
Route::get('colloque', 'Frontend\HomeController@colloque');

// Contact
Route::post('sendMessage', 'Frontend\HomeController@sendMessage');
Route::post('ajax/articles', 'Frontend\SearchController@articles');
Route::post('ajax/notes', 'Frontend\SearchController@notes');

Route::group(['middleware' => ['auth','abonne']], function()
{
    Route::get('jurisprudence', 'Frontend\HomeController@jurisprudence');

    Route::match(['get', 'post'],'domain/{domain}/{volume_id?}', 'Frontend\HomeController@domain');
    Route::match(['get', 'post'],'categorie/{categorie}/{volume_id?}', 'Frontend\HomeController@categorie');

    Route::get('doctrine/{current?}', 'Frontend\HomeController@doctrine');
    Route::get('arret/{id}', 'Frontend\HomeController@arret');
    Route::get('article/{id}', 'Frontend\HomeController@article');
    Route::get('chronique/{id}', 'Frontend\HomeController@chronique');
    Route::get('matiere/{alpha?}', 'Frontend\HomeController@matiere');
    Route::get('lois', 'Frontend\HomeController@lois');
    Route::get('disposition/{id}', 'Frontend\HomeController@disposition');

    // Redirect to content from page
    Route::get('page/{page}/{volume}/{path}', 'Frontend\HomeController@page');

    // Filter and search content
    Route::post('filter', 'Frontend\HomeController@filter');
    Route::get('search/matieres', 'Frontend\SearchController@matieres');
    Route::get('search/lois', 'Frontend\SearchController@lois');

    // Search routes
    Route::match(['get', 'post'], 'search','Frontend\SearchController@index');
    Route::match(['get', 'post'], 'terms', 'Frontend\SearchController@searching');

});

// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth','admini']], function()
{
    Route::get('/', 'Backend\AdminController@index');
    Route::resource('author', 'Backend\AuthorController');
    Route::resource('arret', 'Backend\ArretController');
    Route::resource('groupe', 'Backend\GroupeController');
    Route::resource('article', 'Backend\ArticleController');
    Route::resource('chronique', 'Backend\ChroniqueController');
    Route::resource('critique', 'Backend\CritiqueController');
    Route::resource('matiere', 'Backend\MatiereController');
    Route::resource('domain', 'Backend\DomainController');
    Route::resource('code', 'Backend\CodeController');
    Route::resource('user', 'Backend\UserController');
    Route::resource('categorie', 'Backend\CategorieController');
    Route::get('note/create/{matiere_id}', 'Backend\NoteController@create');
    Route::get('note/matiere/{id}', 'Backend\NoteController@matiere');
    Route::resource('note', 'Backend\NoteController');
    Route::get('disposition/create/{loi_id}', 'Backend\DispositionController@create');
    Route::get('disposition/loi/{id}', 'Backend\DispositionController@loi');
    Route::get('disposition/page/{id}', 'Backend\DispositionController@page');
    Route::post('disposition/addpage', 'Backend\DispositionController@addpage');
    Route::post('disposition/storeAjax', 'Backend\DispositionController@storeAjax');
    Route::resource('disposition', 'Backend\DispositionController');
    Route::resource('loi', 'Backend\LoiController');
    Route::get('lists/{id}', 'Backend\CategorieController@lists');

    // Ajax calls
    Route::get('api/arret', 'Backend\ArretController@arrets');
    Route::get('api/article', 'Backend\ArticleController@articles');
    Route::get('api/chronique', 'Backend\ChroniqueController@chroniques');

});

// Logout routes
Route::get('/logout', function()
{
    Auth::logout();
    return redirect('/');
});

Route::get('code', 'Auth\LoginController@getCode');
Route::get('activate', 'Auth\LoginController@getActivate');
Route::post('postCode', 'Auth\LoginController@postCode');
Route::post('postActivate', 'Auth\LoginController@postActivate');


// Test routes for development
Route::get('testing', function()
{

  /*  $model = new \App\Droit\Matiere\Entities\Matiere();
    $model_note = \App::make('App\Droit\Matiere\Repo\MatiereNoteInterface');
    $model_note_page = new \App\Droit\Matiere\Entities\Matiere_note_page();

    $matieres = $model_note->getAll();

     $fillable = ['matiere_id','volume_id','content','page','domaine','confer_externe','confer_interne'];

    $matieres = $matieres->groupBy(function ($item, $key) {
        return $item->matiere->title;
    })->mapWithKeys(function ($matiere, $key) {
        return [
            $key => $matiere->map(function ($note, $key) use($matiere) {
                return[
                    'content'        => $note->content,
                    'page'           => $note->page,
                    'volume_id'      => $note->volume_id,
                    'domaine'        => $note->domaine,
                    'confer_externe' => $note->confer_externe,
                    'confer_interne' => $note->confer_interne,
                    'notes' => $note->note_pages
                ];
            }),
        ];
    });



    echo '<pre>';
    print_r($matieres);
    echo '</pre>';
    exit;
    // $model = \App::make('App\Droit\Disposition\Repo\DispositionInterface');
    //$result = $model->newsearch(['loi' => 10, 'article' => 23]);
    $model = \App::make('App\Droit\Loi\Repo\LoiInterface');
    $l = $model->find(13);

    $model = \App::make('App\Droit\Disposition\Repo\DispositionInterface');

    $results = $model->newsearch(['loi_id' => 203, 'article' => 8]);

    $results = $results->pluck('disposition_pages')
        ->flatten(1)
        ->unique(function ($item) {
            return $item->resume;
        })
        ->map(function ($disposition) {
        return [
            'id'    => $disposition->id,
            'text'  => $disposition->resume
              'other' => [
                    'alinea'    => $disposition->alinea,
                    'chiffre'   => $disposition->chiffre,
                    'lettre'    => $disposition->lettre,
                    'page'      => $disposition->page,
                    'volume_id' => $disposition->volume_id,
                ]
        ];
    })->reject(function ($item) {
        return empty($item['text']);
    })->values()->toArray();


  echo '<pre>';
        print_r($results);
        echo '</pre>';
        exit;

    $lois = $model->getAllSigle();

    $grouped = $lois->mapWithKeys(function ($loi) {
        return [
            $loi->id => $loi->dispositions->mapToGroups(function ($disposition, $key) use($loi) {
                return [
                    trim($disposition->cote) => [
                        'pages' => $disposition->disposition_pages->map(function ($item, $key) {
                            return [
                                'alinea'  => $item->alinea,
                                'chiffre' => $item->chiffre,
                                'lettre'  => $item->lettre
                            ];
                        })
                        //'alineas'  => !$alineas->isEmpty() ? $alineas->toArray() : null,
                        //'chiffres' => !$chiffres->isEmpty() ? $chiffres->toArray() : null,
                        //'lettres'  => !$lettres->isEmpty() ? $lettres->toArray() : null,
                    ]
                ];
            })
        ];
    })->toArray();

    $droit = $lois->groupBy('droit');

    echo '<pre>';

    print_r($grouped);
    echo '</pre>';exit();*/

    $model = new \App\Droit\Matiere\Entities\Matiere();
    $model_note = new \App\Droit\Matiere\Entities\Matiere_note();
    $model_note_page = new \App\Droit\Matiere\Entities\Matiere_note_page();

    $results = \Excel::toArray(new \App\Imports\MatiereImport, storage_path('app/public/Matieres_2019.xlsx'));

    $results = collect($results)->map(function ($matiere) {
        return collect($matiere)->map(function ($row) {
            return [
                'matiere_id'     => $row[0] ?? null,
                'content'        => $row[1] ?? null,
                'page'           => $row[2] ?? null,
                'volume_id'      => $row[3] ?? null,
                'domaine'        => $row[4] ?? null,
                'confer_externe' => $row[5] ?? null,
                'confer_interne' => $row[6] ?? null,
            ];
        });
    })->flatten(1);


    foreach ($results as $i => $result){

      /*  $found = $model->where('title', 'LIKE', $result['matiere'])->get();

        if(!$found->isEmpty()){
            $matiere = $found->first();
        }
        else{
            $matiere = $model->create(['title' => $result['matiere']]);
        }*/

        $data = [
              'matiere_id'     => $result['matiere_id'],
              'content'        => isset($result['content']) && !empty($result['content']) ? $result['content'] : '',
              'volume_id'      => 12,
              'page'           => isset($result['page']) && !empty($result['page']) ? $result['page'] : null,
              'domain'         => isset($result['domain']) && !empty($result['domain']) ? $result['domain'] : null,
              'confer_interne' => isset($result['confer_interne']) && !empty($result['confer_interne']) ? $result['confer_interne'] : null,
              'confer_externe' => isset($result['confer_externe']) && !empty($result['confer_externe']) ? $result['confer_externe'] : null,
          ];

  /*      echo '<pre>';
        print_r($data);
        echo '</pre>';*/

       $note = $model_note->create($data);

        if(isset($result['page']) && !empty($result['page'])){

            $note_page = $model_note_page->create([
                'note_id'        => $note->id,
                'volume_id'      => 12,
                'page'           => $result['page'],
            ]);
        }

    }

    /*
        \App\Droit\User\Entities\User::create(array(
            'name'  => 'Guest',
            'email' => 'info@rjne.ch',
            'password' => Hash::make('rjne2015')
        ));
    */


});


Auth::routes();


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
