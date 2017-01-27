<?php 

namespace App\Http\ViewComposers;
use Illuminate\Contracts\View\View;
use App\Http\Requests;
use App\Model\Featureassign;
use Illuminate\Http\Request;
use Auth;

class FeaturesListComposer 
{
    public function compose(View $view) 
    {
        $features = new Featureassign();
        $user = Auth::user();
        $getfeatures='';
        $admintype='';
         if(!empty($user->id))
         {
             $getfeaturesList= Featureassign::select('features')->where(array('user_id'=>$user->id))->first();
             if($getfeaturesList)
             {
                $getfeatures=$getfeaturesList->features;
             }
             $admintype = $user->type;
         }
         $view->with('getfeatures', $getfeatures);
         $view->with('admintype', $admintype);
    }
}