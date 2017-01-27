<?php 
namespace App\Http\Controllers\Api;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Common;
use DateTime;
use Auth;
use App\Model\Timezone;
use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\OrderInterface;
use Appfiles\Repo\OrderbreakageInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\EventdetailInterface;
use Appfiles\Repo\TimezoneInterface;
use Appfiles\Repo\RecurringeventInterface;
use Appfiles\Repo\DiscountcouponInterface;
use Appfiles\Repo\WeightageInterface;
use Appfiles\Repo\CurrencyInterface;
use Appfiles\Repo\CoupontktInterface;
use Appfiles\Repo\TicketwidgetInterface;
use Appfiles\Repo\TracksourceInterface;
use Appfiles\Repo\ServicechargesInterface;
use Appfiles\Repo\SlotInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Query\Builder;
use Appfiles\Repo\ExtrachargesInterface;

class TicketController extends Controller 
{

  public function  __construct(TicketInterface $ticket ,ExtrachargesInterface $extracharge, TimezoneInterface $timezone, RecurringeventInterface $recurringevent,OrderbreakageInterface $orderbreakage, CoupontktInterface $coupontkt,TicketwidgetInterface $ticketwidget, EventInterface $event, OrderInterface $order , 
    DiscountcouponInterface $discount,EventdetailInterface $eventdetail,SlotInterface $slotlist ,WeightageInterface $weightage,CurrencyInterface $currancy,ServicechargesInterface $servicecharge,TracksourceInterface $tracksource)
  {
      $this->ticket=$ticket;
      $this->order=$order;
      $this->event = $event;
      $this->discount=$discount;
      $this->timezone = $timezone;
      $this->coupontkt=$coupontkt;
      $this->ticketwidget =$ticketwidget;
      $this->orderbreakage= $orderbreakage;
      $this->recurringevent = $recurringevent;
      $this->eventdetail=$eventdetail;
      $this->extracharge = $extracharge;
      $this->tracksource=$tracksource;
      $this->slotlist = $slotlist;
      $this->currancy=$currancy;
      $this->weightage=$weightage;
      $this->servicecharge = $servicecharge;
  }

  public function ticketlist(Request $request)
  {
    return $this->getTicketList($request);
  }

  public function getTicketList($request,$responseType='json')
  {
    // ticket array//
      //print_r($request);
      $buttonValue='Buy Now';
      $textforbox='Price';
      $saleend ='Sale Ends';
      $tickettext='e-ticket';
      $commonObj = new Common();
      $ticketarray = array();
      $checkevent = $this->event->find($request->id,array('recurring_type','start_date_time','end_date_time','occurrence','category'));
      if($checkevent)
      {
        $ticketCommon = array('event_id'=>$request->id,'display_status'=>1,'status'=>1);
        if(isset($request->ticket_id))
        {
          //$ticketCommon['id'] = $request->ticket_id;
          $ticketCommon = array('event_id'=>$request->id,'status'=>1,'id'=>$request->ticket_id);
        }
        if(isset($request->slotid))
        {
          $ticketCommon = array('event_id'=>$request->id,'slot_id'=>$request->slotid,'status'=>1,'display_status'=>1);
        }
      
      
        $eventTicketList = $this->ticket->getallBy($ticketCommon);
        $gettimezone='Asia/Kolkata';
        if(isset($request->timezonename))
        {
          $gettimezone=$request->timezonename;
        }
        $coupunapply='No';
        if(count($eventTicketList)>0)
        {
          $currancyArray = array();
          $getAllcurrancy = $this->currancy->getallBy(array('status'=>1));
          if(count($getAllcurrancy)>0)
          {
            foreach($getAllcurrancy as $getAllcurrancy)
            {
              $currancyArray[$getAllcurrancy->id] = $getAllcurrancy->short_name;

            }
          }

          ////////////for recuring event/////////////
          $eventcreatetype=$request->eventcreatetype;
          $datetoDisplay=array();
          if($checkevent->recurring_type==1)
          {

            $getstartdate = $commonObj->ConvertGMTToLocalTimezone($checkevent->start_date_time,$gettimezone);
            $getenddate   = $commonObj->ConvertGMTToLocalTimezone($checkevent->end_date_time,$gettimezone);
            $eventcreatetype=1;
            $eventcreateType='multipledates';
            // $conditionIm= array('event_id'=>$request->id,'status'=>1,'type'=>1);
            // $showlist = $this->recurringevent->getallBy($conditionIm);
                       //holidaylist//
            $holiday = 'dates';
            $conditionholi = array('event_id'=>$request->id,'status'=>1,'type'=>2);
            $conditionExtraDate = array('event_id'=>$request->id,'status'=>1,'type'=>3);
            $holidayslist =  $this->recurringevent->getListby($conditionholi,$holiday);
            $ExtraDateList =  $this->recurringevent->getListby($conditionExtraDate,$holiday);
            if($checkevent->occurrence==1)
            {
              $datetoDisplayArray = $commonObj->returnBetweenDates($getstartdate,$getenddate);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }   
            if($checkevent->occurrence==2)
            {
              $conditionIm = array('event_id'=>$request->id,'status'=>1,'type'=>3,'occurrence'=>2);
              $values='name';
              $days = $this->recurringevent->getListby($conditionIm,$values);
              //$days=  array('Monday','Thursday');

              $datetoDisplayArray = $commonObj->returndaysBetweenDates($getstartdate,$getenddate ,$days);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }
            if($checkevent->occurrence==3)
            {
              $conditionIm = array('event_id'=>$request->id,'status'=>1,'type'=>3,'occurrence'=>3);
              $values='monthly';
              $days = $this->recurringevent->getListby($conditionIm,$values);
        
              $datetoDisplayArray = $commonObj->returndatesBetweenDates($getstartdate,$getenddate ,$days);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }
            if($checkevent->occurrence==4)
            {
              $conditionIm = array('event_id'=>$request->id,'status'=>1,'type'=>3,'occurrence'=>4);
              $values='dates';
              $datetoDisplayArray = $this->recurringevent->getListby($conditionIm,$values);
              $datetoDisplay = array_diff($datetoDisplayArray,$holidayslist);
              sort($datetoDisplay);
            }
            // $dateswithShow='';
            // $eventshow = array();
            // if(count($showlist)>0)
            // {
            //   foreach ($showlist as $showlist)
            //   {
            //         $eventshow[$showlist->id] =  array('showname'=>$showlist->name,
            //                                            'start_time'=>$showlist->start_time,
            //                                            'end_time'=>$showlist->end_time);
            //   }
            // }
            
          }
      
          $eventDetails = $this->eventdetail->findBy('event_id',$request->id,array('show_remaining_ticket','booknow_button_value','show_end_date'));
          if($eventDetails)
          {
            if($eventDetails->booknow_button_value)
              {
                $buttonValue=$eventDetails->booknow_button_value;
                switch ($buttonValue) {
                  case 'Buy Ticket':
                  $booknowbutton='Buy Now';
                  $saleend ='Sale Ends';
                  $tickettext=$_ENV['ForBuyTicket_text'];
                  $textforbox=$_ENV['ForBuyTicket'];
                  break;
                  case 'Register':
                  $saleend ='Registration Closes';
                  $tickettext=$_ENV['ForRegister_text'];
                  $textforbox=$_ENV['ForRegister'];
                  break;
                  case 'Donate':
                  $tickettext=$_ENV['ForDonate_text'];
                  $saleend ='Donation Closes';
                  $textforbox=$_ENV['ForDonate'];
                  break;
                 
                }
              }
          }

          
          $checkCoupon =  $this->discount->getBy(array('event_id'=>$request->id,'status'=>1));
          if($checkCoupon || strtolower($checkevent->category)=='new year')
          {
            $coupunapply='Yes';
          }
          $startfrom = 1;
          // if(count($eventTicketList)>1)
          // {
          //   $startfrom = 0; 
          // }
          /////////
          $showremainingTicket = array();
          if($eventcreatetype==1)
          {
             $condition = array('order_status'=>'completed','orderbreakages.event_id'=>$request->id);
                $totalsoldticket = $this->orderbreakage->getquantitynew($condition);
                if(count($totalsoldticket)>0)
                {
                  foreach($totalsoldticket as $totalsoldticket)
                  {
                    $showremainingTicket[$totalsoldticket->showid.'--'.$totalsoldticket->ticket_id] = $totalsoldticket->totalqut;

                  }

                }
                
          }
          //dd($showremainingTicket);
          foreach ($eventTicketList as $eventTicketList)
          {
            $ticketremaining=0;
            $maxorder = $eventTicketList->max_order_quantity;
            $ticketremaining = $eventTicketList->quantity - ($eventTicketList->total_sold+$eventTicketList->on_hold);
            if($ticketremaining<0)
            {
              $ticketremaining=0;
            }
            $buyupto = $maxorder;
            $startfrom = $eventTicketList->min_order_quantity;
            $eventTickettype=3;
            if($eventTicketList->price>0)
            {
              $eventTickettype=2;
            }
            elseif($eventTicketList->price==0)
            {
              $eventTickettype=1;
            }
            $getticketenddate   = $commonObj->ConvertGMTToLocalTimezone($eventTicketList->end_date_time,$gettimezone);
            $getticketstartdate   = $commonObj->ConvertGMTToLocalTimezone($eventTicketList->start_date_time,$gettimezone);
            $ticketprice = $eventTicketList->price;
            $ticketstatus = 'avilable';
            $datetime = $commonObj->ConvertGMTToLocalTimezone(date('Y-m-d H:i:s'),$gettimezone);
            $today  = new DateTime($datetime);
            $past   = new DateTime($getticketenddate);
            $dteDiff  = $today->diff($past);
            
            $convertedTimeCounter="";
            if($dteDiff->m>0)
              $convertedTimeCounter.=$dteDiff->m.'m ';
            if($dteDiff->d>0)
              $convertedTimeCounter.=$dteDiff->d.'d ';
            if($dteDiff->h>0)
              $convertedTimeCounter.=$dteDiff->h.'h ';
            if($dteDiff->m==0 && $dteDiff->i>0)
              $convertedTimeCounter.=$dteDiff->i.'m ';

            $ticketavilablefrom = new DateTime($getticketstartdate);
            if($eventcreatetype==1)
            {
                if($request->showdate!='' && $request->showid!='')
                {
                  $showCondition = array('id'=>$request->showid);
                  $showDetails = $this->recurringevent->getBy($showCondition,array('start_time'));
                  if($showDetails)
                  {
                    $showtime = $showDetails->start_time;
                    if($eventTicketList->event_id==8897) 
                    {
                      $showtime ='09:30';
                      # code...
                    }
                    if($eventTicketList->id==2716 && ($request->showdate=='2016-05-22' || $request->showdate=='2016-05-21'))
                    {
                      $ticketstatus = 'sold out';
                      $convertedTimeCounter = '';

                    }
                    elseif(!in_array($request->showdate, $datetoDisplay))
                    {
                      $ticketstatus = 'Ticket Booking Closed';
                      $convertedTimeCounter = '';

                    }
                    elseif(strtotime($request->showdate.' '.$showtime)<strtotime($datetime))
                    {
                      $ticketstatus = 'Ticket Booking Closed';
                      $convertedTimeCounter = '';
                    }
                    elseif(strtotime($datetime)>strtotime($getticketenddate))
                    {
                      $ticketstatus = 'Ticket Booking Closed';
                      $convertedTimeCounter = '';
                    }
                    elseif($eventTicketList->sold_out==1)
                    {
                      $ticketstatus = 'Sold Out';
                      $convertedTimeCounter = '';
                    }
                    elseif($past<$today)
                    {
                       $ticketstatus = 'Ticket Booking Closed';
                       $convertedTimeCounter = '';
                    }
                    elseif($ticketavilablefrom>$today)
                    {
                      $ticketstatus = 'Coming Soon';
                    }
                  }

                }
                else if($request->showdate=='' && $request->showid=='')
                {
                  $ticketstatus = 'Ticket Booking Closed';
                  $convertedTimeCounter = '';
                }
                else if($request->showdate=='' && $request->showid!='')
                {
                  $ticketstatus = 'Ticket Booking Closed';
                  $convertedTimeCounter = '';
                }
                else
                {
                  if(!in_array($request->showdate, $datetoDisplay))
                    {
                      $ticketstatus = 'Ticket Booking Closed';
                      $convertedTimeCounter = '';

                    }
                    elseif(strtotime($request->showdate)<strtotime($datetime))
                    {
                      $ticketstatus = 'Ticket Booking Closed';
                      $convertedTimeCounter = '';
                    }
                    elseif(strtotime($datetime)>strtotime($getticketenddate))
                    {
                      $ticketstatus = 'Ticket Booking Closed';
                      $convertedTimeCounter = '';
                    }
                    elseif($eventTicketList->sold_out==1)
                    {
                      $ticketstatus = 'Sold Out';
                      $convertedTimeCounter = '';
                    }
                    elseif($past<$today)
                    {
                       $ticketstatus = 'Ticket Booking Closed';
                       $convertedTimeCounter = '';
                    }
                    elseif($ticketavilablefrom>$today)
                    {
                      $ticketstatus = 'Coming Soon';
                    }

                }
               
                // else
                // {
                //   $ticketstatus = 'Ticket Booking Closed';
                //   $convertedTimeCounter = '';
                // }
                 $totalshowsoldticket=0;
                 if(array_key_exists($request->showid.'--'.$eventTicketList->id, $showremainingTicket))
                 {
                  $totalshowsoldticket=$showremainingTicket[$request->showid.'--'.$eventTicketList->id];

                 }
                $showticketremaining = $eventTicketList->quantity- ($totalshowsoldticket+$eventTicketList->on_hold);
                $ticketremaining = $showticketremaining;
                if($ticketremaining<=0)
                {
                  $ticketremaining=0;
                }
                //$buyupto = $ticketremaining;
                if($eventTicketList->special_price>=0)
                {
                  $dayforprice = date('l',strtotime($request->showdate));
                  $daysList = explode(',',$eventTicketList->days);
                  $holiday = 'dates';
                  $conditionExtraDate = array('event_id'=>$request->id,'ticket_id'=>$eventTicketList->id,'status'=>1,'type'=>4);
                  $ExtradatesArray =  $this->recurringevent->getListby($conditionExtraDate,$holiday);
                  
                  if(in_array($dayforprice,$daysList) || in_array($request->showdate, $ExtradatesArray))
                  {
                    $ticketprice = $eventTicketList->special_price;
                  }
                  
                }
                // if($eventTicketList->special_price>0)
                // {
                //   $ticketprice = $eventTicketList->special_price;
                // }
                if($showticketremaining<=0)
                {
                  $ticketremaining=0;
                  $ticketstatus = 'Sold Out';
                }
            }
            elseif($eventTicketList->sold_out==1)
            {
              $ticketstatus = 'Sold Out';
              $convertedTimeCounter = '';

            }
            else if($eventTicketList->total_sold+$eventTicketList->on_hold>=$eventTicketList->quantity)
            {
              $ticketstatus = 'Sold Out';
              $convertedTimeCounter = '';
            }
            elseif($past<$today)
            {
               $ticketstatus = 'Ticket Booking Closed';
               $convertedTimeCounter = '';
            }

            elseif($ticketavilablefrom>$today)
            {
              $ticketstatus = 'Coming Soon';
            }
            if($maxorder>$ticketremaining)
            {
              $buyupto = $ticketremaining;
            }
            $currancyName='';
            if(array_key_exists($eventTicketList->currency_id, $currancyArray))
            {
              $currancyName= $currancyArray[$eventTicketList->currency_id];

            }
            if(isset($request->requestfrom))
            {
              $ticketarray[] =  array('ticketid'=>$eventTicketList->id,
                                      'ticketname'=>$eventTicketList->name,
                                      'ticketdescription'=>$eventTicketList->description,
                                      'tickettype'=>$eventTickettype,
                                      'startfrom'=>$startfrom,
                                      'time'=>$datetime,
                                      'buyupto'=>$buyupto,
                                      'slotid'=>$eventTicketList->slot_id,
                                      'currancyName'=>$currancyName,
                                      'currencyid'=>$eventTicketList->currency_id,
                                      'convertedTimeCounter'=>$convertedTimeCounter,
                                      'ticketenddate'=>$getticketenddate,
                                      'ticketstatus'=>$ticketstatus,
                                      'ticketremaining'=>$ticketremaining,
                                      'ticketprice'=>$ticketprice);

            }
            else
            {
              $ticketarray[$eventTicketList->id] =  array('ticketname'=>$eventTicketList->name,
                                                        'ticketdescription'=>$eventTicketList->description,
                                                        'tickettype'=>$eventTickettype,
                                                        'startfrom'=>$startfrom,
                                                        'time'=>$datetime,
                                                        'buyupto'=>$buyupto,
                                                        'slotid'=>$eventTicketList->slot_id,
                                                        'currancyName'=>$currancyName,
                                                        'currencyid'=>$eventTicketList->currency_id,
                                                        'convertedTimeCounter'=>$convertedTimeCounter,
                                                        'ticketenddate'=>$getticketenddate,
                                                        'ticketstatus'=>$ticketstatus,
                                                        'ticketremaining'=>$ticketremaining,
                                                        'ticketprice'=>$ticketprice);

            }
          }

         }
        }

      $textdispalyArray = array('booknowbutton'=>$buttonValue,
                                'textforbox'=>$textforbox,
                                'Sale_Ends'=>$saleend,
                                'ticket_text'=>$tickettext);
       
      if($responseType=='array')
      return ['ticketarray' => $ticketarray,
              'textdispalyArray'=>$textdispalyArray,
              'coupuncode'=>$coupunapply];
    
      return response()->json(['ticketarray' => $ticketarray,
                                   'textdispalyArray'=>$textdispalyArray,
                                   'coupuncode'=>$coupunapply]);
  }
  

  public function calculateAmount(Request $request)
  {
       //print_r($request->ticketArray);
       $passonAmount=0;
       $absorveamount=0;
       $ticketArray=array();
       $totaltaxArray=array();
       $sepratetaxArray=array();
       $totalExtraChargesArray=array();
       $eventid_value=$request->eventId;
       if(isset($request->coupencode))
       {
         $request->coupencode = rtrim($request->coupencode);
       }
       // $taxdata=array();
       // $customtaxdata=array();
       $commonObj = new Common();
       // $getguestusercokkies = $commonObj->getcokkies('guestusercokkies');
       $eventDetail =$this->event->getBy(array('id'=>$request->eventId),array('created_at','country','user_id','category'));
       if(isset($request->eventcreatetype) && $request->eventcreatetype==2)
       {
         $shedule_id= $this->slotlist->getBy(array('id'=>$request->eventshowid),array('schedule_id'));
         $eventDetail =$this->weightage->getBy(array('event_id'=>$request->eventId,'shedule_id'=>$shedule_id->schedule_id),array('created_at','country','user_id'));
       }

      if($request->ticketArray )
        $ticketArray=$request->ticketArray;
        $donateTicketArray=$request->donateTicketArray;
        if(is_array($donateTicketArray))
        $ticketArrayMerge=$ticketArray+$donateTicketArray;  // combining two arrays into single array
        else
        $ticketArrayMerge=$ticketArray;
        foreach($ticketArrayMerge as $keyticet=>$quantityticket)
        {
          if(strpos($quantityticket, ".")!==false)
          {
            unset($ticketArrayMerge[$keyticet]);
            if(array_key_exists($keyticet, $ticketArray))
            {
              unset($ticketArray[$keyticet]);
            }
          }
        }
        //echo count();
        
        if(count($ticketArrayMerge)>0 && $eventDetail)
        {
          $ticketIds=array_keys($ticketArrayMerge);
          $response=$this->ticket->DetailsById($ticketIds)->get();
          
          $totalQuantity=0;  // setting default value for totalquantity
          $totalAmount=0;     // setting default value for total amount
          $formRequired=0; // setting value to check whether all attendee info is required or not
          $currencyId=0;
          $totaldiscount = 0;
          $tax_id = 0;
          $totaldirectDiscount=0;
          $directdiscount=0;
          $coupunCode= '';
          $directdiscountFlag=0;
          $paymentgetwayfee = 0;
          $goeventzfee =0;
          $perticketfee = 0;
          $totalExtraCharges=0;
          $totalpasson=0;
          $totalabsorvefee=0;
          $absorbflag =0;
          $createdBy=0;
          $totalPGPasson=0;
          $totalPGObsorve=0;
          $totalperticketPasson=0;
          $totalperticketObsorve=0;
          $totalGEPasson=0;
          $totalGEObsorve=0;
          $totalGoeventChargese=0;
          $totalSerViceTax=0;         
          $totalSerViceTaxpasson=0;
          $totalServiceTaxabsorve=0;
          // $totalcusstomtax = 0;
          $totalcustomtax=0;
          $totalcustomservicetax=0;
          $maxgoeventfee=0;
          if(Auth::check())
          {
             $authUser = Auth::user();
             $createdBy = $authUser->id;

          }
          $i=0;
          //$totalticket  = array_sum($request->ticketArray);
          $ticketApply=0;
          $eventextraserviceCharges=array();
          $defaultserviceCharges=array();
          $countrybasedharges = array();  /////country charges arary
          $eventbasedharges = array(); //////event charges arary
          $result['response']['calculatedDetails']['donateError']='';
          $resulthold=array();
          $ticketIdslist=array();
          $createdDate = date("Y-m-d H:i:s",strtotime( $eventDetail->created_at));
          if(count($response)>0 )
          {
            $deafulttax_id=0;
            ///////////check event based extracharge/
            $eventextracharges = $this->servicecharge->getallBy(array('event_id'=>$request->eventId,'status'=>1,'type'=>2),array('id','service_charge_name','rate_of_intrest','ticket_id'));/////event extra charges
            if(count($eventextracharges)>0)
            {
              foreach($eventextracharges as $eventextracharges)
              {
                $eventextraserviceCharges[$eventextracharges->id]=array('service_charge_name'=>$eventextracharges->service_charge_name,
                                                                        'rate_of_intrest'=>$eventextracharges->rate_of_intrest/100,
                                                                        'ticket_id'=>$eventextracharges->ticket_id);
              }
            }
           // dd($eventextraserviceCharges);
            ////////check evenbasesd service charges////
            $eventservicecharges = $this->servicecharge->getList(array('event_id'=>$request->eventId,'status'=>1,'type'=>1),'rate_of_intrest','service_charge_name');/////event extra charges
            if(count($eventservicecharges)>0)
            {
              foreach($eventservicecharges as $keyeventservice=>$valeventservice)
              {
                $defaultserviceCharges[$keyeventservice]=$valeventservice/100;
              }
            }
            else
            {
              $servicechardes = $this->servicecharge->getList(array('country_name'=>strtolower($eventDetail->country),'status'=>1,'type'=>1),'rate_of_intrest','service_charge_name');/////country servicecharges
              if(count($servicechardes)>0)
              {
                foreach($servicechardes as $keyservice=>$valservice)
                {
                  $defaultserviceCharges[$keyservice]=$valservice/100;   // this conaints list of service charges applicable
                }
              }
            }
             
            $getcurrency = $response[0]->currency_id;///currency id
            $chargesArray = array('pg_charge'=>0,'go_charge'=>0,'per_ticket_charge'=>0,'mode'=>0,'max_go_fee'=>0);
            ////////check taxes on event//////////////
            $checkExtracharge = $this->extracharge->getBy(array('event_id'=>$request->eventId,'type'=>2,'currency_id'=>$getcurrency,'status'=>1));
            if($checkExtracharge) //checking if any extra charges set for particular event
            {
              $tax_id = $checkExtracharge->id;
              $chargesArray['pg_charge'] = $checkExtracharge->pg_charges;
              $chargesArray['go_charge'] = $checkExtracharge->goeventz_fee;
              $chargesArray['per_ticket_charge'] = $checkExtracharge->per_ticket_fee;
              $chargesArray['max_go_fee'] = $checkExtracharge->max_goeventz_fee;
              $chargesArray['mode'] = $checkExtracharge->mode;
              // getting default tax id (i.e. if event tax was not there which default tax charge should have been applied)
              $getdefaultid = $this->extracharge->getBy(array('type'=>1,'currency_id'=>$getcurrency,'status'=>1),array('id'));
              if($getdefaultid)
              {
                $deafulttax_id=$getdefaultid->id;
              }

            }
            else 
            {   ////////////check extra charges for user//////////////
                $checkExtracharge = $this->extracharge->getBy(array('user_id'=>$eventDetail->user_id,'type'=>2,'currency_id'=>$getcurrency,'status'=>1));
                if($checkExtracharge) //checking if any extra charges set for particular event
                {
                  $tax_id = $checkExtracharge->id;
                  $chargesArray['pg_charge'] = $checkExtracharge->pg_charges;
                  $chargesArray['go_charge'] = $checkExtracharge->goeventz_fee;
                  $chargesArray['per_ticket_charge'] = $checkExtracharge->per_ticket_fee;
                  $chargesArray['max_go_fee'] = $checkExtracharge->max_goeventz_fee;
                  $chargesArray['mode'] = $checkExtracharge->mode;
                  // getting default tax id (i.e. if event tax was not there which default tax charge should have been applied)
                  $getdefaultid = $this->extracharge->getBy(array('type'=>1,'currency_id'=>$getcurrency,'status'=>1),array('id'));
                  if($getdefaultid)
                  {
                    $deafulttax_id=$getdefaultid->id;
                  }

                }
                else
                {
                  ////////////get default charges//////////////
                  $checkExtracharge = $this->extracharge->getByDesc(array('type'=>1,'currency_id'=>$getcurrency),$createdDate);
                  if($checkExtracharge)
                  {   
                    $tax_id = $checkExtracharge->id;
                    $chargesArray['pg_charge'] = $checkExtracharge->pg_charges;
                    $chargesArray['go_charge'] = $checkExtracharge->goeventz_fee;
                    $chargesArray['per_ticket_charge'] = $checkExtracharge->per_ticket_fee;
                    $chargesArray['max_go_fee'] = $checkExtracharge->max_goeventz_fee;
                    $chargesArray['mode'] = $checkExtracharge->mode;
                  }
                }
            }
            // looping ticket details 
            foreach($response as $key=>$value)
            {
              $discount =0;
              $selectedQuantity=0;
              $price=0;
              $defaultdiscount =0;
              $totalTax = 0;
              $totalcusstomtaxperticket=0;
              $totalcustomservicetaxperticket=0;
              $pg_charges = 0;
              $go_charges = 0;
              $per_ticket_charges =0;
              $totaltaxonticket=0;
              $totalpgtax=0;
              $totalgofeetax=0;
              $servicetaxpass=0;
              $servicetaxobserv=0;
              $customtaxdata=array();
               $taxdata=array();
             
              if($currencyId==0 && $value->currency_id>0)  // setting currency id for transaction
              {
                $currencyId=$value->currency_id;
              }
             if($currencyId==$value->currency_id)
             {
                $ticketIdslist[]=$value->id;
                if(array_key_exists($value->id, $ticketArrayMerge))
                {
                    //$value = $this->ticket->getticket(array('id'=>$value->id));
                    $remaining = $value->quantity-$value->total_sold;
                    if($remaining<=0 || $value->sold_out==1)
                    {
                      $remaining=0;
                      $result['response']['calculatedDetails']['bookingerror']='quantitynotmatch';
                      return response()->json($result,200);
                      exit();
                    }
                    
                    if(is_array($donateTicketArray))
                    {
                      if(array_key_exists($value->id, $donateTicketArray))
                      {
                        $selectedQuantity=1; //for donation tickets quantity is always 1
                        $price=$donateTicketArray[$value->id];
                        if($value->min_donation>=$price)
                        {
                            $result['response']['calculatedDetails']['donateError']='donateError';
                            $result['response']['calculatedDetails']['donateAmount']=$value->min_donation;
                            return response()->json($result,200);
                            exit();
                        }
                      }
                      else
                      {
                        $selectedQuantity=$ticketArray[$value->id];
                        $price=$value->price;
                      }
                    }
                    else
                    {
                      $selectedQuantity=$ticketArray[$value->id];
                      $price=$value->price;
                    }
                    if($request->eventcreatetype==1) // this is for recurring event
                    {
                      
                      if($value->special_price>=0)
                      {
                        $dayforprice = date('l',strtotime($request->eventdate));
                        $daysList = explode(',',$value->days);
                        $holiday = 'dates';
                        $conditionExtraDate = array('event_id'=>$value->event_id,'ticket_id'=>$value->id,'status'=>1,'type'=>4);
                        $ExtradatesArray =  $this->recurringevent->getListby($conditionExtraDate,$holiday);
                        if(in_array($dayforprice,$daysList) || in_array($request->eventdate, $ExtradatesArray))
                        {
                          $price = $value->special_price;  //setting price as special price if present
                        }
                      }
                      $condition = array('orderbreakages.show_id'=>$request->eventshowid,'ticket_id'=>$value->id,'order_status'=>'completed');
                      //$condition = array('show_id'=>$request->eventshowid,'ticket_id'=>$value->id);
                      $totalsoldticket =$this->orderbreakage->getquantity($condition);
                      $remaining = $value->quantity-($totalsoldticket+$value->on_hold);
                      if($remaining<=0)
                      {
                        $remaining=0;
                      }
                    }
                    if($selectedQuantity>$remaining)
                    {
                       $selectedQuantity = $remaining;
                    }
                    if($request->bookfrom==5) // for offline booking & individual ticket
                    {
                      $totalticket= array_sum($request->ticketArray);
                      $selectedQuantity = 1;
                      $price=$request->offlineAmount/$totalticket;
                    }
                    if($selectedQuantity<=0)
                    {
                      $result['response']['calculatedDetails']['bookingerror']='quantitynotmatch';
                      return response()->json($result,200);
                      exit();
                    }
                   
                    $array=array('name'=>$value->name,
                                 'selectedQuantity'=>$selectedQuantity,
                                 'price'=>$price,
                                 'type'=>$value->type,
                                 'formRequired'=>$value->registration_form,
                                 'currencyId'=>$value->currency_id);
                   $array['coupen']='';
                   $array['discount']=0;
                   $array['discountquantity']=0;
                   $array['directdiscount']='No';
                   $array['pg_charges']=0;
                   $array['goeventz_fee']=0;
                   $array['totalgoeventzfee']=0;
                   $array['per_ticket_fee']=0;
                   $array['absorvefee']=0;
                   $array['passonfee']=0;
                   $array['goeventpasson']=0;
                   $array['goeventObserve'] =0;
                   $array['pgFeeObserve'] =0;
                   $array['pgFeePasson']=0;
                   $array['perticketObserve']=0;
                   $array['perticketpasson']=0;
                   $array['taxArray']='';
                   $array['totalpgtax']=0;
                   $array['totalgo']=0;
                   $array['servicetaxobserv']=0;
                   $array['servicetaxpass']=0;
                   $array['totalServiceTax']=0;
                   $array['totalcharges']=0;
                   $array['totalcusstomtax']=0;
                   $array['customtaxArray']='';

                   if($request->book!=3 && !isset($request->offlineAmount))
                   {
                      if($request->coupencode!=env('GOEVENTZ_CODE'))
                      {
                         $coupondiscount=0;
                         $coupondiscountquantity=0;
                         $conditionCoupon = array('event_id'=>$request->eventId,'code'=>$request->coupencode,'status'=>1);
                         $checkCoupen = $this->discount->getBy($conditionCoupon);
                         if($checkCoupen && $checkCoupen->coupon_type!=3)
                         {
                           $quantity =  $checkCoupen->quantity;
                           $remaining = $checkCoupen->remaining_quantity;
                           if($checkCoupen->end_date >date('Y-m-d H:i') && $remaining>0)
                           {
                              $rawcondition = "coupon_id = '".$checkCoupen->id."' and ticket_id='".$value->id."'";
                              $checkTicketId = $this->coupontkt->getBy(array('coupon_id'=>$checkCoupen->id,'ticket_id'=>$value->id));
                             // dd($checkTicketId);
                               if($checkTicketId)
                               {
                                 $disounttotal=0;
                                 $remainingticket = $remaining-$i;
                                 if($checkCoupen->from >0 && $checkCoupen->to >0)
                                 {
                                   $remainingticket = $remaining;
                                 }
                                 //echo $value->id;
                                 if($remainingticket>0)
                                 {
                                    if($checkCoupen->from >0 && $checkCoupen->to >0 )
                                    {
                                       if($selectedQuantity >=$checkCoupen->from && $checkCoupen->to>=$selectedQuantity)
                                        {
                                          $quantityforDiscount = $selectedQuantity;
                                        }
                                        else
                                        {
                                          $quantityforDiscount = 0;
                                        }
                                    }
                                    else
                                    {
                                       if($selectedQuantity>$remainingticket)
                                      {
                                         $quantityforDiscount = $remainingticket;
                                      }
                                      else
                                      {
                                         $quantityforDiscount = $selectedQuantity;
                                      }
                                    }
                                    if($checkCoupen->mode==1)
                                     {
                                       $discount = ($quantityforDiscount*$checkCoupen->amount);
                                       $totaldiscount+= $discount;
                                     }
                                   else
                                   {
                                      $discount = ($quantityforDiscount*$price)*$checkCoupen->amount/100;
                                      $totaldiscount+= $discount;
                                   }
                                  //echo $discount;                         
                                 }
                                   $i+= $selectedQuantity;
                                   //$ticketApply+=$selectedQuantity;
                                   $coupunCode = $request->coupencode;
                                   $coupondiscountquantity=$quantityforDiscount;
                                   $coupondiscount=$discount;
                                   $directdiscount+=0;
                               }
                           }
                         }
                         $defaultqtforDiscount=0;
                         $directcoupondiscount=0;
                         $conditionDiscount='event_id='.$request->eventId.' and discount_coupons.from <='.$selectedQuantity.'  and discount_coupons.from>0 and discount_coupons.to >='.$selectedQuantity.' and ticket_id='.$value->id.' and  discount_coupons.status=1 and discount_coupons.end_date >="'.date('Y-m-d H:i').'" ORDER BY `discount_coupons`.`amount` desc , remaining_quantity desc';
                         $selectRawdis='amount,code,discount_coupons.from,discount_coupons.to,remaining_quantity,mode';
                         $checkBulk = $this->discount->getByRawcoupon($conditionDiscount,$selectRawdis);
                         if($checkBulk)
                         {
                            $defaultqtforDiscount = $selectedQuantity;
                            if($checkBulk->mode==1) /// fixed value discount
                             {
                               $defaultdiscount = ($defaultqtforDiscount*$checkBulk->amount);
                               //$totaldiscount+= $defaultdiscount;
                             }
                             else
                             {
                                $defaultdiscount = ($defaultqtforDiscount*$price)*$checkBulk->amount/100;
                                //$totaldiscount+= $defaultdiscount;
                             }                       
                           $directcoupunCode = $checkBulk->code;
                           $direcrdiscountquantity=$defaultqtforDiscount;
                           $directcoupondiscount=$defaultdiscount;
                           $totaldirectDiscount+=$directcoupondiscount;
                         }
                          $array['coupen']=$request->coupencode;
                          $array['discountquantity']=$coupondiscountquantity;
                          $array['discount']=$coupondiscount;
                          $array['directdiscount']='No';
                         
                          if($totaldirectDiscount > $totaldiscount) /// checking which is higher
                          {
                            $totaldiscount=$totaldirectDiscount;
                            $array['coupen']=$directcoupunCode;
                            $array['discountquantity']=$direcrdiscountquantity;
                            $array['discount']=$directcoupondiscount;
                            $array['directdiscount']='Yes';
                            $directdiscount+=1;
                          }

                      }
                      $totaldiscount = round($totaldiscount,2);
                      ///////////////apply custom taxes set on event by organiser///////
                      $taxableAmount = ($selectedQuantity*$price)-$array['discount'];
                      if(count($eventextraserviceCharges)>0 && !empty($eventextraserviceCharges))
                      {
                        foreach($eventextraserviceCharges as $extrataxvaluekey=>$extrataxvalue)
                        {
                          $ticketsArray = explode(',',$extrataxvalue['ticket_id']);
                          if(in_array($value->id,$ticketsArray))
                          {
                            $cusstomtaxperticket=$taxableAmount*$extrataxvalue['rate_of_intrest'];
                            $customtaxdata[$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['taxid']=$extrataxvaluekey;
                            $customtaxdata[$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['taxamount']=$cusstomtaxperticket;
                            $customtaxdata[$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['taxrate']=$extrataxvalue['rate_of_intrest'];
                            $totalExtraChargesArray[$extrataxvaluekey]['amount'][]=$cusstomtaxperticket;
                            if(in_array($eventDetail->user_id,config('commondata.CUSTOM_USERID')))
                            {
                              $customtaxdata[$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['st']=$taxableAmount*0.14;
                              $customtaxdata[$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['sbc']=$taxableAmount*0.005;
                              $customtaxdata[$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['kkc']=$taxableAmount*0.005;
                              $totalExtraChargesArray[$extrataxvaluekey]['st'][]=$taxableAmount*0.14;
                              $totalExtraChargesArray[$extrataxvaluekey]['sbc'][]=$taxableAmount*0.005;
                              $totalExtraChargesArray[$extrataxvaluekey]['kkc'][]=$taxableAmount*0.005;
                              $totalcustomservicetaxperticket+=$taxableAmount*0.14+$taxableAmount*0.005+$taxableAmount*0.005;
                            }
                            $totalcusstomtaxperticket+=$cusstomtaxperticket;
                          }
                        }
                        // $passonfee=$totalcusstomtaxperticket;
                        $pricenew=$price+$totalcusstomtaxperticket;
                        $taxableAmount = ($selectedQuantity*$price)+$totalcusstomtaxperticket-$array['discount'];
                      }
                      $array['totalcusstomtax']=$totalcusstomtaxperticket;
                      $array['customtaxArray']=$customtaxdata;
                      $totalcustomtax+=$totalcusstomtaxperticket;
                      $totalcustomservicetax+=$totalcustomservicetaxperticket;

                      //////check tax is applicable or not////
                      if($_ENV['TAX_APPLICABLE']=='Yes' && !in_array($request->eventId,config('commondata.Excluded_Events_For_Tax')))
                      {
                        $eventidnotInaray = array('488','420','421','401','408','400','497','426','401','418','419','451');
                        if($taxableAmount>0 && !in_array($request->eventId, $eventidnotInaray)) 
                        {
                          //dd($chargesArray);
                          $cmpGocharges = 0;
                          $totalGoCharges = 0;
                          $abserverfee= 0;
                          $passonfee= 0;
                         
                          if($chargesArray["mode"]==2) // if extra charges are set in %
                          {
                             $pg_charges = ($taxableAmount*$chargesArray["pg_charge"])/100;
                             $go_charges = ($taxableAmount*$chargesArray["go_charge"])/100;
                             if(in_array($eventDetail->user_id,config('commondata.CUSTOM_USERID')))
                             {
                                $oldtaxableAmount = ($selectedQuantity*$price)-$array['discount'];
                                $go_charges = ($oldtaxableAmount*$chargesArray["go_charge"])/100;
                             }
                             
                             $cmpGocharges = $chargesArray["max_go_fee"];
                             $per_ticket_charges = $chargesArray["per_ticket_charge"]*$selectedQuantity;
                             if(($go_charges > $cmpGocharges) and $chargesArray["max_go_fee"]>0)
                             {
                               $totalGoCharges = $cmpGocharges;
                               $maxgoeventfee=$cmpGocharges;
                             }
                            else
                            {
                              $totalGoCharges = $go_charges; 
                            }
                          } 
                          else 
                          {
                            $pg_charges = $chargesArray["pg_charge"];
                            $go_charges =   $selectedQuantity*$chargesArray["go_charge"];
                            $cmpGocharges = $chargesArray["max_go_fee"];
                            $per_ticket_charges = $chargesArray["per_ticket_charge"]*$selectedQuantity;
                            if(($go_charges > $cmpGocharges) and $chargesArray["max_go_fee"]>0)
                            {
                               $totalGoCharges = $cmpGocharges;
                               $maxgoeventfee=$cmpGocharges;
                            }
                            else
                            {
                              $totalGoCharges = $go_charges;   
                            }
                          }
                       
                          if(count($defaultserviceCharges)>0)
                          {
                             $taxonAmount = $totalGoCharges+$per_ticket_charges;
                             $pg_fee_fortax=$pg_charges;
                             foreach($defaultserviceCharges as $taxname=>$taxvalue)
                              {
                                $pgtax = $taxvalue*($pg_fee_fortax);
                                $gofee = $taxvalue*($taxonAmount);
                                ///calculate taxes passon and obsorv///
                                $passonpgAmount=0;
                                $passongofeeAmount=0;
                                $abservergofeeAmount=0;
                                $abserverpgAmount=0;
                                if($value->payment_gateway_fee==2) // 2 is absorb and 1 is passon
                                {
                                  $abserverpgAmount=$pgtax;
                                }
                                else
                                {
                                  $passonpgAmount=$pgtax;
                                }
                                if($value->goeventz_fee==2) // 2 is absorb and 1 is passon
                                {
                                  $abservergofeeAmount=$gofee;
                                }
                                else
                                {
                                  $passongofeeAmount=$gofee;
                                }
                                $taxdata['pgfee'][$taxname]= $pgtax;
                                $taxdata['gofee'][$taxname]=$gofee;
                                $totaltaxArray[$taxname]['amount'][]=$pgtax+$gofee;
                                $totaltaxArray[$taxname]['passon'][]=$passonpgAmount+$passongofeeAmount;
                                $totaltaxArray[$taxname]['absorve'][]=$abserverpgAmount+$abservergofeeAmount;
                                $sepratetaxArray['pgtax'][$taxname]['amount'][]=$passonpgAmount;
                                $sepratetaxArray['goeventztax'][$taxname]['amount'][]=$passongofeeAmount;
                                $totalpgtax+=$pgtax;
                                $totalgofeetax+=$gofee;
                                $totaltaxonticket+=$pgtax+$gofee;
                              }
                          }        
                          if($value->payment_gateway_fee==2) // 2 is absorb and 1 is passon
                          {
                            $servicetaxobserv+=$totalpgtax;
                            $abserverfee=$pg_charges+$totalpgtax;  
                            $totalPGObsorve+= $pg_charges;
                            $array['pgFeeObserve'] = $pg_charges;
                          }
                          else
                          {
                            $servicetaxpass+=$totalpgtax;
                            $passonfee=$pg_charges+$totalpgtax;
                            $totalPGPasson+= $pg_charges; 
                            $array['pgFeePasson'] = $pg_charges; 
                          }
                          if($value->goeventz_fee==2)
                          {
                            $servicetaxobserv+=$totalgofeetax;
                            $passonfeeGov=2;
                            $abserverfee+=$per_ticket_charges+$totalGoCharges+$totalgofeetax;
                            $totalGEObsorve += $totalGoCharges;
                            $totalperticketObsorve += $per_ticket_charges;
                            $array['goeventObserve'] = $totalGoCharges;
                            $array['perticketObserve'] = $per_ticket_charges;
                          }
                          else
                          {
                            $servicetaxpass+=$totalgofeetax;
                            $passonfee+=$per_ticket_charges+$totalGoCharges+$totalgofeetax;
                            $totalGEPasson += $totalGoCharges;
                            $totalperticketPasson += $per_ticket_charges;
                            $array['goeventpasson'] = $totalGoCharges;
                            $array['perticketpasson'] = $per_ticket_charges;
                            $passonfeeGov=1;
                          }
                          $array['pg_charges']=$pg_charges;
                          $array['goeventz_fee']=$totalGoCharges;
                          $array['per_ticket_fee']=$per_ticket_charges; 
                          $array['totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                          $array['absorvefee']=$abserverfee;
                          $array['passonfee']=$passonfee;
                          $array['taxArray']=$taxdata;
                          $array['totalpgtax']=$totalpgtax;
                          $array['totalgo']=$totalgofeetax;
                          $array['servicetaxobserv']=$servicetaxobserv;
                          $array['servicetaxpass']=$servicetaxpass;
                          $array['totalServiceTax']=$totaltaxonticket;
                          $array['totalcharges']=$pg_charges+$totalGoCharges+$per_ticket_charges+$totaltaxonticket;
                        
                          $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;

                          $paymentgetwayfee+= $pg_charges;
                          $goeventzfee+=$totalGoCharges;
                          $perticketfee+=$per_ticket_charges;
                          $totalpasson+=$passonfee;
                          $totalabsorvefee+=$abserverfee;
                          $totalSerViceTaxpasson+=$servicetaxpass;
                          $totalServiceTaxabsorve+=$servicetaxobserv;
                          // $ticketArray[$value->id]=$array;
                          $totalSerViceTax+=$totaltaxonticket;
                                   
                        }
                      }
                      else
                      {
                        $tax_id=0;
                        $default_tax_id=0;
                      }
                   }
                   else
                   {
                    $tax_id=0;
                    $default_tax_id=0;
                   }
                     $ticketArray[$value->id]=$array;
                }
                $totalQuantity+=$selectedQuantity;          //summing up quantity
                $totalAmount+=($selectedQuantity*$price);   // summing up amount
                if($formRequired==0 && $value->registration_form==1)
                {
                  $formRequired=1;
                }
             }
             else
             {
               unset($ticketArray[$value->id]);
             }
            } // end of for loop
   
                               // Building final array
          $totalorderAmout = $totalAmount-$totaldiscount+$totalpasson+$totalcustomtax;
          //dd($totalorderAmout);
          $totalorderprice = round($totalAmount,2);
          $internetCharge =0;
          $internetdiscount =0;
          $servicetax=0;
          $goeventzDiscount=0;
          $evntlIdarray = array('881');

          if($request->coupencode==env('GOEVENTZ_CODE') && !in_array($request->eventId,$evntlIdarray))
          {
            $goeventzDiscount=1;
            $coupunCode = env('GOEVENTZ_CODE');
            $totaldiscount = round($totalorderprice*env('GOEVENTZ_DISCOUNT')/100,1);
            if($totaldiscount> env('MAX_GOEVENTZ_DISCOUNT'))
            {
              $totaldiscount = env('MAX_GOEVENTZ_DISCOUNT');
              $totalorderAmout = $totalorderAmout-$totaldiscount;
              $totalorderprice = $totalAmount;
            }
            else
            {
              $totalorderAmout = $totalorderAmout-$totaldiscount;
              $totalorderprice = $totalAmount;
            }
          }
          else if($request->coupencode && !in_array($request->eventId,$evntlIdarray))
          {

            if(strtolower($eventDetail->category)=='new year')
            {
            $conditionCoupon = array('event_id'=>0,'code'=>$request->coupencode,'status'=>1,'coupon_type'=>3);
            $checkCoupen = $this->discount->getBy($conditionCoupon);
            if($checkCoupen && $checkCoupen->remaining_quantity>0 && date('Y-m-d')<='2016-12-31')
            {
              $goeventzDiscount=1;
              $coupunCode = $checkCoupen->code;
              $totaldiscount = round($totalorderprice*$checkCoupen->amount/100,1);
              //dd($totaldiscount);
              if($totaldiscount>$checkCoupen->max_global_discount &&  $checkCoupen->max_global_discount>0)
              {
                $totaldiscount =   $checkCoupen->max_global_discount;
                $totalorderAmout = $totalorderAmout-$totaldiscount;
                $totalorderprice = $totalAmount;
              }
              else
              {
                $totalorderAmout = $totalorderAmout-$totaldiscount;
                $totalorderprice = $totalAmount;
              }
            }
            }
            else{  // added for Events with global discount coupon
              $conditionCoupon = array('event_id'=>$request->eventId,'code'=>$request->coupencode,'status'=>1,'coupon_type'=>3);
              $checkCoupen = $this->discount->getBy($conditionCoupon);
            if($checkCoupen && $checkCoupen->remaining_quantity>0)
            {
              $goeventzDiscount=1;
              $coupunCode = $checkCoupen->code;
              $totaldiscount = round($totalorderprice*$checkCoupen->amount/100,1);
              //dd($totaldiscount);
              if($totaldiscount>$checkCoupen->max_global_discount &&  $checkCoupen->max_global_discount>0)
              {
                $totaldiscount =   $checkCoupen->max_global_discount;
                $totalorderAmout = $totalorderAmout-$totaldiscount;
                $totalorderprice = $totalAmount;
              }
              else
              {
                $totalorderAmout = $totalorderAmout-$totaldiscount;
                $totalorderprice = $totalAmount;
              }
            }
            }
          }
         
                 //////////check extra discount/////////////
          $result['response']['calculatedDetails']['extracharges']['total']=0;

          $totalpasson = $totalpasson+$totalcustomtax;
          $totalabsorvefee = $totalabsorvefee;
           $currancyname='';
          $getCurrancy = $this->currancy->find($currencyId);
          if($getCurrancy)
          {
            $currancyname =$getCurrancy->short_name;
          }
          
          if($request->book!=3 && ($request->eventId==env('CUSTOM_SEAT_EVENTID') || $request->eventId==env('CUSTOMTAX_EVENTID')  || $request->eventId==env('CUSTOMTAX_EVENTID_ARIJIT1') 
            || $request->eventId==env('CUSTOMTAX_EVENTID_ARIJIT2') || $request->eventId==env('CUSTOMTAX_EVENTID_11112') || $request->eventId==env('CUSTOMTAX_EVENTID_11076') || $request->eventId==env('CUSTOMTAX_EVENTID_497') || $request->eventId==env('CUSTOMTAX_EVENTID_498') || $request->eventId==env('CUSTOMTAX_EVENTID_13094')  || $request->eventId==env('CUSTOMTAX_EVENTID_13081') 
            || $request->eventId==env('CUSTOMTAX_EVENTID_13119') || $request->eventId==env('CUSTOMTAX_EVENTID_13082') || $request->eventId==env('CUSTOMTAX_EVENTID_13104') || $request->eventId==env('CUSTOMTAX_EVENTID_13124') || $request->eventId==env('CUSTOMTAX_EVENTID_13096') || $request->eventId==env('CUSTOMTAX_EVENTID_14481') || $request->eventId==env('CUSTOMTAX_EVENTID_14886') || $request->eventId==env('CUSTOMTAX_EVENTID_15004') || $request->eventId==env('CUSTOMTAX_EVENTID_15656')   || $request->eventId==env('CUSTOMTAX_EVENTID_16553') || $request->eventId==env('CUSTOMTAX_EVENTID_16715') ))
          {
              $totalorderAmout = $totalAmount-$totaldiscount;
             
            if($request->eventId==env('CUSTOMTAX_EVENTID') )
            {
              $ST=round(env('ST_TAX')*$totalorderAmout,2);
              // echo $ST.'--';
              $SBT=round(env('SBT_TAX')*$totalorderAmout,2);
              // echo $SBT.'--';
              $amountPlusTax=$totalorderAmout+$ST+$SBT;
              $con_fee=env('CONFEE_TAX');
            }
            else
            {
              $ST=0;
              $SBT=0;
              $amountPlusTax=$totalorderAmout+$ST+$SBT;
              $con_fee=env('CONFEE_TAX_ARIJIT');
            }
            // echo $amountPlusTax.'--';

              $CF=round($con_fee*$amountPlusTax,2);
              $CFST=round(env('ST_TAX')*$CF,2);
              $CFSBT=round(env('SBT_TAX')*$CF,2);
              if($request->eventId==env('CUSTOM_SEAT_EVENTID'))
              {
                $CF=$totalQuantity*10;
                $CFST=0;
                $CFSBT=0;
              }
              if($request->eventId==env('CUSTOMTAX_EVENTID_11112') || $request->eventId==env('CUSTOMTAX_EVENTID_11076') || $request->eventId==env('CUSTOMTAX_EVENTID_497') || $request->eventId==env('CUSTOMTAX_EVENTID_498'))
              {
                $ST=round(env('ST_TAX_CUSTOM')*$totalorderAmout,2);
                $amountPlusTax=$totalorderAmout+$ST;
                $CF=$totalQuantity*15;
                $CFST=0;
                $CFSBT=0;
              }
              if($request->eventId==env('CUSTOMTAX_EVENTID_13094')  || $request->eventId==env('CUSTOMTAX_EVENTID_13081') 
            || $request->eventId==env('CUSTOMTAX_EVENTID_13119') || $request->eventId==env('CUSTOMTAX_EVENTID_13082') || $request->eventId==env('CUSTOMTAX_EVENTID_13104') || $request->eventId==env('CUSTOMTAX_EVENTID_13124') || $request->eventId==env('CUSTOMTAX_EVENTID_13096'))
              {
                $CF=round(env('ST_TAX_WORLD_MUSIC_CF')*$totalorderAmout,2);
                $CFST=round(env('ST_TAX_WORLD_MUSIC')*$CF,2);
                $CFSBT=0;
              }
              if($request->eventId==env('CUSTOMTAX_EVENTID_14481') )
              {
                
                $CF=round(env('CONFEE_TAX_14481')*$totalorderAmout,2);
                $CFST=round(env('ST_TAX_CUSTOM_14481')*$CF,2);
                $CFSBT=0;
              }
              if($request->eventId==env('CUSTOMTAX_EVENTID_14886'))
              {
                $pgfee=0;//round(env('PG_FEE_14486')*$totalorderAmout,2);
                $gofee=round(env('GO_FEE_14486')*$totalorderAmout,2)+env('perticket_FEE_14486');
                $CF=$pgfee+$gofee;
                $CFST=round(env('ST_TAX_CUSTOM_14486')*$gofee,2);

              }
              if($request->eventId==env('CUSTOMTAX_EVENTID_15656'))
              {
                $ST=round(env('ST_TAX_CUSTOM_15656')*$totalorderAmout,2);
                $amountPlusTax=$totalorderAmout+$ST;
                $CF=0;
                $CFST=0;
                $CFSBT=0;

              }
              if($request->eventId==env('CUSTOMTAX_EVENTID_16553') || $request->eventId==env('CUSTOMTAX_EVENTID_16715') || $request->eventId==env('CUSTOMTAX_EVENTID_15004') )
              {
                $CF=round(env('ST_TAX_CUSTOM_16553')*$totalorderAmout,2);
                $CFST=round(env('CFS_TAX_CUSTOM_16553')*$CF,2);
              }
              $totalExtraCharges=$ST+$SBT+$CF+$CFST+$CFSBT;
              // dd($totalExtraCharges);
              $totalorderAmout=$amountPlusTax+$CF+$CFST+$CFSBT;

               $result['response']['calculatedDetails']['extracharges']['total']=$totalExtraCharges;
               if($request->eventId==env('CUSTOMTAX_EVENTID') )
               {
                 $result['response']['calculatedDetails']['extracharges']['front'][0]['name']='Service Tax';
                 $result['response']['calculatedDetails']['extracharges']['front'][0]['fixedvalue']=env('ST_TAX')*100;
                 $result['response']['calculatedDetails']['extracharges']['front'][0]['amount']=$ST;
                 $result['response']['calculatedDetails']['extracharges']['front'][1]['name']='Swachh Bharat Cess';
                 $result['response']['calculatedDetails']['extracharges']['front'][1]['fixedvalue']=env('SBT_TAX')*100;
                 $result['response']['calculatedDetails']['extracharges']['front'][1]['amount']=$SBT;
              }else
              if($request->eventId==env('CUSTOMTAX_EVENTID_11112') || $request->eventId==env('CUSTOMTAX_EVENTID_11076') || $request->eventId==env('CUSTOMTAX_EVENTID_497') || $request->eventId==env('CUSTOMTAX_EVENTID_498'))
              {
                $result['response']['calculatedDetails']['extracharges']['front'][0]['name']='Service Tax';
                $result['response']['calculatedDetails']['extracharges']['front'][0]['fixedvalue']=env('ST_TAX_CUSTOM')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][0]['amount']=$ST;
                $result['response']['calculatedDetails']['extracharges']['front'][2]['name']='Convenience Fee';
               $result['response']['calculatedDetails']['extracharges']['front'][2]['fixedvalue']=$con_fee*100;
               $result['response']['calculatedDetails']['extracharges']['front'][2]['amount']=$CF;

              }else
              if($request->eventId==env('CUSTOMTAX_EVENTID_13094')  || $request->eventId==env('CUSTOMTAX_EVENTID_13081') || $request->eventId==env('CUSTOMTAX_EVENTID_13119') || $request->eventId==env('CUSTOMTAX_EVENTID_13082') || $request->eventId==env('CUSTOMTAX_EVENTID_13104') || $request->eventId==env('CUSTOMTAX_EVENTID_13124') || $request->eventId==env('CUSTOMTAX_EVENTID_13096'))
              {
                $result['response']['calculatedDetails']['extracharges']['front'][2]['name']='Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][2]['fixedvalue']=env('ST_TAX_WORLD_MUSIC_CF')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][2]['amount']=$CF;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['name']='Service Tax on Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][3]['fixedvalue']=env('ST_TAX_WORLD_MUSIC')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['amount']=$CFST;

              }else
               if($request->eventId==env('CUSTOMTAX_EVENTID_14481') )
              {
                $result['response']['calculatedDetails']['extracharges']['front'][2]['name']='Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][2]['fixedvalue']=env('CONFEE_TAX_14481')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][2]['amount']=$CF;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['name']='Service Tax on Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][3]['fixedvalue']=env('ST_TAX_CUSTOM_14481')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['amount']=$CFST;

              }else
              if($request->eventId==env('CUSTOMTAX_EVENTID_14886'))
              {
                $result['response']['calculatedDetails']['extracharges']['front'][2]['name']='Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][2]['fixedvalue']=env('PG_FEE_14486')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][2]['amount']=$CF;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['name']='Service Tax on Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][3]['fixedvalue']=env('ST_TAX_CUSTOM_14486')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['amount']=$CFST;

              }
              else if($request->eventId==env('CUSTOMTAX_EVENTID_15656'))
              {
                $result['response']['calculatedDetails']['extracharges']['front'][0]['name']='Service Tax';
                $result['response']['calculatedDetails']['extracharges']['front'][0]['fixedvalue']=env('ST_TAX_CUSTOM_15656')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][0]['amount']=$ST;

              }
              else if($request->eventId==env('CUSTOMTAX_EVENTID_16553') || $request->eventId==env('CUSTOMTAX_EVENTID_15004') || $request->eventId==env('CUSTOMTAX_EVENTID_16715'))
              {
                $result['response']['calculatedDetails']['extracharges']['front'][2]['name']='Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][2]['fixedvalue']=env('ST_TAX_CUSTOM_16553')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][2]['amount']=$CF;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['name']='Service Tax on Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][3]['fixedvalue']=env('CFS_TAX_CUSTOM_16553')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['amount']=$CFST;
              

              }
              else
              {
                $result['response']['calculatedDetails']['extracharges']['front'][2]['name']='Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][2]['fixedvalue']=$con_fee*100;
                $result['response']['calculatedDetails']['extracharges']['front'][2]['amount']=$CF;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['name']='Service Tax on Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][3]['fixedvalue']=env('ST_TAX')*100;
                $result['response']['calculatedDetails']['extracharges']['front'][3]['amount']=$CFST;
                $result['response']['calculatedDetails']['extracharges']['front'][4]['name']='Swachh Bharat Cess on Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][4]['fixedvalue']=env('SBT_TAX')*100;           
                $result['response']['calculatedDetails']['extracharges']['front'][4]['amount']=$CFSBT;

              }
              $result['response']['calculatedDetails']['passonfee']=$totalExtraCharges;
              $result['response']['calculatedDetails']['totalServiceTax'] =$totalExtraCharges;
              $result['response']['calculatedDetails']['totalcustomtax']=0;
          }// end of multiple if condition checkign event ids
          else
          {
            $chargetype='';
            if(isset($checkExtracharge->mode))
            {
              if($checkExtracharge->mode==2)
              {
                $chargetype='%';
              }
            }

            $result['response']['calculatedDetails']['extracharges']['detail'][5]['name']='Payment Gateway Fee';
            $result['response']['calculatedDetails']['extracharges']['detail'][5]['fixedvalue']= $chargesArray["pg_charge"];
            $result['response']['calculatedDetails']['extracharges']['detail'][5]['amount']=$paymentgetwayfee;
            $result['response']['calculatedDetails']['extracharges']['detail'][6]['name']='Goeventz Fee';
            $result['response']['calculatedDetails']['extracharges']['detail'][6]['fixedvalue']=$chargesArray['go_charge'];
            $result['response']['calculatedDetails']['extracharges']['detail'][6]['amount']=$goeventzfee;
            $result['response']['calculatedDetails']['extracharges']['detail'][7]['name']='Per Ticket Fee';
            $result['response']['calculatedDetails']['extracharges']['detail'][7]['fixedvalue']=$chargesArray['per_ticket_charge'];
            $result['response']['calculatedDetails']['extracharges']['detail'][7]['amount']=$perticketfee;
            $result['response']['calculatedDetails']['pg_charges'] = $paymentgetwayfee;
            $result['response']['calculatedDetails']['goeventz_fee'] = $goeventzfee;
            $result['response']['calculatedDetails']['per_ticket_fee'] = $perticketfee;
            $result['response']['calculatedDetails']['totalgoeventzfee'] = $totalGoeventChargese;
            $result['response']['calculatedDetails']['pgFeePasson'] = $totalPGPasson;
            $result['response']['calculatedDetails']['pgFeeObserve'] = $totalPGObsorve;
            $result['response']['calculatedDetails']['goeventpasson'] = $totalGEPasson;
            $result['response']['calculatedDetails']['goeventObserve'] = $totalGEObsorve;
            $result['response']['calculatedDetails']['perticketObsorve'] = $totalperticketObsorve;
            $result['response']['calculatedDetails']['perticketPasson'] = $totalperticketPasson;
            $totalconveniencefee=$totalPGPasson+$totalGEPasson+$totalperticketPasson;
            // $result['response']['calculatedDetails']['extracharges']['detail']['ServiceTax'][2]['name']='Goeventz Fee '.$chargesArray['go_charge'].$chargetype.' + '.$currancyname.' '.$chargesArray['per_ticket_charge'].'/ Ticket';
            // $result['response']['calculatedDetails']['extracharges']['detail']['ServiceTax'][2]['amount']=$totalGEPasson+$totalperticketPasson;
      
 
            
              //dd($checkExtracharge);
            $result['response']['calculatedDetails']['totalServiceTaxpasson'] =$totalSerViceTaxpasson;
            $result['response']['calculatedDetails']['totalServiceTaxabsorve'] =$totalServiceTaxabsorve;
            $result['response']['calculatedDetails']['totalServiceTax'] =$totalSerViceTax+$totalcustomservicetax;
            $result['response']['calculatedDetails']['totalcustomtax']=$totalcustomtax;
            $result['response']['calculatedDetails']['totalcustomservicetax']=$totalcustomservicetax;
            $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
            $result['response']['calculatedDetails']['passonfee']=round($totalpasson,2);
            $result['response']['calculatedDetails']['absorvefee']=$totalabsorvefee;
            $result['response']['calculatedDetails']['extracharges']['total']=$totalExtraCharges+$totalcustomtax;
            
            $totalorderAmout = round($totalorderAmout,2);
            $displayAmount=0;
            $taxdisplayname='';
           
           if($totalcustomtax>0)
           {
              if(count($eventextraserviceCharges)>0 && !empty($eventextraserviceCharges))
              {
                $incr=8;
                $incrdetails=10;
                foreach($eventextraserviceCharges as $extrataxvaluekey=>$extrataxvalue)
                {
                    if(array_key_exists($extrataxvaluekey,$totalExtraChargesArray))
                    {
                      $totaleventcustomtax = array_sum($totalExtraChargesArray[$extrataxvaluekey]['amount']);
                      $result['response']['calculatedDetails']['extracharges']['detail'][$incrdetails]['name']=$extrataxvalue['service_charge_name'];
                      $result['response']['calculatedDetails']['extracharges']['detail'][$incrdetails]['fixedvalue']= $extrataxvalue['rate_of_intrest']*100;
                      $result['response']['calculatedDetails']['extracharges']['detail'][$incrdetails]['amount']=$totaleventcustomtax;
                      $result['response']['calculatedDetails']['extracharges']['front'][$incr]['name']=$extrataxvalue['service_charge_name'];
                      $result['response']['calculatedDetails']['extracharges']['front'][$incr]['fixedvalue']=$extrataxvalue['rate_of_intrest']*100;
                      $result['response']['calculatedDetails']['extracharges']['front'][$incr]['amount']=round($totaleventcustomtax,2);
                     
                      
                      $result['response']['calculatedDetails']['extracharges']['detail']['Customtax'][$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['taxid']=$extrataxvaluekey;
                      $result['response']['calculatedDetails']['extracharges']['detail']['Customtax'][$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['taxamount']=$totaleventcustomtax;
                      $result['response']['calculatedDetails']['extracharges']['detail']['Customtax'][$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['taxrate']=$extrataxvalue['rate_of_intrest']*100;
                      if(in_array($eventDetail->user_id,config('commondata.CUSTOM_USERID')))
                      {
                        $totalsccustomtax = array_sum($totalExtraChargesArray[$extrataxvaluekey]['st']);
                        $totalsbccustomtax = array_sum($totalExtraChargesArray[$extrataxvaluekey]['sbc']);
                        $totalkkccustomtax = array_sum($totalExtraChargesArray[$extrataxvaluekey]['kkc']);
                        $result['response']['calculatedDetails']['extracharges']['detail']['Customtax'][$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['Service Tax']=$totalsccustomtax;
                        $result['response']['calculatedDetails']['extracharges']['detail']['Customtax'][$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['Swachh Bharat Cess']=$totalsbccustomtax;
                        $result['response']['calculatedDetails']['extracharges']['detail']['Customtax'][$extrataxvalue['service_charge_name'].'--'.$extrataxvaluekey]['Krishi Kalyan Cess']=$totalkkccustomtax;
                        $result['response']['calculatedDetails']['extracharges']['front'][$incr]['details']['Service Tax']=round($totalsccustomtax,2);
                        $result['response']['calculatedDetails']['extracharges']['front'][$incr]['details']['Swachh Bharat Cess']=round($totalsbccustomtax,2);
                        $result['response']['calculatedDetails']['extracharges']['front'][$incr]['details']['Krishi Kalyan Cess']=round($totalkkccustomtax,2);
                      }
                      // $result['response']['calculatedDetails']['extracharges']['front'][$incr]['show_fixedvalue']='No';

                       $incr++;
                      $incrdetails++;
                    }
                }
              }
           }
           if($totalpasson-$totalcustomtax>0)
           {
              if(in_array($eventDetail->user_id,config('commondata.CUSTOM_USERID')))
              {
                
                // $result['response']['calculatedDetails']['extracharges']['sepratetaxArray']=$sepratetaxArray;
                $pgtotaltax=0;
                $gofeetotaltax=0;
                $result['response']['calculatedDetails']['extracharges']['front'][100]['details']['Convenience Fee']=round($totalGEPasson,2);
                $result['response']['calculatedDetails']['extracharges']['front'][101]['details']['Payment Gateway Fee']=round($totalPGPasson,2);
                if(array_key_exists('goeventztax', $sepratetaxArray))
                {
                  foreach($sepratetaxArray['goeventztax'] as $key=>$vals)
                  {
                    $result['response']['calculatedDetails']['extracharges']['front'][100]['details'][$key]=round(array_sum($vals['amount']),2);
                    $gofeetotaltax+=array_sum($vals['amount']);
                  }

                }
                if(array_key_exists('pgtax', $sepratetaxArray))
                {
                  foreach($sepratetaxArray['pgtax'] as $keypg=>$val)
                  {
                    $result['response']['calculatedDetails']['extracharges']['front'][101]['details'][$keypg]=round(array_sum($val['amount']),2);
                    $pgtotaltax+=array_sum($val['amount']);
                  }

                }
                $result['response']['calculatedDetails']['extracharges']['front'][101]['name']='Payment Gateway Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][101]['fixedvalue']= "";
                $result['response']['calculatedDetails']['extracharges']['front'][101]['amount']=round($pgtotaltax+$totalPGPasson,2);
                
                $result['response']['calculatedDetails']['extracharges']['front'][100]['name']='Convenience Fee';
                $result['response']['calculatedDetails']['extracharges']['front'][100]['fixedvalue']="";
                $result['response']['calculatedDetails']['extracharges']['front'][100]['amount']=round($gofeetotaltax+$totalGEPasson,2);

              }
              else
              {
                $result['response']['calculatedDetails']['extracharges']['front'][99]['name']='Service Charges';
                $result['response']['calculatedDetails']['extracharges']['front'][99]['fixedvalue']= round($totalpasson-$totalcustomtax,2);
                $result['response']['calculatedDetails']['extracharges']['front'][99]['amount']=round($totalpasson-$totalcustomtax,2);
                $result['response']['calculatedDetails']['extracharges']['front'][99]['details']['Convenience Fee']=round($totalconveniencefee,2);
             
              }
           }

           ////////for service tax display section////////
           if($totalSerViceTax>0)
           {
             if(count($defaultserviceCharges)>0)
              {
                $i=0;
                $j=1;
                foreach($defaultserviceCharges as $taxname=>$taxvalue)
                {
                  // $totaltax=0;
                  $taxdisplayname=$taxname.' '.($taxvalue*100).'%';
                  if(array_key_exists($taxname,$totaltaxArray))
                  {
                    $totaltax = array_sum($totaltaxArray[$taxname]['passon']);
                    $displayAmount+=$totaltax;
                  }
                    $result['response']['calculatedDetails']['extracharges']['detail'][$i]['name']=$taxname;
                    $result['response']['calculatedDetails']['extracharges']['detail'][$i]['fixedvalue']= $taxvalue*100;
                    $result['response']['calculatedDetails']['extracharges']['detail'][$i]['amount']=round($totaltax,2);
                    $result['response']['calculatedDetails']['extracharges']['detail']['ServiceTax'][$j]['name']=$taxdisplayname;
                    $result['response']['calculatedDetails']['extracharges']['detail']['ServiceTax'][$j]['amount']=$totaltax;
                    if($totalpasson-$totalcustomtax>0 && !in_array($eventDetail->user_id,config('commondata.CUSTOM_USERID')))
                    {
                      $result['response']['calculatedDetails']['extracharges']['front'][99]['details'][$taxdisplayname]=round($totaltax,2);
                    }
                    $i++;
                    $j++;
                }

                // $result['response']['calculatedDetails']['extracharges']['detail']['ServiceTax'][12]['name']='totaltaxArray';
                // $result['response']['calculatedDetails']['extracharges']['detail']['ServiceTax'][12]['amount']=$totaltaxArray;
              }

           }
          }
          $result['response']['calculatedDetails']['internetChargeRate']=$internetCharge;
          $result['response']['calculatedDetails']['internetdiscount']=$internetdiscount;
          if($totalorderAmout<=0)
          {
            $totalorderAmout = 0;
          }
          $result['response']['calculatedDetails']['ticketArray']=$ticketArray;
          $result['response']['calculatedDetails']['donateTicketArray']='null';
          $result['response']['calculatedDetails']['totalAmount']=$totalorderprice;
          $result['response']['calculatedDetails']['discount']=$totaldiscount;
          $result['response']['calculatedDetails']['directdiscount']=0;
          if($directdiscount>0)
          {
            $directdiscountFlag=1;
            $result['response']['calculatedDetails']['directdiscount']=1;    
          }
          $result['response']['calculatedDetails']['finalamount']=$totalorderAmout;       
          $result['response']['calculatedDetails']['totalQuantity']=$totalQuantity;
          $result['response']['calculatedDetails']['formRequired']=$formRequired;
          $result['response']['calculatedDetails']['currencyId']=$currencyId;
          $result['response']['calculatedDetails']['goeventzdiscount']=$goeventzDiscount;
          $isPayable=0;  // checking if the payment option is required or not
          $isFree=1;
          if($totalorderAmout>0)
          {
            $isPayable=1;
            $isFree=0;
          }
          $result['response']['calculatedDetails']['isPayable']=$isPayable;
         
               //update tickets on hold //
          $result['response']['calculatedDetails']['currancyname']=$currancyname;
          if($request->book)
          {
            if(isset($request->offlineAmount))
            {
              $offlinePayment = 2;
              if($request->bookfrom==5)
              {
                 $totalticket= array_sum($request->ticketArray);
                 $totalorderAmout = $request->offlineAmount/$totalticket;
                 $totalorderprice = $request->offlineAmount/$totalticket;
                 $totalQuantity=1;
                 $result['response']['calculatedDetails']['totalquantity']=1;
                 $result['response']['calculatedDetails']['finalamount']=$totalorderAmout;
                 $result['response']['calculatedDetails']['totalAmount']=$totalorderprice;
              }
              else
              {
                $totalorderAmout = $request->offlineAmount;
                $totalorderprice = $request->offlineAmount;
                $result['response']['calculatedDetails']['finalamount']=$totalorderAmout;
                $result['response']['calculatedDetails']['totalAmount']=$totalorderprice;
              }
            } 
            else 
            {
              $offlinePayment = 1;
            }
            $result['response']['calculatedDetails']['paymentMode']=$offlinePayment;
            $serializedData=json_encode($result);
            $hold_time=1200;
            $on_hold=1;
            $authUser = Auth::user();
              // $orderid=rand(100999999);            
            $orderDataArray = array(
                                    'event_id'=>$request->eventId,
                                    'show_id'=>$request->eventshowid,
                                    'show_date'=>$request->eventdate,
                                    'event_type'=>@$request->eventcreatetype,
                                    'on_hold'=>$on_hold,
                                    'pass_on_amount'=>round($totalpasson,2),
                                    'tax_id'=>$tax_id,
                                    'default_tax_id'=>$deafulttax_id,
                                    'absorb_amount'=>$totalabsorvefee,
                                    'hold_time'=>1200,
                                    'order_time'=>date('Y-m-d H:i:s'),
                                    'is_free'=>$isFree,
                                    'total_amount'=>$totalorderAmout,
                                    'total_quantity'=>$totalQuantity,
                                    'booking_from'=>$request->bookfrom,
                                    'payment_mode'=>$offlinePayment,
                                    'discount'=>$totaldiscount,
                                    'extra_charges'=>$totalExtraCharges,
                                    'custom_taxes'=>$totalcustomtax,
                                    'coupon_code'=>$coupunCode,
                                    'global_discount'=>$goeventzDiscount,
                                    'directdiscount'=>$directdiscountFlag,
                                    'details'=>$serializedData,
                                    'all_info'=>$formRequired,
                                    'currency_id'=>$currencyId,
                                    'created_by'=>$createdBy,
                                    'created_at'=>date('Y-m-d H:i:s'));
              $getguestusercokkies = $commonObj->getcokkies('guestusercokkies');
              //echo  $getguestusercokkies;
              if($getguestusercokkies!=false && @$request->bookfrom==0)
              {
                $gettrackid = $this->tracksource->getBy(array('guest_user_id'=>$getguestusercokkies),array('id'));
                if($gettrackid)
                {
                  $orderDataArray['track_source_id']=$gettrackid->id;
                }
              }
              // dd($orderDataArray);
              if($authUser)
              {
                 $orderDataArray['user_id']=$authUser->id;
              }
              if($request->bookfrom==5)
              {
                $totalticket= array_sum($request->ticketArray);
                $orderDataArray['group_id']=rand(10000,999999);
                for($i=1;$i<=$totalticket;$i++)
                {
                  $Orderdetail=$this->order->create($orderDataArray);
                  $orderid=$Orderdetail;
                }
                $updateArray = array('on_hold'=>$totalticket);
                $upadte=$this->ticket->increment($ticketIds[0],'on_hold',$totalticket);
              }
              else
              {
                $Orderdetail=$this->order->create($orderDataArray);
                $orderid=$Orderdetail;
                foreach($ticketIdslist as $ticket=>$values)
                {
                  if(array_key_exists($values, $ticketArray))
                    $quantityHold=$ticketArray[$values];
                  else if (array_key_exists($values, $donateTicketArray))
                    $quantityHold=$donateTicketArray[$values];
                    $updateArray = array('on_hold'=>$quantityHold['selectedQuantity']);
                    // $upadte=$this->ticket->updateticket($updateArray,array('id'=>$values));
                  $upadte=$this->ticket->increment($values,'on_hold',$quantityHold['selectedQuantity']);
                }
              }
              $result['response']['calculatedDetails']['orderId']=$orderid;
              $result['response']['calculatedDetails']['eventId']=$eventid_value;

          }
        }
        else
        {
          $result['response']['calculatedDetails']['bookingerror']='error';
        }
        }
        else
        {
          $result['response']['calculatedDetails']['bookingerror']='error';
        }
        
        return response()->json($result,200);
    }
    public function tickewidget(Request $request)
    {
        $commonObj = new Common();
        $eventData='';
        $ticketWidgetData='';
        $checkEventId = $this->event->getBy(array('id'=>$request->id,'recurring_type'=>0),array('id','title','city','country','banner_image','venue_name','start_date_time','end_date_time','timezone_id','recurring_type'));
        if($checkEventId)
        {
          $eventDetails = $this->eventdetail->findBy('event_id',$request->id,array('show_remaining_ticket','booknow_button_value'));
          $gettimezone =  $this->timezone->find($checkEventId->timezone_id);
          $getstartdate = $commonObj->ConvertGMTToLocalTimezone($checkEventId->start_date_time,$gettimezone->timezone);
          $getenddate   = $commonObj->ConvertGMTToLocalTimezone($checkEventId->end_date_time,$gettimezone->timezone);
           
          $today  = new DateTime();
          $past   = new DateTime($getenddate);
          if($past>$today)
          {
            
            $ticketwidget = $this->ticketwidget->getBy(array('event_id'=>$request->id,'status'=>1));
            if($ticketwidget)
            {

              if($checkEventId->banner_image)
              {
                $imageUrl = $_ENV['CF_LINK'].'/event/'.$checkEventId->id.'/banner/'.$checkEventId->banner_image;
              }
              else
              {
                 $imageUrl = '';
              }
              $ticketWidgetData=array('eventid'=>$checkEventId->id,
                                      'bannerimage'=>$imageUrl,
                                      'eventtitle'=>$checkEventId->title,
                                      'startdate'=>date('D, M j Y ,h:i A',strtotime($getstartdate)),
                                      'enddate'=>$getenddate,
                                      'venuename'=>$checkEventId->venue_name,
                                      'eventid'=>$checkEventId->id,
                                      'city'=>$checkEventId->city,
                                      'country'=>$checkEventId->country,
                                      'ticketdescription'=>$ticketwidget->show_ticket_description,
                                      'topcolor'=>$ticketwidget->top_background_color,
                                      'textcolor'=>$ticketwidget->text_color,
                                      'height'=>500,
                                      'width'=>600,
                                      'button_color'=>$ticketwidget->booknow_button_color,
                                      'banner'=>$ticketwidget->show_banner,
                                      'logo'=>$ticketwidget->show_logo,
                                      'showtitle'=>$ticketwidget->show_title,
                                      'eventdates'=>$ticketwidget->show_event_dates,
                                      'eventvenue'=>$ticketwidget->show_venue,
                                      'showremaining'=>$eventDetails->show_remaining_ticket);


                      //ticketbox dadta///
              $ticketarray = array();
              $ticketCommon =(object) array('id'=>$request->id,'eventcreatetype'=>$checkEventId->recurring_type,'timezonename'=>$gettimezone->timezone);  // here Id is eventID

              $responseTicket=$this->getTicketList($ticketCommon,'array');
              $ticketarray=$responseTicket['ticketarray'];
              $coupunapply=$responseTicket['coupuncode'];
              $textdispalyArray=$responseTicket['textdispalyArray'];
              // dd($responseTicket);

              return response()->json(['ticketWidgetData'=>$ticketWidgetData,
                                       'ticketarray' => $ticketarray,
                                       'coupuncode'=>$coupunapply,
                                       'textdispalyArray'=>$textdispalyArray]);
              }
              else
              {
                $ticketWidgetData='badrequest';
                return response()->json(['ticketWidgetData'=>$ticketWidgetData]);
              }
            }
            else
            {
                $ticketWidgetData='badrequest';
                return response()->json(['ticketWidgetData'=>$ticketWidgetData]);
            }
        }
        //return response()->json(['ticketWidgetData'=>$ticketWidgetData]);
    }

}
