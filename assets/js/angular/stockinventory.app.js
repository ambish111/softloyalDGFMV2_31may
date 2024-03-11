var app = angular.module('Appiteminventory', [])

      
       
        .controller('CtrInventoryhistory', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin + "/fm";
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.loadershow = false;
            $scope.loadMore_activity = function (page_no, reset)
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
                    url: URLBASE + "stockInventory/filter_activity",
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

            $scope.loadMore_history = function (page_no, reset)
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
                    url: URLBASE + "stockInventory/filter_history",
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

            $scope.loadMore_recieve = function (page_no, reset)
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
                    url: URLBASE + "stockInventory/filter_recieve",
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
    
            $scope.ExportExcelitemInventoryRcieve = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    //console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "stockInventory/exportexcelhistoinventory",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Recieve History ' + d + ".xls");

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

            $scope.transferShipInventoryHistory_stock = function () {
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
                    url: URLBASE + "StockInventory/historyViewExport",
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

            $scope.transferShipInventoryHistory_activity = function () {
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
                    url: URLBASE + "StockInventory/activityViewExport",
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


            $scope.loadMore_stock = function (page_no, reset)
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
                    url: "StockInventory/filter",
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
                                        url: URLBASE + "StockInventory/GetDeleteInvenntory",
                                        method: "POST",
                                        data: {'id': id},
                                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                                    }).then(function (response) {
                                        console.log(response)
                                        if (response.data == 'true')
                                        {
                                            $.alert('Successfully Updated');


                                            setTimeout(function () {
                                                $scope.shipData = [];
                                                $scope.loadMore_stock(1, 1);
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
            
            $scope.GetupdatePallet_stock = function (palletno, sid, tid)
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
                        url: "StockInventory/GetUpdatePalletNoData",
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
           
        

            $scope.GetupdateMissingInventory_stock = function ()
            {
                //alert(id);
                $http({
                    url: URLBASE + "StockInventory/GetUpdateMissingOrDamgeQty",
                    method: "POST",
                    data: $scope.updateArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    if (response.data == 'true')
                    {

                        $scope.shipData = {};
                        $scope.updateArray = {};
                        alert("successfully Updated");
                        $scope.loadMore_stock(1, 1);
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

            
            $scope.ItemInventoryExport_stock = function () {

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
                    url: "StockInventory/StockInventoryExport",
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

        })

/*------ /show shipments-----*/