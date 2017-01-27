<?php 
namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Model\Bannerimage;

class BannerimageComposer 
{
    public function compose(View $view) 
    {
        $bannerimage = Bannerimage::select('image_name','image_text')->where(array('status'=>1,'type'=>1))->orderByRaw("RAND()")->first();
        $view->with('bannerimage', $bannerimage);
    }
}