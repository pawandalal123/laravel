 <?php
$getPram='';
$url=$_SERVER['REQUEST_URI'];
$parsed=[];
$ticketidmatch='';
$dateselect='';
$status='all';
if(strpos($url, '?')!==false)
{
parse_str(substr($url, strpos($url, '?') + 1), $parsed);
if(array_key_exists('ticketid', $parsed))
{
  $ticketidmatch = $parsed['ticketid'];
}
if(array_key_exists('dateselect', $parsed))
{
  $dateselect = $parsed['dateselect'];
}
if(array_key_exists('dateselect', $parsed))
{
  $dateselect = $parsed['dateselect'];
}
if(array_key_exists('dateselect', $parsed))
{
  $dateselect = $parsed['dateselect'];
}
if(array_key_exists('status', $parsed))
{
  $status=$parsed['status'];
  unset($parsed['status']);
}
if(count($parsed)>0)
{
  $getPram=  http_build_query($parsed);
}

}
$class='active';
?>
 <div class="row mt15">
        <div class="col-sm-12">
        <div class="report_box">
            <ul class="admin_report_tab">
              <li class="<?php if($status=='all'){ echo $class;}?>"><a href="{{ URL::to('admin/eventpayment/'.$id.'?status=all') }}">All</a></li>
              @if($complete->reportData!='noorder')
              <li class="<?php if($status=='completed'){ echo $class;}?>"><a href="{{ URL::to('/admin/eventpayment/'.$id.'?status=completed') }}" >Online</a></li>
              @endif
              @if($offline->reportData!='noorder')
              <li class="<?php if($status=='offline'){ echo $class;}?>"><a href="{{ URL::to('/admin/eventpayment/'.$id.'?status=offline') }}">Offline</a></li>
               @endif
              @if($refund->reportData!='noorder')
              <li class="<?php if($status=='refunded'){ echo $class;}?>"><a href="{{URL::to('/admin/eventpayment/'.$id.'?status=refunded') }}">Refunded</a></li>
              @endif
              <!-- <li><a href="#">Collaborator</a></li> -->
            </ul>
               </div>
                  <div class="admin_report_tab_content">
                     @include('includes.partials.reportfilters')
                     @include('includes.partials.reportdisplayfinance')
          </div>
          </div>
        </div>
      </div>
           
 