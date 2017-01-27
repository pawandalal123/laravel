<div class="report_tab_content report_tab_content_bgcolor">
  <div class="row">
       <div class="col-sm-8 col-sm-offset-2 mt10 mb10">
      <label class="labeluppercase">Add/Edit {{($pagename=='updatetopics') ? 'Topics' :'Meta'}}</label>
      <div class="add_payment_form clearfix">
      @if($pagename=='updatetopics')
      <form action="" method="post" id="profiledit-form" enctype="multipart/form-data" novalidate="">
        <div class="col-md-12 col-sm-12">
          <div class="form-group">
          <input type="text" class="form-control xyz" name="topics" placeholder="Enter keywords eg.(event,run,music etc)" value="{{$metaArray['topics']}}">
           @if ($errors->has('topics')) 
           <div style="color:red">{{ $errors->first('topics') }}</div> 
           @endif
        </div>
           </div> 
      
       
        <div class="col-sm-12 mt15">
            <input name="submittopics" type="submit" class="btn-primary btnsize pull-right" value="Save">
          </div>
      </form>
      @else
      <form action="" method="post" id="profiledit-form" enctype="multipart/form-data" novalidate="">
        <div class="col-md-12 col-sm-12">
          <div class="form-group">
          <input type="text" class="form-control xyz" name="keywords" placeholder="Enter keywords eg.(event,run,music etc)" value="{{$metaArray['m_key']}}">
           @if ($errors->has('activitytype')) 
           <div style="color:red">{{ $errors->first('activitytype') }}</div> 
           @endif
        </div>
           </div> 
      
         <div class="col-sm-12">
        <textarea name="description" rows="3"  id="" class="form-control " placeholder="Enter description">{{$metaArray['m_desc']}}</textarea>
        </div>
        <div class="col-sm-12 mt15">
            <input name="submitmeta" type="submit" class="btn-primary btnsize pull-right" value="Save">
          </div>
      </form>
      @endif
      </div>
  
  </div>
  </div>
