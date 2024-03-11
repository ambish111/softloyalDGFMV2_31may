<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/angular/returnlm_new.app.js?v=' . time()); ?>"></script>

    </head>
    <body ng-app="ReturnLmApp" >
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
                                    <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?= lang('lang_Return_To_Fulfilment'); ?></span> </h4>
                                </div>
                            </div>
                        </div>
                        <!-- Content area -->
                        <div class="">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h5 class="panel-title" dir="ltr"><?= lang('lang_Return_To_Fulfilment'); ?> <a href="<?= base_url(); ?>Shipment_og/return_order" class="btn btn-danger pull-right text-white mt-5"><?= lang('lang_Cancel'); ?></a> </h5>
                                </div>
                                <div class="panel-body">
                                    <div class="loader logloder" ng-show="loadershow"></div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <param name="SRC" value="y" />
                                                    <div style="display:none">
                                                        <audio id="audio" controls>
                                                            <source src="<?= base_url('assets/apx_tone_alert_7.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                        <audio id="audioSuccess" controls>
                                                            <source src="<?= base_url('assets/filling-your-inbox.mp3'); ?>" type="audio/ogg">
                                                        </audio>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <input type="text" id="" my-enter="scan_awb();" ng-disabled="awbcolmunBtn" ng-model="scan.slip_no"class="form-control" placeHolder='<?= lang('lang_AWB'); ?>'' />
                                                        </div>
                                                    </div>



                                                    <div class="col-md-3" ng-show="remarkBox">
                                                        <div class="form-group">
                                                            <textarea type="text"  ng-model="scan.remarkbox" class="form-control" placeHolder='Remarks' /></textarea>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="col-md-1">
                                                       
                                                        <div class="form-group">
                                                            <input type="button" ng-click="finishScan();" ng-disabled="btnfinal" value='<?= lang('lang_Verify'); ?>' class="btn btn-primary" />
                                                        </div>
                                                    </div>
                                                   
                                                    <!-- -->



                                                    <div class="col-lg-12">
                                                        <div ng-if="completeArray.length > 2" class="alert alert-danger"> <?= lang('lang_Please_Verify_the_Packing_Limit_Exceed'); ?>! </div>
                                                        <div ng-if='warning' class="alert alert-warning">{{warning}} </div>

                                                        <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group"> &nbsp; </div>
                                                    </div>
                                                </div>
                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>
                                            </div>

                                        </div>
                                        <div class="col-md-9" ng-show="tableshow">

                                            <table class="table table-striped table-bordered table-hover" id="show_messanger_print1">
                                                <thead>
                                                    <tr>
                                                        <th class="head1">Sr.No.</th>
                                                        
<!--                                                        <th class="head1">Item Image</th>-->
                                                        <th class="head1">SKU</th>
                                                        <th class="head0">SKU Size</th>
                                                        <th class="head1">Piece</th>
                                                        
                                                        <th class="head0">Stock Location</th>
                                                        <th class="head1">In Stock</th>
                                                        <th class="head0">Damage</th>
                                                        <th class="head1">Missing</th>
                                                            

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr   ng-repeat="data in shipData">
                                                        <td style="width:1%;">{{$index + 1}}</td>
                                                       
<!--                                                        <td style="width:8%;"> <img ng-if="data.item_path != ''" src="<?= base_url(); ?>{{data.item_path}}" width="80">
                                                            <img ng-if="data.item_path == ''" src="<?= base_url(); ?>assets/nfd.png" width="100">
                                                        </td>-->
                                                        <td style="width:10%;"><span class="label label-warning">{{data.sku}}</span></td>
                                                         <td style="width:1%;"><span class="badge badge badge-pill badge-success" >{{data.sku_size}}</span></td>
                                                        <td style="width:2%;"><span class="badge badge badge-pill badge-info" >{{data.piece}}</span></td>
                                                       
                                                        
                                                       <td style="width:12%;"><input ng-if="data.scaned == 0" type="text" my-enter="GetcheckStocklocation(shipData[$index],$index);" class="form-control" ng-model="shipData[$index].stock_location">
                <span ng-if="data.scaned == 1" class="badge badge badge-pill badge-success" >{{data.stock_location}}</span>
                <i class="fa fa-undo" ng-if="data.scaned == 1"   ng-click="openStockLocation($index)" style="margin-top: 13px;" ></i> </td>
                                                        <td style="width:1%;"><span class="badge badge badge-pill badge-success" >{{data.in_stock}}</span></td>
                                                        
                                                        
                                                        <td style="width:8%;">
                                                            <input ng-if="data.scaned_d==1"  type="number" min="0" ng-keydown="Getchekmess();"  class="form-control" ng-model="shipData[$index].damage">
                                                            
                                                            <span ng-if="data.scaned_d == 0"  class="badge badge badge-pill badge-danger" >{{data.damage}}</span>
                                                            <i ng-if="data.scaned_d == 0 && shipData[$index].damage>0" class="fa fa-undo" ng-click="openmissing($index,'D')" style="margin-top: 13px;" ></i>
                                                            <br>
                                                            <span style="cursor:pointer;" ng-if="data.scaned_d == 1 && shipData[$index].damage>0" ng-click="GetCheckmissingpiece(shipData[$index],$index,'Damage');"  class="badge badge badge-pill badge-warning" >Update</span>
                
                
                                                           
                                                           </td>
                                                           <td style="width:8%;"> <input ng-if="data.scaned_m == 1" ng-keydown="Getchekmess();" type="number" min="0"  class="form-control" ng-model="shipData[$index].missing">
                                                         <span ng-if="data.scaned_m == 0" class="badge badge badge-pill badge-danger" >{{data.missing}}</span>
                                                         <i ng-if="data.scaned_m == 0 && shipData[$index].missing>0" class="fa fa-undo" ng-click="openmissing($index,'M')" style="margin-top: 13px;" ></i>
                                                         <br>
                                                          <span style="cursor:pointer;" ng-if="data.scaned_m == 1 && shipData[$index].missing>0" ng-click="GetCheckmissingpiece(shipData[$index],$index,'Missing');"  class="badge badge badge-pill badge-warning" >Update</span>
                                                        
                                                        </td>
                                                      

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="col-md-3" ng-show="tableshow">

                                            <table class="table table-striped table-bordered table-hover" id="show_messanger_print1">
                                                <thead>
                                                    <tr>
                                                         <th class="head1">SKU</th>
                                                       <th class="head0">Scan Location</th>
                                                        <th class="head1">In Stock</th>
                                                       
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    
                                                    <tr   ng-repeat="data in shipData">
                                                       <td><span ng-if="data.local_type=='New'" class="label label-success">{{data.local_type}}</span>
                                                       <span ng-if="data.local_type=='Old'" class="label label-warning">{{data.sku}}</span>
                                                       </td>
                                                        <td style="width:10%;">
                                                        <h6> <span class="badge badge badge-pill badge-warning" >{{data.st_location}}</span></h6>
                                                        </td>
                                                        <td><span class="badge badge-warning">{{data.in_stock_new}}</span></td>
                                                      

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="test_print" ></div>
