@extends(isset($previewfor) && $previewfor=='admin' ? 'layout.userdefault' : 'layout.seconddefault')
@section('content')
 @include('includes.web.orgsummary')
<!-- /////////////////////// -->
<section>
	<div class="container textreplace">
		<div class="row mt15">
			 @include('includes.web.eventbox3list')
		</div>
		@if($_ENV['CUSTOM_USER_ID_ADE']==$detailsDisplay->id)
	    <div>
	 	<div class="row mt20">
              <div class="col-sm-12">
                <h3 class="HeadLine"><span>Buzz #ADEMumbai</span></h3>
              </div>
             <div class="row mt15">
             <iframe src="http://eventifier.com/event/ADEGSMumbai/popular?full_embed=true" width="100%" height="605" scrolling="no" frameborder="0"></iframe>
              <script type="text/javascript" src="https://s3.amazonaws.com/eventify_static/media/js/eventifierResizer.min.js"></script>
              <script type="text/javascript" src="https://s3.amazonaws.com/eventify_static/media/js/Resizeredit.js"></script>
          </div>
        </div>
	@endif
	</div>
</section>
	</div>
@stop