<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
  <title><?= lang('lang_Inventory'); ?></title>
  <?php $this->load->view('include/file'); ?>


</head>

<body>

  <?php $this->load->view('include/main_navbar'); ?>


  <!-- Page container -->
  <div class="page-container" ng-app="formApp" ng-controller="formCtrl">

    <!-- Page content -->
    <div class="page-content" ng-init="loadmore(1,0)">

      <?php $this->load->view('include/main_sidebar'); ?>


      <!-- Main content -->
      <div class="content-wrapper" >
        <!--style="background-color: black;"-->
        <?php $this->load->view('include/page_header'); ?>



        <!-- Content area -->
        <div class="content" >
          <!--style="background-color: red;"-->
<?php if($this->session->flashdata('msg')):?>
<?= '<div class="alert alert-success">'.$this->session->flashdata('msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?> 
<?php elseif($this->session->flashdata('error')):?>
<?= '<div class="alert alert-danger">'.$this->session->flashdata('error').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';?>
<?php endif;?>


          <!-- Basic responsive table -->
          <div class="panel panel-flat"  >
            <!--style="padding-bottom:220px;background-color: lightgray;"-->
            <div class="panel-heading" dir="ltr">
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
              <h1><strong><?= lang('lang_Gift_Offers_List'); ?></strong></h1>

              <div class="heading-elements">
                <ul class="icons-list">
                  <<!-- li><a data-action="collapse"></a></li>
                  <li><a data-action="reload"></a></li>
                  <li><a data-action="close"></a></li> -->
                </ul>
              </div>
              <hr>

            </div>
             
            
                  

            <div class="panel-body" >
            
             <?php if($this->session->flashdata('errmsg')!=''){echo '<div class="alert alert-warning" role="alert">  '.$this->session->flashdata('errmsg').'.</div>';}?>
            <?php if($this->session->flashdata('succmsg')!=''){echo '<div class="alert alert-success" role="alert">  '.$this->session->flashdata('succmsg').'.</div>';}?>
<table class="table table-bordered table-hover" style="width: 100%;">
                    <!-- width="170px;" height="200px;" -->
                    <tbody >
                    
                      <tr style="width: 80%;">
                       <td><div class="form-group" ><strong><?= lang('lang_Promo_Code'); ?>:</strong>
                            <input type="text" id="promocode"name="promocode" ng-model="filterData.promocode"  class="form-control" placeholder="Enter Promo Code.">
                          </div></td>
                        <td><div class="form-group" ><strong><?= lang('lang_SKU'); ?>:</strong>
                            <input type="text" id="sku"name="sku" ng-model="filterData.sku"  class="form-control" placeholder="Enter SKU no.">
                          </div></td>
                       
                        
                          
                          </tr>
                          <tr>
                          <td ><div class="form-group" ><strong><?= lang('lang_Sellers'); ?>:</strong> <br>
                            <select  id="seller" name="seller_id" ng-model="filterData.seller_id" class="selectpicker" data-width="100%" >
                              <option value=""><?= lang('lang_SelectSeller'); ?></option>
                              <?php foreach($sellersArray as $seller_detail):?>
                              <option value="<?= $seller_detail['id'];?>">
                              <?= $seller_detail['company'];?>
                              </option>
                              <?php endforeach;?>
                            </select>
                          </div></td>
                          <td co><button type="button" class="btn btn-success" style="margin-left: 7%"><?= lang('lang_Total'); ?> <span class="badge">{{listArr.length}}/{{totalCount}}</span></button> <button  class="btn btn-danger" ng-click="loadmore(1,1);" ><?= lang('lang_Search'); ?></button></td>
                        
                      </tr>
                      
                    </tbody>
                  </table>
          

            <div class="table-responsive" style="padding-bottom:20px;" >
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered" id="example">
                <thead>
                  <tr>
                  <th><?= lang('lang_SrNo'); ?>.</th>
                   <th><?= lang('lang_Promo_Code'); ?></th>
                     <th align="center"> 
                     <?= lang('lang_Main_Item'); ?>
<!--                         <table class="table table-striped table-hover table-bordered"><tr><th>Main Item</th> <th>Gift Item</th></tr></table>-->
                             
                         
                     </th>
                     
                     <th><?= lang('lang_Offer_Item'); ?></th>
                     
                  
                    
                      <th><?= lang('lang_Sellers'); ?></th>
                    
                     
                      <th><?= lang('lang_Create_By'); ?></th>
                      <th><?= lang('lang_Updated_Date'); ?></th>
                       
                        <th><?= lang('lang_Status'); ?></th>
                    <!-- <th>Category</th> -->
                    <th class="text-center" ><i class="icon-database-edit2"></i></th>
                  </tr>
                </thead>
                <tbody>
                
                  
                      <tr ng-if="listArr!=0" ng-repeat="data in listArr">
                      <td>{{$index+1}}</td>
                      <td>{{data.promocode}}</td>
                      <td><span s class="label bg-success-400" ng-repeat="mdata in data.SkuArr">{{mdata.main_item}}<br></span><br></td>
                     
                     <td><span ng-repeat="gdata in data.gift_item" class="label bg-primary-400">{{gdata}}<br></span>
                         
                      
                         <br></td>
                      
                       <td>{{data.seller_name}}</td>
                    
                      <td>{{data.username}}</td>
                      <td>{{data.entrydate}}</td>
                      
                     
                     
                       <td><span class="label bg-success-400" ng-if="data.status=='Y'"><?= lang('lang_active'); ?></span><span class="label bg-warning-400" ng-if="data.status=='N'"><?= lang('lang_inactive'); ?></span></td>
                     
                      
                    
                    
                      <td class="text-center">
                        <ul class="icons-list">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="icon-menu9"></i>
                            </a>


                            <ul class="dropdown-menu dropdown-menu-right">
                              <li><a href="<?=site_url();?>Offers/edit_offer_gift/{{data.promocode}}"><i class="icon-pencil7"></i> <?= lang('lang_Edit'); ?> </a></li>
                              <li ng-if="data.status=='N'"><a href="<?=site_url();?>Offers/Inactive_gift/{{data.promocode}}/Y"><i class="icon-unlocked"></i> <?= lang('lang_active'); ?> </a></li>
                              <li ng-if="data.status=='Y'"><a href="<?=site_url();?>Offers/Inactive_gift/{{data.promocode}}/N"><i class="icon-lock4"></i> <?= lang('lang_inactive'); ?> </a></li>
                            
                            </ul>
                          </li>
                        </ul>
                      </td>
                    </tr>
             
                 
               
              </tbody>
            </table>
             <button ng-hide="listArr.length==totalCount" class="btn btn-info" ng-click="loadmore(count=count+1,0);" ng-init="count=1"><?= lang('lang_LoadMore'); ?></button>
           
           </div>
            <hr>
         </div>
       </div>
       <!-- /basic responsive table --> 
         <?php $this->load->view('include/footer'); ?>
       
     </div>
     <!-- /content area -->
   
   </div>
   <!-- /main content -->

 </div>
 <!-- /page content -->

</div>
<!-- /page container -->
<script>
var app = angular.module('formApp', []);
app.controller('formCtrl', function ($scope,$http,$interval,$window) {
	
	$scope.filterData={};
  $scope.listArr=[];   
	$scope.loadmore=function(page_no,reset)
	{
		if(reset==1)
      {
      $scope.listArr=[];
      }
		$http({
			url: "<?=base_url();?>Offers/Getofferlistdata_gift",
			method: "POST",
			data:$scope.filterData,
		   headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
		   .then(function (response) {
			   $scope.totalCount= response.data.count;
		   // console.log(response)
		 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                           
                                $scope.listArr.push(value);

                        });
		 }
		//$scope.itemdata=response.data; 
		});
	}

});
</script> 

</body>
</html>