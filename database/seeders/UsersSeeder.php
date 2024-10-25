<?php

namespace Database\Seeders;

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
      ->comPermissao('ser um admin')
      ->create([
        'name' => 'Admin do CRM',
        'email' => 'admin@crm.com',
      ]);
  }
}
