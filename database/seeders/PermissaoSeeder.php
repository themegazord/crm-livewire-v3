<?php

namespace Database\Seeders;

use App\Models\Permissao;
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
      'permissao' => 'ser um admin'
    ]);
  }
}
