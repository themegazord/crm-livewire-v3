<?php

use App\Livewire\Admin\Dashboard;
use App\Models\User;
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
