<?php
namespace Appfiles\Classes;




/*Handle all functionality related to events*/

class Event
{

        public function create()
    {
        $event =  $this->eventinterface->getList(array('user_id'=>$user->id,'event_mode'=>0),'title','id');
                                          
    }
}

