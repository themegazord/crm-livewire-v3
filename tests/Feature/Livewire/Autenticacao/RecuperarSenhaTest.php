<?php

use App\Livewire\Autenticacao\RecuperarSenha;
use App\Models\User;
use App\Notifications\RecuperarSenhaNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

it('deve ser capaz de renderizar o componente', function () {
  Livewire::test(RecuperarSenha::class)
    ->assertOk();
});

it('deve ser capaz de validar o email corretamente', function ($objeto) {
  Livewire::test(RecuperarSenha::class)
    ->set($objeto->campo, $objeto->value)
    ->call('submit')
    ->assertHasErrors([$objeto->campo => $objeto->rule]);
})->with([
  'email::required' => (object)['campo' => 'email', 'value' => '', 'rule' => 'required'],
  'email::max:255' => (object)['campo' => 'email', 'value' => str_repeat('*'.'@doe.com', 256), 'rule' => 'max'],
  'email::email' => (object)['campo' => 'email', 'value' => 'not-a-email', 'rule' => 'email'],
]);

it('deve ser capaz de enviar uma notificacao para o email informado e redireciona para login', function () {
  Notification::fake();

  $user = User::factory()->create(['email' => 'joe@doe.com']);

  Livewire::test(RecuperarSenha::class)
    ->set('email', $user->email)
    ->call('submit')
    ->assertRedirect(route('login'));

  Notification::assertSentTo($user, RecuperarSenhaNotification::class);
});
