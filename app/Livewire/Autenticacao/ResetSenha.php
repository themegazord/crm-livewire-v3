<?php

namespace App\Livewire\Autenticacao;

use App\Models\User;
use App\Notifications\ResetSenhaNotification;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;

class ResetSenha extends Component
{
  use Toast;

  public ?string $token = null;
  public ?string $email = null;
  #[Rule(['required', 'confirmed'])]
  public ?string $password = null;
  public ?string $password_confirmation = null;
  private User $usuario;

  public function mount(string $token = null, string $email = null): void
  {
    $this->token = $token;
    $this->email = $email;
  }

  #[Layout('components.layouts.guest')]
  public function render()
  {
    return view('livewire.autenticacao.reset-senha');
  }

  public function rendered(): void
  {
    if (empty($this->token) || empty($this->email)) {
      Session::flash('status.warning', 'Token ou email inexistentes.');
      $this->redirect('/login');
    }
  }

  public function submit(): void
  {
    $this->validate();

    $status = Password::reset([
      'email' => $this->email,
      'password' => $this->password,
      'password_confirmation' => $this->password_confirmation,
      'token' => $this->token
    ], function (User $user, string $password) {
      $user->forceFill([
        'password' => Hash::make($password)
      ])->setRememberToken(Str::random(60));

      $user->save();

      $this->usuario = $user;
    });

    if ($status === PasswordBroker::PASSWORD_RESET) {
      $this->usuario->notify(new ResetSenhaNotification($this->usuario->name));

      Auth::login($this->usuario);

      $this->redirect(route('dashboard'));
    } else {
      back();
    }
  }
}
