$(document).ready(function(){

 

            $("#profiledit-form").validate({
                rules: {
						name: {
							
							letterslonly: true
						},
						organizer_description: "required",
						facebook_url: {
										url: true
									},
						twitter_url: {
										url: true
									},
						linkedin_url: {
										url: true
									},
						googleplus_url: {
										url: true
									},
						mobile: {
										wwwMobile: true,
										minlength: 10,
										maxlength: 12
									},
						profile_image: {
									    accept: "jpg|png|jpeg|pjpeg|gif"
										
									}
									,
						profile_banner: {
									    accept: "jpg|png|jpeg|pjpeg|gif"
										
									}
                },
                messages: {
                    title: "Please enter organizer name",
                    venue_location: "Please enter organizer description", 
					facebook_url: {						
						url : "Please enter valid url"
					},
					twitter_url: {						
						url : "Please enter valid url"
					},
					linkedin_url: {						
						url : "Please enter valid url"
					},
					googleplus_url: {						
						url : "Please enter valid url"
					}
					,
					mobile: {						
						phoneUS : "Please enter valid mobile number"
					}
                },
               
            });
       

$("#profiledit-form").rules('add', { wwwMobile: "#evtstartdate" });

});
 
 // function readURLChangeImagelogo(input) {
	// 	    if (input.files && input.files[0]) {
 //                var reader = new FileReader();
 //                reader.onload = function (e) {
 //                    $('#orglogo').css('background-image', 'url('+e.target.result+')');
	// 				//$('#change_pic'+imageId[1]).hide()
					
 //                }
 //                reader.readAsDataURL(input.files[0]);
 //            }
 //        }
		// function readURLChangeImagebanner(input) {
		//    var imageId = ($(input).attr("id")).split('getimage');
		//    if (input.files && input.files[0]) {
  //               var reader = new FileReader();
  //               reader.onload = function (e) {
  //                   $('#orgbanner').css('background-image', 'url('+e.target.result+')');
					
		// 			$("#imageprofile").hide();
  //               }
  //               reader.readAsDataURL(input.files[0]);
  //           }
  //       }

  //       function readURLChangeImagesell(input) {
  //      //var imageId = ($(input).attr("id")).split('getimage');
  //      if (input.files && input.files[0]) {
  //         var reader = new FileReader();
  //         reader.onload = function (e) {
  //             $('#pimgstore').css('background-image', 'url('+e.target.result+')');
  //             $('#change_pic').hide()
  //             $('#change').show()
  //         }
  //         reader.readAsDataURL(input.files[0]);
  //     }
  // }
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