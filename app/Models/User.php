<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\RecuperarSenhaNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable;

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

  public function darPermissao(string $permissao): void {
    $this->permissoes()->firstOrCreate(['permissao' => $permissao]);

    Cache::forget($this->permissaoChaveCache());
    Cache::rememberForever($this->permissaoChaveCache(), fn () => $this->permissoes);
  }

  public function temPermissao(string $permissao): bool {
    /** @var Collection $permissoes */
    $permissoes = Cache::get($this->permissaoChaveCache(), $this->permissoes);

    return $permissoes->where('permissao', $permissao)->isNotEmpty();
  }

  private function permissaoChaveCache(): string {
    return "user::{$this->id}::permissoes";
  }
}
