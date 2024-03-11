<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>


    </head>


    <body ng-app="fulfill" ng-controller="shipment_view">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>

                    <div class="loader logloder" ng-show="loadershow"></div>

                    <!-- Content area -->
                    <div class="content">

                        <div class="panel panel-flat">
                            <div class="panel-heading" dir="ltr"><h1><strong><?= lang('lang_Bulk_Upload'); ?></strong><a href="<?= base_url('Excel_export/update_item_bulk_format'); ?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                            <div class="alert alert-danger"><strong><?= lang('lang_Note'); ?> : </strong><br>&nbsp1. To add bulk of items use this import feature. Below are the columns you must have according to serial number in the excel csv file.<br>&nbsp2. <?= lang('lang_All_fields_are_required'); ?>.<br>&nbsp3. <?= lang('lang_Click_above_excel_icon_excel_file_idea'); ?><br>4.If you are uploading image zip then the name of the folder should be "proimg".</div>
                            <hr>

                            <div class="panel-body">

                                <br> 
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <td>(1) SKU<span style="color:#F00"><strong>*</strong></span></td>
                                            <td>(2) EAN NO.<span style="color:#F00"><strong></strong></span></td>
                                            <td>(3) Name <span style="color:#F00"><strong></strong></span></td>
                                            <td>(4) Description <span style="color:#F00"><strong></strong></span></td>

                                        </tr>
                                        <tr>

                                            <td>(5) Storage Type <span style="color:#F00"><strong></strong></span></td>
                                            <td>(6) Warehouse <span style="color:#F00"><strong></strong></span></td>
                                            <td>(7) Length <span style="color:#F00"><strong></strong></span></td>
                                            <td>(8) Width <span style="color:#F00"><strong></strong></span></td>
                                        </tr>
                                        <tr>

                                            <td>(9)Height <span style="color:#F00"><strong></strong></span></td>
                                            <td>(10) Capacity  <span style="color:#F00"><strong></strong></span></td>
                                            <td>(11) Weight <span style="color:#F00"><strong></strong></span></td>
                                            <td>(12) Image (ex. product.png) <span style="color:#F00"><strong></strong></span></td>


                                        </tr>

                                        <tr>



                                        </tr>

                                    </tbody>
                                </table>
                                <br>
                                <form class="stdform" method="post" action="<?= base_url('Excel_export/update_item_bulk'); ?>" id="AddnventoryID" name="AddnventoryID" enctype="multipart/form-data" onsubmit="document.getElementById('Newaddfrm').disabled = true; processFormData();"  ng-submit="pageloader();">

                                    <label><strong class="alert-danger"> <?= lang('lang_Import_Images_Zip'); ?></strong></label>
                                    <span class="field">
                                        <input type="file" name="product_images" id="product_images"  accept=".zip"  class="btn btn-default">
                                        <!-- <span id="weight" class="alert"></span> -->
                                    </span><br> 
                                    <label><strong class="alert-danger"><?= lang('lang_Import_Excel_File'); ?></strong></label>
                                    <span class="field">
                                        <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                                        <!-- <span id="weight" class="alert"></span> -->
                                    </span><br> 
                                    <button type="submit" id="Newaddfrm"  class="btn btn-success pull-left">Update</button> 
                                </form>
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
        <!-- /page container -->


    </body>
</html>

<link href="<?= base_url(); ?>assets/colorpicker/jquery.colorpicker.bygiro.min.css" rel="stylesheet">
<script src="<?= base_url(); ?>assets/colorpicker/jquery.colorpicker.bygiro.min.js"></script>

<script>
                                    $('.myColorPicker').colorPickerByGiro({
                                        preview: '.myColorPicker-preview',
                                        showPicker: true,
                                        format: 'hex',
                                        sliderGap: 6,

                                        cursorGap: 6,
                                        text: {

                                            close: 'Close',

                                            none: 'None'

                                        }




                                    });
</script>

<script>
    processFormData = function (event) {
//alert("ssssss");
// For this example, don't actually submit the form
        event.preventDefault();


        var Elem = event.target;
        if (Elem.nodeName == 'td') {
            $("#AddnventoryID").submit()
        }






    };


</script>
