<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'book_code'         => 'required|string|max:255',
      'book_title'        => 'required|string|max:255',
      'author'            => 'required|string|max:255',
      'publisher'         => 'required|string|max:255',
      'available_stock'   => 'required|integer',
      'total_stock'       => 'required|integer',
    ];
  }
}
