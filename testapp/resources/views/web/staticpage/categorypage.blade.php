@extends('layout.default')
@section('content')

<style type="text/css">
  
.browes_filter_link{
    padding:0px;
    margin:0px;
}
.browes_filter_link li{
    display:inline-block;
}
.browes_filter_link li a{
    display:inline-block;
    background-color:#fff;
    border:1px solid #ddd;
    padding:3px 15px;
    color:#666;
    text-decoration:none!important;
    margin-bottom:10px;
}
.pagination_link{
    padding:0px!important;
    margin:0px!important;
    text-align:center!important;
}

</style>



<div class="container">
        <div class="row">
            <div class="col-sm-12 mt70">
                <ul class="browes_filter_link">
                    <li><a href="{{URL::to('/directory/'.$country.'/categories')}} " <?php if($type=='Categories') {  echo "class='active'"; } ?> >Categories</a></li>
                    <li><a href="{{URL::to('/directory/'.$country.'/persons')}}" <?php if($type=='Persons') {  echo "class='active'"; } ?>>Persons</a></li>
                    <li><a href="{{URL::to('/directory/'.$country.'/topics')}}" <?php if($type=='Topics') {  echo "class='active'"; } ?>>Topics</a></li>
                </ul>
            </div>
           


      </div> <!-- END OF ROW DIV -->
</div><!-- END OF CONTAINER DIV -->



  <div class="container">
    <div class="row">
      <div class="col-sm-12 mt5">

              <div class="browser_content_box">
          <h1 class="featuredboxhead">Browse by {{$type}}</h1>
            <?php
             if($type=='Persons' || $type=='Topics')
            { 
              ?>
          <div class="mt15 text-center">


            <ul class="pagination pagination_link">
             
                     <?php 
foreach (range('A', 'Z') as $char) {
    ?>
<li <?php if( isset($_GET['search']) && (strtolower($char)==strtolower($_GET['search'])) ) echo "class='active'"; ?>><a href="?search=<?php echo $char;?>"><?php echo $char;?></a></li>
    <?php
}
        ?>

            </ul>
          </div> <!-- END OF mt15 text-center -->
          <?php } ?>

          <div class="row div-min-height">
      @if(count($data)>0)
        <?php if(strcmp(strtolower($type),'categories') == 0)
        {
          $urlvalue="category/$country/";
          }
          elseif(strcmp(strtolower($type),'persons') == 0)
          {
           $urlvalue="person/$country/";
          }
          elseif(strcmp(strtolower($type),'topics') == 0)
          {
             $urlvalue="topic/$country/";
          }

            ?>
          @foreach($data as $key=>$value)
          <?php 
          $linkvalue=str_replace(' ','-',$value);
          $linkvalue=str_replace('_','-',$linkvalue);
          $value=str_replace('_','/',$value);
          // $value=str_replace(' ','-',$value);
          ?>
            <div class="col-xs-6 col-sm-4 col-md-2">
            <div class="city-browse">
              <a track-type="event-browse" track-element="event-listing-country"   href="{{URL::to('/browse/'.$urlvalue.$linkvalue)}}" class="citytext" title='{{$value}}'> {{$value}}</a>
            </div>
          </div>
     
           
          @endforeach
         @else
         <span class="notfound"> no results found</span>
         @endif

         </div>  <!-- END OF row div-min-height -->
                </div>  <!-- END OF browser_content_box -->
      </div> <!-- END OF  col-sm-12 mt5 -->

              <div class="col-sm-12 text-center ">

        



        <?php 

        // dd($data);
        echo $results->appends($appendUrl)->render(); ?>

        </div> <!--- END of col-sm-12 text-center -->

    </div>   <!-- END OF row -->
    <div class="mt30"></div>
  </div> <!-- END OF container -->

   







@include('includes.web.usefulllink')

@stop




