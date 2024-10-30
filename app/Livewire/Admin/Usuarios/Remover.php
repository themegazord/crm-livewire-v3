<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Remover extends Component
{
  public User $usuario;
  #[Rule(['required', 'confirmed'])]
  public string $confirmacao = "MEGAZORDE";

  public string $confirmacao_confirmation = "";

  public function render()
  {
    return view('livewire.admin.usuarios.remover');
  }

  public function destroy(): void {
    $this->validate();
    $this->usuario->delete();
    $this->dispatch('usuario::deletado');
  }
}
