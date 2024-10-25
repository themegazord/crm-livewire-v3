<?php

use App\Livewire\Autenticacao\Login;
use App\Livewire\Autenticacao\RecuperarSenha;
use App\Livewire\Autenticacao\Registro;
use App\Livewire\Autenticacao\ResetSenha;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/registro', Registro::class)->name('autenticacao.registro');
Route::get('/login', Login::class)->name('login');
Route::get('/recuperar_senha', RecuperarSenha::class)->name('password.reset');
Route::get('/password/reset/{token?}/{email?}', ResetSenha::class);
Route::get('/logout', function () {
  Auth::logout();
  return redirect('/');
});

Route::middleware('auth')->group(function () {
  Route::get('/', Welcome::class)->name('dashboard');

  // region Admin

  Route::prefix('/admin')->middleware('can:ser-um-admin')->group(function () {
    Route::get('/dashboard', fn () => 'admin.dashboard')->name('admin.dashboard');
  });

  // endregion

});
