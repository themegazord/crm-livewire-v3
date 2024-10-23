<?php

use App\Livewire\Autenticacao\Registro;
use App\Models\User;
use App\Notifications\BemVindoNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

it('deve renderizar o componente', function () {
  Livewire::test(Registro::class)
    ->assertOk();
});

it('deve ser capaz de registrar um novo usuario no sistema', function () {
  Livewire::test(Registro::class)
    ->set('name', 'Joe Doe')
    ->set('email', 'joe@doe.com')
    ->set('email_confirmation', 'joe@doe.com')
    ->set('password', 'password')
    ->call('submit')
    ->assertHasNoErrors()
    ->assertRedirect('/');

  assertDatabaseHas('users', [
    'name' => 'Joe Doe',
    'email' => 'joe@doe.com'
  ]);

  assertDatabaseCount('users', 1);

  expect(Auth::check())
    ->and(Auth::user())
    ->id->toBe(User::first()->id);
});

test('regras de validacao', function ($objeto) {
  if ($objeto->rule === 'unique') {
    User::factory()->create([$objeto->campo => $objeto->value]);
  }
  $livewire = Livewire::test(Registro::class)
    ->set($objeto->campo, $objeto->value);

  if (property_exists($objeto, 'aValue')) {
    $livewire->set($objeto->aCampo, $objeto->aValue);
  }

  $livewire->call('submit')
    ->assertHasErrors([$objeto->campo => $objeto->rule]);
})->with([
  'name::required' => (object)['campo' => 'name', 'value' => '', 'rule' => 'required'],
  'name::max:255' => (object)['campo' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],
  'email::required' => (object)['campo' => 'email', 'value' => '', 'rule' => 'required'],
  'email::email' => (object)['campo' => 'email', 'value' => 'nao-e-email', 'rule' => 'email'],
  'email::max:255' => (object)['campo' => 'email', 'value' => str_repeat('*' . '@doe.com', 256), 'rule' => 'max'],
  'email::confirmed' => (object)['campo' => 'email', 'value' => 'joe@doe.com', 'rule' => 'confirmed'],
  'email::unique' => (object)['campo' => 'email', 'value' => 'joe@doe.com', 'rule' => 'unique', 'aCampo' => 'email_confirmation', 'aValue' => 'joe@doe.com'],
  'password::required' => (object)['campo' => 'password', 'value' => '', 'rule' => 'required'],
]);

it('deve ser capaz de enviar a notificacao de boas vindas para um novo usuario', function () {
  Notification::fake();

  Livewire::test(Registro::class)
    ->set('name', 'Joe Doe')
    ->set('email', 'joe@doe.com')
    ->set('email_confirmation', 'joe@doe.com')
    ->set('password', 'password')
    ->call('submit');

    $user = User::whereEmail('joe@doe.com')->first();

    Notification::assertSentTo($user, BemVindoNotification::class);
});
