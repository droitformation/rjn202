<?php

Route::get('/', 'Frontend\HomeController@index');
Route::get('contact', 'Frontend\HomeController@contact');
Route::get('historique', 'Frontend\HomeController@historique');
Route::get('colloque', 'Frontend\HomeController@colloque');

// Contact
Route::post('sendMessage', 'Frontend\HomeController@sendMessage');

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
    $model = new \App\Droit\Matiere\Entities\Matiere();
    $model_note = new \App\Droit\Matiere\Entities\Matiere_note();
    $model_note_page = new \App\Droit\Matiere\Entities\Matiere_note_page();

    $results = \Excel::load(storage_path('app/public/Matieres_2018.xlsx'), function($reader) {
        $reader->ignoreEmpty();
        $reader->setSeparator('');
    })->get();

    foreach ($results as $i => $result){
        $found = $model->where('title', 'LIKE', $result['matiere'])->get();

        if(!$found->isEmpty()){
            $matiere = $found->first();
        }
        else{
            $matiere = $model->create(['title' => $result['matiere']]);
        }

        $note = $model_note->create([
            'matiere_id'     => $matiere->id,
            'content'        => isset($result['content']) && !empty($result['content']) ? $result['content'] : '',
            'volume_id'      => 11,
            'page'           => isset($result['page']) && !empty($result['page']) ? $result['page'] : null,
            'domain'         => isset($result['domain']) && !empty($result['domain']) ? $result['domain'] : null,
            'confer_interne' => isset($result['confer_interne']) && !empty($result['confer_interne']) ? $result['confer_interne'] : null,
            'confer_externe' => isset($result['confer_externe']) && !empty($result['confer_externe']) ? $result['confer_externe'] : null,
        ]);

        if(isset($result['page']) && !empty($result['page'])){

            $note_page = $model_note_page->create([
                'note_id'        => $note->id,
                'volume_id'      => 11,
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

    // App\Droit\Matiere\Repo\MatiereInterface
    $model = \App::make('App\Droit\Disposition\Repo\DispositionInterface');
    $result = $model->newsearch(['loi' => 10, 'article' => 23]);

    echo '<pre>';
    print_r($result);
    echo '</pre>';exit();
});


Auth::routes();


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
