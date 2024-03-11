<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" > 

            <!-- Page content -->
            <div class="page-content">
                <?php $this->load->view('include/main_sidebar'); ?>

                <!-- Main content -->
                <div class="content-wrapper" > 
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <!-- Content area -->
                    <div class="content"  > 
                        <!--style="background-color: red;"-->

                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something').'  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>';
                        ?>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong>Print Stock Location</strong> </h1>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row"> </div>
                                        <!-- <div class="alert alert-danger"><?=lang('lang_Note');?>: <?=lang('lang_SKU_Limit_is_fifty');?></div> -->
                                        <form  method="post" action="<?= base_url(); ?>Shelve/barcodebulkprintbulk" target="_blank"  >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <textarea rows="8" id="sku_barcode" name="stocklocationval"  placeholder="Please Enter Your Stock Location" required class="form-control"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-1" >
                                                <div class="form-group">

                                                    <span  id="rowcount" class="btn btn-danger  btn-sm" >0</span>

                                                </div> 
                                            </div> 
                                          
                                           
                                             <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group ">
                                                    <input type="text"  name="sizeh" class="form-control" placeholder="Height" required="">
                                                </div> 
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <input type="text"  name="sizew" class="form-control" placeholder="Width" required=""> 

                                                </div> 
                                            </div>
                                            <div class="col-sm-3"> 
                                                <div class="form-group">

                                                    <select id="size_val" class="form-control"  name="size_type" required="required">
                                                        <option value="inch">inch</option>
                                                    </select>
                                                </div> 
                                            </div>

                                                <div class="col-sm-3">   
                                                    <!-- <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="BulkPrintBarcode(data.SelectedStock);"><?= lang('lang_Print_Barcode'); ?></button>   -->
                                                    
                                                    <button type="submit"  name="print_type"  value="barcode"  class="btn btn-info pull-left ng-scope"  ><?= lang('lang_Print_Barcode'); ?></button>
                                                </div>
                                                <div class="col-sm-3" >   
                                                    <button type="submit"  name="print_type" value="qrcode" class="btn btn-danger"  >Print QR Code</button>
                                                </div>

                                            </div>
                                          
                                          


                                        </form>
                                        
                                    </div>

                                    <div class="text-danger" style="padding-left:20px;" ><strong>Note:</strong> Please enter sizes in inches.</div>
                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /dashboard content --> 

                <!-- /basic responsive table --> 

            </div>
            <!-- /content area --> 
        </div>
        <?php $this->load->view('include/footer'); ?>

        <script type="text/javascript">
            $(".checkdisable").attr("disabled", true);
            $("#sku_barcode").on("change input paste keyup", function () {
                // alert("ssssss");
                //$("#res").html(jQuery(this).val());
                var value = $("#sku_barcode").val();
                var items = value.split('\n');
                var lines = 0;
                for (var no = 0; no < items.length; no++) {
                    lines += Math.ceil(items[no].length / 40);
                }
                document.getElementById('rowcount').innerHTML = lines;<!---->
                // if (parseInt(lines) > 50)
                // {
                //     $(".checkdisable").attr("disabled", true);
                // } else
                // {
                //     $(".checkdisable").attr("disabled", false);
                // }
            });


        </script>

        <!-- /page container -->

    </body>
</html>
