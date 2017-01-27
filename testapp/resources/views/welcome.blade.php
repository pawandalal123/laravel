@extends('layout.adminlayout')
@section('content')
        <div class="container mt45">
            <div class="content">
                 <table class="table table-bordered table-hover" id="orders-table">
        <thead>
            <tr>
              
               <th>Date</th>
                <th>Event Name</th>
                <th>Order No</th>
                <th>Number of Tries</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Quantity</th>
                <th>Amount</th>
                 <th>Called</th>
                  <th>action</th>
                
            </tr>
        </thead>
    </table>
        <!-- <link href="https://datatables.yajrabox.com/css/demo.css" rel="stylesheet"> -->
            <script type="application/javascript"   src="{{ URL::asset('web/js/jquery-latest.js')}}"></script>
    <!-- <link href="https://datatables.yajrabox.com/css/datatables.bootstrap.css" rel="stylesheet"> -->

 <link rel="stylesheet" type="text/css" href="{{ URL::asset('web/js/data-tables/css/jquery.dataTables.min.css')}}" >
 <script type="application/javascript"   src="{{ URL::asset('web/js/data-tables/js/jquery.dataTables.min.js')}}"></script>
<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"> -->

     <script src="{{ URL::asset('web/js/lib/datatables.bootstrap.js')}}"></script>
<script type="text/javascript">
 $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        ajax: 
         {
            url: 'http://localhost/goeventz/GEsite/filterfetch/orders',
               data: function (d) 
              {
                  d.orderstatus ='pending';
                   // d.length =100;
                  //d.event_id=$('input[name=event_id]').val();
                  var dt_params = $('#orders-table').data('dt_params');
                  // Add dynamic parameters to the data object sent to the server
                  if(dt_params)
                  { 
                    //alert(dt_params);
                    $.extend(d, dt_params); 
                  }
              }
        },
        "order": [[ 0, "desc" ]],
       
        columns: [
             { data: 'date', name: 'date' },
            { data: 'eventname', name: 'eventname' },
            { data: 'orderid', name: 'orderid' },
            { data: 'attempts', name: 'attempts' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'mobile', name: 'mobile' },
            { data: 'quantity', name: 'quantity' },
            { data: 'amount', name: 'amount' },
            { data: 'action', name: 'action' },
            { data: 'called', name: 'called' }

            //  { data: 'date'},
            //  { data: 'orderid'},
            // { data: 'trns'},
            //  { data: 'eventname'},
            //  { data: 'city'},
            // { data: 'quantity'},
            // { data: 'amount' },
            // { data: 'currency' },
            // { data: 'name'},
            
            // { data: 'email'},
            // { data: 'mobile'},
            // { data: 'utm_source'},
            // { data: 'utm_medium'},
            // { data: 'utm_campaign'},
            // { data: 'organizer'},
            // { data: 'org_email'}

            ]
    });
    
</script>

            </div>
        </div>
   @stop
