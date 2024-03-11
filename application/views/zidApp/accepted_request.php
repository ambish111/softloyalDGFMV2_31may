<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title>Accepted Request</title>
        <?php $this->load->view('include/file'); ?>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

        <script src="<?= base_url(); ?>assets/js/angular/ZidApp.app.js?v=<?= time(); ?>"></script>
    </head>

    <body ng-app="ZidApp" ng-controller="ZIdAppCtlr" ng-init="showasallatemplatelist('A');" >
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->
        <div class="page-container"> 

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

                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" > 
                            <!--style="padding-bottom:220px;background-color: lightgray;"-->
                            <div class="panel-heading" dir="ltr"> 
                                <!-- <h5 class="panel-title">Basic responsive table</h5> -->
                                <h1><strong>Accepted Request</strong></h1>
                                <div class="heading-elements">
                                    <ul class="icons-list">

                                    </ul>
                                </div>
                                <hr>
                            </div>
                            <div class="panel-body" > 
                                
                                  <?php
                                if ($this->session->flashdata('msg') != '') {
                                    echo '<div class="alert alert-success" role="alert">  ' . $this->session->flashdata('msg') . '.</div>';
                                }
                                ?>
                                  <?php
                                if ($this->session->flashdata('err_msg') != '') {
                                    echo '<div class="alert alert-warning" role="alert">  ' . $this->session->flashdata('err_msg') . '.</div>';
                                }
                                ?>


                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?=lang('lang_SrNo');?>.</th>
                                                <th>Name</th>
                                                <th>Email.</th>
                                                <th>Mobile No</th>
                                                <th>Date</th>
                                              
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <tr ng-if='UserArr.listdata != 0' ng-repeat="data in UserArr.listdata"> 

                                    <td>{{$index + 1}} </td>

                                    
                                    <td>{{data.name}}</td> 
                                    <td>{{data.email}}</td>
                                    <td>{{data.mobile}}</td>
                                    <td >{{data.update_date}}</td>
                                        </tbody>
                                    </table>
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

        <script>

                    $('.timepicker').val('13:24:00');
        </script>

    </body>
</html>
