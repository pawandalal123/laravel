@extends('layout.default')
@section('content')

<section>
  <div class="container-fluid mt45">
    <div class="row landingboxTop">
      <div class="col-sm-8 text-center">
        <h1> Let people attend and turn your events into lasting memories!</h1>
        <h3>Sell Your event tickets online with Goeventz!</h3>
      </div>
      <div class="col-sm-4">
        <div class="landingform">
          <span>About you</span>
         {!! csrf_field() !!}
            <div class="form-group">
              <input type="text" class="form-control" name="name" placeholder="your name" >
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" placeholder="your email id" >
            </div>
            <div class="form-group">
              <input type="tel" class="form-control" name="mobile" placeholder="your contact number" >
            </div>
            <div class="form-group">
              <textarea type="tel" class="form-control" name="message" placeholder="your message" ></textarea>
            </div>
            <div class=" text-center">
              <button class="btn btn-primary" onClick = "submitenquiry();">Request callback</button>
            
          </form>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="landingbox">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 padb15">
        <h1>Right Platform to sell your tickets</h1>
        <h3>Create your event in few minutes & start selling your tickets</h3>
      </div>
      <div class="col-xs-6 col-sm-3 text-center box2icon">
        <a href=""><span><i class="fa fa-television"></i></span>
        <p>Online Presence</p></a>
      </div>
      <div class="col-xs-6 col-sm-3 text-center box2icon">
        <a href=""><span><i class="fa fa-line-chart"></i></span>
        <p>Track Ticket Sales</p></a>
      </div>
      <div class="col-xs-6 col-sm-3 text-center box2icon">
        <a href=""><span><i class="fa fa-credit-card"></i></span>
        <p>Payment Collection</p></a>
      </div>
      <div class="col-xs-6 col-sm-3 text-center box2icon">
        <a href=""><span><i class="fa fa-bell"></i></span>
        <p>Realtime Notifications</p></a>
      </div>
    </div>
  </div>
</section>

<section class="landingbox2">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 landingbox2Left">
        <h1>Create a buzz</h1>
        <h3>We will Promote your event through:</h3>
        <p><i class="fa fa-angle-double-right"></i>Search Engine Promotion</p>
        <p><i class="fa fa-angle-double-right"></i>Mobile Marketing</p>
        <p><i class="fa fa-angle-double-right"></i>Social Media</p>
        <p><i class="fa fa-angle-double-right"></i>Affiliate Marketing</p>
        <p><i class="fa fa-angle-double-right"></i>Email Marketing</p>
        <p><i class="fa fa-angle-double-right"></i>Search Engine Promotion</p>
      </div>
      <div class="col-sm-6 landingbox2Right text-center">
        <img src="{{URL::asset('web/images/eventprcess.png')}}">
      </div>
    </div>
  </div>
</section>


<section class="landingbox ">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1>Benefits You Get</h1>
      </div>
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-television"></i></span>
          </div>
          <div class="media-body">
            Go live and start selling in just 2 minutes.
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-credit-card"></i></span>
          </div>
          <div class="media-body">
            Accept online payments through multiple payment options.
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-inr"></i></span>
          </div>
          <div class="media-body">
           Sell Your event tickets online with Goeventz!
          </div>
        </div>
      </div>      
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-globe"></i></span>
          </div>
          <div class="media-body">
           Our High Search Engines Crawl and Indexing rate will help find out your event easily.
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-envelope-o"></i></span>
          </div>
          <div class="media-body">
            Send us your event details & we can take care of the rest.
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-bullhorn"></i></span>
          </div>
          <div class="media-body">
            We will Create Offer and contest promotion campaign to create buzz of your campaign
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-mobile"></i></span>
          </div>
          <div class="media-body">
            Reach People on Any Device. Our Responsive web design can adjust itself within any device.
          </div>
        </div>
      </div>
      <div class="col-sm-4 ">
        <div class="media box3icon">
          <div class="media-left">
            <span><i class="fa fa-cogs"></i></span>
          </div>
          <div class="media-body">
           Your event page comes pre integrated with a payment gateway.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="landingbox landingbox1">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1>We empower all Kinds of events</h1>
      </div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/confrence.png')}}"><p>Conferences</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/business.png')}}"><p>Business</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/tech.png')}}"><p>Technology</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/alimini.png')}}"><p>Alumni Meets</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/seminar.png')}}"><p>Seminar</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/club.png')}}"><p>Clubs & Parties</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/sport.png')}}"><p>Sports</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/tran.png')}}"><p>Trainings</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/trip.png')}}"><p>Trips</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/music.png')}}"><p>Music Concerts</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/community.png')}}"><p>Community Meets</p></div>
      <div class="col-xs-4 col-sm-2 text-center"><img src="{{URL::asset('web/images/EventsIcon/fund.png')}}"><p>Fund Raising Events</p></div>
    </div>
  </div>
</section>

@stop