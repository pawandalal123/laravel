<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Appfiles\Common\Functions;
use App\Model\Password_resets;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\UserdetailInterface;
use Appfiles\Repo\ArticlesInterface;
use Appfiles\Repo\CategoryInterface;
use Appfiles\Repo\SubcategoryInterface;
use Appfiles\Repo\DiscussionsInterface;
use Appfiles\Repo\CountryInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Model\Common;
use App\Model\Discussion_invite;
Use DB;
use Validator;
use Auth;
use URL;
use View;
class UserController extends Controller
{
  use ValidationTrait;
	protected $APIURL;
    protected $functions;
    protected $s3;

	public function  __construct(Functions $functions, UsersInterface $usersInterface,ArticlesInterface $articles,CategoryInterface $category,SubcategoryInterface $subcat,DiscussionsInterface $discussion,CountryInterface  $country,UserdetailInterface $userdetail)
    {
        //$this->user_id= 1;
        
		    $this->APIURL= URL::to('api/');
        $this->functions=$functions;
		    $this->usersInterface = $usersInterface;
        $this->articles = $articles;
        $this->category=$category;
        $this->subcat=$subcat;
        $this->discussion = $discussion;
        $this->country = $country;
        $this->userdetail =$userdetail;
        // $this->Discussion_invite = new Discussion_invite();

}
   

  public function index(Request $request,$pagename=false)
  {
    $user = Auth::user();
    if(empty($user->id))
    {
         return redirect('auth/login');
    }
    else
    {
        ////////// submit article///////
        if(isset($request->submitarticle))
        {
           $validator = $this->postarticle($request);
        }
        /////// submit///////////
        if(isset($request->submitdiscussion))
        {
            $validator = $this->postdiscussion($request);
        }
        //////////////invitation
        if(isset($request->sendinvite))
        {
          $validator = $this->sendinvite($request);
          $dataArray = array('discussion_id'=>$request->discussion_name,
                             'name'=>$request->name,
                             'email'=>$request->email,
                             'created_by'=>$user->id,
                             'created_at'=>date('Y-m-d H:i:s'));
          $sendinvitation = Discussion_invite::insertGetId($dataArray);
          if($sendinvitation)
          {
            Session::flash('message','Create Successfully.'); 
            Session::flash('alert-class', 'success'); 
            Session::flash('alert-title', 'Success');

          }
          else
          {
            Session::flash('message','Some technical problem.'); 
            Session::flash('alert-class', 'danger'); 
            Session::flash('alert-title', 'error');

          }
        }
        
        $articlelist=array();
        $articledetail='';
        $currenttab='';
        $catlist='';
        $subcatlist='';
        $duscussionlist = '';
        $invitationlist = array();
        if($pagename=='articles')
        {
          $request->userid=$user->id;
          $articlelist= $this->articles->articleslist($request);
          if($request->editid)
          {
            $articledetail= $this->articles->getBy(array('id'=>$request->editid));
            $currenttab='edittab';

          }
           $catlist = $this->category->getallBy(array('type'=>1,'status'=>1),array('name'));
           $subcatlist = $this->subcat->getallBy(array('type'=>1,'status'=>1),array('name'));
        }
        if($pagename=='discussions')
        {
          $request->userid=$user->id;
          $request->showall=1;
          $duscussionlist= $this->discussion->discussionlist($request);
          if($request->editid)
          {
            $duscussiondetail= $this->discussion->getBy(array('id'=>$request->editid));
            $currenttab='edittab';
          }
           
        }
        if($pagename=='invitediscussion')
        {
          $duscussionlist= $this->discussion->getallBy(array('status'=>1,'user_id'=>$user->id),array('id','title'));
          $invitationlist = Discussion_invite::where(array('created_by'=>$user->id))->get(array('name','email','created_at'));

        }
        return view('web/userfiles/userhomepage',compact('pagename','articlelist','articledetail','currenttab','catlist','subcatlist','duscussiondetail','duscussionlist','invitationlist'));
    }
  }

  public function editprofile(Request $request,$pagename=false)
  {
     $user = Auth::user();
    if(empty($user->id))
    {
         return redirect('auth/login');
    }
    else
    {
       $request->id=$user->id;
        ////// save and update basicinformation////
        if(isset($request->editbasicdetails))
        {
           
            $validator = $this->userbasci_info($request);
          
        }

        if(isset($request->editsocial))
        {
          $checkdetails = $this->userdetail->getBy(array(),array('user_id'=>$user->id));
          if($checkdetails)
          {
            $socialinfo  =$this->userdetail->upadte();

          }
          else
          {

          }
           
            
          
        }
        $countryList = $this->country->getallBy(array('status'=>1),array('id','country'));

      return view('web/userfiles/editprofile',compact('pagename','countryList'));
    }

  }
    



	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changepassword()
    {
        //
		$user = Auth::user();
		if(empty($user->id))
        {
             return redirect('auth/login');
        }
		$footerfix = 'footerfix';
		return view('web/changepassword',compact('user','footerfix'));
    }

	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postpassword(Request $request)
    {
        //
		$user = Auth::user();
		if(empty($user->id))
        {
             return redirect('auth/login');
        }
		    $requestData = $request->all();
		   
			$whereCondiyion = 'id';
			$whereCondiyion1 = $user->id;
			$userData['password'] = bcrypt($requestData['password']);
			$this->usersInterface->update($userData,$whereCondiyion1,$whereCondiyion);
            
			Session::flash('message', 'Password has been updated.'); 
            Session::flash('alert-class', 'success'); 
            Session::flash('alert-title', 'Success'); 
            return redirect('user/changepassword');
		
    }

  
}
