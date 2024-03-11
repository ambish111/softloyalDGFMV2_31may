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
                          
                          

                            <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <div class="panel-heading" dir="ltr"><h1><strong>Export Salla City</strong><a href="<?= base_url('Excel_export/ExportSallaCity'); ?>">&nbsp;&nbsp;<i class="icon-file-excel" style="font-size: 35px;"></i></a></h1></div>
                                        </div>
                                        <div class="col-md-6 text-right"></div>
                                    </div>
                                      
                                  
                            <?php if($this->session->flashdata('msg')):?>
                            <?= '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?> 
                            <?php elseif($this->session->flashdata('error')):?>
                            <?= '<div class="alert alert-danger">'.$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?>
                            <?php endif;?>   

                                <br><br>
                                <form class="stdform" method="post" action="<?= base_url('Excel_export/ImportSallaCityData'); ?>" name="add_ship" enctype="multipart/form-data">
                                    <label><strong class="alert-danger"><?=lang('lang_Import_File');?></strong></label>
                                    <span class="field">
                                        <input type="file" name="file" id="file" required accept=".xls,.xlsx,.csv"  class="btn btn-default">
                                        <!-- <span id="weight" class="alert"></span> -->
                                    </span><br> 
                                    <button type="submit"  class="btn btn-success pull-left">Submit </button> 
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
