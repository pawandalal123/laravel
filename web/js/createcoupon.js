$(document).ready(function(){	

$("#coupon-form").validate({
			rules: {
				        "code": {                            
                            lettersspecialonly: false
                        },
                        "quantity": {                            
                            number: true,
							min: 1
                        },
                        "bulkmessage": {                       
                            
							min: 1
                        },
						"amount":{
								 
								 min: 1,
								
							}
                    },
                    messages: {
                        "quantity": {                           
                            number: "Pincode number must contain digits only"
                        }
                    }
                
		});
		$("#couponenddate").rules('add', { greaterThan: "#couponstartdate" });
		$("#tocouponquantity").rules('add', { greaterThandgt: "#fromcouponquantity" });
        $("#couponprice").rules("add", "amount");

		jQuery.validator.addMethod("CheckBoxRequire", function(value, elem, param) {
		if($(".checkboxvalid:checkbox:checked").length > 0){
			return true;
		}else {
			return false;
		}
		},"You must select at least one!");
        
		$(".checkboxvalid").rules('add', { CheckBoxRequire: ".checkboxvalid" });

		$( ".bulkcreate" ).click(function() {
		    $('.formdisplay').show();
            $("#divprefix").show();
			$("#divbulkmessage").show();
			$("#divcouponcode").hide();
			$("#minBulQuantity").hide();
			$("#maxBulQuantity").hide();
			$("#divcouponcode").hide();
            $("#coupontype").val(2);
            $("#groupCouponCheckBox").show();
			$("#groupCouponRadio").hide();
			
		});

		$( ".groupCoupon" ).click(function() {
			$('.formdisplay').show();
		    
   //          $("#divprefix").show();
			// $("#divbulkmessage").show();
			// $("#divcouponcode").hide();
			// $("#minBulQuantity").hide();
			// $("#maxBulQuantity").hide();
			// $("#divcouponcode").hide();
			$("#minBulQuantity").show();
			$("#maxBulQuantity").show();
			$("#groupCouponCheckBox").hide();
			$("#groupCouponRadio").show();
            $("#coupontype").val(3);
			
		});

		$(".amountType").click(function() {   
			
           if($("input[name=mode]:checked").val() == 2) {
                $("#couponprice").val('');
			    $("#couponprice").attr('maxlength','2');
		   } else {
			    $("#couponprice").val('');
               $("#couponprice").attr('maxlength','7');
		   }
		});

		$( "#normalcouponchk" ).click(function() {
		   //console.log('testing'); 
		    $('.formdisplay').show();
            $("#divprefix").hide();
			$("#divbulkmessage").hide();
			 $("#minBulQuantity").hide();
			 $("#maxBulQuantity").hide();
			$("#divcouponcode").show();
			$("#divcouponcode").show();
            $("#coupontype").val(1);
            $("#groupCouponCheckBox").show();
			$("#groupCouponRadio").hide();
			
		});
		
	    $("#divbulkmessage").hide();
		$("#divprefix").hide();

       $(document).on("mouseover",".ddcalendar", function(e){ 
				$('.ddcalendar').datetimepicker({
					yearOffset:0,
					lang:'en',
					timepicker:true,
					format:'Y-m-d H:i',
					formatDate:'Y-m-d H:i',
					minDate:0,
					step:15
					
					//minDate:'-1970/01/2',
				   // maxDate:'+1970/01/2'
					//minDate:'-1970-01-02', // yesterday is minimum date
					//maxDate:'+2025-01-02' // and tommorow is maximum date calendar
				});
			});
 
});


