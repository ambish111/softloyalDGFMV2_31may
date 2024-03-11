<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
         <link href="<?=base_url();?>assets/theme/css/icons.min.css" rel="stylesheet" type="text/css">
        <?php $this->load->view('include/file'); ?>
       
        <script src="<?= base_url(); ?>assets/js/angular/courier_company_partner.js"></script>
  
    </head>
<style type="text/css">
    .powertype{ 
        display: block;
        font-size: 10px;
    text-transform: capitalize;
    text-align: right;
    color: #ccc;
    margin-top:5px;
}
    
     .powernottype{ 
         display: block;
        font-size: 10px;
    text-transform: capitalize;
    text-align: right;
    color: #fff;
    visibility: hidden;
      margin-top:5px;
     }

     .card {
            align-items: center;
     }
     .compname{
            text-transform: capitalize;
            font-weight: bold;
            font-size: 16px;
                text-decoration-line: underline;
     }
      .card {
            align-items: center;
     }
     .compname{
            text-transform: capitalize;
            font-weight: bold;
            font-size: 16px;
                text-decoration-line: underline;
     }

</style>
    <body ng-app="CourierPartnerApp">

        <?php $this->load->view('include/main_navbar'); ?>


        <!-- Page container -->
        <div class="page-container" ng-controller="CourierComapnyPartnerCRL" ng-init="GetAllCompanyList();">

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
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                            ?> 


                        <div class="panel panel-flat" >

                            <div class="panel-heading" dir="ltr">

                                <h1><strong><?=lang('lang_Courier_Company');?> Partner</strong></h1>

                                <div class="heading-elements">
                                    <ul class="icons-list">

                                    </ul>
                                </div>
                                <hr>
                            </div>

                            <div class="panel-body" >
                                
                                <div class="row">
                                    
                                    
                                <div class="col-sm-6 col-lg-3 ng-scope" ng-repeat="data in CompanyListArr">

                                    <!-- Simple card -->
                                    <div class="card">
                                        <img class="card-img-top img-fluid" style="padding: 9px 9px 0px 21px; height:120px;" src="<?=SUPERPATH;?>{{data.image}}" alt="{{data.image}}" width="150" height="100">
                                        <div class="card-body">
                                        <a  ng-click="GetCCDetailsPopup(data.cc_id);"><p class ="compname">{{data.company}}</p></span></a>
                                            
                                            <h5 class="card-title"><span class="badge badge-warning ng-scope" ng-if="data.type == 'test'"><?=lang('lang_test_mode');?></span>
                                                <span class="badge badge-success ng-scope" ng-if="data.type == 'live'"><?=lang('lang_Live_mode');?></span>
                                                <span class="badge badge-success ng-scope" ng-if="data.status == 'Y'"><?=lang('lang_active');?></span>
                                                <span class="badge badge-warning ng-scope" ng-if="data.status == 'N'"><?=lang('lang_inactive');?></span>
                                            </h5>                                       


                                            <!-- <a href="#" class="btn btn-primary" ng-click="GetshowEditModelPOp(data);"> <i class="mdi mdi-pencil mdi-24px"></i></a> -->

                                            <!-- <a href="#" class="btn btn-primary ng-scope" ng-click="Getactivecompany(data.id,data.cc_id, 'N');" ng-confirm-click="Are you sure. this action cannot be undone" ng-if="data.fp_status == 'Y'"><i class="mdi mdi-lock mdi-24px"></i></a>
                                            <a href="#" class="btn btn-primary ng-scope" ng-click="Getactivecompany(data.id,data.cc_id, 'Y');" ng-confirm-click="Are You sure want Active" ng-if="data.fp_status == 'N'"><i class="mdi mdi-lock-open-variant mdi-24px"></i></a> -->


                                              <a href="#" class="btn btn-primary ng-scope" ng-click="Getlivemodecompany(data.id,data.cc_id, 'live');" ng-confirm-click="Are You sure want go live mode" ng-if="data.type == 'test'" title="Live Mode"><i class="mdi mdi-network-off-outline mdi-24px"></i></a>
                                              <a href="#" class="btn btn-primary ng-scope" ng-click="Getlivemodecompany(data.id,data.cc_id, 'test');" ng-confirm-click="Are You sure want go Test mode" ng-if="data.type == 'live'" title="Live Mode"><i class="mdi mdi-network-outline mdi-24px"></i></a>
                                            <p class="powertype" ng-if="data.company_type == 'F'">powered by Fastcoo</p>
                                            <p class="powernottype" ng-if="data.company_type =='O'">powered by Fastcoo</p>
                                        </div>
                                    </div>

                                </div>


 </div>

                                
                            </div>
                        </div>
                        <!-- /basic responsive table --> 
                        <?php $this->load->view('include/footer'); ?>

                    </div>
                    <!-- /content area -->


                </div>
                <!-- /main content -->
                
                <div id="ccDetailsModal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger" style="background-color:<?= DEFAULTCOLOR; ?>;border-color:<?= DEFAULTCOLOR; ?>">
                            <h6 class="modal-title">{{ccData.company}} 3pl company short introduction</h6>
                            <button type="button" class="close" data-dismiss="modal">×</button>

                        </div>

                        <div class="modal-body">
                            {{ccData.cc_description}}
                        </div>
                    </div>
                </div>


            </div>



        </div>




    </body>
    <style>.card {
    margin-bottom: 24px;
    -webkit-box-shadow: 0 0 35px 0 rgba(73,80,87,.15);
    box-shadow: 0 0 35px 0 rgba(73,80,87,.15);
}
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
*, ::after, ::before {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
    .card-img-top {
    width: 100%;
    border-top-left-radius: calc(.25rem - 1px);
    border-top-right-radius: calc(.25rem - 1px);
}
.img-fluid {
    max-width: 100%;
    height: auto;
}
img {
    vertical-align: middle;
    border-style: none;
}
.card-body {
    -webkit-box-flex: 1;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.25rem;
}
*, ::after, ::before {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
user agent stylesheet
div {
    display: block;
}
.card {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: .25rem;
}
    </style>
</html>
