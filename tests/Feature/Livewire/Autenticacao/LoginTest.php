<?php

use App\Livewire\Autenticacao\Login;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

use function Pest\Laravel\call;

it('deve ser capaz de renderizar o componente de login', function () {
  Livewire::test(Login::class)->assertOk();
});

it('deve ser capaz de logar um usuario no sistema', function () {
  $user = User::factory()->create(['email' => 'joe@doe.com']);

  Livewire::test(Login::class)
    ->set('email', 'joe@doe.com')
    ->set('password', 'password')
    ->call('submit')
    ->assertHasNoErrors()
    ->assertRedirect(route('dashboard'));

  expect(Auth::check())->toBeTrue()
    ->and(Auth::user())->id->toBe($user->id);
});

it('deve ser capaz de barrar com erro caso email e senha sejam incompativeis', function () {
  Livewire::test(Login::class)
  ->set('email', 'joe@doe.com')
  ->set('password', 'passwor')
  ->call('submit')
  ->assertHasErrors(['email']);
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

it('deve fazer o bloqueio corretamente usando rate limiting apos 5 tentativas', function () {
  $user = User::factory()->create();

  for($i = 0; $i < 5; $i++) {
    Livewire::test(Login::class)
      ->set('email', $user->email)
      ->set('password', 'wrong-password')
      ->call('submit')
      ->assertHasErrors(['email']);
  }

  Livewire::test(Login::class)
      ->set('email', $user->email)
      ->set('password', 'wrong-password')
      ->call('submit')
      ->assertHasErrors(['rateLimiter']);
});
