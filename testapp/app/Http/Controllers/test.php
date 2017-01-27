<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Model\Event_category;
use App\Model\Common;
use App\Model\Events;
use Validator;
use DB;
use Auth;
use App\Model\Upcoming_event_city;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\Paginator;

class Test extends Controller
{
    // home page display function//
    public function index()
    {
    	//*category List*//
    	$allCategory = Event_category::all()->where('category_status',1);
        //*Upcomoin event city list*//
        $allCity = Upcoming_event_city::all();
    	//*Event List*//
    	$allEvents  = Events::where('is_active',1)->orderBy('id','desc')->paginate(12);
        $commonObj = new Common();
        $userCity = 'Gurgaon';
        $getcokkiesCity = $commonObj->getcokkies('usercity');
        $getcokkiesKeyword = $commonObj->getcokkies('Keyword');
        if($getcokkiesCity!=false)
        {
           $cokkiesCity = $getcokkiesCity;
        }
        else
        {
           $setCokkieCity = $commonObj->setcokkies('usercity',$userCity);
           $cokkiesCity = $userCity;
        }
    	return view('web/index')->with('categoryData',$allCategory)
                                    ->with('allEventsData',$allEvents)
                                    ->with('allCity',$allCity)
                                    ->with('getcokkiesKeyword',$getcokkiesKeyword)
                                    ->with('cokkiesCity',$cokkiesCity);
    }
	
}
