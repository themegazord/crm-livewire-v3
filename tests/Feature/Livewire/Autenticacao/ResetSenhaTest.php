<?php

use App\Livewire\Autenticacao\ResetSenha;
use App\Models\User;
use App\Notifications\ResetSenhaNotification;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Livewire;
use Illuminate\Support\Str;

it('ser capaz de renderizar o componente de reset de senha', function () {
  Livewire::test(ResetSenha::class, [
    'token' => 'dawd',
    'email' => 'dawd@email.comc'
  ])
    ->assertOk();
});

it('ser capaz de redirecionar para a rotina de recuperar senha caso email ou token nao sejam apresentados', function () {
  Livewire::test(ResetSenha::class, [
    'token' => '',
    'email' => ''
  ])
    ->assertRedirect(route('login'));
});

it('ser capaz de validar os campos do reset de senha', function ($objeto) {
  Livewire::test(ResetSenha::class, [
    'email' => 'a@a.com',
    'token' => 'token'
  ])
    ->set($objeto->campo, $objeto->value)
    ->call('submit')
    ->assertHasErrors([$objeto->campo => $objeto->rule]);
})->with([
  'password::required' => (object)['campo' => 'password', 'value' => '', 'rule' => 'required'],
  'password::confirmed' => (object)['campo' => 'password', 'value' => 'password', 'rule' => 'confirmed'],
]);

// Ver depois como fica a implementacao desse teste.
// it('ser capaz de atualizar a senha do usuario', function () {
//   $usuario = User::factory()->create(['email' => 'joe@doe.com']);

//   Livewire::test(ResetSenha::class)
//     ->set('email', 'joe@doe.com')
//     ->set('token', )
// });
