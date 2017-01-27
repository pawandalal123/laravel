<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\Common;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\StateInterface;
use App\Model\Comments;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Mail;
use Appfiles\Common\Functions;
use URL;
use Validator;

class AjaxRequestController extends Controller
{

    protected $APIURL;
    protected $functions;
    protected $s3;

    public function  __construct(Functions $functions,UsersInterface $usersInterface,StateInterface $state)
    {
       $this->APIURL= URL::to('api/');
       $this->functions=$functions;
       $this->usersInterface = $usersInterface;
       $this->state= $state;
   
      
    }
 ////////////login model/////////
  public function loginform(Request $request)
  {
      echo  csrf_field();
 
      return view('includes/web/loginmodel');
  }

  //////////// sasavedetails/////
  public function savedetails(Request $request)
  {
    if($request->ajax())
    {
      $status['status'] = 'error';
      $user = Auth::user();
      $dataComment = array('comment'=>$request->message,
                           'commented_id'=>$request->id,
                           'comment_by'=>$user->id,
                           'created_at'=>date('Y-m-d H:i:s'),
                           'type'=>2);
      if($request->detailfor=='article')
      {
        $dataComment['type'] = 1;

      }
      $createcomment = Comments::insert($dataComment);
      if($createcomment)
      {
        $status['status'] = 'success';
      }

      return response()->json($status);
   }

  }


  public function connectviamailbox(Request $request)
   {
    
    if($request->ajax())
        {
          ?>
          
              
                <div class="input-field">
                  <input type="text" class="validate">
                  <label class="">Email</label>
                </div>
                <div class="input-field">
                <button class="waves-effect waves-light btn">Connect</button>
                </div>
              
            <?php  
      }

   }

   /*save contect form data for user*/

  public function saveforwardform(Request $request)
  {
      if($request->ajax())
      {
          $eventFormData = Input::all();
          @extract($eventFormData);
          $commonObj = new Common();
          $usercity = $commonObj->iplocation('182.64.161.94');
          $Event_forward_details = new Event_forward_details();
          $Event_forward_details->event_id =$request->eventid;
          $Event_forward_details->forward_by =$request->name;
          $Event_forward_details->forward_to_email =$request->email;
          $Event_forward_details->forward_message =$request->message;
          $Event_forward_details->forward_ip_address =$_SERVER['REMOTE_ADDR'];
          $Event_forward_details->forward_from_location =$usercity;
          $Event_forward_details->save();
          echo 1;
      }
  }


  //////////statelist countrybased/////
  public function statelist(Request $request)
  {

    dd($request->all());
    $getstatelist = $this->state->getallBy(array('status'=>1,''=>$request->countryid));
    if(count($getstatelist)>0)
    {

    }

  }



         

      
 
  
 
}
?>
