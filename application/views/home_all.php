
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/dgpk.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>

        <style>
            
           .newbgCL{ background: #f5f5f5;}
        </style>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading"dir="ltr">
                                        <h6 class="panel-title"><?=lang('lang_Dashboard');?></h6>
                                        <div class="heading-elements"> <span class="label bg-success heading-text"><?=lang('lang_DETAILS');?></span> 

                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-lg text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <td class="col-md-5"><div class="media-left">
                                                            <div id="campaigns-donut"></div>
                                                        </div>
                                                        <div class="media-left"> 

                                                        </div></td>
                                                    <td class="col-md-5"><div class="media-left">
                                                            <div id="campaign-status-pie"></div>
                                                        </div>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Quick stats boxes  style="background-color: #263238"-->

                                    <?php if (menuIdExitsInPrivilageArray(93) == 'Y') { ?>
                                        <div class="row" >


                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list ">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Total_Shipments ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Shipments');?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>





                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <!--<a href="<?= base_url('RTS'); ?>">-->
                                                <div class="panel newbgCL">
                                                    <div class="panel-body">
                                                        <div class="heading-elements">
                                                            <ul class="icons-list">
                                                                <li><i class="icon-office icon-2x"></i></li>
                                                            </ul>
                                                        </div>
                                                        <h3 class="no-margin">
                                                            <?= GettotalpalletsCount(); ?>
                                                        </h3>
                                                        <?=lang('lang_Total_Used_Pallets');?>
                                                        <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                    </div>
                                                    <div id="today-revenue"></div>
                                                </div>
                                                <!--  </a> -->
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('ItemInventory'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <?php if ($Item_Inventory != 0): ?>
                                                                <h3 class="no-margin">
                                                                    <?= $Item_Inventory ?>
                                                                </h3>
                                                            <?php else: ?>
                                                                <h3 class="no-margin">0</h3>
                                                            <?php endif; ?>
                                                            Items in Inventory
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Item'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-stack2 icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Total_Items; ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Items');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>

                                            <!-- /today's revenue -->
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Seller'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                  <li><!-- <span class="label bg-warning-400">CHECK </span> --> 
                                                                        <i class="icon-users icon-2x"></i> </li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Total_Sellers; ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Sellers');?> 

                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="#">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $Item_Inventory_expire; ?>
                                                            </h3>
                                                            <?=lang('lang_Total_Items_in_Inventory_Expired');?> 

                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        </div>
                                    <?php } ?>
                                    



                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 

                     

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
<!-- footer limited  check it-->