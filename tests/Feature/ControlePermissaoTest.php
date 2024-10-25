<?php

use App\Models\Permissao;
use App\Models\User;
use Database\Seeders\PermissaoSeeder;
use Database\Seeders\UsersSeeder;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\seed;

it("deve ser capaz de dar ao usuario uma permissao de fazer qualquer coisa", function () {
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


test('permissao tera de ter um seeder', function () {

  seed(PermissaoSeeder::class);

  assertDatabaseHas('permissoes', [
    'permissao' => 'ser um admin'
  ]);
});

test('alimentando com um usuario administrativo', function () {
  seed([PermissaoSeeder::class, UsersSeeder::class]);

  assertDatabaseHas('permissoes', [
    'permissao' => 'ser um admin'
  ]);

  assertDatabaseHas('permissao_user', [
    'user_id' => User::first()?->id,
    'permissao_id' => Permissao::query()->where('permissao', 'ser um admin')->first()?->id,
  ]);
});
