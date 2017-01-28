
   
  
    function PreviewImage(no) {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("uploadImage"+no).files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("uploadPreview"+no).src = oFREvent.target.result;
        };
    }

$( document ).ready(function() {
	
	$('#datetimepicker2').datetimepicker({
		yearOffset:0,
		lang:'en',
		timepicker:false,
		format:'Y-m-d',
		formatDate:'Y-m-d',
		//minDate:'-1970-01-02', // yesterday is minimum date
		//maxDate:'+2025-01-02' // and tommorow is maximum date calendar
	});
	$('#datetimepicker1').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:30
	});
	$('#datetimepicker21').datetimepicker({
		yearOffset:0,
		lang:'en',
		timepicker:true,
		format:'Y-m-d H:i',
		formatDate:'Y-m-d H:i',
		minDate:0,
		
		//minDate:'-1970-01-02', // yesterday is minimum date
		//maxDate:'+2025-01-02' // and tommorow is maximum date calendar
	});
	$('#datetimepicker11').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:30

	});
    
	
		
});

$(document).on("mouseover",".ddcalendar", function(e){ 
	$('.ddcalendar').datetimepicker({
		yearOffset:0,
		lang:'en',
		timepicker:true,
		format:'Y-m-d H:i',
		formatDate:'Y-m-d H:i',
		minDate:0
		//minDate:'-1970/01/2',
       // maxDate:'+1970/01/2'
		//minDate:'-1970-01-02', // yesterday is minimum date
		//maxDate:'+2025-01-02' // and tommorow is maximum date calendar
	});
})
$(document).on("mouseover",".dtcalendar", function(e){ 
	$('.dtcalendar').datetimepicker({
		datepicker:false,
		format:'H:i',
		step:30
	});
})



 

 