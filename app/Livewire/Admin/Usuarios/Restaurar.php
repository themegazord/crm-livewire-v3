<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
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

  public function configuraModalDeConfirmacao(int $usuario_id): void {
    $this->usuario = User::withTrashed()->find($usuario_id);
    $this->confirmacao = $this->usuario->name;
    $this->modal = true;
  }

  public function restore(): void {
    $this->validate();

    $this->usuario->restore();
    $this->dispatch("usuario::restaurado");
    $this->reset(['modal', 'confirmacao_confirmation']);
    $this->success("Usuário reativado com sucesso");
  }
}
