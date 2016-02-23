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
Route::get('home', ['middleware' => 'auth', 'as' => 'home', 'uses' => 'Generic\HomeController@index']);

//Login routes
Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('login', ['as' => 'login.post', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::get('reset', ['as' => 'auth.password_reset', 'uses' => 'Auth\UserController@resetPassword']);
Route::post('reset', ['as' => 'auth.reset', 'uses' => 'Auth\UserController@storeResetPassword']);

Route::get('qr-adhoc', ['uses' => 'Logboek\QrController@generateAdHoc']);

//QR-code routes
Route::get('qr/{code}', ['as' => 'qr-code.start', 'uses' => 'Logboek\QrController@start']);
Route::get('qr/{code}/create', ['as' => 'qr-code.enter', 'uses' => 'Logboek\QrController@create']);
Route::post('qr/{code}/start', ['as' => 'qr-code.start-log', 'uses' => 'Logboek\QrController@store']);
Route::get('qr/{code}/report', ['as' => 'qr-code.report', 'uses' => 'Logboek\QrController@report']);
Route::post('qr/{code}/report', ['as' => 'qr-code.report.store', 'uses' => 'Logboek\QrController@storeReport']);

Route::group(['middleware' => 'auth'], function() {

    Route::get('api/v1/projecten', ['as' => 'api.projecten.v1', 'uses' => 'Logboek\ProjectController@getDatatable']);
    Route::get('api/v1/rapporten', ['as' => 'api.rapporten.v1', 'uses' => 'Logboek\ProjectController@getDatatableRapporten']);
    Route::get('api/v1/subdatabase/{subdatabase}', ['as' => 'api.subdatabase.v1', 'uses' => 'SubdatabaseController@getDatatable']);
    Route::get('api/v1/user', ['as' => 'api.user.v1', 'uses' => 'Auth\UserController@getDatatable']);
    Route::get('api/v1/logs/{project_id}', ['as' => 'api.logs.v1', 'uses' => 'Logboek\LogController@getDatatable']);



    Route::group(['middleware' => ['role:admin|medewerker']], function () {
        Route::get('project', ['as' => 'projecten.index', 'uses' => 'Logboek\ProjectController@index']);
        Route::get('project/create', ['as' => 'projecten.create', 'uses' => 'Logboek\ProjectController@create']);
        Route::post('project/create', ['as' => 'projecten.store', 'uses' => 'Logboek\ProjectController@store']);

        Route::get('project/{id}/edit', ['as' => 'projecten.edit', 'uses' => 'Logboek\ProjectController@edit']);
        Route::patch('project/{id}/update', ['as' => 'projecten.update', 'uses' => 'Logboek\ProjectController@update']);
        Route::get('project/{id}', ['as' => 'projecten.show', 'uses' => 'Logboek\ProjectController@show']);

        Route::get('project/{id}/add-floorplan', ['as' => 'floorplan.create', 'uses' => 'FloorplanController@create']);
        Route::post('project/{id}/add-floorplan', ['as' => 'floorplan.store', 'uses' => 'FloorplanController@store']);

        Route::get('project/{id}/users', ['as' => 'project.users', 'uses' => 'Logboek\ProjectController@users']);
        Route::post('project/{id}/users/store', ['as' => 'project.users.store', 'uses' => 'Logboek\ProjectController@storeUsers']);

        Route::get('qr/{code}/restore', ['as' => 'qr-code.restore', 'uses' => 'Logboek\QrController@restore']);
    });

    Route::get('rapporten', ['as' => 'projecten.rapporten', 'uses' => 'Logboek\ProjectController@indexRapporten']);
    Route::group(['middleware' => ['projectlink']], function () {
        Route::get('project/{id}/rapport', ['as' => 'rapport.show', 'uses' => 'Logboek\ProjectController@rapport']);
    });
    Route::get('project/{id}/plattegrond/{location}/{floor}', ['as' => 'rapport.floorplan', 'uses' => 'Logboek\ProjectController@floorplan']);
    Route::get('project/{id}/plattegrond/{location}/{floor}/download', ['as' => 'rapport.floorplan.download', 'uses' => 'FloorplanController@download']);
    Route::get('plattegrond-js/{projectId}/{location}/{floor}/{editable?}/{lat?}/{lng?}', ['as' => 'rapport.floorplan-js', 'uses' => 'FloorplanController@javascript']);

    Route::get('logboek/{id}/map-show', ['as' => 'log.map-show', 'uses' => 'Logboek\LogController@mapShow']);


    Route::group(['middleware' => ['role:admin|medewerker']], function () {
        Route::get('logboek/create/{project_id}', ['as' => 'log.create', 'uses' => 'Logboek\LogController@create']);
        Route::post('logboek/create/', ['as' => 'log.store', 'uses' => 'Logboek\LogController@store']);
        Route::get('logboek/{id}/edit', ['as' => 'log.edit', 'uses' => 'Logboek\LogController@edit']);
        Route::patch('logboek/{id}/update', ['as' => 'log.update', 'uses' => 'Logboek\LogController@update']);
        Route::get('logboek/{id}/delete', ['as' => 'log.delete', 'uses' => 'Logboek\LogController@destroy']);

        Route::get('logboek/{id}/map/{floor}', ['as' => 'log.map', 'uses' => 'Logboek\LogController@map']);
        Route::patch('logboek/{id}/update-map', ['as' => 'log.update-map', 'uses' => 'Logboek\LogController@updateMap']);
    });

    Route::group(['middleware' => ['role:admin|medewerker']], function () {
        Route::post('subdatabase/generate-qr/', ['as' => 'qr-code.generate', 'uses' => 'Logboek\QrController@generateCodes']);
        Route::get('subdatabase/generate-qr/', ['as' => 'qr-code.select-number', 'uses' => 'Logboek\QrController@selectNumberOfCodes']);


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

    Route::get('my-profile', ['as' => 'user.show', 'uses' => 'Auth\UserController@show']);

    Route::get('project/{project_id}/file/create', ['as' => 'file.create', 'uses' => 'Logboek\FileController@create']);
    Route::post('project/{project_id}/file/create', ['as' => 'file.store', 'uses' => 'Logboek\FileController@store']);
    Route::get('user', ['as' => 'user.index', 'uses' => 'Auth\UserController@index']);

    Route::get('file/download/{id}', ['as' => 'file.download', 'uses' => 'Logboek\FileController@show']);
    Route::get('file/edit/{id}', ['as' => 'file.edit', 'uses' => 'Logboek\FileController@edit']);
    Route::patch('file/edit/{id}', ['as' => 'file.update', 'uses' => 'Logboek\FileController@update']);

    Route::get('file/download-documentatie/{id}/{filename}', ['as' => 'documentatie.download', 'uses' => 'SubdatabaseController@downloadDocumentatie']);
    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('file/remove-documentatie/{id}/{filename}', ['as' => 'documentatie.remove', 'uses' => 'SubdatabaseController@removeDocumentatie']);
    });



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

    Route::get('photoM/{id}', function($id) use ($server) {
        $file = \App\Models\File::findOrFail($id);

        $server->outputImage(
            date("Y", strtotime($file->project->created_at)).'/'.$file->project_id.'/'.$file->naam,
            [
                'w' => 200,
                'h' => 200,
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

