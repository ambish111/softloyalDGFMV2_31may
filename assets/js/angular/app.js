var app = angular.module('fulfill', ['betsol.timeCounter'])



        .controller('shelveView', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []
            $scope.listData1 = {};

            /*hk code start*/
            $scope.listDatalist = {};

            $scope.listData2 = {
                "country_id": false,
                "city_id": false,
                "shelv_location": false,
                "shelv_no": false
            };
            $scope.getExcelDetails = function () {
                //console.log("test");
                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.ViewSlaveExport = function () {

                selected_cols = {};
                for (var key in $scope.listData2) {
                    if ($scope.listData2[key] == true) {
                        selected_cols[key] = true;
                    }
                }

                if (Object.keys(selected_cols).length == 0) {
                    alert("Please select at least one columns");
                    return false;
                }

                $scope.listDatalist.filterData = $scope.filterData;
                $scope.listDatalist.listData2 = selected_cols;

                //console.log($scope.listDatalist);
                $http({
                    //  url: "ItemInventory/getexceldata",
                    url: "ExcelExport/ViewSlaveExport",
                    method: "POST",
                    data: $scope.listDatalist,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                    $scope.toggleAll();
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
                $('#excelcolumn').modal('hide');
            }
            /*hk code end*/

            //console.log($scope.baseUrl);
            $scope.loadMore = function (page_no, reset)
            {
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "shelvefilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            ////console.log(value)

                            $scope.shipData.push(value);

//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //.//console.log( $scope.shipData[0].skuData[0])
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };





            $scope.exportExcel = function ()
            {
                //console.log($scope.shipData);
                $http({
                    url: "pickUp/pickListViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
            $scope.Shelveviewexportdata = function ()
            {
                //console.log($scope.shipData);
                $http({
                    url: "Shelve/Shelveviewexportdata",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", "Shelve Table ", d + ".xls");
                    $a[0].click();
                    $a.remove();


                });
            }

        })

        .controller('stockLocation', function ($scope, $http, $window, $location) {
//	alert("ssssssss");
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []

////console.log($scope.baseUrl);
            $scope.loadMore = function (page_no, reset)
            {
                //alert("sssssss");

                ////console.log(page_no);    
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "stockLocationFilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    ////console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            ////console.log(value)

                            $scope.shipData.push(value);

//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //.//console.log( $scope.shipData[0].skuData[0])
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };





            $scope.exportExcelShowstock = function ()
            {


                ////console.log($scope.shipData);
                $http({
                    url: "shelve/showstockViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //	  //console.log(response.data);

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
                ////console.log($scope.shipData);
                $http({
                    url: "pickUp/pickListViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    //console.log(response);

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
        .controller('dispatch', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.invalid = [];
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.type = 'DL';
            $scope.comments = "";
            $scope.BoxInputshow = false;
            $scope.disableBtnDispatch=true;
            $scope.scan_awb = function () {
                //$('#scan_awb').focus();
                //console.log($scope.scan);
                $scope.scan.awbArray = removeDumplicateValue($scope.scan.slip_no.split("\n"));
                //console.log($scope.scan.awbArray);
                $scope.scan.slip_no = $scope.scan.awbArray.join('\n');


                $scope.validateOrder();
            }
            $scope.GetCheckvalidbox = function ()
            {
                if ($scope.type == 'SFP')
                {
                    $scope.BoxInputshow = true;
                } else
                {
                    $scope.comments = "";
                    $scope.BoxInputshow = false;
                }
            };
            $scope.dispatchOrder = function ()
            {
                 $scope.warning = null;
                $scope.Message = null;
                  disableScreen(1);
                $scope.loadershow=true; 
                $scope.scan.type = $scope.type;
                $scope.scan.comments = $scope.comments;
                $http({
                    url: "PickUp/dispatchOrder",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                }).then(function (response) {
                    //console.log(response);
                    
                      disableScreen(0);
                        $scope.loadershow=false; 
                    if (response.data == 'null')
                    {

                        // console.log("scan="+$scope.scan.type);
                        // console.log("type="+$scope.type);
                        var sound = document.getElementById("audioSuccess");
                        sound.play();
                        // if ($scope.scan.type == 'DL')
                        if ($scope.type == 'DL')
                        {
                            $scope.Message = "Orders Dispatched !";
                        } else
                        {
                            $scope.Message = "Orders Delivered !";
                        }
                        responsiveVoice.speak($scope.Message);
                         $scope.scan={};
                        $scope.sendSms();

                    } else
                    {
                         $scope.scan={};
                        $scope.warning = response.data;
                    }
                    

                })

            }

            $scope.sendSms = function ()
            {
                $scope.scan.type = $scope.type;
                $http({
                    url: "PickUp/sendSms",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                }).then(function (response) {
                    //console.log(response);
                    $scope.scan = {};
                });

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
            $scope.validateOrder = function () {
                 disableScreen(1);
                $scope.loadershow=true;
                $scope.invalid = [];
                $scope.warning = null;
                $scope.Message = null;
                // alert("ss");

                ////console.log($scope.scan);
                $http({
                    url: "PickUp/validateDispatch",
                    method: "POST",
                    data: $scope.scan.awbArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                $scope.loadershow=false;
                    //console.log(response);
                   $scope.errorshow=response.data.status;
                    angular.forEach(response.data.invalid, function (value) {
                        //console.log(value)
                        var index = $scope.scan.awbArray.indexOf(value.slip_no);
                        if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                        }
                        $scope.invalid.push(value.slip_no);


                    });
                    $scope.scan.slip_no = $scope.scan.awbArray.join('\n');
                    $scope.invalidstring = $scope.invalid.join()

                     $scope.disableBtnDispatch=false;
                });




            }


        })

        .controller('dispatch_b2b', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.invalid = [];
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.type = 'DL';
            $scope.scan_awb = function () {
                //$('#scan_awb').focus();
                //console.log($scope.scan);
                $scope.scan.awbArray = removeDumplicateValue($scope.scan.slip_no.split("\n"));
                //console.log($scope.scan.awbArray);
                $scope.scan.slip_no = $scope.scan.awbArray.join('\n');


                $scope.validateOrder();
            }

            $scope.dispatchOrder = function ()
            {
                //console.log($scope.scan);
                $scope.scan.type = $scope.type;
                $http({
                    url: "PickUp/dispatchOrder",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                }).then(function (response) {

                    // $scope.scan.awbArray={};

                    if (response.data == 'null')
                    {

                        var sound = document.getElementById("audioSuccess");
                        sound.play();
                        $scope.Message = "Orders Dispatched !";
                        responsiveVoice.speak($scope.Message);
                        $scope.sendSms();

                    } else
                    {
                        $scope.warning = response.data;
                    }

                })

            }

            $scope.sendSms = function ()
            {
                $scope.scan.type = $scope.type;
                $http({
                    url: "PickUp/sendSms",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                }).then(function (response) {
                    //console.log(response);
                    $scope.scan = {};
                });

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
            $scope.validateOrder = function () {
                $scope.invalid = [];
                $scope.warning = null;
                $scope.Message = null;
                // alert("ss");

                ////console.log($scope.scan);
                $http({
                    url: "PickUp/validateDispatch",
                    method: "POST",
                    data: $scope.scan.awbArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response);

                    angular.forEach(response.data.invalid, function (value) {
                        //console.log(value)
                        var index = $scope.scan.awbArray.indexOf(value.slip_no);
                        if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                        }
                        $scope.invalid.push(value.slip_no);


                    });
                    $scope.scan.slip_no = $scope.scan.awbArray.join('\n');
                    $scope.invalidstring = $scope.invalid.join()


                });




            }


        })


//=====================return from LM======================//
        .controller('returnfromlm', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.invalid = [];
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.scan.type = 'RTC';
            $scope.scan_awb = function () {
                //$('#scan_awb').focus();
                //console.log($scope.scan);
                $scope.scan.awbArray = removeDumplicateValue($scope.scan.slip_no.split("\n"));
                //console.log($scope.scan.awbArray);
                $scope.scan.slip_no = $scope.scan.awbArray.join('\n');



                $scope.validateOrder();
            }

            $scope.returnformlmOrder = function ()
            {

                $http({
                    url: "PickUp/returnformlmOrder",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response);
                    // $scope.scan.awbArray=[];
                    // $scope.scan=[];

                    /*  $scope.scan={};
                     var sound = document.getElementById("audioSuccess");
                     sound.play();
                     $scope.Message="Orders Return To Fulfilment!";
                     responsiveVoice.speak($scope.Message); 
                     */
                })

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
            $scope.validateOrder = function () {
                $scope.invalid = [];
                $scope.warning = null;
                $scope.Message = null;

                //console.log($scope.scan);
                $http({
                    url: "PickUp/validatereturn",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    //console.log(response)
                    angular.forEach(response.data.invalid, function (value) {
                        // //console.log("///"+value.slip_no+"////")
                        var index = $scope.scan.awbArray.indexOf(value.slip_no);
                        if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                        }
                        $scope.invalid.push(value.slip_no);


                    });
                    $scope.scan.slip_no = $scope.scan.awbArray.join('\n');
                    $scope.invalidstring = $scope.invalid.join()


                });




            }


        })
//=========================================================//

        .controller('bulkUpdate', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.invalid = [];
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.scan_awb = function () {
                //alert($scope.scan.status);
                //$('#scan_awb').focus();
                // //console.log($scope.scan);
                $scope.scan.awbArray = removeDumplicateValue($scope.scan.slip_no.split("\n"));
                // //console.log($scope.scan.awbArray); 
                $scope.scan.slip_no = $scope.scan.awbArray.join('\n');


                $scope.validateOrder();
            }

            $scope.updateData = function ()
            {
                   disableScreen(1);
                $scope.loadershow=true; 
                //console.log($scope.scan);
                $http({
                    url: "updateData",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                       disableScreen(0);
                $scope.loadershow=false; 
                    //console.log(response);
                    $scope.scan = {};
                    $scope.scan.status = "";
                    $scope.scan.awbArray = [];

                    if (response.data.error.length > 0)
                    {
                        $scope.warning = response.data.error + " Not Updated!";
                    }
                    if (response.data.success.length > 0)
                    {
                        
                        var sound = document.getElementById("audioSuccess");
                        sound.play();
                        $scope.Message = "Orders Updated!";

                        responsiveVoice.speak($scope.Message);

                    }

                })

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
            $scope.scan.validate = null;
            $scope.validateOrder = function () {
                $scope.invalid = [];
                $scope.warning = null;
                $scope.Message = null;
                $scope.invalidstring = {};
                ////console.log($scope.scan);
                $http({
                    url: "validateUpdate",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                }).then(function (response) {

                    //console.log(response);

                    angular.forEach(response.data.invalid, function (value) {
                        //console.log(value)
                        var index = $scope.scan.awbArray.indexOf(value.slip_no);
                        if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                        }
                        $scope.invalid.push(value.slip_no);


                    });
                    $scope.scan.slip_no = $scope.scan.awbArray.join('\n');
                    $scope.invalidstring = $scope.invalid.join()
                    if ($scope.invalidstring)
                    {
                        $scope.scan = {};
                    }
                    // //console.log($scope.scan.status);
                    if ($scope.scan.status.length > 0 && $scope.scan.slip_no.length > 0)
                    {
                        $scope.scan.validate = true;

                    } else
                    {
                        $scope.scan.validate = null;
                    }


                });




            }


        })

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
              $scope.loadershow=false; 

            $scope.GetremoveBtn = false;
            $scope.scan_new.box_no = 1;
            
            $scope.scan_awb = function () {
                $('#scan_awb').focus();
                $scope.packuShip();
            }
            $scope.scan_awb_new = function () {
                
                $('#scan_awb').focus();
                $scope.packuShip_new();
                
                
            }
            $scope.setFocus = function (id, type)
            {
                /// //console.log();
                if (type == 'sh')
                {
                    document.getElementById('st_' + id).value = '';
                    document.getElementById('st_' + id).focus();
                } else
                {
                    if (document.getElementById('sh_' + (id + 1)) != undefined)
                    {
                        document.getElementById('sh_' + (id + 1)).value = '';
                        document.getElementById('sh_' + (id + 1)).focus();
                    }

                }

            }
            $scope.GetremoveShipemtData = function (s_no)
            {

                $scope.arrayIndex_r = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === s_no.toUpperCase());
                //  alert($scope.arrayIndex_r);
                if ($scope.arrayIndex_r !== -1)
                {
                    angular.forEach($scope.awbArray, function (value, key) {
                        if (value.slip_no == s_no)
                        {
                            $scope.awbArray.splice($scope.arrayIndex_r, 1);
                            $scope.shipData.splice($scope.arrayIndex_r, 1);
                            $scope.SKuMediaArr.splice($scope.arrayIndex_r, 1);
                            $scope.completeArray.splice($scope.arrayIndex_r, 1);
                        }
                        // alert(key);
                    });

                }
            }

              $scope.packuShip_new = function () {
                   disableScreen(1);
                $scope.loadershow=true; 
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    ////console.log($scope.scan);
                    $http({
                        url: "PickUp/packCheck_new",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                         disableScreen(0);
                $scope.loadershow=false; 
                        //$scope.specialtype.specialpack=true;
                        //$scope.specialtype.specialpacktype="warehouse";
                        if (response.data.count == 0)
                        {
                            $scope.warning = "Order Not available for packing!";
                            responsiveVoice.speak($scope.warning);
                        } else
                        {
                            $scope.GetremoveBtn = true;
                        }
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.awbArray.push(value);
                            angular.forEach(JSON.parse(value.sku), function (value1) {
                                ////console.log(value1)

                                $scope.shipData.push({'slip_no': value.slip_no, 'sku': value1.sku, 'piece': value1.piece, 'scaned': 0, 'extra': 0, 'print_url': value.print_url, 'frwd_company_id': value.frwd_company_id, 'frwd_company_awb': value.frwd_company_awb});
                                $scope.SKuMediaArr.push({'sku': value1.sku, 'piece': value1.piece, 'item_path': value1.item_path});

                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                            });

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });

                        // //console.log( $scope.SKuMediaArr);
                        // $scope.GetcheckskuOtherData($scope.shipData[$scope.arrayIndexnew].sku,$scope.shipData[$scope.arrayIndexnew].piece);




                    });
                }

                $scope.scanCheck();
                $scope.checkComplte($scope.shipData, $scope.scan.slip_no);

            }
            $scope.packuShip = function () {
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    ////console.log($scope.scan);
                    $http({
                        url: "PickUp/packCheck",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //$scope.specialtype.specialpack=true;
                        //$scope.specialtype.specialpacktype="warehouse";
                        if (response.data.count == 0)
                        {
                            $scope.warning = "Order Not available for packing!";
                            responsiveVoice.speak($scope.warning);
                        } else
                        {
                            $scope.GetremoveBtn = true;
                        }
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.awbArray.push(value);
                            angular.forEach(JSON.parse(value.sku), function (value1) {
                                ////console.log(value1)

                                $scope.shipData.push({'slip_no': value.slip_no, 'sku': value1.sku, 'piece': value1.piece, 'scaned': 0, 'extra': 0, 'print_url': value.print_url, 'frwd_company_id': value.frwd_company_id, 'frwd_company_awb': value.frwd_company_awb});
                                $scope.SKuMediaArr.push({'sku': value1.sku, 'piece': value1.piece, 'item_path': value1.item_path});

                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                            });

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });

                        // //console.log( $scope.SKuMediaArr);
                        // $scope.GetcheckskuOtherData($scope.shipData[$scope.arrayIndexnew].sku,$scope.shipData[$scope.arrayIndexnew].piece);




                    });
                }

                $scope.scanCheck();
                $scope.checkComplte($scope.shipData, $scope.scan.slip_no);

            }
            $scope.packBoxArr = {};

            $scope.GetCheckBoxNo = function ()
            {
                $scope.scan_new.slip_no = $scope.scan.slip_no;
            }
            $scope.Getallspecialpackstatus = function ()
            {

                // $scope.completeArray.specialtype=$scope.specialtype.specialpacktype;
                //  $scope.completeArray.specialpack=$scope.specialtype.specialpack;
                //$scope.specialtype.specialpack=true;
                //$scope.completeArray.push({'specialpack':$scope.specialtype.specialpack,'specialpacktype':$scope.specialtype.specialpacktype});
                //console.log($scope.specialpacktype);
            }


            $scope.scanCheck = function ()
            {
                $scope.arrayIndexnew = $scope.shipData.findIndex(record => (record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase() && record.sku.toUpperCase().replace(/\s/g, '') === $scope.scan.sku.toUpperCase().replace(/\s/g, '')))
                // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                if ($scope.arrayIndexnew != -1)
                {
                    if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) < parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                    {
                        $scope.shipData[$scope.arrayIndexnew].scaned = parseInt($scope.shipData[$scope.arrayIndexnew].scaned) + 1;

                        if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) == parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                        {

                            $('#GetSkuId' + $scope.arrayIndexnew).css({"background-color": "green"});
                            $scope.Message = null;
                            // $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
                            $scope.warning = 'All Parts Scanned for ' + $scope.shipData[$scope.arrayIndexnew].sku;
                            //responsiveVoice.speak($scope.warning);   
                        } else
                        {
                            // alert($scope.shipData[$scope.arrayIndexnew].sku);
                            $scope.Message = 'Scaned!';
                            //responsiveVoice.speak($scope.message);    
                            responsiveVoice.speak('Scaned!');
                        }


                    } else
                    {

                        //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                        $scope.shipData[$scope.arrayIndexnew].extra = parseInt($scope.shipData[$scope.arrayIndexnew].extra) + 1;
                        $scope.Message = null;
                        $scope.warning = 'Extra Item Scaned';
                        responsiveVoice.speak($scope.warning);
                        //$scope.warning='Shipment Already scanned';
                        var sound = document.getElementById("audio");
                        sound.play();

                    }



                } else
                {
                    if ($scope.scan.sku.length > 0)
                    {
                        $scope.Message = null;
                        $scope.warning = $scope.scan.sku + ', SKU not available for this shipment!';
                        responsiveVoice.speak('SKU not available for this shipment!');
                    } else

                    {

                    }


                }
                $scope.scan.sku = null;
            }
            $scope.ShowOtherSkuArr = {};
            $scope.showmedia = false;
            $scope.GetcheckskuOtherData = function (sku, qty)
            {

                $http({
                    url: "ItemInventory/GetshowingSkuMeadiaData",
                    method: "POST",
                    data: {sku: sku},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.showmedia = true;
                    $scope.ShowOtherSkuArr = response.data;
                    $scope.ShowOtherSkuArr.qty = qty;


                });
            }

            $scope.completeArray = [];
            // $scope.checkArray=[];  
            $scope.checkComplte = function (dataArray, slip_no)
            {
                $scope.checkArray = [];
                angular.forEach(dataArray, function (value) {

                    if (value.slip_no == slip_no)
                    {
                        $scope.checkArray.push(value);


                    }
                });
                $scope.checkqty = 0;
                angular.forEach($scope.checkArray, function (value) {
                    if (value.piece == value.scaned)
                    {
                        $scope.checkqty++
                    }


                });
                if ($scope.checkArray.length == $scope.checkqty && $scope.checkqty > 0)
                {
                    //alert($scope.scan_new.box_no);
                    $scope.inxexComp = $scope.completeArray.findIndex(record => (record.slip_no === $scope.scan.slip_no))
                    if ($scope.inxexComp == -1)
                    {
                        $scope.PrintBtnallAwb = true;
                        $scope.awbArray_print.push($scope.checkArray[0].slip_no);
                        $scope.buttontype = null;
                        if ($scope.checkArray[0].frwd_company_id == 'Esnad' || $scope.checkArray[0].frwd_company_id == 'Labaih' || $scope.checkArray[0].frwd_company_id == 'Clex' || $scope.checkArray[0].frwd_company_id == 'Barqfleet' || $scope.checkArray[0].frwd_company_id == 'Shipadelivery')
                        {
                            $scope.buttontype = "A4";
                        } else
                        {
                            $scope.buttontype = "4*6";
                        }
                        $scope.completeArray.push({'slip_no': $scope.checkArray[0].slip_no, 'specialpack': $scope.specialtype.specialpack, 'specialpacktype': $scope.specialtype.specialpacktype, 'print_url': $scope.checkArray[0].print_url, 'frwd_company_id': $scope.checkArray[0].frwd_company_id, 'frwd_company_awb': $scope.checkArray[0].frwd_company_awb, 'box_no': $scope.scan_new.box_no, 'buttontype': $scope.buttontype});

                        $scope.scan.slip_no = null;
                        $scope.GetremoveBtn = false;
                    }
                    // alert($scope.specialtype.specialpack);

                    $scope.warning = null;
                    var soundsuccess = document.getElementById("audioSuccess");
                    soundsuccess.play();
                    //$scope.Message = $scope.checkArray[0].slip_no + ' Completly Scaned Please Pack this Order!';
                    $scope.Message = 'Completly Scaned Please Pack this Order!';
                    //responsiveVoice.speak($scope.message);  



                    responsiveVoice.speak('Completly Scaned, Please Pack this Order!');

                    $scope.nindex = $scope.shipData.findIndex(record => (record.slip_no.toUpperCase() === $scope.checkArray[0].slip_no.toUpperCase()))
                    //console.log($scope.nindex);
                    // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                    if ($scope.nindex != -1) {
                        $scope.print_url = $scope.shipData[$scope.nindex].print_url

                        $scope.printToCart($scope.print_url);
                    }
                }
                //console.log($scope.awbArray_print);
            }
            $scope.printToCart = function (print_url) {
                // $window.open("//www.tamex.co/", '', '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
                //var innerContents = document.getElementById('test_print').innerHTML;
                // var popupWinindow =  $window.open(print_url, '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');

                // WindowObject.close();
                // popupWinindow.document.close();
                //setTimeout(function () { popupWinindow.close();}, 3000);

            }
            $scope.finishScan = function ()
            {

                if ($scope.completeArray.length > 0)
                {
                    var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                    if (isconfirm)
                    {
                        $http({
                            url: "PickUp/packFinish",
                            method: "POST",
                            data: {
                                shipData: $scope.completeArray,
                                exportData: $scope.shipData,
                                SpecialArr: $scope.specialtype,
                                boxArr: $scope.scan_new,
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                            //console.log(response);

//         var d = new Date();
//    var $a = $("<a>");
//    $a.attr("href",response.data.file);
//    $("body").append($a);
//    $a.attr("download",response.data.file_name);
//    $a[0].click();
//    $a.remove();

                            $scope.GetremoveBtn = true;
                            $scope.scan = {};

                            $scope.SKuMediaArr = [];
                            $scope.shipData = [];
                            $scope.completeArray = [];
                            $scope.Message = "Completed order Packed!";


                        }, function (error) {
                            //console.log(error);
                        });
                    }

                }
            }

        })

        .controller('scanShipment_tods', function ($scope, $http, $interval, $window) {
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

            $scope.GetremoveBtn = false;
            $scope.scan_new.box_no = 1;
            $scope.scan_awb = function () {
                $('#scan_awb').focus();
                $scope.packuShip();
            }

            $scope.GetremoveShipemtData = function (s_no)
            {

                $scope.arrayIndex_r = $scope.awbArray.findIndex(record => record.tods_barcode.toUpperCase() === s_no.toUpperCase());
                //  alert($scope.arrayIndex_r);
                if ($scope.arrayIndex_r !== -1)
                {
                    angular.forEach($scope.awbArray, function (value, key) {
                        if (value.tods_barcode == s_no)
                        {
                            $scope.awbArray.splice($scope.arrayIndex_r, 1);
                            $scope.shipData.splice($scope.arrayIndex_r, 1);
                            $scope.SKuMediaArr.splice($scope.arrayIndex_r, 1);
                            $scope.completeArray.splice($scope.arrayIndex_r, 1);
                        }
                        // alert(key);

                    });

                }
            }


            $scope.packuShip = function () {
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.tods_barcode.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    ////console.log($scope.scan);
                    $http({
                        url: "PickUp/packCheck_tod",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //$scope.specialtype.specialpack=true;
                        //$scope.specialtype.specialpacktype="warehouse";
                        if (response.data.count == 0)
                        {
                            $scope.GetremoveBtn = false;
                            $scope.warning = "Order Not available for packing!";
                            responsiveVoice.speak($scope.warning);
                        } else
                        {
                            $scope.GetremoveBtn = true;
                        }
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.awbArray.push(value);
                            angular.forEach(JSON.parse(value.sku), function (value1) {
                                ////console.log(value1)

                                $scope.shipData.push({'slip_no': value.tods_barcode, 'slip_no_check': value.slip_no, 'sku': value1.sku, 'piece': value1.piece, 'scaned': 0, 'extra': 0, 'print_url': value.print_url, 'frwd_company_id': value.frwd_company_id, 'frwd_company_awb': value.frwd_company_awb});
                                $scope.SKuMediaArr.push({'sku': value1.sku, 'piece': value1.piece, 'item_path': value1.item_path});

                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                            });

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });

                        // //console.log( $scope.SKuMediaArr);
                        // $scope.GetcheckskuOtherData($scope.shipData[$scope.arrayIndexnew].sku,$scope.shipData[$scope.arrayIndexnew].piece);




                    });
                }

                $scope.scanCheck();
                $scope.checkComplte($scope.shipData, $scope.scan.slip_no);

            }
            $scope.packBoxArr = {};

            $scope.GetCheckBoxNo = function ()
            {
                $scope.scan_new.slip_no = $scope.scan.slip_no;
            }
            $scope.Getallspecialpackstatus = function ()
            {

                // $scope.completeArray.specialtype=$scope.specialtype.specialpacktype;
                //  $scope.completeArray.specialpack=$scope.specialtype.specialpack;
                //$scope.specialtype.specialpack=true;
                //$scope.completeArray.push({'specialpack':$scope.specialtype.specialpack,'specialpacktype':$scope.specialtype.specialpacktype});
                //console.log($scope.specialpacktype);
            }


            $scope.scanCheck = function ()
            {
                $scope.arrayIndexnew = $scope.shipData.findIndex(record => (record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase() && record.sku.toUpperCase() === $scope.scan.sku.toUpperCase()))
                // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                if ($scope.arrayIndexnew != -1)
                {
                    if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) < parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                    {
                        $scope.shipData[$scope.arrayIndexnew].scaned = parseInt($scope.shipData[$scope.arrayIndexnew].scaned) + 1;

                        if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) == parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                        {

                            $('#GetSkuId' + $scope.arrayIndexnew).css({"background-color": "green"});
                            $scope.Message = null;
                            // $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
                            $scope.warning = 'All Parts Scanned for ' + $scope.shipData[$scope.arrayIndexnew].sku;
                            //responsiveVoice.speak($scope.warning);   
                        } else
                        {
                            // alert($scope.shipData[$scope.arrayIndexnew].sku);
                            $scope.Message = 'Scaned!';
                            //responsiveVoice.speak($scope.message);    
                            responsiveVoice.speak('Scaned!');
                        }


                    } else
                    {

                        //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                        $scope.shipData[$scope.arrayIndexnew].extra = parseInt($scope.shipData[$scope.arrayIndexnew].extra) + 1;
                        $scope.Message = null;
                        $scope.warning = 'Extra Item Scaned';
                        responsiveVoice.speak($scope.warning);
                        //$scope.warning='Shipment Already scanned';
                        var sound = document.getElementById("audio");
                        sound.play();

                    }



                } else
                {
                    if ($scope.scan.sku.length > 0)
                    {
                        $scope.Message = null;
                        $scope.warning = $scope.scan.sku + ', SKU not available for this shipment!';
                        responsiveVoice.speak('SKU not available for this shipment!');
                    } else

                    {

                    }


                }
                $scope.scan.sku = null;
            }
            $scope.ShowOtherSkuArr = {};
            $scope.showmedia = false;
            $scope.GetcheckskuOtherData = function (sku, qty)
            {

                $http({
                    url: "ItemInventory/GetshowingSkuMeadiaData",
                    method: "POST",
                    data: {sku: sku},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.showmedia = true;
                    $scope.ShowOtherSkuArr = response.data;
                    $scope.ShowOtherSkuArr.qty = qty;


                });
            }

            $scope.completeArray = [];
            // $scope.checkArray=[];  
            $scope.checkComplte = function (dataArray, slip_no)
            {
                $scope.checkArray = [];
                angular.forEach(dataArray, function (value) {

                    if (value.slip_no == slip_no)
                    {
                        $scope.checkArray.push(value);


                    }
                });
                $scope.checkqty = 0;
                angular.forEach($scope.checkArray, function (value) {
                    if (value.piece == value.scaned)
                    {
                        $scope.checkqty++
                    }


                });
                if ($scope.checkArray.length == $scope.checkqty && $scope.checkqty > 0)
                {
                    //alert($scope.scan_new.box_no);
                    $scope.inxexComp = $scope.completeArray.findIndex(record => (record.slip_no === $scope.scan.slip_no))
                    if ($scope.inxexComp == -1)
                    {
                        $scope.PrintBtnallAwb = true;
                        $scope.awbArray_print.push($scope.checkArray[0].slip_no_check);
                        $scope.completeArray.push({'slip_no': $scope.checkArray[0].slip_no, 'slip_no_check': $scope.checkArray[0].slip_no_check, 'specialpack': $scope.specialtype.specialpack, 'specialpacktype': $scope.specialtype.specialpacktype, 'print_url': $scope.checkArray[0].print_url, 'frwd_company_id': $scope.checkArray[0].frwd_company_id, 'frwd_company_awb': $scope.checkArray[0].frwd_company_awb, 'box_no': $scope.scan_new.box_no});
                    }
                    // alert($scope.specialtype.specialpack);

                    $scope.warning = null;
                    var soundsuccess = document.getElementById("audioSuccess");
                    soundsuccess.play();
                    //$scope.Message = $scope.checkArray[0].slip_no + ' Completly Scaned Please Pack this Order!';
                    $scope.Message = 'Completly Scaned Please Pack this Order!';
                    //responsiveVoice.speak($scope.message);  



                    responsiveVoice.speak('Completly Scaned, Please Pack this Order!');

                    $scope.nindex = $scope.shipData.findIndex(record => (record.slip_no.toUpperCase() === $scope.checkArray[0].slip_no.toUpperCase()))
                    //console.log($scope.nindex);
                    // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                    if ($scope.nindex != -1) {
                        $scope.print_url = $scope.shipData[$scope.nindex].print_url

                        $scope.printToCart($scope.print_url);
                    }
                }
                //console.log($scope.awbArray_print);
            }
            $scope.printToCart = function (print_url) {
                // $window.open("//www.tamex.co/", '', '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
                //var innerContents = document.getElementById('test_print').innerHTML;
                // var popupWinindow =  $window.open(print_url, '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');

                // WindowObject.close();
                // popupWinindow.document.close();
                //setTimeout(function () { popupWinindow.close();}, 3000);

            }
            $scope.finishScan = function ()
            {

                if ($scope.completeArray.length > 0)
                {
                    var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                    if (isconfirm)
                    {
                        $http({
                            url: "PickUp/packFinish_tod",
                            method: "POST",
                            data: {
                                shipData: $scope.completeArray,
                                exportData: $scope.shipData,
                                SpecialArr: $scope.specialtype,
                                boxArr: $scope.scan_new,
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                            //console.log(response);

//         var d = new Date();
//    var $a = $("<a>");
//    $a.attr("href",response.data.file);
//    $("body").append($a);
//    $a.attr("download",response.data.file_name);
//    $a[0].click();
//    $a.remove();

                            $scope.GetremoveBtn = false;
                            $scope.SKuMediaArr = {};
                            $scope.shipData = [];
                            $scope.completeArray = [];
                            $scope.Message = "Completed order Packed!";


                        }, function (error) {
                            //console.log(error);
                        });
                    }

                }
            }

        })


        /*------ show shipments-----*/
        .controller('shipment_view', function ($scope, $http, $window, $location) {
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.scan={};
            $scope.excelshipData = [];
            $scope.dropexport = [];
            $scope.Items = [];
            $scope.dropshort = {};
            $scope.loadershow = false;
            $scope.filterData.s_type = 'AWB';
            $scope.filterData.status_o = '';
             $scope.validCheck=[];
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.listData2 = {
                "entrydate": false,
                "booking_id": false,
                "shippers_ref_no": false,
                "slip_no": false,
                "origin": false,
                "destination": false,
                "destination_country":false,
                "sender_name": false,
                "sender_address": false,
                "sender_phone": false,
                "reciever_name": false,
                "reciever_address": false,
                "reciever_phone": false,
                "mode": false,
                "delivered": false,
                "total_cod_amt": false,
                "cust_id": false,
                "pieces": false,
                "weight": false,
                "status_describtion": false,
                "frwd_awb_no": false,
                "transaction_no": false,
                "pay_Invoice_status": false,
                "sub_category": false,
                "onHold_Confirm": false,
                "onHold_Date": false,
                "onHold_Reason": false,
                "shelv_no": false,
                "schedule_date": false,
                "time_slot": false,
                "area_street": false,
                "area": false,
                "dest_lat": false,
                "dest_lng": false,
                "delever_date": false,
                "frwd_throw": false,
                "payable_status": false,
                "receivable_status": false,
                "receivable_invoice_no": false,
                "show_code": false,
                "messenger_name": false,
                "close_date": false,
                "status_o": false,
                "invoice_details": false,
                "pl3_pickup_date": false,
                "pl3_close_date": false,
                "frwd_date": false,
                "transaction_days": false,
                "no_of_attempt": false,
                "cc_name": false,
                "last_status_n": false,
                "laststatus_first": false,
                "laststatus_second": false,
                "laststatus_last": false,
                "fd1_date": false,
                "fd2_date": false,
                "fd3_date": false,
                "audit_status": false,
                "suggest_company": false,
                "cod_received_3pl": false,
                "cod_received_date": false,
                "dispatch_date": false,
                "on_hold": false,
            };

            $scope.pageloader=function()
            {
                disableScreen(1);
                $scope.loadershow=true; 
            };
            $scope.GetorderopenCheck=function(s_no)
            {
                  disableScreen(1);
                $scope.loadershow=true; 
                $scope.scan.slip_no=s_no;
                $http({
                    url: $scope.baseUrl + "/Shipment_og/getcheckSimpleOpenOrder",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {
                    
                     disableScreen(0);
                    $scope.loadershow=false; 
                   // alert("hhh"+response.data.success+"dd");
                    if(response.data.status=='succ')
                    {
                        alert("Updated");
                        $scope.loadMore(1,1);

                    }
                    else{
                        alert("Invliad Order");
                    }
                });
            };
            $scope.showCity = function ()
            {


                $http({
                    url: $scope.baseUrl + "/Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
            $scope.setFocus = function (id, type)
            {
                /// //console.log();
                if (type == 'sh')
                {
                    document.getElementById('st_' + id).value = '';
                    document.getElementById('st_' + id).focus();
                } else
                {
                    if (document.getElementById('sh_' + (id + 1)) != undefined)
                    {
                        document.getElementById('sh_' + (id + 1)).value = '';
                        document.getElementById('sh_' + (id + 1)).focus();
                    }

                }

            }
            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;
                //console.log("?dsfsdf");

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            
            $scope.loadMore = function (page_no, reset,torod)
            {
                
                  disableScreen(1);
                $scope.loadershow=true; 
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.torod = torod;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Shipment/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                     disableScreen(0);
                $scope.loadershow=false; 
                    //console.log(response)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.shipDataexcel = response.data.excelresult;
                    $scope.dropexport = response.data.dropexport;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value['3pl_pickup_date'])
                            value.mydate = new Date(value['3pl_pickup_date']);
                            //if(value['3pl_pickup_date'])
                            $scope.shipData.push(value);

                        });
                        //console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };

            $scope.torod = '';
        $scope.torodwarehouse = [];
        $scope.getAddressList = function ()
        {
          

            $http({
                url: "Shipment/Showlist_address_wherehouse",
                method: "POST",
            
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}

            }).then(function (response) {

                disableScreen(0);
                $scope.loadershow = false;
                if (response.data.length > 0) {
                    angular.forEach(response.data, function (value) {
                        ////console.log(value.slip_no)
                        angular.forEach(value, function (data) {
                            ////console.log(value.slip_no)

                            $scope.torodwarehouse.push(data);

                        });
                      

                    });
                 
                }
            });

     

        };

        $scope.torod = '';
        $scope.Add_Wherehouse = function ()
        {
           

            $http({
                url: "Shipment/add_address",
                method: "POST",
                data: $scope.filterData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}

            }).then(function (response) {
                //console.log(response)
                $scope.dropshort = response.data.dropshort;
                $scope.totalCount = response.data.count;
                $scope.shipDataexcel = response.data.excelresult;
                $scope.dropexport = response.data.dropexport;
                 disableScreen(0);
                $scope.loadershow = false;
                if (response.data.result.length > 0) {
                    angular.forEach(response.data.result, function (value) {
                        ////console.log(value.slip_no)

                        $scope.shipData.push(value);

                    });
                    ////console.log( $scope.shipData)
                    //$scope.$broadcast('scroll.infiniteScrollComplete');
                } else {
                    $scope.nodata = true
                }

               



            }, function (status, error) {

                disableScreen(0);
                $scope.loadershow = false;
            })


        };
        $scope.filterData = { };
        $scope.Success_msg = {};
        $scope.Error_msg ={};
        $scope.add_wherehouse_address = function (filterData)
        {
        //    alert("test");
        //    $scope.filterData=$scope.filterData;
            $http({
                url: "Shipment/Create_Generate_address",
                method: "POST",
                data: $scope.filterData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}

            }).then(function (response) {
              
                 disableScreen(0);
                $scope.loadershow = false;
                if (response.data.status == true) {
                    $scope.Success_msg[0] = 'Merchant address created successfully.';
                    window.location.href= 'GenerateTorodWherehouse';
                } else {
                    $scope.Error_msg = response.data.message;
                }

               



            }, function (status, error) {

                disableScreen(0);
                $scope.loadershow = false;
            })


        };
        $scope.torod = '';
        $scope.dataSubmite = function (filterData)
        {
           alert("test22");
           $scope.data=$scope.filterData;
            $http({
                url: "Shipment/edit_adr_wherehouse",
                method: "POST",
                data: $scope.filterData,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}

            }).then(function (response) {
                //console.log(response)
                $scope.dropshort = response.data.dropshort;
              
                $scope.shipDataexcel = response.data.excelresult;
                $scope.dropexport = response.data.dropexport;
                 disableScreen(0);
                $scope.loadershow = false;
                if (response.data.result.length > 0) {
                    angular.forEach(response.data.result, function (value) {
                        ////console.log(value.slip_no)

                        $scope.shipData.push(value);

                    });
                    ////console.log( $scope.shipData)
                    //$scope.$broadcast('scroll.infiniteScrollComplete');
                } else {
                    $scope.nodata = true
                }

               



            }, function (status, error) {

                disableScreen(0);
                $scope.loadershow = false;
            })


        };

            $scope.loadMoreReverse = function (page_no, reset)
            {
                //  disableScreen(1);
                //$scope.loadershow=true; 
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Shipment/filterReverse",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.shipDataexcel = response.data.excelresult;
                    $scope.dropexport = response.data.dropexport;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value['3pl_pickup_date'])
                            value.mydate = new Date(value['3pl_pickup_date']);
                            //if(value['3pl_pickup_date'])
                            $scope.shipData.push(value);

                        });
                        //console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };



            $scope.runshell_tracking = function ()
            {

                $http({
                    url: "Shipment/runshell_tracking",
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    alert("Sync process has been start. Please wait for 30 minute to update data. ");


                })

            }



            $scope.GetProcessOpenOrder = function (slip_no)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "Shipment/GetProcessOpenOrder",
                    method: "POST",
                    data: {slip_no: slip_no},
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {

                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("successfully status changed");
                    $scope.loadMore(1, 1);
                });
            };
            $scope.GetInventoryPopup = function (id) {
                disableScreen(1);
                $scope.loadershow = true;
                //alert(id); 
                //data:$scope.shipData,
                $scope.filterData.id = id;
                $http({
                    url: "Shipment/filterdetail",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {
                    ////console.log(response)

                    $scope.shipData1 = response.data;
                    //console.log($scope.shipData1)
                    $("#deductQuantityModal").modal({
                        backdrop: 'static',
                        keyboard: true
                    })

                    disableScreen(0);
                    $scope.loadershow = false;


                })



            }
            $scope.saveBTNcheck=false;
            $scope.GetStockLocationPopup = function (id) {
                disableScreen(1);
                $scope.loadershow = true;
                //alert(id); 
                //data:$scope.shipData,
                $scope.itemData1 = [];
                $scope.filterData.id = id;
                $http({
                    url: "Shipment/GetStockLocation",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {
                    //console.log(response)

                    $scope.itemData1 = response.data;
                    //console.log($scope.itemData1)
                    $("#StocklocationModal").modal({
                        backdrop: 'static',
                        keyboard: true
                    })

                    disableScreen(0);
                    $scope.loadershow = false;


                })



            }
            
             $scope.checkbuttonverify=function()
            {
                $scope.saveBTNcheck = true;  
            };
            $scope.stockError = "valid";
            $scope.GetcheckStockLocation = function (data_v, in_1)
            {
                

                //console.log(data_v);
                $http({
                    url: "PickUp/GetCheckStockLOcationValid",
                    method: "POST",
                    data: data_v,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    $scope.stockError = response.data.error;
                    $scope.validCheck.push($scope.stockError);
                    if($scope.stockError=="invalid_location")
                    {
                       $scope.saveBTNcheck = false;  
                    }
                    // alert($scope.stockError);
                });
            }

            $scope.chnagelocation = function (St_id)
            {
                // $scope.stockdata = undefined;
                //console.log(St_id);
                // //console.log(dop);
                //console.log($scope.itemData1[St_id].stockdata);
                if ($scope.stockdata == undefined)
                {
                    //console.log($scope.itemData1[St_id]);

                }

            }


            $scope.savedetails = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                const  isInArray = $scope.validCheck.includes('invalid_location');

                if($scope.stockError=="valid" && isInArray==false)
                   {
                
                    $http({
                        url: "Shipment/save_details",
                        method: "POST",
                        data: $scope.itemData1,
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }).then(function (response) {

                        disableScreen(0);
                        $scope.loadershow = false;
                        //console.log(response);
                        alert("successfully order open");
                        $scope.loadMore(1, 1);
                    });

                    $("#StocklocationModal").modal('hide');
                } else
                {
                    $scope.validCheck=[];
                    
                       disableScreen(0);
                        $scope.loadershow = false;
                   if($scope.stockError=="invalid_location")
                        $scope.warning = "Please Enter Valid Stock Location";  
                    else if($scope.stockError=="capacity_full")
                        $scope.warning = "This Stock Location Capacity is Full. Please try other location";  
                    else
                         $scope.warning = "Please Enter Valid Stock Location";  

                    responsiveVoice.speak($scope.warning);
                }
            };



            $scope.ExportData = {};
            $scope.listData1 = [];

            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };


            $scope.checkall = false;
            $scope.checkAll = function () {
                if ($scope.checkall === false) {
                    angular.forEach($scope.listData1, function (data) {
                        data.checked = true;
                    });
                    $scope.checkall = true;
                } else {
                    angular.forEach($scope.listData1, function (data) {
                        data.checked = false;
                    });
                    $scope.checkall = false;
                }
            };

            $scope.selectedAll = false;
            $scope.selectAll = function (val) {
                //alert(val);


                // //console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // //console.log(key+"======="+newval);
                    if (key <= newval)
                    {

                        ////console.log(key+"======="+newval);
                        ////console.log($scope.selectedAll);
                        data.Selected = true;

                        $scope.Items.push(data.slip_no);

                    } else
                    {
                        ////console.log($scope.selectedAll);
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.slip_no);
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

            $scope.removemultipleorder = function ()
            {
                //console.log($scope.Items);
                if ($scope.Items.length > 0)
                {
                    $http({
                        url: "Shipment/GetremoveMultipleOrders",
                        method: "POST",
                        data: {slipData: $scope.Items, page_check: 'ship_view'},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                    }).then(function (response) {
                        if (response.data == "succ")
                        {
                            alert("successfuly Deleted");
                            $window.location.reload();
                        } else
                        {
                            alert("successfuly Deleted");
                            $window.location.reload();
                        }

                    });
                } else
                {
                    alert("please select atleat one order");
                }

            };


            $scope.transferShip1 = function () {
                 disableScreen(1);
                $scope.loadershow = true;
                //$scope.exportlimit

                $scope.listDatalist.filterData = $scope.filterData;
                //$scope.listDatalist.listData2 = $scope.listData2;
                selected_cols = {};
                for (var key in $scope.listData2) {
                    if ($scope.listData2[key] == true) {
                        selected_cols[key] = true;
                    }
                }

                if (Object.keys(selected_cols).length == 0) {
                    alert("Please select at least one columns");
                    return false;
                }

                $scope.listDatalist.listData2 = selected_cols;

                $http({
                    url: "Shipment/getexceldata",
                    method: "POST",
                    data: $scope.listDatalist,
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
                $('#excelcolumn').modal('hide');
            };





            $scope.exportExcel = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    //console.log("ssssssssss");
                    disableScreen(1);
                    $scope.loadershow = true;
                    // //console.log($scope.exportlimit);
                    $http({
                        url: "Shipment/exportPackedExcel",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //console.log(response);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", d + "orders.xls");
                        $a[0].click();
                        $a.remove();
                        disableScreen(0);
                        $scope.loadershow = false;

                    },
                            function (data) {
                                disableScreen(1);
                                $scope.loadershow = true;
                                //console.log(data);
                            });
                } else
                {
                    alert("please select export limit");
                }
            }

        })

        .controller('forward_view', function ($scope, $http, $window, $location) {
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.excelshipData = [];
            $scope.dropexport = [];
            $scope.Items = [];
            $scope.dropshort = {};
            $scope.loadershow = false;
            $scope.filterData.s_type = 'AWB';
            $scope.filterData.status_o = '';
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.listData2 = {
                "entrydate": false,
                "booking_id": false,
                "shippers_ref_no": false,
                "slip_no": false,
                "origin": false,
                "destination": false,
                "sender_name": false,
                "sender_address": false,
                "sender_phone": false,
                "reciever_name": false,
                "reciever_address": false,
                "reciever_phone": false,
                "mode": false,
                "delivered": false,
                "total_cod_amt": false,
                "cust_id": false,
                "pieces": false,
                "weight": false,
                "status_describtion": false,
                "frwd_awb_no": false,
                "transaction_no": false,
                "pay_Invoice_status": false,
                "sub_category": false,
                "onHold_Confirm": false,
                "onHold_Date": false,
                "onHold_Reason": false,
                "shelv_no": false,
                "schedule_date": false,
                "time_slot": false,
                "area_street": false,
                "area": false,
                "dest_lat": false,
                "dest_lng": false,
                "delever_date": false,
                "frwd_throw": false,
                "payable_status": false,
                "receivable_status": false,
                "receivable_invoice_no": false,
                "show_code": false,
                "messenger_name": false,
                "close_date": false,
                "status_o": false,
                "invoice_details": false,
                "pl3_pickup_date": false,
                "pl3_close_date": false,
                "frwd_date": false,
                "transaction_days": false,
                "no_of_attempt": false,
                "cc_name": false,
                "last_status_n": false,
            };

            $scope.showCity = function ()
            {


                $http({
                    url: $scope.baseUrl + "/Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
            $scope.setFocus = function (id, type)
            {
                /// //console.log();
                if (type == 'sh')
                {
                    document.getElementById('st_' + id).value = '';
                    document.getElementById('st_' + id).focus();
                } else
                {
                    if (document.getElementById('sh_' + (id + 1)) != undefined)
                    {
                        document.getElementById('sh_' + (id + 1)).value = '';
                        document.getElementById('sh_' + (id + 1)).focus();
                    }

                }

            }
            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;
                //console.log("?dsfsdf");

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.loadMore = function (page_no, reset)
            {
                //  disableScreen(1);
                //$scope.loadershow=true; 
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Shipment/forward_report_data",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.shipDataexcel = response.data.excelresult;
                    $scope.dropexport = response.data.dropexport;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value['3pl_pickup_date'])
                            value.mydate = new Date(value['3pl_pickup_date']);
                            //if(value['3pl_pickup_date'])
                            $scope.shipData.push(value);

                        });
                        //console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };

            $scope.loadMoreReverse = function (page_no, reset)
            {
                //  disableScreen(1);
                //$scope.loadershow=true; 
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Shipment/filterReverse",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.shipDataexcel = response.data.excelresult;
                    $scope.dropexport = response.data.dropexport;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value['3pl_pickup_date'])
                            value.mydate = new Date(value['3pl_pickup_date']);
                            //if(value['3pl_pickup_date'])
                            $scope.shipData.push(value);

                        });
                        //console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };



            $scope.runshell_tracking = function ()
            {

                $http({
                    url: "Shipment/runshell_tracking",
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    alert("Sync process has been start. Please wait for 30 minute to update data. ");


                })

            }



            $scope.GetProcessOpenOrder = function (slip_no)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "Shipment/GetProcessOpenOrder",
                    method: "POST",
                    data: {slip_no: slip_no},
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {

                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("successfully status changed");
                    $scope.loadMore(1, 1);
                });
            };
            $scope.GetInventoryPopup = function (id) {
                disableScreen(1);
                $scope.loadershow = true;
                //alert(id); 
                //data:$scope.shipData,
                $scope.filterData.id = id;
                $http({
                    url: "Shipment/filterdetail",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {
                    ////console.log(response)

                    $scope.shipData1 = response.data;
                    //console.log($scope.shipData1)
                    $("#deductQuantityModal").modal({
                        backdrop: 'static',
                        keyboard: true
                    })

                    disableScreen(0);
                    $scope.loadershow = false;


                })



            }

            $scope.GetStockLocationPopup = function (id) {
                disableScreen(1);
                $scope.loadershow = true;
                //alert(id); 
                //data:$scope.shipData,
                $scope.itemData1 = [];
                $scope.filterData.id = id;
                $http({
                    url: "Shipment/GetStockLocation",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {
                    //console.log(response)

                    $scope.itemData1 = response.data;
                    //console.log($scope.itemData1)
                    $("#StocklocationModal").modal({
                        backdrop: 'static',
                        keyboard: true
                    })

                    disableScreen(0);
                    $scope.loadershow = false;


                })



            }

            $scope.chnagelocation = function (St_id)
            {
                // $scope.stockdata = undefined;
                //console.log(St_id);
                // //console.log(dop);
                //console.log($scope.itemData1[St_id].stockdata);
                if ($scope.stockdata == undefined)
                {
                    //console.log($scope.itemData1[St_id]);

                }

            }


            $scope.savedetails = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "Shipment/save_details",
                    method: "POST",
                    data: $scope.itemData1,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(function (response) {

                    disableScreen(0);
                    $scope.loadershow = false;
                    //console.log(response);
                    alert("successfully order open");
                    $scope.loadMore(1, 1);
                });

                $("#StocklocationModal").modal('hide');
            };



            $scope.ExportData = {};
            $scope.listData1 = [];

            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };


            $scope.checkall = false;
            $scope.checkAll = function () {
                if ($scope.checkall === false) {
                    angular.forEach($scope.listData1, function (data) {
                        data.checked = true;
                    });
                    $scope.checkall = true;
                } else {
                    angular.forEach($scope.listData1, function (data) {
                        data.checked = false;
                    });
                    $scope.checkall = false;
                }
            };

            $scope.selectedAll = false;
            $scope.selectAll = function (val) {
                //alert(val);


                // //console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // //console.log(key+"======="+newval);
                    if (key <= newval)
                    {

                        ////console.log(key+"======="+newval);
                        ////console.log($scope.selectedAll);
                        data.Selected = true;

                        $scope.Items.push(data.slip_no);

                    } else
                    {
                        ////console.log($scope.selectedAll);
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.slip_no);
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

            $scope.removemultipleorder = function ()
            {
                //console.log($scope.Items);
                if ($scope.Items.length > 0)
                {
                    $http({
                        url: "Shipment/GetremoveMultipleOrders",
                        method: "POST",
                        data: {slipData: $scope.Items, page_check: 'ship_view'},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                    }).then(function (response) {
                        if (response.data == "succ")
                        {
                            alert("successfuly Deleted");
                            $window.location.reload();
                        } else
                        {
                            alert("successfuly Deleted");
                            $window.location.reload();
                        }

                    });
                } else
                {
                    alert("please select atleat one order");
                }

            };


            $scope.transferShip1 = function () {

                //$scope.exportlimit

                $scope.listDatalist.filterData = $scope.filterData;
                //$scope.listDatalist.listData2 = $scope.listData2;
                selected_cols = {};
                for (var key in $scope.listData2) {
                    if ($scope.listData2[key] == true) {
                        selected_cols[key] = true;
                    }
                }

                if (Object.keys(selected_cols).length == 0) {
                    alert("Please select at least one columns");
                    return false;
                }

                $scope.listDatalist.listData2 = selected_cols;

                $http({
                    url: "Shipment/getexceldata",
                    method: "POST",
                    data: $scope.listDatalist,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                    //console.log(results);
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
                $('#excelcolumn').modal('hide');
            };





            $scope.exportExcel = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    //console.log("ssssssssss");
                    disableScreen(1);
                    $scope.loadershow = true;
                    // //console.log($scope.exportlimit);
                    $http({
                        url: "Shipment/exportPackedExcel",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //console.log(response);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", d + "orders.xls");
                        $a[0].click();
                        $a.remove();
                        disableScreen(0);
                        $scope.loadershow = false;

                    },
                            function (data) {
                                disableScreen(1);
                                $scope.loadershow = true;
                                //console.log(data);
                            });
                } else
                {
                    alert("please select export limit");
                }
            }

        })

        .controller('itemInvontary_view', function ($scope, $http) {

            $scope.filterData = {};
            $scope.shipData = [];



            $scope.loadMore = function (page_no, reset)
            {
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: "ItemInventory/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        ////console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };

            $scope.exportExcel = function ()
            {
                //console.log($scope.shipData);
                $http({
                    url: "Shipment/exportExcel",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
        })

        .controller('shipment_mapping', function ($scope, $http, $window) {
            $scope.filterData = {};
            $scope.mappingData = [];
            $scope.responseError = {};
            $scope.Success_msg = {};
            $scope.loadMore = function (page_no, reset)
            {
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.mappingData = [];
                }

                $http({
                    url: "Shipment/filterMapping",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            ////console.log(value.slip_no)

                            $scope.mappingData.push(value);

                        });
                        ////console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };
            $scope.addNewMapping = function ()
            {
                $window.location.href = "add_new_mapping";
            };
            $scope.viewAlMapping = function ()
            {
                $window.location.href = "shipment_mapping";
            };
            $scope.saveMappingData = function () {
                $http({
                    url: "Shipment/saveMapping",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    if (response.data.status == 'succ') {
                        $scope.Success_msg[0] = response.data.Success_msg;
                    } else {
                        $scope.responseError = response.data.responseError;
                        setTimeout(function () {
                            $('.alert-danger').fadeOut();
                        }, 10000);
                    }
                })
            };
        })
        .controller('pickupList', function ($scope, $http, $window, Excel, $timeout) {
            $scope.AssignData = {};
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.pickerArray = [];
            $scope.loadershow = false;
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
            $scope.assignPicker = function (pickup_id)
            {
                //console.log(pickup_id);
                $("#exampleModal").modal()
                $scope.pickId = pickup_id;
            }
            $scope.savePicker = function ()
            {
                $("#exampleModal").modal('hide');

                //$scope.pickerArray;
                $scope.arrayIndex = $scope.pickerArray.findIndex(record => record.id === $scope.AssignData.selectedPicker);

                $scope.AssignData.pickId = $scope.pickId;

                $scope.arrayIndexMain = $scope.shipData.findIndex(record => record.pickupId === $scope.AssignData.pickId);
                $scope.shipData[$scope.arrayIndexMain].assigned_to = $scope.pickerArray[$scope.arrayIndex].username;
                //console.log($scope.shipData[$scope.arrayIndexMain].assigned_to);
                $http({
                    url: "PickUp/assignPicker",
                    method: "POST",
                    data: $scope.AssignData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {


                })


            }

            $scope.run_pickup_cron = function ()
            {

                $http({
                    url: "PickUp/run_pickup_cron",
                    method: "POST",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    alert("Sync process has been start. Please wait for 10 minute to update data. ");


                })

            }


            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "PickUp/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    $scope.pickerArray = response.data.picker;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        ////console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };



            $scope.generatePickup = function ()
            {
                $scope.shipData_new = $scope.shipData.filter(function (item) {
                    return $scope.Items.includes(item.slip_no);
                })
                if ($scope.shipData_new.length == 0)
                {
                    alert('Please select Orders to generate Pickupsheet!');
                } else
                {
                    var isConfirmed = confirm("You are going to generate Pickupsheet, This Action will change the Order status! Are you sure?");

                    if (isConfirmed)
                    {

                        //console.log($scope.shipData_new);
                        //console.log($scope.Items);

                        $http({
                            url: "generatePickup",
                            method: "POST",
                            data: {
                                listData: $scope.shipData_new,
                                slipData: $scope.Items
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {

                            $window.location.reload();

                        })
                    }
                }
            }



            $scope.exportExcel = function ()
            {
                // alert('ss');
                disableScreen(1);
                $scope.loadershow = true;
                if ($scope.filterData.exportlimit > 0)
                {
                    $http({
                        url: "PickUp/exportExcel_picklist",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //console.log(response.data.file);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", d + "Picklist orders.xls");
                        $a[0].click();
                        $a.remove();
                        disableScreen(0);
                        $scope.loadershow = false;

                    }, function (data) {
                        disableScreen(0);
                        $scope.loadershow = false;
                    }
                    );
                } else
                {
                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("Please Select Export Limit");

                }
            }

            $scope.shipData1 = [];
            $scope.exportExcelpickuplist = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                //console.log($scope.exportlimit);

                $http({
                    url: "PickUp/exportExcelpick",
                    method: "POST",
                    data: $scope.exportlimit,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    // //console.log(response);

                    $scope.pickerArray = response.data.picker;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            ////console.log(value)

                            $scope.shipData1.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log($scope.shipData1);
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;


                });
            }

            $scope.exportToExcelpicklistReport = function (testTable_new) {
                //alert("Hi");   
                $timeout(function () {
                    var exportHref = Excel.tableToExcel(downloadtable, 'sheet name');
                    location.href = exportHref;
                }, 50000); // trigger download         
            }
        })

        .controller('pickListView', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []

            //console.log($scope.baseUrl);
            $scope.loadMore = function (page_no, reset)
            {
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "pickListFilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            ////console.log(value)

                            $scope.shipData.push(value);

//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //.//console.log( $scope.shipData[0].skuData[0])
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };






            $scope.exportExcel = function ()
            {
                //console.log($scope.shipData);
                $http({
                    url: "pickUp/pickListViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
        })

        .controller('ExportCtrl', function ($scope, $http, $window, $location) {
            $scope.ExportData = {};
            $scope.listData1 = [];
            $scope.filterData = {};
            $scope.slipnos = null;

            $scope.listData2 = {
                "entrydate": false,
                "booking_id": false,
                "shippers_ref_no": false,
                "slip_no": false,
                "origin": false,
                "destination": false,
                "destination_country":false,
                "sender_name": false,
                "sender_address": false,
                "sender_phone": false,
                "reciever_name": false,
                "reciever_address": false,
                "reciever_phone": false,
                "mode": false,
                "delivered": false,
                "total_cod_amt": false,
                "cust_id": false,
                "pieces": false,
                "weight": false,
                "status_describtion": false,
                "frwd_awb_no": false,
                "transaction_no": false,
                "pay_Invoice_status": false,
                "sub_category": false,
                "onHold_Confirm": false,
                "onHold_Date": false,
                "onHold_Reason": false,
                "shelv_no": false,
                "schedule_date": false,
                "time_slot": false,
                "area_street": false,
                "area": false,
                "dest_lat": false,
                "dest_lng": false,
                "delever_date": false,
                "frwd_throw": false,
                "payable_status": false,
                "receivable_status": false,
                "receivable_invoice_no": false,
                "show_code": false,
                "messenger_name": false,
                "close_date": false,
                "status_o": false,
                "invoice_details": false,
                "pl3_pickup_date": false,
                "pl3_close_date": false,
                "frwd_date": false,
                "transaction_days": false,
                "no_of_attempt": false,
                "cc_name": false,
                "last_status_n": false,
                "laststatus_first": false,
                "laststatus_second": false,
                "laststatus_last": false,
                "fd1_date": false,
                "fd2_date": false,
                "fd3_date": false,
                "audit_status": false,
            };
            $scope.slipnosdetails = function (val)
            {
                $scope.slipnos = val;

            };


            $scope.getExcelDetails = function (exportlimit) {


                $scope.listData1.exportlimit = exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };
            $scope.checkall = false;
            $scope.toggleAll = function () {
                //  alert("ddddd");
                $scope.checkall = !$scope.checkall;

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };
            $scope.transferShiptracking = function () {
                $scope.baseUrl = new $window.URL($location.absUrl()).origin;

                $http({url: SITEAPP_PATH + "/Shipment/getexceldatatracking",
                    method: "POST",
                    data: {slip_nos: $scope.slipnos, listData2: $scope.listData2, },
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response);
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", response.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
                $('#excelcolumn').modal('hide');
            };
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
        })
        .controller('deliveryManifest', function ($scope, $http, $window, Excel, $timeout) {
            $scope.AssignData = {};
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.pickerArray = [];
            $scope.loadershow = false;




            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                //console.log(page_no);
                // //console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.count = 1;
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "Shipment/manifest_filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    $scope.pickerArray = response.data.picker;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        ////console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status)
                {
                    // //console.log(error)
                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };

            $scope.exportExcel = function ()
            {
                // alert('ss');
                disableScreen(1);
                $scope.loadershow = true;
                if ($scope.filterData.exportlimit > 0)
                {
                    $http({
                        url: "PickUp/exportExcel_picklist",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //console.log(response.data.file);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", d + "Picklist orders.xls");
                        $a[0].click();
                        $a.remove();
                        disableScreen(0);
                        $scope.loadershow = false;

                    });
                } else
                {
                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("Please Select Export Limit");

                }
            }

            $scope.shipData1 = [];
            $scope.exportExcelpickuplist = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                //console.log($scope.exportlimit);

                $http({
                    url: "PickUp/exportExcelpick",
                    method: "POST",
                    data: $scope.exportlimit,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    // //console.log(response);

                    $scope.pickerArray = response.data.picker;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            ////console.log(value)

                            $scope.shipData1.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log($scope.shipData1);
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;


                });
            }

            $scope.exportToExcelpicklistReport = function (testTable_new) {
                //alert("Hi");   
                $timeout(function () {
                    var exportHref = Excel.tableToExcel(downloadtable, 'sheet name');
                    location.href = exportHref;
                }, 50000); // trigger download         
            }
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // //console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

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