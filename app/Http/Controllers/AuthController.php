<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  public function login(Request $request): JsonResponse
  {
    $validator = Validator::make($request->all(), [
      'ni'        => 'required',
      'password'  => 'required|min:8'
    ]);

    if($validator->fails()){
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }

    if(Auth::attempt(['ni' => $request->ni, 'password' => $request->password])){
      $auth = Auth::user();
      $token = $auth->createToken('apiToken')->plainTextToken;
      $name = $auth->name;
      return $this->ok(['token' => $token, 'name' => $name], 'login berhasil');
    }
    else{
      return $this->error('nomor induk atau password salah', 404, []);
    }
  }

  public function logout(Request $request)
  {
    $user = $request->user();
    $user->currentAccessToken()->delete();

    return $this->ok('', 'berhasil logout');
  }
}
