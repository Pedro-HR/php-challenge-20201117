<?php

use App\Http\Controllers\ProductController;
use App\Models\ApiToken;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::get('/', function () {
    return response()->json(['PHP Challenge 20201117'], Response::HTTP_OK);
});

Route::view('/home', 'home');

Route::get('/create-token', function () {
    if(ApiToken::first()) ApiToken::first()->delete();
    return ['token' => ApiToken::create(['token' => Str::random(10)])->token];
});


Route::resource('products', ProductController::class)
    ->except(['create', 'edit'])
    ->middleware('api_token');

