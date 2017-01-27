<div class="row">
        <div class="adminpannel">
          <div class="col-sm-12">
            <div class="panel-heading">Manage Finance Module</div></div>
            @if($eventdata=='error')
            @else
          <div class="panel-body">
            <div class="col-sm-12">
              <div class="table-responsive" >
                <?php
                  switch ($eventdata->paymentstatus) {
                    case '1':
                      $critical='red';
                      break;
                      case '2':
                      $critical='orange';
                      break;
                      case '3':
                      $critical='blue';
                      break;
                    
                    default:
                       $critical='green';
                      break;
                  }
                  switch ($eventdata->eventtype) {
                    case '1':
                      $eventmode='Recurring ';
                      break;
                      break;
                      case '3':
                      $eventmode='Multi Vanue';
                      break;
                    default:
                       $eventmode='Normal';
                      break;
                  }
                  ?>
                <table class="report_table {{$critical}}_color mb10">
                  <thead>
                    <tr>
                      <th>Event ID</th>
                      <th>Event Name</th>
                      <th>Quantity</th>
                      <th>Starts</th>
                      <th>Ends</th>
                      <th>Published on</th>
                      <th>Type</th>
                      <th>Transaction</th>
                      <th>Total Amount</th>
                    </tr>
                  </thead>
                  <tbody class="report_table_back"> 
                    <tr class="report_table_back_head">
                      <td rowspan="2" style="background-color:#E0E0E0;">{{$eventdata->eventid}}</td>                     
                      <td>{{$eventdata->title}}</td>
                      <td>{{$eventdata->totalquantity}}</td>
                      <td>{{date('M j Y, h:i A',strtotime($eventdata->startdate))}}</td>
                      <td>{{date('M j Y, h:i A',strtotime($eventdata->enddate))}}</td>
                      <td>{{date('M j Y, h:i A',strtotime($eventdata->createdat))}}</td>
                      <td>{{$eventmode}}</td>
                      <td>{{$eventdata->totaltransaction}}</td>
                      <td>{{$eventdata->totalamount}}</td>
                    </tr>  
                    <tr>
                      <td colspan="8" style="padding: 0px!important;">
                        <table class="report_summery_box">
                          <tr class="report_head">
                            <td>Account Manager</td>
                            <td>Organizer</td>
                            <td>Payment Terms</td>
                            <td>Amount Payable</td>
                            <td>Paid</td>
                             <td>Adjustment</td>
                            <td>Pending</td>
                           
                            <!-- <td  rowspan="2"><a href="" class="btn btnmy btn-xs">View</a></td> -->
                          </tr>
                          <tr>
                            <td>
                              <span style="display: inline-block; margin-right: 5px;">{{$eventdata->assignto}}</span>
                         <!--      <span style="display: inline-block; margin-right: 5px;">gaurav.aggarwal@goeventz.com</span>
                              <span style="display: inline-block; margin-right: 5px;">7827821676</span> -->
                            </td>
                            <td>
                              <span style="display: inline-block; margin-right: 5px;">{{$eventdata->orgname}}</span>
                              <span style="display: inline-block; margin-right: 5px;">{{$eventdata->orgemail}}</span>
                              <span style="display: inline-block; margin-right: 5px;">{{$eventdata->orgmobile}}</span>
                            </td>
                            <td>Weekly</td>
                            <td>{{$eventdata->paybalamount}}</td>
                            <td>{{$eventdata->amountpaid}}</td>
                            <td>{{$eventdata->adjustment}}</td>
                            <td>{{round($eventdata->paybalamount -$eventdata->amountpaid-$eventdata->adjustment,2)}}</td>
                            
                          </tr>
                        </table>
                      </td>
                    </tr>                                          
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>