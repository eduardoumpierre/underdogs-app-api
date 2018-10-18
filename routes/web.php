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
     * Levels
     */
    $router->group(['prefix' => 'levels', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'LevelController@getAll');
        $router->get('/{id}', 'LevelController@getOne');
        $router->post('/', 'LevelController@create');
        $router->put('/{id}', 'LevelController@update');
        $router->delete('/{id}', 'LevelController@delete');
    });

    /**
     * Drops
     */
    $router->group(['prefix' => 'drops', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'DropController@getAll');
        $router->get('/{id}', 'DropController@getOne');
        $router->post('/', 'DropController@create');
        $router->put('/{id}', 'DropController@update');
        $router->delete('/{id}', 'DropController@delete');
    });

    /**
     * Schedule
     */
    $router->group(['prefix' => 'schedule'], function () use ($router) {
        $router->get('/', 'ScheduleController@getAll');
        $router->get('/{id}', 'ScheduleController@getOne');

        $router->group(['middleware' => ['auth']], function () use ($router) {
            $router->post('/', 'ScheduleController@create');
            $router->put('/{id}', 'ScheduleController@update');
            $router->delete('/{id}', 'ScheduleController@delete');
        });
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
     * Achievements
     */
    $router->group(['prefix' => 'achievements', 'middleware' => ['auth']], function () use ($router) {
        $router->get('/', 'AchievementController@getAll');
        $router->get('/{id}', 'AchievementController@getOne');
        $router->post('/', 'AchievementController@create');
        $router->put('/{id}', 'AchievementController@update');
        $router->delete('/{id}', 'AchievementController@delete');
    });

    /**
     * Users
     */
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->post('/', 'UserController@create');

        $router->get('/ranking', 'UserController@getRanking');

        $router->group(['middleware' => ['auth']], function () use ($router) {
            $router->get('/online', 'UserController@getOnlineUsers');
            $router->get('/online/stats', 'UserController@getOnlineUsersStats');

            $router->get('/{id}/achievements', 'UserController@getAchievements');

            $router->get('/', 'UserController@getAll');
            $router->get('/{id}', 'UserController@getOne');

            $router->post('/quick', 'UserController@createQuickUser');
            $router->put('/{id}', 'UserController@update');
            $router->delete('/{id}', 'UserController@delete');
        });
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
