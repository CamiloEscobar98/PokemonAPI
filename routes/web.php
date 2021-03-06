<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return response('Welcome to Test IPT API V1.0');
});

$router->post('register', 'Auth\AuthController@register');

$router->post('login', 'Auth\AuthController@login');


$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('profile', 'Auth\AuthController@profile');
    $router->put('profile', 'Auth\AuthController@update');
    $router->post('logout', 'Auth\AuthController@logout');

    $router->get('users', 'UserController@index');
    $router->get('users/{uuid}', 'UserController@show');

    $router->get('pokemons', 'PokemonController@index');
    $router->get('pokemon', 'PokemonController@show');
});
