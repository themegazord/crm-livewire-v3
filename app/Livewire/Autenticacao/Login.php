<?php

namespace App\Livewire\Autenticacao;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Str;
use Mary\Traits\Toast;

class Login extends Component
{
  use Toast;

  #[Rule(['required', 'email', 'max:255', 'exists:users'])]
  public ?string $email = null;
  #[Rule('required')]
  public ?string $password = null;

  #[Layout('components.layouts.guest')]
  public function render()
  {
    return view('livewire.autenticacao.login');
  }

  public function submit(): void {
    $this->validate();

    if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
      $this->addError('rateLimiter', "Too many requests, wait for " . RateLimiter::availableIn($this->throttleKey()) . " seconds.");
      return;
    }

    if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
      RateLimiter::hit($this->throttleKey());
      $this->addError('email', "Email and password they're incompatible");
      return;
    }

    $usuario = User::query()->where('email', $this->email)->first();
    Auth::login($usuario);

    $this->redirect(route('dashboard'));
  }

  public function throttleKey(): string {
    return Str::transliterate($this->email .'|'. request()->ip());
  }
}
