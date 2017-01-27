<?php
foreach($allUser as $allUserli)
{
    $useryessales=array();
    $newclientlist =array();
    $userName = $allUserli->name;
    $monthtotalamt =0;
    $monthtotalqty =0;
    $monthtotaltrx =0 ;
    $toatlgmv =0;
    $totalpaidevent =0;
    $totalclients =0 ;
    $useridkey =$allUserli->email;
    if(array_key_exists($allUserli->id, $usersgmvArray))
    {
        $toatlgmv =$usersgmvArray[$allUserli->id]['gmv'];
        $totalpaidevent =$usersgmvArray[$allUserli->id]['paid_events'];
        $totalclients =$usersgmvArray[$allUserli->id]['new_clients'] ;
    }
    if(array_key_exists($allUserli->email, $yesterdaysales))
    {
        $monthtotalamt =$yesterdaysales->$useridkey->monthtotalamt;
        $monthtotalqty =$yesterdaysales->$useridkey->monthtotalqty;
        $monthtotaltrx =$yesterdaysales->$useridkey->monthtotaltrx ;
    }
    ////////// sales report event start yesterday//
    /////////new clients/////
    $newclientlistinmonth=0;
    $newpaidevent=0;
    if(array_key_exists($allUserli->email, $newclientlistdata))
    {
        $newclientlistinmonth = $newclientlistdata->$useridkey->month;
        $newpaidevent = $newclientlistdata->$useridkey->eventinmonth;
    }
    $monthnewclients =$newclientlistinmonth;
    $paideventmonth =$newpaidevent ;
    $gmvamount=100;
	$gmvpaidevent=100;
	$gmvnewclient=100;
	$actaulmonthamount = $monthtotalamt*100/($toatlgmv >0 ? $toatlgmv: 1);
	$actaulpaidevent = $paideventmonth*100/($totalpaidevent >0 ? $totalpaidevent: 1);
	$actaulnewclient = $monthnewclients*100/($totalclients >0 ? $totalclients: 1);
	if($toatlgmv<=0)
	{
	  $gmvamount=0;
	}
	if($totalpaidevent<=0)
	{
	  $gmvpaidevent=0;
	}
	if($totalclients<=0)
	{
	  $gmvnewclient=0;
	}
	
     ?>
       <div  style="width:100%; max-width:780px;  margin:0px auto!important; font-family: 'Roboto', sans-serif!important;  border: 3px solid #002442;">
    
    <div style="background-color: #002341;">
      <table cellspacing="0" cellpadding="0" style="width:100%;font-size:11px;color:#ddd; font-weight: normal;text-align: left;">
        <tr>
          <td style="padding: 3px 5px;"><strong style="color: #fff; font-size:12px;">Hey {{$userName}},</strong><!-- <br>I have put together some insights for you. --></td>
        </tr>
  
       
      </table>
    </div>
    <div style="background-color:#DDDDDD; padding: 10px 5px;">
      <?php
      echo '<img src="http://chart.googleapis.com/chart?cht=bvg&chs=770x300&chm=N,000000,0,-1,11|N,000000,1,-1,11&chd=t:'.$gmvamount.','.$gmvnewclient.','.$gmvpaidevent.'|'.$actaulmonthamount.','.$actaulnewclient.','.$actaulpaidevent.'&chco=4D89F9,C6D9FD&chxt=x,y&chxl=0:|Gmv|New%20clients|Paid Events&chbh=40,10,30&chtt=Performance Report&chdl=Target %|Actual %&chdls=0000CC,13">';
      ?>
     <!--  <img src="http://chart.googleapis.com/chart?cht=bvg&chs=770x300&chm=N,000000,0,-1,11|N,000000,1,-1,11&chd=t:100,100,100|80,10,10&chco=4D89F9,C6D9FD&chxt=x,y&chxl=0:|Gmv|New%20clients|PAID&chbh=40,10,30&chtt=Sales%20Report&chdl=First|Second&chdls=0000CC,13"
      style="width: 100%;"> -->
      <table cellspacing="0" cellpadding="0" style="width: 100%;">
        <thead>
          <tr align="left">
            <th style="padding:3px 5px; background-color:#4D89F9; color: #ffffff; font-size: 11px;"></th>
            <th style="padding:3px 5px; background-color:#4D89F9; color: #ffffff; font-size: 11px;">GMV</th>
            <th style="padding:3px 5px; background-color:#4D89F9; color: #ffffff; font-size: 11px;">New Clients</th>
            <th style="padding:3px 5px; background-color:#4D89F9; color: #ffffff; font-size: 11px;">Paid Events</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">Target</td>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">{{$toatlgmv}}</td>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">{{$totalclients}}</td>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">{{$totalpaidevent}}</td>   
          </tr>
          <tr>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">Actual</td>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">{{$monthtotalamt}}</td>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">{{$monthnewclients}}</td>
            <td style="padding:3px 5px; background-color:#FFFFFF; color: #444444; font-size: 11px;">{{$paideventmonth}}</td>   
          </tr>
        </tbody>
      </table>    
    </div>
</div>

     <?php
}
?>