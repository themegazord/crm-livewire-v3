<?php

namespace App\Livewire\Admin\Usuarios;

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

  public function impersonar(int $id): void {
    session()->put('impersonar', $id);
  }
}
