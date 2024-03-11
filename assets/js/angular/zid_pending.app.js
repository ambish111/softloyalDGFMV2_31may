var app = angular.module('ZidPendingAPP', [])



        .controller('ZidpendingCTRL', function ($scope, $http, $window, $timeout, $location) {

            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};

            $scope.loadershow = false;

            $scope.errorData = {};
            $scope.errorData1 = {};
            $scope.succData = {};
            // $scope.errorData.mess="";
            $scope.GetSearchorder = function ()
            {

                $scope.errorData = {};
                $scope.errorData1 = {};
                $scope.succData = {};
                $scope.errorData.mess = "";
                disableScreen(1);
                $scope.loadershow = true;

                $http({
                    url: SITEAPP_PATH + "Seller/GetcheckZidPendigOrders",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data)

                    $scope.errorData = response.data.emptyErr;
                    $scope.errorData1 = response.data.req;

                    disableScreen(0);
                    $scope.loadershow = false;

                });


            };
            $scope.showlog="";
            $scope.CreateOrderProcess = function ()
            {
                check = confirm("Are you sure to Create Order?")
                if (check) {
                     disableScreen(1);
                $scope.loadershow = true;
                    $http({
                    url: SITEAPP_PATH + "Seller/GetCreateProcessOrder",
                    method: "POST",
                    data: {order:$scope.errorData1.order,filterArr:$scope.filterData},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                   $scope.errorData = {};
                    $scope.errorData1 = {};
                   $scope.showlog=response.data;
                    disableScreen(0);
                $scope.loadershow = false;
                });
                } 
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