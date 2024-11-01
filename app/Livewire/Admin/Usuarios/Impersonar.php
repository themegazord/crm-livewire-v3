<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Impersonar extends Component
{
  public function render()
  {
    return <<<'HTML'
        <div>
            {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
        </div>
        HTML;
  }

  #[On("usuario::impersonar")]
  public function impersonar(int $usuarioId): void {
    /** @var Authenticatable|User $admin */

    $admin = Auth::user();
    session()->put('impersonar', $usuarioId);
    $admin->impersonate(User::find($usuarioId), 'web');
    $this->redirect(route('dashboard'), true);
  }
}
