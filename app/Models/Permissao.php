<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permissao extends Model
{
  protected $table = 'permissoes';
  protected $fillable = [
    'permissao'
  ];

  public function usuarios(): BelongsToMany {
    return $this->belongsToMany(User::class);
  }
}
