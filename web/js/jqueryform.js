$(document).ready(function(){
	
	 var counter =1;
	$( "#free" ).click(function() {
		
		var settingvar = 'setting_'+counter;
		
		$("div#ticket_generate").append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  
		
				$("<div>").attr("id","free_ticket"+counter).addClass('ticketwrap').append(   
					
				    $("<div>").attr("class","row ticketwrapbox1 text-center").append(

						$("<div>").attr("class","col-sm-4").append(
							$("<input/>", {type:'text', id:'freeticket_box'+counter, name:'ticket['+counter+'][Free][ticket_name]',required:'required', placeholder:'Ticket Name','class':'form-controlset form-control'})
						),
						$("<div>").attr("class","col-sm-4").append(
						  $("<input/>", {type:'text', id:'freequantity_box'+counter, name:'ticket['+counter+'][Free][ticket_quantity]',required:'required', placeholder:'Quantity','class':'form-controlset form-control'})
						),
						$("<div>").attr("class","col-sm-4").append(
							$("<span/>").text("Free"),
							$("<a />").attr('href','javascript:void(0)').attr('id',settingvar).addClass('ticketsetting').html('<i class="fa fa-cog"></i> Settings'),
							$("<a />").attr('href','javascript:void(0)').attr('id','delete_'+counter).addClass('freeticketdelete').html('<i class="fa fa-trash-o"></i> Delete')			
						)
					)
				),
				$("<div>").attr("id","freeticket_setting"+counter).addClass('ticketbox').append( )
		 )
         
        $("#freequantity_box"+counter).rules("add", "number");		
		 generatefreeseting(counter)
         counter++;
	 });	   
	

		$( "#paid" ).click(function() {
		var settingvar = 'setting_'+counter;
		
		$("div#ticket_generate").append(			 
		
			$("<div>").attr("id","paid_ticket"+counter).addClass('paidticketwrap ge-ticketcreatebox mb10').append( 

               $("<div>").attr("class","row").append(

				  $("<div>").attr("class","col-sm-3 padr").append(
				  	 $("<div>").attr("class","form-group").append(

					   $("<input/>", {type:'text', id:'paidticket_box'+counter, name:'ticket['+counter+'][Paid][ticket_name]',required:'required', placeholder:'Ticket name','class':'form-control xyz',maxlength:75,title:"eg- early bird, General admission, couple entry etc"})
					 )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group").append(
						 $("<input/>", {type:'text', id:'paidquantity_box'+counter, name:'ticket['+counter+'][Paid][ticket_quantity]',required:'required', placeholder:'Quantity',maxlength:8,'class':'form-control xyz',title:"Quantity"})
				     )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group").append(

					 $("<input/>", {type:'text', id:'price_box'+counter,  name:'ticket['+counter+'][Paid][ticket_price]', placeholder:'Price','class':'form-control calculationPrice xyz',maxlength:12,title:"For free ticket enter 0, for donation leave it blank"})
				     
				     )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group currency_select").attr("id","paidticket_currency"+counter).append(

					
				     
				     )
				  ),
				  $("<div>").attr("class","col-sm-3 col-xs-6 text-right").append(

				  	$("<div>").attr("class","form-group clearfix ge-addsetting").append(
					   // $("<span/>").text("Paid"),
						$("<a />").attr('href','javascript:void(0)').attr('data-toggle',"collapse").attr('aria-controls',"collapseExample").attr('aria-expanded',"false").attr('id',settingvar).addClass('paidticketsetting').html('<i class="fa fa-cog"></i>&nbsp;Setting'),
						$("<a />").attr('href','javascript:void(0)').attr('data-toggle',"collapse").attr('aria-controls',"collapseExample").attr('aria-expanded',"false").attr('id','delete_'+counter).addClass('paidticketdelete').html('<i class="fa fa-trash-o"></i>&nbsp;Delete')
				    )
				  )
			 ),
			 $("<div>").attr("id","paidticket_setting"+counter).addClass('ticketbox2').append( )			 
			)	
			
		)
        
		
	     //$(document).find('#paidticket_currency').empty();  

		/*var arr = [
		{val : 0, text: 'country'},
		{val : 1, text: 'India'}
		];

		var sel = $('<select id="currency_country" required="required" name="ticket['+counter+'][Paid][currency_country]" class="form-controlset">').appendTo('#paidticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
        */

        

		var sel = $('<select class="form-controlset calculationCurrency form-control" required="required" id="ticketCurrency_'+counter+'" name="ticket['+counter+'][Paid][currency]">').appendTo('#paidticket_currency'+counter);
		
		$.each(curencyData, function(k, v) {
	    if(k==1)		
		  sel.append($("<option selected>").attr('value',k).text(v));
		else
		  sel.append($("<option>").attr('value',k).text(v));
		});		
        
		 $("#paidquantity_box"+counter).rules("add", "number");
		 $("#price_box"+counter).rules("add", "number");
		 $("#price_box"+counter).rules("add", "amount");
		 
		 generatepaidseting(counter)
		  
         counter++;
	 });	
	

   $( "#donation" ).click(function() {
		var settingvar = 'setting_'+counter;
		
		$("div#ticket_generate").append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  
		
			$("<div>").attr("id","donation_ticket"+counter).addClass('donationticketwrap').append(   

               $("<div>").attr("class","row ticketwrapbox1 text-center").append(

                   $("<div>").attr("class","col-sm-4").append(
						$("<input/>", {type:'text', id:'donationticket_box'+counter, name:'ticket['+counter+'][Donation][ticket_name]',required:'required', placeholder:'Ticket Name','class':'form-controlset form-control'})
				   ),
                   $("<div>").attr("class","col-sm-3").append(
						$("<input/>", {type:'text', id:'donationquantity_box'+counter, name:'ticket['+counter+'][Donation][ticket_quantity]',required:'required', placeholder:'Quantity','class':'form-controlset form-control'})
				   ),
				   $("<div>").attr("class","col-sm-5").append(
						$("<span/>").text("Donation"),
						$("<a />").attr('href','javascript:void(0)').attr('id',settingvar).addClass('donationtticketsetting').html('<i class="fa fa-cog"></i>Setting'),
						$("<a />").attr('href','javascript:void(0)').attr('id','delete_'+counter).addClass('donationticketdelete').html('<i class="fa fa-trash-o"></i>Delete')
				   )
			  ),
			  $("<div>").addClass('row ticketwrapbox1').append(

			      $("<div>").attr("id","donationticket_currency"+counter).addClass('col-sm-4').append( )

			  )
			  ),
			
			$("<div>").attr("id","donationticket_setting"+counter).addClass('ticketbox').append( )
		  )

   $(document).find('#donationticket_currency'+counter).empty();  

		/*var arr = [
		{val : 0, text: 'Country'},
		{val : 1, text: 'India'}
		];

		var sel = $('<select id="currency_country" required="required" name="ticket['+counter+'][Donation][currency_country]" class="form-controlset">').appendTo('#donationticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
        */
	        
		var arr = [
		{val : 0, text: 'Currency'},
		{val : 1, text: "INR"}
		];

		var sel = $('<select id="test" class="form-controlset form-control" required="required" name="ticket['+counter+'][Donation][currency]">').appendTo('#donationticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
         
		 $("#donationquantity_box"+counter).rules("add", "number");		 
		 generatedonationseting(counter);
         counter++;
	 });	 

 
	
})

//$(document).on("click",".paidticketsetting", function(e){ 
function generatepaidseting(settingcounter){

/*var ticketsettingid =  $(this).attr("id");  

settingcounters = ticketsettingid.split('_');
settingcounter = settingcounters[1];
$(document).find('#paidticket_setting'+settingcounter).empty();  */
var fullDate = new Date()
/* if($('#paidticketset_wrap').length==0){*/
	
	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
    var endtevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();
	if($("form input[name='start_date_time']").val().length > 0){

	   var endtevent = $("form input[name='start_date_time']").val();
	} else {
	   var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	}
	
		if ($("#show_namefirst").length > 0){

			  $("#paidticket_setting"+settingcounter).append(			  		    

					$("<div>").attr("class","paidticketset_wrap ge-ticketsetting").attr("id","tickrtsetting2").append(   
	                     
	                  $("<div>").attr("class","row").append(

	                  	   $("<div>").attr("class","col-sm-12").append(
                                
                               $('<label />', { 'class': 'labeluppercase', text: "Special Pricing" }),
                               	   	   $("<div>").attr("class","specialpricebox").append(

                               	   	   	   $("<div>").attr("class","row").append(

                               	   	   	   	   $("<div>").attr("class","col-sm-4 col-xs-6 form-group").append(

                               	   	   	   	   	   $('<input/>').attr({ type: 'text',id:'special_price'+settingcounter,name:'ticket['+settingcounter+'][Paid][Setting][special_price]','placeholder':'Price'}).addClass("form-control")
                               	   	   	   	   	   		

                               	   	   	   	   	),
                               	   	   	   	   $("<div>").attr("class","col-sm-8 col-xs-6 form-group").append(

                               	   	   	   	   	   $("<div>").attr("class","ge-weeklypricbox mt5").append(

                               	   	   	   	   	   	    $("<span>").attr("class","uppercase").html('Applicable On Days:&nbsp;'),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(
                                                             
                                                             $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>Su",type:"button" }),                               	   	   	   	   	   	    	 	
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_su', value: "Sunday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    ),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(

                               	   	   	   	   	   	    	  $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>Mo",type:"button" }),
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_mu', value: "Monday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    ),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(

                               	   	   	   	   	   	    	  $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>Tu",type:"button" }),  	
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_tu', value: "Tuesday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    ),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(

                               	   	   	   	   	   	    	  $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>We",type:"button" }),  	
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_we', value: "Wednesday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    ),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(

                               	   	   	   	   	   	    	  $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>Th",type:"button" }), 	
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_th', value: "Thursday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    ),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(

                               	   	   	   	   	   	    	  $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>Fr",type:"button" }),   	
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_fr', value: "Friday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    ),
                               	   	   	   	   	   	    $("<span>").attr("class","button-checkbox").append(

                               	   	   	   	   	   	    	  $('<button/>', { class:"btn btn-sm","data-color":"success","style":"margin-right:3px;",html:"<i class='state-icon'></i>Sa",type:"button" }), 	
                               	   	   	   	   	   	    	 $('<input />', { type: 'checkbox', id: 'special_price_day_sa', value: "Saturday", name:'ticket['+settingcounter+'][Paid][Setting][days][]',class:"hidden" })

                               	   	   	   	   	   	    )
                               	   	   	   	   	   	),
                                                    $("<div>").attr("class","applicabledate mt5").append(

                                                    	$("<span>").attr("class","uppercase").html('Applicable On Date:&nbsp;'),
                                                        $("<div>").attr("class","xcombine").append(
                                                        	$('<input/>').attr({ type: 'text',id:'special_price_date_'+settingcounter,name:'ticket['+settingcounter+'][Paid][Setting][special_price_date]','placeholder':'Date'}).addClass("form-control dateticket"),
                                                        	$("<span>").attr("class","xcombinebtn calendar").html('<i class="fa fa-calendar"></i>')
                                                        )
                                                    ),
                                                    $("<div>").attr("class","col-sm-12 text-center").append(

                                                    	$("<div>").attr("class","selectdate").append(
                                                    		$("<span>").attr({"class":"uppercase","id":"spandateshow"+settingcounter}).html('')
                                                    	)

                                                    )


                               	   	   	   	   	)


                               	   	   	   	)

                               	   	 )  

                               ), 

	                           $("<div>").attr("class","col-sm-12 form-group").append(							
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Ticket Description" }),
											$("<textarea/>", {rows:'2', type:'text', id:'paidticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Paid][Setting][description]', placeholder:'Ticket description',maxlength:300,'class':'form-control'})									
										
										
							   ),
							    $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "Payment Gateway Fee" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(							
									 
								            
										
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][payment_gateway_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"paymentgatewaytrue_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][payment_gateway_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"paymentgatewayfalse_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })		
										
							  
							   ),
							   $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "GOEVENTZ FEE" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(			
									 
								            
										
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][goeventz_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"feeGoeventztrue_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][goeventz_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"feeGoeventzfalse_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })	
										
							  
							   ),
							   $("<div>").attr("class","col-sm-12 form-group ticket_loader ").attr("id","extracharge_"+settingcounter).append(
                                       
                                    $("<div>").attr("class","tex_box").append(  
                                    	
                                    )


							   ),
							   $("<div>").attr("class","col-sm-12 form-group").append(
							   							
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Open Price (minimum amount)" }),
											$("<input/>", {type:'text', id:'min_donation'+settingcounter, name:'ticket['+settingcounter+'][Paid][Setting][min_donation]', value:'0',maxlength:6,'class':'form-controlset form-control','style':'width:48%;'})								
										
										
							  
							   ),							   
							   $("<div>").attr("class","col-sm-4 mb5 ge-selectradio").append(
	                                       $('<label />', { 'class': 'labeluppercase', text: "Status","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][status]',value:'1','checked':true}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Enable","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][status]',value:'0'}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Disable" })						
								),	
								$("<div>").attr("class","col-sm-4 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Paid][Setting][show_des]',checked:true }),
											$('<label />', { 'class': 'labeluppercase', text: "Show ticket on event page",style:"padding-left:5px;" })
										
								),
								$("<div>").attr("class","col-sm-4 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_sold_out'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Paid][Setting][sold_out]',checked:false }),
											$('<label />', { 'class': 'labeluppercase', text: "Sold Out",style:"padding-left:5px;" })
										
								),
						        $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Starts" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][startd]', value:startdevent,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
	                             $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Ends" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][endd]', value:endtevent+' 23:59','class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
								 $("<div>").attr("class","row rowtkt footerdiv mt15").append(

									$("<div>").attr("id","row rowtkt fieldgroup").append(
										  
										   $("<div>").attr("class","col-sm-12").append(

											   
												
											),
											 $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Min'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][min]', value:'1',maxlength:6,'class':'form-controlset form-control'})

											  ),
											  $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Max'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][max]', value:'10',maxlength:6,'class':'form-controlset form-control'})

											  )
									 )
	                             )
						  
					    )
					)
			  )

				
             $(".passonDiv"+settingcounter).hide();
             $("#special_price"+settingcounter).rules("add", "amount");
            
     // .find("div.paidticket_setting"+settingcounter).fadeIn('10000');

		} else {


				 $("#paidticket_setting"+settingcounter).append(			  		    

					$("<div>").attr("class","paidticketset_wrap ge-ticketsetting").attr("id","tickrtsetting2").append(   
	                     
	                  $("<div>").attr("class","row").append(


	                           $("<div>").attr("class","col-sm-12 form-group").append(							
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Ticket Description" }),
											$("<textarea/>", {rows:'2', type:'text', id:'paidticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Paid][Setting][description]', placeholder:'Ticket description',maxlength:300,'class':'form-control'})									
										
										
							   ),
							    $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "Payment Gateway Fee" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(							
									 
								            
										
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][payment_gateway_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"paymentgatewaytrue_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][payment_gateway_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"paymentgatewayfalse_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })		
										
							  
							   ),
							   $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "GOEVENTZ FEE" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(			
									 
								            
										
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][goeventz_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"feeGoeventztrue_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][goeventz_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"feeGoeventzfalse_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })	
										
							  
							   ),
							   $("<div>").attr("class","col-sm-12 form-group ticket_loader").attr("id","extracharge_"+settingcounter).append(
                                       
                                    $("<div>").attr("class","tex_box").append(  
                                    	
                                    )

							   ),
							   $("<div>").attr("class","col-sm-12 form-group").append(
							   						
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Open Price (minimum amount)" }),
											$("<input/>", {type:'text', id:'min_donation'+settingcounter, name:'ticket['+settingcounter+'][Paid][Setting][min_donation]', value:'0',maxlength:6,'class':'form-controlset form-control','style':'width:48%;'})								
										
										
							   
							   ),
							  
							   $("<div>").attr("class","col-sm-4 mb5 ge-selectradio").append(
	                                       $('<label />', { 'class': 'labeluppercase', text: "Status","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][status]',value:'1','checked':true}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Enable","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'ticket['+settingcounter+'][Paid][Setting][status]',value:'0'}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Disable" })						
								),	
								$("<div>").attr("class","col-sm-4 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Paid][Setting][show_des]',checked:true }),
											$('<label />', { 'class': 'labeluppercase', text: "Show ticket on event page",style:"padding-left:5px;" })
										
								),
								$("<div>").attr("class","col-sm-4 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_sold_out'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Paid][Setting][sold_out]',checked:false }),
											$('<label />', { 'class': 'labeluppercase', text: "Sold Out",style:"padding-left:5px;" })
										
								),
						        $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Starts" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][startd]', value:startdevent,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
	                             $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Ends" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][endd]', value:endtevent,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
								 $("<div>").attr("class","row rowtkt footerdiv mt15").append(

									$("<div>").attr("id","row rowtkt fieldgroup").append(
										  
										   $("<div>").attr("class","col-sm-12").append(

											   
												
											),
											 $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Min'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][min]', value:'1',maxlength:6,'class':'form-controlset form-control'})

											  ),
											  $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Max'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Paid][Setting][max]', value:'10',maxlength:6,'class':'form-controlset form-control'})

											  )
									 )
	                             )
						  
					    )
					)
			  )
         }

		$(".passonDiv"+settingcounter).hide();
		$("#paidticketsetsell_endd"+settingcounter).rules('add', { greaterThan:'#paidticketsetsell_startd'+settingcounter });
		$("#paidticketsetsell_max"+settingcounter).rules('add', { greaterThandgt:'#paidticketsetsell_min'+settingcounter });
        $("#min_donation"+settingcounter).rules("add", "amount");

		$("#paidticket_setting"+settingcounter).hide();	
 /* }  else {
       
	     $(document).find('#paidticket_setting'+settingcounter).empty();  
 }*/


}  

$(document).on("click",".paidticketsetting", function(e){ 

		var ticketsettingid =  $(this).attr("id");  

		settingcounters = ticketsettingid.split('_');
		settingcounter = settingcounters[1];
     
		if($("#paidticket_setting"+settingcounter).is(":visible")==false) {
			$("#paidticket_setting"+settingcounter).show('slow');
			if ($("#show_namefirst").length > 0)
			     checkboxselectscript();	  
		} else {
		   $("#paidticket_setting"+settingcounter).hide('1000');
		}

})

function generatefreeseting(settingcounter){

/*$(document).on("click",".ticketsetting", function(e){ 
 
  var ticketsettingid =  $(this).attr("id");
  var fullDate = new Date()
  
  settingcounters = ticketsettingid.split('_');
  settingcounter = settingcounters[1];
  $(document).find('#freeticket_setting'+settingcounter).empty(); */
/* if($('#freeticketset_wrap').length==0){
    */
	var fullDate = new Date()
	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;

	if($("form input[name='start_date_time']").val().length > 0){

	   var endtevent = $("form input[name='start_date_time']").val();
	} else {
	   var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	}
	
	  $("#freeticket_setting"+settingcounter).append(			// Creating Form div and adding <h2> and <p> paragraph tag in it.  		    

				$("<div>").attr("id","freeticketset_wrap").append( 
					
					 $("<div>").attr("class","row").append(

						$("<div>").attr("class","col-sm-12 mt15").append(

								$("<div>").attr("id","row fieldgroup").append(
									$("<p/>").addClass('headingp').text("SETTING"),
									$("<textarea/>", {rows:'5',style:'width:100%;padding-left:10px;',type:'text', id:'freeticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Free][Setting][description]', placeholder:'Message','class':'form-control'})
								)
								
							
						),
						$("<div>").attr("class","col-sm-12 mt15").append(

								$("<div>").attr("id","row fieldgroup").append(
									$('<input />', { type: 'checkbox', id: 'freeticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Free][Setting][show_des]',checked:true}),
									$('<label />', { 'for': 'cb', text: "Show ticket on event page",style:"padding-left:5px;" })
								
								)								
						)
						
                     ),
					  $("<div>").attr("class","row mt15").append(

						   $("<div>").attr("class","col-sm-6").append(

						       $("<div>").attr("id","row fieldgroup").append(
									$("<p/>").text("Ticket sales start"),
									$("<input/>", {type:'text', id:'freeticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][startd]', value:startdevent,'class':'form-controlset ddcalendar form-control'})
									//$("<input/>", {type:'text', id:'freeticketsetsell_startt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][startt]', value:starttevent,class:'dtcalendar'})
								)
							),						  
					        $("<div>").attr("class","col-sm-6").append(

								$("<div>").attr("id","row fieldgroup").append(
									$("<p/>").text("Ticket sales end"),
									$("<input/>", {type:'text', id:'freeticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][endd]', value:endtevent,'class':'form-controlset ddcalendar form-control'})
									//$("<input/>", {type:'text', id:'freeticketsetsell_endt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][endt]', value:endtevent,class:'dtcalendar'})
								)
							)
					  ),
					  $("<div>").attr("class","row footerdiv mt15").append(
						 
						  $("<div>").attr("id","row fieldgroup").append(

							  $("<div>").attr("class","col-sm-12").append(

								  $("<p/>").text("Tickets allowed per order minimum or maximum ")

							  ),
						      $("<div>").attr("class","col-sm-6").append(

								  $("<input/>", {type:'text', id:'freeticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][min]', value:'1','class':'form-controlset form-control'})

							  ),
							   $("<div>").attr("class","col-sm-6").append(

								  $("<input/>", {type:'text', id:'freeticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Free][Setting][max]', value:'10','class':'form-controlset form-control'})

							  )
						  )
					 
				  )
				)
		  )
		  
		 $("#freeticketsetsell_endd"+settingcounter).rules('add', { greaterThan:'#freeticketsetsell_startd'+settingcounter });
		 $("#freeticketsetsell_max"+settingcounter).rules('add', { greaterThandgt:'#freeticketsetsell_min'+settingcounter });
           
		 $("#freeticket_setting"+settingcounter).hide();

}

$(document).on("click",".ticketsetting", function(e){ 

		var ticketsettingid =  $(this).attr("id");  

		settingcounters = ticketsettingid.split('_');
		settingcounter = settingcounters[1];
     
		if($("#freeticket_setting"+settingcounter).is(":visible")==false) {
			$("#freeticket_setting"+settingcounter).show();
		} else {
		   $("#freeticket_setting"+settingcounter).hide();
		}

})

function generatedonationseting(settingcounter){
/*$(document).on("click",".donationtticketsetting", function(e){ 
 
  var ticketsettingid =  $(this).attr("id");  
   var fullDate = new Date()
  
  settingcounters = ticketsettingid.split('_');
  settingcounter = settingcounters[1];
  $(document).find('#donationticket_setting'+settingcounter).empty();  */
 /*if($('#donationticketset_wrap').length==0){
*/
    var fullDate = new Date();
	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;

	if($("form input[name='start_date_time']").val().length > 0){
	   var endtevent = $("form input[name='start_date_time']").val();
	} else {
	   var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	}
   
  $("#donationticket_setting"+settingcounter).append(				    

			$("<div>").attr("id","donationticketset_wrap").append(   

				              $("<div>").attr("class","row").append(						 

									$("<div>").attr("class","col-sm-12 mt15").append(

											$("<div>").attr("id","row fieldgroup").append(
												$("<p/>").text("SETTING"),
												$("<textarea/>", {rows:'5',style:'width:100%;padding-left:10px;', type:'text',id:'donationticketset_desc'+settingcounter, name:'ticket['+settingcounter+'][Donation][Setting][description]', placeholder:'Message','class':'form-control'})
											)
									 ),							
							
								   $("<div>").attr("class","col-sm-12 mt15").append(

										$("<div>").attr("id","row fieldgroup").append(
											$('<input />', { type: 'checkbox', id: 'donationticketset_showdes'+settingcounter, value: "1", name:'ticket['+settingcounter+'][Donation][Setting][show_des]',checked:true }),
											$('<label />', { 'for': 'cb', text: "Show ticket on event page",style:"padding-left:5px;" })
										
										)
									)
							),
					         $("<div>").attr("class","row mt15").append(

								  $("<div>").attr("class","col-sm-6").append(

										$("<div>").attr("id","row fieldgroup").append(
											$("<p/>").text("Ticket sales start"),
											$("<input/>", {type:'text', id:'donationticketsetsell_startd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][startd]', value:startdevent,'class':'form-controlset ddcalendar form-control'})
											//$("<input/>", {type:'text', id:'donationticketsetsell_startt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][startt]', value:starttevent,class:'dtcalendar'})
										)

									),
									$("<div>").attr("class","col-sm-6").append(

											$("<div>").attr("id","row fieldgroup").append(
											$("<p/>").text("Ticket sales end"),
											$("<input/>", {type:'text', id:'donationticketsetsell_endd'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][endd]', value:endtevent,'class':'form-controlset ddcalendar form-control'})
											//$("<input/>", {type:'text', id:'donationticketsetsell_endt'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][endt]', value:endtevent,class:'dtcalendar'})
											)

									)
							),
							$("<div>").attr("class","row footerdiv mt15").append(

								$("<div>").attr("id","row fieldgroup").append(

									$("<div>").attr("class","col-sm-12").append(
										 
									    $("<p/>").text("Tickets allowed per order minimum or maximum ")

									),
						             $("<div>").attr("class","col-sm-6").append(

										  $("<input/>", {type:'text', id:'donationticketsetsell_min'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][min]', value:'1','class':'form-controlset form-control'})

									 ),
									  $("<div>").attr("class","col-sm-6").append(

										 $("<input/>", {type:'text', id:'donationticketsetsell_max'+settingcounter,required:'required', name:'ticket['+settingcounter+'][Donation][Setting][max]', value:'10','class':'form-controlset form-control'})

									  )
						         )
				           
			  )   )
		)
	$("#donationticketsetsell_endd"+settingcounter).rules('add', { greaterThan:'#donationticketsetsell_startd'+settingcounter });
	$("#donationticketsetsell_max"+settingcounter).rules('add', { greaterThandgt:'#donationticketsetsell_min'+settingcounter });

	$("#donationticket_setting"+settingcounter).hide();

}

$(document).on("click",".donationtticketsetting", function(e){ 

		var ticketsettingid =  $(this).attr("id");  

		settingcounters = ticketsettingid.split('_');
		settingcounter = settingcounters[1];
     
		if($("#donationticket_setting"+settingcounter).is(":visible")==false) {
			$("#donationticket_setting"+settingcounter).show();
		} else {
		   $("#donationticket_setting"+settingcounter).hide();
		}

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
var counter = 5000;
$( "#addholiday" ).click(function() {

var dateObj = new Date();
var month = dateObj.getUTCMonth() + 1; //months from 1-12
var day = dateObj.getUTCDate();
var year = dateObj.getUTCFullYear();

newdate = year + "-" + month + "-" + day;



	$("#holidaydiv").append(

		$("<div>").attr("class","ge-showbox mb10").attr("id","holidaydivdelete"+counter).append(

			$("<div>").attr("class","row").append(

				$("<div>").attr("class","col-sm-4 col-xs-12 padr").append(

					$("<div>").attr("class","form-group").append(

						$("<div>").attr("class","combine xyz").append(

							$("<input/>", {type:'text', id:'holiday_start_date',required:false, name:'holiday['+counter+'][holiday_start_date]', value:newdate,'class':'form-control ddcalendardate'}),
                            $("<span>").attr("class","combinebtn calendar").append(
                            	$('<i/>', { 'class': 'fa fa-calendar' })
					        )
				        )
					
				    )

				),
				$("<div>").attr("class","col-sm-6 col-xs-12 padl padr").append(

					$("<div>").attr("class","form-group").append(						

							$("<input/>", {type:'text', id:'',required:false, name:'holiday['+counter+'][holiday_name]', value:'','class':'form-control','id':'holiday_name'+counter,placeholder:'Holiday Name'})
                            
				    )

				),
				$("<div>").attr("class","col-sm-2 col-xs-12 text-center").append(

					$("<div>").attr("class","form-group clearfix ge-deleteshow").append(						
                            $('<label />', { class: 'labeluppercase', html: "&nbsp" }),
							$('<a />', {href:'javascript:void(0)', class: 'labeluppercase delholiday ',id:'del_'+counter,html: '<i class="fa fa-trash-o"></i>&nbsp;Delete' })
                            
				    )

				)


			)

		)

	)

	$('#holiday_name'+counter).rules('add', "lettersspecialonly");

  counter++;

})

$(document).on("click",".delholiday", function(e){ 

	var ticketdelids =  $(this).attr("id");
	ticketdelid = ticketdelids.split('_');
	holdelcount = ticketdelid[1];

	$(document).find('#holidaydivdelete'+holdelcount).remove();  

})


var counter = 5000;
$( "#addnewshow" ).click(function() {

var dateObj = new Date();
var hour = dateObj.getHours() + 1; //months from 1-12
var day = dateObj.getUTCDate();
var minute = dateObj.getMinutes();

newdate = hour + "-" + minute ;


	$("#eventshowdiv").append(

		$("<div>").attr("class","ge-showbox mb10").attr("id","showdivdelete"+counter).append(

			$("<div>").attr("class","row").append(

				$("<div>").attr("class","col-sm-4 col-xs-12 padr").append(

					$("<div>").attr("class","form-group").append(						

							$("<input/>", {type:'text', id:'show['+counter+'][show_name]',required:false, name:'show['+counter+'][show_name]', value:'','id':'show_name'+counter,'class':'form-control',placeholder:'Show Name'})
                            
					
				    )

				),
				$("<div>").attr("class","col-sm-3 col-xs-6 padl padr").append(

					$("<div>").attr("class","form-group").append(

					    $("<div>").attr("class","combine xyz").append(						

							$("<input/>", {type:'text', id:'showstarttime_'+counter,required:false, name:'show['+counter+'][show_start_time]', value:'00:00','class':'form-control ddcalendartime',placeholder:'Start Time'}),
							$('<span />', { class: 'combinebtn calendar', html: '<i class="fa fa-clock-o"></i>' })
                        )   
				    )

				),
				$("<div>").attr("class","col-sm-3 col-xs-6 padl padr").append(

					$("<div>").attr("class","form-group").append(

					    $("<div>").attr("class","combine xyz").append(						

							$("<input/>", {type:'text', id:'showendtime_'+counter,required:false, name:'show['+counter+'][show_end_time]', value:'00:00','class':'form-control timepicker2',placeholder:'End Time'}),
							$('<span />', { class: 'combinebtn calendar', html: '<i class="fa fa-clock-o"></i>' })
                        )   
				    )

				),
				$("<div>").attr("class","col-sm-2 col-xs-12 text-center").append(

					$("<div>").attr("class","form-group clearfix ge-deleteshow").append(						
                            $('<label />', { class: 'labeluppercase', html: "&nbsp" }),
							$('<a />', {href:'javascript:void(0)', class: 'labeluppercase delshow',id:'delshow_'+counter,html: '<i class="fa fa-trash-o"></i>&nbsp;Delete' })
                            
				    )

				)


			)

		)

	)
    
    $('#show_name'+counter).rules('add', "lettersspecialonly");  
    $("#showendtime_"+counter).rules('add', { greaterThan:'#showstarttime_'+counter });

    counter++;

})

$(document).on("click",".delshow", function(e){ 

	var ticketdelids =  $(this).attr("id");
	ticketdelid = ticketdelids.split('_');
	holdelcount = ticketdelid[1];

	$(document).find('#showdivdelete'+holdelcount).remove();  

})





$(document).on("mouseover",".timepicker2", function(e){ 
	var pickerid =  $(this).attr("id");
	var pickerspid = pickerid.split('_');
	var sttime = $("#showstarttime_"+pickerspid[1]).val();
	
                $('.timepicker2').datetimepicker({
                   
                   datepicker:false,
                      format:'H:i',
                      step:15,
                      minTime:sttime

                });
            }); 


$(document).on("mouseover",".dateticket", function(e){ 

            var first =  $( "input[name='start_date_time']" ).val();
			var enddate = $( "input[name='end_date_time']" ).val();       

			var firstd = first.split('-');
			var edndt = enddate.split('-');
			var startjsevent = firstd[1]+"/"+firstd[2]+"/"+firstd[0];
			var endjsevent = edndt[1]+"/"+edndt[2]+"/"+edndt[0];   
           
             $('.dateticket').multiDatesPicker(
             	{

					defaultDate: new Date(startjsevent),
					minDate:new Date(startjsevent),
					maxDate: new Date(endjsevent),

             	});
            

});
 
  
var schdulecounter = 0;
var shcounter = 0;
var tkcounter = 0;
$(document).ready(function(){
	
$( "#addSchedule" ).click(function() {   
 	  shcounter++;  
      schdulecounter++;
var fullDate = new Date();
var start_schedule = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();//+" "+fullDate.getHours() + ":" + fullDate.getMinutes();

	$("#event_schedule").append(

	  $("<div>").attr("class","ge-showbox ge-schedule mb10").attr("id","showdivdelete"+shcounter).append(
				$("<span>").attr("class","deleteschedule").append(
					$("<a />").attr('href','javascript:void(0)').attr("class","deletesh").attr("id","deleteschdule_"+shcounter).html('<i class="fa fa-times"></i>')
				),
				$("<div>").attr("class","row").append(

					$("<div>").attr("class","col-sm-11 col-xs-11").append(

						$("<div>").attr("class","form-group").append(						

								$("<input/>", {type:'text', id:'show['+shcounter+'][schedule_name]',required:'required', name:'schedule['+shcounter+'][schedule_name]', value:'','id':'schedule_name'+shcounter,'class':'form-control',placeholder:'Schedule Name'})
	                            
						
					    )

					)
				),
			$("<div>").attr("class","row").append(				
			
				$("<div>").attr("class","form-group col-sm-12").attr("id","plocationdiv").append(
										
                            $('<label />', { class: 'labeluppercase', html: "&nbsp;LOCATION" }),
							$("<input/>", {type:'text', id:'geocomplete'+shcounter,name:'schedule['+shcounter+'][venue_location]', value:'','class':'form-control geocomplete',placeholder:'Schedule Location'}),
							$("<input/>", {type:'hidden', id:'lat'+shcounter, name:'schedule['+shcounter+'][latitude]', value:'','class':'form-control '}),
							$("<input/>", {type:'hidden', id:'lng'+shcounter, name:'schedule['+shcounter+'][longitude]', value:'','class':'form-control '}),
							$("<input/>", {type:'hidden', id:'mapurl'+shcounter, name:'schedule['+shcounter+'][map_url]', value:'','class':'form-control '})
				),
				$("<div>").attr("class","col-sm-8").append(					

						$("<div>").attr("class","form-group mt10").append(
											
	                            // $('<label />', { class: 'labeluppercase', html: "Venue Address" }),
								$("<input/>", {type:'text', id:'venue_name'+shcounter, name:'schedule['+shcounter+'][venue_name]', value:'','class':'form-control',placeholder:'Venue Name'})
	                    ),
	                    $("<div>").attr("class","form-group mt10").append(
											
	                            // $('<label />', { class: 'labeluppercase', html: "Venue Address" }),
								$("<input/>", {type:'text', id:'address1'+shcounter, name:'schedule['+shcounter+'][address1]', value:'','class':'form-control',placeholder:'Address Line 1'})
	                    ),
	                     $("<div>").attr("class","form-group mt10").append(
											
	                            $("<input/>", {type:'text', id:'address2'+shcounter, name:'schedule['+shcounter+'][address2]', value:'','class':'form-control',placeholder:'Address Line 2'})
	                    ),
	                      $("<div>").attr("class","form-group mt10").append(
											
	                            $("<input/>", {type:'text', id:'city'+shcounter, name:'schedule['+shcounter+'][city]', value:'','class':'form-control',placeholder:'City'})
	                    ),
	                   $("<div>").attr("class","row").append(

			                    $("<div>").attr("class","form-group col-sm-6 mt10").append(
													
			                            $("<input/>", {type:'text', id:'state'+shcounter,name:'schedule['+shcounter+'][state]', value:'','class':'form-control',placeholder:'State'})
			                    ),
			                      $("<div>").attr("class","form-group col-sm-6 mt10").append(
													
			                            $("<input/>", {type:'text', id:'pincode'+shcounter,name:'schedule['+shcounter+'][pincode]', value:'','class':'form-control',placeholder:'Zip/Postal'})
			                    )
	                   ),
	                    $("<div>").attr("class","form-group mt10").append(
											
	                            $("<input/>", {type:'text', id:'country'+shcounter,name:'schedule['+shcounter+'][country]', value:'','class':'form-control',placeholder:'country'})
	                    )
	           ),
                $("<div>").attr("class","col-sm-4 text-center").append(
                	$("<div>").attr("style","border:1px solid #ddd; width:100%;height:auto; margin-top:25px;").append(
                		$("<div>").attr("id","us3"+shcounter).attr("style","width: 100%; height: 200px;").append(
                		)
                	)
                )
              ),             
               $("<div>").attr("class","row").append(
               	  $("<div>").attr("class","col-sm-12").append(
               	  	  $('<h3 />', { class: 'HeadLine HeadLineColor', html: "Create Ticket" }),
               	  	  $("<div>").attr("class","text-right").append(
               	  	  	 $('<button />', { class: 'btn btn-sm btnmy btnmyscedule myschdule mb10', html: "<i class='fa fa-plus-circle'></i> Add Slot to this Schedule",id: "addschduleticket_"+shcounter,Type: "button" })
               	  	   )
               	   )
               	),
               $("<div>").attr("class","addslots").attr("id","addslotBox_"+shcounter).append(
        //        	  $("<div>").attr("class","row").attr("id","delslotBox"+schdulecounter).append(
        //        	  	   $("<div>").attr("class","slotbox clearfix mb10").append(
								// $("<span>").attr("class","deleteslotbox").append(
								// $("<a />").attr('href','javascript:void(0)').attr("id","slotdel_"+schdulecounter).addClass('delslot').html('<i class="fa fa-times"></i>')
								// ),

        //        	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
        //        	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "Start Date" }),
        //        	  	   	        	$("<div>").attr("class","combine xyz").append(

        //        	  	   	        		 $("<input/>", {type:'text', id:'schedule_startd'+schdulecounter,required:'required', name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_startd]', value:start_schedule,'class':'ddcalendardate form-control'}),
        //                             	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')

        //        	  	   	        	)

        //        	  	   	         ),
        //        	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
        //        	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "Start Time" }),
        //        	  	   	        	$("<div>").attr("class","combine xyz").append(

        //        	  	   	        		 $("<input/>", {type:'text', id:'schedule_startd_time'+schdulecounter,required:'required', name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_startd_time]', value:'00:00','class':'ddcalendartime form-control'}),
        //                             	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-clock-o"></i>')

        //        	  	   	        	)

        //        	  	   	         ),
        //        	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
        //        	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "End Date" }),
        //        	  	   	        	$("<div>").attr("class","combine xyz").append(

        //        	  	   	        		 $("<input/>", {type:'text', id:'schedule_enddate'+schdulecounter, name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_enddate]', value:start_schedule,'class':'ddcalendardate form-control'}),
        //                             	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')

        //        	  	   	        	)

        //        	  	   	         ),
        //        	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
        //        	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "End Time" }),
        //        	  	   	        	$("<div>").attr("class","combine xyz").append(

        //        	  	   	        		 $("<input/>", {type:'text', id:'schedule_enddate_time'+schdulecounter, name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_enddate_time]', value:'00:00','class':'ddcalendartime form-control'}),
        //                             	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-clock-o"></i>')

        //        	  	   	        	)

        //        	  	   	         )
        //                         ,
        //                 $("<div>").attr("class","col-sm-12").append(
        //         	 	$('<h3 />', { class: 'HeadLine HeadLineColor', html: "Create Tickets" }),                	 	
        //                 $("<div>").attr("id","scticket_generate"+schdulecounter).addClass('scticketbox').append( ),
        //         	 	$("<div>").attr("class","text-right").append(
        //         	 		$('<button />', { class: 'btn btn-sm btnmy btnmyscedule mb10 addtickets', html: "<i class='fa fa-plus-circle'></i> Add Ticket",id: "addtickets_"+schdulecounter+"_"+shcounter,Type: "button" })
        //         	 	 )

        //         	 )
               	  	   	   
        //        	  	   	)  
        //        	  	)
                   
                	 
               

               	)     
               

               
		)

	)    
  // if($('"#schedule_enddate"+schdulecounter').val()!='')
	//$("#schedule_enddate"+schdulecounter).rules('add', { greaterThan:'#schedule_startd'+schdulecounter });
	 //schdulecounter++; 	
	refreshjquery(shcounter);
  
   
})

$(document).on("click",".myschdule", function(e){ 
    
    var fullDate = new Date();
    var start_schedule = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();//+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	var shticketCounter =  $(this).attr("id");
	shticketCounters = shticketCounter.split('_');
	var realval =0;
	if ( $( "#schduleid_"+shticketCounters[1] ).length ) {
 
       realval = $( "#schduleid_"+shticketCounters[1] ).val(); 
    }
    
    schdulecounter++;
	$("#addslotBox_"+shticketCounters[1]).append(	  
	  $("<div>").attr("class","row").attr("id","delslotBox"+schdulecounter).append(
               	  	   $("<div>").attr("class","slotbox clearfix mb10").append(
								$("<span>").attr("class","deleteslotbox").append(
								$("<a />").attr('href','javascript:void(0)').attr("id","slotdel_"+schdulecounter).addClass('delslot').html('<i class="fa fa-times"></i>')
								),

               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "Start Date" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(
                                          $("<input/>", {type:'hidden', name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_id]', value:realval,'class':'form-control'}),
               	  	   	        		 $("<input/>", {type:'text', id:'schedule_startd'+schdulecounter,required:'required', name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_startd]', value:start_schedule,'class':'ddcalendardate form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')

               	  	   	        	)

               	  	   	         ),
               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "Start Time" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(

               	  	   	        		 $("<input/>", {type:'text', id:'schedule_startd_time'+schdulecounter,required:'required', name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_startd_time]', value:'00:00','class':'ddcalendartime form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-clock-o"></i>')

               	  	   	        	)

               	  	   	         ),
               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "End Date" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(

               	  	   	        		 $("<input/>", {type:'text', id:'schedule_enddate'+schdulecounter,name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_enddate]', value:start_schedule,'class':'ddcalendardate form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')

               	  	   	        	)

               	  	   	         ),
               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "End Time" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(

               	  	   	        		 $("<input/>", {type:'text', id:'schedule_enddate_time'+schdulecounter, name:'schedule['+shcounter+'][schduleData]['+schdulecounter+'][schedule_enddate_time]', value:'00:00','class':'ddcalendartime form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-clock-o"></i>')

               	  	   	        	)

               	  	   	         ),
								$("<div>").attr("class","col-sm-12").append(
									$('<h3 />', { class: 'HeadLine HeadLineColor', html: "Create Tickets" }),
									$("<div>").attr("id","scticket_generate"+schdulecounter).addClass('scticketbox ').append( ),
									$("<div>").attr("class","text-right").append(
										$('<button />', { class: 'btn btn-sm btnmy btnmyscedule mb10 addtickets', html: "<i class='fa fa-plus-circle'></i> Add Ticket",id: "addtickets_"+schdulecounter+"_"+shcounter,Type: "button" })
									)
								)

               	  	   	   
               	  	   	)              
	                	
                    
               	  	  )
             )

     $("#schedule_enddate"+schdulecounter).rules('add', { greaterThan:'#schedule_startd'+schdulecounter });
});


$(document).on("click",".addtickets", function(e){ 
     
     tkcounter++;
	  var settingvar = 'setting_'+tkcounter;

      var ticketCounter =  $(this).attr("id");  

		ticketCounters = ticketCounter.split('_');

	
		
	  var schduleTktCounter = ticketCounters[1];
	  var shcounterSecond =  ticketCounters[2]
      
		$("div#scticket_generate"+schduleTktCounter).append(			 
		
			$("<div>").attr("id","paid_ticket"+tkcounter).addClass('paidticketwrap ge-ticketcreatebox mb10').append( 

               $("<div>").attr("class","row").append(

				  $("<div>").attr("class","col-sm-3 padr").append(
				  	 $("<div>").attr("class","form-group").append(
                       
					   $("<input/>", {type:'text', id:'paidticket_box'+tkcounter, name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+tkcounter+'][ticket_name]',required:'required', placeholder:'Ticket name','class':'form-control xyz',maxlength:75,title:"eg- early bird, General admission, couple entry etc"})
					 )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group").append(
						 $("<input/>", {type:'text', id:'paidquantity_box'+tkcounter, name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+tkcounter+'][ticket_quantity]',required:'required', placeholder:'Quantity',maxlength:8,'class':'form-control xyz',title:"Quantity"})
				     )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group").append(

					 $("<input/>", {type:'text', id:'price_box'+tkcounter,'track-element':shcounterSecond+"_"+schduleTktCounter+"_"+tkcounter, name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+tkcounter+'][ticket_price]', placeholder:'Price','class':'form-control xyz calculationPriceRec',maxlength:12,title:"For free ticket enter 0, for donation leave it blank"})
				     
				     )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group currency_select").attr("id","paidticket_currency"+tkcounter).append(

					
				     
				     )
				  ),
				  $("<div>").attr("class","col-sm-3 col-xs-6 text-right").append(

				  	$("<div>").attr("class","form-group clearfix ge-addsetting").append(
					   // $("<span/>").text("Paid"),
						$("<a />").attr('href','javascript:void(0)').attr('data-toggle',"collapse").attr('aria-controls',"collapseExample").attr('aria-expanded',"false").attr('id',settingvar).addClass('schduleticketsetting').html('<i class="fa fa-cog"></i>&nbsp;Setting'),
						$("<a />").attr('href','javascript:void(0)').attr('data-toggle',"collapse").attr('aria-controls',"collapseExample").attr('aria-expanded',"false").attr('id','delete_'+tkcounter).addClass('paidticketdelete').html('<i class="fa fa-trash-o"></i>&nbsp;Delete')
				    )
				  )
			 ),
			 $("<div>").attr("id","paidticket_setting"+tkcounter).addClass('ticketbox2').append( )			 
			)	
			
		)
		 
	     //$(document).find('#paidticket_currency').empty();  

		/*var arr = [
		{val : 0, text: 'country'},
		{val : 1, text: 'India'}
		];

		var sel = $('<select id="currency_country" required="required" name="ticket['+counter+'][Paid][currency_country]" class="form-controlset">').appendTo('#paidticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
        */
	        
		var currencytkcounter = shcounterSecond+"_"+schduleTktCounter+"_"+tkcounter;
		var sel = $('<select id="ticketCurrency_'+tkcounter+'" class="form-controlset form-control calculationCurrencyRec" required="required" track-element='+currencytkcounter+' name="schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+tkcounter+'][currency]">').appendTo('#paidticket_currency'+tkcounter);
		$.each(curencyData, function(k, v) {
			 if(k==1)
		       sel.append($("<option selected>").attr('value',k).text(v));
		      else
               sel.append($("<option >").attr('value',k).text(v));
		});			
        
		 $("#paidquantity_box"+tkcounter).rules("add", "number");
		 $("#price_box"+tkcounter).rules("add", "number");
		 $("#price_box"+tkcounter).rules("add", "amount");
		
		generateschduleseting(tkcounter,schduleTktCounter,shcounterSecond)
		  
        
  });	
	
  
function generateschduleseting(settingcounter,schduleTktCounter,shcounterSecond){


  var fullDate = new Date()
  var startdt = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
  var endtevent = $('#schedule_startd'+schduleTktCounter).val()+" 00:00"



	$("#paidticket_setting"+settingcounter).append(			  		    

					$("<div>").attr("class","paidticketset_wrap ge-ticketsetting").attr("id","tickrtsetting2").append(   
	                     
	                  $("<div>").attr("class","row").append(


	                           $("<div>").attr("class","col-sm-12 form-group").append(							
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Ticket Description" }),
											$("<textarea/>", {rows:'2', type:'text', id:'paidticketset_desc'+settingcounter, name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][description]', placeholder:'Ticket description',maxlength:300,'class':'form-control'})									
										
										
							   ),
							    $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "Payment Gateway Fee" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(							
									 
								            
										
										$('<input/>').attr({ type: 'radio', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][payment_gateway_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"paymentgatewaytrue_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][payment_gateway_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"paymentgatewayfalse_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })		
										
							  
							   ),
							   $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "GOEVENTZ FEE" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(			
									 
								            
										
										$('<input/>').attr({ type: 'radio', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][goeventz_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"feeGoeventztrue_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][goeventz_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"feeGoeventzfalse_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgateway"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })	
										
							  
							   ),
							   $("<div>").attr("class","col-sm-12 form-group ticket_loader").attr("id","extracharge_"+settingcounter).append(
                                       
                                    $("<div>").attr("class","tex_box").append(  
                                    	
                                    )

							   ),
							   $("<div>").attr("class","col-sm-12 form-group").append(
							   						
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Open Price (minimum amount)" }),
											$("<input/>", {type:'text', id:'min_donation'+settingcounter, name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][min_donation]', value:'0',maxlength:6,'class':'form-controlset form-control','style':'width:48%;'})								
										
										
							   
							   ),
							   $("<div>").attr("class","col-sm-5 mb5 ge-selectradio").append(
	                                       $('<label />', { 'class': 'labeluppercase', text: "Status","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][status]',value:'1','checked':true}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Enable","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][status]',value:'0'}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Disable" })						
								),	
								$("<div>").attr("class","col-sm-4 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_showdes'+settingcounter, value: "1", name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][show_des]',checked:true }),
											$('<label />', { 'class': 'labeluppercase', text: "Show ticket",style:"padding-left:5px;" })
										
								),
								$("<div>").attr("class","col-sm-3 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_sold_out'+settingcounter, value: "1", name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][sold_out]',checked:false }),
											$('<label />', { 'class': 'labeluppercase', text: "Sold Out",style:"padding-left:5px;" })
										
								),
						        $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Starts" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_startd'+settingcounter,required:'required', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][startd]', value:startdt,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
	                             $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Ends" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_endd'+settingcounter,required:'required', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][endd]', value:endtevent,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
								 $("<div>").attr("class","row rowtkt footerdiv mt15").append(

									$("<div>").attr("id","row rowtkt fieldgroup").append(
										  
										   $("<div>").attr("class","col-sm-12").append(

											   
												
											),
											 $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Min'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_min'+settingcounter,required:'required', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][min]', value:'1',maxlength:6,'class':'form-controlset form-control'})

											  ),
											  $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Max'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_max'+settingcounter,required:'required', name:'schedule['+shcounterSecond+'][schduleData]['+schduleTktCounter+'][ticket]['+settingcounter+'][Setting][max]', value:'10',maxlength:6,'class':'form-controlset form-control'})

											  )
									 )
	                             )
						  
					    )
					)
			  )
        
        $(".passonDiv"+settingcounter).hide();
		$("#paidticketsetsell_endd"+settingcounter).rules('add', { greaterThan:'#paidticketsetsell_startd'+settingcounter });
		$("#paidticketsetsell_max"+settingcounter).rules('add', { greaterThandgt:'#paidticketsetsell_min'+settingcounter });
		$("#min_donation"+settingcounter).rules("add", "amount");
		  $("#paidticket_setting"+settingcounter).hide();	
 /* }  else {
       
	     $(document).find('#paidticket_setting'+settingcounter).empty();  
 }*/

}

$(document).on("click",".schduleticketsetting", function(e){ 

      
		var ticketsettingid =  $(this).attr("id");  

		settingcounters = ticketsettingid.split('_');
		settingcounter = settingcounters[1];
    
		if($("#paidticket_setting"+settingcounter).is(":visible")==false) {
			$("#paidticket_setting"+settingcounter).show('slow');
			if ($("#show_namefirst").length > 0)
			     checkboxselectscript();	  
		} else {
		   $("#paidticket_setting"+settingcounter).hide('slow');
		}

})

$(document).on("click",".deletesh", function(e){ 
   
	var scdelete =  $(this).attr("id");
	scdeletes = scdelete.split('_');
	holdelcount = scdeletes[1];

	$(document).find('#showdivdelete'+holdelcount).remove();  

})

$(document).on("click",".delslot", function(e){ 
   
	var scdelete =  $(this).attr("id");
	scdeletes = scdelete.split('_');
	holdelcount = scdeletes[1];

	$(document).find('#delslotBox'+holdelcount).remove();  

})


var editslotcounter = 900;
$(document).on("click",".myschduleedit", function(e){ 
    
    var fullDate = new Date();
    var start_schedule = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();//+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	fullDate.setDate(fullDate.getDate() + 30);
	var end_schedule_date =  fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();
	
	var shticketCounter =  $(this).attr("id");
	shticketCounters = shticketCounter.split('_');
	var realval =0;
	if ( $( "#schduleid_"+shticketCounters[1] ).length ) {
 
       realval = $( "#schduleid_"+shticketCounters[1] ).val(); 
    }
    
    
    schdulecounter++;
	$("#addslotBox_"+shticketCounters[1]).append(	  
	  $("<div>").attr("class","row").attr("id","delslotBox"+editslotcounter).append(
               	  	   $("<div>").attr("class","slotbox clearfix mb10").append(
								$("<span>").attr("class","deleteslotbox").append(
								$("<a />").attr('href','javascript:void(0)').attr("id","slotdel_"+editslotcounter).addClass('delslot').html('<i class="fa fa-times"></i>')
								),

               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "Start Date" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(
                                          $("<input/>", {type:'hidden', name:'scheduleedit[Edit]['+shticketCounters[1]+'][Slot]['+editslotcounter+'][schedule_id]', value:realval,'class':'form-control'}),
               	  	   	        		 $("<input/>", {type:'text', id:'schedule_startd'+editslotcounter,required:'required', name:'scheduleedit[Edit]['+shticketCounters[1]+'][Slot]['+editslotcounter+'][schedule_startd]', value:start_schedule,'class':'ddcalendardate form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')

               	  	   	        	)

               	  	   	         ),
               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "Start Time" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(

               	  	   	        		 $("<input/>", {type:'text', id:'schedule_startd_time'+editslotcounter,required:'required', name:'scheduleedit[Edit]['+shticketCounters[1]+'][Slot]['+editslotcounter+'][schedule_startd_time]', value:'00:00','class':'ddcalendartime form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-clock-o"></i>')

               	  	   	        	)

               	  	   	         ),
               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "End Date" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(

               	  	   	        		 $("<input/>", {type:'text', id:'schedule_enddate'+editslotcounter,name:'scheduleedit[Edit]['+shticketCounters[1]+'][Slot]['+editslotcounter+'][schedule_enddate]', value:end_schedule_date,'class':'ddcalendardate form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')

               	  	   	        	)

               	  	   	         ),
               	  	   	        $("<div>").attr("class","col-sm-3 col-xs-6 form-group padr").append(
               	  	   	        	$('<label />', { 'class': 'labeluppercase', text: "End Time" }),
               	  	   	        	$("<div>").attr("class","combine xyz").append(

               	  	   	        		 $("<input/>", {type:'text', id:'schedule_enddate_time'+editslotcounter,name:'scheduleedit[Edit]['+shticketCounters[1]+'][Slot]['+editslotcounter+'][schedule_enddate_time]', value:'00:00','class':'ddcalendartime form-control'}),
                                    	 $("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-clock-o"></i>')

               	  	   	        	)

               	  	   	         ),
								$("<div>").attr("class","col-sm-12").append(
									$('<h3 />', { class: 'HeadLine HeadLineColor', html: "Create Tickets" }),
									$("<div>").attr("id","scticket_generate"+editslotcounter).addClass('scticketbox').append( ),
									$("<div>").attr("class","text-right").append(
										$('<button />', { class: 'btn btn-sm btnmy btnmyscedule mb10 addticketsedit', html: "<i class='fa fa-plus-circle'></i> Add Ticket",id: "addtickets_"+shticketCounters[1]+"_"+editslotcounter,Type: "button" })
									)
								)

               	  	   	   
               	  	   	)              
	                	
                    
               	  	  )
             )
    // if($("#schedule_enddate"+editslotcounter).val()!='')
     $("#schedule_enddate"+editslotcounter).rules('add', { greaterThan:'#schedule_startd'+editslotcounter });
     editslotcounter++;
});

var editticketcounter = 1000;
$(document).on("click",".addticketsedit", function(e){ 
     
     
	  var settingvar = 'setting_'+tkcounter;

      var ticketCounter =  $(this).attr("id");  

		ticketCounters = ticketCounter.split('_');

	
 
       realval = $( "#schduleid_"+shcounterSecond ).val(); 
   
		
	  var schduleTktCounter = ticketCounters[1];
	  var shcounterSecond =  ticketCounters[2]
      
		$("div#scticket_generate"+shcounterSecond).append(			 
		
			$("<div>").attr("id","paid_ticket"+tkcounter).addClass('paidticketwrap ge-ticketcreatebox mb10').append( 

               $("<div>").attr("class","row").append(

				  $("<div>").attr("class","col-sm-3 padr").append(
				  	 $("<div>").attr("class","form-group").append(
                       $("<input/>", {type:'hidden', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+tkcounter+'][schdule_id]', value:realval,'class':'form-control'}),
					   $("<input/>", {type:'text', id:'paidticket_box'+tkcounter, name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+tkcounter+'][ticket_name]',required:'required', placeholder:'Ticket name','class':'form-control xyz',maxlength:75,title:"eg- early bird, General admission, couple entry etc"})
					 )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group").append(
						 $("<input/>", {type:'text', id:'paidquantity_box'+tkcounter, name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+tkcounter+'][ticket_quantity]',required:'required', placeholder:'Quantity',maxlength:8,'class':'form-control xyz',title:"Quantity"})
				     )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group").append(

					 $("<input/>", {type:'text', id:'price_box'+tkcounter,'track-element':schduleTktCounter+"_"+shcounterSecond+"_"+tkcounter,  name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+tkcounter+'][ticket_price]', placeholder:'Price','class':'form-control xyz calculationPriceRecEdit',maxlength:12,title:"For free ticket enter 0, for donation leave it blank"})
				     
				     )
				  ),
				  $("<div>").attr("class","col-sm-2 col-xs-6 padl padr").append(

				  	 $("<div>").attr("class","form-group currency_select").attr("id","paidticket_currency"+tkcounter).append(

					
				     
				     )
				  ),
				  $("<div>").attr("class","col-sm-3 col-xs-6 text-right").append(

				  	$("<div>").attr("class","form-group clearfix ge-addsetting").append(
					   // $("<span/>").text("Paid"),
						$("<a />").attr('href','javascript:void(0)').attr('data-toggle',"collapse").attr('aria-controls',"collapseExample").attr('aria-expanded',"false").attr('id',settingvar).addClass('schduleticketsetting').html('<i class="fa fa-cog"></i>&nbsp;Setting'),
						$("<a />").attr('href','javascript:void(0)').attr('data-toggle',"collapse").attr('aria-controls',"collapseExample").attr('aria-expanded',"false").attr('id','delete_'+tkcounter).addClass('paidticketdelete').html('<i class="fa fa-trash-o"></i>&nbsp;Delete')
				    )
				  )
			 ),
			 $("<div>").attr("id","paidticket_setting"+tkcounter).addClass('ticketbox2').append( )			 
			)	
			
		)
		 
	     //$(document).find('#paidticket_currency').empty();  

		/*var arr = [
		{val : 0, text: 'country'},
		{val : 1, text: 'India'}
		];

		var sel = $('<select id="currency_country" required="required" name="ticket['+counter+'][Paid][currency_country]" class="form-controlset">').appendTo('#paidticket_currency'+counter);
		$(arr).each(function() {
		sel.append($("<option>").attr('value',this.val).text(this.text));
		});		
        */
	        
	   var strcuudata = schduleTktCounter+"_"+shcounterSecond+"_"+tkcounter;

		var sel = $('<select id="ticketCurrency_'+tkcounter+'" class="form-controlset form-control calculationCurencRecEdit" track-element='+strcuudata+' required="required" name="scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+tkcounter+'][currency]">').appendTo('#paidticket_currency'+tkcounter);
		$.each(curencyData, function(k, v) {
		sel.append($("<option>").attr('value',k).text(v));
		});			
        
		 $("#paidquantity_box"+tkcounter).rules("add", "number");
		 $("#price_box"+tkcounter).rules("add", "number");
		 $("#price_box"+tkcounter).rules("add", "amount");
		
		generateschdulesetingedit(tkcounter,schduleTktCounter,shcounterSecond)
		  
        tkcounter++;
  });	
	
  
function generateschdulesetingedit(settingcounter,schduleTktCounter,shcounterSecond){

/*var ticketsettingid =  $(this).attr("id");  

settingcounters = ticketsettingid.split('_');
settingcounter = settingcounters[1];
$(document).find('#paidticket_setting'+settingcounter).empty();  */

var fullDate = new Date()
var startdt = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
fullDate.setDate(fullDate.getDate() + 30);
var endtevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
/* if($('#paidticketset_wrap').length==0){*/
	
//	var startdevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate()+" "+fullDate.getHours() + ":" + fullDate.getMinutes();
	//var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
  //  var endtevent = fullDate.getFullYear() + "-" + ((fullDate.getMonth()+1)<10 ? '0':'')+(fullDate.getMonth()+1) + "-" +(fullDate.getDate()<10 ? '0' : '')  +fullDate.getDate();
	//if($("form input[name='start_date_time']").val().length > 0){

	  // var endtevent = $("form input[name='start_date_time']").val();
	//} else {
	  // var starttevent = fullDate.getHours() + "-" + fullDate.getMinutes() ;
	//}

	$("#paidticket_setting"+settingcounter).append(			  		    

					$("<div>").attr("class","paidticketset_wrap ge-ticketsetting").attr("id","tickrtsetting2").append(   
	                     
	                  $("<div>").attr("class","row").append(


	                           $("<div>").attr("class","col-sm-12 form-group").append(							
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Ticket Description" }),
											$("<textarea/>", {rows:'2', type:'text', id:'paidticketset_desc'+settingcounter, name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][description]', placeholder:'Ticket description',maxlength:300,'class':'form-control'})									
										
										
							   ),
							   $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "Payment Gateway Fee" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(							
									 
								        $('<input/>').attr({ type: 'radio', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][payment_gateway_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"paymentgatewaytrue_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgatewayRec"),	
										$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
										$('<input/>').attr({ type: 'radio', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][payment_gateway_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"paymentgatewayfalse_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgatewayRec"),	
										$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })						
												
										
							  
							   ),
							   $("<div>").attr("class","col-sm-3 form-group passonDiv"+settingcounter).append(							
									 
								            
										$('<label />', { 'class': 'labeluppercase', text: "GOEVENTZ FEE" })
										
										
							  
							   ),
							   $("<div>").attr("class","col-sm-9 form-group passonDiv"+settingcounter).append(	

									$('<input/>').attr({ type: 'radio', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][goeventz_fee]',value:'1','checked':false,"style":"padding-right:10px;","id":"feeGoeventztrue_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgatewayRec"),	
									$('<label />', { 'class': 'labeluppercase', text: "Pass fees on","style":"padding-right:10px;" }),
									$('<input/>').attr({ type: 'radio', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][goeventz_fee]',value:'2','checked':true,"style":"padding-right:10px;","id":"feeGoeventzfalse_"+shcounterSecond+"_"+schduleTktCounter+"_"+settingcounter}).addClass("rad paymentgatewayRec"),	
									$('<label />', { 'class': 'labeluppercase', text: "Absorb fees","style":"padding-right:10px;" })		
										
							  
							   ),									
							   				   
							   $("<div>").attr("class","col-sm-12 form-group ticket_loader").attr("id","extracharge_"+settingcounter).append(
                                       
                                    $("<div>").attr("class","tex_box").append(  
                                    	
                                    )

							   ),
							   $("<div>").attr("class","col-sm-12 form-group").append(
							   						
									 
								            $('<label />', { 'class': 'labeluppercase', text: "Open Price (minimum amount)" }),
											$("<input/>", {type:'text', id:'min_donation'+settingcounter, name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][min_donation]', value:'0',maxlength:6,'class':'form-controlset form-control','style':'width:48%;'})								
										
										
							   
							   ),
							   $("<div>").attr("class","col-sm-5 mb5 ge-selectradio").append(
	                                       $('<label />', { 'class': 'labeluppercase', text: "Status","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][status]',value:'1','checked':true}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Enable","style":"padding-right:10px;" }),
							               $('<input/>').attr({ type: 'radio', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][status]',value:'0'}).addClass("rad"),	
							               $('<label />', { 'class': 'labeluppercase', text: "Disable" })						
								),	
								$("<div>").attr("class","col-sm-4 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_showdes'+settingcounter, value: "1", name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][show_des]',checked:true }),
											$('<label />', { 'class': 'labeluppercase', text: "Show ticket",style:"padding-left:5px;" })
										
								),
								$("<div>").attr("class","col-sm-3 form-group").append( 
								   			$('<input />', { type: 'checkbox', id: 'paidticketset_sold_out'+settingcounter, value: "1", name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][sold_out]',checked:false }),
											$('<label />', { 'class': 'labeluppercase', text: "Sold Out",style:"padding-left:5px;" })
										
								),
						        $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Starts" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_startd'+settingcounter,required:'required', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][startd]', value:startdt,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
	                             $("<div>").attr("class","col-sm-6 form-group").append(

						        	$('<label />', { 'class': 'labeluppercase', text: "Ends" }),    	

									$("<div>").attr("class","combine xyz").append(
								
											$("<input/>", {type:'text', id:'paidticketsetsell_endd'+settingcounter,required:'required', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][endd]', value:endtevent,'class':'ddcalendar form-control'}),
											$("<span>").attr("class","combinebtn calendar").html('<i class="fa fa-calendar"></i>')
									)
														  
									
								 ),
								 $("<div>").attr("class","row rowtkt footerdiv mt15").append(

									$("<div>").attr("id","row rowtkt fieldgroup").append(
										  
										   $("<div>").attr("class","col-sm-12").append(

											   
												
											),
											 $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Min'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_min'+settingcounter,required:'required', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][min]', value:'1',maxlength:6,'class':'form-controlset form-control'})

											  ),
											  $("<div>").attr("class","col-sm-6").append(
	                                            $("<label>").text('Max'),  
												$("<input/>", {type:'text', id:'paidticketsetsell_max'+settingcounter,required:'required', name:'scheduleedit[Edit]['+schduleTktCounter+'][Slot]['+shcounterSecond+'][Ticket]['+settingcounter+'][Setting][max]', value:'10',maxlength:6,'class':'form-controlset form-control'})

											  )
									 )
	                             )
						  
					    )
					)
			  )
        
         $(".passonDiv"+settingcounter).hide();
		$("#paidticketsetsell_endd"+settingcounter).rules('add', { greaterThan:'#paidticketsetsell_startd'+settingcounter });
		$("#paidticketsetsell_max"+settingcounter).rules('add', { greaterThandgt:'#paidticketsetsell_min'+settingcounter });
		$("#min_donation"+settingcounter).rules("add", "amount");
		  $("#paidticket_setting"+settingcounter).hide();	
 /* }  else {
       
	     $(document).find('#paidticket_setting'+settingcounter).empty();  
 }*/

}


});


$(document).ready(function(){

 $(document).on("click",".paymentgateway", function(e){ 
    // alert("ok");
	var ticketCounter =  $(this).attr("id");	
	ticketCounters = ticketCounter.split('_');
	var country ='';
	if(ticketCounters.length==4){
		
		 var j= ticketCounters[3];
		 var pgstatus = $('input[name="schedule['+ticketCounters[1]+'][schduleData]['+ticketCounters[2]+'][ticket]['+ticketCounters[3]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="schedule['+ticketCounters[1]+'][schduleData]['+ticketCounters[2]+'][ticket]['+ticketCounters[3]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[1]).val();
	} else {
	var j= ticketCounters[1];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();
    var country = $("#country").val();	
    }    
    
	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();

	   if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }       

    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	 $('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        $('.loaderbox').hide();
			        // $('.ticket_loader').loader('hide');	
			      if(response.xtraChargeFlag == true){
			      	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
					} else{
						$("#extracharge_"+j).html('');
					}      
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 }  else {
	 	$("#extracharge_"+j).html("");
	 }   
 })

 $(document).on("blur",".calculationPrice", function(e){ 
    
	var ticketCounter =  $(this).attr("id");	
	var ticketCounters =  ticketCounter.replace(/[^0-9]+/ig,""); 

	// var ticketCounter =  $(".paymentgateway").attr("id");
	// ticketCounters = ticketCounter.split('_');
	var country =''; 
	if(ticketCounters.length==4){
		
		 var j= ticketCounters[3];
		 var pgstatus = $('input[name="schedule['+ticketCounters[1]+'][schduleData]['+ticketCounters[2]+'][ticket]['+ticketCounters[3]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="schedule['+ticketCounters[1]+'][schduleData]['+ticketCounters[2]+'][ticket]['+ticketCounters[3]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[1]).val();	
	} else {
	var j= ticketCounters[0];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();
    var country = $("#country").val();	
    }    

	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
    
      if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }
    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        	$('.loaderbox').hide();
			        // $('.ticket_loader').loader('hide');	
				      if(response.xtraChargeFlag == true){
				      	$(".passonDiv"+j).show();
				       var goTax ='';
				       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
					       var feeperticket_go = response.per_ticket_fee+response.go_charges;
		                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
	                   // }
						
				        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
				        	
				        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
				     }  else{
						$("#extracharge_"+j).html('');
					 }    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 }  else {
	 	$("#extracharge_"+j).html("");
	 }      
 })

  $(document).on("blur",".calculationPriceRec", function(e){ 
    
	var ticketCounter =  $(this).attr("track-element");	
	var ticketCounters =  ticketCounter.split('_'); 
    var country =''; 
	// var ticketCounter =  $(".paymentgateway").attr("id");
	// ticketCounters = ticketCounter.split('_');
	
	if(ticketCounters.length==3){
		
		 var j= ticketCounters[2];
		 var pgstatus = $('input[name="schedule['+ticketCounters[0]+'][schduleData]['+ticketCounters[1]+'][ticket]['+ticketCounters[2]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="schedule['+ticketCounters[0]+'][schduleData]['+ticketCounters[1]+'][ticket]['+ticketCounters[2]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[0]).val();
	} else {
	var j= ticketCounters[0];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();	
    var country = $("#country").val();
    }    

	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
      if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }
    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        	$('.loaderbox').hide();
			        // $('.ticket_loader').loader('hide');		
			      if(response.xtraChargeFlag == true){
			      	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
			     }  else{
						$("#extracharge_"+j).html('');
					}    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 } else {
	 	$("#extracharge_"+j).html("");
	 }       
 })
 
  $(document).on("change",".calculationCurrencyRec", function(e){     
	

	var ticketCounter =  $(this).attr("track-element");
	ticketCounters = ticketCounter.split('_');
	var country =''; 
	if(ticketCounters.length==3){
		
		 var j= ticketCounters[2];
		 var pgstatus = $('input[name="schedule['+ticketCounters[0]+'][schduleData]['+ticketCounters[1]+'][ticket]['+ticketCounters[2]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="schedule['+ticketCounters[0]+'][schduleData]['+ticketCounters[1]+'][ticket]['+ticketCounters[2]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[0]).val();
	} else {
	var j= ticketCounters[1];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();	
    var country = $("#country").val();
    }    
	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
     if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }
    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        $('.loaderbox').hide();
			        // $('.ticket_loader').loader('hide');	
			      if(response.xtraChargeFlag == true){
			      	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
			     }  else{
						$("#extracharge_"+j).html('');
					}    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 } else {
	 	$("#extracharge_"+j).html("");
	 }       
 })

 $(document).on("change",".calculationCurrency", function(e){     
	

	var ticketCounter =  $(this).attr("id");
	ticketCounters = ticketCounter.split('_');
	var country =''; 
	if(ticketCounters.length==4){
		
		 var j= ticketCounters[3];
		 var pgstatus = $('input[name="schedule['+ticketCounters[1]+'][schduleData]['+ticketCounters[2]+'][ticket]['+ticketCounters[3]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="schedule['+ticketCounters[1]+'][schduleData]['+ticketCounters[2]+'][ticket]['+ticketCounters[3]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[1]).val();
	} else {
	var j= ticketCounters[1];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();	
    var country = $("#country").val();
    }    
	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
      if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }
    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        $('.loaderbox').hide();
			        // $('.ticket_loader').loader('hide');		
			       if(response.xtraChargeFlag == true){
			       	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
			     }  else{
						$("#extracharge_"+j).html('');
					}    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 } else {
	 	$("#extracharge_"+j).html("");
	 }       
 })

 $(document).on("click",".paymentgatewayRec", function(e){ 
     
	var ticketCounter =  $(this).attr("id");	
	ticketCounters = ticketCounter.split('_');
	var country ='';
	if(ticketCounters.length==4){
	
		 var j= ticketCounters[3];
		 var pgstatus = $('input[name="scheduleedit[Edit]['+ticketCounters[2]+'][Slot]['+ticketCounters[1]+'][Ticket]['+ticketCounters[3]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="scheduleedit[Edit]['+ticketCounters[2]+'][Slot]['+ticketCounters[1]+'][Ticket]['+ticketCounters[3]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[2]).val();

	} else {
	var j= ticketCounters[1];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();
    var country = $("#country"+j).val();	
    }    
	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
      if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }
    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        	$('.loaderbox').hide();
			        // $('.ticket_loader').loader('hide');
			      if(response.xtraChargeFlag == true){
			      	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
			     }  else{
						$("#extracharge_"+j).html('');
					}    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 } else {
	 	$("#extracharge_"+j).html("");
	 }       
 })


 $(document).on("change",".calculationCurencRecEdit", function(e){ 
     
	var ticketCounter =  $(this).attr("track-element");	
	
	ticketCounters = ticketCounter.split('_');
	
	var country ='';
	if(ticketCounters.length==3){
	
		 var j= ticketCounters[2];
		 var pgstatus = $('input[name="scheduleedit[Edit]['+ticketCounters[0]+'][Slot]['+ticketCounters[1]+'][Ticket]['+ticketCounters[2]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="scheduleedit[Edit]['+ticketCounters[0]+'][Slot]['+ticketCounters[1]+'][Ticket]['+ticketCounters[2]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[0]).val();
	    // alert(pgstatus+goEventstatus+country)
	} else {
	var j= ticketCounters[1];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();
    var country = $("#country"+j).val();	
    }    
	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
     if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }

    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        // $('.ticket_loader').loader('hide');	
			        $('.loaderbox').hide();
			       if(response.xtraChargeFlag == true){
			       	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
			     }  else{
						$("#extracharge_"+j).html('');
					}    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 } else {
	 	$("#extracharge_"+j).html("");
	 }       
 })

  $(document).on("blur",".calculationPriceRecEdit", function(e){ 
     
	var ticketCounter =  $(this).attr("track-element");	
	ticketCounters = ticketCounter.split('_');
	
	var country ='';
	if(ticketCounters.length==3){
	
		 var j= ticketCounters[2];
		 var pgstatus = $('input[name="scheduleedit[Edit]['+ticketCounters[0]+'][Slot]['+ticketCounters[1]+'][Ticket]['+ticketCounters[2]+'][Setting][payment_gateway_fee]"]:checked').val();
         var goEventstatus = $('input[name="scheduleedit[Edit]['+ticketCounters[0]+'][Slot]['+ticketCounters[1]+'][Ticket]['+ticketCounters[2]+'][Setting][goeventz_fee]"]:checked').val();	
	     var country = $("#country"+ticketCounters[0]).val();
	     
	} else {
	var j= ticketCounters[1];
	
	var pgstatus = $('input[name="ticket['+j+'][Paid][Setting][payment_gateway_fee]"]:checked').val();
    var goEventstatus = $('input[name="ticket['+j+'][Paid][Setting][goeventz_fee]"]:checked').val();
    var country = $("#country"+j).val();	
    }    
	var currencyID = $( "#ticketCurrency_"+j ).val();
	var taxableAmount = $("#price_box"+j).val();
     if(typeof (event_id) === 'undefined'){
	   	  event_id =0;
	   }

    var pg_charges =0;
    var cmpGocharges =0;
    var per_ticket_charges = 0;
    var totalGoCharges = 0;
    	
    if(taxableAmount > 0 ){
    	$('.loaderbox').show();
    	 // $('.ticket_loader').loader('show');
	  $.ajax({
				  type: "POST",
				  url: SITE_URL+'/event/extracharge',
				  data: {'user_id':foruserid,'event_id':event_id,'country':country,'createdDate':createdDate,'goEventstatus':goEventstatus,'pgstatus':pgstatus,'price':taxableAmount,'currencyID':currencyID,'_token': $('input[name=_token]').val()},
				  dataType: 'json',
			        success: function (response) {	
			        $('.loaderbox').hide();	
			        // $('.ticket_loader').loader('hide');		
			      if(response.xtraChargeFlag == true){
			      	$(".passonDiv"+j).show();
			       var goTax ='';
			       // if(country!="" && country.toLowerCase()=="india" && response.indiaGovTax > 0){
				       var feeperticket_go = response.per_ticket_fee+response.go_charges;
	                    goTax = '<small class="tex_info pull-right"><i class="fa fa-info" aria-hidden="true"></i><div class="tex_info_text"><div class="mb5">'+response.payment+'</div><div class="mb5">'+response.goevent+'</div><div>'+response.service+'</div></div></small>';
                   // }
					
			        // startax='aslkjfd aldfskj sldfj lskdjflkrelkwjrlekj sdlfkjsldfkj'
			        	
			        $("#extracharge_"+j).html("<div class='tex_box ' >Online Fee&nbsp;:<span>"+response.codeCur+' '+response.goeventFee+"&nbsp;&nbsp;"+goTax+"</span> Buyer Pays&nbsp;:<span>"+response.codeCur+' '+response.totalFee+"</span> You Receive&nbsp;:<span>"+response.codeCur+' '+response.youGet+"</span></div>");
			     }  else{
						$("#extracharge_"+j).html('');
					}    
			        },
			        error: function (response) {
			        	$('.loaderbox').hide();
			        	// $('.ticket_loader').loader('hide');
			            alert('Please refresh page and try again.'); 
			            $(this).text('Submit').prop("disabled", false);
			        }
		});
	 } else {
	 	$("#extracharge_"+j).html("");
	 }       
 })

 })
















