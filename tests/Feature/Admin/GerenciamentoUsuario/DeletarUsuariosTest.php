<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Usuarios;
use App\Notifications\UsuarioDeletadoNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

it('deve ser capaz de deletar o usuario', function () {
  $admin = User::factory()->admin()->create();
  $paraDeletar = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class)
    ->set('usuario', $paraDeletar)
    ->set('confirmacao_confirmation', $paraDeletar->name)
    ->call('configuraModalDeConfirmacao', $paraDeletar->id)
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

  Livewire::test(Usuarios\Remover::class)
    ->set('usuario', $paraDeletar)
    ->set('confirmacao', 'nao e o nome')
    ->call('configuraModalDeConfirmacao', $paraDeletar->id)
    ->call('destroy')
    ->assertHasErrors(['confirmacao' => 'confirmed'])
    ->assertNotDispatched('usuario::deletar');
  assertNotSoftDeleted('users', [
    'id' => $paraDeletar->id
  ]);
});

it('deve mandar uma notificacao para o usuario informando que sua conta foi inativada', function () {
  Notification::fake();

  $admin = User::factory()->admin()->create();
  $paraDeletar = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class)
    ->set('usuario', $paraDeletar)
    ->set('confirmacao_confirmation', $paraDeletar->name)
    ->call('configuraModalDeConfirmacao', $paraDeletar->id)
    ->call('destroy');

  Notification::assertSentTo($paraDeletar, UsuarioDeletadoNotification::class);
});

it('deve barrar a inativacao do usuario caso ele seja o autenticado no momento', function () {
  $admin = User::factory()->admin()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class)
    ->set('usuario', $admin)
    ->set('confirmacao_confirmation', $admin->name)
    ->call('configuraModalDeConfirmacao', $admin->id)
    ->call('destroy')
    ->assertHasErrors(['confirmacao'])
    ->assertNotDispatched('usuario::deletar');
  assertNotSoftDeleted('users', [
    'id' => $admin->id
  ]);
});
