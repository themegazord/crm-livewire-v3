<?php

use App\Models\User;
use Livewire\Livewire;
use App\Livewire\Admin\Usuarios;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

it('deve ser capaz de adicionar a chave impersonar na sessao contendo o id do usuario', function () {

  $usuario = User::factory()->create();

  Livewire::test(Usuarios\Impersonar::class)
    ->call('impersonar', $usuario->id);

  assertTrue(session()->has('impersonar'));

  assertSame(session()->get('impersonar'), $usuario->id);
});
