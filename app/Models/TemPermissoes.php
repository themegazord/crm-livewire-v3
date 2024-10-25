<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait TemPermissoes
{
  public function permissoes(): BelongsToMany
  {
    return $this->belongsToMany(Permissao::class);
  }

  public function darPermissao(string $permissao): void
  {
    $this->permissoes()->firstOrCreate(['permissao' => $permissao]);

    Cache::forget($this->permissaoChaveCache());
    Cache::rememberForever($this->permissaoChaveCache(), fn() => $this->permissoes);
  }

  public function temPermissao(string $permissao): bool
  {
    /** @var Collection $permissoes */
    $permissoes = Cache::get($this->permissaoChaveCache(), $this->permissoes);

    return $permissoes->where('permissao', $permissao)->isNotEmpty();
  }

  private function permissaoChaveCache(): string
  {
    return "user::{$this->id}::permissoes";
  }
}
