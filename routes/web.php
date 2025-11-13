<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SheetController;

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
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');

// 管理者
Route::get('/admin/movies', [MovieController::class, 'adminIndex'])->name('movies.adminIndex');
Route::get('/admin/movies/create', [MovieController::class, 'create'])->name('movies.create');
Route::post('/admin/movies/store', [MovieController::class, 'store'])->name('movies.store');
Route::get('/admin/movies/{id}/edit', [MovieController::class, 'edit'])->name('movies.edit');
Route::patch('/admin/movies/{id}/update', [MovieController::class, 'update'])->name('movies.update');
Route::delete('/admin/movies/{id}/destroy', [MovieController::class, 'destroy'])->name('movies.destroy');
//座席表
Route::get('/sheets', [SheetController::class, 'index'])->name('sheets.index');

