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
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/iteminventory.app.js?v=<?=time();?>"></script>
    </head>

    <body ng-app="Appiteminventory">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="Ctrstock_view" ng-init="loadMore(1, 0);">  

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
                                        <h1><strong>Inventory Out Of Stock</strong><a href="<?= base_url('Excel_export/shipments'); ?>"></a>

                                           
                                        
                                            <a  ng-click="ExportExcelitemInventory();" ><i class="icon-file-excel pull-right" style="font-size: 35px; margin-top:3px;"></i></a>
                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;" >
                                                <option value="" selected><?= lang('lang_select_export_limit'); ?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.k}}" >{{exdata.j}}-{{exdata.k}}</option>  

                                            </select> 
                                        </h1>
                                    </div>


                                  
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <!-- Quick stats boxes -->
                                    <div class="panel-body">
                                        <div class="col-lg-12 " style="padding-left: 20px;padding-right: 20px;"> 

                                            <!-- Today's revenue --> 

                                            <!-- <div class="panel-body" style="background-color: pink;"> -->

                                            <table class="table table-bordered table-hover" style="width: 100%;">
                                                <!-- width="170px;" height="200px;" -->
                                                <tbody >

                                                    <tr style="width: 80%;">
                                                        <td><div class="form-group" ><strong><?= lang('lang_SKU'); ?>:</strong>
                                                                <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                                                            </div></td>

                                                   
                                                     
                                                     <td ><div class="form-group" ><strong><?= lang('lang_Seller'); ?>:</strong> <br>
                                                                <select   ng-model="filterData.seller" class="selectpicker" data-show-subtext="true" data-live-search="true" data-width="100%" >
                                                                    <option value=""><?= lang('lang_Select_Seller'); ?></option>
                                                                    <?php foreach ($sellers as $seller_detail): ?>
                                                                        <option value="<?= $seller_detail->id; ?>">
                                                                            <?= $seller_detail->company; ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div></td>
                                                              <td ><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?= lang('lang_Search'); ?></button></td>
                                                     
                                                    </tr>
                                                 
                                                </tbody>
                                            </table>
                                            <br>
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
                                    <table class="table table-striped table-hover table-bordered dataTable" id="printTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th><?= lang('lang_Seller'); ?></th>
                                                <th><?= lang('lang_Item_Type'); ?></th>
                                                <th><?= lang('lang_Item_Image'); ?></th>
<!--                                                <th><?= lang('lang_StorageType'); ?></th>-->
                                                <th><?= lang('lang_ItemSku'); ?></th>


<!--                                              <th><?= lang('lang_Warehouse'); ?></th>-->

                                                <th><?= lang('lang_Quantity'); ?></th>

 </tr>
                                        </thead>
                                        <tbody id="">


                                            <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                                <td>{{$index + 1}}  </td>
                                                <td>{{data.seller_name}}</td> 
                                                <td><span class="badge badge-success" ng-if="data.type == 'B2B'">{{data.type}}</span><span class="badge badge-warning" ng-if="data.type == 'B2C'">{{data.type}}</span></td>
                                                <td><img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="80">
                                                    <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="80">
                                                </td>


<!--                                                <td>{{data.storage_id}}</td>-->
                                                <td>{{data.sku}}</td>



<!--                                                <td ng-if="data.wh_id > 0">{{data.wh_name}}</td>
                                                <td ng-if="data.wh_id == '0'">--</td>-->

                                                <td><span class="badge badge-success">0</span></td>
                                             
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
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

        </div>
      
       

      

      
    </body>
</html>
