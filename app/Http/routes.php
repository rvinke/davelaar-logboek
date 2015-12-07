<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/






Route::get('', ['middleware' => 'auth', 'as' => 'home', 'uses' => 'Generic\HomeController@index']);

Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::get('reset', ['as' => 'auth.password_reset', 'uses' => 'Auth\UserController@resetPassword']);
Route::post('reset', ['as' => 'auth.reset', 'uses' => 'Auth\UserController@storeResetPassword']);

Route::group(['middleware' => 'auth'], function() {

    Route::get('api/v1/projecten', ['as' => 'api.projecten.v1', 'uses' => 'Logboek\ProjectController@getDatatable']);
    Route::get('api/v1/rapporten', ['as' => 'api.rapporten.v1', 'uses' => 'Logboek\ProjectController@getDatatableRapporten']);
    Route::get('api/v1/subdatabase/{subdatabase}', ['as' => 'api.subdatabase.v1', 'uses' => 'SubdatabaseController@getDatatable']);
    Route::get('api/v1/user', ['as' => 'api.user.v1', 'uses' => 'Auth\UserController@getDatatable']);

    Route::group(['middleware' => ['role:admin|medewerker']], function () {
        Route::get('projecten', ['as' => 'projecten.index', 'uses' => 'Logboek\ProjectController@index']);
        Route::get('projecten/create', ['as' => 'projecten.create', 'uses' => 'Logboek\ProjectController@create']);
        Route::post('projecten/create', ['as' => 'projecten.store', 'uses' => 'Logboek\ProjectController@store']);

        Route::get('projecten/{id}/edit', ['as' => 'projecten.edit', 'uses' => 'Logboek\ProjectController@edit']);
        Route::patch('projecten/{id}/update', ['as' => 'projecten.update', 'uses' => 'Logboek\ProjectController@update']);
        Route::get('projecten/{id}', ['as' => 'projecten.show', 'uses' => 'Logboek\ProjectController@show']);

    });

    Route::get('rapporten', ['as' => 'projecten.rapporten', 'uses' => 'Logboek\ProjectController@indexRapporten']);
    Route::get('projecten/{id}/rapport', ['as' => 'rapport.show', 'uses' => 'Logboek\ProjectController@rapport']);

    Route::group(['middleware' => ['role:admin|medewerker']], function () {
        Route::get('logboek/create/{project_id}', ['as' => 'log.create', 'uses' => 'Logboek\LogController@create']);
        Route::post('logboek/create/', ['as' => 'log.store', 'uses' => 'Logboek\LogController@store']);
        Route::get('logboek/{id}/edit', ['as' => 'log.edit', 'uses' => 'Logboek\LogController@edit']);
        Route::patch('logboek/{id}/update', ['as' => 'log.update', 'uses' => 'Logboek\LogController@update']);
        Route::get('logboek/{id}/delete', ['as' => 'log.delete', 'uses' => 'Logboek\LogController@destroy']);
    });

    Route::group(['middleware' => ['role:admin|medewerker']], function () {
        Route::get('subdatabase/{subdatabase}/create', ['as' => 'subdatabase.create', 'uses' => 'SubdatabaseController@create']);
        Route::post('subdatabase/{subdatabase}/create', ['as' => 'subdatabase.store', 'uses' => 'SubdatabaseController@store']);
        Route::get('subdatabase/{subdatabase}', ['as' => 'subdatabase.index', 'uses' => 'SubdatabaseController@index']);
        Route::get('subdatabase/{subdatabase}/{id}/edit', ['as' => 'subdatabase.edit', 'uses' => 'SubdatabaseController@edit']);
        Route::patch('subdatabase/{subdatabase}/{id}/update', ['as' => 'subdatabase.update', 'uses' => 'SubdatabaseController@update']);
        Route::get('subdatabase/{subdatabase}/{id}/delete', ['as' => 'subdatabase.delete', 'uses' => 'SubdatabaseController@destroy']);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('user/create', ['as' => 'user.create', 'uses' => 'Auth\UserController@create']);
        Route::post('user/create', ['as' => 'user.store', 'uses' => 'Auth\UserController@store']);
        Route::get('user', ['as' => 'user.index', 'uses' => 'Auth\UserController@index']);
        Route::get('user/{id}/edit', ['as' => 'user.edit', 'uses' => 'Auth\UserController@edit']);
        Route::patch('user/{id}/update', ['as' => 'user.update', 'uses' => 'Auth\UserController@update']);
    });
    //Route::get('user/{id}/delete', ['as' => 'user.delete', 'uses' => 'Auth\UserController@destroy']);

    Route::get('project/{project_id}/file/create', ['as' => 'file.create', 'uses' => 'Logboek\FileController@create']);
    Route::post('project/{project_id}/file/create', ['as' => 'file.store', 'uses' => 'Logboek\FileController@store']);
    Route::get('user', ['as' => 'user.index', 'uses' => 'Auth\UserController@index']);

    Route::get('file/download/{id}', ['as' => 'file.download', 'uses' => 'Logboek\FileController@show']);
    Route::get('file/edit/{id}', ['as' => 'file.edit', 'uses' => 'Logboek\FileController@edit']);
    Route::patch('file/edit/{id}', ['as' => 'file.update', 'uses' => 'Logboek\FileController@update']);





    //photos

    // Setup Glide server
    $server = League\Glide\ServerFactory::create([
        'source' => 'documenten',
        'cache' => '.cache',
    ]);

    Route::get('photo/{id}', function($id) use ($server) {
        $file = \App\Models\File::findOrFail($id);

        $server->outputImage(
            date("Y", strtotime($file->project->created_at)).'/'.$file->project_id.'/'.$file->naam,
            [
                'w' => 50,
                'h' => 50,
                'fit' => 'crop',
            ]
        );
    });

    Route::get('photoL/{id}', function($id) use ($server) {
        $file = \App\Models\File::findOrFail($id);

        $server->outputImage(
            date("Y", strtotime($file->project->created_at)).'/'.$file->project_id.'/'.$file->naam,
            [
                'w' => 800,
                'h' => 600,
                'fit' => 'contain',
            ]
        );
    });

    //TEMP
    Route::get('tasks/migrateLogs', ['uses' => 'TaskController@migrateLogs']);
    Route::get('tasks/migratePhotos', ['uses' => 'TaskController@migratePhotos']);
});

