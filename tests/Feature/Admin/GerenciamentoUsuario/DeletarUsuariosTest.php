<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Usuarios;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

it('deve ser capaz de deletar o usuario', function () {
  $admin = User::factory()->admin()->create();
  $paraDeletar = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class, ['usuario' => $paraDeletar])
    ->set('confirmacao_confirmation', 'MEGAZORDE')
    ->call('destroy')
    ->assertDispatched('usuario::deletado');

  assertSoftDeleted('users', [
    'id' => $paraDeletar->id
  ]);
});

it('deve ter uma confirmacao antes da remocao do usuario', function () {
  $admin = User::factory()->admin()->create();
  $paraDeletar = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class, ['usuario' => $paraDeletar])
    ->call('destroy')
    ->assertHasErrors(['confirmacao' => 'confirmed'])
    ->assertNotDispatched('usuario::deletado');

  assertNotSoftDeleted('users', [
    'id' => $paraDeletar->id
  ]);
});
