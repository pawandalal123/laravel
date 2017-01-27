<?php
namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	 use SoftDeletes;
	 protected $dates = ['deleted_at'];
	 protected $table='cities';
    //
}
?>
