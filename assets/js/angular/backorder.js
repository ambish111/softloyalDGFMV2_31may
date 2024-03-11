var app = angular.module('BackOrderApp', [])



        .controller('CtrlBackorders', function ($scope, $http, $window, $location, $timeout) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.formArr = {};
            $scope.alretData = [];
            $scope.fullfillmentlistArray = [];
            $scope.productilistArray = [];
            $scope.fullmanilistArray = [];
            $scope.ItemlistArray = [];
            $scope.Allticketlistdata = [];
            $scope.filterData = {};
            $scope.listArraytickets = [];
            $scope.formArr = {};
            $scope.postTimeData = {};
            $scope.ticketpopArr = {};
            //$scope.listData = []; 

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

            $scope.formArr.exportlimit="2000";
            $scope.showCity = function ()
            {


                $http({
                    url:"Country/showCity",
                    method: "POST",
                    data: $scope.formArr,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }
            function disableScreen(val) {
                if (val == 1)
                {
                    var div = document.createElement("div");
                    div.className += "overlay";
                    document.body.appendChild(div);
                } else
                    $("div").removeClass("overlay");
            }

            $scope.isAll = false;
            $scope.selectAllFriends = function () {
                //alert("Hi");
                if ($scope.isAll === false) {
                    angular.forEach($scope.fullfillmentlistArray, function (data) {
                        data.checked = true;

                    });
                    $scope.isAll = true;

                } else {
                    angular.forEach($scope.fullfillmentlistArray, function (data) {
                        data.checked = false;
                    });
                    $scope.isAll = false;
                }
            };


          
            $scope.skuDetailsArr = {};
            $scope.GetskudedetailsPop = function (data)
            {
                $scope.skuDetailsArr = data;
                $("#deductQuantityModal").modal({
                    backdrop: 'static',
                    keyboard: true
                })

            }


            $scope.GetTicketPop = function (ticket_no)
            {
                //alert(ticket_no);
                $scope.postTimeData.ticket_no = ticket_no;
                $http({
                    method: "POST",
                    url: "FullfillmentManagement/GetModal",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: {ticket_no: ticket_no},
                }).then(function (response) {


                    console.log(response);

                    $scope.ticketpopArr = response.data;


                    $("#exampleModal").modal({backdrop: 'static',
                        keyboard: true})
                })





            }
            


            

           
            $scope.showbackorderList = function (page_no, reset, status = 0, pagetype = null)
            {

                if (status != 0 && pagetype == 'F')
                {
                    $scope.formArr.main_status = status;
                } else if (status != 0 && pagetype != 'F')
                {
                    $scope.formArr.main_status = status;
                    $scope.formArr.Checkdate = 1;
                }
                $scope.formArr.page_no = page_no;
                if (reset == 1)
                {
                    $scope.fullfillmentlistArray = [];
                }

                $http({
                    method: "POST",
                    url: "Backorder/showbackorderList",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: $scope.formArr,
                }).then(function (response) {
                    console.log(response);
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    $scope.values = response.data.result;

                    angular.forEach($scope.values, function (value, key) {
                        $scope.fullfillmentlistArray.push(value);
                    });
                })
            }

           

            $scope.exportlimit = {};
            //$scope.filterData=[];
           

            $scope.ShowItemList = function (page_no, reset)
            {

                // console.log($scope.formArr);
                $scope.formArr.page_no = page_no;
                if (reset == 1)
                {
                    $scope.ItemlistArray = [];
                }

                $http({
                    method: "POST",
                    url: "FullfillmentManagement/GetItemList",
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: $scope.formArr,
                }).then(function (response) {
                    console.log(response);
                    $scope.totalCount = response.data.count;
                    $scope.values = response.data.result;

                    angular.forEach($scope.values, function (value, key) {
                        $scope.ItemlistArray.push(value);
                    });
                })
            }


            function Getloginalerttrue1(title, mess, type, icon)
            {
                $.alert({
                    title: title,
                    icon: icon,
                    type: type,
                    content: mess,
                    buttons: {
                        close: function () {
                            $state.reload();
                        },
                    }
                });
            }
            $scope.listData1 = [];

            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.formArr.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;
                if ($scope.checkall == false)
                {
                    $scope.checkall = true;
                } else
                {
                    $scope.checkall = false;
                }

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                    console.log(key + ':' + $scope.listData2[key]);
                }
            };
            $scope.listDatalist = {};



            $scope.transferShip1 = function () {

                //$scope.exportlimit

                $scope.listDatalist.filterData = $scope.formArr;
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
                    url: "FullfillmentManagement/getexceldata",
                    method: "POST",
                    data: $scope.listDatalist,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                    console.log(results);
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
                $('#excelcolumn').modal('hide');
            };



            $scope.Warningshow = {};
            $scope.uploadExcel = function (value) {
                disableScreen(1);
                // console.log($scope.uploadfiles);   
                $scope.loadershow = true;
                var filedata = new FormData();
                angular.forEach($scope.uploadfiles, function (file) {
                    filedata.append('file', file);
                });
                console.log(filedata);
                $http({
                    method: 'post',
                    url: 'FullfillmentManagement/UploadCvExcel',
                    data: filedata,
                    headers: {'Content-Type': undefined},
                }).then(function (response) {
                    console.log(response);
                    disableScreen(0);
                    $scope.loadershow = false;


                    if (response.data != 'null')
                    {
                        $scope.alretData = "";
                        angular.forEach(response.data.invalidrpows, function (value)
                        {
                            $scope.alretData = $scope.alretData + " " + value;

                        });
                        angular.forEach(response.data.validrows, function (value1)
                        {
                            $scope.alretData = $scope.alretData + " " + value1;

                        });
                        Getloginalerttrue1("Alert", $scope.alretData, "orange", "fa fa-warning");

                    } else
                        Getloginalerttrue1("Error", 'please select file', "orange", "fa fa-warning");

                });
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

app.directive('myEnter', function () {
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
        .directive('focusMe', function ($timeout, $parse) {
            return {
                link: function (scope, element, attrs) {
                    var model = $parse(attrs.focusMe);
                    scope.$watch(model, function (value) {
                        console.log('value=', value);
                        if (value === true) {
                            $timeout(function () {
                                element[0].focus();
                            });
                        }
                    });
                    element.bind('blur', function () {
                        console.log('blur')
                        scope.$apply(model.assign(scope, false));
                    })
                }
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
            }]);


app.directive('ngFile', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                element.bind('change', function () {

                    $parse(attrs.ngFile).assign(scope, element[0].files)
                    scope.$apply();
                });
            }
        };
    }])
        .directive('convertToNumber', function () {
            return {
                require: 'ngModel',
                link: function (scope, element, attrs, ngModel) {
                    ngModel.$parsers.push(function (val) {
                        return parseInt(val, 10);
                    });
                    ngModel.$formatters.push(function (val) {
                        return '' + val;
                    });
                }
            };
        })
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