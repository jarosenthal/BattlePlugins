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

Route::group(['domain'=>'battleplugins.com'], mainSite());

function mainSite(){
    Route::get('/', 'Blog\PageController@index');
    Route::get('/blog/{blog}', 'Blog\PageController@getBlog');

    Route::get('/logout', 'UserController@logout');

    Route::group(['before' => 'csrf'], function () {
        Route::post('/login', 'UserController@login');
    });

    Route::group(['before' => 'auth'], function () {
        Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
        Route::get('/delete/{blog}', 'Blog\Blog\BlogController@deleteBlog');

        Route::group(['before' => 'csrf'], function () {
            Route::post('/create', 'Blog\BlogController@create');
            Route::post('/blog/{blog}', 'Blog\BlogController@editBlog');
        });
    });
}