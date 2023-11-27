<?php

use Encore\Admin\Facades\Admin;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/developer', 'DeveloperController@index')->name('developer-home');

    $router->resource('sessions', \App\Admin\Controllers\SessionsController::class);
    $router->resource('tasks', \App\Admin\Controllers\TasksController::class);
    $router->resource('messages', \App\Admin\Controllers\MessagesController::class);
    $router->resource('actions', \App\Admin\Controllers\ActionsController::class);

});
