var app = angular.module('PickingApp', ['betsol.timeCounter'])

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
            $scope.rechecktotalnumber_ST = 0;
            $scope.packuShip = function () {

                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.error_sku = {};
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    //console.log($scope.scan);
                    $http({
                        url: SITEAPP_PATH + "Shipment_og/pickingCheck",
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
                            $scope.scan.slip_no = "";
                            $scope.Btnverify = true;
                            
                            if (response.data.error_sku.length > 0)
                            {
                               // alert("ssssss");
                                 $scope.Message = null;
                                $scope.error_sku = response.data.error_sku;
                            } else
                            {
                                $scope.Message = null;
                                $scope.warning = "Order Not available for Picking!";
                                responsiveVoice.speak($scope.warning);
                            }
                        } else
                        {
                            $scope.warning = null;
                            $scope.Message = "Order Scan";
                                responsiveVoice.speak($scope.Message);
                            $scope.awbInputdis = true;
                            $scope.GetremoveBtn = true;
                            $scope.rechecktotalnumber = response.data.result[0].sku_count;
                            $scope.scan.uid = response.data.result[0].pickupId;
                        }



                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.sku)

                            $scope.awbArray.push(value);
                            angular.forEach(value.sku, function (value3, i) {
                                angular.forEach(value3, function (value1) {
                                    //console.log(value); 
                                    $scope.rechecktotalnumber_ST = parseInt($scope.rechecktotalnumber_ST) + 1;
                                   // $scope.shipData.push({'slip_no': value.slip_no, 'oldPeice':value1.oldPeice,'sku_size': value1.sku_size, 'st_location': value1.st_location,'tableid':value1.tableid,'upqty':value1.upqty, 'quantity': value1.quantity, 'stock': 0, 'expire_block': value1.expire_block, 'sku': value1.sku, 'item_sku': value1.item_sku, 'cust_id': value1.cust_id, 'piece': value1.piece, 'scaned': 0, 'scan_s': 0, 'extra': 0, 'stock_location': ""});
                                     $scope.shipData.push({'slip_no': value.slip_no, 'oldPeice':value1.oldPeice,'sku_size': value1.sku_size, 'st_location': value1.st_location,'tableid':value1.tableid,'upqty':value1.upqty, 'quantity': value1.quantity, 'stock': 0, 'expire_block': value1.expire_block, 'sku': value1.sku, 'item_sku': value1.item_sku, 'cust_id': value1.cust_id, 'piece': value1.piece, 'scaned': 0, 'scan_s': 0, 'extra': 0, 'stock_location': "",'pcode': value1.pcode,'offer': value1.offer});


                                });


                            });

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });

                        //alert($scope.rechecktotalnumber_ST);

                        // console.log( $scope.SKuMediaArr);
                        // $scope.GetcheckskuOtherData($scope.shipData[$scope.arrayIndexnew].sku,$scope.shipData[$scope.arrayIndexnew].piece);

                    });
                }

                //  $scope.scanCheck();
                // $scope.checkComplte($scope.shipData, $scope.scan.slip_no);

            };

            $scope.GetcheckStocklocation_new = function (data, index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $scope.Btnverify = true;
                $scope.in_stock_location = data.st_location.toUpperCase();
                $scope.scan_stock_location = data.stock_location.toUpperCase();
                if ($scope.in_stock_location === $scope.scan_stock_location)
                {
                    $scope.totalnumber = parseInt($scope.totalnumber) + 1;
                    $scope.shipData[index].scan_s = 1;
                    $scope.shipData[index].stock_location = $scope.scan_stock_location;
                    $scope.warning = null;
                    $scope.Message = "Stock Location Scan";
                    responsiveVoice.speak($scope.Message);
                } else
                {
                    //$scope.shipData[index].scan_s = 1;
                    $scope.Message = null;
                    $scope.warning = "Invalid Stock Location";
                    responsiveVoice.speak($scope.warning);
                    $scope.shipData[index].stock_location = "";


                }

                if ($scope.rechecktotalnumber_ST == $scope.totalnumber)
                {
                    $scope.warning = null;
                    $scope.Message = "All Part are scan";
                    responsiveVoice.speak($scope.Message);
                   // $scope.shipData[index].stock_location = "";
                     $scope.Btnverify = false;
                     $scope.scan.tcount = $scope.totalnumber;
                }
                disableScreen(0);
                $scope.loadershow = false;
            };
            
            $scope.openStockLocation = function (index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $scope.totalnumber = parseInt($scope.totalnumber) - 1;
                $scope.Btnverify = true;
                $scope.shipData[index].scan_s = 0;
                $scope.shipData[index].stock = 0;
                disableScreen(0);
                $scope.loadershow = false;
            };
            $scope.GetcheckStocklocation = function (data, index)
            {

                disableScreen(1);
                $scope.loadershow = true;
                //alert(data.stock_location);
                $scope.Btnverify = true;
                $scope.scan.list = data;
                $http({
                    url: SITEAPP_PATH + "Shipment_og/GetcheckStockLocation",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.showerror = response.data.error;
                    if ($scope.showerror == 'valid')
                    {
                        $scope.warning = null;
                        $scope.Message = "Stock Location Scan";
                        responsiveVoice.speak($scope.Message);

                        $scope.totalnumber = parseInt($scope.totalnumber) + 1;
                        $scope.shipData[index].scan_s = 1;
                        $scope.shipData[index].stock = response.data.result.quantity;

                    } else
                    {
                        $scope.Message = null;
                        $scope.warning = "Invalid Stock Location";
                        responsiveVoice.speak($scope.warning);
                        $scope.shipData[index].stock_location = "";
                    }
                    disableScreen(0);
                    $scope.loadershow = false;

                    if ($scope.rechecktotalnumber == $scope.totalnumber)
                    {
                        $scope.warning = null;
                        $scope.Message = "All Part Scan!";
                        responsiveVoice.speak($scope.Message);
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
                    url: SITEAPP_PATH + "Shipment_og/picking_finish",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    if(response.data.status=='succ')
                    {
                    $scope.Message = "Success!";
                    responsiveVoice.speak($scope.Message);
                    $scope.Btnverify = true;
                    $scope.awbInputdis = false;
                      location.reload();
                     
                  }
                  else
                  {
                      
                     $scope.warning = "Faild!";
                    responsiveVoice.speak("Stock Not Available");
                    $scope.Btnverify = true;
                    $scope.awbInputdis = false;
                      location.reload();  
                  }
                   // $scope.scan = {};
                   // $scope.shipData = [];
                    //disableScreen(0);
                    //  $scope.loadershow = false;
                  
                   // alert("success!");


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