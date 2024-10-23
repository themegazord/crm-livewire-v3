<?php

namespace App\Livewire\Autenticacao;

use App\Models\User;
use Livewire\Component;

class Registro extends Component
{
  public ?string $name;
  public ?string $email;
  public ?string $email_confirmation;
  public ?string $password;

  public function render()
  {
    return view('livewire.autenticacao.registro');
  }

  public function submit(): void {
    User::query()->create([
      'name' => $this->name,
      'email' => $this->email,
      'password' => $this->password
    ]);
  }
}
