@extends('layout.default')
@section('content')

<div class="container-fluid faqtophead mt45 text-center">
	<div class="row">
		<div class="col-sm-12">
			<h1>Open Positions</h1>
		</div>
	</div>
</div>
<section>
 
  <div class="container mt15">
    <div class="row ge-position-box">
      <div class="col-sm-12">
        <h2>Open Positions</h2>
        @if(Session::has('message'))
                      <div class="alert alert-dismissible alert-{{ Session::get('alert-class', 'alert-info') }} mt10    ">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                      {{ Session::get('message') }}
                      </div>
                        <script type="text/javascript">
                        $(document).ready(function(){

                        $onload={};
                        $onload.type='update-event';
                        $onload.typeValue=null;
                        $onload.action='update';
                        $onload.page='update-event';
                        $onload.element=null; 
                        $onload.referrer=document.referrer;
                        $onload.page_url=window.location.href;
                        track($onload);


                        });
                        </script>
                    @endif
        <div class="ge-position">
          <a href="#headMarketing" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="androidDveloper"><span>Senior Executive - Sales & Business Development</span> - <span>Mumbai/Pune</span>,&nbsp;&nbsp;<span>Chennai</span><span style="font-size:13px; margin-left:10px; color:#ccc;" class="positionMob">|</span><span style="font-size:13px; margin-left:10px;color:#4788F2;" class="positionMob">Vacancies - 2</span><small>Apply</small></a>
        </div>
        <div class="collapse" id="headMarketing">
          <div class="job-description-box">
            <div class="row">
              <div class="col-md-8 col-sm-7">
                <div class="job-decription">
                  <h3>Job Description:</h3>
                  <p>
                    We are looking for <strong>Sales Executive</strong> with 3+ years of experience.
                  </p>
                  <h4>No. of Vacancies</h4>
                  <div>Mumbai / Pune : 1</div>
                  <div>Chennai : 1</div>
                  <h4>Job Responsibilities</h4>
                  <ul>
                    <li>Selling the product by establishing contact and developing relationships with prospects; recommending solutions.</li>
                    <li>Document qualified opportunities to pass on to Business Development Managers</li>
                    <li>Be the first line of contact, quick on your feet, and handle objections like a pro</li>
                    <li>Have a deep understanding of the entire sales process</li>
                    <li>Negotiating the terms of an agreement and closing sales.</li>
                    <li>Effectively search prospective clients and generate sales leads.</li>
                    <li>Aiming to meet or exceed targets.</li>
                    <li>Listening to customer requirements and presenting appropriately to make a sale.</li>
                  </ul>
                  <h4>Requirements:</h4>
                  <ul>
                    <li>Minimum 3+ year of relevant work experience.</li>
                    <li>Strong Communication skills with strong business related knowledge.</li>
                    <li>The ability and desire for sales job with a confident and determined approach.</li>
                    <li>Highly self motivated and ambitious in achieving goals.</li>
                    <li>Should possess the skill to work both in team and also perform independently.</li>
                    <li>Should be capable of thriving in the competitive markets.</li>
                    <li>Presentation Skills, Client Relationships, High Energy Level, Meeting Sales Goals, Creativity, Independence.</li>
                  </ul>
                </div>
              </div>
              {!! Form::open(['url' => 'career/postjob', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'career-form1','enctype'=>'multipart/form-data']) !!} 
               <div class="col-md-4 col-sm-5">
                <div class="job-apply-form">                
                   <h3>Apply for this Job <h3>
                  <div class="form-group">
                    <label class="labeluppercase">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label class="labeluppercase">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label class="labeluppercase">Attach CV</label>
                    <div class="attachcv">
                      <input type="file" name="resume" multiple="" data-multiple-caption="{count} files selected" class="inputfile inputfile-6" id="file-1" required>
                      <label for="file-1"><span></span> <strong>Browse</strong></label>
                    </div>
                  </div>
                 <!--  <div class="form-group mt15">
                    <small>How did you hear about us ?</small>
                    <select class="form-control">
                      <option>-Please select option-</option>
                      <option>For friends</option>
                      <option>For friends</option>
                      <option>For friends</option>
                      <option>For friends</option>
                    </select>
                  </div> -->
                  <div class="text-center mt15">
                    <button class="btn btn-primary">Send</button>
                  </div>                  
                </div>
               </div>
               {!! Form::close() !!}
            </div>
          </div>
        </div>

        <div class="ge-position">
          <a href="#SeniorSupportSpecialist" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="iOSDveloper"><span>Executive - Sales & Business Development</span> - <span>Gurgaon</span>,&nbsp;&nbsp;<span>Mumbai/Pune</span>,&nbsp;&nbsp;<span>Chennai</span><span style="font-size:13px; margin-left:10px; color:#ccc;" class="positionMob">|</span><span style="font-size:13px; margin-left:10px;color:#4788F2;" class="positionMob">Vacancies - 11</span><small>Apply</small></a>
        </div>
        <div class="collapse" id="SeniorSupportSpecialist">
          <div class="job-description-box">
            <div class="row">
              <div class="col-md-8 col-sm-7">
                <div class="job-decription">
                 <h3>Job Description:</h3>
                  <p>
                    We are looking for <strong>Sales Executive</strong> with 1+ years of experience.
                  </p>
                  <h4>No. of Vacancies</h4>
                  <div>Gurgaon : 6 - 8</div>
                  <div>Mumbai / Pune : 2</div>
                  <div>Chennai : 1</div>  
                  <h4>Job Responsibilities</h4>
                  <ul>
                    <li>Selling the product by establishing contact and developing relationships with prospects; recommending solutions.</li>
                    <li>Document qualified opportunities to pass on to Business Development Managers</li>
                    <li>Be the first line of contact, quick on your feet, and handle objections like a pro</li>
                    <li>Have a deep understanding of the entire sales process</li>
                    <li>Negotiating the terms of an agreement and closing sales.</li>
                    <li>Effectively search prospective clients and generate sales leads.</li>
                    <li>Aiming to meet or exceed targets.</li>
                    <li>Listening to customer requirements and presenting appropriately to make a sale.</li>
                  </ul>
                  <h4>Requirements:</h4>
                  <ul>
                    <li>Minimum 1 year of relevant work experience.</li>
                    <li>Strong Communication skills with strong business related knowledge.</li>
                    <li>The ability and desire for sales job with a confident and determined approach.</li>
                    <li>Highly self motivated and ambitious in achieving goals.</li>
                    <li>Should possess the skill to work both in team and also perform independently.</li>
                    <li>Should be capable of thriving in the competitive markets.</li>
                    <li>Presentation Skills, Client Relationships, High Energy Level, Meeting Sales Goals, Creativity, Independence.</li>
                  </ul>
                </div>
              </div>
                {!! Form::open(['url' => 'career/postjob', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'career-form2','enctype'=>'multipart/form-data']) !!}   
                 <div class="col-md-4 col-sm-5">
                <div class="job-apply-form">                
                   <h3>Apply for this Job <h3>
                  <div class="form-group">
                    <label class="labeluppercase">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label class="labeluppercase">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label class="labeluppercase">Attach CV</label>
                   <div class="attachcv">
                      <input type="file" name="resume" multiple="" data-multiple-caption="{count} files selected" class="inputfile inputfile-6" id="file-2" required>
                      <label for="file-2"><span></span> <strong>Browse</strong></label>
                    </div>
                  </div>
                 <!--  <div class="form-group mt15">
                    <small>How did you hear about us ?</small>
                    <select class="form-control">
                      <option>-Please select option-</option>
                      <option>For friends</option>
                      <option>For friends</option>
                      <option>For friends</option>
                      <option>For friends</option>
                    </select>
                  </div> -->
                  <div class="text-center mt15">
                    <button class="btn btn-primary">Send</button>
                  </div>                  
                </div>
              </div>
             {!! Form::close() !!}
            </div>
          </div>
        </div>

        <div class="ge-position">
          <a href="#DigitalMarketingSpecialist" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="salesAssociate"><span>iOS Developer</span> - <span>Gurgaon</span><span style="font-size:13px; margin-left:10px; color:#ccc;" class="positionMob">|</span><span style="font-size:13px; margin-left:10px;color:#4788F2;" class="positionMob">Vacancy - 1</span><small>Apply</small></a>
        </div>
        <div class="collapse" id="DigitalMarketingSpecialist">
          <div class="job-description-box">
            <div class="row">
              <div class="col-md-8 col-sm-7">
                <div class="job-decription">
                 <h3>Job Description:  </h3>
                  <p>
                    We are looking for <strong>iOS</strong> Developers with 2+ years of sound experience in developing consumer facing mobile apps for <strong>iOS</strong> platform.
                  </p>
                  <h4>Job Responsibilities</h4>
                  <ul>
                    <li>Design and build advanced applications for the <strong>iOS</strong> platform.</li>
                    <li>Collaborate with cross­functional teams to define, design, and ship new features.</li>
                    <li>
                      Designing mobile application interface design, navigation, and presentation along with developing custom controls as required by the application.
                    </li>
                    <li>Work with outside data sources and APIs.</li>
                    <li>Unit­test code for robustness, including edge cases, usability, and general reliability.</li>
                    <li>Work on bug fixing and improving application performance.</li>
                    <li>Continuously discover, evaluate, and implement new technologies to maximize development efficiency.</li>
                    <li>Should be able to utilize native APIs to enable maps, in­app SMS and email features and should be able to develop applications that controls handset resources.</li>
                  </ul>

                  <h4>Requirements:</h4>
                  <ul>
                    <li>Experience in the field of mobile application development of iOS Mac OSX</li>
                    <li>Great knowledge of objective­C/C++, Cocoa Touch, Xcode/ iOS( All Version)</li>
                    <li>GreatKnowledge about API/SDK Facebook, twitter, LinkedIn, Google +,YouTube, Ad Mob(Google Aid), iAid, Google Analytics, Zbar, Swift</li>
                    <li>Knowledge of other web technologies and UI/UX standards</li>
                    <li>Experience in designing applications based on MVC</li>
                    <li>Knowledge of iOS GUI, Camera, GPS API, Maps API, and Services</li>
                    <li>Familiarity with cloud message APIs and push notifications</li>
                    <li>Familiarity with continuous integration</li>
                    <li>Passionate about working in a startup and creating a great product</li>
                  </ul>
                </div>
              </div>
                {!! Form::open(['url' => 'career/postjob', 'method' => 'post', 'role' => 'form','novalidate'=>'novalidate','id'=>'career-form3','enctype'=>'multipart/form-data']) !!}    
                <div class="col-md-4 col-sm-5">
                <div class="job-apply-form">                
                   <h3>Apply for this Job <h3>
                  <div class="form-group">
                    <label class="labeluppercase">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label class="labeluppercase">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label class="labeluppercase">Attach CV</label>
                    <div class="attachcv">
                      <input type="file" name="resume" multiple="" data-multiple-caption="{count} files selected" class="inputfile inputfile-6" id="file-3" required>
                      <label for="file-3"><span></span> <strong>Browse</strong></label>
                    </div>
                  </div>
                 <!--  <div class="form-group mt15">
                    <small>How did you hear about us ?</small>
                    <select class="form-control">
                      <option>-Please select option-</option>
                      <option>For friends</option>
                      <option>For friends</option>
                      <option>For friends</option>
                      <option>For friends</option>
                    </select>
                  </div> -->
                  <div class="text-center mt15">
                    <button class="btn btn-primary">Send</button>
                  </div>                  
                </div>
               </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>

       

        

      </div>
    </div>
    <div class="mt30"></div>
  </div>
  
</section>

@include('includes.web.usefulllink')
<script type="text/javascript">
  $(document).ready(function(){
    $(".ge-position").click(function(){
    if($(this).hasClass('ge-positionbg'))
    {
      $(".ge-position").removeClass("ge-positionbg");
    }
    else
    {
      $(this).addClass("ge-positionbg");
    }
  });
});
</script>
<script type="application/javascript"   src="{{ URL::asset('web/js/jquery.validate.min.js')}}"></script>
<script type="application/javascript"   src="{{ URL::asset('web/js/jquery.custom-file-input.js')}}"></script>
<script type="text/javascript">
  $("#career-form1").validate({
     rules: {
               
               resume: {
                 accept: "doc|docx|pdf"
                    
                  }
            

                    },
                    messages: {
                        "resume": {                           
                            accept: "Your resume should be doc,docx and pdf"
                        }
                    }

  });
  $("#career-form2").validate({
     rules: {
               
               resume: {
                 accept: "doc|docx|pdf"
                    
                  }
            

                    },
                    messages: {
                        "resume": {                           
                            accept: "Your resume should be doc,docx and pdf"
                        }
                    }

  });
  $("#career-form3").validate({
     rules: {
               
               resume: {
                 accept: "doc|docx|pdf"
                    
                  }
            

                    },
                    messages: {
                        "resume": {                           
                            accept: "Your resume should be doc,docx and pdf"
                        }
                    }

  });
  $("#career-form4").validate({
     rules: {
               
               resume: {
                 accept: "doc|docx|pdf"
                    
                  }
            

                    },
                    messages: {
                        "resume": {                           
                            accept: "Your resume should be doc,docx and pdf"
                        }
                    }

  });
  $("#career-form5").validate({
     rules: {
               
               resume: {
                 accept: "doc|docx|pdf"
                    
                  }
            

                    },
                    messages: {
                        "resume": {                           
                            accept: "Your resume should be doc,docx and pdf"
                        }
                    }

  });
  $("#career-form6").validate({
     rules: {
               
               resume: {
                 accept: "doc|docx|pdf|txt"
                    
                  }
            

                    },
                    messages: {
                        "resume": {                           
                            accept: "Your resume should be doc,docx and pdf"
                        }
                    }

  });
</script>
@stop




