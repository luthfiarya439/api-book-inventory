<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): JsonResponse
  {
    $users = User::all();
    return $this->ok($users, 'berhasil get user');
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
      'name'                => 'required',
      'ni'                  => 'required|unique:users,ni',
      'password'            => 'required|min:8|max:20',
      'repeat_password'     => 'required|same:password',
      'role'                => 'required'
    ]);

    if ($validator->fails()) {
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }

    $users = [
      'name'      => $request->name,
      'ni'        => $request->ni,
      'password'  => bcrypt($request->password),
      'role'      => $request->role,
    ];

    try {
      DB::beginTransaction();
      $result = User::create($users);
    } catch (\Throwable $th) {
      DB::rollBack();
      return $this->error('error', 500, ['error' => $th->getMessage()]);
    }

    DB::commit();
    return $this->ok($result, 'berhasil tambah user');
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

    $user = User::findOrFail($id);

    if (empty($request->password)) {
      $validator = Validator::make($request->all(), [
        'name'        => 'required|string',
        'ni'          => 'required|string',
        'role'        => 'required|string'
      ]);

      $update_user = [
        'name'      => $request->name,
        'ni'        => $request->ni,
        'role'      => $request->role
      ];
    } else {
      $validator = Validator::make($request->all(), [
        'name'        => 'required|string',
        'ni'          => 'required|string',
        'password'    => 'required|min:8|max:20',
        'role'        => 'required|string'
      ]);

      $update_user = [
        'name'      => $request->name,
        'ni'        => $request->ni,
        'password'  => bcrypt($request->password),
        'role'      => $request->role
      ];
    }

    if ($validator->fails()) {
      return $this->error('error validasi', 500, ['error' => $validator->messages()->all()]);
    }

    try {
      DB::beginTransaction();
      $user->update($update_user);
    } catch (\Throwable $th) {
      DB::rollBack();
      return $this->error('error', 500, ['error' => $th->getMessage()]);
    }

    DB::commit();
    return $this->ok($user, 'berhasil update user');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id): JsonResponse
  {
    
    try {
      $user = User::findOrFail($id);
      $user->delete();
    } catch (\Throwable $th) {
      return $this->error('error', 500, ['error' => $th->getMessage()]);
    }
    return $this->ok($user, 'berhasil menghapus user');
  }
}
