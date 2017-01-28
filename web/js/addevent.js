$(document).ready(function(){	



		//$("#addressflddiv").hide();
		//$("#addressflddiv2").hide();
		//$("#onlinelocationdiv").hide();
		
		
		var fullDate = new Date()
		$('input[name=event_start_time]').val(fullDate.getHours() + "-" + fullDate.getMinutes());
		$('input[name=event_end_time]').val(fullDate.getHours() + "-" + fullDate.getMinutes());

		

		$("#resetlocation").click(function() {
				$('input[name=venue_location]').val('');
				$('input[name=address1]').val('');
				$('input[name=city]').val('');
				$('input[name=state]').val('');
				$('input[name=zipcode]').val('');
				$('input[name=country]').val('');
				$('input[name=country_code]').val('');
				$('input[name=location_latitude]').val('');
				$('input[name=location_langitude]').val('');
				$('input[name=url]').val('');
				$("#addressflddiv2").hide();
				$("#plocationdiv").show();
				$('input[name="onlineeventchk"]').prop('checked', false);
				  
		});

		$("#pastlocation").click(function(){

				 
				 if($("#pastlocation").html() =="Use Past Location") {

						$("#addressflddiv").show();
						$("#addressflddiv2").show();
						$("#locationaddress").hide();
						$("#plocationdiv").hide();
						$("#pastlocation").hide();
						$("#pastlocationdiv").show();
						$("#locationaddress").show();		
						$("#onlinelocationdiv").hide();

						
				 }  
                    $('input[name="onlineeventchk"]').prop('checked', false);
					$('input[name=venue_location]').val('');
					$('input[name=address1]').val('');
					$('input[name=city]').val('');
					$('input[name=state]').val('');
					$('input[name=zipcode]').val('');
					$('input[name=country]').val('');
					$('input[name=country_code]').val('');
					$('input[name=location_latitude]').val('');
					$('input[name=location_langitude]').val('');
					$('input[name=url]').val('');
					$("#geocomplete").trigger("geocode");
			});

			$("#onlineevent").click(function(){

			  if($("#onlineevent").html() =="Online Event") {
					   
						$('input[name=venue_location]').val('');
						$('input[name=address1]').val('');
						$('input[name=city]').val('');
						$('input[name=state]').val('');
						$('input[name=zipcode]').val('');
						$('input[name=country]').val('');
						$('input[name=country_code]').val('');
						$('input[name=location_latitude]').val('');
						$('input[name=location_langitude]').val('');
						$('input[name=url]').val('');
						$("#geocomplete").trigger("geocode");
						$('input[name="onlineeventchk"]').prop('checked', true);						                       
						$("#plocationdiv").hide();
						$("#pastlocationdiv").hide();
						$("#addressflddiv").hide();
						$("#addressflddiv2").hide();				
						$("#onlinelocationdiv").show();
						$("#pastlocation").hide();
						$("#onlineevent").hide();
						$("#locationaddress").show();
						
			  }

			});

			 $("#locationaddress").click(function(){
				  if($("#locationaddress").html() =="Enter Address") {

						$('input[name=venue_location]').val('');
						$('input[name=address1]').val('');
						$('input[name=city]').val('');
						$('input[name=state]').val('');
						$('input[name=zipcode]').val('');
						$('input[name=country]').val('');
						$('input[name=country_code]').val('');
						$('input[name=location_latitude]').val('');
						$('input[name=location_langitude]').val('');
						$('input[name=url]').val('');
						$("#geocomplete").trigger("geocode");
						$('input[name="onlineeventchk"]').prop('checked', false);

						$("#addressflddiv").hide();
						$("#addressflddiv2").show();
						$("#locationaddress").hide();
						$("#pastlocation").show();
						$("#onlineevent").show();
						$("#plocationdiv").show();
						$("#pastlocationdiv").hide();
						$("#onlinelocationdiv").hide();
					

								 
			  }

			 });



		$( "#geocomplete" ).focus(function() {
			
		});
         
      
			
		
        
		

	   $("#makeeventliv").click(function() {
	       $("#addressflddiv2").show();
       });
	   $("#makeeventliv2").click(function() {
	       $("#addressflddiv2").show();
       });
	    $("#makeeventliv3").click(function() {
	       $("#addressflddiv2").show();
       });
	    $("#makeeventliv4").click(function() {
	       $("#addressflddiv2").show();
       });
	   $("#saveevent").click(function() {
	       $("#addressflddiv2").hide();
       });
	   

		$("#locationfieldtype").change(function(){			
					var id = $("#locationfieldtype").val();	
					
					$.ajax({
						url: SITE_URL+"geteventlocation",
						type: "post",
						data: {'id':id, '_token': $('input[name=_token]').val()},
						success: function(data){
							
							console.log(data);
							var json_obj = $.parseJSON(data);//parse JSON					
							$('input[name=venue_name]').val(json_obj[0].venue_name);
							$("#geocomplete").trigger("geocode");
							$('input[name=address1]').val(json_obj[0].address1);
							$('input[name=city]').val(json_obj[0].city);
							$('input[name=state]').val(json_obj[0].state);
							$('input[name=pincode]').val(json_obj[0].pincode);
							$('input[name=country]').val(json_obj[0].country);
						//	$('input[name=country_code]').val(json_obj[0].country_code);
						   
							$('input[name=latitude]').val(json_obj[0].latitude);
							$('input[name=longitude]').val(json_obj[0].longitude);
							$('input[name=map_url]').val(json_obj[0].map_url);					
							//$('input[name=venue_location]').val(json_obj[0].venue_location);
							//$('input[name=venue_location]').val(json_obj[0].venue_location);					
							initialize(json_obj[0].latitude,json_obj[0].longitude);
						},error:function(){ 
								alert("error!!!!");
						}
					}); 
				 
				});
        
		
        
		
		$("input:radio[name=private]").click(function() {
			var value = $(this).val();
			if(value==2){		
				//$("#publicitychk").show();
			} else {
				$("#publicitychk").hide();
			}
			
		});

		$("#thirdpartyflag").click(function() {
			if($('input[name=thirdpartyflag]:checked').val() ==1){		
				$("#thirdpartylinkdiv").show();
				$("#thirdpartylinkvisible").hide();
			} else {
				$("#thirdpartylinkvisible").show();
				$("#thirdpartylinkdiv").hide();
				
			}
			
		});
      
		google.maps.event.trigger($("#us3")[0], 'resize');
	 
		
	
});

	

