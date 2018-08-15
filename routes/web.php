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

/**
 * Api
 */
$router->group(['prefix' => 'api/v1'], function () use ($router) {
    /**
     * Products
     */
    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', 'ProductController@getAll');
        $router->get('/{id}', 'ProductController@getOne');

        $router->group(['middleware' => ['auth']], function () use ($router) {
            $router->post('/', 'ProductController@create');
            $router->put('/{id}', 'ProductController@update');
            $router->delete('/{id}', 'ProductController@delete');
        });
    });

    /**
     * Categories
     */
    $router->group(['prefix' => 'categories', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'CategoryController@getAll');
        $router->get('/{id}', 'CategoryController@getOne');
        $router->post('/', 'CategoryController@create');
        $router->put('/{id}', 'CategoryController@update');
        $router->delete('/{id}', 'CategoryController@delete');
    });

    /**
     * Ingredients
     */
    $router->group(['prefix' => 'ingredients', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'IngredientController@getAll');
        $router->get('/{id}', 'IngredientController@getOne');
        $router->post('/', 'IngredientController@create');
        $router->put('/{id}', 'IngredientController@update');
        $router->delete('/{id}', 'IngredientController@delete');
    });

    /**
     * Badges
     */
    $router->group(['prefix' => 'badges', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'BadgeController@getAll');
        $router->get('/{id}', 'BadgeController@getOne');
        $router->post('/', 'BadgeController@create');
        $router->put('/{id}', 'BadgeController@update');
        $router->delete('/{id}', 'BadgeController@delete');
    });

    /**
     * Users
     */
    $router->group(['prefix' => 'users', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'UserController@getAll');
        $router->get('/{id}', 'UserController@getOne');
        $router->post('/', 'UserController@create');
        $router->post('/quick', 'UserController@createQuickUser');
        $router->put('/{id}', 'UserController@update');
        $router->delete('/{id}', 'UserController@delete');
    });

    /**
     * Bills
     */
    $router->group(['prefix' => 'bills', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'BillController@getAll');
        $router->get('/{id}', 'BillController@getOne');
        $router->post('/', 'BillController@create');
        $router->put('/{id}', 'BillController@update');
        $router->delete('/{id}', 'BillController@delete');

        $router->post('/products', 'BillController@addProduct');
        $router->delete('/{id}/products/{productId}', 'BillController@deleteProduct');

        $router->post('/checkout', 'BillController@checkout');
    });

    /**
     * Cards
     */
    $router->group(['prefix' => 'cards', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'CardController@getAll');
        $router->get('/{id}', 'CardController@getOne');
        $router->post('/', 'CardController@create');
        $router->put('/{id}', 'CardController@update');
        $router->delete('/{id}', 'CardController@delete');
    });

    /**
     * Auth
     */
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->get('/me', 'AuthController@me');
    });
});
