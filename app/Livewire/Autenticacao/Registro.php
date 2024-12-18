<?php

namespace App\Livewire\Autenticacao;

use App\Models\User;
use App\Notifications\BemVindoNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Registro extends Component
{
  #[Rule(['required', 'max:255'])]
  public ?string $name = null;
  #[Rule(['required', 'email', 'max:255', 'confirmed', 'unique:users,email'])]
  public ?string $email = null;
  public ?string $email_confirmation = null;
  #[Rule(['required'])]
  public ?string $password = null;

  #[Layout('components.layouts.guest')]
  public function render()
  {
    return view('livewire.autenticacao.registro');
  }

  public function submit(): void {
    $this->validate();

    $user = User::query()->create([
      'name' => $this->name,
      'email' => $this->email,
      'password' => $this->password
    ]);

    Auth::login($user);

    $user->notify(new BemVindoNotification);

    $this->redirect('/');
  }
}
