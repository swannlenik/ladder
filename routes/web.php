<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\LaddersController;
use App\Http\Controllers\PlayersController;

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
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->prefix('players')->group(function () {
    Route::get('/list', [PlayersController::class, 'list'])->name('view.players');
    Route::get('/available', [PlayersController::class, 'available'])->name('available.players');
    Route::post('/setAvailable', [PlayersController::class, 'setAvailable'])->name('set.available.players');
    Route::get('/setAllAvailable', [PlayersController::class, 'setAllAvailable'])->name('set.all.available.players');
    Route::post('/create', [PlayersController::class, 'create'])->name('create.player');
    Route::get('/delete/{playerID}', [PlayersController::class, 'delete'])->name('delete.player');
});

Route::middleware('auth')->prefix('games')->group(function () {
    Route::get('/update/{gameID}', [GamesController::class, 'update'])->name('update.game');
    Route::get('/updateDouble/{gameID}', [GamesController::class, 'updateDouble'])->name('update.double.game');
    Route::post('/save', [GamesController::class, 'save'])->name('save.game');
    Route::post('/saveDouble', [GamesController::class, 'saveDouble'])->name('save.double.game');
});

Route::middleware('auth')->prefix('groups')->group(function () {
    Route::get('/index/{ladderID}/{groupID}', [GroupsController::class, 'display']);
    Route::get('/view/{groupID}', [GroupsController::class, 'view'])->name('view.group');
    Route::get('/create/{ladderID}', [GroupsController::class, 'create'])->name('create.group');
    Route::get('/createMultiple/{ladderID}', [GroupsController::class, 'createMultiple'])->name('create.groups');
    Route::post('/save', [GroupsController::class, 'save'])->name('save.group');
    Route::post('/saveMultiple', [GroupsController::class, 'saveMultiple'])->name('save.groups');
    Route::get('/delete/{groupID}', [GroupsController::class, 'delete'])->name('delete.group');
});

Route::middleware('auth')->prefix('ladders')->group(function () {
    Route::get('/list', [LaddersController::class, 'list'])->name('view.all.ladders');
    Route::get('/ranking/{ladderID}', [LaddersController::class, 'ranking'])->name('ladder.ranking');
    Route::get('/view/{ladderID}', [LaddersController::class, 'view'])->name('view.ladder');
    Route::post('/create', [LaddersController::class, 'create'])->name('create.ladder');
    Route::get('/delete/{ladderID}', [LaddersController::class, 'delete'])->name('delete.ladder');
});

Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/view/{userID?}', [UserController::class, 'view'])->name('view.users');
    Route::post('/updateUser', [UserController::class, 'updateUser'])->name('update.user');
});

Route::middleware('auth')->prefix('statistics')->group(function () {
    Route::get('/view/{playerID?}', [StatisticsController::class, 'view'])->name('player.statistics');
});
require __DIR__.'/auth.php';
