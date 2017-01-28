$(function()
		{
			var cityname = $( "#cityname" ).val();
            $( "#cityname" ).keyup(function() {
			  $('.selectfrom').val('');
			});
			$('#changecityname').click(function() 
			{
				var cityval = $(this).val('');
			});
			$('#changecityname').blur(function() 
			{
				$('.cityhide').show();
				$(this).hide();
			});
			$( "#cityname" ).autocomplete({
			source: APIURL+'/city/citylistajax',
			minlength: 1,
			select: function( event, ui ){
				
			 $('.selectfrom').val('dropdown');
			}
			});
			$( "#changecityname" ).autocomplete({
			source: APIURL+'/city/citylistajax',
			minlength: 1,
			select: function( event, ui ){
				
			 $('.selectfrom').val('dropdown');
			  var location = ui.item.label;
			  var city = location.split(",")[0];

			  $.post(APIURL+'/city/changecokkies',{'location':city},function(data,status)
		     {
		     	window.location.href = SITE_URL;
			 });
			}
			});
			$( "#citynamemodel" ).autocomplete({
			source: APIURL+'/city/citylistajax',
			minlength: 1,
			select: function( event, ui ){
				
			 $('.selectfrom').val('dropdown');
			 
			}
			});
			   //for keywords//
			// $( "#categories" ).autocomplete({
			// source: APIURL+'/category/keywordajax?city='+cityname,
			// minlength: 1,
			// select: function( event, ui ) {
		
			// }
			// });
			$(".input_vmobile").hide();
			$(".input_v").hide();
			$("#categoriesmobile").keyup(function(){
			var searchOption = $(this).val();
			var htmlContent = '';
			if(searchOption.length > 2){
			$.ajax({
			type: "GET",
			contentType: "application/json; charset=utf-8",
			url: APIURL+'/category/keywordajax?term='+searchOption,
			dataType: "json",
			success: function (data) {
			if(data.length > 0 ){
			$.each(data, function(index, element) {
				
				
				if(element.type==1)
				{
					htmlContent+='<li  id='+element.label+' rel='+element.url+'><a href='+SITE_URL+'event/'+element.url+'/'+element.label+'><span>'+element.value+'</span></a></li>';
				}
				else if(element.type==3)
				{
					
					htmlContent+='<li  id='+element.label+' rel='+element.url+'><a href='+SITE_URL+element.url+'><span>'+element.value+'</span></a></li>';

				}
				else
				{

					htmlContent+='<li class="makeeventurl" id='+element.label+' ><span>'+element.value+'</span></li>';
				}
				
			});
				$(".input_vmobile #userSuggestions").html(htmlContent);
				$(".input_vmobile").show();
			}
			else
			{
				$(".input_vmobile #userSuggestions").html('No Result Found.');
			    $(".input_vmobile").show();

			}
			},
			});
			}                              
			});

			$("#categories").keyup(function(){
			var searchOption = $(this).val();
			var htmlContent = '';
			if(searchOption.length > 2){
			$.ajax({
			type: "GET",
			contentType: "application/json; charset=utf-8",
			url: APIURL+'/category/keywordajax?term='+searchOption,
			dataType: "json",
			success: function (data) {
			if(data.length > 0 ){
			$.each(data, function(index, element){
				if(element.type==2)
				{
					htmlContent+='<li class="makeeventurl" id='+element.label+' ><i class="fa fa-hashtag"></i><span>'+element.value+'</span></li>';
				}
				else if(element.type==1)
				{
					htmlContent+='<li  id='+element.label+' rel='+element.url+'><a href='+SITE_URL+'event/'+element.url+'/'+element.label+'><i class="fa fa-ticket"></i><span>'+element.value.substr(0,50)+', <small>'+element.city+', '+element.date+'</small></span></a></li>';
				}
				else if(element.type==3)
				{
					htmlContent+='<li  id='+element.label+' rel='+element.url+'><a href='+SITE_URL+element.url+'><i class="fa fa-user"></i><span>'+element.value+'</span></a></li>';
				}
				
				
			});
			$(".input_v #userSuggestions").html(htmlContent);
			    $(".input_v").show();
			
			}
			else
			{
				$(".input_v #userSuggestions").html('No Result Found.');
			    $(".input_v").show();
			}
			},
			});
			}                              
			});
			
			$( "#autocomplete" ).autocomplete({
			  source: APIURL+'/city/citylistajax',
			});


		});
