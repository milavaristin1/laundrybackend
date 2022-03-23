<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;
    protected $fillable = ['id_member', 'tgl', 'batas_waktu', 'tgl_bayar', 'status', 'dibayar', 'id_user'];
}
