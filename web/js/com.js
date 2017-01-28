$(document).ready(function() {
    var s = $(".navbar-default");
    var pos = s.position();                    
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if (windowpos >= 100) {
            s.addClass("topstick");
        } else {
            s.removeClass("topstick");
        }
    });
 });


 $(document).ready(function() {
    var a = $("#navbar-example");
    var pos = a.position();                    
    $(window).scroll(function() {
        var windowpos = $(window).scrollTop();
        if (windowpos >= 220) {
            a.addClass("stick1");
        } else {
            a.removeClass("stick1");
        }
    });
 });

 // -------- Mobile Navigation --------
$(document).ready(function(){
  $('.mobilenavi').click(function(){
    $('.left-section').toggleClass('commonnavi');
    $('.mobilenavi').toggleClass('commonnavi');
  });
  
$(".alertcontent strong").click(function(){
    $(".commonalert").fadeOut();
});
});

