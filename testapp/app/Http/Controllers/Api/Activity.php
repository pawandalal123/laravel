<?php 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\model\Common;
use Appfiles\Common\Functions;
use Appfiles\Repo\MediadetailInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class Activity extends Controller 
{
protected $functions;
  public function  __construct(Functions $functions)
  {
    $this->functions=$functions;
  }

  public function get(Request $request)
  {
    // dd('in here');
$page=    $request->input('page');
// dd($page);
$limit=100;     //Setting limit 100
//$take=$page*$limit;   
$skip=($page-1)*$limit;

$array=array();

      // $data=array('name'=>'value','snippet'=>"snippet value is here");

    $mongoConnection=DB::connection('mongodb');

    if($request->input('type'))
{
  $value=$request->input('value');
  $type=$request->input('type');
  if($type=='user_id')
    $value=(int)$value;

  if($type=='date')
  {
    $fromdate=$request->input('datefrom');
    $todate=$request->input('dateto');

    // echo "in here ";
$from = new \MongoDate(strtotime($fromdate.' 00:00:00'));
$to = new \MongoDate(strtotime($todate.' 23:59:59'));
// echo "$d\n";
  $data=$mongoConnection->collection('activity')->where('created_at','>',$from)->where('created_at','<',$to)->orderBy('created_at','desc')->skip($skip)->take($limit)->get();
  // dd($data);
  }else
    if($type=='custom')
  {
 $dateArray=   $this->getCustomDates($request->input('value'));
 $from = new \MongoDate(strtotime($dateArray['fromdate']));
$to = new \MongoDate(strtotime($dateArray['todate']));

   $data=$mongoConnection->collection('activity')->where('created_at','>',$from)->where('created_at','<',$to)->orderBy('created_at','desc')->skip($skip)->take($limit)->get();

  }else {
  $array=array($type=>$value);
  $data=$mongoConnection->collection('activity')->where($array)->orderBy('created_at','desc')->skip($skip)->take($limit)->get();
}
}
else
  $data=$mongoConnection->collection('activity')->orderBy('created_at','desc')->skip($skip)->take($limit)->get();

    array_walk($data,function(&$value,$key){

      $value['created_at']=date('H:i:s d-m-Y ',$value['created_at']->sec);
      $value['browser']=$this->functions->getBrowser($value['user_agent']);
      $value['os']=$this->functions->getOS($value['user_agent']);

    });
    // dd($d);
    return $data;
          // return response()->json(['data' => $data ]);

          
  }

  function getCustomDates($custom){
    $commonObj = new Common();
    switch ($custom) {
      case 'today':
        $todaydate=date('Y-m-d');
        $dateValue=$commonObj->ConvertGMTToLocalTimezone($todaydate,'Asia/Kolkata');
        $date=explode(' ',$dateValue)[0];
        return array('fromdate'=>$date.' 00:00:00','todate'=>$date.' 23:59:59');
        break;

        case 'yesterday':
        $yesterday=date('Y-m-d', strtotime('-1 days'));
        $dateValue=$commonObj->ConvertGMTToLocalTimezone($yesterday,'Asia/Kolkata');
        $date=explode(' ',$dateValue)[0];
        return array('fromdate'=>$date.' 00:00:00','todate'=>$date.' 23:59:59');
        break; 

        case 'last7day':
        $last7day=date('Y-m-d', strtotime('-7 days'));
        $todaydate=date('Y-m-d');
        $dateValue=$commonObj->ConvertGMTToLocalTimezone($last7day,'Asia/Kolkata');
        $toDateValue=$commonObj->ConvertGMTToLocalTimezone($todaydate,'Asia/Kolkata');
        $date=explode(' ',$dateValue)[0];
        $todate=explode(' ',$toDateValue)[0];
        return array('fromdate'=>$date.' 00:00:00','todate'=>$todate.' 23:59:59');
        break; 

        case 'lastmonth':
        $firstdate=date('Y-m-d', strtotime('first day of last month'));
        $lastdate=date('Y-m-d', strtotime('last day of last month'));
        $dateValue=$commonObj->ConvertGMTToLocalTimezone($firstdate,'Asia/Kolkata');
        $toDateValue=$commonObj->ConvertGMTToLocalTimezone($lastdate,'Asia/Kolkata');
        $date=explode(' ',$dateValue)[0];
        $todate=explode(' ',$toDateValue)[0];
        return array('fromdate'=>$date.' 00:00:00','todate'=>$todate.' 23:59:59');
        break; 

        case 'thismonth':
        $firstdate=date('Y-m-d', strtotime('first day of this month'));
        $lastdate=date('Y-m-d', strtotime('last day of this month'));
        $dateValue=$commonObj->ConvertGMTToLocalTimezone($firstdate,'Asia/Kolkata');
        $toDateValue=$commonObj->ConvertGMTToLocalTimezone($lastdate,'Asia/Kolkata');
        $date=explode(' ',$dateValue)[0];
        $todate=explode(' ',$toDateValue)[0];
        return array('fromdate'=>$date.' 00:00:00','todate'=>$todate.' 23:59:59');
        break;  
      

    }

  }


}
