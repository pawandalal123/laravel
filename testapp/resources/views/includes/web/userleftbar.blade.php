<div class="col s12 m4 l3">
        <div class="sidebar card">
        
          <a class="waves-effect waves-light btn">Become a Job Owner</a>
          <a class="waves-effect waves-light btn" href="{{URL::to('profile/articles')}}">Write an Article</a>
          <a class="waves-effect waves-light btn" href="{{URL::to('profile/discussions')}}">Start Discussion Forum</a>
          <a class="waves-effect waves-light btn">Invite to Join Discussion Forum</a>
          <a class="waves-effect waves-light btn" href="javascript:void(0)" onclick="connectmailbox()">Connect Via Mail</a>
          <a class="waves-effect waves-light btn">Invite Friend on Social Media</a>
           <a title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer.php?u=http://hireme.slugcorner.com/', 'facebookShare', 'width=626,height=436'); return false;" href="#"><img src="{{URL::to('web/images/icons/Facbook-hover.svg')}}"></a>
          <a title="Share on linkedin" onclick="window.open('https://www.linkedin.com/shareArticle?mini=true&url=http://hireme.slugcorner.com&summary=hireme', 'linkedinShare', 'width=750,height=350'); return false;" href="#" ><img src="{{URL::to('web/images/icons/Linkedin-hover.svg')}}"></a>
          <a title="Tweet This" onclick="window.open('https://twitter.com/share?url=http://hireme.slugcorner.com&text=hireme', 'twitterShare', 'width=626,height=436'); return false;" href="#"><img src="{{URL::to('web/images/icons/Twitter-hover.svg')}}"></a>
          <a target="_blank" title="Share on Google+" onclick="window.open('https://plusone.google.com/_/+1/confirm?hl=en-US&amp;url=http://hireme.slugcorner.com&text=hireme', 'googleShare', 'width=626,height=436'); return false;" href="#"  ><img src="{{URL::to('web/images/icons/Google-hover.svg')}}"></a>
         
        
          Profile Status
          <div class="progress">
              <div class="determinate" style="width: 70%"></div>
          </div>
        </div>
    </div>