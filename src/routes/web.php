<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/hohoho', function () {
    echo("This is from the backend<br>");
    return 'registered from TasksServiceProvider';
});

Route::get('/hehehe', 'TasksController@test')->name('task.hehehe');