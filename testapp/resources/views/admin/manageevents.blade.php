@extends('layout.adminlayout')
@section('content')
<div class="admin-right" id="right-section">
    <div class="adminright-inner">
        <div class="row admintophed">
        <div class="col-sm-12">
          <h6>Manage Event</h6>
          <span class="mobileicon" id="iconId"><i class="fa fa fa-bars pull-left"></i></span>
        </div>
      </div>

      <div ng-app="manageeventmodule" ng-init="getParam='<?php echo $getParam;?>'">
          <div  ng-controller="activityCtrl" >
              <div class="row mt15">
                <div class="col-sm-12 mb10">
                  <div class="checklist_section checklist_section_icon">
                     <span class="filter_icon" title="Select filter">
                       <i class="fa fa-filter" aria-hidden="true"></i></span>
                      <div class="row">
                     <div class="col-sm-3 padr">
                                    <div class="inputbgcolor">
                      <!-- <label>Eventid / City / Organizeremail</label> -->
                      <input type="text" ng-model="commonfeilds" class="form-control" placeholder="Eventid / City / Organizeremail" value=''> 
                    </div>
                     </div>
                        <div class="col-sm-3 padr padl">
                                    <div class="inputbgcolor">
                     <!--  <label>Date</label> -->
                      <input class="form-control" type="text" id="enddateid" ng-model="dateto" placeholder="date"> 
                    </div>
                  </div>
                   <div class="col-sm-2 padl padr">
                     <!--  --> <!-- <label>Account Manager</label> -->
                      <select ng-model='accountmanager' class="form-control selectbgcolor"> 
                        <option value="" selected> Account Manager</option>
                				@foreach($alluserList as $alluserList)
                				<option value="{{ $alluserList->id}}">{{ $alluserList->name}}</option>

                				@endforeach
                      </select>
                    </div>
                 
                   <div class="col-sm-2 padl padr">
                      <!-- <label>Assigned Status</label> -->
                      <select ng-model='assigned' class="form-control selectbgcolor"> 
                	 	<option value=''>Assigned Status</option>
      				<option value="1">Assigned</option>
      				<option value="2">Not Assigned</option>
      			 </select>
                    </div>
                    <div class="col-sm-2 padl ">
                     <!--  <label>Event Type</label> -->
                      <select ng-model='eventtype' class="form-control selectbgcolor"> 
                	 	<option value=''>Event Type</option>
      				<option value="1">Discovery</option>
      				<option value="2">Not Discovery</option>
              <option value="3">Paid Events</option>
              
      			 </select>
                    </div>
                    <div class="col-sm-3 padr mt10 ">
                     <!--  <label>Event Type</label> -->
                      <select ng-model='eventfeature' class="form-control selectbgcolor"> 
                    <option value=''>None Featured</option>
              <option value="1">All Featured</option>
              <option value="2">Featured In city</option>
               <option value="3">Featured In Country</option>
             </select>
                    </div>
                  

                    <div class="col-sm-3  padl padr mt10">
                     <!--  <label>Event status</label> -->
                      <select ng-model='eventstatus' class="form-control selectbgcolor"> 
                	 	<option value=''>Event status</option>
      				<option value="1">Live</option>
      				<option value="2">Not Live</option>
      			 </select> 
                    </div>
                     <div class="col-sm-3  padl padr mt10">
                     <!--  <label>Event status</label> -->
                      <select ng-model='reviewed' class="form-control selectbgcolor"> 
                    <option value=''>Event Reviewed</option>
              <option value="1">Reviewed</option>
              <option value="2">Not Reviewed</option>
             </select> 
                    </div>
                   <div class="col-sm-2 padr padl mt10">
                      <button class="btn_filter" ng-click="freshContent()">Apply</button>
                    </div>
        
                </div>
              </div>
             </div>
             </div>

            
              <div class="adminpannel">
                <div class="panel-heading">All Event List <span style="color:#BBB; font-size:12px;font-weight:normal;text-transform: lowercase; text-align:right;"> - Manage all events</span></div>
                <div class="panel-body">
                  <div class="col-sm-12">
                    <div class="ge-event-detail clearfix" ng-repeat="activitydata in activity ">
                      <div class="event-detail">
                        <strong>Title:</strong><span>@{{activitydata.title}} / @{{activitydata.id}},</span> 
                        <strong>Starts:</strong><span>@{{activitydata.start_date_time}},</span>
                        <strong>Venue:</strong><span>@{{activitydata.venue_name}}</span> 
                        <strong>Addr:</strong><span>@{{activitydata.address1}}</span> 
                        <strong>Ip:</strong><span>@{{activitydata.ipaddress}}</span> 
                        <strong>Published on:</strong><span>@{{activitydata.publish_date}}</span> 
                        <strong>Created at:</strong><span>@{{activitydata.created_at}}</span> 

                        Feature in City&nbsp;<input type="checkbox" name="fearuredcity" class="fearuredcity" id="fearuredcity_@{{activitydata.id}}" value=">@{{activitydata.featured_city}}" ng-checked="activitydata.featured_city== 1" >
                        &nbsp;&nbsp;&nbsp;Feature in Country&nbsp;<input type="checkbox" name="featuredCountry" class="fearuredcity" id="featuredCountry_@{{activitydata.id}}" value="@{{activitydata.featured_country}}" ng-checked="activitydata.featured_country== 1">
                        <button class="btn btnmy btn-xs pull-right" ng-click="assignto(activitydata.user_id)">Assign</button>
                        
                      </div>
                      <div class="spacebox">&nbsp;</div>
                      <div class="all-field-detail">
                        <div class="assign-org">
                          <strong>Organizer:</strong><span>@{{activitydata.email}}</span>
                        </div>
                        <div class="assign-org">
                          <strong>Created by:</strong><span>@{{activitydata.creadtedBy}}</span>
                        </div>
                        <div class="assign-org">
                          <strong>Assigned to:</strong><span>@{{activitydata.assignedto}}</span>
                        </div>
                        <input type="checkbox" name="relatedevents" class="relatedevents" id="@{{activitydata.id}}" value="" ng-checked="activitydata.showrelatedevent== 1">Show related events, or more events from city? 
                        <input type="checkbox" name="reviewedevent" class="reviewedevent" id="@{{activitydata.id}}" value="" ng-checked="activitydata.reviewed== 1">Reviewed? 
                        <button class="btn btnmy btn-xs pull-right @{{activitydata.id}}" style="margin:5px 5px 5px 3px; background-color:red!important; border-radius:1px!important;"  confirmed-click="deleteevent(activitydata.id,'event')"  ng-confirm-click="Are you want to delete this event?">Delete Event</button> 
                        <button class="btn btnmy btn-xs pull-right @{{activitydata.id}}" style="margin:5px 3px 5px 3px;  border-radius:1px!important;"  confirmed-click="deleteall(activitydata.user_id,'allevent')"  ng-confirm-click="Are you want to delete this event of this user?">Delete All Event</button> 
                        <button class="btn btnmy btn-xs pull-right @{{activitydata.user_id}}" style="margin:5px 3px 5px 3px; background-color:#EF611D!important; border-radius:1px!important;"  confirmed-click="banuser(activitydata.user_id,'banuser')"  ng-confirm-click="Are you want to banned this user?">Block User</button>         
                      </div>
                    </div>
                    <a ng-click="loadMore(page)" class="loadmore" >Load More</a>
                  </div>
                </div>
              </div>
            
          </div>
      </div>
	 </div>
    </div>
     </div>
<div class="loaderbox" style="display:none;"><div class="loader"></div></div>
 <script type="application/javascript"   src="{{URL::to('testapp/public/js/bot.js')}}"></script>
 <script type="text/javascript" src="{{ URL::asset('web/js/angularjs/manageevent.js')}}"></script>
<script type="text/javascript">
   

    $(document).ready(function()
        {

      $(document).on("click",".fearuredcity", function(e){ 

        $('.loaderbox').show();

          var featured =  $(this).attr("id");  
          var value = $(this).is(':checked');
          var val =0;
          if(value == true)
            val =1;

          featuredcounters = featured.split('_');
          featuredcounter = featuredcounters[1];
          if(featuredcounters[0]=="fearuredcity"){
            var type = 1;
          } else {
            var type = 2;
          }
                            
               $.ajax({
                url: SITE_URL+"api/changefeatured",
                type: "post",
                data: {'value':val,'id':featuredcounter,'type':type, '_token': $('input[name=_token]').val()},
                success: function(data){  
                $('.loaderbox').hide();            
                },error:function(){ 
                  $('.loaderbox').hide();
                    alert("error!!!!");
                 }
              });
     })
      $(document).on("click",".relatedevents", function(e){ 
         var value = $(this).is(':checked');
          var val =0;
          if(value == true)
            val =1;
         var eventid =  $(this).attr("id");  
        setrelatedevent('relatedevent',val,eventid); 
});
      $(document).on("click",".reviewedevent", function(e)
      { 
         var value = $(this).is(':checked');
          var val =0;
          if(value == true)
            val =1;
         var eventid =  $(this).attr("id");  
        setrelatedevent('reviewedevent',val,eventid); 
     });


		$('#enddateid').datetimepicker({
					yearOffset:0,
					lang:'en',
					timepicker:false,
					changeMonth: true,
					format:'Y-m-d',
					formatDate:'Y-m-d',
					
		
					      onClose: function( selectedDate ) {
        $( "#startdateid" ).datetimepicker( "option", "maxDate", selectedDate );
      }
					
				});
			});
</script>
@stop


