 var SITE_URL ='http://localhost/php/devgo/GEsite/';
 var APIURL = 'http://localhost/php/devgo/GEsite/api/s';
document.write('<link rel="stylesheet" type="text/css" href="'+SITE_URL+'web/css/style.css">');
document.write('<link rel="stylesheet" type="text/css" href="'+SITE_URL+'web/css/paper-bootstrap.css">');

var myElement = document.getElementById('myFirstWidget');
var JavaScriptCode = document.createElement("script");
JavaScriptCode.setAttribute('type', 'text/javascript');
JavaScriptCode.setAttribute("src", SITE_URL+'web/js/WidgetCreate.js');
document.getElementById('myFirstWidget').appendChild(JavaScriptCode);
 