<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Inventory</title>
        <?php $this->load->view('include/file'); ?>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">


        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <script src="<?= base_url(); ?>assets/js/bootstrap5-custom-pagination.js"></script>
        <style>

            tbody:empty:after{
                content:'No records found'
            }

        </style>
    </head>
    <body>
        <?php $this->load->view('include/main_navbar'); ?>
        <!-- Page container -->
        <div class="page-container" id="opacity"  ng-app="SallOrderApp" ng-controller="tblController">

            <!-- Page content -->
            <div class="page-content">

                <?php $this->load->view('include/main_sidebar'); ?>


                <!-- Main content -->
                <div class="content-wrapper" >
                    <!--style="background-color: black;"-->
                    <?php $this->load->view('include/page_header'); ?>



                    <!-- Content area -->
                    <div class="content" >
                        <div class="loader logloder" ng-show="loadershow"></div>
                        <div class="row" >
                            <div class="col-lg-12" >

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h1>
                                            <strong>Salla Orders</strong>
                                        </h1>

                                    </div>



                                    <div class="panel-body" >
                                        <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;">
                                            <div class="col-md-3"> 
                                                <div class="form-group" ><strong>Choose Seller:</strong>
                                                    <br>
                                                    <?php $salla_customer = GetSallaallCustomers(); ?>
                                                    <select  class="selectpicker" ng-model="filter_data.cus_id" data-show-subtext="true" data-live-search="true"  data-width="100%" >

                                                        <option value="">Choose Seller</option>
                                                        <?php foreach ($salla_customer as $data): ?>
                                                            <?php if($data['salla_access']=='FM') {?>
                                                            <option value="<?= $data['uniqueid']; ?>">
                                                                <?= $data['name']; ?>
                                                            </option>
                                                            <?php } ?>
                                                        <?php endforeach; ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3"> 
                                                <div class="form-group" ><strong>Reference ID:</strong>
                                                    <br>
                                                    <input  type="text" class="form-control"  ng-model="filter_data.order_id" placeholder="Reference ID">
                                                </div>
                                            </div>
                                            <div class="col-md-3"> 
                                                <div class="form-group" ><strong>From Date:</strong>
                                                    <br>
                                                    <input  class="form-control date"  ng-model="filter_data.from_date" placeholder="From Date" >   
                                                </div>
                                            </div>
                                            <div class="col-md-3"> 
                                                <div class="form-group" ><strong>To Date:</strong>
                                                    <br>
                                                    <input  class="form-control date" ng-model="filter_data.to_date" placeholder="To Date" >   
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group" >
                                                    <button type="bbutton"  class="btn btn-warning" ng-click="get_filter();" ><?= lang('lang_Search'); ?></button>
                                                    <button type="bbutton"  class="btn btn-danger" >{{total_show}}</button>
                                                    
                                                    <button type="button" class="btn btn-success" ng-if="showList.length>0" ng-click="allSallaPush(showList);">Sync </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="alert alert-danger mb-3 rounded-0" ng-show="error">{{error_msg}}</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <input type="checkbox" name="update_all[]" ng-modal="data.checked" ng-click="selectAllFriends()"></th>
                                                <th class="text-center">Order ID</th>
                                                <th class="text-center">AWB No.</th>
                                                <th class="text-center">Reference ID</th>
                                                <th class="text-center">Order Status</th>
                                                <th class="text-center">Company</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr ng-repeat="data in showList">
                                                <td class="text-center">  
                                                    <input type="checkbox" ng-if="data.fs_awb!= 'NO'" disabled />
                                                    <input type="checkbox" ng-if="data.fs_awb== 'NO'"  ng-model="data.checked" value="{{data.reference_id}}"  />
                                                </td>
                                                <td class="text-center">{{data.id}} </td>
                                                <td class="text-center">{{data.fs_awb}}</td>
                                                <td class="text-center">{{data.reference_id}}</td>
                                                <td class="text-center">{{data.status.name}}/{{data.status.slug}}</td>
                                                <td class="text-center">{{data.shipping.company}}</td>
                                                <td class="text-center"> {{data.date.date}}</td>
                                                <td class="text-center">
                                                    <span ng-if="data.fs_awb=='NO'"  class="btn btn-warning">Pending</span>
                                                <span  ng-if="data.fs_awb!='NO'"  class="btn btn-success " >Success</span>
                                                
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                               
                                <div style="padding: 1%;float: right;" class="bootstrap5-custom-pagination"  
                                     data-max-length="5"
                                     data-number-of-pages="{{NumberOfPages}}"
                                     data-current-page="{{currentPage}}"
                                     data-btn-callback="paginate">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?= base_url(); ?>assets/js/app_new.js?v=<?= time(); ?>"></script>
        <script type="text/javascript">
                $('.date').datepicker({format: 'dd-mm-yyyy'});
        </script>
    </body>
</html>