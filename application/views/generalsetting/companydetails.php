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
                            <div class="panel-heading"><h1><strong><?=lang('lang_company_details');?></strong></h1></div>
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

                                <form action="<?= base_url('Generalsetting/updateform'); ?>" name="adduser" method="post" enctype="multipart/form-data">

                                <?php 
                                    if($EditData['fastcoo_partner_status']=='Y')
                                    {
                                      $fastcoo_partner_status='YES';  
                                    }
                                    else
                                    {
                                      $fastcoo_partner_status='NO';     
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="fastcoo_partner_status"><strong>Fastcoo Partner Activated :</strong></label> <strong><?= $fastcoo_partner_status; ?></strong><br>
                                    </div>
                                    <div class="form-group">
                                        <label for="company_name"><strong><?=lang('lang_Company_name');?>:</strong></label>
                                        <input type="text" class="form-control" name='company_name' id="company_name" placeholder="Company Name" value="<?= $EditData['company_name'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="ligal_name"><strong>Legal Name:</strong></label>
                                        <input type="text" class="form-control" name='ligal_name' id="ligal_name" placeholder="Legal Name" value="<?= $EditData['ligal_name'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="company_address"><strong><?=lang('lang_Address');?>:</strong></label>
                                        <input type="text" class="form-control" name='company_address' id="company_address" placeholder="Address" value="<?= $EditData['company_address'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_code_no"><strong>Phone No. Code:</strong></label>
                                        <input type="text" maxlength="3"  class="form-control" name='phone_code_no' id="phone_code_no" placeholder="Phone Code No" value="<?= $EditData['phone_code_no'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone"><strong><?=lang('lang_Phone');?>:</strong></label>
                                        <input type="text" class="form-control" name='phone' id="phone" placeholder="Phone" value="<?= $EditData['phone'] ?>">
                                    </div>
<!--                                    <div class="form-group">
                                        <label for="fax"><strong>Fax:</strong></label>
                                        <input type="text" class="form-control" name='fax' id="fax" placeholder="Fax" value="<? //= $EditData['fax'] ?>">
                                    </div>-->
                                    <div class="form-group">
                                        <label for="email"><strong> <?=lang('lang_Email');?>:</strong></label>
                                        <input type="email" class="form-control" name='email' id="email" placeholder="<?=lang('lang_Email');?>" value="<?= $EditData['email'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="support_email"><strong><?=lang('lang_SupportEmail');?>:</strong></label>
                                        <input type="email" class="form-control" name='support_email' id="support_email" placeholder="Support Email" value="<?= $EditData['support_email'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="webmaster_email"><strong><?=lang('lang_WebmasterEmail');?>:</strong></label>
                                        <input type="email" class="form-control" name='webmaster_email' id="webmaster_email" placeholder="Webmaster Email" value="<?= $EditData['webmaster_email'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="webmaster_email"><strong><?=lang('lang_Default_ABW');?>:</strong></label>
                                        <input type="text" class="form-control" maxlength="4" name='default_awb_char_fm' id="default_awb_char_fm" placeholder="Default AWB" value="<?= $EditData['default_awb_char_fm'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="default_currency"><strong> <?=lang('lang_Default_Currency');?>:</strong></label>
                                        <input type="text" class="form-control" maxlength="10" name='default_currency' id="default_currency" placeholder="<?=lang('lang_Default_Currency');?>" value="<?= $EditData['default_currency'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="pickup_address"><strong> Pickup Address:</strong></label>
                                        <input type="text" class="form-control" name='pickup_address' id="pickup_address" placeholder="Pickup Address" value="<?= $EditData['pickup_address'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="pickup_area"><strong> Pickup Area :</strong></label>
                                        <input type="text" class="form-control" name='pickup_area' id="pickup_area" placeholder="Pickup Area" value="<?= $EditData['pickup_area'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="latitude"><strong> Latitude :</strong></label>
                                        <input type="text" class="form-control" name='latitude' id="latitude" placeholder="Latitude" value="<?= $EditData['latitude'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="longitude"><strong> Longitude :</strong></label>
                                        <input type="text" class="form-control" name='longitude' id="longitude" placeholder="Longitude" value="<?= $EditData['longitude'] ?>">
                                    </div>


                                    <div class="form-group">
                                        <label for="country_code"><strong><?=lang('lang_Country_Code');?>:</strong></label>
                                        <input type="text" class="form-control" maxlength="10" name='country_code' id="country_code" placeholder="<?=lang('lang_Country_Code');?>" value="<?= $EditData['country_code'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="default_time_zone"><strong><?=lang('lang_Default_Time_Zone');?>: </strong></label>
                                        <select  id="default_time_zone" name="default_time_zone"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                            <option value=""><?=lang('lang_Select_Time_Zone');?></option>el>
                                            <?php
                                                if(!empty($TimeZone)){
                                                    foreach($TimeZone as $zone){ 
                                                        $select = "";
                                                        if($zone['value'] == $EditData['default_time_zone']) {$select = "selected";}
                                                        ?>
                                                            <option value="<?= $zone['value']; ?>"  <?= $select; ?> ><?= $zone['name']; ?>  </option>el>
                                             <?php       }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="dropoff_option"><strong><?=lang('lang_Drop_Off_Details');?></strong></label>
                                        <textarea  class="form-control" placeholder="<?=lang('lang_Drop_Off_Details');?>"  name='dropoff_option' id="dropoff_option" ><?= $EditData['dropoff_option'] ?></textarea>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="tollfree_fm"><strong><?=lang('lang_AWB_Tollfree_No');?>.</strong></label>
                                        <input type="text" class="form-control" maxlength="12" name='tollfree_fm' id="tollfree_fm" placeholder="<?=lang('lang_AWB_Tollfree_No');?>" value="<?= $EditData['tollfree_fm'] ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="font_color"><strong><?=lang('lang_Font_Color');?>:</strong></label>
                                        <input type="color" class="form-control"  name='font_color' id="font_color" placeholder="font_color" value="<?= $EditData['font_color'] ?>">
                                    </div> 
                                    <div class="form-group">
                                        <label for="vat"><strong><?=lang('lang_Vat_No');?>:</strong></label>
                                        <input type="text" class="form-control"  name='vat' id="vat" placeholder="vat" value="<?= $EditData['vat'] ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="vat"><strong>VAT %:</strong></label>
                                        <input type="text" class="form-control"  name='default_service_tax' id="default_service_tax" placeholder="Vat %" value="<?= $EditData['default_service_tax'] ?>">
                                    </div>
                                    <!-- <div class="form-group">
                            <label for="inputText">AWB Tollfree No. <span style="color:red">*</span></label>
                            <input type="text" name="tollfree_fm" id="tollfree_fm" class="form-control"  ng-model="updatearray.tollfree_fm" required>

                        </div> -->
                                    <div class="form-group">
                                        <label for="theme_color_fm"><strong><?=lang('lang_Theme_Color');?>:</strong></label>
                                        <input type="color" class="form-control" name='theme_color_fm' id="theme_color_fm" placeholder="Company color" value="<?= $EditData['theme_color_fm'] ?>">
                                    </div>
                                    <div class="form-group" ><strong><?=lang('lang_Select_Exception_Cities');?>:</strong>
                                        <br>
                                        <?php
                                        $destData = getAllDestination();
                                        $e_city = explode(",", $EditData['e_city']);

                                        //print_r($destData);
                                        ?>
                                        <select  id="e_city" name="e_city[]"  multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                            <option value=""><?=lang('lang_Select_Exception_Cities');?></option>
                                            <?php foreach ($destData as $data): ?>
                                                <?php
                                                if (in_array($data['id'], $e_city)) {
                                                    $selected = "selected";
                                                } else {
                                                    $selected = "";
                                                }
                                                ?>
                                                <option value="<?= $data['id']; ?>" <?= $selected; ?> ><?= $data['city']; ?></option>
                                            <?php endforeach; ?>

                                        </select>
                                    </div>



                                    <div class="form-group" style="display: none;">
                                        <label for="logo"><strong><?=lang('lang_Logo');?>:</strong></label>
                                        <input type="file" class="form-control" name='logo' id="logo">
                                        <input type="hidden"  name='logo_old' id="logo_old" value="<?= $EditData['logo']; ?>">
                                        <?php
                                        if (!empty($EditData['logo']))
                                            echo '<img src="' . $EditData['logo'] . '" class="img-thumbnail img-responsive" width="100">';
                                        ?>
                                    </div>
                                    <?php 
                                    if($EditData['auto_assign_picker']=='Y')
                                    {
                                      $auto_assign_picker_check1='checked';  
                                    }
                                    else
                                    {
                                      $auto_assign_picker_check2='checked';     
                                    }
                                    ?>
                                    <div class="form-group">
                                        <label for="auto_assign_picker"><strong><?=lang('lang_Auto_Assign_Picker');?>:</strong></label><br>
                                        <input type="radio" name="auto_assign_picker" id="auto_assign_picker" value="Y" <?=$auto_assign_picker_check1;?>> Yes
                                        <input type="radio" name="auto_assign_picker" id="auto_assign_picker1" value="N"  <?=$auto_assign_picker_check2;?>> No
                                    </div>
                                    </fieldset>

<div class="form-group" style="display: none;">
    <label for="logo"><strong><?=lang('lang_Logo');?>:</strong></label>
    <input type="file" class="form-control" name='logo' id="logo">
    <input type="hidden"  name='logo_old' id="logo_old" value="<?= $EditData['logo']; ?>">
    <?php
    if (!empty($EditData['logo']))
        echo '<img src="' . $EditData['logo'] . '" class="img-thumbnail img-responsive" width="100">';
    ?>
</div>
 <fieldset class="scheduler-border">
    <legend class="scheduler-border">ZID</legend>
<div class="form-group">
    <label for="zid_provider_token"><strong>ZID Provider Token:</strong></label>
    <input type="text" class="form-control"  name='zid_provider_token' id="zid_provider_token" placeholder="Zid Provider Token" value="<?= $EditData['zid_provider_token'] ?>">
</div>
 </fieldset>




 <!-- <fieldset class="scheduler-border">
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
    
    <?php 
// if($EditData['salla_auth_type']=='auth'){
//     $salla_auth_type_check1='checked';  
// }else{
//     $salla_auth_type_check2='checked';     
// }
?>
     <div class="form-group">
    <label for="salla_auth_type"><strong>Salla Auth Type:</strong></label><br>
    <input type="radio" name="salla_auth_type" id="salla_auth_type" value="auth" <?=$salla_auth_type_check1;?>> Authentication
    <input type="radio" name="salla_auth_type" id="salla_auth_type1" value="app"  <?=$salla_auth_type_check2;?>> App Connect
</div>          
 </fieldset> -->
<?php 
if($EditData['citc_status']=='Y'){
    $citc_status_check1='checked';  
}else{
    $citc_status_check2='checked';     
}
?>


 <?php 
if($EditData['citc_system']=='LM'){
    $citc_system_check1='checked';  
}else{
    $citc_system_check2='checked';     
}
?>



      <fieldset class="scheduler-border">
    <legend class="scheduler-border">CITC</legend>

    <div class="form-group">
    <label for="citc_provider_token"><strong>CITC Provider Token:</strong></label>
    <input type="text" class="form-control"  name='citc_provider_token' id="citc_provider_token" placeholder="CITC Provider Token" value="<?= $EditData['citc_provider_token'] ?>">
</div>
     <div class="form-group">
    <label for="citc_status"><strong><?=lang('lang_CITC_Status');?>:</strong></label><br>
    <input type="radio" name="citc_status" id="citc_status" value="Y" <?=$citc_status_check1;?>> Yes
    <input type="radio" name="citc_status" id="citc_status" value="N"  <?=$citc_status_check2;?>> No
</div>
    <div class="form-group">
    <label for="citc_status"><strong>CITC System Type:</strong></label><br>
    <input type="radio" name="citc_system" id="citc_system" value="LM" <?=$citc_system_check1;?>> Lastmile
    <input type="radio" name="citc_system" id="citc_system1" value="FM"  <?=$citc_system_check2;?>> Fulfilment
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
