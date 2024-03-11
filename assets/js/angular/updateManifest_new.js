var app = angular.module('updateManifest', [])

        .controller('scanInventory', function ($scope, $http, $interval, $window, $location) {

            $scope.Menidata = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.scan_new = {};
            $scope.specialtype = {};

            $scope.shipData_new = [];

            $scope.awbArray = [];
            $scope.CustDropArr = {};
            $scope.shelve = null;
            $scope.scan_new.box_no = 1;
            $scope.cust_nameBtn = false;
            $scope.location_nameBtn = true;
            $scope.sku_nameBtn = true;
            $scope.nextBtnShow = false;
            $scope.shelve_nameBtn = true;
            $scope.TotalUserCount = 0;
            $scope.AlltotalCount = 0;
            $scope.AlltotalCount_shelve = 0;

            $scope.newCompeleteArr = [];
            $scope.ExportBtnShow = false;
            $scope.AddInventoryBtn = false;

            $scope.skuLoopArr = [];
            $scope.skuLoopArr_new = [];
            $scope.MatchStockLocation = [];
            $scope.MatchShelveArrr = [];
            $scope.UpdateInventoryCheck = 0;
            $scope.TotalCountSameTime = 0;
            $scope.singleStockLocationIndex = null;
            $scope.validCheck = [];
            $scope.checkstockUndo = true;
            //  disableScreen(1);
            $scope.loadershow = false;
            $scope.GetUrlData = function (uid, sid) {
                $scope.scan.uid = uid;
                $scope.scan.sid = sid;
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: URLBASE + "Stocks/GetSkulistForUpdateInventory",
                    method: "POST",
                    data: {uid: uid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response);
                    $scope.skuLoopArr = response.data.result;
                    $scope.skuLoopArr_new = response.data.result;
                    disableScreen(0);
                    $scope.loadershow = false;


                });
            }


            $scope.totalnumber = 0;
            $scope.checktotalnumber = 0;
            $scope.scan_sku = function () {

                $scope.arrayIndexnew1 = $scope.Menidata.findIndex(record => (record.sku === $scope.scan.sku));
                //  alert($scope.arrayIndexnew1);
                if ($scope.arrayIndexnew1 == -1) {
                    disableScreen(1);
                    $scope.loadershow = true;
                    $http({
                        url: URLBASE + "Stocks/GetUpdateManifestStockLocation",
                        method: "POST",
                        data: {list: $scope.scan, stockArr: $scope.MatchStockLocation, shelveArr: $scope.MatchShelveArrr},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response.data.result);
                        disableScreen(0);
                        $scope.loadershow = false;
                        if (response.data.result.length === 0) {
                            $scope.Message = null;
                            $scope.warning1 = true;
                            //  alert($scope.warning);

                            //responsiveVoice.speak($scope.warning);
                        } else {
                            $scope.warning1 = false;
                            if (response.data.countarray != response.data.countbox) {
                                $scope.warning2 = true;
                                $scope.countarray = response.data.countarray;
                                $scope.countbox = response.data.countbox;
                                response.data = [];

                            } else if ($scope.UpdateInventoryCheck == 0) {
                                $scope.countarray = response.data.countarray;
                                $scope.countbox = response.data.countbox;
                                $scope.warning2 = false;
                                $scope.ExportBtnShow = false;
                                $scope.AddInventoryBtn = false;
                                $scope.cust_nameBtn = true;
                                $scope.location_nameBtn = false;
                                $scope.shelve_nameBtn = true;
                                $scope.AlltotalCount = 0;
                                $scope.AlltotalCount_shelve = 0;
                                $scope.warning = null;
                                $scope.TotalUserCount = response.data.length;
                                $scope.TotalCountSameTime = response.data.result.length

                                $scope.warning = null;
                                $('#scan_stocklocation_id').focus();
                                $scope.Message = "Sku Scan";
                                //  alert($scope.warning);

                                responsiveVoice.speak($scope.Message);
                            } else {
                                $scope.ExportBtnShow = false;
                                $scope.AddInventoryBtn = false;
                                $scope.cust_nameBtn = false;
                                $scope.location_nameBtn = true;
                                $scope.shelve_nameBtn = true;
                                $scope.AlltotalCount = 0;
                                $scope.AlltotalCount_shelve = 0;
                                $scope.warning = null;
                                $scope.TotalUserCount = response.data.length;
                                $scope.TotalCountSameTime = response.data.result.length


                                //$('#scan_stocklocation_id').focus();
                                $scope.Message = "inventory added";
                                $scope.UpdateInventoryCheck = 0;

                            }


                        }
                        // alert(response.data.result.length);
                        // alert(response.data.result.length);
                        $scope.totalnumber = response.data.result.length;
                        $scope.checktotalnumber = 0;
                        angular.forEach(response.data.result, function (value, key) {

                            $scope.Menidata.push({'stockLocation': value.stockLocation, 'boxes': value.boxes,'totalqty_new':value.totalqty_new, 'capacity': value.capacity,'missing_qty': value.missing_qty,'damage_qty': value.damage_qty, 'shelveNo': value.shelveNo, 'sid': value.sid, 'sku': value.sku, 'storage_type': value.storage_type, 'totalqty': value.totalqty, 'uid': value.uid, 'warehouse_name': value.warehouse_name, 'wh_id': value.wh_id, 'totoalbox': value.countbox, 'total_location': response.data.countarray, 'scaned': 0, 's_status': 'pending', 'l_status': 'pending', 'scaned_s': 0, 'expire_date': value.expire_date, 'skuid': value.skuid, 'filled': value.filled, 'id': value.id});

                            $scope.MatchStockLocation.push(value.stockLocation);
                            $scope.MatchShelveArrr.push(value.shelveNo);

                            console.log($scope.Menidata);
                        });

                        // console.log($scope.Menidata);

                    });
                } else {
                    $scope.warning = "this sku already scanned!";
                    // $('#scan_stocklocation_id').focus();
                    $scope.Message = null;
                    responsiveVoice.speak($scope.warning);
                }




                // $scope.CheckCustomerInventory();
            }


            $scope.oldStockLocationArr = [];
            $scope.oldshelveArr = [];
            $scope.fillStockLocations = [];
            $scope.updatearr = [];
            $scope.GetcheckStockLocation = function (data, index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                //$scope.arrayIndexnew2 = $scope.Menidata.findIndex(record => (record.stockLocation === data.stockLocation));
                //alert($scope.arrayIndexnew2);
                console.log($scope.fillStockLocations);
                $scope.scan.list = data;
                $scope.scan.fillStockLocations = $scope.fillStockLocations;
                console.log($scope.scan);
                $http({
                    url: URLBASE + "Stocks/GetcheckStockLocation",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.stockError = response.data.error;
                    disableScreen(0);
                    $scope.loadershow = false;
                    if ($scope.stockError == 'valid')
                    {
                        $scope.Menidata[index].scaned = 1;
                        $scope.fillStockLocations.push($scope.Menidata[index].stockLocation);
                        $scope.checktotalnumber = parseInt($scope.checktotalnumber) + 1;
                        $scope.warning = null;
                        $scope.Message = "Stock Location Scan";
                        responsiveVoice.speak($scope.Message);
                        $scope.scan.c_number = $scope.checktotalnumber;
                        $scope.scan.t_number = $scope.totalnumber;
                    } else
                    {
                        $scope.warning = "Invalid Stock Location ";
                        // $('#scan_stocklocation_id').focus();
                        $scope.Message = null;
                        responsiveVoice.speak($scope.warning);
                        $scope.Menidata[index].stockLocation = "";
                    }

                    // console.log($scope.checktotalnumber+" tt=="+ $scope.totalnumber);
                    if ($scope.checktotalnumber == $scope.totalnumber)
                    {
                        $scope.ExportBtnShow = true;
                        $scope.AddInventoryBtn = true;

                        $scope.Message = null;

                        $scope.warning = "All Parts Scanned ";//+ $scope.Menidata[index].sku;
                        responsiveVoice.speak($scope.warning);
                    }
                    $scope.validCheck.push($scope.stockError);
                });
            };

            $scope.GetcheckshelvekLocation = function (data, index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                // alert(data.shelveNo);
                $scope.scan.list = data;
                $http({
                    url: URLBASE + "Stocks/GetcheckshelvekLocation",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    if (response.data.error == 'valid')
                    {

                        $scope.warning = null;
                        $scope.Message = "Shelve Location Scan ";

                        responsiveVoice.speak($scope.Message);
                        $scope.Menidata[index].scaned_s = 1;
                    } else
                    {
                        $scope.warning = "Invalid Shelve Location "; //+ response.data.location;
                        // $('#scan_stocklocation_id').focus();
                        $scope.Message = null;
                        responsiveVoice.speak($scope.warning);
                        $scope.Menidata[index].shelveNo = "";
                    }

                });

            };


            $scope.openStockLocation = function (index)
            {
                angular.forEach($scope.fillStockLocations, function (value,key) {
                    if(value==$scope.Menidata[index].stockLocation)
                    {
                   $scope.fillStockLocations.splice(key, 1);  
                  }
                });
               
                $scope.ExportBtnShow = false;
                $scope.AddInventoryBtn = false;
                $scope.Message = null;
                $scope.warning = "Stock Location Open "; //+ $scope.Menidata[index].stockLocation;
                responsiveVoice.speak($scope.warning);
                $scope.Menidata[index].scaned = 0;
                $scope.checktotalnumber = parseInt($scope.checktotalnumber) - 1;

            };
            $scope.openshelveLocation = function (index)
            {

                $scope.Message = null;
                $scope.warning = "Shelve Location Open ";// + $scope.Menidata[index].shelveNo;
                responsiveVoice.speak($scope.warning);
                $scope.Menidata[index].scaned_s = 0;

            };











            $scope.GetSaveReportInventpry = function () {
                //$scope.AddInventoryBtn = null;
                //console.log($scope.shipData);   
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: URLBASE + "Stocks/GetSaveInventoryManifest",
                    method: "POST",
                    data: {locations: $scope.Menidata, otherData: $scope.scan},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                   // disableScreen(0);
                    //$scope.loadershow = false;
                     $window.location.reload();
                   // $window.location.href = URLBASE + 'Shipment_og/show_manifest';

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
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.confirmedClick;
                        element.bind('click', function (event) {
                            if (window.confirm(msg)) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
            }])
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
/*------ /show shipments-----*/