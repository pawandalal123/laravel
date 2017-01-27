<?php 
namespace App\Http\Controllers\Api;
use Illuminate\Http\Response;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Appfiles\Repo\MediadetailInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Query\Builder;

class Mediadetail extends Controller 
{

  public function  __construct(MediadetailInterface $media)
  {
      $this->media=$media;
  }

  public function medialist(Request $request)
  {
      $imagesArray = array();
      $videoArray = array();
      $conditionImages = array('event_id'=>$request->id,'status'=>1);
      $medialist = $this->media->getallBy($conditionImages);
      if(count($medialist)>0)
      {
        foreach ($medialist as $medialist)
        {
          if($medialist->type==1)
          {
               $imagesArray[$medialist->id] = array('imgtext'=>$medialist->name,'imgpath'=>$_ENV['CF_LINK'].'/event/'.$request->id.'/gallery/'.$medialist->path);
          }
          if($medialist->type==2 OR $medialist->type==3)
          {
              $videoArray[$medialist->id] = array('path'=>$medialist->path,'type'=>$medialist->type);

          }
        }
      }
      

          return response()->json(['imageslist' => $imagesArray,
                                   'videolink' => $videoArray ]);

          
  }
  public function medialistuser(Request $request)
  {
      $imagesArray = array();
      $videoArray = array();
      $conditionImages = array('status'=>1,'event_id'=>$request->userid,'media_for'=>2);
      $medialist = $this->media->getallBy($conditionImages,array('mediadetails.id','name','mediadetails.event_id','path','type'));
      if(count($medialist)>0)
      {
        foreach ($medialist as $medialist)
        {
          if($medialist->type==1)
          {
               $imagesArray[$medialist->id] = array('imgtext'=>$medialist->name,'imgpath'=>$_ENV['CF_LINK'].'/user/'.$medialist->event_id.'/organizer/media/'.$medialist->path);
          }
          if($medialist->type==2 OR $medialist->type==3)
          {
              $videoArray[$medialist->id] = array('path'=>$medialist->path,'type'=>$medialist->type);
          }
        }
      }
      

          return response()->json(['imageslist' => $imagesArray,
                                   'videolink' => $videoArray ]);
          
  }

}
