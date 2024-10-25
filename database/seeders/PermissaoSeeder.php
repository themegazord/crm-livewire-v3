<?php

namespace Database\Seeders;

use App\Models\Permissao;
use App\Models\Pode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissaoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Permissao::create([
      'permissao' => Pode::SER_UM_ADMIN->value
    ]);
  }
}
