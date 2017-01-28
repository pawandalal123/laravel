var activity = angular.module('activityModule', []);

activity.controller('activityCtrl', function ($scope,$http) {
  $scope.activity=[];
  $scope.getParam="?true=1";
  $scope.page=1;
  $scope.url=SITE_URL+'api/activity';

  $scope.ajaxCall =  function(url){
  			$http.get(url).success( function(response) {
               // console.log(response);
               console.log(response.length);
               if(response.length<100)
               	$('.loadmore').hide();
               else
               	$('.loadmore').show();
               $scope.activity=$scope.activity.concat(response);
               // console.log($scope.activity);
            });
  		}
  $scope.ajaxCall($scope.url+$scope.getParam+'&page=1');

  $scope.freshContent=function(value,type){
  	$scope.activity=[];
  	$scope.page=1;
  	if(type=='date')
  	{$scope.getParam='?type='+type+'&datefrom='+$scope.datefrom+'&dateto='+$scope.dateto;}
  else
  	$scope.getParam='?type='+type+'&value='+value;
  	

$scope.ajaxCall($scope.url+$scope.getParam+'&page=1');
  };
  

  $scope.loadMore=function(){
  	$scope.page++;
  	var param='&page='+$scope.page;
  	// $scope.url=;
  	// alert($scope.getParam);
  	$scope.ajaxCall($scope.url+$scope.getParam +param);
  }
});