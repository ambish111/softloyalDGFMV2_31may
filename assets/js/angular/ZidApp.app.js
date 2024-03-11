var app = angular.module('ZidApp', [])

        .controller('ZIdAppCtlr', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.UserArr = {};
            $scope.sub_catArr = {};

            angular.element(document).ready(function (){

            });


            $scope.showasallatemplatelist = function (type)
            {
                $scope.filterData.type=type;
                $http({
                    url: SITEAPP_PATH + "ZidApp/showasallatemplatelist",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.UserArr = response.data;
                });


            };

            $scope.close_model=function()
            {
                 $scope.loadershow = true;
                disableScreen(1);
                $("#addcustomerModal").modal("hide");
                $scope.showasallatemplatelist();
            }
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


            $scope.linkToSalla = function (index,id)
            {
                 $("#customerModal").modal({backdrop: 'static',
                     keyboard: false})
                     $scope.mid = id;
            };

            $scope.GetChangeAssignType = function (type)
            {
                console.log(type);
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

            $scope.sallaupactive_new = function (data1,type)
            {
                $scope.filterData.main=data1;
                 $scope.filterData.type_new=type;
                if(confirm("Are you want to sure ?")){
                    $http({
                        url: SITEAPP_PATH + "ZidApp/sallaStatusUpdate_new",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {

                        if (response.data.status == 'succ')
                        {
                          alert("Status Updated Successfully");
                          location.reload();
                        }
                        else
                        {
                           alert("try again"); 
                        }
                    });
                }
            };
            
            $scope.sallaupactive = function (id,type)
            {
                if(confirm("Are you want to sure ?")){
                    $http({
                        url: SITEAPP_PATH + "ZidApp/sallaStatusUpdate",
                        method: "POST",
                        data: 'id='+id+'&type='+type,
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
                if(confirm('Are you sure want to link this customer ?')){
                    
                        if ($scope.AssignData.customer_id )
                        {
                            //if($scope.AssignData.salla_shipping_cost)
                            {
                                $scope.loadershow = true;
                                disableScreen(1);
                                $scope.AssignData.mid = $scope.mid;
                                $http({
                                    url: SITEAPP_PATH + "ZidApp/saveassigntosalla",
                                    method: "POST",
                                    data: $scope.AssignData,
                                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                                }).then(function (response) {
                                    //console.log(response);
                                    if (response){
                                        if (response.data.status == 'succ'){
                                            alert("Successfully Assign");
                                            location.reload();
                                        }else{
                                            $scope.Success_msg = 'Linked Successfully.';
                                            $scope.Error_msg = response.data.Error_msg;
                                        }
                                    } else{
                                        alert("try again");
                                    }
    
                                });
                                disableScreen(0);
                                $scope.loadershow = false;
                            }
                            
//                        else{
//                                alert("Please Enter Shipping Cost Value.");
//                            }
                            
                        } else{
                                alert("Please Select Seller Name");
                        }
                }
            }
        });
/*------ /show shipments-----*/