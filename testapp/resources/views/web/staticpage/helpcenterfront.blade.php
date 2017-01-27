@extends('layout.default')
@section('content')
<!--#include file="includes/header.shtml"-->
<section class="top_h_bg pt10 mt45">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 text_position">
				<small class="spacepre"></small><span class="ge_main_head">Help Center</span>
			</div>
			<div class="col-sm-4">
				<div class="ge_h_top_link text-right">
					
				</div>
			</div>
		</div>
	</div>
</section>

<section class="contant_container pt15">
	<div class="contant_overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="text_h_box">
					<!-- <h5 class="help_heahing_2">Settings</h5> -->
					<div class="row">
						@if(count($mainArray)>0)
						@foreach($mainArray as $pagename=>$pagesubhead)
						<div class="col-sm-6 col-md-4">
							<div class="sub_heading_box">
								<h4 class="settings_sub_heading">{{$pagename}}</h4>
								@if(count($pagesubhead)>0)
								<ul class="help_left_list">
									@foreach($pagesubhead as $pagesubhead)
									<li><a href="{{URL::to('help-center/'.$pagesubhead['urlname'].'/'.$pagesubhead['id'])}}">
										{{$pagesubhead['displayname']}}</a></li>
									@endforeach
								</ul>
								@endif()
							</div>
						</div>
						@endforeach()
						@endif
					
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<!--#include file="includes/topfooter.shtml"-->
@stop