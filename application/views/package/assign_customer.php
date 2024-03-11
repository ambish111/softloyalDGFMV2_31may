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
                            <div class="panel-heading"><h1><strong>Assign Customer</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>

                                
                                
                                <form action="<?= base_url('Package/assign_custmer_submit'); ?>" method="post" name="itmfrm">

                                    <div class="form-group">
                                        <label for="name"><strong>Customer:</strong></label>
                                       <select name="seller_id" id="seller_id" class="selectpicker" data-show-subtext="true" data-live-search="true"  ng-model="item.seller_id"data-width="100%">
                                            <option value=""><?=lang('lang_Please_select_seller');?></option>
                                            <?php
                                            // print_r($sellers);
                                            foreach (GetcustomerDropdata_wallet() as $seller):
                                                ?>

                                                <option value="<?= $seller['id'] ?>"><?= $seller['company']; ?>/<?= $seller['uniqueid'] ?></option>

<?php endforeach; ?>

                                        </select>
                                        <span class="error" ng-show="itmfrm.seller_id.$error.required"> Please Select Customer </span>
                                    </div>
                                    
                                     <div class="form-group">
                                        <label for="name"><strong>Package:</strong></label>
                                       <select name="p_id" id="p_id" class="selectpicker" data-show-subtext="true" data-live-search="true" ng-model="item.p_id"data-width="100%">
                                            <option value="">Select Package</option>
                                            <?php
                                            // print_r($sellers);
                                            foreach ($packageArr as $data):
                                                ?>

                                                <option value="<?= $data['id'] ?>"><?= $data['name']; ?></option>

<?php endforeach; ?>

                                        </select>
                                        <span class="error" ng-show="itmfrm.p_id.$error.required"> Please Select Package </span>
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

