var app = angular.module('Appiteminventory', [])

        .controller('Ctrstocktranfer', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.SellerArr = {};
            $scope.SellerArrTO = {};
            $scope.SkuArr = {};
            $scope.StockLOcation = {};
            $scope.ToskuArr = {};
            $scope.fromSkuDetails = {};
            $scope.CountLocation = "";
            $scope.showform = [];
            angular.element(document).ready(function () {

                $("#expity_date1").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    minDate: 0
                });


            });

            $scope.GetSellerDropDataPage = function ()
            {


                $http({
                    url: "ItemInventory/GetsellerDropDataFrom",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    $scope.SellerArr = response.data;
                });
            }
            $scope.showqtyerror = "";
            $scope.showqtyerror2 = "";
            $scope.GettosellerStockLOcationhow = function ()
            {
                $http({
                    url: "ItemInventory/GetshowStockLocationToseller",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                }).then(function (response) {
                    console.log(response);
                    if (response.data.error == '00')
                    {
                        $scope.StockLOcation = response.data.stocklocation;
                        $scope.showform.qtycout = response.data.CountLocation;
                        $scope.CountLocationerr = "Please Select " + response.data.CountLocation + " Location";
                    }
                    if (response.data.error == '302')
                    {
                        $scope.showqtyerror = "Please enter valid quantity";
                        $scope.filterData.qty = "";
                    } else
                        $scope.showqtyerror = "";
                    if (response.data.error == '301')
                    {
                        $scope.showqtyerror2 = "all field are required";
                    } else
                        $scope.showqtyerror2 = "";
                    //alert($scope.CountLocationerr);
                    //$scope.ToskuArr=response.data.skuqty; 

                });

            }
            $scope.GetSellerStockLocation = function (toid)
            {



                $http({
                    url: "ItemInventory/GetSellerStockLocationDrop",
                    method: "POST",
                    data: {toid: toid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    //$scope.StockLOcation=response.data.stocklocation;
                    $scope.ToskuArr = response.data.skuqty;

                });
            }
            $scope.GetUpdateStockQty = function ()
            {
                $http({
                    url: "ItemInventory/GetStockReadyToTransfer",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    if (response.data == "true")
                    {
                        alert("successfully Transfered");
                        $window.location.href = "skuTransferedList";

                    } else
                    {
                        alert(response.data);
                    }
                });
            }

            $scope.GetSellerDropDataPageTo = function (toid)
            {


                $http({
                    url: "ItemInventory/GetsellerDropDataTo",
                    method: "POST",
                    data: {toid: toid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    $scope.SellerArrTO = response.data.tosellers;
                    $scope.SkuArr = response.data.skuqty;
                    $scope.fromSkuDetails = response.data.locationArr;

                });
            }


        })

        .controller('CtrStockTranferdlistpage', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];

            $scope.loadMore = function (page_no, reset)
            {
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: SITEAPP_PATH + "ItemInventory/filter_history_transfered",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };

            $scope.ExportExcelitemInventory = function ()
            {

                //console.log($scope.shipData);
                $http({
                    url: SITEAPP_PATH + "ItemInventory/showiteminventoryexport_history",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //	  console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", 'Inventory History ' + d + ".xls");

                    $a[0].click();
                    $a.remove();


                });


            }
        })

        .controller('InventoryRecord', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.listData1 = [];

            $scope.listData2 = {
                "seller_name": false,
                "sku": false,
                "itype": false,
                "entrydate": false,
                "qty_count": false,
                "no_of_pallets": false,
                "size": false
            };


            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.getExcelDetails = function () {
                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.InboundRecordExport = function () {

                $scope.listDatalist = {};
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
                console.log($scope.listDatalist);
                $http({
                    url: "ExcelExport/InboundRecordExport",
                    method: "POST",
                    data: $scope.listDatalist,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                    console.log(results);
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

            $scope.loadMore = function (page_no, reset)
            {
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: "ItemInventory/filterRecord",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                })

            };
            $scope.enableEdit = function (id)
            {
                $scope.shipData[id].enable = true;
            }

            $scope.cancleChange = function (id)
            {
                $scope.shipData[id].new_qty = $scope.shipData[id].qty_count;
                $scope.shipData[id].enable = false;
            }

            $scope.saveUpdate = function (id)
            {
                console.log($scope.shipData);
                if (parseInt($scope.shipData[id].qty_count) > parseInt($scope.shipData[id].new_qty))
                {
                    console.log($scope.shipData[id]);
                    $scope.shipData[id].size;
                    $scope.shipData[id].diff = parseInt($scope.shipData[id].qty_count) - parseInt($scope.shipData[id].new_qty);
                    $scope.shipData[id].qty_count = parseInt($scope.shipData[id].new_qty);
                    $scope.newpallet = Math.ceil(parseInt($scope.shipData[id].qty_count) / parseInt($scope.shipData[id].size));

                    $scope.rateInbound = parseFloat($scope.shipData[id].inbound_charge) / parseInt($scope.shipData[id].no_of_pallets);
                    $scope.rateInvontary = parseFloat($scope.shipData[id].inventory_charge) / parseInt($scope.shipData[id].no_of_pallets);


                    $scope.shipData[id].no_of_pallets = $scope.newpallet;
                    $scope.shipData[id].inventory_charge = ($scope.rateInvontary * $scope.newpallet);
                    $scope.shipData[id].inbound_charge = ($scope.rateInbound * $scope.newpallet);
                    $http({
                        url: "ItemInventory/updateQty",
                        method: "POST",
                        data: $scope.shipData[id],
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response)
                    })
                } else
                {
                    $scope.shipData[id].new_qty = $scope.shipData[id].qty_count;
                    alert("new quentity you added is greater then Old quentity!");
                }

                $scope.shipData[id].enable = false;
            }


        })

        .controller('Ctrtopdispatchpro', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.filterData.pagetype = 'toppro';
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.loadMore = function (page_no, reset)
            {
                console.log($scope.filterData);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }



                $http({
                    url: "ItemInventory/Gettop10productshow",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.ExportExcelitemInventory = function ()
            {

                //console.log($scope.shipData);
                $http({
                    url: "ItemInventory/showiteminventoryexport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //	  console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                    $a[0].click();
                    $a.remove();


                });


            }
        })
        .controller('CtrInventoryhistory', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + "/fm";
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.loadershow = false;
            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: URLBASE + "ItemInventory/filter_history",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;


                })


            };



            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    //console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelhistoinventory",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Inventory History ' + d + ".xls");

                        $a[0].click();
                        $a.remove();

                        disableScreen(0);
                        $scope.loadershow = false;
                    });
                } else
                    alert("please select export limit");


            }


            $scope.ExportData = {};
            $scope.listData1 = [];
            $scope.listData2 = {};
            $scope.listDatalist = {};
            $scope.getExcelDetails1 = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#InventoryHistoryexcelcolumn").modal({backdrop: 'static',
                    keyboard: false});
                $('.md-check').prop('checked', false);    
                //     $("#InventoryHistoryexcelcolumn").val("")
                // $(".md-check i").reset();
            };

            $scope.checkall1 = function () {
//alert("Hi");
                $scope.checkall = true;
            }

            $scope.uncheckAll = function () {
                $scope.checkall = false;
            }


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

            $scope.listData2 = {
                "sku": false,
                "p_qty": false,
                "qty": false,
                "qty_used": false,
                "seller_name": false,
                "username": false,
                "entrydate": false,
                "type": false,
                "st_location": false,
                "awb_no": false
            }

            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.transferShipInventoryHistory = function () {
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
                $scope.listDatalist.listData2 = $scope.listData2;

                console.log($scope.listDatalist);
                $http({
                    url: URLBASE + "ExcelExport/historyViewExport",
                    //url: "ItemInventory/getexceldataInventoryHistory",
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
                $('#InventoryHistoryexcelcolumn').modal('hide');
            };


        })
        .controller('CtritemInvontaryview', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items=[];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;
            $scope.PalletArray = {};
            $scope.PupdateArray = {};
            $updateArray = {};
            $scope.loadershow = false;
            
            
             $scope.assigndata = {};
            $scope.returnUpdate = {};
            $scope.courierData = {};

           
            
             $scope.returnUpdate.assign_type = "D";
            $scope.driverbtn = true;
            $scope.crourierbtn = false;
            
            $scope.returnUpdate.pack_type="B";

            $scope.listData2 = {
                "name": false,
                "sku": false,
                "item_type": false,
                "storage_id": false,
                "stock_location": false,
                "shelve_no": false,
                "wh_name": false,
                "quantity": false,
                "seller_name": false,
                "item_description": false,
                "update_date": false,
                "expiry": false,
                "expity_date": false

            };

            $scope.checkall = false;
            $scope.toggleAll = function () {

                $scope.checkall = !$scope.checkall;
                console.log($scope.listData2);
                for (var key in $scope.listData2) {

                    $scope.listData2[key] = $scope.checkall;
                }
            };
            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                 //   alert($scope.filterData.page_no);
                    // $scope.filterData.page_no = 1;
                }
               //  alert($scope.filterData.page_no);

                $http({
                    url: "ItemInventory/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };
            
            
            $scope.loadMore_damage = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: "ItemInventory/filter_damage",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    $scope.courierData = response.data.courierData;
                    $scope.assigndata = response.data.assignuser;
                    $scope.sellerID = response.data.seller;
                    
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };
            
            
    $scope.selectedAll = false;
            $scope.selectAll = function (val) {
                //alert(val);
$scope.Items=[];

                // console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // console.log(key+"======="+newval);
                    if (key <= newval)
                    {

                        //console.log(key+"======="+newval);
                        //console.log($scope.selectedAll);
                        data.Selected = true;

                        $scope.Items.push(data.id);

                    } else
                    {
                        //console.log($scope.selectedAll);
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.id);
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
            
            
           
            
            $scope.GetCheckReturnOrderProcesspop = function ()
            {

               
                $http({
                    url: $scope.baseUrl + "/Manifest/GetreturnCourierDropShow",
                    method: "POST",
                    data: $scope.Items,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $("#PopidreturnShowitem").modal({backdrop: 'static',
                        keyboard: false});
                    $scope.assigndata = response.data.assignuser;
                    $scope.courierData = response.data.courierData;
                });
            }
            
            
              $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};
            $scope.responseError = {};
            $scope.saveassigntodriver = function ()
            {
                if ($scope.returnUpdate.assign_type == 'D')
                {
                    $scope.returnUpdate.cc_id = "";
                }
                if ($scope.returnUpdate.assign_type == 'CC')
                {
                    $scope.returnUpdate.assignid = "";
                }
                if ($scope.returnUpdate.assignid || $scope.returnUpdate.cc_id || $scope.returnUpdate.seller)
                {
                    $scope.loadershow = true;
                    disableScreen(1);


                    $scope.returnUpdate.order_type ='return_o';
                    $scope.returnUpdate.sellerid =$scope.sellerID;

                    $http({
                        url: "Manifest/getupdateassign_return",
                        method: "POST",
                        data: {singleArr:$scope.returnUpdate,itemdata:$scope.Items},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //alert(response);
                        console.log(response);
                        // $scope.message=response.data;
                        // $scope.AssignData.assignid="";
                        // $scope.response="";
                        if (response)
                        {
                            if (response.data.status == 'succ')
                            {
                                //alert("Data updated successfully.");
                                $scope.Success_msg = response.data.Success_msg;
                                location.reload();
                            } else
                            {

                                $scope.invalidSslip_no = response.data.invalid_slipNO;
                                $scope.Success_msg = response.data.Success_msg;
                                $scope.Error_msg = response.data.Error_msg;
                                $scope.responseError = response.data.responseError;
                                    setTimeout(function() {
                                     $('.alert-danger').fadeOut();
                                    }, 10000 );
                            }
                        } else
                        {
                            alert("try again");
                        }
                    });
                    disableScreen(0);
                    $scope.loadershow = false;
                } else
                {
                    if ($scope.returnUpdate.assign_type == 'D'){
                        alert("Please Select Driver");
                    }else{
                        alert("Please Select Courier Company");
                }
            }
            }
             $scope.GetChangeAssignType = function (type)
            {
                if (type == 'D')
                {
                    $scope.driverbtn = true;
                    $scope.crourierbtn = false;
                    $scope.returnUpdate.assign_type = "D";
                } else
                {
                    $scope.driverbtn = false;
                    $scope.crourierbtn = true;
                    $scope.returnUpdate.assign_type = "CC";
                }
                // alert();

            };
            $scope.GetupdatePallet = function (palletno, sid, tid)
            {



                $scope.inputbutton = true;
                if (!palletno)
                {
                    alert("please Enter Shelve No.");
                } else
                {
                    $scope.PupdateArray.palletno = palletno;
                    $scope.PupdateArray.sid = sid;
                    $scope.PupdateArray.tid = tid;
                    $http({
                        url: "ItemInventory/GetUpdatePalletNoData",
                        method: "POST",
                        data: $scope.PupdateArray,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response);
                        if (response.data == "true")
                        {
                            alert("successfully Updated");
                            location.reload();
                        } else if (response.data == 301)
                        {
                            alert("this Shelve no used another seller.please enter other Shelve no.");
                        } else
                        {
                            alert("please enter valid Shelve no");
                        }


                    });
                }

            };


            $scope.GetalluserLocationUpdate = function (sid, id)
            {
                $scope.UpdateData.sid = sid;
                $scope.UpdateData.id = id;
                $http({
                    url: "ItemInventory/GetstocklocationDataDrop",
                    method: "POST",
                    data: $scope.UpdateData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    $scope.locationData = response.data;
                    $("#exampleModal").modal({backdrop: 'static',
                        keyboard: false})
                });

            }

            $scope.GetUpdateDamageMissing = function (data)
            {
                //alert(data);
                $scope.updateArray = data;
                //$scope.updateArray.updateType="error";
                console.log($scope.updateArray);
                $("#UpdateInventory").modal({backdrop: 'static',
                    keyboard: false})
            }
            $scope.updaateNewExp={};
            
             $scope.GetUpdateExpirePopShow = function (data)
            {
               
                $scope.updaateNewExp.id = data;
                
               
                $("#UpdateInventory_expire").modal({backdrop: 'static',
                    keyboard: false})
            }
            $scope.GetinventorydeleteUpdate = function (id)
            {
                console.log($scope.filterData);
                //	alert(id);
                angular.element(document).ready(function () {
                    $.alert({
                        title: 'Delete',
                        icon: 'fa fa-warning',
                        type: 'orange',
                        content: 'Do you want to delete?',
                        buttons: {
                            heyThere: {
                                text: 'Yes', // With spaces and symbols
                                action: function () {
                                    $http({
                                        url: URLBASE + "ItemInventory/GetDeleteInvenntory",
                                        method: "POST",
                                        data: {'id': id},
                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                                    }).then(function (response) {
                                        if (response.data == 1)
                                        {
                                            $.alert('Successfully Deleted');


                                            setTimeout(function () {
                                                $scope.shipData = [];
                                                $scope.loadMore(1, 0);
                                            }, 4);



                                        } else
                                            $.alert('Try Again');

                                    });
                                }
                            },
                            close: function () {
                                $.alert('action is canceled');
                            },

                        }
                    });
                });
            }
            $scope.GetupdateMissingInventory = function ()
            {
                //alert(id);
                $http({
                    url: URLBASE + "ItemInventory/GetUpdateMissingOrDamgeQty",
                    method: "POST",
                    data: $scope.updateArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    if (response.data == 'true')
                    {

                        $scope.shipData = {};
                        $scope.updateArray = {};
                        alert("successfully Updated");
                        $scope.loadMore(1, 1);
                        $("#UpdateInventory").modal('hide');
                    } else
                    {
                        alert("all field are required");
                    }
                });
            }

            $scope.GetuserUpdateQtyData = function (upArray)
            {

                $scope.QtyUpArray = upArray;
                console.log($scope.QtyUpArray);
                //$scope.QtyUpArray.qty=qty;
                //$scope.QtyUpArray.date=date;
                //$scope.QtyUpArray.tid=tid;


                $("#exampleModal2").modal({backdrop: 'static',
                    keyboard: false});
            }


            $scope.GetUpdateqtydata = function ()
            {

                // alert($scope.QtyUpArray.newqty);	
                if ($scope.QtyUpArray.newqty == undefined || $scope.QtyUpArray.newqty == '')
                {
                    alert("Please Enter Update QTY");
                } else
                {
                    if ($scope.QtyUpArray.newqty > 0)
                    {

                        $http({
                            url: URLBASE + "ItemInventory/GetUpdateQtyInventry",
                            method: "POST",
                            data: $scope.QtyUpArray,
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                            console.log(response);
                            //alert("successfully Updated.");

                            //location.reload();

                        });
                    } else
                    {
                        alert("Please Enter Valid  Update QTY");
                    }
                }
            }
            $scope.GetupdatelocationData = function ()
            {
                console.log($scope.UpdateData);

                if ($scope.UpdateData.locationUp == 'error')
                {
                    alert("Please Select Location");
                } else
                {
                    $http({
                        url: "ItemInventory/GetupdateStockLocationData",
                        method: "POST",
                        data: $scope.UpdateData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        alert("successfully Updated.");

                        location.reload();

                    });
                }
            }


            $scope.ExportData = {};
            $scope.listData1 = [];

            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false});
                $('.md-check').prop('checked', false); 
            };

            $scope.checkall1 = function () {
//alert("Hi");
                $scope.checkall = true;
            }

            $scope.uncheckAll = function () {
                $scope.checkall = false;
            }


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



            $scope.ItemInventoryExport = function () {

                //$scope.exportlimit
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

                console.log($scope.listDatalist);
                $http({
                    //  url: "ItemInventory/getexceldata",
                    url: "ExcelExport/ItemInventoryExport",
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
            };



            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelinventory",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                        $a[0].click();
                        $a.remove();


                    });
                } else
                {
                    alert("please select export limit");
                }
                disableScreen(0);
                $scope.loadershow = false;


            }
            
            $scope.GetCheckReturnOrderProcesspop = function (){
                $("#PopidreturnShowitem").modal()
            }
        })

        // New report Start

        .controller('CtrShelveNoReport_new', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;
            $scope.PalletArray = {};
            $scope.PupdateArray = {};
            $updateArray = {};
              $scope.updateArray_exp = {};
            $scope.loadershow = false;
            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: "ItemInventory/filter_shelve_new",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };






            $scope.showPopData = {};
            $scope.detailsListArr = {};

            $scope.GetShowShelveDetails = function (seller_id, shelve_no)
            {
                $scope.showPopData.shelve_no = shelve_no;
                $scope.showPopData.seller_id = seller_id;

                $http({
                    url: "ItemInventory/filter_shelve_details",
                    method: "POST",
                    data: $scope.showPopData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.detailsListArr = response.data;

                    $("#exampleModal2").modal({backdrop: 'static', keyboard: false});
                });
            }





            $scope.ExportData = {};
            $scope.listData1 = [];
            $scope.listData2 = {};
            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.checkall1 = function () {
//alert("Hi");
                $scope.checkall = true;
            }

            $scope.uncheckAll = function () {
                $scope.checkall = false;
            }


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



            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelinventory",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                        $a[0].click();
                        $a.remove();


                    });
                } else
                {
                    alert("please select export limit");
                }
                disableScreen(0);
                $scope.loadershow = false;


            }
        })

        // new Report end 

        .controller('CtrShelveNoReport', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;
            $scope.PalletArray = {};
            $scope.PupdateArray = {};
            $updateArray = {};
              $scope.updateArray_exp = {};
            $scope.loadershow = false;
            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url:  URLBASE +"ItemInventory/filter_shelve",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };






            $scope.showPopData = {};
            $scope.detailsListArr = {};

            $scope.GetShowShelveDetails = function (seller_id, shelve_no)
            {
                $scope.showPopData.shelve_no = shelve_no;
                $scope.showPopData.seller_id = seller_id;

                $http({
                    url:  URLBASE +"ItemInventory/filter_shelve_details",
                    method: "POST",
                    data: $scope.showPopData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.detailsListArr = response.data;

                    $("#exampleModal2").modal({backdrop: 'static', keyboard: false});
                });
            }





            $scope.ExportData = {};
            $scope.listData1 = [];
            $scope.listData2 = {};
            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.checkall1 = function () {
//alert("Hi");
                $scope.checkall = true;
            }

            $scope.uncheckAll = function () {
                $scope.checkall = false;
            }


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



            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelinventory",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                        $a[0].click();
                        $a.remove();


                    });
                } else
                {
                    alert("please select export limit");
                }
                disableScreen(0);
                $scope.loadershow = false;


            }
        })

        .controller('CtritemInvontaryview_total', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;
            $scope.PalletArray = {};
            $scope.PupdateArray = {};
            $updateArray = {};
            $scope.listData1 = {};
            $scope.listDatalist = {};
            $scope.loadershow = false;

            $scope.listData2 = {
                "seller_name": false,
                "sku": false,
                "itype": false,
                "entrydate": false,
                "qty_count": false,
                "no_of_pallets": false,
                "size": false,
                "name": false,
                "item_type": false,
                "storage_id": false,
                "stock_location": false,
                "shelve_no": false,
                "wh_name": false,
                "quantity": false,
                "seller_name": false,
                "item_description": false,
                "update_date": false,
                "expiry": false,
                "expity_date": false

            };

            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.getExcelDetails = function () {
                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.ViewTotalInventoryExport = function () {
                console.log("dsfdsf");
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

                $http({
                    //  url: "ItemInventory/getexceldata",
                    url: URLBASE + "ExcelExport/ViewTotalInventoryExport",
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
            };

            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: URLBASE + "ItemInventory/filter_totalview",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };






            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelinventory_totalView",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Items Inventory Total ' + d + "orders.xls");

                        $a[0].click();
                        $a.remove();


                    });
                } else
                {
                    alert("please select export limit");
                }
                disableScreen(0);
                $scope.loadershow = false;


            }
        })
        .controller('Ctrstock_view', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;
            $scope.PalletArray = {};
            $scope.PupdateArray = {};
            $updateArray = {};
            $scope.listData1 = {};
            $scope.listDatalist = {};
            $scope.loadershow = false;

          
            

            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                //alert($scope.filterData.seller);
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: URLBASE + "ItemInventory/filter_viewStock",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };







            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelinventory_viewStock",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Items Inventory Total ' + d + "orders.xls");

                        $a[0].click();
                        $a.remove();


                    });
                } else
                {
                    alert("please select export limit");
                }
                disableScreen(0);
                $scope.loadershow = false;


            }
        })

        .controller('IteminventoryAdd', function ($scope, $http, $window, $location) {

            $scope.baseUrl = new $window.URL($location.absUrl()).origin;


            $scope.filterData = {};
            $scope.shipData = [];
            $scope.CountLocation = "";
            $scope.formDatashow = {};
            $scope.filterData.sku = "";
            $scope.filterData.quantity = "";
            $scope.filterData.stock_location = "";
            $scope.filterData.seller_id = "";
            $scope.typeInput1 = false;
            $scope.typeInput2 = false;
            $scope.GetLocations=0;
            $scope.pending_location=0;
            $scope.CountLocation2="";
            
            $scope.buttonhide=false;
            
            $scope.totalLOcationData=0;

            $scope.showform = [];

            $scope.loadMore = function () {
                $http({
                    url: URLBASE + "ItemInventory/GetallStockLocation_new",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                  //  console.log(response);
                    $scope.shipData = response.data.result;
                   
                    $scope.showform.qtycout = response.data.CountLocation;
                     $scope.totalLOcationData=response.data.CountLocation;;
                    
                    if(response.data.CountLocation_pending==0)
                    {
                        $scope.CountLocation2="";
                        $scope.buttonhide=false;
                         $scope.CountLocation = "Please Select " + response.data.CountLocation + " Location";
                    }
                    else
                    {
                        $scope.CountLocation="";
                        $scope.buttonhide=false;
                        $scope.CountLocation2 = "Please add " + response.data.CountLocation_pending + " more Location"; 
                    }
                    
                   
                   






                });
            };
            
             $scope.Getitemlist = function ()
            {

                $http({
                    url: URLBASE + "ItemInventory/GetSearchItemlist",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    angular.element(document).ready(function () {
                        var availableTags = response.data;
                        $("#tags").autocomplete({
                            source: availableTags
                        });
                    });
                });
            };
            
            $scope.GetcheckButton=function()
            {
               if($scope.filterData.stock_location.length==$scope.totalLOcationData)
               {
                 $scope.buttonhide=true;  
                  $scope.CountLocation ="";
               }
               else
               {
                    $scope.CountLocation ="";
                   $scope.buttonhide=false;
               }
            }
            $scope.GetaddconfirmDatashow = function ($event) {
                console.log($scope.filterData);
                if ($scope.filterData.search_sku != "" && $scope.filterData.quantity != '' && $scope.filterData.stock_location != '' && $scope.filterData.seller_id != '')
                {

                    $scope.getinputfieldName();

                    $("#showskuformviewid2").modal({backdrop: 'static', keyboard: false})
                }
            }
            $scope.getinputfieldName = function () {
                //console.log($scope.filterData);
                $http({
                    url: URLBASE + "ItemInventory/GetFieldnameinventory",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.filterData.seller_name = response.data.sellerName;
                    $scope.filterData.skuname = response.data.skuname;
                    console.log(response);




                });
            }
            $scope.Getallqtycount = function ($event) {
                $http({
                    url: URLBASE + "ItemInventory/",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);




                });
            }
        })

        .controller('Ctrlessqtyalertview', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.filterData.pagetype = 'lessqty';
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.loadMore = function (page_no, reset)
            {
                console.log($scope.filterData);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }



                $http({
                    url: "ItemInventory/GetlessqtyData",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.ExportExcelitemInventory = function ()
            {

                //console.log($scope.shipData);
                $http({
                    url: "ItemInventory/showiteminventoryexport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //	  console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                    $a[0].click();
                    $a.remove();


                });


            }
        })

        .controller('CtrStockexpireAlert', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.filterData.pagetype = 'lessqty';
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.loadMore = function (page_no, reset)
            {
                console.log($scope.filterData);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }



                $http({
                    url: "ItemInventory/GetexpirealertData",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.ExportExcelitemInventory = function ()
            {

                //console.log($scope.shipData);
                $http({
                    url: "ItemInventory/showiteminventoryexport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //	  console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                    $a[0].click();
                    $a.remove();


                });


            }
        })
        .controller('CTR_itemviewpage', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';

            $scope.filterData = {};
            $scope.shipData = [];
            $scope.postArray = {};
            $scope.sellers = {};
            $scope.showbarcode = {};
            $scope.message = "";
            $scope.tableshow = false;

            $scope.showform = [];


            $scope.loadMore = function (page_no, reset)
            {
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Item/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.showdropsellerArr = {};

            $scope.getallskubarcodeform = function (sku) {
                $scope.showbarcode={};
                
                $("#showskuformviewid").modal({backdrop: 'static',
                    keyboard: false})
                $scope.postArray.sku = sku;
                $http({
                    url: URLBASE + "Item/Getallsellersdata",
                    method: "POST",
                    data: $scope.postArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //alert("sssssss");
                    //console.log(response);
                    $scope.filterData.sku = sku;
                    $scope.sellers = response.data;
                    $scope.showdropsellerArr = response.data;





                });
            }
            $scope.GetGenratebarcode = function () {
                console.log($scope.filterData);
                if ($scope.filterData.seller_id && $scope.filterData.sqty)
                {
                    $scope.message = "";
                    $http({
                        url: URLBASE + "Item/GetgenrateSkubarcodes",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response);
                        $scope.tableshow = true;
                        $scope.showbarcode = response.data;
                        $scope.filterData.seller_id = "";
                        $scope.filterData.sqty = "";




                    });
                    // alert("sssss");
                } else
                {
                    $scope.message = "all field are required";
                }
            }

            $scope.ExportExcelitemview = function ()
            {

                //console.log($scope.shipData);
                $http({
                    url: URLBASE + "Item/showiteminventoryexport",
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    // console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                    $a[0].click();
                    $a.remove();


                });


            }

        })
        
        
        .controller('CTR_vehicleList', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + '/fm';

            $scope.filterData = {};
            $scope.shipData = [];
            $scope.postArray = {};
            $scope.sellers = {};
            $scope.showbarcode = {};
            $scope.message = "";
            $scope.tableshow = false;

            $scope.showform = [];


            $scope.loadMore = function (page_no, reset)
            {
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Vehicle/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.showdropsellerArr = {};

            $scope.getallskubarcodeform = function (sku) {
                $("#showskuformviewid").modal({backdrop: 'static',
                    keyboard: false})
                $scope.postArray.sku = sku;
                $http({
                    url: URLBASE + "Item/Getallsellersdata",
                    method: "POST",
                    data: $scope.postArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //alert("sssssss");
                    //console.log(response);
                    $scope.filterData.sku = sku;
                    $scope.sellers = response.data;
                    $scope.showdropsellerArr = response.data;





                });
            }
            $scope.GetGenratebarcode = function () {
                console.log($scope.filterData);
                if ($scope.filterData.seller_id && $scope.filterData.sqty)
                {
                    $scope.message = "";
                    $http({
                        url: URLBASE + "Item/GetgenrateSkubarcodes",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response);
                        $scope.tableshow = true;
                        $scope.showbarcode = response.data;
                        $scope.filterData.seller_id = "";
                        $scope.filterData.sqty = "";




                    });
                    // alert("sssss");
                } else
                {
                    $scope.message = "all field are required";
                }
            }

            $scope.ExportExcelitemview = function ()
            {

                //console.log($scope.shipData);
                $http({
                    url: URLBASE + "Item/showiteminventoryexport",
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    // console.log(response.data);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                    $a[0].click();
                    $a.remove();


                });


            }

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
/*------ /show shipments-----*/