<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Logout extends Component
{
  public function render()
  {
    return view('livewire.logout');
  }

  public function logout(): void {
    Auth::logout();

    Session::invalidate();
    Session::regenerateToken();

    $this->redirect(route('login'));
  }
}
