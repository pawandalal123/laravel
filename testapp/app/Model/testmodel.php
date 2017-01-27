<?php

namespace App\model;
use DB;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    //

    public function selectData()
    {
         $users = DB::table('testmodels')
                
                ->get();
                return $users;

    }
}
