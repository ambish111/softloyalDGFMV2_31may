var app = angular.module('fulfillPackingEAN', ['betsol.timeCounter'])

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
            $scope.scan_awb_frwd = function () {
                $('#scan_awb').focus();
                $scope.packuShipfwd();
            }
            $scope.setFocus = function (id, type)
            {
                /// console.log();
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

            };

            $scope.packuShipfwd = function () {
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                // $scope.scan.frwd_no = $scope.scan.frwd_no;
                
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.frwd_company_awb.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                
                if ($scope.arrayIndex == -1)
                {
                    ////console.log($scope.scan);
                    $http({
                        url: "PickUp_en/packCheck_3pl",
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
                        }
                        else
                        {
                            $scope.GetremoveBtn=true;
                        }
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.awbArray.push(value);
                            angular.forEach(JSON.parse(value.sku), function (value1) {
                                ////console.log(value1)

                                $scope.shipData.push({'slip_no': value.frwd_company_awb,  'sku': value1.ean_no, 'ean_no': value1.sku,  'piece': value1.piece, 'scaned': 0, 'extra': 0, 'print_url': value.print_url, 'frwd_company_id': value.frwd_company_id,'slip_no_r': value.slip_no,'frwd_company_awb': value.frwd_company_awb,'box_type':{},'slip_new':value.slip_no});
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

            };

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


            $scope.packuShip = function () {
                 
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                     disableScreen(1);
                     $scope.loadershow=true;
                     
                    //console.log($scope.scan);
                    $http({
                        url: "PickUp_en/packCheck",
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
                            console.log(value)

                            $scope.awbArray.push(value);
                            angular.forEach(JSON.parse(value.sku), function (value1) {
                                //console.log(value1)

                                //note 
                                //ean_no=sku
                                //sku=ean_no
                                $scope.shipData.push({'slip_no': value.slip_no, 'sku': value1.ean_no, 'ean_no': value1.sku, 'piece': value1.piece, 'scaned': 0, 'extra': 0, 'print_url': value.print_url, 'frwd_company_id': value.frwd_company_id, 'frwd_company_awb': value.frwd_company_awb,'invoice_label': value.invoice_label});
                                $scope.SKuMediaArr.push({'sku': value1.sku, 'piece': value1.piece, 'item_path': value1.item_path});

                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                            });

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });

                        // console.log( $scope.SKuMediaArr);
                        // $scope.GetcheckskuOtherData($scope.shipData[$scope.arrayIndexnew].sku,$scope.shipData[$scope.arrayIndexnew].piece);




                    });
                      disableScreen(0);
                         $scope.loadershow=false; 
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
                console.log($scope.specialpacktype);
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
                        $scope.warning = $scope.scan.sku + ', EAN not available for this shipment!';
                        responsiveVoice.speak('EAN not available for this shipment!');
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
                        $scope.completeArray.push({'slip_no': $scope.checkArray[0].slip_no, 'specialpack': $scope.specialtype.specialpack, 'specialpacktype': $scope.specialtype.specialpacktype, 'print_url': $scope.checkArray[0].print_url, 'frwd_company_id': $scope.checkArray[0].frwd_company_id,'invoice_label': $scope.checkArray[0].invoice_label, 'frwd_company_awb': $scope.checkArray[0].frwd_company_awb, 'box_no': $scope.scan_new.box_no, 'buttontype': $scope.buttontype,'slip_new':$scope.checkArray[0].slip_new});

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
                    console.log($scope.nindex);
                    // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                    if ($scope.nindex != -1) {
                        $scope.print_url = $scope.shipData[$scope.nindex].print_url

                        $scope.printToCart($scope.print_url);
                    }
                }
                console.log($scope.awbArray_print);
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
                         disableScreen(1);
                         $scope.loadershow=true; 
                        $http({
                            url: "PickUp_en/packFinish",
                            method: "POST",
                            data: {
                                shipData: $scope.completeArray,
                                exportData: $scope.shipData,
                                SpecialArr: $scope.specialtype,
                                boxArr: $scope.scan_new,
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                             disableScreen(0);
                         $scope.loadershow=false; 
                            console.log(response);

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
                            console.log(error);
                        });
                    }

                }
            };
            $scope.finishScanFrwd = function ()
            {

                if ($scope.completeArray.length > 0)
                {
                    var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                    if (isconfirm)
                    {
                         disableScreen(1);
                         $scope.loadershow=true; 
                        $http({
                            url: "PickUp_en/packFinishFwd",
                            method: "POST",
                            data: {
                                shipData: $scope.completeArray,
                                exportData: $scope.shipData,
                                SpecialArr: $scope.specialtype,
                                boxArr: $scope.scan_new,
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                             disableScreen(0);
                         $scope.loadershow=false; 
                            console.log(response);
                            $scope.GetremoveBtn = true;
                            $scope.scan = {};

                            $scope.SKuMediaArr = [];
                            $scope.shipData = [];
                            $scope.completeArray = [];
                            $scope.Message = "Completed order Packed!";


                        }, function (error) {
                            console.log(error);
                        });
                    }

                }
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