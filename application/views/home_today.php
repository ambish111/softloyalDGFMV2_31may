
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

        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>

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

                                   
                                    <?php if (menuIdExitsInPrivilageArray(94) == 'Y') { ?>
                                        <div class="row">
                                            <div class="panel-heading">
                                                <h6 class="panel-title"><?=lang('lang_TodayDashboard');?></h6>

                                            </div>
                                              <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-list icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?=$shipArr['t_og'];?>
                                                            </h3>
                                                            <?=lang('lang_OrderGenerated');?>
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
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?=$shipArr['t_oc'];?>
                                                            </h3>
                                                            <?=lang('lang_OrderCreated');?>
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
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-copy icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                 <?=$shipArr['t_pg'];?>
                                                            </h3>
                                                            <?=lang('lang_PicklistGenerated');?> 
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
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-user icon-2x"></i></li>
                                                                </ul>
                                                            </div>

                                                            <h3 class="no-margin">
                                                                <?=$shipArr['t_ap'];?>
                                                            </h3>
                                                            <?=lang('lang_AssigningToPicker');?> 
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
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-box icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?=$shipArr['t_pk'];?>
                                                            </h3>
                                                            <?=lang('lang_Packed');?> 
                                                            <!-- <div class="text-muted text-size-small">34.6% avg</div> --> 
                                                        </div>
                                                        <div id="today-revenue"></div>
                                                    </div>
                                                </a> 
                                                <!-- /today's revenue --> 

                                            </div>
                                            <div class="col-lg-4" style="padding-left: 20px; padding-right: 20px;"> 

                                                <?php //echo get_total_current(5); die;?>
                                                <!-- Today's revenue --> 
                                                <a href="<?= base_url('Shipment'); ?>">
                                                    <div class="panel newbgCL">
                                                        <div class="panel-body">
                                                            <div class="heading-elements">
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-bus icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?=$shipArr['t_dl'];?>
                                                            </h3>
                                                            <?=lang('lang_DispatchedtoLM');?> 
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
                                                                <ul class="icons-list">
                                                                    <li><i class="icon-server icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                               <?=$shipArr['t_pod'];?>
                                                            </h3>
                                                            <?=lang('lang_Delivered');?>
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
                                                                    <li><i class="fa fa-arrow-left fa-2x icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                 <?=$shipArr['t_rtc'];?>
                                                            </h3>
                                                            <?=lang('lang_Return');?>
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
                                                                    <li><i class="fa fa-arrow-right fa-2x icon-2x"></i></li>
                                                                </ul>
                                                            </div>
                                                            <h3 class="no-margin">
                                                                <?= get_total_current_undelivered(); ?>
                                                            </h3>
                                                            <?//=lang('lang_Return');?>
                                                            Undelivered
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
<!-- footer limited  check it-->