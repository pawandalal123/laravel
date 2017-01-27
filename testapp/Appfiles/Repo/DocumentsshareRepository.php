<?php
namespace Appfiles\Repo;

use Appfiles\Repo\SlotInterface;
use Illuminate\Container\Container as App;

use App\Model\Documents_share;
Use DB;

//echo "in here";
/**
 * User Details Repository
 */
class DocumentsshareRepository implements DocumentsshareInterface
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
        return Documents_share::orderBy('id','DESC')->get($columns);
    }

	public function getallBy($condition, $columns = array('*'))
    {
        return Documents_share::where($condition)->get($columns);
    }

     
	        /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getBy($condition, $columns = array('*')) {

        return Documents_share::where($condition)->first($columns);
    }
    
     public function getRawfirst($condition, $selectRaw) {

        return Documents_share::whereRaw($condition)->selectRaw($selectRaw)->first();
    }
	 /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id") {
        return Documents_share::where($attribute, $id)->update($data);
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
        return Documents_share::where($condition)->lists($key,$value);
    }
     public function getListall($condition,$key,$value ) 
    {
        //print_r($condition);exit;
        return Documents_share::where($condition)->lists($key,$value)->all();
    }
     public function getalldetails($condition, $columns = array('*'))
    {
        return    DB::table('documents_shares')
                        ->where($condition)
                        ->get($columns);
       
    }

    

	 /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        // dd($data);
        return Documents_share::insertGetId($data);
    }

    
public function delete($id) {
        return Documents_share::destroy($id);
    }

    function model()
    {
        return 'App\Model\Documents_share';
    }

  
}
