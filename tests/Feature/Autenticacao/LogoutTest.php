<?php

use App\Livewire\Autenticacao\Logout;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

it('deve ser capaz de deslogar o usuario do sistema', function () {
  $user = User::factory()->create();

  actingAs($user);

  Livewire::test(Logout::class)
    ->call('logout')
    ->assertRedirect(route('login'));

  expect(auth())
    ->guest()
    ->toBeTrue();
});
