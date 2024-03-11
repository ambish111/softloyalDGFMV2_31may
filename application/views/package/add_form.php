<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
    </head>
    <body>
        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-app="formApp" ng-controller="formCtrl">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="loader logloder" ng-show="loadershow"></div>
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong>Add New Package</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>

                                <form action="<?= base_url('Package/add_submit'); ?>" method="post" name="itmfrm">

                                    <div class="form-group">
                                        <label for="name"><strong><?= lang('lang_Name'); ?>:</strong></label>
                                        <input type="text" class="form-control" name='name' id="name" placeholder="<?= lang('lang_Name'); ?>" ng-model="item.name" required>
                                        <span class="error" ng-show="itmfrm.name.$error.required"> <?= lang('lang_PleaseEnterName'); ?> </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_of_orders"><strong>Order Limit:</strong></label>
                                        <input type="number" class="form-control" min="1" name='no_of_orders'  id="no_of_orders" placeholder="Order Limit" ng-model="item.no_of_orders" required>
                                        <span class="error" ng-show="itmfrm.no_of_orders.$error.required">Please Enter order Limit </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="price"><strong>Price:</strong></label>
                                        <input type="number" class="form-control" name='price' min="1" step="any"  id="price" placeholder="Price" ng-model="item.price" required>
                                        <span class="error" ng-show="itmfrm.price.$error.required">Please Enter Price </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="less_qty"><strong>Validity Days:</strong></label>
                                        <input type="number" class="form-control" name='validity_days' id="validity_days" placeholder="Validity Days" ng-model="item.validity_days" required>
                                        <span class="error" ng-show="itmfrm.validity_days.$error.required"> Please Enter Validity Days </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="description"><strong>Details:</strong></label>
                                        <textarea rows="5" id="details" name="details" class="form-control" placeholder="Details" ng-model="item.details" required></textarea><span class="error" ng-show="itmfrm.details.$error.required"> Please Enter Details </span>
                                    </div>

                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success" ng-click="getcheckform();" ng-disabled="itmfrm.$invalid"><?= lang('lang_Submit'); ?></button>
                                    </div>
                                </form>

                            </div>
                        </div>    
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->



                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->
        <!--/script> -->



        <script>
            var app = angular.module('formApp', []);
            app.controller('formCtrl', function ($scope) {
                $scope.loadershow = false;

                $scope.getcheckform = function ()
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                };


            });
        </script>

    </body>
</html>

