<?php

use App\Http\Controllers\FirebaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users', [FirebaseController::class, 'index'])->name('users');
Route::get('/users/add', [FirebaseController::class, 'create'])->name('user-create');
Route::post('/users/add', [FirebaseController::class, 'store'])->name('user-store');
Route::get('/users/delete/{id}', [FirebaseController::class, 'delete'])->name('user-delete');
Route::put('/users/edit/{id}', [FirebaseController::class, 'edit'])->name('user-edit');
Route::get('/users/edit/{id}', [FirebaseController::class, 'update'])->name('user-update');
