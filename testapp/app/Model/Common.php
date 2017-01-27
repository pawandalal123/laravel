<?php

namespace App\model;
use DB;
use Cookie;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Appfiles\Common\Functions;
use Mail;
use PDF;
use App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Model\Mail_settings;
use App\Model\View;
use App\Model\CustomPayment;
use URL;

class Common extends Model
{
    //
   public function __construct() 
   {   
        
   
      
     }

    public function setcity_ipaddress($ipaddress)
    {
       $city = $this->iplocation($ipaddress);
       $setcokkies = $this->setcokkies('usercity',$city);
       return $city;

    }

     public function setcokkies($name, $value, $minutes = false)
     {
        $cookie='';
        $getcokkies = $this->getcokkies($name);
        if($getcokkies!=false)
        {
            $forget = $this->deletecokkies($name);
            $setcookie =Cookie::queue($name, $value, $minutes);
            $cookie=$value;
            //print_r($cookie);
        }
        else
        {
            $setcookie =Cookie::queue($name, $value, $minutes);
            $cookie=$value;
        }
       // print_r($cookie);exit;
        return $cookie;
     }

     public function getcokkies($cookiename , $option = false)
     {
      
        $cookieValue = Cookie::get($cookiename);
        return $cookieValue;
     }
     public function deletecokkies($cookiesname)
     {
        $cookieValue = Cookie::forget($cookiesname);
        //print_r($cookieValue);
        //$cookieVale = Cookie::get($cookiesname);
        //print_r($cookieVale);
         return $cookieValue;
     }
     /* remove special charcter for string*/

    public function cleanURL($string) 
    {
     $string = str_replace(' ', '-', trim($string)); // Replaces all spaces with hyphens.
     $string = preg_replace('/[^A-Za-z0-9\-\.]/', '', $string); // Removes special chars.

      return preg_replace('/-+/', '-', strtolower($string)); // Replaces multiple hyphens with single one.
    }


      /* calculate distnace bases of latitude and langitude*/
    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {

      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);
      if ($unit == "K") 
      {
        return ($miles * 1.609344);
      } 
      else if ($unit == "N") 
      {
          return ($miles * 0.8684);
      } 
      else
      {
         return $miles;
      }
    }

      public function googleMapLink($getAddress, $getCity,$getState)
      {
        $destinationAddy = '&daddr='.urlencode($getAddress.' '.$getCity.' '.$getState);
        $startfrom = '&saddr=';
        return htmlentities("http://maps.google.com/maps?f=q&amp;hl=en&amp;{$startfrom}{$destinationAddy}");
      }

    public function iplocation($ip)
    {
      //$ip = $_SERVER['REMOTE_ADDR'];
     // $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
      //print_r($details);
      //return $details->city;
       return 'India';
    }


    
  public function ConvertGMTToLocalTimezone($gmttime,$timezoneRequired)
  {
    $system_timezone = date_default_timezone_get();

    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");

    $local_timezone = $timezoneRequired;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");

    date_default_timezone_set($system_timezone);
    $diff = (strtotime($local) - strtotime($gmt));

    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("Y-m-d H:i");
    return $timestamp;
  }

  public function ConvertLocalTimezoneToGMT($gmttime,$timezoneRequired)
  {
    $system_timezone = date_default_timezone_get();
   
    $local_timezone = $timezoneRequired;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");
   
    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");
   
    date_default_timezone_set($system_timezone);
    $diff = (strtotime($gmt) - strtotime($local));
   
    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("Y-m-d H:i");
    return $timestamp;
  }

  public function urlwithoutoperator($string) 
  {
   $string = str_replace('', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
  }

    public function sendmail($mailmessage,$dataArray)
    {
            Mail::send('emails.welcome',array('user_message' => $mailmessage), function($message) use ($dataArray)
                {
                $message->from('noreply@goeventz.com','GoEventz');
                $message->to($dataArray['to'], $dataArray['name'])->bcc('support@goeventz.com','GoEventz')->subject($dataArray['subject']);
                if(isset($dataArray['file']) && count($dataArray['file'])>0 && $dataArray['file']!='')
                {
                  foreach($dataArray['file'] as $key=>$val)
                  {
                   $exp=explode('.', $key);
                   $extension=end($exp);
                   if($extension=='pdf')
                    {
                       $pos = strpos($val,$_ENV['CF_LINK']);
                        if ($pos !== false)
                        {
                          $message->attach($val);
                        }
                        else
                        {
                          $message->attachData($val, $key);
                        }

                    }
                    else
                    {
                       $message->attach($val);

                    }
                  }
                }
                });
    }
    public function sendmailpending($mailmessage,$dataArray)
    {
            Mail::send('emails.welcome',array('user_message' => $mailmessage), function($message) use ($dataArray)
                {
                $message->from('support@goeventz.com','Goeventz');
                $message->to($dataArray['to'], $dataArray['name'])->bcc($dataArray['bcc'],'Goeventz')->subject($dataArray['subject']);
                if(isset($dataArray['file']) && count($dataArray['file'])>0 && $dataArray['file']!='')
                {
                  foreach($dataArray['file'] as $key=>$val)
                  {
                   $exp=explode('.', $key);
                   $extension=end($exp);
                   if($extension=='pdf')
                    {
                       $pos = strpos($val,$_ENV['CF_LINK']);
                        if ($pos !== false)
                        {
                          $message->attach($val);
                        }
                        else
                        {
                          $message->attachData($val, $key);
                        }

                    }
                    else
                    {
                       $message->attach($val);

                    }
                  }
                }
                });
    }
 




    

    public function genratepdf($filename,$data)
    {
       $dataArray = extract($data);
       $pdf = PDF::loadView($filename,compact('data'));
       return $pdf->output();
    }
    public function genratepdfdownload($filename,$data)
    {
       $dataArray = extract($data);
       $pdf = PDF::loadView($filename,compact('data'));
       return $pdf->download('invoice.pdf');
    }

    public function genratepdfsavefolder($filename,$data,$path)
    {
      
       $dataArray = extract($data);
       // $pdf = PDF::loadView($filename,compact('data'));      
       $pdf = PDF::loadView( $filename, compact('data'))->save( $path ); 
       // return $pdf->output();
    }
    
    
    function getFacesharecount($url)
    {
        $shares=0;
          $rest_url = "http://api.facebook.com/restserver.php?format=json&method=links.getStats&urls=".urlencode($url);
          $json = json_decode(file_get_contents($rest_url),true);
          if($json)
          {
            $shares = $json[0]['share_count'];
          }
        return $shares;
    } 
    function gettwittersharecount($url)
    {
        $shares=0;
        $source_url = $url;
        $rest_url = "https://api.twitter.com/1.1/search/tweets.json?url=".urlencode($source_url);
        $json = json_decode(file_get_contents($rest_url),true);
        if($json)
        {
          $shares = $json['count'];
        }
        return $shares;
    } 
    function getpinterestsharecount($url)
    {
        $shares=0;
        $json = file_get_contents( "http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=".$url);
        $json = substr( $json, 13, -1);
        if($json)
        {
          $ajsn = json_decode($json, true);
          $shares = $ajsn['count'];
        }
        return $shares;
    } 

    public function getPlus1($url)
    {
      $shares=0;
     
      $html =  file_get_contents( "https://plusone.google.com/_/+1/fastbutton?url=".urlencode($url));
      $doc = new \DOMDocument(); 
      libxml_use_internal_errors(true);
      $doc->loadHTML($html);
      libxml_clear_errors(); 
      $counter=$doc->getElementById('aggregateCount');
      if($counter)
      {
         $shares = $counter->nodeValue;
      }
      return $shares;
    }
    public function gettotalsharecounter($url)
    {
       $fbcount = $this->getFacesharecount($url);
       $twittercount =0;
       $pintrest = $this->getpinterestsharecount($url);
       $getPlus1 = $this->getPlus1($url);
       return $fbcount+$twittercount+$pintrest+$getPlus1;
       //return 0;
    }

    function returnBetweenDates( $startDate, $endDate )
    {
        $startStamp = strtotime( $startDate );
        $endStamp   = strtotime( $endDate );
        if( $endStamp > $startStamp )
        {
          for($i = $startStamp; $i <= $endStamp; $i = strtotime('+1 day', $i))
            {
                $dateArr[] = date('Y-m-d', $i);
            }
            return $dateArr;    
        }
        else
        {

             $dateArr[]=date('Y-m-d', $startStamp);
             return $dateArr;

        }
    }
    function returndaysBetweenDates( $startDate, $endDate , $daysArray)
    {
         //$daysArray = array('Monday','Thursday','Friday');
         $startDate = strtotime($startDate);
         $endDate = strtotime($endDate);
         $dateArr=array();
          if( $endDate >= $startDate )
          {
            foreach($daysArray as $daysArray)
            {
              for($i = strtotime($daysArray, $startDate); $i <= $endDate; $i = strtotime('+1 week', $i))
              {
                 $dateArr[] = date('Y-m-d', $i);
              }
            }
            return $dateArr;    
         }
    }

    function returndatesBetweenDates( $startDate, $endDate , $daysArray)
    {
         //$daysArray = array('Monday','Thursday','Friday');
         $startDate = strtotime($startDate);
         $endDate = strtotime($endDate);
         $dateArr=array();
          if( $endDate >= $startDate )
          {
              for($i = $startDate; $i <= $endDate; $i = strtotime('+1 day', $i))
              {
                if(in_array(date('d', $i),$daysArray))
                {
                 $dateArr[] = date('Y-m-d', $i);
                }
              }
            return $dateArr;    
         }
    }


     

     public function sendsms($numbers,$message)
     {
        $username = "kumar.deepam@goeventz.com";
        $hash = "08842f3c347a0043f09b0042e5a309319a0910f7";
                        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "0";


                        // Data for text message. This is the text message data.
        $sender = "GOEVNZ"; 
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
         curl_close($ch);
         return $result;
         
     }

     public function removefromurl($url,$toRemove)
     {
       $parsed = [];
       parse_str(substr($url, strpos($url, '?') + 1), $parsed);
       $removed = $parsed[$toRemove];
       unset($parsed[$toRemove]);
       $url = '';
       if(!empty($parsed))
       {
          $url=  http_build_query($parsed);
       }
       return $url;
     }

     public function getLatLong($address)
     {
      if(!empty($address))
      {
          //Formatted address
          $formattedAddr = str_replace(' ','+',$address);
          //Send request and receive json data by address
          $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false'); 
          $output = json_decode($geocodeFromAddr);
          //Get latitude and longitute from json data
          $data['latitude']  = $output->results[0]->geometry->location->lat; 
          $data['longitude'] = $output->results[0]->geometry->location->lng;
          //Return latitude and longitude of the given address
          if(!empty($data))
          {
              return $data;
          }else
          {
              return false;
          }
      }
      else
      {
        return false;   
      }
}

     


}
