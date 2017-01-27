@extends('layout.defaultdashboard')
@section('content')

{!! Form::open(['url' => 'storecoupon/', 'method' => 'post','novalidate'=>'novalidate','id'=>'coupon-form','enctype'=>'multipart/form-data']) !!}
<section>
	
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2 text-center mt45 createhaeding">
				<h2>Create Coupon</h2>
				<!-- <h3 class="centerline"><span>Create Event</span></h3> -->
			</div>
		</div>
	
</section>
<section>
	
						<div class="row">
							<div class="col-sm-8 col-sm-offset-2">
								<div class="row CreateEventform">
									{!! Form::hidden('event_id',$event->id,array('class' => 'form-control','id'=>'event_id')); !!}
									{!! Form::hidden('coupontype',1,array('class' => 'form-control','id'=>'coupontype')); !!}
									<div class="col-sm-12" id="paddingcls">
									    <a href="#" id="bulkcreate" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Bulk Create</a>
									    &nbsp;&nbsp;&nbsp;<a href="#" id="normalcoupon" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Normal Coupon Code</a>
										<h3 class="HeadLine">Coupon Details</h3>
										 
									</div>
									<div class="col-sm-12">
										
										  <!--<div class="form-group">
										    <label for="tid">Ticket</label>
											{!! Form::select('ticket_id', $ticketData, null, ['class' => 'form-control']); !!}
										    
										  </div>-->
										  <div class="form-group" id="">
										    <label for="">Coupon Name</label>
											{!! Form::text('name', null,array('class' => 'form-control','placeholder'=>"Coupon Name",'id'=>'','required'=>'required','maxlength'=>'70')); !!} 
										  </div>
										  <div class="form-group" id="divcouponcode">
										    <label for="locid">Coupon Code</label>
											{!! Form::text('code', null,array('class' => 'form-control','placeholder'=>"Coupon Code",'id'=>'couponcode','required'=>'required','maxlength'=>'70')); !!} 
										  </div>
										  <div class="form-group" id="divprefix">
										    <label for="locid">Prefix Code</label>
											{!! Form::text('prefixcode', null,array('class' => 'form-control','placeholder'=>"Prefix Code",'id'=>'','maxlength'=>'4')); !!} 
										  </div>
										  <div class="form-group" id="">
										    <label for="locid">Start Date</label>
											{!! Form::text('start_date', date("Y-m-d H:i",strtotime('+6 hours')),array('class' => 'form-control ddcalendar','id'=>'couponstartdate','required'=>'required')); !!}
										  </div>
										   <div class="form-group" id="">
										    <label for="locid">End Date</label>
											{!! Form::text('end_date', date("Y-m-d H:i",strtotime('+9 hours')),array('class' => 'form-control ddcalendar ','placeholder'=>'','id'=>'couponenddate','required'=>'required')); !!}	
										  </div>
										  <div class="form-group" id="">
										    <label for="locid">Coupon Type</label>
											{!! Form::radio('mode', '1',true,['required' => 'required']); !!} Fix Price:	
											{!! Form::radio('mode', '2',true,['required' => 'required']); !!} Percentage Price:	
										  </div>
										   <div class="form-group" id="">
										    <label for="locid">Amount</label>
											{!! Form::text('amount', null,array('class' => 'form-control','placeholder'=>'Price','id'=>'couponprice','required'=>'required')); !!}	
										  </div>
										   <div class="form-group" id="">
										    <label for="locid">Quantity</label>
											{!! Form::text('quantity', null,array('class' => 'form-control','placeholder'=>'quantity','id'=>'couponquantity','required'=>'required')); !!}	
										  </div>
										  <div class="form-group" id="divbulkmessage">
										    <label for="locid">Bulk Generated Coupon Code </label>
											{!! Form::text('bulkmessage', null,array('class' => 'form-control','placeholder'=>'quantity In bulk','id'=>'bulkquantity','required'=>'required')); !!}	
										  </div>
										   <div class="form-group" id="">
										    <label for="locid">Ticket </label>
										     @foreach($ticketData as $ticketDatas)
                                                 {!! Form::checkbox("tktdata[]", $ticketDatas->id, 0) !!} {!! $ticketDatas->name !!}
										   @endforeach
										    
												
										  </div>

										 
								</div>
							</div>
						</div>
					
			        

					<!-- ///footer button start//// -->
					<div class="col-sm-12 text-center mt30 footerbutton">
						<!--{!! Form::submit('Save',array('class'=>'btn btn-primary btnmy')) !!}	-->
						<input  name="Save" id="saveevent" type="submit" class="btn btn-primary btnsize" value="Save">
						
					</div>
					<!-- ///footer button end//// -->
					
				</div>						
			</div>
		</div>
	
<br><br>
</section>
{!! Form::close() !!}

<script type="application/javascript"   src="{{ URL::asset('web/js/createcoupon.js')}}"></script>

@stop


 