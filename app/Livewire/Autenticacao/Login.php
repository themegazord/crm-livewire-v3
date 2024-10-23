<?php

namespace App\Livewire\Autenticacao;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class Login extends Component
{
  #[Rule(['required', 'email', 'max:255', 'exists:users'])]
  public ?string $email = null;
  #[Rule('required')]
  public ?string $password = null;

  #[Layout('components.layouts.guest')]
  public function render()
  {
    return view('livewire.autenticacao.login');
  }

  public function submit(): MessageBag|Redirector|RedirectResponse|self {
    $this->validate();

    if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
      $this->addError('email', "Email and password they're incompatible");
      return $this;
    }

    $usuario = User::query()->where('email', $this->email)->first();
    Auth::login($usuario);

    return redirect('/');
  }
}
