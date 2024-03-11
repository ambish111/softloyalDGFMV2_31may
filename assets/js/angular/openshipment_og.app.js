var app = angular.module('OpenshipAPP', [])

 .controller('oepnship_CTLR', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.invalid = [];
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.type = 'OG';
             $scope.comments = "";
            $scope.BoxInputshow = false;
            $scope.scan_awb = function () {
                //$('#scan_awb').focus();
                console.log($scope.scan);
                $scope.scan.awbArray = removeDumplicateValue($scope.scan.slip_no.split("\n"));
                console.log($scope.scan.awbArray);
                $scope.scan.slip_no = $scope.scan.awbArray.join('\n');
                $scope.validateOrder();
            }
            
            $scope.GetCheckvalidbox=function()
            {
              if($scope.type=='SFP')
              {
                 $scope.BoxInputshow = true;  
              }
              else
              {
                   $scope.comments = "";
                   $scope.BoxInputshow = false;
              }
            };

            $scope.dispatchOrder = function ()
            {
                 disableScreen(1);
                $scope.loadershow = true;
                $scope.scan.type = $scope.type;
                $scope.scan.comments = $scope.comments;
                $http({
                    url: SITEAPP_PATH+"OpenShipment/openorderStatusProcess_og",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}



                }).then(function (response) {
                    console.log(response);
                    // $scope.scan.awbArray={};
                   // $scope.scan = {};
                    disableScreen(0);
                    $scope.loadershow = false;
                    if (response.data == 'null')
                    {
                        
                        $scope.scan={};
                        var sound = document.getElementById("audioSuccess");
                        sound.play();
                        $scope.Message = "Orders Updated !";
                        responsiveVoice.speak($scope.Message);
                       // $scope.sendSms();
                    } else
                    {
                        $scope.warning = response.data;
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
            $scope.validateOrder = function () {
                $scope.invalid = [];
                $scope.warning = null;
                $scope.Message = null;
                // alert("ss");

                //console.log($scope.scan);
                $http({
                    url: SITEAPP_PATH+"OpenShipment/validateDispatch_og",
                    method: "POST",
                    data: $scope.scan.awbArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                   
                  
                    angular.forEach(response.data.invalid, function (value) {
                      console.log(value);
                        var index = $scope.scan.awbArray.indexOf(value.slip_no);
                        if (index > -1) {
                            
                          
                            $scope.scan.awbArray.splice(index, 1);
                        }
                        $scope.invalid.push(value.slip_no);


                    });
                    $scope.scan.slip_no = $scope.scan.awbArray.join('\n');
                    $scope.invalidstring = $scope.invalid.join();
                   // alert("wwwwwww");
                    //console.log($scope.invalidstring);
                    


                });




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