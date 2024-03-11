var app = angular.module('ReturnLmApp', [])

        .controller('scanShipment', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.specialtype = {};
            $scope.boxArray = {};
            $scope.showboxArray = {};
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.tableshow = false;
            $scope.LocationArr = {};
            $scope.awbcolmunBtn = false;
            $scope.btnfinal = true;
            $scope.btnfinal_location = true;
            $scope.boxshow1 = false;
            $scope.stockError = "valid";
            $scope.UpdateArr = {};
            $scope.remarkBox = false;
            $scope.loadershow = false;
            $scope.validCheck = [];
            $scope.scan_awb = function () {
                $('#scan_awb').focus();
                $scope.packuShip();
            };
            $scope.totalnumber = 0;
            $scope.rechecktotalnumber = 0;
            $scope.packuShip = function () {

                disableScreen(1);
                $scope.loadershow = true;
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    //console.log($scope.scan);
                    $http({
                        url: SITEAPP_PATH + "Shipment_og/CheckReturnFulfil",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        $scope.btnfinal = true;
                        if (response.data.count > 0)
                        {
                            $scope.awbcolmunBtn = true;
                            $scope.tableshow = true;
                            $scope.rechecktotalnumber = response.data.count_sku;

                        }

                        //console.log(response);
                        //$scope.specialtype.specialpack=true;
                        //$scope.specialtype.specialpacktype="warehouse";
                        if (response.data.count == 0)
                        {
                            $scope.scan.slip_no = "";
                            $scope.warning = "Order Not available for RTF!";
                            responsiveVoice.speak($scope.warning);
                        }
                          disableScreen(0);
                            $scope.loadershow = false;
                        angular.forEach(response.data.result, function (value) {
                            console.log(value);

                            $scope.awbArray.push(value);
                            angular.forEach(value.sku, function (value1) {
                                console.log(value1)

                                $scope.shipData.push({'slip_no': value.slip_no, 'item_path': value1.item_path, 'sku_size': value1.sku_size, 'sku': value1.sku, 'item_sku': value1.item_sku, 'cust_id': value1.cust_id, 'piece': value1.piece, 'scaned': 0,'local_type':value1.local_type,'st_location':value1.st_location, 'scaned_m': 0, 'scaned_d': 0, 'stock_location': "", 'in_stock': value1.in_stock,'in_stock_new': value1.in_stock_new, 'missing': 0, 'damage': 0});

                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                            });

                          

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });


                    });
                }

                // $scope.scanCheck();
                // $scope.checkComplte($scope.shipData, $scope.scan.slip_no);

            };




            $scope.completeArray = [];


            $scope.Clountshowlocation = "";
            $scope.LocationError = false;

            $scope.UpdateotherArr = {};




            $scope.fillStockLocations = [];
            $scope.GetcheckStocklocation = function (data, index)
            {

                disableScreen(1);
                $scope.loadershow = true;
                //alert(data.stock_location);
                $scope.scan.fillStockLocations = $scope.fillStockLocations;
                $scope.scan.list = data;
                $http({
                    url: SITEAPP_PATH + "Shipment_og/GetcheckStockLocation_return",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.showerror = response.data.error;
                    if ($scope.showerror == 'valid')
                    {
                         $scope.fillStockLocations.push($scope.shipData[index].stock_location);
                        $scope.totalnumber = parseInt($scope.totalnumber) + 1;
                        $scope.shipData[index].scaned = 1;
                        $scope.shipData[index].scaned_d = 1;
                        $scope.shipData[index].scaned_m = 1;
                       
                        $scope.shipData[index].local_type=response.data.result.loc_new;
                        
                        $scope.shipData[index].in_stock = response.data.result.quantity;
                        $scope.warning = null;
                        $scope.Message = "Stock Location Scan";

                    } else
                    {
                        $scope.shipData[index].stock_location = "";
                        $scope.Message = null;
                        $scope.warning = "Invalid Stock Location";
                    }
                    disableScreen(0);
                    $scope.loadershow = false;

                    if ($scope.rechecktotalnumber == $scope.totalnumber)
                    {
                        $scope.btnfinal = false;
                        $scope.Btnverify = false;
                        $scope.scan.tcount = $scope.totalnumber;
                    }




                });
            };

            $scope.openStockLocation = function (index)
            {
                disableScreen(1);
                $scope.loadershow = true;
                
                 angular.forEach($scope.fillStockLocations, function (value,key) {
                    if(value==$scope.shipData[index].stock_location)
                    {
                   $scope.fillStockLocations.splice(key, 1);  
                  }
                });
               
                $scope.totalnumber = parseInt($scope.totalnumber) - 1;
                $scope.Btnverify = true;
                $scope.btnfinal = true;
                $scope.shipData[index].scaned = 0;
                $scope.shipData[index].in_stock = 0;
                $scope.shipData[index].damage = 0;
                $scope.shipData[index].missing = 0;
                $scope.shipData[index].scaned_d = 1;
                $scope.shipData[index].scaned_m = 1;

                $scope.Message = null;
                $scope.warning = "Stock Location Opened";
                disableScreen(0);
                $scope.loadershow = false;
            };

            $scope.openmissing = function (i, type)
            {
                disableScreen(1);
                $scope.loadershow = true;
                if (type == 'D')
                    $scope.shipData[i].scaned_d = 1;
                else
                    $scope.shipData[i].scaned_m = 1;
                disableScreen(0);
                $scope.loadershow = false;
            };

            $scope.GetCheckmissingpiece = function (data, i, type)
            {
                disableScreen(1);
                $scope.loadershow = true;
                var totaldamge = parseInt($scope.shipData[i].damage) + parseInt($scope.shipData[i].missing);
                var s_piece = $scope.shipData[i].piece;
                if (totaldamge > s_piece)
                {
                    $scope.Message = null;
                    $scope.warning = "Invlid qty For " + type;
                    if (type == 'Damage')
                    {
                        $scope.shipData[i].damage = 0;
                    }
                    if (type == 'Missing')
                    {
                        $scope.shipData[i].missing = 0;
                    }
                } else

                {

                    if (type == 'Damage')
                    {
                        $scope.shipData[i].scaned_d = 0;
                    }
                    if (type == 'Missing')
                    {
                        $scope.shipData[i].scaned_m = 0;
                    }

                    $scope.warning = null;
                    $scope.Message = type + " Update";
                }
                disableScreen(0);
                $scope.loadershow = false;
            };
            $scope.Getchekmess = function ()
            {
                $scope.warning = null;
                $scope.Message = null;
            };

            $scope.finishScan = function ()
            {



                if ($scope.shipData.length > 0)
                {
                    var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                    if (isconfirm)
                    {
                        disableScreen(1);
                        $scope.loadershow = true;
                        $scope.scan.sku_data = $scope.shipData;
                        $http({
                            url: SITEAPP_PATH + "Shipment_og/save_details",
                            method: "POST",
                            data: $scope.scan,

                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                            //  console.log(response);


                            // var d = new Date();
                            // var $a = $("<a>");
                            // $a.attr("href", response.data.file);
                            // $("body").append($a);
                            // $a.attr("download", response.data.file_name);
                            // $a[0].click();
                            //$a.remove();
                            // $scope.awbcolmunBtn = false;
                            // $scope.boxshow1 = false;
                            // // $scope.tableshow = false;

                            $scope.scan = {};
                            $scope.scan.slip_no = "";
                            $scope.shipData = {};

                            $scope.Message = "Completed order RTF!";
                            //alert($scope.Message);

                            location.reload();

                            //console.log(error);
                        });

                    }

                }
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