<?php
namespace Appfiles\Repo;

use Appfiles\Repo\CategoryInterface;
use Illuminate\Container\Container as App;
use App\Model\Category;


//echo "in here";
/**
 * Order Details Repository
 */
class CategoryRepository implements CategoryInterface
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
        return Category::where($condition)->get($columns);
    }
     public function getBy($condition, $columns = array('*'))
    {
        return Category::where($condition)->first($columns);
    }


    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */


      public function rulesForCreate()
      {
      return [
                'name' => 'required',
               
                
            ];
    }

    
     public function rulesForCreatMessage(){
      return [
                'name.required' => 'The field name is required',
               
                
            ];
        }


        function model()
    {
        return 'App\Model\Category';
    }
}
// echo "till here";
// $obj= new OrderdetailsRepository("test");
// $obj->all();
// echo "ends here";