<?php
namespace Appfiles\Repo;

use Appfiles\Repo\DocumentsInterface;
use Illuminate\Container\Container as App;
use App\Model\Documents;
Use DB;

//echo "in here";
/**
 * Order Details Repository
 */
class DocumentsRepository implements DocumentsInterface
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
        return Documents::where($condition)->get($columns);
    }
    public function getalldetails($condition, $columns = array('*'))
    {
        return    DB::table('documents')
                        ->where($condition)
                        ->get($columns);
       
    }
     public function getBy($condition, $columns = array('*'))
    {
        return Documents::where($condition)->first($columns);
    }

    public function getByraw($condition, $columns = array('*'))
    {
        return Documents::whereRaw($condition)->get($columns);
    }
   public function update(array $data, $condition)
    {
        return Documents::whereRaw($condition)->update($data);
    }



        function model()
    {
        return 'App\Model\Documents';
    }
}
// echo "till here";
// $obj= new OrderdetailsRepository("test");
// $obj->all();
// echo "ends here";