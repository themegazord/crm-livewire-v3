<?php

use App\Livewire\Autenticacao\Registro;
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
    ->assertHasNoErrors();

    assertDatabaseHas('users', [
      'name' => 'Joe Doe',
      'email' => 'joe@doe.com'
    ]);

    assertDatabaseCount('users', 1);
});
