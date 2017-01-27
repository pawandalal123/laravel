<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Appfiles\Common\Functions;
use Appfiles\Repo\UsersInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Model\Common;
Use DB;
use Validator;
use Auth;
use URL;
use View;
class JoblistingController extends Controller
{

	protected $APIURL;
    protected $functions;
    protected $s3;

	public function  __construct(Functions $functions, UsersInterface $usersInterface)
    {
        //$this->user_id= 1;
        
		    $this->APIURL= URL::to('api/');
        $this->functions=$functions;
		    $this->usersInterface = $usersInterface;
  

}
   

  public function index(Request $request,$pagename=false)
  {
  
        return view('web/joblisting');
    
  }



  
}
