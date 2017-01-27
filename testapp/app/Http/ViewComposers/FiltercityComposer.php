<?php 
namespace App\Http\ViewComposers;
use Appfiles\Common\Functions;
use App\Model\Event;
use Illuminate\Contracts\View\View;
use App\Http\Requests;
use Illuminate\Http\Request;

class FiltercityComposer
{
    public function compose(View $view) 
    {
        $showfilter='';
    	$functions = new Functions();
        $getcity = $functions->getcityforfilters();
        $conditionRawa="status=1  and (country like '%".$getcity."%' or state like '%".$getcity."%' or city like '%".$getcity."%')  and (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."') and category!=''";
        $checkcategory = Event::select('id','category')->whereRaw($conditionRawa)->first();
        //dd($checkcategory);
        if($checkcategory)
        {
            $showfilter=$getcity;
        }
        $view->with('showfilter', $showfilter);
    }
}