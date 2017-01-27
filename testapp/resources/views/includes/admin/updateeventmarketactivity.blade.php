<div class="report_tab_content report_tab_content_bgcolor">
  <div class="row">
       <div class="col-sm-8 col-sm-offset-2 mt10 mb10">
      @if($activitytopUpdate=='')
    
           <label class="labeluppercase">Enter Activity Details</label>
      <div class="add_payment_form clearfix">
      <form action="" method="post" id="profiledit-form" enctype="multipart/form-data" novalidate="">
        <div class="col-md-6 col-sm-12">
          <div class="form-group">
          <select class="form-control xyz" name="activitytype">
          <option value="0">Select Marketing Activity</option>
              @foreach($marketingactivitylist as $list)
              <option value="{{$list->id}}">{{$list->activity_name}} </option>
              @endforeach()
           </select>
       @if ($errors->has('activitytype')) 
       <div style="color:red">{{ $errors->first('activitytype') }}</div> 
       @endif
        </div>
           </div> 
        <div class="col-md-6 col-sm-12">
       <div class="form-group">
        <input class="form-control xyz"  id="refrenlink" placeholder="Enter Refrence Link" name="refrenlink" type="text">
         @if ($errors->has('refrenlink')) 
         <div style="color:red">{{ $errors->first('refrenlink') }}</div> 
         @endif
        </div>
        </div>
         <div class="col-sm-12">
        <textarea name="comments" rows="3"  id="" class="form-control " placeholder="Enter comments"></textarea>
        </div>
        <div class="col-sm-12 mt15">
            <input name="submitactivity" type="submit" class="btn-primary btnsize pull-right" value="Save">
          </div>
      </form>
      </div>
  @else

         <label class="labeluppercase">Update Activity Details</label>
      <div class="add_payment_form clearfix">
        @if($activitytopUpdate=='notallow')
        <div style="color:red">You are not allow to upadte this activity..</div>
        @elseif($activitytopUpdate=='notfound')
        <div style="color:red">This activity is not found..</div>
        @else
      <form action="" method="post" id="profiledit-form" enctype="multipart/form-data" novalidate="">
      <div class="col-md-6 col-sm-12">
        <div class="form-group">
        <select class="form-control xyz" name="activitytype">
          <option value="0">Select Marketing Activity</option>
              @foreach($marketingactivitylist as $list)
              <?php
              $select='' ;
              if($activitytopUpdate->activity_id==$list->id)
              {
                $select='selected' ;
              } 
              ?>
              <option value="{{$list->id}}" {{$select}} >{{$list->activity_name}} </option>
              @endforeach()
           </select>
       @if ($errors->has('activitytype')) 
                   <div style="color:red">{{ $errors->first('activitytype') }}</div> 
                   @endif
        </div>
        </div>
        <div class="col-md-6 col-sm-12">
     <div class="form-group">
      <input class="form-control xyz" id="refrenlink" placeholder="Enter Refrence Link" name="refrenlink" type="text" value="{{$activitytopUpdate->refrence_link}}">
       @if ($errors->has('refrenlink')) 
       <div style="color:red">{{ $errors->first('refrenlink') }}</div> 
       @endif
      </div>
      </div>
       <div class="col-sm-12">
      <textarea name="comments" rows="3"  id="" class="form-control " placeholder="Enter comments">{{$activitytopUpdate->comments}}</textarea>
      </div>
       <div class="col-sm-12 mt15">
          <input name="updateactivity" type="submit" class="btn-primary btnsize pull-right" value="Update">
        </div>
      </form>
      @endif
      </div>
  @endif
  </div>
  </div>
