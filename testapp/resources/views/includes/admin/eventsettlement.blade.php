 <div class="report_tab_content report_tab_content_bgcolor">
  <div class="row">
    <div class="col-sm-12">
    <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr class="active">
                      <th><span>S.No</span>
                         Event ID</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Refrence Number</th>
                        <th>Date</th>
                        <th>file</th>
                        <th>Comments</th>
                        <!-- <th>Create By</th> -->
                        <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                   {{--*/ $i = 1 /*--}}
                     @foreach($paymentlist as $paymentlist)
                      <tr>
                        <td><span>{{$i}} </span>{{$paymentlist['event_id']}}</td>
                        <td>{{$paymentlist['amount']}}</td>
                        <td>{{$paymentlist['paymode']}}</td>
                        <td>{{$paymentlist['refrence']}}</td>
                        <td>{{$paymentlist['created_at']}}</td>
                        <td>
                          @if($paymentlist['file']!='' && count($paymentlist['file'])>0 && $paymentlist['file']!='""')
                          {{--*/ $prooflist = json_decode($paymentlist['file']) /*--}}
                            @foreach ($prooflist as $imagename) 
                               <a href="{{URL::to('uplode/paymentdocs/'.$imagename)}}" target="_blank">Click to view</a>
                           @endforeach
                          @endif
                    </td>
                        <td>{{$paymentlist['comments']}}</td>
                         <!-- <td>{{$paymentlist['createby']}}</td> -->
                         <td><a href="{{URL::to('admin/eventpayment/'.$paymentlist['event_id'].'/makepayment?paymentid='.$paymentlist['id'])}}">Edit</a>
                        <a href="javascript:void(0);" onClick="paymentmessage({{$paymentlist['id']}})">Send Mail</a>
                        <a href="javascript:void(0);" onClick="paymentcaptured({{$paymentlist['event_id']}},{{$paymentlist['id']}})">Update Trnx</a></td>
                      </tr>
                      {{--*/ $i++ /*--}}
                     @endforeach                         
                  </tbody>
                </table>
              </div>
  </div>
  </div>
</div>
<script type="application/javascript"   src="{{URL::to('web/js/common.js')}}"></script>
<script type="application/javascript"   src="{{URL::to('web/js/timer.js')}}"></script>
<script type="application/javascript"   src="{{URL::to('web/js/jquery.colorbox.js')}}"></script>