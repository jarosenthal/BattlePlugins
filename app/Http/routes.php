<?php

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/register', 'Auth\AuthController@getRegister');

Route::group(['before' => 'auth', 'prefix' => 'user'], function () {
  Route::get('settings', 'Auth\UserController@getSettings');
});

Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');

Route::group(['before' => 'csrf'], function () {
  Route::post('auth/login', 'Auth\AuthController@postLogin');
  Route::post('auth/register', 'Auth\UserController@postRegister');
  Route::post('user/settings', 'Auth\UserController@postChangeSettings');
  Route::post('password/email', 'Auth\PasswordController@postEmail');
  Route::post('password/reset', 'Auth\PasswordController@postReset');
});

Route::get('/', 'BlogController@getIndex');

Route::group(['prefix' => 'blog'], function () {
  Route::group(['before' => 'csrf', 'before' => 'auth'], function () {
    Route::post('{id}', 'BlogController@postEditBlog');
  });

  Route::get('{id?}', 'BlogController@getIndex');
});

Route::group(['prefix' => 'api'], function () {
  Route::get('/', 'ApiController@getIndex');

  Route::group(['prefix' => 'v1'], function () {
    Route::resource('tasks', 'Endpoints\TasksController');
    Route::resource('users', 'Endpoints\UsersController');
    Route::resource('blogs', 'Endpoints\BlogsController');
    Route::resource('pastes', 'Endpoints\PastesController');
  });

  Route::group(['before' => 'csrf', 'before' => 'auth'], function () {
    Route::post('generateKey', 'ApiController@postGenerateKey');
    Route::post('webhooks', 'ApiController@postCreateWebhook');
  });
});

Route::group(['prefix' => 'paste'], function () {
  Route::get('/', 'PasteController@getIndex');

  Route::group(['before' => 'auth', 'before' => 'csrf'], function () {
    Route::post('create', 'PasteController@postCreatePaste');
    Route::post('edit', 'PasteController@postEditPaste');
    Route::post('togglepub/{id}', 'PasteController@postTogglePublic');
    Route::post('delete/{id}', 'PasteController@postDeletePaste');
  });

  Route::get('{slug}', 'PasteController@getPaste');
  Route::get('{slug}/raw', 'PasteController@getRawPaste');
  Route::get('{slug}/download', 'PasteController@getDownloadPaste');
});

Route::group(['prefix' => 'dl'], function () {
  Route::get('/', 'DownloadController@getIndex');
  Route::any('update/{event?}', 'DownloadController@anyUpdateJenkins');

  Route::group(['prefix' => 'job'], function () {
    Route::get('{job?}', 'DownloadController@getJob');
    Route::get('{job}/download/{build?}', 'DownloadController@getDownload');
    Route::get('{job}/latestVersionImage/{w?}/{h?}/{font_size?}', 'DownloadController@getLatestVersionImage');
    Route::get('{job}/latestStableVersionImage/{w?}/{h?}/{font_size?}', 'DownloadController@getLatestStableVersionImage');

    Route::group(['before' => 'csrf', 'before' => 'auth'], function () {
      Route::post('{job}/production', 'DownloadController@postToggleProduction');
    });
  });
});

Route::group(['prefix' => 'admin'], function () {
  Route::get('/', 'AdminController@getIndex');

  Route::group(['prefix' => 'feeds'], function () {
    Route::get('github', 'AdminController@getGithub');
    Route::get('github/{repo}', 'AdminController@getGithubIssues');

    Route::get('logs/{name?}/{currPage?}/{perPage?}', 'AdminController@getLogs');
  });

  Route::group(['prefix' => 'user'], function () {
    Route::get('create', 'AdminController@getCreateUser');
    Route::get('modify', 'AdminController@getModifyUser');
    Route::get('modify/{uid}', 'AdminController@getModifyUserPermissions');
    Route::get('permissions', 'AdminController@getPermissionOverview');
    Route::get('apikeys', 'AdminController@getApiKeys');

    Route::group(['before' => 'csrf'], function () {
      Route::post('modify/{id}/delete', 'Auth\UserController@postDeleteUser');
      Route::post('create', 'Auth\UserController@postDreateUser');
      Route::post('modify/{uid}/permissions', 'Auth\UserController@postModifyUserPermissions');
    });
  });

  Route::group(['prefix' => 'tools'], function () {
    Route::get('alert', 'AdminController@getAlerts');
    Route::get('alert/json', 'AdminController@getJsonAlerts');
    Route::get('cms', 'AdminController@getCms');
    Route::get('serverstats', 'AdminController@getServerStats');
    Route::get('pastes', 'AdminController@getPastes');

    Route::group(['before' => 'csrf'], function () {
      Route::post('alert', 'AdminController@postAlert');
      Route::post('alert/delete/{id}', 'AdminController@postDeleteAlert');
      Route::post('alert/admin-delete/{id}', 'AdminController@postAdminDeleteAlert');
      Route::post('cms/{toggle}', 'AdminController@postToggleSetting');
      Route::post('pastes/delete/{id}', 'AdminController@postDeletePaste');
    });
  });

  Route::group(['prefix' => 'blog'], function () {
    Route::get('/', 'AdminController@getEditBlogPosts');
    Route::get('create', 'AdminController@getCreateBlogPost');
    Route::get('edit/{id}', 'AdminController@getEditBlogPost');

    Route::group(['before' => 'csrf', 'before' => 'auth'], function () {
      Route::post('create', 'AdminController@postCreateBlogPost');
      Route::post('delete/{blog}', 'AdminController@postDeleteBlogPost');
      Route::post('edit/{blog}', 'AdminController@postEditBlogPost');
    });
  });

  Route::group(['prefix' => 'tasks'], function () {

    Route::get('/', 'AdminController@getTasks');
    Route::get('create', 'AdminController@getCreateTask');

    Route::group(['before' => 'csrf', 'before' => 'auth'], function () {
      Route::post('delete/{id}', 'AdminController@postDeleteTask');
      Route::post('create', 'AdminController@postCreateTask');
    });
  });
});