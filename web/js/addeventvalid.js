$(document).ready(function(){	
 $("#addressflddiv2").hide();
$('#us3').locationpicker({				
				
				location: {latitude: 0, longitude: 0},
			  
				inputBinding: {
					latitudeInput: $('#lat'),
					longitudeInput: $('#lng'),
				 //   radiusInput: $('#us3-radius'),
					locationNameInput: $('#geocomplete')
				},
				enableAutocomplete: true,
				onchanged: function (currentLocation, radius, isMarkerDropped) {
					$("#addressflddiv2").show();
					$("#plocationdiv").hide();
					var addressComponents = $(this).locationpicker('map').location.addressComponents;
					updateControls(addressComponents);
				},
					oninitialized: function(component) {
					
                   $("#addressflddiv2").css('visibility', 'visible');
					var addressComponents = $(component).locationpicker('map').location.addressComponents;
					updateControls(addressComponents);
				}
				//alert(locationNameInput);
			});
jQuery.validator.setDefaults({
   ignoreTitle: true
});
			
$("#eventadd-form").validate({
			rules: {
				        "title": {                            
                            lettersspecialonly: false
                        },
                        "pincode": {                            
                            number: true
                        },
						"contact_website_url": {                            
                            url: true
                        },
						"facebook_link": {                            
                            url: true
                        },
						"twitter_link": {                            
                            url: true
                        },
						"google_link": {                            
                            url: true
                        },
						"linkedin_link": {                            
                            url: true
                        }
						,
						"thirdpartylink": {                            
                            url: true
                        }
                        ,
						"youtube_link": {                            
                            url: true
                        },
                        "support_mobile": {							
							lettersspecialonly: true
						},
						"support_email": {							
							email: true
						},
						"event_image": {
								 accept: "jpg|png|jpeg|pjpeg|gif"
										
									}
						

                    },
                    messages: {
                        "pincode": {                           
                            number: "Pincode number must contain digits only"
                        },
						"contact_website_url": {                           
                            url: "Please enter valid url"
                        },
						"facebook_link": {                           
                            url: "Please enter valid url"
                        },
						"twitter_link": {                           
                            url: "Please enter valid url"
                        },
						"google_link": {                           
                            url: "Please enter valid url"
                        },
						"linkedin_link": {                           
                            url: "Please enter valid url"
                        },
						"thirdpartylink": {                           
                            url: "Please enter valid url"
                        },
						"youtube_link": {                           
                            url: "Please enter valid url"
                        },
						"support_mobile": {                           
                            url: "Please enter valid mobile number"
                        }
                    }
                
		});

        $('.holiday_name').each(function() {
		    $(this).rules('add', "lettersspecialonly");
		});

		$('.show_name').each(function() {
		    $(this).rules('add', "lettersspecialonly");
		});
		//$("#evtenddate").rules('add', { greaterThan: "#evtstartdate" });
		$("#keyword").rules('add', { lettersspecialonly: "#keyword" });

		  $('#password_required').click(function() {
			 if($(this).is(":checked")) {
			   $("#evtpassword").show();
			 } else {
				$("#evtpassword").hide();
			 }
		});

		$('#showsciallink').click(function() {
			 // if($(this).is(":checked")) {
			 //   $("#sociallink").show();
			 // } else {
				// $("#sociallink").hide();
			 // }
		});
});

function initialize(lat,lng,vanue){
			 var myLatlng = new google.maps.LatLng(lat,lng);
			 var myOptions = {
				 zoom: 16,
				 center: myLatlng,
				 mapTypeId: google.maps.MapTypeId.TERRAIN 
				 }
			  map = new google.maps.Map(document.getElementById("us3"), myOptions);
			  var marker = new google.maps.Marker({
				  position: myLatlng,				
				  map: map,			  
			  label: vanue
			 });
		} 	

		function updateControls(addressComponents) {

			$('#formatted_address').val(addressComponents.addressLine1);
			$('#locality').val(addressComponents.city);
			$('#administrative_area_level_1').val(addressComponents.stateOrProvince);
			$('#postal_code').val(addressComponents.postalCode);
			$('#country').val(addressComponents.country);
			var mapurl = "https://www.google.com/maps?q="+$("#lat").val()+","+$("#lng").val();
			$('#mapurl').val(mapurl);
			$('#venue_name').val(addressComponents.vanue);
			$('#geocomplete').val('');
			initialize($("#lat").val(),$("#lng").val(),addressComponents.vanue);
			getTimeUsingLatLng($("#lat").val(),$("#lng").val());
		}

		function getTimeUsingLatLng(lat,lng){  
		
			var times_Stamp = (Math.round((new Date().getTime())/1000)).toString(); 

			$.ajax({
			url:"https://maps.googleapis.com/maps/api/timezone/json?location=" + lat + "," + lng + "&timestamp=" + times_Stamp,
			cache: false,
			type: "POST",
			async: false,
			}).done(function(response){

			

			if(response.timeZoneId=="Asia/Calcutta")
					response.timeZoneId="Asia/Kolkata";

			var tmz;
				$.each(timezoneData, function(i, item) { 
					if(item.timezone==response.timeZoneId) {
						tmz = item.id;							
					}
			    })
				$('select#timeZone option').each(function () {
					if ($(this).val() == tmz) {
					this.selected = true;       
					return;
				} });
              			   		    
			});
		

					
		}


