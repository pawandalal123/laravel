<?php namespace App\Model;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Jenssegers\Mongodb\Model as Eloquent;


class Activity extends Eloquent {

    // protected $connection = 'mongodb';
	protected	$connection;

	
	function __construct() {
			
		}
    	/**
	 * Create an activity log entry.
	 *
	 * @param  mixed    $data
	 * @return boolean
	 */
	public  function log($data = [])
	{
		if($_SERVER['REMOTE_ADDR']=='59.144.160.64')
			return false;

		try{

			$this->connection=DB::connection('mongodb');
			
		}catch(\MongoConnectionException $e){
			Log::alert($e);
			return false;
		}
		$activity = array();
		$user_id=null;
		$user_name='Anonymous';
		$user_email=null;
		$user_mobile=null;
		if (config('log.auto_set_user_id') )
		{
			$user = \Auth::user();
			$user_id = isset($user->id) ? $user->id : null;
			$user_email=isset($user->email) ? $user->email: null;
			$user_mobile=isset($user->mobile) ? $user->mobile: null;
			if(strlen($user_id)>0 && strlen($user->name)==0)
				$user_name='No-Name';
			if(strlen($user_id)>0 && strlen($user->name)>0)
				$user_name=$user->name;
			

		}



		$activity['user_id']=$user_id;
		$activity['user_email']=$user_email;
		$activity['user_mobile']=$user_mobile;
		$activity['user_name']=$user_name;
		$activity['session_id']=isset($data['sessionId'])?$data['sessionId']:Session::getId();


		$activity['type'] = isset($data['type']) ? $data['type'] : null;		// Can be different types like Event, Services, etc
		$activity['type_value']   = isset($data['typeValue'])   ? $data['typeValue']   : null;  // related id to that 


		$activity['action']=isset($data['action'])      ? $data['action']      : null;
		$activity['element']=isset($data['element'])      ? $data['element']      : null;

		//$activity['page']=isset($data['page'])?$data['page']:null;
		$activity['description']=isset($data['description'])?$data['description']:null;
		//$activity['details']=isset($data['details'])?$data['details']:null;
		
		$name=$activity['user_name'];
		if($activity['user_name']=='Anonymous' && $activity['user_email']!=null)  //setting email if name is not present
		{
			$name=$activity['user_email'];
		}

		$activity['details']=$name.' '.$activity['action'].' '.(isset($data['element'])?$data['element'].' on ':'').$data['page_url'].' page';
		

		$activity['ip'] = $_SERVER['REMOTE_ADDR'];
		$activity['user_agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'No UserAgent';

		$activity['referrer'] = isset($data['referrer'])?$data['referrer']:null;

		$activity['page_url']=isset($data['page_url'])?$data['page_url']:null;
		
		$activity['created_at'] = new \MongoDate();

		$this->connection->collection('activity')->insert($activity);

		return true;
	}


}