<?php

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
    return $router->app->version();
});

/**
 * Api
 */
$router->group(['prefix' => 'api/v1', 'middleware' => []], function () use ($router) {
    /**
     * Products
     */
    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', 'ProductController@getAll');
        $router->get('/{id}', 'ProductController@getOne');
        $router->post('/', 'ProductController@create');
        $router->put('/{id}', 'ProductController@update');
        $router->delete('/{id}', 'ProductController@delete');
    });

    /**
     * Categories
     */
    $router->group(['prefix' => 'categories'], function () use ($router) {
        $router->get('/', 'CategoryController@getAll');
        $router->get('/{id}', 'CategoryController@getOne');
        $router->post('/', 'CategoryController@create');
        $router->put('/{id}', 'CategoryController@update');
        $router->delete('/{id}', 'CategoryController@delete');
    });

    /**
     * Ingredients
     */
    $router->group(['prefix' => 'ingredients'], function () use ($router) {
        $router->get('/', 'IngredientController@getAll');
        $router->get('/{id}', 'IngredientController@getOne');
        $router->post('/', 'IngredientController@create');
        $router->put('/{id}', 'IngredientController@update');
        $router->delete('/{id}', 'IngredientController@delete');
    });

    /**
     * Badges
     */
    $router->group(['prefix' => 'badges'], function () use ($router) {
        $router->get('/', 'BadgeController@getAll');
        $router->get('/{id}', 'BadgeController@getOne');
        $router->post('/', 'BadgeController@create');
        $router->put('/{id}', 'BadgeController@update');
        $router->delete('/{id}', 'BadgeController@delete');
    });

    /**
     * Users
     */
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('/', 'UserController@getAll');
        $router->get('/{id}', 'UserController@getOne');
        $router->post('/', 'UserController@create');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');
    });
});