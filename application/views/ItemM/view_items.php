<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js?auth=<?= time(); ?>"></script>

    </head>

    <body ng-app="Appiteminventory" ng-controller="CTR_itemviewpage">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" >

            <!-- Page content -->
            <div class="page-content" ng-init="loadMore(1, 0)">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        <!--style="background-color: red;"-->


                        <?php
                        if (!empty($this->session->flashdata('errorA'))) {
                            // print_r($this->session->flashdata('errorA'));
                            foreach ($this->session->flashdata('errorA')['invalidW'] as $validAlert) {
                                echo '<div class="alert alert-warning">this warehouse "' . $validAlert . '" not valid   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['invalid'] as $validAlert) {
                                echo '<div class="alert alert-warning">this item  "' . $validAlert . '" not valid   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }


                            foreach ($this->session->flashdata('errorA')['emptyname'] as $validAlert) {
                                echo '<div class="alert alert-warning">row ' . $validAlert . ' Name  field are required.   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }

                            foreach ($this->session->flashdata('errorA')['emptysku'] as $validAlert) {
                                echo '<div class="alert alert-warning">row ' . $validAlert . ' Sku  field are required.  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['emptycapcity'] as $validAlert) {
                                echo '<div class="alert alert-warning">row ' . $validAlert . ' Capacity  field are required.  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }



                            foreach ($this->session->flashdata('errorA')['alreadyexits'] as $validAlert) {
                                echo '<div class="alert alert-warning">this sku no ' . $validAlert . '" already exits   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }

                            foreach ($this->session->flashdata('errorA')['invalidstorage'] as $validAlert) {
                                echo '<div class="alert alert-warning">invalid storage name ' . $validAlert . '   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['validrow'] as $validAlert) {
                                echo '<div class="alert alert-success">added row ' . $validAlert . '  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['validrow_update'] as $validAlert) {
                                echo '<div class="alert alert-success">updated row ' . $validAlert . '  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            
                        }
                        ?>
                        <?php if ($this->session->flashdata('msg')): ?>
                            <?= '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?> 
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <?= '<div class="alert alert-danger">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php endif; ?>


                        <!-- Basic responsive table -->
                        <div class="panel panel-flat"  >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong><?= lang('lang_Items_Table'); ?></strong>

                                    <a id="btnExport" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>



                                </h1>

                                <hr>

                            </div>

                            <div class="panel-body" >

                                <table class="table table-bordered table-hover" style="width: 100%;">
                                    <!-- width="170px;" height="200px;" -->
                                    <tbody >

                                        <tr style="width: 80%;">
                                            <td><div class="form-group" ><strong><?= lang('lang_SKU'); ?>:</strong>
                                                    <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                </div></td>

                                            <td ><div class="form-group" ><strong><?= lang('lang_Name'); ?>:</strong>
                                                    <input type="text"  id="name" name="name"  ng-model="filterData.name" class="form-control" placeholder="Enter Name">
                                                </div></td>
                                            <td ><div class="form-group" ><strong><?= lang('lang_Storage_Capacity'); ?>:</strong>
                                                    <input type="text"  id="sku_size"name="sku_size"  ng-model="filterData.sku_size" class="form-control" placeholder="Enter Storage Capacity">
                                                </div></td>
                                            <td ><div class="form-group" ><strong><?= lang('lang_StorageType'); ?>:</strong> <br>
                                                    <select  id="storage_id" name="storage_id" ng-model="filterData.storage_id" class="selectpicker" data-width="100%" >
                                                        <option value=""><?= lang('lang_SelectStorageType'); ?></option>
                                                        <?php foreach ($StorageType as $storage_detail): ?>
                                                            <option value="<?= $storage_detail['id']; ?>">
                                                                <?= $storage_detail['storage_type']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div></td></tr>
                                        <tr style="width: 80%;">

                                            <td><div class="form-group" ><strong><?= lang('lang_warehouse'); ?>:</strong>
                                                    <?php
                                                    $warehouseArr = Getwarehouse_Dropdata();
                                                    ?>
                                                    <select  id="storagwh_ide_id" name="wh_id" ng-model="filterData.wh_id" class="selectpicker" data-width="100%" >
                                                        <option value=""><?= lang('lang_Selectwarehousename'); ?></option>
                                                        <?php foreach ($warehouseArr as $storage_detail): ?>
                                                            <option value="<?= $storage_detail['id']; ?>">
                                                                <?= $storage_detail['name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div></td>
                                            <td><div class="form-group" ><strong><?= lang('lang_warehouse'); ?>:</strong>
                                                    <?php
                                                    $warehouseArr = Getwarehouse_Dropdata();
                                                    ?>
                                                    <select  id="added_by" name="added_by" ng-model="filterData.added_by" class="selectpicker" data-width="100%" >
                                                        <option value=""><?= lang('lang_Added_By'); ?></option>
                                                        <option value="admin"> <?= lang('lang_Admin'); ?></option>
                                                        <?php
                                                        $sellerdrop = Getallsellerdata();
                                                        ?>
                                                        <?php foreach ($sellerdrop as $sdata): ?>
                                                            <option value="<?= $sdata['id']; ?>"><?= $sdata['name']; ?> </option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div></td>

                                            <td><div class="form-group" ><strong><?= lang('lang_Description'); ?>:</strong>
                                                    <input type="text" id="item_description" name="description" ng-model="filterData.description"  class="form-control" placeholder="Enter Description.">
                                                </div></td>

                                            <td><div class="form-group" ><strong>Expire Block:</strong>

                                                    <select  id="expire_block" name="expire_block" ng-model="filterData.expire_block" class="selectpicker" data-width="100%" >
                                                        <option value="">Expire Block</option>
                                                        <option value="Y"> Yes</option>
                                                        <option value="N"> No</option>

                                                    </select>

                                                </div></td>



                                        </tr>
                                        <tr>

                                            <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                                                <td><div class="form-group" ><strong>EAN No.:</strong>
                                                        <input type="text" id="ean_no"name="ean_no" ng-model="filterData.ean_no"  class="form-control" placeholder="Enter EAN NO.">
                                                    </div></td>   
                                            <?php } ?>
                                            <td> <div class="form-group" ><strong><?= lang('lang_From'); ?>:</strong>
                                                    <input class="form-control date" placeholder="From" id="from" name="from" ng-model="filterData.from">

                                                </div></td>
                                            <td><div class="form-group" ><strong><?= lang('lang_To'); ?>:</strong>

                                                    <input class="form-control date" placeholder="To" id="to"name="to"  ng-model="filterData.to" class="form-control"> 

                                                </div></td>



                                            <td co><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?= lang('lang_Search'); ?></button></td>

                                        </tr>

                                    </tbody>
                                </table>

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered" id="example">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Created_Date'); ?></th>
                                                <th><?= lang('lang_Item_Image'); ?></th>
                                                <th><?= lang('lang_Item_Type'); ?></th>
                                                <th><?= lang('lang_Expire_Block'); ?></th>
                                                <th><?= lang('lang_Name'); ?></th>
                                                <th><?= lang('lang_item_sku'); ?></th>
                                                <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                                                    <th>EAN No.</th>
                                                <?php } ?>
                                                <th>Warehouse</th>
                                                <th><?= lang('lang_StorageType'); ?></th>
                                                <th><?= lang('lang_Capacity'); ?></th>
                                                <th><?= lang('lang_Less_Quantity'); ?></th>
                                                <th><?= lang('lang_Expiry_Days'); ?></th>
                                                <th><?= lang('lang_Color'); ?></th>
                                                <th><?= lang('lang_Length'); ?></th>
                                                <th><?= lang('lang_Width'); ?></th>
                                                <th><?= lang('lang_Height'); ?></th>
                                                <th><?= lang('lang_Weight'); ?>(KG)</th>
                                                <th><?= lang('lang_Added_By'); ?></th>


                                                <th><?= lang('lang_Description'); ?></th>

 <!-- <th>Category</th> -->
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-if="shipData != 0" ng-repeat="data in shipData">
                                                <td>{{$index + 1}}</td>
                                                <td><span ng-if="data.entry_date!='0000-00-00 00:00:00'">{{data.entry_date}}</span>
                                                    <span ng-if="data.entry_date=='0000-00-00 00:00:00'">--</span>
                                                </td>
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="65">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="65"></td>
                                                <td>
                                                    <span class="badge badge-success" ng-if="data.type == 'B2B'">{{data.type}}</span>
                                                    <span class="badge badge-warning" ng-if="data.type == 'B2C'">{{data.type}}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-success" ng-if="data.expire_block == 'Y'">Yes</span>
                                                    <span class="badge badge-warning" ng-if="data.expire_block == 'N'">No</span>
                                                </td>
                                                <td><a href="<?= base_url(); ?>Item/edit_view/{{data.id}}">{{data.name}}</a></td>
                                                <td>{{data.sku}}</td>
                                                <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                                                    <td>{{data.ean_no}}</td>
                                                <?php } ?>
                                                <td>{{data.wh_name}}</td>
                                                <td>{{data.storage_type}}</td>
                                                <td>{{data.sku_size}}</td>

                                                <td>{{data.less_qty}}</td>
                                                <td>{{data.alert_day}}</td>
                                                <td style="background-color: {{data.color}};"></td>
                                                <td>{{data.length}}</td>
                                                <td>{{data.width}}</td>
                                                <td>{{data.height}}</td>
                                                <td>{{data.weight}} </td>
                                                <td>{{data.seller_name}} </td>

                                                <td>{{data.description}}</td>



                                                <td class="text-center">
                                                    <ul class="icons-list">
                                                        <li class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                <i class="icon-menu9"></i>
                                                            </a>

                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <li><a href="<?= base_url(); ?>Item/edit_view/{{data.id}}"><i class="icon-pencil7"></i> <?= lang('lang_Edit'); ?> </a></li>
                                                                <li><a ng-click="getallskubarcodeform(data.sku);"><i class="icon-file-pdf"></i> <?= lang('lang_Print_Barcode'); ?></a></li>
                                                                <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                                                                    <li><a ng-click="getallskubarcodeform(data.ean_no);"><i class="icon-file-pdf"></i> Print Barcode EAN</a></li>
                                                                <?php } ?>

                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>


                                </div>
                                
                                <button ng-hide="shipData.length < 20 || shipData.length == totalCount || shipData == 0" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                <hr>
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
        <div style="display:none;">
            <table id="downloadtable">
                <thead>
                    <tr>
                        <th><?= lang('lang_SrNo'); ?>.</th>

                        <th><?= lang('lang_Item_Type'); ?></th>
                        <th><?= lang('lang_Expire_Block'); ?></th>
                        <th><?= lang('lang_Name'); ?></th>
                        <th><?= lang('lang_item_sku'); ?></th>
                        <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                            <th>EAN No.</th>
                        <?php } ?>
                        <th><?= lang('lang_StorageType'); ?></th>
                        <th><?= lang('lang_Capacity'); ?></th>
                        <th><?= lang('lang_Less_Quantity'); ?></th>
                        <th><?= lang('lang_Expiry_Days'); ?></th>
                        <th><?= lang('lang_Color'); ?></th>
                        <th><?= lang('lang_Length'); ?></th>
                        <th><?= lang('lang_Width'); ?></th>
                        <th>Weight</th>
                        <th><?= lang('lang_Height'); ?></th>

                        <th><?= lang('lang_Description'); ?></th>

                    </tr>
                </thead>
                <tbody>
                    <tr ng-if="shipData != 0" ng-repeat="data in shipData">
                        <td>{{$index + 1}}</td>

                        <td>
                            <span class="badge badge-success" ng-if="data.type == 'B2B'">{{data.type}}</span>
                            <span class="badge badge-warning" ng-if="data.type == 'B2C'">{{data.type}}</span>
                        </td>
                        <td>
                            <span class="badge badge-success" ng-if="data.expire_block == 'Y'"><?= lang('lang_Yes'); ?></span>
                            <span class="badge badge-warning" ng-if="data.expire_block == 'N'"><?= lang('lang_No'); ?></span>
                        </td>
                        <td>{{data.name}}</td>
                        <td>{{data.sku}}</td>
                        <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                            <td>{{data.ean_no}}</td>
                        <?php } ?>
                        <td>{{data.storage_type}}</td>
                        <td>{{data.sku_size}}</td>

                        <td>{{data.less_qty}}</td>
                        <td>{{data.alert_day}}</td>
                        <td style="background-color: {{data.color}};"></td>
                        <td>{{data.length}}</td>
                        <td>{{data.width}}</td>
                        <td>{{data.weight}}</td>
                        <td>{{data.height}}</td>

                        <td>{{data.description}}</td>




                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal fade bd-example-modal-lg" tabindex="-1" id="showskuformviewid"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">


                        <h5 class="modal-title" id="exampleModalLabel" dir="ltr">Print Barcode</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>


                    </div>


                    <div class="modal-body">
                        <form novalidate ng-submit="myForm.$valid && GetGenratebarcode()" name="myForm" >
                            <div class="alert alert-warning" ng-if="message" >{{message}}</div>
                            <table class="table table-bordered table-hover" style="width: 100%;">
                                <!-- width="170px;" height="200px;" -->
                                <tbody >

                                    <tr style="width: 80%;">

                                        <td>
                                            <div class="form-group" ><strong><?= lang('lang_Sellers'); ?>:</strong>
                                                <select id="seller_id" name="seller_id" ng-model="filterData.seller_id" class="form-control"> 
                                                    <option value=""><?= lang('lang_Select_Seller'); ?></option>
                                                    <option ng-repeat="sdata in showdropsellerArr"  value="{{sdata.id}}">{{sdata.name}}</option>
                                                </select>
                                                <span class="error" ng-show="myForm.seller_id.$error.required"> <?= lang('lang_PleaseSelectSeller'); ?></span>
                                            </div>
                                        </td>



                                        <td>
                                            <div class="form-group" ><strong><?= lang('lang_QTY'); ?>:</strong>
                                                <input type="text" id="sqty" name="sqty" ng-model="filterData.sqty" class="form-control"> 
                                                <span class="error" ng-show="myForm.sqty.$error.required"> <?= lang('lang_PleaseEnterQTY'); ?> </span>
                                            </div>
                                        </td>


                                        <td><button type="button" class="btn btn-success" ng-click="myForm.$valid && GetGenratebarcode()" style="margin-left: 7%"><?= lang('lang_Generate'); ?></button></td>



                                    </tr>


                                </tbody>
                            </table>
                        </form>  
                        <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;" ng-show="tableshow"></i></a>
                        <form method="post" action="<?= base_url(); ?>Item/GetprintBarcode" target="_blank">
                            <input type="submit" class="btn btn-success pull-right" ng-show="tableshow" value="Download">
                            <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" ng-show="tableshow">
                                <thead>
                                    <tr>

                                        <th><?= lang('lang_Barcode'); ?></th>




                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="data2 in showbarcode">




                                        <td><input type="hidden" name="skus[]" ng-model="data2.sku" value="{{data2.sku}}"><img src="{{data2.barcode}}"/><br>
                                            {{data2.sku}}</td>




                                    </tr>
                                </tbody>
                            </table>
                        </form> 
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= lang('lang_Close'); ?></button>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /page container -->
        <script type="text/javascript">

            $('.date').datepicker({

            format: 'yyyy-mm-dd'

            });
        </script>
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
                                                var todaysDate = 'Items Table ' + new Date();
                                                        var blobURL = tableToExcel('downloadtable', 'test_table');
                                                        $(this).attr('download', todaysDate + '.xls')
                                                        $(this).attr('href', blobURL);
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
                                                                                            '}' +   'table th, table td {' +      'padding:8px;' +
                '}' +
                        'table th {' +
                        'padding-top: 12px;'+
                        'padding-bottom: 12px;'+
                        ' text-align: left;'+
       
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