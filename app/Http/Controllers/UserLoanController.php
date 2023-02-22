<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserLoanController extends Controller
{
  public function index()
  {
    $user = auth()->user()->id;
    $my_loans = DB::table('loans')
      ->join('users', 'loans.user_id', '=', 'users.id')
      ->join('books', 'loans.book_id', '=', 'books.id')
      ->select('loans.id', 'users.name', 'loans.book_id', 'books.book_title', 'loans.total_loan', 'loans.loan_code')
      ->where('loans.user_id','=', $user)
      ->get();

    return $this->ok($my_loans, 'berhasil get pinjaman user', 200);
  }
}
