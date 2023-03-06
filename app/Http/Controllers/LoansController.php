<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddLoanRequest;
use App\Http\Requests\UpdateLoanRequest;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class LoansController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request): JsonResponse
  {
    $loans = DB::table('loans')
      ->join('users', 'loans.user_id', '=', 'users.id')
      ->join('books', 'loans.book_id', '=', 'books.id')
      ->select('loans.id', 'users.name', 'loans.book_id', 'books.book_title', 'loans.total_loan', 'loans.loan_code')
      ->where('loan_code', 'like', '%' . $request->get('search') . '%')
      // ->orderBy('created_at', 'desc')
      ->paginate($request->get('per_page', 10));
      // ->get();
    return $this->ok($loans, 'berhasil get pinjaman', 200);
  }

  /**
   * Show the form for creating a new resource.
   */
  // public function create(): Response
  // {
  //     //
  // }

  /**
   * Store a newly created resource in storage.
   */
  public function store(AddLoanRequest $request): JsonResponse
  {
 
    $book = Book::findOrFail($request->validated('book_id'));

    $loan_data = [
      'user_id'     => auth()->user()->id,
      'book_id'     => $request->validated('book_id'),
      'total_loan'  => $request->validated('total_loan'),
      'loan_code'   => date('YmdHis'). '-' .rand(0, 100). '-' .rand(101, 200)
    ];

    if ($book) {
      try {
        DB::beginTransaction();
        $book->decrement('available_stock', $request->validated('total_loan'));
        Loan::create($loan_data);
      } catch (\Throwable $th) {
        DB::rollBack();
        return $this->error('error', 500, ['error' => $th->getMessage()]);
      }
      DB::commit();
      return $this->ok($loan_data, 'berhasil meminjam', 200);
    } else {
      return $this->error('buku tidak ada', 500, []);
    }
  }

  /**
   * Display the specified resource.
   */
  // public function show(string $id): Response
  // {
  //     //
  // }

  /**
   * Show the form for editing the specified resource.
   */
  // public function edit(string $id): Response
  // {
  //     //
  // }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateLoanRequest $request, string $id): JsonResponse
  {
    $get_loan = Loan::where('id', $id)->where('loan_code', $request->validated('loan_code'))->firstOrFail();
    $get_book = Book::where('id', $get_loan->book_id)->firstOrFail();
    DB::beginTransaction();
    if((int)$request->validated('return') == (int)$get_loan->total_loan){
      try {
        $get_loan->delete();
        $get_book->increment('available_stock', $request->validated('return'));
      } catch (\Throwable $th) {
        DB::rollBack();
        return $this->error('error', 500, ['error' => $th->getMessage()]);
      }
    }
    elseif((int)$request->return < (int)$get_loan->total_loan){
      try {
        $get_loan->decrement('total_loan', $request->validated('return'));
        $get_book->increment('available_stock', $request->validated('return'));
      } catch (\Throwable $th) {
        DB::rollBack();
        return $this->error('error', 500, ['error' => $th->getMessage()]);
      }
    }
    else{
      return $this->error('tidak bisa mengembalikan lebih dari yang di pinjam', 500, []);
    }

    DB::commit();
    return $this->ok('', 'berhasil mengembalikan buku');
  }

  /**
   * Remove the specified resource from storage.
   */
  // public function destroy(string $id): RedirectResponse
  // {
  //     //
  // }
}
