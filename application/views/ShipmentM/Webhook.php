<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
    <title>Webhook status</title>
    <?php $this->load->view('include/file'); ?>
    <script src="<?= base_url(); ?>assets/js/angular/courier_company.js"></script>
</head>

<body ng-app="CourierAppPage" ng-controller="CourierComapnyCRL">
    <?php $this->load->view('include/main_navbar'); ?>

    <!-- Page container -->
    <div class="page-container" ng-init="webhookcourier(1,0);">

        <!-- Page content -->
        <div class="page-content">
            <?php $this->load->view('include/main_sidebar'); ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <!--style="background-color: black;"-->
                <?php $this->load->view('include/page_header'); ?>

                <!-- Content area -->
                <div class="content">
                    <!--style="background-color: red;"-->
  <!-- Button trigger modal -->


                    <!-- <div class="loader logloder" ng-show="loadershow" ></div> -->
                    <!-- Dashboard content -->
                    <div class="row">
                        <div class="col-lg-12">

                            <!-- Marketing campaigns -->
                            <div class="panel panel-flat">
                           
                                <div class="panel-heading">
                                    <h1> <strong>
                                            Webhook Company
                                        </strong> </h1>
                                </div>


                                <div>
                                    <nav class="navbar navbar-light bg-light">
  <form class="form-inline" method="post" action="" >
  <div class="col-md-3"> <div class="form-group" ><strong><?= lang('lang_company'); ?>:</strong>
                                                        <br>
                                                        <?php
                                                        //$destData = getAllDestination();
                                                        //print_r($destData);
                                                        ?>
                                                        <select  id="cc_id" name="cc_id"  ng-model="filterData.cc_id" multiple data-show-subtext="true" data-live-search="true" class="selectpicker" data-width="100%" >

                                                            <option value=""><?= lang('lang_Select_Company'); ?></option>
                                                            <?php foreach (GetCourierCompanyDrop() as $data): ?>
                                                                <option value="<?= $data['cc_id']; ?>"><?= $data['company']; ?></option>
                                                            <?php endforeach; ?>

                                                        </select>
                                                    </div> 
    <button class="btn btn-primary"  ng-click="webhookcourier(1,1)" type="button">Search</button>
  </form>
</nav>
                                <button type="button" class="btn btn-primary" style=margin:20px; data-toggle="modal" ng-click="GetCompanylistDrop();"
                                    data-target="#exampleModal">
                                    ADD /EDIT webhook URL
                                </button>

                                </div>
                                
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">id</th>
                                            <th scope="col">COMPANY</th>
                                            <th scope="col">WEBHOOK URL</th>
                                            <th scope="col">WEBHOOK STATUS</th>
                                            <th scope="col">ACTIVE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-if='item != 0' ng-repeat="item in webhookListArr">
                                            
                                             <td>{{$index + 1}}</td>
                                            <td>{{item.company}}</td>
                                            <td>{{item.webhook_url}}</td>
                                            <td>{{item.webhook_status}}</td>
                                            <td> 
                                            <p ng-if="item.webhook_status === 'Y'">Active</p>
                                            <p ng-if="item.webhook_status === 'N'">Inactive</p>
                                            <!-- <select  ng-change="updatewebhookstatus(item.company);" ng-model="item.webhook_status" >
                                                <option  value="Y"  ng-selected="item.webhook_status === 'Y'" >Active</option>
                                                <option value="N" ng-selected="item.webhook_status === 'N'" >Inactive</option>
                                            </select> -->
                                        </td>

                                        </tr>

                                    </tbody>

                                </table>
                                <div>   <button class="btn btn-info" ng-hide="webhookListArr.length == totalCount" ng-click="webhookcourier(count= count + 1, 0);" ng-init="count = 1"><?= lang('lang_LoadMore'); ?></button></div>
                         
                         

                              

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true" >
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">change Webhook status
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" >
                                 <form action="" method="post" enctype="multipart/form-data">
                                                    <div class="form-group" >
                                                        <label for="updatecomany">COMPANY NAME</label>
                                                      <!-- <select name="" id="" ng-repeat="item in DeliveryDropArr">
                                                        <option value="">{{$index + 1}}</option>
                                            <option value="">{{item.company}}</option>
                                                      </select> -->
                                                      <select class="form-control" id="companySelect" ng-model="EditDataArr.cc_name" required>
                                                        <option value="">All Companies</option>
                                                        <option ng-repeat="item in DeliveryDropArr" name="company" value="{{item.company}}">{{item.company}}</option>
                                                    </select>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="WEBHOOKURL">Webhook Url</label>
                             <input type="text"  class="form-control"  ng-model="EditDataArr.WEBHOOKURL" placeholder="URl" required  >
                                                    </div>

                                             <button type="button" class="btn btn-primary" ng-click="updatewebhookurl();"> SUBMIT</button>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                                <!-- <button type="button" class="btn btn-primary" ng-click="updatewebhookurl();">Save changes</button> -->
                                            </div>
                                        </div>
                                    </div>
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

    








</body>

</html>