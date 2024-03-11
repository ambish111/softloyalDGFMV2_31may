<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Add_New_User');?></title>
        <?php $this->load->view('include/file'); ?>


    </head>
<?php  if(!empty($EditData)){
$countyname="'".$EditData['country']."'";
}
?>
    <body ng-app="CountryApp" ng-controller="CountrypageCrl" ng-init="<?php  if(!empty($EditData)){echo'getStatelist('.$countyname.');'; }?>">

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
                            <div class="panel-heading"><h1><strong> <?php if(!empty($EditData)){ echo 'Edit';} else { echo 'Add';}?> <?=lang('lang_City');?></strong></h1></div>
                            <hr>
                            <div class="panel-body">
                                <?php if (!empty(validation_errors())) echo'<div class="alert alert-warning" role="alert"><strong>Warning!</strong> ' . validation_errors() . '</div>'; ?>
                                <?php
                                if ($this->session->flashdata('errormess') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('errormess') . '.</div>';
                                }
                                ?>

                                <form action="<?= base_url('country/addcitybtn'); ?>" name="adduser" method="post">

                                    <input type="hidden" id="id" name="id" value="<?=$EditData['id'];?>">
                                    
                                     <div class="form-group">
                                        <label for="country" ><strong><?=lang('lang_Country');?>:</strong></label>
                                        <select name="country" class="form-control" id="country" ng-model="country" ng-change="getStatelist(country);">
                                            
                                            <option value=""><?=lang('lang_select');?></option>
                                            <?php 
                                            
                                            foreach($Countrylist as $val)
                                            {
                                                if($EditData['country']==$val['country'])
                                                {
                                                   echo '<option value="'.$val['country'].'" selected>'.$val['country'].'</option>'; 
                                                }
                                                else
                                                {
                                             echo '<option value="'.$val['country'].'">'.$val['country'].'</option>';
                                                }
                                            }
                                                     ?>
                                        </select>
                                    </div> 
                                    
                                     <div class="form-group">
                                        <label for="state" ><strong><?=lang('lang_select');?> <?=lang('lang_Hub');?>:</strong></label>
                                        <select name="state" class="form-control" id="state" ng-model="state" >
                                            
                                            <option value=""><?=lang('lang_select');?> <?=lang('lang_Hub');?></option>
                                            <option ng-repeat="cdata in stateListArr" value="{{cdata.state}}">{{cdata.state}}</option>
                                          
                                        </select>
                                    </div> 
                                    <div class="form-group">
                                        <label for="state"><strong><?=lang('lang_City');?> <?=lang('lang_Name');?>:</strong></label>
                                       <input type="text" class="form-control" name='city' id="country" placeholder="Enter City" value="<?=$EditData['city'];?>">
                                    </div> 
                                      <div class="form-group">
                                        <label for="city_code"><strong><?=lang('lang_City_Code');?>:</strong></label>
                                       <input type="text" class="form-control" name='city_code' id="country" placeholder="Enter City Code" value="<?=$EditData['city_code'];?>">
                                    </div> 
                                   
                             
                                    
                                   


                                    <div style="padding-top: 20px;">
                                        <button type="submit" class="btn btn-success"><?=lang('lang_Submit');?></button>
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
<script type="application/javascript">
var app = angular.module('CountryApp', [])
.controller('CountrypageCrl', function($scope,$http,$window,$location) {
    $scope.filterData={};
     $scope.stateListArr={};
   
     $scope.country="<?=$EditData['country']?>";
      $scope.state="<?=$EditData['state']?>";
    $scope.getStatelist=function(country)
    {
        
        $scope.filterData.country=$scope.country;
         $http({
		url: "<?=base_url()?>Country/getStatelistDrop",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           $scope.stateListArr=response.data;
        });
    }
 
});
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
