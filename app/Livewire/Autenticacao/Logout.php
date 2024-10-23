<?php

namespace App\Livewire\Autenticacao;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Logout extends Component
{
  public function render()
  {
    return <<<BLADE
      <div>
        <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" wire:click="logout" />
      </div>
    BLADE;
  }

  public function logout(): void
  {
    Auth::logout();

    Session::invalidate();
    Session::regenerateToken();

    $this->redirect(route('login'));
  }
}
