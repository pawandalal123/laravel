$(document).ready(function(){


            //form validation rules
            $("#profiledit-form").validate({
                rules: {
						organizer_name: {
							required: true,
							lettersspecialonly: true
						},
						tabname: {
							required: true,
							lettersspecialonly: true
						},
						organization_url: {
							firstcharateralpha: true
					    },
						organizer_description: "required",
						facebook: {
						url: true
						},
						twitter: {
						url: true
						},
						linkdin: {
						url: true
						},
						googleplus_url: {
						url: true
						},
						organization_url: {
						lettersspecialonly: true
						},
						organization_logo: {
									    accept: "jpg|png|jpeg|pjpeg|gif"
										
									}
									,
						organization_image: {
									    accept: "jpg|png|jpeg|pjpeg|gif"
										
									}
                },
                messages: {
                    title: "Please enter organizer name",
                    venue_location: "Please enter rganizer description", 
					facebook: {						
						url : "Please enter valid url"
					},
					twitter: {						
						url : "Please enter valid url"
					},
					linkdin: {						
						url : "Please enter valid url"
					},
					googleplus_url: {						
						url : "Please enter valid url"
					},
					organization_url: {						
						url : "Please enter valid url"
					}
                },
               
            });
    });    
 
    $("#orgurlnew").click(function() {
			
			 if($(this).is(":checked")) {		
				$("#changeurl").show();
			} else {
				$("#changeurl").hide();
			}
			
		});

		 
  function readURLChangeImagesell(input) {
       //var imageId = ($(input).attr("id")).split('getimage');
       if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#pimgstore').css('background-image', 'url('+e.target.result+')');
              $('#change_pic').hide()
              $('#change').show()
          }
          reader.readAsDataURL(input.files[0]);
      }
  }
  function ChangeImageBanner(input) {
       //var imageId = ($(input).attr("id")).split('getimage');
       if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#pimgstore1').css('background-image', 'url('+e.target.result+')');
            $('#change_pic1').hide()
          }
          reader.readAsDataURL(input.files[0]);
      }
  }
      