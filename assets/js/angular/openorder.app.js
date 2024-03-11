var app = angular.module('OpenorderApp', ['betsol.timeCounter'])

        .controller('scanShipment', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.scan_new = {};
            $scope.specialtype = {};

            $scope.awbArray = [];
            $scope.awbArray_print = [];
            $scope.SKuMediaArr = [];
            $scope.shelve = null;
            $scope.PrintBtnallAwb = false;
            $scope.loadershow = false;

            $scope.GetremoveBtn = false;
            $scope.Btnverify = true;
            $scope.awbInputdis = false;
            $scope.scan_new.box_no = 1;
            $scope.scan_awb = function () {
                
                $('#scan_awb').focus();
                $scope.packuShip();
            };

            $scope.totalnumber = 0;
            $scope.rechecktotalnumber = 0;
            $scope.Geturlcheck=function(slip_no)
                {
                  $scope.scan.slip_no = slip_no;
                  $scope.scan_awb();
                };
                
            $scope.packuShip = function () {

                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    //console.log($scope.scan);
                    $http({
                        url: SITEAPP_PATH + "Shipment_og/orderopencheckCheck",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        disableScreen(0);
                        $scope.loadershow = false;
                        //$scope.specialtype.specialpack=true;
                        //$scope.specialtype.specialpacktype="warehouse";
                        if (response.data.count == 0)
                        {
                            $scope.scan.slip_no ="";
                            $scope.Btnverify = true;
                            $scope.warning = "Order Not available for Open!";
                            responsiveVoice.speak($scope.warning);
                        } else
                        {
                            $scope.awbInputdis = true;
                            $scope.GetremoveBtn = true;
                            $scope.rechecktotalnumber = response.data.sku_count;
                             $scope.Btnverify = true;
                              $scope.warning=null;
                             $scope.Message = "AWB Scan";
                             responsiveVoice.speak($scope.Message);
                            
                        }


                        angular.forEach(response.data.result, function (value1) {
                            console.log(value1);

                            $scope.awbArray.push(value1);
                           
                                $scope.shipData.push({'slip_no': response.data.slip_no, 'sku_size': value1.sku_size, 'stock': 0, 'expire_block': value1.expire_block, 'sku': value1.sku, 'item_sku': value1.item_sku, 'cust_id': value1.cust_id, 'piece': value1.piece, 'scaned': 0, 'scan_s': 0, 'extra': 0, 'stock_location': "",'shelve_no':""});
                               
                        });

                       

                    });
                }


            };
            

            $scope.GetcheckStocklocation = function (data, index)
            {

                disableScreen(1);
                $scope.loadershow = true;
                //alert(data.stock_location);
                $scope.Btnverify = true;
                $scope.scan.list = data;
                $http({
                    url: SITEAPP_PATH + "Shipment_og/GetcheckStockLocation_open",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.showerror = response.data.error;
                    if ($scope.showerror == 'valid')
                    {

                        $scope.totalnumber = parseInt($scope.totalnumber) + 1;
                        $scope.shipData[index].scan_s = 1;
                        $scope.shipData[index].stock = response.data.result.quantity;
                        $scope.warning=null;
                        $scope.Message = "Stock Location Scan";
                    } else
                    {
                        $scope.shipData[index].stock_location = "";
                        $scope.Message=null;
                        $scope.warning = "Invalid Stock Location";
                    }
                    disableScreen(0);
                    $scope.loadershow = false;

                    if ($scope.rechecktotalnumber == $scope.totalnumber)
                    {
                        $scope.Btnverify = false;
                        $scope.scan.tcount = $scope.totalnumber;
                    }




                });
            };

            $scope.finishScan = function (index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $scope.scan.sku_data = $scope.shipData;

                $http({
                    url: SITEAPP_PATH + "Shipment_og/openorder_finish",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    
                     $scope.Btnverify = true;
                       $scope.awbInputdis = false;
                    $scope.scan = {};
                    $scope.shipData = [];
                   // disableScreen(0);
                   // $scope.loadershow = false;
                    alert("success!");
                    location.reload();

                });
            };

            $scope.openStockLocation = function (index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $scope.totalnumber = parseInt($scope.totalnumber) - 1;
                $scope.Btnverify = true;
                $scope.shipData[index].scan_s = 0;
                $scope.shipData[index].stock = 0;
                $scope.Message=null;
                $scope.warning = "Stock Location Opened";
                disableScreen(0);
                $scope.loadershow = false;
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