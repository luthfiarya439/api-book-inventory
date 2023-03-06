<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Book([
            'book_code'             => $row['kode_buku'],
            'book_title'            => $row['judul_buku'],
            'author'                => $row['penulis'],
            'publisher'             => $row['penerbit'],
            'available_stock'       => $row['stok_tersedia'],
            'total_stock'           => $row['total_stok']
        ]);
    }
}
