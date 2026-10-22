<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BansController;
use App\Http\Controllers\ForbiddenController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by bootstrap/app.php within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [MainController::class, 'getIndex'])->name("home");


Route::resource('api', APIController::class)
    ->except(['create', 'destroy', 'edit', 'update', 'destroy']);

//Must stay above the short url catch-all
Route::redirect('/login', '/auth/login');

Route::get('/{shortUrl}', [APIController::class, 'show'])->name('redirect');

//Route::post('/api/search/', [APIController::class, 'showUrlInfo'])->name('search');


Route::group([
    'prefix' => 'auth'
], function () {
    Route::get('login', [LoginController::class, 'login'])->name('login');
    Route::post('login', [LoginController::class, 'postLogin'])->name('postLogin');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});


Route::group([
    'prefix' => 'admin',
    'middleware' => ['auth']
], function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::resource('bans', BansController::class)->only(['index', 'create', 'show']);
    Route::resource('forbidden', ForbiddenController::class)->only(['index', 'create', 'show']);

    Route::group([
        'prefix' => 'api'
    ], function(){
        Route::resource('bans', BansController::class)->only(['store', 'update', 'destroy']);
        Route::resource('forbidden', ForbiddenController::class)->only(['store', 'update', 'destroy']);

        Route::post('search', [APIController::class, 'searchAdmin'])->name('searchAdmin');

        Route::resource('api', APIController::class)->only(['destroy']);
    });


});
