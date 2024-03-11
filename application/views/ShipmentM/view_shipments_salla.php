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
         <script type="text/javascript" src="<?= base_url('assets/js/angular/Sallaorders.js?v='.time()); ?>"></script>
         <style>
.tooltip {
  position: relative;
  display: inline-block;
}

.tooltip .tooltiptext {
  visibility: hidden;
  width: 140px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 5px;
  position: absolute;
  z-index: 1;
  bottom: 150%;
  left: 50%;
  margin-left: -75px;
  opacity: 0;
  transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
  visibility: visible;
  opacity: 1;
}
</style>
    </head>

    <body ng-app="SallorderApp" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_view_salla" ng-init="loadMore(1, 0);" >

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
                         if ($this->session->flashdata('error'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

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
                                            <strong>Salla Pending Orders</strong>

                                         
                                        </h1>
                                    </div>
                                    <form ng-submit="dataFilter();">
                                   
                                        <div class="panel-body" >
                                            <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                            
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Ref_No');?>:</strong>
                                                        <input  id="booking_id" name="booking_id"  ng-model="filterData.booking_id" class="form-control" placeholder="Enter Ref no."> 
                                                    </div>
                                                </div>
                                                <div class="col-md-3"> <div class="form-group" ><strong><?=lang('lang_From');?>:</strong>
                                                        <input class="form-control date" id="from" name="from" ng-model="filterData.from" class="form-control"> 
                                                    </div> 
                                                </div>
                                                <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_To');?>:</strong>
                                                        <input class="form-control date" id="to" name="to"  ng-model="filterData.to" class="form-control"> 
                                                    </div>
                                                </div>
                                                <div class="col-md-8"><div class="form-group" >
                                                        <button  class="btn btn-danger" ng-click="loadMore(1, 1);" ><?=lang('lang_Search');?></button>
                                                        <button type="button" class="btn btn-success" style="margin-left: 7%"><?=lang('lang_Total');?>  <span class="badge">{{shipData.length}}/{{totalCount}}</span></button>
                                                        
                                                         <?php if (menuIdExitsInPrivilageArray(122) == 'Y') { ?>
<!--                                                        <button  class="btn btn-danger ml-10" ng-confirm-click="Are you sure want delete Orders?" ng-click="removemultipleorder();" ><?=lang('lang_Delete');?></button>-->
                                                         <?php } ?>
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

                                <span ng-if="erroshow!='null'">{{erroshow}}</span>
                                <div class="table-responsive" style="padding-bottom:20px;" >
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.  
<!--                                                    <input type="checkbox" ng-model="selectedAll"  ng-change="selectAll();" />-->
                                                </th>
                                                <th><?=lang('lang_Ref_No');?>.</th>
<!--                                                <th>Merchant Id</th>-->
                                                <th>Order Request</th>
                                                <th><?=lang('lang_Date');?></th>
                                                <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                            </tr>  
                                        </thead>  
                                        <tr ng-if='shipData != 0' ng-repeat="data in shipData"> 

                                            <td>{{$index + 1}}  
<!--                                                <input type="checkbox" value="{{data.slip_no}}" check-list='Items' ng-model="data.Selected" ng-click="checkIfAllSelected()" />-->

                                     
                                            <td>{{data.booking_id}}</td>
                                         
<!--                                            <td>{{data.merchant_id}}</td>   -->
                                        
                                            <td><span id="makecopyid" style="display:none;  ">{{ data.data_s}}</span><pre >{{ data.data_s | limitTo: 90 }}<a onclick="copyToClip(document.getElementById('makecopyid').innerHTML)"> <i class="fa fa-copy fa-2x"></i></a></pre> </td>  
                                         
                                          
                                          <td>{{data.created_at}}</td>
                                         
                                            <td class="text-center"  ng-if="data.status=='P'">
                                                <a class="btn btn-info" ng-if="data.order_verify=='Y'"  ng-confirm-click="Are you sure want Generate Orders?" ng-click="GetOrderCreateProcess(data.id);">Generate Order</a>
                                                <a class="btn btn-warning" ng-if="data.order_verify=='N'" title="Please Assign Seller" disabled>Generate Order</a>
                                            </td>
                                            <td class="text-center"  ng-if="data.status=='S'"><span class="label label-success">Generated</span></td>

                                        </tr>

                                    </table>

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

            <div id="deductQuantityModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger" style="background-color:<?= DEFAULTCOLOR; ?>;border-color:<?= DEFAULTCOLOR; ?>">
                            <h6 class="modal-title"><?=lang('lang_Item_Sku_Detail');?></h6>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>

                        <div class="modal-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                    <th><?=lang('lang_SKU');?> </th>
                                        
                                         <th><?=lang('lang_QTY');?></th>
                                    <th><<?=lang('lang_Deducted_Shelve_NO');?></th>
                                    <th><?=lang('lang_COD');?> ( <?=  $this->deafult_currency; ?> )</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="dataship in shipData1">
                                        <td><span class="label label-primary">{{dataship.sku}}</span> </td>
                                        
                                        <td><span class="label label-info">{{dataship.piece}}</span></td>
                                        <td><span class="label label-info">{{dataship.deducted_shelve}}</span></td>
                                        <td><span class="label label-danger">{{dataship.cod}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>


           





        </div>
       
        <!-- /page container -->
        <script>
function copyToClip(str) {
  function listener(e) {
    e.clipboardData.setData("text/html", str);
    e.clipboardData.setData("text/plain", str);
    e.preventDefault();
  }
  document.addEventListener("copy", listener);
  document.execCommand("copy");
  document.removeEventListener("copy", listener);
  alert("Copied");
};
</script>
        <script type="text/javascript">

            $('.date').datepicker({

                format: 'yyyy-mm-dd'

            });

        </script>
    </body>
</html>
