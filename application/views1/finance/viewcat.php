<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
<script src="<?=base_url();?>assets/js/angular/finance.app.js"></script>
</head>

<body ng-app="Appfinance" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" ng-controller="CTR_catlistfinance" ng-init="loadMore(1,0);">

<!-- Page content -->
<div class="page-content">
<?php $this->load->view('include/main_sidebar'); ?>

<!-- Main content -->
<div class="content-wrapper" >
<!--style="background-color: black;"-->
<?php $this->load->view('include/page_header'); ?>

<!-- Content area -->
<div class="content" >
<!--style="background-color: red;"-->
<?php 
if($this->session->flashdata('succmsg'))
echo '<div class="alert alert-success">'.$this->session->flashdata('succmsg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

if($this->session->flashdata('errormess'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('errormess').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
?>
<div class="row" >
<div class="col-lg-12" >

<!-- Marketing campaigns -->
<div class="panel panel-flat">
<div class="panel-heading">
  <h1> <strong><?=lang('lang_All_Storage_Type');?> </strong> 
    <!--  <a  ng-click="exportmanifestlist();" ><i class="icon-file-excel pull-right" style="font-size: 35px;"></i></a>--> 
    <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>--> 
  </h1>
</div>
<form ng-submit="dataFilter();">
<!-- href="<?// base_url('Excel_export/shipments');?>" --> 
<!-- href="<?//base_url('Pdf_export/all_report_view');?>" --> 
<!-- Quick stats boxes -->
<div class="table-responsive " >
  <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 
    
    <!-- Today's revenue --> 
    
    <!-- <div class="panel-body" > -->
    
    
    <br>
    <div id="today-revenue"></div>
    <!-- </div> panel-body--> 
    
    <!-- /today's revenue --> 
    
  </div>
</div>

<!-- /quick stats boxes -->
</div>
</div>
</div>
<div class="panel panel-flat" >
  <div class="panel-body" >
    <div class="table-responsive" style="padding-bottom:20px;" > 
      <!--style="background-color: green;"-->
      <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
        <thead>
          <tr>
          <th><?=lang('lang_Sr_No');?></th>
            <th><?=lang('lang_name');?></th>
           <!--  <th>Type</th> -->
<!--            <th>Created Date</th>-->
            <th><?=lang('lang_Description');?></th>
<!--             <th class="text-center" ><i class="icon-database-edit2"></i></th>-->
            
          </tr>
          
        </thead>
        <tr ng-if='showlistData!=0' ng-repeat="data in showlistData">
          <td>{{$index+1}} </td>
          <td>{{data.name}}</td>
         <!--  <td>{{data.type}}</td> -->
<!--          <td > {{data.entrydate}}</td>-->
          <td > {{data.description}}</td>
<!--           <td class="text-center"><ul class="icons-list">
              <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-menu9"></i> </a>
                <ul class="dropdown-menu dropdown-menu-right">
                <li ><a href="<?=base_url();?>editcatview/{{data.id}}" ><i class="icon-eye" ></i> Edit</a></li>
                 <li ng-if="data.status=='Y'"><a onclick="return confirm('Are you sure you want to Inactive  this category?');" href="<?=base_url();?>Finance/Activein/{{data.id}}/N" ><i class="icon-lock" ></i> Inactive</a></li>
                   <li ng-if="data.status=='N'"><a onclick="return confirm('Are you sure you want to Inactive  this category?');" href="<?=base_url();?>Finance/Activein/{{data.id}}/Y" ><i class="icon-unlocked" ></i> Active</a></li>
                  
                  
                
                  
                </ul>
              </li>
            </ul></td>-->
         
        </tr>
      </table>
     <!-- <button ng-hide="showlistData.length==totalCount" class="btn btn-info" ng-click="loadMore(count=count+1,0);" ng-init="count=1">Load More</button>-->
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

<!-- <script>
var $rows = $('tbody tr');
$('#search').keyup(function() {
var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

$rows.show().filter(function() {
var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
return !~text.indexOf(val);
}).hide();
});
</script> -->

</div>
 
<!-- /page container -->

</body>
</html>
