<div class="row mt15">
        <div class="adminpannel">
          <div class="col-sm-12"><div class="panel-heading">Manage Service charges</div></div>
          <div class="col-sm-12  mt10 mb10">
           <div class="add_payment_form clearfix">
            <form action='' method='post' id='profiledit-form' enctype='multipart/form-data' novalidate>
           <div class="col-sm-3 ">
            
              <select class="form-control " name="country">
                 <option value="0">Select Country</option>
                 @foreach($countryArray as $countryArray)
                 <option value="{{$countryArray}}">{{$countryArray}}</option>
                 @endforeach
                 
               </select>
                @if ($errors->has('country')) 
             <div style="color:red">{{ $errors->first('country') }}</div> 
             @endif
          </div>
          <div  class="col-sm-3 ">
           
              {!! Form::text("taxname",'',array("class" => "form-control xyz","maxlength"=>"100","id"=>"pgcharges","placeholder"=>"Enter Tax Name",'maxlength'=>40)); !!}
              @if ($errors->has('taxname')) 
             <div style="color:red">{{ $errors->first('taxname') }}</div> 
             @endif
          </div>
          
           <div  class="col-sm-3 ">
           
              {!! Form::text("taxvalue",'',array("class" => "form-control xyz","maxlength"=>"100","id"=>"taxvalue","placeholder"=>"Value",'maxlength'=>40)); !!}
           @if ($errors->has('taxvalue')) 
             <div style="color:red">{{ $errors->first('taxvalue') }}</div> 
             @endif
          </div>
         
       
   
          <div class="col-sm-2  text-right">
             <input name="servicesubmitdefault" type="submit" class="btn-primary btnsize" value="Save">
          </div>
          </form>
          </div>
          </div>
        </div>
      </div>
      
       @if(count($servicetaxAray)>0)
      <div class="row">
        <div class="adminpannel">
          <div class="col-sm-12"><div class="panel-heading">Manage Service charges</div></div>
            <div class="panel-body">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr class="active">
                        <th><span>S.No</span>
                         Country Name</th>
                        <th>Tax Name</th>
                        <th>Tax Value</th>
                        <th>Tax For</th>
                         <th>Status</th>
                        <th>Created On</th>
                      </tr>
                    </thead>
                    <tbody>
                      {{--*/ $i = 1 /*--}}
                     @foreach($servicetaxAray as $servicetaxAray)
                      <tr>
                        <td><span>{{$i}} </span>
                          @if($servicetaxAray->country_name) 
                          {{$servicetaxAray->country_name}}
                          @else
                          {{$servicetaxAray->event_id}}
                          @endif</td>
                     
                        <td>{{$servicetaxAray->service_charge_name}}</td>
                        <td>{{$servicetaxAray->rate_of_intrest}}</td>
                        @if($servicetaxAray->type==1)
                        <td>For Country</td>
                        @else
                        <td>For Event</td>
                        @endif
                        <td>{{$servicetaxAray->status}}</td>
                         <td>{{$servicetaxAray->created_at}}</td>
                      </tr>
                      {{--*/ $i++ /*--}}
                     @endforeach

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
         @endif