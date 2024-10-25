<?php

use App\Models\Permissao;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it ("deve ser capaz de dar ao usuario uma permissao de fazer qualquer coisa", function () {
  $usuario = User::factory()->create();

  $usuario->darPermissao('ser um admin');

  expect($usuario)
    ->temPermissao('ser um admin')
    ->toBeTrue();

  assertDatabaseHas('permissoes', [
    'permissao' => 'ser um admin'
  ]);

  assertDatabaseHas('permissao_user', [
    'user_id' => $usuario->id,
    'permissao_id' => Permissao::query()->where('permissao', 'ser um admin')->first()->id,
  ]);
});
