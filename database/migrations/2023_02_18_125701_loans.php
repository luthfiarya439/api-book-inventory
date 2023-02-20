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
    Schema::create('loans', function (Blueprint $table) {
      // $table->id();
      $table->uuid('id')->primary();
      // $table->foreignId('user_id');
      // $table->foreignId('book_id');
      $table->uuid('user_id');
      $table->uuid('book_id');
      $table->unsignedInteger('total_loan');
      $table->integer('loan_code');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('loans');
  }
};
