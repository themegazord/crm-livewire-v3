<?php

namespace Database\Seeders;

use App\Enum\Pode;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    User::factory()
      ->comPermissao(Pode::SER_UM_ADMIN->value)
      ->create([
        'name' => 'Admin do CRM',
        'email' => 'admin@crm.com',
      ]);

    User::factory(50)->create();
  }
}
