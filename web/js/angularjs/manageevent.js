var activity = angular.module('manageeventmodule', []);
activity.controller('activityCtrl', function ($scope,$http) {
  $scope.activity=[];
  $scope.getParam="?true=1";
  $scope.page=1;
  $scope.url=SITE_URL+'api/getallevents';

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
  $scope.ajaxCall($scope.url+$scope.getParam+'&page='+$scope.page);

  $scope.freshContent=function()
  {
    $scope.activity=[];
    $scope.page=1;
    $scope.getParam='?eventstatus='+$scope.eventstatus+'&eventfeature='+$scope.eventfeature+'&commonfeilds='+$scope.commonfeilds+'&assigned='+$scope.assigned+'&eventtype='+$scope.eventtype+'&accountmanager='+$scope.accountmanager+'&reviewed='+$scope.reviewed;
    $scope.ajaxCall($scope.url+$scope.getParam+'&page=1');
  };
  

  $scope.loadMore=function(){
    $scope.page++;
    var param='&page='+$scope.page;
     $('.loaderbox').show();
    // $scope.url=;
     //alert($scope.getParam);
    $scope.ajaxCall($scope.url+$scope.getParam +param);
    $('.loaderbox').hide();
  }
  $scope.assignto = function(userid) 
  { 
    window.assignto(userid); 
  };
  //  $scope.setrelatedevent = function(eventid) 
  // { 
    
  //   if ($scope.Hasevent[eventid]) 
  //   {
  //     window.alert("CheckBox is checked.");
  // } 
  // else {
  //     window.alert("CheckBox is not checked.");
  // }

  //  // window.setrelatedevent('relatedevent',val,eventid); 
  // };
  $scope.deleteevent = function(id,delettype)
  {
    window.makedelete(id,delettype);

  }
  $scope.deleteall = function(id,delettype)
  {
    window.makedelete(id,delettype);

  }
  $scope.banuser = function(id,delettype)
  {
    window.makedelete(id,delettype);
 
  }

}).directive('ngConfirmClick', [
        function(){
            return {
                link: function (scope, element, attr) {
                    var msg = attr.ngConfirmClick || "Are you sure?";
                    var clickAction = attr.confirmedClick;
                    element.bind('click',function (event) {
                        if ( window.confirm(msg) ) {
                            scope.$eval(clickAction)
                        }
                    });
                }
            };
    }]);