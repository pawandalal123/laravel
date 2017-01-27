<?php
namespace Appfiles\Repo;

use Appfiles\Repo\AccountInterface;
use Illuminate\Container\Container as App;
use App\Model\Country;

//echo "in here";
/**
 * Order Details Repository
 */
class CountryRepository implements CountryInterface
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

        return Country::where($condition)->first($columns);
    }
       public function update(array $data, $acondition) {
        return Country::where($acondition)->update($data);
    }
    
    public function getallBy($condition, $columns = array('*'))
    {
        return Country::where($condition)->orderBy('id','desc')->get($columns);
    }
      public function rulesForCreatecountry()
      {
      return [
                'country' => 'required|unique:countries'
                
            ];
    }

    
     public function rulesForCreatcountryMessage(){
      return [
                'country.required' => 'The field name is required'
                
            ];
    }

     
        function model()
    {
        return 'App\Model\Country';
    }
}
// echo "till here";
// $obj= new OrderdetailsRepository("test");
// $obj->all();
// echo "ends here";