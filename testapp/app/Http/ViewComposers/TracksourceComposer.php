<?php
namespace App\Http\ViewComposers;
use App\Model\Common;
use Illuminate\Contracts\View\View;
use App\Http\Requests;
use Illuminate\Http\Request;
use Appfiles\Repo\TracksourceInterface;
use Illuminate\Support\Facades\Session;


class TracksourceComposer 
{
    public function  __construct(TracksourceInterface $tracksource)
    {
       $this->tracksource = $tracksource;
    }
    public function compose(View $view) 
    {
        $commonObj = new Common();
        $url=$_SERVER['REQUEST_URI'];
        $parsed=[];
        $time=60*24*15;
        $trackdata = array();
        //$time=1;
        $sessionid= Session::getId();
        $guestuser=$_SERVER['REMOTE_ADDR'].$sessionid;
        $getguestusercokkies = $commonObj->getcokkies('guestusercokkies');
        parse_str(substr($url, strpos($url, '?') + 1), $parsed);
        if(array_key_exists('utm_source', $parsed))
        {
            $trackdata['utm_source']=$parsed['utm_source'];
        }
        if(array_key_exists('utm_medium', $parsed))
        {
            $trackdata['utm_medium']=$parsed['utm_medium'];
        }
        if(array_key_exists('utm_campaign', $parsed))
        {
            $trackdata['utm_campaign']=$parsed['utm_campaign'];
        }
        if(isset($_SERVER["HTTP_REFERER"]))
        {
            $parsed['referer']=$_SERVER["HTTP_REFERER"];
        }
        try
        {
            if(!empty($parsed))
            {
              $trackdata['extra_terms']=json_encode($parsed);
            }
            if(array_key_exists('utm_campaign', $trackdata) || array_key_exists('utm_medium', $trackdata) || array_key_exists('utm_source', $trackdata))
            {
                if(array_key_exists('utm_source', $trackdata) && ($trackdata['utm_source']=='featured' || $trackdata['utm_source']=='recentview'))
                {
                    if($getguestusercokkies==false || $getguestusercokkies=='' || is_null($getguestusercokkies))
                    {
                        $trackdata['utm_position']=$trackdata['utm_source'];
                        $guestusercokkies = $commonObj->setcokkies('guestusercokkies',$guestuser,$time);
                        $trackdata['guest_user_id']=$guestuser;
                        $createtrack=$this->tracksource->create($trackdata);
                    }
                    else
                    {
                        $updatedata['utm_position']=$trackdata['utm_source'];
                        $update=$this->tracksource->updatetrack($updatedata,array('guest_user_id'=>$getguestusercokkies));
                    }
                }
                else
                {
                    $guestusercokkies = $commonObj->setcokkies('guestusercokkies',$guestuser,$time);
                    $trackdata['guest_user_id']=$guestuser;
                    $createtrack=$this->tracksource->create($trackdata);
                }
            }
            else
            {
                // $path = parse_url($url, PHP_URL_HOST); // will return 'domainname'
                if(isset($_SERVER["HTTP_REFERER"]) )
                {
                    $refralPath = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
                    $pathArray = array('goeventz.com','dev.goeventz.com');
                    if(!in_array($path, $refralPath))
                    {
                        // $refralPath = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST);
                        $trackdata['utm_source']=$refralPath;
                        $trackdata['utm_medium']='referer';
                        $trackdata['utm_campaign']='thirdparty';
                        $guestusercokkies = $commonObj->setcokkies('guestusercokkies',$guestuser,$time);
                        $trackdata['guest_user_id']=$guestuser;
                        $createtrack=$this->tracksource->create($trackdata);
                    }
                }
                else
                {
                    //echo $getguestusercokkies;
                    if(Isset($trackdata['utm_source']))
                       $trackdata['utm_source']='goeventz';

                   if(Isset($trackdata['utm_medium']))
                    $trackdata['utm_medium']='organic';

                   if(Isset($trackdata['utm_campaign']))
                      $trackdata['utm_campaign']='direct';

                    if($getguestusercokkies==false || $getguestusercokkies=='' || is_null($getguestusercokkies))
                    {
                        $guestusercokkies = $commonObj->setcokkies('guestusercokkies',$guestuser,$time);
                        $trackdata['guest_user_id']=$guestuser;
                        $createtrack=$this->tracksource->create($trackdata);
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            
        }
       // session_write_close();
    }
}