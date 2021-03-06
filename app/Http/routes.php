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

Route::get('/', function () {
    return view('welcome');
});

Route::group(array('prefix' => 'api/v1.0'), function()
{	
	Route::post('/register', 'UserController@register');
	Route::post('/login', 'UserController@login');
	Route::resource('/marca', 'MarcaController');
	Route::pattern('inexistentes', '.*');
});
	Route::any('/{inexistentes}', function()
{
	return response()->json(['mensaje' => 'Pagina no existe.', 'codigo' => 400],400);
});
