<?php
namespace App\model;

use Illuminate\Database\Eloquent\Model;

use DB;

class Password_resets extends Model
{
   

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    
    //
    protected $table='password_resets';


    	public function scopeDetailsById($query,$ticketIdArray)
     {

     	
     	$ticketDetails = $query->whereIn('id',$ticketIdArray);
     	
        return $ticketDetails;
     } 

}