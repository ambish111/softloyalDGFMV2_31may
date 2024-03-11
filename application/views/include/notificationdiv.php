
   <?php if(!empty($notiArr)){ ?>
<div class="notification" style="position: fixed;
    width: 100%;
    z-index: 2;
">
      
    <div class="alert alert-danger " style="margin-top: -55px;font-size: large;">Please Note: <b><?=strip_tags($notiArr['notification_desc']);?></b> </div>
  
</div>
 <?php } ?>