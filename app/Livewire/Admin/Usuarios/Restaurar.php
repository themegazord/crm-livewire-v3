<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Notifications\UsuarioResetadoNotification;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Restaurar extends Component
{
  use Toast;

  #[Rule(rule: [
    'required', 'confirmed'
  ], message: [
    'confirmed' => 'O que você inseriu não condiz com o que foi solicitado.'
  ])]
  public ?string $confirmacao = null;
  public string $confirmacao_confirmation = '';
  public User $usuario;
  public bool $modal = false;


  public function render()
  {
    return view('livewire.admin.usuarios.restaurar');
  }

  #[On("usuario::resetar")]
  public function configuraModalDeConfirmacao(int $usuarioId): void {
    $this->usuario = User::withTrashed()->find($usuarioId);
    $this->confirmacao = $this->usuario->name;
    $this->modal = true;
  }

  public function restore(): void {
    $this->validate();

    $this->usuario->restore();

    $this->usuario->update([
      'restorer_id' => auth()->user()->id,
      'restored_at' => now()
    ]);

    $this->usuario->notify(new UsuarioResetadoNotification($this->usuario->name));

    $this->dispatch("usuario::restaurado");
    $this->reset(['modal', 'confirmacao_confirmation']);
    $this->success("Usuário reativado com sucesso");
  }
}
