<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Outlet;
use Tymon\JWTAuth\Facades\JWTAuth;

class OutletController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet'  => 'required|string',
            'alamat'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $outlet = new Outlet();
        $outlet->nama_outlet  = $request->nama_outlet;
        $outlet->alamat  = $request->alamat;


        $outlet->save();
        $data = Outlet::where('id', '=', $outlet->id)->first();

        return response()->json([
          'success' => true,
          'message' => 'Data outlet berhasil ditambahkan',
          'data' => $outlet
        ]);
    }

    public function getAll($limit = NULL, $offset = NULL)
    {

        $data = Outlet::get();

        return response()->json($data);
    }

    public function getById($id)
    {
        $data = Outlet::where('id', '=', $id)->first();

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet'  => 'required|string',
            'alamat'       => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $outlet = Outlet::where('id', '=', $id)->first();
        $outlet->nama_outlet   = $request->nama_outlet;
        $outlet->alamat  = $request->alamat;

        $outlet->save();

        return response()->json([
          'success' => true,
          'message' => 'Data outlet berhasil diubah',
          'data' => $outlet
        ]);
    }

    public function delete($id)
    {
        $delete = Outlet::where('id', '=', $id)->delete();

        if ($delete) {
            return response()->json(['message' => 'Data outlet berhasil dihapus']);
        } else {
            return response()->json(['message' => 'Data outlet gagal dihapus']);
        }
    }
}
