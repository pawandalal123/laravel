<?php
namespace Appfiles\Repo;

use Appfiles\Repo\SubcategoryInterface;
use Illuminate\Container\Container as App;

use App\Model\Subcategory;

//echo "in here";
/**
 * User Details Repository
 */
class SubcategoryRepository implements SubcategoryInterface
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
        return Subcategory::orderBy('id','DESC')->get($columns);
    }

	public function getallBy($condition, $columns = array('*'))
    {
        return Subcategory::where($condition)->get($columns);
    }
    public function getallsucat($condition, $columns = array('subcategories.*'))
    {
        return Subcategory::join('categories','categories.id=subcategories.category_id')->where($condition)->get($columns);
    }
    public function getByraw($condition, $columns = array('*'))
    {
        return Subcategory::whereRaw($condition)->get($columns);
    }

    public function delete($id) {
        return Subcategory::destroy($id);
    }
     
	        /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getBy($condition, $columns = array('*')) {

        return Subcategory::where($condition)->first($columns);
    }
    
	 /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data,$condition) {
        return Subcategory::where($condition)->update($data);
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
        return Subcategory::where($condition)->lists($key,$value);
    }
       public function getListall($condition,$key,$value ) 
    {
        //print_r($condition);exit;
        return Subcategory::where($condition)->lists($key,$value)->all();
    }
     public function getRawfirst($condition, $selectRaw) {

        return Subcategory::whereRaw($condition)->selectRaw($selectRaw)->first();
    }

	 /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        // dd($data);
        return Subcategory::insertGetId($data);
    }

    public function rulesForCreate()
      {
      return [
                'category' => 'required|not_in:0',
                'name' => 'required'
                
            ];
    }

    
     public function rulesForCreateMessage(){
      return [
                'category.required' => 'The field name is required',
                'name.required' => 'The field name is required'
                
            ];
    }
    


    function model()
    {
        return 'App\Model\Subcategory';
    }

  
}
