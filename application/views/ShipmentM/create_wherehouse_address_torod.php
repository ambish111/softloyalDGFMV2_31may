<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>

        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
    </head>

    <body ng-app="fulfill" >

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="shipment_view" ng-init="getAddressList();" >

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>

                    <div class="content" >
                    
                                        
                  
                     
                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1>
                                            <strong>Add Warehouse address Torod</strong>
                                         

                                        </h1>
                                        <?php  
                                            if($this->session->flashdata('error')){ ?>
                                                    <div class="alert alert-danger"><?php echo $this->session->flashdata('error') ?></div>
                                        <?php    }
                                            if($this->session->flashdata('msg')){ ?>
                                                        <div class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>
                                        <?php     }  ?>

                                        <div class="row" style="margin-top:10px">
                            
                                            <div class="col-md-12" ng-if="Success_msg">
                                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?=lang('lang_Shipment_Forwarded');?></div>
                                            </div>
                                            <div class="col-md-12" ng-if="Error_msg">
                                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                                            </div>
                                            
                                        
                                        </div>
                                    </div>
                                 </div>
                                <div class="panel panel-flat" >
                                    <div class="panel-body" >   
                                        <div class="table-responsive" style="padding-bottom:20px;" >
                                                       <div class="col-md-12"><div class="form-group" >
                                                       
                                                      <a href="<?= base_url(); ?>add_address" class="btn btn-success"><i class="icon-button"></i> Add Warehouse </a>
                                                    
                                                    </div>   
                                                    
                                                    
                                                <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                                    <thead>  
                                                                                     
                                                            <th>Warehouse Name</th>
                                                            <td>Warehouse </td>
                                                            <td>Contact Name</td>
                                                            <td>Phone Number</td>
                                                            <td>Email</td>
                                                            <td>Zip_code</td>
                                                            <td>City</td> 
                                                            <td>Locate Address</td> 
                                                            <th class="text-center" ><i class="icon-database-edit2"></i></th>  
                                                       
                                                    </thead>
                                                    <tbody>

                                                        <tr ng-repeat="warehouse in torodwarehouse" > 

                                                            <td id="wname_{{warehouse.id}}">{{warehouse.warehouse_name}} </td>
                                                            <td  id="wh_{{warehouse.id}}">{{warehouse.warehouse}}</td>
                                                            <td  id="cname_{{warehouse.id}}">{{warehouse.contact_name}}</td>
                                                            <td  id="pnumber_{{warehouse.id}}">{{warehouse.phone_number}}</td>
                                                            <td  id="email_{{warehouse.id}}">{{warehouse.email}}</td>
                                                            <td  id="zip_{{warehouse.id}}">{{warehouse.zip_code}}</td>
                                                            <td  id="city_{{warehouse.id}}">{{warehouse.city}}</td> 
                                                            <td  id="address_{{warehouse.id}}">{{warehouse.locate_address}}</td> 
                                                            <td class="text-center">
                                                      <ul class="icons-list">
                                                       <li class="dropdown">
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                            <i class="icon-menu9"></i>
                                                        </a>   

                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            <li>  <a href="javascript:;" data-whid="{{warehouse.id}}" onclick="edit_show_modal(this)"><i class="icon-pencil7"></i> Edit </a></li> 
                                                        </ul>
                                                       </li>
                                                      </ul>
                                                     </td>
                                                        </tr>
                                        
                                                    </tbody>

                                                </table>
                                        </div>
                                      </div>
                                        <!-- /quick stats boxes -->
                                 </div>
                              </div>
                            </div>
                        </div>
                        <!-- /dashboard content -->
                    </div>   <!-- Basic responsive table -->
                    <?php $this->load->view('include/footer'); ?>
                </div>
                
           </div>
        </div>
        <div class="modal fade" id="modalwharehousForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Update Wharehouse Address</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form name="wh_frm" method="POST" action="<?php echo base_url('Shipment/update_sh_address'); ?>">
                    <div class="modal-body mx-3">
                        <div class="md-form mb-5">
                            <i class="grey-text"></i>
                            <input type="text" name="wname" id="wname" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-name">Wharehouse Name</label>
                        </div>
                        <div class="md-form mb-5">
                            <i class="grey-text"></i>
                            <input type="text" name="wh_" id="wh_" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-name">Wharehouse</label>
                        </div>

                        <div class="md-form mb-4">
                            <i class="grey-text"></i>
                            <input type="text" name="cname_" id="cname_" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-pass">Contact Name</label>
                        </div>
                        <div class="md-form mb-4">
                            <i class="grey-text"></i>
                            <input type="text" name="email_" id="email_" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-pass">Email</label>
                        </div>
                        <div class="md-form mb-4">
                            <i class="grey-text"></i>
                            <input type="text" name="pnumber_" id="pnumber_" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-pass">Phone Number</label>
                        </div>
                        <div class="md-form mb-4">
                            <i class="grey-text"></i>
                            <input type="text" name="zip_" id="zip_" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-pass">Zipcode</label>
                        </div>
                        
                        <div class="md-form mb-4">
                            <i class="grey-text"></i>
                            <input type="text" name="address_" id="address_" class="form-control validate">
                            <label data-error="wrong" data-success="right" for="orangeForm-pass">Address</label>
                        </div>
                        <input type="hidden" name="whid" id="whid" >   

                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>

               

            <script type="text/javascript">
                function edit_show_modal(obj){
                    let whid = $(obj).data('whid');
                    let wname = $("#wname_"+whid).html();
                    let wh_ = $("#wh_"+whid).html();
                    let cname_ = $("#cname_"+whid).html();
                    let pnumber_ = $("#pnumber_"+whid).html();
                    let zip_ = $("#zip_"+whid).html();
                    let address_ = $("#address_"+whid).html();
                    let email_ = $("#email_"+whid).html();
                    
                    $("#whid").val(whid);
                    $("#wname").val(wname);
                    $("#wh_").val(wh_);
                    $("#cname_").val(cname_);
                    $("#pnumber_").val(pnumber_);
                    $("#zip_").val(zip_);
                    
                    $("#address_").val(address_);
                    $("#email_").val(email_);
                    $("#modalwharehousForm").modal('show');

                }

            </script>
    </body>
</html>
