<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/courier_company.js"></script>
    </head>

    <body ng-app="CourierAppPage" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"  ng-controller="CourierComapnyCRL" ng-init="GetCompanylistDrop();"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 
                        <!--style="background-color: red;"-->


<div class="loader logloder" ng-show="loadershow"></div>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?//=lang('lang_Forward_to_Delivery_Station');?>Torod Automations </strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                    <?php
                                            if ($this->session->flashdata('Success') != '') {
                                                echo '<div class="alert alert-success" role="alert">  ' . $this->session->flashdata('Success') . '.</div>';
                                            }
                                            ?>

                                        <div class="card-body">
                                           <form method="post" action="TorodForward/update_automation">
                                            <?php if(site_configTable('torod_automation_flag')=='Y'){ $automationYes = "checked";}else{$automationNo = "checked";}  ?>

                                            <div class="form-group">
                                                <label for="automation"><strong>Torod Automation Active Status :</strong></label><br>
                                                <input type="radio" name="automation" id="automation" value="Y" <?=$automationYes; ?>>Yes
                                                <input type="radio" name="automation" id="automation" value="N" <?=$automationNo; ?>>No
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                           </form>

                                        </div>


                                    </div>

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /dashboard content --> 

                <!-- /basic responsive table --> 

            </div>
            <!-- /content area --> 
        </div>
        <?php $this->load->view('include/footer'); ?>

        <!-- /page container -->

    </body>
</html>
