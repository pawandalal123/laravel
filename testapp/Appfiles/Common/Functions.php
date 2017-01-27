<?php
namespace Appfiles\Common;
use Appfiles\Payment\EbsDecrypter;
use App\Model\Bannerimage;
use App\Model\Common;
use Appfiles\Repo\FeaturesRepository;
use Request;
use Route;

/**
 * Common Functions
 */
class Functions
{
    /**
     * summary
     */
    public function fetchGetData($path,$method='GET',$data=null)
    {   
        if(is_null($data))
          $request = Request::create($path, $method);
        else
            $request = Request::create($path, $method,$data);
            Request::replace($request->input());
          // Dispatch your request instance with the router

//          $response = Route::dispatch($request);
        $decodedResponse = json_decode(Route::dispatch($request)->getContent());
        return $decodedResponse;

    }

    public function setuserciycokkies()
    {
      //  $ipaddress='1.39.40.88';
        $getcokkiesCity='';
        $route = Request::segment(1);
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $commonObj = new Common();
        $getcokkies = $commonObj->getcokkies('usercity');
        $searchCity = '';
        if($route=='search')
        {
            $userSearchpram =  explode('--',Request::segment(2).'--');
            if($userSearchpram[1])
            {
                //$searchCity=ucwords($userSearchpram[1]);
                $searchCity=ucwords(str_replace('-',' ',$userSearchpram[1]));
            }
            else
            {
               // $searchCity=ucwords($userSearchpram[0]);
                $searchCity=ucwords(str_replace('-',' ',$userSearchpram[0]));
            }

        }
        if($getcokkies!=false )
        {
            if(!empty($searchCity)){
                $getcokkiesCity = $commonObj->setcokkies('usercity',$searchCity);
            }else{
                $getcokkiesCity = ucwords($getcokkies);
            }
        }
        else
        {
            $setcityCokkie='India';
            $getcokkiesCity = $commonObj->setcokkies('usercity',$setcityCokkie);

        }
        

        return $getcokkiesCity;
    }
    public function getcityforfilters()
    {
        $cityname='';
        $commonObj = new Common();
        $route = Request::segment(1);
        $searchCity = '';
        if($route=='search')
        {
            $userSearchpram =  explode('--',Request::segment(2).'--');
            if($userSearchpram[1])
            {
                //$searchCity=ucwords($userSearchpram[1]);
                $cityname=str_replace('-',' ',$userSearchpram[1]);
            }
            else
            {
                //$searchCity=ucwords($userSearchpram[0]);
                $cityname=str_replace('-',' ',$userSearchpram[0]);
            }

        }
        elseif($route=='browse')
        {

            if(Request::segment(2)=='person' || Request::segment(2)=='topic')
            {
                $cityname=Request::segment(3);
            }
            else
            {
                if(!empty(Request::segment(3)))
                {
                    $cityname=str_replace('-',' ',Request::segment(3));
                }
                else
                {
                    $cityname=Request::segment(2);
                }
            }
        }
        else
        {
            $getcokkies = $commonObj->getcokkies('usercity');
            if($getcokkies!=false )
            {
                 $cityname = $getcokkies;
            }
        }
        return $cityname;
        
    }

    public function extractEBSResponse($ebsReply)
    {
            if(strlen($ebsReply)>50) {
            $secret_key = $_ENV['EBS_SECRET_KEY'];     // Your Secret Key
            $DR = preg_replace("/\s/","+",$ebsReply);
            $decrypter = new EbsDecrypter($secret_key);
            $QueryString = base64_decode($DR);

            $decrypter->decrypt($QueryString);
            $QueryString = explode('&',$QueryString);

            $response = array();
            foreach($QueryString as $param){
            $param = explode('=',$param);
            $response[$param[0]] = urldecode($param[1]);
            }
            return $response;
            }
            return false;
            
        
    }

    public function loadDataToDefault($object)
    {
        // fetching category list        
        $object->categoryList = $this->fetchGetData('/api/category/catlist');
        // Fetching city list 
        $object->eventCityList = $this->fetchGetData('/api/city/citylist');
    }

    function getOS($user_agent) { 

   

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}

function getBrowser($user_agent) {



    $browser        =   $user_agent;

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}

public function eventsdate()
{
    $route = Request::segment(1);
    $eventdate = '';
    if($route=='search')
    {
        if(Request::segment(3))
        {
            $eventdate = Request::segment(3);
        }
    }

    return $eventdate;
}
public function searchkeyword()
{
    $route = Request::segment(1);
    $keyword = '';
    if($route=='search')
    {
        if(Request::segment(4))
        {
           $keyword = Request::segment(4);
        }
    }
    return $keyword;
}

}