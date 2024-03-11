<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_company_details');?> </title>
        <?php $this->load->view('include/file'); ?>



    </head>
  
    <body   >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" >

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper">

                    <?php $this->load->view('include/page_header'); ?>


                    <!-- Content area -->
                    <div class="content">
                        <div class="panel panel-flat">
                            <div class="panel-heading"><h1><strong><?=lang('lang_salla_details');?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>
                                <?php
                                if ($this->session->flashdata('msg') != '') {
                                    echo '<div class="alert alert-success" role="alert">  ' . $this->session->flashdata('msg') . '.</div>';
                                }
                                ?>

                                <form action="<?= base_url('Generalsetting/updatesalla'); ?>" name="adduser" method="post" enctype="multipart/form-data">
                              <?php //print_r($EditData); die; ?>
                                    <fieldset class="scheduler-border">
                                                <legend class="scheduler-border">Salla</legend>
                                                 <div class="form-group">
                                                <label for="salla_provider_token"><strong>Salla Provider Token:</strong></label>
                                                <input type="text" class="form-control"  name='salla_provider_token' id="salla_provider_token" placeholder="Salla Provider Token" value="<?= $EditData['salla_provider_token'] ?>">
                                            </div>
                                            
                                           
                                            
                                             <div class="form-group">
                                                <label for="salla_track_url"><strong>Salla Track Url:</strong></label>
                                                <input type="text" class="form-control"  name='salla_track_url' id="salla_track_url" placeholder="Salla Track Url" value="<?= $EditData['salla_track_url'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="salla_provider"><strong>Salla Provider:</strong></label><br>
                                                <input type="radio" name="salla_provider" id="" value="1" <?php echo ($EditData['salla_provider'] == 1)? 'checked' : '';?>> Yes
                                                <input type="radio" name="salla_provider" id="" value="0"  <?php echo ($EditData['salla_provider'] == 0)? 'checked' : '';?>> No
                                            </div>

                                            <div class="form-group">
                                            <label for="salla_app_clientId"><strong>Salla App Client ID:</strong></label>
                                            <input type="text" class="form-control"  name='salla_app_clientId' id="salla_app_clientId"  value="<?= $EditData['salla_app_clientId'] ?>" />
                                        </div>
                                         <div class="form-group">
                                            <label for="salla_app_secret_key"><strong>Salla App Secret Key:</strong></label>
                                            <input type="text" class="form-control"  name='salla_app_secret_key' id="salla_app_secret_key" value="<?= $EditData['salla_app_secret_key'] ?>" />
                                        </div>
                                                
                                                <?php 
                                            if($EditData['salla_auth_type']=='auth'){
                                                $salla_auth_type_check1='checked';  
                                            }else{
                                                $salla_auth_type_check2='checked';     
                                            }
                                            ?>
                                                 <div class="form-group">
                                                <label for="salla_auth_type"><strong>Salla Auth Type:</strong></label><br>
                                                <input type="radio" name="salla_auth_type" id="salla_auth_type" value="auth" <?=$salla_auth_type_check1;?>> Authentication
                                                <input type="radio" name="salla_auth_type" id="salla_auth_type1" value="app"  <?=$salla_auth_type_check2;?>> App Connect
                                            </div>  
                                                
                                                <div class="form-group">
                                                <label for="salla_app_id"><strong>Salla APP ID:</strong></label>
                                                <input type="text" class="form-control"  name='salla_app_id' id="salla_app_id" placeholder="Salla APP ID" value="<?= $EditData['salla_app_id']; ?>">
                                            </div>
                                     </fieldset>
                                    
                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success"><?=lang('lang_Update');?></button>
                                    </div>
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



