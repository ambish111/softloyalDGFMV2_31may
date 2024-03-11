var app = angular.module('AppSplitOrder', [])

        .controller('SplitOrderCRTL', function ($scope, $http, $window, Excel, $timeout, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = {};

            $scope.loadershow = false;

            $scope.loadMore = function (order)
            {
                $scope.filterData.slip_no = order;
                 disableScreen(1);
                 $scope.loadershow = true;



                $http({
                    url: SITEAPP_PATH + "Split/Getcheckdata",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                     disableScreen(0);
                     $scope.loadershow = false;
                    $scope.shipData = response.data.result;
                    $scope.totalCount = response.data.count;



                });



            };
            
            $scope.totalArr = [];
            $scope.GecheckSkuDetails = function (data, i)
            {
               disableScreen(1);
                 $scope.loadershow = true;  
               // alert(data.check_item);
                if (data.check_item == true)
                {
                    //console.warn("check item");
                    $scope.totalArr.push(data);
                } else
                {
                    $scope.dataIndex=  $scope.totalArr.findIndex( record => record.sku ===data.sku);
                    console.warn($scope.dataIndex);
                    $scope.totalArr.splice($scope.dataIndex, 1);
                }
                  disableScreen(0);
                     $scope.loadershow = false;
               //console.log($scope.totalArr);

            };
            
            $scope.GetproceedNewOrder=function()
            {
                  disableScreen(1);
                 $scope.loadershow = true;  
                 $http({
                    url: SITEAPP_PATH + "Split/GetproceedNewOrder",
                    method: "POST",
                    data: $scope.totalArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $window.location = SITEAPP_PATH+"Backorder";
                });
                
            };



        })


        .factory('Excel', function ($window) {
            var uri = 'data:application/vnd.ms-excel;base64,',
                    template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
                    base64 = function (s) {
                        return $window.btoa(unescape(encodeURIComponent(s)));
                    },
                    format = function (s, c) {
                        return s.replace(/{(\w+)}/g, function (m, p) {
                            return c[p];
                        })
                    };
            return {
                tableToExcel: function (tableId, worksheetName) {
                    var table = $(tableId),
                            ctx = {worksheet: worksheetName, table: table.html()},
                            href = uri + base64(format(template, ctx));
                    return href;
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
        .directive('stringToNumber', function () {
            return {
                require: 'ngModel',
                link: function (scope, element, attrs, ngModel) {
                    ngModel.$parsers.push(function (value) {
                        return '' + value;
                    });
                    ngModel.$formatters.push(function (value) {
                        return parseFloat(value);
                    });
                }
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