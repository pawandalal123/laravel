<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\admin\AdminController;
use App\Model\Category;
use App\Model\Common;
use App\Model\Event;
use Validator;
use DateTime;
use Appfiles\Repo\TimezoneInterface;

use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\WeightageInterface;
use App\Model\Userdetail;
use App\Model\Timezone;
use Appfiles\Common\Functions;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\UserdetailInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\ViewsCountInterface;
use Appfiles\Repo\BookingdetailsInterface;
use Appfiles\Repo\CronhistoryInterface;
use Appfiles\Repo\ScheduleInterface;
use Appfiles\Repo\PaymenthistoryInterface;
use Appfiles\Repo\OrderbreakageInterface;
use App\Model\Password_resets;
use Appfiles\Repo\SlotInterface;
use Appfiles\Repo\UserassignInterface;
use Appfiles\Repo\AmountpaidInterface;
use Appfiles\Repo\OrderInterface;
use Appfiles\Repo\OrderreminderInterface;
//use App\User;
use App\Model\Eventdetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use Appfiles\Repo\MailsInterface;
use DB;
use Auth;
use URL;
use Moloquent;
use Razorpay\Api\Api;

class apiController extends Controller 
{
   private  $connection;
   public function  __construct(OrderbreakageInterface $orderBreakage,UsersInterface $user ,TicketInterface $ticketInterface,BookingdetailsInterface $bookingDetails,EventInterface $event ,TimezoneInterface $timezone,
    Functions $functions,ViewsCountInterface $viewcount ,WeightageInterface $weightage,
    UserdetailInterface $userdetail,ScheduleInterface $schedule ,WeightageInterface $weightage, SlotInterface $slotlist,CronhistoryInterface $cronhistory,
    PaymenthistoryInterface $paymenthistory,UserassignInterface $userassign,AmountpaidInterface $amountpaid,OrderInterface $order,OrderreminderInterface $orderreminder,AdminController $admincntrl,MailsInterface $mail)
      {
          $this->user=$user;
          $this->order=$order;
          $this->event=$event;
          $this->functions=$functions;
          $this->timezone = $timezone;
          $this->bookingDetails=$bookingDetails;
          $this->viewcount=$viewcount;
          $this->weightage=$weightage;
          $this->userdetail = $userdetail;
          $this->userassign =$userassign;
          $this->ticketInterface = $ticketInterface; 
          $this->schedule = $schedule;
          $this->slotlist = $slotlist;
          $this->cronhistory = $cronhistory;
          $this->orderBreakage = $orderBreakage;
          $this->weightage=$weightage;
          $this->paymenthistory =$paymenthistory;
          $this->amountpaid =$amountpaid;
          $this->orderreminder =$orderreminder;
          $this->admincntrl=$admincntrl;
          $this->mail=$mail;
           
      }
     //function for geting categrylist//
  public function categorylist(Request $request)
  {
    $datacategory = array();
    $allCategory = Category::where('status',1)->get();
        $commonObj =  new Common();
    foreach($allCategory as $allCategory)
    {
            $cleanURL = $commonObj->cleanURL($allCategory->name);
            $datacategory[$cleanURL] =  $allCategory->name;
    }
    return response()->json([
                   'catlist' => $datacategory
                   ]);
  }
  public function keywordajax(Request $request)
  {
    $datacategory = array();
    $commonObj =  new Common();
    
             //get keyword from event table list//
    
    $commonObj =  new Common();
    $getcokkiesCity = $commonObj->getcokkies('usercity');
    $request->location = $getcokkiesCity;
    $getcountry = $this->checklocation($request);
    $country = explode('--',$getcountry.'--');

    $gettimezone = $this->timezone->all();
    foreach($gettimezone as $gettimezone)
    {
      $gettimezoneArray[$gettimezone->id] = $gettimezone->timezone;
    }
     $allCategoryData = DB::select("(select  name as data,id,'2' AS type,display_name as typeurl,'' as city,created_at as time,'1' as zone from `categories` where  name like '%".$request->term."%') 
    union all  (select title as data,id,'1' AS type,url as typeurl,city,start_date_time as time ,timezone_id as zone from `events` where (category like '%".$request->term."%'  or title like '%".$request->term."%' or event_topics like '%".$request->term."%' or tags like '%".$request->term."%') and (country = '".$country[0]."' or recurring_type!=0 ) and status=1 and private=0 and deleted_at='0000-00-00 00:00:00' and (end_date_time>='".date('Y-m-d H:i:s')."' or end_date_time ='0000-00-00 00:00:00'))
 union all  (select  CONCAT_WS(', ',title,venue_name) as data,id,'1' AS type,url as typeurl,city,start_date_time as time,'1' as zone from `events` where venue_name like '%".$request->term."%' and status=1 and private=0  and deleted_at='0000-00-00 00:00:00'  and end_date_time>='".date('Y-m-d H:i:s')."'  group by venue_name ) union all  (select  CONCAT_WS(' - ',id, title) as data,id,'1' AS type,url as typeurl,city,start_date_time as time,'1' as zone from `events` where id like '%".$request->term."%' and status=1 and private=0  and deleted_at='0000-00-00 00:00:00'  and end_date_time>='".date('Y-m-d H:i:s')."'  group by id ) union all  (select  eventtypes as data,id,'2' AS type,url as typeurl,city,start_date_time as time,'1' as zone from `events` where eventtypes like '%".$request->term."%' and status=1 and private=0  and deleted_at='0000-00-00 00:00:00'  and end_date_time>='".date('Y-m-d H:i:s')."' )
      union all (select CONCAT_WS(', ',SUBSTR(t.name,1,50),SUBSTR(e.title,1,50)) as data,e.id as id,'1' AS type,e.url as typeurl,e.city as city,e.start_date_time as time,'1' as zone from `tickets` as t, `events` as e where e.id=t.event_id And t.name like '%".$request->term."%' and t.end_date_time >='".date('Y-m-d H:i:s')."' group by e.id)
     union all  (select  organization_name as data,id,'3' AS type,organization_url as typeurl,'' as city,created_at as time,'1' as zone from `userdetails` where organization_url like '%".$request->term."%' or organization_name like '%".$request->term."%') limit 30 ");    
    foreach($allCategoryData as $allCategoryData)
    {
      $datetimeevent='';
      if($allCategoryData->type ==1)
      {
        $timeZone = $gettimezoneArray[$allCategoryData->zone];
        $datetimeevent = $commonObj->ConvertGMTToLocalTimezone($allCategoryData->time,$timeZone);
        $datetimeevent =date('D, M j Y ,h:i A',strtotime($datetimeevent));
      }
      if(isset($request->requestfrom))
      {
         if(!in_array($allCategoryData->id,config('commondata.Hardcoded_Events')))
         {
          $datacategory[] =  array('label' => $allCategoryData->id,
                                   'url' => $allCategoryData->typeurl,
                                   'type'=>$allCategoryData->type,
                                   'value' => $allCategoryData->data,
                                   'city' => $allCategoryData->city,
                                   'date' => $datetimeevent);

        }
      }
      else
      {
        $datacategory[] =  array('label' => $allCategoryData->id,
                                 'url' => $allCategoryData->typeurl,
                                 'type'=>$allCategoryData->type,
                                 'value' => $allCategoryData->data,
                                 'city' => $allCategoryData->city,
                                 'date' => $datetimeevent);

      }
    }
    return response()->json($datacategory);
  }



   //city list autocomplete function//
    public function citylistajax(Request $request)
    {
        $datacity = array();
        $completeformData = Input::all();
        @extract($completeformData);
        $commonObj =  new Common();
        $allcountry = DB::table('events')->whereRaw("(country like '%".$request->term."%') and status=1 and end_date_time>='".date('Y-m-d H:i:s')."'")->select('country as data')->take(20);
        $allstate = DB::table('events')->whereRaw("(state like '%".$request->term."%') and status=1 and end_date_time>='".date('Y-m-d H:i:s')."'")->select(DB::raw('CONCAT(state, ", ", country) AS data'))->take(20);
        $allcity = DB::table('events')->whereRaw("city like '%".$request->term."%' and status=1 and end_date_time>='".date('Y-m-d H:i:s')."'")->select(DB::raw('CONCAT(city, ", ", country) AS data'))->groupBy('city')->unionAll($allcountry)->unionAll($allstate)->take(20)->get();
        foreach($allcity as $allcity)
        {
            $cleanURL = $commonObj->cleanURL($allcity->data);
            $datacity[$cleanURL] =  ucwords($allcity->data);
        }
        return response()->json($datacity);
    }
    
       //function for geting city in which events are comming//
    public function citylist(request $request)
    {
        $datacity = '';
        if($request->getbylocation)
        {
          $allcity = Event::whereRaw("status = 1 and country like '%".$request->getbylocation."%'")->whereRaw("(city!='' and city is not null and city!='NULL' and end_date_time >= '".date('Y-m-d H:i:s')."' or end_date_time ='0000-00-00 00:00:00') and (DATEDIFF(NOW(),start_date_time)<=20 or recurring_type!=0)")
                             ->select(DB::raw('count(id) as total'),'country','city')
                            ->groupBy('city')
                            ->orderBy('total','desc')
                             ->take(10)->get();

          if(count($allcity)<=0)
          {
            $getcountry = Event::whereRaw("status = 1 and city like '%".$request->getbylocation."%'")
                             ->select('country','city')->first();
                             if($getcountry)
                             {
                                $allcity = Event::whereRaw("status = 1 and country like '%".$getcountry->country."%'")->whereRaw("(city!='' and city is not null and city!='NULL' and end_date_time >= '".date('Y-m-d H:i:s')."' or end_date_time ='0000-00-00 00:00:00') and (DATEDIFF(NOW(),start_date_time)<=20 or recurring_type!=0)")
                               ->select(DB::raw('count(id) as total'),'country','city')
                              ->groupBy('city')
                              ->orderBy('total','desc')
                               ->take(10)->get();
                             }


          }
        }
        elseif($request->getbykeyword)
        {
          $allcity = Event::whereRaw("status = 1 and country like '%india%' and   (category like '%".str_replace('-',' ',$request->getbykeyword)."%' or ( venue_name like '%".str_replace('-',' ',$request->getbykeyword)."%' or title like '%".str_replace('-',' ',$request->getbykeyword)."%'  or eventtypes like '%".str_replace('-',' ',$request->getbykeyword)."%' or  tags like '%".str_replace('-',' ',$request->getbykeyword)."%'  or  event_topics like '%".str_replace('-',' ',$request->getbykeyword)."%'))")->whereRaw("(IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."') and (DATEDIFF(NOW(),start_date_time)<=20 or recurring_type!=0)")
                             ->select(DB::raw('count(id) as total'),'country','city')
                            ->groupBy('city')
                            ->orderBy('total','desc')
                             ->take($request->take)->get();

        }
        else
        {
          $allcity = Event::whereRaw("status = 1 and country like '%india%'")->whereRaw("(city!='' and city is not null and city!='NULL' and end_date_time >= '".date('Y-m-d H:i:s')."' or end_date_time ='0000-00-00 00:00:00') and (DATEDIFF(NOW(),start_date_time)<=20 or recurring_type!=0)")
                             ->select(DB::raw('count(id) as total'),'country','city')
                            ->groupBy('city')
                            ->orderBy('total','desc')
                             ->take(10)->get();
        }

        $commonObj =  new Common();
        foreach($allcity as $allcity)
        {
          switch ($allcity->city) {
            case 'New Delhi':
            $city = 'Delhi';
            break;
             case 'Bangalore':
            $city = 'Bengaluru';
            break;
            default:
             $city = $allcity->city;
              break;
          }
          $cleanURL = $commonObj->cleanURL($city);
          $cleancountry = $commonObj->cleanURL($allcity->country);
          $datacity[ucwords(strtolower($city))] =  $cleancountry;
        }
        if($request->getbykeyword)
        {
          // dd($datacity);
          if(array_key_exists('Goa', $datacity))
          {
            unset($datacity['Goa']);
            $datacity = array('Goa' => 'india') + $datacity;

          }
          // if(array_key_exists('Delhi', $datacity))
          // {
          //    // unset($datacity['Delhi']);
            
          // }
          $datacity = array('Delhi NCR' => 'india') + $datacity;
          
         
       }
        
        // dd($datacity);
        return response()->json([
                   'citylist' => $datacity
                   ]);
    }
         ///////browse pages city and country display///////////
    public function browselist(Request $request)
    {
        $allcity = '';

        if($request->country)
        {
           $reponseContry = str_replace('-',' ',$request->country);
           $allcity = Event::whereRaw("status = 1  and country like '".$reponseContry."'")->whereRaw("(IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."') and city!='' and city is not null and city!='NULL' and (DATEDIFF(NOW(),start_date_time)<=20 or recurring_type!=0)")
                             ->select('country',DB::raw('trim(city) as city'))
                            ->groupBy('city')
                            ->orderBy('city','asc')->get();
          
        }
        else
        {
          $allcity = Event::whereRaw("status = 1 ")->whereRaw("(IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."') and country!='' and country is not null and country!='NULL' and (DATEDIFF(NOW(),start_date_time)<=20 or recurring_type!=0)")
                             ->select(DB::raw('trim(country) as country'))

                            ->groupBy('country')
                            ->orderBy('country','asc')->get();
        }
       if($request->ajax())
       {
         $html='<option>Select City</option>';
          foreach ($allcity as $allcity)
          {
            $html.='<option value='.$allcity->city.'>'.$allcity->city.'</option>';
          }
          echo $html;
       }
       else
       {
        return response()->json(['citylist' => $allcity]);

       }
        
    }

    public function getpendingBooking()
    {
        
        $whereCondition = "order_time >= '".date("Y-m-d H:i:s", strtotime('-56 hours'))."'";
        $data = $this->bookingDetails->getallByRaw($whereCondition);
        $searchArray = array();
        $dataArrayKey = array();
        $finalArray = array();
        $eventArray = array(); 
        $inarray = array();
        $dataevent = array();

        if(count($data)>0){
          foreach($data as $datas){
            if($datas->order_status=="completed"){
               $searchArray[$datas->order_id] = $datas->event_id."_".$datas->name."_".$datas->mobile."_".$datas->email;
               $inarray[]  =  $datas->event_id;
            }
              $eventArray[] = $datas->event_id;
              $dataArrayKey[$datas->order_id] = $datas->event_id."_".$datas->name."_".$datas->mobile."_".$datas->email;
          }

              
         if(count($searchArray)>0){
          $finalArray = array_diff($dataArrayKey,$searchArray);   
          $dataevent =  array_diff($eventArray,$inarray);        
        } else {
          $finalArray = $dataArrayKey;    
          $dataevent = $eventArray;    
        }    

          if(count($finalArray)>0){
            return response()->json(['alldata' => $finalArray,'eventArray'=>implode(',',array_unique($dataevent)),'orderList'=>implode(",",array_keys($finalArray))]);
          } else{
             return response()->json(['alldata' => '']);
          }
              
        }         
        return response()->json(['alldata' => '']);
    }


      //check city or country for making search url//
    public function resumeOrder($id)
    {

       $data = $this->order->getBy(array('id'=>$id));
       if(count($data)>0){
          
        
          $eventData = $this->event->getBy(array('id'=>$data->event_id,'status'=>1),array('id','title','url','end_date_time'));
          $date = date('Y-m-d H:i');

          if((strtotime($eventData->end_date_time) > strtotime($date))){

            //$this->orderBreakage;

           //  $this->ticketInterface->getBy(array(''=>));

          } else {

            Session::flash('message', $eventData->title.' event has been expired.'); 
            Session::flash('alert-class', 'danger'); 
            Session::flash('alert-title', 'Error'); 
            return redirect('error404/');

          }
          //if($eventData->)
            Session::flash('message', 'Something went wrong! The page you are looking for can not be found.'); 
            Session::flash('alert-class', 'danger'); 
            Session::flash('alert-title', 'Error'); 
            return redirect('error404/');
        
       }

    }           
       //check city or country for making search url//
    public function checklocation(Request $request)
    {
        $completeformData = Input::all();
        @extract($completeformData);
        ///first check event city////
        $condition = array('status'=>1,'city'=>$request->location);
        $checkforCity = Event::where($condition)->select('country')->first();
        if($checkforCity)
        {
          $reponse = str_replace(' ','-',$checkforCity->country).'--'.$request->location;
        }
        else
        {
          $condition = array('status'=>1,'country'=>$request->location);
          $checkforCountry = Event::where($condition)->select('country')->first();
          if($checkforCountry)
          {
            $reponse = str_replace(' ','-',$checkforCountry->country);
          }
          else
          {
            $reponse = 'india'.'--'.$request->location;
          }
        }

        if($request->requestfrom=='android')
        {
          return response()->json(['cityname' => $reponse]);
        }

        if(isset($request->dataFormat) && $request->dataFormat=='json')
          return response()->json(['cityname' => $reponse]);

       // dd($reponse.'---- new');
        return $reponse;
    }


    public function closedevent(request $request)
    {
      $alleventsData = DB::select("select events.id as eventid,max(tickets.end_date_time) as ticketend from events inner join tickets on events.id=tickets.event_id where events.status=1 and ticketed=1 and (events.end_date_time >= '".date('Y-m-d H:i:s')."' or events.end_date_time ='0000-00-00 00:00:00') group by events.id");

      if(count($alleventsData)>0)
      {
        foreach($alleventsData as $alleventsData)
        {
           if(strtotime(date('Y-m-d H:i:s'))>strtotime($alleventsData->ticketend))
           {
              $updateEvemts = Event::where(array('id'=>$alleventsData->eventid))->update(array('closed'=>1));
           }
           else
           {
              $updateEvemts = Event::where(array('id'=>$alleventsData->eventid))->update(array('closed'=>0));
           }
        }

      }
      else
      {
        exit();
      }
     
    }
    
    public function resetpwd($token)
    {

       $data =   Password_resets::where(array('token'=>$token))->first(array('email')); 
       if(count($data)>0){
        echo  json_encode(['userdata' => $data]);
       } else{
           echo json_encode([]);

       }

    }
    public function updateticketsoldout(request $request)
    {
      $ticketArray=array();
      $conditionticket = 'status=1 and display_status=1';
      $selectcolumnRawTicket = 'event_id,sum(quantity) as totalqyt,sum(total_sold) as totalsold';
      $ticketdetail = $this->ticketInterface->getByRaw($conditionticket,$selectcolumnRawTicket);
      if(count($ticketdetail)>0)
      {
        foreach ($ticketdetail as $ticketdetail) 
        {
          $ticketArray[$ticketdetail->event_id] =array('totalqyt'=>$ticketdetail->totalqyt,
                                                        'totalsold'=>$ticketdetail->totalsold);
        }
      }

      $conditiondate ="status=1 and private=0 and (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')";
      $selectdateRaw='id,ticketsoldout';
      $liveevents  = $this->event->getrawList($conditiondate ,$selectdateRaw);
      if(count($liveevents)>0)
      {
        foreach($liveevents as $liveevents)
        {
          if(array_key_exists($liveevents->id, $ticketArray))
          {
            if($ticketArray[$liveevents->id]['totalsold']>=$ticketArray[$liveevents->id]['totalqyt'])
            {
              $updatesoldout = $this->event->update(array('ticketsoldout'=>1),$liveevents->id);
              if($updatesoldout)
              {
                echo $liveevents->id.'--soldout';
              }
            }
          }
          else
          {
            echo $liveevents->id.'--notsoldout';
          }
        }
      }
    }

    public function changecokkies(request $request)
    {
      $paginationArray = '?page=1';
      $commonObj =  new Common();
      $getcokkiesCity = $commonObj->setcokkies('usercity',$request->location);
       return 1;
      // $allEventsData=$this->functions->fetchGetData('/api/event/eventlist'.$paginationArray.'&cityname='.$request->location);
      //   //*Event list*//
      //   //$eventlist = file_get_contents($this->APIURL.'/event/eventlist');
      // print_r($allEventsData);
      // die;
      // $eventlistData=$allEventsData->eventlist;
      // if(count($eventlistData)>0)
      // {
      //     $getcokkiesCity = $commonObj->setcokkies('usercity',$request->location); 
      //    //$getcokkiesCity = $this->functions->setuserciycokkies();
      // }
      // return 1;
    }
 
    
    public function eventpopular (Request $request)
    {
      ini_set('max_execution_time', 0);   
      $this->connection=DB::connection('mongodbevent');
      $amountWeight=0;
      $quantityWeight=0;
      $totalWeight = 0;
      $viewWeight = 0;
      $dateWeight = 0;
                      /////////get eventorder weight////////////////
      $EventList = array();
      $condition ="order_status='completed' and order_time between  DATE_sub(curdate(),INTERVAL 10 DAY) and DATE_add(curdate(),INTERVAL 1 DAY)";
      $selectRaw='event_id,sum(total_amount) as amount,sum(total_quantity) as quantity';
      $getevent =  $this->bookingDetails->getrawList($condition,$selectRaw);
      if(count($getevent)>0)
      {
        foreach ($getevent as $getevent)
        {
          $amountWeight = $getevent->amount;
          $quantityWeight = $getevent->quantity;
          $EventList[$getevent->event_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
        }

      }
      
                      ////get view count weight////
      $conditionView ="type='event' and created_at between  DATE_sub(curdate(),INTERVAL 10 DAY) and curdate() ";
      $selectViewRaw='view_id,count(id) as viewcount';
      $getview  = $this->viewcount->getrawList($conditionView ,$selectViewRaw);
      if(count($getview)>0)
      {
        foreach($getview as $getview)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = $getview->viewcount;
          if(array_key_exists($getview->view_id, $EventList))
          {
             $EventList[$getview->view_id]['view']=$viewWeight;
          }
          else
          {
            $EventList[$getview->view_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
          }
        }

      }
     // dd($EventList);
                            ////////ticketlist Array/////////
      $ticketArray = array();
      $conditionticket = 'status=1 and display_status=1';
      $selectcolumnRawTicket = 'event_id,max(price) as maxprice,min(price) as minprice';
      $ticketdetail = $this->ticketInterface->getByRaw($conditionticket,$selectcolumnRawTicket);
      if(count($ticketdetail)>0)
      {
        foreach ($ticketdetail as $ticketdetail) 
        {
          $ticketArray[$ticketdetail->event_id] =array('maxprice'=>$ticketdetail->maxprice,
                                                        'minprice'=>$ticketdetail->minprice);
        }

      }
                      ///////// ////get date weight/////////////////
      $conditiondate ="(status=1 and private=0 and recurring_type!=3 and (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')) or (TIMESTAMPDIFF(HOUR, updated_at,now())<=3)";
      $selectdateRaw='id,city,status,country,featured_city,featured_country,no_dates,recurring_type,ticketsoldout,closed,popularity,user_id,banner_image,title,ticketed,venue_name,timezone_value,tags,eventtypes,category,start_date_time,(IF(end_date_time = "0000-00-00 00:00:00", `start_date_time`, `end_date_time`)) as enddate,state,timezone_id,url,event_mode,event_topics,address1';
      $geteventdate  = $this->event->getrawList($conditiondate ,$selectdateRaw);
      $eventIdArray = array();
      if(count($geteventdate)>0)
      {
        foreach($geteventdate as $geteventdate)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = 0;
          $eventIdArray[] = $geteventdate->id;
          $start_date_time = $geteventdate->start_date_time;
          $enddate = $geteventdate->enddate;
          if(array_key_exists($geteventdate->id, $EventList))
          {

            $EventList[$geteventdate->id]['city']=$geteventdate->city;
            $EventList[$geteventdate->id]['status']=$geteventdate->status;
            // $EventList[$geteventdate->id]['paused']=$geteventdate->paused;
            $EventList[$geteventdate->id]['state']=$geteventdate->state;

            $EventList[$geteventdate->id]['featured_city']=$geteventdate->featured_city;
            $EventList[$geteventdate->id]['featured_country']=$geteventdate->featured_country;
            $EventList[$geteventdate->id]['address1']=$geteventdate->address1;

            $EventList[$geteventdate->id]['country']=$geteventdate->country;
            $EventList[$geteventdate->id]['start_date']=$start_date_time;
            $EventList[$geteventdate->id]['end_date']=$enddate;
            $EventList[$geteventdate->id]['no_dates']=$geteventdate->no_dates;
            $EventList[$geteventdate->id]['ticketsoldout']=$geteventdate->ticketsoldout;
            $EventList[$geteventdate->id]['closed']=$geteventdate->closed;
            $EventList[$geteventdate->id]['popularity']=$geteventdate->popularity;
            $EventList[$geteventdate->id]['title']=$geteventdate->title;
            $EventList[$geteventdate->id]['category']=strtolower($geteventdate->category);
            $EventList[$geteventdate->id]['eventtypes']=$geteventdate->eventtypes;
            $EventList[$geteventdate->id]['venue_name']=$geteventdate->venue_name;
            $EventList[$geteventdate->id]['timezone_value']=$geteventdate->timezone_value;
            $EventList[$geteventdate->id]['event_topics']=$geteventdate->event_topics;
            $EventList[$geteventdate->id]['tags']=$geteventdate->tags;
            $EventList[$geteventdate->id]['user_id']=$geteventdate->user_id;
            $EventList[$geteventdate->id]['event_mode']=$geteventdate->event_mode;
            $EventList[$geteventdate->id]['recurring_type']=$geteventdate->recurring_type;
            $EventList[$geteventdate->id]['banner_image']=$geteventdate->banner_image;
            $EventList[$geteventdate->id]['timezone_id']=$geteventdate->timezone_id;
            $EventList[$geteventdate->id]['ticketed']=$geteventdate->ticketed;
            $EventList[$geteventdate->id]['url']=$geteventdate->url;
            $EventList[$geteventdate->id]['date']=1;
            $EventList[$geteventdate->id]['maxticketprice'] =0;
            $EventList[$geteventdate->id]['minticketprice'] =0;
            if(array_key_exists($geteventdate->id, $ticketArray))
            {
               $EventList[$geteventdate->id]['maxticketprice'] =intval($ticketArray[$geteventdate->id]['maxprice']) ;
               $EventList[$geteventdate->id]['minticketprice'] =intval($ticketArray[$geteventdate->id]['minprice']) ;
            }
          }
          else
          {
           
            $EventList[$geteventdate->id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>1);
            $EventList[$geteventdate->id]['city']=$geteventdate->city;
            $EventList[$geteventdate->id]['state']=$geteventdate->state;
            $EventList[$geteventdate->id]['status']=$geteventdate->status;

            $EventList[$geteventdate->id]['featured_city']=$geteventdate->featured_city;
            $EventList[$geteventdate->id]['featured_country']=$geteventdate->featured_country;
             $EventList[$geteventdate->id]['address1']=$geteventdate->address1;


            // $EventList[$geteventdate->id]['paused']=$geteventdate->paused;
            $EventList[$geteventdate->id]['country']=$geteventdate->country;
            $EventList[$geteventdate->id]['start_date']=$start_date_time;
            $EventList[$geteventdate->id]['end_date']=$enddate;
            $EventList[$geteventdate->id]['no_dates']=$geteventdate->no_dates;
            $EventList[$geteventdate->id]['ticketsoldout']=$geteventdate->ticketsoldout;
            $EventList[$geteventdate->id]['closed']=$geteventdate->closed;
            $EventList[$geteventdate->id]['popularity']=$geteventdate->popularity;
            $EventList[$geteventdate->id]['recurring_type']=$geteventdate->recurring_type;
            $EventList[$geteventdate->id]['title']=$geteventdate->title;
            $EventList[$geteventdate->id]['category']=strtolower($geteventdate->category);
            $EventList[$geteventdate->id]['eventtypes']=$geteventdate->eventtypes;
            $EventList[$geteventdate->id]['venue_name']=$geteventdate->venue_name;
            $EventList[$geteventdate->id]['timezone_value']=$geteventdate->timezone_value;
            $EventList[$geteventdate->id]['event_topics']=$geteventdate->event_topics;
            $EventList[$geteventdate->id]['tags']=$geteventdate->tags;
            $EventList[$geteventdate->id]['user_id']=$geteventdate->user_id;
            $EventList[$geteventdate->id]['event_mode']=$geteventdate->event_mode;
            $EventList[$geteventdate->id]['banner_image']=$geteventdate->banner_image;
            $EventList[$geteventdate->id]['timezone_id']=$geteventdate->timezone_id;
            $EventList[$geteventdate->id]['ticketed']=$geteventdate->ticketed;
            $EventList[$geteventdate->id]['url']=$geteventdate->url;
            $EventList[$geteventdate->id]['maxticketprice'] =0;
            $EventList[$geteventdate->id]['minticketprice'] =0;
            if(array_key_exists($geteventdate->id, $ticketArray))
            {
               $EventList[$geteventdate->id]['maxticketprice'] =intval($ticketArray[$geteventdate->id]['maxprice']) ;
               $EventList[$geteventdate->id]['minticketprice'] =intval($ticketArray[$geteventdate->id]['minprice']) ;
            }
          }
        }

        $checkEvent=$this->weightage->getListallIn($eventIdArray,'id','event_id');
        // dd($checkEvent);
        // $eventidnotUpadte = array('7268','8897','10792','5026','13096','12387');
        $eventidnotUpadte = array('7268','8897','10792','5026','13096','12387','33496','33501');
        foreach ($EventList as $key=>$values) 
        {
          $stausUpdate = 0;
          if($values['date']==1)
          {
            $stausUpdate = 1;
            $dataArray = array('amount'=>$values['amount'],
                                  'quantity'=>$values['quantity'],
                                  'view'=>$values['view'],
                                  'date'=>$values['date'],
                                  'status'=>$values['status'],
                                  'featured_city'=>$values['featured_city'],
                                  'featured_country'=>$values['featured_country'],
                                   'address1'=>$values['address1'],
                                  // 'paused'=>$values['paused'],
                                  'city'=>$values['city'],
                                  'state'=>$values['state'],
                                  'country'=>$values['country'],
                                  'start_date_time'=>$values['start_date'],
                                  'end_date_time'=>$values['end_date'],
                                  'no_dates'=>$values['no_dates'],
                                  'ticketsoldout'=>$values['ticketsoldout'],
                                  'recurring_type'=>$values['recurring_type'],
                                  'closed'=>$values['closed'],
                                  'popularity'=>$values['popularity'],
                                  'event_topics'=>$values['event_topics'],
                                  'user_id'=>$values['user_id'],
                                  'title'=>$values['title'],
                                  'ticketed'=>$values['ticketed'],
                                  'venue_name'=>$values['venue_name'],
                                  'timezone_value'=>$values['timezone_value'],
                                  'category'=>$values['category'],
                                  'eventtypes'=>$values['eventtypes'],
                                  'tags'=>$values['tags'],
                                  'event_mode'=>$values['event_mode'],
                                  'banner_image'=>$values['banner_image'],
                                  'timezone_id'=>$values['timezone_id'],
                                  'url'=>$values['url'],
                                  'min_ticket_price'=>$values['minticketprice'],
                                  'max_ticket_price'=>$values['maxticketprice']);
            $totalWeightage = $values['amount']*1+$values['quantity']*0.1+$values['view']*0.01+$values['date']*0.001;
            // $checkEvent=$this->weightage->findBy('event_id',$key);
            if(array_key_exists($key, $checkEvent))
            {
              $condition = array('event_id'=>$key);
              if(!in_array($key, $eventidnotUpadte))
              {
                $dataArray['total_weightage']=$totalWeightage;
              }
              $updateWeight = $this->weightage->update($dataArray,$condition);
              echo $key.'--Updated';
            }
            else
            {
              $dataArray['event_id']=$key;
              if(!in_array($key, $eventidnotUpadte))
              {
                $dataArray['total_weightage']=$totalWeightage;
              }
              $InsertWeight = $this->weightage->create($dataArray);
               echo $key.'--inserted';
            }
          }
          
        }
      }
    }
    
    public function mutivenueeventpopular (Request $request)
    {
      ini_set('max_execution_time', 0);   
      $this->connection=DB::connection('mongodbevent');
      $amountWeight=0;
      $quantityWeight=0;
      $totalWeight = 0;
      $viewWeight = 0;
      $dateWeight = 0;
                      /////////get eventorder weight////////////////
      $EventList = array();
      $condition ="order_status='completed' and order_time between  DATE_sub(curdate(),INTERVAL 10 DAY) and DATE_add(curdate(),INTERVAL 1 DAY)";
      $selectRaw='event_id,sum(total_amount) as amount,sum(total_quantity) as quantity';
      $getevent =  $this->bookingDetails->getrawList($condition,$selectRaw);
      if(count($getevent)>0)
      {
        foreach ($getevent as $getevent)
        {
          $amountWeight = $getevent->amount;
          $quantityWeight = $getevent->quantity;
          $EventList[$getevent->event_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
        }

      }

      
                      ////get view count weight////
      $conditionView ="type='event' and created_at between  DATE_sub(curdate(),INTERVAL 10 DAY) and curdate() ";
      $selectViewRaw='view_id,count(id) as viewcount';
      $getview  = $this->viewcount->getrawList($conditionView ,$selectViewRaw);
      if(count($getview)>0)
      {
        foreach($getview as $getview)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = $getview->viewcount;
          if(array_key_exists($getview->view_id, $EventList))
          {
             $EventList[$getview->view_id]['view']=$viewWeight;
          }
          else
          {
            $EventList[$getview->view_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
          }
        }

      }
     // dd($EventList);
                            ////////ticketlist Array/////////
      $ticketArray = array();
      $conditionticket = 'status=1 and display_status=1';
      $selectcolumnRawTicket = 'event_id,max(price) as maxprice,min(price) as minprice';
      $ticketdetail = $this->ticketInterface->getByRaw($conditionticket,$selectcolumnRawTicket);
      if(count($ticketdetail)>0)
      {
        foreach ($ticketdetail as $ticketdetail) 
        {
          $ticketArray[$ticketdetail->event_id] =array('maxprice'=>$ticketdetail->maxprice,
                                                       'minprice'=>$ticketdetail->minprice);
        }

      }
                      ///////// ////get date weight/////////////////
      $conditiondate ="(status=1 and private=0 and recurring_type=3 and  (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')) or (TIMESTAMPDIFF(HOUR, updated_at,now())<=3)";
      $selectdateRaw='id,city,country,status,featured_city,featured_country,no_dates,ticketsoldout,closed,popularity,user_id,recurring_type,banner_image,title,venue_name,timezone_value,tags,eventtypes,category,start_date_time,(IF(end_date_time = "0000-00-00 00:00:00", `start_date_time`, `end_date_time`)) as enddate,state,timezone_id,url,event_mode,ticketed,event_topics';
      $geteventdate  = $this->event->getrawList($conditiondate ,$selectdateRaw);
      if(count($geteventdate)>0)
      {
        foreach($geteventdate as $geteventdate)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = 0;
          if(array_key_exists($geteventdate->id, $EventList))
          {
            $EventList[$geteventdate->id]['no_dates']=$geteventdate->no_dates;
            $EventList[$geteventdate->id]['ticketed']=$geteventdate->ticketed;
            $EventList[$geteventdate->id]['ticketsoldout']=$geteventdate->ticketsoldout;
            $EventList[$geteventdate->id]['closed']=$geteventdate->closed;
            $EventList[$geteventdate->id]['popularity']=$geteventdate->popularity;
            $EventList[$geteventdate->id]['title']=$geteventdate->title;
            $EventList[$geteventdate->id]['category']=strtolower($geteventdate->category);
            $EventList[$geteventdate->id]['status']=$geteventdate->status;

            $EventList[$geteventdate->id]['featured_city']=$geteventdate->featured_city;
            $EventList[$geteventdate->id]['featured_country']=$geteventdate->featured_country;
            $EventList[$geteventdate->id]['event_topics']=$geteventdate->event_topics;

            // $EventList[$geteventdate->id]['paused']=$geteventdate->paused;
            $EventList[$geteventdate->id]['eventtypes']=$geteventdate->eventtypes;
            $EventList[$geteventdate->id]['recurring_type']=$geteventdate->recurring_type;
            $EventList[$geteventdate->id]['timezone_value']=$geteventdate->timezone_value;
            $EventList[$geteventdate->id]['tags']=$geteventdate->tags;
            $EventList[$geteventdate->id]['user_id']=$geteventdate->user_id;
            $EventList[$geteventdate->id]['event_mode']=$geteventdate->event_mode;
            $EventList[$geteventdate->id]['banner_image']=$geteventdate->banner_image;
            $EventList[$geteventdate->id]['timezone_id']=$geteventdate->timezone_id;
            $EventList[$geteventdate->id]['url']=$geteventdate->url;
            $EventList[$geteventdate->id]['date']=1;
          }
          else
          {
           
            $EventList[$geteventdate->id] = array('amount'=>$amountWeight,
                                                  'quantity'=>$quantityWeight,
                                                  'view'=>$viewWeight,
                                                  'date'=>1,
                                                  'ticketed'=>$geteventdate->ticketed,
                                                  'status'=>$geteventdate->status,
                                                  // 'paused'=>$geteventdate->paused,
                                                  'no_dates'=>$geteventdate->no_dates,
                                                  'recurring_type'=>$geteventdate->recurring_type,
                                                  'ticketsoldout'=>$geteventdate->ticketsoldout, 

                                                   'featured_city'=>$geteventdate->featured_city,
                                                   'event_topics'=>$geteventdate->event_topics,
                                                  'featured_country'=>$geteventdate->featured_country,
                                                  'closed'=>$geteventdate->closed,
                                                  'popularity'=>$geteventdate->popularity,
                                                  'title'=>$geteventdate->title,
                                                  'category'=>strtolower($geteventdate->category),
                                                  'eventtypes'=>$geteventdate->eventtypes,
                                                  'venue_name'=>$geteventdate->venue_name,
                                                  'timezone_value'=>$geteventdate->timezone_value,
                                                  'tags'=>$geteventdate->tags,
                                                  'user_id'=>$geteventdate->user_id,
                                                  'event_mode'=>$geteventdate->event_mode,
                                                  'banner_image'=>$geteventdate->banner_image,
                                                  'timezone_id'=>$geteventdate->timezone_id,
                                                  'url'=>$geteventdate->url);
          
          }
          
        }
      
       //////////////get all slots///
        $slotArray = array();
        $conditionticket = 'status=1';
        $selectcolumnRawTicket = 'schedule_id,min(start_date_time) as startdate,max(end_date_time) as enddate';
        $slotdetails = $this->slotlist->getRawselect($conditionticket,$selectcolumnRawTicket);
        if(count($slotdetails)>0)
        {
          foreach ($slotdetails as $slotdetails) 
          {
            $slotArray[$slotdetails->schedule_id] =array('startdate'=>$slotdetails->startdate,
                                                         'enddate'=>$slotdetails->enddate);
          }

        }

               ///////////get all shedule////////

        $sheduleEvents = array();
        $sheduleList = $this->schedule->getallBy(array('status'=>1));
        if(count($sheduleList)>0)
        {
          foreach ($sheduleList as $sheduleList) 
          {  
            
            if(array_key_exists($sheduleList->id, $slotArray) && array_key_exists($sheduleList->event_id, $EventList) && array_key_exists($sheduleList->event_id, $ticketArray))
            {
              $start_date_time = $slotArray[$sheduleList->id]['startdate'];
              $enddate = $slotArray[$sheduleList->id]['enddate'];
              $sheduleEvents[$sheduleList->id] = array('city'=>$sheduleList->city,
                                                       'state'=>$sheduleList->state,
                                                       'country'=>$sheduleList->country,
                                                       'venue_name'=>$sheduleList->venue_name,
                                                       'event_id'=>$sheduleList->event_id);
              $sheduleEvents[$sheduleList->id]['start_date'] = $start_date_time;
              $sheduleEvents[$sheduleList->id]['end_date'] = $enddate;
              $sheduleEvents[$sheduleList->id]['eventdetails'] = $EventList[$sheduleList->event_id];
              $sheduleEvents[$sheduleList->id]['maxticketprice'] =intval($ticketArray[$sheduleList->event_id]['maxprice']) ;
              $sheduleEvents[$sheduleList->id]['minticketprice'] =intval($ticketArray[$sheduleList->event_id]['minprice']) ;
              

            }
            
          }
        }
        // $eventidnotUpadte = array('7268','8897','10792','5026','13096','12387');
        $eventidnotUpadte = array('7268','8897','10792','5026','13096','12387');
        foreach ($sheduleEvents as $key=>$values) 
        {
          if($values['eventdetails']['date']==1)
          {
            $stausUpdate = 1;
            $dataArray = array('amount'=>$values['eventdetails']['amount'],
                                  'quantity'=>$values['eventdetails']['quantity'],
                                  'view'=>$values['eventdetails']['view'],
                                  'date'=>$values['eventdetails']['date'],
                                  'status'=>$values['eventdetails']['status'],
                                  // '=>$values['eventdetails']['],
                                  'city'=>$values['city'],
                                  'state'=>$values['state'],
                                  'country'=>$values['country'],
                                  'start_date_time'=>$values['start_date'],
                                  'end_date_time'=>$values['end_date'],
                                  'event_topics'=>@$values['event_topics'],
                                  'no_dates'=>0,
                                  'recurring_type'=>$values['eventdetails']['recurring_type'],
                                  'ticketsoldout'=>$values['eventdetails']['ticketsoldout'],
                                  'closed'=>$values['eventdetails']['closed'],
                                  'popularity'=>$values['eventdetails']['popularity'],
                                  'user_id'=>$values['eventdetails']['user_id'],
                                  'title'=>$values['eventdetails']['title'],
                                  'ticketed'=>$values['eventdetails']['ticketed'],
                                  'venue_name'=>$values['venue_name'],
                                  'timezone_value'=>$values['eventdetails']['timezone_value'],
                                  'category'=>$values['eventdetails']['category'],
                                  'eventtypes'=>$values['eventdetails']['eventtypes'],
                                  'tags'=>$values['eventdetails']['tags'],
                                  'event_mode'=>$values['eventdetails']['event_mode'],
                                  'banner_image'=>$values['eventdetails']['banner_image'],
                                  'timezone_id'=>$values['eventdetails']['timezone_id'],
                                  'url'=>$values['eventdetails']['url'],
                                  'min_ticket_price'=>$values['minticketprice'],
                                  'max_ticket_price'=>$values['maxticketprice']);
            $checkEvent= $this->weightage->getBy(array('event_id'=>$values['event_id'],'shedule_id'=>$key));
            $totalWeightage = $values['eventdetails']['amount']*1+$values['eventdetails']['quantity']*0.1+$values['eventdetails']['view']*0.01+$values['eventdetails']['date']*0.001;
            if($checkEvent)
            {
              $condition = array('event_id'=>$values['event_id'],'shedule_id'=>$key);
              if(!in_array($values['event_id'], $eventidnotUpadte))
              {
                $dataArray['total_weightage']=$totalWeightage;
              }
              $updateWeight = $this->weightage->update($dataArray,$condition);
              echo $key.'--Updated';
            }
            else
            {
              $dataArray['event_id'] = $values['event_id'];
              $dataArray['shedule_id']=$key;
              if(!in_array($values['event_id'], $eventidnotUpadte))
              {
                $dataArray['total_weightage']=$totalWeightage;
              }
              $InsertWeight =  $this->weightage->create($dataArray);
               echo $key.'--inserted';
            }
          }
        }
      }
    }

    public function eventpopularmongo (Request $request)
    {
      ini_set('max_execution_time', 0);   
      $this->connection=DB::connection('mongodbevent');
      $amountWeight=0;
      $quantityWeight=0;
      $totalWeight = 0;
      $viewWeight = 0;
      $dateWeight = 0;
                      /////////get eventorder weight////////////////
      $EventList = array();
      $condition ="order_status='completed' and order_time between  DATE_sub(curdate(),INTERVAL 10 DAY) and DATE_add(curdate(),INTERVAL 1 DAY) ";
      $selectRaw='event_id,sum(total_amount) as amount,sum(total_quantity) as quantity';
      $getevent =  $this->bookingDetails->getrawList($condition,$selectRaw);
      if(count($getevent)>0)
      {
        foreach ($getevent as $getevent)
        {
          $amountWeight = $getevent->amount;
          $quantityWeight = $getevent->quantity;
          $EventList[$getevent->event_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
        }

      }
      
                      ////get view count weight////
      $conditionView ="type='event' and created_at between  DATE_sub(curdate(),INTERVAL 10 DAY) and curdate() ";
      $selectViewRaw='view_id,count(id) as viewcount';
      $getview  = $this->viewcount->getrawList($conditionView ,$selectViewRaw);
      if(count($getview)>0)
      {
        foreach($getview as $getview)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = $getview->viewcount;
          if(array_key_exists($getview->view_id, $EventList))
          {
             $EventList[$getview->view_id]['view']=$viewWeight;
          }
          else
          {
            $EventList[$getview->view_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
          }
        }

      }
     // dd($EventList);
                            ////////ticketlist Array/////////
      $ticketArray = array();
      $conditionticket = 'status=1 and display_status=1';
      $selectcolumnRawTicket = 'event_id,max(price) as maxprice,min(price) as minprice';
      $ticketdetail = $this->ticketInterface->getByRaw($conditionticket,$selectcolumnRawTicket);
      if(count($ticketdetail)>0)
      {
        foreach ($ticketdetail as $ticketdetail) 
        {
          $ticketArray[$ticketdetail->event_id] =array('maxprice'=>$ticketdetail->maxprice,
                                                        'minprice'=>$ticketdetail->minprice);
        }

      }
                      ///////// ////get date weight/////////////////
      $conditiondate ="status=1 and recurring_type!=3 and (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')";
      $selectdateRaw='id,city,country,no_dates,ticketsoldout,recurring_type,private,closed,ticketed,popularity,user_id,banner_image,title,venue_name,timezone_value,tags,eventtypes,category,start_date_time,(IF(end_date_time = "0000-00-00 00:00:00", `start_date_time`, `end_date_time`)) as enddate,state,timezone_id,url,event_mode';
      $geteventdate  = $this->event->getrawList($conditiondate ,$selectdateRaw);
      if(count($geteventdate)>0)
      {
        foreach($geteventdate as $geteventdate)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = 0;
          $start_date_time = new \MongoDate(strtotime($geteventdate->start_date_time));
          $enddate = new \MongoDate(strtotime($geteventdate->enddate));
          if(array_key_exists($geteventdate->id, $EventList))
          {

            $EventList[$geteventdate->id]['city']=$geteventdate->city;
            $EventList[$geteventdate->id]['state']=$geteventdate->state;
            $EventList[$geteventdate->id]['country']=$geteventdate->country;
            $EventList[$geteventdate->id]['start_date']=$start_date_time;
            $EventList[$geteventdate->id]['end_date']=$enddate;
            $EventList[$geteventdate->id]['no_dates']=$geteventdate->no_dates;
            $EventList[$geteventdate->id]['ticketsoldout']=$geteventdate->ticketsoldout;
            $EventList[$geteventdate->id]['closed']=$geteventdate->closed;
            $EventList[$geteventdate->id]['popularity']=$geteventdate->popularity;
            $EventList[$geteventdate->id]['title']=$geteventdate->title;
            $EventList[$geteventdate->id]['category']=strtolower($geteventdate->category);
            $EventList[$geteventdate->id]['eventtypes']=$geteventdate->eventtypes;
            $EventList[$geteventdate->id]['venue_name']=$geteventdate->venue_name;
            $EventList[$geteventdate->id]['timezone_value']=$geteventdate->timezone_value;
            $EventList[$geteventdate->id]['tags']=$geteventdate->tags;
            $EventList[$geteventdate->id]['recurring_type']=$geteventdate->recurring_type;
            $EventList[$geteventdate->id]['user_id']=$geteventdate->user_id;
            $EventList[$geteventdate->id]['event_mode']=$geteventdate->event_mode;
            $EventList[$geteventdate->id]['ticketed']=$geteventdate->ticketed;
            $EventList[$geteventdate->id]['private']=$geteventdate->private;
            $EventList[$geteventdate->id]['banner_image']=$geteventdate->banner_image;
            $EventList[$geteventdate->id]['timezone_id']=$geteventdate->timezone_id;
            $EventList[$geteventdate->id]['url']=$geteventdate->url;
            $EventList[$geteventdate->id]['date']=1;
            $EventList[$geteventdate->id]['maxticketprice'] =0;
            $EventList[$geteventdate->id]['minticketprice'] =0;
            if(array_key_exists($geteventdate->id, $ticketArray))
            {
               $EventList[$geteventdate->id]['maxticketprice'] =intval($ticketArray[$geteventdate->id]['maxprice']) ;
               $EventList[$geteventdate->id]['minticketprice'] =intval($ticketArray[$geteventdate->id]['minprice']) ;
            }
          }
          else
          {
           
            $EventList[$geteventdate->id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>1);
            $EventList[$geteventdate->id]['city']=$geteventdate->city;
            $EventList[$geteventdate->id]['state']=$geteventdate->state;
            $EventList[$geteventdate->id]['country']=$geteventdate->country;
            $EventList[$geteventdate->id]['start_date']=$start_date_time;
            $EventList[$geteventdate->id]['end_date']=$enddate;
            $EventList[$geteventdate->id]['private']=$geteventdate->private;
            $EventList[$geteventdate->id]['recurring_type']=$geteventdate->recurring_type;
            $EventList[$geteventdate->id]['no_dates']=$geteventdate->no_dates;
            $EventList[$geteventdate->id]['ticketsoldout']=$geteventdate->ticketsoldout;
            $EventList[$geteventdate->id]['closed']=$geteventdate->closed;
            $EventList[$geteventdate->id]['popularity']=$geteventdate->popularity;
            $EventList[$geteventdate->id]['ticketed']=$geteventdate->ticketed;
            $EventList[$geteventdate->id]['title']=$geteventdate->title;
            $EventList[$geteventdate->id]['category']=strtolower($geteventdate->category);
            $EventList[$geteventdate->id]['eventtypes']=$geteventdate->eventtypes;
            $EventList[$geteventdate->id]['venue_name']=$geteventdate->venue_name;
            $EventList[$geteventdate->id]['timezone_value']=$geteventdate->timezone_value;
            $EventList[$geteventdate->id]['tags']=$geteventdate->tags;
            $EventList[$geteventdate->id]['user_id']=$geteventdate->user_id;
            $EventList[$geteventdate->id]['event_mode']=$geteventdate->event_mode;
            $EventList[$geteventdate->id]['banner_image']=$geteventdate->banner_image;
            $EventList[$geteventdate->id]['timezone_id']=$geteventdate->timezone_id;
            $EventList[$geteventdate->id]['url']=$geteventdate->url;
            $EventList[$geteventdate->id]['maxticketprice'] =0;
            $EventList[$geteventdate->id]['minticketprice'] =0;
            if(array_key_exists($geteventdate->id, $ticketArray))
            {
               $EventList[$geteventdate->id]['maxticketprice'] =intval($ticketArray[$geteventdate->id]['maxprice']) ;
               $EventList[$geteventdate->id]['minticketprice'] =intval($ticketArray[$geteventdate->id]['minprice']) ;
            }
          }
        }
      }
      //dd($EventList);
      foreach ($EventList as $key=>$values) 
      {
        //$checkEvent = $this->weightage->findBy('event_id',$key);
        $stausUpdate = 0;
        if($values['date']==1)
        {
          $stausUpdate = 1;
          $checkEvent=$this->connection->collection('eventlisting')->where(array('event_id'=>$key))->first();
          if($checkEvent)
          {
            $condition = array('event_id'=>$key);
            $totalWeightage = $values['amount']*1+$values['quantity']*0.1+$values['view']*0.01+$values['date']*0.001;
            $updateData = array('amount'=>$values['amount'],
                                'quantity'=>$values['quantity'],
                                'view'=>$values['view'],
                                'date'=>$values['date'],
                                'total_weightage'=>$totalWeightage,
                                'status'=>$stausUpdate,
                                'private'=>$values['private'],
                                'recurring_type'=>$values['recurring_type'],
                                'city'=>$values['city'],
                                'state'=>$values['state'],
                                'country'=>$values['country'],
                                'start_date_time'=>$values['start_date'],
                                'end_date_time'=>$values['end_date'],
                                'no_dates'=>$values['no_dates'],
                                'ticketsoldout'=>$values['ticketsoldout'],
                                'closed'=>$values['closed'],
                                'popularity'=>$values['popularity'],
                                'user_id'=>$values['user_id'],
                                'title'=>$values['title'],
                                'venue_name'=>$values['venue_name'],
                                'timezone_value'=>$values['timezone_value'],
                                'category'=>$values['category'],
                                'eventtypes'=>$values['eventtypes'],
                                'ticketed'=>$values['ticketed'],
                                'tags'=>$values['tags'],
                                'event_mode'=>$values['event_mode'],
                                'banner_image'=>$values['banner_image'],
                                'timezone_id'=>$values['timezone_id'],
                                'url'=>$values['url'],
                                'min_ticket_price'=>$values['minticketprice'],
                                'max_ticket_price'=>$values['maxticketprice']);
            $updateWeight = $this->connection->collection('eventlisting')->where($condition)->update($updateData);
            echo $key.'--Updated';
          }
          else
          {
            $totalWeightage = $values['amount']*1+$values['quantity']*0.1+$values['view']*0.01+$values['date']*0.001;
            $InsertData = array('event_id'=>$key,
                                'amount'=>$values['amount'],
                                'quantity'=>$values['quantity'],
                                'view'=>$values['view'],
                                'date'=>$values['date'],
                                'total_weightage'=>$totalWeightage,
                                'status'=>$stausUpdate,
                                'city'=>$values['city'],
                                'state'=>$values['state'],
                                'country'=>$values['country'],
                                'recurring_type'=>$values['recurring_type'],
                                'start_date_time'=>$values['start_date'],
                                'end_date_time'=>$values['end_date'],
                                'no_dates'=>$values['no_dates'],
                                'ticketsoldout'=>$values['ticketsoldout'],
                                'closed'=>$values['closed'],
                                'popularity'=>$values['popularity'],
                                'user_id'=>$values['user_id'],
                                'title'=>$values['title'],
                                'venue_name'=>$values['venue_name'],
                                'timezone_value'=>$values['timezone_value'],
                                'ticketed'=>$values['ticketed'],
                                'category'=>$values['category'],
                                'eventtypes'=>$values['eventtypes'],
                                'tags'=>$values['tags'],
                                'private'=>$values['private'],
                                'event_mode'=>$values['event_mode'],
                                'banner_image'=>$values['banner_image'],
                                'timezone_id'=>$values['timezone_id'],
                                'url'=>$values['url'],
                                'min_ticket_price'=>$values['minticketprice'],
                                'max_ticket_price'=>$values['maxticketprice']);
            $InsertWeight = $this->connection->collection('eventlisting')->insert($InsertData);
             echo $key.'--inserted';
          }
        }
        // else
        // {
        //   $checkEvent=$this->connection->collection('eventlisting')->where(array('event_id'=>$key))->first();
        //   if($checkEvent)
        //   {
        //     $condition = array('event_id'=>$key);
        //     $totalWeightage = $values['amount']*1+$values['quantity']*0.1+$values['view']*0.01+$values['date']*0.001;
        //     $updateData = array('amount'=>$values['amount'],
        //                         'quantity'=>$values['quantity'],
        //                         'view'=>$values['view'],
        //                         'date'=>$values['date'],
        //                         'total_weightage'=>$totalWeightage,
        //                         'status'=>0);
        //     $updateWeight = $this->connection->collection('eventlisting')->where($condition)->update($updateData);
        //      echo $key.'--status is changed';
        //   }

        // }
        # code...
      }
    }

    public function mutivenueeventmongo (Request $request)
    {
      ini_set('max_execution_time', 0);   
      $this->connection=DB::connection('mongodbevent');
      $amountWeight=0;
      $quantityWeight=0;
      $totalWeight = 0;
      $viewWeight = 0;
      $dateWeight = 0;
                      /////////get eventorder weight////////////////
      $EventList = array();
      $condition ="order_status='completed' and order_time between  DATE_sub(curdate(),INTERVAL 10 DAY) and DATE_add(curdate(),INTERVAL 1 DAY) ";
      $selectRaw='event_id,sum(total_amount) as amount,sum(total_quantity) as quantity';
      $getevent =  $this->bookingDetails->getrawList($condition,$selectRaw);
      if(count($getevent)>0)
      {
        foreach ($getevent as $getevent)
        {
          $amountWeight = $getevent->amount;
          $quantityWeight = $getevent->quantity;
          $EventList[$getevent->event_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
        }

      }

      
                      ////get view count weight////
      $conditionView ="type='event' and created_at between  DATE_sub(curdate(),INTERVAL 10 DAY) and curdate() ";
      $selectViewRaw='view_id,count(id) as viewcount';
      $getview  = $this->viewcount->getrawList($conditionView ,$selectViewRaw);
      if(count($getview)>0)
      {
        foreach($getview as $getview)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = $getview->viewcount;
          if(array_key_exists($getview->view_id, $EventList))
          {
             $EventList[$getview->view_id]['view']=$viewWeight;
          }
          else
          {
            $EventList[$getview->view_id] = array('amount'=>$amountWeight,'quantity'=>$quantityWeight,'view'=>$viewWeight,'date'=>$dateWeight);
          }
        }

      }
       // dd($EventList);
                            ////////ticketlist Array/////////
      $ticketArray = array();
      $conditionticket = 'status=1 and display_status=1';
      $selectcolumnRawTicket = 'event_id,max(price) as maxprice,min(price) as minprice';
      $ticketdetail = $this->ticketInterface->getByRaw($conditionticket,$selectcolumnRawTicket);
      if(count($ticketdetail)>0)
      {
        foreach ($ticketdetail as $ticketdetail) 
        {
          $ticketArray[$ticketdetail->event_id] =array('maxprice'=>$ticketdetail->maxprice,
                                                       'minprice'=>$ticketdetail->minprice);
        }

      }
                      ///////// ////get date weight/////////////////
      $conditiondate ="status=1  and recurring_type=3 and  (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')";
      $selectdateRaw='id,city,country,no_dates,ticketsoldout,private,closed,ticketed,popularity,user_id,banner_image,title,venue_name,timezone_value,tags,eventtypes,category,start_date_time,(IF(end_date_time = "0000-00-00 00:00:00", `start_date_time`, `end_date_time`)) as enddate,state,timezone_id,url,event_mode';
      $geteventdate  = $this->event->getrawList($conditiondate ,$selectdateRaw);
      if(count($geteventdate)>0)
      {
        foreach($geteventdate as $geteventdate)
        {
          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = 0;
          $start_date_time = new \MongoDate(strtotime($geteventdate->start_date_time));
          $enddate = new \MongoDate(strtotime($geteventdate->enddate));
          if(array_key_exists($geteventdate->id, $EventList))
          {

            $EventList[$geteventdate->id]['no_dates']=$geteventdate->no_dates;
            $EventList[$geteventdate->id]['ticketsoldout']=$geteventdate->ticketsoldout;
            $EventList[$geteventdate->id]['closed']=$geteventdate->closed;
            $EventList[$geteventdate->id]['popularity']=$geteventdate->popularity;
            $EventList[$geteventdate->id]['title']=$geteventdate->title;
            $EventList[$geteventdate->id]['category']=strtolower($geteventdate->category);
            $EventList[$geteventdate->id]['eventtypes']=$geteventdate->eventtypes;
            $EventList[$geteventdate->id]['timezone_value']=$geteventdate->timezone_value;
            $EventList[$geteventdate->id]['tags']=$geteventdate->tags;
            $EventList[$geteventdate->id]['ticketed']=$geteventdate->ticketed;
            $EventList[$geteventdate->id]['user_id']=$geteventdate->user_id;
            $EventList[$geteventdate->id]['event_mode']=$geteventdate->event_mode;
            $EventList[$geteventdate->id]['banner_image']=$geteventdate->banner_image;
            $EventList[$geteventdate->id]['timezone_id']=$geteventdate->timezone_id;
            $EventList[$geteventdate->id]['private']=$geteventdate->private;
            $EventList[$geteventdate->id]['url']=$geteventdate->url;
            $EventList[$geteventdate->id]['date']=1;
          }
          else
          {
           
            $EventList[$geteventdate->id] = array('amount'=>$amountWeight,
                                                  'quantity'=>$quantityWeight,
                                                  'view'=>$viewWeight,
                                                  'date'=>1,
                                                  'no_dates'=>$geteventdate->no_dates,
                                                  'ticketsoldout'=>$geteventdate->ticketsoldout,
                                                  'closed'=>$geteventdate->closed,
                                                  'popularity'=>$geteventdate->popularity,
                                                  'title'=>$geteventdate->title,
                                                  'ticketed'=>$geteventdate->ticketed,
                                                  'category'=>strtolower($geteventdate->category),
                                                  'eventtypes'=>$geteventdate->eventtypes,
                                                  'venue_name'=>$geteventdate->venue_name,
                                                  'timezone_value'=>$geteventdate->timezone_value,
                                                  'tags'=>$geteventdate->tags,
                                                  'private'=>$geteventdate->private,
                                                  'user_id'=>$geteventdate->user_id,
                                                  'event_mode'=>$geteventdate->event_mode,
                                                  'banner_image'=>$geteventdate->banner_image,
                                                  'timezone_id'=>$geteventdate->timezone_id,
                                                  'url'=>$geteventdate->url);
          
          }
          
        }
      }
     

       //////////////get all slots///
      $slotArray = array();
      $conditionticket = 'status=1';
      $selectcolumnRawTicket = 'schedule_id,min(start_date_time) as startdate,max(end_date_time) as enddate';
      $slotdetails = $this->slotlist->getRawselect($conditionticket,$selectcolumnRawTicket);
      if(count($slotdetails)>0)
      {
        foreach ($slotdetails as $slotdetails) 
        {
          $slotArray[$slotdetails->schedule_id] =array('startdate'=>$slotdetails->startdate,
                                                       'enddate'=>$slotdetails->enddate);
        }

      }

             ///////////get all shedule////////

      $sheduleEvents = array();
      $sheduleList = $this->schedule->getallBy(array('status'=>1));
      if(count($sheduleList)>0)
      {
        foreach ($sheduleList as $sheduleList) 
        {  
          
          if(array_key_exists($sheduleList->id, $slotArray) && array_key_exists($sheduleList->event_id, $EventList) && array_key_exists($sheduleList->event_id, $ticketArray))
          {
            $start_date_time = new \MongoDate(strtotime($slotArray[$sheduleList->id]['startdate']));
            $enddate = new \MongoDate(strtotime($slotArray[$sheduleList->id]['enddate']));
            $sheduleEvents[$sheduleList->id] = array('city'=>$sheduleList->city,
                                                     'state'=>$sheduleList->state,
                                                     'country'=>strtolower($sheduleList->country),
                                                     'venue_name'=>$sheduleList->venue_name,
                                                     'event_id'=>$sheduleList->event_id);
            $sheduleEvents[$sheduleList->id]['start_date'] = $start_date_time;
            $sheduleEvents[$sheduleList->id]['end_date'] = $enddate;
            $sheduleEvents[$sheduleList->id]['eventdetails'] = $EventList[$sheduleList->event_id];
            $sheduleEvents[$sheduleList->id]['maxticketprice'] =intval($ticketArray[$sheduleList->event_id]['maxprice']) ;
            $sheduleEvents[$sheduleList->id]['minticketprice'] =intval($ticketArray[$sheduleList->event_id]['minprice']) ;
            

          }
          
        }
      }
       // dd($sheduleEvents);
      foreach ($sheduleEvents as $key=>$values) 
      {

        if($values['eventdetails']['date']==1)
        {
            $stausUpdate = 1;
            $checkEvent=$this->connection->collection('eventlisting')->where(array('event_id'=>$values['event_id'],'shedule_id'=>$key))->first();
            if($checkEvent)
            {
              $condition = array('event_id'=>$values['event_id'],'shedule_id'=>$key);
              //$totalWeightage = $values['amount']*1+$values['quantity']*0.1+$values['view']*0.01+$values['date']*0.001;
              $totalWeightage = $values['eventdetails']['amount']*1+$values['eventdetails']['quantity']*0.1+$values['eventdetails']['view']*0.01+$values['eventdetails']['date']*0.001;
              $updateData = array('amount'=>$values['eventdetails']['amount'],
                                  'quantity'=>$values['eventdetails']['quantity'],
                                  'view'=>$values['eventdetails']['view'],
                                  'date'=>$values['eventdetails']['date'],
                                  'total_weightage'=>$totalWeightage,
                                  'status'=>$stausUpdate,
                                  'private'=>$values['eventdetails']['private'],
                                  'city'=>$values['city'],
                                  'state'=>$values['state'],
                                  'country'=>$values['country'],
                                  'recurring_type'=>3,
                                  'start_date_time'=>$values['start_date'],
                                  'end_date_time'=>$values['end_date'],
                                  'no_dates'=>0,
                                  'ticketsoldout'=>$values['eventdetails']['ticketsoldout'],
                                  'closed'=>$values['eventdetails']['closed'],
                                  'popularity'=>$values['eventdetails']['popularity'],
                                  'user_id'=>$values['eventdetails']['user_id'],
                                  'title'=>$values['eventdetails']['title'],
                                  'venue_name'=>$values['venue_name'],
                                  'timezone_value'=>$values['eventdetails']['timezone_value'],
                                  'category'=>$values['eventdetails']['category'],
                                  'eventtypes'=>$values['eventdetails']['eventtypes'],
                                  'ticketed'=>$values['eventdetails']['ticketed'],
                                  'tags'=>$values['eventdetails']['tags'],
                                  'event_mode'=>$values['eventdetails']['event_mode'],
                                  'banner_image'=>$values['eventdetails']['banner_image'],
                                  'timezone_id'=>$values['eventdetails']['timezone_id'],
                                  'url'=>$values['eventdetails']['url'],
                                  'min_ticket_price'=>$values['minticketprice'],
                                  'max_ticket_price'=>$values['maxticketprice']);
              $updateWeight = $this->connection->collection('eventlisting')->where($condition)->update($updateData);
              echo $key.'--Updated';
            }
            else
            {
             
              $totalWeightage = $values['eventdetails']['amount']*1+$values['eventdetails']['quantity']*0.1+$values['eventdetails']['view']*0.01+$values['eventdetails']['date']*0.001;
              $InsertData = array('event_id'=>$values['event_id'],
                                  'shedule_id'=>$key,
                                  'amount'=>$values['eventdetails']['amount'],
                                  'quantity'=>$values['eventdetails']['quantity'],
                                  'view'=>$values['eventdetails']['view'],
                                  'date'=>$values['eventdetails']['date'],
                                  'total_weightage'=>$totalWeightage,
                                  'status'=>$stausUpdate,
                                  'city'=>$values['city'],
                                  'state'=>$values['state'],
                                  'country'=>$values['country'],
                                  'start_date_time'=>$values['start_date'],
                                  'end_date_time'=>$values['end_date'],
                                  'no_dates'=>0,
                                  'recurring_type'=>3,
                                  'ticketsoldout'=>$values['eventdetails']['ticketsoldout'],
                                  'private'=>$values['eventdetails']['private'],
                                  'closed'=>$values['eventdetails']['closed'],
                                  'popularity'=>$values['eventdetails']['popularity'],
                                  'user_id'=>$values['eventdetails']['user_id'],
                                  'title'=>$values['eventdetails']['title'],
                                  'venue_name'=>$values['venue_name'],
                                  'ticketed'=>$values['eventdetails']['ticketed'],
                                  'timezone_value'=>$values['eventdetails']['timezone_value'],
                                  'category'=>$values['eventdetails']['category'],
                                  'eventtypes'=>$values['eventdetails']['eventtypes'],
                                  'tags'=>$values['eventdetails']['tags'],
                                  'event_mode'=>$values['eventdetails']['event_mode'],
                                  'banner_image'=>$values['eventdetails']['banner_image'],
                                  'timezone_id'=>$values['eventdetails']['timezone_id'],
                                  'url'=>$values['eventdetails']['url'],
                                  'min_ticket_price'=>$values['minticketprice'],
                                  'max_ticket_price'=>$values['maxticketprice']);

              $InsertWeight = $this->connection->collection('eventlisting')->insert($InsertData);
               echo $key.'--inserted';
            }
        }
      }
    }

    /////////////get organiser/user based on popularity/////////


    public function addUpdateMogodb($event_id,$datatype=false){
      
      $this->connection=DB::connection('mongodbevent');
      $whereCondition = array("id"=>$event_id);
     
      $chkData = $this->event->getBy($whereCondition);
      if(count($chkData)>0){
      //AND $chkData->status==1 AND $chkData->private==0 And $chkData->end_date_time>=date('Y-m-d H:i:s')
      if($chkData->recurring_type==3 ){
          
          $dataFinal =array();
          // $conditiondate ="id=".$event_id." and status=1 and private=0 and recurring_type=3 and  (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')";
          // $selectdateRaw='id,city,country,no_dates,ticketsoldout,closed,popularity,user_id,banner_image,title,venue_name,timezone_value,tags,eventtypes,category,start_date_time,(IF(end_date_time = "0000-00-00 00:00:00", `start_date_time`, `end_date_time`)) as enddate,state,timezone_id,url,event_mode';
          // $geteventdata  = $this->event->getrawList($conditiondate ,$selectdateRaw);
         
          $schduleData  = $this->schedule->getallBy(array('status'=>1,'event_id'=>$event_id));
 
          $ticketArray = array();
          $conditionticket = 'event_id='.$event_id.' and status=1 and display_status=1';
          $selectcolumnRawTicket = 'event_id,max(price) as maxprice,min(price) as minprice';
          $ticketdetail = $this->ticketInterface->getByRaw($conditionticket,$selectcolumnRawTicket);
          
          $dataFinal['event_id'] = $event_id; 
          $dataFinal['date'] = 1;
          $dataFinal['no_dates'] = 1;
          $dataFinal['title'] = $chkData->title;    
          $dataFinal['category'] = $chkData->category; 
          
          $dataFinal['eventtypes'] = $chkData->eventtypes;
          $dataFinal['timezone_value'] = $chkData->timezone_value;
          $dataFinal['recurring_type'] = $chkData->recurring_type;
          $dataFinal['user_id'] = $chkData->user_id;
          $dataFinal['event_mode'] = $chkData->event_mode;
          $dataFinal['banner_image'] = $chkData->banner_image;
          $dataFinal['timezone_id'] = $chkData->timezone_id;
          $dataFinal['private'] = $chkData->private;
          $dataFinal['status'] = $chkData->status;
          $dataFinal['url'] = $chkData->url;

          $amountWeight=0;
          $quantityWeight=0;
          $viewWeight = 0;         
          
          if(count(@$schduleData)>0)
          {
            foreach ($schduleData as $sheduleList) 
            {  
                $dataFinal2=array();
                $dataArrayFinal =array();
                 $conditionticket = 'schedule_id='.$sheduleList->id.' and status=1';
                $selectcolumnRawTicket = 'schedule_id,min(start_date_time) as startdate,max(end_date_time) as enddate';
                $slotdetails = $this->slotlist->getRawselect($conditionticket,$selectcolumnRawTicket);
           
             if(!empty(@$slotdetails[0])>0){
                if($datatype==1){
                    $start_date_time = $slotdetails[0]->startdate;
                    $enddate = $slotdetails[0]->enddate;     
                } else {
                  $start_date_time = new \MongoDate(strtotime($slotdetails[0]->startdate));
                  $enddate = new \MongoDate(strtotime($slotdetails[0]->enddate));     
                }
             
                 $dataFinal2['venue_name'] = $sheduleList->venue_name;            
                 $dataFinal2['shedule_id'] = $sheduleList->id;
                 $dataFinal2['city'] = $sheduleList->city;
                 $dataFinal2['state'] = $sheduleList->state;
                 $dataFinal2['country'] = strtolower($sheduleList->country);
                 $dataFinal2['start_date_time'] = $start_date_time;
                 $dataFinal2['end_date_time'] = $enddate;            
                 
                 if(!empty(@$ticketdetail[0]->minprice)){
                    $dataFinal2['min_ticket_price'] = @$ticketdetail[0]->minprice;
                    $dataFinal2['max_ticket_price'] = @$ticketdetail[0]->maxprice;
                    $dataArrayFinal =array_merge($dataFinal, $dataFinal2);
                    
                    $condition = array('event_id'=>$event_id,'shedule_id'=>$sheduleList->id);
                    $this->makeeventlist($condition,$dataArrayFinal,$datatype);
                    // $checkEvent=$this->connection->collection('eventlisting')->where(array('event_id'=>$event_id,'shedule_id'=>$sheduleList->id))->first();
                    //  if($checkEvent) {
                    //       $condition = array('event_id'=>$event_id,'shedule_id'=>$sheduleList->id);
                    //       $updateWeight = $this->connection->collection('eventlisting')->where($condition)->update($dataArrayFinal);
                    //       echo $sheduleList->id.'--Updated';
                    //  } else {
                    //        $InsertWeight = $this->connection->collection('eventlisting')->insert($dataArrayFinal);
                    //        echo $sheduleList->id.'--inserted';
                    //  }
                                  
                 }
                 
              }   
  
                
          
              }
              
            }

          } 
         
      
      }
       

    }

    public function makeeventlist($condition,$data,$updatefor=false)
    {
        
      
        if($updatefor)
        { 
            $checkEvent = $this->weightage->getBy($condition); 
            if($checkEvent)
            {    
                $updateEvent= $this->weightage->update($data,$condition);
                
            }
            else
            {
                $InsertWeight = $this->weightage->create($data);
            }
        }
        else
        {
            $checkEvent=$this->connection->collection('eventlisting')->where($condition)->first();
            if($checkEvent)
            {
                $updateEvent= $this->connection->collection('eventlisting')->where($condition)->update($data);
                
            }
            else
            {
                $InsertWeight = $this->connection->collection('eventlisting')->insert($data);
            }

        }
    }

    public function getorgprofiles(Request $request)
    {
      $commonObj = new Common();
      $allUserArray = array(); ////user details array
      $getalluserdata=$this->userdetail->all(array('user_id','profile_id','profile_url','organization_url','organization_name','organization_logo'));
      foreach($getalluserdata as $getalluserdata)
      {
        //$profileurl=$getalluserdata->organization_url;
        if($getalluserdata->organization_url!='')
        {
          $organiserImage = URL::asset('web/images/org.jpg');
          if($getalluserdata->organization_logo)
          {
            
            $organiserImage = $_ENV['CF_LINK'].'/user/'.$getalluserdata->user_id.'/organizer/logo/'.$getalluserdata->organization_logo;
          }
           $organiserurl = URL::to('/'.$commonObj->cleanURL($getalluserdata->organization_url));
           $allUserArray[$getalluserdata->user_id] = array('logo'=>$organiserImage,
                                                           'profileurl'=>$organiserurl,
                                                           'name'=>$getalluserdata->organization_name);
        }
      }

      ///////////get all events popularity bases/////////
      $popularuserArray = array();
      $select = 'user_id,sum(total_weightage) as totalweighteg';
      $orderby = 'totalweighteg';
      $popularuser  =$this->weightage->popularuser(array(),$select,$orderby);
      foreach($popularuser as $popularuser)
      {
        if(array_key_exists($popularuser->user_id, $allUserArray))
        {
          $popularuserArray[$popularuser->user_id]=$allUserArray[$popularuser->user_id];
        }
      }

      return response()->json(['allUserArray' => $popularuserArray]);
    }



    function getTransactionStatus($order_id=NULL)
    {

      $commonObj =  new Common();
      $newTime = strtotime('-22 minutes');
      $minus22minutes=date('Y-m-d H:i:s', $newTime);

      $newTime = strtotime('-48 hours');
      $minus48hours=date('Y-m-d H:i:s', $newTime);

      $newTime = strtotime('-40 minutes');
      $minus40minutes=date('Y-m-d H:i:s', $newTime);

      /////////get incomplte order list///
      $conditionincomplete = "all_info=1 and details_submit=0 and order_time  <= '$minus22minutes' and order_time  >= '$minus48hours' and order_status=0";
      
      $getCondition= "order_status='pending' and order_time  <= '$minus22minutes' and order_time  >= '$minus48hours' and checked=0";
      if(strlen($order_id)>2)
      {
        $getCondition="order_status='pending' and order_id='$order_id'";
        $conditionincomplete = "id='$order_id' and all_info=1 and details_submit=0";
      }
      $incompleteorders = $this->order->getList($conditionincomplete,'event_id','id');
      // dd($incompleteorders);

      $dataList=$this->bookingDetails->getallByRaw($getCondition,array('order_id','order_time','mobile','total_quantity','event_id'));
      echo "executed ------ ".date('Y-m-d H:i:s',strtotime("+5 hours 30 minutes")).'*********************';
      if(count($dataList)>0)
      {
        foreach($dataList as $datavalue)
        {
         
          $order_id=$datavalue['order_id'];
          $order_time=$datavalue['order_time'];

          $url = 'https://api.secure.ebs.in/api/status';

          $fields = array(
              'Action' => 'status',
              'SecretKey' => 'e5b1d463bf0bcd24a41b4af491b53eeb',
              'AccountID' => '18143',
              'RefNo' => $datavalue['order_id'],
              'submitted' => 'Submit'
          );

        $result='';
        // echo $order_id.'-----';
        // continue;
        $fields_string='';
        //url-ify the data for the POST
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //execute post
        $result = curl_exec($ch);


        //close connection
        curl_close($ch);
        // echo "heressss";
        $xmlString=simplexml_load_string($result);
        $updatedata=array();
        $condition=array();
         // dd($xmlString);

        if(strtolower($xmlString['status'])=='captured')
        {
          if(strtolower($xmlString['isFlagged'])=='no')
          {
            $mailArray = array('orderid'=>$datavalue['order_id'],
                               'mobile'=>$datavalue['mobile'],
                               'totalQuantity'=>$datavalue['total_quantity'],
                               'event_id'=>$datavalue['event_id']);
            $updatedata=array('order_status'=>'completed','transaction_id'=>(string)$xmlString['paymentId'],'transaction_message'=>'Completed through automated module','checked'=>1);
            $condition=array('order_id'=>$order_id);
            $val= $this->bookingDetails->updateorder($updatedata, $condition);
            $bookingdata = json_decode($datavalue['details']);
            $updateticketholdtime= $this->functions->fetchGetData('/api/order/updateticket?order_id='.$order_id.'&order_status=completed&on_hold=1');
            if($datavalue['coupon_code']!='' || $datavalue['directdiscount']==1)
            {
              $updatecoupon= $this->functions->fetchGetData('/api/order/updatecoupon?orderid='.$order_id);
            }
            $mailArray['mailtype']=1;
            // dd($incompleteorders);
            if(array_key_exists($order_id, $incompleteorders))
            {
            
              $mailArray['mailtype']='incomplete';
            }
            
            $sendmail = $this->checkandsendmail($mailArray);
            echo date('Y-m-d H:i:s',strtotime("+5 hours 30 minutes")).' ('.$order_id.') Made Success  ------- ';
          }
        }
        else
        { 
          $updatedata=array('checked'=>1);
          $condition=array('order_id'=>$order_id);

// echo "in here_______((((((((((((()))))))))))))))))))))";
          if($order_time<=$minus40minutes)
            {$this->bookingDetails->updateorder($updatedata, $condition);
              echo date('Y-m-d H:i:s',strtotime("+5 hours 30 minutes")).' ('.$order_id.') Incomplete  ------- ';
            }
          
        }
        // print_r($result);
        // return $result;
        }
      }// end of if count condition
    }

    function getTransactionStatusRazorPay($order_id=NULL)
    {

      $commonObj =  new Common();
      $newTime22 = strtotime('-22 minutes');
      $minus22minutes=date('Y-m-d H:i:s', $newTime22);

      $newTime48 = strtotime('-48 hours');
      $minus48hours=date('Y-m-d H:i:s', $newTime48);

    // dd($minus22minutes.'----'.$minus48hours);
      $newTime = strtotime('-40 minutes');
      $minus40minutes=date('Y-m-d H:i:s', $newTime);

      $order_data=array();

      /////////get incomplte order list///
      $conditionincomplete = "all_info=1 and details_submit=0 and order_time  <= '$minus22minutes' and order_time  >= '$minus48hours' and order_status=0";
      
      $getCondition= "order_status='pending' and order_time  <= '$minus22minutes' and order_time  >= '$minus48hours' and payment_gateway=5 and checked=0";
      if(strlen($order_id)>2)
      {
        $getCondition="order_status='pending' and  order_id='$order_id'";
        $conditionincomplete = "id='$order_id'";
      }
      $incompleteorders = $this->order->getList($conditionincomplete,'event_id','id');

      $dataList=$this->bookingDetails->getallByRaw($getCondition,array('order_id','order_time','order_status','total_amount','coupon_code','directdiscount','total_quantity','mobile','event_id'));
      // echo "executed ------ ".date('Y-m-d H:i:s',strtotime("+5 hours 30 minutes")).'*********************';
      if(count($dataList)>0)
      {
      foreach($dataList as $datavalue)
      {
      
        $order_data[$datavalue->order_id]['order_status']=$datavalue->order_status;
        $order_data[$datavalue->order_id]['total_amount']=$datavalue->total_amount+$datavalue->extra_services_total;
        $order_data[$datavalue->order_id]['coupon_code']=$datavalue->coupon_code;
        $order_data[$datavalue->order_id]['directdiscount']=$datavalue->directdiscount;
        $order_data[$datavalue->order_id]['order_time']=$datavalue->order_time;
        $order_data[$datavalue->order_id]['event_id']=$datavalue->event_id;
        $order_data[$datavalue->order_id]['mobile']=$datavalue->mobile;
        $order_data[$datavalue->order_id]['total_quantity']=$datavalue->total_quantity;
      }
    }



    $exitloop=1;
    $loop=0;
    $skip=0;
    $count=100;

    while($exitloop)   // finding 
    {


    $rzp_order_data=array();
    $rzpResponse=$this->razorPayPublic($count,$skip,$newTime48,$newTime22);
    echo "_____$loop --- $skip -- count $count ---- $exitloop --- responsecount ".count($rzpResponse);
    // dd($rzpResponse);
    if(count($rzpResponse)>0)
    {

    foreach($rzpResponse['items'] as $rzpKey=>$rzpValue)
    {
      // $rzp_orderid=$rzpValue['notes']['order_id'];
            $rzp_orderid='';
      if(isset($rzpValue['notes']['order_id']))
        $rzp_orderid=$rzpValue['notes']['order_id'];
      $rzp_status=strtolower($rzpValue['status']);
      // dd($rzpValue);

      if(array_key_exists($rzp_orderid,$order_data) &&  ($rzp_status=='captured' || $rzp_status=='authorized'))
      {

        //scenario .... Authorized then captured
        if(isset($rzp_order_data[$rzp_orderid]) && $rzp_order_data[$rzp_orderid]['status']=='captured')
        {
        $rzp_order_data[$rzp_orderid]['status']=$rzp_status;
        $rzp_order_data[$rzp_orderid]['id']=$rzpValue['id'];
        $rzp_order_data[$rzp_orderid]['amount']=$rzpValue['amount']/100;  // Dividing by 100 to get the base Amount for in Razorpay the amount is multiplied by 100 for INR to get value in Paise
        }
        elseif(!isset($rzp_order_data[$rzp_orderid])){
                  $rzp_order_data[$rzp_orderid]['status']=$rzp_status;
        $rzp_order_data[$rzp_orderid]['id']=$rzpValue['id'];
        $rzp_order_data[$rzp_orderid]['amount']=$rzpValue['amount']/100;  // Dividing by 100 to get the base Amount for in Razorpay the amount is multiplied by 100 for INR to get value in Paise
        }

        // $rzp_
      }
    } //end of if(count($rzpResponse)) condtion

    // dd($rzp_order_data);
    if(count($rzp_order_data)>0 && count($order_data)>0 )
    {

        foreach($order_data as $order_data_key=>$order_data_value)
        {
          $updatedata=array();
          $order_id=$order_data_key;
          $mailArray = array('orderid'=>$order_data_key,
                               'mobile'=>$order_data_value['mobile'],
                               'totalQuantity'=>$order_data_value['total_quantity'],
                               'event_id'=>$order_data_value['event_id']);
          if(array_key_exists($order_data_key,$rzp_order_data))
          {
            
            
            if(($rzp_order_data[$order_data_key]['status']=='authorized' || $rzp_order_data[$order_data_key]['status']=='captured') && $order_data_value['total_amount']==$rzp_order_data[$order_data_key]['amount'])
            {
              $toupdate=0;
              $updatedata=array('order_status'=>'completed','transaction_id'=>'','transaction_message'=>'Completed through automated module','checked'=>1);

              if($rzp_order_data[$order_data_key]['status']=='authorized')
              {
                // dd('authorized');
                $razorPayToken=$rzp_order_data[$order_data_key]['id'];
                      try {

$api = new Api($_ENV['RAZORPAY_KEY_ID'], $_ENV['RAZORPAY_SECRET_KEY']);
// $api->payment->all($options); // Returns array of payment objects
$payment = $api->payment->fetch($razorPayToken); // Returns a particular payment
// dd($payment);
$rzpResponse=$payment->capture(array('amount'=>($rzp_order_data[$order_data_key]['amount']*100))); // Captures a payment
// $request->razorPayResponse=$rzpResponse;
                // return $this->response($request);
              if($rzpResponse->status=='captured')
            { 
              $toupdate=1;
              $updatedata['transaction_id']=$rzpResponse->id;


            }


            } catch (\Exception $e) {
              // dd('in here '.$e);
                echo "ERROR Occured for order id : $order_data_key   ____ ";
            }
            } // end of if($rzp_order_data[$order_data_key]['status']=='Authorized') condition
            else if($rzp_order_data[$order_data_key]['status']=='captured')
            {
                              
              $toupdate=1;
              $updatedata['transaction_id']=$rzp_order_data[$order_data_key]['id'];
            }

                      if($toupdate==1)
          {
            
            $condition=array('order_id'=>$order_id);
            $val= $this->bookingDetails->updateorder($updatedata, $condition);
            // $bookingdata = json_decode($datavalue['details']);
            $updateticketholdtime= $this->functions->fetchGetData('/api/order/updateticket?order_id='.$order_id.'&order_status=completed&on_hold=1');
            if($order_data_value['coupon_code']!='' || $order_data_value['directdiscount']==1)
            {
              $updatecoupon= $this->functions->fetchGetData('/api/order/updatecoupon?orderid='.$order_id);
            }
            $mailArray['mailtype']=1;
            if(array_key_exists($order_id, $incompleteorders))
            {
              $mailArray['mailtype']='incomplete';
            }
            
            $sendmail = $this->checkandsendmail($mailArray);
            echo date('Y-m-d H:i:s',strtotime("+5 hours 30 minutes")).' ('.$order_id.') Made Success  ------- ';
          }

          }//end of  if(($rzp_order_data[$order_data_key]['status']=='Authorized' || $rzp_order_data[$order_data_key]['status'] condition

          } // end of array_key_exists($order_data_key,$rzp_order_data) condition
          else
          {
          $updatedata=array('checked'=>1);
          $condition=array('order_id'=>$order_id);

// echo "in here_______((((((((((((()))))))))))))))))))))";
          if($order_data_value['order_time']<=$minus40minutes)
            {$this->bookingDetails->updateorder($updatedata, $condition);
              echo date('Y-m-d H:i:s',strtotime("+5 hours 30 minutes")).' ('.$order_id.') Incomplete  ------- ';
            }
          }


        }// end of foreach loop $order_data

    }  //end of if count($rzp_order_data)  condition

    // dd($rzpResponse['items']);
    if(count($rzpResponse)<$count)
    {
      echo "in here-- $loop";
      $exitloop=0;
      break;
    }

    $loop++;
    $skip=$loop*$count;
    if($loop>2)
    {echo "out here-- $loop";
      $exitloop=0;
      break;
    }

  } // end of count(rzpResponse) check 


    
    }

    


    }

 ///////////////////////
    ///parameter(event_id,orderid,mailtype,mobile,totalQuantity)
    private function checkandsendmail($requestArray)
    {
      $event  =$this->event->find($requestArray['event_id']);
      if($event)
      {
          $commonObj = new Common();
          $checkMail = $this->mail->getBy(array('order_id'=>$requestArray['orderid'],'type'=>$requestArray['mailtype']));
          if($checkMail)
          {
              $update = $this->mail->updatemail(array('status'=>1),array('order_id'=>$requestArray['orderid'],'type'=>$requestArray['mailtype']));
              return true;
          }
          else
          {
              $mail=0;
             if(in_array($requestArray['event_id'], config('commondata.Running_Events')))
              {
                $mail = $commonObj->customeventsmail(1,$requestArray['orderid'],1);
              }
              else
              {
                $mail = $commonObj->sendmailcommon($requestArray['mailtype'],$requestArray['orderid'],1);

              }
             if($mail==1)
             {
              $insertMail = $this->mail->create(array('order_id'=>$requestArray['orderid'],'status'=>1,'type'=>$requestArray['mailtype']));
             }
             if($requestArray['mailtype']==1)
             {
              $numbers = $requestArray['mobile']; // A single number or a comma-seperated list of numbers
                            $message = "Booking confirmed for event : ".substr($event->title,0,55).",
    Order ID: ".$requestArray['orderid'].",
    Quantity: ".$requestArray['totalQuantity']."
    Cheers! www.goeventz.com";
                            // A single number or a comma-seperated list of numbers
                            $message = urlencode($message);
                            $sensms = $commonObj->sendsms($numbers,$message);

             }
              return true;
          }
      }
      else
      {
        return false;
      }
  
    }

    public function razorPayPublic($count=10,$skip=0,$from=NULL,$to=NULL){
             try {

$api = new Api($_ENV['RAZORPAY_KEY_ID'], $_ENV['RAZORPAY_SECRET_KEY']);

$options['count']=$count;
$options['skip']=$skip;
if(isset($from) && isset($to))
  $options['from']=$from;

if(isset($to) && isset($from))
  $options['to']=$to;

$rzpResponse=$api->payment->all($options); // Returns array of payment objects
// $payment = $api->payment->fetch($razorPayToken); // Returns a particular payment
return ($rzpResponse);
// $rzpResponse=$payment->capture(array('amount'=>$baseCurrencyAmount)); // Captures a payment
// $request->razorPayResponse=$rzpResponse;
//                 return $this->response($request);
            } catch (\Exception $e) {
              return "error";
                // return back()->with('error',$e->getMessage());
            }
    }

 public function updateeventpayment(Request $request)
    {
      $assignedtoArray=array();
      $assignArray = array();
      $alluserArray = array();
      $checkeventidArray = array();
      $updateEventId = array();

      // dd($request->commonfeilds);
      $checkAssign = $this->userassign->getallBy(array('status'=>1),array('admin_user_id','user_id'));
      if(count($checkAssign)>0)
      {
        foreach($checkAssign as $checkAssign)
        {
          $assignArray[$checkAssign->user_id][] = $checkAssign->admin_user_id;
        }
      }
      ////////////////all admin userslist//////
     
      $whereCondition = array('status'=>1);
      $userData = $this->user->getallBy($whereCondition,array('id','name','email','mobile'));
      foreach($userData as $userData)
      {
        $alluserArray[$userData->id] = array('name'=>$userData->name,
                                             'email'=>$userData->email,
                                             'mobile'=>$userData->mobile);
      }
                
     
      $result=array();

      //////////list off completed and refunded order////////
      $orderRawcondition = "(order_status='completed' or order_status='refunded')  and payment_mode=1";
      if(isset($request->commonfeilds) && strlen($request->commonfeilds)>0) //////// if eventid set 
      {
         $checkeventidArray[]=$request->commonfeilds;
         $eventList=$request->commonfeilds;
      }
      else
      {
        ////////get latest cron time///////
        $getlatesttime = $this->cronhistory->getBy(array('cron_type'=>'payment'),array('latest_time'));
        if($getlatesttime)
        {
          $orderRawcondition.=" and updated_at >'".$getlatesttime->latest_time."'";

        }
        else
        {
          $orderRawcondition.="  or (TIMESTAMPDIFF(HOUR, updated_at,now())<=3";
        }
        $selectOrder = 'distinct(event_id) as eventidlist';
        $getEventids = $this->bookingDetails->getrawList($orderRawcondition,$selectOrder);
        $eventList=''; //// id string////event
        foreach($getEventids as $value)
        {
          $checkeventidArray[]=$value->eventidlist;
          $eventList.=$value->eventidlist.',';
        }

        $eventList=rtrim($eventList,',');
      }
        // dd($checkeventidArray);
      if(!empty($eventList))
      {
        $Rawconditionorder = 'event_id in '.'('.$eventList.')'.' and order_status="completed" and payment_mode=1';
        //dd($Rawconditionorder);
        //////////all booking array/////
        $maxdate = '0000-00-00 00:00:00';
        $eventIdArray = '';
        $selectOrder = 'event_id,sum(total_amount) as totalamount,sum(round(total_amount-extra_charges)) as paybalamount,sum(total_quantity) as totalquantity,count(order_id) as totaltransaction,max(updated_at) as ordertime';
        $orderbookingDetailsList = $this->bookingDetails->getrawList($Rawconditionorder,$selectOrder);
        if(count($orderbookingDetailsList)>0)
        {
          
          foreach ($orderbookingDetailsList as $orderDetails)
          {
            if(strtotime($orderDetails->ordertime)>strtotime($maxdate))
            {
              $maxdate=$orderDetails->ordertime;
            }
            $updateEventId[] = $orderDetails->event_id;
            $eventIdArray.=$orderDetails->event_id.',';
            $result[$orderDetails->event_id]=array('title'=>'',
                                                    'user_id'=>'',
                                                    'assignto'=>'',
                                                    'orgname'=>'',
                                                    'orgemail'=>'',
                                                    'orgmobile'=>'',
                                                    'eventtype'=>'',
                                                    'startdate'=>'',
                                                    'enddate'=>'',
                                                    'createdat'=>'',
                                                    'totalamount'=>$orderDetails->totalamount,
                                                    'amountpaid'=>0,
                                                    'adjustment'=>0,
                                                    'paybalamount'=>round($orderDetails->paybalamount,2),
                                                    'totalquantity'=>$orderDetails->totalquantity,
                                                    'totaltransaction'=>$orderDetails->totaltransaction);
            
          }
          $selectColumn ='user_id,id,title,CONVERT_TZ(start_date_time,"+00:00",timezone_value) as startdate,IF(end_date_time = "0000-00-00 00:00:00", CONVERT_TZ(start_date_time,"+00:00",timezone_value), CONVERT_TZ(end_date_time,"+00:00",timezone_value)) as enddate,recurring_type,venue_name,created_at,created_by,modified_by';
          $conditionRaw= "id in ".'('.substr($eventIdArray,0,-1).')'."";
          $dataEventlist = $this->event->getrawList($conditionRaw,$selectColumn);
          if(count($dataEventlist)>0)
          {
             foreach($dataEventlist as $dataEvent)
             {
                $assignto='';
                if(array_key_exists($dataEvent->user_id, $assignArray))
                {
                  $assinadminId = $assignArray[$dataEvent->user_id];
                  foreach($assinadminId as $assinadminId)
                  {
                    if(array_key_exists($assinadminId, $alluserArray))
                    {
                      $assignto.=$alluserArray[$assinadminId]['name'].', ';
                    }
                  }
                }

                $orgname='';
                $orgemail='';
                $orgmobile='';
                if(array_key_exists($dataEvent->user_id, $alluserArray))
                {
                  $orgname=$alluserArray[$dataEvent->user_id]['name'];
                  $orgemail=$alluserArray[$dataEvent->user_id]['email'];
                  $orgmobile=$alluserArray[$dataEvent->user_id]['mobile'];
                }
                $result[$dataEvent->id]['title']=$dataEvent->title;
                $result[$dataEvent->id]['user_id']=$dataEvent->user_id;
                $result[$dataEvent->id]['ipaddress']=$dataEvent->ipaddress;
                $result[$dataEvent->id]['assignto']=$assignto;
                $result[$dataEvent->id]['orgname']=$orgname;
                $result[$dataEvent->id]['orgmobile']=$orgmobile;
                $result[$dataEvent->id]['orgemail']=$orgemail;
                $result[$dataEvent->id]['eventtype']=$dataEvent->recurring_type;
                $result[$dataEvent->id]['startdate']=$dataEvent->startdate;
                $result[$dataEvent->id]['enddate']=$dataEvent->enddate;
                $result[$dataEvent->id]['createdat']=date('Y-m-d H:i:s',strtotime($dataEvent->created_at));
              
                
              }
          }
          //////////all paynemtg history array/////
          $eventtarnsaction = array();
          $eventpaidcondition = "status='1' and event_id in ".'('.substr($eventIdArray,0,-1).')'." and payment_mode!='Adjustment'";
          $selectcolumns = 'event_id,sum(amount) as totalamount';
          $getpaidamount = $this->paymenthistory->getrawList($eventpaidcondition,$selectcolumns);
          if(count($getpaidamount)>0)
          {
            foreach ($getpaidamount as $getpaidamount)
            {
              $result[$getpaidamount->event_id]['amountpaid'] = $getpaidamount->totalamount;
            }
          }
          /////////// adjustmnet amount///////

          $eventpaidcondition = "status='1' and event_id in ".'('.substr($eventIdArray,0,-1).')'." and payment_mode='Adjustment'";
          $selectcolumns = 'event_id,sum(amount) as totalamount';
          $getadjusdamount = $this->paymenthistory->getrawList($eventpaidcondition,$selectcolumns);
          if(count($getadjusdamount)>0)
          {
            foreach ($getadjusdamount as $getadjusdamount)
            {
              $result[$getadjusdamount->event_id]['adjustment'] = $getadjusdamount->totalamount;
            }
          }
          // dd($checkeventidArray);
          $checkpayment = $this->amountpaid->getListeventid($checkeventidArray,'id','event_id');
          // dd($checkpayment);
          foreach($result as $key=>$val)
          {
            // $checkpayment = $this->amountpaid->getBy(array('event_id'=>$key),array('id','event_id'));
            $ArrayData = array('event_id'=>$key,
                               'title'=>$val['title'],
                               'user_id'=>$val['user_id'],
                               'start_date_time'=>$val['startdate'],
                               'end_date_time'=>$val['enddate'],
                               'published_on'=>$val['createdat'],
                               'event_create_on'=>$val['createdat'],
                               'event_type'=>$val['eventtype'],
                               'org_email'=>$val['orgemail'],
                               'org_name'=>$val['orgname'],
                               'org_mobile'=>$val['orgmobile'],
                               'assignto'=>$val['assignto'],
                               'total_amount'=>$val['totalamount'],
                               'total_quantity'=>$val['totalquantity'],
                               'total_txn'=>$val['totaltransaction'],
                               'amount_payable'=>$val['paybalamount'],
                               'adjustment'=>$val['adjustment'],
                               'amount_paid'=>$val['amountpaid'],
                               'created_at'=>date('Y-m-d H:i:s'));
            if(array_key_exists($key,$checkpayment))
            {
             
              $updateeventpay = $this->amountpaid->update($ArrayData,array('event_id'=>$key));
             // echo $key.'update----';
            }
            else
            {
              $create = $this->amountpaid->create($ArrayData);
              //echo $key.'inserted----';
            }
          }
          //////////////////  id's not updated//////
          $getnotUPadteid = array_diff($checkeventidArray, $updateEventId);
          if(count($getnotUPadteid)>0)
          {
            foreach($getnotUPadteid as $getnotUPadteid)
            {
              if(array_key_exists($getnotUPadteid,$checkpayment))
              {
                $dd['total_amount'] = 0;
                $dd['total_quantity'] = 0;
                $dd['total_txn'] = 0;
                $dd['amount_payable'] = 0;
                $updateeventpay = $this->amountpaid->update($dd,array('event_id'=>$getnotUPadteid));
               // echo $key.'update----';
              }
            }
          }
          $conArray = array('cron_type'=>'payment','latest_time'=>$maxdate,'created_at'=>date('Y-m-d H:i:s'));
          $createCronlog = $this->cronhistory->create($conArray);
          $underif=1;
        }
        elseif(isset($request->commonfeilds) && strlen($request->commonfeilds)>0)
        {
          $checkpayment = $this->amountpaid->getBy(array('event_id'=>$eventList),array('id','event_id'));
          if($checkpayment)
          {
              $dd['total_amount'] = 0;
              $dd['total_quantity'] = 0;
              $dd['total_txn'] = 0;
              $dd['amount_payable'] = 0;

              $updateeventpay = $this->amountpaid->update($dd,array('event_id'=>$eventList));
          }
        }
        else
        {
          $eventidarrayLIst = explode(',', $eventList);
          $checkpayment = $this->amountpaid->getListeventid($checkeventidArray,'id','event_id');
          foreach($eventidarrayLIst as $eventid)
          {
            // $checkpayment = $this->amountpaid->getBy(array('event_id'=>$eventid),array('id','event_id'));
            if(array_key_exists($eventid,$checkpayment))
            {
                $dd['total_amount'] = 0;
                $dd['total_quantity'] = 0;
                $dd['total_txn'] = 0;
                $dd['amount_payable'] = 0;
                $updateeventpay = $this->amountpaid->update($dd,array('event_id'=>$eventid));
            }
          }
        }
      }
    }


    public function eventviewcounter(Request $request)
    {
      $commonObj = new Common();
      $viewconter= $commonObj->eventviewcounter($request->eventid,$request->type);
      return $viewconter;

    }

    ///////////////////////////// send pending order remnders//////
    public function sendorderreminders(Request $request)
    {
      // dd('xzXz');
      $status['status']='error';
      $commonObj = new Common();
      if($request->reminder==1)
      {

         ////////////get all pending orders/////////
        $eventIdArray='';
        $whereConditionraw="payment_mode=1 and IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."' and date(order_time) = DATE_sub(curdate(),INTERVAL 1 DAY)";
         $selecTRawCon = 'event_id,name ,email,mobile ,order_time,order_id,IF(end_date_time = "0000-00-00 00:00:00", CONVERT_TZ(start_date_time,"+00:00",timezone_value), CONVERT_TZ(end_date_time,"+00:00",timezone_value)) as end_date_time';
        $getpendinglist = $this->bookingDetails->getallorderByRawpending($whereConditionraw,$selecTRawCon,array('email','event_id'));
        foreach($getpendinglist as $getlist)
        {
          $eventIdArray.=$getlist->event_id.',';
        }
         // dd($eventIdArray);
        //////////////////// get remknder//////
        if($eventIdArray!='')
        {
          $reminderArray = array();
          $whereCondition=" event_id in ".'('.substr($eventIdArray,0,-1).')'."";
          $reminderList = $this->orderreminder->getallByRaw($whereCondition,array('id','event_id','email','order_id_in'));
          if(count($reminderList)>0)
          {
            foreach($reminderList as $reminderList)
            {
              $reminderArray[$reminderList->event_id.'-'.$reminderList->email] = $reminderList->order_id_in;
            }
          }
            //dd($reminderArray);
          foreach($getpendinglist as $getpendinglist)
          {
            if(!array_key_exists($getpendinglist->event_id.'-'.$getpendinglist->email, $reminderArray))
            {
              $request->orderid=$getpendinglist->order_id;
              $request->eventid=$getpendinglist->event_id;
              $request->reminder=1;
              // $getpendinglist->end_date_time.'end date---<br/>';
              $insertArrray  = array('event_id' =>$getpendinglist->event_id, 
                                     'email' =>$getpendinglist->email,
                                     'order_id_in' =>$getpendinglist->order_id);
              $firstdate = date('Y-m-d H:i:s');
              $insertArrray['reminder1'] = $firstdate;
              $insertArrray['reminder1_status'] =1;
              $insertArrray['created_at'] = date('Y-m-d H:i:s');
              $today  = new DateTime(date('Y-m-d H:i:s'));
              $past   = new DateTime($getpendinglist->end_date_time);
              $dteDiff  = $today->diff($past);
              // $dteDiff->d.'datediff<br/>';
              if($dteDiff->d>4)
              {
                $insertArrray['reminder2']=  date('Y-m-d H:i:s', strtotime("+2 days"));
                $insertArrray['reminder3'] =  date('Y-m-d H:i:s', strtotime($getpendinglist->end_date_time."-1 days"));

              }
              elseif($dteDiff->d>3)
              {
                $insertArrray['reminder2'] =  date('Y-m-d H:i:s', strtotime("+1 days"));
                $insertArrray['reminder3'] =  date('Y-m-d H:i:s', strtotime($getpendinglist->end_date_time."-1 days"));
              }
              elseif($dteDiff->d>2)
              {
                $insertArrray['reminder2'] =  date('Y-m-d H:i:s', strtotime("+1 days"));
                $insertArrray['reminder3'] =  date('Y-m-d H:i:s', strtotime("+2 days"));
              }
              elseif($dteDiff->d>1)
              {
                $insertArrray['reminder2'] =  '';
                $insertArrray['reminder3'] =  date('Y-m-d H:i:s', strtotime($getpendinglist->end_date_time."-1 days"));
              }
              else
              {
                $request->reminder=3;
                $insertArrray['reminder1']='';
                $insertArrray['reminder1_status'] =0;
                $insertArrray['reminder2'] =  '';
                $insertArrray['reminder3'] =  date('Y-m-d H:i:s');
                $insertArrray['reminder3_status'] =1;
              }
              $createreminder = $this->orderreminder->create($insertArrray);
              if($createreminder)
              {
                $extraEmail =array('1656465@bcc.hubspot.com','support@goeventz.com');


              $mailtemplete = $this->admincntrl->getmailtempletes($request);
               if($mailtemplete['status']=='success')
               {
                $mailArray= array('to'=>$getpendinglist->email,
                          'name'=>$mailtemplete['name'],
                          'subject'=>$mailtemplete['subject'],
                          'bcc'=>$extraEmail);
                
                 $sendmail = $commonObj->sendmailpending($mailtemplete['message'],$mailArray);

               }
               $status['events'][]=$getpendinglist->event_id.'--'.$mailtemplete['status'];
                
              }
            }
            else
            {
              $status['events_update'][]=$getpendinglist->event_id;
             // echo $reminderArray[$getpendinglist->event_id.'-'.$getpendinglist->email].'----<br/>';
              $orderidin = $reminderArray[$getpendinglist->event_id.'-'.$getpendinglist->email].','.$getpendinglist->order_id;
              $getuniqueorder = explode(',',$orderidin);
              $uniquerorder = array_unique($getuniqueorder);

              //echo $getpendinglist->order_id.'<br/>';
              $updatereminder = $this->orderreminder->updateorder(array('order_id_in'=>implode(',',$uniquerorder)),array('event_id'=>$getpendinglist->event_id,'email'=>$getpendinglist->email));
            }
          }

          $status['status']='success';
        }
        else
        {
          $status['status']='norecords';
        }
      }
      else
      {
        if($request->reminder==2 || $request->reminder==3)
        {
          $whereCondition=" date(reminder".$request->reminder.") = curdate() and reminder".$request->reminder."_status=0";
          $reminderList = $this->orderreminder->getallByRaw($whereCondition,array('id','event_id','email','order_id_in'));
          if(count($reminderList)>0)
          {
            $i=0;
            foreach($reminderList as $reminderList)
            {
              $orderid = explode(',',$reminderList->order_id_in);
              // dd($orderid);
              $request->orderid=end($orderid);
              $request->eventid=$reminderList->event_id;
              $extraEmail =array('1656465@bcc.hubspot.com','support@goeventz.com');
              $mailtemplete = $this->admincntrl->getmailtempletes($request);
               if($mailtemplete['status']=='success')
               {
                $mailArray= array('to'=>$reminderList->email,
                            'name'=>$mailtemplete['name'],
                            'subject'=>$mailtemplete['subject'],
                            'bcc'=>$extraEmail);
                  
                $sendmail = $commonObj->sendmailpending($mailtemplete['message'],$mailArray);
               }
              $i++;
              $status['update'][]=$request->eventid.'|'.$request->email.'|'.$mailtemplete['status'];

            }
            $status['status']='success';
          }
          else
          {
            $status['status']='norecords';
          }
       }
      }

      return $status;
    }



   
}