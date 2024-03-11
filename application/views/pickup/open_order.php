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
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/openorder.app.js?v=<?= time(); ?>"></script>

    </head>

    <body ng-app="OpenorderApp" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="scanShipment" <?php if(!empty($slip_no)){echo 'ng-init="Geturlcheck(&QUOT;'.$slip_no.'&QUOT;);"';} ?>>

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
                                    <h5 class="panel-title">Open Order</h5>

                                </div>
                                <div class="panel-body">
                                    <div class="row">


                                        <div class="col-md-12" ng-show="PrintBtnallAwb">
                                            <div class="panel panel-default">
                                                <div class="panel-body">
                                                    <form action="<?= base_url(); ?>PickUp/BulkPrintAllLabels_p" target="_blank" method="post">
                                                        <input type="hidden" name="PrintAllAWB" value="{{awbArray_print}}">

                                                        <input type="submit" class="btn btn-primary pull-right" value="Print All AWB" >
                                                    </form></div>
                                            </div>






                                        </div>   
                                        <div class="col-lg-12">

                                            <div class="panel panel-default">

                                                <div class="panel-body">

                                                    <div class="loader logloder" ng-show="loadershow"></div>
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


                                                            <input type="text" ng-disabled="awbInputdis" my-enter="scan_awb();" ng-model="scan.slip_no" class="form-control" placeHolder='AWB' />
                                                        </div>
                                                    </div>




                                                    <div class="col-md-3">

                                                        <div class="form-group">


                                                            <input type="button" ng-disabled="Btnverify" ng-click="finishScan();" value='<?= lang('lang_Verify'); ?> 'class="btn btn-primary" />
                                                        </div>


                                                    </div> 



                                                    <div class="col-lg-12">

                                                        <div ng-if="completeArray.length > 20" class="alert alert-danger"><?= lang('lang_Please_Verify_the_Packing_Limit_Exceed'); ?> ! </div>
                                                        <div ng-if='warning' class="alert alert-warning">{{warning}} </div>
                                                        <div ng-if='Message' class="alert alert-success">{{Message}} </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group"> &nbsp; </div>
                                                    </div></div>

                                                <div>&nbsp;</div>
                                                <div>&nbsp;</div>

                                            </div> 
                                            <!--contenttitle--> 
                                        </div>

                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-body"><?= lang('lang_Sort'); ?></div>
                                            </div>
                                            <div class="table-responsive" style="padding-bottom:20px;" >
                                                <table class="table table-striped table-bordered table-hover" id="show_messanger_print1">
                                                    <thead>
                                                        <tr>
                                                            <th class="head1"><?= lang('lang_SrNo'); ?>.</th>
                                                            <th class="head0"><?= lang('lang_AWB'); ?>.</th>
                                                            <th class="head1"><?= lang('lang_SKU'); ?>.</th>
                                                            <th class="head1">Capacity</th>
                                                            <th class="head0">Piece</th> 

                                                            <th class="head0">Stock Location</th>
                                                         
                                                            <th class="head0">Stock</th>


                                                        </tr>
                                                    </thead>
                                                    <tbody>


                                                  
                                                        

                                                       
        <tr   ng-repeat="data in shipData">
            
            <td>{{$index + 1}}</td>
            <td><span class="label label-primary">{{data.slip_no}}</span></td>
            <td><span class="label label-warning">{{data.sku}}</span></td>
            <td><span class="badge badge badge-pill badge-success" >{{data.sku_size}}</span></td>
            <td>  <span class="badge badge badge-pill badge-info" >{{data.piece}}</span></td>


            <td>
                <input ng-if="data.scan_s == 0" type="text" my-enter="GetcheckStocklocation(shipData[$index],$index);" class="form-control" ng-model="shipData[$index].stock_location">
                <span ng-if="data.scan_s == 1" class="badge badge badge-pill badge-success" >{{data.stock_location}}</span>
                <i class="fa fa-undo" ng-if="data.scan_s == 1"   ng-click="openStockLocation($index)" style="margin-top: 13px;" ></i> 
            </td>
            <td><span class="badge badge badge-pill badge-danger" >{{data.stock}}</span></td>



        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <div>&nbsp;</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="test_print" ></div>


