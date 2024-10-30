<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;

class Remover extends Component
{
  public User $usuario;

  public function render()
  {
    return view('livewire.admin.usuarios.remover');
  }

  public function destroy(): void {
    $this->usuario->delete();
    $this->dispatch('usuario::deletado');
  }
}
