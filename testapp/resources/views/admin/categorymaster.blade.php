@extends('layout.adminlayout')
@section('content')

  <div class="content">
            
    <div class="breadLine">

    <ul class="breadcrumb">
    <li><a href="#">Admin</a> <span class="divider">></span></li>                
    <li class="active">Articles</li>
    </ul>
    </div>
    <div class="workplace">
    <div class="page-header">
    <h1>{{$type=='' ? 'Category' :ucwords($type)}} Management</h1>
    </div> 
@if($type=='subcategory')
@include('includes.admin.subcat')
@else
@include('includes.admin.cat')
@endif
 </div>
</div> 
@stop



