<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta
            http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>



        <style>


            .newbgCL{
                background: #f5f5f5;
            }
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
                                    <div class="panel-heading">
                                        <h6 class="panel-title">Order Filters</h6>

                                    </div>




                                  
                                  


                                    <div class="row">
                                        <?php if (menuIdExitsInPrivilageArray(80) == 'Y' && $this->system_type == 'old') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('ordergenerated'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-list icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_og']; ?>
                                                            </h3>
                                                            <?= lang('lang_OrderGenerated'); ?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(80) == 'Y' && $this->system_type == 'new') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('ordergeneratedView'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-list icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_og']; ?>
                                                            </h3>
                                                            <?= lang('lang_OrderGenerated'); ?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(151) == 'Y' && $this->system_type == 'old') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Backorder'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-list icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?php echo statusCount_back(); ?>
                                                            </h3>
                                                             Back Order
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(151) == 'Y' && $this->system_type == 'new') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Backorder_new'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-list icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                               <?php echo statusCount_back(); ?>
                                                            </h3>
                                                            Back Order
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(13) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('orderCreated'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_oc']; ?>
                                                            </h3>
                                                            <?= lang('lang_OrderCreated'); ?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(14) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 


                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('pickupList'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_pg']; ?>
                                                            </h3>
                                                            Pick List
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(15) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('packed'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-box icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_pk']; ?>
                                                            </h3>
                                                            <?= lang('lang_Packed'); ?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(16) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <?php //echo get_total_current(5); die;?>
                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('dispatched'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?php echo $shipArr['t_dl'] + $shipArr['t_d_to3pl']; ?>
                                                            </h3>
                                                            <?= lang('lang_DispatchedtoLM'); ?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(104) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('delivered'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-server icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_pod']; ?>
                                                            </h3>
                                                            <?= lang('lang_Delivered'); ?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(105) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('returned'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list ">
                                                                    <li><i class="fa fa-arrow-left fa-2x icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_rtc']; ?>
                                                            </h3>
                                                            <?= lang('lang_Return'); ?>
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(103) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('delivery_manifest'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list ">

                                                                    <li><i class="icon-stack2 icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= ManifeststatusCount(); ?>
                                                            </h3>
                                                            Delivery Manifest
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(188) == 'Y') { ?>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('reverse_order'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list ">
                                                                    <li><i class="fa fa-arrow-left fa-2x icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= $shipArr['t_ro']; ?>
                                                            </h3>
                                                            Reverse Order
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>

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
                                                                <?= $today_shipment['total_today']; ?>
                                                            </h3>
                                                            <? //=lang('lang_Return');?>Today Recieved Order
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                        <?php } ?>



                                    </div>



                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 

                        <!-- Main charts -->

                        <!-- /main charts -->

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

