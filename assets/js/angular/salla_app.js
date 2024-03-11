var app = angular.module('SallaAppPage', [])
        .controller('SallaAppCRL', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.loadershow = false;
            $scope.userselected = {};
            $scope.awbArray = [];
            $scope.scan = {};
            $scope.newarray = [];

            $scope.scan_awb = function () {
                $scope.scan.awbArray = removeDumplicateValue($scope.userselected.slip_no.split("\n"));
                $scope.userselected.slip_no = $scope.scan.awbArray.join('\n');
            }

            function removeDumplicateValue(myArray) {
                var newArray = [];
                angular.forEach(myArray, function (value, key) {
                    var exists = false;
                    angular.forEach(newArray, function (val2, key) {
                        if (angular.equals(value, val2)) {
                            exists = true
                        }
                        ;
                    });
                    if (exists == false && value != "") {
                        newArray.push(value);
                    }
                });

                return newArray;
            }
            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};

            $scope.BulkPushSallaStatus = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log($scope.userselected);
                //   $scope.isVisible.loading = true;

                $http({
                    url: URLBASE + "Salla/BulkPushSallaStatus",
                    method: "POST",
                    data: $scope.userselected,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    $scope.userselected = {};
                    $scope.scan.awbArray = {};
                    $scope.invalidSslip_no = response.data.invalid_slipNO;
                    $scope.Success_msg = response.data.Success_msg;
                    $scope.Error_msg = response.data.Error_msg;

                }, function (data) {
                    disableScreen(0);
                    $scope.loadershow = false;
                });

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
                    priority: 1,
                    terminal: true,
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.ngClick;
                        element.bind('click', function (event) {
                            if (window.confirm(msg)) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
            }]);
/*------ /show shipments-----*/