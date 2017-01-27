<?php
namespace App\Http\Controllers\Admin; 
use Auth;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\AccountInterface;
use Appfiles\Repo\InvoicepdfInterface;
use Appfiles\Repo\OrderbreakageInterface;
use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\BookingdetailsInterface;
use Appfiles\Repo\AdminfeatureInterface;
use Appfiles\Repo\UserassignInterface;
use Appfiles\Repo\FeaturesInterface;
use App\Model\Common;
use Appfiles\Repo\UserdetailInterface;
use App\Model\Orderbreakage;
use Redirect;
use Validator;
use DB;
use Mail;
class AccountController extends Controller {
 
  public function  __construct(TicketInterface $ticketInterface,OrderbreakageInterface $OrderbreakageInterface,InvoicepdfInterface $invoicepdfInterface,AccountInterface $accountInterface,UsersInterface $usersInterface,BookingdetailsInterface $bookingdetailsInterface,
    UserdetailInterface $userdetailInterface,FeaturesInterface $featuresassign,AdminfeatureInterface $adminfeature,EventInterface $eventinterface ,UserassignInterface $userassign)
    {
        //$this->user_id= 1;        
        $this->usersInterface = $usersInterface;
        $this->accountInterface = $accountInterface;
        $this->userdetailInterface = $userdetailInterface; 
        $this->eventinterface = $eventinterface;
        $this->bookingdetailsInterface = $bookingdetailsInterface;
        $this->adminfeature =$adminfeature;
        $this->userassign =$userassign;
        $this->featuresassign=$featuresassign;
        $this->invoicepdf=$invoicepdfInterface;
        $this->OrderbreakageInterface = $OrderbreakageInterface;
        $this->ticketInterface = $ticketInterface;
    }
  
  public function getinvoice(Request $request,$id=null)
  {
      $invoice =null;
      $user= null;
      $postData = $request->all();       
      if(!empty($postData)) {

          $whereCondition = array('event_id'=>$postData['event_id']);
          $invoice = $this->invoicepdf->getallBy($whereCondition); 

          $whereCondition = array('id'=>$postData['event_id']);
          $event = $this->eventinterface->getBy($whereCondition); 

          $whereCondition = array('id'=>@$event->user_id);
          $user = $this->usersInterface->getBy($whereCondition);
          return \View::make('admin.getinvoice')->with('invoice',$invoice)->with('user',$user);
      } elseif(!empty($id)){

          $whereCondition = array('event_id'=>$id);
          $invoice = $this->invoicepdf->getallBy($whereCondition); 

          $whereCondition = array('id'=>$id);
          $event = $this->eventinterface->getBy($whereCondition); 

          $whereCondition = array('id'=>$event->user_id);
          $user = $this->usersInterface->getBy($whereCondition);
      }

      return \View::make('admin.getinvoice')->with('invoice',$invoice)->with('user',$user); 
   
  }
  
  public function generateinvoice(Request $request)
  {
        $postData = $request->all(); 
        if(!empty($postData['invoice'])){
          $data = explode("_",$postData['invoice']);

          $dataSet = array();
          $dataSet["invoice"] = $data['0'].".pdf";
          $dataSet["event_id"] = $data['1'];
          $dataSet["status"] = 1;
          $this->invoicepdf->create($dataSet);
          
          $whereCondition = array('event_id'=>$data['1']);
          $invoice = $this->invoicepdf->getallBy($whereCondition); 
          
          $whereCondition = array('id'=>$data['1']);
          $event = $this->eventinterface->getBy($whereCondition); 
          
          $whereCondition = array('id'=>$event->user_id);
          $user = $this->usersInterface->getBy($whereCondition);
          return redirect('admin/getinvoice/'.$data['1']);
        }    
          
  }

  public function postinvoice(Request $request)
  {
    $postData = $request->all();
   
    $whereCondition = array('id'=>$postData['eventid']);
    $eventData = $this->eventinterface->getBy($whereCondition);
    $finalArray = array();
    if(empty($eventData)){
       Session::flash('message', 'This event id does not exists.'); 
                Session::flash('alert-class', 'error'); 
                Session::flash('alert-title', 'Error'); 
      return back();
    }
        
      //  $data['invoice'] =  DB::table('bookingdetails')
      // ->selectRaw( 'bookingdetails.event_id,bookingdetails.event_id, sum(orderbreakages.amount) as totalamount,sum(orderbreakages.quantity) as totalquantity,orderbreakages.id,orderbreakages.order_id,orderbreakages.ticket_id' )
      // ->where('bookingdetails.event_id',$postData['eventid'])->where('bookingdetails.order_status','completed')
      // ->rightJoin('orderbreakages', 'bookingdetails.order_id', '=', 'orderbreakages.order_id')
      // ->groupBy( 'orderbreakages.ticket_id' )
      // ->get();     
      // $whereCondition = array('event_id'=>$eventData->id);
      // $bookingData = $this->bookingdetailsInterface->getList($whereCondition,'order_id','order_status');

      $whereCondition = array('event_id'=>$eventData->id);
      $ticketNameData = $this->ticketInterface->getListTrusted($whereCondition,'name','id')->toArray();

      $whereCondition = array('event_id'=>$eventData->id);
      $ticketPriceData = $this->ticketInterface->getListTrusted($whereCondition,'price','id');
      
      $orderbreakge =  DB::table('orderbreakages')
          ->select('orderbreakages.ticket_id','orderbreakages.quantity','orderbreakages.amount','orderbreakages.discount','orderbreakages.coupon_code','bookingdetails.order_status')
          ->leftJoin('bookingdetails','bookingdetails.order_id','=','orderbreakages.order_id')    
          ->where('bookingdetails.event_id',$eventData->id)      
         ->get();
     
      if(count($orderbreakge) >0)
      foreach($orderbreakge as $orderbreakges){
        
        if($orderbreakges->order_status=="completed"){
          
           $finalArray['Complete'][$orderbreakges->ticket_id]['TicketId'] = $orderbreakges->ticket_id;
           $finalArray['Complete'][$orderbreakges->ticket_id]['TicketName'] = $ticketNameData[$orderbreakges->ticket_id];
           $finalArray['Complete'][$orderbreakges->ticket_id]['TicketPrice'] = $ticketPriceData[$orderbreakges->ticket_id];
          // $finalArray['Complete'][$orderbreakges->ticket_id]['OrderNumber'] = $orderbreakges->order_id; 

           if(!isset($finalArray['Complete'][$orderbreakges->ticket_id]['TotalAmount'])){
                $finalArray['Complete'][$orderbreakges->ticket_id]['TotalAmount'] = (($orderbreakges->amount * $orderbreakges->quantity)-$orderbreakges->discount);
            } else {          
                $finalArray['Complete'][$orderbreakges->ticket_id]['TotalAmount'] += (($orderbreakges->amount * $orderbreakges->quantity)-$orderbreakges->discount);
            }  
           
            if(!isset($finalArray['Complete'][$orderbreakges->ticket_id]['TotalQuantity'])){
                $finalArray['Complete'][$orderbreakges->ticket_id]['TotalQuantity'] = $orderbreakges->quantity;
            } else { 
                $finalArray['Complete'][$orderbreakges->ticket_id]['TotalQuantity'] += $orderbreakges->quantity;
            }

            if(!isset($finalArray['Complete'][$orderbreakges->ticket_id]['Discount'])){
                $finalArray['Complete'][$orderbreakges->ticket_id]['Discount'] = $orderbreakges->discount;
            } else { 
               $finalArray['Complete'][$orderbreakges->ticket_id]['Discount'] += $orderbreakges->discount;     
            }
           
        } else {
           
           $finalArray['Pending'][$orderbreakges->ticket_id]['TicketId'] = $orderbreakges->ticket_id;
           $finalArray['Pending'][$orderbreakges->ticket_id]['TicketName'] = $ticketNameData[$orderbreakges->ticket_id];
           $finalArray['Pending'][$orderbreakges->ticket_id]['TicketPrice'] = $ticketPriceData[$orderbreakges->ticket_id]; 
           //$finalArray['Pending'][$orderbreakges->ticket_id]['OrderNumber'] = $orderbreakges->order_id; 

           if(!isset($finalArray['Pending'][$orderbreakges->ticket_id]['TotalAmount'])){
                $finalArray['Pending'][$orderbreakges->ticket_id]['TotalAmount'] = $orderbreakges->amount;
            } else {          
                $finalArray['Pending'][$orderbreakges->ticket_id]['TotalAmount'] += $orderbreakges->amount;
            }  

            if(!isset($finalArray['Pending'][$orderbreakges->ticket_id]['TotalQuantity'])){
                $finalArray['Pending'][$orderbreakges->ticket_id]['TotalQuantity'] = $orderbreakges->quantity;
            } else { 
                $finalArray['Pending'][$orderbreakges->ticket_id]['TotalQuantity'] += $orderbreakges->quantity;
            }

            if(!isset($finalArray['Pending'][$orderbreakges->ticket_id]['Discount'])){
                $finalArray['Pending'][$orderbreakges->ticket_id]['Discount'] = $orderbreakges->discount;
            } else { 
               $finalArray['Pending'][$orderbreakges->ticket_id]['Discount'] += $orderbreakges->discount;
            }
           
        }        
       
      }
       
       $finalArray['éventdtail']= $eventData;
       $finalArray['postdata'] = $postData;
     
      if (!file_exists('uplode/'.$eventData->id)) {
        mkdir('uplode/'.$finalArray['éventdtail']->id, 0777, true);
      }
      $eventdata = explode("-",$finalArray["éventdtail"]->start_date_time);
      $eventID = str_pad($finalArray["éventdtail"]->id, 6, '0', STR_PAD_LEFT);

      $whereCondition = array('event_id'=>$finalArray["éventdtail"]->id);
      $invoice = $this->invoicepdf->getallBy($whereCondition);      
      $num = count($invoice)+1;

      $invoiceNo = str_pad($num, 3, '0', STR_PAD_LEFT);

      $pdfName = $eventdata[0]."GE".$eventID.$invoiceNo;
      $finalArray['invoice_num'] = $pdfName;
      $common =  new Common();
     
      $common->genratepdfsavefolder("pdf.postinvoice",$finalArray,'uplode/'.$finalArray['éventdtail']->id.'/'.$pdfName.'.pdf');
      $flag = 1;
      
      return \View::make('pdf.postinvoice')->with('data',$finalArray)->with('flag',$flag);
     // exit;
    //  return redirect('admin/getinvoice/'.$postData['event_id']);
     //return response()->download("pdf.", "Ahmed Badawy - postinvoice.pdf");
  }

   public function calculates($data){

     if(!empty($data))
        foreach($data as $datas){
           
           if($datas->id == $id){
              return $datas;
           }
        }

        return null;

   }
   
   public function arraygetsearch($data,$id){

     if(!empty($data))
        foreach($data as $datas){
           
           if($datas->id == $id){
              return $datas;
           }
        }

        return null;

   }
   public function sendinvoice(Request $request){
         
         $postData = $request->all();
        
         $whereCondition = array('id'=>$postData['id']);
         $invoice = $this->invoicepdf->getBy($whereCondition);        
             
         $usermessage = null;
         $data = array();
         $data['invoice']=$invoice->invoice;
         $data['event_id']=$invoice->event_id;
         $data['email']=$postData['email']; 
         Mail::queue('emails.invoice',array('user_message'=>$usermessage), function($message) use ($data) {
              try {
                
                    $file =  'uplode/'.$data["event_id"].'/'.$data["invoice"];

                    $message->attach($file, array('as' => $data["invoice"], 'mime' => 'application/pdf'));

                    $message->to($data['email'])->cc('support@goeventz.com')->subject('invoice_'.$data["invoice"]);

                    
                  } catch (Exception $e) {

               }
        });

        $invoiceData = array();
        $invoiceData['invoice_sent'] = 1;
        $this->invoicepdf->update($invoiceData,$postData['id'],"id");
    }
}