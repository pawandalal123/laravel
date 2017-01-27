@extends('layout.default')
@section('content')

<section>
  <div class="container mt45">
    <div class="row">
      <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
        <div class="main-errorbox">
          <i class="fa fa-exclamation-triangle"></i>
          <h1>404. The requested URL was not found on this server.</h1>
          <p>Something not where you expected it to be. Either we don't have what you're looking for or something broken on our website. In both cases, we're quite sorry.</p>
          <p>Please click here to go to <a href="{!! URL::to('/') !!}">home page</a> or <a href="{!! URL::to('/contactus') !!}">contact us</a>.</p>
        </div>
      </div>
    </div>
  </div>
</section>
    
@stop


<script>
  $(document).ready(function() {  
var stickyNavTop = $('.nav').offset().top;  
  
var stickyNav = function(){  
var scrollTop = $(window).scrollTop();  
       
if (scrollTop > stickyNavTop) {   
    $('.nav').addClass('sticky');  
    $('.sticky-div-container').css('display','block');
} else {  
    $('.nav').removeClass('sticky'); 
    $('.sticky-div-container').css('display','none');  
}  
};  
  
stickyNav();  
  
$(window).scroll(function() {  
    stickyNav();  
});  

}); 
</script>
<script type="text/javascript">
$(function () {
    
    var filterList = {
    
      init: function () {
      
        // MixItUp plugin
        // http://mixitup.io
        $('#portfoliolist').mixitup({
          targetSelector: '.portfolio',
          filterSelector: '.filter',
          effects: ['fade'],
          easing: 'snap',
          // call the hover effect
          onMixEnd: filterList.hoverEffect()
        });       
      
      },
      
      hoverEffect: function () {
      
        // Simple parallax effect
        //$('#portfoliolist .portfolio').hover(
          //function () {
            //$(this).find('.label').stop().animate({bottom: 0}, 200, 'easeOutQuad');
            //$(this).find('img').stop().animate({top: -30}, 500, 'easeOutQuad');       
         // },
         // function () {
           // $(this).find('.label').stop().animate({bottom: -40}, 200, 'easeInQuad');
           // $(this).find('img').stop().animate({top: 0}, 300, 'easeOutQuad');               
          //}   
        //);        
      
      }

    };
    
    // Run the show!
    filterList.init();
  });

  $(document).ready(function() {
    $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        }); 
 
        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });

    
    $('a[href^="#"]').click(function(event) {
      var id = $(this).attr("href");
      var offset = 0;
      var target = $(id).offset().top;

      $('html, body').animate({scrollTop:target}, 500);
      event.preventDefault();
    });
      });
</script>

  </body>
</html>