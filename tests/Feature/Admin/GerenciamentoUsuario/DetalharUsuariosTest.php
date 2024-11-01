<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Usuarios;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('ser capaz de mostrar todos os dados do cliente num form somente leitura', function () {
  $admin = User::factory()->admin()->create();
  $usuario = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Detalhar::class)
    ->set('usuario', $usuario)
    ->assertSee($usuario->name)
    ->assertSee($usuario->email)
    ->assertSee(date('d/m/Y H:i:s', strtotime($usuario->created_at)))
    ->assertSee(date('d/m/Y H:i:s', strtotime($usuario->updated_at)));
});

test('se o usuario contem remover deve mostrar o nome de quem removeu o cadastro e o deleted_at', function () {
  /** @var User $admin */
  $admin = User::factory()->admin()->create();
  /** @var User $usuario */
  $usuario = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class)
    ->set('confirmacao_confirmation', $usuario->name)
    ->call('configuraModalDeConfirmacao', $usuario->id)
    ->call('destroy');

  $usuario->refresh();

  if (expect($usuario->remover_id === $admin->id) && !is_null($usuario->deleted_at)) {
    Livewire::test(Usuarios\Detalhar::class)
      ->set('usuario', $usuario)
      ->assertSee($usuario->remover->name)
      ->assertSee(date('d/m/Y H:i:s', strtotime($usuario->deleted_at)));
  }
});

test('se o usuario contem restorer_id deve mostrar o nome de quem restaurou o cadastro e o restored_at', function () {
  /** @var User $admin */
  $admin = User::factory()->admin()->create();
  /** @var User $usuario */
  $usuario = User::factory()->create();

  actingAs($admin);

  Livewire::test(Usuarios\Remover::class)
    ->set('confirmacao_confirmation', $usuario->name)
    ->call('configuraModalDeConfirmacao', $usuario->id)
    ->call('destroy');

  $usuario->refresh();

  Livewire::test(Usuarios\Restaurar::class)
    ->set('confirmacao_confirmation', $usuario->name)
    ->call('configuraModalDeConfirmacao', $usuario->id)
    ->call('restore');

  $usuario->refresh();

  if (expect($usuario->restored_id === $admin->id) && !is_null($usuario->restored_at)) {
    Livewire::test(Usuarios\Detalhar::class)
      ->set('usuario', $usuario)
      ->assertSee($usuario->restorer->name)
      ->assertSee(date('d/m/Y H:i:s', strtotime($usuario->restored_at)));
  }
});


it('deve ter certeza que estamos logando com o usuario impersonado', function () {
  $admin = User::factory()->admin()->create();
  $usuario = User::factory()->create();

  actingAs($admin);

  expect(auth()->id())->toBe($admin->id);

  Livewire::test(Usuarios\Impersonar::class)
    ->call('impersonar', $usuario->id)
    ->assertRedirect(route('dashboard'));

  get(route('dashboard'))
    ->assertSee(__("Você está impersonando :nome, clique aqui para voltar ao normal.", ["nome" => $usuario->name]));


  expect(auth()->id())->toBe($usuario->id);
});
