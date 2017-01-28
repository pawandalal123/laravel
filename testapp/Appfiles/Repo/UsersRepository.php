<?php
namespace Appfiles\Repo;
use Appfiles\Repo\UsersInterface;
use Illuminate\Container\Container as App;
use DB;
use App\User;
use App\Modal\Userdetail;
use Illuminate\Support\Facades\Session;

//echo "in here";
/**
 * User Details Repository
 */
class UsersRepository implements UsersInterface
{
	use RepositoryTrait;

	protected $model,$app;
    /**
     * User Details Repository
     */
    public function __construct(App $app)
    {
    	$this->app=$app;
    	$this->makeModel();  
    }

    

 public function all($columns = array('*')) {
       // dd('in here trait');
        return User::orderBy('id','DESC')->get($columns);
    }

public function allpaging($columns = array('*'),$paging=100) {
       // dd('in here trait');
        return User::orderBy('id','DESC')->paginate($paging);
    }


public function countTotalUsers($condition)
    {
     return  User::whereRaw($condition)->count();
     }

public function getallByBetween($condition,$wherebetween, $columns = array('*')) {
      
        return User::where($condition)->whereBetween('created_at', $wherebetween)->orderBy('id','desc')->get($columns);
    }




 public function getallBy($condition, $columns = array('*'))
    {
        return User::where($condition)->get($columns);
    }
     public function getallBylimit($condition, $columns = array('*'),$skip=false,$limit=false)
    {
        return User::where($condition)->skip($skip)
        ->take($limit)->get($columns);
    }

    public function getallByIn($array, $columns = array('*'))
    {
        return User::whereIn('id', $array)->get($columns);
    }

    public function getallByRaw($condition,$columns = array('*')) {
       // dd('in here trait');
      
        return User::whereRaw($condition)->get($columns);
    }

    public function getBy($condition, $columns = array('*'))
    {
        return User::where($condition)->first($columns);
    }

    public function updateuser(array $data, $condition)
     {
      
          $Usertabs = User::where($condition)->update($data);
          return $Usertabs;
     }
      public function getList($condition,$key,$value )
      {
        return User::where($condition)->lists($key,$value)->all();
    }
    public function delete($id)
    {
        return User::destroy($id);
    }

    public function updateuserdetails($request)
    {
       
        $usertableArray = array('name'=>$request->first_name,
                                'middle_name'=>$request->middle_name,
                                'last_name'=>$request->last_name,
                                'mobile'=>$request->mobile);
        $userdetailArray = array('gender'=>$request->id,
                                 'mother_name'=>$request->mothername,
                                 'father_name'=>$request->fathername,
                                 'country'=>$request->country,
                                 'state'=>$request->state,
                                 'city'=>$request->city,
                                 'pincode'=>$request->pincode,
                                 'area'=>$request->area_name);
        try
        {
             $updateUser = $this->updateuser($usertableArray,array('id'=>$request->id));
            //////// check userdata in userdetail table
            $checkuserdetails = Userdetail::where(array('user_id'=>$request->id))->first(array('id'));
            if($checkuserdetails)
            {
                $upadtedetails = Userdetail::where(array('user_id'=>$request->id))->update($userdetailArray);

            }
            else
            {
                $userdetailArray['user_id'] =$request->id ;
                $userdetailArray['created_at'] =date('Y-m-d H:i:s') ;
                $cretedetails = Userdetail::insertGetId($userdetailArray);
            }
            Session::flash('message','Detail update Successfully.'); 
            Session::flash('alert-class', 'success'); 
            Session::flash('alert-title', 'Success');

        }
        catch(\Exception $ex)
        {
            ///throw $e;
            Session::flash('message','Some technical problem.'); 
            Session::flash('alert-class', 'danger'); 
            Session::flash('alert-title', 'error');

        }
       

    }
     public function updateusersocialdetails($request)
    {
       
        $userdetailArray = array('gender'=>$request->id,
                                 'mother_name'=>$request->mothername,
                                 'father_name'=>$request->fathername,
                                 'country'=>$request->country,
                                 'state'=>$request->state,
                                 'city'=>$request->city,
                                 'pincode'=>$request->pincode,
                                 'area'=>$request->area_name);
        try
        {
            $checkuserdetails = Userdetail::where(array('user_id'=>$request->id))->first(array('id'));
            if($checkuserdetails)
            {
                $upadtedetails = Userdetail::where(array('user_id'=>$request->id))->update($userdetailArray);

            }
            else
            {
                $userdetailArray['user_id'] =$request->id ;
                $userdetailArray['created_at'] =date('Y-m-d H:i:s') ;
                $cretedetails = Userdetail::insertGetId($userdetailArray);
            }
            Session::flash('message','Detail update Successfully.'); 
            Session::flash('alert-class', 'success'); 
            Session::flash('alert-title', 'Success');

        }
        catch(\Exception $ex)
        {
            ///throw $e;
            Session::flash('message','Some technical problem.'); 
            Session::flash('alert-class', 'danger'); 
            Session::flash('alert-title', 'error');

        }
       

    }

    public function rulesForCreateuser(){
      return [
                'email' => 'required|email|unique:users',
                'name' => 'required|max:25',
                'mobile' => 'required|max:10'
                
            ];
    }
    public function rulesForediteuser(){
      return [
                
                'name' => 'required|max:25',
                'mobile' => 'required|max:10'
                
            ];
    }
     public function rulesForCreateuserMessage(){
      return [
                'email.required' => 'The field name is required',
                 'email.unique:users' => 'Must Be Unique',
                'name.max' => 'The field name may not be greater than 25 characters'
                
                
            ];
    }
     public function rulesForedituserMessage(){
      return [
               
                'name.max' => 'The field name may not be greater than 25 characters',
                'mobile.required'=>'Mobile is required'
                
                
            ];
    }
 


  function model()
    {
        return 'App\User';
    }
}
