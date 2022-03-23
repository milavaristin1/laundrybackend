<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Member;

class MemberController extends Controller
{
  public $user;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'tlp' => 'required',

        ]);

        if ($validator->fails()) {
            return Response()->json($validator->errors());
        }

        $member = new Member;
        $member->nama_member = $request->nama_member;
        $member->alamat = $request->alamat;
        $member->jenis_kelamin = $request->jenis_kelamin;
        $member->tlp = $request->tlp;
        $member->save();

        return response()->json([
          'success' => true,
          'message' => 'Data member berhasil ditambahkan',
          'data' => $member
        ]);
    }
    public function getAll()
 {
//    $data['count'] = Member::count();

   $data = Member::get();
   return response()->json(['data'=> $data]);
 }

    public function getdata($id)
    {
        $data = Member::get()->where('id_member', $id)->first();
        return Response()->json(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_member' => 'required',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
            'tlp' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $member = Member::where('id_member', '=', $id)->first();
        $member ->  nama_member   = $request  ->  nama_member;
        $member ->  alamat        = $request  ->  alamat;
        $member ->  jenis_kelamin = $request  ->  jenis_kelamin;
        $member ->  tlp           = $request  ->  tlp;
        $member ->  save();

        $data = Member::where('id_member', '=', $member->id_member)->first();
        return response()->json([
          'success' => true,
          'message' => 'Data member berhasil diubah',
          'data' => $member
        ]);
    }

    public function delete($id)
  {
      $delete = Member::where('id_member', '=', $id)->delete();

      if($delete) {
        return response()->json([
            'success' => true,
            'message' => "Data member berhasil dihapus"
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => "Data member gagal dihapus"
        ]);
    }
}
}
