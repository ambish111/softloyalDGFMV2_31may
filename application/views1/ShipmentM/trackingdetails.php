<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png'); ?>" type="image/x-icon">
        <title><?=lang('lang_Inventory');?></title>
        <?php $this->load->view('include/file'); ?>
    </head>

    <body>
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
                        <?php
                        if ($this->session->flashdata('msg'))
                            echo '<div class="alert alert-success">' . $this->session->flashdata('msg') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

                        if ($this->session->flashdata('something'))
                            echo '<div class="alert alert-warning">' . $this->session->flashdata('something') . ": " . $this->session->flashdata('error') . ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        ?>

                        <!-- Dashboard content -->
                        <div class="row" >
                            <div class="col-lg-12" > 
                                <?php 
                                    $awb_label = ''; $trackFlag = FALSE; $track_awb= '';
                                    if(!empty($Shipmentinfo['frwd_company_awb'])){
                                        $track_url = GetCourCompanynameId($Shipmentinfo['frwd_company_id'], 'company_url');
                                        if(!empty($track_url)){
                                            $trackFlag = TRUE;
                                            $track_awb = $track_url.$Shipmentinfo['frwd_company_awb'];
                                        }else{
                                            $track_awb = '#';
                                        }
                                        $awb_label = ' / ( 3pl-Label : <a href="'.$Shipmentinfo['frwd_company_label'].'" target="_blank" >'.$Shipmentinfo['frwd_company_awb'].' </a> )';
                                    }
                                ?>
                                <!-- Marketing campaigns -->
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        
                                        <h1> <strong><?=lang('lang_Detail');?>- (<?=lang('lang_Tracking_No');?> :
                                                <?= $Shipmentinfo['slip_no']; ?>
                                                ) / (<?=lang('lang_Reference_No');?>. : <?php echo $Shipmentinfo['booking_id'] ?>) <?php echo $awb_label; ?></strong> 
                                        <?php if($trackFlag){ ?>
                                            <a class="btn btn-danger" target="_blank" href="<?php echo $track_awb; ?>" ><?=lang('lang_Track');?></a>
                                        <?php } ?>
                                        </h1>
                                        
                                                
                                    </div>
                                    


<!-- href="<? // base_url('Excel_export/shipments');  ?>" --> 
<!-- href="<? //base_url('Pdf_export/all_report_view');  ?>" --> 
                                    <!-- Quick stats boxes --> 

                                    <!-- /quick stats boxes --> 
                                </div>
                            </div>
                            
                        </div>
                        <!-- /dashboard content --> 
                        <!-- Basic responsive table -->
                        <div class="panel panel-flat" >
                            <div class="panel-body" >
                                <div class="table-responsive" style="padding-bottom:20px;" > 
                                    <!--style="background-color: green;"-->
                                    <div class="panel-heading">
                                        <h1><strong><?=lang('lang_Shipment_Info');?></strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                             $SrNo=lang('lang_SrNo');	
                                             $SKU=lang('lang_SKU');	
                                             $Weight=lang('lang_Weight');	
                                             $Pieces=lang('lang_Pieces');	
                                             $Description=lang('lang_Description');	

                                             $Reference_No=lang('lang_Reference_No');	
                                             $Shipper_Refrence=lang('lang_shipper_Refrence');	

                                            
                                             $lang_Shipping_Zone=lang('lang_Shipping_Zone');	
                                             $lang_Entry_Date=lang('lang_Entry_Date');	
                                             $lang_Origin=lang('lang_Origin');	
                                             $lang_Receiver=lang('lang_Receiver');		
                                             $lang_Destination=lang('lang_Destination');	
                                             $lang_No_of_Pieces=lang('lang_No_of_Pieces');	
                                             $lang_Payment_Mode=lang('lang_Payment_Mode');	
                                             $lang_Schedule_Chanel=lang('lang_Schedule_Chanel');
                                             $lang_Payment_Mode=lang('lang_Payment_Mode');		
                                             $lang_Transaction_Date=lang('lang_Transaction_Date');	
                                             $lang_No_of_Attempt=lang('lang_No_of_Attempt');	
                                             $lang_tpl_Pickup_Date=lang('lang_tpl_Pickup_Date');	
                                             $lang_tpl_Closed_Date=lang('lang_tpl_Closed_Date');	
                                             $lang_tpl_Pickup_Date=lang('lang_tpl_Pickup_Date');	
                                             $lang_tpl_Closed_Date=lang('lang_tpl_Closed_Date');	
                                             $lang_Forwarded_Date=lang('lang_Forwarded_Date');	
                                             $lang_Forwarded_Company=lang('lang_Forwarded_Company');
                                             $lang_Sender=lang('lang_Sender');	
                                             $lang_User_Type=lang('lang_User_Type');	
                                             $lang_Scheduled=lang('lang_Scheduled');	
                                             $lang_Shelve_No=lang('lang_Shelve_No');	
                                             $lang_Location=lang('lang_Location');	
                                             $lang_Shelve=lang('lang_Shelve');	
                                             $lang_On_Hold=lang('lang_On_Hold');	
                                             $lang_Amount_Collected=lang('lang_Amount_Collected');	
                                             $Weight=lang('lang_Weight');	
                                             $lang_Status=lang('lang_Status');	
                                             $lang_Product_Type=lang('lang_Product_Type');	
                                             $lang_Product_Description=lang('lang_Product_Description');
                                             $lang_View_detail=lang('lang_View_detail');	
                                             $lang_Forwarded_AWB_No=lang('lang_Forwarded_AWB_No');	
                                             $lang_Forwarded=lang('lang_Forwarded');	
                                             
                                            if ($Shipmentinfo['booking_id'] != '')
                                                echo' <tr><th><b class="size-2">'.$Reference_No.'</b></th><td>' . $Shipmentinfo['booking_id'] . '</td></tr>';

                                            echo'<tr><th><b class="size-2">'.$Shipper_Refrence.'</b></th>';
                                            if ($Shipmentinfo['shippers_ref_no'] != '')
                                                echo'<td>' . $Shipmentinfo['shippers_ref_no'] . '</td>';
                                            else
                                                echo'<td>--</td>';
                                            echo'</tr>';
                                            echo' <tr><th><b class="size-2">'.$lang_Entry_Date.'</b></th><td>' . date('d-m-Y', strtotime($Shipmentinfo['entrydate'])) . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_Origin.'</b></th><td>' . getdestinationfieldshow($Shipmentinfo['origin'], 'city') . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_Destination.'</b></th><td>' . getdestinationfieldshow($Shipmentinfo['destination'], 'city') . '</td></tr>';
                                            if ($Shipmentinfo['total_amt'])
                                                echo'<tr><th><b class="size-2">Net Price</b></th><td>' . $Shipmentinfo['total_amt'] . '</td></tr>';
                                            echo'<tr><th><b class="size-2">'.$lang_No_of_Pieces.'</b></th><td>' . $Shipmentinfo['pieces'] . '</td></tr>';
                                            if ($Shipmentinfo['mode'] == 'COD')
                                                echo' <tr><th><b class="size-2">'.$lang_Payment_Mode.'</b></th><td>' . $Shipmentinfo['mode'] . ' ' . $Shipmentinfo['total_cod_amt'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_Payment_Mode.'</b></th><td>' . $Shipmentinfo['mode'] . '</td></tr>';
                                            echo'<tr><th><b class="size-2">'.$lang_Schedule_Chanel.'</b></th>';
                                            if ($Shipmentinfo['schedule_type'])
                                                echo'<td><span class="label label-success">' . $Shipmentinfo['schedule_type'] . '</span></td>';
                                            else
                                                echo'<td><span class="label label-danger">N/A</span></td>';
                                            echo'</tr>';
                                            echo '<tr><th><b class="size-2">'.$lang_Payment_Date.'</b></th><td>--</td></tr>';
                                            if ($Shipmentinfo['shipping_zone'])
                                                echo'<tr><th><b class="size-2">'.$lang_Shipping_Zone.'</b></th><td>' . $Shipmentinfo['shipping_zone'] . '</td></tr>';

                                            echo'<tr><th><b class="size-2">'.$lang_Scheduled.'</b></th>';
                                            if ($Shipmentinfo['schedule_status'] == 'Y')
                                                echo'<td>' . $Shipmentinfo['schedule_date'] . ' | ' . $Shipmentinfo['time_slot'] . '</td>';
                                            else
                                                echo'<td>NO</td>';
                                            echo'</tr>';
                                            if ($Shipmentinfo['shelv_no'])
                                                echo'<tr><th><b class="size-2">'.$lang_Shelve_No.'</b></th><td>' . $Shipmentinfo['shelv_no'] . '</td></tr>';
                                            if ($Shipmentinfo['shelv_no'])
                                                echo'<tr><th><b class="size-2">'.$lang_Shelve.' '.$lang_Location.'</b></th><td>' . $Shipmentinfo['shelv_no'] . '</td></tr>';
                                            if ($Shipmentinfo['refused'] == 'YES')
                                                echo' <tr><th><b class="size-2">'.$lang_On_Hold.'</b></th><td>YES</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_On_Hold.'</b></th><td>No</td></tr>';

                                            if ($Shipmentinfo['mode'] == 'COD')
                                                $colorclass = 'style="background-color:#AEFFAE;"';
                                            if ($Shipmentinfo['booking_mode'] == 'Pay at pickup' && $Shipmentinfo['total_cod_amt'] != 0)
                                                $colorclass2 = 'style="background-color:#AEFFAE;"';
                                            if ($Shipmentinfo['amount_collected'] == 'N')
                                                echo' <tr><th><b class="size-2">'.$lang_Amount_Collected.'</b></th><td>No</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_Amount_Collected.'</b></th><td>Yes</td></tr>';
                                            echo' <tr><th><b class="size-2">'.$Weight.'</b></th><td>' . $Shipmentinfo['weight'] . 'Kg</td></tr>
                          <!--<tr><th><b class="size-2" >Status </b></th><td ' . $colorclass . ' ' . $colorclass2 . '>' . getallmaincatstatus($Shipmentinfo['delivered'], 'main_status') . '</td></tr>-->
                          <tr><th><b class="size-2" >'.$lang_Status.' </b></th><td ' . $colorclass . ' ' . $colorclass2 . '>' . $Shipmentinfo['status_fm'] . '</td></tr>
                         <!-- <tr><th><b class="size-2">Store Link</b></th><td>' . $Shipmentinfo['cust_id'] . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_User_Type.'</b></th><td>' . $Shipmentinfo['cust_id'] . '</td></tr>-->
                          <tr><th><b class="size-2">'.$lang_Product_Type.'</b></th><td>' . $Shipmentinfo['nrd'] . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_No_of_Attempt.'</b></th><td>' . $Shipmentinfo['no_of_attempt'] . '</td></tr>                              
                          <tr><th><b class="size-2">'.$lang_tpl_Pickup_Date.'</b></th><td>' . $Shipmentinfo['pl3_pickup_date'] . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_tpl_Closed_Date.'</b></th><td>' . $Shipmentinfo['pl3_closed_date'] . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_Transaction_Date.'</b></th><td>' . $Shipmentinfo['transaction_date'] . '</td></tr>
                          <tr><th><b class="size-2">'.$lang_Product_Description.'</b></th><td>' . $Shipmentinfo['status_describtion'] . '</td></tr>';
                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">'.$lang_View_detail.'</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>

                                     <div class="panel-heading">
                                        <h1><strong><?=lang('lang_Sender_Info');?></strong></h1>
                                    </div>
                                  
                                      <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            $sender_company=getallsellerdatabyID($Shipmentinfo['cust_id'],'company');
                                            if ($sender_company != '')
                                                echo' <tr><th><b class="size-2">'.$lang_Sender.'</b></th><td>' . $sender_company . '</td></tr>';
                                            else
                                                 echo' <tr><th><b class="size-2">'.$lang_Sender.'</b></th><td style="color:grey;">No Info Found</td></tr>';

                                           
                                            if ($Shipmentinfo['sender_address'] != '')
                                                echo' <tr><th><b class="size-2">Sender Address</b></th><td>' . $Shipmentinfo['sender_address'] . '</td></tr>';
                                            else
                                                 echo' <tr><th><b class="size-2">Sender Address</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            if ($Shipmentinfo['sender_phone'] != '')
                                            echo'<tr><th><b class="size-2">Sender Mobile</b></th><td>' . ($Shipmentinfo['sender_phone']) . '</td></tr>';
                                               else
                                                 echo' <tr><th><b class="size-2">Sender Mobile</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            if ($Shipmentinfo['sender_email'] != '')
                                            echo'<tr><th><b class="size-2">Sender Email</b></th><td>' . ($Shipmentinfo['sender_email']) . '</td></tr>';
                                                 else
                                                 echo' <tr><th><b class="size-2">Sender Email</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">'.$lang_View_detail.'</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>

                                    <div class="panel-heading">
                                        <h1><strong><?=lang('lang_Receiver_Info');?></strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            if ($Shipmentinfo['reciever_name'] != '')
                                                echo' <tr><th><b class="size-2">'.$lang_Receiver.'</b></th><td>' . $Shipmentinfo['reciever_name'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Sender</b></th><td style="color:grey;">No Info Found</td></tr>';

                                            if ($Shipmentinfo['reciever_address'] != '')
                                                echo'<tr><th><b class="size-2">Receiver Address</b></th><td>' . $Shipmentinfo['reciever_address'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Receiver Address</b></th><td style="color:grey;">No Info Found</td></tr>';
                                           
                                            if ($Shipmentinfo['reciever_phone'] != '')
                                                echo'<tr><th><b class="size-2">Receiver Mobile</b></th><td>' . ($Shipmentinfo['reciever_phone']) . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Receiver Mobile</b></th><td style="color:grey;">No Info Found</td></tr>';


                                           
                                            if ($Shipmentinfo['reciever_email'] != '')
                                                echo' <tr><th><b class="size-2">Receiver Email</b></th><td>' . ($Shipmentinfo['reciever_email']) . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">Receiver Email</b></th><td style="color:grey;">No Info Found</td></tr>';
                         
                                            ?>
                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">'.$lang_View_detail.'</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>

                                    <div class="panel-heading">
                                    <h1><strong><?=lang('lang_Forwarded_Info');?></strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered"  style="width:100%;">
                                        <thead>
                                            <?php
                                            if ($Shipmentinfo['frwd_date'] != '')
                                                echo' <tr><th><b class="size-2">'.$lang_Forwarded_Date.'</b></th><td>' . $Shipmentinfo['frwd_date'] . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_Forwarded_Date.'</b></th><td style="color:grey;">No Info Found</td></tr>';

                                           
                                            if ($Shipmentinfo['frwd_company_id'] != '0')
                                                echo   '<tr><th><b class="size-2">'.$lang_Forwarded_Company.'</b></th><td>' . GetCourCompanynameId($Shipmentinfo['frwd_company_id'], 'company') . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_Forwarded_Company.'</b></th><td style="color:grey;">No Info Found</td></tr>';
                                           
                                           
                                            if ($Shipmentinfo['frwd_company_awb'] != '')
                                                echo'<tr><th><b class="size-2"> '.$lang_Forwarded_AWB_No.'</b></th><td>' . ($Shipmentinfo['frwd_company_awb']) . '</td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_Forwarded_AWB_No.'</b></th><td style="color:grey;">No Info Found</td></tr>';

                                          
                                            if ($Shipmentinfo['forwarded'] != '0')
                                                echo' <tr><th><b class="size-2">'.$lang_Forwarded.' </b></th><td> Yes </td></tr>';
                                            else
                                                echo' <tr><th><b class="size-2">'.$lang_Forwarded.' </b></th><td style="color:grey;">No</td></tr>';
                                            
                                            if ($Shipmentinfo['forwarded'] != '0'){
                                                $fd1_date = '';
                                                if(!empty($Shipmentinfo['laststatus_first'])){
                                                    $fd1_date = '<span style="float:right;" ><b class="size-2">[ '.$Shipmentinfo['fd1_date'].' ]</b></span>';
                                                }
                                                echo' <tr><th><b class="size-2"> Failed Delivery First Status</b>'.$fd1_date.'</th><td> ' . ($Shipmentinfo['laststatus_first']) . ' </td></tr>';
                                            }else{
                                                echo' <tr><th><b class="size-2"> Failed Delivery First Status </b></th><td style="color:grey;">No Info Found</td></tr>';
                                            }    
                                            if ($Shipmentinfo['forwarded'] != '0'){
                                                $fd2_date = '';
                                                if(!empty($Shipmentinfo['laststatus_second'])){
                                                    $fd2_date = '<span style="float:right;" ><b class="size-2">[ '.$Shipmentinfo['fd2_date'].' ]</b></span>';
                                                }
                                                echo' <tr><th><b class="size-2"> Failed Delivery Second Status  </b>'.$fd2_date.'</th><td> ' . ($Shipmentinfo['laststatus_second']) . ' </td></tr>';
                                            }else{
                                                echo' <tr><th><b class="size-2"> Failed Delivery Second Status </b></th><td style="color:grey;">No Info Found</td></tr>';
                                            }    
                                            if ($Shipmentinfo['forwarded'] != '0'){
                                                $fd3_date = '';
                                                if(!empty($Shipmentinfo['laststatus_last'])){
                                                    $fd3_date = '<span style="float:right;"><b class="size-2">[ '.$Shipmentinfo['fd3_date'].' ]</b></span>';
                                                }
                                                echo' <tr><th><b class="size-2">Failed Delivery last Status</b>'.$fd3_date.'</th><td> ' . ($Shipmentinfo['laststatus_last']) . ' </td></tr>';
                                            }else{
                                                echo' <tr><th><b class="size-2">Failed Delivery last Status</b></th><td style="color:grey;">No Info Found</td></tr>';
                                            }
                                            ?>


                                            <?php
                                            foreach ($shipmentdata as $awbdata) {
                                                echo '<tr>
                        <td>' . $awbdata['entrydate'] . '</td>
                        <td>' . getdestinationfieldshow($awbdata['origin'], 'city') . '</td>
                        <td>' . getdestinationfieldshow($awbdata['destination'], 'city') . '</td>
                        <td>' . $awbdata['pieces'] . '</td>
                        
                        <td>' . $awbdata['weight'] . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td>' . getallmaincatstatus($awbdata['delivered'], 'main_status') . '</td>
                        <td><a href="' . base_url() . 'TrackingDetails/' . $awbdata['id'] . '" class="btn btn-primary">'.$lang_View_detail.'</a></td>
                        
                        </tr>';
                                            }
                                            ?>
                                        </thead>
                                    </table>


                                    <div class="panel-heading">
                                        <h1><strong><?=lang('lang_Dimension_Details');?></strong></h1>
                                    </div>
                                    <?php
                                  //  $skuArr = Getallskudatadetails_tracking($Shipmentinfo['slip_no']);
                                    echo ' <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
              <thead>
                  <tr>
                    <th width="20">'.$SrNo.'</th><th>'.$SKU.'</th><th>'.$Weight.'</th><th>'.$Pieces.'</th><th>Total Weight</th><th>'.$Description.'</th></thead>
              ';
                                    foreach ($Shipmentinfo['sku_data'] as $key => $skuval) {        
                                    //foreach ($skuArr as $key => $skuval) {
                                        $sku_details = !empty($skuval['description'])?$skuval['description']:$skuval['name'];
                                        $total_weight = 0;
                                        $total_weight = ($skuval['weight'] * $skuval['piece']);
                                        $counter2 = $key + 1;
                                        //echo '<tr><td>' . $counter2 . '</td><td>' . $skuval['sku'] . '</td><td>' . $skuval['wieght'] . ' KG</td><td>' . $skuval['piece'] . '</td><td>' . $sku_details. '</td></tr>';
                                        echo '<tr><td>' . $counter2 . '</td><td>' . $skuval['sku'] . '</td><td>' . $skuval['weight'] . ' KG</td><td>' . $skuval['piece'] . '</td><td>'.$total_weight.'</td><td>' . $sku_details . '</td></tr>';
                                    }
                                    echo '</table>';
                                    ?>

                                    <div class="panel-heading">
                                        <h1><strong><?=lang('lang_Latest_Status_TravelHistory');?></strong></h1>
                                    </div>
                                    <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example" style="width:100%;">
                                        <thead>
                                            <tr>
                                            <th width="20"><?=lang('lang_SrNo');?></th>
                                                <th width="110"><?=lang('lang_Date');?></th>
                                                <th width="150"><?=lang('lang_Activities');?></th>
<!--                                                <th>Location</th>
                                                <th>City Code</th>-->
                                                <th><?=lang('lang_code');?></th>
                                                <th><?=lang('lang_Details');?></th>
                                                <th><?=lang('lang_User_Name');?></th>
                                                <th><?=lang('lang_User_Type');?></th>
                                                <th><?=lang('lang_Comment');?></th>
                                            </tr>
                                            <?php
                                            $counter = 0;
//print_r($THData);
                                            
                                            foreach ($THData as $historydata) {
                                                $counter1 = $counter + 1;
                                                echo'<tr>
                                                <td>' . $counter1 . '</td>
                                                <td>' . date("Y-m-d H:i:s", strtotime($historydata['entry_date'])) . '</td>
                                                <td>' . $historydata['Activites'] . '</td>';
                                //    if ($historydata['new_location'] > 0)
                                //        echo'<td>' . getdestinationfieldshow($historydata['new_location'], 'city') . '</td>';
                                //    else
                                //        echo'<td>--</td>';
                                //    if ($historydata['new_location'] > 0)
                                //        echo'<td>' . getdestinationfieldshow($historydata['new_location'], 'city_code') . '</td>';
                                //    else
                                //        echo'<td>--</td>';
                                                echo'<td>' . $historydata['code'] . '</td>
                <td>' . $historydata['Details'] . '</td>
                <td>' . getUserNameByIdType($historydata['user_id'], $historydata['user_type'], $Shipmentinfo['Api_Integration']) . '</td>
                <td>' . $historydata['user_type'] . '</td>';
                                                if ($historydata['comment'])
                                                    echo'<td>' . $historydata['comment'] . '</td>';
                                                else
                                                    echo'<td>--</td>';
                                                echo'</tr>';
                                                $counter++;
                                            }
                                            ?>
                                        </thead>
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
            </div>
           </div>
            <!-- /page content --> 



        </div>

        <!-- /page container -->

    </body>
</html>
