<?php
$Urlprams =  Request::segment(1);
$class = 'active';
?>      
      {{--*/ $eventid = $event->id/*--}}
      @if($loginid)
      {{--*/ $eventid = $event->id.'/'.$loginid /*--}}
      @endif

  @if(isset($permissionData))   
    @if(empty($permissionData->permission)) 
       {{--*/ $dataOfflinePermission = array('0'=>0) /*--}} 
    @else
      {{--*/ $dataOfflinePermission = explode(",",$permissionData->permission) /*--}} 
    @endif
  @else
       {{--*/ $dataOfflinePermission = array('0'=>0) /*--}} 
  @endif

	  <ul id="accordion" role="tablist" aria-multiselectable="true">
      @if(@$collaboratorFlag == false) 
        <li class="panel panel-menu">
          @if($loginid)
          <a href="{{ URL::asset('/myevents'.'/'.$loginid)}}" class="Dashboardactive" >
            @else
            <a href="{{ URL::asset('/') }}myevents" class="Dashboardactive">
          @endif
          <i class="fa fa-home"></i>Event Dashboard
          </a>
        </li>
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/') }}eventgallery/{!! $eventid !!}" class="<?php if($Urlprams=='eventgallery'){ echo $class;}?>" title="Image" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-image">
          <i class="fa fa-picture-o"></i>Image
          </a>
        </li>
		<li class="panel panel-menu">
          <a href="{{ URL::asset('/') }}videogallery/{!! $eventid !!}" class="<?php if($Urlprams=='videogallery'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-video">
          <i class="fa fa-video-camera"></i>Video
          </a>
        </li>
		<li class="panel panel-menu">
          <a href="{{ URL::to('/bookingreport/'.$event->id.'/completed/'.$loginid) }}" class="<?php if($Urlprams=='bookingreport'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-report">
          <i class="fa fa-usd"></i>Report
          </a>
        </li>
		
         @if($event->status==1)
			<li class="panel panel-menu">
			  <a href="{{ URL::asset('/') }}pause/{!! $eventid !!}" class="<?php if($Urlprams=='pause'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-pause">
			  <i class="fa fa-pause"></i>Pause
			  </a>
			</li>
		  @endif
		   <li class="panel panel-menu">
          <a href="{{ URL::asset('/') }}customregister/{!! $eventid !!}" class="<?php if($Urlprams=='customregister'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-orderForm">
          <i class="fa fa-file-text-o"></i>Customize Order Form</a>          
        </li>
     @if(@$event->recurring_type==1)   
		  <li class="panel panel-menu">


          <a href="{{ URL::asset('/') }}edit/recurring-event/{!! $eventid !!}" class="<?php if($Urlprams=='editevent'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-edit">
          <i class="fa fa-pencil"></i>Edit</a>          
        </li>
    @elseif(@$event->recurring_type==3)
      <li class="panel panel-menu">

          <a href="{{ URL::asset('/') }}edit/multiple-venue-event/{!! $eventid !!}" class="<?php if($Urlprams=='editevent'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-edit">

          
          <i class="fa fa-pencil"></i>Edit</a>          
        </li>
    @else
      <li class="panel panel-menu">

          <a href="{{ URL::asset('/') }}edit/event/{!! $eventid !!}" class="<?php if($Urlprams=='editevent'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-edit">

          
          <i class="fa fa-pencil"></i>Edit</a>          
        </li>

    @endif
		<li class="panel panel-menu">
          <a href="{{ URL::asset('/') }}coupon/manage/{!! $eventid !!}" class="<?php if($Urlprams=='coupon'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-coupon">
          <i class="fa fa-newspaper-o"></i>Manage Coupon</a>         
        </li>
		<li class="panel panel-menu">
          <a href="{{ URL::asset('/') }}ticketwidget/edit/{!! $eventid !!}" class="<?php if($Urlprams=='ticketwidget'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-ticketPlugin">
          <i class="fa fa-plug"></i>Ticket Plugin</a>         
        </li>
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/') }}mailsetting/{!! $eventid !!}" class="<?php if($Urlprams=='mailsetting'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-mailSetting">
          <i class="fa fa-envelope"></i>Confirmation Mail Setting</a>         
        </li>
      
     @if(in_array(1,$dataOfflinePermission))
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/offline/payment/setting/') }}/{!! $eventid !!}" class="<?php if($Urlprams=='offline'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-offlinePayment">
          <i class="fa fa-at"></i>Offline Ticket</a>         
        </li>
      @endif
          <li class="panel panel-menu">
          <a href="{{ URL::asset('/eventtab') }}/{!! $eventid !!}" class="<?php if($Urlprams=='eventtab'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-eventTabs">
          <i class="fa fa-table"></i>Manage Event Tabs</a>         
        </li>
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/manage/tax') }}/{!! $eventid !!}" class="<?php if($Urlprams=='manage'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="Manage-Taxes">
          <i class="fa fa-table"></i>Manage/Add Taxes</a>         
        </li>
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/incompleteorderform') }}/{!! $eventid !!}" class="<?php if($Urlprams=='incompleteorderform'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="Incomplete-Order-Forms">
          <i class="fa fa-table"></i>Incomplete Order Forms</a>         
        </li>
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/event/settings') }}/{!! $eventid !!}" class="<?php if($Urlprams=='event'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-setting">
          <i class="fa fa-cog"></i>Setting</a>         
        </li>
      @if(in_array(2,$dataOfflinePermission))
        <li class="panel panel-menu">
          <a href="{{ URL::asset('/dashboard/collaborator') }}/{!! $eventid !!}" class="<?php if($Urlprams=='event'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_collaborator" track-element="manage_collaborator-setting">
          <i class="fa fa-users"></i>Collaborators</a>         
        </li>
      @endif 

    @endif
    @if(isset($collaboratorAccess))
      @if(in_array("3", $collaboratorAccess)==3)
          <li class="panel panel-menu">
            <a href="{{ URL::to('/bookingreport/'.$event->id.'/completed/'.$loginid) }}" class="<?php if($Urlprams=='bookingreport'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-report">
            <i class="fa fa-usd"></i>Report
            </a>
          </li>
      @endif
      @if(in_array("2", $collaboratorAccess)==2)
          <li class="panel panel-menu">
            <a href="{{ URL::asset('/offline/payment/setting/') }}/{!! $eventid !!}" class="<?php if($Urlprams=='collaborator'){ echo $class;}?>" track-type-value="{!! $eventid !!}" track-type="dashboard_event" track-element="manage_event-offlinePayment">
            <i class="fa fa-at"></i>Offline Ticket</a>         
          </li>
      @endif
    @endif
        
      </ul>

     
