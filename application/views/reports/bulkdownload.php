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
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/bulk_report.app.js?v=<?= time(); ?>"></script>
    </head>

    <body ng-app="BulkreportApp" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container  ng-init="loadMore(1, 0);"-->
        <div class="page-container" ng-controller="BulkreportCtrl" > 

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
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <div class="loader logloder" ng-show="loadershow"></div>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong>Bulk Order Report</strong>

                                        </h1>
                                    </div>
                                    
                                    <form  id="exportfrom" name="exportfrom" action="<?=base_url();?>Bulkdownload/export" method="post" onsubmit="document.getElementById('Newaddfrm').disabled=true; processFormData();" target="_blank">

                                        <div class="panel-body" >
                                            <div class="alert alert-danger" ng-if="message">{{message}} </div>
                                           
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                 <div class="alert alert-warning">Please Note: You can Search AWB maximum 50 orders per Transaction. </div>
                                                 <div class="col-md-12"> 
                                                   
                                                    <div class="form-group" ><strong>Search AWB:</strong>
                                                       
                                                        <textarea id="slip_no" name="slip_no" ng-model="filterData.slip_no" rows="3" class="form-control">Search By AWB</textarea>
                                                    </div> 
                                                </div>
                                            

                                                <div class="col-md-3"><div class="form-group" ><strong><?= lang('lang_Seller'); ?>:</strong>
                                                        <br>
                                                        <select  id="seller" name="seller"  ng-model="filterData.seller" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?= lang('lang_SelectSeller'); ?></option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <option value="<?= $seller_detail->id; ?>"><?= $seller_detail->company; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_Destination'); ?> Country/HUB:</strong>
                                                        <br>
                                                        <?php
                                                        $destData = countryList();

                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="destination" name="destination"  ng-change="showCity();" ng-model="filterData.country"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                                                            <option value=""><?= lang('lang_Select_Destination'); ?></option>
                                                            <?php foreach ($destData as $data) { ?>
                                                                <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                                                            <?php } ?>

                                                        </select>
                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_Destination'); ?> City:</strong>
                                                        <br>

                                                        <select  id="city" name="city" multiple  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" ng-model="filterData.destination"   >

                                                            <option ng-repeat="cData in citylist"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                                                        </select>
                                                    </div></div>
                                            
                                          
                                               
                                               
                                                
                                                <div class="col-md-3"><div class="form-group" ><strong><?= lang('lang_Payment_Mode'); ?>:</strong><br/>

                                                        <select  id="mode" name="mode"  ng-model="filterData.mode"   class="form-control" data-width="100%" >

                                                            <option value=""><?= lang('lang_Select_Mode'); ?></option>
                                                            <option value="COD"><?= lang('lang_COD'); ?></option>
                                                            <option value="CC"><?= lang('lang_CC'); ?></option>



                                                        </select>
                                                    </div>  </div>
                                          
                                           
                                                <div class="col-md-3"> 
                                                    <div class="form-group" ><strong><?= lang('lang_From'); ?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.from" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong><?= lang('lang_To'); ?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.to" class="form-control"> 

                                                    </div>
                                                </div>

                                                <div class="col-md-3">  <div class="form-group" ><strong><?= lang('lang_Main'); ?>  <?= lang('lang_Status'); ?>:</strong>
                                                        <br>
                                                        <select  id="status" name="status" ng-model="filterData.status" class="selectpicker" multiple data-show-subtext="true" data-live-search="true" data-width="100%" >

                                                            <option value=""><?= lang('lang_Select_Status'); ?></option>
                                                            <?php
                                                            foreach ($status as $status_detail):
                                                                if ($status_detail->main_status == "3PL Updates") {
                                                                    continue;
                                                                    //$status_detail->code = "3PL";
                                                                }
                                                                ?>
                                                                <option value="<?= $status_detail->code; ?>"><?= $status_detail->main_status; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div> 
                                                </div>
                                                
                                                  <div class="col-md-3"><div class="form-group" ><strong>Back Order:</strong><br/>

                                                        <select  id="mode" name="mode"  ng-model="filterData.backorder"   class="form-control" data-width="100%" >

                                                            <option value="">Select Type</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>



                                                        </select>
                                                    </div>  </div>
                                                
                                                
                                                
                                                
                                                
                                          

                                          
                                                </div>
                                            
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                                
                                                 <div class="col-md-3"> 
                                                    <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.from_c" placeholder="From Close Date" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.to_c" placeholder="To Close Date" class="form-control"> 

                                                    </div>
                                                </div>


                                                <div class="col-md-3"> 
                                                    <div class="form-group" ><strong>Forward Date <?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.f_from" placeholder="From Forward Date" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Forward Date <?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.f_to" placeholder="To Forward Date" class="form-control"> 

                                                    </div>
                                                </div>
                                                
                                                  <div class="col-md-3"> 
                                                    <div class="form-group" ><strong>Dispatch Date <?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="dispatch_date_from" name="dispatch_date_from" ng-model="filterData.dispatch_date_from" placeholder="From Dispatch Date" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>Dispatch Date <?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="dispatch_date_to" name="dispatch_date_to"  ng-model="filterData.dispatch_date_to" placeholder="To Dispatch Date" class="form-control"> 

                                                    </div>
                                                </div>
                                                 <div class="col-md-3"> 
                                                    <div class="form-group" ><strong>COD Received DATE <?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="cod_received_date_f" name="cod_received_date_f" ng-model="filterData.cod_received_date_f" placeholder="From COD Received DATE" class="form-control"> 

                                                    </div> 
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" ><strong>COD Received DATE <?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="cod_received_date_t" name="cod_received_date_t"  ng-model="filterData.cod_received_date_t" placeholder="To COD Received DATE" class="form-control"> 

                                                    </div>
                                                </div>
                                                
                                                
                                                 <div class="col-md-3">
                                                    <div class="form-group" > <strong>Invoice:</strong>
                                                        <select class="form-control"  ng-model="filterData.product_invoice">
                                                            <option value="">Invoice</option>
                                                            <option  value="Yes">Yes</option>
                                                            <option  value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group" > <strong>Audit:</strong>
                                                        <select class="form-control"  ng-model="filterData.audit_status">
                                                            <option value="">Audit Status</option>
                                                            <option value="On Process">On Process</option>
                                                            <option value="CS Audited">CS Audited</option>
                                                            <option value="Pending">Pending</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                 <div class="col-md-3">
                                                    <div class="form-group" ><strong><?=lang('lang_Mobile');?>:</strong>
                                                        <input class="form-control" id="reciever_phone" name="reciever_phone"  ng-model="filterData.reciever_phone" class="form-control" placeholder="Enter Mobile No."> 
                                                    </div>
                                                </div>
                                                 
                                                <div class="col-md-3" style="margin-top: 20px;">
                                                    <div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.order_type">
                                                            <option value="">Order Type</option>
                                                            <option  value="B2B">B2B</option>
                                                            <option  value="B2C">B2C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-top: 20px;">
                                                    <div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.reverse_type">
                                                            <option value="">Shipment Type</option>
                                                            <option  value="1">Reverse order</option>
                                                            <option  value="0">Fullfillment order</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3" style="margin-top: 20px;">
                                                    <div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.on_hold">
                                                            <option value="">On Hold</option>
                                                            <option  value="Yes">Yes</option>
                                                            <option  value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-top: 20px;">
                                                    <div class="form-group" >
                                                        <select class="form-control"  ng-model="filterData.show_sku">
                                                            <option value="">Show Sku</option>
                                                            <option  value="Yes">Yes</option>
                                                            <option  value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Quantity');?>:</strong>
                                                        <input type="number" min="0" class="form-control" id="piece" name="piece"  ng-model="filterData.piece" class="form-control" placeholder="Enter Qauntity"> 

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_SKU');?>:</strong>
                                                        <input class="form-control" id="sku" name="sku"  ng-model="filterData.sku" class="form-control" placeholder="Enter SKU"> 

                                                    </div></div>
                                                <div class="col-md-3"> <div class="form-group" ><strong>Company:</strong>
                                                        <br>
                                                        
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value="">Company</option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['cc_id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div class="col-md-12"><div class="form-group">
                                                    <button type="button"  class="btn btn-danger" ng-click="loadMore(1, 1);" >Filter</button>
                                                    <button type="button" class="btn btn-success" style="margin-left: 10px;"><?= lang('lang_Total'); ?> <span class="badge">{{totalCount}}</span></button>

                                                    <select id="exportlimit" class="custom-select " ng-model="filterData.exportlimit" name="exprort_limit" required="required" ng-change="Getcheckbutton();" style="    font-size: 16px;padding: 8px;margin-left: 7px;"  >
                                                        <option value="" selected><?= lang('lang_select_export_limit'); ?></option>
                                                        <option ng-repeat="exdata in dropexport" value="{{exdata.i}}" >{{exdata.j}}-{{exdata.i}}</option>  
                                                    </select> 
                                                    <button  type="submit" id="Newaddfrm"  style="margin-left: 10px;" class="btn btn-info">Report Download</button>
                                                </div>

                                                <input type="hidden" name="searchval" value="{{filterData}}">
                                                   

                                            </div>





                                        </div>

                                        <!-- /quick stats boxes -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content -->
                        <!-- Basic responsive table -->

                        <!-- /basic responsive table -->
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->


            </div>

        </div>

        <!-- /page container -->
            <script>
  processFormData = function(event) {
  //alert("ssssss");
   // For this example, don't actually submit the form
   event.preventDefault();

    
    var Elem = event.target;
       if (Elem.nodeName=='td'){
          $("#exportfrom").submit()
       }
       
       
  
  
   

  };

    </script>
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
