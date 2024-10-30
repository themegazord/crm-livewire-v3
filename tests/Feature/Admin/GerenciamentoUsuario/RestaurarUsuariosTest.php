<?php

use App\Models\User;
use App\Livewire\Admin\Usuarios;
use App\Notifications\UsuarioResetadoNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

it('deve ser possivel restaurar um usuario inativo', function () {
  /** @var User $admin */
  $admin = User::factory()->admin()->create();
  /** @var User $guest */
  $guest = User::factory()->create();

  $guest->delete();

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

it('deve ter uma confirmacao para poder restaurar o usuario', function () {
  /** @var User $admin */
  $admin = User::factory()->admin()->create();
  /** @var User $guest */
  $guest = User::factory()->create();

  $guest->delete();

  actingAs($admin);

  Livewire::test(Usuarios\Restaurar::class)
    ->set('confirmacao_confirmation', 'nao e uma nome valido da silva santos')
    ->call('configuraModalDeConfirmacao', $guest->id)
    ->call('restore')
    ->assertHasErrors(['confirmacao' => 'confirmed'])
    ->assertNotDispatched("usuario::restaurado");

  assertSoftDeleted('users', [
    'id' => $guest->id
  ]);
});

it('deve notificar o usuario que sua conta foi reativada por um administrador', function () {
  Notification::fake();

  /** @var User $admin */
  $admin = User::factory()->admin()->create();
  /** @var User $guest */
  $guest = User::factory()->create();

  $guest->delete();

  actingAs($admin);

  Livewire::test(Usuarios\Restaurar::class)
    ->set('confirmacao_confirmation', $guest->name)
    ->call('configuraModalDeConfirmacao', $guest->id)
    ->call('restore')
    ->assertHasNoErrors()
    ->assertDispatched("usuario::restaurado");

  Notification::assertSentTo($guest, UsuarioResetadoNotification::class);

  assertNotSoftDeleted('users', [
    'id' => $guest->id
  ]);
});
