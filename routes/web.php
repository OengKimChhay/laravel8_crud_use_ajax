<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StuController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[StuController::class,'index'])->name('home');
Route::post('/store',[StuController::class,'store'])->name('store');
Route::delete('/delete/{id}',[StuController::class,'destroy'])->name('delete');
Route::get('/student/{id}',[StuController::class,'edit'])->name('edit');
Route::put('update-student',[StuController::class,'update'])->name('update');
