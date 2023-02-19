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

    // if(Auth::attempt(['ni' => $request->ni, 'password' => $request->password])){
    //   $auth = Auth::user();
    //   $token = $auth->createToken('apiToken')->plainTextToken;
    //   $name = $auth->name;
    //   return $this->ok(['token' => $token, 'name' => $name], 'login berhasil');
    // }

    $user = User::where('ni', $request->ni)->first();

    if($user){
      if($user->role == 'Super Admin'){
        if(Hash::check($request->password, $user->password)){
          // create token
          // $user->tokens()->where('name', 'token')->delete();
          $token = $user->createToken('apiToken')->plainTextToken;

          return $this->ok(['token' => $token, 'user' => $user], 'login berhasil');
        }
        else{
          return $this->error('Password Salah', 422);
        }
      }
      if($user->role == 'Admin'){
        if(Hash::check($request->password, $user->password)){
          // create token
          // $user->tokens()->where('name', 'token')->delete();
          $token = $user->createToken('apiToken')->plainTextToken;

          return $this->ok(['token' => $token, 'user' => $user], 'login berhasil');
        }
        else{
          return $this->error('Password Salah', 422);
        }
      }
      if($user->role == 'Teachers'){
        if(Hash::check($request->password, $user->password)){
          // create token
          // $user->tokens()->where('name', 'token')->delete();
          $token = $user->createToken('apiToken')->plainTextToken;

          return $this->ok(['token' => $token, 'user' => $user], 'login berhasil');
        }
        else{
          return $this->error('Password Salah', 422);
        }
      }
      if($user->role == 'Students'){
        if(Hash::check($request->password, $user->password)){
          // create token
          // $user->tokens()->where('name', 'token')->delete();
          $token = $user->createToken('apiToken')->plainTextToken;

          return $this->ok(['token' => $token, 'user' => $user], 'login berhasil');
        }
        else{
          return $this->error('Password Salah', 422);
        }
      }
    }
    else{
      return $this->error('Pengguna Tidak Diketahui!', 404);
    }
  }
}
