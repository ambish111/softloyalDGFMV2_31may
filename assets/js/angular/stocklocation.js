var app = angular.module('stockLocationApp', [])
 .controller('stockLocation', function ($scope, $http, $window, $location) {
//	alert("ssssssss");
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []

//console.log($scope.baseUrl);
            $scope.loadMore = function (page_no, reset,type)
            {
               // alert(type);
                //alert("sssssss");

                //console.log(page_no);    
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                 $scope.filterData.type = type;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: SITEAPP_PATH+"stockLocationFilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    $scope.dropexport = response.data.dropexport;
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.shipData.push(value);

//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //.console.log( $scope.shipData[0].skuData[0])
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };
            

              $scope.selectedAll = false;
            $scope.selectAll = function (val) {
                //alert(val);


                // console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // console.log(key+"======="+newval);
                    if (key <= newval)
                    {
                        data.Selected = true;

                        $scope.Items.push(data.stock_location);

                    } else
                    {
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.stock_location);
                        else
                            $scope.Items = [];
                    }

                });


            };
            $scope.checkIfAllSelected = function () {
                $scope.selectedAll = $scope.shipData.every(function (data) {
                    return data.Selected == true
                })
            };
             $scope.printlabelsku = function () {
                // alert();
                $("#printlabelsize").modal({backdrop: 'static',
                    keyboard: false});
                    $("#stocklocationval").val($scope.Items);
            };

             $scope.GetexportExcelStocklocation = function () {
              
              
              if($scope.filterData.exportlimit>0)
              {
                     disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: SITEAPP_PATH+"ExcelExport/GetexportExcelStocklocation",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                     disableScreen(0);
                $scope.loadershow = false;
                    //console.log(results);
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
                     alert("please select export limit");
                 }
               
            };


            $scope.exportExcelShowstock = function ()
            {


                //console.log($scope.shipData);
                $http({
                    url: SITEAPP_PATH+"shelve/showstockViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //	  console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });


            }



            $scope.exportExcel = function ()
            {
                //console.log($scope.shipData);
                $http({
                    url: SITEAPP_PATH+"pickUp/pickListViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    console.log(response);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
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