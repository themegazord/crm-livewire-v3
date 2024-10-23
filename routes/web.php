<?php

use App\Livewire\Autenticacao\Login;
use App\Livewire\Autenticacao\Registro;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', Welcome::class);
Route::get('/registro', Registro::class)->name('autenticacao.registro');
Route::get('/login', Login::class)->name('autenticacao.login');
Route::get('/logout', function () {
  Auth::logout();
  return redirect('/');
});
