  var geocoder;

  
//Get the latitude and the longitude;
function successFunction(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    codeLatLng(lat, lng)
}

function errorFunction(){
  var city='';
    setcitycokkies(city);
   // alert("Geocoder failed");
}

  function initializelocation() 
  {
    if (navigator.geolocation)
    {
      navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
    } 
    geocoder = new google.maps.Geocoder();
  }

  function codeLatLng(lat, lng)
  {
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status)
     {
      if (status == google.maps.GeocoderStatus.OK) 
      {
        console.log(results)
        if (results[1])
        {
         //formatted address
         //alert(results[0].formatted_address)
        //find country name
            for (var i=0; i<results[0].address_components.length; i++)
            {
                for (var b=0;b<results[0].address_components[i].types.length;b++) 
                {
                    //alert(results[0].address_components[i].types[b]);
                //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                    if (results[0].address_components[i].types[b] == "locality")
                    {
                            //this is the object you are looking for
                            city= results[0].address_components[i];
                            //alert(city);
                            
                    }
                }
        }
           var city = city.long_name;
           var cokkiestype = 'citybase';
           setcitycokkies(city);
        } 
        else 
        {
          var city ='';
          setcitycokkies(city);

        }
      } 
      else 
      {
        var city ='';
        setcitycokkies(city);
      }
      //return city;
    });
  }

  function setcitycokkies(city)
  {
    $.post(SITE_URL+'setcokkies',{'city':city},function(data,status)
    {
      if(data!='')
      {
         window.location.reload();
      }
   });

  }
