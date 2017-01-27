<?php
namespace Appfiles\Repo;
use App\Http\Requests;
use Illuminate\Http\Request;
use Appfiles\Repo\ArticlesInterface;
use Illuminate\Container\Container as App;
use App\Model\Articles;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Model\Common;
use Illuminate\Support\Facades\Session;


class ArticlesRepository implements ArticlesInterface
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

        return Articles::where($condition)->first($columns);
    }
    public function getByraw($condition, $columns = array('*'),$order) {

        return Articles::whereRaw($condition)->orderBy('id',$order)->first($columns);
    }
    
    public function getallBy($condition, $columns = array('*'))
    {
        return Articles::where($condition)->get($columns);
    }
    public function getallByRaw($condition, $columns = array('*'),$take=false)
    {
        if($take)
        {
            return Articles::whereRaw($condition)->orderBy('id','desc')->take($take)->get($columns);

        }
        else
        {
            return Articles::whereRaw($condition)->get($columns);

        }
    }
    public function getallpaginate($condition, $columns = array('*'))
    {
        return Articles::select($columns)->where($condition)->paginate(1);
    }
     public function deletearticle($id) {
        return Articles::destroy($id);
    }
    public function articleslist($request,$columns = array('*'))
    {

        $completeformData = Input::all();
        @extract($completeformData);
        $articleList = Articles::select($columns);
        $pageData =1;
        $wherecondition = array('status'=>1);
         
        $articleList = $articleList->where($wherecondition);
        if(Input::has('idnotin'))
        {
          $articleList = $articleList->whereRaw("event_id != '".$request->idnotin."'");

        }
        if(Input::has('userid'))
        {
          $articleList = $articleList->whereRaw("user_id = '".$request->userid."'");

        }
        if($request->paginate)
        {
          $articleList = $articleList->paginate($request->paginate);

        }
        else
        {
          $articleList = $articleList->paginate($pageData);
        }

        return $articleList;
    }

    public function update(array $data, $condition) {
        return Articles::where($condition)->update($data);
    }
    
     public function all($columns = array('*')) {
       // dd('in here trait');
        return Articles::orderBy('id','DESC')->get($columns);
    }
     /////////////////article form validation//////
    public function validator()
    {
        return  [
        'title.required'=>'You cant leave title field empty',
        'category.required'=>'Please select category',
        'subcategory.required'=>'Please select subcategory',
        'articlefile.mimes'=>'Please select valid file'];
    

    }
    public function validatemassege()
    {
        return [
        'title'=>'required|max:255',
        'category'=>'required|not_in:0',
        'subcategory' => 'required|not_in:0',
        'articlefile' => 'mimes:jpeg,png,bmp,gif,jpg'
           ];
    }
   
  public function savearticle($request)
  {
  
    $user = Auth::user();
    $fileName='';
    if($request->file('articlefile'))
    {
        $image = $request->file('articlefile');
        // $destinationPath = 'storage/articles'; // upload path
        $destinationPath = 'uplode/articles/';
        $extension = $request->file('articlefile')->getClientOriginalExtension(); // getting image extension
        $fileName = 'article_'.time().'.'.$extension; // renameing image
        $request->file('articlefile')->move($destinationPath, $fileName); // uploading file to given path
    }
    $CommonObj = new Common();
    $titleurl = $CommonObj->cleanURL($request->title);
    $dataArray = array('title'=>$request->title,
                       'category'=>$request->category,
                       'subcategory'=>$request->subcategory,
                       'description'=>$request->description,
                       'articles_image'=>$fileName,
                       'article_url'=>$titleurl,
                       'user_id'=>$user->id,
                       'created_at'=>date('Y-m-d H:i:s'));
    if($request->editid)
    {
        unset($dataArray['created_at']);
        if($request->file('articlefile') && $request->file('articlefile')!='')
        {
            unset($dataArray['articles_image']);
        }
        $article = $this->update($dataArray,array('id'=>$request->editid));
        $message='Update';

    }
    else
    {
        $article = $this->create($dataArray);
        $message='Create';
    }
    
    if($article)
    {
         Session::flash('message',''.$message.' Successfully.'); 
          Session::flash('alert-class', 'success'); 
          Session::flash('alert-title', 'Success');

    }
    else
    {
        Session::flash('message','Some technical problem.'); 
        Session::flash('alert-class', 'danger'); 
        Session::flash('alert-title', 'error');   

    }
  }

  function model()
    {
        return 'App\Model\Articles';
    }
   
	

              /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    


}
