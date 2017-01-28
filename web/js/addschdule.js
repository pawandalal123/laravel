$( document ).ready(function() {
$("#schdule-form").validate({
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
						event_image: {
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
                        }
                    }
                
		});
});



  $(document).ready(function(){

  $onload={};
  $onload.type='create-recurring-event';
  $onload.typeValue=null;
  $onload.action='create';
  $onload.page='create-recurring-event';
  $onload.element=null; 
  $onload.referrer=document.referrer;
  $onload.page_url=window.location.href;
  track($onload);


});

  function initialize(lat,lng,vanue,counter){
			 var myLatlng = new google.maps.LatLng(lat,lng);
			 var myOptions = {
				 zoom: 16,
				 center: myLatlng,
				 mapTypeId: google.maps.MapTypeId.TERRAIN 
				 }
			  map = new google.maps.Map(document.getElementById("us3"+counter), myOptions);
			  var marker = new google.maps.Marker({
				  position: myLatlng,				
				  map: map,			  
			  label: vanue
			 });
		} 	


	function refreshjquery(counter){
       
		$('#us3'+counter).locationpicker({				
						
						location: {latitude: 0, longitude: 0},
					  
						inputBinding: {
							latitudeInput: $('#lat'+counter),
							longitudeInput: $('#lng'+counter),
						 //   radiusInput: $('#us3-radius'),
							locationNameInput: $('#geocomplete'+counter)
						},
						enableAutocomplete: true,
						onchanged: function (currentLocation, radius, isMarkerDropped) {
						//	$("#addressflddiv2").show();
							//$("#plocationdiv"+counter).hide();
                            
							var addressComponents = $(this).locationpicker('map').location.addressComponents;
							updateControls(addressComponents,counter);
						},
							oninitialized: function(component) {
							
		                  // $("#addressflddiv2").css('visibility', 'visible');
							var addressComponents = $(component).locationpicker('map').location.addressComponents;
							updateControls(addressComponents,counter);
						}
						//alert(locationNameInput);
					});
		jQuery.validator.setDefaults({
		   ignoreTitle: true
		});
}

function refreshjqueryedit(counter){

	    $('#us3'+counter).locationpicker({				
						
						location: {latitude: $("#lat"+counter).val(), longitude: $("#lng"+counter).val()},
					  
						inputBinding: {
							latitudeInput: $('#lat'+counter),
							longitudeInput: $('#lng'+counter),
						 //   radiusInput: $('#us3-radius'),
							locationNameInput: $('#geocomplete'+counter)
						},
						enableAutocomplete: true,
						onchanged: function (currentLocation, radius, isMarkerDropped) {
						//	$("#addressflddiv2").show();
							//$("#plocationdiv"+counter).hide();
                            
							var addressComponents = $(this).locationpicker('map').location.addressComponents;
						    updateControls(addressComponents,counter);
						},
							oninitialized: function(component) {
							
		                  // $("#addressflddiv2").css('visibility', 'visible');
							var addressComponents = $(component).locationpicker('map').location.addressComponents;
							//updateControls(addressComponents,counter);
							$('#geocomplete'+counter).val('');
						}
						//alert(locationNameInput);
					});
		jQuery.validator.setDefaults({
		   ignoreTitle: true
		});
        
		
}


		function updateControls(addressComponents,counter) {
			
			$('#address1'+counter).val(addressComponents.addressLine1);
			$('#address2'+counter).val(addressComponents.addressLine2);
			$('#city'+counter).val(addressComponents.city);
			$('#state'+counter).val(addressComponents.stateOrProvince);
			$('#pincode'+counter).val(addressComponents.postalCode);
			$('#country'+counter).val(addressComponents.country);
			var mapurl = "https://www.google.com/maps?q="+$("#lat"+counter).val()+","+$("#lng"+counter).val();
			$('#mapurl'+counter).val(mapurl);
			$('#venue_name'+counter).val(addressComponents.vanue);
			$('#geocomplete'+counter).val('');
			initialize($("#lat"+counter).val(),$("#lng"+counter).val(),addressComponents.vanue,counter)
		}


   
  




  


 