<?php

namespace App\Models;

use App\Notifications\RecuperarSenhaNotification;
use App\Traits\Models\TemPermissoes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
  use AuditableTrait;

  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable, TemPermissoes, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'restorer_id',
    'remover_id',
    'restored_at',
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

  public function restorer(): BelongsTo {
    return $this->belongsTo(User::class, 'restorer_id', 'id');
  }

  public function remover(): BelongsTo {
    return $this->belongsTo(User::class, 'remover_id', 'id');
  }
}
