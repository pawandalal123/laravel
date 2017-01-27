@extends('layout.default')
@section('content')
<section>
	<div class="container mt70">
		<div class="row">
			<div class="col-sm-12  text-center ">
				<h1>Something Went Wrong</h1>
				<!-- <h3 class="centerline"><span>Create Event</span></h3> -->

				<div style="background-color: #FFF; padding: 10px; font-size: 15px; " id="live">
	            Error occured during processing your request.  Please click here to go back to event <a href="{{URL::to('event/'.$url.'/'.$id)}}">{!! $title !!}</a>
	            Or <a href="{{URL::to('/contactus')}}" target="_blank"> Contact Us</a>
          </div>
			</div>
		</div>
	</div>
</section>


@stop
