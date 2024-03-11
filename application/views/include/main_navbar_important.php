<!-- Main navbar -->
<?php 
$config_data_head=Getsite_configData();
$color = $config_data_head['theme_color_fm'];
$font_color = $config_data_head['font_color'];

?>
<?php
$sizeArr = $config_data_head['logo_size_fm'];
$marginArr = $config_data_head['logo_margin_fm'];
$sizeArrRow = explode(',', $sizeArr);
$width = $sizeArrRow[0];
$height = $sizeArrRow[1];
if ($width)
    $width = $width;
else
    $width = '200';

$marginArr_new=explode(',', $marginArr);
$margin_top=$marginArr_new[0];
$margin_right=$marginArr_new[1];
$margin_bottom=$marginArr_new[2];
$margin_left=$marginArr_new[3];
?>
<style>
.navigation li a {
    color:<?=$font_color;?>;
}
    .navbar-inverse{
        
        background-color:<?php echo $color; ?> !important;
    }
    .collapse{
        background-color:<?php echo $color; ?> !important;
    }
</style>
<div class="navbar navbar-inverse" style="background-color:#0B70CD;">
    <div class="navbar-header" style="margin-top:; height: 90px; background-color:#fff;"> 
      <!-- <a class="navbar-brand" href="<?= base_url('Home'); ?>" style="margin-left: 15px;
      margin-top: 9px;"><strong><span><b>Track Fastcoo </b></strong>Solution</span></a>--> 
        <img src="<?= SUPERPATH . $config_data_head['upload_site_logo']; ?>" style="width:<?= $width ?>px;height:<?= $height ?>px;margin-top: <?=$margin_top;?>px;
  margin-bottom: <?=$margin_right;?>px;
  margin-right: <?=$margin_bottom;?>px;
  margin-left: <?=$margin_left;?>px;" />
        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>
    <div class="navbar-collapse collapse" id="navbar-mobile" style=" margin-top:16px; height: 90px;background-color:#0B70CD;">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <div class="col-sm-5"  style="margin-top:-20px; margin-left: 70px;">   
            <form action="<?= base_url('TrackingResult'); ?>" method="post" name="traking"  >
                <div class="form-group">
                    <div class="col-lg-7">
                        <input  class="form-control"   autocomplete="off"   placeholder="AWB | 3PL | Ref# Number Search " name="tracking_numbers"   
                                style="color:white;" id="tracking_numbers" />
                        <!-- autofocus --> 
                    </div>

                    <div class="col-lg-2">

                        <?php
                        
                        if ($_SESSION['top_search_type'] == '3pl') {
                            $sel_top_3pl = "selected";
                        } else if ($_SESSION['top_search_type'] == 'booking_id') {
                            $sel_top_booking_id = "selected";
                        } else {
                             $sel_top_awb = "selected";
                        }
                        ?>
                        <select name="top_search_type" class="form-control" autocomplete="off" style="color:white;background-color: <?php echo $color; ?>;">
                            <option value="awb" <?=$sel_top_awb;?>>AWB</option>
                            <option value="3pl" <?=$sel_top_3pl;?>>3PL</option>
                            <option value="booking_id" <?=$sel_top_booking_id;?>>Ref#</option>
                        </select>


                    </div>
                </div>
                <div class="" >
                    <button type="submit" class="btn btn-danger" name="submit" id="submit1" value="submit"  style="margin-top:6px;"><?= lang('lang_Track_Now'); ?></button>
                </div>
            </form>
        </div>
        <ul class="nav navbar-nav navbar-right" >
            <p class="navbar-text"><span class="label bg-success">
                    Active
                </span></p>
            <li class="dropdown dropdown-user"> <a class="dropdown-toggle" data-toggle="dropdown"> 
              <!-- <img src="<? // base_url('assets/images/placeholder.jpg'); ?>" alt=""> --> 
                    <span>
<?= $this->session->userdata('user_details')['username']; ?>
                    </span> <i class="caret"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right" style="width: 229px;">

<!--                    <li><a href="<?= base_url(); ?>lessqty_alert"><span class="label bg-warning-400 pull-right"><?= Alertcountshowdata('two'); ?></span> <i class="icon-alert"></i> <?= lang('lang_Less_Quantity'); ?></a></li> 


                    <li><a href="<?= base_url(); ?>expiry_alert"><span class="label bg-warning-400 pull-right"><?= Alertcountshowdata('one'); ?></span> <i class="icon-alert"></i> <?= lang('lang_Expiry_Days'); ?></a></li>
                   
                    <li title="New Manifest"><a href="<?= base_url(); ?>shownewmanifestRequest"><span class="label bg-warning-400 pull-right"><?= GetCountUnseenManifestNew('PR'); ?></span> <i class="icon-alert"></i> <?= lang('lang_New_Manifest'); ?></a></li>
                     <li title="Not Picked Manifest"><a href="<?= base_url(); ?>showpickuplist"><span class="label bg-warning-400 pull-right"><?= GetCountUnseenManifestNew('AT'); ?></span> <i class="icon-alert"></i> <?= lang('lang_Not_Picked_Manifest'); ?></a></li>
                      <li title="Not Stock Update"><a href="<?= base_url(); ?>showmenifest"><span class="label bg-warning-400 pull-right"><?= GetCountUnseenManifestNew('PU'); ?></span> <i class="icon-alert"></i><?= lang('lang_Not_Stock_Update'); ?></a></li>
                      
                       <li title="Not Closed Manifest"><a href="<?= base_url(); ?>show_assignedlist"><span class="label bg-warning-400 pull-right"><?= GetCountUnseenManifestNew('RI','N'); ?></span> <i class="icon-alert"></i> <?= lang('lang_Not_Closed_Manifest'); ?></a></li>
                       <li title="Open Ticker FF"><a href="<?= base_url(); ?>showTicketview"><span class="label bg-warning-400 pull-right"><?= GetCountFullfilTicketStatus(); ?></span> <i class="icon-alert"></i> <?= lang('lang_Open_Ticket_FF'); ?></a></li>
                         <li title="Open Ticker Manifest"><a href="<?= base_url(); ?>showTicket"><span class="label bg-warning-400 pull-right"><?= GetCountManifestTicketStatus(); ?></span> <i class="icon-alert"></i> <?= lang('lang_Open_Ticket_Manifest'); ?></a></li>-->
                    
                    
                    
                    <li><a href="<?= base_url('update_password'); ?>"><i class="icon-switch2"></i><?= lang('lang_Update_Password'); ?></a></li>          
                    <li><a href="<?= base_url('Home/logout'); ?>"><i class="icon-switch2"></i> <?= lang('lang_Logout'); ?></a></li>
                </ul>
            </li>
            <?php if($this->session->userdata('user_details')['changeSystem']==true) { ?>
            <li class="dropdown dropdown-user" style="padding-top:7px;"> 
                <a class="dropdown-toggle" data-toggle="dropdown">
                   
                       Select System
                 
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
            <?php 
            $systemList=systemList();

            foreach($systemList as $ssval)
            {
            
            ?>

                <li><a href="<?= base_url().'System/select/'.$ssval[id]; ?>"><i class="fa fa-language" aria-hidden="true"></i> <?=$ssval['company'];?> </a></li>

                <?php } ?>
                </ul>   
                <?php } ?>
            <li class="dropdown dropdown-user" style="padding-top:7px;"> 
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <?php
                    if ($this->session->userdata('langCheck') == 'AR')
                        echo 'AR';
                    else
                        echo 'EN';
                    ?>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <?php if ($this->session->userdata('langCheck') != 'AR') { ?>

                        <li><a href="<?= base_url(); ?>LanguageS/langSwitch/AR"><i class="fa fa-language" aria-hidden="true"></i> <?= lang('lang_Arabic'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->session->userdata('langCheck') != 'EN') { ?>

                        <li><a href="<?= base_url(); ?>LanguageS/langSwitch/EN"><i class="fa fa-language" aria-hidden="true"></i> <?= lang('lang_English'); ?></a></li>
<?php } ?>
                </ul>
                </div>
                </div>
                <!-- /main navbar