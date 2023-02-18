<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): Response
  {
    // $books = DB::table('books')->get();
    $books = Book::all();
    $response = [
      'success' => true,
      'data'    => $books
    ];
    return $this->ok($books, 'Success');
    // return response($response);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): Response
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id): Response
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id): Response
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id): RedirectResponse
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): RedirectResponse
  {
    //
  }
}
