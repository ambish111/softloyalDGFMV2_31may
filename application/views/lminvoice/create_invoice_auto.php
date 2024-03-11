<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
       
        <?php $this->load->view('include/file'); ?>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/angular/lminvoice.app.js"></script>
    </head>

    <body ng-app="lmInvoice">
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container" ng-controller="createInvoice" > 

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

                      
                            <div ng-if="message!=undefined" class="alert alert-success">{{message}}<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></div>'

                      
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1> <strong><?=lang('lang_Bulk_Create_Invoice');?></strong> </h1>
                                    </div>
                                     
                                    <div class="panel-body" >
                                    <?php if(!empty($avail_path)) { ?>
                                    <div   class="alert alert-success">Available to Create Invoice <button class="float-right" ><a href="assets/lminvoice/<?=$avail_path;?>" > Download </a></button></div>
                                    <?php }?>
                    <?php if(!empty($zero_value_path)) { ?>
                                    <div   class="alert alert-danger">
                                   Shipment Price or zone not set (Shipment Price Zero)
                                    <button class="float-right" ><a href="assets/lminvoice/<?=$zero_value_path;?>" > Download <?=lang('lang_Download');?></a></button>
                                    </div>
<?php }?>

<?php if(!empty($already_path)) { ?>
                                    <div   class="alert alert-danger">
                                   Shipment Already In Invoice
                                    <button class="float-right" ><a href="assets/lminvoice/<?=$already_path;?>" > Download <?=lang('lang_Download');?></a></button>
                                    </div>
<?php }?>
                     
                                    
                                  
                                    </div>
                                    <div class="panel-body" >
                                        <div class="row"> </div>
                                        <!-- <div class="alert alert-danger"><?=lang('lang_Note');?> Note Order Limit is 200</div> -->
                                        <form  method="post" action="<?= base_url(); ?>createInvoiceAuto"  >
                                            
                                        <div class="col-md-3"><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong> <br>
                                                        <select  id="seller" name="seller"  data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >
                                                            <option value=""><?=lang('lang_SelectSeller');?></option>
                                                            <?php foreach ($sellers as $seller_detail): ?>
                                                                <?php if($seller_detail->id==$cust_id) {?>
                                                                    <option value="<?= $seller_detail->id; ?>" selected>
                                                                    <?= $seller_detail->company; ?>
                                                                </option>
                                                                    <?php }?>

                                                                <option value="<?= $seller_detail->id; ?>">
                                                                    <?= $seller_detail->company; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div></div>
                                          

                                          
                                            <?php if(!empty($avail_path)) { ?>
                                                <input type='hidden' name="awb_array" value="<?=$avail;?> ">
                                            <input type="submit"  name="create_invoice" class="btn btn-primary form-control" value="<?=lang('lang_Create_Invoice');?>">	
                                            <?php } else {?>
                                                <input type="submit"  name="check_invoice" class="btn btn-primary form-control" value="<?=lang('lang_Check_Invoice');?>">	
                                                <?php }?>
                                            </div> 
                                            </div>
                                            
                                            
                                          
                                        </form>
                                    </div>


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
 $("#show_awb_no").on("change input paste keyup", function() {
	// alert("ssssss");
   //$("#res").html(jQuery(this).val());
 var value = $("#show_awb_no").val();   
    var items = value.split('\n');
    var lines = 0;
    for(var no=0;no<items.length;no++){
        lines += Math.ceil(items[no].length/40);    }
        document.getElementById('rowcount').innerHTML =lines;
    $(".checkdisable").attr("disabled", false);
if(parseInt(lines)>200)
{
  
}
else
{
  //$(".checkdisable").attr("disabled", false);
}
});


</script>

        <!-- /page container -->

    </body>
</html>
