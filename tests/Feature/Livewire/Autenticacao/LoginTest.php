<?php

use App\Livewire\Autenticacao\Login;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\call;

it('deve ser capaz de renderizar o componente de login', function () {
  Livewire::test(Login::class)->assertOk();
});

it('deve ser capaz de logar um usuario no sistema', function () {
  User::factory()->create(['email' => 'joe@doe.com']);

  Livewire::test(Login::class)
    ->set('email', 'joe@doe.com')
    ->set('password', 'password')
    ->call('submit')
    ->assertHasNoErrors()
    ->assertRedirect('/');
});

it('deve ser capaz de barrar com erro caso email e senha sejam incompativeis', function () {
  Livewire::test(Login::class)
  ->set('email', 'joe@doe.com')
  ->set('password', 'passwor')
  ->call('submit')
  ->assertHasErrors();
});

it('regras de validacao', function ($objeto) {
  Livewire::test(Login::class)
    ->set($objeto->campo, $objeto->value)
    ->call('submit')
    ->assertHasErrors([$objeto->campo => $objeto->rule]);
})->with([
  'email::required' => (object)['campo' => 'email', 'value' => '', 'rule' => 'required'],
  'email::email' => (object)['campo' => 'email', 'value' => 'nao-e-email', 'rule' => 'email'],
  'email::max:255' => (object)['campo' => 'email', 'value' => str_repeat('*' . '@doe.com', 256), 'rule' => 'max'],
  'email::exists' => (object)['campo' => 'email', 'value' => 'jane@doe.com', 'rule' => 'exists:users'],
  'password::required' => (object)['campo' => 'password', 'value' => '', 'rule' => 'required'],
]);
