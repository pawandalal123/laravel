 <div class="report_tab_content report_tab_content_bgcolor">
  <div class="row">
  <div class="col-sm-12">
     @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>
    <div class="col-sm-8 col-sm-offset-2 mt10 mb10">
       <label class="labeluppercase">Enter Payment Details</label>
     <div class="add_payment_form clearfix">
  <form action='' method='post' id='profiledit-form' enctype='multipart/form-data' novalidate>
 <input type="hidden" name="eventid" value="{{$eventid}}">
 <input type="hidden" name="paidamount" value="{{round($eventdata->paybalamount -$eventdata->amountpaid-$eventdata->adjustment,2)}}">
  @if ($errors->has('eventid')) 
  <div style="color:red">{{ $errors->first('eventid') }}</div> 
  @endif

   
     <div class="col-md-6 col-sm-12">
      <div class="form-group">
       {!! Form::text("amount",@$paymentedit->amount,array("class" => "form-control xyz","maxlength"=>"100","id"=>"pgcharges","placeholder"=>"Enter Amount",'maxlength'=>40)); !!}
  @if ($errors->has('amount')) 
  <div style="color:red">{{ $errors->first('amount') }}</div> 
  @endif
      </div>
    </div>
  <?php
  $paymode = array('Online','Neft','Cash','Cheque');
  if($pagename=='makeadjustment' || @$paymentedit->payment_mode=='Adjustment')
  {
    $paymode = array('Adjustment');
  }
  ?>
   <div class="col-md-6 col-sm-12">
                                <div class="form-group">
  <select class="form-control " name="type">
   @foreach($paymode as $paymode)
    {{--*/ $selected = '' /*--}}
   @if(@$paymentedit->payment_mode==$paymode)
   {{--*/ $selected = 'selected' /*--}}
   @endif
    <option value='{{$paymode}}' {{$selected}}>{{$paymode}}</option>
   @endforeach
  </select>

  </div>
    </div>
   <div class="col-md-6 col-sm-12">
     <div class="form-group">
  {!! Form::text("refrencenumber",@$paymentedit->refrence_number,array("class" => "form-control xyz","maxlength"=>"100","id"=>"eventid","placeholder"=>"Refrence Number",'maxlength'=>25)); !!}
  @if ($errors->has('refrencenumber')) 
  <div style="color:red">{{ $errors->first('refrencenumber') }}</div> 
  @endif
  </div>
      </div>
 <div class="col-md-6 col-sm-12">
  <div class="form-group">
  <div class="choose_finance">
  {!! Form::file('paymentproof[]',['id' => 'fileToUpload',"class" => "form-control xyz",'multiple'=>'multiple']); !!}

  @if ($errors->has('file')) 
  <div style="color:red">{{ $errors->first('file') }}</div> 
  @endif
  @if($paymentedit!='' )
  @if(@$paymentedit->payment_proof!='' && count($paymentedit->payment_proof)>0 && is_array(json_decode($paymentedit->payment_proof)) && $paymentedit->payment_proo!='""')
  <?php
  $fileslist = json_decode($paymentedit->payment_proof);
  ?>
  @foreach($fileslist as $fileslist)
    <a href="{{URL::to('uplode/paymentdocs/'.$fileslist)}}" target="_blank">View document</a>
  @endforeach
  @endif
  @endif
  </div>
    </div>
      </div>
 <div class="col-sm-12">
  <textarea name="comments" id="" rows="3" class="form-control " placeholder="Enter comments">{{@$paymentedit->comments}}</textarea>
  </div>
  @if(count($OrderList)>0)
<div class="col-sm-12 mt15">
<div class="mb10">
    <label style="text-transform:uppercase;font-weight:bold;color:#444;font-size:14px;">Select All 
       <input type="checkbox" name="checkall" class="checkall" value="all">
     </label>
     <label class="totalsection pull-right" style="text-transform:uppercase;font-weight:bold;color:#444;font-size:14px;">Toatl Amount :<span class="toatlamount">0</span>
      
     </label>
</div>
  <ul class="filterlist">
  <?php
  foreach($OrderList as $getorders)
  {
  ?>
    <li>
    <label class="btn btn-default-my btn-xs" title="<?php echo $getorders->order_id;?>">
    <input type="checkbox" class="trnxid" id="<?php echo $getorders->total_amount?>" name="trnxid[]" value="<?php echo $getorders->order_id;?>">&nbsp;<?php echo $getorders->order_id;?>
    </label>
    </li>
  <?php
  }
 ?>
  </ul>
  </div>
  @endif
  <div class="col-sm-12 mt15">
  @if($paymentedit!='')
  <input name="editpayment" type="submit" class="btn-primary btnsize pull-right" value="Edit">
  @else
    <input name="submitpayment" type="submit" class="btn-primary btnsize pull-right" value="Save">
  @endif
  </div>
  </form>
  </div>
  </div>
  </div>
 
</div>
<script type="application/javascript"   src="{{URL::to('web/js/common.js')}}"></script>
<script type="application/javascript"   src="{{URL::to('web/js/timer.js')}}"></script>
<script type="application/javascript"   src="{{URL::to('web/js/jquery.colorbox.js')}}"></script>