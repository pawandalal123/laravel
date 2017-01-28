$(document).ready(function(){
	
	 var counter =1;
	$( "#free" ).click(function() {
		
		var settingvar = 'setting_'+counter;
		
		$("div#ticket_generate").append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  
		
			$("<div>").attr("id","free_ticket"+counter).addClass('ticketwrap').append(   // Create <form> tag and appending in html div form1.
			$("<input/>", {type:'text', id:'freeticket_box'+counter, name:'ticket['+counter+'][Free][ticket_name]',required:'required', placeholder:'Ticket Name','class':'form-controlset'}),  // Creating input element with attribute
			$("<input/>", {type:'text', id:'freequantity_box'+counter, name:'ticket['+counter+'][Free][ticket_quantity]',required:'required', placeholder:'Quantity Available','class':'form-controlset'}),
			$("<span/>").text("Free"),
			$("<a />").attr('href','javascript:void(0)').attr('id',settingvar).addClass('ticketsetting').text('Setting'),
			$("<a />").attr('href','javascript:void(0)').attr('id','delete_'+counter).addClass('freeticketdelete').text('delete')			
			),
			$("<div>").attr("id","freeticket_setting"+counter).append( )
		  )

		
         counter++;
	 });	   
	

		$( "#paid" ).click(function() {
		var settingvar = 'setting_'+counter;
		
		$("div#ticket_generate").append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  
		
			$("<div>").attr("id","paid_ticket"+counter).addClass('paidticketwrap').append(   // Create <form> tag and appending in html div form1.
			$("<input/>", {type:'text', id:'paidticket_box'+counter, name:'ticket['+counter+'][Paid][ticket_name]',required:'required', placeholder:'Ticket Name','class':'form-controlset'}),  // Creating input element with attribute
			$("<input/>", {type:'text', id:'paidquantity_box'+counter, name:'ticket['+counter+'][Paid][ticket_quantity]',required:'required', placeholder:'Quantity Available','class':'form-controlset'}),
			$("<input/>", {type:'text', id:'price_box'+counter,  name:'ticket['+counter+'][Paid][ticket_price]',required:'required', placeholder:'15.00','class':'form-controlset',title:"Set your ticket price. (You'll select payout options later on the Manage page)"}),
			$("<a />").attr('href','javascript:void(0)').attr('id',settingvar).addClass('paidticketsetting').text('Setting'),
			$("<a />").attr('href','javascript:void(0)').attr('id','delete_'+counter).addClass('paidticketdelete').text('delete'),
			$("<div>").attr("id","paidticket_currency"+counter).append( )
			),			
			$("<div>").attr("id","paidticket_setting"+counter).append( )
		  )
		
	     //$(document).find('#paidticket_currency').empty();  

		var arr = [
		{val : 0, text: 'PLease select country'},
		{val : 1, text: 'India'}
		];

		var sel = $('<select id="currency_country" required="required" name="ticket['+counter+'][Paid][currency_country]" class="form-controlset">').appendTo('#paidticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
        
	        
		var arr = [
		{val : 0, text: 'PLease select Currency'},
		{val : 1, text: 'INR'}
		];

		var sel = $('<select id="test" class="form-controlset" required="required" name="ticket['+counter+'][Paid][currency]">').appendTo('#paidticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
		  
         counter++;
	 });	
	

   $( "#donation" ).click(function() {
		var settingvar = 'setting_'+counter;
		
		$("div#ticket_generate").append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  
		
			$("<div>").attr("id","donation_ticket"+counter).addClass('donationticketwrap').append(   // Create <form> tag and appending in html div form1.
			$("<input/>", {type:'text', id:'donationticket_box'+counter, name:'ticket['+counter+'][Donation][ticket_name]',required:'required', placeholder:'Ticket Name','class':'form-controlset'}),  // Creating input element with attribute
			$("<input/>", {type:'text', id:'donationquantity_box'+counter, name:'ticket['+counter+'][Donation][ticket_quantity]',required:'required', placeholder:'Quantity Available','class':'form-controlset'}),
			$("<span/>").text("Donation"),
			$("<a />").attr('href','javascript:void(0)').attr('id',settingvar).addClass('donationtticketsetting').text('Setting'),
			$("<a />").attr('href','javascript:void(0)').attr('id','delete_'+counter).addClass('donationticketdelete').text('delete'),
			$("<div>").attr("id","donationticket_currency"+counter).append( )
			),
			
			$("<div>").attr("id","donationticket_setting"+counter).append( )
		  )

   $(document).find('#donationticket_currency'+counter).empty();  

		var arr = [
		{val : 0, text: 'PLease select country'},
		{val : 1, text: 'India'}
		];

		var sel = $('<select id="currency_country" required="required" name="ticket['+counter+'][Donation][currency_country]" class="form-controlset">').appendTo('#donationticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
        
	        
		var arr = [
		{val : 0, text: 'PLease select Currency'},
		{val : 1, text: "INR"}
		];

		var sel = $('<select id="test" class="form-controlset" required="required" name="ticket['+counter+'][Donation][currency]">').appendTo('#donationticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		

         counter++;
	 });	 

 
	
})

$(document).on("click",".paidticketsetting", function(e){ 

var ticketsettingid =  $(this).attr("id");  
 var fullDate = new Date()
settingcounters = ticketsettingid.split('_');
settingcounter = settingcounters[1];
$(document).find('#paidticket_setting'+settingcounter).empty();  

/* if($('#paidticketset_wrap').length==0){*/
	
	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;

	if($("form input[name='start_date_time']").val().length > 0){

	   var endtevent = $("form input[name='start_date_time']").val();
	} else {
	   var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	}
	
			 $("#paidticket_setting"+settingcounter).append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  		    

					$("<div>").attr("id","paidticketset_wrap").append(   // Create <form> tag and appending in html div form1.
					$("<div>").attr("id","row fieldgroup").append(
						$("<p/>").text("SETTING"),
						$("<textarea/>", {rows:'5px', cols:'27px', type:'text',required:'required', id:'paidticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Paid][Setting][description]', placeholder:'Message'})
					),
					$("<div>").attr("id","row fieldgroup").append(
						$('<input />', { type: 'checkbox', id: 'paidticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Paid][Setting][show_des]' }),
						$('<label />', { 'for': 'cb', text: "Show ticket description on event page" })
					
					),
					
					$("<div>").attr("id","row fieldgroup").append(
						$("<p/>").text("Ticket sales start"),
						$("<input/>", {type:'text', id:'paidticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][startd]', value:startdevent,class:'ddcalendar'})
					//	$("<input/>", {type:'text', id:'paidticketsetsell_startt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][startt]', value:starttevent,class:'dtcalendar'})
					),
					$("<div>").attr("id","row fieldgroup").append(
						$("<p/>").text("Ticket sales end"),
						$("<input/>", {type:'text', id:'paidticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][endd]', value:endtevent,class:'ddcalendar'})
						//$("<input/>", {type:'text', id:'paidticketsetsell_endt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][endt]', value:endtevent,class:'dtcalendar'})
					),
					$("<div>").attr("id","row fieldgroup").append(
						$("<p/>").text("Tickets allowed per order minimum or maximum "),
						$("<input/>", {type:'text', id:'paidticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][min]', placeholder:'1'}),
						$("<input/>", {type:'text', id:'paidticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][max]', placeholder:'10'})
					)
				)
			)

 /* }  else {
       
	     $(document).find('#paidticket_setting'+settingcounter).empty();  
 }*/

})

$(document).on("click",".ticketsetting", function(e){ 
 
  var ticketsettingid =  $(this).attr("id");
  var fullDate = new Date()
  
  settingcounters = ticketsettingid.split('_');
  settingcounter = settingcounters[1];
  $(document).find('#freeticket_setting'+settingcounter).empty(); 
/* if($('#freeticketset_wrap').length==0){
    */
	
	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;

	if($("form input[name='start_date_time']").val().length > 0){

	   var endtevent = $("form input[name='start_date_time']").val();
	} else {
	   var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	}
	
	  $("#freeticket_setting"+settingcounter).append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  		    

				$("<div>").attr("id","freeticketset_wrap").append(   // Create <form> tag and appending in html div form1.
				$("<div>").attr("id","row fieldgroup").append(
					$("<p/>").text("SETTING"),
					$("<textarea/>", {rows:'5px', cols:'27px',required:'required',type:'text', id:'freeticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Free][Setting][description]', placeholder:'Message'})
				),
				$("<div>").attr("id","row fieldgroup").append(
					$('<input />', { type: 'checkbox', id: 'freeticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Free][Setting][show_des]'}),
					$('<label />', { 'for': 'cb', text: "Show ticket description on event page" })
				
				),
				
				$("<div>").attr("id","row fieldgroup").append(
					$("<p/>").text("Ticket sales start"),
					$("<input/>", {type:'text', id:'freeticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][startd]', value:startdevent,class:'ddcalendar'})
					//$("<input/>", {type:'text', id:'freeticketsetsell_startt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][startt]', value:starttevent,class:'dtcalendar'})
				),
				$("<div>").attr("id","row fieldgroup").append(
					$("<p/>").text("Ticket sales end"),
					$("<input/>", {type:'text', id:'freeticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][endd]', value:endtevent,class:'ddcalendar'})
					//$("<input/>", {type:'text', id:'freeticketsetsell_endt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][endt]', value:endtevent,class:'dtcalendar'})
				),
				$("<div>").attr("id","row fieldgroup").append(
					$("<p/>").text("Tickets allowed per order minimum or maximum "),
					$("<input/>", {type:'text', id:'freeticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][min]', placeholder:'1'}),
					$("<input/>", {type:'text', id:'freeticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][max]', placeholder:'10'})
				)
				)
			  )
   /* }  else {
       
	     $(document).find('#freeticket_setting'+settingcounter).empty();  
    }*/
    

})

$(document).on("click",".donationtticketsetting", function(e){ 
 
  var ticketsettingid =  $(this).attr("id");  
   var fullDate = new Date()
  
  settingcounters = ticketsettingid.split('_');
  settingcounter = settingcounters[1];
  $(document).find('#donationticket_setting'+settingcounter).empty();  
 /*if($('#donationticketset_wrap').length==0){
*/
	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();
	var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;

	if($("form input[name='start_date_time']").val().length > 0){
	   var endtevent = $("form input[name='start_date_time']").val();
	} else {
	   var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	}
   
  $("#donationticket_setting"+settingcounter).append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  		    

			$("<div>").attr("id","donationticketset_wrap").append(   // Create <form> tag and appending in html div form1.
			$("<div>").attr("id","row fieldgroup").append(
				$("<p/>").text("SETTING"),
				$("<textarea/>", {rows:'5px', cols:'27px', type:'text',required:'required', id:'donationticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Donation][Setting][description]', placeholder:'Message'})
			),
			$("<div>").attr("id","row fieldgroup").append(
				$('<input />', { type: 'checkbox', id: 'donationticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Donation][Setting][show_des]' }),
				$('<label />', { 'for': 'cb', text: "Show ticket description on event page" })
			
			),
			
			$("<div>").attr("id","row fieldgroup").append(
				$("<p/>").text("Ticket sales start"),
				$("<input/>", {type:'text', id:'donationticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][startd]', value:startdevent,class:'ddcalendar'})
				//$("<input/>", {type:'text', id:'donationticketsetsell_startt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][startt]', value:starttevent,class:'dtcalendar'})
			),
			$("<div>").attr("id","row fieldgroup").append(
				$("<p/>").text("Ticket sales end"),
				$("<input/>", {type:'text', id:'donationticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][endd]', value:endtevent,class:'ddcalendar'})
				//$("<input/>", {type:'text', id:'donationticketsetsell_endt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][endt]', value:endtevent,class:'dtcalendar'})
			),
			$("<div>").attr("id","row fieldgroup").append(
				$("<p/>").text("Tickets allowed per order minimum or maximum "),
				$("<input/>", {type:'text', id:'donationticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][min]', placeholder:'1'}),
				$("<input/>", {type:'text', id:'donationticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][max]', placeholder:'10'})
			)
			)
		  )
  /*  }  else {
       
	     $(document).find('#donationticket_setting'+settingcounter).empty();  
    }*/
    

})

$(document).on("click",".freeticketdelete", function(e){ 	
var ticketdelids =  $(this).attr("id");
ticketdelid = ticketdelids.split('_');
ticketdelcount = ticketdelid[1];
$(document).find('#freeticket_setting'+ticketdelcount).remove();  
$(document).find('#free_ticket'+ticketdelcount).remove();  

if($('#ticket_generate').length==0){
	 $("#ticket_currency").empty();
}
})

$(document).on("click",".paidticketdelete", function(e){ 	

var ticketdelids =  $(this).attr("id");
ticketdelid = ticketdelids.split('_');
ticketdelcount = ticketdelid[1];
$(document).find('#paidticket_setting'+ticketdelcount).remove();  
$(document).find('#paid_ticket'+ticketdelcount).remove();  

if($(document).find('.paidticketwrap').length==0 && $(document).find('.donationticketwrap').length==0){
	 $("#ticket_currency").empty();
}

})

$(document).on("click",".donationticketdelete", function(e){ 
var ticketdelids =  $(this).attr("id");
ticketdelid = ticketdelids.split('_');
ticketdelcount = ticketdelid[1];
$(document).find('#donationticket_setting'+ticketdelcount).remove();  
$(document).find('#donation_ticket'+ticketdelcount).remove();  

if($(document).find('.paidticketwrap').length==0 && $(document).find('.donationticketwrap').length==0){
	 $("#ticket_currency").empty();
}
})




















