<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookStoreRequest;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): JsonResponse
  {
    // $books = DB::table('books')->get();
    $books = Book::all();
    return $this->ok($books, 'Berhasil Get Buku', 200);
    // return response()->json($response, 200);
    // return response()->json($response);
    // return response($response);
  }

  /**
   * Show the form for creating a new resource.
   */
  // public function create(): Response
  // {
  //   //
  // }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): JsonResponse
  {

    $validator = Validator::make($request->all(), [
      'book_code'         => 'required|string|max:255',
      'book_title'        => 'required|string|max:255',
      'author'            => 'required|string|max:255',
      'publisher'         => 'required|string|max:255',
      'stock'             => 'required|integer',
    ]);

    if($validator->fails()){
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }

    $store_book = [
      'book_code'         => $request->book_code,
      'book_title'        => $request->book_title,
      'author'            => $request->author,
      'publisher'         => $request->publisher,
      'available_stock'   => (int)$request->stock,
      'total_stock'   => (int)$request->stock,
    ];

    try {
      DB::beginTransaction();
      $store = Book::create($store_book);
    } catch (\Throwable $th) {
      DB::rollBack();
      return $this->error('gagal simpan buku', 500, ['error' => $th->getMessage()]);
    }
    DB::commit();
    return $this->ok($store, 'Berhasil Simpan Buku');
  }

  /**
   * Display the specified resource.
   */
  // public function show(string $id): Response
  // {
  //   //
  // }

  /**
   * Show the form for editing the specified resource.
   */
  // public function edit(string $id): Response
  // {
  //   //
  // }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id): JsonResponse
  {

    $book = Book::findOrFail($id);
    $loan = DB::table('loans')->where('book_id', '=', $id)->sum('total_loan');

    // return $this->ok(['book' => $book, 'loan' => gettype($loan)], '');

    $validator = Validator::make($request->all(), [
      'book_code'         => 'required|string|max:255',
      'book_title'        => 'required|string|max:255',
      'author'            => 'required|string|max:255',
      'publisher'         => 'required|string|max:255',
      'stock'             => 'required|integer',
    ]);

    
    if($validator->fails()){
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }
    
    if((int)$loan > $request->stock){
      return $this->error('stock tidak boleh kurang dari buku yang sudah di pinjam', 500, []);
    }

    $update_book = [
      'book_code'         => $request->book_code,
      'book_title'        => $request->book_title,
      'author'            => $request->author,
      'publisher'         => $request->publisher,
      'available_stock'   => (int)$request->stock - (int)$loan,
      'total_stock'       => (int)$request->stock,
    ];

    try {
      DB::beginTransaction();
      $book->update($update_book);
    } catch (\Throwable $th) {
      DB::rollBack();
      return $this->error('update error', 500, ['error' => $th->getMessage()]);
    }

    DB::commit();
    return $this->ok($book, 'berhasil update buku');

  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): JsonResponse
  {
    try {
      $book = Book::findOrFail($id);
      $book->delete();
      return $this->ok($book, 'berhasil dihapus');
    } catch (\Throwable $th) {
      return $this->error('gagal menghapus', 500, ['error' => $th->getMessage()]);
    }
  }
}
