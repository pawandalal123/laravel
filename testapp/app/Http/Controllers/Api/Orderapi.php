<?php 
namespace App\Http\Controllers\Api;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Common;
use App\Model\Currency;
use DateTime;
use App\Model\Ticket;
use Auth;
use URL;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\OrderInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\EventcustomfieldInterface;
use Appfiles\Repo\OrderbreakageInterface;
use Appfiles\Repo\EventcustomfieldsvalueInterface;
use Appfiles\Repo\RecurringeventInterface;
use Appfiles\Repo\BookingdetailsInterface;
use Appfiles\Repo\TicketwidgetInterface;
use Appfiles\Repo\UserassignInterface;
use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\EventdetailInterface;
use Appfiles\Repo\TimezoneInterface;
use Appfiles\Repo\SettingsInterface;
use Appfiles\Repo\UsertabInterface;
use Appfiles\Repo\ScheduleInterface;
use Appfiles\Repo\SeatplanInterface;
use Appfiles\Repo\GecommerceInterface;
use Appfiles\Repo\MailsInterface;
use Appfiles\Repo\SlotInterface;
use Appfiles\Repo\DiscountcouponInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Appfiles\Common\AmazonS3Upload;
class Orderapi extends Controller 
{
   protected $s3;
   public function  __construct(UsersInterface $user , AmazonS3Upload $s3,OrderInterface $order , EventInterface $event ,
    RecurringeventInterface $recurringevent, EventcustomfieldInterface $eventfeild , 
    BookingdetailsInterface $bookingDetails ,OrderbreakageInterface $orderbreakage , 
    EventcustomfieldsvalueInterface $customfieldsvalue , 
    TicketInterface $ticket , TimezoneInterface $timezone , 
    EventdetailInterface $eventdetail,DiscountcouponInterface $discount , 
    TicketwidgetInterface $ticketwidget ,SettingsInterface $setting,UserassignInterface $userassign,
    ScheduleInterface $schedule , GecommerceInterface $geoecommerec , 
    SlotInterface $slotlist,SeatplanInterface $seatplan,MailsInterface $mail,UsertabInterface $tabs)
    {
        $this->order=$order;
        $this->user=$user;
        $this->event=$event;
        $this->bookingDetails=$bookingDetails;
        $this->eventfeild=$eventfeild;
        $this->orderbreakage= $orderbreakage;
        $this->customfieldsvalue = $customfieldsvalue;
        $this->ticket = $ticket;
        $this->ticketwidget =$ticketwidget;
        $this->timezone = $timezone;
        $this->eventdetail = $eventdetail;
        $this->discount = $discount;
        $this->geoEcommerce = $geoecommerec;
        $this->recurringevent = $recurringevent;
        $this->setting=$setting;
        $this->userassign =$userassign;
        $this->schedule = $schedule;
        $this->slotlist = $slotlist;
        $this->seatplan = $seatplan;
        $this->tabs = $tabs;
        $this->mail=$mail;
        $this->s3=$s3;
    }

    public function orderformdatanew(Request $request)
    {
        $condition = array('id'=>$request->order_id);
        $Events_tickets_bookings =  $this->order->getBy($condition);
        $type=0;
        $showtime='';
        $orderformdata = '';
        $formfeild = array();
        $addressdata = '';
        $commonfeild='';
        if($Events_tickets_bookings)
        {
            //event details array//
                $dataEvent = '';
                $eventDetails = $this->event->getBy(array('id'=>$Events_tickets_bookings->event_id));
                $commonObj = new Common();
                $gettimezone = $this->timezone->find($eventDetails->timezone_id);
                $timezonename = $commonObj->gettimezonename($gettimezone->timezone);
                $getstartdate = $commonObj->ConvertGMTToLocalTimezone($eventDetails->start_date_time,$gettimezone->timezone);
                if($eventDetails->no_dates==1)
                {
                   //$eventcreateType='withoutdate';
                   if($eventDetails->start_date_time=='' || $eventDetails->start_date_time=='0000-00-00 00:00:00')
                   {
                     $getstartdate='';
                   }
                }
                ////////////////check getway selected/////////////////
                $getwayChecked='';
                $paytm='';
                $ebs='';
                $paypal='';
                $razorpay='';
                $stripe='';
                $checkGetway = $this->setting->getallBy(array('event_id'=>$eventDetails->id,
                                                              'type'=>1,'status'=>1),array('value'));
                if(count($checkGetway)>0)
                {
                  $getwayChecked='Yes';
                  foreach ($checkGetway as $checkGetway) 
                  {
                    switch ($checkGetway->value) {
                      case 1:
                      $ebs='show';
                      break;
                      case 2:
                      $paytm='show';
                      break;
                      case 3:
                      $paypal='show';
                      break;
                      case 4:
                      $stripe='show';
                      break;
                      case 5:
                      $razorpay='show';
                      break;

                    }
                  }
                }

                $eventcity = $eventDetails->city;
                $eventstate = $eventDetails->state;
                $eventcountry = $eventDetails->country;
                if($Events_tickets_bookings->event_type==2)
                {
                  $checkslot = $this->slotlist->find($Events_tickets_bookings->show_id);
                  if($checkslot)
                  {
                    $getstartdate = $commonObj->ConvertGMTToLocalTimezone($checkslot->start_date_time,$gettimezone->timezone);
                    $getenddate   = $commonObj->ConvertGMTToLocalTimezone($checkslot->end_date_time,$gettimezone->timezone);
                    $result['eventstartdate'] = $getstartdate;
                    $getshedule = $this->schedule->find($checkslot->schedule_id);
                    if($getshedule)
                    {
                      $eventvenue = $getshedule->venue_name;
                      $eventcity = $getshedule->city;
                      $eventstate = $getshedule->state;
                      $eventcountry = $getshedule->country;
                    }
                  }
                }
                elseif($Events_tickets_bookings->show_date!='')
                {
                  $type=1;
                  if($Events_tickets_bookings->show_date!='0000-00-00')
                  {
                    
                    $showtiming = $this->recurringevent->find($Events_tickets_bookings->show_id);
                    if($showtiming)
                    {
                      $showtime = $showtiming->start_time;
                    }
                   //$getstartdateshow = $commonObj->ConvertGMTToLocalTimezone($Events_tickets_bookings->show_date,$gettimezone->timezone);
                    $getstartdate = substr($Events_tickets_bookings->show_date, 0,10).' '.$showtime;
                  }
                }
                $date1timestamp = strtotime(date('Y-m-d H:i:s'));
                $date2timestamp = strtotime($Events_tickets_bookings->order_time);
                $timediffrence = round(($date1timestamp - $date2timestamp));
                $holdtime=0;
                $remainingtime = $Events_tickets_bookings->hold_time - $timediffrence;
                if($remainingtime>0)
                {
                  $holdtime = $remainingtime;
                }
                $dataEvent = array('eventid'=>$eventDetails->id,
                                   'eventname'=>$eventDetails->title,
                                   'eventcity'=>$eventcity,
                                   'eventstate'=>$eventstate,
                                   'eventcountry'=>$eventcountry,
                                   'timezonename'=>$timezonename,
                                   'eventtype'=>$type,
                                   'eventshow'=>$showtime,
                                   'eventstartdate'=>$getstartdate,
                                   'eventurl'=>$commonObj->cleanURL($eventDetails->url),
                                   'eventmapurl'=>$eventDetails->map_url,
                                   'getwayChecked'=>$getwayChecked,
                                   'paytm'=>$paytm,
                                   'ebs'=>$ebs,
                                   'paypal'=>$paypal,
                                   'stripe'=>$stripe,
                                   'razorpay'=>$razorpay);
            if($Events_tickets_bookings->order_status==1)
            {
                $orderformdata = 'success';
                return response()->json([
                       'orderformdata' => $orderformdata,
                       'eventdetail' => $dataEvent,
                       'commonfeild'=>$commonfeild,
                       'cstomfeild'=>''
                       ]);
            }
            elseif($Events_tickets_bookings->hold_time<=0 || $holdtime<=0)
            {
                $orderformdata = 'timeover';
                return response()->json([
                       'orderformdata' => $orderformdata,
                       'eventdetail' => $dataEvent,
                       ]);
            }
            else
            {   

                $isformshow='No';
                $is_more_informational='No';
                 $orderfrom='online';
                $is_pay='No';
                $bookingdata = json_decode($Events_tickets_bookings->details);
                //echo "<pre>"; print_r($bookingdata);die;
                $ticketArray='';
                $formtitle='Attendee';
                if($Events_tickets_bookings->all_info==1)
                {
                    $is_more_informational='Yes';
                    /////////////set form title///////////
                    $checktitle = $this->setting->getBy(array('event_id'=>$Events_tickets_bookings->event_id,'status'=>1,'type'=>5),array('value'));
                    if($checktitle)
                    {
                      $formtitle=$checktitle->value;
                    }
                    /////////for offline booking form is always display//////////
                    if($Events_tickets_bookings->booking_from>3)
                    {
                      $orderfrom='offline';
                    }
                    //////////check form setting//////////////
                    $checkformsetting = $this->setting->getBy(array('event_id'=>$Events_tickets_bookings->event_id,'status'=>1,'type'=>4),array('value'));
                    if($checkformsetting)
                    {
                      if($checkformsetting->value==1)
                      {
                        $isformshow='Yes';
                      }
                    }
                  $formfeild = $this->getcustomfeilds($Events_tickets_bookings->id);
                }

                $commonfeild = array('Name'=>array('type'=>'text','value'=>''),
                                     'Email'=>array('type'=>'email','value'=>''),
                                     'Mobile'=>array('type'=>'text','value'=>''));
                if(Auth::check())
                {
                  $userDetail =  Auth::user();  
                  $phoneWths='';
                  if(isset($userDetail->mobile)){
                   
                    $phoneWths = str_replace('-', ' ', $userDetail->mobile); 
                 }
                 
                  $commonfeild = array('Name'=>array('type'=>'text','value'=> $userDetail->name),
                                     'Email'=>array('type'=>'email','value'=>$userDetail->email),
                                     'Mobile'=>array('type'=>'text','value'=>$phoneWths));
                }
                
                
                  $date1timestamp = strtotime(date('Y-m-d H:i:s'));
                  $date2timestamp = strtotime($Events_tickets_bookings->order_time);
                  $timediffrence = round(($date1timestamp - $date2timestamp));
                  $holdtime=0;
                  $remainingtime = $Events_tickets_bookings->hold_time - $timediffrence;
                  if($remainingtime>0)
                  {
                    $holdtime = $remainingtime;
                  }
                 
                     //order details  array//
                if($Events_tickets_bookings->is_free==0)
                {
                    $is_pay='Yes';
                }
                $seatselected='';
              if($Events_tickets_bookings->event_id==env('CUSTOM_SEAT_EVENTID'))
              {
                $selectcolumns ="event_id,group_concat(seat_selected) as seatselected";
                $checkseat = $this->seatplan->getByraw(array('event_id'=>$Events_tickets_bookings->event_id,'status'=>1,'show_date'=>$Events_tickets_bookings->show_date),$selectcolumns);
                if($checkseat)
                {
                  $seatselected = $checkseat->seatselected;
                }
              }
                $orderformdata = array('order_id'=>$Events_tickets_bookings->id,
                                       'user_id'=>$Events_tickets_bookings->user_id,
                                       'event_id'=>$Events_tickets_bookings->event_id,
                                       'booking_data'=>$bookingdata->response->calculatedDetails,
                                       'totalamount'=>$bookingdata->response->calculatedDetails->totalAmount,
                                       'totalQuantity'=>$bookingdata->response->calculatedDetails->totalQuantity,
                                       'discount'=>$bookingdata->response->calculatedDetails->discount,
                                       'globaldiscount'=>$bookingdata->response->calculatedDetails->goeventzdiscount,
                                       'finalamount'=>$bookingdata->response->calculatedDetails->finalamount,
                                       'currencyid'=>$bookingdata->response->calculatedDetails->currencyId,
                                       'currancyname'=>$bookingdata->response->calculatedDetails->currancyname,
                                       'booking_hold_time'=>$holdtime,
                                       'form_require'=>$is_more_informational,
                                       'form_on_orderbooking'=>$isformshow,
                                       'formtitle'=>$formtitle,
                                       'orderfrom'=>$orderfrom,
                                       'seatselected'=>$seatselected,
                                       'paybale'=>$is_pay);
                  
                return response()->json([
                       'orderformdata' => $orderformdata,
                       'eventdetail' => $dataEvent,
                       'commonfeild'=>$commonfeild,
                       'cstomfeild' => $formfeild
                       ]);
            }
            
        }
        else
        {
            $orderformdata ='notfound';
            return response()->json([
                   'orderformdata' => $orderformdata,
                   'eventdetail'=>''
                   ]);
        }
    }
    ///////// custom order form///////
    public function orderform(Request $request)
    {
      //////// check valid order/////////
      $textforbox='Price';
      $succ_mesg ='Order';
      $tickettext='e-ticket';
      $condition = array('id'=>$request->order_id,'email'=>$request->email_id,'all_info'=>1,'order_status'=>1);
      $Events_tickets_bookings =  $this->order->getBy($condition);
      $formfeild=array();
      if($Events_tickets_bookings)
      {
        $detailssubmit='No';
        $form_require='Yes';
        $orderfrom='online';
        if($Events_tickets_bookings->details_submit==1)
        {
              $detailssubmit='Yes';
              $form_require='Yes';
              if($Events_tickets_bookings->booking_from>3)
              {
                $orderfrom='offline';
              }
        }
        else
        {
            $formfeild = $this->getcustomfeilds($Events_tickets_bookings->id);
        }
        $eventotherdetails = $this->eventdetail->getBy(array('event_id'=>$Events_tickets_bookings->event_id));
        if($eventotherdetails)
        {
          $result['eventterm']=$eventotherdetails->term_condition;
          if($eventotherdetails->booknow_button_value)
          {
            $buttonValue=$eventotherdetails->booknow_button_value;
            switch ($buttonValue) {
            case 'Buy Ticket':
            $succ_mesg=$_ENV['ForBuyTicket_msg'];
            $tickettext=$_ENV['ForBuyTicket_text'];
            $textforbox=$_ENV['ForBuyTicket'];
            break;
            case 'Register':
            $succ_mesg =$_ENV['ForRegister_msg'];
            $tickettext=$_ENV['ForRegister_text'];
            $textforbox=$_ENV['ForRegister'];
            break;
            case 'Donate':
            $succ_mesg =$_ENV['ForDonate_msg'];
            $tickettext=$_ENV['ForDonate_text'];
            $textforbox=$_ENV['ForDonate'];
            break;
             
            }
          }
        }
        $formtitle=$_ENV['Formtitle'];
        /////////////set form title///////////
        $checktitle = $this->setting->getBy(array('event_id'=>$Events_tickets_bookings->event_id,'status'=>1,'type'=>5),array('value'));
        if($checktitle)
        {
          $formtitle=$checktitle->value;
        }
        $result = array('detailssubmit'=>$detailssubmit,
                        'orderfrom'=>$orderfrom,
                        'form_require'=>$form_require,
                        'formtitle'=>$formtitle,
                        'customfeilds'=>$formfeild);
        $textdispalyArray = array('textforbox'=>$textforbox,
                                  'succ_mesg'=>$succ_mesg,
                                  'ticket_text'=>$tickettext);

         return response()->json([
                     'orderformdata' => $result,
                     'textdispalyArray'=>$textdispalyArray,
                 ]);
      }
      else
      {   /////not a valid order///
          $orderformdata ='notfound';
          return response()->json([
                  'orderformdata' => $orderformdata,
                 ]);
      } 


    }
    public function fillcustomdetails(Request $request)
    {
      $checkOrderId = $this->order->getBy(array('id'=>$request->orderid,'all_info'=>1),array('event_id'));
      if($checkOrderId)
      {
        $detailsubmit = $this->savecusstomdetails($request,$request->orderid,$checkOrderId->event_id);
        $result['detailsubmit']='error';
        if($detailsubmit>0)
        {
          $update = $updatebooking = $this->order->updateorder(array('details_submit'=>1),array('id'=>$request->orderid));
          $result['detailsubmit']='Yes';
        }
      }
      else
      {
        $result['detailsubmit']='error';
      }
      
        return response()->json($result);
    }
    private function getcustomfeilds($orderid)
    {
      $formfeild = array();
      $condition = array('id'=>$orderid);
      $checkorder =  $this->order->getBy($condition);
      if($checkorder)
      {
        if($checkorder->all_info==1)
        {
            $bookingdata = json_decode($checkorder->details);
            $j = 1;
            $values1='id';
            $values2='mode_type';
            $conditionTicketList= array('event_id'=>$checkorder->event_id); 
            $ticketDatalist = $this->ticket->getListraw($conditionTicketList,$values2,$values1);
            //print_r($ticketDatalist);die;
            if(array_key_exists('ticketArray', $bookingdata->response->calculatedDetails))
            {
                $ticketArray = $bookingdata->response->calculatedDetails->ticketArray;
                foreach($ticketArray as $ticketkey=>$ticketval)
                {
                  if(array_key_exists($ticketkey,$ticketDatalist))
                  {
                    $formFor = $ticketval->selectedQuantity;//No of from genrated
                    if($checkorder->booking_from==5)
                    {
                      $conditionGroup = 'group_id="'.$checkorder->group_id.'"  and booking_from=5';
                      $checkGroupOrders = $this->order->grouporders($conditionGroup);
                      $formFor = count($checkGroupOrders);
                    }
                    for($i=1;$i<=$formFor; $i++)
                    {
                      $getTicketFeilds = $this->eventfeild->getallBy(array('ticket_id'=>$ticketkey,
                                                                           'status'=>1));
                      $incre = 1;
                      if(count($getTicketFeilds)>0)
                      {
                          foreach($getTicketFeilds as $getTicketFeilds)
                          {
                              $formfeild[$ticketkey.'_'.$ticketval->name][$j][]= array('feild_id'=>$getTicketFeilds->id,
                                                                                       'feild_name'=>$getTicketFeilds->name,
                                                                                       'feild_type'=>$getTicketFeilds->type,
                                                                                       'feild_require'=>$getTicketFeilds->mandatory,
                                                                                       'feild_default_value'=>$getTicketFeilds->multiple_value
                                                                                       );
                              $incre ++;
                          }
                          //$formfeild[$j]= $ticketArray;
                          $j++;
                      }
                      elseif($ticketDatalist[$ticketkey]==1)
                      {
                        $conditionfeildRaw='event_id='.$checkorder->event_id.' and ticket_id<=0 and status=1';
                        $getTicketFeilds = $this->eventfeild->getallByRaw($conditionfeildRaw);
                        if(count($getTicketFeilds)>0)
                        {
                          foreach($getTicketFeilds as $getTicketFeilds)
                          {
                              $formfeild[$ticketkey.'_'.$ticketval->name][$j][]= array('feild_id'=>$getTicketFeilds->id,
                                                                                       'feild_name'=>$getTicketFeilds->name,
                                                                                       'feild_type'=>$getTicketFeilds->type,
                                                                                       'feild_require'=>$getTicketFeilds->mandatory,
                                                                                       'feild_default_value'=>$getTicketFeilds->multiple_value
                                                                                       );
                              $incre ++;
                          }
                          //$formfeild[$j]= $ticketArray;
                          $j++;
                        }
                      }
                    }
                  }
                }
            }
        }
      } 
      return $formfeild;
    }

    public function makecompleteorder(Request $request)
    {
        
        $completeformData = Input::all();
        $commonObj = new Common();
        @extract($completeformData);
        //dd($completeformData);
        $condition = array('id'=>$orderid);
       

        // START -Updating the Details array of order details
        //  $checkOrderDetails = $this->order->getBy($condition);
        // if(isset($guest0) || isset($guest16) || isset($guest5))
        // {
        // $detailsArray=json_decode($checkOrderDetails->details,true);
        // // $detailsArray=(array)$detailsArray;
        // // dd($detailsArray['response']);
        // if(in_array($checkOrderDetails->event_id,Config::get('commondata.Running_Events_Kihim_Guest')))
        // {
        //   $detailsArray['response']['calculatedDetails']['guest0']=$guest0*150;
        //   $guestTotal=$guest0*150;
        // }
        // else
        // {
        //   $detailsArray['response']['calculatedDetails']['guest0']=$guest0;
        //   $detailsArray['response']['calculatedDetails']['guest5']=$guest5*200;
        //   $detailsArray['response']['calculatedDetails']['guest16']=$guest16*400;
        //   $guestTotal=$guest0 + $guest5*200 + $guest16*400;
        // }
       
        
       
        // $detailsArray['response']['calculatedDetails']['finalamount']=$detailsArray['response']['calculatedDetails']['finalamount'] + $guestTotal;
        // $detailsArray['response']['calculatedDetails']['totalAmount']=$detailsArray['response']['calculatedDetails']['totalAmount'] + $guestTotal;
        // $serializedDetailsArray=json_encode($detailsArray);
        // $updateOrder=$this->order->updateorder(array('details'=>$serializedDetailsArray),$condition);
        // }
         // END - Updating the Details array of order details


          $checkOrderId = $this->order->getBy($condition);
        if($checkOrderId )
        {

          //check user is exit or not//
          //$conditionUser = array('email'=>$Email);
          $conditioncheck = array('order_id'=>$orderid);
          $checkorderinbooking = $this->bookingDetails->getBy($conditioncheck);
          $update=0;
          
          if($checkorderinbooking)
          {
            $update=1;
          }
          if($checkorderinbooking && $checkorderinbooking->order_status=='completed')
          {
            $result['status']='completed';
          }
          else
          {
            $checkUser = $this->user->findBy('email',$Email);
           //$checkUser = User::where($conditionUser)->first();
           if($checkUser)
           {
              $userid =$checkUser->id;
           }
           else
           {
             $userDataArray = array('email'=>$Email,
                                    'name'=>$Name,
                                    'mobile'=>$Mobile);
             $makeuserEntry  =$this->user->create($userDataArray);
             $userid = $makeuserEntry;
           }
           if(Auth::check())
           {
            $LoginUser = Auth::user();
            $userid = $LoginUser->id;
           }
            $updateArray = array('user_id'=>$userid,
                                 'email'=>$Email,
                                 'name'=>$Name,
                                 'mobile'=>$Mobile,
                                 'clicked'=>1,
                                 'payment_gateway'=>$payWith); 
                                //update clicked status as well
            if($checkOrderId->booking_from==5)
            {
              $condition = array('group_id'=>$checkOrderId->group_id);
            }
            $updatebooking = $this->order->updateorder($updateArray,$condition);
            $bookingdata = json_decode($checkOrderId->details);

            $bookingdetailsArray = array('order_id'=>$orderid,
                                         'event_id'=>$checkOrderId->event_id,
                                         'show_id'=>$checkOrderId->show_id,
                                         'show_date'=>$checkOrderId->show_date,
                                         'event_type'=>$checkOrderId->event_type,
                                         'user_id'=>$checkOrderId->user_id,
                                         'email'=>$Email,
                                         'tax_id'=>$checkOrderId->tax_id,
                                         'default_tax_id'=>$checkOrderId->default_tax_id,
                                         'pass_on_amount'=>$checkOrderId->pass_on_amount,
                                         'absorb_amount'=>$checkOrderId->absorb_amount,
                                         'name'=>$Name,
                                         'mobile'=>$Mobile,
                                         'total_amount'=>$checkOrderId->total_amount,
                                         'currency_id'=>$checkOrderId->currency_id,
                                         'order_time'=>$checkOrderId->order_time,
                                         'details'=>$checkOrderId->details,
                                         'barcode'=>$orderid,
                                         'track_source_id'=>$checkOrderId->track_source_id,
                                         'total_quantity'=>$bookingdata->response->calculatedDetails->totalQuantity,
                                         'booking_from'=>$checkOrderId->booking_from,
                                         'group_id'=>$checkOrderId->group_id,
                                         'discount'=>$bookingdata->response->calculatedDetails->discount,
                                         'coupon_code'=>$checkOrderId->coupon_code,
                                         'global_discount'=>$checkOrderId->global_discount,
                                         'extra_charges'=>$checkOrderId->extra_charges,
                                         'custom_taxes'=>$checkOrderId->custom_taxes,
                                         'directdiscount'=>$checkOrderId->directdiscount,
                                         'payment_mode'=>$checkOrderId->payment_mode,
                                         'created_by'=>$checkOrderId->created_by,
                                         'send_customer_email'=>@$completeformData['sendmail'],
                                         'payment_gateway'=>$payWith,
                                         'updated_at'=>date('Y-m-d H:i:s'));




        if(isset($guest0) || isset($guest16) || isset($guest5))
        {
        
        // $detailsArray=(array)$detailsArray;
        // dd($detailsArray['response']);
      if(in_array($checkOrderId->event_id,\Config::get('commondata.Running_Events_Kihim_Guest')))
        {
          $guestArray[0]=array('name'=>'guest0','text'=>'Guest','quantity'=>$guest0,'value'=>$guest0*500);
          $guestTotal= $guest0*150;
        }
        elseif(in_array($checkOrderId->event_id,\Config::get('commondata.Running_Events_Corbet_Guest')) || in_array($checkOrderId->event_id,\Config::get('commondata.Running_Events_Himalyan_Guest')))
        {
          $guestArray[0]=array('name'=>'guest0','text'=>'Guest','quantity'=>$guest0,'value'=>$guest0*500);
          $guestTotal= $guest0*500;
        }
        elseif(in_array($checkOrderId->event_id,\Config::get('commondata.Running_Events_Living_Marathon')))
        {
          $guestArray[0]=array('name'=>'guest0','text'=>'Guest','quantity'=>$guest0,'value'=>$guest0*400);
          $guestTotal= $guest0*400;
        }
       
        else
        {
            $guestArray[0]=array('name'=>'guest0','text'=>'Guest (0-5 yrs)','quantity'=>$guest0,'value'=>0);
            $guestArray[1]=array('name'=>'guest5','text'=>'Guest (5-16 yrs)','quantity'=>$guest5,'value'=>$guest5*200);
            $guestArray[2]=array('name'=>'guest16','text'=>'Guest (16+ yrs)','quantity'=>$guest16,'value'=>$guest16*400);
            $guestTotal= $guest5*200 + $guest16*400;
        }
       
        $bookingdetailsArray['extra_services']=json_encode($guestArray);
        $bookingdetailsArray['extra_services_total']=$guestTotal;


        }




              $result['payment_mode']= $checkOrderId->payment_mode;
              $result['event_id']= $checkOrderId->event_id;
                  if($checkOrderId->booking_from==5)
                  {
                     $conditionGroup = 'group_id="'.$checkOrderId->group_id.'"  and booking_from=5';
                     $checkGroupOrders = $this->order->grouporders($conditionGroup);
                     if(count($checkGroupOrders)>0)
                     {
                        $i=1;
                        foreach($checkGroupOrders as $checkGroupOrders)
                        {
                          $bookingdetailsArray['order_status']='completed';
                          $bookingdetailsArray['order_id']=$checkGroupOrders->id;
                          $bookingdetailsArray['barcode']=$checkGroupOrders->id;
                          $checkorderinbooking = $this->bookingDetails->getBy(array('order_id'=>$checkGroupOrders->id));
                          if($checkorderinbooking)
                          {
                            $savebookingDetails = $this->bookingDetails->update($bookingdetailsArray,$checkGroupOrders->id,'order_id');
                          }
                          else
                          {
                            $savebookingDetails = $this->bookingDetails->create($bookingdetailsArray);
                          }
                            $bookingdata = json_decode($checkGroupOrders->details);
                            if(array_key_exists('ticketArray', $bookingdata->response->calculatedDetails))
                            {
                                $ticketArray = $bookingdata->response->calculatedDetails->ticketArray;
                                if(count($ticketArray)>0)
                                {

                                    foreach($ticketArray as $ticketkey=>$ticketval)
                                    {
                                      $conditionbreakage = array('order_id'=>$checkGroupOrders->id,'ticket_id'=>$ticketkey);
                                      $checkinbreakage = $this->orderbreakage->getBy($conditionbreakage);
                                      if($checkinbreakage)
                                      {
                                        $updateArray = array('quantity'=>$ticketval->selectedQuantity,
                                                             'amount'=>$ticketval->price,
                                                             'ticket_id'=>$ticketkey,
                                                             'event_id'=>$checkGroupOrders->event_id,
                                                             'show_id'=>$checkGroupOrders->show_id,
                                                             'show_date'=>$checkGroupOrders->show_date,
                                                             'discount'=>$ticketval->discount,
                                                             'coupon_code'=>$ticketval->coupen);
                                        $Orderbreakage = $this->orderbreakage->updatebrakage($updateArray,$conditionbreakage);
                                      }
                                      else
                                      {
                                        $OrderbreakageArray = array('order_id'=>$checkGroupOrders->id,
                                                                    'ticket_id'=>$ticketkey,
                                                                    'event_id'=>$checkGroupOrders->event_id,
                                                                    'quantity'=>$ticketval->selectedQuantity,
                                                                    'amount'=>$ticketval->price,
                                                                    'show_id'=>$checkGroupOrders->show_id,
                                                                    'show_date'=>$checkGroupOrders->show_date,
                                                                    'discount'=>$ticketval->discount,
                                                                    'coupon_code'=>$ticketval->coupen);
                                        $Orderbreakage = $this->orderbreakage->create($OrderbreakageArray);
                                      }
                                        
                                    }
                                }
                            }
                                 //dd($request->feild);
                            $detailsubmit = $this->savecusstomdetails($request,$checkGroupOrders->id,$checkGroupOrders->event_id);
                          $i++;
                        }
                     }
                  }
                  else 
                  {
                    if($update==0)
                    {
                      if($checkOrderId->payment_mode == 2 || ($checkOrderId->is_free==1 && $checkOrderId->total_amount==0))
                      {
                        $bookingdetailsArray['order_status']='completed';
                      }
                      $savebookingDetails = $this->bookingDetails->create($bookingdetailsArray);
                      if($checkOrderId->coupon_code!='' && $checkOrderId->total_amount<=0)
                      {
                        $updatecoupon= $this->updatecoupon($request);
                      }
                      if(isset($request->seatplan))
                      {
                        $seatselected = implode(',',$request->seatplan);
                        $seatPlan = array('order_id'=>$orderid,
                                           'event_id'=>$checkOrderId->event_id,
                                           'show_date'=>$checkOrderId->show_date,
                                           'seat_selected'=>$seatselected);
                        $this->seatplan->create($seatPlan);
                      }
                    }
                    else
                    {
                      $savebookingDetails = $this->bookingDetails->update($bookingdetailsArray,$orderid,'order_id');
                    }
                    //process the order data diff slaves wise//
                    $bookingdata = json_decode($checkOrderId->details);
                    if($checkOrderId->booking_from!=5)
                    {
                      if(array_key_exists('ticketArray', $bookingdata->response->calculatedDetails))
                      {
                          $ticketArray = $bookingdata->response->calculatedDetails->ticketArray;
                          if(count($ticketArray)>0)
                          {
                              foreach($ticketArray as $ticketkey=>$ticketval)
                              { 
                                $conditionbreakage = array('order_id'=>$orderid,'ticket_id'=>$ticketkey);
                                $checkinbreakage = $this->orderbreakage->getBy($conditionbreakage);
                                if($checkinbreakage)
                                {
                                  $updateArray = array('quantity'=>$ticketval->selectedQuantity,
                                                       'ticket_id'=>$ticketkey,
                                                       'amount'=>$ticketval->price,
                                                       'pass_on_amount'=>$ticketval->passonfee,
                                                       'absorb_amount'=>$ticketval->absorvefee,
                                                       'payment_gateway_fee'=>$ticketval->pg_charges,
                                                       'goevent_fee'=>$ticketval->goeventz_fee,
                                                       'per_ticket_fee'=>$ticketval->per_ticket_fee,
                                                       'event_id'=>$checkOrderId->event_id,
                                                       'show_id'=>$checkOrderId->show_id,
                                                       'show_date'=>$checkOrderId->show_date,
                                                       'discount'=>$ticketval->discount,

                                                       'pg_charges'=>$ticketval->pg_charges,
                                                       'goeventz_fee'=>$ticketval->goeventz_fee,
                                                       'absorvefee'=>$ticketval->absorvefee,
                                                       'passonfee'=>$ticketval->passonfee,
                                                       'goeventpasson'=>$ticketval->goeventpasson,
                                                       'goeventabsorve'=>$ticketval->goeventObserve,
                                                       'pgFeeAbsorve'=>$ticketval->pgFeeObserve,
                                             
                                                       'pgFeePasson'=>$ticketval->pgFeePasson,
                                                   

                                                       'serviceTaxAbsorve'=>$ticketval->servicetaxobserv,
                                                       'serviceTaxPasson'=>$ticketval->servicetaxpass,
                                                       'totalServiceTax'=>$ticketval->totalServiceTax,

                                                       'coupon_code'=>$ticketval->coupen);
                                  $Orderbreakage = $this->orderbreakage->updatebrakage($updateArray,$conditionbreakage);
                                }
                                else
                                {
                                  $OrderbreakageArray = array('order_id'=>$orderid,
                                                              'ticket_id'=>$ticketkey,
                                                              'pass_on_amount'=>$ticketval->passonfee,
                                                              'absorb_amount'=>$ticketval->absorvefee,
                                                              'payment_gateway_fee'=>$ticketval->pg_charges,
                                                              'goevent_fee'=>$ticketval->goeventz_fee,
                                                              'per_ticket_fee'=>$ticketval->per_ticket_fee,
                                                              'event_id'=>$checkOrderId->event_id,
                                                              'quantity'=>$ticketval->selectedQuantity,
                                                              'amount'=>$ticketval->price,
                                                              'show_id'=>$checkOrderId->show_id,
                                                              'show_date'=>$checkOrderId->show_date,
                                                              'discount'=>$ticketval->discount,
                                                               'pg_charges'=>$ticketval->pg_charges,
                                                               'goeventz_fee'=>$ticketval->goeventz_fee,
                                                               'absorvefee'=>$ticketval->absorvefee,
                                                               'passonfee'=>$ticketval->passonfee,
                                                               'goeventpasson'=>$ticketval->goeventpasson,
                                                               'goeventabsorve'=>$ticketval->goeventObserve,
                                                               'pgFeeAbsorve'=>$ticketval->pgFeeObserve,
                                                               'pgFeePasson'=>$ticketval->pgFeePasson,

                                                               'serviceTaxAbsorve'=>$ticketval->servicetaxobserv,
                                                               'serviceTaxPasson'=>$ticketval->servicetaxpass,
                                                               'totalServiceTax'=>$ticketval->totalServiceTax,

                                                              'coupon_code'=>$ticketval->coupen);
                                  $Orderbreakage = $this->orderbreakage->create($OrderbreakageArray);
                                }
                              }
                          }
                      }
                      $detailsubmit = $this->savecusstomdetails($request,$orderid,$checkOrderId->event_id);
                      //dd($detailsubmit);
                    }
                  }
                  if($detailsubmit>0)
                  {
                     $updatebooking = $this->order->updateorder(array('details_submit'=>1),$condition);
                  }
                  if($checkOrderId->payment_mode == 2 || ($checkOrderId->is_free==1 && $checkOrderId->total_amount==0))
                  {
                    $result['status']='free';
                    $result['payment_mode']=$checkOrderId->payment_mode;
                    $updateholdticket_time = $this->updateholdticket_time($orderid,$bookingdata->response->calculatedDetails);
                  }
                  else
                  { 

         if($checkOrderId->currency_id==1 ) // making sure that INR currency is not passed for stripe or paypal payment
            {

              if($payWith==3 || $payWith==4)
              {
            $result['status']='error';
            return response()->json($result);
            }
            }
            else // making sure that other than INR currency is handled by stripe or paypal pg
            {
              if($payWith==1 || $payWith==2 || $payWith==5)
              {
                $result['status']='error';
                return response()->json($result);
              }
            }


                    if($payWith==4)
                    {


                    $currencycondition = array('id'=>$checkOrderId->currency_id);
        $currencydetails = Currency::select('code')->where($currencycondition)->first(); 
        $currencyCode=0;
        if($currencydetails)
         { $currencyCode=$currencydetails->code;
          $result['currency']=$currencyCode;
          }

        if(in_array($currencyCode,config('commondata.STRIPE_ZERO_DECIMAL_CURRENCIES')))
          $baseCurrencyAmount=$checkOrderId->total_amount;
        else
          $baseCurrencyAmount=$checkOrderId->total_amount*100;
        

                    $result['amount']=$baseCurrencyAmount;
                    $result['email']=$Email;

        }

                            if($payWith==5)
                    {

                      if(isset($bookingdetailsArray['extra_services_total']))
          $baseCurrencyAmount=($checkOrderId->total_amount+$bookingdetailsArray['extra_services_total'])*100;
        else
          $baseCurrencyAmount=($checkOrderId->total_amount)*100;
        

                    $result['amount']=$baseCurrencyAmount;
                    $result['email']=$Email;

        }

                    $result['status']='payment';

                  }
          }


     
            return response()->json($result);
        }
        else
        {
            $result['status']='error';
            return response()->json($result);
        }
    }
    private function  savecusstomdetails($request,$orderid,$eventid)
    {
      $detailsubmit=0;
      if($request->feild)
      {
          foreach($request->feild as $key=>$val)
          {
            foreach($val as $keyfeild=>$allvalues)
            {
              foreach($allvalues as $keystore=>$valuestore)
              {
                 $savevalue=$valuestore;
                 if(is_array($valuestore))
                 {
                  $savevalue=implode(',', $valuestore);

                 }
                 $EventcustomfieldsvalueArray = array('order_id'=>$orderid,
                                                      'event_id'=>$eventid,
                                                      'ticket_id'=>$key,
                                                      'event_custom_fields_id'=>$keyfeild,
                                                      'value'=>$savevalue,
                                                      'position'=>$keystore);
                 $Eventcustomfieldsvalue = $this->customfieldsvalue->create($EventcustomfieldsvalueArray);
                 $detailsubmit++;
              }
            }
          
          }
      }
      //if coustom data is comming//
      if($request->filearray)
      {
       // dd($request->filearray);
        foreach($request->filearray as $filekey=>$fileval)
        {

          foreach($fileval as $filefeild=>$allvaluesfile)
          {
                 
            foreach($allvaluesfile as $filekeystore=>$filevaluestore)
            {

               //$destinationPath = '../uplode';
               //$filename = $filevaluestore->getClientOriginalName(); 
               //$upload_success = $filevaluestore->move($destinationPath, $filename);
               $image  = $filevaluestore->getClientOriginalName(); 
               $destinationPath = 'event/'.$eventid.'/customfeild'; // upload path
               $extension = $filevaluestore->getClientOriginalExtension(); // getting image extension
               $fileName = 'event_'.time().'.'.$image; // renameing image
              // $request->file('event_image')->move($destinationPath, $fileName); // uploading file to given path

                $inputArray=array(
                'ContentType' =>'image/'.$filevaluestore->guessClientExtension(),
                'Key'        => $destinationPath.'/'. $fileName,
                'SourceFile' => $filevaluestore->getPathname(),   //storage_path().'/files/icons-390.jpg',

                );
               $upload_success= $this->s3->upload($inputArray);
               if($upload_success)
               {
                  $EventcustomimagesvalueArray = array('order_id'=>$orderid,
                                                       'event_id'=>$eventid,
                                                       'ticket_id'=>$filekey,
                                                       'event_custom_fields_id'=>$filefeild,
                                                       'value'=>$fileName,
                                                       'position'=>$filekeystore);
                  $Eventcustomfieldsvalue = $this->customfieldsvalue->create($EventcustomimagesvalueArray);
                  $detailsubmit++;
               }
            }
          }
        }
      }
      
        return $detailsubmit;
    }

    public function ordersuccess(Request $request)
    {
                                                         
      $textforbox='Price';
      $succ_mesg ='Order';
      $tickettext='e-ticket';
      $eventtype=0;
      $completeformData = Input::all();
      @extract($completeformData);
      $redirectto='';//// redirect after booking from ticket widget
      //echo $request->order_id;
      $checkOrderCon = array('order_id'=>$request->order_id , 'order_status'=>'completed');
      if($request->email_id!='')
      {
        $checkOrderCon = array('order_id'=>$request->order_id,'email'=>$request->email_id);
      }
      $checkOrder = $this->bookingDetails->getBy($checkOrderCon);
      $result='';
      if($checkOrder)
      {
          if($checkOrder->event_id==$_ENV['CUSTOM_EVENT_ID'])
          {
            $condition = array('id'=>$checkOrder->event_id);
          }else
          $condition = array('id'=>$checkOrder->event_id);
          $eventDetails = $this->event->getBy($condition);
          $commonObj = new Common();
          $eventotherdetails = $this->eventdetail->getBy(array('event_id'=>$checkOrder->event_id));
                    //ConvertGMTToLocalTimezone//
          $gettimezone = $this->timezone->find($eventDetails->timezone_id);
          $timezonename = $commonObj->gettimezonename($gettimezone->timezone);
          $getstartdate = $commonObj->ConvertGMTToLocalTimezone($eventDetails->start_date_time,$gettimezone->timezone);
          $getenddate   = $commonObj->ConvertGMTToLocalTimezone($eventDetails->end_date_time,$gettimezone->timezone);
          $ordertime   = $commonObj->ConvertGMTToLocalTimezone($eventDetails->order_time,'Asia/Kolkata');
          $bookingdata = json_decode($checkOrder->details);
          $imageUrl='';
          if($eventDetails->banner_image)
          {
            //$imageUrl = URL::asset('uplode/'.$eventDetails->user_id.'/'.$eventDetails->banner_image);
            $imageUrl = $_ENV['CF_LINK'].'/event/'.$checkOrder->event_id.'/banner/'.$eventDetails->banner_image;
          }
          if($checkOrder->booking_from==1)
          {
            $conditionRedirect = array('event_id'=>$checkOrder->event_id);
            $checkRedirect = $this->ticketwidget->getBy($conditionRedirect,array('redirect_url'));
            if($checkRedirect)
            {
              $redirectto=$checkRedirect->redirect_url;
            }

          }
           //event user details//
          $bookingData = json_decode($checkOrder->details);
          $eventUser = $this->user->find($eventDetails->user_id);
          $eventshowdetails = '';
          $eventshowdate = '';
          $showtime='';
          if($checkOrder->show_date && $checkOrder->event_type!=2)
          {
            $eventtype=1;
            $eventshowdate = $checkOrder->show_date;
            $showCondition = array('id'=>$checkOrder->show_id);
            $eventshowdetailsdata = $this->recurringevent->getBy($showCondition,array('name','start_time','end_time'));
            if($eventshowdetailsdata)
            {
              $showtime=$eventshowdetailsdata->start_time;
              $eventshowdetails=$eventshowdetailsdata;
            }
          }
          //$eventUser = User::where('id',$eventDetails->user_id)->first();
                 //event detail array//
          if($eventDetails->no_dates==1)
          {
             //$eventcreateType='withoutdate';
             if($eventDetails->start_date_time=='' || $eventDetails->start_date_time=='0000-00-00 00:00:00')
             {
               $getstartdate='';

             }
          }
          $extraEmail = array($eventUser->email);
          $getExtraemails = $this->setting->getBy(array('event_id'=>$eventDetails->id,
                                                        'type'=>2),array('value'));
          if($getExtraemails)
          {
            $mails=explode(',', $getExtraemails->value);
            foreach ($mails as $mails) 
            {
              array_push($extraEmail, $mails);
            }

          }
                       ////////checkassign//
          $conditionUser = array('userassigns.status'=>1,'user_id'=>$eventDetails->user_id);
          $allassignusers = $this->userassign->getallDetails($conditionUser,array('name','admin_user_id','user_id','email'));
          if(count($allassignusers)>0)
          {
            foreach($allassignusers as $allusers)
            {
              array_push($extraEmail, $allusers->email);
            }
          }
          $seatselected='';
          if($eventDetails->id==env('CUSTOM_SEAT_EVENTID'))
          {
            $checkseat = $this->seatplan->getBy(array('order_id'=>$request->order_id));
            if($checkseat)
            {
              $seatselected = $checkseat->seat_selected;
            }

          }
          if(isset($ENV["APP_HOST"]))
            $serverType = $ENV["APP_HOST"];
          else
            $serverType = "DEV";

          $result = array('orderid'=>$request->order_id,
                          'eventid'=>$eventDetails->id,
                          'eventtype'=>$eventtype,
                          'serverType'=>$serverType,
                          'eventshow'=>$showtime,
                          'eventname'=>$eventDetails->title,
                          'eventimage'=>$imageUrl,
                          'eventvenue'=>$eventDetails->venue_name,
                          'eventaddress1'=>$eventDetails->address1,
                          'eventdescription'=>$eventDetails->description,
                          'eventcity'=>$eventDetails->city,
                          'eventstate'=>$eventDetails->state,
                          'eventcountry'=>$eventDetails->country,
                          'eventstartdate'=>$getstartdate,
                          'eventbarcode'=>$checkOrder->barcode,
                          'eventenddate'=>$getenddate,
                          'eventtimezone'=>$gettimezone->timezone,
                          'timezonename'=>$timezonename,
                          'shedulename'=>'',
                          'seatselected'=>$seatselected,
                          'eventurl'=>$commonObj->cleanURL($eventDetails->url),
                          'bookingdata'=>$bookingdata->response->calculatedDetails,
                          'eventmapurl'=>$eventDetails->map_url,
                          'bookinguseremailid'=>$checkOrder->email,
                          'bookingusername'=>$checkOrder->name,
                          'bookingusermobile'=>$checkOrder->mobile,
                          'organisername'=> $eventUser->name,
                          'redirectto'=> $redirectto,
                          'user_id'=>$eventDetails->user_id,
                          'organiseremail'=> $eventUser->email,
                          'organisermobile'=> $eventUser->mobile,
                          'sendorganiseremails'=> $extraEmail,
                          'eventshowdate'=> $eventshowdate,
                          'eventshowdetails'=> $eventshowdetails,
                          'discount'=> $checkOrder->discount,
                          'coupon_code'=> $checkOrder->coupon_code,
                          'payment_mode'=> $checkOrder->payment_mode,
                          'group_id'=> $checkOrder->group_id,
                          'sendEmailCustomer'=> $checkOrder->send_customer_email,
                          'orderdetails'=>$bookingData->response->calculatedDetails,
                          'directdiscount'=>$checkOrder->directdiscount,
                          'extra_services'=>json_decode($checkOrder->extra_services,true),
                          'extra_services_total'=>$checkOrder->extra_services_total,
                          'ordertime'=>$ordertime,
                          'transactionid'=>$checkOrder->transaction_id,
                          'totalamountpayble'=>$checkOrder->total_amount,
                          'amountpayble'=>$checkOrder->total_amount-$checkOrder->extra_charges,
                          'globaldiscount'=>$checkOrder->global_discount);
                          $result['eventterm']="";
                          $result['Group_orderId']='';
                          ///////////make form for details//////
                          $feildsArray = array();
                          $form_require='No';
                          $detailssubmit = 'No';
                          $orderfrom='online';
                          $result['formtitle']=$_ENV['Formtitle'];
                          $checkformstaus = $this->order->getBy(array('id'=>$request->order_id));
                          if($checkformstaus->all_info==1)
                          {
                            
                            /////////////set form title///////////
                            $checktitle = $this->setting->getBy(array('event_id'=>$eventDetails->id,'status'=>1,'type'=>5),array('value'));
                            if($checktitle)
                            {
                              if($checktitle->value!='')
                              {
                                $result['formtitle']=$checktitle->value;
                              }
                            }
                            if($checkformstaus->booking_from>3)
                            {
                              $orderfrom='offline';
                            }
                            if($checkformstaus->details_submit==1)
                            {
                              $detailssubmit='Yes';
                            }
                            else
                            {
                              $form_require='Yes';
                              $feildsArray = $this->getcustomfeilds($request->order_id);
                            }
                          }
                          $result['form_require']=$form_require;
                          $result['detailssubmit']=$detailssubmit;
                          $result['orderfrom']=$orderfrom;
                          $result['formfeilds']=$feildsArray;
                          $OrderIdArray = array();
                          if($checkOrder->booking_from==5)
                          {
                            
                            $conditionGroup = 'group_id="'.$checkOrder->group_id.'" and booking_from=5';
                            $checkGroupOrders = $this->bookingDetails->grouporders($conditionGroup);
                            if(count($checkGroupOrders)>0)
                            {
                              foreach($checkGroupOrders as $checkGroupOrders)
                              {
                                $OrderIdArray[]=$checkGroupOrders->order_id;
                              }
                            }
                               $result['Group_orderId'] = $OrderIdArray;
                          }
                          if($checkOrder->event_type==2)
                          {
                            $checkslot = $this->slotlist->find($checkOrder->show_id);
                            if($checkslot)
                            {
                              $getstartdate = $commonObj->ConvertGMTToLocalTimezone($checkslot->start_date_time,$gettimezone->timezone);
                              $getenddate   = $commonObj->ConvertGMTToLocalTimezone($checkslot->end_date_time,$gettimezone->timezone);
                              $result['eventstartdate'] = $getstartdate;
                              $result['eventenddate'] = $getenddate;
                              $getshedule = $this->schedule->find($checkslot->schedule_id);
                              if($getshedule)
                              {
                                $result['shedulename'] = $getshedule->schedule_name;
                                $result['eventvenue'] = $getshedule->venue_name;
                                $result['eventaddress1'] = $getshedule->address1;
                                $result['eventcity'] = $getshedule->city;
                                $result['eventstate'] = $getshedule->state;
                                $result['eventcountry'] = $getshedule->country;
                              }
                            }
                          }
          
                          if($eventotherdetails)
                          {
                            //$result['eventterm']=$eventotherdetails->term_condition;
                            if($eventotherdetails->booknow_button_value)
                            {
                              $buttonValue=$eventotherdetails->booknow_button_value;
                              switch ($buttonValue) {
                              case 'Buy Ticket':
                              $succ_mesg=$_ENV['ForBuyTicket_msg'];
                              $tickettext=$_ENV['ForBuyTicket_text'];
                              $textforbox=$_ENV['ForBuyTicket'];
                              break;
                              case 'Register':
                              $succ_mesg =$_ENV['ForRegister_msg'];
                              $tickettext=$_ENV['ForRegister_text'];
                              $textforbox=$_ENV['ForRegister'];
                              break;
                              case 'Donate':
                              $succ_mesg =$_ENV['ForDonate_msg'];
                              $tickettext=$_ENV['ForDonate_text'];
                              $textforbox=$_ENV['ForDonate'];
                              break;
                               
                              }
                            }
                          }
                          //////check tabs data////
                          $getterm = $this->tabs->getBy(array('event_id'=>$eventDetails->id,'tabfor'=>4,'status'=>1),array('tabcontent','tabname'));
                          if($getterm)
                          {
                             $result['eventterm']=array('content'=>$getterm->tabcontent,
                                                        'labelname'=>$getterm->tabname);

                          }
                           //get event all custom feild//
                         $customfeildData ='';
                         $customdetailsData='';
                         if($bookingData->response->calculatedDetails->formRequired==1)
                         {
                           $getorderattendeinfo = $this->getattendeinfo($request->order_id,$eventDetails->id);
                           if($checkOrder->booking_from==5)
                           {
                             $getorderattendeinfo = $this->getofflineattendeinfo($OrderIdArray,$eventDetails->id);
                           }
                           $customfeildData = $getorderattendeinfo['customfeild'];
                           $customdetailsData = $getorderattendeinfo['customdetails'];
                         }
                         ////////custom details///////////
                        if($_ENV['CUSTOMTAX_RECEIPT_EVENTID']==$eventDetails->user_id)
                        {
                          $result['ordernumber']='';
                          $selectcolumns = "count(id) as ordernumber";
                          $condition = "event_id='".$checkOrder->event_id."' and id>='".$checkOrder->id."' and order_status='completed'";
                          $getordernumber = $this->bookingDetails->getByraw($condition,$selectcolumns);
                          if($getordernumber)
                          {
                             $result['ordernumber']=$getordernumber->ordernumber;
                          }
                        }

                      $checkGeoData =    $this->geoEcommerce->getBy(array('order_id'=>$request->order_id));
                      if(count($checkGeoData)>0){
                        $result['geoDataFlag'] = 0;
                      } else {

                        $this->geoEcommerce->create(array('order_id'=>$request->order_id));
                        $result['geoDataFlag'] = 1;
                      }

         $textdispalyArray = array('textforbox'=>$textforbox,
                                  'succ_mesg'=>$succ_mesg,
                                  'ticket_text'=>$tickettext);         
          return response()->json(['eventdetails' => $result,
                                   'textdispalyArray'=>$textdispalyArray,
                                   'customfeild'=>$customfeildData,
                                   'customdetails' => $customdetailsData]);
     }
      else
      {
         
          $result='error';
          return response()->json(['eventdetails' => $result]);
      }
        
    }

     public function orderresume(Request $request)
    {
       
        $orderData = $this->bookingDetails->getBy(array('order_id'=>$request->order_id),array('order_id','email','event_id','name'));
        $eventData = $this->event->getBy(array('id'=>$orderData->event_id),array('url','id','title'));
        $result = array();
        
        if(count($orderData)>0){

          if(count($eventData)>0){

            $result['order_id'] = $orderData->order_id;
            $result['email'] = $orderData->email;
            $result['event_id'] = $orderData->event_id;
            $result['name'] = $orderData->name;
            $result['url'] = $eventData->url;
            $result['event_id'] = $eventData->id;
            $result['title'] = $eventData->title;
          
          } else {
            $result='error';
          }

        } else {
          $result='error';
        }
        
        return response()->json(['eventdetails' => $result]);
    }

  //////////// resume order for complete transaction/////
  public function resumetransaction(Request $request)
  {
    $status['status']='error';
    $conditioncheck = array('id'=>$request->order_id);
    $getOrderType = $this->order->getBy($conditioncheck,array('event_id','clicked','details','order_status','hold_time','order_time'));
    if($getOrderType)
    {  
      if($getOrderType->order_status==1)
      {
        $status['status']='completed';

      }
      else
      {
        $date1timestamp = strtotime(date('Y-m-d H:i:s'));
        $date2timestamp = strtotime($getOrderType->order_time);
        $timediffrence = round(($date1timestamp - $date2timestamp));
        $holdtime=0;
        $remainingtime = $getOrderType->hold_time - $timediffrence;
        if($remainingtime>0)
        {
          $holdtime = $remainingtime;
        }
        if($holdtime>0)
        {
          $updateOrderArray = array('on_hold'=>1,'hold_time'=>1200,'order_time'=>date('Y-m-d H:i:s'));
          $update =  $this->order->updateorder($updateOrderArray,array('id'=>$request->order_id));
          if($update)
          {
            $status['status']='makeredirect';
          }
          
        }
        else
        {
          $Arrayticket = array();
          $ticketArrayList = $this->ticket->getallBy(array('event_id'=>$getOrderType->event_id),array('id','on_hold'));
          foreach($ticketArrayList as $ticketArrayList)
          {
            $Arrayticket[$ticketArrayList->id] = $ticketArrayList->on_hold;
          }
     
          $bookingdata = json_decode($getOrderType->details);
          $ticketArrayData = $bookingdata->response->calculatedDetails;
          if(array_key_exists('ticketArray', $ticketArrayData))
          {
            $ticketArray = $ticketArrayData->ticketArray;
            if(count($ticketArray)>0)
            {
                foreach($ticketArray as $ticketkey=>$ticketval)
                {
                  if(is_numeric($ticketkey))
                  {
                     if(array_key_exists($ticketkey, $Arrayticket))
                     {
                        if(isset($ticketval->selectedQuantity))
                        {
                          $selectedTicket = $ticketval->selectedQuantity;
                         if($selectedTicket>$Arrayticket[$ticketkey])
                         {
                           $updateTicketon_hold = $this->ticket->updateticket(array('on_hold'=>$selectedTicket),array('id'=>$ticketkey));
                         }
                         else
                         {
                           $updateTicketon_hold = $this->ticket->increment($ticketkey,'on_hold',$selectedTicket);
                         }
                        }
                                      //update onhold values//
                     }
                  }
                   //check total sold ticket and quantity//
                }
            }
          }
          $updateOrderArray = array('on_hold'=>1,'hold_time'=>1200,'order_time'=>date('Y-m-d H:i:s'));
          $update =  $this->order->updateorder($updateOrderArray,array('id'=>$request->order_id));
          if($update)
          {
            $status['status']='makeredirect';
          }
        }
      }
    }
    return response()->json($status);
    
  }
    public function sendsmsandmail(Request $request)
    {
       /////////////send mail and sms for booking from android///
      if($request->order_id)
      {
        $orderid =$request->order_id;
        $commonObj= new Common();
        $checkOrderCon = array('order_id'=>$request->order_id , 'order_status'=>'completed');
        $checkOrder = $this->bookingDetails->getBy($checkOrderCon);
        if($checkOrder)
        {
          $condition = array('id'=>$checkOrder->event_id);
          $eventDetails = $this->event->getBy($condition);
          $bookingdata = json_decode($checkOrder->details);
          $checkMail = $this->mail->getBy(array('order_id'=>$orderid,'type'=>'order'));
          if($checkMail)
          {
            if(isset($request->resend))
            {
              $mail = $commonObj->sendmailcommon(1,$orderid);
            }
            $update = $this->mail->updatemail(array('status'=>1),array('order_id'=>$orderid,'type'=>'order'));
          }
          else
          {
              //$eventidArray=array('573','576','575','574');
              $mail = $commonObj->sendmailcommon(1,$orderid,1);
              if($mail==1)
              {
                $insertMail = $this->mail->create(array('order_id'=>$orderid,'status'=>1,'type'=>'order'));
              }
              $numbers = $checkOrder->mobile;; // A single number or a comma-seperated list of numbers
              $message = "Booking confirmed for event : ".$eventDetails->title.",
Order ID: ".$orderid.",
Quantity: ".$bookingdata->response->calculatedDetails->totalQuantity."
Cheers! www.goeventz.com";
              // A single number or a comma-seperated list of numbers
              $message = urlencode($message);
              $sensms = $commonObj->sendsms($numbers,$message);

          }
          $result='success';
          return response()->json(['status' => $result]);
        }
        else
        {
          $result='error';
          return response()->json(['status' => $result]);

        }
      }
      else
      {
        $result='error';
        return response()->json(['status' => $result]);
      }
    }
    private function getofflineattendeinfo($OrderIdArray,$eventid)
    {

        $customfeildArray=array();
        $customdetails=array(); 
        $feildArray = array();   
        $customfeilddetails=array();    
        $customfeilds = $this->eventfeild->getallBy(array('event_id'=>$eventid),array('name','id','ticket_id','type'));
        foreach($customfeilds as $customfeilds)
        {
          //$feildArray[]=$customfeilds->id;
          $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,'type'=>$customfeilds->type);

        }
         ///////get order attendeeinfo//////////
        $orderidoffline  = array_values($OrderIdArray)[0]; 
        $getorderinfo = $this->customfieldsvalue->groupByall(array('order_id'=>$orderidoffline),array('ticket_id','order_id','event_custom_fields_id'),'event_custom_fields_id');
        if(count($getorderinfo)>0)
        {
          foreach($getorderinfo as $getorderinfo)
          {
            $feildName='';
            $feildtype='';
            $feildArray[]=$getorderinfo->event_custom_fields_id;
            if(array_key_exists($getorderinfo->event_custom_fields_id, $customfeilddetails))
            {
              $feildName = $customfeilddetails[$getorderinfo->event_custom_fields_id]['name'];
              $feildtype= $customfeilddetails[$getorderinfo->event_custom_fields_id]['type'];
            }
            $customfeildArray[$getorderinfo->ticket_id][$getorderinfo->event_custom_fields_id]=array('name'=>$feildName,
                                                                                                     'type'=>$feildtype);

          }
        }

          //////////////////////// feild value //////////////

        $feildvalues = array();
        // $conditionfeild = array('order_id'=>$orderbookingDetails->order_id);
        foreach($OrderIdArray as $orderid)
        {
          $getorderticket = $this->customfieldsvalue->DetailsByIdorder($feildArray,array('order_id'=>$orderid),array('ticket_id','order_id','event_custom_fields_id','value','position'));
          if(count($getorderticket)>0)
          {
            foreach($getorderticket as $getorderticket)
            {
              
              if(array_key_exists($getorderticket->event_custom_fields_id, $customfeildArray[$getorderticket->ticket_id]))
              {
                
                if($customfeildArray[$getorderticket->ticket_id][$getorderticket->event_custom_fields_id]['type']=='file')
                {
                  $feildvalues[$getorderticket->order_id][$getorderticket->ticket_id][$getorderticket->position][$getorderticket->event_custom_fields_id]= $imageUrl = $_ENV['CF_LINK'].'/event/'.$eventid.'/customfeild/'.$getorderticket->value;

                }
                else
                {
                  $feildvalues[$getorderticket->order_id][$getorderticket->ticket_id][$getorderticket->position][$getorderticket->event_custom_fields_id]= $getorderticket->value; 
                }
                 
              }
              $customdetails = $feildvalues;

            }

          }
          if(!array_key_exists($orderid, $customdetails))
          {
            $customdetailsArray[$orderid] ='';
          }
          else
          {
            $customdetailsArray[$orderid] = $customdetails[$orderid];
          }

        }
        
         $customData = array('customfeild'=>$customfeildArray,'customdetails'=>$customdetailsArray);
          return $customData;

    }

    public function updateholdtime(Request $request)
    {
        if($request->ajax())
        {
          $conditioncheck = array('id'=>$request->orderid ,'on_hold'=>1);
          $getOrderType = $this->order->getBy($conditioncheck);
          if($getOrderType)
          {
            if($getOrderType->on_hold==1)
            {
              $bookingdata = json_decode($getOrderType->details);
              $ticketArrayData = $bookingdata->response->calculatedDetails;
              if(array_key_exists('ticketArray', $ticketArrayData))
              {
                $ticketArray = $ticketArrayData->ticketArray;
                if(count($ticketArray)>0)
                {
                    foreach($ticketArray as $ticketkey=>$ticketval)
                    {
                       //check total sold ticket and quantity//
                       $ticketData = $this->ticket->getticket(array('id'=>$ticketkey));
                       $selectedTicket = $ticketval->selectedQuantity;
                       if($getOrderType->booking_from==5)
                       {
                         $countOrder = $this->order->getallBy(array('group_id'=>$getOrderType->group_id),array('id'));
                         $selectedTicket = count($countOrder);
                       }
                                        //update onhold values//
                      if($selectedTicket>$ticketData->on_hold)
                      {
                        $updateTicketon_hold = $this->ticket->updateticket(array('on_hold'=>0),array('id'=>$ticketkey));
                      }
                      else
                      {
                        $updateTicketon_hold = $this->ticket->decrement($ticketkey,'on_hold',$selectedTicket);
                      }
                    }
                }
              }
              
              if($getOrderType->booking_from==5)
              {
                 $updateOrderArray = array('on_hold'=>0,'hold_time'=>0);
                 $update =  $this->order->updateorder($updateOrderArray,array('group_id'=>$getOrderType->group_id));
              }
              else
              {
                $update =  $this->order->updateorder(array('on_hold'=>0,'hold_time'=>0),array('id'=>$request->orderid));
              }
              
              // $update = Orderdetail::where('order_id',$request->orderid)->update(array('on_hold'=>0,'hold_time'=>0));
              if($update)
              {
                  
                return 'success';
              }
              else
              {
                 return 'error';
              }

            }
            else
            {
              return 'success';
            }

          }
          else
          {
             return 'error';
          }
         
        }
        else
        {
           return 'error';
        }
    }


    public function updateholdticket_time($orderid , $ticketArrayData)
    {
       
      $conditioncheck = array('id'=>$orderid,'on_hold'=>1);
      $getOrderType = $this->order->getBy($conditioncheck);
      if($getOrderType)
      {
        if(array_key_exists('ticketArray', $ticketArrayData))
        {
          $ticketArray = $ticketArrayData->ticketArray;
          if(count($ticketArray)>0)
          {
              foreach($ticketArray as $ticketkey=>$ticketval)
              {
                 $ticketData = $this->ticket->getticket(array('id'=>$ticketkey));
                 //check total sold ticket and quantity//
                 $totalsoalticket = $ticketData->total_sold+$ticketval->selectedQuantity;
                 $selectedTicket = $ticketval->selectedQuantity;
                 if($getOrderType->booking_from==5)
                 {
                   $countOrder = $this->order->getallBy(array('group_id'=>$getOrderType->group_id));
                   $totalsoalticket = $ticketData->total_sold+count($countOrder);
                   $selectedTicket = count($countOrder);
                 }
                 if($totalsoalticket>=$ticketData->quantity)
                 {
                                   //upadte sold //
                    $updatesold = $this->ticket->updateticket(array('sold_out'=>1),array('id'=>$ticketkey));
                 }
                 $updateTickettotal_sold = $this->ticket->increment($ticketkey,'total_sold',$selectedTicket);
                                  //update onhold values//
                if($selectedTicket>$ticketData->on_hold)
                {
                  $updateTicketon_hold = $this->ticket->updateticket(array('on_hold'=>0),array('id'=>$ticketkey));
                }
                else
                {
                  $updateTicketon_hold = $this->ticket->decrement($ticketkey,'on_hold',$selectedTicket);
                }
                    
              }
          }
        }
       
        if($getOrderType->booking_from==5)
        {
         $updateOrderArray = array('order_status'=>1,'on_hold'=>0,'hold_time'=>0);
         $update =  $this->order->updateorder($updateOrderArray,array('group_id'=>$getOrderType->group_id));
        }
        else
        {
         $updateOrderArray = array('order_status'=>1,'on_hold'=>0,'hold_time'=>0);
         $update =  $this->order->updateorder($updateOrderArray,array('id'=>$orderid));
        }
      }
    }
   public function updateholdticket_timeAPI(Request $request)
   {
      $condition = array('id'=>$request->order_id,'on_hold'=>1);
      if(isset($request->on_hold))
      {
        $condition = array('id'=>$request->order_id);
      }
      $orderdetails = $this->order->getBy($condition);
     // $orderdetails = $this->bookingDetails->find();
      if($orderdetails)
      {
        $bookingdata = json_decode($orderdetails->details);
        $ticketArrayData = $bookingdata->response->calculatedDetails;
        if(array_key_exists('ticketArray', $ticketArrayData)  && (isset($request->order_status) && $request->order_status=='completed') )
        {
          $ticketArray = $ticketArrayData->ticketArray;
          if(count($ticketArray)>0)
          {
              foreach($ticketArray as $ticketkey=>$ticketval)
              {
                 $ticketData = $this->ticket->getticket(array('id'=>$ticketkey));
                 //check total sold ticket and quantity//
                 $totalsoalticket = $ticketData->total_sold+$ticketval->selectedQuantity;
                 if($totalsoalticket>=$ticketData->quantity)
                 {
                                   //upadte sold //
                    $updatesold = $this->ticket->updateticket(array('sold_out'=>1),array('id'=>$ticketkey));
                 }
                    $updateTickettotal_sold = $this->ticket->increment($ticketkey,'total_sold',$ticketval->selectedQuantity);
                                  //update onhold values//
                    $updateTicketon_hold = $this->ticket->decrement($ticketkey,'on_hold',$ticketval->selectedQuantity);
              }
          }
        }
        $order_status_value=0;
        if(isset($request->order_status) &&  $request->order_status=='completed')
        {
          $order_status_value=1;
        }
        $updateOrderArray = array('order_status'=>$order_status_value,'on_hold'=>0,'hold_time'=>0);
        $update =  $this->order->updateorder($updateOrderArray,array('id'=>$request->order_id));
      }
   }

   private function getattendeinfo($orderid,$eventid)
   {

      $customfeildArray=array();
      $customdetails=array(); 
      $feildArray = array();
      $customfeilddetails = array();   
      $customfeilds = $this->eventfeild->getallBy(array('event_id'=>$eventid),array('name','id','ticket_id','type'));
      foreach($customfeilds as $customfeilds)
      {
        //$feildArray[]=$customfeilds->id;
        $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,
                                                    'type'=>$customfeilds->type);

      }

      //   ///////get order attendeeinfo//////////
       $getorderinfo = $this->customfieldsvalue->groupByall(array('order_id'=>$orderid),array('ticket_id','event_id','order_id','event_custom_fields_id','value','position'),array('order_id','event_custom_fields_id','ticket_id','position'));
       if(count($getorderinfo)>0)
       {
          foreach($getorderinfo as $getorderinfo)
          {
            $feildName='';
            $feildtype='';
            $feildArray[]=$getorderinfo->event_custom_fields_id;
            if(array_key_exists($getorderinfo->event_custom_fields_id, $customfeilddetails))
            {
              $feildName = $customfeilddetails[$getorderinfo->event_custom_fields_id]['name'];
              $feildtype= $customfeilddetails[$getorderinfo->event_custom_fields_id]['type'];
            }
            $customfeildArray[$getorderinfo->ticket_id][$getorderinfo->event_custom_fields_id]=array('name'=>$feildName,
                                                                                                     'type'=>$feildtype);
            $customdetails[$getorderinfo->order_id][$getorderinfo->ticket_id][$getorderinfo->position][$getorderinfo->event_custom_fields_id]= $getorderinfo->value; 
            if($feildtype=='file')
            {
              $customdetails[$getorderinfo->order_id][$getorderinfo->ticket_id][$getorderinfo->position][$getorderinfo->event_custom_fields_id] = $_ENV['CF_LINK'].'/event/'.$getorderinfo->event_id.'/customfeild/'.$getorderinfo->value;;
            }
          }
       }
      if(!array_key_exists($orderid, $customdetails))
      {
        $customdetailsArray ='';
      }
      else
      {
        $customdetailsArray = $customdetails[$orderid];
      }
       $customData = array('customfeild'=>$customfeildArray,'customdetails'=>$customdetailsArray);
        return $customData;

   }

  private function getorderattendeinfo($orderid,$eventid)
  {
        $customfeild='';
        $customdetails='';
        $customfeilds = $this->eventfeild->getList(array('event_id'=>$eventid),'name','id');
                                             
                        //chwck values store//
        $getorderticket = $this->customfieldsvalue->groupByall(array('order_id'=>$orderid),array('ticket_id'),array('order_id', 'ticket_id'));
        if($getorderticket)
        {
           $arrayfeild =array();
            foreach($getorderticket as $getorderticket)
            {
               $conditionfeild = array('order_id'=>$orderid,'ticket_id'=>$getorderticket->ticket_id);
               $getfeildid = $this->customfieldsvalue->groupByall($conditionfeild,
                                                                 array('event_custom_fields_id'),
                                                                 array('order_id','ticket_id','event_custom_fields_id'));
                                                    
                foreach($getfeildid as $getfeildid)
                {
                  $feilname = $customfeilds[$getfeildid->event_custom_fields_id];
                  $arrayfeild = array($getfeildid->event_custom_fields_id=>$feilname);
                  $customfeild[$getorderticket->ticket_id][$getfeildid->event_custom_fields_id]= $feilname;
                }
            }
            $checkcustomdetails = $this->customfieldsvalue->groupByall(array('order_id'=>$orderid),array('*'),array('order_id', 'ticket_id','position'));
                if($checkcustomdetails)
                {
                  foreach($checkcustomdetails as $checkcustomdetails)
                  {
                    $condition = array('order_id'=>$checkcustomdetails->order_id,
                                       'ticket_id'=>$checkcustomdetails->ticket_id,
                                       'position'=>$checkcustomdetails->position);
                    $getfeilddetaills =$this->customfieldsvalue->getallBy($condition);
                    $j=0;
                    foreach($getfeilddetaills as $getfeilddetaills)
                    {
                      $customdetails[$checkcustomdetails->ticket_id][$checkcustomdetails->position][$getfeilddetaills->event_custom_fields_id] = $getfeilddetaills->value;
                    }
                  }
                }
        }
        $customData = array('customfeild'=>$customfeild,'customdetails'=>$customdetails);
        return $customData;
  }
  public function updatecoupon(Request $request)
  {
      $couponcodeData = Input::all();
      @extract($couponcodeData);
        //updateremaingQuantity//
      $totalQuantity=0;
      $checkOrder = $this->bookingDetails->getBy(array('order_id'=>$request->orderid));
      if($checkOrder)
      {
        if($checkOrder->global_discount==1)
        {
          $totalQuantity=1;
          $updatecondition = array('code'=>$checkOrder->coupon_code,'coupon_type'=>3);
          $updatecouponcode = $this->discount->decrement($updatecondition,'remaining_quantity',$totalQuantity);
          if($updatecouponcode)
          {
            return 'success';
          }
          else
          {
            return 'error';
          }
        }
        else
        {
          $bookingdata = json_decode($checkOrder->details);
          $ticketArrayData = $bookingdata->response->calculatedDetails;
          if(array_key_exists('ticketArray', $ticketArrayData))
          {
            $ticketArray = $ticketArrayData->ticketArray;
            if(count($ticketArray)>0)
            {
                if($checkOrder->directdiscount==1)
                {
                  foreach($ticketArray as $ticketkey=>$ticketval)
                  {
                     if($ticketval->coupen!='')
                     {
                       $totalQuantity=1;
                     }
                     $updatecondition = array('event_id'=>$checkOrder->event_id,'code'=>$ticketval->coupen);
                     $updatecouponcode = $this->discount->decrement($updatecondition,'remaining_quantity',$totalQuantity);
                      //update remaing values//
                  }
                }
                else
                {
                  foreach($ticketArray as $ticketkey=>$ticketval)
                  {
                     if($ticketval->coupen==$checkOrder->coupon_code && $ticketval->coupen!='')
                     {
                       $totalQuantity+=$ticketval->discountquantity;
                     }
                      //update remaing values//
                  }
                  $updatecondition = array('event_id'=>$checkOrder->event_id,'code'=>$checkOrder->coupon_code);
                  $updatecouponcode = $this->discount->decrement($updatecondition,'remaining_quantity',$totalQuantity);
                }
                
                if($updatecouponcode)
                {
                  return 'success';
                }
                else
                {
                  return 'error';
                }
            }
          }
          else
          {
             return 'error';
          }

        }
     
      }
      
      else
      {
         return 'error';
      }
      
  }

    ///////////update hold time and tickets on hold for pending orders/////////
  public function updateonholdpending(Request $request)
  {
    $conditioncheck = 'on_hold=1 and TIMESTAMPDIFF(MINUTE,created_at,NOW())>22 and details!=""';
    $getOrderType = $this->order->grouporders($conditioncheck,array('id','clicked','details'));
    if(count($getOrderType)>0)
    {  
      $Arrayticket = array();
      $ticketArrayList = $this->ticket->getallBy(array(),array('id','on_hold'));
      foreach($ticketArrayList as $ticketArrayList)
      {
        $Arrayticket[$ticketArrayList->id] = $ticketArrayList->on_hold;
      }
      foreach($getOrderType as $getorder)
      {
        $orderid = $getorder->id;
        $bookingdata = json_decode($getorder->details);
        $ticketArrayData = $bookingdata->response->calculatedDetails;
        if(array_key_exists('ticketArray', $ticketArrayData))
        {
          $ticketArray = $ticketArrayData->ticketArray;
          if(count($ticketArray)>0)
          {
              foreach($ticketArray as $ticketkey=>$ticketval)
              {
                if(is_numeric($ticketkey))
                {
                  //$ticketData = $this->ticket->getticket(array('id'=>$ticketkey));
                 if(array_key_exists($ticketkey, $Arrayticket))
                 {
                    if(isset($ticketval->selectedQuantity))
                    {
                      $selectedTicket = $ticketval->selectedQuantity;
                     if($selectedTicket>$Arrayticket[$ticketkey])
                     {
                       $updateTicketon_hold = $this->ticket->updateticket(array('on_hold'=>0),array('id'=>$ticketkey));
                     }
                     else
                     {
                       $updateTicketon_hold = $this->ticket->decrement($ticketkey,'on_hold',$selectedTicket);
                     }
                    }
                                  //update onhold values//
                 }
                }
                 
                 //check total sold ticket and quantity//
              }
          }
        }
        
        $updateOrderArray = array('on_hold'=>0,'hold_time'=>0);
        $update =  $this->order->updateorder($updateOrderArray,array('id'=>$orderid));
        $sheatstatus = $this->seatplan->update(array('status'=>0),array('order_id'=>$orderid));
        if($update)
        {
          echo $orderid.'--hold time updates in order details';
        }
      }
    }
    
  }



  public function myticketsapi(Request $request)
  {
    $result=array();
    $whereCondition = array('user_id'=>$request->user_id,'order_status'=>1);
    $bookinglist = $this->order->getallBy($whereCondition,array('id','event_id','email','event_type','show_id','all_info','details_submit'));
    $commonObj = new common();
    if(count($bookinglist)>0)
    {
      $eventsdata = array();
      $whereCondition = array('status'=>1);
      $select = array('zone','timezone');
      $timezoned = $this->timezone->getBy($whereCondition,$select);
      $todaydate = date("Y-m-d h:i:s");
      $arrayfinal =array();
      $arraypast =array();
      $evetidArray=array();
      $evetlistArray=array();
      $counter = 0;
      $counter2 = 0;
      foreach($bookinglist as $bookings)
      {
        $evetidArray[]=$bookings->event_id;
        $is_form_required='No';
        if($bookings->all_info==1 && $bookings->details_submit==0)
        {
          $is_form_required='Yes';
        }
        $mainArray[$bookings->id]['email'] = $bookings->email;
        $mainArray[$bookings->id]['eventid'] = $bookings->event_id;
        $mainArray[$bookings->id]['order_id'] = $bookings->id;
        $mainArray[$bookings->id]['order_number'] = $bookings->id;
        $mainArray[$bookings->id]['is_form_required'] = $is_form_required;
        $mainArray[$bookings->id]['event_type'] = $bookings->event_type;
        $mainArray[$bookings->id]['show_id'] = $bookings->show_id;
      }

      $evetlist = $this->event->DetailsById($evetidArray,array('id','title','start_date_time','end_date_time','venue_name','city','state','country'));
      if(count($evetlist)>0)
      {
        foreach($evetlist as $evetlist)
        {
          $evetlistArray[$evetlist->id]['title'] = $evetlist->title;
          $evetlistArray[$evetlist->id]['venue_name'] = $evetlist->venue_name;
          $evetlistArray[$evetlist->id]['city'] = $evetlist->city;
          $evetlistArray[$evetlist->id]['state'] = $evetlist->state;
          $evetlistArray[$evetlist->id]['country'] = $evetlist->country;
          $evetlistArray[$evetlist->id]['startdatetime'] = $commonObj->ConvertGMTToLocalTimezone($evetlist->start_date_time,$timezoned->timezone);
          $evetlistArray[$evetlist->id]['enddatetime'] = $commonObj->ConvertGMTToLocalTimezone($evetlist->end_date_time,$timezoned->timezone);
          if($evetlist->start_date_time=='0000-00-00 00:00:00')
          {
            $evetlistArray[$evetlist->id]['startdatetime'] ='';
          }
          if($evetlist->end_date_time=='0000-00-00 00:00:00')
          {
            $evetlistArray[$evetlist->id]['enddatetime'] ='';
          }
        }
      }
      foreach($mainArray as $orderid=>$ordervalues)
      {
        //echo $ordervalues['eventid'];
        if(array_key_exists($ordervalues['eventid'], $evetlistArray))
        {
           $eventstartdate =  $evetlistArray[$ordervalues['eventid']];  
           if(strtotime($todaydate) <= strtotime($eventstartdate['enddatetime']) || $eventstartdate['enddatetime']=='0000-00-00 00:00:00')
           {
                $arrayfinal[$counter]['title'] = $eventstartdate['title'];
                $arrayfinal[$counter]['venue_name'] = $eventstartdate['venue_name'];
                $arrayfinal[$counter]['city'] = $eventstartdate['city'];
                $arrayfinal[$counter]['state'] = $eventstartdate['state'];
                $arrayfinal[$counter]['country'] = $eventstartdate['country'];
                $arrayfinal[$counter]['email'] = $ordervalues['email'];
                $arrayfinal[$counter]['order_id'] = $ordervalues['order_id'];
                $arrayfinal[$counter]['order_number'] = $ordervalues['order_number'];
                $arrayfinal[$counter]['is_form_required'] = $ordervalues['is_form_required'];
                $arrayfinal[$counter]['startdatetime'] = $eventstartdate['startdatetime'];
                if($ordervalues['event_type']==2)
                {
                  $checkslot = $this->slotlist->getBy(array('id'=>$ordervalues['show_id']));
                  if($checkslot)
                  {
                    $getstartdate = $commonObj->ConvertGMTToLocalTimezone($checkslot->start_date_time,$timezoned->timezone);
                    $getenddate   = $commonObj->ConvertGMTToLocalTimezone($checkslot->end_date_time,$timezoned->timezone);
                    $arrayfinal[$counter]['startdatetime'] = $getstartdate;
                    $getshedule = $this->schedule->getBy(array('id'=>$checkslot->schedule_id));
                    if($getshedule)
                    {
                      $arrayfinal[$counter]['venue_name'] = $getshedule->venue_name;
                      $arrayfinal[$counter]['city'] = $getshedule->city;
                      $arrayfinal[$counter]['state'] = $getshedule->state;
                      $arrayfinal[$counter]['country'] = $getshedule->country;
                    }
                  }
                }
                $counter++;
           }
           else
           {
              $arraypast[$counter2]['title'] = $eventstartdate['title'];
              $arraypast[$counter2]['venue_name'] = $eventstartdate['venue_name'];
              $arraypast[$counter2]['city'] = $eventstartdate['city'];
              $arraypast[$counter2]['state'] = $eventstartdate['state'];
              $arraypast[$counter2]['country'] = $eventstartdate['country'];
              $arraypast[$counter2]['email'] = $ordervalues['email'];
              $arraypast[$counter2]['order_id'] = $ordervalues['order_id'];
              $arraypast[$counter2]['order_number'] = $ordervalues['order_number'];
              $arraypast[$counter2]['is_form_required'] = $ordervalues['is_form_required'];
              $arraypast[$counter2]['startdatetime'] = $eventstartdate['startdatetime'];
              if($ordervalues['event_type']==2)
              {
                $checkslot = $this->slotlist->getBy(array('id'=>$ordervalues['show_id']));
                if($checkslot)
                {
                  $getstartdate = $commonObj->ConvertGMTToLocalTimezone($checkslot->start_date_time,$timezoned->timezone);
                  $getenddate   = $commonObj->ConvertGMTToLocalTimezone($checkslot->end_date_time,$timezoned->timezone);
                  $arraypast[$counter2]['startdatetime'] = $getstartdate;
                  $getshedule = $this->schedule->getBy(array('id'=>$checkslot->schedule_id));
                  if($getshedule)
                  {
                    $arraypast[$counter2]['venue_name'] = $getshedule->venue_name;
                    $arraypast[$counter2]['city'] = $getshedule->city;
                    $arraypast[$counter2]['state'] = $getshedule->state;
                    $arraypast[$counter2]['country'] = $getshedule->country;
                  }
                }
              }
                $counter2++;
           }
        }
      }
      $result['live'] = $arrayfinal;
      $result['past'] = $arraypast;
      $result['livecount'] = count($arrayfinal);
      $result['pastcount'] = count($arraypast);
      return response()->json(['myticktes' => $result]);
    }
    else
    {
      return response()->json(['myticktes' =>'notfound']);
    }
  }


   ////////////// send incomplete mail //////////////
  public function filldetailsmail(Request $request)
  {
    $commonObj = new Common();
    $status['status']='error';
    if($request->timeinterval)
    {
      $status['status']='success';
     
       $condition = "order_status=1 and details_submit=0 and all_info=1 and TIMESTAMPDIFF(MINUTE,updated_at,NOW()) between ".$request->timeinterval." and ".$request->timeinterval*2;
      $getorders = $this->order->getallByRaw($condition,array('id','event_id'));
      if(count($getorders)>0)
      {
        ///////////// get all incomplete mails//////
         $checkMail = $this->mail->getListBy(array('type'=>'incompleteform'),'order_id');
         //dd($checkMail);
         $i=0;
        foreach($getorders as $getorders)
        {
            if(in_array($getorders->id,$checkMail))
            {
              $status['update'][]=$getorders->id;
              $i++;
              $update = $this->mail->updatemail(array('status'=>1),array('order_id'=>$getorders->id,'type'=>'incompleteform'));
            }
            else
            {
               $mail=0;
               $mail = $commonObj->sendmailcommon('incomplete',$getorders->id,0,1);
               if($mail==1)
               {
                $status['insert'][]=$getorders->id;
                $insertMail = $this->mail->create(array('order_id'=>$getorders->id,'status'=>1,'type'=>'incompleteform'));
                $i++;
               }
           }
        }
        if($i>0)
        {
          $status['status']='success';
        }
      }
      else
      {
        $status['status']='norecords';

      }

    }
    return response()->json([$status]);

  }
  

}
