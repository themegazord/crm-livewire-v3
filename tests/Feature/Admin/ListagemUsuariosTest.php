<?php

use App\Enum\Pode;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Usuarios;
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

