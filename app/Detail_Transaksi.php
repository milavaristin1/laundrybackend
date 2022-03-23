<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_Transaksi extends Model
{
  protected $table = 'detail_transaksi';
  protected $primaryKey = "id_detail";
  public $timestamps= false;
  protected $fillable =['id_transaksi', 'id_paket', 'qty'];
}
