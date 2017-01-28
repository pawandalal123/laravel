$(document).ready(function(){

jQuery.validator.setDefaults({
   ignoreTitle: true
});

$("#event_edit").validate({
			rules: {
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
                        },
						"thirdpartylink": {                            
                            url: true
                        },
						"youtube_link": {                            
                            url: true
                        },
						event_image: {
									    accept: "jpg|png|jpeg|pjpeg|gif"
										
									}
                    },
                    messages: {
                        "pincode": {                           
                            minlength: "Pincode number must contain digits only"
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
	
$("#savedata").click(function() {
		// if($("#venue_name").val()=="")
		// 	//$("#venue_name").val('Null');
		// if($("#formatted_address").val()=="")
		// 	$("#formatted_address").val('Null');
		// if($("#locality").val()=="")
		// 	$("#locality").val('Null');
		// if($("#administrative_area_level_1").val()=="")
		// 	$("#administrative_area_level_1").val('Null');
		// if($("#postal_code").val()=="")
		// 	$("#postal_code").val('0');
		// if($("#country").val()=="")
		// 	$("#country").val('Null');
		// if($("#description").val()=="")
		// 	$("#description").val('Null');

	 
	   $( "#event_edit" ).submit();
	 

});

         $("#tktevtenddate").rules('add', { greaterThan: "#tktevtstartdate" });

			

        $("#event_url").rules('add', { atleatOneAlphaNum: "#event_url" });
        
        $('.holiday_name').each(function() {
		    $(this).rules('add', "lettersspecialonly");
		});

		$('.show_name').each(function() {
		    $(this).rules('add', "lettersspecialonly");
		});

		
		
		$("#keyword").rules('add', { lettersspecialonly: "#keyword" });
		var cntc_counter = $("input[name=cntc_counter]").val();
		splitcounters = cntc_counter.split('_');
			for (i = 1; i < splitcounters[0]; i++) { 
                $("#min_donation_"+i).rules("add", "amount");
				$("#tktquantity_box_"+i).rules("add", "number");
				$("#tktticketsetsell_endd_"+i).rules('add', { greaterThan:'#tktticketsetsell_startd_'+i });
		        $("#tktticketsetsell_max_"+i).rules('add', { greaterThandgt:'#tktticketsetsell_min_'+i });
			
			}
			
			for (i = 1; i < splitcounters[1]; i++) { 

				$("#tktprice_box_"+i).rules("add", "number");
				$("#tktprice_box_"+i).rules("add", "amount");
		
			}
		
		$('#us3').locationpicker({
        location: {latitude: $("#lat").val(), longitude: $("#lng").val()},
      
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
			var addressComponents = $(component).locationpicker('map').location.addressComponents;
			updateControls2(addressComponents);
		}
		//alert(locationNameInput);
    });

	function updateControls(addressComponents) {
		
		
		 $('#formatted_address').val($("#geocomplete").val());	  
		$('#locality').val(addressComponents.city);
		$('#administrative_area_level_1').val(addressComponents.stateOrProvince);
		$('#postal_code').val(addressComponents.postalCode);
		$('#country').val(addressComponents.country);
		var mapurl = "https://www.google.com/maps?q="+$("#lat").val()+","+$("#lng").val();
		$('#mapurl').val(mapurl);
		$('#geocomplete').val('');
		$('#venue_name').val(addressComponents.vanue);
		initialize($("#lat").val(),$("#lng").val());

	}

	function updateControls2(addressComponents) {
		
		var mapurl = "https://www.google.com/maps?q="+$("#lat").val()+","+$("#lng").val();
		$('#mapurl').val(mapurl);
		$('#geocomplete').val('');
		initialize($("#lat").val(),$("#lng").val());
	}

$("#savedata2").click(function() {
		// if($("#venue_name").val()=="")
		// 	$("#venue_name").val('Null');
		// if($("#formatted_address").val()=="")
		// 	$("#formatted_address").val('Null');
		// if($("#locality").val()=="")
		// 	$("#locality").val('Null');
		// if($("#administrative_area_level_1").val()=="")
		// 	$("#administrative_area_level_1").val('Null');
		// if($("#postal_code").val()=="")
		// 	$("#postal_code").val('0');
		// if($("#country").val()=="")
		// 	$("#country").val('Null');
		// if($("#description").val()=="")
		// 	$("#description").val('Null');

	 
	   $( "#event_edit" ).submit();
	 

});
	$(document).on("click",".tktsettingshowhide", function(e){ 
				var ticketsettingsh =  $(this).attr("id");
				var settingcounters = ticketsettingsh.split('_');
				var settingcounter = settingcounters[1];

				if($("#tkt_setting"+settingcounter).is(":visible")==false) {
			    	$("#tkt_setting"+settingcounter).show();
				} else {
				   $("#tkt_setting"+settingcounter).hide();
				}

		})

		$(document).on("click",".tktfreeticketdelete", function(e){ 
				var ticketsettingsh =  $(this).attr("id");
				var settingcounters = ticketsettingsh.split('_');
				var settingcounter = settingcounters[1];
                var event_ID = $("#event_ID").val();
				var cnf = confirm("Are you sure want to delete this ticket.");
				if (cnf == true) {				   
					 $.ajax({
						url: SITE_URL+"deleteticket",
						type: "post",
						data: {'event_ID':event_ID,'id':settingcounter, '_token': $('input[name=_token]').val()},
						success: function(data){							
							 $("#parenttktdiv"+settingcounter).hide();
						},error:function(){ 
								alert("error!!!!");
						 }
					});
				   $("#parenttktdiv"+settingcounter).hide();

				} else {
				   $("#tkt_setting"+settingcounter).show();
				}
				

		})


	if($('input[name=private]:checked').val() ==1){

		   $("#publicitychk").hide();

	   }  else  {

		   $("#publicitychk").show();

	   }

	   if($('input[name=thirdpartyflag]:checked').val() ==1){		
				$("#thirdpartylinkdiv").show();
				$("#thirdpartylinkvisible").hide();
			} else {
				$("#thirdpartylinkvisible").show();
				$("#thirdpartylinkdiv").hide();
				
			}


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


		$('.showDelete').click(function() {

				var ticketsettingsh =  $(this).attr("id");
				var settingcounters = ticketsettingsh.split('_');
				var settingcounter = settingcounters[1];
                var event_ID = $("#event_ID").val();
               
				var cnf = confirm("Are you sure want to delete this show.");
				if (cnf == true) {		
				       $('.ge-eventshow').loader('show');		   
					 $.ajax({
						url: SITE_URL+"deleteshow",
						type: "post",
						data: {'event_ID':event_ID,'id':settingcounter, '_token': $('input[name=_token]').val()},
						success: function(data){
						   $('.ge-eventshow').loader('hide');	
						   if(data = "Success"){
                              $("#showdivdelete"+settingcounter).remove();
                             // swal({ title: "Done", text: "Show has been deleted", type: "success" });
						   } else {
						   	  swal({ title: "Oops...", text: "Something went wrong. Please try again", type: "error" });                              
                              window.location.reload();
						   }						
							 
						},error:function(){ 
								alert("error!!!!");
								 $('.ge-eventshow').loader('hide');
						 }
					});
				   $("#parenttktdiv"+settingcounter).hide();

				} else {
				   $("#tkt_setting"+settingcounter).show();
				}
				

		})

       $('.delholidayedit').click(function() {

				var ticketsettingsh =  $(this).attr("id");
				var settingcounters = ticketsettingsh.split('_');
				var settingcounter = settingcounters[1];
                var event_ID = $("#event_ID").val();
               
				var cnf = confirm("Are you sure want to delete this holiday.");
				if (cnf == true) {		
				       $('.ge-eventshow').loader('show');		   
					 $.ajax({
						url: SITE_URL+"deleteshow",
						type: "post",
						data: {'event_ID':event_ID,'id':settingcounter, '_token': $('input[name=_token]').val()},
						success: function(data){
						   $('.ge-eventshow').loader('hide');	
						   if(data = "Success"){
                              $("#holidaydivdelete"+settingcounter).remove();
                             // swal({ title: "Done", text: "Show has been deleted", type: "success" });
						   } else {
						   	  //swal({ title: "Oops...", text: "Something went wrong. Please try again", type: "error" });                              
                              window.location.reload();
						   }						
							 
						},error:function(){ 
								alert("error!!!!");
								 $('.ge-eventshow').loader('hide');
						 }
					});
				   $("#parenttktdiv"+settingcounter).hide();

				} else {
				   $("#tkt_setting"+settingcounter).show();
				}
				

		})



 

 