<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
        <?php $this->load->view('include/file'); ?>

        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/picking.app.js?v=<?= time(); ?>"></script>

    </head>

    <body ng-app="PickingApp" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="scanShipment">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?> 

                    <div class=""  >
                        <input type="text" name="destination[]" ng-model="inputValue" style="display:none"/>
                        <div class="page-header page-header-default">
                            <div class="page-header-content">
                                <div class="page-title">
                                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?= lang('lang_Packaging'); ?></span> </h4>
                                </div>
                            </div>
                        </div>

                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title">All New Links</h5>

                                </div>
                                <div class="panel-body">
                                    <div class="row">



                                        <div class="col-lg-12">

                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <p><a href="<?=base_url();?>Stocks/generateStockLocation" target="_blank">Generate Stock location</a></p>
                                                     <p><a href="<?=base_url();?>Shipment_og/bulk_location" target="_blank">Bulk Upload Location</a></p>
                                                   
                                                    <p><a href="<?=base_url();?>showStocklocation" target="_blank">All Stock Location</a></p>
                                                    <p><a href="<?=base_url();?>showStocklocation/AS" target="_blank">Assigned Stock Location</a></p>
                                                    <p><a href="<?=base_url();?>showStocklocation/UN" target="_blank">Unassigned Stock Location</a></p>
                                                    <p><a href="<?=base_url();?>stocks/updateManifest/62D4EC4A50346" target="_blank">Manifest Add Inventory</a> Replace manifest id</p>
                                                    <p><a href="<?=base_url();?>stockInventory" target="_blank">Show Inventory</a></p>
                                                    <p><a href="<?=base_url();?>stockInventory/activityhistory" target="_blank">Inventory History</a></p>
                                                    <p><a href="<?=base_url();?>stockInventory/stockhistory" target="_blank">Seller Stock History</a></p>
                                                    <p><a href="<?=base_url();?>stockInventory/recieveinventory" target="_blank">Seller Inventory</a></p>

                                                    <p><a href="<?=base_url();?>Shipment_og/ordergeneratedView" target="_blank">Order Generated</a></p>

                                                    <p><a href="<?=base_url();?>Shipment_og/pickup_order" target="_blank">Picking Order</a></p>

                                                    <p><a href="<?=base_url();?>Shipment_og/open_order" target="_blank">Open Order</a></p>

                                                    <p><a href="<?=base_url();?>Shipment_og/return_order" target="_blank">Return Order</a></p>






                                                </div>

                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>

                                            </div> 
                                            <!--contenttitle--> 
                                        </div>




                                        <div>&nbsp;</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="test_print" ></div>


