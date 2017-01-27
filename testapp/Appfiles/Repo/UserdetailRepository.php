<?php
namespace Appfiles\Repo;

use Appfiles\Repo\UserdetailInterface;
use Illuminate\Container\Container as App;
use App\Model\Userdetail;

//echo "in here";
/**
 * User Details Repository
 */
class UserdetailRepository implements UserdetailInterface
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
        return Userdetail::orderBy('id','DESC')->get($columns);
    }

 public function getByRaw($condition, $columns = array('*')) {

        return Userdetail::whereRaw($condition)->first($columns);
    }
    

          /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getBy($condition, $columns = array('*')) {

        return Userdetail::where($condition)->first($columns);
    }
   public function update(array $data, $condition) {
      
         return Userdetail::where($condition)->update($data);
          
    }
    public function updatedetails(array $data, $condition) {
      
         return Userdetail::where($condition)->update($data);
          
    }
 

  function model()
    {
        return 'App\Model\Userdetail';
    }
}
