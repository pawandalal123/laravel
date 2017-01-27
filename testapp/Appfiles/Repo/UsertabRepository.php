<?php
namespace Appfiles\Repo;

use Appfiles\Repo\UsertabInterface;
use Illuminate\Container\Container as App;
use App\Model\Usertabs;

//echo "in here";
/**
 * Order Details Repository
 */
class UsertabRepository implements UsertabInterface
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

    public function getBy($condition, $columns = array('*')) {

        return Usertabs::where($condition)->first($columns);
    }
     public function getByRaw($condition, $columns = array('*')) {

        return Usertabs::whereRaw($condition)->first($columns);
    }

    public function getallBy($condition, $columns = array('*'))
    {
        return Usertabs::where($condition)->get($columns);
    }
     public function updatetab(array $data, $condition) {
      
          $Usertabs = Usertabs::where($condition)->update($data);
          return $Usertabs;
    }
    public function delete($id) 
  {
    
        return Usertabs::destroy($id);
  }
    function model()
    {
        return 'App\Model\Usertabs';
    }
}
?>