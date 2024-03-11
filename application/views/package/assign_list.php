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

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/package.app.js?auth=<?= time(); ?>"></script>
    </head>

    <body ng-app="PackageApp" ng-controller="package_view_ctrl">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container"  ng-init="loadMore_assign(1, 0);" >

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
                        <?php if ($this->session->flashdata('msg')): ?>
                            <?= '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?> 
                        <?php elseif ($this->session->flashdata('error')): ?>
                            <?= '<div class="alert alert-danger">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; ?>
                        <?php endif; ?>

                        <div class="loader logloder" ng-show="loadershow"></div>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong>Assigned List</strong>
                                            <a  ng-click="getexport();" >   
                                                <i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a> 

                                            <select id="exportlimit" class="custom-select pull-right" ng-model="filterData.exportlimit" name="exprort_limit" required="" style="    font-size: 16px;padding: 5px;margin-right: 10px;"  >
                                                <option value="" selected><?= lang('lang_select_export_limit'); ?></option>
                                                <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  
                                            </select> 


<!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>-->

                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                    <!-- href="<? // base_url('Excel_export/shipments');        ?>" -->
                         <!-- href="<? //base_url('Pdf_export/all_report_view');        ?>" -->
                                        <!-- Quick stats boxes -->
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">


                                                <div class="col-md-3"> <div class="form-group" ><strong>Customer:</strong>
                                                        <select class="selectpicker" data-show-subtext="true" data-live-search="true"  ng-model="filterData.seller_id"data-width="100%">  <option value=""><?= lang('lang_Please_select_seller'); ?></option>
                                                            <?php
                                                            foreach (GetcustomerDropdata_wallet() as $seller):
                                                                ?>

                                                                <option value="<?= $seller['id'] ?>"><?= $seller['company']; ?>/<?= $seller['uniqueid'] ?></option>

                                                            <?php endforeach; ?>

                                                        </select>

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>Package:</strong>
                                                        <select  class="selectpicker" data-show-subtext="true" data-live-search="true" ng-model="filterData.p_id"data-width="100%">
                                                            <option value="">Select Package</option>
                                                            <?php
                                                            // print_r($sellers);
                                                            foreach ($packageArr as $data):
                                                                ?>

                                                                <option value="<?= $data['id'] ?>"><?= $data['name']; ?></option>

                                                            <?php endforeach; ?>
                                                                
                                                        </select>

                                                    </div></div>
                                                <div class="col-md-2" style="margin-top: 20px;">
                                                    <div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.status">
                                                            <option value="">Status</option>
                                                            <option  value="Y">Active</option>
                                                            <option  value="N">Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">





                                                <div class="col-md-5"><div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadMore_assign(1, 1);" ><?= lang('lang_Search'); ?></button>
<!--                                                        <button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>-->
                                                        
                                                        <button type="button" ng-click="getSyncpackage();" class="btn btn-success" style="margin-left: 7%">Sync Package</button>


                                                    </div>
                                                </div>


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

                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered" >
                                        <thead>
                                            <tr>
                                                <th><?= lang('lang_SrNo'); ?>.</th>
                                                <th>Customer Name</th>
                                                <th>Plan Name</th>
                                                <th>Total Orders</th>
                                                <th>Pending</th>
                                                <th>Validity</th>
                                                <th>Assign Date</th>
                                                <th>Start Date</th>
                                                <th>Expire Date</th>
                                                <th>Expire Status</th>
                                                <th>Status</th>
<!--                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-if="listdata != 0" ng-repeat="data in listdata">
                                                <td>{{$index + 1}}</td>
                                                <td>{{data.cust_name}}</td>
                                                <td>{{data.package_name}}</td>
                                                <td> <span class="badge badge-success">{{data.no_of_orders}}</span> </td>
                                                <td><span class="badge badge-warning">{{data.order_limit}}</span> </td>
                                                <td> <span class="badge badge-danger">{{data.validity_days}}</span> </td>
                                                <td>{{data.entry_date}}</td>
                                                <td>{{data.start_date}}</td>
                                                <td>{{data.expiry}}</td>
                                                <td><span class="badge badge-success" ng-if="data.expiry_status == 'Y'">Yes</span>
                                                    <span class="badge badge-warning" ng-if="data.expiry_status == 'N'">No</span>
                                                </td>
                                                <td><span class="badge badge-success" ng-if="data.status == 'Y'">Active</span>
                                                    <span class="badge badge-warning" ng-if="data.status == 'N'">Inactive</span>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>


                                </div>
                                <button ng-hide="listdata.length < 100 || listdata.length == totalCount || listdata == 0" class="btn btn-info" ng-click="loadMore_assign(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
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
