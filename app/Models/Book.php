<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
  use HasFactory, HasUuids;

  protected $fillable = [
    'book_code',
    'book_title',
    'author',
    'publisher',
    'stock',
  ];
}
