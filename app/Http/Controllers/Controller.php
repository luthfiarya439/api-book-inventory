<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  protected function ok($data = null, string $message = '', int $status = 200)
  {
    return response()->json(
      [
        'success'     => true,
        'code'        => $status,
        'message'     => $message,
        'data'        => $data
      ]
    );
    // return response()->json($data, $status);
    // return $this->responseJson($data, $message, $status);
  }

  protected function error($message, int $status = 500, array $errors = [])
  {
    return response()->json([
      'success'   => false,
      'code'      => $status,
      'message'   => $message,
      'errors'    => $errors
    ], $status);
  }
}
