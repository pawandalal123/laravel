<?php 
namespace App\Http\ViewComposers;
use Appfiles\Common\Functions;
use Illuminate\Contracts\View\View;
use App\Http\Requests;
use Illuminate\Http\Request;

use Route;

class CommonDataComposer 
{

    public function compose(View $view) 
    {
        $function = new Functions();
        $eventdate = $function->eventsdate();
        $keyword = $function->searchkeyword();
        $view->with('eventdates', $eventdate);
        $view->with('keyword', $keyword);
    }
}