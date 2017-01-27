<?php
namespace Appfiles\Repo;

use Appfiles\Repo\CityInterface;
use Illuminate\Container\Container as App;

use App\Model\City;
use DB;

//echo "in here";
/**
 * User Details Repository
 */
class CityRepository implements CityInterface
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

    

        /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
   
 

 public function all($columns = array('*')) {
       // dd('in here trait');
        return City::orderBy('id','DESC')->get($columns);
    }

	public function getallBy($condition, $columns = array('*'))
    {
        return City::where($condition)->get($columns);
    }
    public function getallBydetails($condition, $columns = array('*'))
    {
        return DB::table('cities')->where($condition)->get($columns);
    }
    public function getByraw($condition, $columns = array('*'))
    {
        return City::whereRaw($condition)->get($columns);
    }
     
	        /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getBy($condition, $columns = array('*')) {

        return City::where($condition)->first($columns);
    }
    
	 /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id") {
        return City::where($attribute, $id)->update($data);
    }

    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getList($condition,$key,$value ) 
    {
        //print_r($condition);exit;
        return City::where($condition)->lists($key,$value);
    }

	 /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        // dd($data);
        return City::insertGetId($data);
    }
     public function rulesForCreatecity()
      {
      return [
                'country' => 'required|not_in:0',
                'state' => 'required|not_in:0',
                'city' => 'required|unique:cities'
                
            ];
    }

    
     public function rulesForCreatecityWMessage(){
      return [
                'country.required' => 'The field name is required',
                'state.required' => 'The field name is required',
                'city.required' => 'The field name is required'
                
            ];
    }


    function model()
    {
        return 'App\Model\City';
    }

  
}
