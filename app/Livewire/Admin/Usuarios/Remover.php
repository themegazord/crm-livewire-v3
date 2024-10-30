<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use App\Notifications\UsuarioDeletadoNotification;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Remover extends Component
{
  public User $usuario;
  #[Rule(['required', 'confirmed'], message: [
    'confirmed' => "O que você inseriu não condiz com o nome do usuário."
  ])]
  public string $confirmacao = "";
  public string $confirmacao_confirmation = "";

  public bool $modal = false;

  public function mount(): void {
    $this->confirmacao = $this->usuario->name;
  }

  public function render()
  {
    return view('livewire.admin.usuarios.remover');
  }

  public function destroy(): void {
    $this->validate();
    $this->usuario->delete();
    $this->usuario->notify(new UsuarioDeletadoNotification($this->usuario->name));
    $this->dispatch('usuario::deletado');
  }
}
