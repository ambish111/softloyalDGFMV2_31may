<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">

        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/seller.app.js?v<?= time(); ?>"></script>
        <style type="text/css">
            .form-group.radiosection {
                display: inline-block;
                width: 24%;
            } 
        </style>

    </head>

    <body >

        <?php $this->load->view('include/main_navbar'); ?>
        <!-- Page container -->
        <div class="page-container" ng-app="SellerAPp">
            <!-- Page content -->
            <div class="page-content" ng-controller="woocommereceCtrl">

                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>
                    <!-- Content area -->
                    <div class="content">
                        
                         <div class="loader logloder" ng-show="loadershow"></div>
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong>WooCommerce Configuration</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/updateWoocommerce/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">

                                    <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">WooCommerce <?= lang('lang_Details'); ?></legend>
                                        <div class="form-group">
                                            <label>Consumer key</label>
                                            <input type="text" class="form-control" name="consumer_key" id="consumer_key" value="<?php echo $customer['wc_consumer_key']; ?>" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label>Consumer secret key</label>
                                            <input type="text" class="form-control" name="consumer_secreat_key" id="consumer_secreat_key" value="<?php echo $customer['wc_secreat_key']; ?>" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label>Store Url</label>
                                            <input type="text" class="form-control" name="consumer_store_url" id="consumer_store_url" value="<?php echo $customer['wc_store_url']; ?>" autocomplete="off">
                                        </div>

                                        <div class="form-group">
                                            <label class="radio-inline">
                                                <input type="radio" name="consumer_active" <?php echo ($customer['wc_active'] == "1" ? 'checked' : ''); ?> value="1"><?= lang('lang_active'); ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="consumer_active" <?php echo ($customer['wc_active'] == "0" ? 'checked' : ''); ?> value="0"><?= lang('lang_inactive'); ?>
                                            </label>
                                        </div>
                                        
                                        <button type="submit" name="updatewoocommerce" value="1" class="btn btn-primary pull-right">Update</button>

                                    </fieldset>
                                   
                                </form>
                                
                                 <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">Status Mapping</legend>
                                         <div class="form-group">
                                            <button type="button"  class="btn btn-warning" ng-click="GetshowStatusList(<?=$customer['id'];?>);">Status Mapping</button>
                                        </div>
                                        <div class="form-group">
                                            <button type="button"  class="btn btn-info" ng-click="GetshowStatusList_WC(<?=$customer['id'];?>);">Get Status</button>
                                        </div>
                                 </fieldset>

                            </div>
                        </div>
                        
                        <div id="Viewliststatus_pop" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f3f5f6;">
                               <h4 class="modal-title" style="color:#000">Status Mapping</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                              

                                <div class="table-responsive" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable">
                                        
                                         <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th>Main Status</th>
                                                 <th>WooCommerce Status</th>
                                               
                                            </tr>
                                        </thead>
                                        
                                          <tbody>


                                            <tr ng-if='ListData != 0' ng-repeat="data in ListData">
                                                <td>{{$index + 1}} </td>
                                                <td>{{data.main_status}}</td>
                                                <td><input type="text" ng-model="ListData[$index].wc_status"></td>
                                               
                                            </tr>
                                        </tbody>
                                    </table></div>
                               

                               

                            </div>

                        </div>
                        
                         <div class="modal-footer">
                               
                                <button type="button" ng-confirm-click="Are you sure want to update ?"  class="btn btn-info pull-right" ng-click="GetUpdateStatusFinal(<?=$customer['id'];?>);">Update</button> 
                            </div>
                    </div>
                </div>  


            </div>   
                        
                          <div id="Viewliststatus_WC_pop" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f3f5f6;">
                               <h4 class="modal-title" style="color:#000">WooCommerce Status</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                              

                                <div class="table-responsive" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable">
                                        
                                         <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th>Name</th>
                                                 <th>Slug</th>
                                               
                                            </tr>
                                        </thead>
                                        
                                          <tbody>


                                            <tr ng-if='ListData_WC != 0' ng-repeat="data in ListData_WC">
                                                <td>{{$index + 1}} </td>
                                                <td>{{data.name}}</td>
                                                <td>{{data.slug}}</td>
                                              
                                               
                                            </tr>
                                        </tbody>
                                    </table></div>
                               

                               

                            </div>

                        </div>
                    </div>
                </div>  


            </div>   
                        <hr>

                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->
                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->

        </div>
        <!-- /page container -->

    </body>
</html>

<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }
    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<script type="text/javascript">
    $('form').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');
</script>  
