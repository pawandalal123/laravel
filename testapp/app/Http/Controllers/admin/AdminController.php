<?php
namespace App\Http\Controllers\Admin; 
use Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\DiscussionsInterface;
// use Appfiles\Repo\AdminfeatureInterface;
// use Appfiles\Repo\FeaturesInterface;
use App\Model\Common;
use Appfiles\Repo\CategoryInterface;
use Appfiles\Repo\SubcategoryInterface;
use Appfiles\Repo\CountryInterface;
use Appfiles\Repo\StateInterface;
use Appfiles\Repo\CityInterface;
use Appfiles\Repo\ArticlesInterface;
Use App\Model\Discussion_invite;
use App\Model\Comments;
use Redirect;
use Validator;
use DB;
use Illuminate\Container\Container;
use Appfiles\Common\Functions;
use URL;
use Illuminate\Support\Collection;

// use Url;
class AdminController extends Controller {

   protected $functions;
   protected $s3;
   public function  __construct(Functions $function,UsersInterface $usersInterface,CountryInterface $country,StateInterface $state,CityInterface $city,CategoryInterface $category,SubcategoryInterface $subcat,ArticlesInterface $articles,DiscussionsInterface $discussion)
    {
        //$this->user_id= 1;        
    $this->usersInterface = $usersInterface;
    // $this->adminfeature =$adminfeature;
    // $this->featuresassign=$featuresassign;
    $this->function=$function;
    $this->country=$country;
    $this->state=$state;
    $this->city=$city;
    $this->category=$category;
    $this->subcat=$subcat;
    $this->articles = $articles;
    $this->discussion=$discussion;
   
    }

    public function checkpermission($featuredid=false)
    {
      ///first check login///
      $user = Auth::user();
      $status=array();
      if(empty($user->id))
      {
        //return redirect('auth/login');
        $status['status']='login';
      }
      else
      {
        //check fetaure assign or not///   
        $status['loginid']=$user->id;
        if($user->user_type==1)
        {
            $status['status']='success';
        }
        else
        {
          $status['status']='notfound';

        }
      }
       return $status;
    }
  
  public function index()
  {
    if(Auth::check())
    {
      $user = Auth::user();
      if($user->user_type!= 0)
      {
          return redirect('/admin/dashboard');
      }
      else
      {
        return redirect('/');
      }
    }
    return \View::make('admin.index');
  }

  

  public function postIndex()
  {
    $username = Input::get('username');
    $password = Input::get('password');
    if (Auth::attempt(['email' => $username, 'password' => $password,'user_type'=>1,'status'=>1]))
    {
      return redirect('/admin/dashboard');
      
    }

    return Redirect::back()->withInput()
                           ->withErrors('That username/password combo does not exist.');
  }
    
  public function dashboard(Request $request)
  {   
    $user = Auth::user();

    if(empty($user->id))
    {
        return redirect('admin/login');
    }
    $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')////////if login require
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')////////page not belong to user
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
    $postData = $request->all();
   
    
    return \View::make('admin.admindashboard');
   }
  }

  public function locationtype(Request $request,$type=false,$id=false)
  {
    $dataCuntry = array();
    $dataCity  =array();
    $dataState = array();
    $datatoedit='';
      $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')////////if login require
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')////////page not belong to user
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
      if(isset($request->submitcountry))
      {
          $data = array('country'=>$request->country,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$checkstatus['loginid']);
            $validator = Validator::make($request->all(), $this->country->rulesForCreatecountry(),$this->country->rulesForCreatcountryMessage());

            if ($validator->fails())
            {
                return redirect('/admin/location')
                            ->withErrors($validator)
                            ->withInput();
            }
            else
            {
              $this->country->create($data);
              Session::flash('message','Save Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');
            }

      }
      if(isset($request->submitstate))
      {
        $data = array('state'=>$request->state,
                        'country_id'=>$request->country,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$checkstatus['loginid']);
            $validator = Validator::make($request->all(), $this->state->rulesForCreatestate(),$this->state->rulesForCreatstateMessage());

            if ($validator->fails())
            {
                return redirect('/admin/location/state')
                            ->withErrors($validator)
                            ->withInput();
            }
            else
            {
              $this->state->create($data);
              Session::flash('message','Save Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');
            }

      }
      if(isset($request->citysubmit))
      {
        $data = array('city'=>$request->city,
          'state'=>$request->state,
                        'country_id'=>$request->country,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$checkstatus['loginid']);
            $validator = Validator::make($request->all(), $this->city->rulesForCreatecity(),$this->city->rulesForCreatecityWMessage());

            if ($validator->fails())
            {
                return redirect('/admin/location/city')
                            ->withErrors($validator)
                            ->withInput();
            }
            else
            {
              $this->city->create($data);
              Session::flash('message','Save Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');
            }

      }
      if(isset($request->updatecountry))
      {
          $data = array('country'=>$request->country);
          $this->country->update($data,array('id'=>$type));
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');

      }
      if(isset($request->updatestate))
      {
          $data = array('country_id'=>$request->country,
                        'state'=>$request->state);
          $this->state->update($data,array('id'=>$id));
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');

      }
      if($type=='state')
      {
        $dataCuntry = $this->country->getallBy(array('status'=>1),array('id','country'));
        $dataState = $this->state->getalldetails(array(),array('states.id','state','country','states.status','states.created_at'));
        if($id)
        {
          $datatoedit = $this->state->getBy(array('id'=>$id));
          if(empty($datatoedit))
          {
            return redirect('error404/');
          }
        }

      }
      elseif($type=='city')
      {
        $countryArray = array();
        $stateArray = array();
        $dataCuntry = $this->country->getallBy(array('status'=>1),array('id','country'));
        foreach($dataCuntry as $dataCuntrylist)
        {
          $countryArray[$dataCuntrylist->id] = $dataCuntrylist->country;

        }
        //////state array///
         $dataState = $this->state->getallBy(array('status'=>1),array('id','state'));
        foreach($dataState as $dataStatelist)
        {
          $stateArray[$dataStatelist->id] = $dataStatelist->state;
        }
       
          $dataCity = $this->city->getallBydetails(array());
           array_walk($dataCity, function(&$value, $key, $sourceArray)
            { 
                $value->country='';
                 
                if(array_key_exists($value->country_id, $sourceArray))
                {
                     $value->country = $sourceArray[$value->country_id];
                }

            },$countryArray); 

           array_walk($dataCity, function(&$value, $key, $sourceArray)
            { 
                
                if(array_key_exists($value->state, $sourceArray))
                {
                     $value->state = $sourceArray[$value->state];
                }
                else
                {
                  $value->state='';
                }

            },$stateArray); 

         
        if($id)
        {
          $datatoedit = $this->city->getBy(array('id'=>$id));
          if(empty($datatoedit))
          {
            return redirect('error404/');
          }
        }

      }
      else
      {
        $dataCuntry = $this->country->getallBy(array());
        if($type)
        {
          $datatoedit = $this->country->getBy(array('id'=>$type));
          if(empty($datatoedit))
          {
            return redirect('error404/');
          }
        }
      }
      return \View::make('admin.locationmaster',compact('dataCuntry','dataCity','dataState','type','datatoedit'));
   }
  }
  public function changelocationstatus(Request $request,$type=false,$id=false)
  {
      if($type=='state')
      {
        $checkstate = $this->state->getBy(array('id'=>$id));
        if($checkstate)
        {
          $status=0;
          if($checkstate->status==0)
          {
             $status=1;
          }
          $data = array('status'=>$status);
          $this->state->update($data,array('id'=>$id));

          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');
          return redirect('/admin/location/state');

        }
        else
        {
          return redirect('error404/');

        }
      }
      elseif($type=='city')
      {
        $checkcity= $this->city->getBy(array('id'=>$id));
        if($checkcity)
        {
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');
          return redirect('/admin/location/state');

        }
        else
        {
          return redirect('error404/');

        }

      }
      else
      {
        $checkcountry= $this->country->getBy(array('id'=>$type));
        if($checkcountry)
        {
          $status=0;
          if($checkcountry->status==0)
          {
             $status=1;

          }
          $data = array('status'=>$status);
          $this->country->update($data,array('id'=>$type));
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');
          return redirect('/admin/location');
        }
        else
        {
          return redirect('error404/');

        }
      }
  }

  ////////////////////// articles management///////////////
  public function articlesmaster(Request $request,$type=false,$id=false)
  {
    $dataCat = array();
    $datasubcat = array();
    $datatoedit='';
    $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')////////if login require
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')////////page not belong to user
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
      if(isset($request->submitcat))
      {
          $data = array('name'=>$request->name,
            'type'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$checkstatus['loginid']);
            $validator = Validator::make($request->all(), $this->category->rulesForCreate(),$this->category->rulesForCreatMessage());

            if ($validator->fails())
            {
                return redirect('/admin/articles')
                            ->withErrors($validator)
                            ->withInput();
            }
            else
            {
              $this->category->create($data);
              Session::flash('message','Save Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');
            }

      }
      if(isset($request->submitsubcat))
      {
        $data = array('name'=>$request->name,
                        'category_id'=>$request->category,
                        'status'=>1,
                        'type'=>1,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'created_by'=>$checkstatus['loginid']);
            $validator = Validator::make($request->all(), $this->subcat->rulesForCreate(),$this->subcat->rulesForCreateMessage());

            if ($validator->fails())
            {
                return redirect('/admin/articles/subcategory')
                            ->withErrors($validator)
                            ->withInput();
            }
            else
            {
              $this->subcat->create($data);
              Session::flash('message','Save Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');
            }

      }
      
      if(isset($request->updatecatgeroy))
      {
          $data = array('name'=>$request->name);
          $this->category->update($data,array('id'=>$type));
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');

      }
      if(isset($request->updatesubcatgeroy))
      {
          $data = array('country_id'=>$request->country,
                        'state'=>$request->state);
          $this->state->update($data,array('id'=>$id));
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');

      }
      if($type=='subcategory')
      {
        $dataCat = $this->category->getallBy(array('status'=>1,'type'=>1),array('id','name'));
        $datasubcat = $this->subcat->getallBy(array());
        if($id)
        {
          $datatoedit = $this->subcat->getBy(array('id'=>$id));
          if(empty($datatoedit))
          {
            return redirect('error404/');
          }
        }

      }
      else
      {
        $dataCat = $this->category->getallBy(array('type'=>1));
        if($type)
        {
          $datatoedit = $this->category->getBy(array('id'=>$type));
          if(empty($datatoedit))
          {
            return redirect('error404/');
          }
        }
      }
      return \View::make('admin.categorymaster',compact('dataCat','datasubcat','type','datatoedit'));
   }
  }

  public function changecategorystatus(Request $request,$type=false,$id=false)
  {
      if($type=='subcategory')
      {
        $checksubcat= $this->subcat->getBy(array('id'=>$id));
        if($checksubcat)
        {
          $status=0;
          if($checksubcat->status==0)
          {
             $status=1;
          }
          $data = array('status'=>$status);
          $this->subcat->update($data,array('id'=>$id));

          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');
          return redirect('/admin/articles/subcategory');

        }
        else
        {
          return redirect('error404/');

        }
      }
      else
      {
        $checkcategory= $this->category->getBy(array('id'=>$type));
        if($checkcategory)
        {
          $status=0;
          if($checkcategory->status==0)
          {
             $status=1;

          }
          $data = array('status'=>$status);
          $this->category->update($data,array('id'=>$type));
          Session::flash('message','Update Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');
          return redirect('/admin/articles');
        }
        else
        {
          return redirect('error404/');

        }
      }
  }

  public function articlelist(Request $request)
  {
    $articlesList = array();
    $userArray = array();
    $userids='';
    $getarticles = $this->articles->getallpaginate(array());
    if(count($getarticles)>0)
    {
      foreach($getarticles as $getarticleslist)
      {
        $userids.=$getarticleslist->user_id.',';
        $articlesList[$getarticleslist->id]= array('title'=>substr($getarticleslist->title,0,25),
                                               'description'=>substr($getarticleslist->description,0,25),
                                               'created_by'=>'',
                                               'created_at'=>$getarticleslist->created_at,
                                               'subcategory'=>$getarticleslist->subcategory,
                                               'category'=>$getarticleslist->category,
                                               'status'=>$getarticleslist->status);

      }
      $condition = "id in ".'('.substr($userids,0,-1).')'."";
      $getuser = $this->usersInterface->getallByRaw($condition,array('id','email'));
      foreach($getuser as $getuser)
      {
        $userArray[$getuser->id] = $getuser->email;
      }
       array_walk($articlesList, function(&$value, $key, $sourceArray)
            { 
               
                 
                if(array_key_exists($key, $sourceArray))
                {
                     $value['created_by'] = $sourceArray[$key];
                }

            },$userArray); 


    }
 
    return  \View::make('admin.articlelisting',compact('articlesList','getarticles'));

  }

  public function articlestatus(Request $request,$id)
  {
    $checksarticle= $this->articles->getBy(array('id'=>$id));
    if($checksarticle)
    {
      $status=0;
      if($checksarticle->status==0)
      {
         $status=1;
      }
      $data = array('status'=>$status);
      $this->articles->update($data,array('id'=>$id));

      Session::flash('message','Update Successfully.'); 
      Session::flash('alert-class', 'success'); 
      Session::flash('alert-title', 'Success');
      return redirect('/articlelist');

    }
    else
    {
      return redirect('error404/');

    }

  }
  public function deletearticle(Request $request)
  {
    $checksarticle= $this->articles->getBy(array('id'=>$id));
    if($checksarticle)
    {
      
      $this->articles->delete($id);
      Session::flash('message','Delete Successfully.'); 
      Session::flash('alert-class', 'success'); 
      Session::flash('alert-title', 'Success');
      return redirect('/articlelist');

    }
    else
    {
      return redirect('error404/');

    }

  }

  ///////////////////////// discussionmaster///////////////////
  public function discussionlist(Request $request)
  {
    $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')////////if login require
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')////////page not belong to user
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
      $discussionList = array();
      $userArray = array();
      $userids='';
      $getdiscussion = $this->discussion->getallpaginate(array());
      if(count($getdiscussion)>0)
      {
        foreach($getdiscussion as $getdiscussionlist)
        {
          $userids.=$getdiscussionlist->user_id.',';
          $discussionList[$getdiscussionlist->id]= array('title'=>substr($getdiscussionlist->title,0,25),
                                                 'description'=>substr($getdiscussionlist->description,0,25),
                                                 'created_by'=>'',
                                                 'created_at'=>$getdiscussionlist->created_at,
                                                 'status'=>$getdiscussionlist->status);

        }
        $condition = "id in ".'('.substr($userids,0,-1).')'."";
        $getuser = $this->usersInterface->getallByRaw($condition,array('id','email'));
        foreach($getuser as $getuser)
        {
          $userArray[$getuser->id] = $getuser->email;
        }
         array_walk($discussionList, function(&$value, $key, $sourceArray)
              { 
                   
                  if(array_key_exists($key, $sourceArray))
                  {
                       $value['created_by'] = $sourceArray[$key];
                  }

              },$userArray); 
      }
 
      return  \View::make('admin.discussionlisting',compact('discussionList','getdiscussion'));
    }

  }

  public function discussionstatus(Request $request,$id)
  {
    $checksdiscussion= $this->discussion->getBy(array('id'=>$id));
    if($checksdiscussion)
    {
      $status=0;
      if($checksdiscussion->status==0)
      {
         $status=1;
      }
      $data = array('status'=>$status);
      $this->discussion->update($data,array('id'=>$id));

      Session::flash('message','Update Successfully.'); 
      Session::flash('alert-class', 'success'); 
      Session::flash('alert-title', 'Success');
      return redirect('/discussionlist');

    }
    else
    {
      return redirect('error404/');

    }

  }
  public function deletediscussion(Request $request)
  {
    $checksdiscussion= $this->discussion->getBy(array('id'=>$id));
    if($checksdiscussion)
    {
      
      $this->discussion->delete($id);
      Session::flash('message','Delete Successfully.'); 
      Session::flash('alert-class', 'success'); 
      Session::flash('alert-title', 'Success');
      return redirect('/discussionlist');

    }
    else
    {
      return redirect('error404/');

    }

  }
  public function invitationlist(Request $request)
  {
    $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')////////if login require
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')////////page not belong to user
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
      
      $discussionList = array();
      $userArray = array();
      $userids='';
      $invitationlist = Discussion_invite::where(array('created_by'=>$checkstatus['loginid']))->paginate(1);
      if(count($invitationlist)>0)
      {
        foreach($invitationlist as $invitation)
        {
          $userids.=$invitation->created_by.',';
          $discussionList[$invitation->id]= array('name'=>$invitation->name,
                                                 'email'=>$invitation->email,
                                                 'created_by'=>'',
                                                 'created_at'=>$invitation->created_at);

        }
        $condition = "id in ".'('.substr($userids,0,-1).')'."";
        $getuser = $this->usersInterface->getallByRaw($condition,array('id','email'));
        foreach($getuser as $getuser)
        {
          $userArray[$getuser->id] = $getuser->email;
        }
         array_walk($discussionList, function(&$value, $key, $sourceArray)
              { 
                   
                  if(array_key_exists($key, $sourceArray))
                  {
                       $value['created_by'] = $sourceArray[$key];
                  }

              },$userArray); 
      }
      return  \View::make('admin.invitationlisting',compact('invitationlist','discussionList'));
    }
  }

  public function discussion_comment(Request $request)
  {
     $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')////////if login require
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')////////page not belong to user
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
      $commentsArray = array();
      $userArray = array();
      $userids='';
      $discussionid='';
      $discussionArray = array();
      $commentlist = Comments::where(array('type'=>2))->paginate(1);
      if(count($commentlist)>0)
      {
        foreach($commentlist as $commentval)
        {
          $userids.=$commentval->comment_by.',';
          $discussionid.=$commentval->commented_id.',';
          $commentsArray[$commentval->id]= array('comment'=>$commentval->comment,
                                                 'commented_id'=>$commentval->commented_id,
                                                 'created_by'=>$commentval->comment_by,
                                                 'created_at'=>$commentval->created_at,
                                                 'status'=>$commentval->status);

        }
        $condition = "id in ".'('.substr($userids,0,-1).')'."";
        $getuser = $this->usersInterface->getallByRaw($condition,array('id','email'));
        foreach($getuser as $getuser)
        {
          $userArray[$getuser->id] = $getuser->email;
        }
         array_walk($commentsArray, function(&$value, $key, $sourceArray)
              { 
                   
                  if(array_key_exists($value['created_by'], $sourceArray))
                  {
                       $value['created_by'] = $sourceArray[$value['created_by']];
                  }

              },$userArray); 
      }


        $condition = "id in ".'('.substr($discussionid,0,-1).')'."";
        $getdiscussion = $this->discussion->getallByRaw($condition,array('id','title'));
        foreach($getdiscussion as $getdiscussion)
        {
          $discussionArray[$getdiscussion->id] = $getdiscussion->title;
        }
         array_walk($commentsArray, function(&$value, $key, $sourceArray)
              { 
                   
                  if(array_key_exists($value['commented_id'], $sourceArray))
                  {
                       $value['commented_id'] = $sourceArray[$value['commented_id']];
                  }

              },$discussionArray); 
      
      dd($commentsArray);
      return  \View::make('admin.discussion_comment_list',compact('invitationlist','discussionList'));

    }
  }




  ///////////////////////////////////

  public function getLogout()
  {
    $commonObj = new Common();
    Auth::logout();
    $deleteuserLoginid = $commonObj->deletecokkies('userLoginid');
    $deleteloginToken = $commonObj->deletecokkies('loginToken');
    return redirect('/admin')->withCookie($deleteuserLoginid);;
    
  }
 
  public function manageuser()
  {   
    $user = Auth::user();

    if(empty($user->id))
    {
        return redirect('admin/login');
    }

    $checkstatus = $this->checkpermission();
    if($checkstatus['status']=='login')
    {
      return redirect('auth/login');
    }
    else if($checkstatus['status']=='notfound')
    {
      return redirect('error404/');
    }
    elseif($checkstatus['status']=='success')
    {
      $column = array('name','email','mobile','login_type','created_at','type','user_type','status','about','is_register','id');
      $dataUser = $this->usersInterface->allpaging($column); 
      $counter=1;
      return \View::make('admin.manageuser',compact('dataUser','counter'));
    }
    else
    {
      return redirect('error404/');
    }
  }
 

//function for create admin users:Pawan//
public function makeuser(request $request)
{
     $user = Auth::user();
     if(empty($user->id) ||  $user->user_type != 1)
     {
         return redirect('auth/login');
     }
      if(isset($request->submit))
      {
          $userData = array('name'=>$request->name,
                            'email'=>$request->email,
                            'mobile'=>$request->mobile,
                            'type'=>$request->type,
                            'password'=>bcrypt($request->password),
                            'user_type'=>1,
                            'created_by'=>$user->id);
          $validator = Validator::make($request->all(), $this->usersInterface->rulesForCreateuser(),$this->usersInterface->rulesForCreateuserMessage());

          if ($validator->fails()) {
              return redirect('/admin/makeuser')
                          ->withErrors($validator)
                          ->withInput();
          }
          // if($request->type==2)
          // {
          //     $validator = Validator::make($request->all(), $this->usersInterface->rulesForfeatures(),$this->usersInterface->rulesForfeaturesMessage());
          //     if ($validator->fails())
          //     {
          //         return redirect('/admin/makeuser')
          //                     ->withErrors($validator)
          //                     ->withInput();
          //     }
          //     $featuresid=implode(',',$request->feature);

          // }
          if(isset($request->userID))  //Is used for editing
          {
              $makeuser = $this->usersInterface->updateuser($userData, array('id'=>$request->userID));
              $changedValue='Updated';
              
          }
          else
          {  
              $makeuser = $this->usersInterface->create($userData);
              // if($request->type==2)
              // {
              //     $featuresData = array('user_id'=>$makeuser,'features'=>$featuresid,'created_by'=>$user->id);
              //     $makefeatures = $this->featuresassign->create($featuresData);
              // } 
              $changedValue='Created';
          }
          if($makeuser)
          {

              Session::flash('message', $changedValue.' Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');

          }
          else
          {
              Session::flash('message', 'Please try later.'); 
              Session::flash('alert-class', 'error'); 
              Session::flash('alert-title', 'error');
          }
              // dd(here);
             return redirect('admin/makeuser/');
              
      }
        // $featureslist =$this->adminfeature->getallBy(array('status'=>1));
        $featureslist='';
        $alluserlist =$this->usersInterface->getallBy(array('user_type'=>1));
        return \View::make('admin.makeuserform',compact('alluserlist','featureslist'));

}

public function editadminuser(request $request,$id)
{
     $user = Auth::user();
     if(empty($user->id) ||  $user->user_type != 1)
     {
         return redirect('/');
     }
      if(isset($request->submit))
      {
          $userData = array('name'=>$request->name,
                            'email'=>$request->email,
                            'mobile'=>$request->mobile,
                            'type'=>$request->type,
                            'user_type'=>1,
                            'updated_by'=>$user->id);
          $validator = Validator::make($request->all(), $this->usersInterface->rulesForediteuser(),$this->usersInterface->rulesForedituserMessage());

          if ($validator->fails()) {
              return redirect('/editadminuser/'.$id)
                          ->withErrors($validator)
                          ->withInput();
          }
          // if($request->type==2)
          // {
          //     $validator = Validator::make($request->all(), $this->usersInterface->rulesForfeatures(),$this->usersInterface->rulesForfeaturesMessage());
          //     if ($validator->fails()) {
          //         return redirect('/editadminuser/'.$id)
          //                     ->withErrors($validator)
          //                     ->withInput();
          //     }
          //     $featuresid=implode(',',$request->feature);

          // }
          
          $updateuserDetails = $this->usersInterface->updateuser($userData, array('id'=>$id));
          $changedValue='User details';
          // if($request->type==2)
          // {
          //   $featuresData = array('user_id'=>$id,'features'=>$featuresid,'created_by'=>$user->id);
          //   $deletefeatures = $this->featuresassign->delete(array('user_id'=>$id));
          //   $makefeatures = $this->featuresassign->create($featuresData);
          //   $changedValue.=' and features';
          // } 
          $changedValue.=' update';
          if($updateuserDetails)
          {
              Session::flash('message', $changedValue.' Successfully.'); 
              Session::flash('alert-class', 'success'); 
              Session::flash('alert-title', 'Success');

          }
          else
          {
              Session::flash('message', 'Please try later.'); 
              Session::flash('alert-class', 'error'); 
              Session::flash('alert-title', 'error');
          }
              // dd(here);
             return redirect('admin/makeuser/');
      }

    // $featureslist =$this->adminfeature->getallBy(array('status'=>1));
    $getUserdetails =$this->usersInterface->getBy(array('id'=>$id,'user_type'=>1));
    // $getfeatures =$this->featuresassign->getBy(array('user_id'=>$id));
    // if(!$getfeatures)
    // {
      $getfeatures='';

    //}
    
    return \View::make('admin.editadminuser',compact('getUserdetails','featureslist','getfeatures'));

}




 public function adminuserstatus(Request $request ,$id , $status)

 {

        $user = Auth::user();
        $condition = array('id'=>$id);
        $checkuser = $this->usersInterface->getBy($condition);
        if($checkuser)
        {
            $updateData = array('status'=>$status);
            $updateuser = $this->usersInterface->updateuser($updateData,$condition);
            if($updateuser)
            {
                Session::flash('message', 'Update Successfully.'); 
                Session::flash('alert-class', 'success'); 
                Session::flash('alert-title', 'Success');
            }
             return back();
            //return redirect();
        }
        else
        {
            Session::flash('message', 'Something went wrong! The page you are looking for can not be found.'); 
            Session::flash('alert-class', 'error'); 
            Session::flash('alert-title', 'error'); 
            return redirect('error404/');
        }

 }
  ////////// change password////////
  /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changepassword(Request $request)
    {
        //
    $user = Auth::user();
    if(empty($user->id)){
             return redirect('auth/login');
        }
        
     $footerfix = 'footerfix';
    return view('admin/changepassword',compact('user','footerfix'));
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
    if(empty($user->id)){
             return redirect('auth/login');
        }
        $requestData = $request->all();
    $rules = array('password'=> 'required',
                   'confirmpassword'=> 'required|same:password');

    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) 
    {
        return redirect('/admin/changepassword')
                    ->withErrors($validator)
                    ->withInput();
    }
      $whereCondiyion = 'id';
      $whereCondiyion1 = $user->id;
      $userData['password'] = bcrypt($requestData['password']);
      $this->usersInterface->update($userData,$whereCondiyion1,$whereCondiyion);
            
       Session::flash('message', 'Password has been updated.'); 
                Session::flash('alert-class', 'success'); 
                Session::flash('alert-title', 'Success'); 
            return redirect('admin/changepassword');
    
    }

  


}


