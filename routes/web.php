<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\MovieController;

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

Route::get('/practice', [PracticeController::class, 'sample'])->name('practice');
Route::get('/practice2', [PracticeController::class, 'sample2'])->name('practice2');
Route::get('/practice3', [PracticeController::class, 'sample3'])->name('practice3');
Route::get('/getPractice', [PracticeController::class, 'getPractice'])->name('getPractice');

// 一般ユーザー
Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');

// 管理者
Route::get('/admin/movies', [MovieController::class, 'adminIndex'])->name('movies.adminIndex');
Route::get('/admin/movies/create', [MovieController::class, 'create'])->name('movies.create');
Route::post('/admin/movies/store', [MovieController::class, 'store'])->name('movies.store');
