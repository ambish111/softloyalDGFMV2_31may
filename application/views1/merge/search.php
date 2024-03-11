<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
                    <link rel="stylesheet" href="https://rawgit.com/select2/select2/master/dist/css/select2.min.css">
        <script type="text/javascript" src="https://rawgit.com/select2/select2/master/dist/js/select2.js"></script>
         <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/merge.app.js?token=<?= time(); ?>"></script>

         <style>
            
            .select2-container--default .select2-selection--single .select2-selection__rendered {
    
    line-height: 13px !important;
}
        </style>
    </head>


    <body ng-app="Merge">
        <?php $this->load->view('include/main_navbar');
        ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="MergeStock">  

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

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat" >
                                    <div class="panel-heading" dir="ltr">
                                        <h1><strong>Merge Stock</strong>


                                        </h1>


                                    </div>
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 



                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >


                                                    <tr style="width: 80%;">


                                                        <td ><div class="form-group" ><strong><?= lang('lang_Seller'); ?>:</strong> <br>
                                                                <select  id="select_box" name="seller"  class="form-control" data-width="100%" ng-model="filterData.seller" >
                                                                    <option value=""><?= lang('lang_Select_Seller'); ?></option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>" >
                                                                            <?= $seller_detail->company; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>
                                                        <td><div class="form-group" ><strong><?= lang('lang_SKU'); ?>:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
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
                                                            </div></td>



                                                        <td><div class="form-group" ><strong><?= lang('lang_Shelve_No'); ?>:</strong>
                                                                <input type="text" id="shelve_no" name="shelve_no" ng-model="filterData.shelve_no"  class="form-control" placeholder="Enter Shelve No.">
                                                            </div></td>

                                                        <td><div class="form-group" ><strong><?= lang('lang_Stock_Location'); ?>:</strong>
                                                                <input type="text" id="shelve_no" name="stock_location" ng-model="filterData.stock_location"  class="form-control" placeholder="Enter Stock Location.">
                                                            </div></td>

                                                        <td><div class="form-group" ><strong><?= lang('lang_Warehouse'); ?>:</strong>
                                                                <input type="text" id="shelve_no" name="wh_name" ng-model="filterData.wh_name"  class="form-control" placeholder="Enter Warehouse.">
                                                            </div></td>
                                                    </tr>

                                                    <tr style="width: 100%;">	


                                                        <td><div class="form-group" ><strong><?= lang('lang_Expire_Date'); ?>:</strong>
                                                                <input  class="form-control date" id="expity_date" name="expity_date" ng-model="filterData.expity_date" autocomplete="off" > 

   <!--  <input type="date" id="expity_date" name="expity_date" ng-model="filterData.expity_date"  class="form-control" placeholder="Enter Expirty date.">-->
                                                            </div></td>  

                                                        <td><div class="form-group" ><strong><?= lang('lang_Expire_Status'); ?>:</strong>

                                                                <select  id="expiry" name="expiry" ng-model="filterData.expiry" class="selectpicker" data-width="100%" >
                                                                    <option value=""><?= lang('lang_Select_Status'); ?></option>
                                                                    <option value="Y"><?= lang('lang_YES'); ?></option>
                                                                    <option value="N"><?= lang('lang_NO'); ?></option>
                                                                </select>
                                                            </div></td>  
                                                        <td colspan="1"><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button></td>
                                                        <td colspan="1"> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" style="margin-left: 7%" ><?= lang('lang_Search'); ?></button></td>
                                                         <td colspan="1"> <button  class="btn btn-danger" ng-click="Getresetproces();" style="margin-left: 7%" >Reset</button></td>
                                                    </tr>
                                                    <!--<td colspan="2">
                                                      <div class="form-group" style="background-color: pink;"><strong><p style="text-align: center;" id="result"><?php //echo "Total ".count($items)." entries";      ?></p></strong>
                                                      style="background-color: pink;width: 80%;" 
                                                             
                                                        </div>
                                                    </td>--> 

                                                </tbody>
                                            </table>
                                            <br>
                                            <table class="table table-bordered table-hover" style="width: 100%;" ng-if="filterData.seller != null && filterData.sku != null && shipData != null">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >

                                                   
                                                    <tr>
                                                        <td colspan="3">
                                                           
                                                            <input type="text" placeHolder="Stock location"  ng-model="filterData.newstockLocation" class="form-control" my-enter="checkStockLocation()">

                                                            <!-- ng-if='stock_location_drop.length>0 && stock_location_drop[0].quantity>0'-->

                                                            <select   ng-model="filterData.stockId" class="form-control" ng-change="GetChnageLocationData();">

                                                                <option ng-repeat="stk in stock_location_drop" value="{{$index}}">{{stk.stock_location}} : quantity:{{stk.quantity}}</option>

                                                            </select>

                                                                          

                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th> Capacity</th><th>Quantity</th>
                                                    </tr> 
                                                    <tr>
                                                        <td><span class="badge badge-warning">{{shipData[0].sku_size}}</span></td>
                                                        <td><span class="badge badge-success" ng-if="MainupdatingArr.length>0">{{MainupdatingArr[0].FL_updating_qty}}</span><span class="badge badge-success" ng-if="MainupdatingArr.length==0">0</span></td>
                                                        <td>  <button ng-if="UpdatingLocation.length>0" confirmed-click="GetUpdateMergeData()"   ng-confirm-click="Are you sure want to update?"  class="btn btn-danger" style="margin-left: 7%" ng-disabled="merge_btn_disable" >Merge Qty</button>
                                                        <button ng-if="UpdatingLocation.length==0"  class="btn btn-danger" style="margin-left: 7%" ng-disabled="true" >Merge Qty</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div id="today-revenue"></div>
                                            <!-- </div> panel-body--> 

                                            <!-- /today's revenue --> 

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
                              <!-- <input type="text" value="{{data1.sku}}" id="check" style="display: none;" name="check" />
                                -->

                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Name'); ?></th>
                                                <th><?= lang('lang_Item_Type'); ?></th>
                                                <th><?= lang('lang_Capacity'); ?></th>
                                                <th><?= lang('lang_Quantity'); ?></th>
                                                <th><?= lang('lang_Item_Image'); ?></th>
                                                <th><?= lang('lang_StorageType'); ?></th>
                                                <th><?= lang('lang_ItemSku'); ?></th>
                                                <th><?= lang('lang_Stock_Location'); ?></th>
                                                <th colspan="2"><?= lang('lang_Shelve_No'); ?>.</th>
                                                <th><?= lang('lang_Warehouse'); ?></th>



                                                <th><?= lang('lang_Expire_Status'); ?></th>
                                                <th><?= lang('lang_Expire_Date'); ?></th>

                                            </tr>
                                        </thead>
                                        <tbody id="">


                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}} <input  disabled id="stockdisable_id_{{$index}}" type="checkbox" ng-model="selectedData[data.id]" ng-change="calculateQty($index,data);"> </td>
                                                <td>{{data.name}}</td>
                                                <td><span class="badge badge-success" ng-if="data.item_type == 'B2B'">{{data.item_type}}</span><span class="badge badge-warning" ng-if="data.item_type == 'B2C'">{{data.item_type}}</span></td>
                                                <td>{{data.sku_size}}</td>
                                                <td ><span class="badge badge-success">{{data.quantity}}</span></td>
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="100">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="100">
                                                </td>

                                                <td>{{data.storage_id}}</td>
                                                <td><a href="<?= base_url(); ?>ItemInventory/historyview/{{data.sku}}/{{data.seller_id}}">{{data.sku}}</a></td>
                                                <td>{{data.stock_location}}

                                                </td>
<!--                                                <td ng-if="data.stock_location == ''"><a ng-click="GetalluserLocationUpdate(data.sid, data.id);" class="label label-warning"><?= lang('lang_Update'); ?></a></td>-->
                                                <td ng-if="data.shelve_no" colspan="2">{{data.shelve_no}}</td>
                                                <td ng-if="!data.shelve_no" colspan="2">N/A</td>
                                                <td ng-if="data.wh_id > 0">{{data.wh_name}}</td>
                                                <td ng-if="data.wh_id == '0'">N/A</td>





                                                <td ng-if='data.expity_date_status == "Yes"'><span class="badge badge-danger"><?= lang('lang_YES'); ?></span></td>
                                                <td ng-if='data.expity_date_status == "No"'><span class="badge badge-success"><?= lang('lang_NO'); ?></span></td>
                                                <td>{{data.expity_date !== '0000-00-00' ? data.expity_date : "---"}}</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount || shipData.length==0" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                </div>
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


        <!-- /page container --> 
        <script>
            function printPage()
            {
                var divToPrint = document.getElementById('printTable');
                var htmlToPrint = '' +
                        '<style type="text/css">' +
                        'table th, table td {' +
                        'border:1px solid #000;' +
                        'width:1200px' +
                        '}' +
                        'table th, table td {' +
                        'border:1px solid #000;' +
                        'padding:8px;' +
                        '}' +
                        'table th {' +
                        'padding-top: 12px;' +
                        'padding-bottom: 12px;' +
                        ' text-align: left;' +
                        'border:1px solid #000;' +
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
        <script type="text/javascript">

            $('.date').datepicker({

                format: 'yyyy-mm-dd'

            });

        </script>

    </body>
</html>
