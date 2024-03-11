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

    <body>

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content">


                        <div class="panel panel-flat">
                            <div class="panel-heading" dir="ltr"><h1><strong><?= lang('lang_Bulk_Upload'); ?></strong><a href="<?= base_url('Buk_update/cod_update_3pl_format'); ?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                            <div class="alert alert-danger"><strong><?= lang('lang_Note'); ?> </strong><br>&nbsp1.  To add bulk of Shipment use this import feature. Below are the columns you must have according to serial number in the excel csv file.<br>&nbsp2. <?= lang('lang_All_fields_are_required'); ?>.<br>&nbsp3. <?= lang('lang_Click_above_excel_icon_excel_file_idea'); ?>.<br>&nbsp4. If you enter wrong date format then current date will be saved.</div>
                            <hr> 

                            <div class="panel-body">
                                
                                <?php 
                               // echo "<pre>";
                               $e_all=$this->session->flashdata('error_all');
                              
                               if(!empty($e_all['invalid_awb']))
                               {
                                  echo ' <div class="alert alert-warning alert-dismissible">Invliad AWB : '.implode(',',$e_all['invalid_awb']).'</div>'; 
                               }
                               if(!empty($e_all['empty_row']))
                               {
                                  echo ' <div class="alert alert-warning alert-dismissible">Empty Rows : '.implode(',',$e_all['empty_row']).'</div>'; 
                               }
                                if(!empty($e_all['invalid_date']))
                               {
                                  echo ' <div class="alert alert-warning alert-dismissible">Invalid COD Received Date : '.implode(',',$e_all['invalid_date']).'</div>'; 
                               }
                                if(!empty($e_all['invalid_cod']))
                               {
                                  echo ' <div class="alert alert-warning alert-dismissible">Invalid 3PL Received COD Rows : '.implode(',',$e_all['invalid_cod']).'</div>'; 
                               }
                                if(!empty($e_all['cod_already']))
                               {
                                  echo ' <div class="alert alert-danger alert-dismissible">Already Updated AWB : '.implode(',',$e_all['cod_already']).'</div>'; 
                               }
                                if(!empty($e_all['valid_awb']))
                               {
                                  echo ' <div class="alert alert-success alert-dismissible">Success AWB : '.implode(',',$e_all['valid_awb']).'</div>'; 
                               }
                                 if(!empty($e_all['faild_awb']))
                               {
                                  echo ' <div class="alert alert-danger alert-dismissible">Failed AWB Numbers : '.implode(',',$e_all['valid_awb']).'</div>'; 
                               }
                                ?>
                                <?php if ($this->session->flashdata('error')) { ?>

                                    <div class="alert alert-danger alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <?= $this->session->flashdata('error') ?> </div>
                                <?php } ?>
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><?= $this->session->flashdata('msg') ?> </div>
                                <?php } ?>

                                <br> 
                                <table class="table table-striped table-bordered table-hover">
                                    <tbody>

                                        <tr>
                                            

                                            <td>(1) <?= lang('lang_AWB_Number'); ?> <span style="color:#F00"><strong>*</strong></span></td>
                                            <td>(2) 3PL Received COD  <span style="color:#F00"><strong>*</strong></span></td>
                                            <td>(3) COD Received Date <span style="color:#F00"><strong>*</strong></span></td>


                                        </tr>

                                    </tbody>
                                </table>
                                <br>
                                <form class="stdform" method="post" action="<?= base_url('Buk_update/shipmentsBulk'); ?>" id="AddnventoryID" name="AddnventoryID" enctype="multipart/form-data" onsubmit="document.getElementById('Newaddfrm').disabled = true; processFormData();">


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
