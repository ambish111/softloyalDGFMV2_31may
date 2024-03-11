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
                            <div class="panel-heading"><h1><strong><?=lang('lang_EditItem');?></strong></h1>
                            </div>
                            <hr>
                            <div class="panel-body">

                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <form action="<?= base_url('Item/edit/' . $item->id); ?>" method="post" enctype="multipart/form-data">



                                    
                                    
                                    <?php
                                    
                                    if($item->expire_block=='Y')
                                    {
                                       $chek_expire_block1='selected';
                                    }
                                    else
                                    { $chek_expire_block2='selected';
                                        
                                    }
                                    ?>
                                    
                                       <div class="form-group" >
                                        <label for="name"><strong><?=lang('lang_Expire_Block');?>:</strong></label>
                                        <select id="expire_block" name="expire_block" class="form-control" data-width="100%" required>
                                            

                                            <option value="Y" <?=$chek_expire_block1;?>><?=lang('lang_Yes');?></option>
                                            <option value="N" <?=$chek_expire_block2;?>><?=lang('lang_No');?></option>

                                        </select>

                                       
                                    </div>
                                    <div class="form-group">

<!--                                        <label for="exampleInputEmail1"><strong>ID#:</strong></label>-->
                                        <input  type="hidden"  name='id' value='<?= $item->id; ?>' disabled  class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="wh_id"><strong><?=lang('lang_warehouse');?>:</strong></label>
                                        <?= GetwherehouseDropShow($item->wh_id); ?>     
                                    </div>
                                    <div class="form-group">
                                        <label for="name"><strong><?=lang('lang_StorageType');?>:</strong></label>
                                        <select id="storage_id" name="storage_id" class="bootstrap-select"  data-width="100%" required>
                                            <option value=""><?=lang('lang_SelectStorageType');?></option>
                                            <?php
                                            if (!empty($StorageArray)) {
                                                foreach ($StorageArray as $rdata) {
                                                    // echo $rdata->id;
                                                    if ($item->storage_id == $rdata->id)
                                                        echo '<option value="' . $rdata->id . '" selected>' . $rdata->storage_type . '</option>';
                                                    else
                                                        echo '<option value="' . $rdata->id . '">' . $rdata->storage_type . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>

                                    <div class="form-group">

                                        <label for="exampleInputEmail1"><strong><?=lang('lang_Name');?>:</strong></label>
                                        <input type="text" class="form-control" name='name' value="<?= $item->name; ?>" id="exampleInputEmail1" placeholder="<?=lang('lang_Name');?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><strong><?=lang('lang_Sku');?> #:</strong></label>
                                        <input type="text" disabled="disabled" class="form-control" name='sku'  value="<?= $item->sku; ?>" id="exampleInputEmail1" placeholder="<?=lang('lang_Sku');?> #" >
                                    </div>
                                     <?php if (menuIdExitsInPrivilageArray(230) == 'Y') { ?>
                                     <div class="form-group">
                                        <label for="exampleInputEmail1"><strong>EAN NO.:</strong></label>
                                        <input type="text"  class="form-control" name='ean_no'  value="<?= $item->ean_no; ?>" id="exampleInputEmail1" placeholder="EAN NO." >
                                    </div>
                                       <?php  } ?>
                                    <div class="form-group">
                                        <label for="sku"><strong><?=lang('lang_Capacity');?>:</strong></label>
                                        <input type="text" class="form-control" name='sku_size'  id="sku_size" placeholder="<?=lang('lang_Capacity');?>" ng-model="item.sku_size" value="<?= $item->sku_size; ?>" required>

                                    </div>
                                    
                                     <div class="form-group">
                                    <label for="less_qty"><strong><?=lang('lang_Less_Quantity');?>:</strong></label>
                                    <input type="number" class="form-control" name='less_qty' id="less_qty" placeholder="<?=lang('lang_Less_Quantity');?>" ng-model="item.less_qty" value="<?= $item->less_qty; ?>" required>
                                   
                                </div>
                                 <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Expiry_Days');?>:</strong></label>
                                    <input type="number" class="form-control" name='alert_day' id="alert_day" placeholder="<?=lang('lang_Expiry_Days');?>" ng-model="item.alert_day" value="<?= $item->alert_day; ?>" required>
                                  
                                </div>
                                
                                  
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Color');?>:</strong></label>
                                    <div class="input-group myColorPicker">
                                        <?php
                                        if($item->color!=''){
                                        ?>
                                        <span class="input-group-addon myColorPicker-preview" style="background-color: <?= $item->color; ?>;" onClick="Getchangcolor();"></span>
                                        <?php } else { ?>  <span class="input-group-addon myColorPicker-preview" style="background-color:blank;" onClick="Getchangcolor();"><?=lang('lang_Color');?></span><?php  } ?>
                                        <input type="text" class="form-control" name="color" value="<?= $item->color; ?>" onkeypress="Getchangcolor();">
					</div>
                                   
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Length');?>:</strong></label>
                                    <input type="number" class="form-control" name='length' id="length" placeholder="<?=lang('lang_Length');?>" value="<?= $item->length; ?>">
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Width');?>:</strong></label>
                                    <input type="number" class="form-control" name='width' id="width" placeholder="<?=lang('lang_Width');?>" value="<?= $item->width; ?>" >
                                   
                                </div>
                                
                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Height');?>:</strong></label>
                                    <input type="number" class="form-control" name='height' id="height" placeholder="<?=lang('lang_Height');?>" value="<?=$item->height;?>">
                                   
                                </div>

                                <div class="form-group">
                                    <label for="alert_day"><strong><?=lang('lang_Weight');?>:</strong></label>
                                    <input type="number" class="form-control" require name='weight' step="any" id="weight"  min="0.01" max="15" placeholder="<?=lang('lang_Weight');?>" value="<?=$item->weight;?>">
                                   
                                </div>


                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><strong><?=lang('lang_Description');?>:</strong></label>
                                        <textarea rows="5" id="description" name="description" class="form-control" placeholder="<?=lang('lang_Description');?>" required><?= $item->description; ?></textarea>

                                    </div>
                                    <div class="form-group">
                                        <label for="description"><strong><?=lang('lang_Item_Image');?>:</strong></label>
                                        <input type="file" id="item_path" name="item_path"  class="form-control" ng-model="item.item_path">
                                        <input type="hidden" class="form-control" name='old_item_path'  id="old_item_path" value="<?= $item->item_path; ?>">

                                    </div>




                                    <button type="submit" class="btn btn-primary pull-right"> <?=lang('lang_Edit');?></button>
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

<link href="<?=base_url();?>assets/colorpicker/jquery.colorpicker.bygiro.min.css" rel="stylesheet">
    <script src="<?=base_url();?>assets/colorpicker/jquery.colorpicker.bygiro.min.js"></script>

	<script>
	function Getchangcolor()
	{
		$('.myColorPicker').colorPickerByGiro({
			preview: '.myColorPicker-preview',
            showPicker:false,
            format:'hex',
			
			
            sliderGap: 6,

  cursorGap: 6,
            text: {

    close:'Close',

    none:'None'

  }


		});
	}
	</script>
    </body>
</html>