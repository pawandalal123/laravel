<?php 
namespace App\Http\Controllers\Api;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Common;
use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\CollaboratorInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\EventcustomfieldInterface;
use Appfiles\Repo\EventcustomfieldsvalueInterface;
use Appfiles\Repo\BookingdetailsInterface;
use Appfiles\Repo\UserassignInterface;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\PaymenthistoryInterface;
use Appfiles\Repo\ExtrachargesInterface;
use Appfiles\Repo\AmountpaidInterface;
use Appfiles\Repo\ServicechargesInterface;
use Appfiles\Repo\WeightageInterface;
use Appfiles\Repo\SeatplanInterface;
use Appfiles\Repo\SlotInterface;
use Appfiles\Repo\ScheduleInterface;
use Appfiles\Repo\Marketing_activitylistInterface;
use Appfiles\Repo\Eventmarketing_historyInterface;
use Appfiles\Repo\RecurringeventInterface;
use Appfiles\Repo\CurrencyInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\Paginator;
use App\Model\Bookingdetail;
use DB;
use Auth;
use URL;


class Reportapi extends Controller 
{

   public function  __construct(EventInterface $event ,ExtrachargesInterface $extracharge,CollaboratorInterface $collaboratorInterface, UsersInterface $usersInterface,EventcustomfieldInterface $eventfeild , BookingdetailsInterface $bookingDetails ,
    EventcustomfieldsvalueInterface $customfieldsvalue,UserassignInterface $userassign,TicketInterface $ticketInterface,SeatplanInterface $seatplan,
    PaymenthistoryInterface $paymenthistory,AmountpaidInterface $amountpaid,
    ServicechargesInterface $servicecharge,WeightageInterface $weightage,
     Marketing_activitylistInterface $marketinglist,RecurringeventInterface $recurringevent,Eventmarketing_historyInterface $eventmarket,CurrencyInterface $currency,SlotInterface $slotlist,ScheduleInterface $schedule)
    {
        $this->event=$event;
        $this->bookingDetails=$bookingDetails;
        $this->eventfeild=$eventfeild;
        $this->customfieldsvalue = $customfieldsvalue;
        $this->collaboratorInterface = $collaboratorInterface;
        $this->usersInterface = $usersInterface;
        $this->userassign =$userassign;
        $this->seatplan = $seatplan;
        $this->paymenthistory =$paymenthistory;
        $this->ticketInterface = $ticketInterface;
        $this->amountpaid =$amountpaid;
        $this->extracharge = $extracharge;
        $this->recurringevent = $recurringevent;
        $this->servicecharge = $servicecharge;
        $this->weightage=$weightage;
        $this->marketinglist=$marketinglist;
        $this->eventmarket = $eventmarket;
        $this->currency= $currency;
        $this->slotlist=$slotlist;
        $this->schedule = $schedule;
 
    }
   
   public function eventreportlist(Request $request)
   {
      $completeformData = Input::all();
      @extract($completeformData);      
      $getrepotData = $this->geteventreportscurrency($request);
      return $getrepotData ;
   }
     public function eventreportsexcel(Request $request)
   {
      $completeformData = Input::all();
      @extract($completeformData);      
      $getrepotData = $this->geteventreportsnewcurrency($request);
      return $getrepotData ;
   }
   public function eventreportnologin(Request $request)
   {
      $completeformData = Input::all();
      @extract($completeformData);      
      $getrepotData = $this->geteventreports($request);
      return $getrepotData ;
   }
   public function allreportlist(Request $request)
   {
      $completeformData = Input::all();
      @extract($completeformData);
      $getrepotData = $this->getalleventreports($request);
      return $getrepotData ;
   }

   public function alleventsreport(Request $request)
   {
      $completeformData = Input::all();
      @extract($completeformData);
      $getrepotData = $this->completeeventreport($request);
      return $getrepotData ;
   }
   public function merketingreport(Request $request)
   {
      $completeformData = Input::all();
      @extract($completeformData);
      $getrepotData = $this->completeeventlist($request);
      return $getrepotData ;
   }


      //////////////////get specific event report order details////////////////
    private function geteventreports($request)
    {
        $eventid = 46;
        $status = 'allreports';
        $checkEventId = $this->event->find($eventid);
        $result='';
        //$customdetails='';
        //$customfeild='';
        $commonObj = new Common();
        $customfeildArray=array();
        $customdetails=array(); 
        $feildArray = array(); 
        $customfeilddetails = array();      
        $customfeilds = $this->eventfeild->getallBy(array('event_id'=>$eventid),array('name','id','ticket_id','type'));
        foreach($customfeilds as $customfeilds)
        {
          //$feildArray[]=$customfeilds->id;
          $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,'type'=>$customfeilds->type);

        }

        $getorderinfo = $this->customfieldsvalue->groupByall(array('event_id'=>$eventid),array('ticket_id','order_id','event_custom_fields_id'),'event_custom_fields_id');
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
        $getorderticket = $this->customfieldsvalue->DetailsById($feildArray,array('ticket_id','order_id','event_custom_fields_id','value','position'));
        if(count($getorderticket)>0)
        {
          foreach($getorderticket as $getorderticket)
          {
            
            if(array_key_exists($getorderticket->event_custom_fields_id, $customfeildArray[$getorderticket->ticket_id]))
            {
              $feildvalues[$getorderticket->order_id][$getorderticket->ticket_id][$getorderticket->position][$getorderticket->event_custom_fields_id]= $getorderticket->value; 
              if($customfeildArray[$getorderticket->ticket_id][$getorderticket->event_custom_fields_id]['type']=='file')
              {
                $feildvalues[$getorderticket->order_id][$getorderticket->ticket_id][$getorderticket->position][$getorderticket->event_custom_fields_id]= $imageUrl = $_ENV['CF_LINK'].'/event/'.$eventid.'/customfeild/'.$getorderticket->value;

              }
               
            }
           

          }
           $customdetails = $feildvalues;

        }
        if($checkEventId)
        {
          // if($status=="offline")
          //   $condition = array('event_id'=>$checkEventId->id,'payment_mode'=>2,'order_status'=>'completed');
          // elseif($status=="completed")
          //   $condition = array('event_id'=>$checkEventId->id,'order_status'=>$status,'payment_mode'=>0,'payment_mode'=>1);
          // else
            $condition = "event_id = '".$checkEventId->id."' and order_status='completed'";
            if($request->orderid)
            {
              $condition.= " and order_id = '".$request->orderid."'";
            }
            if($request->barcode)
            {
              $condition.= " and barcode = '".$request->barcode."'";
            }
            if($request->dateafter)
            {
              $condition.= " and CONVERT_TZ(order_time,'+00:00','+05:30') >= '".$request->dateafter."'";
            }

          $orderbookingDetails = $this->bookingDetails->getallByRaw($condition);
          if(count($orderbookingDetails)>0)
          {
            $totalAmount=0;
            $totalquantity=0;
             foreach($orderbookingDetails as $orderbookingDetails)
             {
                $bookingData = json_decode($orderbookingDetails->details);

                $ticketArray=array();

                foreach($bookingData->response->calculatedDetails->ticketArray as $ticketval)
                {
                  $arraytosplice = (array)$ticketval;
                  $ticketArray[] = array_splice($arraytosplice, 0,2);
                }
               
                $result[$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                'buyeremail'=>$orderbookingDetails->email,
                                                                'buyermobile'=>$orderbookingDetails->mobile,
                                                                'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                'quantity'=>$orderbookingDetails->total_quantity,
                                                                'amount'=>$orderbookingDetails->total_amount,
                                                                'barcode'=>$orderbookingDetails->barcode,
                                                                'ticketArray'=>$ticketArray,
                                                                'discount'=>$orderbookingDetails->discount,
                                                                'coupon_code'=>$orderbookingDetails->coupon_code);
                $totalAmount+=$orderbookingDetails->total_amount;
                $totalquantity+=$orderbookingDetails->total_quantity;
                     //make order custom field for order//
                if(!array_key_exists($orderbookingDetails->order_id, $customdetails))
                {
                  $customdetails[$orderbookingDetails->order_id] ='';
                }
               
            }
            
             return response()->json(['reportData' => $result,
                                      // 'customfeild'=>$customfeildArray,
                                      // 'customdetails' => $customdetails,
                                       'totalamount' => $totalAmount,
                                       'totalquanitity' => $totalquantity]);
          }
          else
          {
            $result='noorder';
            return response()->json(['reportData' => $result]);
          }
            
       }
        else
        {
            $result='error';
            return response()->json(['reportData' => $result]);
        }
     
    }

    private function geteventreportsnew($request)
    {
        $eventid = $request->id;
        $status= $request->status;
        $checkEventId = $this->event->find($eventid);
        $result='';
        //$customdetails='';
        //$customfeild='';
        $commonObj = new Common();
        $customfeildArray=array();
        $customdetails=array(); 
        $orderidArray=array();
        $feildArray = array(); 
        $customfeilddetails = array();
        $totaldetailArray = array();
        $chargesArray = array();
        $defaultserviceCharges=array();
        $totaltaxArray=array();
        $taxdata=array();
        $getcolumns=array();
         $extraColumns = array();
        $customfeilds = $this->eventfeild->getallBy(array('event_id'=>$eventid),array('name','id','ticket_id','type'));
        foreach($customfeilds as $customfeilds)
        {
          //$feildArray[]=$customfeilds->id;
          $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,'type'=>$customfeilds->type);
        }

        /////////service charges details/////////
        $eventservicecharges = $this->servicecharge->getList(array('event_id'=>$request->eventId,'status'=>1),'rate_of_intrest','service_charge_name');/////event extra charges
        if(count($eventservicecharges)>0)
        {
          foreach($eventservicecharges as $keyeventservice=>$valeventservice)
          {
            $defaultserviceCharges[$keyeventservice]=$valeventservice/100;
          }
        }
        else
        {
          $servicechardes = $this->servicecharge->getList(array('country_name'=>strtolower($checkEventId->country),'status'=>1),'rate_of_intrest','service_charge_name');/////country servicecharges
            if(count($servicechardes)>0)
            {
              foreach($servicechardes as $keyservice=>$valservice)
              {
                $defaultserviceCharges[$keyservice]=$valservice/100;
              }
          }
        }
           //////////exytracharges//////////////
        $checkExtracharge = $this->extracharge->getallBy(array());
        if(count($checkExtracharge)>0)
        {
          foreach ($checkExtracharge as $checkExtracharge) 
          {
            $chargesArray[$checkExtracharge->id] = array('pg_charge'=>$checkExtracharge->pg_charges,
                                  'go_charge'=>$checkExtracharge->goeventz_fee,
                                  'per_ticket_charge'=>$checkExtracharge->per_ticket_fee,
                                  'mode'=>$checkExtracharge->mode,
                                  'max_go_fee'=>$checkExtracharge->max_goeventz_fee);
          }

        }
        $seatselectedArray = array();
        if($eventid==env('CUSTOM_SEAT_EVENTID'))
        {
          $checkseat = $this->seatplan->getallBy(array('event_id'=>$eventid));
          if(count($checkseat)>0)
          {
            foreach ($checkseat as $checkseat) 
            {
              $seatselectedArray[$checkseat->order_id] = $checkseat->seat_selected;
            }
          }
        }
        if($checkEventId)
        {
          $user = Auth::user();
          $whereCondition =array('user_id'=>$user->id,"event_id"=>$checkEventId->id);
          $collaborator = $this->collaboratorInterface->getBy($whereCondition);

          if(count($collaborator)>0)
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2 and user_id ='".$user->id."'";
             //$condition = array('event_id'=>$checkEventId->id,'order_status'=>'completed','payment_mode'=>2,'user_id'=>$user->id);
          }else
          if($status=="offline")
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2";
            //$condition = array('event_id'=>$checkEventId->id,'payment_mode'=>2,'order_status'=>'completed');
          else 
            $condition = "event_id = '".$checkEventId->id."' and order_status ='".$status."' and payment_mode!=2";
            //$condition = array('event_id'=>$checkEventId->id,'order_status'=>$status,'payment_mode'=>0,'payment_mode'=>1);
          
          if(isset($_GET['colid']))
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2 and user_id ='".$_GET['colid']."'";
            // $condition = array('event_id'=>$checkEventId->id,'order_status'=>'completed','payment_mode'=>2,'user_id'=>$_GET['colid']);
          }
         
          if(isset($request->dateselect) && $request->dateselect!='')
          {
            $getdates = explode(',',$request->dateselect.',');
            if($getdates[1]=='')
            {
              $getdates[1] = $getdates[0];
            }
            $condition.= " and date(order_time) between '".$getdates[0]."' and '".$getdates[1]."'";
          }

          $orderbookingDetails = $this->bookingDetails->getallByRaw($condition,array('*'));

          if(count($orderbookingDetails)>0)
          {
            $totalAmount=0;
            $totalpgfee=0;
            $totalgoeventfee=0;
            $total_servicetax=0;
            $totalquantity=0;
            $totalSerViceTaxpasson=0;
            $totalServiceTaxabsorve=0;
            $calculatedDetails=array();
            $totalmarketingcharges=0;
            $totalpramotionaldiscount=0;
             foreach($orderbookingDetails as $orderbookingDetails)
             {
                $paymentgetwayfee= 0;
                $goeventzfee=0;
                $perticketfee=0;
                $totalGoeventChargese=0;
                $defaulttax='No';
                $totalExtraCharges=0;
                $totalSerViceTax=0;
               
                $showdate = $orderbookingDetails->show_date;
                if($orderbookingDetails->show_date=='0000-00-00')
                {
                  $showdate='';
                }
                $bookingData = json_decode($orderbookingDetails->details);
                if(isset($request->ticketid) && $request->ticketid!='')
                {
                   $ticketArray =array();
                   $ticketdetailsArray = array();
                   $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                   if(in_array($request->ticketid, array_keys($ticketArray)))
                   {
                      foreach($ticketArray as $ticketkey=>$ticketval)
                      {
                        if($ticketkey == $request->ticketid )
                        {
                          $orderidArray[]=$orderbookingDetails->order_id;
                          $ticketdetailsArray = (array)$ticketval;
                          //$bookingData->response->calculatedDetails->ticketArray->$ticketkey =
                          $bookingData->response->calculatedDetails->ticketArray = array($ticketkey=>$bookingData->response->calculatedDetails->ticketArray->$ticketkey);
                          if($orderbookingDetails->default_tax_id>0)
                          {
                            
                            //$calculatedDetails=array($bookingData->response->calculatedDetails);
                            if(array_key_exists($orderbookingDetails->default_tax_id, $chargesArray))
                            {
                              $newticketArray=array();
                              if(!empty($ticketArray))
                              {   $ticketid=$ticketkey;
                                  $defaulttax='Yes';
                                  $totaltaxonticket=0;
                                  $servicetaxpass=0;
                                  $servicetaxobserv=0;
                                  $totalpgtax=0;
                                  $totalgofeetax=0;
                                  $totalTax = 0;
                                  $pg_charges = 0;
                                  $go_charges = 0;
                                  $per_ticket_charges =0;
                                  $newticketArray[$ticketid]['name'] = $ticketval->name;
                                  $newticketArray[$ticketid]['selectedQuantity'] = $ticketval->selectedQuantity;
                                  $newticketArray[$ticketid]['price'] = $ticketval->price;
                                  $newticketArray[$ticketid]['coupen'] = $ticketval->coupen;
                                  $newticketArray[$ticketid]['discount'] = $ticketval->discount;
                                  $newticketArray[$ticketid]['pg_charges'] = $ticketval->pg_charges;
                                  $newticketArray[$ticketid]['goeventz_fee'] = $ticketval->goeventz_fee;
                                  $newticketArray[$ticketid]['totalgoeventzfee'] = $ticketval->totalgoeventzfee;
                                  $newticketArray[$ticketid]['per_ticket_fee'] = $ticketval->per_ticket_fee;
                                  $newticketArray[$ticketid]['absorvefee'] = $ticketval->absorvefee;
                                  $newticketArray[$ticketid]['passonfee'] = $ticketval->passonfee;
                                  $newticketArray[$ticketid]['goeventpasson'] = $ticketval->goeventpasson;
                                  $newticketArray[$ticketid]['goeventObserve'] = $ticketval->goeventObserve;
                                  $newticketArray[$ticketid]['pgFeeObserve'] = $ticketval->pgFeeObserve;
                                  $newticketArray[$ticketid]['pgFeePasson'] = $ticketval->pgFeePasson;
                                  $newticketArray[$ticketid]['perticketObserve'] = $ticketval->perticketObserve;
                                  $newticketArray[$ticketid]['perticketpasson'] = $ticketval->perticketpasson;
                                  $newticketArray[$ticketid]['taxArray'] = @$ticketval->taxArray;
                                  $newticketArray[$ticketid]['totalpgtax'] = @$ticketval->totalpgtax;
                                  $newticketArray[$ticketid]['totalgo'] = @$ticketval->totalgo;
                                  $newticketArray[$ticketid]['servicetaxobserv'] = @$ticketval->servicetaxobserv;
                                  $newticketArray[$ticketid]['servicetaxpass']=@$ticketval->servicetaxpass;
                                  $newticketArray[$ticketid]['totalServiceTax']=@$ticketval->totalServiceTax;
                                  $newticketArray[$ticketid]['totalcharges']=@$ticketval->totalcharges;
                                  $newticketArray[$ticketid]['totalcusstomtax']=@$ticketval->totalcusstomtax;
                                  $newticketArray[$ticketid]['customtaxArray']=@$ticketval->customtaxArray;
                                  $taxableAmount = ($ticketval->selectedQuantity*$ticketval->price)-$ticketval->discount;
                                  $cmpGocharges = 0;
                                  $totalGoCharges = 0;
                                  if($chargesArray[$orderbookingDetails->default_tax_id]["mode"]==2)
                                  {
                                     $pg_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"])/100;
                                     $go_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"])/100;
                                     $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                     $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                     if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                    $pg_charges = $chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"];
                                    $go_charges =   $ticketval->selectedQuantity*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"];
                                    $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                    $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                    // echo $go_charges;
                                    // echo $cmpGocharges;
                                    if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                        $taxdata['pgfee'][$taxname]= $pgtax;
                                        $taxdata['gofee'][$taxname]=$gofee;
                                        // $taxdata[$taxname]=$pgtax+$gofee;
                                        $totaltaxArray[$taxname][]=$pgtax+$gofee;
                                        $totalpgtax+=$pgtax;
                                        $totalgofeetax+=$gofee;
                                        $totaltaxonticket+=$pgtax+$gofee;
                                      }
                                  }
                                 // $serViceTax = env('ST_TAX')*($totalGoCharges+$per_ticket_charges);         
                                 // $swachBharatTax = env('SBT_TAX')*($totalGoCharges+$per_ticket_charges); 
                                 // $kishanTax = env('KBT_TAX')*($totalGoCharges+$per_ticket_charges); 
                                 $newticketArray[$ticketid]['default_taxArray'] = $taxdata;
                                 $newticketArray[$ticketid]['default_totalpgtax'] = $totalpgtax;
                                 $newticketArray[$ticketid]['default_totalgo'] = $totalgofeetax;
                                 $newticketArray[$ticketid]['default_pg_charges']=$pg_charges;
                                 $newticketArray[$ticketid]['default_goeventz_fee']=$totalGoCharges;
                                 $newticketArray[$ticketid]['default_per_ticket_fee']=$per_ticket_charges; 
                                 $newticketArray[$ticketid]['default_totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                                 $newticketArray[$ticketid]['default_totalServiceTax']=$totaltaxonticket;
                                 $newticketArray[$ticketid]['default_totalcharges']=$totalGoCharges+$per_ticket_charges+$totaltaxonticket+$pg_charges;
                                 $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;
                                 $paymentgetwayfee+= $pg_charges;
                                 $goeventzfee+=$totalGoCharges;
                                 $perticketfee+=$per_ticket_charges;
                                 $totalSerViceTax+=$totaltaxonticket;      
                                 $bookingData->response->calculatedDetails->ticketArray=$newticketArray;
                                 $bookingData->response->calculatedDetails->donateTicketArray=array();
                                $bookingData->response->calculatedDetails->default_pg_charges = $paymentgetwayfee;
                                $bookingData->response->calculatedDetails->default_goeventz_fee = $goeventzfee;
                                $bookingData->response->calculatedDetails->default_per_ticket_fee = $perticketfee;
                                $bookingData->response->calculatedDetails->default_totalgoeventzfee = $totalGoeventChargese;
                                $bookingData->response->calculatedDetails->default_totalServiceTax =$totalSerViceTax;
                                $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
                                $bookingData->response->calculatedDetails->default_total=$totalExtraCharges;
                              }
                            }
                          }
                          $orderamount = $ticketval->price*$ticketval->selectedQuantity -$ticketval->discount;
                          if($orderamount<0)
                          {
                            $orderamount=0;

                          }
                          if(array_key_exists('customtaxArray', $ticketdetailsArray) )
                          {
                            if(!empty($ticketdetailsArray['customtaxArray']))
                            {
                              foreach($ticketdetailsArray['customtaxArray'] as $extrafeildkey=>$extrafeildval)
                              {
                                 $extraColumns[]=$extrafeildkey;
                              }
                            }
                            
                            $bookingData->response->calculatedDetails->extracharges->detail->Customtax =$ticketdetailsArray['customtaxArray'];
                          }
                          $result[$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                          'buyeremail'=>$orderbookingDetails->email,
                                                                          'buyermobile'=>$orderbookingDetails->mobile,
                                                                          'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                          'quantity'=>$ticketval->selectedQuantity,
                                                                          'amount'=>$orderamount,
                                                                          'amountpaybale'=>$orderamount,
                                                                          'orderdetails'=>$bookingData->response->calculatedDetails,
                                                                          'discount'=>$ticketval->discount,
                                                                          'coupon_code'=>$ticketval->coupen,
                                                                          'extracharges'=>'',
                                                                          'extra_service'=>'',
                                                                          'default_tax'=>$defaulttax,
                                                                          'default_total'=>$totalExtraCharges,
                                                                          'show_date'=>$showdate);
                          if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                          {
                            $extracharges = round(($ticketdetailsArray['totalgoeventzfee']+$ticketdetailsArray['pg_charges']+$ticketdetailsArray['totalServiceTax']),2);
                            if(array_key_exists('totalcusstomtax', $ticketdetailsArray))
                            {
                              $extracharges = $extracharges+$ticketdetailsArray['totalcusstomtax'];
                            }
                            $amountpaybale = $orderamount-$ticketdetailsArray['absorvefee'];
                            if($amountpaybale<0)
                            {
                              $amountpaybale=0;
                            }
                            $result[$orderbookingDetails->order_id]['amount']=$orderamount+$extracharges-$ticketdetailsArray['absorvefee'];
                            $result[$orderbookingDetails->order_id]['amountpaybale']=$amountpaybale;
                            $result[$orderbookingDetails->order_id]['extracharges']=$extracharges;
                            if($defaulttax=='Yes')
                            {
                              if($extracharges>$totalExtraCharges)
                              {
                                $totalmarketingcharges+=$extracharges-$totalExtraCharges;
                              }
                              else
                              {
                                $totalpramotionaldiscount+=$totalExtraCharges-$extracharges;
                              }
                            }
                          }
                          if(array_key_exists('pg_charges', $ticketdetailsArray))
                          {
                            $totalpgfee+=$ticketdetailsArray['pg_charges'];
                          }
                          if(array_key_exists('totalServiceTax', $ticketdetailsArray))
                          {
                            $total_servicetax+=$ticketdetailsArray['totalServiceTax'];
                          }
                          if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                          {
                            $totalgoeventfee+=$ticketdetailsArray['totalgoeventzfee'];
                          }
                          $result[$orderbookingDetails->order_id]['seatselected']='';
                          if(array_key_exists($orderbookingDetails->order_id, $seatselectedArray))
                          {
                            $result[$orderbookingDetails->order_id]['seatselected']=$seatselectedArray[$orderbookingDetails->order_id];
                          }
                          
                          $totalAmount+=$orderamount;
                          $totalquantity+=$ticketval->selectedQuantity;
                          
                        }
                      }
                   }
                }
                else
                {
                  $orderidArray[]=$orderbookingDetails->order_id;
                  if($orderbookingDetails->default_tax_id>0)
                  {
                    //$calculatedDetails=array($bookingData->response->calculatedDetails);
                    if(array_key_exists($orderbookingDetails->default_tax_id, $chargesArray))
                    {
                      $newticketArray=array();
                      $newdonateticketArray=array();
                      $donateticketArray=array();
                      $ticketArray =array();
                      $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                      $donateticketArray = (array)$bookingData->response->calculatedDetails->donateTicketArray;
                      if(!empty($ticketArray))
                      {
                        $defaulttax='Yes';
                        foreach($ticketArray as $ticketid=>$ticketval)
                        {
                          $totaltaxonticket=0;
                          $servicetaxpass=0;
                          $servicetaxobserv=0;
                          $totalpgtax=0;
                          $totalgofeetax=0;
                          $totalTax = 0;
                          $pg_charges = 0;
                          $go_charges = 0;
                         
                          $per_ticket_charges =0;
                          $newticketArray[$ticketid]['name'] = $ticketval->name;
                          $newticketArray[$ticketid]['selectedQuantity'] = $ticketval->selectedQuantity;
                          $newticketArray[$ticketid]['price'] = $ticketval->price;
                          $newticketArray[$ticketid]['coupen'] = $ticketval->coupen;
                          $newticketArray[$ticketid]['discount'] = $ticketval->discount;
                          $newticketArray[$ticketid]['pg_charges'] = $ticketval->pg_charges;
                          $newticketArray[$ticketid]['goeventz_fee'] = $ticketval->goeventz_fee;
                          $newticketArray[$ticketid]['totalgoeventzfee'] = $ticketval->totalgoeventzfee;
                          $newticketArray[$ticketid]['per_ticket_fee'] = $ticketval->per_ticket_fee;
                          $newticketArray[$ticketid]['absorvefee'] = $ticketval->absorvefee;
                          $newticketArray[$ticketid]['passonfee'] = $ticketval->passonfee;
                          $newticketArray[$ticketid]['goeventpasson'] = $ticketval->goeventpasson;
                          $newticketArray[$ticketid]['goeventObserve'] = $ticketval->goeventObserve;
                          $newticketArray[$ticketid]['pgFeeObserve'] = $ticketval->pgFeeObserve;
                          $newticketArray[$ticketid]['pgFeePasson'] = $ticketval->pgFeePasson;
                          $newticketArray[$ticketid]['perticketObserve'] = $ticketval->perticketObserve;
                          $newticketArray[$ticketid]['perticketpasson'] = $ticketval->perticketpasson;
                          $newticketArray[$ticketid]['taxArray'] = @$ticketval->taxArray;
                          $newticketArray[$ticketid]['totalpgtax'] = @$ticketval->totalpgtax;
                          $newticketArray[$ticketid]['totalgo'] = @$ticketval->totalgo;
                          $newticketArray[$ticketid]['servicetaxobserv'] = @$ticketval->servicetaxobserv;
                          $newticketArray[$ticketid]['servicetaxpass']=@$ticketval->servicetaxpass;
                          $newticketArray[$ticketid]['totalServiceTax']=@$ticketval->totalServiceTax;
                          $newticketArray[$ticketid]['totalcharges']=@$ticketval->totalcharges;
                          $newticketArray[$ticketid]['totalcusstomtax']=@$ticketval->totalcusstomtax;
                          $newticketArray[$ticketid]['customtaxArray']=@$ticketval->customtaxArray;
                          $taxableAmount = ($ticketval->selectedQuantity*$ticketval->price)-$ticketval->discount;
                          $cmpGocharges = 0;
                          $totalGoCharges = 0;
                          if($chargesArray[$orderbookingDetails->default_tax_id]["mode"]==2)
                          {
                             $pg_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"])/100;
                             $go_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"])/100;
                             $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                             $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                             if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                            $pg_charges = $chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"];
                            $go_charges =   $ticketval->selectedQuantity*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"];
                            $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                            $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                            // echo $go_charges;
                            // echo $cmpGocharges;
                            if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                  $taxdata['pgfee'][$taxname]= $pgtax;
                                  $taxdata['gofee'][$taxname]=$gofee;
                                  // $taxdata[$taxname]=$pgtax+$gofee;
                                  $totaltaxArray[$taxname][]=$pgtax+$gofee;
                                  $totalpgtax+=$pgtax;
                                  $totalgofeetax+=$gofee;
                                  $totaltaxonticket+=$pgtax+$gofee;
                                }
                          } 
                         $newticketArray[$ticketid]['default_taxArray'] = $taxdata;
                         $newticketArray[$ticketid]['default_totalpgtax'] = $totalpgtax;
                         $newticketArray[$ticketid]['default_totalgo'] = $totalgofeetax;
                         $newticketArray[$ticketid]['default_pg_charges']=$pg_charges;
                         $newticketArray[$ticketid]['default_goeventz_fee']=$totalGoCharges;
                         $newticketArray[$ticketid]['default_per_ticket_fee']=$per_ticket_charges; 
                         $newticketArray[$ticketid]['default_totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                         $newticketArray[$ticketid]['default_totalServiceTax']=$totaltaxonticket;
                         $newticketArray[$ticketid]['default_totalcharges']=$totalGoCharges+$per_ticket_charges+$totaltaxonticket+$pg_charges;
                         $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;
                         $paymentgetwayfee+= $pg_charges;
                         $goeventzfee+=$totalGoCharges;
                         $perticketfee+=$per_ticket_charges;
                         $totalSerViceTax+=$totaltaxonticket;     
                        }
                        
                        $bookingData->response->calculatedDetails->donateTicketArray=$newdonateticketArray;
                        $bookingData->response->calculatedDetails->ticketArray=$newticketArray;
                        //$calculatedDetails=array($bookingData->response->calculatedDetails);
                        $bookingData->response->calculatedDetails->default_pg_charges = $paymentgetwayfee;
                        $bookingData->response->calculatedDetails->default_goeventz_fee = $goeventzfee;
                        $bookingData->response->calculatedDetails->default_per_ticket_fee = $perticketfee;
                        $bookingData->response->calculatedDetails->default_totalgoeventzfee = $totalGoeventChargese;
                        $bookingData->response->calculatedDetails->default_totalServiceTax =$totalSerViceTax;
                        $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
                        $bookingData->response->calculatedDetails->default_total=$totalExtraCharges;
                      }
                    }
                  }
                  $result[$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                   'buyeremail'=>$orderbookingDetails->email,
                                                                   'buyermobile'=>$orderbookingDetails->mobile,
                                                                   'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                   'quantity'=>$orderbookingDetails->total_quantity,
                                                                   'amount'=>$orderbookingDetails->total_amount,
                                                                   'amountpaybale'=>$orderbookingDetails->total_amount-$orderbookingDetails->extra_charges+$orderbookingDetails->extra_services_total,
                                                                   'orderdetails'=>$bookingData->response->calculatedDetails,
                                                                   'discount'=>$orderbookingDetails->discount,
                                                                   'coupon_code'=>$orderbookingDetails->coupon_code,
                                                                   'extracharges'=>$orderbookingDetails->extra_charges,
                                                                   'default_tax'=>$defaulttax,
                                                                   'extra_service'=>json_decode($orderbookingDetails->extra_services,true),
                                                                   'default_total'=>$totalExtraCharges,
                                                                   'show_date'=>$showdate);
                  $result[$orderbookingDetails->order_id]['seatselected']='';
                  if($defaulttax=='Yes')
                  {
                    if($orderbookingDetails->extra_charges>$totalExtraCharges)
                    {
                      $totalmarketingcharges+=$orderbookingDetails->extra_charges-$totalExtraCharges;
                    }
                    else
                    {
                      $totalpramotionaldiscount+=$totalExtraCharges-$orderbookingDetails->extra_charges;
                    }
                  }
                  if(array_key_exists('pg_charges', (array)$bookingData->response->calculatedDetails))
                  {
                    $totalpgfee+=$bookingData->response->calculatedDetails->pg_charges;
                  }
                  if(array_key_exists('totalServiceTax', (array)$bookingData->response->calculatedDetails))
                  {
                    if(array_key_exists('totalcustomservicetax', (array)$bookingData->response->calculatedDetails))
                    {
                      $total_servicetax+=$bookingData->response->calculatedDetails->totalServiceTax-$bookingData->response->calculatedDetails->totalcustomservicetax;
                    }
                    else
                    {
                      $total_servicetax+=$bookingData->response->calculatedDetails->totalServiceTax;
                    }
                  }
                  if(array_key_exists('totalgoeventzfee', (array)$bookingData->response->calculatedDetails))
                  {
                    $totalgoeventfee+=$bookingData->response->calculatedDetails->totalgoeventzfee;
                  }
                  if(array_key_exists($orderbookingDetails->order_id, $seatselectedArray))
                  {
                    $result[$orderbookingDetails->order_id]['seatselected']=$seatselectedArray[$orderbookingDetails->order_id];
                  }
                  if(isset($bookingData->response->calculatedDetails->extracharges->detail) && array_key_exists('Customtax', (array)$bookingData->response->calculatedDetails->extracharges->detail))
                  {
                    $extrafeildArray = (array)$bookingData->response->calculatedDetails->extracharges->detail->Customtax;
                    foreach($extrafeildArray as $extrafeildkey=>$extrafeildval)
                    {
                       $extraColumns[]=$extrafeildkey;
                    }
                  }
                    $totalAmount+=$orderbookingDetails->total_amount-$orderbookingDetails->extra_charges;
                    $totalquantity+=$orderbookingDetails->total_quantity;
                    
                }
             
                $customdetails[$orderbookingDetails->order_id] ='';
              
             }
             /////////custom feilds////////
             $getorderinfo = $this->customfieldsvalue->groupByallIn($orderidArray,array('ticket_id','event_id','order_id','event_custom_fields_id','value','position'),array('order_id','event_custom_fields_id','ticket_id','position'));
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
                    $customdetails[$getorderinfo->order_id][$getorderinfo->ticket_id][$getorderinfo->position][$getorderinfo->event_custom_fields_id] =  $_ENV['CF_LINK'].'/event/'.$getorderinfo->event_id.'/customfeild/'.$getorderinfo->value;
                  }
                }
             }
             
              $totaldetailArray = array('totalamount'=>$totalAmount,
                                        'totalquanitity'=>$totalquantity,
                                        'totalpgfee'=>$totalpgfee,
                                        'total_servicetax'=>$total_servicetax,
                                        'totalgoeventfee'=>$totalgoeventfee,
                                        'totalmarketingcharges'=>$totalmarketingcharges,
                                        'totalpramotionaldiscount'=>$totalpramotionaldiscount);
             return response()->json(['reportData' => $result,
                                      'customfeild'=>$customfeildArray,
                                      'customdetails' => $customdetails,
                                      'totalarray' => $totaldetailArray,
                                      'extraColumns' => array_unique($extraColumns)]);
          }
          else
          {
            $result='noorder';
            return response()->json(['reportData' => $result]);
          }
            
        }
        else
        {
            $result='error';
            return response()->json(['reportData' => $result]);
        }
     
    }

    ////////////// currency based report section ///////////
    private function geteventreportsnewcurrency($request)
    {
        $eventid = $request->id;
        $status= $request->status;
        $checkEventId = $this->event->find($eventid);
        $result='';
        //$customdetails='';
        //$customfeild='';
        $commonObj = new Common();
        $customfeildArray=array();
        $customdetails=array(); 
        $orderidArray=array();
        $feildArray = array(); 
        $customfeilddetails = array();
        $totaldetailArray = array();
        $chargesArray = array();
        $defaultserviceCharges=array();
        $totaltaxArray=array();
        $taxdata=array();
        $getcolumns=array();
        $extraColumns = array();
        $allcurrency = $this->currency->getListArray(array(),'name','id');
        $customfeilds = $this->eventfeild->getallBy(array('event_id'=>$eventid),array('name','id','ticket_id','type'));
        foreach($customfeilds as $customfeilds)
        {
          //$feildArray[]=$customfeilds->id;
          $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,'type'=>$customfeilds->type);
        }

        /////////service charges details/////////
        $eventservicecharges = $this->servicecharge->getList(array('event_id'=>$request->eventId,'status'=>1),'rate_of_intrest','service_charge_name');/////event extra charges
        if(count($eventservicecharges)>0)
        {
          foreach($eventservicecharges as $keyeventservice=>$valeventservice)
          {
            $defaultserviceCharges[$keyeventservice]=$valeventservice/100;
          }
        }
        else
        {
          $servicechardes = $this->servicecharge->getList(array('country_name'=>strtolower($checkEventId->country),'status'=>1),'rate_of_intrest','service_charge_name');/////country servicecharges
            if(count($servicechardes)>0)
            {
              foreach($servicechardes as $keyservice=>$valservice)
              {
                $defaultserviceCharges[$keyservice]=$valservice/100;
              }
          }
        }
           //////////exytracharges//////////////
        $checkExtracharge = $this->extracharge->getallBy(array());
        if(count($checkExtracharge)>0)
        {
          foreach ($checkExtracharge as $checkExtracharge) 
          {
            $chargesArray[$checkExtracharge->id] = array('pg_charge'=>$checkExtracharge->pg_charges,
                                  'go_charge'=>$checkExtracharge->goeventz_fee,
                                  'per_ticket_charge'=>$checkExtracharge->per_ticket_fee,
                                  'mode'=>$checkExtracharge->mode,
                                  'max_go_fee'=>$checkExtracharge->max_goeventz_fee);
          }

        }
        $seatselectedArray = array();
        if($eventid==env('CUSTOM_SEAT_EVENTID'))
        {
          $checkseat = $this->seatplan->getallBy(array('event_id'=>$eventid));
          if(count($checkseat)>0)
          {
            foreach ($checkseat as $checkseat) 
            {
              $seatselectedArray[$checkseat->order_id] = $checkseat->seat_selected;
            }
          }
        }
        if($checkEventId)
        {
           $cityArray = array();
          $showarray = array();
          if($checkEventId->recurring_type==3)
          {
              $schedulecitylist = $this->weightage->getListall(array('event_id'=>$checkEventId->id),'city','shedule_id');
              $slotlist = $this->slotlist->getListall(array('event_id'=>$checkEventId->id),'schedule_id','id');
              foreach($slotlist as $slotid=>$schedule)
              {
                  if(array_key_exists($schedule, $schedulecitylist))
                  {
                      $cityArray[$slotid] = $schedulecitylist[$schedule];
                  }
              }
          }
          elseif ($checkEventId->recurring_type==1)
          {
            $showCondition = array('event_id'=>$checkEventId->id,'type'=>1);
            $showDetails = $this->recurringevent->getallBy($showCondition,array('id','name','start_time','end_time'));
            if(count($showDetails)>0)
            {
              foreach($showDetails as $showDetails)
              {
                $showtiming = $showDetails->name.' ('.$showDetails->start_time.''.$showDetails->end_time.')';
                $showarray['id'] = $showtiming;

              }
            }
          }
         $user = Auth::user();
          $whereCondition =array('user_id'=>$user->id,"event_id"=>$checkEventId->id);
          $collaborator = $this->collaboratorInterface->getBy($whereCondition);

          if(count($collaborator)>0)
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2 and user_id ='".$user->id."'";
             //$condition = array('event_id'=>$checkEventId->id,'order_status'=>'completed','payment_mode'=>2,'user_id'=>$user->id);
          }
           elseif($status=="all")
          {
           $condition = "event_id = '".$checkEventId->id."' and order_status='completed'";
          }
          else
          if($status=="offline")
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2";
            //$condition = array('event_id'=>$checkEventId->id,'payment_mode'=>2,'order_status'=>'completed');
          else 
            $condition = "event_id = '".$checkEventId->id."' and order_status ='".$status."' and payment_mode!=2";
            //$condition = array('event_id'=>$checkEventId->id,'order_status'=>$status,'payment_mode'=>0,'payment_mode'=>1);
          
          if(isset($_GET['colid']))
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2 and user_id ='".$_GET['colid']."'";
            // $condition = array('event_id'=>$checkEventId->id,'order_status'=>'completed','payment_mode'=>2,'user_id'=>$_GET['colid']);
          }
         
          if(isset($request->dateselect) && $request->dateselect!='')
          {
            $getdates = explode(',',$request->dateselect.',');
            if($getdates[1]=='')
            {
              $getdates[1] = $getdates[0];
            }
            $condition.= " and date(order_time) between '".$getdates[0]."' and '".$getdates[1]."'";
          }

          $orderbookingDetails = $this->bookingDetails->getallByRaw($condition,array('*'));

          if(count($orderbookingDetails)>0)
          {
            $NewtotalArray=array();
            $totalAmount=0;
            $totalpgfee=0;
            $totalgoeventfee=0;
            $total_servicetax=0;
            $totalquantity=0;
            $totalSerViceTaxpasson=0;
            $totalServiceTaxabsorve=0;
            $calculatedDetails=array();
            $totalmarketingcharges=0;
            $totalpramotionaldiscount=0;
             foreach($orderbookingDetails as $orderbookingDetails)
             {
              $taxseetingArray = array('pg_charge'=>0,
                                          'go_charge'=>0,
                                          'per_ticket_charge'=>0,
                                          'mode'=>'None',
                                          'max_go_fee'=>0);
                $currencyName = $orderbookingDetails->currency_id;
                if( $orderbookingDetails->currency_id<1)
                {
                  $currencyName='0--free';
                }
                if(array_key_exists($currencyName, $allcurrency))
                {
                 $currencyName = $orderbookingDetails->currency_id.'--'.$allcurrency[$currencyName];
                }
                $paymentgetwayfee= 0;
                $goeventzfee=0;
                $perticketfee=0;
                $totalGoeventChargese=0;
                $defaulttax='No';
                $totalExtraCharges=0;
                $totalSerViceTax=0;
                $city=$checkEventId->city;
                $showdate = $orderbookingDetails->show_date;
                 $show_timing='';
                if($orderbookingDetails->show_date=='0000-00-00')
                {
                  $showdate='';
                }
                if($showdate!='0000-00-00' && $showdate!='')
                {
                  if($orderbookingDetails->event_type==2)
                  {
                    $show_timing=$showdate;
                    if(array_key_exists($orderbookingDetails->show_id, $cityArray))
                    {
                      $city=$cityArray[$orderbookingDetails->show_id];
                    }
                  }
                  else
                  {
                    if(array_key_exists($orderbookingDetails->show_id, $showarray))
                    {
                      $show_timing=$showarray[$orderbookingDetails->show_id];
                    }
                  }
                }
                if(array_key_exists($orderbookingDetails->tax_id, $chargesArray))
                {
                  $taxseetingArray=$chargesArray[$orderbookingDetails->tax_id];
                }
                $bookingData = json_decode($orderbookingDetails->details);
                if(isset($request->ticketid) && $request->ticketid!='')
                {
                   $ticketArray =array();
                   $ticketdetailsArray = array();
                   $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                   if(in_array($request->ticketid, array_keys($ticketArray)))
                   {
                      foreach($ticketArray as $ticketkey=>$ticketval)
                      {
                        if($ticketkey == $request->ticketid )
                        {
                          $orderidArray[]=$orderbookingDetails->order_id;
                          $ticketdetailsArray = (array)$ticketval;
                          //$bookingData->response->calculatedDetails->ticketArray->$ticketkey =
                          $bookingData->response->calculatedDetails->ticketArray = array($ticketkey=>$bookingData->response->calculatedDetails->ticketArray->$ticketkey);
                          if($orderbookingDetails->default_tax_id>0)
                          {
                            
                            //$calculatedDetails=array($bookingData->response->calculatedDetails);
                            if(array_key_exists($orderbookingDetails->default_tax_id, $chargesArray))
                            {
                              $newticketArray=array();
                              if(!empty($ticketArray))
                              {   $ticketid=$ticketkey;
                                  $defaulttax='Yes';
                                  $totaltaxonticket=0;
                                  $servicetaxpass=0;
                                  $servicetaxobserv=0;
                                  $totalpgtax=0;
                                  $totalgofeetax=0;
                                  $totalTax = 0;
                                  $pg_charges = 0;
                                  $go_charges = 0;
                                  $per_ticket_charges =0;
                                  $newticketArray[$ticketid]['name'] = $ticketval->name;
                                  $newticketArray[$ticketid]['selectedQuantity'] = $ticketval->selectedQuantity;
                                  $newticketArray[$ticketid]['price'] = $ticketval->price;
                                  $newticketArray[$ticketid]['coupen'] = $ticketval->coupen;
                                  $newticketArray[$ticketid]['discount'] = $ticketval->discount;
                                  $newticketArray[$ticketid]['pg_charges'] = $ticketval->pg_charges;
                                  $newticketArray[$ticketid]['goeventz_fee'] = $ticketval->goeventz_fee;
                                  $newticketArray[$ticketid]['totalgoeventzfee'] = $ticketval->totalgoeventzfee;
                                  $newticketArray[$ticketid]['per_ticket_fee'] = $ticketval->per_ticket_fee;
                                  $newticketArray[$ticketid]['absorvefee'] = $ticketval->absorvefee;
                                  $newticketArray[$ticketid]['passonfee'] = $ticketval->passonfee;
                                  $newticketArray[$ticketid]['goeventpasson'] = $ticketval->goeventpasson;
                                  $newticketArray[$ticketid]['goeventObserve'] = $ticketval->goeventObserve;
                                  $newticketArray[$ticketid]['pgFeeObserve'] = $ticketval->pgFeeObserve;
                                  $newticketArray[$ticketid]['pgFeePasson'] = $ticketval->pgFeePasson;
                                  $newticketArray[$ticketid]['perticketObserve'] = $ticketval->perticketObserve;
                                  $newticketArray[$ticketid]['perticketpasson'] = $ticketval->perticketpasson;
                                  $newticketArray[$ticketid]['taxArray'] = @$ticketval->taxArray;
                                  $newticketArray[$ticketid]['totalpgtax'] = @$ticketval->totalpgtax;
                                  $newticketArray[$ticketid]['totalgo'] = @$ticketval->totalgo;
                                  $newticketArray[$ticketid]['servicetaxobserv'] = @$ticketval->servicetaxobserv;
                                  $newticketArray[$ticketid]['servicetaxpass']=@$ticketval->servicetaxpass;
                                  $newticketArray[$ticketid]['totalServiceTax']=@$ticketval->totalServiceTax;
                                  $newticketArray[$ticketid]['totalcharges']=@$ticketval->totalcharges;
                                  $newticketArray[$ticketid]['totalcusstomtax']=@$ticketval->totalcusstomtax;
                                  $newticketArray[$ticketid]['customtaxArray']=@$ticketval->customtaxArray;
                                  $taxableAmount = ($ticketval->selectedQuantity*$ticketval->price)-$ticketval->discount;
                                  $cmpGocharges = 0;
                                  $totalGoCharges = 0;
                                  if($chargesArray[$orderbookingDetails->default_tax_id]["mode"]==2)
                                  {
                                     $pg_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"])/100;
                                     $go_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"])/100;
                                     $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                     $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                     if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                    $pg_charges = $chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"];
                                    $go_charges =   $ticketval->selectedQuantity*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"];
                                    $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                    $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                    // echo $go_charges;
                                    // echo $cmpGocharges;
                                    if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                        $taxdata['pgfee'][$taxname]= $pgtax;
                                        $taxdata['gofee'][$taxname]=$gofee;
                                        // $taxdata[$taxname]=$pgtax+$gofee;
                                        $totaltaxArray[$taxname][]=$pgtax+$gofee;
                                        $totalpgtax+=$pgtax;
                                        $totalgofeetax+=$gofee;
                                        $totaltaxonticket+=$pgtax+$gofee;
                                      }
                                  }
                                 // $serViceTax = env('ST_TAX')*($totalGoCharges+$per_ticket_charges);         
                                 // $swachBharatTax = env('SBT_TAX')*($totalGoCharges+$per_ticket_charges); 
                                 // $kishanTax = env('KBT_TAX')*($totalGoCharges+$per_ticket_charges); 
                                 $newticketArray[$ticketid]['default_taxArray'] = $taxdata;
                                 $newticketArray[$ticketid]['default_totalpgtax'] = $totalpgtax;
                                 $newticketArray[$ticketid]['default_totalgo'] = $totalgofeetax;
                                 $newticketArray[$ticketid]['default_pg_charges']=$pg_charges;
                                 $newticketArray[$ticketid]['default_goeventz_fee']=$totalGoCharges;
                                 $newticketArray[$ticketid]['default_per_ticket_fee']=$per_ticket_charges; 
                                 $newticketArray[$ticketid]['default_totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                                 $newticketArray[$ticketid]['default_totalServiceTax']=$totaltaxonticket;
                                 $newticketArray[$ticketid]['default_totalcharges']=$totalGoCharges+$per_ticket_charges+$totaltaxonticket+$pg_charges;
                                 $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;
                                 $paymentgetwayfee+= $pg_charges;
                                 $goeventzfee+=$totalGoCharges;
                                 $perticketfee+=$per_ticket_charges;
                                 $totalSerViceTax+=$totaltaxonticket;      
                                 $bookingData->response->calculatedDetails->ticketArray=$newticketArray;
                                 $bookingData->response->calculatedDetails->donateTicketArray=array();
                                $bookingData->response->calculatedDetails->default_pg_charges = $paymentgetwayfee;
                                $bookingData->response->calculatedDetails->default_goeventz_fee = $goeventzfee;
                                $bookingData->response->calculatedDetails->default_per_ticket_fee = $perticketfee;
                                $bookingData->response->calculatedDetails->default_totalgoeventzfee = $totalGoeventChargese;
                                $bookingData->response->calculatedDetails->default_totalServiceTax =$totalSerViceTax;
                                $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
                                $bookingData->response->calculatedDetails->default_total=$totalExtraCharges;
                              }
                            }
                          }
                          $orderamount = $ticketval->price*$ticketval->selectedQuantity -$ticketval->discount;
                          if($orderamount<0)
                          {
                            $orderamount=0;
                          }
                          if(array_key_exists('customtaxArray', $ticketdetailsArray) )
                          {
                            if(!empty($ticketdetailsArray['customtaxArray']))
                            {
                              foreach($ticketdetailsArray['customtaxArray'] as $extrafeildkey=>$extrafeildval)
                              {
                                 //$extraColumns[]=$extrafeildkey;
                                 $extraColumns[$currencyName][]=$extrafeildkey;
                              }
                            }
                            
                            $bookingData->response->calculatedDetails->extracharges->detail->Customtax =$ticketdetailsArray['customtaxArray'];
                          }
                          if(!isset($NewtotalArray[$currencyName]['totalamount']))
                          {
                             $NewtotalArray[$currencyName]['totalamount']=0;
                          }
                          if(!isset($NewtotalArray[$currencyName]['totalquanitity']))
                          {
                             $NewtotalArray[$currencyName]['totalquanitity']=0;
                          }
                          if(!isset($NewtotalArray[$currencyName]['totalpgfee']))
                          {
                             $NewtotalArray[$currencyName]['totalpgfee']=0;
                          }
                          if(!isset($NewtotalArray[$currencyName]['total_servicetax']))
                          {
                             $NewtotalArray[$currencyName]['total_servicetax']=0;
                          }
                          if(!isset($NewtotalArray[$currencyName]['totalgoeventfee']))
                          {
                             $NewtotalArray[$currencyName]['totalgoeventfee']=0;
                          }
                          if(!isset($NewtotalArray[$currencyName]['totalmarketingcharges']))
                          {
                             $NewtotalArray[$currencyName]['totalmarketingcharges']=0;
                          }
                          if(!isset($NewtotalArray[$currencyName]['totalpramotionaldiscount']))
                          {
                             $NewtotalArray[$currencyName]['totalpramotionaldiscount']=0;
                          }
                          $result[$currencyName][$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                                         'Venue'=>$city,
                                                                                          'buyeremail'=>$orderbookingDetails->email,
                                                                                          'buyermobile'=>$orderbookingDetails->mobile,
                                                                                          'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                                          'quantity'=>$ticketval->selectedQuantity,
                                                                                          'amount'=>$orderamount,
                                                                                          'amountpaybale'=>$orderamount,
                                                                                          'orderdetails'=>$bookingData->response->calculatedDetails,
                                                                                          'discount'=>$ticketval->discount,
                                                                                          'coupon_code'=>$ticketval->coupen,
                                                                                          'extracharges'=>'',
                                                                                          'extra_service'=>'',
                                                                                          'default_tax'=>$defaulttax,
                                                                                          'default_total'=>$totalExtraCharges,
                                                                                          'taxseeting'=>$taxseetingArray,
                                                                                          'show_date'=>$showdate,
                                                                                          'show_timing'=>$show_timing);
                          if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                          {
                            $extracharges = round(($ticketdetailsArray['totalgoeventzfee']+$ticketdetailsArray['pg_charges']+$ticketdetailsArray['totalServiceTax']),2);
                            if(array_key_exists('totalcusstomtax', $ticketdetailsArray))
                            {
                              $extracharges = $extracharges+$ticketdetailsArray['totalcusstomtax'];
                            }
                            $amountpaybale = $orderamount-$ticketdetailsArray['absorvefee'];
                            if($amountpaybale<0)
                            {
                              $amountpaybale=0;
                            }
                            $result[$currencyName][$orderbookingDetails->order_id]['amount']=$orderamount+$extracharges-$ticketdetailsArray['absorvefee'];
                            $result[$currencyName][$orderbookingDetails->order_id]['amountpaybale']=$amountpaybale;
                            $result[$currencyName][$orderbookingDetails->order_id]['extracharges']=$extracharges;
                            if($defaulttax=='Yes')
                            {
                              if($extracharges>$totalExtraCharges)
                              {
                                $NewtotalArray[$currencyName]['totalmarketingcharges']+=$extracharges-$totalExtraCharges;
                              }
                              else
                              {
                                $NewtotalArray[$currencyName]['totalpramotionaldiscount']+=$totalExtraCharges-$extracharges;
                              }
                            }
                          }
                          if(array_key_exists('pg_charges', $ticketdetailsArray))
                          {
                            $NewtotalArray[$currencyName]['totalpgfee']+=$ticketdetailsArray['pg_charges'];
                          }
                          if(array_key_exists('totalServiceTax', $ticketdetailsArray))
                          {
                            $NewtotalArray[$currencyName]['total_servicetax']+=$ticketdetailsArray['totalServiceTax'];
                          }
                          if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                          {
                            $NewtotalArray[$currencyName]['totalgoeventfee']+=$ticketdetailsArray['totalgoeventzfee'];
                          }
                          $result[$currencyName][$orderbookingDetails->order_id]['seatselected']='';
                          if(array_key_exists($orderbookingDetails->order_id, $seatselectedArray))
                          {
                            $result[$currencyName][$orderbookingDetails->order_id]['seatselected']=$seatselectedArray[$orderbookingDetails->order_id];
                          }
                          
                           $NewtotalArray[$currencyName]['totalamount']+=$orderamount;
                           $NewtotalArray[$currencyName]['totalquanitity']+=$ticketval->selectedQuantity;
                        }
                      }
                   }
                }
                else
                {
                  $orderidArray[]=$orderbookingDetails->order_id;
                  if($orderbookingDetails->default_tax_id>0)
                  {
                    //$calculatedDetails=array($bookingData->response->calculatedDetails);
                    if(array_key_exists($orderbookingDetails->default_tax_id, $chargesArray))
                    {
                      $newticketArray=array();
                      $newdonateticketArray=array();
                      $donateticketArray=array();
                      $ticketArray =array();
                      $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                      $donateticketArray = (array)$bookingData->response->calculatedDetails->donateTicketArray;
                      if(!empty($ticketArray))
                      {
                        $defaulttax='Yes';
                        foreach($ticketArray as $ticketid=>$ticketval)
                        {
                          $totaltaxonticket=0;
                          $servicetaxpass=0;
                          $servicetaxobserv=0;
                          $totalpgtax=0;
                          $totalgofeetax=0;
                          $totalTax = 0;
                          $pg_charges = 0;
                          $go_charges = 0;
                         
                          $per_ticket_charges =0;
                          $newticketArray[$ticketid]['name'] = $ticketval->name;
                          $newticketArray[$ticketid]['selectedQuantity'] = $ticketval->selectedQuantity;
                          $newticketArray[$ticketid]['price'] = $ticketval->price;
                          $newticketArray[$ticketid]['coupen'] = $ticketval->coupen;
                          $newticketArray[$ticketid]['discount'] = $ticketval->discount;
                          $newticketArray[$ticketid]['pg_charges'] = $ticketval->pg_charges;
                          $newticketArray[$ticketid]['goeventz_fee'] = $ticketval->goeventz_fee;
                          $newticketArray[$ticketid]['totalgoeventzfee'] = $ticketval->totalgoeventzfee;
                          $newticketArray[$ticketid]['per_ticket_fee'] = $ticketval->per_ticket_fee;
                          $newticketArray[$ticketid]['absorvefee'] = $ticketval->absorvefee;
                          $newticketArray[$ticketid]['passonfee'] = $ticketval->passonfee;
                          $newticketArray[$ticketid]['goeventpasson'] = $ticketval->goeventpasson;
                          $newticketArray[$ticketid]['goeventObserve'] = $ticketval->goeventObserve;
                          $newticketArray[$ticketid]['pgFeeObserve'] = $ticketval->pgFeeObserve;
                          $newticketArray[$ticketid]['pgFeePasson'] = $ticketval->pgFeePasson;
                          $newticketArray[$ticketid]['perticketObserve'] = $ticketval->perticketObserve;
                          $newticketArray[$ticketid]['perticketpasson'] = $ticketval->perticketpasson;
                          $newticketArray[$ticketid]['taxArray'] = @$ticketval->taxArray;
                          $newticketArray[$ticketid]['totalpgtax'] = @$ticketval->totalpgtax;
                          $newticketArray[$ticketid]['totalgo'] = @$ticketval->totalgo;
                          $newticketArray[$ticketid]['servicetaxobserv'] = @$ticketval->servicetaxobserv;
                          $newticketArray[$ticketid]['servicetaxpass']=@$ticketval->servicetaxpass;
                          $newticketArray[$ticketid]['totalServiceTax']=@$ticketval->totalServiceTax;
                          $newticketArray[$ticketid]['totalcharges']=@$ticketval->totalcharges;
                          $newticketArray[$ticketid]['totalcusstomtax']=@$ticketval->totalcusstomtax;
                          $newticketArray[$ticketid]['customtaxArray']=@$ticketval->customtaxArray;
                          $taxableAmount = ($ticketval->selectedQuantity*$ticketval->price)-$ticketval->discount;
                          $cmpGocharges = 0;
                          $totalGoCharges = 0;
                          if($chargesArray[$orderbookingDetails->default_tax_id]["mode"]==2)
                          {
                             $pg_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"])/100;
                             $go_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"])/100;
                             $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                             $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                             if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                            $pg_charges = $chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"];
                            $go_charges =   $ticketval->selectedQuantity*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"];
                            $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                            $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                            // echo $go_charges;
                            // echo $cmpGocharges;
                            if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                  $taxdata['pgfee'][$taxname]= $pgtax;
                                  $taxdata['gofee'][$taxname]=$gofee;
                                  // $taxdata[$taxname]=$pgtax+$gofee;
                                  $totaltaxArray[$taxname][]=$pgtax+$gofee;
                                  $totalpgtax+=$pgtax;
                                  $totalgofeetax+=$gofee;
                                  $totaltaxonticket+=$pgtax+$gofee;
                                }
                          } 
                         $newticketArray[$ticketid]['default_taxArray'] = $taxdata;
                         $newticketArray[$ticketid]['default_totalpgtax'] = $totalpgtax;
                         $newticketArray[$ticketid]['default_totalgo'] = $totalgofeetax;
                         $newticketArray[$ticketid]['default_pg_charges']=$pg_charges;
                         $newticketArray[$ticketid]['default_goeventz_fee']=$totalGoCharges;
                         $newticketArray[$ticketid]['default_per_ticket_fee']=$per_ticket_charges; 
                         $newticketArray[$ticketid]['default_totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                         $newticketArray[$ticketid]['default_totalServiceTax']=$totaltaxonticket;
                         $newticketArray[$ticketid]['default_totalcharges']=$totalGoCharges+$per_ticket_charges+$totaltaxonticket+$pg_charges;
                         $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;
                         $paymentgetwayfee+= $pg_charges;
                         $goeventzfee+=$totalGoCharges;
                         $perticketfee+=$per_ticket_charges;
                         $totalSerViceTax+=$totaltaxonticket;     
                        }
                        
                        $bookingData->response->calculatedDetails->donateTicketArray=$newdonateticketArray;
                        $bookingData->response->calculatedDetails->ticketArray=$newticketArray;
                        //$calculatedDetails=array($bookingData->response->calculatedDetails);
                        $bookingData->response->calculatedDetails->default_pg_charges = $paymentgetwayfee;
                        $bookingData->response->calculatedDetails->default_goeventz_fee = $goeventzfee;
                        $bookingData->response->calculatedDetails->default_per_ticket_fee = $perticketfee;
                        $bookingData->response->calculatedDetails->default_totalgoeventzfee = $totalGoeventChargese;
                        $bookingData->response->calculatedDetails->default_totalServiceTax =$totalSerViceTax;
                        $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
                        $bookingData->response->calculatedDetails->default_total=$totalExtraCharges;
                      }
                    }
                  }
                 
                  // $NewtotalArray[$currencyName]['totalquanitity']=0;
                  // $NewtotalArray[$currencyName]['totalpgfee']=0;
                  // $NewtotalArray[$currencyName]['total_servicetax']=0;
                  // $NewtotalArray[$currencyName]['totalgoeventfee']=0;
                  // $NewtotalArray[$currencyName]['totalmarketingcharges']=0;
                  // $NewtotalArray[$currencyName]['totalpramotionaldiscount']=0;
                  if(!isset($NewtotalArray[$currencyName]['totalamount']))
                  {
                     $NewtotalArray[$currencyName]['totalamount']=0;
                  }
                  if(!isset($NewtotalArray[$currencyName]['totalquanitity']))
                  {
                     $NewtotalArray[$currencyName]['totalquanitity']=0;
                  }
                  if(!isset($NewtotalArray[$currencyName]['totalpgfee']))
                  {
                     $NewtotalArray[$currencyName]['totalpgfee']=0;
                  }
                  if(!isset($NewtotalArray[$currencyName]['total_servicetax']))
                  {
                     $NewtotalArray[$currencyName]['total_servicetax']=0;
                  }
                  if(!isset($NewtotalArray[$currencyName]['totalgoeventfee']))
                  {
                     $NewtotalArray[$currencyName]['totalgoeventfee']=0;
                  }
                  if(!isset($NewtotalArray[$currencyName]['totalmarketingcharges']))
                  {
                     $NewtotalArray[$currencyName]['totalmarketingcharges']=0;
                  }
                  if(!isset($NewtotalArray[$currencyName]['totalpramotionaldiscount']))
                  {
                     $NewtotalArray[$currencyName]['totalpramotionaldiscount']=0;
                  }
                   $amountpaybale = round($orderbookingDetails->total_amount-$orderbookingDetails->extra_charges+$orderbookingDetails->extra_services_total,2);
                    if($amountpaybale<0)
                    {
                      $amountpaybale=0;
                    }

                  $result[$currencyName][$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                                  'Venue'=>$city,
                                                                                 'buyeremail'=>$orderbookingDetails->email,
                                                                                 'buyermobile'=>$orderbookingDetails->mobile,
                                                                                 'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                                 'quantity'=>$orderbookingDetails->total_quantity,
                                                                                 'amount'=>$orderbookingDetails->total_amount,
                                                                                 'amountpaybale'=>$amountpaybale,
                                                                                 'orderdetails'=>$bookingData->response->calculatedDetails,
                                                                                 'discount'=>$orderbookingDetails->discount,
                                                                                 'coupon_code'=>$orderbookingDetails->coupon_code,
                                                                                 'extracharges'=>$orderbookingDetails->extra_charges,
                                                                                 'default_tax'=>$defaulttax,
                                                                                 'extra_service'=>json_decode($orderbookingDetails->extra_services,true),
                                                                                 'default_total'=>$totalExtraCharges,
                                                                                 'taxseeting'=>$taxseetingArray,
                                                                                 'show_date'=>$showdate,
                                                                                 'show_timing'=>$show_timing);
                  $result[$currencyName][$orderbookingDetails->order_id]['seatselected']='';
                  if($defaulttax=='Yes')
                  {
                    if($orderbookingDetails->extra_charges>$totalExtraCharges)
                    {
                       $NewtotalArray[$currencyName]['totalmarketingcharges']+=$orderbookingDetails->extra_charges-$totalExtraCharges;
                    }
                    else
                    {
                      $NewtotalArray[$currencyName]['totalpramotionaldiscount']+=$totalExtraCharges-$orderbookingDetails->extra_charges;
                    }
                  }
                  if(array_key_exists('pg_charges', (array)$bookingData->response->calculatedDetails))
                  {
                    $NewtotalArray[$currencyName]['totalpgfee']+=$bookingData->response->calculatedDetails->pg_charges;
                  }
                  if(array_key_exists('totalServiceTax', (array)$bookingData->response->calculatedDetails))
                  {
                    $NewtotalArray[$currencyName]['total_servicetax']+=$bookingData->response->calculatedDetails->totalServiceTax;
                  }
                  if(array_key_exists('totalgoeventzfee', (array)$bookingData->response->calculatedDetails))
                  {
                    $NewtotalArray[$currencyName]['totalgoeventfee']+=$bookingData->response->calculatedDetails->totalgoeventzfee;
                  }
                  if(array_key_exists($orderbookingDetails->order_id, $seatselectedArray))
                  {
                    $result[$currencyName][$orderbookingDetails->order_id]['seatselected']=$seatselectedArray[$orderbookingDetails->order_id];
                  }
                  if(isset($bookingData->response->calculatedDetails->extracharges->detail) && array_key_exists('Customtax', (array)$bookingData->response->calculatedDetails->extracharges->detail))
                  {
                    $extrafeildArray = (array)$bookingData->response->calculatedDetails->extracharges->detail->Customtax;
                    foreach($extrafeildArray as $extrafeildkey=>$extrafeildval)
                    {
                       $extraColumns[$currencyName][]=$extrafeildkey;
                    }
                  }

                  $NewtotalArray[$currencyName]['totalamount']+=$orderbookingDetails->total_amount-$orderbookingDetails->extra_charges;
                  $NewtotalArray[$currencyName]['totalquanitity']+=$orderbookingDetails->total_quantity;
                    
                }
                $customdetails[$orderbookingDetails->order_id] ='';
              
             }
             /////////custom feilds////////
              if(!isset($request->reportfroadmin))
              {
                 $getorderinfo = $this->customfieldsvalue->groupByallIn($orderidArray,array('ticket_id','event_id','order_id','event_custom_fields_id','value','position'),array('order_id','event_custom_fields_id','ticket_id','position'));
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
                        $customdetails[$getorderinfo->order_id][$getorderinfo->ticket_id][$getorderinfo->position][$getorderinfo->event_custom_fields_id] =  $_ENV['CF_LINK'].'/event/'.$getorderinfo->event_id.'/customfeild/'.$getorderinfo->value;
                      }
                    }
                 }
                   ///////extra columns currency based/////////
                  $UniqueExtraColumns = array();
                  if(count($extraColumns)>0)
                  {
                    foreach($extraColumns as $key=>$val)
                    {
                      $values = array_values($val);
                      $UniqueExtraColumns[$key]=array_unique($values);
                    }
                  }
                   return response()->json(['reportData' => $result,
                                      'customfeild'=>$customfeildArray,
                                      'customdetails' => $customdetails,
                                      'totalarray' => $NewtotalArray,
                                      'extraColumns' => $UniqueExtraColumns]);
              }
              else
              {
                 return response()->json(['reportData' => $result,
                                      'totalarray' => $NewtotalArray]);
              }
 
            
          }
          else
          {
            $result='noorder';
            return response()->json(['reportData' => $result]);
          }
            
        }
        else
        {
            $result='error';
            return response()->json(['reportData' => $result]);
        }
     
    }
      ////////////// currency based report section ///////////
    private function geteventreportscurrency($request)
    {
        $eventid = $request->id;
        $status= $request->status;
        $checkEventId = $this->event->find($eventid);
        $result='';
        $commonObj = new Common();
        $customfeildArray=array();
        $customdetails=array(); 
        $orderidArray=array();
        $feildArray = array(); 
        $customfeilddetails = array();
        $totaldetailArray = array();
        $chargesArray = array();
        $defaultserviceCharges=array();
        $totaltaxArray=array();
        $taxdata=array();
        $getcolumns=array();
        $extraColumns = array();
        $allcurrency = $this->currency->getListArray(array(),'name','id');
        $customfeilds = $this->eventfeild->getallBy(array('event_id'=>$eventid),array('name','id','ticket_id','type'));
        foreach($customfeilds as $customfeilds)
        {
          //$feildArray[]=$customfeilds->id;
          $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,'type'=>$customfeilds->type);
        }

        /////////service charges details/////////
        $eventservicecharges = $this->servicecharge->getList(array('event_id'=>$request->eventId,'status'=>1),'rate_of_intrest','service_charge_name');/////event extra charges
        if(count($eventservicecharges)>0)
        {
          foreach($eventservicecharges as $keyeventservice=>$valeventservice)
          {
            $defaultserviceCharges[$keyeventservice]=$valeventservice/100;
          }
        }
        else
        {
            $servicechardes = $this->servicecharge->getList(array('country_name'=>strtolower($checkEventId->country),'status'=>1),'rate_of_intrest','service_charge_name');/////country servicecharges
            if(count($servicechardes)>0)
            {
              foreach($servicechardes as $keyservice=>$valservice)
              {
                $defaultserviceCharges[$keyservice]=$valservice/100;
              }
          }
        }
           //////////exytracharges//////////////
        $checkExtracharge = $this->extracharge->getallBy(array());
        if(count($checkExtracharge)>0)
        {
          foreach ($checkExtracharge as $checkExtracharge) 
          {
            $chargesArray[$checkExtracharge->id] = array('pg_charge'=>$checkExtracharge->pg_charges,
                                                         'go_charge'=>$checkExtracharge->goeventz_fee,
                                                         'per_ticket_charge'=>$checkExtracharge->per_ticket_fee,
                                                         'mode'=>$checkExtracharge->mode,
                                                         'max_go_fee'=>$checkExtracharge->max_goeventz_fee);
          }

        }
        $seatselectedArray = array();
        if($eventid==env('CUSTOM_SEAT_EVENTID'))
        {
          $checkseat = $this->seatplan->getallBy(array('event_id'=>$eventid));
          if(count($checkseat)>0)
          {
            foreach ($checkseat as $checkseat) 
            {
              $seatselectedArray[$checkseat->order_id] = $checkseat->seat_selected;
            }
          }
        }
        if($checkEventId)
        {
          $cityArray = array();
          $showarray = array();
          if($checkEventId->recurring_type==3)
          {
              $schedulecitylist = $this->weightage->getListall(array('event_id'=>$eventid),'city','shedule_id');
              $slotlist = $this->slotlist->getListall(array('event_id'=>$eventid),'schedule_id','id');
              foreach($slotlist as $slotid=>$schedule)
              {
                  if(array_key_exists($schedule, $schedulecitylist))
                  {
                      $cityArray[$slotid] = $schedulecitylist[$schedule];
                  }
              }
          }
          elseif ($checkEventId->recurring_type==1)
          {
            $showCondition = array('event_id'=>$request->eventId,'type'=>1);
            $showDetails = $this->recurringevent->getallBy($showCondition,array('id','name','start_time','end_time'));
            if(count($showDetails)>0)
            {
              foreach($showDetails as $showDetails)
              {
                $showtiming = $showDetails->name.' ('.$showDetails->start_time.''.$showDetails->end_time.')';
                $showarray['id'] = $showtiming;

              }
            }
          }

          

          $user = Auth::user();
          $whereCondition =array('user_id'=>$user->id,"event_id"=>$checkEventId->id);
          $collaborator = $this->collaboratorInterface->getBy($whereCondition);
          if(count($collaborator)>0)
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2 and user_id ='".$user->id."'";
          }
          elseif($status=="all")
          {
           $condition = "event_id = '".$checkEventId->id."' and order_status='completed'";
          }
          elseif($status=="offline")
          {
          $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2";
          }
          else 
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='".$status."' and payment_mode!=2";
          }
            
          if(isset($_GET['colid']))
          {
            $condition = "event_id = '".$checkEventId->id."' and order_status ='completed' and payment_mode=2 and user_id ='".$_GET['colid']."'";
          }
          if(!isset($request->getcount))
          {
              if(isset($request->dateselect) && $request->dateselect!='')
              {
                $getdates = explode(',',$request->dateselect.',');
                if($getdates[1]=='')
                {
                  $getdates[1] = $getdates[0];
                }
                $condition.= " and date(order_time) between '".$getdates[0]."' and '".$getdates[1]."'";
              }
              if(isset($request->slotid) && $request->slotid!='')
              {
                $condition.= " and show_id = '".$request->slotid."'";
              }
              elseif(isset($request->schedule) && $request->schedule!='')
              {
                $conditionRaw = 'schedule_id="'.$request->schedule.'"';
                $selectconditionRaw = 'group_concat(id) as slotsid';
                $getslots = $this->slotlist->getRawfirst($conditionRaw,$selectconditionRaw);
                $condition.= " and show_id in ".'('.rtrim($getslots->slotsid,',').')'."";

              }
              elseif(isset($request->schedulecity) && $request->schedulecity!='')
              {
                $conditionRaw = 'city="'.$request->schedulecity.'"';
                $selectconditionRaw = 'group_concat(id) as schedulids';
                $getschedulids = $this->schedule->getRawfirst($conditionRaw,$selectconditionRaw);
                if($getschedulids)
                {
                  $conditionRaw = "schedule_id in ".'('.rtrim($getschedulids->schedulids,',').')'."";
                  $selectconditionRaw = 'group_concat(id) as slotsid';
                  $getslots = $this->slotlist->getRawfirst($conditionRaw,$selectconditionRaw);
                  $condition.= " and show_id in ".'('.rtrim($getslots->slotsid,',').')'."";

                }
              }
          }
        
          $currencyids='';
          $currencyArray=array();
          $columns='event_id,GROUP_CONCAT(DISTINCT(currency_id)) as currency';
          //dd($condition);
          $getcurrencylist = $this->bookingDetails->getByraw($condition,$columns);
          //dd($getcurrencylist);
          if($getcurrencylist)
          {
            $currencyids=$getcurrencylist->currency;
          }
          if(isset($request->getcount))
          {
            return response()->json(['reportData' => $currencyids]);
            exit();
          }
          if($currencyids!='')
          {
            $currencyArray[] = $currencyids;
            if(strpos($currencyids, ',')!==false)
            {
              $currencyArray =explode(',', $currencyids);
            }
            $conditionnew='';
           
            // dd($currencyArray);
            $pagination=array();
            $NewtotalArray=array();
            $i=1;
            $perpage = 10;
            foreach($currencyArray as $currencyid)
            {

              $currencyName = $currencyid;
              if($currencyid<1)
              {
                $currencyName='0--free';
              }
              if(array_key_exists($currencyid, $allcurrency))
              {
               $currencyName = $currencyid.'--'.$allcurrency[$currencyid];
              }
               // $NewtotalArray[$currencyName]['totalamount']=0;
               // $NewtotalArray[$currencyName]['totalquanitity']=0;
               $conditionnew=$condition.' and currency_id="'.$currencyid.'"';
              if($request->pagenumber && $request->currencyid)
              {
                $request['page']=$request->pagenumber;
                if($request->currencyid!=$currencyid)
                {
                  $request['page']=1;
                }
              }
              if($request->perpage && $request->currencyid)
              {
                //dd($request->all());
                if($request->currencyid==$currencyid)
                {
                   $perpage=$request->perpage;
                   //dd($request->perpage);
                }
                else
                {
                  $perpage = 10;
                }
              }
              $getcurrencybooking = $this->bookingDetails->getallBypaginateAdmin(null,$conditionnew,$perpage);
             // dd($getcurrencybooking);
              //print_r($getcurrencybooking);
              if(count($getcurrencybooking)>0)
              {
                
                $totalAmount=0;
                $totalpgfee=0;
                $totalgoeventfee=0;
                $total_servicetax=0;
                $totalquantity=0;
                $totalSerViceTaxpasson=0;
                $totalServiceTaxabsorve=0;
                $calculatedDetails=array();
                $totalmarketingcharges=0;
                $totalpramotionaldiscount=0;
                foreach($getcurrencybooking as $orderbookingDetails)
                {
                  $taxseetingArray = array('pg_charge'=>0,
                                          'go_charge'=>0,
                                          'per_ticket_charge'=>0,
                                          'mode'=>'None',
                                          'max_go_fee'=>0);
                  $city=$checkEventId->city;
                  $defaulttax='No';
                  $totalExtraCharges=0;
                  $showdate = $orderbookingDetails->show_date;
                  $show_timing='';
                  if($orderbookingDetails->show_date=='0000-00-00')
                  {
                    $showdate='';
                  }
                  ///////// show date and timing//////
                  if($showdate!='0000-00-00' && $showdate!='')
                  {
                    if($orderbookingDetails->event_type==2)
                    {
                      $show_timing=$showdate;
                      if(array_key_exists($orderbookingDetails->show_id, $cityArray))
                      {
                        $city=$cityArray[$orderbookingDetails->show_id];
                      }
                    }
                    else
                    {
                      if(array_key_exists($orderbookingDetails->show_id, $showarray))
                      {
                        $show_timing=$showarray[$orderbookingDetails->show_id];
                      }
                    }
                  }
                  if(array_key_exists($orderbookingDetails->tax_id, $chargesArray))
                  {
                    $taxseetingArray=$chargesArray[$orderbookingDetails->tax_id];
                  }
                  $bookingData = json_decode($orderbookingDetails->details);
                  if(isset($request->ticketid) && $request->ticketid!='')
                  {
                     $ticketArray =array();
                     $ticketdetailsArray = array();
                     $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                     if(in_array($request->ticketid, array_keys($ticketArray)))
                     {
                        foreach($ticketArray as $ticketkey=>$ticketval)
                        {
                          if($ticketkey == $request->ticketid )
                          {
                            $orderidArray[]=$orderbookingDetails->order_id;
                            $ticketdetailsArray = (array)$ticketval;
                            //$bookingData->response->calculatedDetails->ticketArray->$ticketkey =
                            $bookingData->response->calculatedDetails->ticketArray = array($ticketkey=>$bookingData->response->calculatedDetails->ticketArray->$ticketkey);
                            
                            $orderamount = $ticketval->price*$ticketval->selectedQuantity -$ticketval->discount;
                            if($orderamount<0)
                            {
                              $orderamount=0;
                            }
                            if(array_key_exists('customtaxArray', $ticketdetailsArray) )
                            {
                              if(!empty($ticketdetailsArray['customtaxArray']))
                              {
                                foreach($ticketdetailsArray['customtaxArray'] as $extrafeildkey=>$extrafeildval)
                                {
                                   //$extraColumns[]=$extrafeildkey;
                                   $extraColumns[$currencyName][]=$extrafeildkey;
                                }
                              }
                              
                              $bookingData->response->calculatedDetails->extracharges->detail->Customtax =$ticketdetailsArray['customtaxArray'];
                            }
                            
                          
                            $result[$currencyName][$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                                            'Venue'=>$city,
                                                                                            'buyeremail'=>$orderbookingDetails->email,
                                                                                            'buyermobile'=>$orderbookingDetails->mobile,
                                                                                            'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                                            'quantity'=>$ticketval->selectedQuantity,
                                                                                            'amount'=>$orderamount,
                                                                                            'amountpaybale'=>$orderamount,
                                                                                            'orderdetails'=>$bookingData->response->calculatedDetails,
                                                                                            'discount'=>$ticketval->discount,
                                                                                            'coupon_code'=>$ticketval->coupen,
                                                                                            'extracharges'=>'',
                                                                                            'extra_service'=>'',
                                                                                            'default_tax'=>$defaulttax,
                                                                                            'default_total'=>$totalExtraCharges,
                                                                                            'taxseeting'=>$taxseetingArray,
                                                                                            'show_date'=>$showdate,
                                                                                            'show_timing'=>$show_timing);
                            $customcharges=0;
                            if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                            {
                              $extracharges = round(($ticketdetailsArray['totalgoeventzfee']+$ticketdetailsArray['pg_charges']+$ticketdetailsArray['totalServiceTax']),2);
                              $amountpaybale = $orderamount-$ticketdetailsArray['absorvefee'];
                              if(array_key_exists('totalcusstomtax', $ticketdetailsArray))
                              {
                                $customcharges = $ticketdetailsArray['totalcusstomtax'];
                                $amountpaybale = $amountpaybale+$ticketdetailsArray['totalcusstomtax'];
                              }
                              if($amountpaybale<0)
                              {
                                $amountpaybale=0;
                              }
                              $result[$currencyName][$orderbookingDetails->order_id]['amount']=round($orderamount+$extracharges+$customcharges-$ticketdetailsArray['absorvefee'],0,2);
                              $result[$currencyName][$orderbookingDetails->order_id]['amountpaybale']=round($amountpaybale,0,2);
                              $result[$currencyName][$orderbookingDetails->order_id]['extracharges']=round($extracharges,0,2);
                             
                            }
                            
                            $result[$currencyName][$orderbookingDetails->order_id]['seatselected']='';
                            if(array_key_exists($orderbookingDetails->order_id, $seatselectedArray))
                            {
                              $result[$currencyName][$orderbookingDetails->order_id]['seatselected']=$seatselectedArray[$orderbookingDetails->order_id];
                            }
                            
                            
                          }
                        }
                     }
                  }
                  else
                  {
                    $orderidArray[]=$orderbookingDetails->order_id;
                    $amountpaybale = round($orderbookingDetails->total_amount-$orderbookingDetails->extra_charges+$orderbookingDetails->extra_services_total,0,2);
                    if($amountpaybale<0)
                    {
                      $amountpaybale=0;
                    }
                    $result[$currencyName][$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                                   'Venue'=>$city,
                                                                                   'buyeremail'=>$orderbookingDetails->email,
                                                                                   'buyermobile'=>$orderbookingDetails->mobile,
                                                                                   'orderdate'=>$commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,'Asia/Kolkata'),
                                                                                   'quantity'=>$orderbookingDetails->total_quantity,
                                                                                   'amount'=>$orderbookingDetails->total_amount,
                                                                                   'amountpaybale'=>$amountpaybale,
                                                                                   'orderdetails'=>$bookingData->response->calculatedDetails,
                                                                                   'discount'=>$orderbookingDetails->discount,
                                                                                   'coupon_code'=>$orderbookingDetails->coupon_code,
                                                                                   'extracharges'=>round($orderbookingDetails->extra_charges,0,2),
                                                                                   'default_tax'=>$defaulttax,
                                                                                   'extra_service'=>json_decode($orderbookingDetails->extra_services,true),
                                                                                   'default_total'=>$totalExtraCharges,
                                                                                   'taxseeting'=>$taxseetingArray,
                                                                                   'show_date'=>$showdate,
                                                                                   'show_timing'=>$show_timing);
                    $result[$currencyName][$orderbookingDetails->order_id]['seatselected']='';
               
               
                    if(array_key_exists($orderbookingDetails->order_id, $seatselectedArray))
                    {
                      $result[$currencyName][$orderbookingDetails->order_id]['seatselected']=$seatselectedArray[$orderbookingDetails->order_id];
                    }
                    if(isset($bookingData->response->calculatedDetails->extracharges->detail) && array_key_exists('Customtax', (array)$bookingData->response->calculatedDetails->extracharges->detail))
                    {
                      $extrafeildArray = (array)$bookingData->response->calculatedDetails->extracharges->detail->Customtax;
                      foreach($extrafeildArray as $extrafeildkey=>$extrafeildval)
                      {
                         $extraColumns[$currencyName][]=$extrafeildkey;
                      }
                    }
                  }
                  $customdetails[$orderbookingDetails->order_id] ='';
                }
                
                if(!isset($request->reportfroadmin))
                {
                   /////////custom feilds////////
                   $getorderinfo = $this->customfieldsvalue->groupByallIn($orderidArray,array('ticket_id','event_id','order_id','event_custom_fields_id','value','position'),array('order_id','event_custom_fields_id','ticket_id','position'));
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
                          $customdetails[$getorderinfo->order_id][$getorderinfo->ticket_id][$getorderinfo->position][$getorderinfo->event_custom_fields_id] =  $_ENV['CF_LINK'].'/event/'.$getorderinfo->event_id.'/customfeild/'.$getorderinfo->value;
                        }
                      }
                   }
                   ///////extra columns currency based/////////
                    $UniqueExtraColumns = array();
                    if(count($extraColumns)>0)
                    {
                      foreach($extraColumns as $key=>$val)
                      {
                        $values = array_values($val);
                        $UniqueExtraColumns[$key]=array_unique($values);
                      }
                    }
                }
                   
                   /////////////for total Array/
                   /////////////calculation for total Array/////
                   $orderbookingDetails = $this->bookingDetails->getallByRaw($conditionnew,array('*'));
                   foreach($orderbookingDetails as $orderbookingDetails)
                   {
                      $currencyName = $orderbookingDetails->currency_id;
                      if($currencyid<1)
                      {
                        $currencyName='0--free';
                      }
                      if(array_key_exists($currencyName, $allcurrency))
                      {
                       $currencyName = $orderbookingDetails->currency_id.'--'.$allcurrency[$currencyName];
                      }
                      $paymentgetwayfee= 0;
                      $goeventzfee=0;
                      $perticketfee=0;
                      $totalGoeventChargese=0;
                      $defaulttax='No';
                      $totalExtraCharges=0;
                      $totalSerViceTax=0;
              
                      $bookingData = json_decode($orderbookingDetails->details);
                      if(isset($request->ticketid) && $request->ticketid!='')
                      {
                         $ticketArray =array();
                         $ticketdetailsArray = array();
                         $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                         if(in_array($request->ticketid, array_keys($ticketArray)))
                         {
                            foreach($ticketArray as $ticketkey=>$ticketval)
                            {
                              if($ticketkey == $request->ticketid )
                              {
                                $ticketdetailsArray = (array)$ticketval;
                                $bookingData->response->calculatedDetails->ticketArray = array($ticketkey=>$bookingData->response->calculatedDetails->ticketArray->$ticketkey);
                                if($orderbookingDetails->default_tax_id>0)
                                {
                                  if(array_key_exists($orderbookingDetails->default_tax_id, $chargesArray))
                                  {
                                    $newticketArray=array();
                                    if(!empty($ticketArray))
                                    {   $ticketid=$ticketkey;
                                        $defaulttax='Yes';
                                        $totaltaxonticket=0;
                                        $servicetaxpass=0;
                                        $servicetaxobserv=0;
                                        $totalpgtax=0;
                                        $totalgofeetax=0;
                                        $totalTax = 0;
                                        $pg_charges = 0;
                                        $go_charges = 0;
                                        $per_ticket_charges =0;
                                        $newticketArray[$ticketid]['name'] = $ticketval->name;
                                        $newticketArray[$ticketid]['selectedQuantity'] = $ticketval->selectedQuantity;
                                        $newticketArray[$ticketid]['price'] = $ticketval->price;
                                        $newticketArray[$ticketid]['coupen'] = $ticketval->coupen;
                                        $newticketArray[$ticketid]['discount'] = $ticketval->discount;
                                        $newticketArray[$ticketid]['pg_charges'] = $ticketval->pg_charges;
                                        $newticketArray[$ticketid]['goeventz_fee'] = $ticketval->goeventz_fee;
                                        $newticketArray[$ticketid]['totalgoeventzfee'] = $ticketval->totalgoeventzfee;
                                        $newticketArray[$ticketid]['per_ticket_fee'] = $ticketval->per_ticket_fee;
                                        $newticketArray[$ticketid]['absorvefee'] = $ticketval->absorvefee;
                                        $newticketArray[$ticketid]['passonfee'] = $ticketval->passonfee;
                                        $newticketArray[$ticketid]['goeventpasson'] = $ticketval->goeventpasson;
                                        $newticketArray[$ticketid]['goeventObserve'] = $ticketval->goeventObserve;
                                        $newticketArray[$ticketid]['pgFeeObserve'] = $ticketval->pgFeeObserve;
                                        $newticketArray[$ticketid]['pgFeePasson'] = $ticketval->pgFeePasson;
                                        $newticketArray[$ticketid]['perticketObserve'] = $ticketval->perticketObserve;
                                        $newticketArray[$ticketid]['perticketpasson'] = $ticketval->perticketpasson;
                                        $newticketArray[$ticketid]['taxArray'] = @$ticketval->taxArray;
                                        $newticketArray[$ticketid]['totalpgtax'] = @$ticketval->totalpgtax;
                                        $newticketArray[$ticketid]['totalgo'] = @$ticketval->totalgo;
                                        $newticketArray[$ticketid]['servicetaxobserv'] = @$ticketval->servicetaxobserv;
                                        $newticketArray[$ticketid]['servicetaxpass']=@$ticketval->servicetaxpass;
                                        $newticketArray[$ticketid]['totalServiceTax']=@$ticketval->totalServiceTax;
                                        $newticketArray[$ticketid]['totalcharges']=@$ticketval->totalcharges;
                                        $newticketArray[$ticketid]['totalcusstomtax']=@$ticketval->totalcusstomtax;
                                        $newticketArray[$ticketid]['customtaxArray']=@$ticketval->customtaxArray;
                                        $taxableAmount = ($ticketval->selectedQuantity*$ticketval->price)-$ticketval->discount;
                                        $cmpGocharges = 0;
                                        $totalGoCharges = 0;
                                        if($chargesArray[$orderbookingDetails->default_tax_id]["mode"]==2)
                                        {
                                           $pg_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"])/100;
                                           $go_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"])/100;
                                           $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                           $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                           if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                          $pg_charges = $chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"];
                                          $go_charges =   $ticketval->selectedQuantity*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"];
                                          $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                          $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                          // echo $go_charges;
                                          // echo $cmpGocharges;
                                          if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                              $taxdata['pgfee'][$taxname]= $pgtax;
                                              $taxdata['gofee'][$taxname]=$gofee;
                                              // $taxdata[$taxname]=$pgtax+$gofee;
                                              $totaltaxArray[$taxname][]=$pgtax+$gofee;
                                              $totalpgtax+=$pgtax;
                                              $totalgofeetax+=$gofee;
                                              $totaltaxonticket+=$pgtax+$gofee;
                                            }
                                        }
                                      
                                       $newticketArray[$ticketid]['default_taxArray'] = $taxdata;
                                       $newticketArray[$ticketid]['default_totalpgtax'] = $totalpgtax;
                                       $newticketArray[$ticketid]['default_totalgo'] = $totalgofeetax;
                                       $newticketArray[$ticketid]['default_pg_charges']=$pg_charges;
                                       $newticketArray[$ticketid]['default_goeventz_fee']=$totalGoCharges;
                                       $newticketArray[$ticketid]['default_per_ticket_fee']=$per_ticket_charges; 
                                       $newticketArray[$ticketid]['default_totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                                       $newticketArray[$ticketid]['default_totalServiceTax']=$totaltaxonticket;
                                       $newticketArray[$ticketid]['default_totalcharges']=$totalGoCharges+$per_ticket_charges+$totaltaxonticket+$pg_charges;
                                       $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;
                                       $paymentgetwayfee+= $pg_charges;
                                       $goeventzfee+=$totalGoCharges;
                                       $perticketfee+=$per_ticket_charges;
                                       $totalSerViceTax+=$totaltaxonticket;      
                                      $bookingData->response->calculatedDetails->ticketArray=$newticketArray;
                                      $bookingData->response->calculatedDetails->donateTicketArray=array();
                                      $bookingData->response->calculatedDetails->default_pg_charges = $paymentgetwayfee;
                                      $bookingData->response->calculatedDetails->default_goeventz_fee = $goeventzfee;
                                      $bookingData->response->calculatedDetails->default_per_ticket_fee = $perticketfee;
                                      $bookingData->response->calculatedDetails->default_totalgoeventzfee = $totalGoeventChargese;
                                      $bookingData->response->calculatedDetails->default_totalServiceTax =$totalSerViceTax;
                                      $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
                                      $bookingData->response->calculatedDetails->default_total=$totalExtraCharges;
                                    }
                                  }
                                }
                                $orderamount = $ticketval->price*$ticketval->selectedQuantity -$ticketval->discount;
                                if($orderamount<0)
                                {
                                  $orderamount=0;
                                }
                                if(in_array($orderbookingDetails->order_id, $orderidArray))
                                {
                                   $result[$currencyName][$orderbookingDetails->order_id]['orderdetails'] =$bookingData->response->calculatedDetails;
                                   $result[$currencyName][$orderbookingDetails->order_id]['default_tax']=$defaulttax;
                                   $result[$currencyName][$orderbookingDetails->order_id]['default_total']=$totalExtraCharges;
                                }
                             
                                if(!isset($NewtotalArray[$currencyName]['totalamount']))
                                {
                                   $NewtotalArray[$currencyName]['totalamount']=0;
                                }
                                if(!isset($NewtotalArray[$currencyName]['totalquanitity']))
                                {
                                   $NewtotalArray[$currencyName]['totalquanitity']=0;
                                }
                                if(!isset($NewtotalArray[$currencyName]['totalpgfee']))
                                {
                                   $NewtotalArray[$currencyName]['totalpgfee']=0;
                                }
                                if(!isset($NewtotalArray[$currencyName]['total_servicetax']))
                                {
                                   $NewtotalArray[$currencyName]['total_servicetax']=0;
                                }
                                if(!isset($NewtotalArray[$currencyName]['totalgoeventfee']))
                                {
                                   $NewtotalArray[$currencyName]['totalgoeventfee']=0;
                                }
                                if(!isset($NewtotalArray[$currencyName]['totalmarketingcharges']))
                                {
                                   $NewtotalArray[$currencyName]['totalmarketingcharges']=0;
                                }
                                if(!isset($NewtotalArray[$currencyName]['totalpramotionaldiscount']))
                                {
                                   $NewtotalArray[$currencyName]['totalpramotionaldiscount']=0;
                                }
                                
                                $customcharges=0;
                                if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                                {
                                  $extracharges = round(($ticketdetailsArray['totalgoeventzfee']+$ticketdetailsArray['pg_charges']+$ticketdetailsArray['totalServiceTax']),2);
                                  $orderamount = $orderamount-$ticketdetailsArray['absorvefee'];
                                  if(array_key_exists('totalcusstomtax', $ticketdetailsArray))
                                  {
                                    $customcharges = $ticketdetailsArray['totalcusstomtax'];
                                    $orderamount =  $orderamount+$ticketdetailsArray['totalcusstomtax'];
                                  }
                                  if($defaulttax=='Yes')
                                  {
                                    if($extracharges>$totalExtraCharges)
                                    {
                                      $NewtotalArray[$currencyName]['totalmarketingcharges']+=$extracharges-$totalExtraCharges;
                                    }
                                    else
                                    {
                                      $NewtotalArray[$currencyName]['totalpramotionaldiscount']+=$totalExtraCharges-$extracharges;
                                    }
                                  }
                                }
                                if(array_key_exists('pg_charges', $ticketdetailsArray))
                                {
                                  $NewtotalArray[$currencyName]['totalpgfee']+=$ticketdetailsArray['pg_charges'];
                                }
                                if(array_key_exists('totalServiceTax', $ticketdetailsArray))
                                {
                                  $NewtotalArray[$currencyName]['total_servicetax']+=$ticketdetailsArray['totalServiceTax'];
                                }
                                if(array_key_exists('totalgoeventzfee', $ticketdetailsArray))
                                {
                                  $NewtotalArray[$currencyName]['totalgoeventfee']+=$ticketdetailsArray['totalgoeventzfee'];
                                }
                                 $NewtotalArray[$currencyName]['totalamount']+=round($orderamount,2);
                                 $NewtotalArray[$currencyName]['totalquanitity']+=$ticketval->selectedQuantity;
                              }
                            }
                         }
                      }
                      else
                      {
                        // $orderidArray[]=$orderbookingDetails->order_id;
                        if($orderbookingDetails->default_tax_id>0)
                        {
                          //$calculatedDetails=array($bookingData->response->calculatedDetails);
                          if(array_key_exists($orderbookingDetails->default_tax_id, $chargesArray))
                          {
                            $newticketArray=array();
                            $newdonateticketArray=array();
                            $donateticketArray=array();
                            $ticketArray =array();
                            $ticketArray = (array)$bookingData->response->calculatedDetails->ticketArray;
                            $donateticketArray = (array)$bookingData->response->calculatedDetails->donateTicketArray;
                            if(!empty($ticketArray))
                            {
                              $defaulttax='Yes';
                              foreach($ticketArray as $ticketid=>$ticketval)
                              {
                                $totaltaxonticket=0;
                                $servicetaxpass=0;
                                $servicetaxobserv=0;
                                $totalpgtax=0;
                                $totalgofeetax=0;
                                $totalTax = 0;
                                $pg_charges = 0;
                                $go_charges = 0;
                               
                                $per_ticket_charges =0;
                                $newticketArray[$ticketid]['name'] = $ticketval->name;
                                $newticketArray[$ticketid]['selectedQuantity'] = $ticketval->selectedQuantity;
                                $newticketArray[$ticketid]['price'] = $ticketval->price;
                                $newticketArray[$ticketid]['coupen'] = $ticketval->coupen;
                                $newticketArray[$ticketid]['discount'] = $ticketval->discount;
                                $newticketArray[$ticketid]['pg_charges'] = $ticketval->pg_charges;
                                $newticketArray[$ticketid]['goeventz_fee'] = $ticketval->goeventz_fee;
                                $newticketArray[$ticketid]['totalgoeventzfee'] = $ticketval->totalgoeventzfee;
                                $newticketArray[$ticketid]['per_ticket_fee'] = $ticketval->per_ticket_fee;
                                $newticketArray[$ticketid]['absorvefee'] = $ticketval->absorvefee;
                                $newticketArray[$ticketid]['passonfee'] = $ticketval->passonfee;
                                $newticketArray[$ticketid]['goeventpasson'] = $ticketval->goeventpasson;
                                $newticketArray[$ticketid]['goeventObserve'] = $ticketval->goeventObserve;
                                $newticketArray[$ticketid]['pgFeeObserve'] = $ticketval->pgFeeObserve;
                                $newticketArray[$ticketid]['pgFeePasson'] = $ticketval->pgFeePasson;
                                $newticketArray[$ticketid]['perticketObserve'] = $ticketval->perticketObserve;
                                $newticketArray[$ticketid]['perticketpasson'] = $ticketval->perticketpasson;
                                $newticketArray[$ticketid]['taxArray'] = @$ticketval->taxArray;
                                $newticketArray[$ticketid]['totalpgtax'] = @$ticketval->totalpgtax;
                                $newticketArray[$ticketid]['totalgo'] = @$ticketval->totalgo;
                                $newticketArray[$ticketid]['servicetaxobserv'] = @$ticketval->servicetaxobserv;
                                $newticketArray[$ticketid]['servicetaxpass']=@$ticketval->servicetaxpass;
                                $newticketArray[$ticketid]['totalServiceTax']=@$ticketval->totalServiceTax;
                                $newticketArray[$ticketid]['totalcharges']=@$ticketval->totalcharges;
                                $newticketArray[$ticketid]['totalcusstomtax']=@$ticketval->totalcusstomtax;
                                $newticketArray[$ticketid]['customtaxArray']=@$ticketval->customtaxArray;
                                $taxableAmount = ($ticketval->selectedQuantity*$ticketval->price)-$ticketval->discount;
                                $cmpGocharges = 0;
                                $totalGoCharges = 0;
                                if($chargesArray[$orderbookingDetails->default_tax_id]["mode"]==2)
                                {
                                   $pg_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"])/100;
                                   $go_charges = ($taxableAmount*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"])/100;
                                   $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                   $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                   if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                  $pg_charges = $chargesArray[$orderbookingDetails->default_tax_id]["pg_charge"];
                                  $go_charges =   $ticketval->selectedQuantity*$chargesArray[$orderbookingDetails->default_tax_id]["go_charge"];
                                  $cmpGocharges = $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"];
                                  $per_ticket_charges = $chargesArray[$orderbookingDetails->default_tax_id]["per_ticket_charge"]*$ticketval->selectedQuantity;
                                  // echo $go_charges;
                                  // echo $cmpGocharges;
                                  if(($go_charges > $cmpGocharges) and $chargesArray[$orderbookingDetails->default_tax_id]["max_go_fee"]>0)
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
                                        $taxdata['pgfee'][$taxname]= $pgtax;
                                        $taxdata['gofee'][$taxname]=$gofee;
                                        // $taxdata[$taxname]=$pgtax+$gofee;
                                        $totaltaxArray[$taxname][]=$pgtax+$gofee;
                                        $totalpgtax+=$pgtax;
                                        $totalgofeetax+=$gofee;
                                        $totaltaxonticket+=$pgtax+$gofee;
                                      }
                                } 
                               $newticketArray[$ticketid]['default_taxArray'] = $taxdata;
                               $newticketArray[$ticketid]['default_totalpgtax'] = $totalpgtax;
                               $newticketArray[$ticketid]['default_totalgo'] = $totalgofeetax;
                               $newticketArray[$ticketid]['default_pg_charges']=$pg_charges;
                               $newticketArray[$ticketid]['default_goeventz_fee']=$totalGoCharges;
                               $newticketArray[$ticketid]['default_per_ticket_fee']=$per_ticket_charges; 
                               $newticketArray[$ticketid]['default_totalgoeventzfee'] = $totalGoCharges+$per_ticket_charges;
                               $newticketArray[$ticketid]['default_totalServiceTax']=$totaltaxonticket;
                               $newticketArray[$ticketid]['default_totalcharges']=$totalGoCharges+$per_ticket_charges+$totaltaxonticket+$pg_charges;
                               $totalGoeventChargese+=$totalGoCharges+$per_ticket_charges;
                               $paymentgetwayfee+= $pg_charges;
                               $goeventzfee+=$totalGoCharges;
                               $perticketfee+=$per_ticket_charges;
                               $totalSerViceTax+=$totaltaxonticket;     
                              }
                              
                              $bookingData->response->calculatedDetails->donateTicketArray=$newdonateticketArray;
                              $bookingData->response->calculatedDetails->ticketArray=$newticketArray;
                              //$calculatedDetails=array($bookingData->response->calculatedDetails);
                              $bookingData->response->calculatedDetails->default_pg_charges = $paymentgetwayfee;
                              $bookingData->response->calculatedDetails->default_goeventz_fee = $goeventzfee;
                              $bookingData->response->calculatedDetails->default_per_ticket_fee = $perticketfee;
                              $bookingData->response->calculatedDetails->default_totalgoeventzfee = $totalGoeventChargese;
                              $bookingData->response->calculatedDetails->default_totalServiceTax =$totalSerViceTax;
                              $totalExtraCharges=round(($paymentgetwayfee+$totalGoeventChargese+$totalSerViceTax),2);
                              $bookingData->response->calculatedDetails->default_total=$totalExtraCharges;
                            }
                          }
                        }
                       
                        if(!isset($NewtotalArray[$currencyName]['totalamount']))
                        {
                           $NewtotalArray[$currencyName]['totalamount']=0;
                        }
                        if(!isset($NewtotalArray[$currencyName]['totalquanitity']))
                        {
                           $NewtotalArray[$currencyName]['totalquanitity']=0;
                        }
                        if(!isset($NewtotalArray[$currencyName]['totalpgfee']))
                        {
                           $NewtotalArray[$currencyName]['totalpgfee']=0;
                        }
                        if(!isset($NewtotalArray[$currencyName]['total_servicetax']))
                        {
                           $NewtotalArray[$currencyName]['total_servicetax']=0;

                        }
                        if(!isset($NewtotalArray[$currencyName]['totalgoeventfee']))
                        {
                           $NewtotalArray[$currencyName]['totalgoeventfee']=0;
                        }
                        if(!isset($NewtotalArray[$currencyName]['totalmarketingcharges']))
                        {
                           $NewtotalArray[$currencyName]['totalmarketingcharges']=0;
                        }
                        if(!isset($NewtotalArray[$currencyName]['totalpramotionaldiscount']))
                        {
                           $NewtotalArray[$currencyName]['totalpramotionaldiscount']=0;
                        }
                        if(in_array($orderbookingDetails->order_id, $orderidArray))
                        {
                          $result[$currencyName][$orderbookingDetails->order_id]['orderdetails']=$bookingData->response->calculatedDetails;
                          $result[$currencyName][$orderbookingDetails->order_id]['default_tax']=$defaulttax;
                          $result[$currencyName][$orderbookingDetails->order_id]['default_total']=$totalExtraCharges;
                        }
                        if($defaulttax=='Yes')
                        {
                          if($orderbookingDetails->extra_charges>$totalExtraCharges)
                          {
                             $NewtotalArray[$currencyName]['totalmarketingcharges']+=$orderbookingDetails->extra_charges-$totalExtraCharges;
                          }
                          else
                          {
                            $NewtotalArray[$currencyName]['totalpramotionaldiscount']+=$totalExtraCharges-$orderbookingDetails->extra_charges;
                          }
                        }
                        if(array_key_exists('pg_charges', (array)$bookingData->response->calculatedDetails))
                        {
                          $NewtotalArray[$currencyName]['totalpgfee']+=round($bookingData->response->calculatedDetails->pg_charges,2);
                        }
                        if(array_key_exists('totalServiceTax', (array)$bookingData->response->calculatedDetails))
                        {
                          
                          if(array_key_exists('totalcustomservicetax', (array)$bookingData->response->calculatedDetails))
                          {
                            $NewtotalArray[$currencyName]['total_servicetax']+=round($bookingData->response->calculatedDetails->totalServiceTax-$bookingData->response->calculatedDetails->totalcustomservicetax,2);
                          }
                          else
                          {
                            $NewtotalArray[$currencyName]['total_servicetax']+=round($bookingData->response->calculatedDetails->totalServiceTax,2);
                          }
                        }
                        if(array_key_exists('totalgoeventzfee', (array)$bookingData->response->calculatedDetails))
                        {
                          $NewtotalArray[$currencyName]['totalgoeventfee']+=$bookingData->response->calculatedDetails->totalgoeventzfee;
                        }
                      
                        $NewtotalArray[$currencyName]['totalamount']+=round($orderbookingDetails->total_amount-$orderbookingDetails->extra_charges,2);
                        $NewtotalArray[$currencyName]['totalquanitity']+=$orderbookingDetails->total_quantity;
                      }
                      
                   }
              }
              
              $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];

              $pagination[$currencyName] =  array('total' => $getcurrencybooking->total(),
                            'per_page'     => $getcurrencybooking->perPage(),
                            'current_page' => $getcurrencybooking->currentPage(),
                            'last_page'    => $getcurrencybooking->lastPage(),
                            'nextpage'     => $getcurrencybooking->nextPageUrl(),
                            'prevpage'     => $getcurrencybooking->previousPageUrl(),
                            'refrlUrl'     => $urlPage,
                            );
              $i++;
               
            }
              if(isset($request->reportfroadmin))
                {
                  return response()->json(['reportData' => $result,
                                    'totalarray' => $NewtotalArray,
                                    'pagination'=>$pagination]);
                }
                else
                {
                  return response()->json(['reportData' => $result,
                                     'customfeild'=>$customfeildArray,
                                    'customdetails' => $customdetails,
                                    'totalarray' => $NewtotalArray,
                                    'extraColumns' => $UniqueExtraColumns,
                                    'pagination'=>$pagination]);

                }
            
          }
          else
          {
            $result='noorder';
            return response()->json(['reportData' => $result]);
          }
        }
        else
        {
            $result='error';
            return response()->json(['reportData' => $result]);
        }
    }

     /////////////event report for send mail//////////////

    public function sendmailreport(Request $request)
    {
      $totalAmount=0;
      $totalquantity=0;
      $transaction=0;
      $result=array();
      $summary=array();
      $alleventsArray=array();
      $total=array();
      $currenciyId = array();
      $totalArray=array();
      $timeZone='Asia/Kolkata';
      $condition = "order_status = 'completed' and payment_mode=1";
      $commonObj = new Common();
      if($request->reportfor)
      {
        if($request->reportfor=='daily')
        {
          $condition.= " and date(CONVERT_TZ(order_time,'+00:00','+05:30')) =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }
        if($request->reportfor=='today')
        {
          $condition.= " and date(CONVERT_TZ(order_time,'+00:00','+05:30')) =  CURDATE()";
        }
        // else
        // {
        //   $condition.= " and order_time BETWEEN DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE()) - 1) DAY)
        //   AND DATE_ADD(CURDATE(), INTERVAL (7 - DAYOFWEEK(CURDATE())) DAY)";
        // }
      }
      //dd($request->reportfor);
            //////////////////get all live event //////////////////////
      
                      ////////////total for month//////////
        
          ////////get extra email////////////
          $mailtosend=array();
          $extraEmail = array();
          $conditionUser = array('userassigns.status'=>1);
          $allassignusers = $this->userassign->getallDetails($conditionUser,array('name','admin_user_id','user_id','email'));
          if(count($allassignusers)>0)
          {
            foreach($allassignusers as $allusers)
            {
              $extraEmail[$allusers->user_id][] = $allusers->email;
            }
          }
            /////////////get all order list////////////////
      $eventIdArray='';
      $orderbookingDetailsList = $this->bookingDetails->getallByRaw($condition,array('event_id','order_id','name','mobile','order_time','total_quantity','total_amount','currency_id'));
      if(count($orderbookingDetailsList)>0)
      {
        foreach($orderbookingDetailsList as $orderbookingDetails)
        {
          $currenciyId[]=$orderbookingDetails->currency_id;
          $eventIdArray.=$orderbookingDetails->event_id.',';
          $convertOrderDate = $commonObj->ConvertGMTToLocalTimezone($orderbookingDetails->order_time,$timeZone);
          $orderArray[$orderbookingDetails->event_id][$orderbookingDetails->currency_id]['title']='';
          $orderArray[$orderbookingDetails->event_id][$orderbookingDetails->currency_id]['startat']='';
          $orderArray[$orderbookingDetails->event_id][$orderbookingDetails->currency_id]['eventvenue']='';
          $orderArray[$orderbookingDetails->event_id][$orderbookingDetails->currency_id]['city']='';
          $orderArray[$orderbookingDetails->event_id][$orderbookingDetails->currency_id]['order'][$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                                                                                      'buyermobile'=>$orderbookingDetails->mobile,
                                                                                                                      'orderdate'=>$convertOrderDate,
                                                                                                                      'quantity'=>$orderbookingDetails->total_quantity,
                                                                                                                      'amount'=>$orderbookingDetails->total_amount);
           
           $totalArray[$orderbookingDetails->currency_id]['totalamount'][]=$orderbookingDetails->total_amount;
           $totalArray[$orderbookingDetails->currency_id]['totalquantity'][]=$orderbookingDetails->total_quantity;
           
        
        }
        //dd($totalArray);
        $groupby = array('event_id','currency_id');
        $totalMontahsales= array();
        $conditionMonth= " order_status = 'completed' and  event_id in ".'('.substr($eventIdArray,0,-1).')'." and concat(month(CURDATE()),'-',Year(CURDATE()))  =  concat(month(CONVERT_TZ(order_time,'+00:00','+05:30')),'-',Year(CONVERT_TZ(order_time,'+00:00','+05:30'))) and payment_mode=1";
        $selectcolumnmonth='event_id ,sum(total_amount) as totalamount,sum(total_quantity) as totalquntity,count(order_id) totalorder,currency_id';
        $monthbookingDetailsList = $this->bookingDetails->getrawListgroup($conditionMonth,$selectcolumnmonth,$groupby);
        if(count($monthbookingDetailsList)>0)
        {
          foreach($monthbookingDetailsList as $monthbookingDetailsList)
          {
            $totalMontahsales[$monthbookingDetailsList->event_id][$monthbookingDetailsList->currency_id] = array('monthtotalamt'=>$monthbookingDetailsList->totalamount,
                                                                          'monthtotalqty'=>$monthbookingDetailsList->totalquntity,
                                                                          'monthtotaltrx'=>$monthbookingDetailsList->totalorder);
          }

        }
        ///////////get total sales and total quantityfor event/////
        $totaleventsales= array();
        $conditionevent= " order_status = 'completed' and  event_id in ".'('.substr($eventIdArray,0,-1).')'." and payment_mode=1";
        $selectcolumnevent='event_id ,sum(total_amount) as totalamount,sum(total_quantity) as totalquntity,count(order_id) totalorder,currency_id';
        $totalforeventsale = $this->bookingDetails->getrawListgroup($conditionevent,$selectcolumnevent,$groupby);
        if(count($totalforeventsale)>0)
        {
          foreach($totalforeventsale as $totalforeventsale)
          {
            $totaleventsales[$totalforeventsale->event_id][$totalforeventsale->currency_id] = array('totaleventamount'=>$totalforeventsale->totalamount,
                                                                   'totaleventqty'=>$totalforeventsale->totalquntity,
                                                                   'totaleventtrx'=>$totalforeventsale->totalorder);
          }

        }
        //////////all currencyy list////////
        $allcurrency = $this->currency->getListArray(array(),'code','id');
        
          // dd($totalMontahsales);
        ///////eventdetails///////////
        $selectColumn = "users.email,title,CONVERT_TZ(start_date_time,'+00:00',timezone_value) as startdate,venue_name,city,events.id as eventid,user_id";
        $conditionRaw= "events.status=1 and events.id in ".'('.substr($eventIdArray,0,-1).')'."";
        $dataEventlist = $this->usersInterface->getallDetailslistraw($conditionRaw,$selectColumn);  
        if(count($dataEventlist)>0)
        {
           foreach($dataEventlist as $dataEventlist)
           {
            foreach($currenciyId as $currenciyId)
            {
                $currencyName=$currenciyId;
                if(array_key_exists($currenciyId, $allcurrency))
                {
                 $currencyName = $currenciyId.'--'.$allcurrency[$currencyName];
                }
                else
                {
                  $currencyName = $currenciyId.'--INR';
                }
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName] = $orderArray[$dataEventlist->eventid][$currenciyId];
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['title']=$dataEventlist->title;
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['startat']=$dataEventlist->startdate;
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventvenue']=$dataEventlist->venue_name;
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['city']=$dataEventlist->city;
                if(!isset($total[$dataEventlist->email][$currencyName]['totalamount']))
                {
                  $total[$dataEventlist->email][$currencyName]['totalamount'][]=array_sum($totalArray[$currenciyId]['totalamount']);

                }
                if(!isset($total[$dataEventlist->email][$currencyName]['totalquantity']))
                {
                   $total[$dataEventlist->email][$currencyName]['totalquantity'][]=array_sum($totalArray[$currenciyId]['totalquantity']);

                }
                 if(!isset($total[$dataEventlist->email][$currencyName]['totaltransaction']))
                {
                    $total[$dataEventlist->email][$currencyName]['totaltransaction']=$totalArray[$currenciyId]['totalquantity'];
                }
                
               
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventtotalamount']=0;
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventtotalqty']=0;
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventtotaltrx']=0;
                 $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['monthtotalamt']=0;
                $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['monthtotalqty']=0;
                
               
                $mailtosend[] =$dataEventlist->email;
                if(array_key_exists($dataEventlist->user_id, $extraEmail))
                {
                   $mailtosend = $extraEmail[$dataEventlist->user_id];
                   array_push($mailtosend, $dataEventlist->email);
                }
                if(array_key_exists($dataEventlist->eventid, $totalMontahsales))
                {
                  $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['monthtotalamt']=$totalMontahsales[$dataEventlist->eventid][$currenciyId]['monthtotalamt'];
                  $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['monthtotalqty']=$totalMontahsales[$dataEventlist->eventid][$currenciyId]['monthtotalqty'];
                }
                if(array_key_exists($dataEventlist->eventid, $totaleventsales))
                {
                   $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventtotalamount']=$totaleventsales[$dataEventlist->eventid][$currenciyId]['totaleventamount'];
                   $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventtotalqty']=$totaleventsales[$dataEventlist->eventid][$currenciyId]['totaleventqty'];
                   $result[$dataEventlist->email]['event'][$dataEventlist->eventid][$currencyName]['eventtotaltrx']=$totaleventsales[$dataEventlist->eventid][$currenciyId]['totaleventtrx'];
                }
                $result[$dataEventlist->email]['mailsendto']= array_unique($mailtosend);
            }
         }
        }
             // dd($total);

        foreach($total as $key=>$val)
        {
          foreach($val as $currency=>$currencyval)
          {
            $summary[$key][$currency]['totalamount'] = array_sum($currencyval['totalamount']);
            $summary[$key][$currency]['totalquantity'] = array_sum($currencyval['totalquantity']);
            $summary[$key][$currency]['totaltransaction'] = count($currencyval['totaltransaction']);

          }
          
        }
          
      }
      return response()->json(['reportData' => $result,
                               'summary'=>$summary]);

      
    }
       ///////////////////client booking internat reports//////////////
    public function sendreportinternaluser(Request $request)
    {
      $totalAmount=0;
      $totalquantity=0;
      $transaction=0;
      $result=array();
      $summary=array();
      $alluserArray=array();
      $total=array();
      $monthtotal = array();
      $timeZone='Asia/Kolkata';
      /////////////all internal user///////////
      $conditionUser = array('userassigns.status'=>1);
      $allusers = $this->userassign->getallDetails($conditionUser,array('name','admin_user_id','user_id','email'));
      foreach($allusers as $allusers)
      {
        $alluserArray[$allusers->email][]= $allusers->user_id; 

      }
        /////////////////////////get all assign user data//////
      $allclientArray=array();
      $condition= "events.status=1 and events.user_id in (select userassigns.user_id from userassigns where status=1  group by user_id)";
      $allclientsDetails = $this->usersInterface->getRaw($condition,array('user_id','name','email','mobile'));
      if(count($allclientsDetails)>0)
      {
        foreach($allclientsDetails as $allclientsDetails)
        {
          $allclientArray[$allclientsDetails->user_id] = array('name'=>$allclientsDetails->name,
                                                               'email'=>$allclientsDetails->email,
                                                               'mobile'=>$allclientsDetails->mobile);
        }
      }

       //////////////////////use based monthaly sales////////
      $totalMontahsales= array();
      $conditionMonth= " order_status = 'completed' and concat(month(CURDATE()),'-',Year(CURDATE()))  =  concat(month(CONVERT_TZ(order_time,'+00:00','+05:30')),'-',Year(CONVERT_TZ(order_time,'+00:00','+05:30'))) and payment_mode=1 and events.user_id in (select userassigns.user_id from userassigns where status=1  group by user_id)";
      if($request->reportfor=='lastmonth')
      {
        $conditionMonth= " order_status = 'completed' and left(CONVERT_TZ(order_time,'+00:00','+05:30'),7) =  left(DATE_SUB(CURDATE(), INTERVAL 1 month),7) and payment_mode=1 and events.user_id in (select userassigns.user_id from userassigns where status=1  group by user_id)";
      }
      $selectcolumnmonth='events.user_id as userid,sum(total_amount) as totalamount,sum(total_quantity) as totalquntity,count(order_id) totalorder';
      $monthbookingDetailsList = $this->bookingDetails->getallorderByRaw($conditionMonth,$selectcolumnmonth,'events.user_id');
      if(count($monthbookingDetailsList)>0)
      {
        foreach($monthbookingDetailsList as $monthbookingDetailsList)
        {
          $totalMontahsales[$monthbookingDetailsList->userid] = array('monthtotalamt'=>$monthbookingDetailsList->totalamount,
                                                                      'monthtotalqty'=>$monthbookingDetailsList->totalquntity,
                                                                      'monthtotaltrx'=>$monthbookingDetailsList->totalorder);
        }

      }

        ////////////get all user event//////////
      $condition= " order_status = 'completed' and payment_mode=1 and events.user_id in (select userassigns.user_id from userassigns where status=1  group by user_id)";
      $commonObj = new Common();
      if($request->reportfor)
      {
        //////////Sales summary for yesterday/////////////
        if($request->reportfor=='yesterday')
        {
          $condition.= " and date(CONVERT_TZ(order_time,'+00:00','+05:30')) =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }
        if($request->reportfor=='lastmonth')
        {
          $condition.= " and left(CONVERT_TZ(order_time,'+00:00','+05:30'),7) =  left(DATE_SUB(CURDATE(), INTERVAL 1 month),7)";
        }
        
      }
      $selectcolumn='event_id,title,events.user_id,sum(total_amount) as totalamount,sum(total_quantity) as totalquntity,count(order_id) totalorder';
      $orderbookingDetailsList = $this->bookingDetails->getallorderByRaw($condition,$selectcolumn);

       $useridArray = array();
      if(count($orderbookingDetailsList)>0)
      {
        foreach ($orderbookingDetailsList as $orderbookingDetailsList) 
        {
          $useridArray[]=$orderbookingDetailsList->user_id;
          $result[$orderbookingDetailsList->user_id]['event'][$orderbookingDetailsList->event_id] =array('title'=>$orderbookingDetailsList->title,
                                                                                                'totalamount'=>$orderbookingDetailsList->totalamount,
                                                                                                'totalquntity'=>$orderbookingDetailsList->totalquntity,
                                                                                                'totalorder'=>$orderbookingDetailsList->totalorder,
                                                                                                'orgname'=>'',
                                                                                                'orgemail'=>'',
                                                                                                'orgmobile'=>'');
          if(array_key_exists($orderbookingDetailsList->user_id, $allclientArray))
          {
            $result[$orderbookingDetailsList->user_id]['event'][$orderbookingDetailsList->event_id]['orgname']=$allclientArray[$orderbookingDetailsList->user_id]['name'];
            $result[$orderbookingDetailsList->user_id]['event'][$orderbookingDetailsList->event_id]['orgemail']=$allclientArray[$orderbookingDetailsList->user_id]['email'];
            $result[$orderbookingDetailsList->user_id]['event'][$orderbookingDetailsList->event_id]['orgmobile']=$allclientArray[$orderbookingDetailsList->user_id]['mobile'];
          }

         
           $result[$orderbookingDetailsList->user_id]['totalamount'][]=$orderbookingDetailsList->totalamount;
           $result[$orderbookingDetailsList->user_id]['totalquantity'][]=$orderbookingDetailsList->totalquntity;
           $result[$orderbookingDetailsList->user_id]['totaltransaction'][]=$orderbookingDetailsList->totalorder;
        }
       

         ///////////////////////make final array ////////////
        $finalArrayData = array();

        foreach($alluserArray as $key=>$val)
        {
           $monthtotal[$key]['monthtotalamt'][] = 0;
           $monthtotal[$key]['monthtotalqty'][]= 0;
           $monthtotal[$key]['monthtotaltrx'][]=0;
           $finalArrayData[$key]['event']= '';
            $total[$key]['totalamount'][] = 0;
            $total[$key]['totalquantity'][] = 0;
            $total[$key]['totaltransaction'][] =0;
          foreach($val as $Assignuserid)
          {
              
            if(array_key_exists($Assignuserid, $result))
            {
              $finalArrayData[$key]['event'][$Assignuserid] = $result[$Assignuserid]['event'];
              $total[$key]['totalamount'][] = array_sum($result[$Assignuserid]['totalamount']);
              $total[$key]['totalquantity'][] = array_sum($result[$Assignuserid]['totalquantity']);
              $total[$key]['totaltransaction'][] = array_sum($result[$Assignuserid]['totaltransaction']);
            }
            
            if(array_key_exists($Assignuserid, $totalMontahsales))
            {
                $monthtotal[$key]['monthtotalamt'][] = $totalMontahsales[$Assignuserid]['monthtotalamt'];
                $monthtotal[$key]['monthtotalqty'][]= $totalMontahsales[$Assignuserid]['monthtotalqty'];
                $monthtotal[$key]['monthtotaltrx'][]= $totalMontahsales[$Assignuserid]['monthtotaltrx'];
            }
          }
        }
        foreach($total as $key=>$val)
        {
          $finalArrayData[$key]['totalamount'] = array_sum($val['totalamount']);
          $finalArrayData[$key]['totalquantity'] = array_sum($val['totalquantity']);
          $finalArrayData[$key]['totaltransaction'] = array_sum($val['totaltransaction']);
        }
        foreach($monthtotal as $key=>$monthval)
        {
          $finalArrayData[$key]['monthtotalamt'] = array_sum($monthval['monthtotalamt']);
          $finalArrayData[$key]['monthtotalqty'] = array_sum($monthval['monthtotalqty']);
          $finalArrayData[$key]['monthtotaltrx'] = array_sum($monthval['monthtotaltrx']);

        }
       return response()->json(['reportData' => $finalArrayData]);
      }
      else
      {
          $finalArrayData = array();
          foreach($alluserArray as $key=>$val)
          {
            foreach($val as $Assignuserid)
            {
              if(array_key_exists($Assignuserid, $totalMontahsales))
              {
                $finalArrayData[$key]['event']='';
                $monthtotal[$key]['monthtotalamt'][] = $totalMontahsales[$Assignuserid]['monthtotalamt'];
                $monthtotal[$key]['monthtotalqty'][]= $totalMontahsales[$Assignuserid]['monthtotalqty'];
                $monthtotal[$key]['monthtotaltrx'][]= $totalMontahsales[$Assignuserid]['monthtotaltrx'];
              }
            }
          }
          foreach($monthtotal as $key=>$val)
          {
            $finalArrayData[$key]['totalamount'] = 0;
            $finalArrayData[$key]['totalquantity'] = 0;
            $finalArrayData[$key]['totaltransaction'] = 0;
            $finalArrayData[$key]['monthtotalamt'] = array_sum($val['monthtotalamt']);
            $finalArrayData[$key]['monthtotalqty'] = array_sum($val['monthtotalqty']);
            $finalArrayData[$key]['monthtotaltrx'] = array_sum($val['monthtotaltrx']);

          }
         // $result=array();
          return response()->json(['reportData' => $finalArrayData]);
      }
     
    }

    ///////////////////client based internat reports//////////////
    public function clientreorts(Request $request)
    {
      $result=array();
      $allclientmonthArray = array();
      $alluserArray=array();
      $allclientArray = array();
      $timeZone='Asia/Kolkata';
      $userIDArray='';
      /////////////all internal user///////////
      $conditionUser = array('userassigns.status'=>1);
      $allusers = $this->userassign->getallDetails($conditionUser,array('name','admin_user_id','user_id','email'));
      foreach($allusers as $allusers)
      {
        $userIDArray.=$allusers->user_id.',';
        $alluserArray[$allusers->email][]= $allusers->user_id; 

      }
       /////////////all event tickets data////////////////////////
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

      //////////all new client in month/////
      $conditionmonth="events.status=1 and ticketed=1 and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      if($request->reportfor=='lastmonth')
      {
        $allclientsmonth = $this->usersInterface->getRaw($conditionmonth,array('user_id','name','email','mobile',DB::raw("if(left(CONVERT_TZ(events.created_at,'+00:00','+05:30'),7) =  left(DATE_SUB(CURDATE(), INTERVAL 1 month),7),'Yes','No') as clientstatus")));
      }
      else
      {
        $allclientsmonth = $this->usersInterface->getRaw($conditionmonth,array('user_id','name','email','mobile',DB::raw("if(left(CONVERT_TZ(events.created_at,'+00:00','+05:30'),7) =  left(CURDATE(),7),'Yes','No') as clientstatus")));
      }
      if(count($allclientsmonth)>0)
      {
        foreach($allclientsmonth as $allclientsmonth)
        {
          if($allclientsmonth->clientstatus=='Yes')
          {
            $allclientmonthArray[$allclientsmonth->user_id] = array('name'=>$allclientsmonth->name,
                                                                    'email'=>$allclientsmonth->email,
                                                                    'mobile'=>$allclientsmonth->mobile);
          }
        }
      }
       //////////all events in month/////
      $allpaideventsArray = array();
      $condition= "events.status=1 and left(CURDATE(),7) =  left(created_at,7) and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      if($request->reportfor=='lastmonth')
      {
        $condition= "events.status=1 and left(CONVERT_TZ(events.created_at,'+00:00','+05:30'),7) =  left(DATE_SUB(CURDATE(), INTERVAL 1 month),7) and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      }
      $selectcolumnRaw = "user_id,id";
      $alleventdetail = $this->event->getrawList($condition,$selectcolumnRaw);
      if(count($alleventdetail)>0)
      { 
        foreach($alleventdetail as $alleventdetail)
        {
          if(array_key_exists($alleventdetail->id, $ticketArray))
          {
            if($ticketArray[$alleventdetail->id]['maxprice']>0)
            {
              $allpaideventsArray[$alleventdetail->user_id][] = $alleventdetail->id;
            }
          }
        }
      }

      /////////////all clients details ///////////
      $pramtosend=0;
      $condition= "events.status=1 and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      $commonObj = new Common();
      if($request->reportfor)
      {
        ////////all  Clients who has events Today////
        if($request->reportfor=='today')
        {
          $condition.= " and curdate() BETWEEN CONVERT_TZ(start_date_time,'+00:00',timezone_value) AND CONVERT_TZ(end_date_time,'+00:00',timezone_value)";
        }
        ////////all  Clients who has events yesterday////
        else if($request->reportfor=='yesterday')
        {
          $condition.= " and DATE_SUB(CURDATE(), INTERVAL 1 DAY) BETWEEN CONVERT_TZ(start_date_time,'+00:00',timezone_value) AND CONVERT_TZ(end_date_time,'+00:00',timezone_value)";
        }
        ////////all Clients whose events went live today////
        else if($request->reportfor=='livetoday')
        {

          $condition.= " and date(CONVERT_TZ(events.created_at,'+00:00','+05:30')) = CURDATE()";
        }
         ////////all Clients whose events went live yesterday////
        else if($request->reportfor=='liveyesterday')
        {

          $condition.= " and date(CONVERT_TZ(events.created_at,'+00:00','+05:30')) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }
            ////////all Clients Active////
        else if($request->reportfor=='active')
        {

          $condition.= " and (IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`) >= '".date('Y-m-d H:i:s')."')";
        }
      }
      $selectArray = array('user_id','name','email','mobile');
      if($request->reportfor=='newclients')
      {
        $selectArray = array('user_id','name','email','mobile',DB::raw("if(date(CONVERT_TZ(events.created_at,'+00:00','+05:30')) =  date(DATE_SUB(CURDATE(), INTERVAL 1 DAY)),'Yes','No') as clientstatus"));
      }
      $allclientsDetails = $this->usersInterface->getRaw($condition,$selectArray);
      if(count($allclientsDetails)>0)
      {
        if($request->reportfor=='newclients')
        {
            foreach($allclientsDetails as $allclientsDetails)
            {
              if($allclientsDetails->clientstatus=='Yes')
              {
                  $allclientArray[$allclientsDetails->user_id] = array('name'=>$allclientsDetails->name,
                                                                       'email'=>$allclientsDetails->email,
                                                                       'mobile'=>$allclientsDetails->mobile);
              }
            }
        }
        else
        {
          foreach($allclientsDetails as $allclientsDetails)
          {
            $allclientArray[$allclientsDetails->user_id] = array('name'=>$allclientsDetails->name,
                                                                 'email'=>$allclientsDetails->email,
                                                                 'mobile'=>$allclientsDetails->mobile);
          }
        }
        
        
        foreach($alluserArray as $key=>$val)
        {
          $i=0;
          $result[$key]['client']='';
          $eventinmonth=0;
          foreach($val as $Assignuserid)
          {
            if(array_key_exists($Assignuserid, $allclientArray))
            {
              $result[$key]['client'][$Assignuserid] = $allclientArray[$Assignuserid];
            }
            if(array_key_exists($Assignuserid, $allclientmonthArray))
            {
              $i++;
            }
            if(array_key_exists($Assignuserid, $allpaideventsArray))
            {
              $eventinmonth+=count($allpaideventsArray[$Assignuserid]);
            }
              $result[$key]['month'] = $i;
              $result[$key]['eventinmonth'] = $eventinmonth;
          }
        }
       return response()->json(['reportData' => $result]);

      }
      else
      {
        foreach($alluserArray as $key=>$val)
        {
           $i=0;
           $eventinmonth=0;
          foreach($val as $Assignuserid)
          {
              $result[$key]['client']='';
              if(array_key_exists($Assignuserid, $allclientmonthArray))
              {
                $i++;
              }
              if(array_key_exists($Assignuserid, $allpaideventsArray))
              {
                $eventinmonth+=count($allpaideventsArray[$Assignuserid]);
              }
              $result[$key]['month'] = $i;
              $result[$key]['eventinmonth'] = $eventinmonth;
          }
        }
          return response()->json(['reportData' => $result]);
      }
      //return response()->json(['reportData' => $allclientsDetails]);
    }

    /////////////////// event based internat reports//////////////
    public function eventbasedreport(Request $request)
    {
      $result=array();
      $alluserArray=array();
      $alleventArray = array();
      $timeZone='Asia/Kolkata';
      $userIDArray='';
            /////////////all internal user///////////
      $conditionUser = array('userassigns.status'=>1);
      $allusers = $this->userassign->getallDetails($conditionUser,array('name','admin_user_id','user_id','email'));
      foreach($allusers as $allusers)
      {
        $userIDArray.=$allusers->user_id.',';
        $alluserArray[$allusers->email.'/'.$allusers->admin_user_id][]= $allusers->user_id; 

      }
           /////////////////////////get all assign user data//////
      $allclientArray=array();
      $conditionclient= "events.status=1 and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      $allclientsDetails = $this->usersInterface->getRaw($conditionclient,array('user_id','name','email','mobile'));
      if(count($allclientsDetails)>0)
      {
        foreach($allclientsDetails as $allclientsDetails)
        {
          $allclientArray[$allclientsDetails->user_id] = array('name'=>$allclientsDetails->name,
                                                               'email'=>$allclientsDetails->email,
                                                               'mobile'=>$allclientsDetails->mobile);
        }
      }
            ///////////////////get all order list ///////////////////////
      $bookingArray=array();
      $conditionorder= " order_status = 'completed' and payment_mode=1 and  events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      $selectcolumn='event_id,title,events.user_id,sum(total_amount) as totalamount,sum(total_quantity) as totalquntity,count(order_id) totalorder';
      $orderbookingDetailsList = $this->bookingDetails->getallorderByRaw($conditionorder,$selectcolumn);
      if(count($orderbookingDetailsList)>0)
      {
        foreach ($orderbookingDetailsList as $orderbookingDetailsList) 
        {
          $bookingArray[$orderbookingDetailsList->event_id] =array('totalamount'=>$orderbookingDetailsList->totalamount,
                                                                   'totalquntity'=>$orderbookingDetailsList->totalquntity,
                                                                   'totalorder'=>$orderbookingDetailsList->totalorder);
        }
      }
                  /////////////all event tickets data////////////////////////
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
                 /////////////all event details  details ///////////
   
      $condition= "events.status=1 and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
      $conditionmonth= "events.status=1 and left(CURDATE(),7) =  left(created_at,7) and events.user_id in ".'('.substr($userIDArray,0,-1).')'."";
     
      $commonObj = new Common();
      if($request->reportfor)
      {
        //////////discovery event live yesterday////////
        if($request->reportfor=='discovery')
        {
          $condition= " events.status=1 and user_id=3 and date(CONVERT_TZ(events.created_at,'+00:00','+05:30')) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
          $conditionmonth.=" and user_id=3";
        }
        /////////// all the events that were live yesterday///////
        if($request->reportfor=='yesterday')
        {
          $condition.= " and date(CONVERT_TZ(end_date_time,'+00:00',timezone_value)) >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }

        ///////////events that were created and was made live yesterday///////
       if($request->reportfor=='liveyesterday')
        {

          $condition.= " and date(CONVERT_TZ(events.created_at,'+00:00','+05:30')) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }
        //////////Sales summary for Events Starting Today/////////////
        if($request->reportfor=='starttoday')
        {
          $condition.= " and curdate() = date(CONVERT_TZ(start_date_time,'+00:00',timezone_value))";
        }
        //////////Sales summary for Events started Yesterday/////////////
        if($request->reportfor=='startyesterday')
        {
          $condition.= " and DATE_SUB(CURDATE(), INTERVAL 1 DAY) =  date(CONVERT_TZ(start_date_time,'+00:00',timezone_value))";
        }
      }
       //////////all events in month/////
      $totalmonthevents = array();
      $selectcolumnRawmonth = "user_id,modified_by,id";
      $allmontheventdetail = $this->event->getrawList($conditionmonth,$selectcolumnRawmonth);
      if(count($allmontheventdetail)>0)
      { 
        foreach($allmontheventdetail as $allmontheventdetail)
        {
          $useridkeymonth = $allmontheventdetail->user_id;
          if($request->reportfor=='discovery')
          {
            $useridkeymonth = $allmontheventdetail->modified_by;
          }
          $totalmonthevents[$useridkeymonth][] = $allmontheventdetail->id;
        }
      }
       //////////all events on condition bases/////
      $selectcolumnRaw = "user_id,id,title,modified_by,city,CONVERT_TZ(events.start_date_time,'+00:00',timezone_value) as start_date";
      $alleventdetail = $this->event->getrawList($condition,$selectcolumnRaw);
      if(count($alleventdetail)>0)
      {
        foreach($alleventdetail as $alleventdetail)
        {
          $useridkey = $alleventdetail->user_id;
          if($request->reportfor=='discovery')
          {
            $useridkey = $alleventdetail->modified_by;
          }
          $alleventArray[$useridkey][$alleventdetail->id] = array('title'=>$alleventdetail->title,
                                                                  'start'=>$alleventdetail->start_date,
                                                                  'city'=>$alleventdetail->city,
                                                                  'orgname'=>'',
                                                                  'orgemail'=>'',
                                                                  'orgmobile'=>'',
                                                                  'totalamount'=>'',
                                                                  'totalquntity'=>'',
                                                                  'totalorder'=>'',
                                                                  'maxticketprice'=>'',
                                                                  'minticketprice'=>'');
          if(array_key_exists($alleventdetail->user_id, $allclientArray))
          {
            $alleventArray[$useridkey][$alleventdetail->id]['orgname']=$allclientArray[$alleventdetail->user_id]['name'];
            $alleventArray[$useridkey][$alleventdetail->id]['orgemail']=$allclientArray[$alleventdetail->user_id]['email'];
            $alleventArray[$useridkey][$alleventdetail->id]['orgmobile']=$allclientArray[$alleventdetail->user_id]['mobile'];
          }
          if(array_key_exists($alleventdetail->id, $bookingArray))
          {
             $alleventArray[$useridkey][$alleventdetail->id]['totalamount'] =$bookingArray[$alleventdetail->id]['totalamount'] ;
             $alleventArray[$useridkey][$alleventdetail->id]['totalquntity'] =$bookingArray[$alleventdetail->id]['totalquntity'] ;
             $alleventArray[$useridkey][$alleventdetail->id]['totalorder'] =$bookingArray[$alleventdetail->id]['totalorder'] ;

          }
          if(array_key_exists($alleventdetail->id, $ticketArray))
          {
             $alleventArray[$useridkey][$alleventdetail->id]['maxticketprice'] =$ticketArray[$alleventdetail->id]['maxprice'] ;
             $alleventArray[$useridkey][$alleventdetail->id]['minticketprice'] =$ticketArray[$alleventdetail->id]['minprice'] ;
          
          }
        }
        foreach($alluserArray as $key=>$val)
        {
          $eventinmonth=0;
          $makeKeys = explode('/', $key);
          $result[$makeKeys[0]]['event']='';
          foreach($val as $Assignuserid)
          {
            $useridkeytocheck = $Assignuserid;
            if($request->reportfor=='discovery')
            {
              $useridkeytocheck = $makeKeys[1];
            }
            if(array_key_exists($useridkeytocheck, $alleventArray))
            {
              $result[$makeKeys[0]]['event'][$useridkeytocheck] = $alleventArray[$useridkeytocheck];
            }
            if(array_key_exists($useridkeytocheck, $totalmonthevents))
            {
                $eventinmonth+=count($totalmonthevents[$useridkeytocheck]);
            }
            $result[$makeKeys[0]]['eventinmonth'] = $eventinmonth;
          }
        }
       return response()->json(['reportData' => $result]);
      }
      else
      {
        $result=array();
        return response()->json(['reportData' => $result]);
      }
      //return response()->json(['reportData' => $allclientsDetails]);
    }

 //////////////////get all order details////////////////
    private function getalleventreports($request)
    {
      $result='';
      $customdetails='';
      $customfeild='';
      $condition = "order_status = 'completed'";
      if($request->commonfeilds)
      {
        $condition.= "and (order_id = '".$request->commonfeilds."' or event_id = '".$request->commonfeilds."' or transaction_id = '".$request->commonfeilds."')";
      }
      if(!empty($request->paymentmode))
      {
        $condition.= "and payment_mode = '".$request->paymentmode."'";
      }
      if(!empty($request->getway))
      {
        $condition.= "and payment_gateway = '".$request->getway."'";
      }
      if($request->dateto || $request->enddateto)
      {
        $condition.= " and order_time between  '".$request->dateto."' and '".$request->enddateto."'";
      }
      if(isset($request->dateselect) && $request->dateselect!='')
      {
        $getdates = explode(',',$request->dateselect.',');
        if($getdates[1]=='')
        {
          $getdates[1] = $getdates[0];
        }
        $condition.= " and date(CONVERT_TZ(order_time,'+00:00','+05:30')) between  '".$getdates[0]."' and '".$getdates[1]."'";
      }
      if(!empty($request->bookingfrom) && $request->bookingfrom>=0)
      {
        if($request->bookingfrom==1)
        {
          $condition.= " and booking_from=1";
        }
        else
        {
          $condition.= " and booking_from=0";
        
        }
      }
      if($request->reportfor)
      {
        
        if($request->reportfor=='daily')
        {
          $condition.= " and date(order_time) =  '".date('Y-m-d')."'";

        }
        else
        {
          $condition.= " and order_time BETWEEN DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE()) - 1) DAY)
          AND DATE_ADD(CURDATE(), INTERVAL (7 - DAYOFWEEK(CURDATE())) DAY)";
        }

      }
      if(!empty($request->accountmanager) && $request->accountmanager>=0)
      {
        $condition.= " and events.user_id in (select userassigns.user_id from userassigns where status=1 and userassigns.admin_user_id='".$request->accountmanager."' group by user_id,admin_user_id)";

      }
      if($request->usertype==2)
      {
        $userId = Auth::user();
        $condition.= " and events.user_id in (select userassigns.user_id from userassigns where status=1 and userassigns.admin_user_id='".$userId->id."' group by user_id,admin_user_id)";
      }
      $alleventsArray = array();
      $assignedtoArray=array();
     
      $orderbookingDetailsList = $this->bookingDetails->getallBypaginate($request ,$condition);
      // if($request->withoutpage)
      // {
      //   $orderbookingDetailsList = $this->bookingDetails->getallByRaw($condition);

      // }

      ////////////assign userslist////////
      $assignArray = array();
      $checkAssign = $this->userassign->getallBy(array('status'=>1),array('admin_user_id','user_id'));
      if(count($checkAssign)>0)
      {
        foreach($checkAssign as $checkAssign)
        {
          $assignArray[$checkAssign->user_id][] = $checkAssign->admin_user_id;
        }
      }
      ////////////////all admin userslist//////
      $alluserArray = array();
      $whereCondition = array('user_type'=>1);
      $userData = $this->usersInterface->getallBy($whereCondition,array('id','name'));
      foreach($userData as $userData)
      {
        $alluserArray[$userData->id] = $userData->name;
      }
      // print_r($alluserArray);
      // print_r($assignArray);
      // die;
      
      $id='id';
      $value='email';
      $userData = $this->usersInterface->getList(array('status'=>1),$id,$value);
      $selectColumn = array('users.email','name','email','mobile','title','start_date_time','venue_name','address1','events.created_at','events.id','ipaddress','user_id','events.created_by');
      $conditionRaw= "private=0";
      $dataEventlist = $this->usersInterface->getallDetailslist($conditionRaw,$selectColumn);  
      if(count($dataEventlist)>0)
      {
         foreach($dataEventlist as $dataEventlist)
         {
            $assignto='';
            if(array_key_exists($dataEventlist->user_id, $assignArray))
            {
              $assinadminId = $assignArray[$dataEventlist->user_id];
              foreach($assinadminId as $assinadminId)
              {
                if(array_key_exists($assinadminId, $alluserArray))
                {
                  $assignto.=$alluserArray[$assinadminId].', ';
                }
              }
            }
            $createdBy=$dataEventlist->email;
            if(array_key_exists($dataEventlist->created_by, $userData))
            {
              $createdBy=$userData[$dataEventlist->created_by];
            }
              $alleventsArray[$dataEventlist->id] = array('title'=>$dataEventlist->title,
                                                          'user_id'=>$dataEventlist->title,
                                                          'eventvenue'=>$dataEventlist->venue_name,
                                                          'eventaddress'=>$dataEventlist->address1,
                                                          'ipaddress'=>$dataEventlist->ipaddress,
                                                          'assignto'=>$assignto,
                                                          'orgdetail'=>$dataEventlist->email.'/'.$dataEventlist->name.'/'.$dataEventlist->mobile,
                                                          'startsat'=>$dataEventlist->start_date_time,
                                                          'createdat'=>$dataEventlist->created_at,
                                                          'createdBy'=>$createdBy);
         }
      }

      $customfeildArray=array();
      $customdetails=array(); 
      $feildArray = array();
      $orderidArray = array();
      $customfeilddetails = array();     
      $customfeilds = $this->eventfeild->getallBy(array(),array('name','id','ticket_id','type'));
      foreach($customfeilds as $customfeilds)
      {
        //$feildArray[]=$customfeilds->id;
        $customfeilddetails[$customfeilds->id]=array('name'=>$customfeilds->name,'type'=>$customfeilds->type);

      }

        
      // print_r($customdetails);
      // die;
      if(count($orderbookingDetailsList)>0)
      {
        $totalAmount=0;
        $totalquantity=0;
        
         foreach($orderbookingDetailsList as $orderbookingDetails)
         {
            $orderidArray[]=$orderbookingDetails->order_id;
            $bookingData = json_decode($orderbookingDetails->details);
            $result[$orderbookingDetails->order_id] = array('buyername'=>$orderbookingDetails->name,
                                                            'buyeremail'=>$orderbookingDetails->email,
                                                            'buyermobile'=>$orderbookingDetails->mobile,
                                                            'orderdate'=>$orderbookingDetails->order_time,
                                                            'quantity'=>$orderbookingDetails->total_quantity,
                                                            'amount'=>$orderbookingDetails->total_amount,
                                                            'orderdetails'=>$bookingData->response->calculatedDetails,
                                                            'discount'=>$orderbookingDetails->discount,
                                                            'transaction_id'=>$orderbookingDetails->transaction_id,
                                                            'eventtitle'=>'',
                                                            'eventvenue'=>'',
                                                            'eventaddress'=>'',
                                                            'ipaddress'=>'',
                                                            'assignto'=>'',
                                                            'orgdetail'=>'',
                                                            'startsat'=>'',
                                                            'createdBy'=>'',
                                                            'coupon_code'=>$orderbookingDetails->coupon_code);
            
            if(array_key_exists($orderbookingDetails->event_id, $alleventsArray))
            {
              $result[$orderbookingDetails->order_id]['eventtitle']=$alleventsArray[$orderbookingDetails->event_id]['title'];
              $result[$orderbookingDetails->order_id]['eventvenue']=$alleventsArray[$orderbookingDetails->event_id]['eventvenue'];
              $result[$orderbookingDetails->order_id]['eventaddress']=$alleventsArray[$orderbookingDetails->event_id]['eventaddress'];
              $result[$orderbookingDetails->order_id]['ipaddress']=$alleventsArray[$orderbookingDetails->event_id]['ipaddress'];
              $result[$orderbookingDetails->order_id]['startsat']=$alleventsArray[$orderbookingDetails->event_id]['startsat'];
              $result[$orderbookingDetails->order_id]['assignto']=$alleventsArray[$orderbookingDetails->event_id]['assignto'];
              $result[$orderbookingDetails->order_id]['createdBy']=$alleventsArray[$orderbookingDetails->event_id]['createdBy'];
              $result[$orderbookingDetails->order_id]['createdat']=$alleventsArray[$orderbookingDetails->event_id]['createdat'];
              $result[$orderbookingDetails->order_id]['orgdetail']=$alleventsArray[$orderbookingDetails->event_id]['orgdetail'];
            }
            $totalAmount+=$orderbookingDetails->total_amount;
            $totalquantity+=$orderbookingDetails->total_quantity;
            $customdetails[$orderbookingDetails->order_id] ='';
            
                 //make order custom field for order//
            
         }
         $getorderinfo = $this->customfieldsvalue->groupByallIn($orderidArray,array('ticket_id','event_id','order_id','event_custom_fields_id','value','position'),array('order_id','event_custom_fields_id','ticket_id','position'));
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
                $customdetails[$getorderinfo->order_id][$getorderinfo->ticket_id][$getorderinfo->position][$getorderinfo->event_custom_fields_id] = $_ENV['CF_LINK'].'/event/'.$getorderinfo->event_id.'/customfeild/'.$getorderinfo->value;
              }
            }
         }

         $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
         if($request->withoutpage)
         {
          $pagination = array();
          
         }
         else
         {
          $pagination =  array('total' => $orderbookingDetailsList->total(),
                        'per_page'     => $orderbookingDetailsList->perPage(),
                        'current_page' => $orderbookingDetailsList->currentPage(),
                        'last_page'    => $orderbookingDetailsList->lastPage(),
                        'nextpage'     => $orderbookingDetailsList->nextPageUrl(),
                        'prevpage'     => $orderbookingDetailsList->previousPageUrl(),
                        'refrlUrl'     => $urlPage,
                        'REQUEST_URI'=>$_SERVER['REQUEST_URI']
                        ); 
         }

        

         return response()->json(['reportData' => $result,
                                  'pagination'=>$pagination,
                                  'customfeild'=>$customfeildArray,
                                  'customdetails' => $customdetails,
                                  'totalamount' => $totalAmount,
                                  'totalquanitity' => $totalquantity]);
      }
      else
      {
          $result='error';
          return response()->json(['reportData' => $result]);
      }
     
    }

     
    private function completeeventreport($request)
    {

      $result='';
      $alleventsArray = array();
      $selectColumn ='event_id,user_id,total_amount,total_quantity,total_txn,amount_payable,amount_paid,adjustment,title,assignto,org_email,org_name,org_mobile,start_date_time as startdate,IF(end_date_time = "0000-00-00 00:00:00", start_date_time, end_date_time) as enddate, event_type,created_at,event_create_on,id,user_id,(CASE  when round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time)<curdate() THEN "1" 
         when round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time) between curdate() and DATE_ADD(curdate(),INTERVAL 3 day) THEN "2"  when round(amount_payable-amount_paid-adjustment) > 0  THEN "3"   ELSE "4"  END) AS paymentstatus';
      $conditionRaw= "user_id!=3";
      if($request->commonfeilds)
      {
        if(is_numeric($request->commonfeilds))
        {
          $conditionRaw.= " and event_id='".$request->commonfeilds."'";
        }
        else
        {
          $conditionRaw.= " and  org_email ='".$request->commonfeilds."'";
        }
      }
      if($request->pending)
      {
         $conditionRaw.= " and amount_payable-amount_paid-adjustment > 0";
      }
      if($request->priority)
      {
        switch($request->priority)
        {
          case 'red':
           $conditionRaw.= " and round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time)<curdate()";
            break;
            case 'orange':
            $conditionRaw.= " and round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time) between curdate() and DATE_ADD(curdate(),INTERVAL 3 day)";
            break;
            case 'blue':
           $conditionRaw.= " and round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time) > DATE_ADD(curdate(),INTERVAL 7 day)";
            break;
          default:
             $conditionRaw.= " and round(amount_payable-amount_paid-adjustment) = 0";
            break;
        }
      }
      if(isset($request->eventtype) && $request->eventtype>=0 &&  $request->eventtype!='')
      {
        $conditionRaw.= " and event_type='".$request->eventtype."'";
      }
      // if($request->dateto || $request->enddateto)
      // {
      //   $conditionRaw.= " and date(CONVERT_TZ(event_create_on,'+00:00','+05:30')) between  '".$request->dateto."' and '".$request->enddateto."'";
      // }
      if(isset($request->dateselect) && $request->dateselect!='')
      {
        $getdates = explode(',',$request->dateselect.',');
        if($getdates[1]=='')
        {
          $getdates[1] = $getdates[0];
        }
        $conditionRaw.= " and date(CONVERT_TZ(event_create_on,'+00:00','+05:30')) between '".$getdates[0]."' and '".$getdates[1]."'";
      }
      $dataEventlist = $this->amountpaid->getallpaginate($request,$conditionRaw,$selectColumn,'paymentstatus');
      //dd($dataEventlist);
      if(count($dataEventlist)>0)
      {
         foreach($dataEventlist as $dataEvent)
         {
            $result[$dataEvent->event_id] = array('title'=>$dataEvent->title,
                                            'user_id'=>$dataEvent->user_id,
                                            'assignto'=>$dataEvent->assignto,
                                            'orgname'=>$dataEvent->org_name,
                                            'orgemail'=>$dataEvent->org_email,
                                            'orgmobile'=>$dataEvent->org_mobile,
                                            'eventtype'=>$dataEvent->event_type,
                                            'startdate'=>$dataEvent->startdate,
                                            'paymentstatus'=>$dataEvent->paymentstatus,
                                            'enddate'=>$dataEvent->enddate,
                                            'createdat'=>$dataEvent->event_create_on,
                                            'totalamount'=>round($dataEvent->total_amount,2),
                                            'amountpaid'=>round($dataEvent->amount_paid,2),
                                            'adjustment'=>round($dataEvent->adjustment,2),
                                            'paybalamount'=>round($dataEvent->amount_payable,2),
                                            'totalquantity'=>$dataEvent->total_quantity,
                                            'totaltransaction'=>$dataEvent->total_txn);
         }
          /////////////
         $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
         if(isset($request->withoutpage))
         {
          $pagination = array();
         }
         else
         {
            $pagination =  array('total' => $dataEventlist->total(),
                          'per_page'     => $dataEventlist->perPage(),
                          'current_page' => $dataEventlist->currentPage(),
                          'last_page'    => $dataEventlist->lastPage(),
                          'nextpage'     => $dataEventlist->nextPageUrl(),
                          'prevpage'     => $dataEventlist->previousPageUrl(),
                          'refrlUrl'     => $urlPage,
                          'REQUEST_URI'=>$_SERVER['REQUEST_URI']
                          ); 
         }
         return response()->json(['reportData' => $result,
                                  'pagination'=>$pagination]);
      }
      else
      {
          $result='error';
          return response()->json(['reportData' => $result,
                                   'pagination'=>'']);
      }
    }
    //////////////////complete event report////////////////
    public function eventpaymentreport(Request $request)
    {
      $selectColumn ='event_id,user_id,total_amount,total_quantity,total_txn,amount_payable,adjustment,amount_paid,title,assignto,org_email,org_name,org_mobile,start_date_time as startdate,IF(end_date_time = "0000-00-00 00:00:00", start_date_time, end_date_time) as enddate, event_type,created_at,event_create_on,id,user_id,(CASE  when round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time)<curdate() THEN "1" 
         when round(amount_payable-amount_paid-adjustment) > 0 and date(end_date_time) between curdate() and DATE_ADD(curdate(),INTERVAL 3 day) THEN "2"  when round(amount_payable-amount_paid-adjustment) > 0 THEN "3"   ELSE "4"  END) AS paymentstatus';
      $conditionRaw= "event_id ='".$request->id."'";
      $dataEvent = $this->amountpaid->getraw($conditionRaw,$selectColumn);
      //dd($dataEventlist);
      if($dataEvent)
      {
        $result= array('title'=>$dataEvent->title,
                        'eventid'=>$dataEvent->event_id,
                        'user_id'=>$dataEvent->user_id,
                        'assignto'=>$dataEvent->assignto,
                        'orgname'=>$dataEvent->org_name,
                        'orgemail'=>$dataEvent->org_email,
                        'orgmobile'=>$dataEvent->org_mobile,
                        'eventtype'=>$dataEvent->event_type,
                        'startdate'=>$dataEvent->startdate,
                        'paymentstatus'=>$dataEvent->paymentstatus,
                        'enddate'=>$dataEvent->enddate,
                        'createdat'=>$dataEvent->event_create_on,
                        'totalamount'=>round($dataEvent->total_amount,2),
                        'amountpaid'=>round($dataEvent->amount_paid,2),
                        'adjustment'=>round($dataEvent->adjustment,2),
                        'paybalamount'=>round($dataEvent->amount_payable,2),
                        'totalquantity'=>$dataEvent->total_quantity,
                        'totaltransaction'=>$dataEvent->total_txn);
         return response()->json(['reportData' => $result]);
      }
      else
      {
          $result='error';
          return response()->json(['reportData' => $result,
                                   'pagination'=>'']);
      }
    }

    ////////////////// report for marketing///////////
    private function completeeventlist($request)
    {

      $result='';
      $alleventsArray = array();
      $eventIdArray='';
       ////////////assign userslist////////
      $assignArray = array();
      $checkAssign = $this->userassign->getallBy(array('status'=>1),array('admin_user_id','user_id'));
      if(count($checkAssign)>0)
      {
        foreach($checkAssign as $checkAssign)
        {
          $assignArray[$checkAssign->user_id][] = $checkAssign->admin_user_id;
        }
      }
      ////////////////all  userslist//////
      $alluserArray = array();
      $whereCondition = array('status'=>1);
      $userData = $this->usersInterface->getallBy($whereCondition,array('id','name','email','mobile'));
      foreach($userData as $userData)
      {
        $alluserArray[$userData->id] = array('name'=>$userData->name,
                                             'email'=>$userData->email,
                                             'mobile'=>$userData->mobile);
      }
      $conditionRaw= "user_id!=3";
      if($request->commonfeilds)
      {
        if(is_numeric($request->commonfeilds))
        {
          $conditionRaw.= " and event_id='".$request->commonfeilds."'";
        }
        else
        {
          $conditionRaw.= " and  user_id = (select id from users where email ='".$request->commonfeilds."')";
        }
      }
      if($request->eventstatus)
      {
        switch ($request->eventstatus) 
        {
          case 'published':
          $conditionRaw.= " and status=1";
          break;
          case 'paused':
          $conditionRaw.= " and status=0";
          break;
          case 'past':
          $conditionRaw.= ' and IF(end_date_time = "0000-00-00 00:00:00", CONVERT_TZ(start_date_time,"+00:00",timezone_value), CONVERT_TZ(end_date_time,"+00:00",timezone_value)) < "'.date('Y-m-d H:i:s').'"';
          break;
        }
         
      }
      if($request->priority>0)
      {
        if($request->priority==1)
        {
           $conditionRaw.= " and event_id in (select event_id from eventmarketing_histories group by event_id)";
        }
        else
        {
           $conditionRaw.= " and event_id not in (select event_id from eventmarketing_histories group by event_id)";
        }

      }
      if($request->activity>0)
      {
         $conditionRaw.= " and event_id in (select event_id from eventmarketing_histories where activity_id=".$request->activity." group by event_id)";
      }
      if($request->cityname!='')
      {
        $cityname = trim($request->cityname);
        if(strpos($request->cityname, ',')!==false)
        {
          $getcity = explode(',', $request->cityname);
          $cityname = trim($getcity[0]);
        }
         $conditionRaw.= " and city like '%".$cityname."%' or state like '%".$cityname."%' or country like '%".$cityname."%'";

      }
    
      if(isset($request->eventtype) && $request->eventtype>=0 &&  $request->eventtype!='')
      {
        $conditionRaw.= " and recurring_type='".$request->eventtype."'";
      }
     if(isset($request->dateselect) && $request->dateselect!='')
      {
            $getdates = explode(',',$request->dateselect.',');
            if($getdates[1]=='')
            {
              $getdates[1] = $getdates[0];
            }
        if($request->datecheckon=='create')
        {
          $conditionRaw.= " and date(CONVERT_TZ(created_at,'+00:00','+05:30')) between  '". $getdates[0]."' and '".$getdates[1]."'";

        }
        elseif($request->datecheckon=='starton')
        {
          $conditionRaw.= " and date(CONVERT_TZ(start_date_time,'+00:00','+05:30')) between  '". $getdates[0]."' and '".$$getdates[1]."'";

        }
        else
        {
          $conditionRaw.= " and date(CONVERT_TZ(created_at,'+00:00','+05:30')) between  '". $getdates[0]."' and '".$getdates[1]."'";
        }
        
      }
      $selectColumn ='event_id,user_id,title,city,min_ticket_price,max_ticket_price,CONVERT_TZ(start_date_time,"+00:00",timezone_value) as startdate,IF(end_date_time = "0000-00-00 00:00:00", CONVERT_TZ(start_date_time,"+00:00",timezone_value), CONVERT_TZ(end_date_time,"+00:00",timezone_value)) as enddate, recurring_type,created_at,id';
      $dataEventlist = $this->weightage->getallpaginate($conditionRaw,$selectColumn,'created_at');
      //dd($dataEventlist);
      if(count($dataEventlist)>0)
      {
         foreach($dataEventlist as $dataEvent)
         {
            $eventIdArray.=$dataEvent->event_id.',';
            $orgname='';
            $orgemail='';
            $orgmobile='';
            if(array_key_exists($dataEvent->user_id, $alluserArray))
            {
              $orgname=$alluserArray[$dataEvent->user_id]['name'];
              $orgemail=$alluserArray[$dataEvent->user_id]['email'];
              $orgmobile=$alluserArray[$dataEvent->user_id]['mobile'];
            }
            ///////////set assignto value///////////
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
            $result[$dataEvent->event_id] = array('title'=>$dataEvent->title,
                                                  'user_id'=>$dataEvent->user_id,
                                                  'assignto'=>$assignto,
                                                  'orgname'=>$orgname,
                                                  'orgemail'=>$orgemail,
                                                  'orgmobile'=>$orgmobile,
                                                  'eventtype'=>$dataEvent->recurring_type,
                                                  'startdate'=>$dataEvent->startdate,
                                                  'city'=>$dataEvent->city,
                                                  'activitycounts'=>'',
                                                  'enddate'=>$dataEvent->enddate,
                                                  'createdat'=>date('Y-m-d H:i:s',strtotime($dataEvent->created_at)),
                                                  'totalamount'=>0,
                                                  'totaltransaction'=>0,
                                                  'minprice'=>$dataEvent->min_ticket_price,
                                                  'maxprice'=>$dataEvent->max_ticket_price);

         }
        /////////// all active activity//////
        $allmktactivityArray=array();
        $activitynameArray = array();
        $marketingactivitylist = $this->marketinglist->getallBy(array('status'=>1));
        foreach($marketingactivitylist as $listArray)
        {
          $activitynameArray[$listArray->id] =  $listArray->activity_name;
        }
         /////////////get event based activity count////
         $selectRow = "event_id,activity_id,count(refrence_link) as totalcount";
         $condition= "event_id in ".'('.substr($eventIdArray,0,-1).')'."";
         $getalleventmkt = $this->eventmarket->getallByRaw($condition,$selectRow,array('event_id','activity_id'));
         foreach($getalleventmkt as $getalleventmkt)
         {
            $allmktactivityArray[$getalleventmkt->event_id][$getalleventmkt->activity_id]=$getalleventmkt->totalcount;
            
         }
         //dd($allmktactivityArray);
         foreach($activitynameArray as $actkey=>$actval)
         {
           foreach($result as $eventid=>$eventvalues)
           {
             if(array_key_exists($eventid, $allmktactivityArray))
              {
                 $keyList = array_keys($allmktactivityArray[$eventid]);
                 // dd($keyList);
                if(in_array($actkey, $keyList))
                {
                   // dd($valact[$actkey]);
                  $result[$eventid]['activitycounts'][$actval]=$allmktactivityArray[$eventid][$actkey];
                }
                else
                {
                  // dd($actkey);
                  $result[$eventid]['activitycounts'][$actval]=0;
                }
              }
              else
              {
                $result[$eventid]['activitycounts'][$actval]=0;
              }
           }
         }
          ///////////// pagination data///////////////////
         $selectRawColumns = 'event_id,count(order_id) as totaltrns,sum(total_amount) as totalamount';
         $conditionRaw = "event_id in ".'('.substr($eventIdArray,0,-1).')'." and order_status='completed'";
         $bookingdetails = $this->bookingDetails->getrawList($conditionRaw,$selectRawColumns);
         if(count($bookingdetails)>0)
         {
          foreach($bookingdetails as $bookingData)
          {
            if($bookingData->event_id!='')
            {
              $result[$bookingData->event_id]['totaltransaction']=$bookingData->totaltrns;
              $result[$bookingData->event_id]['totalamount']=$bookingData->totalamount;

            }
            

          }
         }
         $urlPage = $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
         if(isset($request->withoutpage))
         {
          $pagination = array();
         }
         else
         {
            $pagination =  array('total' => $dataEventlist->total(),
                          'per_page'     => $dataEventlist->perPage(),
                          'current_page' => $dataEventlist->currentPage(),
                          'last_page'    => $dataEventlist->lastPage(),
                          'nextpage'     => $dataEventlist->nextPageUrl(),
                          'prevpage'     => $dataEventlist->previousPageUrl(),
                          'refrlUrl'     => $urlPage,
                          'REQUEST_URI'=>$_SERVER['REQUEST_URI']
                          ); 
         }
         return response()->json(['reportData' => $result,
                                  'pagination'=>$pagination]);
      }
      else
      {
          $result='error';
          return response()->json(['reportData' => $result,
                                   'pagination'=>'']);
      }
    }
    public function eventactivityreport(Request $request)
    {

      $result='';
      $alleventsArray = array();
       ////////////assign userslist////////
      $assignArray = array();
      $checkAssign = $this->userassign->getallBy(array('status'=>1),array('admin_user_id','user_id'));
      if(count($checkAssign)>0)
      {
        foreach($checkAssign as $checkAssign)
        {
          $assignArray[$checkAssign->user_id][] = $checkAssign->admin_user_id;
        }
      }
      ////////////////all  userslist//////
      $alluserArray = array();
      $whereCondition = array('status'=>1);
      $userData = $this->usersInterface->getallBy($whereCondition,array('id','name','email','mobile'));
      foreach($userData as $userData)
      {
        $alluserArray[$userData->id] = array('name'=>$userData->name,
                                             'email'=>$userData->email,
                                             'mobile'=>$userData->mobile);
      }
      $conditionRaw= "event_id ='".$request->id."'";
      $selectColumn ='event_id,user_id,title,city,min_ticket_price,max_ticket_price,CONVERT_TZ(start_date_time,"+00:00",timezone_value) as startdate,IF(end_date_time = "0000-00-00 00:00:00", CONVERT_TZ(start_date_time,"+00:00",timezone_value), CONVERT_TZ(end_date_time,"+00:00",timezone_value)) as enddate, recurring_type,created_at,id';
      $dataEvent = $this->weightage->getraw($conditionRaw,$selectColumn,'created_at');
      //dd($dataEventlist);
      if($dataEvent)
      {
             $orgname='';
            $orgemail='';
            $orgmobile='';
          if(array_key_exists($dataEvent->user_id, $alluserArray))
          {
            $orgname=$alluserArray[$dataEvent->user_id]['name'];
            $orgemail=$alluserArray[$dataEvent->user_id]['email'];
            $orgmobile=$alluserArray[$dataEvent->user_id]['mobile'];
          }
          ///////////set assignto value///////////
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
        $result= array('title'=>$dataEvent->title,
                       'user_id'=>$dataEvent->user_id,
                       'assignto'=>$assignto,
                       'orgname'=>$orgname,
                       'orgemail'=>$orgemail,
                       'orgmobile'=>$orgmobile,
                       'eventtype'=>$dataEvent->recurring_type,
                       'startdate'=>$dataEvent->startdate,
                       'city'=>$dataEvent->city,
                       'activitycounts'=>'',
                       'enddate'=>$dataEvent->enddate,
                       'createdat'=>date('Y-m-d H:i:s',strtotime($dataEvent->created_at)),
                       'totalamount'=>0,
                       'totaltransaction'=>0,
                       'minprice'=>$dataEvent->min_ticket_price,
                       'maxprice'=>$dataEvent->max_ticket_price);

          $dataalleventmkt=array();
          $activitynameArray = array();
          $condition = array('status'=>1,'type'=>1);
          if($request->pagename=='updatecreative' || $request->pagename=='allcretives')
          {
             $condition = array('status'=>1,'type'=>2);
          }
          $marketingactivitylist = $this->marketinglist->getallBy($condition);
          foreach($marketingactivitylist as $listArray)
          {
            $activitynameArray[$listArray->id] =  $listArray->activity_name;
          }

          /////////////get event based activity count////
          $allmktactivityArray=array();
         $selectRow = "event_id,activity_id,count(refrence_link) as totalcount";
         $condition= "event_id = ".$request->id."";
         $getalleventmkt = $this->eventmarket->getallByRaw($condition,$selectRow,array('event_id','activity_id'));
         foreach($getalleventmkt as $getalleventmkt)
         {
            $allmktactivityArray[$getalleventmkt->event_id][$getalleventmkt->activity_id]=$getalleventmkt->totalcount;
            
         }
         //dd($allmktactivityArray);
         foreach($activitynameArray as $actkey=>$actval)
         {
            if(array_key_exists($request->id, $allmktactivityArray))
            {
               $keyList = array_keys($allmktactivityArray[$request->id]);
               // dd($keyList);
                if(in_array($actkey, $keyList))
                {
                   // dd($valact[$actkey]);
                  $result['activitycounts'][$actval]=$allmktactivityArray[$request->id][$actkey];
                }
                else
                {
                  // dd($actkey);
                  $result['activitycounts'][$actval]=0;
                }
            }
            else
            {
              $result['activitycounts'][$actval]=0;
            }
           
         }
         ////////////get booking data////////
         $selectRawColumns = 'count(order_id) as totaltrns,sum(total_amount) as totalamount';
         $conditionRaw = 'event_id="'.$request->id.'" and order_status="completed"';
         $bookingdetails = $this->bookingDetails->getByraw($conditionRaw,$selectRawColumns);
         if($bookingdetails)
         {
            $result['totaltransaction']=$bookingdetails->totaltrns;
            $result['totalamount']=$bookingdetails->totalamount;
         }
          if($request->pagename=='' || $request->pagename=='allcretives')
          {
            $counter=1;
            /////////// activity list//////////////
            $condition = array('event_id'=>$request->id,'type'=>1);
            if($request->pagename=='allcretives')
            {
               $condition = array('event_id'=>$request->id,'type'=>2);

            }
            $getalleventmkt = $this->eventmarket->getallBy($condition);
            if(count($getalleventmkt)>0)
            {
              foreach($getalleventmkt as $getalleventmkt)
              {
                $dataalleventmkt[$counter]['id']=$getalleventmkt->id;
                $dataalleventmkt[$counter]['refrence_link']=$getalleventmkt->refrence_link;
                $dataalleventmkt[$counter]['user_id']=$getalleventmkt->created_by;
                $dataalleventmkt[$counter]['comments']=$getalleventmkt->comments;
                $dataalleventmkt[$counter]['activity_name']='';
                $dataalleventmkt[$counter]['created_by']='';
                $dataalleventmkt[$counter]['created_at']=date('Y-m-d H:i:s',strtotime($getalleventmkt->created_at));
                if(array_key_exists($getalleventmkt->created_by, $alluserArray))
                {
                  $dataalleventmkt[$counter]['created_by']=$alluserArray[$getalleventmkt->created_by]['name'];
                }
                if(array_key_exists($getalleventmkt->activity_id, $activitynameArray))
                {
                   $dataalleventmkt[$counter]['activity_name']=$activitynameArray[$getalleventmkt->activity_id];
                }
                  $counter++;
              }

            }
          }
          $activitytopUpdate='';
          if($request->activityid!='')
          {
            $checkActivity = $this->eventmarket->getBy(array('id'=>$request->activityid));
            if($checkActivity)
            {
              $userData = Auth::user();
              if($checkActivity->created_by==$userData->id)
              {
                 $activitytopUpdate = $checkActivity;
              }
              else
              {
                $activitytopUpdate='notallow';
              }
            }
            else
            {
              $activitytopUpdate='notfound';
            }
          }
          
         return response()->json(['reportData' => $result,
                                  'eventmarketingdata'=>$dataalleventmkt,
                                  'marketingactivitylist'=>$marketingactivitylist,
                                  'activitytopUpdate'=>$activitytopUpdate]);
      }
      else
      {
          $result='error';
          return response()->json(['reportData' => $result,
                                   'pagination'=>'']);
      }
    }
}
