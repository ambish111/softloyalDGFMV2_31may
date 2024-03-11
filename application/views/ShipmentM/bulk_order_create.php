<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
        <script src="<?= base_url(); ?>assets/js/angular/bulk_create.js?v=<?= time(); ?>"></script>
        <style>
            .wrapcl{word-break: break-all;}
        </style>
    </head>

    <body ng-app="BulkCreateApp">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="BulkCreateCNTLR"> 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 

                        <div class="loader logloder" ng-show="loadershow"></div>
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">







                                    <div class="panel-heading">
                                        <h1> <strong>Bulk Order Create</strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                        <div class="clearfix">

                                            <span class="badge badge badge-pill badge-danger" id="count_val">Duplicate Orders are automatically removed</span>
                                            <br>
                                            <span class="badge badge badge-pill badge-danger mt-10" id="count_val">You can order Create 500 shipments.
                                            </span> 
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12" ng-if="errordata_all.length != 0">
                                                <div  ng-repeat="e_data in errordata_all" >
                                                    <div  class="alert alert-warning wrapcl"  ng-if="e_data.destination_error">Destination is empty :  {{e_data.destination_error}}</div>
                                                    <div class="alert alert-warning wrapcl" ng-if="e_data.destination_in_error">Invalid Destination : {{e_data.destination_in_error}}</div>
                                                    <div class="alert alert-warning wrapcl" ng-if="e_data.pieces_error">Invalid Order Piece : {{e_data.pieces_error}}</div>
                                                    <div  class="alert alert-warning wrapcl" ng-if="e_data.origin_error">Invalid origin : {{e_data.origin_error}}</div>
                                                    <div  class="alert alert-warning wrapcl"ng-if="e_data.backorder_error">the {{e_data.backorder_error}} order from back order</div>
                                                    <div class="alert alert-warning wrapcl" ng-if="e_data.reciever_name_error">Receiver Name : {{e_data.reciever_name_error}}</div>
                                                    <div class="alert alert-warning wrapcl" ng-if="e_data.reciever_address_error">Receiver Address : {{e_data.reciever_address_error}}</div>
                                                    <div class="alert alert-warning wrapcl" ng-if="e_data.order_status_error">Order Status is invalid : {{e_data.order_status_error}}</div>

                                                </div>
                                            </div>
                                            <div class="col-md-12" ng-if="errordata_stock.length != 0">
                                                <div class="alert alert-danger"  ng-repeat="es_data in errordata_stock">
                                                    <p ng-if="es_data.error_type == 'invalid_stock'">this sku {{es_data.sku}} out of Stock {{es_data.slip_no}}</p>
                                                    <p ng-if="es_data.error_type == 'less_stock'">this sku {{es_data.sku}} out of Stock {{es_data.slip_no}}</p>
                                                    <p ng-if="es_data.error_type == 'invalid_sku'">this sku is {{es_data.sku}} invalid {{es_data.slip_no}}</p>
                                                    <p ng-if="es_data.error_type == 'invalid_qty'">this sku {{es_data.sku}} piece invalid {{es_data.slip_no}}</p>

                                                </div>


                                            </div>

                                            <div class="alert alert-danger wrapcl" ng-if="awb_error != ''"> {{awb_error}}</div>
                                            <div class="alert alert-success wrapcl" ng-if="succ_awb != ''">Success : {{succ_awb}}</div>
                                        </div>


<!--                                        <form  method="post" action="<?= base_url(); ?>Shipment/bulk_forward_remove"  >-->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea rows="10" id="show_awb_no"  ng-change="scan_awb();" ng-model="scan.slip_no" placeholder="<?= lang('lang_Please_Enter_Your_AWB_Number'); ?>" required class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-1" >
                                            <div class="form-group">

                                                <span  id="rowcount" class="btn btn-danger  btn-sm" >0</span>

                                            </div> 
                                        </div> 

                                        <div class="col-md-2" >
                                            <div class="form-group">

                                                <input type="button" ng-click="getsubmitdata();" class="btn btn-primary form-control checkdisable" value="Create Order">	

                                            </div> 
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /content area --> 
        </div>
        <?php $this->load->view('include/footer'); ?>
        <script type="text/javascript">
            $(".checkdisable").attr("disabled", true);
            $("#show_awb_no").on("change input paste keyup", function () {
                // alert("ssssss");
                //$("#res").html(jQuery(this).val());
                var value = $("#show_awb_no").val();
                var items = value.split('\n');
                var lines = 0;
                for (var no = 0; no < items.length; no++) {
                    lines += Math.ceil(items[no].length / 40);
                }
                document.getElementById('rowcount').innerHTML = lines;<!---->
                if (parseInt(lines) > 500)
                {
                    $(".checkdisable").attr("disabled", true);
                } else
                {
                    $(".checkdisable").attr("disabled", false);
                }
            });


        </script>

    </body>
</html>
