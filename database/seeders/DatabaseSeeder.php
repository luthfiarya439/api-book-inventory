<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    // \App\Models\User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);
    // DB::table('users')->insert([
    //   'name' => 'Admin',
    //   'ni'   => '1',
    //   'password' => bcrypt('password'),
    //   'role' => 'Super Admin'
    // ]);

    User::create([
      'name' => 'Admin',
      'ni'   => '1',
      'password' => bcrypt('password'),
      'role' => 'Admin'
    ]);

    User::create([
      'name' => 'Teacher',
      'ni'   => '2',
      'password' => bcrypt('password'),
      'role' => 'User'
    ]);

    User::create([
      'name' => 'Student 1',
      'ni'   => '210101',
      'password' => bcrypt('password'),
      'role' => 'User'
    ]);

    User::create([
      'name' => 'Student 2',
      'ni'   => '210102',
      'password' => bcrypt('password'),
      'role' => 'User'
    ]);

    User::create([
      'name' => 'Student 3',
      'ni'   => '210103',
      'password' => bcrypt('password'),
      'role' => 'User'
    ]);
    

    Book::factory(20)->create();
  }
}
