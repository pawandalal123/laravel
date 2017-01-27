@extends('layout.default')
@section('content')

<div class="container-fluid mt45 faqtophead text-center">
  <div class="row">
    <div class="col-sm-12">
      <h1>Got Questions?</h1>
    </div>
  </div>
</div>
<section>
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="typebox">
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion1" aria-expanded="true" aria-controls="CustomerQuestion1">
                 How can I get assistance if I need it?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                <storng>For Quick Assistance</storng><br> 
                Call the Customer Relationship Team:<br>
                We are  here for you. Can Contact us at -91 9555601111<br>
                <storng>Email:</storng><br>
                We looks forward to helping you with your inquiry. We respond to email messages in the order that they are received, and we will respond to your email as quickly as possible. Write to us at support@goeventz.com<br>
                <storng>Live chat:</storng><br>
                Live chat option is available on our website to help you get in touch with us.<br>
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion2" aria-expanded="true" aria-controls="CustomerQuestion2">
                  Do I need to register to book tickets?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                No, it is not necessary to login to book your tickets.  In order to book your tickets from GoEventZ you can go forward without Login but the website will ask for your Name, Contact Details & EmailId for booking purposes.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion3" aria-expanded="true" aria-controls="CustomerQuestion3">
                  Are there any benefits of an account with you?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
                Yes we would suggest you to register on our website, since it would give you the following features: - 
                <ol>
                  <li>Access your booking history.</li>
                  <li>Your own account allows you to save your card details and there you can book tickets with just a click.</li>
                </ol>
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion4" aria-expanded="true" aria-controls="CustomerQuestion4">
                  How can I confirm whether my tickets have been booked?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               As soon as your booking is confirmed, a confirmation e-mail containing your booking details would be sent across to your contact details entered while booking. If you did not receive a confirmation SMS/email for a transaction. This may be due to a temporary network fluctuation. We are so sorry to know about this, drop in your contact details entered while transaction at  support@goeventz.com, LIVE CHAT  or Call us at -91 9555601111.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion5" aria-expanded="true" aria-controls="CustomerQuestion5">
                  My transaction got timed out? What do I do?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               While making the payment, the transaction session is timed. In case of any network issues it may err.  You can definitely book again within minutes.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion6" aria-expanded="true" aria-controls="CustomerQuestion6">
                  What should i do when my transaction is not going through? Please help.
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               Go Eventz wants to know more about your concern and help you out. Please share with us the contact details (email id and contact number) entered while transacting and the screenshot of the error if any at helpdesk  support@goeventz.com or via LIVE CHATS. We can be contacted at -91 9555601111.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion7" aria-expanded="true" aria-controls="CustomerQuestion7">
                  My booking has been rejected (failed), but my credit/debit card has been charged. What do I do?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               This is a very rare situation, and can occur in case of a network fluctuation at the time of confirming the transaction. Please be assured that if the ticket has not been successfully booked, your card payment will be reversed within 10- 15 working days.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion8" aria-expanded="true" aria-controls="CustomerQuestion8">
                  Can we cancel or replace our tickets? 
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               Once the tickets are confirmed it is deemed as sold, once a ticket has been paid for, it cannot be replaced or cancelled.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion9" aria-expanded="true" aria-controls="CustomerQuestion9">
                  What happens if an Event is cancelled?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               If the event is cancelled by the organizer, GoEventz will update it on the website and a personal mail will be sent to you. If the event that you registered for is a paid event you will also be notified about your fee refund in the mail. However, if you are cancelling your registration for an event that you have registered for, then your fee refund will depend on the Organizer's terms & conditions. For more information you can Contact us on 91 9555601111 or Write to us at support@goeventz.com or Live Chat with Us.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion10" aria-expanded="true" aria-controls="CustomerQuestion10">
                  In the situation where event is cancelled . Will I receive the complete refund for my transaction?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion10" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               Yes, you will  receive the complete refund on the booked tickets as soon as the organizer provides us with an update on the said event. You can count on us.
              </div>
            </div>
          </div>
          <div class="panel mypanel">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#CustomerQuestion11" aria-expanded="true" aria-controls="CustomerQuestion11">
                  Can I get tickets instead of the refund for my cancelled show?
                <span><i class="fa fa-angle-down"></i></span></a>
              </h4>
            </div>
            <div id="CustomerQuestion11" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
              <div class="panel-body">
               We at GoEventz will refund your amount for the cancelled event. Your tickets were confirmed for a particular show which has been cancelled due to some said issues. You would surely get the refund amount back to your account.
              </div>
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion1" aria-expanded="true" aria-controls="OrganiserQuestion1">
                What does GoEventz  do for me and my organization?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              With your event listing on our website there are attractive benefits that come your way.
              <ul>
                <li>Social Media Marketing</li>
                <li>Access to our large customer base</li>
                <li>Increase sign-ups for events</li>
                <li>Encourage referrals</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion3" aria-expanded="true" aria-controls="OrganiserQuestion3">
                How much GoEventz charge for Event Publishing ?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              GoEventz is not charging anything for publishing the events. We want you to experience GoEventz and also want you to provide us an opportunity to serve you better. We focus on providing one step place for posting your all events.
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion4" aria-expanded="true" aria-controls="OrganiserQuestion4">
                How much commission do GoEventz charge ?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              GoEventz has waived off the commission till  March, 2016. After March, 2016 we will charge 2% commission.
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion5" aria-expanded="true" aria-controls="OrganiserQuestion5">
                How can i add my event on your website?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion5" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              You can login to the GoEventz  website and go to “Create Event”  Tab and fill in the required event details as per your requirement.
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion6" aria-expanded="true" aria-controls="OrganiserQuestion6">
                Is the information that I submit secure?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion6" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              At GoEventz we follow industry guidelines, software practices to keep data safe. We endeavor to keep all the information that you provide secure.
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion7" aria-expanded="true" aria-controls="OrganiserQuestion7">
                Do I need to have an account with the bank that GoEventz banks with?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion7" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              No, you do not need to set-up a bank account near GoEventz office. At the time of registration, you are required to provide us with your bank details. We will send you a cheque or write your payment as per the payment option that you have selected.
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion8" aria-expanded="true" aria-controls="OrganiserQuestion8">
                When will we recieve the amount from GoEventz for the event ?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              At GoEventz we process the amount within  48 working hours after the event ends through the organizers chosen payment method.
            </div>
          </div>
        </div>
        <div class="panel mypanel">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#OrganiserQuestion9" aria-expanded="true" aria-controls="OrganiserQuestion9">
                Where can I check  the Sales report for my event?
              <span><i class="fa fa-angle-down"></i></span></a>
            </h4>
          </div>
          <div id="OrganiserQuestion9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">
              GoEventz will send you email alerts on your email id as and when the ticket is booked. Also, you are provided report section to view the booking details.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
 $(document).ready(function(){
    $(".mypanel a").click(function()
    {
     if($(this).hasClass('ge'))
     {
      $(".mypanel a").removeClass("ge i");
     }
     else
     {
       $(this).addClass("ge i");
     }
});
});
</script>
@include('includes.web.usefulllink')

@stop




