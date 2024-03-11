<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
       

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
         <script src='<?=base_url();?>/assets/js/angular/logs.app.js?v=<?=time();?>'></script>
    </head>

    <body ng-app="LogsApp" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_view" ng-init="loadMore(1, 0);" >

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        

                        <div class="loader logloder" ng-show="loadershow"></div>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong>Salla Booked Quantity logs</strong>

                                         
                                        </h1>
                                    </div>
                               
                                  <div class="panel-body" >
                                           
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                <div class="col-md-3"><div class="form-group" ><strong>AWB No.:</strong>
                                                        <input  id="booking_id" name="booking_id"  ng-model="filterData.temp_order_no" class="form-control" placeholder="Enter AWB."> 

                                                    </div></div>
                                                <div class="col-md-3"><div class="form-group" ><strong>SKU:</strong>
                                                        <input   ng-model="filterData.sku" class="form-control" placeholder="Enter SKU."> 

                                                    </div></div>
                                                
                                              
                                             <div class="col-md-3"> 
                                                    <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" ng-model="filterData.entry_date_f" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date"   ng-model="filterData.entry_date_t" class="form-control"> 

                                                    </div>
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                 
                                                <div class="col-md-5"><div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?> <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                     
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
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>

                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.  </th>
                                               
                                                
                                                <th><?=lang('lang_AWBNo');?>.</th>
                                                <th>SKU</th>
                                                <th>Order QTY</th>
                                                 <th>Update QTY</th>
                                                  <th>log</th>
                                                    <th>log Date</th>
                                               
<!--                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
                                            </tr>  
                                        </thead>   
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}} </td>    
                                           
                                            <td>{{data.temp_order_no}}</td>
                                            <td>{{data.sku}}</td>
                                             <td>{{data.o_qty}}</td>
                                            <td>{{data.qty}}</td>
                                             <td>{{data.log}}</td>
                                              <td>{{data.entry_date}}</td>
                                            

                                        </tr>

                                    </table>
                                    <br>
                                    <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?=lang('lang_Load_More');?></button>
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
        <script type="text/javascript">

            $('.date').datepicker({

                format: 'yyyy-mm-dd'

            });


 $('.date_new').datepicker({

                format: 'yyyy-mm-dd'

            });

        </script>
    </body>
</html>
