@extends(isset($previewfor) && $previewfor=='admin' ? 'layout.userdefault' : 'layout.seconddefault')
@section('content')

 @include('includes.web.orgsummary')

<!-- /////////////////////// -->

<section>
	<div class="container textreplace">
		<div class="row mt15">
			@include('includes.web.eventbox2list')
		  
		</div>
	</div>
</section>


 @stop