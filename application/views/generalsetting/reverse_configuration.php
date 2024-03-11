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
                            <div class="panel-heading"><h1><strong>Reverse Configuration</strong></h1></div>
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

                                <form action="<?= base_url('Generalsetting/updatereverseconfig'); ?>" name="adduser" method="post" >

                                    <div class="form-group">
                                        <label for="default_time_zone"><strong>Pickup: </strong></label>
                                        <select  id="picker" name="picker"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                            <option value="">--select picker--</option>el>
                                            <?php
                                                if(!empty($courier_company)){
                                                    foreach($courier_company as $company){ 
                                                        $select = "";
                                                        if($company->cc_id == $EditData['pickup_courier_company']) {$select = "selected";}
                                                        ?>
                                                            <option value="<?= $company->cc_id; ?>"  <?= $select; ?> ><?= $company->company; ?>  </option>el>
                                             <?php       }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="default_time_zone"><strong>Drop Off: </strong></label>
                                        <select  id="drop_off" name="drop_off"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                            <option value="">--select dropoff--</option>el>
                                            <?php
                                                if(!empty($courier_company)){
                                                    foreach($courier_company as $company){
                                                        $select = "";
                                                        if($company->cc_id == $EditData['dropoff_courier_company']) {$select = "selected";}
                                                        ?>
                                                            <option value="<?= $company->cc_id; ?>"  <?= $select; ?> ><?= $company->company; ?>  </option>el>
                                        <?php       }
                                                }
                                        ?>
                                        </select>
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

</html><style>

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

<script type="application/javascript">

    $(function() {
    $("form[name='adduser']").validate({
    rules: {
    usertype: "required",
    username: "required",

    email: {
    required: true,
    email: true
    },
    mobile_no: {
    required: true,
    number: true,
    minlength: 10,
    maxlength: 10
    },
    password: {
    required: true,
    minlength: 6
    },
    profile_pic: "required",
    },
    messages: {
    usertype: "Please Select User Type",
    username: "Please enter Username",
    password: {
    required: "Please provide a password",
    minlength: "Your password must be at least 6 characters long"
    },
    email: {
    required: "Please Email Address",
    email: "Please enter a valid Email address"
    },
    mobile_no: {
    required: "Please enter Mobile No.",
    number: "Please enter a valid number.",
    minlength: "Please Enter Valid Mobile No."
    },

    },
    submitHandler: function(form) {
    form.submit();
    }
    });
    });

</script>
