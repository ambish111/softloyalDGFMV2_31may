<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
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
        <style type="text/css">
            .form-group.radiosection {
                display: inline-block;
                width: 24%;
            } 
        </style>

    </head>

    <body >

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
                            <div class="panel-heading"><h1><strong><?= lang('lang_Salla_Configuration'); ?> Private App</strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <form action="<?= base_url('Seller/updateSallaConfigApp/' . $customer['id']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
                                    <input type="hidden" class="form-control"  name="id" value="<?php echo $customer['id']; ?>">

                                    <fieldset class="scheduler-border" id="show_salla_details" style="">   
                                        <legend class="scheduler-border">Salla <?= lang('lang_Details'); ?></legend>
                                        <div class="form-group">
                                            <label>Salla Client ID</label>
                                            <input type="text" class="form-control" name="client_id_salla" id="client_id_salla" value="<?php echo $customer['client_id_salla']; ?>" autocomplete="off">
                                        </div>
                                        
                                         <div class="form-group">
                                            <label>Salla Client Secret Key</label>
                                            <input type="text" class="form-control" name="client_secret_key_salla" id="client_id_salla" value="<?php echo $customer['client_secret_key_salla']; ?>" autocomplete="off">
                                        </div>

                                       
                                      
                                    </fieldset>
                                    <button type="submit" name="updatesalla" value="1" class="btn btn-primary pull-right"><?= lang('lang_Update'); ?></button>
                                </form>

                            </div>
                        </div>
                        <hr>

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

<style>
    fieldset.scheduler-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }
    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<script type="text/javascript">
    $('form').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');
</script>  

