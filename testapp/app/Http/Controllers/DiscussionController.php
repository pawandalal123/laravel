<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Common;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\DiscussionsInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Appfiles\Common\Functions;
use Validator;
use Auth;
use URL;
use View;

class DiscussionController extends Controller
{

	public function  __construct(UsersInterface $usersInterface, DiscussionsInterface $discussion)
    {
        
        $this->usersInterface = $usersInterface;
        $this->discussion=$discussion;

    }
    /////////////////article form validation//////
    protected function validator(array $data)
    {
        $messsages = [
        'title.required'=>'You cant leave title field empty',
        'description.required'=>'Please enter description'];
       

    $rules = [
        'title'=>'required|max:255',
        'description'=>'required|not_in:0'
    ];

    return Validator::make($data, $rules,$messsages);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //////// form submit////
        if(isset($request->submitdiscussion))
        {
            $validator = $this->validator($request->all());
            if ($validator->fails())
            {
                $this->throwValidationException(
                    $request, $validator
                );
            }
          $this->discussion->saveform($request);

        }
       
        $duscussionlist = $this->discussion->discussionlist($request);
        return \View::make('web.discussionviewlist',compact('duscussionlist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function deletediscussion(Request $request,$id)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            if($id)
            {
                $check = $this->discussion->getBy(array('id'=>$id));
                if($check)
                {
                    if($user->id==$check->user_id)
                    {
                        $this->discussion->deletearticle($id);
                        Session::flash('message','Discussion delete successfully.'); 
                        Session::flash('alert-class', 'success'); 
                        Session::flash('alert-title', 'success');  
                        return redirect('profile/discussions');

                    }
                    else
                    {
                        Session::flash('message','You have no permission to delete the discussion.'); 
                        Session::flash('alert-class', 'danger'); 
                        Session::flash('alert-title', 'error');  
                        return redirect('profile/discussions');
                    }

                }
                else
                {
                   return redirect('error404');
                }

            }
            else
            {
                Session::flash('message','Please select discussion.'); 
                Session::flash('alert-class', 'danger'); 
                Session::flash('alert-title', 'error');  
                return redirect('profile/discussions');
            }

        }
        else
        {
            return redirect('auth/login');

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function discussionshow($id)
    // {
    //     discussiondetail.blade.php
    //     //
    // }
     public function discussionshow(Request $request,$discussionurl)
    {
        if($discussionurl)
        {
            $$discussiondetail = $this->discussion->getBy(array('discussion_url'=>$discussionurl));
            if($discussiondetail)
            {
                $login=0;
                if(Auth::check())
                {
                    $login=1;
                }
                $conditionRaw='status=1 and discussion_url !="'.$articleurl.'"';
                $getsimilararticle = $this->discussion->getallByRaw($conditionRaw,array('title','discussion_url'),6);
                return \View::make('web.discussiondetail',compact('discussiondetail','login','getsimilararticle'));

            }
            else
            {
                redirect('error404');
            }
            
        }
        else
        {
            return redirect('/');
        }
        

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
}
