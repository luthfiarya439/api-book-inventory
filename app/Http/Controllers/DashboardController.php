<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  public function index()
  {
    $total_stock = DB::table('books')->sum('total_stock');
    $available_stock = DB::table('books')->sum('available_stock');
    $loaned = DB::table('loans')->sum('total_loan');

    return $this->ok([
      'total_stock' => $total_stock,
      'available_stock' => $available_stock,
      'loaned'  => $loaned
    ], 'berhasil');
  }
}
