<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>  
<style type="text/css">
.form-group.radiosection {
    display: inline-block;
    width: 23%;
}
</style>
</head>
<body  ng-app="fulfill">
<?php $this->load->view('include/main_navbar');?>

<!-- Page container -->
<div class="page-container"  ng-controller="shipment_view" > 
  
  <!-- Page content -->
  <div class="page-content">
    <?php $this->load->view('include/main_sidebar'); ?>
    
    <!-- Main content -->
    <div class="content-wrapper">
      <?php $this->load->view('include/page_header'); ?>
      
      <!-- Content area -->
      <div class="content">
        <div class="panel panel-flat">
          <div class="panel-heading">
            <h1><strong><?=lang('lang_Add_Seller');?></strong></h1>
          </div>
          <hr>
          <div class="panel-body">
            <?php if(!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> '.validation_errors().'</div>';?>
            <?php if($this->session->flashdata('err_msg')!=''){echo '<div class="alert alert-warning" role="alert">'.$this->session->flashdata('err_msg').'.</div>';}?>
            <form action="<?= base_url('Seller/add');?>" method="post"  name="add_customer" enctype="multipart/form-data">
             
                
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Profile_Details');?></legend>
                
                 <div class="form-group radiosection">
                      <label for="u_type"><strong>User Type B2B:</strong></label><br>
                      <input type="radio" name="u_type" id="u_type" value="B2B"> Yes <input type="radio" name="u_type" id="u_type" checked="checked" value="B2C"> No      
                  </div>
                 <div class="form-group radiosection">
                      <label for="access_lm"><strong><?=lang('lang_Access_LM');?>:</strong></label><br>
                      <input type="radio" name="access_lm" id="access_lm" value="Y">  <?= lang('lang_Yes'); ?> <input type="radio" name="access_lm" id="access_lm" checked="" value="N">  <?= lang('lang_No'); ?>                      
                  </div>

                  <div class="form-group radiosection">
                      <label for="auto_forward"><strong><?= lang('lang_Auto_Forwarding'); ?>:</strong></label><br>                      
                      <input type="radio" name="auto_forward" id="auto_forward" value="Y">  <?= lang('lang_Yes'); ?> <input type="radio" name="auto_forward" id="auto_forward" checked="" value="N">  <?= lang('lang_No'); ?>
                  </div>

                  <div class="form-group radiosection">
                      <label for="invoice_type"><strong><?= lang('lang_Invoice_Type'); ?> :</strong></label><br>
                      
                      <input type="radio" name="invoice_type" id="invoice_type" value="Fix Rate">  <?= lang('lang_Fix_Rate'); ?> <input type="radio" name="invoice_type" id="invoice_type" checked="" value="Dynamic">  <?= lang('lang_Dynamic'); ?> 
                  </div> 

                  <div class="form-group radiosection">
                      <label for="first_out"><strong><?= lang('lang_Inventory_First_In_First_Out'); ?>  :</strong></label><br>
                      
                      <input type="radio" name="first_out" id="first_out" value="Y">  <?= lang('lang_Yes'); ?> <input type="radio" name="first_out" id="first_out" checked="" value="N">  <?= lang('lang_No'); ?>
                  </div>
                  <div class="form-group radiosection">
                      <label for="label_info"><strong>3PL Label Info :</strong></label><br>
                      <input type="radio" name="label_info" id="label_info_customer"  checked='checked'   value="1" >&nbsp;From Customer 
                      <input type="radio" name="label_info" id="label_info_shipment"    value="2">&nbsp;From Shipment      
                      <!-- <input type="radio" name="label_info" id="label_info_config"      value="3">&nbsp;From Site Config       -->
                  </div>
                  <div class="form-group radiosection">
                      <label ><strong>Email Hide On Label Zid:</strong></label><br>
                      <input type="radio" name="hide_email" id="hide_email"   value="Y"> Yes 
                      <input type="radio" name="hide_email" id="hide_email1"  value="N"  checked='checked'> No
                  </div>                                        

                <div class="form-group">
                  <label><?=lang('lang_Name');?></label>
                  <input type="text"  class="form-control" id="name" name="name" value="<?=set_value('name');?>"/>
                </div>
                 
                <div class="form-group">
                  <p style=" display:none;">
                    <label><?=lang('lang_User_Name');?></label>
                    <span id="alert_user_name"></span>
                    <input type="text" class="form-control" name="user_name" value="<?=set_value('user_name');?>"/>
                  </p>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Company_name');?></label>
                  <input type="text" class="form-control" id="company" name="company" value="<?=set_value('company');?>"/>
                </div>
                
                <div class="form-group" >
                  <strong> Partner:</strong>
                  <?php
                    $agentData = partnerList();
                  ?>
                  <select name="agent_id" id="agent_id"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                    <option value="">Partner Name</option>
                    <?php foreach ($agentData as $data) { ?>
                        <option value="<?= $data['id']; ?>"><?= $data['name']; ?></option>
                    <?php } ?>
                  </select>
                  </div>
                  <div class="form-group" style="display:none">
                    <label><?=lang('lang_Sender_Zip');?></label>
                    <input type="text" class="form-control"id="sender_zip" name="pincode" value="<?=set_value('pincode');?>"/>
                    <input type="hidden" name="city_id" class="form-control" id="city_send_id"/>
                  </div>
                

                <div class="form-group" >
                <strong> Country/HUB:</strong>

                <?php
                $destData = countryList();

                //print_r($destData);
                ?>
                <select   ng-change="showCity();" ng-model="filterData.country"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%">

                  <option value="">Country/HUB</option>
                  <?php foreach ($destData as $data) { ?>
                  <option value="<?= $data['country']; ?>"><?= $data['country']; ?></option>
                  <?php } ?>

                </select>
                </div>
                <div class="form-group" ><strong>City:</strong>
                        
                        
                        <select  name="city_drop" id="city_drop" required  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" ng-model="filterData.destination"   >

                <option ng-repeat="cData in citylist"  data-select-watcher data-last="{{$last}}" value="{{cData.id}}" >{{cData.city}}</option>
                        </select>
                    </div>
                <div class="form-group">
                  <label><?=lang('lang_Address');?></label>
                  <input type="text" class="form-control" id="address" name="address" value="<?=set_value('address');?>"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_PhoneNo');?>1</label>
                  <input type="text" name="phone1" class="form-control" id="phone1" value="<?=set_value('phone1');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_PhoneNo');?> 2</label>
                  <input type="text"  name="phone2" class="form-control" id="phone2" value="<?=set_value('phone2');?>"/>
                </div>
                <div class="form-group">
                  <label>Lat</label>
                  <input type="text"  name="lat" class="form-control" id="lat" value="<?=set_value('lat');?>"/>
                </div>
                <div class="form-group">
                  <label>Lng</label>
                  <input type="text"  name="lng" class="form-control" id="lng" value="<?=set_value('lng');?>"/>
                </div>
                <div class="form-group">
                  <label>Area</label>
                  <input type="text"  name="area" class="form-control" id="area" value="<?=set_value('area');?>"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Store_Link');?>:<span class="bold_alert">*</span></label>
                  <input class="form-control" type="text" name="store_link" id="store_link" value="<?=set_value('store_link');?>" placeholder=" Store Link"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Tracking_webhook_link');?>:</label>
                  <input class="form-control" type="text" name="tracking_webhook" id="tracking_webhook" value="<?=set_value('tracking_webhook');?>" placeholder="Tracking Webhook Link"/>
                </div>
              </fieldset>
              <!--========================================Bank Detail=======================-->
              
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Bank_Details');?></legend>
                <div class="form-group">
                  <label><?=lang('lang_Bank_Name');?></label>  
                  <input type="text"  class="form-control" name="bank_name" id="bank_name" value="<?=set_value('bank_name');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Account_Number');?></label>
                  <input type="text"  class="form-control" name="account_number" id="account_number" value="<?=set_value('account_number');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Iban_Number');?></label>
                  <input type="text"  class="form-control" name="iban_number" id="iban_number" value="<?=set_value('iban_number');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Account_Manager');?></label>
                  <input type="text" class="form-control" name="account_manager" id="account_manager" value="<?=set_value('account_manager');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Manager_Email');?></label>
                  <input type="text" class="form-control" name="managerEmail" id="managerEmail" value="<?=set_value('managerEmail');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Manager_Number');?></label>
                  <input type="text" class="form-control" name="managerMobileNo" id="managerMobileNo" value="<?=set_value('managerMobileNo');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Bank_Fee');?>:</label>
                  <input type="num"  class="form-control" name="bankfee" id="bankfee" value="<?=set_value('bankfee');?>"/>
                </div>
              </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Files');?></legend>
                <div class="form-group">
                  <label><?=lang('lang_UploadCRpdf');?>:</label>
                  <input type="file"  class="form-control" name="upload_cr" id="upload_cr" />
                </div>
                <div class="form-group">
                  <label><?=lang('lang_UploadIDpdf');?>:</label>
                  <input type="file"  class="form-control" name="upload_id" id="upload_id" />
                </div>
                <div class="form-group">
                  <label><?=lang('lang_UploadContractpdf');?>:</label>
                  <input type="file"  class="form-control" name="upload_contact" id="upload_contact" />
                </div>
                
                 <div class="form-group">
                  <label>Logo:</label>
                  <input type="file"  class="form-control" name="image" id="image" />
                </div>
              </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Contract');?></legend>
                <div class="form-group">
                  <label> <?=lang('lang_Contract_Date');?></label>
                  <input class="form-control" type="date" name="entrydate" id="entrydate"  value="<?=set_value('entrydate');?>"/>
                </div>
                <div class="form-group">
                  <label> <?=lang('lang_Vat_No');?></label>
                  <input class="form-control" type="text" name="vat_no" id="vat_no"  value="<?=set_value('vat_no');?>"/>
                </div>
              </fieldset>
              <fieldset class="scheduler-border">
                <legend class="scheduler-border">Discount</legend>
                <div class="form-group ">
                      <label for="first_out"><strong>Discount  :</strong></label><br>
                      
                      <input type="radio" name="discount" id="discount1" value="1">  <?= lang('lang_Yes'); ?>&nbsp;&nbsp; <input type="radio" name="discount" id="discount2" checked="" value="0">  <?= lang('lang_No'); ?>
                  </div>
                <div class="form-group radiosection">
                  <label> From</label>
                  <input class="form-control datepppp" type="text" placeholder="From" name="discount_f" id="discount_f"  value="<?=set_value('discount_f');?>"/>
                </div>  
                <div class="form-group radiosection">&nbsp;</div>
                <div class="form-group radiosection">
                  <label> To</label>
                  <input class="form-control datepppp" type="text" placeholder="To" name="discount_to" id="discount_to"  value="<?=set_value('discount_to');?>"/>
                </div>
              </fieldset>
              
              <!-- <div class="form-group">
                            <strong>C2C Client</strong>&nbsp;&nbsp;<input type="radio"  name="VIP_user" />&nbsp;&nbsp;&nbsp;
                            <strong>B2C Client</strong>&nbsp;&nbsp;<input type="radio" name="VIP_user" checked="checked" />
                            </div>-->
              <fieldset class="scheduler-border">
                <legend class="scheduler-border"><?=lang('lang_Login_Details');?></legend>
                <div class="form-group">
                  <label><?=lang('lang_Email');?></label>
                  <input type="text"  class="form-control" id="email" name="email" value="<?=set_value('email');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Password');?></label>
                  <input type="password" class="form-control" name="password" id="alert_password" value="<?=set_value('password');?>"/>
                </div>
                <div class="form-group">
                  <label><?=lang('lang_Confirm_Password');?></label>
                  <input type="password"  class="form-control" name="conf_password" id="alert_conf_password" value="<?=set_value('conf_password');?>"/>
                </div>
              </fieldset>

			   

              <input name="id" type="hidden" />
              <button type="submit" class="btn btn-primary" name="submit" value="submit"><?=lang('lang_Add_New_Seller');?></button>
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


      <script type="text/javascript">

                                    $('.datepppp').datepicker({

                            format: 'yyyy-mm-dd'

                            });

<!-- /page container -->
        </script>
 