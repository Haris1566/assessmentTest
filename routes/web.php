<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['prefix' => 'meetings', 'middleware' => ['auth']], function () {
    Route::get('/', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/edit/{meeting}/{currentPage}', [MeetingController::class, 'edit'])->name('meetings.edit');
    Route::get('/destroy/{meeting}/{currentPage}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::post('/store', [MeetingController::class, 'store'])->name('meetings.store');
    Route::post('/update', [MeetingController::class, 'update'])->name('meetings.update');
});
