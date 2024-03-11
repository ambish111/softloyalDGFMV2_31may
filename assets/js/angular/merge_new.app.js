var app = angular.module('Merge', [])


        .controller('MergeStock', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;

            $scope.loadershow = false;
            $scope.selectedData = [];
            $scope.UpdatingLocation = [];
            $scope.MainupdatingArr = [];
            $scope.merge_btn = false;
            $scope.merge_btn_disable = false;

            $scope.totalshowMerge = 0;
            angular.element(document).ready(function () {
                $("#select_box").select2();

            });



            $scope.loadMore = function (page_no, reset) {
                disableScreen(1);
                $scope.loadershow = true;

                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1) {
                    $scope.count = 1;
                    $scope.shipData = [];
                    //   alert($scope.filterData.page_no);
                    // $scope.filterData.page_no = 1;
                }
                //  alert($scope.filterData.page_no);


                $http({
                    url: "Mergestock_new/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

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



                },function(error)
                {
                    disableScreen(0);
                    $scope.loadershow = false;
 
                })


            };

            $scope.calculateQty = function (index, data) {
                // console.log($scope.selectedData);

                disableScreen(1);
                $scope.loadershow = true;
                console.log($scope.filterData.fill_total_qty+"////"+$scope.MainupdatingArr[0].counting_stock);
                if ($scope.filterData.fill_total_qty > $scope.MainupdatingArr[0].counting_stock)
                {
                    $("#stockdisable_id_" + index).attr("disabled", true);


                    // / console.log(key);
                    //$scope.shipData[index].stock_location;
                    $scope.MainupdatingArr[0].counting_stock = parseInt($scope.MainupdatingArr[0].counting_stock) + parseInt($scope.shipData[index].quantity);
                    $scope.MainupdatingArr[0].FL_updating_qty = parseInt($scope.MainupdatingArr[0].FL_updating_qty) + parseInt($scope.shipData[index].quantity);

                    $scope.counting_stock = parseInt($scope.MainupdatingArr[0].counting_stock) + parseInt($scope.shipData[index].quantity);
                    $scope.totalshowMerge = parseInt($scope.totalshowMerge) + parseInt($scope.shipData[index].quantity)
                    var ST_qty = $scope.shipData[index].quantity;
                    var newupdableval = $scope.totalshowMerge - $scope.filterData.totalsize;

                    $scope.MainupdatingArr[0].lastrow_id = $scope.shipData[index].id;
                    $scope.MainupdatingArr[0].lastrow_q = newupdableval;

                    $scope.UpdatingLocation.push({'quantity': $scope.shipData[index].quantity, 'stock_location': $scope.shipData[index].stock_location, 't_id': $scope.shipData[index].id, 'counting_stock': $scope.counting_stock, check: newupdableval});
                    console.log($scope.UpdatingLocation);
                    // console.log($scope.MainupdatingArr);
                } else
                {
                    alert("Capacity full");
                }
                $scope.merge_total = parseInt($scope.MainupdatingArr[0].counting_stock) + parseInt($scope.filterData.total_qty);
//                if ($scope.merge_total > $scope.filterData.totalsize && scope.MainupdatingArr[$scope.filterData.stockId].lastrow_id==0)
//                {
//                   var ST_qty=$scope.shipData[index].quantity;
//                   var  newupdableval=$scope.merge_total-$scope.filterData.totalsize;
//                   var second_qty=ST_qty-newupdableval;
//                   $scope.MainupdatingArr[$scope.filterData.stockId].lastrow_id=$scope.shipData[index].id;
//                   $scope.MainupdatingArr[$scope.filterData.stockId].lastrow_q=newupdableval;
//                   
//                   //console.log(newupdableval);
//                    
//                } 
                if ($scope.merge_total >= $scope.filterData.totalsize)
                {

                    $scope.merge_btn = true;
                } else
                {
                    $scope.merge_btn = false;
                }
                disableScreen(0);
                $scope.loadershow = false;

            }


            $scope.stock_location_drop = [];
            $scope.checkStockLocation = function () {
                disableScreen(1);
                $scope.loadershow = true;
                // console.log($scope.filterData);

                $http({
                    url: "Mergestock_new/GetallStockLocation_new",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    //console.log(response);
                    if (response.data == 'false') {
                        alert('invaid Stock loaction');
                        $scope.filterData.newstockLocation = null;
                        $scope.stock_location_drop = [];
                    } else {



                        //if (response.data.length > 1)

                        $scope.filterData.stockId = '0';

                        $scope.filterData.item_sku = $scope.shipData[0].item_sku;
                        $scope.filterData.wh_id = $scope.shipData[0].wh_id;
                        $scope.filterData.shelve_no = $scope.shipData[0].shelve_no;

                        $scope.stock_location_drop = response.data;
                        $scope.filterData.total_qty = $scope.stock_location_drop[$scope.filterData.stockId].quantity;
                        $scope.totalshowMerge = $scope.filterData.total_qty;
                        $scope.filterData.totalsize = $scope.shipData[0].sku_size;
                        $scope.filterData.fill_total_qty = parseInt($scope.filterData.totalsize) - parseInt($scope.filterData.total_qty);
                        $scope.filterData.sel_location = $scope.stock_location_drop[$scope.filterData.stockId].stock_location;
                        $scope.filterData.sel_in_id = $scope.stock_location_drop[$scope.filterData.stockId].id;
                        $scope.MainupdatingArr.push({indexid: $scope.filterData.stockId, In_id: $scope.stock_location_drop[$scope.filterData.stockId].id, counting_stock: 0, lastrow_q: 0, lastrow_id: 0, FL_updating_qty: $scope.filterData.total_qty});
                        if (response.data.length == 1 || response.data.length > 1)
                        {

                            if ($scope.filterData.sel_in_id > 0)
                            {
                                angular.forEach($scope.shipData, function (data, key) {
                                    if (data.id != $scope.filterData.sel_in_id)
                                    {
                                        $("#stockdisable_id_" + key).attr("disabled", false);
                                        // console.warn(data);
                                    } else
                                    {
                                        $("#stockdisable_id_" + key).attr("disabled", true);
                                    }


                                });
                            } else
                            {

                                angular.forEach($scope.shipData, function (data, key) {
                                    if (data.stock_location != $scope.filterData.sel_location)
                                    {
                                        $("#stockdisable_id_" + key).attr("disabled", false);
                                        //console.warn(data);
                                    } else
                                    {
                                        $("#stockdisable_id_" + key).attr("disabled", true);
                                    }


                                });

                            }
                        }


                    }


                });

            };

            $scope.GetChnageLocationData = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;

                $scope.filterData.sel_in_id = $scope.stock_location_drop[$scope.filterData.stockId].id;
                $scope.filterData.sel_location = $scope.stock_location_drop[$scope.filterData.stockId].stock_location;
                $scope.filterData.total_qty = $scope.stock_location_drop[$scope.filterData.stockId].quantity;
                $scope.totalshowMerge = $scope.filterData.total_qty;
                $scope.filterData.totalsize = $scope.shipData[0].sku_size;
                $scope.filterData.fill_total_qty = parseInt($scope.filterData.totalsize) - parseInt($scope.filterData.total_qty);
                $scope.MainupdatingArr = [];
                $scope.MainupdatingArr.push({indexid: $scope.filterData.stockId, In_id: $scope.stock_location_drop[$scope.filterData.stockId].id, counting_stock: 0, lastrow_q: 0, lastrow_id: 0, FL_updating_qty: $scope.filterData.total_qty});

                if ($scope.filterData.sel_in_id > 0)
                {
                    angular.forEach($scope.shipData, function (data, key) {
                        if (data.id != $scope.filterData.sel_in_id)
                        {
                            $("#stockdisable_id_" + key).attr("disabled", false);
                            // console.warn(data);
                        } else
                        {
                            $("#stockdisable_id_" + key).attr("disabled", true);
                        }


                    });
                } else
                {
                    angular.forEach($scope.shipData, function (data, key) {
                        if (data.stock_location != $scope.filterData.sel_location)
                        {
                            $("#stockdisable_id_" + key).attr("disabled", false);
                            //console.warn(data);
                        } else
                        {
                            $("#stockdisable_id_" + key).attr("disabled", true);
                        }


                    });
                }
                disableScreen(0);
                $scope.loadershow = false;

            };

            $scope.GetUpdateMergeData = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;

                $http({
                    url: "Mergestock_new/GetMergeReadyStock",
                    method: "POST",
                    data: {filter: $scope.filterData, main: $scope.MainupdatingArr, stocklocation: $scope.UpdatingLocation},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    $("#select_box").select2();
                    $scope.filterData.seller = "";
                    $scope.filterData = {};
                    $scope.filterData.seller = "";
                    $scope.shipData = {};
                    $scope.MainupdatingArr = {};
                    $scope.UpdatingLocation = {};
                    $scope.totalshowMerge = 0;
                    $scope.filterData.newstockLocation = null;
                    $scope.stock_location_drop = [];
                    //$("#stockdisable_id_" + key).attr("disabled", true);
                    $scope.merge_btn = false;
                    alert("Successfully Updated!");
                    $window.location.reload();
                });


            };


            $scope.Getresetproces = function ()
            {
                $("#select_box").select2();
                $scope.filterData.seller = "";
                $scope.filterData = {};
                $scope.filterData.seller = "";
                $scope.shipData = {};
                $scope.MainupdatingArr = {};
                $scope.UpdatingLocation = {};
                $scope.totalshowMerge = 0;
                $scope.filterData.newstockLocation = null;
                $scope.stock_location_drop = [];
                //$("#stockdisable_id_" + key).attr("disabled", true);
                $scope.merge_btn = false;

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