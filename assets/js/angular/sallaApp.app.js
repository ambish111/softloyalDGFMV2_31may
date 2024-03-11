var app = angular.module('sallaApp', [])

        .controller('SallaAppCtlr', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.UserArr = {};
            $scope.sub_catArr = {};
            $scope.newCustArr = {};

            angular.element(document).ready(function () {
                $(".select2").select2();

            });

            $scope.showasallatemplatelist = function (type)
            {
                 $scope.loadershow = true;
                disableScreen(1);
                $scope.filterData.type = type;
                $http({
                    url: SITEAPP_PATH + "SallaApp/showasallatemplatelist",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.UserArr = response.data;
                     $scope.loadershow = false;
                disableScreen(0);
                });


            };

            $scope.linkToSalla = function (index, id,app_mode)
            {
                $("#customerModal").modal({backdrop: 'static',
                    keyboard: false})
                $scope.mid = id;
                $scope.app_mode = app_mode;
            };

            $scope.addNewCustomerPop = function (data, type, index)
            {
                $scope.loadershow = true;
                disableScreen(1);
                $scope.newCustArr.main = data;
                $scope.newCustArr.type_new = type;
                $("#addcustomerModal").modal({backdrop: 'static', keyboard: true});
                $scope.mid = data.id;
                $scope.loadershow = false;
                disableScreen(0);
            };

            $scope.GetChangeAssignType = function (type)
            {
                if (type == 'ADD')
                {
                    $scope.addbutton = true;
                    $scope.linkbutton = false;
                } else
                {
                    $scope.addbutton = false;
                    $scope.linkbutton = true;
                }
            };
            $scope.close_model=function()
            {
                 $scope.loadershow = true;
                disableScreen(1);
                $("#addcustomerModal").modal("hide");
                $scope.showasallatemplatelist();
            }
            $scope.show_error={};
            $scope.sallaupactive_new = function (data1, type)
            {
                 $scope.loadershow = true;
                disableScreen(1);
                $scope.filterData.main = data1;
                $scope.filterData.type_new = type;
                if (confirm("Are you want to sure ?")) {
                    $http({
                        url: SITEAPP_PATH + "SallaApp/sallaStatusUpdate_new",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {

                        if (response.data.status == 'succ')
                        {
                            alert(response.data.mess);
                           // alert("Status Updated Successfully");
                            location.reload();
//                             $scope.loadershow = false;
//                            disableScreen(0);
                        } else
                        {
                             $scope.loadershow = false;
                            disableScreen(0);
                           
                            alert(response.data.mess);
                        }
                    });
                }
            };

            $scope.sallaupactive = function (id, type)
            {
                if (confirm("Are you want to sure ?")) {
                    $http({
                        url: SITEAPP_PATH + "SallaApp/sallaStatusUpdate",
                        method: "POST",
                        data: 'id=' + id + '&type=' + type,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {

                        if (response.data.status == 'succ')
                        {
                            alert("Status Updated Successfully");
                            location.reload();
                        }
                    });
                }
            };

            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};
            $scope.saveassigntosalla = function ()
            {
                if (confirm('Are you sure want to link this customer ?')) {

                    if ($scope.AssignData.customer_id)
                    {
                        if ($scope.AssignData.salla_shipping_cost) {
                            $scope.loadershow = true;
                            disableScreen(1);
                            $scope.AssignData.mid = $scope.mid;
                             $scope.AssignData.app_mode=$scope.app_mode;
                            $http({
                                url: SITEAPP_PATH + "SallaApp/saveassigntosalla",
                                method: "POST",
                                data: $scope.AssignData,
                                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                            }).then(function (response) {
                                //console.log(response);
                                if (response) {
                                    if (response.data.status == 'succ') {
                                        alert("Successfully Assign");
                                        location.reload();
                                    } else {
                                        $scope.Success_msg = 'Linked Successfully.';
                                        $scope.Error_msg = response.data.Error_msg;
                                    }
                                } else {
                                    alert("try again");
                                }

                            });
                            disableScreen(0);
                            $scope.loadershow = false;
                        } else {
                            alert("Please Enter Shipping Cost Value.");
                        }

                    } else {
                        alert("Please Select Seller Name");
                    }
                }
            }
        });
/*------ /show shipments-----*/