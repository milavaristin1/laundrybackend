<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
            'level' => 'required',
            'id_outlet' => 'required',


        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->level = $request->level;
        $user->id_outlet = $request->id_outlet;

        $user->save();

        $data = User::where('id', '=', $user->id)->first();

        return response()->json([
          'success' => true,
          'message' => 'Data user berhasil ditambahkan',
          'data' => $user
        ]);
    }
    public function getAll()
    {
        $data = DB::table('users')->join('outlet', 'users.id_outlet', 'outlet.id')
                                    ->select('users.*', 'outlet.nama_outlet')
                                    ->get();

        return response()->json($data);
    }

    public function getById($id)
    {
        $data = User::where('id', '=', $id)->first();

        return Response()->json($data);

        // return Response()->json([
        //     'message' => 'Sukses Menampilkan Data',
        //     'data' => $data
        // ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'username'  => 'required|string',
            'level'     => 'required|string',
            'id_outlet' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::where('id', '=', $id)->first();
        $user->name        = $request->name;
        $user->username    = $request->username;
        $user->level       = $request->level;
        $user->id_outlet   = $request->id_outlet;

        if ($request->password_edit != NULL) {
          $user->password    = Hash::make($request->password_edit);
        }

        $user->save();

        return response()->json([
          'success' => true,
          'message' => 'Data user berhasil diubah',
          'data' => $user
        ]);
    }

    public function delete($id)
    {
        $delete = User::where('id', '=', $id)->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data user gagal dihapus'
            ]);
        }
    }
}
