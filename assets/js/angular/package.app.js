var app = angular.module('PackageApp', ['betsol.timeCounter'])

        .controller('package_view_ctrl', function ($scope, $http, $window, $location) {
            $scope.filterData = {};
            $scope.listdata = [];

            $scope.loadershow = false;

            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.loadMore = function (page_no, reset)
            {

                disableScreen(1);
                $scope.loadershow = true;
                $scope.filterData.page_no = page_no;

                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.listdata = [];
                }

                $http({
                    url: SITEAPP_PATH+"Package/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    //console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {

                            $scope.listdata.push(value);

                        });

                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;
                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                });


            };
            
            $scope.getSyncpackage=function()
            {
                 disableScreen(1);
                $scope.loadershow = true;
                if($scope.filterData.seller_id>0)
                {
                $http({
                    url: SITEAPP_PATH+"Package/getSyncpackage",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                   
                    alert(response.data.mess);
                     $scope.loadMore_assign(1,1);
                });
            }
            else
            {
                  disableScreen(0);
                    $scope.loadershow = false;
                alert("please select Customer");
            }
            };
            $scope.loadMore_assign = function (page_no, reset)
            {

                disableScreen(1);
                $scope.loadershow = true;
                $scope.filterData.page_no = page_no;

                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.listdata = [];
                }

                $http({
                    url: SITEAPP_PATH+"Package/filter_assign",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    //console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {

                            $scope.listdata.push(value);

                        });

                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;
                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                });


            };
            
             $scope.getexport = function () {

               if($scope.filterData.exportlimit>0)
               {
                    disableScreen(1);
                $scope.loadershow = true;
                $http({
                    //  url: "ItemInventory/getexceldata",
                    url:SITEAPP_PATH+ "Package/getexceldata",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                     disableScreen(0);
                $scope.loadershow = false;
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
            }
            else
            {
                alert("Please Select Limit");
            }
               
            };
            $scope.getexport_wallet = function () {

               if($scope.filterData.exportlimit>0)
               {
                    disableScreen(1);
                $scope.loadershow = true;
                $http({
                    //  url: "ItemInventory/getexceldata",
                    url:SITEAPP_PATH+ "Package/getexceldata_wallet",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                     disableScreen(0);
                $scope.loadershow = false;
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
            }
            else
            {
                alert("Please Select Limit");
            }
               
            };
            
             $scope.loadMore_wallet = function (page_no, reset)
            {

                disableScreen(1);
                $scope.loadershow = true;
                $scope.filterData.page_no = page_no;

                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.listdata = [];
                }

                $http({
                    url: SITEAPP_PATH+"Package/filter_wallet",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    //console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {

                            $scope.listdata.push(value);

                        });

                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;
                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
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