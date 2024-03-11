<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">


        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/dispatched.app.js"></script>
    </head>

    <body ng-app="Appdispatched" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="CTLRdelivered" ng-init="loadMore(1, 0);">

            <!-- Page content -->
            <div class="page-content">   
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" >
                        <!--style="background-color: red;"-->


                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <div class="loader logloder" ng-show="loadershow"></div>
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading"dir="ltr">
                                        <h1> <strong><?=lang('lang_Delivered');?></strong> 
                                            <!--  <a  ng-click="exportdispatchForLm();" >-->
                                            <a  ng-click="getExcelDetailsDelivered();" >
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>   

                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected><?=lang('lang_select_export_limit');?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  

                                            </select> 
<!--<a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> 
<!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a> --> 
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');   ?>" --> 
                                    <!-- href="<? //base_url('Pdf_export/all_report_view');   ?>" --> 
                                        <!-- Quick stats boxes -->
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 

                                                <!-- Today's revenue --> 

                                                <!-- <div class="panel-body" > -->
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Type');?>:</strong> <br>
                                                        <select  id="s_type" name="s_type" ng-model="filterData.s_type" class="selectpicker"  data-width="100%" >
                                                            <option value="AWB"><?=lang('lang_AWB');?></option>
<!--                                                            <option value="SKU">SKU</option>-->
                                                            <option value="REF"><?=lang('lang_Reference');?> #</option>
                                                            <option value="MOBL"><?=lang('lang_Mobile_No');?>.</option>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Search_value');?>:</strong>
                                                        <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.s_type_val"  class="form-control" placeholder="Enter AWB no.">
                                                        <!--  <?php // if($condition!=null): ?>
                                                              <input type="text" id="condition" name="condition" class="form-control" value="<?= $condition; ?>" >
                                                        <?php // endif; ?> --> 
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                                                        <select  id="seller" name="seller"  ng-model="filterData.seller" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_SelectSeller');?></option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <option value="<?= $seller_detail->id; ?>">
                                                                    <?= $seller_detail->company; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                                               <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_company');?>:</strong>
                                                        <br>
                                                        <?php
                                                        //$destData = getAllDestination();
                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?=lang('lang_Select_Company');?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_warehouse');?>:</strong> <br>
                                                        <?php
                                                        $warehouseArr = Getwarehouse_Dropdata();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-model="filterData.wh_id"  class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_Selectwarehousename');?></option>
                                                                <?php foreach ($warehouseArr as $data): ?>
                                                                <option value="<?= $data['id']; ?>">
                                                                <?= $data['name']; ?>
                                                                </option>
<?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                             
                                                    <div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_Destination'); ?> Country/HUB:</strong>
                                                        <br>
                                                        <?php
                                                        $destData = countryList();

                                                        //print_r($destData);
                                                        ?>
                                                        <select   ng-change="showCity();" ng-model="filterData.country"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                                            <option value=""><?= lang('lang_Select_Destination'); ?></option>
                                                            <?php foreach ($destData as $data) { ?>
                                                                <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div></div>
                                                    <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_Destination');?> City:</strong>
                                                        <br>
                                                       
                                                        <select   id="destination" name="destination"  multiple  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" ng-model="filterData.destination"   >
                       
                     <option ng-repeat="cData in citylist"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Exactdate');?>:</strong>
                                                        <input type="date" id="exact"name="exact" ng-model="filterData.exact"  class="form-control">
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input type="date" id="from"name="from" ng-model="filterData.from" class="form-control">
                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input type="date" id="to"name="to"  ng-model="filterData.to" class="form-control">
                                                    </div></div>
                                                     <div class="col-md-2"><div class="form-group" ><strong><?=lang('lang_Short_List');?>:</strong>
                                                        <select class="form-control"  ng-model="filterData.sort_list" ng-change="loadMore(1, 1);">

                                                            <option value=""><?=lang('lang_Short_List');?></option>


                                                            <option value="NO"><?=lang('lang_Newest_Order');?></option>
                                                        <option value="OLD"><?=lang('lang_Oldest_Order');?></option>
                                                        <option value="OBD"><?=lang('lang_Order_By_Date');?></option>
                                                            

                                                        </select>

                                                    </div></div>
                                                <div class="col-md-2"><div class="form-group" ><strong>Order Type:</strong>
                                                        <select class="form-control"  ng-model="filterData.order_type">
                                                            <option value="">Order Type</option>
                                                            <option  value="B2B">B2B</option>
                                                            <option  value="B2C">B2C</option>
                                                        </select>
                                                    </div></div>
                                                <div class="col-md-2"><div class="form-group" ><strong>Limit:</strong>
                                                        <select class="form-control"  ng-model="filterData.sort_limit" ng-change="loadMore(1, 1);">

                                                            <option value=""><?=lang('lang_Short');?></option>


                                                            <option ng-repeat="(key,value) in dropshort" value="{{key}}-{{value}}">{{value}}</option>

                                                        </select>

                                                    </div></div>
                                               
                                                    <div class="col-lg-12" style="padding-left: 0px;padding-right: 20px;"> <div class="form-group" > <button  class="btn btn-danger ml-10" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success ml-10"><?=lang('lang_Total');?>  <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                       
                                                    </div>
                                                </div>
                                                




                                            </div>
                                        </div>

                                        <!-- /quick stats boxes -->
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <tr>
                                            <th><?=lang('lang_SrNo');?>.

                                        <th><?=lang('lang_Order_Type');?></th>
                                        <th><?=lang('lang_AWB_No');?>.</th>
                                        <th><?=lang('lang_Ref_No');?>.</th>
                                        <th><?=lang('lang_Forwarded_Company');?></th>
                                        <th><?=lang('lang_Destination');?></th>
                                        <th><?=lang('lang_Receiver');?></th>
                                        <th><?=lang('lang_Receiver_Address');?></th>
                                        <th><?=lang('lang_Receiver_Mobile');?></th>
                                        <th><?=lang('lang_Item_Sku_Detail');?></th>
                                        <th><?=lang('lang_Payment_Mode');?></th>

                                        <th><?=lang('lang_Seller');?></th>
                                            <th><?=lang('lang_warehouse');?></th>
                                            <th><?=lang('lang_Date');?> </th>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}}
                                            </td>
                                            <td><span class="label label-success" ng-if="data.order_type == 'B2B'">{{data.order_type}}</span>
                                                <span class="label label-warning" ng-if="data.order_type == 'B2C'">{{data.order_type}}</span></td>


                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.booking_id}}</td>
                                            <td>{{data.cc_name}}</td>
                                            <td>{{data.destination}}</td>
                                            <td>{{data.reciever_name}}</td>
                                            <td>{{data.reciever_address}}</td>
                                            <td>{{data.reciever_phone}}</td>

                                            <td><a  ng-click="GetInventoryPopup(data.slip_no);"><span class="label label" style="background-color:<?= DEFAULTCOLOR; ?>;">Get Details</span></a></td>
                                              <td>{{data.mode}}
                                                <span ><br>({{data.total_cod_amt}})</span></td>  
                                           

                                            <td>{{data.sender_name}}</td> 
                                            <td > {{data.wh_id}}</td>
                                            <td>{{data.entrydate}}</td>
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1">Load More</button>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div id="deductQuantityModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger" style="background-color:<?= DEFAULTCOLOR; ?>;border-color:<?= DEFAULTCOLOR; ?>">
                                        <h6 class="modal-title">Item Sku Detail</h6>
                                        <button type="button" class="close" data-dismiss="modal">×</button>

                                    </div>

                                    <div class="modal-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                     <th><?=lang('lang_SKU');?> </th>
                                                      <th> <?=lang('lang_Gift_Item');?> </th>
                                                    <th><?=lang('lang_QTY');?></th>
                                    <th><?=lang('lang_Deducted_Shelve_NO');?></th>
                                                    <th>COD (<?= site_configTable("default_currency"); ?>)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr ng-repeat="dataship in shipData1">
                                                    <td><span class="label label-primary">{{dataship.sku}}</span></td>
                                                        <td><span  ng-if="dataship.free_sku=='N'" class="label label-warning">No</span> <span ng-if="dataship.free_sku=='Y'" class="label label-primary">Yes</span></td>
                                                    <td><span class="label label-info">{{dataship.piece}}</span></td>
                                                    <td><span class="label label-info">{{dataship.deducted_shelve}}</span></td>
                                                    <td><span class="label label-danger">{{dataship.cod}}</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>


                        </div>


                        <div id="excelcolumnDelivered" class="modal fade">   
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: #f3f5f6;">
                                        <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Column_to_download'); ?></h4></center>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">             
                                    <label class="container">

                                        <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>   <b><?= lang('lang_SelectAll'); ?></b>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>

                                <div class="col-md-12 row">
                                    <div class="col-sm-4">          
                                        <label class="container">  
                                            <input type="checkbox" name="Date" value="Date"    ng-model="listData2.entrydate"> <?= lang('lang_Date'); ?>
                                            <span class="checkmark"></span>
                                        </label>   
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Reference" value="Reference"   ng-model="listData2.booking_id"><?= lang('lang_Reference'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Shipper_Reference" value="Shipper_Reference"   ng-model="listData2.shippers_ref_no"> <?= lang('lang_shipper_Refrence'); ?> #
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="AWB" value="AWB"   ng-model="listData2.slip_no"> <?= lang('lang_AWB_No'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Origin" value="Origin"  ng-model="listData2.origin"> <?= lang('lang_Origin'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Destination" value="Destination"  ng-model="listData2.destination"> <?= lang('lang_Destination'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender" value="Sender"  ng-model="listData2.sender_name"><?= lang('lang_Sender'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender_Address" value="Sender_Address"   ng-model="listData2.sender_address"> <?= lang('lang_Sender_Address'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Sender_Phone" value="Sender_Phone"   ng-model="listData2.sender_phone"> <?= lang('lang_Sender_Phone'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Receiver" value="Receiver"   ng-model="listData2.reciever_name"> <?= lang('lang_Receiver_Name'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Recevier_Address" value="Recevier_Address"   ng-model="listData2.reciever_address"> <?= lang('lang_Receiver_Address'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Receiver_Phone" value="Receiver_Phone"   ng-model="listData2.reciever_phone"><?= lang('lang_Receiver_Mobile'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Mode" value="Mode"  ng-model="listData2.mode"> <?=lang('lang_Payment_Mode');?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Status" value="Status"  ng-model="listData2.delivered"><?= lang('lang_Main'); ?> <?= lang('lang_Status'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Status_O" value="Status_O"  ng-model="listData2.status_o"> 3PL Status
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="COD_Amount" value="COD_Amount"   ng-model="listData2.total_cod_amt"> <?= lang('lang_COD_Amount'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>




                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="UID_Account" value="UID_Account"  ng-model="listData2.cust_id"> <?= lang('lang_UID_Account'); ?>
                                            <span class="checkmark"></span> 
                                        </label>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Pieces" value="Pieces"  ng-model="listData2.pieces" > <?= lang('lang_Pieces'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Weight" value="Weight"  ng-model="listData2.weight" > <?= lang('lang_Weight'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="Description" value="Description"  ng-model="listData2.status_describtion" > <?= lang('lang_Description'); ?>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <!-- <div class="col-sm-4">
                                         <label class="container">
                                             <input type="checkbox" name="Forward_through" value="Forward_through"  ng-model="listData2.frwd_throw" > Forward through
                                             <span class="checkmark"></span> 
                                         </label>
                                     </div> -->
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="Forward_awb" value="Forward_awb"  ng-model="listData2.frwd_awb_no"> <?= lang('lang_Forwarded_AWB_No'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>  
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="transaction_no" value="transaction_no"  ng-model="listData2.transaction_no"> <?= lang('lang_Transaction_Number'); ?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="invoice_details" value="invoice_details"  ng-model="listData2.invoice_details"> <?=lang('lang_invoice');?> <?=lang('lang_Details');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">    
                                        <label class="container">
                                            <input type="checkbox" name="pl3_pickup_date" value="pl3_pickup_date"  ng-model="listData2.pl3_pickup_date"> <?=lang('lang_tpl_Pickup_Date');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="pl3_close_date" value="pl3_close_date"  ng-model="listData2.pl3_close_date"> <?=lang('lang_tpl_Closed_Date');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                  <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="transaction_days" value="transaction_days"  ng-model="listData2.transaction_days"> Transaction Days
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="no_of_attempt" value="no_of_attempt"  ng-model="listData2.no_of_attempt"> <?=lang('lang_No_of_Attempt');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="cc_name" value="cc_name"  ng-model="listData2.cc_name"> <?=lang('lang_Forwarded_Company');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="container">
                                            <input type="checkbox" name="close_date" value="close_date"  ng-model="listData2.close_date"> <?=lang('lang_close_date');?>
                                            <span class="checkmark"></span>    
                                        </label>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="last_status_n" value="last_status_n"  ng-model="listData2.last_status_n"> Last Status
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>



                                </div>
                                <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                    <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShipDelivered(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                </div>

                            </div>

                        </div>

                                            </div>
                                            <!-- <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                            <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                                <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShipDelivered(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
                                            </div> -->

                                        </div>

                                    </div>
                                </div>
                            </div>  


                        </div>   



                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content --> 


            <div style="display:none">
                <table class="table table-striped table-hover table-bordered dataTable bg-*" id="downloadtable" style="width:100%;">
                    <thead>
                        <tr>
                            <th><?=lang('lang_SrNo');?>.
                                <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" /></th>
                             <th><?=lang('lang_Order_Type');?></th>
                                                    <th><?=lang('lang_AWB_No');?>.</th>
                                                    <th><?=lang('lang_Ref_No');?>.</th>
                                                    <th><?=lang('lang_Destination');?></th>
                                                    <th><?=lang('lang_Receiver');?></th>
                                                    <th><?=lang('lang_Receiver_Address');?></th>
                                                    <th><?=lang('lang_Receiver_Mobile');?></th>
                            <th><?=lang('lang_Item_Sku_Detail');?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?=lang('lang_SKU');?> </th>
                                    <th><?=lang('lang_QTY');?></th>
                                            <th><?=lang('lang_COD');?> (<?= site_configTable("default_currency"); ?>)</th>

                                        </tr>
                                    </thead>
                                </table></th>
                              <!-- <th>Cartoon Sku#</th> --> 

<!-- <th>Cartoon Quantity</th> -->
                            <th>Seller</th>
                            <th>Date </th>
                        </tr>
                    </thead>
                    <tr ng-if='shipData1 != 0' ng-repeat="data in shipData1">
                        <td>{{$index + 1}}
                            <input type="checkbox" value="{{data.slip_no}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" /></td>
                        <td><span class="label label-success" ng-if="data.order_type == 'B2B'">{{data.order_type}}</span>
                            <span class="label label-warning" ng-if="data.order_type == 'B2C'">{{data.order_type}}</span></td>


                        <td>{{data.slip_no}}</td>
                        <td>{{data.booking_id}}</td>
                        <td>{{data.destination}}</td>
                        <td>{{data.reciever_name}}</td>
                        <td>{{data.reciever_address}}</td>
                        <td>{{data.reciever_phone}}</td>
                        <td><table class="table table-striped table-hover table-bordered dataTable bg-*">
                                <tbody>
                                    <tr ng-repeat="data1 in data.skuData">
                                        <td ><span class="label label-primary">{{data1.sku}}</span></td>
                                        <td><span class="label label-info">{{data1.piece}}</span></td>
                                        <td><span class="label label-danger">{{data1.cod}}</span></td>
                                    </tr>
                                </tbody>
                            </table></td>
                        <td>{{data.name}}</td>
                        <td>{{data.entrydate}}</td>
                    </tr>
                </table>
            </div>






            <script>

            var tableToExcel = (function() {
    var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
                                        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                                , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
                                return function(table, name) {
                                if (!table.nodeType) table = document.getElementById(table)
                                        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                                var blob = new Blob([format(template, ctx)]);
                                var blobURL = window.URL.createObjectURL(blob);
                                return blobURL;
                                }
                                })()

                                $("#btnExport").click(function () {
                        var todaysDate = 'Dispatched Details ' + new Date();                                        var blobURL = tableToExcel('downloadtable', 'test_table');
$(this).attr('download',todaysDate+'.xls')
$(this).attr('href',blobURL);
});


// "order": [[0, "asc" ]]
$('#s_type').on('change',function(){
//if($('#s_type').val()=="SKU"){
//$('#s_type_val').attr('placeholder','Enter SKU no.');
//}else 
    if($('#s_type').val()=="AWB"){
$('#s_type_val').attr('placeholder','Enter AWB no.');
}

});

     
            </script>
            
            <!-- /page container -->

    </body>
</html>
