<?php

namespace App\Models;

use App\Notifications\RecuperarSenhaNotification;
use App\Traits\Models\TemPermissoes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable, TemPermissoes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * Get the attributes that should be cast.
   *
   * @return array<string, string>
   */
  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
    ];
  }

  public function sendPasswordResetNotification($token): void
  {
    $this->notify(new RecuperarSenhaNotification($token, $this->getEmailForPasswordReset()));
  }

  public function permissoes(): BelongsToMany {
    return $this->belongsToMany(Permissao::class);
  }
}
