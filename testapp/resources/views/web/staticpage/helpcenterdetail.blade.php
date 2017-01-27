@extends('layout.default')
@section('content')
<!--#include file="includes/header.shtml"-->
<section class="top_h_bg">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8 text_position">
				<small class="spacepre"></small><span class="ge_main_head">GoEventz Help Center</span>
			</div>
		<!-- 	<div class="col-sm-4">
				<div class="ge_h_top_link text-right">
					<a href="">Create Events</a>
					<a href="">Help forum</a>
				</div>
			</div> -->
		</div>
		<div class="row">
			<div class="col-sm-12 ge_breadcrumbs_box">
				<small class="spacepre"></small>
				<ul class="ge_breadcrumbs">
					<?php echo $bradecum; ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<section class="contant_container">
	<div class="contant_overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-3 col-md-push-9 padl padr">
				<div class="ge_h_right">
					<h4 class="step_heading">{{$pagename}}</h4>
					@if(count($getallsubsubhead)>0)
					<ul class="ge_h_right_list">
						@foreach($getallsubsubhead as $getallsubsubhead)
						{{--*/ $addclass = '' /*--}}
						@if($subsubheadId==$getallsubsubhead['id'])
						{{--*/ $addclass = 'active' /*--}}
						@endif
						<li><a href="{{URL::to('help-center/'.$pram1.'/'.$getallsubsubhead['url'].'/'.$getallsubsubhead['id'])}}" class="{{$addclass}}">{{$getallsubsubhead['name']}}</a></li>
						@endforeach()
						
					</ul>
					@endif
				</div>
			</div>
			<div class="col-sm-12 col-md-9 col-md-pull-3">
				<div class="text_h_box">
					@if($dataArray!='')
					<h5 class="help_heahing_2">{{$dataArray['subsubheadname']}}</h5>
					<?php echo $dataArray['description'];?>
		           @endif
				</div>
			</div>
		</div>
	</div>
</section>
  <script type="application/javascript"   src="{{ URL::asset('web/js/lib/jquery-migrate-1.2.1.min.js')}}"></script>
<script type="application/javascript"   src="{{ URL::asset('web/js/lib/jquery.fancybox-1.3.4.pack.min.js')}}"></script>
<script type="text/javascript">
    $(function($){
    	$('.text_h_box img').addClass('fancybox');
        var addToAll = false;
        var gallery = true;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
    });
    $.noConflict();
</script>
@stop