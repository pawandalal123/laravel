<?php 
namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Model\Activity;
use Illuminate\Http\Request;
use Session;

class Log  extends Controller
{
  function __construct() {
    
  }

  public function add(Request $request)
  {
  	echo "in here ".$request->test;
    echo Session::getId();
            Activity::log([
    'action'      => 'Create',
    'description' => 'Created a User',
    'details'     => 'Username: test',
    'updated'     => (bool) 0,
]);


        
    
  }
}
