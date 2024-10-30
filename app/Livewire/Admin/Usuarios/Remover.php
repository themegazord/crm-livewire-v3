<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Notifications\UsuarioDeletadoNotification;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Remover extends Component
{
  public ?User $usuario = null;
  #[Rule(['required', 'confirmed'], message: [
    'confirmed' => "O que você inseriu não condiz com o nome do usuário."
  ])]
  public ?string $confirmacao = "";
  public string $confirmacao_confirmation = "";

  public bool $modal = false;

  public function render()
  {
    return view('livewire.admin.usuarios.remover');
  }

  #[On("usuario::deletar")]
  public function configuraModalDeConfirmacao(int $usuarioId): void {
    $this->usuario = User::select(['id', 'name'])->find($usuarioId);
    $this->confirmacao = $this->usuario->name;
    $this->modal = true;
  }

  public function destroy(): void {
    $this->validate();
    $this->usuario->delete();
    $this->usuario->notify(new UsuarioDeletadoNotification($this->usuario->name));
    $this->dispatch('usuario::deletado');
    $this->reset(['modal', 'confirmacao_confirmation']);
  }
}
