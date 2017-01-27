<?php
namespace Appfiles\Repo;
use Appfiles\Repo\AdminfeatureInterface;
use Illuminate\Container\Container as App;
use App\Model\Adminfeatures;
use DB;

//echo "in here";
/**
 * Order Details Repository
 */
class AdminfeatureRepository implements AdminfeatureInterface
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

    public function getallDetails($condition, $columns = array('*'))
    {
        $users = DB::table('Userassigns')
            ->join('users', 'Userassigns.user_id', '=', 'users.id')
            ->where($condition)
            ->get($columns);
            return $users;
    }
    public function getBy($condition, $columns = array('*'))
    {
        return adminfeatures::where($condition)->first($columns);
    }

    public function getallBy($condition, $columns = array('*'))
    {
        return adminfeatures::where($condition)->get($columns);

    }
    
    function model()
    {
        return 'App\Model\Adminfeatures';
    }
}
?>