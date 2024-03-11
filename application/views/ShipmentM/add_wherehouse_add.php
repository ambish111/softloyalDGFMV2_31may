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
    </head>

    <body ng-app="fulfill">
 
        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_view"  >

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                    <div class="row" style="margin-top:10px">
                            
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?=lang('lang_Shipment_Forwarded');?></div>
                            </div>
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>
                           
                        </div>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong>Add Torod Address</strong>
                                        </h1>
                                        <!-- <div class="text-danger" style="padding-left:20px;"><strong>Note:</strong> 1. Warehouse Name field only charecter allow.<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Warehouse field allow Alfa Numeric.</div> -->
                                    </div>
                                    <div class="panel-body" >
                                        <div class="alert alert-danger">
                                            <strong>Note ! </strong><br>&nbsp;1. Warehouse Name field only charecter allow. <br>&nbsp;2. Warehouse field allow Alfa Numeric.<br>&nbsp;3. Please Enter Mobile No without Prefix 966 or etc.
                                        </div>
                                        <hr/>
                                        <form >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">

                                                <!-- Today's revenue -->

                                                <!-- <div class="panel-body" > -->
                                               
                                                <div class="col-md-3"> <div class="form-group" ><strong>Warehouse Name:</strong>
                                                        <input type="text" name="warehouse_name"  ng-model="filterData.warehouse_name"  class="form-control" placeholder="Enter warehouse_name">
                                                    </div></div>
                                               
                                                <div class="col-md-3"><div class="form-group" ><strong>Warehouse:</strong>
                                                        <input type="text" name="warehouse"  ng-model="filterData.warehouse" class="form-control" placeholder="Enter warehouse"> 
                                                    </div></div>
                                                    <div class="col-md-3"><div class="form-group" ><strong>Contact Name:</strong>
                                                        <input type="text" name="contact_name"  ng-model="filterData.contact_name" class="form-control" placeholder="Enter contact_name"> 
                                                    </div></div>
                                                    <div class="col-md-3"><div class="form-group" ><strong>Phone Number:</strong>
                                                        <input type="number" name="phone_number"  ng-model="filterData.phone_number" class="form-control" placeholder="Enter phone_number"> 
                                                    </div></div>

                                                    <div class="col-md-3"><div class="form-group" ><strong>Email:</strong>
                                                        <input  type="text" name="email"  ng-model="filterData.email" class="form-control" placeholder="Enter email"> 
                                                    </div></div> 
                                                    <div class="col-md-3"><div class="form-group" ><strong>Zip code:</strong>
                                                        <input type="number" name="zip_code"  ng-model="filterData.zip_code" class="form-control" placeholder="Enter zip_code"> 
                                                    </div></div> 
                                                    <div class="col-md-3"><div class="form-group" ><strong>Type:</strong>
                                                        <!-- <input   placeholder="Enter type">  -->
                                                        <select name="type"  ng-model="filterData.type" class="form-control">
                                                            <option value="address">Address</option>
                                                        </select>
                                                    </div></div>
                                                    <div class="col-md-3"><div class="form-group" ><strong> Address:</strong>
                                                        <input name="locate_address"  ng-model="filterData.locate_address" class="form-control" placeholder="Enter address"> 
                                                    </div></div>      
                                                <div class="col-md-8"><div class="form-group" >
                                                        <button  class="btn btn-primary" ng-click="add_wherehouse_address(filterData);" >Submit</button>
                                                       

                                                    </div>
                                                </div>

                                            </div>



                                        </div>

                                        <!-- /quick stats boxes -->
                                    </form> 
                                </div>
                            </div>
                        </div>
                    
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->


            </div>

        </div>
    </body>
</html>
