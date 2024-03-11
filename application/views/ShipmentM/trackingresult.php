<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?= lang('lang_Inventory'); ?></title>
        <?php $this->load->view('include/file'); ?>
    </head>

    <body>
        <?php $this->load->view('include/main_navbar'); ?>

        <!-- Page container -->

        <div class="page-container" ng-app="fulfill" ng-controller="ExportCtrl" ng-init="slipnosdetails('<?= implode(',', $traking_awb_no); ?>');"> 

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


                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 

                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading" dir="ltr">
                                        <h1> <strong><?= lang('lang_Tracking_Parcel_List'); ?></strong>  
                                            <?php if (menuIdExitsInPrivilageArray(261) == 'Y') { ?>
                                                <a ng-click="getExcelDetails(filterData.exportlimit);" ><i class="icon-file-excel pull-right" style="font-size: 38px; padding-left: 10px;"></i></a>
                                            <?php } ?>  
                                            <span class="pull-right btn btn-danger  btn-sm" id="rowcount" style=" cursor: none;">Total (<?php echo count($shipmentdata); ?>)</span> 
                                            <?php if (menuIdExitsInPrivilageArray(163) == 'Y') { ?>
                                                <button name="track" id="trck_btn" value="Update Tracking" class="btn btn-success" style="float: right;margin-right: 10px;">Update Tracking</button>
                                                <div id="loader" class="hide" > Please wait..
                                                    <img src="<?php echo base_url(); ?>assets/images/loading.gif" style="height:20px;float: right;padding-right: 10px;padding-left: 10px;margin-right:10px;" id"loader" />
                                                <?php } ?>  
                                        </h1>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <?php
                                    $lang_Tracking_Result_for_AWB = lang('lang_Tracking_Result_for_AWB');
                                    if (!empty($traking_awb_no))
                                        echo '' . $lang_Tracking_Result_for_AWB . '#      <b>' . implode(',', $traking_awb_no) . '</b>';
                                    ?>
                                    <table class="table table-striped table-hover table-bordered dataTable" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th><b class="size-2"><?= lang('lang_Date'); ?></b></th>
                                                <th><b class="size-2">AWB</b></th>

                                                <th><b class="size-2"><?= lang('lang_Origin'); ?></b></th>
                                                <th><b class="size-2"><?= lang('lang_Destination'); ?></b></th>
                                                <th><b class="size-2"><?= lang('lang_CompanyName'); ?></b></th>
                                                <th><b class="size-2"><?= lang('lang_Pieces'); ?></b></th>
                                                <th><b class="size-2"><?= lang('lang_Weight'); ?></b></th>
                                                <th><b class="size-2"><?= lang('lang_Status'); ?></b></th>
                                                <th><b class="size-2">Back Order</b></th>
                                                <th><b class="size-2">On Hold</b></th>
                                                <th><b class="size-2"><?= lang('lang_Action'); ?></b></th>
                                            </tr>
                                            <?php
                                            $lang_View_detail = lang('lang_View_detail');
                                            //print_r($shipmentdata);
                                            if (!empty($shipmentdata)) {
                                                foreach ($shipmentdata as $awbdata) {
                                                    $status = getStatusByCode_fm($awbdata['code']);
                                                    if (empty($status)) {
                                                        $status = getallmaincatstatus($awbdata['delivered'], 'main_status');
                                                    }
                                                    echo '<tr>
                                                        <td>' . $awbdata['entrydate'] . '</td>
                                                        <td class="slipno">' . $awbdata['slip_no'] . '</td>
                                                            
                                                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                                                        <td>' . GetCourCompanynameId($awbdata['frwd_company_id'], 'company') . '</td>
                                                        <td>' . $awbdata['pieces'] . '</td>

                                                        <td>' . $awbdata['weight'] . 'Kg</td>
                                                        <td>' . $status . '</td>';
                                                    if ($awbdata['backorder'] == 1 && $awbdata['code'] == 'OG') {
                                                        echo '<td><span class="label label-danger">Yes</span></td>';
                                                    } else {
                                                        echo '<td><span class="label label-success">No</span></td>';
                                                    }
                                                    if ($awbdata['on_hold'] == 'Yes') {
                                                        echo '<td><span class="label label-danger">' . $awbdata['on_hold'] . '</span></td>';
                                                    } else {
                                                        echo '<td><span class="label label-primary">' . $awbdata['on_hold'] . '</span></td>';
                                                    }




                                                    echo' <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary" target="_black">' . $lang_View_detail . '</a></td>

                                                        </tr>';
                                                }
                                            } else {
                                                echo'<tr><td colspan="6" align="center">record not found</td></tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div id="excelcolumn" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color: <?= DEFAULTCOLOR; ?>;">
                                        <center>   <h4 class="modal-title" style="color:#000"><?= lang('lang_Select_Column_to_download'); ?></h4></center>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-4">             
                                                <label class="container">

                                                    <input type="checkbox" id='but_checkall' value='Check all' ng-model="checkall" ng-click='toggleAll()'/>   <b><?= lang('lang_SelectAll'); ?></b>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            <div class="col-md-12 row">
                                                <div class="col-sm-4">          
                                                    <label class="container">  
                                                        <input type="checkbox" name="Date" value="Date"    ng-model="listData2.entrydate"> <?= lang('lang_Date'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>   
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Reference" value="Reference"   ng-model="listData2.booking_id"><?= lang('lang_Reference'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Shipper_Reference" value="Shipper_Reference"   ng-model="listData2.shippers_ref_no"> <?= lang('lang_shipper_Refrence'); ?> #
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="AWB" value="AWB"   ng-model="listData2.slip_no"> <?= lang('lang_AWB_No'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Origin" value="Origin"  ng-model="listData2.origin"> <?= lang('lang_Origin'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Destination" value="Destination"  ng-model="listData2.destination"> <?= lang('lang_Destination'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="destination_country" value="Destination Country"  ng-model="listData2.destination_country"> Destination Country
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Sender" value="Sender"  ng-model="listData2.sender_name"><?= lang('lang_Sender'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Sender_Address" value="Sender_Address"   ng-model="listData2.sender_address"> <?= lang('lang_Sender_Address'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Sender_Phone" value="Sender_Phone"   ng-model="listData2.sender_phone"> <?= lang('lang_Sender_Phone'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Receiver" value="Receiver"   ng-model="listData2.reciever_name"> <?= lang('lang_Receiver_Name'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Recevier_Address" value="Recevier_Address"   ng-model="listData2.reciever_address"> <?= lang('lang_Receiver_Address'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Receiver_Phone" value="Receiver_Phone"   ng-model="listData2.reciever_phone"><?= lang('lang_Receiver_Mobile'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Mode" value="Mode"  ng-model="listData2.mode"> <?= lang('lang_Payment_Mode'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Status" value="Status"  ng-model="listData2.delivered"><?= lang('lang_Main'); ?> <?= lang('lang_Status'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Status_O" value="Status_O"  ng-model="listData2.status_o"> 3PL Status
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="last_status_n" value="last_status_n"  ng-model="listData2.last_status_n"> Last Status
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="COD_Amount" value="COD_Amount"   ng-model="listData2.total_cod_amt"> <?= lang('lang_COD_Amount'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>




                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="UID_Account" value="UID_Account"  ng-model="listData2.cust_id"> <?= lang('lang_UID_Account'); ?>
                                                        <span class="checkmark"></span> 
                                                    </label>
                                                </div>

                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Pieces" value="Pieces"  ng-model="listData2.pieces" > <?= lang('lang_Pieces'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Weight" value="Weight"  ng-model="listData2.weight" > <?= lang('lang_Weight'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="Description" value="Description"  ng-model="listData2.status_describtion" > <?= lang('lang_Description'); ?>
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <!-- <div class="col-sm-4">
                                                     <label class="container">
                                                         <input type="checkbox" name="Forward_through" value="Forward_through"  ng-model="listData2.frwd_throw" > Forward through
                                                         <span class="checkmark"></span> 
                                                     </label>
                                                 </div> -->
                                                <div class="col-sm-4">    
                                                    <label class="container">
                                                        <input type="checkbox" name="Forward_awb" value="Forward_awb"  ng-model="listData2.frwd_awb_no"> <?= lang('lang_Forwarded_AWB_No'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>  
                                                <div class="col-sm-4">    
                                                    <label class="container">
                                                        <input type="checkbox" name="transaction_no" value="transaction_no"  ng-model="listData2.transaction_no"> <?= lang('lang_Transaction_Number'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">    
                                                    <label class="container">
                                                        <input type="checkbox" name="invoice_details" value="invoice_details"  ng-model="listData2.invoice_details"> <?= lang('lang_invoice'); ?> <?= lang('lang_Details'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">    
                                                    <label class="container">
                                                        <input type="checkbox" name="pl3_pickup_date" value="pl3_pickup_date"  ng-model="listData2.pl3_pickup_date"> <?= lang('lang_tpl_Pickup_Date'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="frwd_date" value="frwd_date"  ng-model="listData2.frwd_date"> 3PL Forward Date
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="transaction_days" value="transaction_days"  ng-model="listData2.transaction_days"> Transaction Days
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="no_of_attempt" value="no_of_attempt"  ng-model="listData2.no_of_attempt"> <?= lang('lang_No_of_Attempt'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="cc_name" value="cc_name"  ng-model="listData2.cc_name"> <?= lang('lang_Forwarded_Company'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="close_date" value="close_date"  ng-model="listData2.close_date"> <?= lang('lang_close_date'); ?>
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="laststatus_first" value="laststatus_first"  ng-model="listData2.laststatus_first"> Failed 1st Status
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="laststatus_second" value="laststatus_second"  ng-model="listData2.laststatus_second"> Failed 2nd Status
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="laststatus_last" value="laststatus_last"  ng-model="listData2.laststatus_last"> Failed 2rd Status
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>                                    
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="fd1_date" value="fd1_date"  ng-model="listData2.fd1_date"> FD1 Date
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="fd2_date" value="fd2_date"  ng-model="listData2.fd2_date"> FD2 Date
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="container">
                                                        <input type="checkbox" name="fd3_date" value="fd3_date"  ng-model="listData2.fd3_date"> FD3 Date
                                                        <span class="checkmark"></span>    
                                                    </label>
                                                </div>

                                            </div>
                                            <input type="hidden" name="exportlimit" value="exportlimit" ng-model="listData1.exportlimit">   

                                            <div class="row" style="padding-left: 40%;padding-top: 10px;">   


                                                <button type="submit" class="btn btn-info pull-left" name="shipment_transfer" ng-click="transferShiptracking(listData2, listData1.exportlimit);"><?= lang('lang_Download_Excel_Report'); ?></button>  
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

            </div>
            <!-- /page content --> 

        </div>

        <div style="display:none;">

            <table  id="downloadtable">
                <thead>
                    <tr>
                        <th><b class="size-2"><?= lang('lang_Date'); ?></b></th>
                        <th><b class="size-2"><?= lang('lang_AWB_No'); ?>.</b></th>
                        <th><b class="size-2"><?= lang('lang_Origin'); ?></b></th>
                        <th><b class="size-2"><?= lang('lang_Destination'); ?></b></th>
                        <th><b class="size-2"><?= lang('lang_Pieces'); ?></b></th>
                        <th><b class="size-2"><?= lang('lang_Weight'); ?></b></th>
                        <th><b class="size-2"><?= lang('lang_Status'); ?></b></th>

                    </tr>
                    <?php
//print_r($shipmentdata);
                    if (!empty($shipmentdata)) {
                        foreach ($shipmentdata as $awbdata) {
                            echo '<tr>
                                                        <td>' . $awbdata['entrydate'] . '</td>
                                                             <td>' . $awbdata['slip_no'] . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                                                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                                                        <td>' . $awbdata['pieces'] . '</td>

                                                        <td>' . $awbdata['weight'] . 'Kg</td>
                                                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>

                                                       

                                                        </tr>';
                        }
                    } else {
                        echo'<tr><td colspan="6" align="center">record not found</td></tr>';
                    }
                    ?>
                </thead>
            </table>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {

                $("#trck_btn").click(function () {
                    let totle_shipment = $(".slipno").length;
                    var arr = $('.slipno').map(function () {
                        return $(this).text();
                    }).get();

                    $.ajax({
                        url: "<?= base_url('Update_tracking_status'); ?>",
                        type: 'POST',
                        data: 'slip_nos=' + arr,
                        beforeSend: function () {
                            $("#loader").removeClass('hide');
                            $("#trck_btn").addClass('hide');
                        },
                        complete: function (res) {

                            $("#loader").addClass('hide');
                            $("#trck_btn").removeClass('hide');
                            location.reload(true);
                        },
                    });
                })

            })

        </script>

    </body>
</html>
