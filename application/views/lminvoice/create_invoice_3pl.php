<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
    <title><?=lang('lang_Inventory');?></title>
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
                    
                    <?php
                    
                        if (!empty($this->session->flashdata('errorA'))) {
                            // print_r($this->session->flashdata('errorA'));
                            foreach ($this->session->flashdata('errorA')['3pl_awb_empty'] as $validAlert) {
                                echo '<div class="alert alert-warning">Row No. "' . $validAlert . '" 3pl AWB is Empty  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                             foreach ($this->session->flashdata('errorA')['account_empty'] as $validAlert) {
                                echo '<div class="alert alert-warning">Row No. "' . $validAlert . '" Seller Account Number is Empty  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                             foreach ($this->session->flashdata('errorA')['close_date_empty'] as $validAlert) {
                                echo '<div class="alert alert-warning">Row No. "' . $validAlert . '" Close Date is Empty  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['invalid_account_no'] as $validAlert) {
                                echo '<div class="alert alert-warning">This Seller Account Number is"' . $validAlert . '" Invalid  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['invalid_order_nummber'] as $validAlert) {
                                echo '<div class="alert alert-warning">This 3PL AWB is"' . $validAlert . '" Invalid  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            foreach ($this->session->flashdata('errorA')['different_seller'] as $validAlert) {
                                echo '<div class="alert alert-warning">This 3PL AWB "' . $validAlert . '" belong to other seller <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                             foreach ($this->session->flashdata('errorA')['already_created'] as $validAlert) {
                                echo '<div class="alert alert-warning">This 3PL AWB "' . $validAlert . '" already Created <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            
                             foreach ($this->session->flashdata('errorA')['valid_no'] as $validAlert) {
                                echo '<div class="alert alert-success">Success "' . $validAlert . '" <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                            }
                            
                            
                            
                        }
                             ?>
            
                    <div class="panel panel-flat">
                        <div class="panel-heading" dir="ltr"><h1><strong> Create 3PL Invoice</strong><a href="<?= base_url('Excel_export/bulk_format_3pl');?>"><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a></h1></div>
                        <div class="alert alert-danger"><strong><?=lang('lang_Note');?> </strong><br>&nbsp1.  To Create bulk of 3PL invoice use this import feature. Below are the columns you must have according to serial number in the excel csv file.<br>&nbsp2. <?=lang('lang_All_fields_are_required');?>.<br>&nbsp3. <?=lang('lang_Click_above_excel_icon_excel_file_idea');?>.</div>
                        <hr>

                        <div class="panel-body">
               
                <br> 
                
                <table class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <td>(1) 3PL AWB No. <span style="color:#F00"><strong>*</strong></span></td>
                        <td>(2) <?=lang('lang_Seller_AccountNo');?>. <span style="color:#F00"><strong>*</strong></span></td>
                        <td>(2) Close Date. <span style="color:#F00"><strong>*</strong></span></td>
                      
                    </tr>
                   
                </tbody>
                </table>
                <br>
                <form class="stdform" id="AddnventoryID" method="post" action="<?= base_url('LastmileInvoice/Import3plInvoice');?>" name="AddnventoryID" enctype="multipart/form-data" onsubmit="document.getElementById('Newaddfrm').disabled=true; processFormData();">
                <label><strong class="alert-danger"><?=lang('lang_Import_File');?></strong></label>
                <span class="field">
                    <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                    <!-- <span id="weight" class="alert"></span> -->
                </span><br> 
                <input type="submit" id="Newaddfrm"  class="btn btn-success pull-left" value="Upload">
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


    
    <script>
  processFormData = function(event) {
  //alert("ssssss");
   // For this example, don't actually submit the form
   event.preventDefault();

    
    var Elem = event.target;
       if (Elem.nodeName=='td'){
          $("#AddnventoryID").submit()
       }
       
       
  
  
   

  };

    </script>
</body>
</html>
