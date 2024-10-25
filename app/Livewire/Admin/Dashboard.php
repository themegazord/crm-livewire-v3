<?php

namespace App\Livewire\Admin;

use App\Enum\Pode;
use Livewire\Component;

class Dashboard extends Component
{
  public function mount(): void {
    $this->authorize(Pode::SER_UM_ADMIN->value);
  }

  public function render()
  {
    return view('livewire.admin.dashboard');
  }
}
