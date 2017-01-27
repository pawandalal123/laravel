<?php
namespace Appfiles\Repo;

use Appfiles\Repo\FeaturesInterface;
use Illuminate\Container\Container as App;
use App\Model\Featureassign;

//echo "in here";
/**
 * Order Details Repository
 */
class FeaturesRepository implements FeaturesInterface
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

    public function getallBy($condition, $columns = array('*'))
    {
        return Featureassign::where($condition)->get($columns);
    }
     public function getBy($condition, $columns = array('*'))
    {
        return Featureassign::where($condition)->first($columns);
    }
    public function update(array $data, $condition) {
      
          $Usertabs = Featureassign::where($condition)->update($data);
          return $Usertabs;
    }
    public function delete($condition) {
        return  Featureassign::where($condition)->delete();
    }

    
    function model()
    {
        return 'App\Model\Featureassign';
    }
}
?>