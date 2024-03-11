<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/stocklocation.js"></script>

    </head>

    <body ng-app="stockLocationApp" >
        <?php $this->load->view('include/main_navbar'); ?>
        <?php
        if (!empty($type)) {
            $type = ",'" . $type . "'";
        }
        ?>
        <!-- Page container -->
        <div class="page-container"  ng-controller="stockLocation" ng-init="loadMore(1, 0 <?= $type; ?>);">

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
<?php if ($this->session->flashdata('msg')): ?>
    <?= '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <?= '<div class="alert alert-danger">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php endif; ?>

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat"  >
                            <div class="loader logloder" ng-show="loadershow"></div>
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?php if ($type1 == 'AS') echo 'Assigned ';
                        else if ($type1 == 'UN') echo 'Unassigned';
                        else echo 'Show'; ?> <?= lang('lang_Stock_Location'); ?> </strong>
<!--                                    <a  ng-click="ExportExcelitemshelve();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>&nbsp;&nbsp;-->
                                    <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;"></i></a>
                                    <a  ng-click="GetexportExcelStocklocation();" >   
                                        <i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> 

                                    <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                        <option value="" selected><?= lang('lang_select_export_limit'); ?></option>
                                        <option ng-repeat="exdata in dropexport" value="{{exdata.k}}" >{{exdata.j}}-{{exdata.k}}</option>  
                                    </select> 
                                    
                                </h1>

                                <hr>
                            </div>
                            <div class="panel-body" >

                                <!-- Dashboard content -->

                                <!-- /dashboard content -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <table class="table table-bordered table-hover" style="width: 100%;">
                                        <!-- width="170px;" height="200px;" -->
                                        <tbody >
                                            <tr style="width: 80%;">
                                                <td><div class="form-group" ><strong><?= lang('lang_Stock_Location'); ?>:</strong>
                                                        <input type="text" id="s_type_val" name="s_type_val"  ng-model="filterData.stock_location"  class="form-control" placeholder="Stock Location">
                                                    </div></td>
                                                <td><select  id="seller" data-show-subtext="true" data-live-search="true" name="seller"  ng-model="filterData.seller_id" multiple  class="selectpicker" data-width="100%" >
                                                        <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                        <?php foreach ($sellers as $seller_detail): ?>
                                                            <option value="<?= $seller_detail->id; ?>">
                                                                <?= $seller_detail->company; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select></td>
                                                <td><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                <td><button  class="btn btn-danger" ng-click="loadMore(1, 1<?= $type; ?>);" ><?= lang('lang_Search'); ?></button>
                                                 <a  class="btn btn-warning ml-10 " ng-click="printlabelsku();" ng-if="Items.length!=0" > Print </a>
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                               <th><input type="checkbox"  ng-model="selectedAll"  ng-change="selectAll();"> &nbsp;&nbsp;<?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Stock_Location'); ?> (Barcode)</th>
                                                 <th><?= lang('lang_Stock_Location'); ?> (QR-code)</th>
                                                <th><?= lang('lang_Seller'); ?></th>

<!-- <th>Category</th> --> 

                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                           <td><input type="checkbox" value="{{data.stock_location}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" > &nbsp; {{$index + 1}}</td>
                                            <td><img src="{{data.barcode}}"/><br>
                                                {{data.stock_location}}</td>
                                            <td><img src="{{data.qrcode}}"/><br>
                                                {{data.stock_location}}</td>
                                            <td><span class="label label-info">{{data.company}}</span></td>
                                        </tr>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0<?= $type; ?>);" ng-init="count = 1"><?= lang('lang_LoadMore'); ?></button>



                                </div>
                                <hr>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div style="display:none;">
                        <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable">
                            <thead>
                                <tr>

                                    <th><?= lang('lang_Stock_Location'); ?></th>


<!-- <th>Category</th> --> 

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">

                                <td><img src="{{data.barcode}}"/><br>
                                    {{data.stock_location}}</td>

                            </tr>
                        </table>

                    </div>
                     <div id="printlabelsize" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color: #f3f5f6;">
                                    <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Size_to_download'); ?></h4></center>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form method="post" action="<?= base_url(); ?>shelve/barcodebulkprint" target="_blank" >
                                    <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group ">
                                            <input type="text"  name="sizeh" class="form-control" ng-model="barcode.sizeh" placeholder="Height" required="">
                                        </div> 
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <input type="text"  name="sizew" class="form-control" ng-model="barcode.sizew" placeholder="Width" required=""> 

                                        </div> 
                                    </div>
                                    <div class="col-sm-4" style="display:none;"> 
                                        <div class="form-group">

                                            <select id="size_val" class="form-control"  name="size_type"  required="required">
                                                <!-- <option value="">Select Size Type</option> -->
                                                <option value="inch">inch</option>
                                            </select>
                                        </div> 
                                    </div>

                                        <div class="row" style="padding-left: 70%;">   

                                        <!-- <a  class="btn btn-info pull-left" ng-click="bulkprintsku(listData2, listData1.exportlimit);" > <?= lang('lang_Print_Barcode'); ?> </a> -->
                                            <!-- <button type="button" class="btn btn-info pull-left ng-scope"  ng-click="bulkprintStocksku();"><?= lang('lang_Print_Barcode'); ?></button>
                                           -->
                                           <input type="hidden"  name="stocklocationval" id="stocklocationval" class="form-control" value="" > 
                                           <button type="submit" class="btn btn-info pull-left ng-scope"  ><?= lang('lang_Print_Barcode'); ?></button>
                                        </div>
                                        <div class="row" style="padding-left: 70%;">   
                                            <button type="submit"  name="print_type" value="qrcode" class="btn btn-danger"  >Print QR code</button>
                                        </div>                                    

                                    </div>
                                    <span class="text-danger"><strong>Note:</strong> Please enter sizes in inches.</span>
                                    </form>
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

    </div>
    <!-- /page container --> 


    <script>
        $(document).ready(function () {
            var table = $('#example').DataTable({});
        });


        function printPage()
        {
            var divToPrint = document.getElementById('printTable');
            var htmlToPrint = '' +
                    '<style type="text/css">' +
                    'table {' +
                    'border:1px solid #000;' +
                    '}' +
                    'table th, table td {' +
                    'width:1200px' +
                    '}' +
                    'table th, table td {' +
                    'padding:8px;' +
                    '}' +
                    'table th {' +
                    'padding-top: 12px;' +
                    'padding-bottom: 12px;' +
                    ' text-align: left;' +
                    'padding:0.5em;' +
                    '}' +
                    '</style>';
            htmlToPrint += divToPrint.outerHTML;
            newWin = window.open("");
            newWin.document.write(htmlToPrint);
            newWin.print();
            newWin.close();
        }
    </script>
</body>
</html>