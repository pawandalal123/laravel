<?php
namespace Appfiles\Repo;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Container\Container as App;
use App\Model\State;
use Illuminate\Support\Facades\Input;
use DB;

//echo "in here";
/**
 * Order Details Repository
 */
class StateRepository implements StateInterface
{
    use RepositoryTrait;

    protected $model,$app;
    /**
     * Order Details Repository
     */
    public function __construct(App $app)
    {
  
        $this->app=$app;
        $this->makeModel();  
    }

    public function getallBy($condition, $columns = array('*')) {
        return State::where($condition)->orderBy('id','desc')->get($columns);
    }
     public function getallByIn($condition,$columns = array('*')) 
    {
        
        return State::whereIn('event_id',$condition)->get($columns);
    }
     public function getalldetails($condition,$columns = array('*')) 
    {
         $states =  DB::table('states')
        ->join('countries', 'countries.id', '=', 'states.country_id')
        ->where($condition)
        ->groupBy('states.id')
        ->orderBy('state','asc')
        ->get($columns);
        return  $states;
    }

    public function getBy($condition, $columns = array('*')) {

        return State::where($condition)->first($columns);
    }
    public function getallpaginate($request,$condition, $columns = '*',$orderBy='id')
    {
        if(isset($request->withoutpage))
        {
            return State::selectRaw($columns)->whereRaw($condition)->orderBy($orderBy,'asc')->get();
        }
        else
        {
           return State::selectRaw($columns)->whereRaw($condition)->orderBy($orderBy,'asc')->paginate(25);
        }
        
    }
     public function getraw($condition, $columns = '*',$orderBy='id')
    {
        return State::selectRaw($columns)->whereRaw($condition)->first();;
    }

    public function getList($condition,$key,$value ) 
    {
        
        return State::where($condition)->lists($key,$value)->all();
    }
     public function getListeventid($condition,$key,$value) 
    {
        
        return State::whereIn('event_id',$condition)->lists($key,$value)->all();
    }

        public function findBy($attribute, $value, $columns = array('*')) {
        return State::where($attribute, '=', $value)->first($columns);
    }
    public function update(array $data, $condition) {
      
          $State = State::where($condition)->update($data);
          return $State;
    }

        public function updateRaw(array $data, $condition) {
      
          $State = State::whereRaw($condition)->update($data);
          return $State;
    }
 


    public function getallByRaw($condition,$columns = array('*'))
     {
       // dd('in here trait');
       
        return State::whereRaw($condition)->get($columns);
    }
    public function getrawList($condition, $columns)
    {
        $eventlist = State::selectRaw($columns)
            ->whereRaw($condition)
            ->groupBy('event_id')
            ->get();

            return $eventlist;
    }
      public function create(array $data) {
        // dd($data);
        return State::insertGetId($data);
    }

    public function rulesForCreatestate()
      {
      return [
                'country' => 'required|not_in:0',
                'state' => 'required|unique:states'
                
            ];
    }

    
     public function rulesForCreatstateMessage(){
      return [
                'country.required' => 'The field name is required',
                'state.required' => 'The field name is required'
                
            ];
    }


    function model()
    {
        return 'App\Model\State';
    }
}
// echo "till here";
// $obj= new OrderdetailsRepository("test");
// $obj->all();
// echo "ends here";