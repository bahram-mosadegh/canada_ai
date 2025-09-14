<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DesktopController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CaseController;

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', function () {
        return redirect('/desktop');
    });

    Route::controller(ClientController::class)->group(function () {
        Route::get('clients', 'index');
        // Route::get('add_user', 'add_get');
        // Route::post('add_user', 'add_post');
        // Route::get('edit_user/{id}', 'edit_get');
        // Route::post('edit_user/{id}', 'edit_post');
    });

    Route::controller(CaseController::class)->group(function () {
        Route::get('cases', 'index');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index');
        Route::get('add_user', 'add_get');
        Route::post('add_user', 'add_post');
        Route::get('edit_user/{id}', 'edit_get');
        Route::post('edit_user/{id}', 'edit_post');
        Route::get('change_user_status/{id}/{status}', 'change_user_status');
        Route::get('logout', 'logout');
        Route::get('profile', 'profile');
        Route::post('edit_profile', 'edit_profile');
    });

    Route::get('/desktop', [DesktopController::class, 'desktop']);
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function ()
    {
        return view('session.login');
    })->name('login');
    Route::post('/login', [UserController::class, 'login']);
});
