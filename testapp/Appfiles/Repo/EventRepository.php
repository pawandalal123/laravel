<?php
namespace Appfiles\Repo;
use App\Http\Requests;
use Illuminate\Http\Request;
use Appfiles\Repo\EventInterface;
use Illuminate\Container\Container as App;
use App\Model\Event;
use App\Model\Weightage;
use Illuminate\Support\Facades\Input;
use SEO;
use DB;
use Moloquent;
use DateTime;

class EventRepository implements EventInterface
{

	  use RepositoryTrait;
	  protected $model,$app;
    private  $connection;
    /**
     * Order Details Repository
     */
    public function __construct(App $app)
    {
    	$this->app=$app;
    	$this->makeModel();  
      // $this->connection=DB::connection('mongodbevent');
    }

    public function getBy($condition, $columns = array('*')) {

        return Event::where($condition)->first($columns);
    }
    public function getByraw($condition, $columns = array('*'),$order) {

        return Event::whereRaw($condition)->orderBy('id',$order)->first($columns);
    }
    public function selectByraw($condition, $columns) {

        return Event::selectRaw($columns)->whereRaw($condition)->first();
    }
    
    public function getallBy($condition, $columns = array('*'))
    {
        return Event::where($condition)->get($columns);
    }
      public function getallpaginate($condition, $columns = '*',$orderBy='id')
    {
        return Event::selectRaw($columns)->whereRaw($condition)->orderBy($orderBy,'desc')->paginate(25);
    }

    public function getListdrop($condition,$key,$value ) {
    
        return Event::where($condition)->lists($key,$value);
    }

     public function getWeekWiseEventLive($condition,$weekDate = null)
      {
        if($weekDate==null)
          $weekDate = date("Y-m-d");
       
        return Event::where($condition)->whereRaw("WEEK('".$weekDate."') between week(`created_at`) and week(`created_at`)")->count('id');
      }

  public function getallByRaw($condition,$columns = array('*'),$groupby='id')
  {
       // dd('in here trait');
      
        return Event::whereRaw($condition)->groupBy($groupby)->get($columns);
    }

    public function getallByINRaw($condition,$columns = array('*'))
  {
       // dd('in here trait');
        
        return Event::whereIn('id',$condition)->get($columns);
    }

    public function update(array $data, $id, $attribute="id") {
        return Event::where($attribute, $id)->update($data);
    }
    public function updateevent(array $data, $condition) {
        return Event::where($condition)->update($data);
    }
    
     public function all($columns = array('*')) {
       // dd('in here trait');
        return Event::orderBy('id','DESC')->get($columns);
    }
    public function DetailsById($eventidarray,$columns = array('*'))
     {

      $eventdetails = Event::whereIn('id',$eventidarray)->get($columns);
      
        return $eventdetails;
     } 
     public function getrawList($condition, $columns)
    {
        $eventlist = Event::selectRaw($columns)
            ->whereRaw($condition)
            ->groupBy('id')
            ->get();

            return $eventlist;
    }
    public function countevent($condition, $columns)
    {

        $eventlist = Event::selectRaw($columns)
            ->whereRaw($condition)
            ->groupBy('user_id')
            ->get();

            return $eventlist;
    }



    public function eventlistbycat($request)
    {

    }


     public function eventlistall($request)
    {


        $seoFlag=0;
        $seoTitleCategory='';
        $completeformData = Input::all();
        @extract($completeformData);
      
       $featuredFlag = 0;       
       
        //if both city and keyword are comming//
        $allEventsList = Weightage::select('weightages.event_id','title','venue_name','recurring_type','no_dates',DB::raw('group_concat(city) as city'),'state','country','category','url','event_mode','weightages.start_date_time',DB::raw("IF(weightages.end_date_time = '0000-00-00 00:00:00', weightages.`start_date_time`, weightages.`end_date_time`) as enddate"),'timezone_id','banner_image','ticketed','weightages.created_at','min_ticket_price','max_ticket_price','closed','featured_country','featured_city','address1');
        $pageData =12;
        $wherecondition = array('weightages.status'=>1,'private'=>0);
         
        $allEventsList = $allEventsList->where($wherecondition);

       if(isset($request->featured))
        {
          $countryName = '';
          $featuredFlag = 1;
          if($request->featured == '1')
          {
              $countryName =  substr($request->countryname,0,strpos($request->countryname,'--'));
              if(stripos(trim($request->cityname), 'ncr')!==false )
               { 
                $allEventsList = $allEventsList->where
                (function ($query) use ($countryName){
                    $query->where('featured_city','=','1')->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%')->orWhere('city', 'LIKE', '%gurugram%')->orWhere('country', 'LIKE', '%'.$countryName.'%')->where('featured_country','=','1');
                    });
              }
              else
              {

              $allEventsList = $allEventsList->whereRaw("((featured_city ='1' And city like '%".$request->cityname."%') or (country like '%".$countryName."%' and featured_country= 1))");

              }
          }
          
          else  if($request->featured == '2')
          {
              $allEventsList = $allEventsList->whereRaw("(country like '%".$request->countryname."%' And (featured_country= 1 or featured_city ='1'))");
          }
            $pageData=20;
        }
        
     
        if(isset($request->pricefilter) && $request->pricefilter!='')
        {
          $pricefilters = explode(',', $request->pricefilter);
          $arraymaxval = '';
          $maxval = '';
          if(($key=array_search(5001, $pricefilters))!==false)
          {
            $maxval=5001;
            unset($pricefilters[$key]);
          }
          if(count($pricefilters)>0)
          {
             $arraymaxval = max($pricefilters);

          }
         
          if($arraymaxval!='' && $maxval!='')
          {
            $conditionprice=" (min_ticket_price <$arraymaxval and max_ticket_price or max_ticket_price>5000)";

          }
          else
          {
            if($arraymaxval!='')
            {
               $conditionprice="min_ticket_price <$arraymaxval";
            }
            if($maxval!='')
            {
               $conditionprice="max_ticket_price>5000";

            }

          }
          $allEventsList = $allEventsList->whereRaw($conditionprice);

        }

        if($featuredFlag==1 || Input::has('userid') || Input::has('userview'))
        {
          $allEventsList = $allEventsList->whereRaw("weightages.date=1 and  (end_date_time >= now() or end_date_time ='0000-00-00 00:00:00') ");
        }
        else
        {
          $allEventsList = $allEventsList->whereRaw("weightages.date=1 and  ((weightages.end_date_time >= now() or weightages.end_date_time ='0000-00-00 00:00:00') and (DATEDIFF(NOW(),weightages.start_date_time)<=20 or recurring_type!=0))");
        }

        if(Input::has('userview')){

             $arraData = explode(",",$request->userview);
            
             if(count($arraData)>0) {
                
                 $allEventsList = $allEventsList->whereIn('event_id',$arraData);
              } 
        }
            if(isset($request->eventhighlites) && $request->eventhighlites!='')
         {

          $filters = explode(',', $request->eventhighlites);
          $allEventsList = $allEventsList->leftJoin('event_highlights', 'event_highlights.event_id', '=', 'weightages.event_id')->join('tickets', 'tickets.event_id', '=', 'weightages.event_id');

              $highlitesname = explode(',', $request->highlitesname);
              $allEventsList = $allEventsList->where
              (function ($query) use($highlitesname,$filters)
              {
                 $query->whereIn('event_highlights.highlight_id',$filters)
                 ->where('event_highlights.status',1);
                foreach ($highlitesname as $highlitesname)
                {
                 $query->orWhereRaw("tickets.name LIKE '%".str_replace('-',' ',$highlitesname)."%'");
                 $query->orWhereRaw("event_topics LIKE '%".str_replace('-',' ',$highlitesname)."%'");
                }
                });
             
           
         }
        $seoTitle="";
        if (Input::has('keyword'))
        {
          $seoTitle="Buy tickets for upcoming events happening in  ".$request->keyword;
          $ogTitle="Explore Upcoming  Events happening in ".$request->keyword;

          if(is_numeric($request->keyword))
          {
            $allEventsList = $allEventsList->whereRaw(" left(start_date_time,4) = '".$request->keyword."'");

          }
          elseif(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$request->keyword))
          {
            $datetosearch = date('Y-m-d',strtotime($request->keyword));
            $allEventsList = $allEventsList->whereRaw(" left(CONVERT_TZ(start_date_time,'+00:00',timezone_value),10) = '".$datetosearch."'");

            $seoTitle="Buy tickets for upcoming events happening on  ".$request->keyword;
            $ogTitle="Explore Upcoming  Events happening on ".$request->keyword;

          }
          else
          {
            $allEventsList = $allEventsList->leftJoin('tickets', 'tickets.event_id', '=', 'weightages.event_id');
            $allEventsList = $allEventsList->whereRaw("(category like '%".str_replace('-',' ',$request->keyword)."%' or ( venue_name like '%".str_replace('-',' ',$request->keyword)."%' or title like '%".str_replace('-',' ',$request->keyword)."%'  or eventtypes like '%".str_replace('-',' ',$request->keyword)."%' or  tags like '%".str_replace('-',' ',$request->keyword)."%'  or  event_topics like '%".str_replace('-',' ',$request->keyword)."%' or  tickets.name like '%".str_replace('-',' ',$request->keyword)."%'))");
          }
            
        }
        if(Input::has('statickeyword'))
        {
           $allEventsList = $allEventsList->whereRaw("(category like '%".str_replace('-',' ',$request->statickeyword)."%' or ( venue_name like '%".str_replace('-',' ',$request->statickeyword)."%' or (title like '%".str_replace('-',' ',$request->statickeyword)."%' or title like '%nye%' or title like '%2k17%')  or (eventtypes like '%".str_replace('-',' ',$request->statickeyword)."%' or eventtypes like '%2k17%' or eventtypes like '%nye%') or  (tags like '%".str_replace('-',' ',$request->statickeyword)."%' or tags like '%2k17%' or tags like '%nye%')  or  (event_topics like '%".str_replace('-',' ',$request->statickeyword)."%' or  event_topics like '%2k17%' or  event_topics like '%nye%')))");
         

        }
        if (Input::has('persons') && Input::has('topics') && Input::has('socialtags'))
        {
          
           $allEventsList = $allEventsList->whereRaw("(persons like '%".$request->persons."%' or ( topics like '%".str_replace('-',' ',$request->topics)."%' or socialtags like '%".str_replace('-',' ',$request->socialtags)."%'))");
        }
       
        if(Input::has('multidates'))
        {
          $allEventsList = $allEventsList->whereRaw("recurring_type = 1");
        }
        if(Input::has('multivenues'))
        {
          $allEventsList = $allEventsList->whereRaw("recurring_type = 3");
        }
        if(Input::has('idnotin'))
        {
          $allEventsList = $allEventsList->whereRaw("event_id != '".$request->idnotin."'");

        }
        if(Input::has('userid'))
        {
          $allEventsList = $allEventsList->whereRaw("user_id = '".$request->userid."'");

        }

        if (Input::has('cityname') And $featuredFlag ==0)
        { 
              $cityforsearch = $request->cityname;
              if(strcasecmp(trim($cityforsearch),'bangalore')==0 || strcasecmp(trim($cityforsearch),'bengaluru')==0 )
                {$allEventsList = $allEventsList->where
                  (function ($query){
                    $query->where('city', 'LIKE', '%bengaluru%')->orWhere('city', 'LIKE', '%bangalore%');
                    });

                 }
                  elseif(strcasecmp(trim($cityforsearch),'calcutta')==0 || strcasecmp(trim($cityforsearch),'kolkata')==0 )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($cityforsearch){
                      $query->where('city', 'LIKE', '%calcutta%')->orWhere('city', 'LIKE', '%kolkata%');
                      });
                }
                  elseif(strcasecmp(trim($cityforsearch),'gurgaon')==0 || strcasecmp(trim($cityforsearch),'gurugram')==0 )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query){
                      $query->where('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%gurugram%');
                      });
                }
                 elseif(stripos(trim($cityforsearch), 'ncr')!==false )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($cityforsearch){
                      $query->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%')->orWhere('city', 'LIKE', '%gurugram%');
                      });
                }
              elseif(stripos(trim($cityforsearch), 'delhi')!==false )
               {  $allEventsList = $allEventsList->where('city', 'LIKE', '%delhi%');}
              else
                {
                  $allEventsList = $allEventsList->where
                  (function ($query) use($cityforsearch,$featuredFlag){
                 
                    
                       $query->where('city', 'LIKE', '%' . str_replace('-', ' ', $cityforsearch).'%')->orWhere('state','LIKE', '%' . str_replace('-', ' ', $cityforsearch))->orWhere('country','LIKE', '%' . str_replace('-', ' ', $cityforsearch));
   
               
                   
                    });

                 }
            if(empty($seoTitle))
             { $seoTitle="Events  in  ".$request->cityname;
               $ogTitle="Events  in ".$request->cityname;
             }
        }

        if (Input::has('countryName') && $featuredFlag ==0)
        {
            $contryString = explode('--',$request->countryName.'--');

            $allEventsList = $allEventsList->where('country', 'LIKE', '%' . str_replace('-', ' ', $contryString[0]).'%');

            //FOR CATEGORY WISE SEARCH (mongo data)
            if($request->categoryType)
            {

                $allEventsList = $allEventsList->where('topics', 'LIKE', '%' .$request->categoryType.'%');
                $valueCategory=$request->categoryType;
                // dd($valueCategory);
          if(strtolower($valueCategory)!='human_interest' && strtolower($valueCategory)!='social_issues')
                $valueCategory=str_replace('_','/',$valueCategory);
          else
            $valueCategory=str_replace('_',' ',$valueCategory);
                $seoTitleCategory="Events related to ".ucwords($valueCategory)." in ".ucwords($contryString[0]);
                $seoFlag=1;

            }
            //end of CATEGORY WISE SEARCH

            //FOR CATEGORY WISE SEARCH (mongo data)
            if($request->topicsType)
            {
                $allEventsList = $allEventsList->where('socialtags', 'LIKE', '%' .$request->topicsType.'%');
                $valueCategory=$request->topicsType;

                $valueCategory=str_replace('-',' ',$valueCategory);
                $seoTitleCategory="Events related to ".ucwords($valueCategory)." in ".ucwords($contryString[0]);
                $seoFlag=1;

            }
            //end of CATEGORY WISE SEARCH

            //FOR CATEGORY WISE SEARCH (mongo data)
            if($request->personsType)
            {
                $allEventsList = $allEventsList->where('persons', 'LIKE', '%' .$request->personsType.'%');
                $valueCategory=$request->personsType;

                $valueCategory=str_replace('-',' ',$valueCategory);
                $seoTitleCategory="Events related to ".ucwords($valueCategory)." in ".ucwords($contryString[0]);
                $seoFlag=1;

            }
            //end of CATEGORY WISE SEARCH



            if($contryString[1]!='')
            {
              if(strcasecmp(trim($contryString[1]),'bangalore')==0 || strcasecmp(trim($contryString[1]),'bengaluru')==0 )
                {$allEventsList = $allEventsList->where
                  (function ($query) {
                    $query->where('city', 'LIKE', '%bengaluru%')->orWhere('city', 'LIKE', '%bangalore%');
                    });
                 }
              elseif(strcasecmp(trim($contryString[1]),'calcutta')==0 || strcasecmp(trim($contryString[1]),'kolkata')==0 )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query){
                      $query->where('city', 'LIKE', '%calcutta%')->orWhere('city', 'LIKE', '%kolkata%');
                      });
                }
                 elseif(strcasecmp(trim($contryString[1]),'gurgaon')==0 || strcasecmp(trim($contryString[1]),'gurugram')==0 )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query){
                      $query->where('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%gurugram%');
                      });
                }
                 elseif(stripos(trim($contryString[1]), 'ncr')!==false )
               { 
                $allEventsList = $allEventsList->where
                (function ($query) use ($contryString){
                    $query->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%')->orWhere('city', 'LIKE', '%gurugram%');
                    });
              }
              elseif(stripos(trim($contryString[1]), 'delhi')!==false )
               { $allEventsList = $allEventsList->where('city', 'LIKE', '%delhi%');}

           else
                {
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($contryString){
                    $query->where('city', 'LIKE', '%' . str_replace('-', ' ', $contryString[1]).'%')->orWhere('state','LIKE',str_replace('-', ' ', $contryString[1]));
                    });

                 }
            }

            if(!Input::has('cityname') && empty($seoTitle))
              {$seoTitle="Events in  ".$contryString[0];
               $ogTitle=" Events in ".$contryString[0];
                }
        }
        if(Input::has('eventdates'))
        {
            if(is_numeric($request->eventdates))
            {
              $allEventsList = $allEventsList->whereRaw(" left(start_date_time,4) = '".$request->eventdates."'");

            }
            elseif(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$request->eventdates))
            {
              $datetosearch = date('Y-m-d',strtotime($request->eventdates));
              $allEventsList = $allEventsList->whereRaw(" left(CONVERT_TZ(start_date_time,'+00:00',timezone_value),10) = '".$datetosearch."'");

            }
            else
            {
                $eventdateArray = array('today','tomorrow','month','week','weekend');
                if(in_array($request->eventdates, $eventdateArray))
                {
                  switch ($request->eventdates) 
                  {
                    case 'today':
                      $allEventsList = $allEventsList->whereRaw(" (curdate() BETWEEN start_date_time AND end_date_time)");
                    break;
                    case 'tomorrow':
                      $allEventsList = $allEventsList->whereRaw(" ( DATE_ADD(CURDATE() , INTERVAL 1 DAY) BETWEEN start_date_time AND end_date_time)");
                    break;
                    case 'month':
                    $allEventsList = $allEventsList->whereRaw(" (left(curdate(),7) BETWEEN left(start_date_time,7) AND left(end_date_time,7))");
                    break;
                    case 'weekend':
                    $allEventsList = $allEventsList->whereRaw("(DATE_ADD(CURRENT_DATE,INTERVAL mod(6-DAYOFWEEK(current_date)+7,7) DAY) between start_date_time and end_date_time or DATE_ADD(CURRENT_DATE,INTERVAL mod(7-DAYOFWEEK(current_date)+7,7) DAY) between start_date_time and end_date_time or  DATE_ADD(CURRENT_DATE,INTERVAL mod(1-DAYOFWEEK(current_date)+7,7) DAY) between start_date_time and end_date_time)");
                    break;
                    case 'week':
                     $allEventsList = $allEventsList->whereRaw("(WEEK(curdate()) between week(start_date_time) and week(end_date_time))");
             //        $allEventsList = $allEventsList->whereRaw("(WEEK(curdate()) between week(start_date_time) and week(end_date_time))start_date_time BETWEEN DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE()) - 1) DAY)
             // AND DATE_ADD(CURDATE(), INTERVAL (7 - DAYOFWEEK(CURDATE())) DAY)");
                    break;
                  }
                }
            }
        }
        if(Input::has('category'))
        {
          $categoryList = explode('-',str_replace('and','&',$request->category));
          $allEventsList = $allEventsList->whereIn('category',array_filter($categoryList));
        }

        $allEventsList = $allEventsList->groupBy('weightages.event_id');
        if($request->sortby)
        {

          switch ($request->sortby) {
            case 'upcoming':
              $allEventsList = $allEventsList->orderBy('closed','asc')->orderBy('start_date_time','asc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc');
              break;
            
            case 'popular':
              $allEventsList = $allEventsList->orderBy('total_weightage','desc')->orderBy('closed','asc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc');
              break;
              case 'popular':
              $allEventsList = $allEventsList->orderBy('total_weightage','desc')->orderBy('closed','asc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc');
              break;
              case 'recently-added':
               $allEventsList = $allEventsList->orderBy('created_at','desc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc');
              break;
          }
        }
        else
        {
           if(isset($request->featured)){
             $allEventsList = $allEventsList->orderBy(DB::raw('RAND()'));
            } else {
              
                 $allEventsList = $allEventsList->orderBy('total_weightage','desc')->orderBy('closed','asc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc')->orderBy('start_date_time','asc');
            }
       
        }
        if($request->paginate)
        {
          $allEventsList = $allEventsList->paginate($request->paginate);

        }
        else
        {
          $allEventsList = $allEventsList->paginate($pageData);
        }

          // Title: 
          // Description: Find things to do in India. Book tickets for latest entertainment events happening nearby you with Goeventz.
          // Adding SEO data here
        if($seoFlag==1 && strlen($seoTitleCategory)>0)
        {
          SEO::setTitle($seoTitleCategory);
        }
        else{
        // SEO::setTitle(@$seoTitle);
        // SEO::opengraph()->addProperty('title', @$ogTitle);
        // SEO::twitter()->setTitle(@$ogTitle);
      }
        return $allEventsList;
    }
    
    public function eventlistpast($request)
    {
        $completeformData = Input::all();
        @extract($completeformData);
        $wherecondition = array('events.status'=>1,'private'=>0);
         //if both city and keyword are comming//
        $allEventsList = Event::select('events.id','title','venue_name','recurring_type','no_dates','city','state','country','category','url','event_mode','start_date_time','timezone_id','banner_image');
        $allEventsList = $allEventsList->where($wherecondition);
        $allEventsList = $allEventsList->whereRaw("(IF(end_date_time = '0000-00-00 00:00:00', `start_date_time`, `end_date_time`)  <= '".date('Y-m-d H:i:s')."')");
        $seoTitle="";
        if (Input::has('keyword'))
        {
          $seoTitle="Buy tickets for upcoming events happening in  ".$request->keyword;
          $ogTitle="Explore Upcoming  Events happening in ".$request->keyword;

          if(is_numeric($request->keyword))
          {
            $allEventsList = $allEventsList->whereRaw(" left(start_date_time,4) = '".$request->keyword."'");

          }
          elseif(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$request->keyword))
          {
            $datetosearch = date('Y-m-d',strtotime($request->keyword));
            $allEventsList = $allEventsList->whereRaw(" left(CONVERT_TZ(start_date_time,'+00:00',timezone_value),10) = '".$datetosearch."'");

            $seoTitle="Buy tickets for upcoming events happening on  ".$request->keyword;
            $ogTitle="Explore Upcoming  Events happening on ".$request->keyword;

          }
          else
          {
            $allEventsList = $allEventsList->whereRaw("(category like '%".$request->keyword."%' or ( venue_name like '%".str_replace('-',' ',$request->keyword)."%' or title like '%".str_replace('-',' ',$request->keyword)."%'  or eventtypes like '%".str_replace('-',' ',$request->keyword)."%' or  tags like '%".str_replace('-',' ',$request->keyword)."%' )) ");
          }
            
        }
        if(Input::has('idnotin'))
        {
          $allEventsList = $allEventsList->whereRaw("events.id != '".$request->idnotin."'");

        }
        if(Input::has('userid'))
        {
          $allEventsList = $allEventsList->whereRaw("events.user_id = '".$request->userid."'");

        }
        if (Input::has('cityname'))
        {
            $cityforsearch = $request->cityname;
              if(strcasecmp(trim($cityforsearch),'bangalore')==0 || strcasecmp(trim($cityforsearch),'bengaluru')==0 )
                {$allEventsList = $allEventsList->where
                  (function ($query){
                    $query->where('city', 'LIKE', '%bengaluru%')->orWhere('city', 'LIKE', '%bangalore%');
                    });

                 }
                 elseif(stripos(trim($cityforsearch), 'ncr')!==false )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($cityforsearch){
                      $query->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%');
                      });
                }
              elseif(stripos(trim($cityforsearch), 'delhi')!==false )
               { $allEventsList = $allEventsList->where('city', 'LIKE', '%delhi%');}
              else
                {
                  $allEventsList = $allEventsList->where
                  (function ($query) use($cityforsearch){
                    $query->where('city', 'LIKE', '%' . str_replace('-', ' ', $cityforsearch).'%')->orWhere('state','=',str_replace('-', ' ', $cityforsearch))->orWhere('country','=',str_replace('-', ' ', $cityforsearch));
                    });

                 }
            if(empty($seoTitle))
             { $seoTitle="Buy tickets for upcoming events happening in  ".$request->cityname;
               $ogTitle="Explore Upcoming  Events happening in ".$request->cityname;
             }
        }
        
        
        if (Input::has('countryName'))
        {
            $contryString = explode('--',$request->countryName.'--');
            $allEventsList = $allEventsList->where('country', 'LIKE', '%' . str_replace('-', ' ', $contryString[0]).'%');
            if($contryString[1]!='')
            {
              if(strcasecmp(trim($contryString[1]),'bangalore')==0 || strcasecmp(trim($contryString[1]),'bengaluru')==0 )
                {$allEventsList = $allEventsList->where
                  (function ($query) {
                    $query->where('city', 'LIKE', '%bengaluru%')->orWhere('city', 'LIKE', '%bangalore%');
                    });
                 }
                 elseif(stripos(trim($contryString[1]), 'ncr')!==false )
               { 
                $allEventsList = $allEventsList->where
                (function ($query) use ($contryString){
                    $query->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%');
                    });
              }
              elseif(stripos(trim($contryString[1]), 'delhi')!==false )
               { $allEventsList = $allEventsList->where('city', 'LIKE', '%delhi%');}

           else
                {
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($contryString){
                    $query->where('city', 'LIKE', '%' . str_replace('-', ' ', $contryString[1]).'%')->orWhere('state','=',str_replace('-', ' ', $contryString[1]));
                    });

                 }
            }

            if(!Input::has('cityname') && empty($seoTitle))
              {$seoTitle="Buy tickets for upcoming events happening in  ".$contryString[0];
               $ogTitle=" Explore Upcoming  Events happening in ".$contryString[0];
                }
        }
        if(Input::has('eventdates'))
        {
            if(is_numeric($request->eventdates))
            {
              $allEventsList = $allEventsList->whereRaw(" left(start_date_time,4) = '".$request->eventdates."'");

            }
            elseif(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$request->eventdates))
            {
              $datetosearch = date('Y-m-d',strtotime($request->eventdates));
              $allEventsList = $allEventsList->whereRaw(" left(CONVERT_TZ(start_date_time,'+00:00',timezone_value),10) = '".$datetosearch."'");

            }
            else
            {
                $eventdateArray = array('today','month','week');
                if(in_array($request->eventdates, $eventdateArray))
                {
                  switch ($request->eventdates) 
                  {
                    case 'today':
                      $allEventsList = $allEventsList->whereRaw(" (curdate() BETWEEN start_date_time AND end_date_time)");
                    break;
                    case 'month':
                    $allEventsList = $allEventsList->whereRaw(" (left(curdate(),7) BETWEEN left(start_date_time,7) AND left(end_date_time,7))");
                    break;
                    case 'week':
                     $allEventsList = $allEventsList->whereRaw("(WEEK(curdate()) between week(start_date_time) and week(end_date_time))");
             //        $allEventsList = $allEventsList->whereRaw("(WEEK(curdate()) between week(start_date_time) and week(end_date_time))start_date_time BETWEEN DATE_SUB(CURDATE(), INTERVAL (DAYOFWEEK(CURDATE()) - 1) DAY)
             // AND DATE_ADD(CURDATE(), INTERVAL (7 - DAYOFWEEK(CURDATE())) DAY)");
                    break;
                    
                  }

                }
                

            }

        }
       
        $allEventsList = $allEventsList->orderBy('closed','asc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc')->orderBy('start_date_time','asc');
        if($request->paginate)
        {
          $allEventsList = $allEventsList->paginate($request->paginate);
        }
        else
        {
          $allEventsList = $allEventsList->paginate(12);
        }
        
        // dd($allEventsList);
          //         Title: 
          // Description: Find things to do in India. Book tickets for latest entertainment events happening nearby you with Goeventz.


               
          
                  // Adding SEO data here
        SEO::setTitle(@$seoTitle);
        SEO::opengraph()->addProperty('title', @$ogTitle);
        SEO::twitter()->setTitle(@$ogTitle);
        return $allEventsList;
    }

     

    public function eventlistmongo($request)
    {
        $completeformData = Input::all();
        @extract($completeformData);
        
        //$checkEvent=$this->connection->collection('eventlisting')->where(array('status'=>1))->paginate(15);
        $completeformData = Input::all();
        @extract($completeformData);
        $wherecondition = array('status'=>1);
         //if both city and keyword are comming//                                                                                                                                                       
        $allEventsList = $this->connection->collection('eventlisting')->select('event_id', 'city','state', 'country','closed','title', 'venue_name','category', 'url','event_mode','banner_image','no_dates', 'start_date_time','timezone_id','popularity','total_weightage','ticketsoldout','popularity','ticketed','end_date_time','recurring_type')->where($wherecondition);
       // dd($allEventsList);
        //$allEventsList = $allEventsList->where($wherecondition);
        $enddate_time = new \MongoDate(strtotime(date('Y-m-d H:i:s')));
        $allEventsList = $allEventsList->where('end_date_time','>=',$enddate_time);
        $seoTitle="";
        if (Input::has('keyword'))
        {
          $seoTitle="Buy tickets for upcoming events happening in  ".$request->keyword;
          $ogTitle="Explore Upcoming  Events happening in ".$request->keyword;

          if(is_numeric($request->keyword))
          {
           // $allEventsList = $allEventsList->where("year(start_date_time)",'=',$request->keyword);
             $nextYear = $request->keyword+1;
             $datetosearch = date('Y-m-d',strtotime($request->keyword.'-01-01'));
             $datematch =    date('Y-m-d', strtotime($nextYear.'-01-01'));
             $start = new \MongoDate(strtotime($datetosearch." 00:00:00"));
             $end = new \MongoDate(strtotime($datematch." 00:00:00"));

             $allEventsList = $allEventsList->whereBetween('start_date_time', array($start, $end));

          }
          elseif(preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$request->keyword))
          {
             $datetosearch = date('Y-m-d',strtotime($request->keyword));
             $datematch =    date('Y-m-d', strtotime($request->keyword . ' +1 day'));
             $start = new \MongoDate(strtotime($datetosearch." 00:00:00"));
             $end = new \MongoDate(strtotime($datematch." 00:00:00"));
             $allEventsList = $allEventsList->whereBetween('start_date_time', array($start, $end));
             //dd($allEventsList);
             $seoTitle="Buy tickets for upcoming events happening on  ".$request->keyword;
             $ogTitle="Explore Upcoming  Events happening on ".$request->keyword;

          }
          else
          {
            $keywordforsearch = $request->keyword;
            $allEventsList = $allEventsList->where
            (function ($query) use ($keywordforsearch){
                $query->where('category', 'LIKE', '%' .$keywordforsearch.'%')->orWhere('venue_name', 'LIKE', '%' .str_replace('-',' ',$keywordforsearch).'%')->orWhere('title', 'LIKE', '%' .str_replace('-',' ',$keywordforsearch).'%')->orWhere('eventtypes', 'LIKE', '%' .str_replace('-',' ',$keywordforsearch).'%')->orWhere('tags', 'LIKE', '%' .str_replace('-',' ',$keywordforsearch).'%');
                });
          }
            
        }
        if(Input::has('idnotin'))
        {
           $allEventsList = $allEventsList->where("event_id", '!=', intval($request->idnotin));
        }
        if(Input::has('userid'))
        {
          $allEventsList = $allEventsList->where('user_id','=',intval($request->userid));

        }
        if (Input::has('cityname'))
        {
            $cityforsearch = $request->cityname;
              if(strcasecmp(trim($cityforsearch),'bangalore')==0 || strcasecmp(trim($cityforsearch),'bengaluru')==0 )
                {$allEventsList = $allEventsList->where
                  (function ($query){
                    $query->where('city', 'LIKE', '%bengaluru%')->orWhere('city', 'LIKE', '%bangalore%');
                    });

                 }
                 elseif(stripos(trim($cityforsearch), 'ncr')!==false )
                 { 
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($cityforsearch){
                      $query->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%');
                      });
                }
              elseif(stripos(trim($cityforsearch), 'delhi')!==false )
               { $allEventsList = $allEventsList->where('city', 'LIKE', '%delhi%');}
              else
                {
                  $allEventsList = $allEventsList->where
                  (function ($query) use($cityforsearch){
                    $query->where('city', 'LIKE', '%' . str_replace('-', ' ', $cityforsearch).'%')->orWhere('state','like','%' .str_replace('-', ' ', $cityforsearch).'%')->orWhere('country','LIKE', '%' . str_replace('-', ' ', $cityforsearch).'%');
                    });

                 }
            if(empty($seoTitle))
             { $seoTitle="Buy tickets for upcoming events happening in  ".$request->cityname;
               $ogTitle="Explore Upcoming  Events happening in ".$request->cityname;
             }
        }
        
        if (Input::has('countryName'))
        {

            $contryString = explode('--',$request->countryName.'--');
            $allEventsList = $allEventsList->where('country', 'LIKE', '%' . str_replace('-', ' ', $contryString[0]).'%');
            if($contryString[1]!='')
            {
              if(strcasecmp(trim($contryString[1]),'bangalore')==0 || strcasecmp(trim($contryString[1]),'bengaluru')==0 )
                {$allEventsList = $allEventsList->where
                  (function ($query) {
                    $query->where('city', 'LIKE', '%bengaluru%')->orWhere('city', 'LIKE', '%bangalore%');
                    });
                 }
                 elseif(stripos(trim($contryString[1]), 'ncr')!==false )
               { 
                $allEventsList = $allEventsList->where
                (function ($query) use ($contryString){
                    $query->where('city', 'LIKE', '%delhi%')->orWhere('city', 'LIKE', '%gurgaon%')->orWhere('city', 'LIKE', '%noida%')->orWhere('city', 'LIKE', '%faridabad%')->orWhere('city', 'LIKE', '%ghaziabad%');
                    });
              }
              elseif(stripos(trim($contryString[1]), 'delhi')!==false )
               { $allEventsList = $allEventsList->where('city', 'LIKE', '%delhi%');}

           else
                {
                  $allEventsList = $allEventsList->where
                  (function ($query) use ($contryString){
                    $query->where('city', 'LIKE', '%' . str_replace('-', ' ', $contryString[1]).'%')->orWhere('state','LIKE','%' .str_replace('-', ' ', $contryString[1]).'%');
                    });

                 }
            }

            if(!Input::has('cityname') && empty($seoTitle))
              {$seoTitle="Buy tickets for upcoming events happening in  ".$contryString[0];
               $ogTitle=" Explore Upcoming  Events happening in ".$contryString[0];
                }
        }

        if(Input::has('category'))
        {
          
           $categoryList = explode('-',str_replace('and','&',$request->category));
           //$allEventsList = $allEventsList->where('category','in',array_filter($categoryList));
           $allEventsList = $allEventsList->whereIn('category',array_filter($categoryList));
        }
        if(Input::has('eventdates'))
        {
          $eventdateArray = array('today','tomorrow','month','week');
          if(in_array($request->eventdates, $eventdateArray))
          {
            switch ($request->eventdates) 
            {
              case 'today':
                $allEventsList = $allEventsList->where('start_date_time', '<=', new DateTime())->where('end_date_time', '>=', new DateTime());
              break;
              case 'tomorrow':
                $allEventsList = $allEventsList->where('start_date_time', '<=', new DateTime('+1 day'))->where('end_date_time', '>=', new DateTime('+1 day'));
              break;
              case 'month':
              $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
              $last_day_this_month  = date('Y-m-t');
              $start = new \MongoDate(strtotime($first_day_this_month." 00:00:00"));
              $end = new \MongoDate(strtotime($last_day_this_month." 00:00:00"));
              -$allEventsList = $allEventsList->where('start_date_time', '<=', $end)->where('end_date_time', '>=', $start);
              break;
              case 'week':
              $monday = new DateTime();
              $sunday = new DateTime();
              $monday->modify('this week');
              $sunday->modify('this week +6 days');
              $firtDay = $monday->format('Y-m-d');
              $lastday = $sunday->format('Y-m-d');
              $start = new \MongoDate(strtotime($firtDay." 00:00:00"));
              $end = new \MongoDate(strtotime($lastday." 00:00:00"));
              $allEventsList = $allEventsList->where('start_date_time', '<=', $end)->where('end_date_time', '>=', $start);
              break;
            }
          }
        }
        //dd($allEventsList);
        
        if($request->sortby)
        {

          switch ($request->sortby) {
            case 'date':
              $allEventsList = $allEventsList->orderBy('closed','asc')->orderBy('start_date_time','desc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc');
              break;
            
            case 'popular':
              $allEventsList = $allEventsList->orderBy('closed','asc')->orderBy('total_weightage','desc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc');
              break;
          }

        }
        else
        {
          $allEventsList = $allEventsList->orderBy('closed','asc')->orderBy('total_weightage','desc')->orderBy('ticketsoldout','asc')->orderBy('no_dates','asc')->orderBy('ticketed', 'desc')->orderBy('popularity', 'desc')->orderBy('start_date_time','asc');
        }
        $allEventsList = $allEventsList->groupBy('event_id');
        //$projections = ['event_id', 'city','state', 'country','closed','title', 'venue_name','category', 'url','event_mode','banner_image','no_dates', 'start_date_time','timezone_id'];
        if($request->paginate)
        {
          $allEventsList = $allEventsList->paginate(intval($request->paginate));
        }
        else
        {
          $allEventsList = $allEventsList->paginate(12);
        }
        
        

          //         Title: 
          // Description: Find things to do in India. Book tickets for latest entertainment events happening nearby you with Goeventz.

          
                  // Adding SEO data here
        SEO::setTitle(@$seoTitle);
        SEO::opengraph()->addProperty('title', @$ogTitle);
        SEO::twitter()->setTitle(@$ogTitle);
        return $allEventsList;
    }

  public function getList($condition,$key,$value )
  {
        return Event::where($condition)->lists($key,$value);
  }
  public function delete($id) 
  {
    
        return Event::destroy($id);
  }

  function model()
    {
        return 'App\Model\Event';
    }
   
	/**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
	// Custom show function for event show
	 public function showall($condition,$condition1,$limit=12,$order=array('id'=>'desc'),$columns = array('*')) {
		        
            return Event::where($condition)->whereRaw($condition1)->take($limit)->OrderBy('id','desc')->get($columns);
	  
    }

    public function getallByCount($daydate,$condition, $columns = array('*')) {
       
        return Event::whereDate('created_at', '=',$daydate)->get($columns);
    }

   public function getallByBetween($wherebetween, $columns = array('*')) {
       
          return Event::whereBetween('created_at', $wherebetween)->orderBy('id','desc')->get($columns);
        
    }

    public function getallByBetweenPublish($wherebetween, $columns = array('*')) {

     

      // dd($data);
        
        return Event::where(array('status'=>1))->whereBetween('publish_date', $wherebetween)->orderBy('publish_date','asc')->get($columns);
    }

    
     public function getallByBetweenPublishCount($data, $columns = array('*')) {


         return Event::where(array('status'=>1))->whereRaw("end_date_time >= '".$data."'")->count('id');
      
    }

    public function getallByCountcreate($daydate,$condition, $columns = array('*')) {
       
        return Event::whereDate('created_at', '=',$daydate)->count('id');
    }

	/**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
	// Custom show function for event show
	 public function countNum($condition,$condition1) {
		
        return Event::where($condition)->whereRaw($condition1)->count();
	  
    }
    
    public function rulesForCreateOrderForm(){
      return [
                'feild_name' => 'required|max:500',
                'feild_type' => 'required',
                'feild_require_default' => 'required'
                
            ];
    }
    public function rulesForCreateOrderFormMessage(){
      return [
                'feild_name.required' => 'The field name is required',
                'feild_name.max' => 'The field name may not be greater than 65 characters'
                
                
            ];
    }

    public function rulesForCreateOrderVideo(){
      return [
                'youtube' => 'required|max:300'

            ];
    }

     public function rulesForCreateOrderImage(){
      return  [
                'imagegallery' => 'required',
                'name' => 'max:150'

            ];
    }
	  public function rulesForCreateOrderImageMessage()
    {
       return [
            'imagegallery.required' => 'The image field is required.',
            'name.max' => 'The image title may not be greater than 150 characters'
        ];
    }

     public function rulesForCreateEvent(){
      return  [
                'title' => 'required|max:100',
                'venue_name' => 'max:150',
                'address1' => 'max:150',
                'city' => 'max:65',
                'state' => 'max:65',
                'pincode' => 'max:15',
                'country' => 'max:65',
                // 'start_date_time' => 'required|date',
                // 'end_date_time' => 'required|date|after:start_date_time',
                'keyword' => 'max:150',
               'event_image' => 'max:4096|mimes:JPEG,jpg,jpeg,png,gif,PNG',
                


            ];
    }
    public function rulesForCreateEventMessage()
    {
       return [
            'title.required' => 'The event title field is required.',
            'title.max' => 'The event title may not be greater than 150 characters',
            'address1.max' => 'The event address1 may not be greater than 150 characters',
            'event_image.size'    => 'Max image size 4MB.',
        ];
    }
	

              /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    


}
// echo "till here";
// $obj= new OrderdetailsRepository("test");
// $obj->all();
// echo "ends here";