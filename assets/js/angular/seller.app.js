var app = angular.module('SellerAPp', ['betsol.timeCounter'])



        .controller('woocommereceCtrl', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.ListData={};
            $scope.ListData_WC={};
            $scope.tokenbtn=false;
           
            
            $scope.GetcheckAuth_btn=function(auth)
            {
               
                if(auth=='Y')
                {
                  $scope.tokenbtn=true;  
                }
                else
                {
                    $scope.filterData.auth_token="";
                    $scope.tokenbtn=false;
                }
            }
            $scope.GetshowStatusList = function (uid)
            {
                 disableScreen(1);
                $scope.loadershow = true;
                $scope.filterData.id=uid;
                $http({
                    url: SITEAPP_PATH+"Seller/GetcustmerData",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                     disableScreen(0);
                $scope.loadershow = false;
                    $scope.ListData=response.data.systam_cat;
                    $("#Viewliststatus_pop").modal({backdrop: 'static',
                    keyboard: false})
                 

                })


            };
            
             $scope.GetshowStatusList_WC = function (uid)
            {
                   disableScreen(1);
                $scope.loadershow = true;
                $scope.filterData.id=uid;
                $http({
                    url: SITEAPP_PATH+"Seller/GetshowStatusList_WC",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                       disableScreen(0);
                $scope.loadershow = false;
                    $scope.ListData_WC=response.data.WC_statusArr;
                    $("#Viewliststatus_WC_pop").modal({backdrop: 'static',
                    keyboard: false})
                 

                })


            };
            
            $scope.GetUpdateStatusFinal=function(uid)
            {
                 disableScreen(1);
                $scope.loadershow = true;
               $scope.filterData.id=uid; 
               $scope.filterData.updates=$scope.ListData; 
               
                $http({
                    url: SITEAPP_PATH+"Seller/GetUpdateStatusFinal",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                  
                     disableScreen(0);
                $scope.loadershow = false;
                  alert("Successfully Updated!");
                   $("#Viewliststatus_pop").modal("hide");
                   
                 

                })
            };





           

        })

        
       

        .directive('checkList', function () {
            return {
                scope: {
                    list: '=checkList',
                    value: '@'
                },
                link: function (scope, elem, attrs) {
                    var handler = function (setup) {
                        var checked = elem.prop('checked');
                        var index = scope.list.indexOf(scope.value);

                        if (checked && index == -1) {
                            if (setup)
                                elem.prop('checked', false);
                            else
                                scope.list.push(scope.value);
                        } else if (!checked && index != -1) {
                            if (setup)
                                elem.prop('checked', true);
                            else
                                scope.list.splice(index, 1);
                        }
                    };

                    var setupHandler = handler.bind(null, true);
                    var changeHandler = handler.bind(null, false);

                    elem.on('change', function () {
                        scope.$apply(changeHandler);
                    });
                    scope.$watch('list', setupHandler, true);
                }
            };
        })
        .filter('reverse', function () {
            return function (items) {
                return items.slice().reverse();
            };
        })

        .directive('myEnter', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if (event.which === 13) {
                        scope.$apply(function () {
                            scope.$eval(attrs.myEnter);
                        });

                        event.preventDefault();
                    }
                });
            };
        })
        .directive('ngConfirmClick', [
            function () {
                return {
                    priority: -1,
                    restrict: 'A',
                    link: function (scope, element, attrs) {
                        element.bind('click', function (e) {
                            var message = attrs.ngConfirmClick;
                            // confirm() requires jQuery
                            if (message && !confirm(message)) {
                                e.stopImmediatePropagation();
                                e.preventDefault();
                            }
                        });
                    }
                }
            }
        ])
/*------ /show shipments-----*/
.directive('selectWatcher', function ($timeout) {
    return {
        link: function (scope, element, attr) {
            var last = attr.last;
            if (last === "true") {
                $timeout(function () {
                    $(element).parent().selectpicker('val', 'any');
                    $(element).parent().selectpicker('refresh');
                });
            }
        }
    };
});