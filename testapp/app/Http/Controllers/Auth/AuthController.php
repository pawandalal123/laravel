<?php
namespace App\Http\Controllers\Auth;
use App\User;
use App\Model\Userdetail;
use Validator;
use Mail;
use App\Model\Common;
use Illuminate\Contracts\Auth\Guard; 
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Cookie;
use URL;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    // adding default redirect path if not set
    protected $redirectTo='/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    private $socialite;
    private $auth;
    private $users;

    public function __construct(Socialite $socialite ,Guard $auth )
    {
         $this->middleware('guest', ['except' => 'getLogout']);
         $this->socialite = $socialite;
         $this->auth = $auth;
    }



 
     //protected $redirectPath = '/dashboard';
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $messsages = [
        'email.required'=>'You cant leave Email field empty',
        'name.required'=>'You cant leave name field empty',
        'name.min'=>'The name has to be :min chars long',
        're_password.required'=>'You cant leave password field empty',
        're_password.min'=>'The password has to be :min chars long',
        'password_confirmation.same'=>'The password and confirn password not match',
    ];

    $rules = [
        'email'=>'required|email|max:255|unique:users',
        'name'=>'required|min:3',
        're_password' => 'required|min:6',
        'password_confirmation' => 'required|min:6|same:re_password'
    ];

    return Validator::make($data, $rules,$messsages);
       
    }


       //custom login and register function//
    public function userlogin_register(Request $request)
    {
        
           $this->validate($request, [
            $this->loginUsername() => 'required|email', 'password' => 'required',
        ]);
        $path = $_SERVER['HTTP_HOST'];
        //check user exit or not//
        $checkemail =  User::where('email', '=', $request->email)->where('is_banned', '=', 0)->first();
        if($checkemail)
        {
          if($checkemail->is_register==0)
          {
            $updateUsers = User::where('id', '=', $checkemail->id)->update(array('password' => bcrypt($request->password),'is_register'=>1));
            Auth::loginUsingId($checkemail->id);
            $pathRedirect = $request->refferurl;
            $urlpaths = str_replace($_ENV['SITE_URL'], '', $pathRedirect);
            if($urlpaths=='/auth/login')
            {
                return redirect('/');
            }
            else
            {
                return redirect($pathRedirect);
            }

          }
          else
          {
            $throttles = $this->isUsingThrottlesLoginsTrait();

            if ($throttles && $this->hasTooManyLoginAttempts($request)) {
                return $this->sendLockoutResponse($request);
            }

            $credentials = $this->getCredentials($request);
            if (Auth::attempt($credentials, $request->has('remember')))
            {
                $pathRedirect = $request->refferurl;
                $urlpaths = str_replace($_ENV['SITE_URL'], '', $pathRedirect);
                if($urlpaths=='/auth/login')
                {
                    return redirect('/');
                }
                else
                {
                    return redirect($pathRedirect);
                }
               //return $this->handleUserWasAuthenticated($request, $throttles);
            }
          }
           
        }
        else
        {
            Auth::login($this->create($request->all()));
        }
     

        return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), 'remember'))
                ->withErrors([
                    $this->loginUsername() => $this->getFailedLoginMessage(),
                ]);
    
    }

    public function postajaxRegister(Request $request)
    {
       $eventFormData = Input::all();
        @extract($eventFormData);
        //print_r($eventFormData);
        if($request->ajax())
        {
            $status = array();
            $errror  = array();
            if(empty($email))
            {
                if($email=='')
                {
                    $errror['email'] ='Email is required';
                }
                if($password=='')
                {
                    $errror['password'] ='Password is required';
                }
                return response()->json([
                   'error' => $errror
                   ]);
            }
            else
            {
                $checkemail =  User::where('email', '=', $request->email)->first();
                if($checkemail)
                {
                    $errror['emailexist'] ='This Email id is laready exit';
                    return response()->json([
                   'error' => $errror
                   ]);

                }
                else
                {

                    Auth::login($this->create($request->all()));
                    $errror['auth']=true;
                    return response()->json([
                                            'loginsuccess' => $errror
                                           ]);
                    //$errror['userid']=$userData->id;

                }

            }

        }
    }

    /*Login ajax functrion*/
    public function postLoginajax(Request $request)
    {
        $eventFormData = Input::all();
        @extract($eventFormData);
          // if(isset($request->requestfrom))
          // {
          //   $request->password=$password;
          // }
            $status = array();
            $errror  = array();
            if(empty($email))
            {
                if($email=='')
                {
                    $errror['email'] ='Email is required';
                }
                if($password=='')
                {
                    $errror['password'] ='Password is required';
                }
                return response()->json([
                   'error' => $errror
                   ]);
            }
            else
            {
                $credentials = $this->getCredentials($request);
                if (Auth::attempt($credentials, $request->has('remember')))
                {
                    $userData = Auth::user();
                   $imagePatah = URL::asset('web/images/profilePic.png');
                   if($userData->profile_image)
                   {
                     $imagePatah = URL::asset($_ENV['CF_LINK'].'/user/'.$userData->id.'/profile/logo/'.$userData->profile_image);
                     if($userData->login_type==1)
                     {
                       $imagePatah = $userData->profile_image;
                     }
                   }
                    $errror['auth']=true;
                    $errror['userid']=$userData->id;
                    $errror['name']=$userData->name;
                    $errror['email']=$userData->email;
                    $errror['mobile']=$userData->mobile;
                    $errror['imagePatah']=$imagePatah;
                    //return $this->handleUserWasAuthenticated($request, $throttles);
                }
                return response()->json([
                    'loginsuccess' => $errror
                   ]);
                // }
            }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $Makeuser =  User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_register'=>1]);
 
    }



    public function getLogout()
    {
         Auth::logout();
        if(Session::has('isUserMobile'))
        {
            Session::forget('isUserMobile');
            Session::forget('isUserID'); 
        }
         
         $commonObj = new Common();
         $name='userLoginid';
         $getcokkies = $commonObj->getcokkies('userLoginid');
         $cookie='';
         if($getcokkies!=false)
         {
            $cookie = $commonObj->deletecokkies('userLoginid');
         }
         
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/')->withCookie($cookie);
    }
}
