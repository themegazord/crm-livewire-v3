<?php

use App\Models\Permissao;
use App\Models\User;
use Database\Seeders\PermissaoSeeder;
use Database\Seeders\UsersSeeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\actingAs;
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

it("deveria bloquear o acesso as paginas administrativas caso o usuario nao seja um admin", function () {
  $usuario = User::factory()->create();
  actingAs($usuario)
    ->get(route('admin.dashboard'))
    ->assertForbidden();
});

test("vamos ter certeza que as permissoes estao sendo gravadas em cache", function () {
  $usuario = User::factory()->create();

  $usuario->darPermissao('ser um admin');

  $chaveCache = "user::{$usuario->id}::permissoes";

  expect(Cache::has($chaveCache))->toBeTrue('Checando se a chave existe em cache')
    ->and(Cache::get($chaveCache))->toBe($usuario->permissoes, 'Checando se a permissão em cache é a mesma que o usuário contem');
});

test('vamos ter certeza que nos vamos usar o cache para receber e/ou checar quando o usuario tiver as permissoes solicitadas', function () {
  $usuario = User::factory()->create();

  $usuario->darPermissao('ser um admin');

  // Verificar se nao tivemos nenhum hit no banco de dados a partir desse ponto

  DB::listen(fn ($query) => throw new Exception('fomos acertados'));

  $usuario->temPermissao('ser um admin');

  expect(true)->toBeTrue();
});
