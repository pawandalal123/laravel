@extends('layout.adminlayout')
@section('content')

  <div class="content">
            
            <div class="breadLine">

                <ul class="breadcrumb">
                    <li><a href="#">Admin</a> <span class="divider">></span></li>                
                    <li class="active">Location</li>
                </ul>
            </div>
            <div class="workplace">
                <div class="page-header">
                    <h1>{{$type=='' ? 'Country' :ucwords($type)}} Management</h1>
                </div> 
@if($type=='state')
@include('includes.admin.statelist')
@elseif($type=='city')
@include('includes.admin.citylist')
@else
@include('includes.admin.countrylist')
@endif
 </div>
</div> 
@stop



