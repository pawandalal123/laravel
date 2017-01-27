@extends('layout.default')
@section('content')
<section class="top-blue-sec">
  <div class="container">
    <div class="row">
      <div class="col s12 m12 l12">
          <h1>User Profile</h1>
      </div>
    </div>
  </div>
</section>
<div class="container user-profile">
  <div class="row">
   @include('includes.web.userprofileleftbar')
   @if($pagename=='articles')
    @include('includes.web.userfiles.userarticle')
    @elseif($pagename=='discussions')
    @include('includes.web.userfiles.userdiscussion')
    @elseif($pagename=='invitediscussion')
    @include('includes.web.userfiles.discussionjoin')
   @else
   @include('includes.web.userfiles.userhome')
   @endif
    <div class="col s12 m4 l3">
      <div class="sidebar-box">
        <div class="user-gray-box">
          <h6>Skill</h6>
          <p>Management, Leadership &amp; Supervision</p>
          <p>Communication, And Industry Knowledge</p>
          <p>Application Design, Programming, Training</p>
          <a href="#" class="waves-effect waves-light btn basicdetail-edit-right-side">Edit</a>
        </div>
        <div class="user-gray-box">
          <h6>Academic Credentials </h6>
           <table class="bordered responsive-table">
            <tbody>
              <tr>
                <th>MCA</th>
                <td>67.90%</td>
                <td>Abc Univercity</td>
                <td>2012</td>
              </tr>
              <tr>
                <th>MCA</th>
                <td>67.90%</td>
                <td>Abc Univercity</td>
                <td>2012</td>
              </tr>
              <tr>
                <th>MCA</th>
                <td>67.90%</td>
                <td>Abc Univercity</td>
                <td>2012</td>
              </tr>
            </tbody>
          </table>
          <a href="#" class="waves-effect waves-light btn basicdetail-edit-right-side">Edit</a>
        </div>
        <div class="user-gray-box">
          <h6>My Recent Feedback</h6>
          <p>Morbi eget velit ultricies, volutpat nulla id</p>
          <p>Morbi eget velit ultricies, volutpat nulla id</p>
          <p>Morbi eget velit ultricies, volutpat nulla id</p>
          <a href="#" class="waves-effect waves-light btn basicdetail-edit-right-side">Edit</a>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
