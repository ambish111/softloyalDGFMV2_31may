<!-- Main sidebar -->
<?php 
$LeftFmenuArr=menuIdExitsInPrivilageArray_return();

 $prohibatedIds = array(175,54) ;
        
        

$color = Getsite_configData_field('theme_color_fm'); ?>
<style>
    .sidebar-main{
        background-color:<?php echo $color; ?> !important;
    }
</style>
<div class="sidebar sidebar-main" style="background-color:#0B70CD;">
    <div class="sidebar-content"> 



        
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="navigation-header"><span><?= lang('lang_Main'); ?>    
                        </span> <i class="icon-menu" title="Main pages"></i></li>
<!--                    <li  <?php if ($this->uri->segment(1) == 'Home' || $this->uri->segment(1) == '') echo 'class="active"'; ?>>
                        <a href="<?= base_url('Home'); ?>"><i class="icon-home4"></i> <span><?= lang('lang_Home'); ?> </span></a>
                    </li>-->
                    <li <?php if ($this->uri->segment(1) == 'Home') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-home"></i> <span>Dashboard</span></a>
                        <ul>
                            <li><a href="<?= base_url('Home'); ?>">Today Dashboard</a></li>
                            <?php if(!in_array($this->session->userdata('user_details')['super_id'],$prohibatedIds)){
           ?>
                            <li><a href="<?= base_url('Home/all'); ?>">All Data</a></li>
                            <li><a href="<?= base_url('Home/all_graph'); ?>">All Graph</a></li>
                            <li><a href="<?= base_url('Home/order_filters'); ?>">Order Filters</a></li>
                            <?php } ?>

                        </ul>
                    </li>
                    <?php if (menuIdExitsInPrivilageArray_check(68,$LeftFmenuArr,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'CompanyDetails') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-cogs"></i> <span><?= lang('lang_General_Setting'); ?></span></a>
                            <ul>
                                <li><a href="<?= base_url('CompanyDetails'); ?>"><?= lang('lang_company_details'); ?></a></li>
                                <li><a href="<?= base_url('defaultlist_view'); ?>"><?= lang('lang_Default_courier_company'); ?></a></li>
                                <li><a href="<?= base_url('smsconfigration'); ?>"><?= lang('lang_SMS_Configuration'); ?></a></li>
                                <li><a href="<?= base_url('reverseconfigration'); ?>"><?= lang('lang_Reverse_Configuration'); ?></a></li>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(221,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'ZidApp') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-star"></i> <span>Integration</span></a>

                            <ul>
                                <?php if (menuIdExitsInPrivilageArray(239) == 'Y') { ?>

                                <li><a href="<?= base_url('SallaDetails'); ?>"><i class="fa fa-star"></i>Salla Configuration</a></li>
                                
                                <li  <?php if ($this->uri->segment(1) == 'new_request_salla' || $this->uri->segment(1) == 'rejected_request_salla' || $this->uri->segment(1) == 'accepted_request_salla') echo 'class="active"'; ?>>
                                    <a href="javascript: void(0);">
                                        <i class="fa fa-star"></i>
                                        <span>Salla App Config</span>
                                        <span class="badge badge-danger badge-pill float-right"></span>
                                    </a>
                                    <ul class="nav-second-level" aria-expanded="false">

                                        <?php if (menuIdExitsInPrivilageArray(240) == 'Y') { ?>
                                            <li><a href="<?= base_url('new_request_salla'); ?>">New Request</a></li>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(241) == 'Y') { ?>
                                            <li><a href="<?= base_url('rejected_request_salla'); ?>">Rejected</a></li>
                                        <?php } ?>
                                        <?php if (menuIdExitsInPrivilageArray(242) == 'Y') { ?>
                                            <li><a href="<?= base_url('accepted_request_salla'); ?>">Accepted</a></li>
                                        <?php } ?>



                                    </ul>
                                </li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(215,$LeftFmenuArr) == 'Y') { ?>
                                    <li  <?php if ($this->uri->segment(1) == 'ZidApp' || $this->uri->segment(1) == 'new_request') echo 'class="active"'; ?>>
                                        <a href="javascript: void(0);">
                                            <i class="fa fa-star"></i>
                                            <span>Zid App Config</span>
                                            <span class="badge badge-danger badge-pill float-right"></span>
                                        </a>
                                        <ul class="nav-second-level" aria-expanded="false">

                                            <?php if (menuIdExitsInPrivilageArray_check(216,$LeftFmenuArr) == 'Y') { ?>
                                                <li><a href="<?= base_url('ZidApp/new_request'); ?>">New Request</a></li>
                                            <?php } ?>
                                            <?php if (menuIdExitsInPrivilageArray_check(217,$LeftFmenuArr) == 'Y') { ?>
                                                <li><a href="<?= base_url('ZidApp/rejected_request'); ?>">Rejected</a></li>
                                            <?php } ?>
                                            <?php if (menuIdExitsInPrivilageArray_check(218,$LeftFmenuArr) == 'Y') { ?>
                                                <li><a href="<?= base_url('ZidApp/accepted_request'); ?>">Accepted</a></li>
                                            <?php } ?>



                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(1,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Shipment' || $this->uri->segment(1) == 'bulk_update_view' || $this->uri->segment(1) == 'TrackingResult' || $this->uri->segment(1) == 'TrackingDetails' || $this->uri->segment(1) == 'Forward_Delivery_Station' || $this->uri->segment(1) == 'bulkprint' || $this->uri->segment(1) == 'forwardshipments' || $this->uri->segment(1) == 'forwardedshipments' || $this->uri->segment(1) == 'bulk_tracking' || $this->uri->segment(1) == 'Backorder' || $this->uri->segment(1) == 'OpenShipment' || $this->uri->segment(1) == 'cod_update_3pl' || $this->uri->segment(1) == 'OpenShipment' || $this->uri->segment(1) == 'cancelReverseOrder' || $this->uri->segment(1) == 'Onhold' || $this->uri->segment(1) == 'Salla_orders_new' || $this->uri->segment(1) == 'Salla_orders' || $this->uri->segment(1) == 'remove_reverse_order') echo 'class="active"'; ?>> <a href="#"><i class="icon-bus"></i> <span><?= lang('lang_Shipment_Management'); ?></span></a>
                            <ul>
                            
                                <?php if (menuIdExitsInPrivilageArray_check(29,$LeftFmenuArr) == 'Y') { ?>

                                    <li><a href="<?= base_url('Shipment'); ?>"><?= lang('lang_All_Orders'); ?></a></li>
                                <?php } ?>
                                <?php //if (menuIdExitsInPrivilageArray_check(81,$LeftFmenuArr) == 'Y') { ?>
                    <!-- <li><a href="<?= base_url('Forward_Delivery_Station'); ?>"><?= lang('lang_Bulk_Forward3PL'); ?></a></li> -->
                                <?php //} ?>
                                <?php if (menuIdExitsInPrivilageArray_check(81,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Forward_Delivery_Station_New'); ?>"><?= lang('lang_Bulk_Forward3PL'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(101,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('forwardshipments'); ?>"><?= lang('lang_Manual_Forward3PL'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(142,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('cancelOrder'); ?>"><?= lang('lang_Cancelling_orders3PL'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(82,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulkprint'); ?>"><?= lang('lang_Bulk_Print'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(137,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulk_tracking'); ?>"><?= lang('lang_bulk_track'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(143,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('remove_forward'); ?>"><?= lang('lang_remove_forwarding'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(144,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Reverse_Delivery_Station'); ?>"><?= lang('lang_Reverse_Shipment'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(155,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('OpenShipment'); ?>"><?= lang('lang_Open_Shipment'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(169,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('openShipment/open_og'); ?>"> <?= lang('lang_Update_under_review'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(146,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Reverse_Shipment'); ?>"><?= lang('lang_View_Reverse_Shipment'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(145,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('shipment_mapping'); ?>"><?= lang('lang_Shipment_Mappings'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(151,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Backorder'); ?>"><?= lang('lang_Back_Order'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(157,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulk-audit'); ?>"><?= lang('lang_Bulk_Audit'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(160,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('bulk_order_create'); ?>"><?= lang('lang_Bulk_Order_Create'); ?></a></li>
                                <?php } ?>
                                     <?php if (menuIdExitsInPrivilageArray_check(254,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('order/bulk_order_create'); ?>"><?= lang('lang_Bulk_Order_Create'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(161,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('cod_update_3pl'); ?>"><?= lang('lang_Bulk_3PL_Cod'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(30,$LeftFmenuArr) == 'Y') { ?>

                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('bulk_update_view'); ?>"><?= lang('lang_Bulk_Update'); ?><span class="label bg-warning-400"><?= lang('lang_Update'); ?></span></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(159,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulkUploadShipment'); ?>"><?= lang('lang_Bulk_Update_Shipment'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(167,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('print/invoice'); ?>"><?= lang('lang_Bulk_Invoice_Print'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(176,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('salla_push_status'); ?>"><?= lang('lang_Salla_Push_Status'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(229,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('cancelReverseOrder'); ?>">Cancel Reverse Order</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(232,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Onhold'); ?>">Bulk On Hold</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(250,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Salla_orders_new'); ?>"><?= lang('lang_Salla_Order_Listing'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(251,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Salla_orders'); ?>"><?= lang('lang_Salla_Pending_Order'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(253,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('zidpendingOrder'); ?>"><?= lang('lang_Zid_Pending_Order'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(257,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('remove_reverse_order'); ?>">Remove Reverse Order</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(223,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'GenerateTorodWherehouse' || $this->uri->segment(1) == 'torod_order_create' || $this->uri->segment(1) == 'torod_shipment' || $this->uri->segment(1) == 'Torodforwardshipments' || $this->uri->segment(1) == 'torodLog') echo 'class="active"'; ?>> <a href="#"><i class="icon-bus"></i> <span>Torod Integration</span></a>   
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(224,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('GenerateTorodWherehouse'); ?>"><? //= lang('lang_Bulk_Forward3PL');  ?>Torod Create Warehouse</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(225,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('torod_order_create'); ?>"><? //= lang('lang_Bulk_Forward3PL');  ?>Torod Order Create</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(226,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('torod_shipment'); ?>"><? //= lang('lang_All_Orders');  ?>Torod Shipment View</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(227,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Torodforwardshipments'); ?>"><? //= lang('lang_Bulk_Forward3PL');  ?>Torod forward 3pl Manual</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(228,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('torodLog'); ?>"><? //= lang('lang_Bulk_Forward3PL');  ?>Torod Warehouse Log</a></li>
                                <?php } ?>
                                    <li><a href="<?= base_url('torod_automation'); ?>"><? //= lang('lang_Bulk_Forward3PL'); ?>Torod Automation</a></li>
                            </ul> 
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(2,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'ItemInventory' || $this->uri->segment(1) == 'add_shelve' || $this->uri->segment(1) == 'view_shelve' || $this->uri->segment(1) == 'shelve_sku' || $this->uri->segment(1) == 'historyview' || $this->uri->segment(1) == 'skuTransfer' || $this->uri->segment(1) == 'skuTransferedList' || $this->uri->segment(1) == 'inventory_check') echo 'class="active"'; ?>> <a href="#"><i class="icon-copy"></i> <span><?= lang('lang_Inventory_Management'); ?></span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(31,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('ItemInventory/add_view'); ?>"> <?= lang('lang_Add_Item_Inventory'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(33,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('ItemInventory'); ?>"><?= lang('lang_View_Item_Inventory'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(33,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url(); ?>stockInventory" > <?= lang('lang_Show_Inventory'); ?></a></li>
                                    <li><a href="<?= base_url(); ?>stockInventory/recieveinventory" ><?= lang('lang_Seller_Inventory'); ?></a></li>
                                <?php } ?>


                                <?php if (menuIdExitsInPrivilageArray_check(34,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_shelve'); ?>"><?= lang('lang_Add_Shelve'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(35,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('view_shelve'); ?>"><?= lang('lang_View_Pallet'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(36,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('shelve_sku'); ?>"><?= lang('lang_Update_Pallet'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(153,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('ItemInventory/ViewStock'); ?>"><?= lang('lang_Out_of_stock_inventory'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(74,$LeftFmenuArr) == 'Y') { ?>
                                <!--                                <li><a href="<?= base_url('skuTransfer'); ?>">Stock Transfer</a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(74,$LeftFmenuArr) == 'Y') { ?>
                                <li><a href="<?= base_url('skuTransferedList'); ?>">Stock Tranfer List</a></li>-->
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(150,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('Mergestock'); ?>"><?= lang('lang_Merge_Stock'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(150,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('Mergestock_new'); ?>"><?= lang('lang_Merge_Stock'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(119,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('inventory_check'); ?>"><?= lang('lang_Inventory_Per_Client'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(119,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('inventory_check_new'); ?>"><?= lang('lang_Inventory_Per_Client'); ?></a></li>
                                <?php } ?>

                                <?php if ($this->session->userdata('user_details')['super_id'] != 54) {
                                    ?>  
                                    <?php if (menuIdExitsInPrivilageArray_check(40,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                        <li class="navigation-divider"></li>
                                        <li><a href="<?= base_url('ItemInventory/add_bulk_view'); ?>"><?= lang('lang_Upload_Inventory'); ?><span class="label bg-warning-400"><?= lang('lang_Add'); ?> </span></a></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(2,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'showStocklocation' || $this->uri->segment(2) == 'generateStockLocation' || $this->uri->segment(1) == 'generateStockLocation' || $this->uri->segment(1) == 'showStock' || $this->uri->segment(2) == 'bulk_location' || $this->uri->segment(2) == 'bulk_location') echo 'class="active"'; ?>> <a href="#"><i class="icon-list"></i> <span> <?= lang('lang_Stock_Location_management'); ?> </span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(37,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('generateStockLocation'); ?>"><?= lang('lang_Generate_Stock_Location'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(37,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('Stocks/generateStockLocation'); ?>"><?= lang('lang_Generate_Stock_Location'); ?></a></li>

                                    <li><a href="<?= base_url(); ?>Shipment_og/bulk_location" > <?= lang('lang_Bulk_Upload_Location'); ?></a></li>
                                    <li><a href="<?= base_url(); ?>print_stlocation" > <?= lang('lang_Bulk_Print_Location'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(38,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('showStock'); ?>"> <?= lang('lang_All_Stock_Location'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(38,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('showStocklocation'); ?>"> <?= lang('lang_All_Stock_Location'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(38,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('showStock/AS'); ?>"> <?= lang('lang_Assigned_Stock_Location'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(38,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('showStocklocation/AS'); ?>"> <?= lang('lang_Assigned_Stock_Location'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(38,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('showStock/UN'); ?>"> <?= lang('lang_Unassigned_Stock_Location'); ?></a></li>
                                    <li><a href="<?= base_url('bulk_print_stock'); ?>"> Bulk Print Stock Location</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(38,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('showStocklocation/UN'); ?>"> <?= lang('lang_Unassigned_Stock_Location'); ?></a></li>
                                <?php } ?>


                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>




                    <?php if (menuIdExitsInPrivilageArray_check(3,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Item') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Item_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(41,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Item/add_view'); ?>"><?= lang('lang_Add_Item'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(42,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Item'); ?>"><?= lang('lang_View_All_Items'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(42,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('bulk_print_barcode'); ?>"> <?= lang('lang_Bulk_sku_print'); ?></a></li>
                                <?php } ?>
                                    
                                    <li><a href="<?= base_url('Item/bulk_update'); ?>"> Bulk Update</a></li>
                                    <?php if (menuIdExitsInPrivilageArray_check(230,$LeftFmenuArr) == 'Y') { ?>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(43,$LeftFmenuArr) == 'Y') { ?>
                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('Item/add_bulk_view'); ?>"><?= lang('lang_Import_Items'); ?><span class="label bg-warning-400"><?= lang('lang_Add'); ?> </span></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(43,$LeftFmenuArr) == 'Y') { ?>
                                    <li class="navigation-divider"></li>
                                    <li><a href="<?= base_url('Item/add_bulk_weight_view'); ?>"><?= lang('lang_Import_bulk_weight'); ?> <span class="label bg-warning-400">  <?= lang('lang_NEW'); ?></span></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(134,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'add_vehicle' || $this->uri->segment(1) == 'vehicle_list') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Vehicle_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(135,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_vehicle'); ?>"><?= lang('lang_Add_Vehicle'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(136,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('vehicle_list'); ?>"><?= lang('lang_Vehicle_List'); ?></a></li>
                                <?php } ?>


                            </ul>
                        </li>
                    <?php } ?>

                    <!-- <?php if (menuIdExitsInPrivilageArray_check(125,$LeftFmenuArr) == 'Y') { ?>
                                  <li <?php if ($this->uri->segment(1) == 'showtods' || $this->uri->segment(1) == 'GenerateTods') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span>Tod Management</span></a>
                                      <ul>
                                         
                        <?php if (menuIdExitsInPrivilageArray_check(127,$LeftFmenuArr) == 'Y') { ?>
                                                              <li><a href="<?= base_url('showtods'); ?>">Show Tod</a></li>
                        <?php } ?>
                                      </ul>
                                  </li>
                    <?php } ?> -->

                    <?php if (menuIdExitsInPrivilageArray_check(128,$LeftFmenuArr) == 'Y') {
                        ?>

                        <li  <?php if ($this->uri->segment(1) == 'damage_list' || $this->uri->segment(1) == 'return_manifest') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-truck"></i> <span><?= lang('lang_Return_Manifest'); ?></span></a>   
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(129,$LeftFmenuArr) == 'Y') {
                                    ?>

                                    <li><a href="<?= base_url('damage_list'); ?>"><?= lang('lang_Show_Damage_Item'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(130,$LeftFmenuArr) == 'Y') {
                                    ?>
                                    <li><a href="<?= base_url('return_manifest'); ?>"><?= lang('lang_Return_Order_List'); ?></a></li>
                                <?php } ?>
                            </ul> 
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(77,$LeftFmenuArr) == 'Y') { ?>

                        <li <?php if ($this->uri->segment(1) == 'GenerateTods' || $this->uri->segment(1) == 'showtods' || $this->uri->segment(1) == 'addWarehouse' || $this->uri->segment(1) == 'viewWarehouse' || $this->uri->segment(1) == 'warehouse_storage_report') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Warehouse_Management'); ?></span></a>

                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(126,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('GenerateTods'); ?>"><?= lang('lang_Generate_Tods'); ?> </a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(127,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('showtods'); ?>"><?= lang('lang_Show_Tod'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(84,$LeftFmenuArr) == 'Y') { ?>

                                    <li><a href="<?= base_url('addWarehouse'); ?>"><?= lang('lang_Add_Warehouse'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(85,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewWarehouse'); ?>"><?= lang('lang_View_Warehouse'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(147,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('warehouse_storage_report'); ?>"><?= lang('lang_Warehouse_Storage_Report'); ?></a></li>
                                <?php } ?>
                            </ul> 
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(17,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'shownewmanifestRequest' || $this->uri->segment(1) == 'showpickuplist' || $this->uri->segment(1) == 'showmenifest' || $this->uri->segment(1) == 'manifestview' || $this->uri->segment(1) == 'showTicket' || $this->uri->segment(1) == 'show_assignedlist') echo 'class="active"'; ?>> <a href="#"><i class="icon-stack2"></i> <span><?= lang('lang_Manifest_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(49,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('shownewmanifestRequest'); ?>"><?= lang('lang_New_Manifest'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(50,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('showpickuplist'); ?>"><?= lang('lang_Pickup_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(51,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('showmenifest'); ?>"><?= lang('lang_Manifest_List'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(51,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('show_assignedlist'); ?>"><?= lang('lang_Show_Assigned_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(51,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url('Shipment_og/show_manifest'); ?>"><?= lang('lang_Show_Assigned_List'); ?></a></li>
                                <?php } ?>


                                <!-- <?php if (menuIdExitsInPrivilageArray_check(52,$LeftFmenuArr) == 'Y') { ?>
                                                    <li><a href="<?= base_url('showTicket'); ?>">Manifest Ticket</a></li>
                                <?php } ?> -->
                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>
                    <?php if (menuIdExitsInPrivilageArray_check(18,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'showTicketview') echo 'class="active"'; ?>> <a href="#"><i class="icon-ticket"></i> <span><?= lang('lang_Ticket_Management'); ?></span></a>
                            <ul>
                                <li><a href="<?= base_url('showTicketview'); ?>"><?= lang('lang_All_List'); ?></a></li>
                                <li><a href="<?= base_url('showTicket'); ?>"><?= lang('lang_Manifest_Ticket'); ?></a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(19,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'offerslist' || $this->uri->segment(2) == 'OfferOrders' || $this->uri->segment(2) == 'Addoffers' || $this->uri->segment(1) == 'OfferOrders' || $this->uri->segment(1) == 'OfferOrders_gift' || $this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList') echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span> <?= lang('lang_Offer_Management'); ?> </span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(86,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/offerslist'); ?>"><?= lang('lang_Bundel_Offers_List'); ?></a></li>
                                <?php } ?>  

                                <?php if (menuIdExitsInPrivilageArray_check(148,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/import_promo'); ?>"><?= lang('lang_Bulk_Import_Offers'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(87,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/Addoffers'); ?>"><?= lang('lang_Add_New_Bundle_Offers'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(88,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('OfferOrders'); ?>"><?= lang('lang_Bundle_Orders_List'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(86,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/giftOffersList'); ?>"><?= lang('lang_Gift_Offers_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(87,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Offers/AddofferGift'); ?>"><?= lang('lang_Add_New_Gift_Offers'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(88,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('OfferOrders_gift'); ?>"><?= lang('lang_Gift_Order_List'); ?></a></li>
                                <?php } ?>



                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>

                    <!-- <?php if (menuIdExitsInPrivilageArray_check(19,$LeftFmenuArr) == 'Y') { ?>
                                    <li <?php if ($this->uri->segment(2) == 'AddofferGift' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'giftOffersList' || $this->uri->segment(2) == 'edit_offer_gift') echo 'class="active"'; ?>> <a href="#"><i class="icon-gift"></i> <span> Gift Offer Management </span></a>
                                        <ul>

                        <?php if (menuIdExitsInPrivilageArray_check(86,$LeftFmenuArr) == 'Y') { ?>
                                                                <li><a href="<?= base_url('Offers/giftOffersList'); ?>"><?= lang('lang_Offers_List'); ?></a></li>
                        <?php } ?>
                        <?php if (menuIdExitsInPrivilageArray_check(87,$LeftFmenuArr) == 'Y') { ?>
                                                                <li><a href="<?= base_url('Offers/AddofferGift'); ?>"><?= lang('lang_Add_New_Offers'); ?></a></li>
                        <?php } ?>
                        <?php if (menuIdExitsInPrivilageArray_check(88,$LeftFmenuArr) == 'Y') { ?>
                                                                <li><a href="<?= base_url('OfferOrders'); ?>"><?= lang('lang_Orders_List'); ?></a></li>
                        <?php } ?>


                                        </ul>
                                    </li>
                        <?php
                        /**/
                    }
                    ?> -->

                    <?php if (menuIdExitsInPrivilageArray_check(138,$LeftFmenuArr) == 'Y') { ?>

                        <li <?php if ($this->uri->segment(2) == 'pickedSingleView' || $this->uri->segment(2) == 'pickedBatchView' || $this->uri->segment(2) == 'pickedcompletedView'  || $this->uri->segment(1) == 'picker_setting') echo 'class="active"'; ?>> <a href="#"><i class="icon-list2"></i> <span>   <?= lang('lang_Pickers_Management'); ?></span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(98,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url(); ?>Shipment_og/pickup_order" >Picking Order</a></li>

                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(230,$LeftFmenuArr) == 'Y') { ?>
                                    <li  <?php if ($this->uri->segment(1) == 'pickedSingleView') echo 'class="active"'; ?>> <a href="<?= base_url('pickedSingleView'); ?>"></i> <span> <?= lang('lang_Single_Pickup_List'); ?></span></a> </li>
                                    <li  <?php if ($this->uri->segment(1) == 'pickedBatchView') echo 'class="active"'; ?>> <a href="<?= base_url('pickedBatchView'); ?>"></i> <span><?= lang('lang_Batch_Pickup_List'); ?></span></a> </li>
                                    <li  <?php if ($this->uri->segment(1) == 'pickedcompletedView') echo 'class="active"'; ?>> <a href="<?= base_url('pickedcompletedView'); ?>"></i> <span><?= lang('lang_pickup_completed_list'); ?></span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(99,$LeftFmenuArr) == 'Y') { ?>

                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(100,$LeftFmenuArr) == 'Y') { ?>

                                <?php } ?> 

                                <?php if (menuIdExitsInPrivilageArray_check(139,$LeftFmenuArr) == 'Y') { ?>
                                                                    <!-- <li><a href="<?= base_url(); ?>users" id="layout2"><?= lang('lang_View_All_Users'); ?></a></li> -->
                                    <li><a href="<?= base_url(); ?>picker_setting" id="layout2"><?= lang('lang_Picker_Settings'); ?></a></li>
                                <?php } ?>


                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>

                    <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y' || menuIdExitsInPrivilageArray_check(230,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'packing' || $this->uri->segment(2) == 'packing_b2b') echo 'class="active"'; ?>> <a href="#"><i class="icon-package"></i> <span> <?= lang('lang_Packaging_Management'); ?> </span></a>
                            <ul>

                                <!-- <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y') { ?>
                                            <li <?php if ($this->uri->segment(1) == 'packing_report') echo 'class="active"'; ?>> <a href="<?= base_url('packing_report'); ?>"></i> <span><?= lang('lang_Packaging_Report'); ?></span></a> </li>
                                <?php } ?> -->
                                <?php if (menuIdExitsInPrivilageArray_check(162,$LeftFmenuArr) == 'Y') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'PackStatus') echo 'class="active"'; ?>> <a href="<?= base_url('PackStatus'); ?>"></i> <span> <?= lang('lang_Pack_Force'); ?></span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing') echo 'class="active"'; ?>> <a href="<?= base_url('packing'); ?>"></i> <span><?= lang('lang_Packaging'); ?></span></a> </li>
                                <?php } ?>
                                    <?php if (menuIdExitsInPrivilageArray_check(230,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing_ean') echo 'class="active"'; ?>> <a href="<?= base_url('packing_ean'); ?>"></i> <span><?= lang('lang_Packaging'); ?> EAN</span></a> </li>
                                      <li <?php if ($this->uri->segment(1) == 'packing_CPS') echo 'class="active"'; ?>> <a href="<?= base_url('packing_CPS'); ?>"></i> <span><?= lang('lang_Packaging'); ?> With serial</span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>

                                    <li <?php if ($this->uri->segment(1) == 'packing_new') echo 'class="active"'; ?>> <a href="<?= base_url('packing_new'); ?>"></i> <span><?= lang('lang_Packaging'); ?></span></a> </li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing_b2b') echo 'class="active"'; ?>> <a href="<?= base_url('packing_b2b'); ?>"></i> <span><?= lang('lang_Packaging'); ?> B2B</span></a> </li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing_tod') echo 'class="active"'; ?>> <a href="<?= base_url('packing_tod'); ?>"></i> <span><?= lang('lang_Packaging_With_Tod'); ?></span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(258,$LeftFmenuArr) == 'Y') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing_3pl') echo 'class="active"'; ?>> <a href="<?= base_url('packing_3pl'); ?>"></i> <span><?= lang('lang_Packaging'); ?> 3PL</span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(259,$LeftFmenuArr) == 'Y') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing_CPS_3pl') echo 'class="active"'; ?>> <a href="<?= base_url('packing_CPS_3pl'); ?>"></i>Packagin With Serial 3PL </span></a> </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>


                    <?php if (menuIdExitsInPrivilageArray_check(10,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(2) == 'dispatchtodeliver' || $this->uri->segment(2) == 'dispatch' || $this->uri->segment(2) == 'dispatch_b2b' || $this->uri->segment(2) == 'dispatch3pl') echo 'class="active"'; ?>> <a href="#"><i class="icon-car2"></i> <span>  <?= lang('lang_Dispatching_Management'); ?></span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(10,$LeftFmenuArr) == 'Y') { ?>
                                    <li  <?php if ($this->uri->segment(1) == 'dispatch') echo 'class="active"'; ?>> <a href="<?= base_url('dispatch'); ?>"></i> <span><?= lang('lang_Dispatching'); ?></span></a> </li>
                                <?php } ?>


                                <?php if (menuIdExitsInPrivilageArray_check(10,$LeftFmenuArr) == 'Y') { ?>
                                    <li  <?php if ($this->uri->segment(1) == 'dispatchtodeliver') echo 'class="active"'; ?>> <a href="<?= base_url('dispatchtodeliver'); ?>"></i> <span> <?= lang('lang_Dispatch_To_Delivered'); ?></span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(10,$LeftFmenuArr) == 'Y') { ?>
                                    <li  <?php if ($this->uri->segment(1) == 'dispatch_b2b') echo 'class="active"'; ?>> <a href="<?= base_url('dispatch_b2b'); ?>"></i> <span><?= lang('lang_Dispatching'); ?> B2B</span></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(10,$LeftFmenuArr) == 'Y') { ?>
            <!--                        <li  <?php if ($this->uri->segment(1) == 'dispatch3pl') echo 'class="active"'; ?>> <a href="<?= base_url('dispatch3pl'); ?>"></i> <span> <?= lang('lang_Dispatch_To_tpl'); ?></span></a> </li>-->
                                <?php } ?>



                            </ul>
                        </li>
                        <?php
                        /**/
                    }
                    ?>






                    <?php if (menuIdExitsInPrivilageArray_check(78,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                        <li <?php if ($this->uri->segment(1) == 'returnLM') echo 'class="active"'; ?>> <a href="<?= base_url('returnLM'); ?>"><i class="fa fa-arrow-left"></i> <span>RTF</span></a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(78,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                        <li <?php if ($this->uri->segment(1) == 'returnLM') echo 'class="active"'; ?>> <a href="<?= base_url('Shipment_og/return_order'); ?>"><i class="fa fa-arrow-left"></i> <span>RTF</span></a> </li>
                    <?php } ?>


                    <?php if (menuIdExitsInPrivilageArray_check(149,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                        <li <?php if ($this->uri->segment(1) == 'ReturnShipment') echo 'class="active"'; ?>> <a href="<?= base_url('ReturnShipment'); ?>"><i class="fa fa-arrow-left"></i> <span> <?= lang('lang_RTF_Bulk'); ?></span></a> </li>
                    <?php } ?>


                    <?php if (menuIdExitsInPrivilageArray_check(4,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Seller' || $this->uri->segment(1) == 'viewZoneclient' || $this->uri->segment(1) == 'addZoneclient') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-industry"></i> <span><?= lang('lang_Seller_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(44,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Seller/add_view'); ?>" id="layout1"><?= lang('lang_Add_Seller'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(45,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Seller'); ?>" id="layout2"><?= lang('lang_view_All_sellers'); ?></a></li>
                                <?php } ?>
                                <li><a href="<?= base_url('addZoneclient'); ?>"  id="layout2"><?= lang('lang_Add_Seller_Zone'); ?> </a></li>
                                <li><a href="<?= base_url('viewZoneclient'); ?>" id="layout2"><?= lang('lang_View_Seller_Zone'); ?></a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(5,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'add-new-user' || $this->uri->segment(1) == 'users' || $this->uri->segment(1) == 'update-user' || $this->uri->segment(1) == 'user-privilege' || $this->uri->segment(1) == 'user-details') echo 'class="active"'; ?>> <a href="#"><i class="icon-users"></i> <span><?= lang('lang_Users_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(47,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url(); ?>users" id="layout2"><?= lang('lang_View_All_Users'); ?></a></li>
                                    <li><a href="<?= base_url(); ?>add-new-user" id="layout1"><?= lang('lang_Add_User'); ?></a></li>
                                <?php } ?>



                            </ul>
                        </li>
                    <?php } ?>

                    <!-- <?php if (menuIdExitsInPrivilageArray_check(20,$LeftFmenuArr) == 'Y') { ?>
                                <li <?php if ($this->uri->segment(1) == 'add_storage' || $this->uri->segment(1) == 'view_storage' || $this->uri->segment(1) == 'setStorageRate') echo 'class="active"'; ?>> <a href="#"><i class="icon-copy"></i> <span><?= lang('lang_Storage_Management'); ?></span></a>
                                    <ul> -->
                        <!--  <?php if (menuIdExitsInPrivilageArray_check(53,$LeftFmenuArr) == 'Y') { ?>
                                             <li><a href="<?= base_url('add_storage'); ?>"><?= lang('lang_Add_Storage_Type'); ?></a></li>
                        <?php } ?> -->

                        <!--  <?php if (menuIdExitsInPrivilageArray_check(55,$LeftFmenuArr) == 'Y') { ?>
                                             <li><a href="<?= base_url('setStorageRate'); ?>"><?= lang('lang_SetStorage_Rate'); ?></a></li>
                        <?php } ?> -->
                        <!-- </ul>
                    </li>
                    <?php } ?> -->
                    <?php if (menuIdExitsInPrivilageArray_check(21,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'addfinancecategory' || $this->uri->segment(1) == 'viewfinancecategory' || $this->uri->segment(1) == 'viewfixrateCharges' || $this->uri->segment(1) == 'sellerCharges' || $this->uri->segment(1) == 'storageInvoices' || $this->uri->segment(1) == 'PickupchargesInvocie' || $this->uri->segment(1) == 'ordersinvoiceView' || $this->uri->segment(1) == 'transaction_report' || $this->uri->segment(1) == 'editcatview' || $this->uri->segment(1) == 'invoices_dynamic' || $this->uri->segment(1) == 'newinvoicesView' || $this->uri->segment(1) == 'createInvoice3pl') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-money"></i> <span><?= lang('lang_Finance_Management'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(56,$LeftFmenuArr) == 'Y') { ?>
                                                  <!-- <li><a href="<?= base_url('addfinancecategory'); ?>">Add Category</a></li>-->
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(57,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewfixrateCharges'); ?>"><?= lang('lang_Set_Fixed_Rate_Charges'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(58,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('sellerCharges'); ?>"><?= lang('lang_Set_Dynamic_Rate_Charges'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(57,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewfinancecategory'); ?>"><?= lang('lang_All_Category'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(59,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('storageInvoices'); ?>"><?= lang('lang_StorageChargesInvoices'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(60,$LeftFmenuArr) == 'Y') { ?>
                                    <!-- <li><a href="<?= base_url('PickupchargesInvocie'); ?>">Pickup Charges Invoice</a></li>-->
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(60,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('ordersinvoiceView'); ?>"><?= lang('lang_All_Invoices'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(61,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('transaction_report'); ?>"><?= lang('lang_Transaction_Report'); ?></a></li> 
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(61,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('newinvoicesView'); ?>"><?= lang('lang_Fix_Rate_Invoice'); ?></a></li>
                                <?php } ?> 

                                <?php if (menuIdExitsInPrivilageArray_check(61,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('invoices_dynamic'); ?>"><?= lang('lang_Dynamic_Invoice'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(54,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('view_storage'); ?>"><?= lang('lang_All_Storage_Types'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(141,$LeftFmenuArr) == 'Y') {
                                    ?>
                                    <li><a href="<?= base_url('cancelOrder'); ?>"><?= lang('lang_Cancel_Order'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(54,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('createInvoice'); ?>"><?= lang('lang_Create_Lm_Invoice'); ?></a></li>
                                    <li><a href="<?= base_url('createInvoice3pl'); ?>"><?= lang('lang_Create_Lm_Invoice'); ?> By 3PL</a></li>


                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(54,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('viewLmInvoice'); ?>"><?= lang('lang_View_Lm_Invoice'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(166,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('createInvoiceAuto'); ?>"> <?= lang('lang_Create_Invoice_Auto'); ?></a></li>
                                <?php } ?>

                            </ul>
                        </li>
                    <?php } ?>




                    <?php if (menuIdExitsInPrivilageArray_check(131,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'show_route' || $this->uri->segment(1) == 'add_route') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-map"></i> <span><?= lang('lang_Route_Management'); ?> </span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(132,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_route'); ?>"><?= lang('lang_Add_Route'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(133,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('show_route'); ?>"><?= lang('lang_Show_Route'); ?></a></li>
                                <?php } ?>







                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(23,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php
                        if ($this->uri->segment(1) == "Courier" || $this->uri->segment(1) == "addZone" || $this->uri->segment(1) == "viewZone" || $this->uri->segment(1) == "Webhook") {
                            echo 'class="active"';
                        }
                        ?>> 
                            <a href="#"><i class="fa fa-truck"></i> <span><?= lang('lang_Courier_Service_Management'); ?></span></a>
                            <ul>
                                <li <?php
                                if ($this->uri->segment(1) == "viewCourierCompany") {
                                    echo 'class="active"';
                                }
                                ?>>
                                    <a href="<?= base_url('viewCourierCompany'); ?>" ><?= lang('lang_View_Courier_Company'); ?></a></li>


                                <li>  <a href="<?= base_url('addZone'); ?>" ><?= lang('lang_Add_Zone'); ?></a></li>
                                <li> <a href="<?= base_url('viewZone'); ?>" ><?= lang('lang_View_Zone'); ?></a></li>
                                <li><a href="<?= base_url('ShipmentLogview'); ?>"><?= lang('lang_Shipment_Log'); ?></a></li>
                                <li><a href="<?= base_url('ReverseShipmentLog'); ?>"> <?= lang('lang_Reverse_Shipment_Log'); ?></a></li>

                                <?php if (menuIdExitsInPrivilageArray(260) == 'Y') { ?>
                                    <li><a href="<?= base_url('Webhook'); ?>"><?='Webhook Activity' ?></a></li>
                                <?php } ?>

                            </ul>
                        </li>
                    <?php } ?>


                    <?php if (menuIdExitsInPrivilageArray_check(79,$LeftFmenuArr) == 'Y') { ?>
                        <li <?php if ($this->uri->segment(1) == 'Country') echo 'class="active"'; ?>> <a href="#"><i class="fa fa-map"></i> <span><?= lang('lang_Location'); ?></span></a>
                            <ul>

                                <?php if (menuIdExitsInPrivilageArray_check(89,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/ViewCountrylist'); ?>"><?= lang('lang_Location_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(90,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/Importlocations'); ?>"><?= lang('lang_import_location'); ?></a></li>
                                    <li><a href="<?= base_url('Country/CountraddForm'); ?>"><?= lang('lang_Add_Country'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(91,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/Importdeliverycity'); ?>"><?= lang('lang_import_delivery_City'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(91,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Country/Delivery_city_list'); ?>"><?= lang('lang_Delivery_Company_List'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(91,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('import_from_master'); ?>"><?= lang('lang_add_from_Master'); ?></a></li>
                                <?php } ?>


                            </ul>
                        </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(108,$LeftFmenuArr) == 'Y') { ?>
                        <li> <a href="#"><i class="icon-users"></i> <span><?= lang('lang_FAQ'); ?></span></a>
                            <ul>
                                <?php if (menuIdExitsInPrivilageArray_check(111,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url(); ?>add_faq" id="layout1"><?= lang('lang_Add_FAQ'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(112,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url(); ?>show_faq" id="layout2"><?= lang('lang_Show_FAQ'); ?></a></li>  
                                <?php } ?>
                            </ul>
                        </li>  
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(24,$LeftFmenuArr) == 'Y') { ?>

                        <li <?php if ($this->uri->segment(1) == 'add_template' || $this->uri->segment(1) == 'show_template' || $this->uri->segment(1) == 'bulksms') echo 'class="active"'; ?> >
                            <a href="javascript: void(0);">
                                <i class="fa fa-envelope-open"></i>
                                <span><?= lang('lang_Sms_Management'); ?></span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                                <?php if (menuIdExitsInPrivilageArray_check(100,$LeftFmenuArr) == 'Y') { ?>
                                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('add_template'); ?>"><?= lang('lang_Add_Template'); ?></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(100,$LeftFmenuArr) == 'Y') { ?>
                                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('show_template'); ?>"><?= lang('lang_Show_Template'); ?></a> </li>
                                <?php } ?>


                                <?php if (menuIdExitsInPrivilageArray_check(264,$LeftFmenuArr) == 'Y') { ?>
                                    
                                    <li class="nav-item"><a class="nav-link" href="<?= base_url('bulksms');?>">Bulk SMS</a></li>
                                <?php } ?>


                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(109,$LeftFmenuArr) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'shelve_report' || $this->uri->segment(1) == 'topdispatchproduct' || $this->uri->segment(1) == 'performance' || $this->uri->segment(1) == 'client_report' || $this->uri->segment(1) == 'dispatching_report' || $this->uri->segment(1) == 'InboundRecord' || $this->uri->segment(1) == 'report_3pl' || $this->uri->segment(1) == 'view_damage_inventory' || $this->uri->segment(1) == 'delivered' || $this->uri->segment(1) == 'returned' || $this->uri->segment(1) == 'packing_serial') echo 'class="active"'; ?>>
                            <a href="javascript: void(0);">
                                <i class="icon-copy"></i>
                                <span><?= lang('lang_Reports_Management'); ?></span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                                <?php if (menuIdExitsInPrivilageArray_check(113,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>

                                    <li><a href="<?= base_url('shelve_report'); ?>"><?= lang('lang_Shelve_Report'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(113,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>

                                    <li><a href="<?= base_url('shelve_report_new'); ?>"><?= lang('lang_Shelve_Report'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(114,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('topdispatchproduct'); ?>"><?= lang('lang_Top_Product_Dispatch'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(115,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a href="<?= base_url('performance'); ?>" ><?= lang('lang_OFD_Report'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(116,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a href="<?= base_url('Staff_report'); ?>" ><?= lang('lang_Staff_performance'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(117,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a href="<?= base_url('dispatching_report'); ?>" > <?= lang('lang_Dispatching_Report'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(118,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a href="<?= base_url('report_3pl'); ?>" ><?= lang('lang_TPL_Report'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(9,$LeftFmenuArr) == 'Y') { ?>
                                    <li <?php if ($this->uri->segment(1) == 'packing_report') echo 'class="active"'; ?>> <a href="<?= base_url('packing_report'); ?>"></i> <span><?= lang('lang_Packaging_Report'); ?></span></a> </li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(83,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('ItemInventory/ViewTotalInventory'); ?>"><?= lang('lang_Item_Inventory_total'); ?></a></li>
                                <?php } ?>

                                <?php if (menuIdExitsInPrivilageArray_check(32,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('InboundRecord'); ?>"><?= lang('lang_Inbound_Record'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(76,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                                    <li><a href="<?= base_url('ItemInventory/historyview'); ?>"><?= lang('lang_Inventory_History'); ?></a></li>

                                    <li><a href="<?= base_url('view_damage_inventory'); ?>"> <?= lang('lang_Damage_Inventory_History'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(76,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                                    <li><a href="<?= base_url(); ?>stockInventory/activityhistory" ><?= lang('lang_Inventory_History'); ?></a></li>
                                    <li><a href="<?= base_url(); ?>stockInventory/stockhistory" > <?= lang('lang_Seller_Stock_History'); ?></a></li>

                                    <li><a href="<?= base_url('damage_inventory_history'); ?>"> <?= lang('lang_Damage_Inventory_History'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(76,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('warehouse_storage_report'); ?>"><?= lang('lang_Warehouse_Storage_Report'); ?></a></li>
                                    <li><a href="<?= base_url('Reports/storage_report'); ?>"><?= lang('lang_Storage_Report'); ?></a></li>


                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(154,$LeftFmenuArr) == 'Y') { ?>
        <!-- <li><a href="<?= base_url('Bulkdownload'); ?>">Bulk Shipment Report</a></li> -->
                                <?php } ?>
                                <li><a href="<?= base_url('Bulkdownload'); ?>"><?= lang('lang_Bulk_Shipment_Report'); ?> </a></li>

                                <?php if (menuIdExitsInPrivilageArray_check(104,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a   href="<?= base_url(); ?>delivered" ><?= lang('lang_Delivered'); ?></a> </li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(105,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a   href="<?= base_url(); ?>returned"  ><?= lang('lang_Returned'); ?> </a> </li>
                                <?php } ?>
                                    
                                    <?php if (menuIdExitsInPrivilageArray_check(256,$LeftFmenuArr) == 'Y') { ?>
                                    <li> <a   href="<?= base_url(); ?>packing_serial"  >Serial Packaging Report </a> </li>
                                <?php } ?>
                                <li> <a   href="<?= base_url(); ?>courierHealthReport" > <?= lang('lang_3PL_courier_Company_Reports'); ?></a> </li>                           



                            </ul>
                        </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(110,$LeftFmenuArr) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'add_access_template' || $this->uri->segment(1) == 'show_access_template') echo 'class="active"'; ?>>
                            <a href="javascript: void(0);">
                                <i class="fa fa-star"></i>
                                <span> <?= lang('lang_Access_Management'); ?></span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                                <?php if (menuIdExitsInPrivilageArray_check(120,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('add_access_template'); ?>"><?= lang('lang_New_Template'); ?></a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(121,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('show_access_template'); ?>"><?= lang('lang_Show_Template'); ?></a></li>
                                <?php } ?>



                            </ul>
                        </li>
                    <?php } ?>
                        
                         <?php if (menuIdExitsInPrivilageArray_check(233,$LeftFmenuArr) == 'Y') { ?>
                        <li  <?php if ($this->uri->segment(1) == 'Package') echo 'class="active"'; ?>>
                            <a href="javascript: void(0);">
                                <i class="fa fa-list"></i>
                                <span> Packages</span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">

                                <?php if (menuIdExitsInPrivilageArray_check(234,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Package/add'); ?>">Add</a></li>
                                <?php } ?>
                                <?php if (menuIdExitsInPrivilageArray_check(235,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Package/view_list'); ?>">List</a></li>
                                <?php } ?>
                                     <?php if (menuIdExitsInPrivilageArray_check(236,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Package/package_assign'); ?>">Assign Customer</a></li>
                                <?php } ?>
                                    <?php if (menuIdExitsInPrivilageArray_check(237,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Package/assigned_list'); ?>">Assigned List</a></li>
                                <?php } ?>
                                     <?php if (menuIdExitsInPrivilageArray_check(238,$LeftFmenuArr) == 'Y') { ?>
                                    <li><a href="<?= base_url('Package/wallet_history'); ?>">Wallet History</a></li>
                                <?php } ?>



                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($this->session->userdata('user_details')['super_id'] == 175 || $this->session->userdata('user_details')['super_id'] == 6) { ?>
    <!--                        <li <?php if ($this->uri->segment(1) == 'Stocks' || $this->uri->segment(2) == 'bulk_location' || $this->uri->segment(1) == 'showStocklocation' || $this->uri->segment(2) == 'show_manifest' || $this->uri->segment(1) == 'stockInventory' || $this->uri->segment(1) == 'Shipment_og' || $this->uri->segment(1) == 'Backorder_new' || $this->uri->segment(1) == 'inventory_check_new') echo 'class="active"'; ?>>
                            <a href="javascript: void(0);">
                                <i class="fa fa-link"></i>
                                <span> New Process Link</span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">


                                <li><a href="<?= base_url(); ?>Stocks/generateStockLocation">Generate Stock location</a></li>
                                <li><a href="<?= base_url(); ?>Shipment_og/bulk_location" >Bulk Upload Location</a></li>
                                <li><a href="<?= base_url(); ?>print_stlocation" >Bulk Print Location</a></li>
                                <li><a href="<?= base_url(); ?>showStocklocation" >All Stock Location</a></li>
                                <li><a href="<?= base_url(); ?>showStocklocation/AS" >Assigned Stock Location</a></li>
                                <li><a href="<?= base_url(); ?>showStocklocation/UN" >Unassigned Stock Location</a></li>
                                <li><a href="<?= base_url(); ?>inventory_check_new">Inventory Per Client</a></li>
                                <li><a href="<?= base_url(); ?>Mergestock_new">Merge Stock</a></li>
                                <li><a href="<?= base_url(); ?>Stocks/bulk_upload" >Bulk inventory</a></li>
                                <li><a href="<?= base_url(); ?>Shipment_og/show_manifest" >Manifest List</a></li>
                                <li><a href="<?= base_url(); ?>stockInventory" >Show Inventory</a></li>
                                <li><a href="<?= base_url(); ?>stockInventory/activityhistory" >Inventory History</a></li>
                                <li><a href="<?= base_url(); ?>stockInventory/stockhistory" >Seller Stock History</a></li>
                                <li><a href="<?= base_url(); ?>stockInventory/recieveinventory" >Seller Inventory</a></li>
                                <li><a href="<?= base_url(); ?>Backorder_new">BackOrder</a> </li>
                                <li><a href="<?= base_url(); ?>Shipment_og/ordergeneratedView" >Order Generated</a></li>
                                <li><a href="<?= base_url(); ?>Shipment_og/pickup_order" >Picking Order</a></li>
                                <li><a href="<?= base_url(); ?>packing_new" >Packaging</a></li>
                                <li><a href="<?= base_url(); ?>Shipment_og/open_order" >Open Order</a></li>
                                <li><a href="<?= base_url(); ?>Shipment_og/return_order">Return Order</a></li>
                                <li><a href="<? //= base_url();   ?>damage_inventory_history">Damage Inventory History</a></li>
                                <li><a href="<? //= base_url();   ?>shelve_report_new">Shelve Report</a></li> 

                                


                            </ul>
                        </li>-->
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(248,$LeftFmenuArr) == 'Y') { ?>

                        <li>
                            <a href="javascript: void(0);">
                                <i class="fa fa-envelope-open"></i>
                                <span>Diggistores</span>
                                <span class="badge badge-danger badge-pill float-right"></span>
                            </a>
                            <ul class="nav-second-level" aria-expanded="false">
                                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('connect_seller'); ?>">Connect Seller</a> </li>
                                    <li class="nav-item"> <a class="nav-link" href="<?= base_url('viewStoreSeller'); ?>">View Connected Seller</a> </li>
                                    <!-- <li class="nav-item"> <a class="nav-link" href="<?= base_url('show_dgstores_orders'); ?>">Show Orders</a> </li> -->
                            </ul>
                        </li>
                        <?php } ?>
                    
                    <li><a href="<?= base_url('Country/cityList'); ?>"><i class="icon-city"></i> <span><?= lang('lang_City_List'); ?></span></a>    </li>
                    <?php if (menuIdExitsInPrivilageArray_check(222,$LeftFmenuArr) == 'Y') { ?>
                        <li>
                            <a href="<?= base_url('viewCourierCompanyPartner'); ?>" ><i class="fa fa-truck"></i> <span>Fastcoo Partners</span></a></li>
                    <?php } ?>


                    <?php if (menuIdExitsInPrivilageArray_check(151,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>Backorder" class="btn btn-danger" style=" margin-left: 2%; margin-right: 3%; "><span><?= lang('lang_Back_Order'); ?> </span>
   
                            </a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(151,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>Backorder_new" class="btn btn-danger" style=" margin-left: 2%; margin-right: 3%; "><span><?= lang('lang_Back_Order'); ?></span>

                            </a> </li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(80,$LeftFmenuArr) == 'Y' && $this->system_type == 'old') { ?>
                        <li > <a type="button" href="<?= base_url(); ?>ordergenerated" class="btn btn-warning" style="margin-left: 2%; margin-right: 3%"><span><?= lang('lang_OrderGenerated'); ?></span>
   
                            </a> </li>


                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(80,$LeftFmenuArr) == 'Y' && $this->system_type == 'new') { ?>
                        <li > <a type="button" href="<?= base_url(); ?>Shipment_og/ordergeneratedView" class="btn btn-warning" style="margin-left: 2%; margin-right: 3%"><span><?= lang('lang_OrderGenerated'); ?> </span>
   
                            </a> </li>


                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(13,$LeftFmenuArr) == 'Y') { ?>
                        <li > <a type="button" href="<?= base_url(); ?>orderCreated" class="btn btn-primary" style="margin-left: 2%; margin-right: 3%"><span><?= lang('lang_OrderCreated'); ?></span>
  
                            </a></li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(14,$LeftFmenuArr) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>pickupList" class="btn btn-warning" style="margin-left: 2%; margin-right: 3%"><span><?= lang('lang_Pick_List'); ?></span>
           </a> </li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(15,$LeftFmenuArr) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>packed" class="btn btn-info" style="margin-left: 2%; margin-right: 3%"><span><?= lang('lang_Packed'); ?> </span>
  
                            </a></li>
                    <?php } ?>

                    <?php if (menuIdExitsInPrivilageArray_check(16,$LeftFmenuArr) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>dispatched" class="btn btn-success" style=" margin-left: 2%; margin-right: 3%; "><span><?= lang('lang_Dispatched'); ?> </span>


                            </a></li>
                    <?php } ?>
                    <?php if (menuIdExitsInPrivilageArray_check(103,$LeftFmenuArr) == 'Y') { ?>
                        <li> <a type="button"  href="<?= base_url(); ?>delivery_manifest" class="btn btn-warning" style=" margin-left: 2%; margin-right: 3%; "><span><?= lang('lang_delivery_Manifest'); ?> </span>

                            </a> </li>
                    <?php } ?>
            
                 



                </ul>
            </div>
        </div>
        <!-- /main navigation --> 

    </div>
</div>
<!-- /main sidebar -->