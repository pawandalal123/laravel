<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discussion extends Model
{
    //
     use SoftDeletes;

	 protected $dates = ['deleted_at'];
	 protected $table='discussions';
}
