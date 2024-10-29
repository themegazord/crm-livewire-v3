<?php

use App\Enum\Pode;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Usuarios;
use App\Models\Permissao;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

it('deve bloquear o acesso dos usuarios que não contem permissao de admin', function () {
  /** @var User $usuario */
  $usuario = User::factory()->create();

  actingAs($usuario);

  Livewire::test(Dashboard::class)
    ->assertForbidden();

  get(route('admin.dashboard'))
    ->assertForbidden();
});


it('deve conter a rota para listagem dos usuarios na rotina administrativa', function () {
  actingAs(User::factory()->admin()->create());

  get(route('admin.listagem.usuarios'))
  ->assertOk();
});

it('deve renderizar a view de listagem de usuarios', function () {
  actingAs(User::factory()->admin()->create());

  Livewire::test(Usuarios\Listagem::class)
  ->assertOk();
});

test('checar o formato da tabela', function () {
  actingAs(User::factory()->admin()->create());

  Livewire::test(Usuarios\Listagem::class)
  ->assertSet('headers', [
      ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
      ['key' => 'name', 'label' => 'Nome do usuário'],
      ['key' => 'email', 'label' => 'Email do usuário'],
      ['key' => 'permissoes', 'label' => 'Permissões', 'sortable' => false]
    ]);
});

it ('deve ser capaz de filtrar por nome e email', function () {
  $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);
  $mario = User::factory()->create(['name' => 'Mario', 'email' => 'little_guy@gmail.com']);

  actingAs($admin);

  Livewire::test(Usuarios\Listagem::class)
    ->assertSet('usuarios', function ($usuarios) {
      expect($usuarios)
        ->toHaveCount(2);

      return true;
    })
    ->set('consulta', 'mar')
    ->assertSet('usuarios',function ($usuarios) {
      expect($usuarios)
        ->toHaveCount(1)
      ->first()
      ->name->toBe('Mario');

      return true;
    })
    ->set('consulta', 'admin')
    ->assertSet('usuarios',function ($usuarios) {
      expect($usuarios)
        ->toHaveCount(1)
      ->first()
      ->name->toBe('Joe Doe');

      return true;
    })
    ->set('consulta', 1)
    ->assertSet('usuarios',function ($usuarios) {
      expect($usuarios)
        ->toHaveCount(1)
      ->first()
      ->name->toBe('Joe Doe');

      return true;
    });
});

it ('deve ser capaz de filtrar por permissao', function () {
  $admin = User::factory()->admin()->create(['name' => 'Joe Doe', 'email' => 'admin@gmail.com']);
  $guest = User::factory()->create(['name' => 'Mario', 'email' => 'little_guy@gmail.com']);
  $guest->darPermissao(Pode::TESTANDO->value);
  $permissao = Permissao::where('permissao', Pode::SER_UM_ADMIN->value)->first();
  $permissao2 = Permissao::where('permissao', Pode::TESTANDO->value)->first();
  actingAs($admin);

  Livewire::test(Usuarios\Listagem::class)
    ->assertSet('usuarios', function ($usuarios) {
      expect($usuarios)
        ->toHaveCount(2);

      return true;
    })
    ->set('permissaoConsulta', [$permissao->id, $permissao2->id])
    ->assertSet('usuarios',function ($usuarios) {
      expect($usuarios)
        ->toHaveCount(2);

      return true;
    });
});

