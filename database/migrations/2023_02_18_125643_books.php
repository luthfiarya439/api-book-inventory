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
    Schema::create('books', function (Blueprint $table) {
      // $table->id();
      $table->uuid('id')->primary();
      $table->string('book_code')->unique();
      $table->string('book_title');
      $table->string('author');
      $table->string('publisher');
      $table->unsignedInteger('available_stock');
      $table->unsignedInteger('total_stock');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('books');
  }
};
