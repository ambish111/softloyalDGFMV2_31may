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
            <div class="page-content" ng-controller="woocommereceCtrl" ng-init="GetcheckAuth_btn('<?=$customer['auth'];?>');">

                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper">
                    <?php $this->load->view('include/page_header'); ?>
                    <!-- Content area -->
                    <div class="content">
                        
                         <div class="loader logloder" ng-show="loadershow"></div>
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong>Webhook Configuration</strong></h1></div>
                            <hr>
                            
                            <div class="panel-body">
                                 <?php if (menuIdExitsInPrivilageArray(174) == 'Y') { ?>
                                <form action="<?= base_url('Seller/update_webhook/' . $cust_id); ?>" method="post" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $cust_id; ?>">

                                    <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">Status Webhook <?= lang('lang_Details'); ?></legend>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" id="name" required value="<?php echo $customer['name']; ?>" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="text" class="form-control" name="url" id="url" required value="<?php echo $customer['url']; ?>" autocomplete="off" required>
                                        </div>

                                       
                                        <?php
                                        
                                        if($customer['subscribe']=='Y')
                                        {
                                            $subscribe_check="checked";
                                        }
                                        else
                                        {
                                           $subscribe_check1="checked"; 
                                        }
                                        if($customer['auth']=='Y')
                                        {
                                            $auth_check="checked";
                                        }
                                        else
                                        {
                                           $auth_check1="checked"; 
                                        }
                                        ?>
                                        
                                        
                                        <div class="form-group">
                                            <label>Subscribe</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" name="subscribe" <?=$subscribe_check;?> value="Y">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="subscribe" <?=$subscribe_check1;?> value="N">No
                                            </label>
                                        </div>
                                         <div class="form-group">
                                            <label>Auth</label><br>
                                            <label class="radio-inline">
                                                <input type="radio"  name="auth" <?=$auth_check;?> value="Y" ng-click="GetcheckAuth_btn('Y');">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="auth" <?=$auth_check1;?> value="N" ng-click="GetcheckAuth_btn('N');">No
                                            </label>
                                        </div>
                                        
                                        <div class="form-group" ng-show="tokenbtn">
                                            <label>Token</label>
                                            <input type="text" class="form-control" name="auth_token" id="auth_token" value="<?php echo $customer['auth_token']; ?>" autocomplete="off">
                                        </div>
                                        
                                        <button type="submit" name="updatewebhook" value="1" class="btn btn-primary pull-right">Update</button>

                                    </fieldset>
                                   
                                </form>
                                 <?php } ?>
                                 <?php if (menuIdExitsInPrivilageArray(175) == 'Y') { ?>
                                 <form action="<?= base_url('Seller/update_webhook_inventory/' . $cust_id); ?>" method="post" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $cust_id; ?>">

                                    <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">Inventory Webhook <?= lang('lang_Details'); ?></legend>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" id="name" required value="<?php echo $customer_in['name']; ?>" autocomplete="off" required>
                                        </div>

                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="text" class="form-control" name="url" id="url" required value="<?php echo $customer_in['url']; ?>" autocomplete="off" required>
                                        </div>

                                       
                                        <?php
                                        
                                        if($customer_in['subscribe']=='Y')
                                        {
                                            $subscribe_check_in="checked";
                                        }
                                        else
                                        {
                                           $subscribe_check_In1="checked"; 
                                        }
                                       
                                        ?>
                                        
                                        
                                        <div class="form-group">
                                            <label>Subscribe</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" name="subscribe" <?=$subscribe_check_in;?> value="Y">Yes
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="subscribe" <?=$subscribe_check_In1;?> value="N">No
                                            </label>
                                        </div>
                                        
                                     
                                        <button type="submit" name="updatewebhook" value="1" class="btn btn-primary pull-right">Update</button>

                                    </fieldset>
                                   
                                </form>
                                
                                
                                 <?php } ?>
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
