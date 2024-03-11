<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?=base_url();?>assets/js/angular/zid_pending.app.js?v=<?=time();?>"></script>


    </head>


    <body ng-app="ZidPendingAPP">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="ZidpendingCTRL">

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
                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?> 
                        
                        

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading"dir="ltr">
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Pending Zid Order</strong></h1>

                               
                                <hr>
                            </div>
                            
                            
                            <div class="loader logloder" ng-show="loadershow"></div>
                           
                            <div class="panel-body" >
                                <div class="alert alert-warning" ng-if="errorData.status=='emp'">{{errorData.mess}} <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                                  <div class="alert alert-warning" ng-if="errorData1.status=='error'">{{errorData1.message}} <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                                    <div class="alert alert-success" ng-if="errorData1.order.id>0">Order Found : {{errorData1.order.id}}  {{errorData1.order.order_status.name}}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                                   
                                     <div class="alert alert-warning" ng-if="showlog">{{showlog}} <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
                            
                                    
                                

                                <div class="col-md-3"><div class="form-group" ><strong><?= lang('lang_Seller'); ?>:</strong>
                                        <br>
                                        <select  id="seller" name="seller"  ng-model="filterData.seller"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                            <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                            <?php foreach ($sellers as $seller_detail): ?>
                                                <option value="<?= $seller_detail->id; ?>"><?= $seller_detail->company; ?></option>
                                            <?php endforeach; ?>

                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-3"> <div class="form-group" ><strong>Order Number:</strong>
                                        <input type="text" id="order_no"  name="order_no"  ng-model="filterData.order_no"  class="form-control" placeholder="Enter Order No.">



                                    </div></div>
                                <div class="col-md-2">
                                    <button  class="btn btn-danger" ng-click="GetSearchorder();" style="margin-top: 20px;" ><?= lang('lang_Search'); ?></button>
                                </div>
                                  
                                  <div class="col-md-2" ng-if="errorData1.order.id>0">
                                      
                                      
                                      <button  class="btn btn-primary" ng-click="CreateOrderProcess();" style="margin-top: 20px;" >Create Order</button>
                                  </div>



                            </div>
                            <hr>
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

    </body>
</html>
