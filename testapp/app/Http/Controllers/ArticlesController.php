<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
Use DB;
use Validator;
use Auth;
use URL;
use View;
use App\Model\Common;
use App\Model\Comments;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\ArticlesInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Appfiles\Common\Functions;
use Appfiles\Repo\CategoryInterface;
use Appfiles\Repo\SubcategoryInterface;



class ArticlesController extends Controller
{

	public function  __construct(UsersInterface $usersInterface, ArticlesInterface $articles,CategoryInterface $category,SubcategoryInterface $subcat)
    {
        
        $this->usersInterface = $usersInterface;
        $this->articles = $articles;
        $this->category=$category;
        $this->subcat=$subcat;
    }
    /////////////////article form validation//////
    protected function validator(array $data)
    {
        $messsages = [
        'title.required'=>'You cant leave title field empty',
        'category.required'=>'Please select category',
        'subcategory.required'=>'Please select subcategory',
        'articlefile.mimes'=>'Please select valid file'];
       

    $rules = [
        'title'=>'required|max:255',
        'category'=>'required|not_in:0',
        'subcategory' => 'required|not_in:0',
        'articlefile' => 'mimes:jpeg,png,bmp,gif,jpg'
    ];

    return Validator::make($data, $rules,$messsages);
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //////// form submit////
        if(isset($request->submitarticle))
        {
            $validator = $this->validator($request->all());
            if ($validator->fails())
            {
                $this->throwValidationException(
                    $request, $validator
                );
            }
            $this->articles->savearticle($request);
        }
        $catlist = $this->category->getallBy(array('type'=>1,'status'=>1),array('name'));
        $subcatlist = $this->subcat->getallBy(array('type'=>1,'status'=>1),array('name'));
        $articlelist = $this->articles->articleslist($request);
        return \View::make('web.articlesviewlist',compact('articlelist','catlist','subcatlist'));
    }

    public function articledetail(Request $request,$articleurl)
    {
        if($articleurl)
        {
            $articledetail = $this->articles->getBy(array('article_url'=>$articleurl));
            if($articledetail)
            {
                $login=0;
                if(Auth::check())
                {
                    $login=1;
                }
                $conditionRaw='status=1 and article_url !="'.$articleurl.'"';
                $articlaCatlist = $this->category->getallBy(array('type'=>1,'status'=>1),array('name'));
                $getsimilararticle = $this->articles->getallByRaw($conditionRaw,array('title','article_url'),6);
                return \View::make('web.articledetail',compact('articledetail','login','articlaCatlist','getsimilararticle'));

            }
            else
            {
                return redirect('error404');
            }
            
        }
        else
        {
            return redirect('/');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deletearticle(Request $request,$id)
    {
        if(Auth::check())
        {
            $user = Auth::user();
            if($id)
            {
                $check = $this->articles->getBy(array('id'=>$id));
                if($check)
                {
                    if($user->id==$check->user_id)
                    {
                        $this->articles->deletearticle($id);
                        Session::flash('message','Article delete successfully.'); 
                        Session::flash('alert-class', 'success'); 
                        Session::flash('alert-title', 'success');  
                        return redirect('profile/articles');

                    }
                    else
                    {
                        Session::flash('message','You have no permission to delete the article.'); 
                        Session::flash('alert-class', 'danger'); 
                        Session::flash('alert-title', 'error');  
                        return redirect('profile/articles');
                    }

                }
                else
                {
                   return redirect('error404');
                }

            }
            else
            {
                Session::flash('message','Please select article.'); 
                Session::flash('alert-class', 'danger'); 
                Session::flash('alert-title', 'error');  
                return redirect('profile/articles');
            }

        }
        else
        {
            return redirect('auth/login');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
}
