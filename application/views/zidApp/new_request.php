<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>ZID New Request</title>
        <?php $this->load->view('include/file'); ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/angular/ZidApp.app.js?v=<?= time(); ?>"></script>
    </head>
    <body ng-app="ZidApp" ng-controller="ZIdAppCtlr" ng-init="showasallatemplatelist('');" >
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
                            <div class="panel-heading"><h1><strong>Zid New Request</strong></h1></div>
                            <hr>
                            <div class="panel panel-flat">

                                <div class="panel panel-flat" >

                                    <div class="panel-body" >
                                        <div class="table-responsive" style="padding-bottom:20px;" >
                                            <!--style="background-color: green;"-->
                                            <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th><?= lang('lang_SrNo'); ?>.</th>
                                                        <th>Name</th>
                                                       
                                                        <th>Email.</th>
                                                        <th>Mobile No</th>
                                                         <th>Status</th>
                                                        <th>Date</th>
                                                        <th class="text-center" ><i class="icon-database-edit2"></i></th>
                                                    </tr>  
                                                </thead>  
                                                <tr ng-if='UserArr.listdata != 0' ng-repeat="data in UserArr.listdata"> 
                                                    <td>{{$index + 1}} </td>
                                                    <td>{{data.name}}</td> 
                                                  
                                                    <td>{{data.email}}</td>
                                                    <td>{{data.mobile}}</td>
                                                   
                                                    <td>
                                                        <span class="badge badge-primary ng-binding ng-scope" ng-if="data.status == 'A'">Accepted</span>
                                                        <span class="badge badge-danger ng-binding ng-scope" ng-if="data.status == 'R'">Rejected</span> 
                                                        <span class="badge badge-info ng-binding ng-scope" ng-if="data.status == 'L'">Linked</span> 
                                                        <span class="badge badge-warning ng-binding ng-scope" ng-if="data.status == 'N'">New Request</span> 
                                                    </td> 
                                                    <td >{{data.update_date}}</td>
                                                    <td class="text-center">
                                                        <ul class="icons-list">
                                                            <li class="dropdown">
                                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-menu9"></i></a>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    <li> <a ng-if="data.status == 'N'" class="dropdown-item" ng-click="sallaupactive(data.id, 'R');" ><i class="mdi mdi-chevron-down"></i>  Rejected </a> </li>
                                                                    <li> <a ng-if="data.status == 'N'" class="dropdown-item" ng-click="sallaupactive(data.id, 'A');" ><i class="mdi mdi-chevron-down"></i>  Accepted</a> </li> 
                                                                    <!-- <li> <a ng-if="data.status == 'N'" class="dropdown-item" ng-click="sallaupactive_new(data, 'A');" ><i class="mdi mdi-chevron-down"></i>  Accept & Activate</a> </li>  -->
                                                                    <li> <a ng-if="data.status == 'N'" class="dropdown-item" ng-click="addNewCustomerPop(data, 'A',$index);"  ><i class="mdi mdi-chevron-down"></i>  Accept & Activate</a> </li>                                                                     
                                                                    <li><a ng-if="data.status == 'A'" ng-click="linkToSalla($index, data.id);"  ><i class="icon-pencil7"></i>Link To ZID</a></li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                            <button ng-hide="shipData.length == totalCount" class="btn btn-info" ng-click="loadMore(count = count + 1, 0);" ng-init="count = 1"><?= lang('lang_Load_More'); ?></button>
                                            
                                            <br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->load->view('include/footer'); ?>
                    </div>
                    <!-- /content area -->
                </div>
                <!-- /main content -->
            </div>
            <!-- /page content -->

            <div class="modal fade" id="addcustomerModal" tabindex="-1" role="dialog" aria-labelledby="addcustomerModal" aria-hidden="true">
               <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel" dir="ltr"><?= lang('lang_Assign_TO'); ?>{{uniqueid}} </h5>
                            <button type="button" class="close" ng-click="close_model();" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                           
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>

                            <div class="loader logloder" ng-show="loadershow"></div>
                            
                          
                            <div style="padding-top:50px;">
                                    <b>Name</b>
                                    <div>
                                        <input type="text" class="form-control" ng-model="newCustArr.main.name"   placeholder="Name"  required  >
                                    </div>
                                </div> 
                             <div style="padding-top:50px;">
                                    <b>Mobile</b>
                                    <div>
                                        <input type="text" class="form-control" ng-model="newCustArr.main.mobile"  placeholder="Mobile" required  >
                                    </div>
                                </div> 
                             <div style="padding-top:50px;">
                                    <b>Email</b>
                                    <div>
                                        <input type="text" class="form-control" ng-model="newCustArr.main.email"   placeholder="Email" required  >
                                    </div>
                                </div> 
                             <div style="padding-top:50px;">
                                    <b>Password</b>
                                    <div>
                                        <input type="password" class="form-control" ng-model="newCustArr.main.password"   placeholder="Enter Password" required  >
                                    </div>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" ng-click="close_model();"  ><?= lang('lang_Close'); ?></button>
                            <button  type="button" class="btn btn-primary" ng-click="sallaupactive_new(newCustArr.main,newCustArr.type_new);" >Assing ZID</button>
                        </div>
                              
                    </div>
                </div>
            </div>

            <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel" dir="ltr"><?= lang('lang_Assign_TO'); ?>{{uniqueid}} </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        
                        <div class="modal-body">
                            <div class="alert alert-warning" ng-if="message.error" id="errhide">{{message.error}}</div>
                            <div class="alert alert-success" ng-if="message.success">{{message.success}}</div>
                            <div class="col-md-12" ng-if="Success_msg">
                                <div class="alert alert-success" ng-repeat="success_msg in Success_msg">{{success_msg}} : <?= lang('lang_Shipment_Forwarded'); ?></div>
                            </div>
                            <div class="col-md-12" ng-if="Error_msg">
                                <div class="alert alert-danger" ng-repeat="error_msg in Error_msg">{{error_msg}}</div>
                            </div>

                            <div class="loader logloder" ng-show="loadershow"></div>
                            <div class="col-md-12" ng-if="mainstatusEmpty">
                                <div class="alert alert-danger" ng-if="mainstatusEmpty">{{mainstatusEmpty}}</div>
                            </div>
                            <div class="col-md-12" ng-if="messArray1 != 0">
                                <div class="alert alert-danger" ng-repeat="mdata in messArray1"><?= lang('lang_wrong_AWB_no'); ?> {{mdata}}</div>    
                            </div>
                            <form novalidate ng-submit="myForm.$valid && createUser()" >
                                <input type="radio" name="assign_type" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('ADD');" value="ADD"  > Add New Cutomer <input type="radio" name="assign_type" value="Link" ng-model="AssignData.assign_type" ng-click="GetChangeAssignType('Link');"  > Link Customer
                                <div ng-show="addbutton" style="padding-top:50px;">
                                    <a  class="btn btn-primary" href="<?= base_url(); ?>Seller/add_view"><i class="mdi mdi-chevron-down"></i>Add New Customer</a> 
                                </div>    
                                <div ng-show="linkbutton"  style="padding-top:50px;">
                                    <b>Select <?= lang('lang_Seller'); ?></b>
                                    <div>
                                        <select type="text"  ng-model="AssignData.customer_id" class="form-control" required>
                                            <option ng-repeat="cx in UserArr.customer"  value="{{cx.id}}">{{cx.name}}/{{cx.uniqueid}}</option>
                                        </select>
                                    </div>
                                </div>
<!--                                <div ng-show="linkbutton"  style="padding-top:50px;">
                                    <b>Shipping Cost</b>
                                    <div>
                                        <input type="number" class="form-control" name="cost" id="cost" ng-model="AssignData.salla_shipping_cost"  value="" pacleholder="Shipping Cost" required  >
                                    </div>
                                </div>    -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" ><?= lang('lang_Close'); ?></button>
                            <button id="assing_btn" ng-if="AssignData.assign_type == 'Link'" type="button"   class="btn btn-primary" ng-click="saveassigntosalla();" >Assing ZID</button>
                        </div>
                        </form>          
                    </div>
                </div>
            </div>
        </div>
        <!-- /page container -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#cost").keyup(function () {
                    if ($(this).val() > 0) {
                        $("#assing_btn").removeAttr('disabled');
                    } else {
                        $("#assing_btn").attr('disabled', true);
                    }
                })

            })

        </script>
    </body>
</html>