<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('permissoes', function (Blueprint $table) {
      $table->id();
      $table->string('permissao');
      $table->timestamps();
    });

    Schema::create('permissao_user', function (Blueprint $table) {
      $table->foreignId('user_id');
      $table->foreignId('permissao_id');
      $table->index(['user_id', 'permissao_id']);
      $table->unique(['user_id', 'permissao_id']);
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permissoes');
    Schema::dropIfExists('permissao_user');
  }
};
