<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/split_order.app.js?v=<?= time(); ?>"></script>
    </head>

    <body ng-app="AppSplitOrder" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="SplitOrderCRTL" ng-init="loadMore('<?= $slip_no; ?>');"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content" > 



                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1> <strong>Split Order by SKU</strong> 
                                        </h1>
                                    </div>

                                    <div class="panel-body" >
                                        <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 


                                            <div class="col-lg-12" style="padding-left: 0px;padding-right: 20px;"> <div class="form-group" > <button  class="btn btn-success ml-10" ng-click="GetproceedNewOrder();" ng-if="totalArr.length>0" >Split Order</button>
                                                    <button type="button" class="btn btn-danger ml-10"><?= lang('lang_Total'); ?>  <span class="badge">{{shipData.length}}</span></button>
                                                    <button type="button" class="btn btn-warning ml-10">Selected <span class="badge">{{totalArr.length}}</span></button>



                                                </div>
                                            </div>





                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="loader logloder" ng-show="loadershow"></div>


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.
                                                <th>AWB No.</th>
                                                <th><?= lang('lang_Ref_No'); ?>.</th>
                                                <th><?= lang('lang_SKU'); ?></th>
                                                <th><?= lang('lang_Gift_Item'); ?>  </th>
                                                <th><?= lang('lang_QTY'); ?></th>
                                                <th>Back Reason</th>
                                                <td align="center"><?= lang('lang_Action'); ?> </td>
                                            </tr>
                                        </thead>
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData">
                                            <td>{{$index + 1}}</td>
                                            <td>{{data.slip_no}}</td>
                                            <td>{{data.booking_id}}</td>


                                            <td ><span class="label label-primary">{{data.sku}}</span></td>
                                            <td><span  ng-if="data.free_sku == 'N'" class="label label-warning">No</span> <span ng-if="data.free_sku == 'Y'" class="label label-primary">Yes</span></td>
                                            <td><span class="label label-info">{{data.piece}}</span></td>
                                            <td><span class="label label-danger">{{data.back_reason}}</span>


                                            <td align="center"><span ng-if="data.back_reason != 'Out Of Stock' && data.back_reason != 'Sku Not Available'" class="label label-success"><input type="checkbox" class="btn btn-info" ng-model="data.check_item" value="{{data.sku}}" ng-change="GecheckSkuDetails(data, $index);" ></span>

                                                <span ng-if="data.back_reason == 'Out Of Stock' || data.back_reason == 'Sku Not Available'">--</span></td>

                                        </tr>
                                    </table>

                                </div>
                                <hr>
                            </div>
                        </div>

                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area -->

                </div>
                <!-- /main content -->

            </div>
            <!-- /page content -->



        </div>



    </body>
</html>
