<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Model\Contactus;
use App\Model\Common;
use Appfiles\Common\Functions;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Session;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\ArticlesInterface;
use Route;
use SEO;
use Auth;
use URL;
use View;
use Input;
use SEOMeta;

class mainController extends Controller 
{
    protected $eventCityList;
    protected $categoryList;
    protected $functions;

    public function  __construct(UsersInterface $usersInterface,Functions $functions,ArticlesInterface $articles)
    {
       $this->functions=$functions;
       $this->usersInterface = $usersInterface;
       $this->articles = $articles;
       // $commonObj = new common();
    }

    // home page display function//
    public function index(Request $request , $page = false, $pagenumber = false)
    {
        if(Auth::check())
        {
            $articlelist = $this->articles->getallpaginate(array('status'=>1));
            return view('web/index',compact('articlelist'));

        }
        else
        {
            return view('auth.login');
        }
       
    }


      /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function careers()
    {
        SEO::setTitle('Career Page');
        SEO::setDescription('');
  
        //
        return view('web/staticpage/careers');
    }

    public function contactus(Request $request)
    {
        SEO::setTitle('Contact Us - Goeventz');
        SEO::setDescription('Buy & sell advance tickets online. Explore things to do & events happening around the World with Goeventz.');
        if($request->submitcontact)
        {
            $common = new Common();
            $contactArray['name']=$request->input('name');
            $contactArray['email']=$request->input('email');
            $contactArray['mobile']=$request->input('mobile');
            $contactArray['message']=$request->input('message');
            $response=Contactus::create($contactArray);

            // $mailmessage = "<p>Hi Team, ".ucwords($contactArray['name'])." has contacted us
            // <p>Here are the details,</p>

            // <p>Name: <b>".ucwords($contactArray['name'])."</b></p>
            // <p>Email:  <b>".$contactArray['email']."</b></p>
            // <p>Mobile: <b>".$contactArray['mobile']."</b></p>
            // <p>City: <b>".$contactArray['city']."</b></p>
            // <p>Message: <b>".$contactArray['message']."</b></p>

            // ";
            // $mailArray= array('to'=>'support@goeventz.com',
            // 'name'=>$contactArray['name'],
            // 'subject'=>'Message received from Contact Us Form on GoEventz - '.date('Y-m-d H:i'));

            // $mail= $common->sendmail($mailmessage,$mailArray);
            
            if($response)
            {
                Session::flash('message', 'Thank you for contacting us, we will get back to you soon.'); 
                Session::flash('alert-class', 'success'); 
                // Session::flash('alert-title', 'Message');  
            }
        }

        return view('web/staticpage/contactus');
    }




  

}
