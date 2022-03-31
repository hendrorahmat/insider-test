<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index']);
Route::group(['prefix' => 'champion-league'], function () {
    Route::get('/', [\App\Http\Controllers\ChampionLeagueController::class, 'index'])
        ->name('champion.league.index');
    Route::resources([
        'groups' => \App\Http\Controllers\GroupController::class,
        'fixtures' => \App\Http\Controllers\FixtureController::class,
        'groups/{groupId}/simulation' => \App\Http\Controllers\GroupSimulationController::class,
        'play-match' => \App\Http\Controllers\PlayMatchController::class,
        'groups/{groupId}/reset-match' => \App\Http\Controllers\ResetMatchController::class
    ]);
});
