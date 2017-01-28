 $( document ).ready(function() {
	var jsslotcounter = $("#jsslots").val();
	for(i=600;i<jsslotcounter;i++){
		
		// if($("#SlotscheduleEnd_"+i).val()!= '')
		// $("#SlotscheduleEnd_"+i).rules('add', { greaterThan:'#Slotschedulestartd_'+i});
	}
  
  var jstkt = $("#jstkt").val();

  for(i=800;i<jstkt;i++){

	$("#editquantity_"+i).rules("add", "number");
	$("#editPrice_"+i).rules("add", "number");
	$("#editPrice_"+i).rules("add", "amount");
    $("#tktticketsetsell_min_"+i).rules("add", "number");
    $("#tktticketsetsell_max_"+i).rules("add", "number");
	$("#tktticketsetsell_endd_"+i).rules('add', { greaterThan:'#tktticketsetsell_startd_'+i });
	$("#tktticketsetsell_max_"+i).rules('add', { greaterThandgt:'#tktticketsetsell_min_'+i });
	$("#min_donation_"+i).rules("add", "amount");
  }
});

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

	 
	   $( "#schdule-edit" ).submit();
	 

});

$(document).on("click",".deleteShduleTicket", function(e){ 

	var tktdeleteId =  $(this).attr("id");
	tktdeleteIds = tktdeleteId.split('_');
	var event_ID = $("#event_ID").val();
	 
				var cnf = confirm("Are you sure want to delete this ticket.");
				if (cnf == true) {				   
					 $.ajax({
						url: SITE_URL+"deleteticketschdule",
						type: "post",
						data: {'event_ID':event_ID,'id':tktdeleteIds[1],'divid':tktdeleteIds[2], '_token': $('input[name=_token]').val()},
						success: function(data){							
							 $("#paid_ticket"+tktdeleteIds[2]).hide();
						},error:function(){ 
								alert("error!!!!");
						 }
					});
				   $("#parenttktdiv"+settingcounter).hide();

				} else {
				   $("#tkt_setting"+settingcounter).show();
				}
});

$(document).on("click",".delslotedit", function(e){ 

	var tktdeleteId =  $(this).attr("id");
	tktdeleteIds = tktdeleteId.split('_');
	var event_ID = $("#event_ID").val();
	 
				var cnf = confirm("Are you sure want to delete this slot.");
				if (cnf == true) {				   
					 $.ajax({
						url: SITE_URL+"deleteslot",
						type: "post",
						data: {'event_ID':event_ID,'id':tktdeleteIds[1],'divid':tktdeleteIds[2], '_token': $('input[name=_token]').val()},
						success: function(data){							
							 $("#delslotBox"+tktdeleteIds[2]).hide();
						},error:function(){ 
								alert("error!!!!");
						 }
					});
				   $("#parenttktdiv"+settingcounter).hide();

				} else {
				   $("#tkt_setting"+settingcounter).show();
				}
});

$(document).on("click",".deleteshedit", function(e){ 

	var tktdeleteId =  $(this).attr("id");
	tktdeleteIds = tktdeleteId.split('_');
	var event_ID = $("#event_ID").val();
	 
				var cnf = confirm("Are you sure want to delete this slot.");
				if (cnf == true) {				   
					 $.ajax({
						url: SITE_URL+"deleteschdule",
						type: "post",
						data: {'event_ID':event_ID,'id':tktdeleteIds[1], '_token': $('input[name=_token]').val()},
						success: function(data){							
							 $("#showdivdelete"+tktdeleteIds[1]).hide();
						},error:function(){ 
								alert("error!!!!");
						 }
					});
				   $("#parenttktdiv"+settingcounter).hide();

				} else {
				   $("#tkt_setting"+settingcounter).show();
				}
});