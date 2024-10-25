<?php

namespace App\Providers;

use App\Enum\Pode;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Can;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    foreach(Pode::cases() as $pode) {
      Gate::define($pode->value, fn (User $usuario) => $usuario->temPermissao($pode->value));
    }
  }
}
