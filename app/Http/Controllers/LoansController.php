<?php

namespace App\Http\Controllers;

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
  public function index(): JsonResponse
  {
    $loans = DB::table('loans')
      ->join('users', 'loans.user_id', '=', 'users.id')
      ->join('books', 'loans.book_id', '=', 'books.id')
      ->select('loans.id', 'users.name', 'loans.book_id', 'books.book_title', 'loans.total_loan', 'loans.loan_code')
      ->get();
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
  public function store(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'book_id'       => 'required|string',
      'total_loan'    => 'required|integer'
    ]);

    if ($validator->fails()) {
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }

    // return $this->ok($request->book_id, '', 200);

    $book = Book::findOrFail($request->book_id);

    $loan_data = [
      'user_id'     => auth()->user()->id,
      'book_id'     => $request->book_id,
      'total_loan'  => $request->total_loan,
      'loan_code'   => auth()->user()->id . '/' . UUID::uuid4()
    ];

    if ($book) {
      try {
        DB::beginTransaction();
        Book::where('id', $request->book_id)->decrement('available_stock', $request->total_loan);
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
  public function update(Request $request, string $id): JsonResponse
  {

    $get_loan = Loan::findOrFail($id);
    // $sum_loan = DB::table('loans')->where('id', '=', $id)->sum('total_loan');
    $sum_loan = DB::table('loans')->where('id', '=', $id)->get();

    $validator = Validator::make($request->all(), [
      'book_id'     => 'required|string',
      'loan_code'   => 'required|string',
      'return'      => 'required|integer'
    ]);

    if($validator->fails()){
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }
    
    DB::beginTransaction();
    if((int)$request->return == (int)$sum_loan[0]->total_loan){
      try {
        $loan = Loan::where('id', $id)->where('loan_code', $request->loan_code)->firstOrFail();
        $loan->delete();
      } catch (\Throwable $th) {
        return $this->error('error', 500, ['error' => $th->getMessage()]);
        
      }
      try {
        Book::where('id', $request->book_id)->increment('available_stock', $request->return);
      } catch (\Throwable $th) {
        DB::rollBack();
        return $this->error('error', 500, ['error' => $th->getMessage()]);
      }
    }
    elseif((int)$request->return < (int)$sum_loan[0]->total_loan){
      try {
        $loan = Loan::where('id', $id)->where('loan_code', $request->loan_code)->decrement('total_loan', $request->return);
        Book::where('id', $request->book_id)->increment('available_stock', $request->return);
      } catch (\Throwable $th) {
        DB::rollBack();
        return $this->error('error', 500, ['error' => $th->getMessage()]);
      }
    }
    // elseif($sum_loan < $request->return){
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
