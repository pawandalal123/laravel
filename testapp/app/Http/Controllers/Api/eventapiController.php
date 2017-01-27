<?php 
namespace App\Http\Controllers\Api;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Common;
use Validator;
use DateTime;
use App\Http\Requests\EventRequest;
use App\Model\Mediadetail;
use App\Model\Userassign;

#use App\Model\Event_video_link;
use App\Model\User_favourite_events;
use App\Model\Userdetail;
use Appfiles\Common\Functions;
use DB;
use Auth;
use URL;
use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\UserdetailInterface;
use Appfiles\Repo\EventdetailInterface;
use Appfiles\Repo\ViewsCountRepository;
use Appfiles\Repo\UserassignInterface;
use Appfiles\Repo\TimezoneInterface;
use Appfiles\Repo\UserfavouriteeventsInterface;
use Appfiles\Repo\RecurringeventInterface;
use Appfiles\Repo\ScheduleInterface;
use Appfiles\Repo\SlotInterface;
use App\Model\Eventdetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;

class EventapiController extends Controller 
{

   public function  __construct(UsersInterface $user , EventInterface $event , EventdetailInterface $eventdetail ,  
    TicketInterface $ticket,UserassignInterface $userassign, UserdetailInterface $userdetail,TimezoneInterface $timezone ,ViewsCountRepository $ViewsCountRepository,Functions $function, UserfavouriteeventsInterface $favroteEvent , RecurringeventInterface $recurringevent ,ScheduleInterface $schedule , SlotInterface $slotlist)
    {
        $this->user=$user;
        $this->userdetail=$userdetail;
        $this->event=$event;
        $this->ticket=$ticket;
        $this->eventdetail = $eventdetail;
        $this->timezone = $timezone;
        $this->favroteEvent = $favroteEvent;
        $this->recurringevent = $recurringevent;
        $this->userassign =$userassign;
        $this->functions=$function;
        $this->viewcount = $ViewsCountRepository;
        $this->schedule = $schedule;
        $this->slotlist = $slotlist;
    }

  public function eventlistcat(Request $request)
  {
      $completeformData = Input::all();
      @extract($completeformData);
      $eventlist = array();
      $getticketeArray=array();
      $geteventDetailsArray = array();
      $data = array();
      $commonObj = new Common();
      $allEventsList = $this->event->eventlistall($request);
      $gettimezone = $this->timezone->all();
      foreach($gettimezone as $gettimezone)
      {
        $gettimezoneArray[$gettimezone->id] = $gettimezone->timezone;
      }
      $geteventDetails = $this->eventdetail->getByraw("thirdpartylink !=''");
      foreach($geteventDetails as $geteventDetails)
      {
        $geteventDetailsArray[] = $geteventDetails->event_id;
      }
    

       //ticket array//
      $ticketCondition = array('display_status'=>1,'status'=>1);
      $ticketeArray = $this->ticket->getallBy($ticketCondition);
      foreach($ticketeArray as $ticketeArray)
      {
        $getticketeArray[] = $ticketeArray->event_id;

       // $getticketesoldArray[$ticketeArray->event_id] = $ticketeArray->total_sold;

      }
      $is_favroute='loginrequired';
      $checkfavrouteList = array();
      if(Auth::check())
      {
           $userDetails = Auth::user();              
           $conditionFavroute = array('user_id'=>$userDetails->id,'type'=>'event');
           $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute);
          // print_r($checkfavroute);
           if(count($checkfavroute)>0)
           {
              foreach($checkfavroute as $checkfavroute)
              {
                $checkfavrouteList[] = $checkfavroute->event_id;
              }
           }
      }
      else if(isset($request->android_login_id) && $request->android_login_id!='')
      {
           $conditionFavroute = array('user_id'=>$request->android_login_id,'type'=>'event');
           $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute);
          // print_r($checkfavroute);
           if(count($checkfavroute)>0)
           {
              foreach($checkfavroute as $checkfavroute)
              {
                $checkfavrouteList[] = $checkfavroute->event_id;
              }
           }
      }
         //die;
      foreach ($allEventsList as $allEvents)
      {

          $timeZone = $gettimezoneArray[$allEvents->timezone_id];
         
          $getstartdate = $commonObj->ConvertGMTToLocalTimezone($allEvents->start_date_time,$timeZone);
          $imageUrl = '';
          if($allEvents->banner_image)
          {
            $imageUrl = $_ENV['CF_LINK'].'/event/'.$allEvents->id.'/banner/'.$allEvents->banner_image;

          }
          if($allEvents->no_dates==1)
          {
             //$eventcreateType='withoutdate';
           if($allEvents->start_date_time=='' || $allEvents->start_date_time=='0000-00-00 00:00:00')
           {
             $getstartdate='';

           }
          }
          $eventtype = 'offline';
          $mapurl = $commonObj->googleMapLink($allEvents->venue_name,$allEvents->city,$allEvents->state);
          $ButtonText = 'View Detail';
          $totalsold=0;
          if(in_array($allEvents->id, $getticketeArray) || in_array($allEvents->id, $geteventDetailsArray))
          {
             // $totalsold = $getticketesoldArray[$allEvents->id];
             $ButtonText = 'Buy Tickets';
          }

          if(Auth::check())
          {
             $is_favroute='No';
            if(in_array($allEvents->id,$checkfavrouteList))
            {
               $is_favroute='Yes';
            }
          }

          if($allEvents->event_mode==1)
          {
              $eventtype='online';
              $mapurl='';

          }
          
          $data[$allEvents->category][$allEvents->id] = array('eventname'=>$allEvents->title,
                                     'eventimage'=>$imageUrl,
                                      'eventtype'=>$eventtype,
                                      'eventvenue'=>ucwords($allEvents->venue_name),
                                      'eventcity'=>ucwords($allEvents->city),
                                      'eventcountry'=>ucwords($allEvents->country),
                                      'eventcategory'=>$allEvents->category,
                                      'favroute'=>$is_favroute,
                                      
                                      'eventstartdate'=>$getstartdate,
                                      'buttontext'=>$ButtonText,
                                      'eventurl'=>$commonObj->cleanURL($allEvents->url),
                                      'eventmapurl'=>$mapurl);
                
        # code...
      }
          
      //dd($data);
        return response()->json([
                       'eventlist' => $data,
                       'pagination'=>''
                       ]);
  }
 public  function cmp($a, $b) 
 {
  if($a['totalsold'] == $b['totalsold']) {
      return 0;
  }
  return ($a['totalsold'] > $b['totalsold']) ? -1 : 1;
  }
     //function for geeting event list live //
  public function eventlist(Request $request)
  {

      $completeformData = Input::all();
      @extract($completeformData);
      $eventlist = array();
      $getticketeArray=array();
      $geteventDetailsArray = array();
      // $getticketesoldArray = array();
      $data = array();
      $commonObj = new Common();
    
      $allEventsList = $this->event->eventlistall($request);
       

      $ticketed_events=array();
      $listed_events=array();

      //creating list of events which has tickets ie. set to Ticketed in events table
      foreach($allEventsList as $key=>$value)
      {
        if($value->ticketed==1)
        $ticketed_events[$value->event_id]=$value->event_id;
        array_push($listed_events,$value->event_id);
      }

      $listed_events_comma_seperated=implode($listed_events,',');

      $gettimezone = $this->timezone->all(array('id','timezone','name'));
      foreach($gettimezone as $gettimezone)
      {
        $gettimezoneArray[$gettimezone->id] = $gettimezone->timezone;
      }

      if(strlen($listed_events_comma_seperated)>0)
      {
        $geteventDetails = $this->eventdetail->getByraw("booknow_button_value !='' and event_id in(".$listed_events_comma_seperated.")",array('event_id','booknow_button_value'));
        foreach($geteventDetails as $geteventDetails)
        {
          $geteventDetailsArray[$geteventDetails->event_id] = $geteventDetails->booknow_button_value;
        }
      }
      
      //$featuredEvent = $this->getFeaturedEvents($gettimezoneArray,$geteventDetailsArray,$request);
      
      $is_favroute='loginrequired';
      $checkfavrouteList = array();
      
      if(isset($request->android_login_id) && $request->android_login_id!='')
      {
        $conditionFavroute = array('user_id'=>$request->android_login_id,'type'=>'event');
         $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
        // print_r($checkfavroute);
         if($checkfavroute)
         {
            foreach($checkfavroute as $checkfavroute)
            {
              $checkfavrouteList[] = $checkfavroute->event_id;
            }
         }

      }
      elseif(Auth::check())
      {
       // echo 1;
         $userDetails = Auth::user();              
         $conditionFavroute = array('user_id'=>$userDetails->id,'type'=>'event');
         $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
        // print_r($checkfavroute);
         if($checkfavroute)
         {
            foreach($checkfavroute as $checkfavroute)
            {
              $checkfavrouteList[] = $checkfavroute->event_id;
            }
         }
           //print_r($checkfavrouteList);
      }
         //die;

      foreach ($allEventsList as $allEvents)
      {
        //dd($allEvents);


          $timeZone = $gettimezoneArray[$allEvents->timezone_id];
          $timezonename = $commonObj->gettimezonename($timeZone);

           $day = '';  
          if(isset($allEvents->start_date_time)){
                $sdate = $commonObj->ConvertGMTToLocalTimezone($allEvents->start_date_time,$timeZone);
                $days = $this->ordinal_suffix(date('j',strtotime($sdate)));
                $day = date('j',strtotime($sdate));
                $day = $day.$days;  
          }
       
         
          $getstartdate = $commonObj->ConvertGMTToLocalTimezone($allEvents->start_date_time,$timeZone);
          $getenddate = $commonObj->ConvertGMTToLocalTimezone($allEvents->enddate,$timeZone);

          if($allEvents->no_dates==1)
            {
               //$eventcreateType='withoutdate';
             if($allEvents->start_date_time=='' || $allEvents->start_date_time=='0000-00-00 00:00:00')
             {
               $getstartdate='';

             }
            }
          $imageUrl = '';
          if($allEvents->banner_image)
          {
            
            $imageUrl = $_ENV['CF_LINK'].'/event/'.$allEvents->event_id.'/banner/'.$allEvents->banner_image;

          }
           $totalsold=0;
          $eventtype = 'offline';
          $mapurl = $commonObj->googleMapLink($allEvents->venue_name,$allEvents->city,$allEvents->state);
          $ButtonText = 'View Detail';
          if(in_array($allEvents->event_id, $ticketed_events) )
          {
             // $totalsold = $getticketesoldArray[$allEvents->id];
            $ButtonText = 'Buy Ticket';
            if(array_key_exists($allEvents->event_id, $geteventDetailsArray))
            {
              $ButtonText = $geteventDetailsArray[$allEvents->event_id];
            }
          }
          
          
          if(Auth::check() || (isset($request->android_login_id) && $request->android_login_id!=''))
          {
            $is_favroute='No';
            if(in_array($allEvents->event_id,$checkfavrouteList))
            {
               $is_favroute='Yes';
            }
          }
          // elseif(Auth::check())
          // {
          //    $is_favroute='No';
          //   if(in_array($allEvents->event_id,$checkfavrouteList))
          //   {
          //      $is_favroute='Yes';
          //   }
          // }
          

          if($allEvents->event_mode==1)
          {
              $eventtype='online';
              $mapurl='';

          }
          
           

          if(isset($request->requestfrom))
          {
            // if(!in_array($allEvents->event_id,config('commondata.Hardcoded_Events')) && $allEvents->recurring_type!=3)
             if(!in_array($allEvents->event_id,config('commondata.Hardcoded_Events')))
            {
              $data[]=array('eventid'=>$allEvents->event_id,
                            'eventname'=>$allEvents->title,
                            'eventimage'=>$imageUrl,
                          
                            'eventtype'=>$eventtype,
                            'eventvenue'=>ucwords($allEvents->venue_name),
                            'eventcity'=>ucwords($allEvents->city),
                            'recurring_type'=>$allEvents->recurring_type,
                            'eventcountry'=>ucwords($allEvents->country),
                            'eventcategory'=>$allEvents->category,
                            'favroute'=>$is_favroute,
                            'timezonename'=>$timezonename,
                            'eventstartdate'=>$getstartdate,
                            'eventenddate'=>$getenddate,
                            'suffixDate'=>$day,
                            'min_ticket_price'=>$allEvents->max_ticket_price,
                            'max_ticket_price'=>$allEvents->max_ticket_price,
                            'buttontext'=>$ButtonText,
                            'eventurl'=>$commonObj->cleanURL($allEvents->url),
                            'closed'=>$commonObj->cleanURL($allEvents->closed),
                            'eventmapurl'=>$mapurl);

            }
          }
          else
          {
            $data[$allEvents->event_id] = array('eventname'=>$allEvents->title,
                                        'eventimage'=>$imageUrl,
                                        'eventtype'=>$eventtype,
                                        'eventvenue'=>ucwords($allEvents->venue_name),
                                        'eventcity'=>ucwords($allEvents->city),
                                        'recurring_type'=>$allEvents->recurring_type,
                                        'eventcountry'=>ucwords($allEvents->country),
                                        'eventcategory'=>$allEvents->category,
                                        'favroute'=>$is_favroute,
                                        'eventaddress'=>$allEvents->address1,
                                        
                                        'timezonename'=>$timezonename,
                                        'eventstartdate'=>$getstartdate,
                                        'suffixDate'=>$day,
                                        'eventenddate'=>$getenddate,
                                        'min_ticket_price'=>$allEvents->max_ticket_price,
                                        'max_ticket_price'=>$allEvents->max_ticket_price,
                                        'buttontext'=>$ButtonText,
                                        'eventurl'=>$commonObj->cleanURL($allEvents->url),
                                        'closed'=>$commonObj->cleanURL($allEvents->closed),
                                        'eventmapurl'=>$mapurl);
          }
          

                # code...
      }
       // uasort($data, function ($a, $b) { return $a['totalsold'] - $b['totalsold']; });
        $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $pagination =  array('total' => $allEventsList->total(),
                            'per_page'     => $allEventsList->perPage(),
                            'current_page' => $allEventsList->currentPage(),
                            'last_page'    => $allEventsList->lastPage(),
                            'nextpage'     => $allEventsList->nextPageUrl(),
                            'prevpage'     => $allEventsList->previousPageUrl(),
                            'refrlUrl'     => $urlPage,
                            ); 
    //dd($data);
       
        return response()->json([
                     'eventlist' => $data,
                     'pagination'=>$pagination
                     
                     ]);
    }

  public function getFeaturedEvents($gettimezoneArray=null,$geteventDetailsArray=null,$request){
    //dd($request->cityname)
      if (strpos($request->cityname, '--') !== false) {      
               $wherecondition = "featured_city='1' AND end_date_time >= '$datetime'";
      } else{
         $wherecondition = "featured_city='1' AND end_date_time >= '$datetime'";
      }
      $datetime  = date("Y-m-d h:i");
      $select = array('id','title','venue_name','recurring_type','no_dates','city','state','country','category','url','event_mode','start_date_time','end_date_time','timezone_id','banner_image','ticketed','created_at','closed','banner_image','recurring_type');
      $featuredData = $this->event->getallByRaw($wherecondition,$select);
      $returnData = array();
      $commonObj = new Common();
      if(count($featuredData)>0){
        foreach($featuredData as $featuredDatas){

           $eventtype = 'offline';

          if($featuredDatas->event_mode==1)
          {
              $eventtype='online';
              $mapurl='';

          }

          if($featuredDatas->banner_image)
          {
            
            $imageUrl = $_ENV['CF_LINK'].'/event/'.$featuredDatas->id.'/banner/'.$featuredDatas->banner_image;

          }
         $ButtonText = 'Buy Ticket';

          $mapurl = $commonObj->googleMapLink($featuredDatas->venue_name,$featuredDatas->city,$featuredDatas->state);
          
          $timeZone = $gettimezoneArray[$featuredDatas->timezone_id]; 
          $timezonename = $commonObj->gettimezonename($timeZone);
          $sdate='';
          $edate='';
          $day ='';
          if(isset($featuredDatas->start_date_time)){
              $sdate = $commonObj->ConvertGMTToLocalTimezone($featuredDatas->start_date_time,$timeZone);
              $days = $this->ordinal_suffix(date('j',strtotime($sdate)));
              $day = date('j',strtotime($sdate));
              $day = $day.$days;  
          }

          if(isset($featuredDatas->end_date_time)){
              $edate = $commonObj->ConvertGMTToLocalTimezone($featuredDatas->start_date_time,$timeZone);
          
          }

          $returnData[$featuredDatas->id] = array('eventid'=>$featuredDatas->id,
                            'eventname'=>$featuredDatas->title,
                            'eventimage'=>$imageUrl,
                            'eventtype'=>$eventtype,
                            'eventvenue'=>ucwords($featuredDatas->venue_name),
                            'eventcity'=>ucwords($featuredDatas->city),
                            'recurring_type'=>$featuredDatas->recurring_type,
                            'eventcountry'=>ucwords($featuredDatas->country),
                            'eventcategory'=>$featuredDatas->category,
                            'favroute'=>'no',
                            'timezonename'=>$timezonename,
                            'eventstartdate'=>$sdate,
                            'eventenddate'=>$edate,
                            'suffixDate'=>$day,
                            'buttontext'=>$ButtonText,
                            'eventurl'=>$commonObj->cleanURL($featuredDatas->url),
                            'closed'=>$commonObj->cleanURL($featuredDatas->closed),
                            'eventmapurl'=>$mapurl);       

        }


            return $returnData;
         }
   
  }


  public function ordinal_suffix($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return 'st';
            case 2: return 'nd';
            case 3: return 'rd';
        }
    }
    return 'th';
}

  public function eventlistmongo(Request $request)
  {
      $completeformData = Input::all();
      @extract($completeformData);
      $eventlist = array();
      $getticketeArray=array();
      $geteventDetailsArray = array();
      // $getticketesoldArray = array();
      $data = array();
      $commonObj = new Common();
      $allEventsList = $this->event->eventlistmongo($request);
      
      $gettimezone = $this->timezone->all(array('id','timezone','name'));
      foreach($gettimezone as $gettimezone)
      {
        $gettimezoneArray[$gettimezone->id] = $gettimezone->timezone;
      }
      $geteventDetails = $this->eventdetail->getByraw("booknow_button_value !=''",array('event_id','booknow_button_value'));
      foreach($geteventDetails as $geteventDetails)
      {
        $geteventDetailsArray[$geteventDetails->event_id] = $geteventDetails->booknow_button_value;
      }

       //ticket array//
      $ticketCondition = array('display_status'=>1,'status'=>1);
      $ticketeArray = $this->ticket->getallBy($ticketCondition,array('event_id'));
      foreach($ticketeArray as $ticketeArray)
      {
        $getticketeArray[] = $ticketeArray->event_id;
       
      }
      // $getticketesoldArray = $this->ticket->ticketsold();

      $is_favroute='loginrequired';
      $checkfavrouteList = array();
      if(Auth::check())
      {
       // echo 1;
         $userDetails = Auth::user();              
         $conditionFavroute = array('user_id'=>$userDetails->id,'type'=>'event');
         $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
        // print_r($checkfavroute);
         if($checkfavroute)
         {
            foreach($checkfavroute as $checkfavroute)
            {
              $checkfavrouteList[] = $checkfavroute->event_id;
            }
         }
           //print_r($checkfavrouteList);
      }
      

       //ticket array//
      foreach ($allEventsList as $allEvents)
      {
        //dd($allEvents);
        
          $timeZone = $gettimezoneArray[$allEvents['timezone_id']];
          $timezonename = $commonObj->gettimezonename($timeZone);
          $date = $allEvents['start_date_time'];
          $start_date_time = date('Y-m-d H:i:s',$date->sec);
          $getstartdate = $commonObj->ConvertGMTToLocalTimezone($start_date_time,$timeZone);

          if($allEvents['no_dates']==1)
            {
               //$eventcreateType='withoutdate';
             if($start_date_time=='' || $start_date_time=='0000-00-00 00:00:00')
             {
               $getstartdate='';
             }
            }
          $imageUrl = '';
          if($allEvents['banner_image'])
          {
            $imageUrl = $_ENV['CF_LINK'].'/event/'.$allEvents['event_id'].'/banner/'.$allEvents['banner_image'];

          }
           $totalsold=0;
          $eventtype = 'offline';
          $mapurl = $commonObj->googleMapLink($allEvents['venue_name'],$allEvents['city'],$allEvents['state']);
          $ButtonText = 'View Detail';
          $eventid = $allEvents['event_id'];
          if(in_array($allEvents['event_id'], $getticketeArray) )
          {
             // $totalsold = $getticketesoldArray[$allEvents->event_id];
            $ButtonText = 'Buy Ticket';
            if(array_key_exists(intval($eventid), $geteventDetailsArray))
            {
              $ButtonText = $geteventDetailsArray[$allEvents['event_id']];
            }
          }
          
          if(Auth::check())
          {
             $is_favroute='No';
            if(in_array(intval($allEvents['event_id']),$checkfavrouteList))
            {
               $is_favroute='Yes';
            }
          }

          if($allEvents['event_mode']==1)
          {
              $eventtype='online';
              $mapurl='';

          }
          
            $data[$allEvents['event_id']] = array('eventname'=>$allEvents['title'],
                                        'eventimage'=>$imageUrl,
                                        'eventtype'=>$eventtype,
                                        'recurring_type'=>$allEvents['recurring_type'],
                                        'eventvenue'=>ucwords($allEvents['venue_name']),
                                        'eventcity'=>ucwords($allEvents['city']),
                                        'eventcountry'=>ucwords($allEvents['country']),
                                        'eventcategory'=>$allEvents['category'],
                                        'favroute'=>$is_favroute,
                                        'timezonename'=>$timezonename,
                                        'eventstartdate'=>$getstartdate,
                                        'buttontext'=>$ButtonText,
                                        'eventurl'=>$commonObj->cleanURL($allEvents['url']),
                                        'eventmapurl'=>$mapurl);
        # code...
      }
       // uasort($data, function ($a, $b) { return $a['totalsold'] - $b['totalsold']; });
        $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $pagination =  array('total' => $allEventsList->total(),
                            'per_page'     => $allEventsList->perPage(),
                            'current_page' => $allEventsList->currentPage(),
                            'last_page'    => $allEventsList->lastPage(),
                            'nextpage'     => $allEventsList->nextPageUrl(),
                            'prevpage'     => $allEventsList->previousPageUrl(),
                            'refrlUrl'     => $urlPage,
                            ); 
    //dd($data);
      return response()->json([
                   'eventlist' => $data,
                   'pagination'=>$pagination
                   ]);

    }
             /////////past event details///////////
  public function eventlistpast(Request $request)
  {
      $completeformData = Input::all();
      @extract($completeformData);
      $eventlist = array();
      $getticketeArray=array();
      $geteventDetailsArray = array();
      // $getticketesoldArray = array();
      $data = array();
      $commonObj = new Common();
      $allEventsList = $this->event->eventlistpast($request);
      $gettimezone = $this->timezone->all(array('id','timezone','name'));
      foreach($gettimezone as $gettimezone)
      {
        $gettimezoneArray[$gettimezone->id] = $gettimezone->timezone;
      }
      $geteventDetails = $this->eventdetail->getByraw("booknow_button_value !=''",array('event_id','booknow_button_value'));
      foreach($geteventDetails as $geteventDetails)
      {
        $geteventDetailsArray[$geteventDetails->event_id] = $geteventDetails->booknow_button_value;
      }

       //ticket array//
      $ticketCondition = array('display_status'=>1,'status'=>1);
      $ticketeArray = $this->ticket->getallBy($ticketCondition,array('event_id'));
      foreach($ticketeArray as $ticketeArray)
      {
        $getticketeArray[] = $ticketeArray->event_id;
       
      }
      // $getticketesoldArray = $this->ticket->ticketsold();

      $is_favroute='loginrequired';
      $checkfavrouteList = array();
      if(isset($request->android_login_id) && $request->android_login_id!='')
      {
        $conditionFavroute = array('user_id'=>$request->android_login_id,'type'=>'event');
         $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
        // print_r($checkfavroute);
         if($checkfavroute)
         {
            foreach($checkfavroute as $checkfavroute)
            {
              $checkfavrouteList[] = $checkfavroute->event_id;
            }
         }

      }
      elseif(Auth::check())
      {
        // echo 1;
       $userDetails = Auth::user();              
       $conditionFavroute = array('user_id'=>$userDetails->id,'type'=>'event');
       $checkfavroute = $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
      // print_r($checkfavroute);
       if($checkfavroute)
       {
          foreach($checkfavroute as $checkfavroute)
          {
            $checkfavrouteList[] = $checkfavroute->event_id;
          }
       }
        //print_r($checkfavrouteList);
      }
         //die;
      foreach ($allEventsList as $allEvents)
      {

          $timeZone = $gettimezoneArray[$allEvents->timezone_id];
          $timezonename = $commonObj->gettimezonename($timeZone);
         
          $getstartdate = $commonObj->ConvertGMTToLocalTimezone($allEvents->start_date_time,$timeZone);

          if($allEvents->no_dates==1)
            {
               //$eventcreateType='withoutdate';
             if($allEvents->start_date_time=='' || $allEvents->start_date_time=='0000-00-00 00:00:00')
             {
               $getstartdate='';

             }
            }
          $imageUrl = '';
          if($allEvents->banner_image)
          {
            $imageUrl = $_ENV['CF_LINK'].'/event/'.$allEvents->id.'/banner/'.$allEvents->banner_image;

          }
           $totalsold=0;
          $eventtype = 'offline';
          $mapurl = $commonObj->googleMapLink($allEvents->venue_name,$allEvents->city,$allEvents->state);
          $ButtonText = 'View Detail';
          if(array_key_exists($allEvents->id, $geteventDetailsArray))
          {
            $ButtonText = $geteventDetailsArray[$allEvents->id];

          }
          elseif(in_array($allEvents->id, $getticketeArray) )
          {
             // $totalsold = $getticketesoldArray[$allEvents->id];
             $ButtonText = 'Buy Tickets';
          }
          if(isset($request->android_login_id) && $request->android_login_id!='')
          {
            $is_favroute='No';
            if(in_array($allEvents->id,$checkfavrouteList))
            {
               $is_favroute='Yes';
            }
          }
          elseif(Auth::check())
          {
             $is_favroute='No';
            if(in_array($allEvents->id,$checkfavrouteList))
            {
               $is_favroute='Yes';
            }
          }

          if($allEvents->event_mode==1)
          {
              $eventtype='online';
              $mapurl='';

          }
           if(isset($request->requestfrom))
          {
            if(!in_array($allEvents->id,config('commondata.Hardcoded_Events')) || $allEvents->recurring_type!=3)
            {
              $data[]=array('eventid'=>$allEvents->id,
                            'eventname'=>$allEvents->title,
                            'eventimage'=>$imageUrl,
                            'eventtype'=>$eventtype,
                            'eventvenue'=>ucwords($allEvents->venue_name),
                            'eventcity'=>ucwords($allEvents->city),
                            'eventcountry'=>ucwords($allEvents->country),
                            'eventcategory'=>$allEvents->category,
                            'favroute'=>$is_favroute,
                            'timezonename'=>$timezonename,
                            'eventstartdate'=>$getstartdate,
                            'buttontext'=>$ButtonText,
                            'eventurl'=>$commonObj->cleanURL($allEvents->url),
                            'eventmapurl'=>$mapurl);

            }
          }
          else
          {
            $data[$allEvents->id] = array('eventname'=>$allEvents->title,
                                        'eventimage'=>$imageUrl,
                                        'eventtype'=>$eventtype,
                                        'eventvenue'=>ucwords($allEvents->venue_name),
                                        'eventcity'=>ucwords($allEvents->city),
                                        'eventcountry'=>ucwords($allEvents->country),
                                        'eventcategory'=>$allEvents->category,
                                        'favroute'=>$is_favroute,
                                        'timezonename'=>$timezonename,
                                        'eventstartdate'=>$getstartdate,
                                        'buttontext'=>$ButtonText,
                                        'eventurl'=>$commonObj->cleanURL($allEvents->url),
                                        'eventmapurl'=>$mapurl);

          }
          
                
        # code...
      }
       // uasort($data, function ($a, $b) { return $a['totalsold'] - $b['totalsold']; });
        $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $pagination =  array('total' => $allEventsList->total(),
                            'per_page'     => $allEventsList->perPage(),
                            'current_page' => $allEventsList->currentPage(),
                            'last_page'    => $allEventsList->lastPage(),
                            'nextpage'     => $allEventsList->nextPageUrl(),
                            'prevpage'     => $allEventsList->previousPageUrl(),
                            'refrlUrl'     => $urlPage,
                            ); 
    //dd($data);
      return response()->json([
                     'eventlist' => $data,
                     'pagination'=>$pagination
                     ]);
    }

  //function for display the complete event detail//
  //get the parametere//

  public function eventdetails(Request $request)
  {
    $dataEvent = '';
    $thirdpartylink='';
    $websitelink='';
    $facebooklink='';
    $twitterlink='';
    $gpluslink='';
    $youtubelink='';
    $linkedinlink='';
    $Preventurl='';
    $Nexteventurl='';
    $breadcus='';
    $eventshow='';
    $datetoDisplay='';
    $eventhashtag='';
    $scheduleList = array();
    $condition = array('id'=>$request->id,'status'=>1);
    if($request->preview==1)
    {
      $condition = array('id'=>$request->id);
    }
    if($request->useridlogin)
    {
      $condition = array('id'=>$request->id,'user_id'=>$request->useridlogin);
    }
    if($request->loginid)
    {
        $checkAssign = $this->userassign->getBy(array('admin_user_id'=>$request->useridlogin,'user_id'=>$request->loginid,'status'=>1));
        if($checkAssign)
        {
            $userloginas =$this->user->find($request->loginid);
            $breadcus = 'Currently Login As:'.$userloginas->email;
            $condition =array('id'=>$request->id,'user_id'=>$request->loginid);
        }
        else
        {
             $dataEvent = 'notfound';
            return response()->json([
                   'eventdetails' =>$dataEvent,
                   ]);
        }
    }
   
    $commonObj = new Common();
    $eventDetails = $this->event->getBy($condition);
    
    if($eventDetails)
    {
      // $viewconter= $commonObj->makeviewcount($request->id,'event');
      if(strcmp($request->eventurl,$eventDetails->url)!=0)
      {
                  return response()->json([
                   'redirectTo' => URL::to('/').'/event/'.$eventDetails->url.'/'.$request->id
                   ]);
      }
      $imageUrl = '';
      $eventtype='offline';
      $eventcreateType='normal';
      $mapurl = $commonObj->googleMapLink($eventDetails->venue_name,$eventDetails->city,$eventDetails->state);
      if($eventDetails->banner_image)
      {
        // $imageUrl = URL::asset('uplode/'.$eventDetails->user_id.'/'.$eventDetails->banner_image);
        $imageUrl = $_ENV['CF_LINK'].'/event/'.$request->id.'/banner/'.$eventDetails->banner_image;

      }
      if($eventDetails->event_mode==1)
      {
          $eventtype='online';
          $mapurl='';
      }
      
      
      $organisation =  $this->userdetail->getBy(array('user_id'=>$eventDetails->user_id), array('profile_url','organization_url','about_organization','ua_number','organization_name','organization_logo','user_id'));
      $eventotherdetails = $this->eventdetail->getBy(array('event_id'=>$eventDetails->id));
      $showmap='No';
      
              //ConvertGMTToLocalTimezone//
      $gettimezone =  $this->timezone->find($eventDetails->timezone_id);
      $gettimezonename =$commonObj->gettimezonename($gettimezone->timezone);
      $eventenddate = $eventDetails->end_date_time;
      if($eventDetails->end_date_time=='0000-00-00 00:00:00')
      {
        $eventenddate = $eventDetails->start_date_time;
      } 
      $getstartdate = $commonObj->ConvertGMTToLocalTimezone($eventDetails->start_date_time,$gettimezone->timezone);
      $getenddate   = $commonObj->ConvertGMTToLocalTimezone($eventenddate,$gettimezone->timezone);
             //event detail array//
      $ispassedEvent='no';
      $today  = new DateTime();
      $past   = new DateTime($getenddate);
      if($eventDetails->no_dates==1)
      {
         //$eventcreateType='withoutdate';
         if($eventDetails->start_date_time=='' || $eventDetails->start_date_time=='0000-00-00 00:00:00')
         {
           $getstartdate='';

         }
         if($eventDetails->end_date_time=='' || $eventDetails->end_date_time=='0000-00-00 00:00:00')
         {
           $getenddate='';
           $past   = new DateTime($getstartdate);
         }
   
      }
      if($past<$today)
      {
          $ispassedEvent='yes';
      }
      if($eventDetails->recurring_type==1)////////multi dates
      {
            $eventcreateType='multipledates';
            $conditionIm= array('event_id'=>$request->id,'status'=>1,'type'=>1);
            $showlist = $this->recurringevent->getallBy($conditionIm);
                       //holidaylist//
            $holiday = 'dates';
            $conditionholi = array('event_id'=>$request->id,'status'=>1,'type'=>2);
            $conditionExtraDate = array('event_id'=>$request->id,'status'=>1,'type'=>3);
            $holidayslist =  $this->recurringevent->getListby($conditionholi,$holiday);
            $ExtraDateList =  $this->recurringevent->getListby($conditionExtraDate,$holiday);
            if($eventDetails->occurrence==1)
            {
              $datetoDisplayArray = $commonObj->returnBetweenDates($getstartdate,$getenddate);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
              
            }   
            if($eventDetails->occurrence==2)
            {
              $conditionIm = array('event_id'=>$request->id,'status'=>1,'type'=>3,'occurrence'=>2);
              $values='name';
              $days = $this->recurringevent->getListby($conditionIm,$values);
              //$days=  array('Monday','Thursday');

              $datetoDisplayArray = $commonObj->returndaysBetweenDates($getstartdate,$getenddate ,$days);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }
            if($eventDetails->occurrence==3)
            {
              $conditionIm = array('event_id'=>$request->id,'status'=>1,'type'=>3,'occurrence'=>3);
              $values='monthly';
              $days = $this->recurringevent->getListby($conditionIm,$values);
        
              $datetoDisplayArray = $commonObj->returndatesBetweenDates($getstartdate,$getenddate ,$days);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }
            if($eventDetails->occurrence==4)
            {
              $conditionIm = array('event_id'=>$request->id,'status'=>1,'type'=>3,'occurrence'=>4);
              $values='dates';
              $datetoDisplayArray = $this->recurringevent->getListby($conditionIm,$values);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }
            $dateswithShow='';
            $eventshow = array();
            if(count($showlist)>0)
            {
              foreach ($showlist as $showlist)
              {
                    $eventshow[$showlist->id] =  array('showname'=>$showlist->name,
                                                       'start_time'=>$showlist->start_time,
                                                       'end_time'=>$showlist->end_time);
              }
            }
           
      }
      elseif($eventDetails->recurring_type==3)///////// multi venue
      {
           $eventcreateType='multiplace';
           $slotlistArray = array();
           $slotcondition = "status=1 and end_date_time>='".date('Y-m-d H:i:s')."'";
           $getslotlist  = $this->slotlist->getByraw($slotcondition);
           if(count($getslotlist)>0)
           {
            foreach($getslotlist as $getslotlist)
            {
              $getslotstartdate = $commonObj->ConvertGMTToLocalTimezone($getslotlist->start_date_time,$gettimezone->timezone);
              $getslotenddate   = $commonObj->ConvertGMTToLocalTimezone($getslotlist->end_date_time,$gettimezone->timezone);
              $slotlistArray[$getslotlist->schedule_id][$getslotlist->id] = array('slot_start'=>$getslotstartdate,'slot_end'=>$getslotenddate);
            }
           }

           $getallShedule = $this->schedule->getallBy(array('event_id'=>$request->id,'status'=>1));
           foreach($getallShedule as $getallShedule)
           {
             // $scheduleList[$getallShedule->city][$getallShedule->id]['slotsdetail']='';
             if(array_key_exists($getallShedule->id, $slotlistArray))
             {
              $scheduleList[$getallShedule->city][$getallShedule->id] = array('schedulename'=>$getallShedule->schedule_name,
                                                       'schedule_venue_name'=>$getallShedule->venue_name,
                                                       'schedule_address1'=>$getallShedule->address1,
                                                       'schedule_pincode'=>$getallShedule->pincode,
                                                       'schedule_city'=>ucwords($getallShedule->city),
                                                       'schedule_state'=>ucwords($getallShedule->state),
                                                       'schedule_country'=>ucwords($getallShedule->country));
              $scheduleList[$getallShedule->city][$getallShedule->id]['slotsdetail']  = $slotlistArray[$getallShedule->id];
             }
           }

      }
          
          $eventdateTodisplay = '';
          $eventshowId = '';
          $dateswithShow = array();
           //print_r($datetoDisplay);
          if(!empty($datetoDisplay))
          {
            foreach($datetoDisplay as $datetoDisplays)
            {
              if(count($eventshow)>0)
              {
                foreach($eventshow as $eventkey=>$evetvalues)
                {
                  $dateswithShow[] = $datetoDisplays.' '.$evetvalues['start_time'].'/'.$eventkey;
                }
              }
              else
              {
                $dateswithShow[] = $datetoDisplays.' '.'00:00'.'/';
              }
            }
           
           // echo $commonObj->ConvertGMTToLocalTimezone(date('Y-m-d H:i'),$gettimezone->timezone);
         
            foreach($dateswithShow as $showidkey=>$dateshowtime)
            {
              $eventdateTodisplay = '';
              $datetimetomaatch = explode('/', $dateshowtime);
              //echo $showidkey.$datetimetomaatch[0];
              $todaydatetime  = strtotime($commonObj->ConvertGMTToLocalTimezone(date('Y-m-d H:i'),$gettimezone->timezone));
              $dateofshow   = strtotime($datetimetomaatch[0]);
              if($dateofshow>=$todaydatetime && $datetimetomaatch[0]<=$getenddate && $datetimetomaatch[0]>=$getstartdate)
              {
                $eventdateTodisplay = substr($datetimetomaatch[0], 0,10);
                if($datetimetomaatch[1]!='')
                {
                  $eventshowId = $datetimetomaatch[1];
                }
                break;
              }
            }
          }
          //////// view count/////
          $getViewCount=0;
          // $getViewlist = $this->viewcount->getallBy(array('view_id'=>$eventDetails->id,'type'=>'event'));
          // if(count($getViewlist)>0)
          // {
          //   $getViewCount = count($getViewlist);
          // }
           /////////next and pre events////////
          // $nexteventCondition ="id >'".$eventDetails->id."' and   city like '".$eventDetails->city."' and end_date_time >= '".date('Y-m-d H:i:s')."' and status=1";
          // $PreeventCondition ="id <'".$eventDetails->id."' and  city like '".$eventDetails->city."' and end_date_time >= '".date('Y-m-d H:i:s')."' and status=1";
          // $nextevent = $this->event->getByraw($nexteventCondition,array('id','url'),'asc');
          // $preevent = $this->event->getByraw($PreeventCondition,array('id','id','id','url'),'desc');

          // if($nextevent)
          // {
          //   $Preventurl = URL::to('event/'.$commonObj->cleanURL($nextevent->url).'/'.$nextevent->id);
          // }
          // if($preevent)
          // {
          //   $Nexteventurl = URL::to('event/'.$commonObj->cleanURL($preevent->url).'/'.$preevent->id);
          // }
         
          $adminUserRelation = Userassign::where(array('user_id'=>$eventDetails->user_id,'status'=>1))->first();
         
 // dd( $adminUserRelation);
          $accountManagerName ="Support";
          $accountManagerMobile ='+91-9555601111';  

          if(count($adminUserRelation)>0){

             $adminUser = $this->user->getBy(array('id'=>$adminUserRelation->admin_user_id),array('name','mobile')); 

             if( count( $adminUser) >0 && strlen(trim($adminUser->name))>0 && strlen(trim($adminUser->mobile))>0 )
             {
             $accountManagerName = $adminUser->name;
             $accountManagerMobile = $adminUser->mobile;
             }

          }

    

          $sourceurl = URL::to('event/'.$commonObj->cleanURL($eventDetails->url).'/'.$eventDetails->id);
          $getcount = 0;
          $dataEvent = array('userid'=>$eventDetails->user_id,
                             'eventid'=>$eventDetails->id,
                             'eventname'=>$eventDetails->title,
                             'eventimage'=>$imageUrl,
                             'recurring_type'=>$eventDetails->recurring_type,
                             'dateTodisplay'=>$eventdateTodisplay,
                             'eventshowId'=>$eventshowId,
                             'eventView'=>$getViewCount,
                             'eventtype'=>$eventtype,
                             'timezonename'=>$gettimezonename,
                             'eventcreateType'=>$eventcreateType,
                             'scheduleList'=>$scheduleList,
                             'eventoccurrence'=>$eventDetails->occurrence,
                             'eventdatetoDisplay'=>$datetoDisplay,
                             'eventshowlist'=>$eventshow,
                             'eventcategory'=>$eventDetails->category,
                             'eventdescription'=>$eventDetails->description,
                             'eventvenue'=>$eventDetails->venue_name,
                             'eventaddress1'=>$eventDetails->address1,
                             'eventcity'=>$eventDetails->city,
                             'eventstate'=>$eventDetails->state,
                             'eventcountry'=>$eventDetails->country,
                             'eventlatitude'=>$eventDetails->latitude,
                             'eventlongitude'=>$eventDetails->longitude,
                             'ispassedEvent'=>$ispassedEvent,
                             'eventpincode'=>$eventDetails->pincode,
                             'status'=>$eventDetails->status,

                             'supportName'=>$accountManagerName,
                             'supportMobile'=>$accountManagerMobile,

                             'breadcus'=>$breadcus,
                             'eventstartdate'=>$getstartdate,
                             'eventenddate'=>$getenddate,
                             'eventtimezone'=>$gettimezone->timezone,
                             'eventurl'=>$commonObj->cleanURL($eventDetails->url),
                             'private'=>$eventDetails->private,
                             'getcount'=>$getcount,
                             'eventmapurl'=>$mapurl,
                             'eventmetadesc'=>$eventDetails->meta_description,
                             'eventsource'=>$eventDetails->source);
              $dataEvent['website']=$websitelink;
              $dataEvent['facebooklink']=$facebooklink;
              $dataEvent['Prevent']=$Preventurl;
              $dataEvent['Nextevent']=$Nexteventurl;
              $dataEvent['twitterlink']=$twitterlink;
              $dataEvent['eventhashtag']=$eventhashtag;
              $dataEvent['gpluslink']=$gpluslink;
              $dataEvent['youtubelink']=$youtubelink;
              $dataEvent['linkedinlink']=$linkedinlink;
              $dataEvent['thirdpartylink']=$thirdpartylink;
              $dataEvent['eventmapshow']=$showmap;
              $dataEvent['eventterm']="";
              $dataEvent['showremaining']="";
              $dataEvent['ua_number']='';
              $dataEvent['is_user']='no';
              $dataEvent['button_value']='Buy tickets';
              if(Auth::check())
              {
                $user = Auth::user();
                if($user->id==$eventDetails->user_id)
                {
                   $dataEvent['is_user']='yes';
                   
                }

                    $dataEvent['loginname']   =    $user->name;
                    $dataEvent['loginemail']  =    $user->email;
                    $dataEvent['loginmobile'] =    $user->mobile;

              } else {

                  $dataEvent['loginname']   = "";
                  $dataEvent['loginemail']  = "";
                  $dataEvent['loginmobile'] = "";
              }

              $organiserImage = URL::asset('web/images/org.jpg');
              if($organisation)
              {
                // $userDetails = $this->user->find($eventDetails->user_id);
                $organiserurl = URL::to('/'.$organisation->profile_url);
                $about = '';
                $organisationname = $organisation->profile_url;
                if($organisation->organization_url)
                {
                  $organiserurl = URL::to('/'.$commonObj->cleanURL($organisation->organization_url));
                }
                if($organisation->about_organization)
                {
                  $about =substr($organisation->about_organization,0,20);
                }
                if($organisation->ua_number)
                {
                  $dataEvent['ua_number']=$organisation->ua_number;
                }
                if($organisation->organization_name)
                {
                   $organisationname = $organisation->organization_name;
                }
                if($organisationname=='')
                {
                  $organisationname = $organisation->profile_url;
                }
                if($organisation->organization_logo)
                {
                  // $organiserImage = URL::asset('uplode/'.$organisation->user_id.'/'.$organisation->organisation_logo);
                  $organiserImage = $_ENV['CF_LINK'].'/user/'.$organisation->user_id.'/organizer/logo/'.$organisation->organization_logo;
                }
                $dataEvent['organisername']= $organisationname;
                // $dataEvent['organiseremail']= $userDetails->email;
                $dataEvent['organiseremail']= '';
                $dataEvent['organiserabout']= $about;
                $dataEvent['organiserurl']= $organiserurl;
              }
              else
              {
                $dataEvent['organisername']= '';
                $dataEvent['organiseremail']= '';
                $dataEvent['organiserabout']= '';
                $dataEvent['organiser']= '';
                $dataEvent['organiserurl']= '';

              }
              $dataEvent['organiserimage']= $organiserImage;
              if($eventotherdetails)
              {
                if($eventotherdetails->show_map==1)
                {
                  $showmap='Yes';
                }
          
                if($eventotherdetails->eventhashtag)
                {
                  $eventhashtag=urlencode($eventotherdetails->eventhashtag);
                }
                if($eventotherdetails->thirdpartylink)
                {
                  $thirdpartylink=$eventotherdetails->thirdpartylink;
                }
                if($eventotherdetails->contact_website_url)
                {
                  $websitelink=$eventotherdetails->contact_website_url;
                }
                if($eventotherdetails->facebook_link)
                {
                  $facebooklink=$eventotherdetails->facebook_link;
                }
                if($eventotherdetails->google_link)
                {
                  $gpluslink=$eventotherdetails->google_link;
                }
                if($eventotherdetails->twitter_link)
                {
                  $twitterlink=$eventotherdetails->twitter_link;
                }
                if($eventotherdetails->youtube_link)
                {
                  $youtubelink=$eventotherdetails->youtube_link;
                }
                if($eventotherdetails->linkedin_link)
                {
                  $linkedinlink=$eventotherdetails->linkedin_link;
                }
                if($eventotherdetails->booknow_button_value)
                {
                 $dataEvent['button_value']=$eventotherdetails->booknow_button_value;
                }
                $dataEvent['website']=$websitelink;
                $dataEvent['facebooklink']=$facebooklink;
                $dataEvent['twitterlink']=$twitterlink;
                $dataEvent['eventhashtag']=$eventhashtag;
                $dataEvent['gpluslink']=$gpluslink;
                $dataEvent['youtubelink']=$youtubelink;
                $dataEvent['linkedinlink']=$linkedinlink;
                $dataEvent['thirdpartylink']=trim($thirdpartylink);
                $dataEvent['eventmapshow']=$showmap;
                $dataEvent['showremaining']=$eventotherdetails->show_remaining_ticket;
                $dataEvent['eventterm']=$eventotherdetails->term_condition;
                
              }
            
          return response()->json([
                   'eventdetails' => $dataEvent
                   ]);

        }
        else
        {
            $dataEvent = 'notfound';
            return response()->json([
                   'eventdetails' =>$dataEvent,
                   ]);
        }

    }
  
}
