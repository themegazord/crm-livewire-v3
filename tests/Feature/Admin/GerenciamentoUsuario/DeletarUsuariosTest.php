<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Usuarios;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertSoftDeleted;

it('deve ser capaz de deletar o usuario', function () {
  $admin = User::factory()->admin()->create();
  $paraDeletar = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class, ['usuario' => $paraDeletar])
    ->call('destroy')
    ->assertDispatched('usuario::deletado');

  assertSoftDeleted('users', [
    'id' => $paraDeletar->id
  ]);
});
