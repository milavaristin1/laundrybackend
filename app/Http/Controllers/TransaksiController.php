<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Transaksi;
use App\Detail_Transaksi;
use App\Member;
use Carbon\Carbon;
use JWTAuth;


class TransaksiController extends Controller
{
    public $user;
    //untuk menampung data user yang login

    public function __construct()
    {
      $this->user = JWTAuth::parseToken()->authenticate();
      //memanggil data secara otomatis
    }

    public function store(Request $request)
    {
      $validator = Validator::make($request->all(),[
        'id_member' => 'required',
        ]);

        if($validator->fails()) {
          return Response()->json($validator->errors());
        }

        $transaksi = new Transaksi();
        $transaksi->id_member = $request->id_member;
        $transaksi->tgl = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum dibayar';
        $transaksi->id_user = $this->user->id;


        $transaksi->save();
        $data = Transaksi::where('id_transaksi', '=', $transaksi->id_user)->first();

        return response()->json([
          'success' => true,
          'message' => 'Data transaksi berhasil ditambahkan',
          'data' => $transaksi
        ]);
    }

    public function getAll()
    {
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', 'member.id_member')
                ->select('transaksi.*', 'member.nama_member')
                ->get();

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getById($id)
  {
      $data = Transaksi::where('id_transaksi', '=', $id)->first();
      $data = DB::table('transaksi')->join('member', 'transaksi.id_member', 'member.id_member')
              ->select('transaksi.*', 'member.nama_member')
              ->where('transaksi.id_transaksi', $id)
              ->first();

      return response()->json(['data' => $data]);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'id_member' => 'required'
    ]);


  if ($validator->fails()) {
    return Response()->json($validator->errors());
  }

  $update = Transaksi::find($id)->first();
  $update->update($request->all());

  if ($update) {
    return Response()->json(['message' => 'Berhasil Update']);
  } else {
    return Response()->json(['message' => 'Gagal Update']);
  }
}


  public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $transaksi->status = $request->status;

        $transaksi->save();

        return response()->json([
          'success' => true,
          'message' => 'Data transaksi berhasil ditambahkan',
          'data' => $transaksi
        ]);
    }

    public function bayar($id)
    {
      $transaksi = Transaksi::where('id_transaksi', $id)->first();
       $total = Detail_Transaksi::where('id_transaksi', $id)->sum('subtotal');
       $bayar = Transaksi::where('id_transaksi', $id)->update([
           'tgl_bayar' => Carbon::now(),
           'status' => "diantar",
           'dibayar' => "dibayar",
           'total_bayar' => $total
       ]);

       if ($bayar) {
           return Response()->json([
               'success' => true,
               'message' => 'Pembayaran Berhasil'
           ]);
       } else {
           return Response()->json(['message' => 'Pembayaran Gagal']);
       }
    }
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_outlet' => 'required',
            'tahun' => 'required',
            'bulan' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $id_outlet = $request->id_outlet;

        $data = DB::table('transaksi')
                    ->join('member', 'transaksi.id_member', '=', 'member.id_member')
                    ->join('users', 'users.id', '=', 'transaksi.id_user')
                    ->select('transaksi.id_user','transaksi.tgl','transaksi.tgl_bayar','transaksi.total_bayar', 'member.nama_member')
                    ->where('users.id_outlet', '=', $id_outlet)
                    ->whereYear('transaksi.tgl', '=' , $tahun)
                    ->whereMonth('transaksi.tgl', '=', $bulan)
                    ->get();

        return response()->json($data);
    }


}
