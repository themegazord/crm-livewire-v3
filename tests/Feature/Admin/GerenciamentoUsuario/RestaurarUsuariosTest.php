<?php

use App\Models\User;
use App\Livewire\Admin\Usuarios;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;

it('deve ser possivel restaurar um usuario inativo', function () {
  /** @var User $admin */
  $admin = User::factory()->admin()->create();
  /** @var User $guest */
  $guest = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Restaurar::class)
    ->set('confirmacao_confirmation', $guest->name)
    ->call('configuraModalDeConfirmacao', $guest->id)
    ->call('restore')
    ->assertHasNoErrors()
    ->assertDispatched("usuario::restaurado");

  assertNotSoftDeleted('users', [
    'id' => $guest->id
  ]);
});
