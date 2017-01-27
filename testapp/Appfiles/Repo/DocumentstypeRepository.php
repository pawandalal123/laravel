<?php
namespace Appfiles\Repo;
use Appfiles\Repo\DocumentstypeInterface;
use Illuminate\Container\Container as App;
use App\Model\Documents_type;

//echo "in here";
/**
 * User follwers Details Repository
 */
class DocumentstypeRepository implements DocumentstypeInterface
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

     public function getallBy($condition, $columns = array('*')) {
        return Documents_type::where($condition)->get($columns);
    }

    

  function model()
    {
        return 'App\Model\Documents_type';
    }
}
