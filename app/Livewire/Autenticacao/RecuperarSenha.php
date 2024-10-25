<?php

namespace App\Livewire\Autenticacao;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class RecuperarSenha extends Component
{
  use Toast;

  #[Rule(['required', 'email', 'max:255'])]
  public ?string $email = null;

  #[Layout('components.layouts.guest')]
  public function render()
  {
    return view('livewire.autenticacao.recuperar-senha');
  }

  public function submit(): void
  {
    $this->validate();
    $user = User::query()->where('email', $this->email)->first();
    if (!is_null($user)) {
      Password::sendResetLink(
        ['email' => $this->email]
      );
    }
    Session::flash('status.success', 'Email enviado com sucesso');
    $this->redirect('/login');
  }
}
