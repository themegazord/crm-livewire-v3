<?php

namespace App\Livewire\Autenticacao;

use Illuminate\Notifications\Notification;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RecuperarSenha extends Component
{
  #[Rule(['required', 'email', 'max:255'])]
  public ?string $email = null;

  public function render()
  {
    return view('livewire.autenticacao.recuperar-senha');
  }

  public function submit(): void {
    $this->validate();


  }
}
