var mymodule = angular.module("SallOrderApp", []);

/**
 * Angular Controller
 */

var Employee_list = mymodule.controller("tblController", function ($compile, $scope, $http) {
    $scope.NumberOfPages = 0;
    $scope.filter_data = {};
    $scope.currentPage = 1;
    $scope.paginationMaxBtn = 5;
    $scope.maxLength = 10;
    
    $scope.showList = [];
    $scope.error = false
    $scope.error_msg = '';
    $scope.loadershow=false;
   

$scope.isAll = false;
    $scope.selectAllFriends = function () {
        if ($scope.isAll === false) {
            angular.forEach($scope.showList, function (data) {
                data.checked = true;
            });
            $scope.isAll = true;
        } else {
            angular.forEach($scope.showList, function (data) {
                data.checked = false;
            });
            $scope.isAll = false;
        }
    };
$scope.allSallaPush=function(list)
{
    disableScreen(1);
        $scope.loadershow = true; 
       
    var itemList = [];
        angular.forEach(list, function (value, key) {
            //console.log(value.fs_awb);
            if (list[key].checked && value.fs_awb=='NO') {
                itemList.push(list[key].reference_id);
            }
        }); 
        console.log(itemList);
        $scope.filter_data.list_awb=itemList;
 
        $http({
            url: URLBASE+"Shipment/pushSallaOrder_new",
            method: "POST",
            data: $scope.filter_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

        }).then(function (response) {
            $scope.get_filter();
           // disableScreen(0);
       // $scope.loadershow = false; 
        }); 
};

$scope.total_show=0;
    $scope.get_filter = function () {
        $scope.showList = [];
        disableScreen(1);
        $scope.loadershow = true;
        $scope.filter_data.currentPage = $scope.currentPage;
        $scope.filter_data.limit = $scope.currentPage;


        $http({
            url: URLBASE+"Salla_orders_new/filter",
            method: "POST",
            data: $scope.filter_data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

        }).then(function (response) {
            disableScreen(0);
            $scope.loadershow = false;
            if (response.status == 200) {
                var data = response.data
                if (!!data.salla_data && data.total) {
                   
                    $scope.showList = data.salla_data;
                    $scope.total_show=data.total;
                    $scope.NumberOfPages = data.totalpage;
                    //alert($scope.NumberOfPages);


                    // Initiate Pagination
                    bs5cp_paginations.paginate();
                    document.querySelectorAll('.bootstrap5-custom-pagination').forEach(function (element) {
                        element.childNodes.forEach(function (child_element) {
                            $compile(child_element)($scope)
                        })
                    })

                } else if (!!data.error) {
                    $scope.error_msg = data.error
                } else {
                   // console.error(response)
                    $scope.error_msg = "Fetching List failed due to some reasons."
                }
            } else {
                $scope.error_msg = "Fetching List failed due to some reasons."
               // console.error(response)
            }
        },
                function (error) {
                    $scope.error_msg = "Fetching List failed due to some reasons."
                   // console.error(error)
                }
        )
    }

    /**
     * Trigger Pagination
     * @param {int} page  
     */
    $scope.paginate = function (page) {
        $scope.currentPage = page;
    }

    /**
     * Listen for Current Page Changes
     */
    $scope.$watch('currentPage', function () {
        $scope.get_filter()
    })
    //$scope.get_filter()

})