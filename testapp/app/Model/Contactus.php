<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    //
	protected $table='contactus';
	protected $fillable=['name','email','mobile','message','city'];

}
