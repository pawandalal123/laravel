<?php 
namespace App\Http\ViewComposers;
use Appfiles\Common\Functions;
use App\Model\Common;
use Illuminate\Contracts\View\View;
use App\Http\Requests;
use Illuminate\Http\Request;

class CitylistComposer 
{
    public function compose(View $view) 
    {
    	$functions = new Functions();
    	$commonObj = new Common();
        // fetching category list        
        //$categoryList = $functions->fetchGetData('/api/category/catlist');
        // Fetching city list 
        $getcokkies = $commonObj->getcokkies('usercity');
        $location='';
        if($getcokkies)
        {
            $location = $getcokkies;
        }
        // $getCountry = $functions->fetchGetData('/api/city/checklocation?type=get&dataFormat=json&location='.$location);
        // $checkcountry = explode('--',$getCountry->cityname.'--');
        // if($checkcountry[0]!=='' && strtolower($checkcountry[0])=='india' )
        // {
        //     $cityList = $functions->fetchGetData('/api/city/citylist?getbykeyword=new-year&take=10');
        // }
        // else
        // {
            $cityList = $functions->fetchGetData('/api/city/citylist?getbylocation='.$location);

        // }

        
        //get cookiee city//
        $view->with('eventcitylist', $cityList->citylist);
        //$view->with('categoryData', $categoryList->catlist);
    }
}