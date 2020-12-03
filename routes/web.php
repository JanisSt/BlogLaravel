<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('articles', 'ArticlesController')
    ->middleware(['auth'])
    ->except(['index', 'show']);

Route::get('articles', 'ArticlesController@index')->name('articles.index');
Route::get('articles/{article}', 'ArticlesController@show')->name('articles.show');

Route::get('/products/{product}','ProductsController@show');
