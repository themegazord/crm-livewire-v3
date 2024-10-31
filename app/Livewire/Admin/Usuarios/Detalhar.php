<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Detalhar extends Component
{
  public ?User $usuario = null;
  public bool $modal = false;

  public function render()
  {
    return view('livewire.admin.usuarios.detalhar');
  }

  #[On("usuario::detalhar")]
  public function configuraModalDeDetalhamento(int $usuarioId): void {
    $this->usuario = User::withTrashed()->find($usuarioId);
    $this->modal = true;
  }
}
