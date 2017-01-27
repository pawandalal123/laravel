<?php 
namespace App\Http\Controllers\Api;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Common;
use DateTime;
use Appfiles\Repo\UserdetailInterface;
use Auth;
use URL;
use Appfiles\Repo\TicketInterface;
use Appfiles\Repo\MediadetailInterface;
use Appfiles\Repo\UsersInterface;
use Appfiles\Repo\EventInterface;
use Appfiles\Repo\EventdetailInterface;
use Appfiles\Repo\TimezoneInterface;
use Appfiles\Repo\ViewsCountRepository;
use Appfiles\Repo\User_followersInterface;
use Appfiles\Repo\UserfavouriteeventsInterface;
use Appfiles\Repo\SettingsInterface;

use Appfiles\Repo\UsertabInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
class Userapi extends Controller 
{

   public function  __construct(UsersInterface $user , UserdetailInterface $userdetail , EventInterface $event , 
    MediadetailInterface $media , TicketInterface $ticket ,User_followersInterface $followers, EventdetailInterface $eventdetail ,TimezoneInterface $timezone ,
    UserfavouriteeventsInterface $favroteEvent , SettingsInterface $setting,UsertabInterface $tabs,ViewsCountRepository $ViewsCountRepository )
    {
        $this->user=$user;
        $this->event=$event;
        $this->media=$media;
        $this->ticket=$ticket;
        $this->eventdetail = $eventdetail;
        $this->timezone = $timezone;
        $this->followers = $followers;
        $this->userdetail = $userdetail;
        $this->setting=$setting;
        $this->favroteEvent = $favroteEvent;
        $this->tabs = $tabs;
        $this->viewcount = $ViewsCountRepository;
        
    }

    public function userdetail(Request $request)
    {
        //first check organiser//
        $commonObj =  new Common();
        $userdata = '';
        $imagesArray = array();
        $upcommingevent = array(); //upcomminArray
        $pastevent = array();    //pastEvent
        $videoArray = array();
        $condition = array('organization_url'=>$request->name);
        $organiserDetails = $this->userdetail->getBy($condition,array('id','user_id','organization_name','organization_url','organization_websie_url','organization_pinterest_url','organization_banner_image','organization_logo','about_organization','organization_facebook_url','organization_twitter_url','organization_googleplus_url','organization_linkedin_url','ua_number'));
        $imagepath='';
        $eventcondition = '';
        $getticketeArray=array();
               // Default logo Image//
        $logo  = URL::asset('web/images/org.jpg');
        //first check request for organiser//
        if($organiserDetails)
        {
            $userIdDetail = $organiserDetails->user_id;
            if($organiserDetails->organization_banner_image)
            {
                // $imagepath = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organization_banner_image);
                $imagepath = $_ENV['CF_LINK'].'/user/'.$organiserDetails->user_id.'/organizer/banner/'.$organiserDetails->organization_banner_image;

            }
            if($organiserDetails->organization_logo)
            {
                // $logo = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organisation_logo);
                $logo = $_ENV['CF_LINK'].'/user/'.$organiserDetails->user_id.'/organizer/logo/'.$organiserDetails->organization_logo;

            }
                  //organiser all event  Condition//
            $eventcondition = array('user_id'=>$organiserDetails->user_id,'status'=>1,'private'=>0);
                    //get total followers//
            $allfollowers = $this->followers->getallBy(array('user_id'=>$organiserDetails->user_id),array('id'));
            $sourceurl = URL::to('/'.$commonObj->cleanURL($organiserDetails->organization_url));
            $getcount = 0;
            $userdata = array('id'=>$organiserDetails->user_id,
                              'name'=>$organiserDetails->organization_name,
                              'imageUrl'=>$imagepath,
                              'logo'=>$logo,
                              'about'=>$organiserDetails->about_organization,
                              'contact'=>$organiserDetails->organization_contact,
                               'getcount'=>$getcount,
                              'facebookurl'=>$organiserDetails->organization_facebook_url,
                              'twitterurl'=>$organiserDetails->organization_twitter_url,
                              'googleurl'=>$organiserDetails->organization_googleplus_url,
                              'totalfollower'=>count($allfollowers),
                              'ua_number'=>$organiserDetails->ua_number,
                              'linkdinurl'=>$organiserDetails->organization_linkedin_url,
                              'pintresturl'=>$organiserDetails->organization_pinterest_url,
                              'websiteurl'=>$organiserDetails->organization_websie_url,
                              'detailfor'=>'organiser');

            /////
            $usertabsData = $this->usertabsData($organiserDetails->user_id,2);
           
        }
        else
        {
                   //check Request for user//
         // $conditionUser = array('profile_url'=>$request->name);
          $conditionUser = "profile_url = '".$request->name."'";
          if(is_numeric($request->name))
          {
             $conditionUser = "profile_id = '".$request->name."'";
          }
            $userDetails = $this->userdetail->getByRaw($conditionUser,array('id','user_id','profile_url','facebook_url','twitter_url','linkedin_url','googleplus_url','ua_number'));
            if($userDetails)
            {
              $userIdDetail = $userDetails->user_id;
                     //User all event  Condition//
                $eventcondition = array('user_id'=>$userDetails->user_id,'status'=>1,'private'=>0);
                $UserProfileDetails = $this->user->find($userDetails->user_id);
                //$UserProfileDetails = User::where('id',$userDetails->user_id)->first();
              if($UserProfileDetails->profile_banner)
              {
                  // $imagepath = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organization_banner_image);
                  $imagepath = $_ENV['CF_LINK'].'/user/'.$userDetails->user_id.'/profile/banner/'.$UserProfileDetails->profile_banner;

              }
              if($UserProfileDetails->profile_image)
              {
                  // $logo = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organisation_logo);
                  $logo = $_ENV['CF_LINK'].'/user/'.$userDetails->user_id.'/profile/logo/'.$UserProfileDetails->profile_image;

              }
                $allfollowers = $this->followers->getallBy(array('user_id'=>$userDetails->user_id),array('id'));
                $sourceurl = URL::to('/'.$UserProfileDetails->profile_url);
                $getcount = $commonObj->gettotalsharecounter($sourceurl);
                $userdata = array('id'=>$UserProfileDetails->id,
                                  'name'=>$UserProfileDetails->profile_url,
                                  'imageUrl'=>$imagepath,
                                  'logo'=>$logo,
                                  'about'=>$UserProfileDetails->about,
                                  'contact'=>'',
                                  'getcount'=>$getcount,
                                  'facebookurl'=>$userDetails->facebook_url,
                                  'twitterurl'=>$userDetails->twitter_url,
                                  'googleurl'=>$userDetails->googleplus_url,
                                  'totalfollower'=>count($allfollowers),
                                  'linkdinurl'=>$userDetails->linkedin_url,
                                  'pintresturl'=>'',
                                  'websiteurl'=>'',
                                  'is_user'=>'no',
                                  'ua_number'=>$userDetails->ua_number,
                                  'detailfor'=>'User');
                $usertabsData = $this->usertabsData($userDetails->user_id,1);
                 
            }
        }
        
        
        if($eventcondition)
        {
          $userdata['profile_view']=0;
          //  $viewconter= $commonObj->makeviewcount($userIdDetail,'user_profile');
          // $getViewlist = $this->viewcount->getallBy(array('view_id'=>$userIdDetail,'type'=>'user_profile'));
          // if(count($getViewlist)>0)
          // {
          //   $userdata['profile_view'] = count($getViewlist);

          // }
                  $is_favroute='loginrequired';
                  $checkfavrouteList = array();
                  $userdata['userfavroute'] ='loginrequired';
                  $userfavrouteList = array();
                  $FavrouteCondition = array('event_id'=>$userIdDetail,'type'=>'user');
                  $totalfavroute =  $this->favroteEvent->getallBy($FavrouteCondition,array('event_id'));
                  $userdata['totallikes'] =count($totalfavroute);
                  if(Auth::check())
                  {
                       // echo 1;
                    $userDetails = Auth::user();  
                    if($userIdDetail==$userDetails->id)
                    {
                        $userdata['is_user'] = 'yes';
                    }            
                    $conditionFavroute = array('user_id'=>$userDetails->id,'type'=>'event');
                    $checkfavroute =  $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
                    $conditionuserFavroute = array('user_id'=>$userDetails->id,'type'=>'user');
                    $checkuserfavroute =  $this->favroteEvent->getallBy($conditionuserFavroute,array('event_id'));
                      // print_r($checkfavroute);User_favourite_events
                    
                    if($checkuserfavroute)
                    {
                        foreach($checkuserfavroute as $checkuserfavroute)
                        {
                          $userfavrouteList[] = $checkuserfavroute->event_id;
                        }
                        $userdata['userfavroute'] ='no';
                        if(in_array($userIdDetail,$userfavrouteList))
                        {
                           $userdata['userfavroute'] ='yes';

                        }
                    }

                  }

            $organiserevents = $this->event->getallBy($eventcondition,array('id','title','no_dates','venue_name','city','state','country','category','url','event_mode','start_date_time','end_date_time','timezone_id','banner_image'));
            if(count($organiserevents)>0)
            {
                $gettimezone = $this->timezone->all(array('id','timezone'));
                foreach($gettimezone as $gettimezone)
                {
                  $gettimezoneArray[$gettimezone->id] = $gettimezone->timezone;
                }
                $ticketCondition = array('display_status'=>1,'status'=>1);
                $ticketeArray = $this->ticket->getallBy($ticketCondition,array('event_id'));
                foreach($ticketeArray as $ticketeArray)
                {
                  $getticketeArray[] = $ticketeArray->event_id;
                }
                 $conditionRaw = "thirdpartylink != ''";
                 $geteventDetails = $this->eventdetail->getByraw($conditionRaw,array('event_id'));
                  foreach($geteventDetails as $geteventDetails)
                  {
                    $geteventDetailsArray[] = $geteventDetails->event_id;
                  }
                  $mediaArray = array();
                  $conditionCommon = array('status'=>1);
                  $medialist = $this->media->getallBy($conditionCommon,array('id','event_id','path','type'));
                  foreach ($medialist as $medialist)
                  {
                    $mediaArray[$medialist->event_id][$medialist->id] = $medialist->path.'-type-'.$medialist->type;
                  }
                
                      //check favroute //
                 
                  
                foreach($organiserevents as $organiserevents)
                {
                    //all upcomming events array//
                    $imageUrl = '';
                    if($organiserevents->banner_image)
                    {
                        // $imageUrl = URL::asset('uplode/'.$organiserevents->user_id.'/'.$organiserevents->banner_image);
                        $imageUrl = $_ENV['CF_LINK'].'/event/'.$organiserevents->id.'/banner/'.$organiserevents->banner_image;

                    }
                    $eventtype = 'offline';
                    $ButtonText = 'View Detail';
                    if(in_array($organiserevents->id, $getticketeArray) || in_array($organiserevents->id, $geteventDetailsArray))
                    {
                       $ButtonText = 'Buy Ticket';
                    }
                    if($organiserevents->event_mode==1)
                    {
                        $eventtype='online';
                    }
                          //convert date to local timezones//
                    $timeZone = $gettimezoneArray[$organiserevents->timezone_id];
                    $gettimezonename =$commonObj->gettimezonename($timeZone); 
                    $getstartdate = $commonObj->ConvertGMTToLocalTimezone($organiserevents->start_date_time,$timeZone);
                    $getenddate   = $commonObj->ConvertGMTToLocalTimezone($organiserevents->end_date_time,$timeZone);
                    $is_favroute='loginrequired';
                              //check user login//
                    if(Auth::check())
                    {
                      $is_favroute='No';
                      if(in_array($organiserevents->id,$checkfavrouteList))
                      {
                         $is_favroute='Yes';
                      }
                    }
                      //event detail array//
                     $today  = new DateTime();
                     $past   = new DateTime($getenddate);
                    if($past >$today ||  $organiserevents->end_date_time=='0000-00-00 00:00:00')
                    {
                      if($organiserevents->no_dates==1)
                      {
                         //$eventcreateType='withoutdate';
                         if($organiserevents->start_date_time=='' || $organiserevents->start_date_time=='0000-00-00 00:00:00')
                         {
                           $getstartdate='';
                         }

                      }
                      $upcommingevent[$organiserevents->id] = array('eventname'=>$organiserevents->title,
                                                                    'eventimage'=>$imageUrl,
                                                                    'eventtype'=>$eventtype,
                                                                    'eventcity'=>$organiserevents->city,
                                                                    'eventcountry'=>$organiserevents->country,
                                                                    'eventvenue'=>$organiserevents->venue_name,
                                                                    'eventcategory'=>$organiserevents->category,
                                                                    'eventstartdate'=>$getstartdate,
                                                                    'timezonename'=>$gettimezonename,
                                                                    'favroute'=>$is_favroute,
                                                                    'buttontext'=>$ButtonText,
                                                                    'eventurl'=>$commonObj->cleanURL($organiserevents->url),
                                                                    'eventmapurl'=>$organiserevents->map_url);

                    }
                    else
                    {
                        //Past event Array//
                      $pastevent[$organiserevents->id] = array('eventname'=>$organiserevents->title,
                                                                  'eventimage'=>$imageUrl,
                                                                  'eventtype'=>$eventtype,
                                                                  'eventcity'=>$organiserevents->city,
                                                                  'eventvenue'=>$organiserevents->venue_name,
                                                                  'eventcountry'=>$organiserevents->country,
                                                                  'eventcategory'=>$organiserevents->category,
                                                                  'eventstartdate'=>$getstartdate,
                                                                  'eventurl'=>$commonObj->cleanURL($organiserevents->url),
                                                                  'eventmapurl'=>$organiserevents->map_url);
                    }
                    
                    //organiser all event images array//
                   // $conditionCommon = array('event_id'=>$organiserevents->id,'status'=>1);
                   // $medialist = $this->media->getallBy($conditionCommon);
                    if(array_key_exists($organiserevents->id, $mediaArray))
                    {
                        $mediaData =  $mediaArray[$organiserevents->id];
                        foreach($mediaData as $key=>$mediaData)
                        {
                          $mediaType = explode('-type-', $mediaData);
                          if($mediaType[1]==1)
                          {
                              // $imagesArray[$medialist->id] =  URL::asset('uplode/'.$organiserevents->user_id.'/'.$organiserevents->id.'/'.$medialist->path);
                              $imagesArray[$key] = $_ENV['CF_LINK'].'/event/'.$organiserevents->id.'/gallery/'.$mediaType[0];
                          }
                          if($mediaType[1]==2)
                          {
                              $videoArray[$key] =  $mediaType[0];
                          }
                        }
                    }
                     //event videos array//
                }
            }
           // print_r($mediaArray);
            return response()->json([
                   'userdetail' =>$userdata,
                   'upcommingevent'=>$upcommingevent,
                   'pastevent'=>$pastevent,
                   'imageslist' => $imagesArray,
                   'videolink' => $videoArray,
                   'tabs'=>$usertabsData
                   ]);

        }

        else
        {
          return response()->json([
                   'userdetail' =>$userdata
                   
                   ]);
        }
    }

    public function userdetailnew(Request $request)
    {
        //first check organiser//
        $commonObj =  new Common();
        $userdata = '';
        $imagesArray = array();
        $upcommingevent = array(); //upcomminArray
        $pastevent = array();    //pastEvent
        $videoArray = array();
        $condition = array('organization_url'=>$request->name);
        $organiserDetails = $this->userdetail->getBy($condition,array('id','user_id','organization_name','organization_url','organization_websie_url','organization_pinterest_url','organization_banner_image','organization_logo','about_organization','organization_facebook_url','organization_twitter_url','organization_googleplus_url','organization_linkedin_url','ua_number'));
        $imagepath='';
        $eventcondition = '';
        $getticketeArray=array();
        $logo  = URL::asset('web/images/org.jpg'); // Default logo Image//
        //first check request for organiser//
        if($organiserDetails)
        {
            $userIdDetail = $organiserDetails->user_id;
            if($organiserDetails->organization_banner_image)
            {
                // $imagepath = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organization_banner_image);
                $imagepath = $_ENV['CF_LINK'].'/user/'.$organiserDetails->user_id.'/organizer/banner/'.$organiserDetails->organization_banner_image;

            }
            if($organiserDetails->organization_logo)
            {
                // $logo = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organisation_logo);
                $logo = $_ENV['CF_LINK'].'/user/'.$organiserDetails->user_id.'/organizer/logo/'.$organiserDetails->organization_logo;

            }
                  //organiser all event  Condition//
            $eventcondition = array('user_id'=>$organiserDetails->user_id,'status'=>1,'private'=>0);
                    //get total followers//
            $allfollowers = $this->followers->getallBy(array('user_id'=>$organiserDetails->user_id),array('id'));
            $sourceurl = URL::to('/'.$commonObj->cleanURL($organiserDetails->organization_url));
            $getcount = 0;
            // $commonObj->gettotalsharecounter($sourceurl);
            $userdata = array('id'=>$organiserDetails->user_id,
                              'name'=>$organiserDetails->organization_name,
                              'imageUrl'=>$imagepath,
                              'logo'=>$logo,
                              'about'=>$organiserDetails->about_organization,
                              'contact'=>$organiserDetails->organization_contact,
                               'getcount'=>$getcount,
                              'facebookurl'=>$organiserDetails->organization_facebook_url,
                              'twitterurl'=>$organiserDetails->organization_twitter_url,
                              'googleurl'=>$organiserDetails->organization_googleplus_url,
                              'totalfollower'=>count($allfollowers),
                              'is_user'=>'no',
                              'canedit'=>'no',
                              'ua_number'=>$organiserDetails->ua_number,
                              'linkdinurl'=>$organiserDetails->organization_linkedin_url,
                              'pintresturl'=>$organiserDetails->organization_pinterest_url,
                              'websiteurl'=>$organiserDetails->organization_websie_url,
                              'detailfor'=>'organiser');

            /////
            $usertabsData = $this->usertabsDatalist($organiserDetails->user_id,2);
           
        }
        else
        {
          //check Request for user//
         // $conditionUser = array('profile_url'=>$request->name);
          $conditionUser = "profile_url = '".$request->name."'";
          if(is_numeric($request->name))
          {
             $conditionUser = "profile_id = '".$request->name."'";
          }
          $userDetails = $this->userdetail->getByRaw($conditionUser,array('id','user_id','profile_url','facebook_url','twitter_url','linkedin_url','googleplus_url','ua_number'));
          if($userDetails)
          {
            $userIdDetail = $userDetails->user_id;
            $eventcondition = array('user_id'=>$userDetails->user_id,'status'=>1,'private'=>0);
            $UserProfileDetails = $this->user->find($userDetails->user_id);
              //$UserProfileDetails = User::where('id',$userDetails->user_id)->first();
            if($UserProfileDetails->profile_banner)
            {
                // $imagepath = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organization_banner_image);
              $imagepath = $_ENV['CF_LINK'].'/user/'.$userDetails->user_id.'/profile/banner/'.$UserProfileDetails->profile_banner;

            }
            if($UserProfileDetails->profile_image)
            {
                // $logo = URL::asset('uplode/'.$organiserDetails->user_id.'/'.$organiserDetails->organisation_logo);
              $logo = $_ENV['CF_LINK'].'/user/'.$userDetails->user_id.'/profile/logo/'.$UserProfileDetails->profile_image;

            }
              $allfollowers = $this->followers->getallBy(array('user_id'=>$userDetails->user_id),array('id'));
              $sourceurl = URL::to('/'.$UserProfileDetails->profile_url);
              $getcount = 0; 
              // $commonObj->gettotalsharecounter($sourceurl);
              $userdata = array('id'=>$UserProfileDetails->id,
                                'name'=>$UserProfileDetails->profile_url,
                                'imageUrl'=>$imagepath,
                                'logo'=>$logo,
                                'about'=>$UserProfileDetails->about,
                                'contact'=>'',
                                'getcount'=>$getcount,
                                'facebookurl'=>$userDetails->facebook_url,
                                'twitterurl'=>$userDetails->twitter_url,
                                'googleurl'=>$userDetails->googleplus_url,
                                'totalfollower'=>count($allfollowers),
                                'is_user'=>'no',
                                'canedit'=>'no',
                                'linkdinurl'=>$userDetails->linkedin_url,
                                'pintresturl'=>'',
                                'websiteurl'=>'',
                                'ua_number'=>$userDetails->ua_number,
                                'detailfor'=>'User');
              $usertabsData = $this->usertabsDatalist($userDetails->user_id,1);
          }
        }
        if($eventcondition)
        {
          $userdata['profile_view']=0;
          $userdata['usermode']=0;
          $viewconter= $commonObj->makeviewcount($userIdDetail,'user_profile');
          $getViewlist = $this->viewcount->getallBy(array('view_id'=>$userIdDetail,'type'=>'user_profile'));
          if(count($getViewlist)>0)
          {
            $userdata['profile_view'] = count($getViewlist);

          }
          $is_favroute='loginrequired';
          $checkfavrouteList = array();
          $userdata['userfavroute'] ='loginrequired';
          $userdata['userfollow'] ='loginrequired';
          $userfavrouteList = array();
          $FavrouteCondition = array('event_id'=>$userIdDetail,'type'=>'user');
          // $totalfavroute =  $this->favroteEvent->getallBy($FavrouteCondition,array('event_id'));
          // $userdata['totallikes'] =count($totalfavroute);
          
          if(isset($request->android_login_id) && $request->android_login_id!='')
          {
            $userdata['userfavroute'] ='no';
            $userdata['userfollow'] ='no';
            if($userIdDetail==$request->android_login_id)
            {
              $userdata['is_user'] = 'yes';
            }            
            $conditionuserFavroute = array('user_id'=>$request->android_login_id,'type'=>'user','event_id'=>$userIdDetail);
            $checkuserfavroute =  $this->favroteEvent->getBy($conditionuserFavroute,array('event_id'));
            if($checkuserfavroute)
            {
              $userdata['userfavroute'] ='yes';
            }
            ////////////for follow and unfollow///////
            $conditioncheck = array('user_id'=>$userIdDetail,'followed_by_id'=>$request->android_login_id);
            $followcheck = $this->followers->getBy($conditioncheck,array('user_id'));
            if($followcheck)
            {
              $userdata['userfollow'] ='yes';
            }
          }
          elseif(Auth::check())
          {
            // echo 1;
            $userDetails = Auth::user();
            if($userIdDetail==$userDetails->id)
            {
              $userdata['is_user']='yes';
              $checksetting = $this->setting->getBy(array('event_id'=>$userDetails->id,
                                                          'status'=>1,
                                                          'type'=>6));
              if($checksetting)
              {
                $userdata['canedit']='Yes';
                $userdata['usermode']=$checksetting->status;

              }
            }         
            $userdata['userfavroute'] ='no';
            $userdata['userfollow'] ='no';
            $conditionuserFavroute = array('user_id'=>$userDetails->id,'type'=>'user','event_id'=>$userIdDetail);
            $checkuserfavroute =  $this->favroteEvent->getBy($conditionuserFavroute,array('event_id'));
            if($checkuserfavroute)
            {
                $userdata['userfavroute'] ='yes';
            }
            ////////////for follow and unfollow///////
            $conditioncheck = array('user_id'=>$userIdDetail,'followed_by_id'=>$userDetails->id);
            $followcheck = $this->followers->getBy($conditioncheck,array('user_id'));
            if($followcheck)
            {
              $userdata['userfollow'] ='yes';
            }
          }
          else
          {

              $checksetting = $this->setting->getBy(array('event_id'=>$userIdDetail,
                                                          'status'=>1,
                                                          'type'=>6));
              if($checksetting)
              {
                $userdata['usermode']=$checksetting->status;
              }
          }

           // print_r($mediaArray);
            return response()->json([
                   'userdetail' =>$userdata,
                   'tabs'=>$usertabsData
                   ]);
        }

        else
        {
          return response()->json([
                   'userdetail' =>$userdata
                   ]);

        }
            
    }

    public function usertabsData($id,$tabfor)
    {
      $tabsData = '';
      $gettabs = $this->tabs->getallBy(array('user_id'=>$id,'tabfor'=>$tabfor,'status'=>1),array('id','tabname','tabcontent'));
      if(count($gettabs)>0)
      {
        foreach($gettabs as $gettabs)
        {
          $tabsData[$gettabs->id] = array('tabname'=>$gettabs->tabname,
                                          'tabcontent'=>$gettabs->tabcontent);

        }
      }
      

      return $tabsData;
     
    }
    public function usertabsDatalist($id,$tabfor)
    {
      $tabsData = '';
      $gettabs = $this->tabs->getallBy(array('user_id'=>$id,'tabfor'=>$tabfor,'status'=>1),array('id','tabname','tabcontent'));
      if(count($gettabs)>0)
      {
        foreach($gettabs as $gettabs)
        {
          $tabsData[$gettabs->tabname] =$gettabs->tabcontent;

        }
      }
      return $tabsData;
    }

    public function userlikesdata(Request $request)
    {

      //////////get all user list////////////
      $alluserlist=array();
      $userlist = $this->user->getallBy(array('status'=>1),array('id','name','profile_image','login_type'));
      foreach ($userlist as $userlist)
      {
        $logo  = URL::asset('web/images/org.jpg');
        if($userlist->profile_image)
        {
          $logo = $_ENV['CF_LINK'].'/user/'.$userlist->id.'/profile/logo/'.$userlist->profile_image;
        }
        
        $alluserlist[$userlist->id] = array('name'=>$userlist->name,'imagepath'=>$logo);
        # code...
      }
             //////////get all data Favroute////////////
      $is_favroute='loginrequired';
      $checkFavrouteList = array();
      if(Auth::check())
      {
        $userDetails = Auth::user();              
        $conditionFavroute = array('user_id'=>$userDetails->id,'type'=>'user');
        $checkFavroute = $this->favroteEvent->getallBy($conditionFavroute,array('event_id'));
        // print_r($checkfollow);
        if(count($checkFavroute)>0)
        {
          foreach($checkFavroute as $checkFavroute)
          {
            $checkFavrouteList[] = $checkFavroute->event_id;
          }
        }
               // echo 1;
          
      }


                  //////////get all user  Favroute list////////////
      $dataFavroute = array();
      $conditionall = array('event_id'=>$request->userid,'type'=>'user');
      $getallFavroute = $this->favroteEvent->getallBy($conditionall,array('event_id','user_id'));
      
      foreach($getallFavroute as $getallFavroute)
      {
        if(Auth::check())
        {
           $is_favroute='No';
          if(in_array($getallFavroute->user_id,$checkFavrouteList))
          {
             $is_favroute='Yes';
          }
        }
        if(array_key_exists($getallFavroute->user_id, $alluserlist))
        {
          $dataFavroute[$getallFavroute->user_id]= array('name'=>$alluserlist[$getallFavroute->user_id]['name'],
                                  'imageLink'=>$alluserlist[$getallFavroute->user_id]['imagepath'],
                                  'is_favroute'=>$is_favroute);

        }
      }

       return response()->json([
                   'likesData' =>$dataFavroute                  
                   ]);
    }

    public function userfollowdata(Request $request)
    {

      //////////get all user list////////////
      $alluserlist=array();
      $userlist = $this->user->getallBy(array('status'=>1),array('id','name','profile_image','login_type'));
      foreach ($userlist as $userlist)
      {
        $logo  = URL::asset('web/images/org.jpg');
        if($userlist->login_type==1)
        {
          $logo=$userlist->profile_image;
        }
        else
        {
          if($userlist->profile_image)
          {
            $logo = $_ENV['CF_LINK'].'/user/'.$userlist->id.'/profile/logo/'.$userlist->profile_image;
          }
        }
        $alluserlist[$userlist->id] = array('name'=>$userlist->name,'imagepath'=>$logo);
        # code...
      }
      //////////get all data Favroute////////////
      $is_favroute='loginrequired';
      $checkFollowerList = array();
      if(isset($request->android_login_id) && $request->android_login_id!='')
      {
        $conditionFollower = array('followed_by_id'=>$request->android_login_id);
        $checkFollower = $this->followers->getallBy($conditionFollower,array('user_id'));
        // print_r($checkfollow);
        if(count($checkFollower)>0)
        {
          foreach($checkFollower as $checkFollower)
          {
            $checkFollowerList[] = $checkFollower->user_id;
          }
        }
      }
      elseif(Auth::check())
      {
        $userDetails = Auth::user();              
        $conditionFollower = array('followed_by_id'=>$userDetails->id);
        $checkFollower = $this->followers->getallBy($conditionFollower,array('user_id'));
        // print_r($checkfollow);
        if(count($checkFollower)>0)
        {
          foreach($checkFollower as $checkFollower)
          {
            $checkFollowerList[] = $checkFollower->user_id;
          }
        }
               // echo 1;
      }

                  //////////get all user  Follower list////////////
      $dataFollower = array();
      $conditionall = array('user_id'=>$request->userid);
      $getallFollower = $this->followers->getallBy($conditionall,array('user_id','followed_by_id'));
      ////////////get all user list////////////
      $userdetailArray = array();
      $userDetails = $this->userdetail->all(array('id','user_id','profile_id','profile_url','organization_url'));
      foreach($userDetails as $userDetails)
      {
        if($userDetails->organization_url)
        {
          $profilename=$userDetails->organization_url;

        }
        elseif($userDetails->profile_url)
        {
          $profilename=$userDetails->profile_url;
        }
        else
        {
          $profilename=$userDetails->profile_id;
        }
        $userdetailArray[$userDetails->user_id] = $profilename;
      }
      foreach($getallFollower as $getallFollower)
      {
        if(isset($request->android_login_id) && $request->android_login_id!='')
        {
           $is_favroute='No';
          if(in_array($getallFollower->followed_by_id,$checkFollowerList))
          {
             $is_favroute='Yes';
          }
        }
        elseif(Auth::check())
        {
           $is_favroute='No';
          if(in_array($getallFollower->followed_by_id,$checkFollowerList))
          {
             $is_favroute='Yes';
          }
        }
        if(array_key_exists($getallFollower->followed_by_id, $alluserlist))
        {
          $dataFollower[$getallFollower->followed_by_id]= array('name'=>$alluserlist[$getallFollower->followed_by_id]['name'],
                                  'imageLink'=>$alluserlist[$getallFollower->followed_by_id]['imagepath'],
                                  'is_follow'=>$is_favroute);
          $dataFollower[$getallFollower->followed_by_id]['profileurl']='';
          if(array_key_exists($getallFollower->followed_by_id, $userdetailArray))
          {
            $dataFollower[$getallFollower->followed_by_id]['profileurl']=URL::asset('/'.$userdetailArray[$getallFollower->followed_by_id]);

          }

        }
         
      }

       return response()->json([
                   'followData' =>$dataFollower                  
                   ]);
    }

}
