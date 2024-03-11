<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PickUp_en extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Seller_model');

        $this->load->model('Status_model');
        $this->load->model('Pickup_model');

        $this->load->helper('zid');
        $this->load->helper('utility');

        $this->load->model('PickupEN_model');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function packCheck() {
        $postData = json_decode(file_get_contents('php://input'), true);

        $shipments = $this->PickupEN_model->pickListFilterNotPicked($postData['slip_no']);
        $ReturnArray = $shipments['result'];

        //echo"<pre>"; print_r( $shipments); exit;
        foreach ($ReturnArray as $key => $val) {
            $frwd_company_id = GetshpmentDataByawb($val['slip_no'], 'frwd_company_id');
            $booking_id = GetshpmentDataByawb($val['slip_no'], 'booking_id');
            $invoice_label = GetshpmentDataByawb($val['slip_no'], 'invoice_label');
            $sku_details = $this->PickupEN_model->GetskuDetailspack($val['slip_no']);
            //print_r($sku_details);
            foreach ($sku_details as $newkey => $skudata) {
                //$singleSku = $this->ItemInventory_model->GetshowingSkuMeadiaDataQry($skudata);
                $skuArrayDatap[$newkey]['sku'] = $skudata['sku'];
                $skuArrayDatap[$newkey]['ean_no'] = $skudata['ean_no'];
                $skuArrayDatap[$newkey]['piece'] = $skudata['piece'];
                $skuArrayDatap[$newkey]['cod'] = $skudata['cod'];
                if (file_exists($skudata['item_path']) && $skudata['item_path'] != '') {

                    $skuArrayDatap[$newkey]['item_path'] = base_url() . $skudata['item_path'];
                } else {
                    $skuArrayDatap[$newkey]['item_path'] = base_url() . "assets/nfd.png";
                }
                // [{"sku":"TESTBOX","piece":"1","cod":"116.00"}]
            }
            $ReturnArray[$key]['booking_id'] = $booking_id;
            $ReturnArray[$key]['sku'] = json_encode($skuArrayDatap);
            $ReturnArray[$key]['invoice_label'] = $invoice_label;
            if ($frwd_company_id != '') {
                $ReturnArray[$key]['frwd_company_id2'] = $frwd_company_id;
                $ReturnArray[$key]['frwd_company_id'] = GetCourCompanynameId($frwd_company_id, 'company');
                $ReturnArray[$key]['frwd_company_awb'] = GetshpmentDataByawb($val['slip_no'], 'frwd_company_awb');
                if ($ReturnArray[$key]['frwd_company_id'] == 'Bosta V2') {
                    $ReturnArray[$key]['print_url'] = base_url('PickUp/Printpicklist3PL_bulk/' . $val['slip_no']);
                } else {
                    $ReturnArray[$key]['print_url'] = base_url() . 'assets/all_labels/' . $val['slip_no'] . '.pdf';
                }
            }
        }
        $return['result'] = $ReturnArray;
        $return['count'] = 1;
        echo json_encode($return);
    }

    public function packFinish() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $boxArr = $dataArray['boxArr'];
        $SpecialArr = $dataArray['SpecialArr'];

       
        $slip_data = array();
        $Pickingcharge = array();
        $file_name = date('Ymdhis') . '.xls';
        // echo json_encode($_POST['exportData']);exit;
        //$dataArray['shipData']=array('slip_no'=>'TAM7368768739');
        $key = 0;
        //print_r($dataArray);
        $shippingArr = array();

        $reportArr = array();
        $serial_detailsArr = array();
        $wbh_array = array();
        foreach ($_POST['exportData'] as $reportrows) {
            $entrydate = date('Y-m-d H:i:s');
            $insertskureport = array(
                'slip_no' => $reportrows['slip_no'],
                'sku' => $reportrows['ean_no'],
                'ean_no' => $reportrows['sku'],
                'quantity' => $reportrows['piece'],
                'qty_scan' => $reportrows['scaned'],
                'qty_extra' => $reportrows['extra'],
                'updated_by' => $this->session->userdata('user_details')['user_id'],
                'entrydate' => $entrydate,
                'super_id' => $this->session->userdata('user_details')['super_id'],
            );
            $cust_id = GetshpmentDataByawb($reportrows['slip_no'], 'cust_id');
            
            $serial_detailsData = array(
                    'serial_no' => json_encode($reportrows['serial_details']),
                    'slip_no' => $reportrows['slip_no'],
                    'booking_id' => $reportrows['booking_id'],
                    'sku' => $reportrows['ean_no'],
                    'ean_no' => $reportrows['sku'],
                    'piece' => $reportrows['piece'],
                    'entry_date' => $entrydate,
                    'cust_id' => $cust_id,
                    'entry_date'=>$entrydate,
                    //'serial_no'=>$s_val,
                    'super_id' => $this->session->userdata('user_details')['super_id'],
                    'updated_by' => $this->session->userdata('user_details')['user_id'],
                );
                array_push($serial_detailsArr, $serial_detailsData);
//            foreach ($reportrows['serial_details'] as $s_val) {
//                
//            }
            array_push($reportArr, $insertskureport);
        }
        
         
       
       
        
       
        foreach ($dataArray['shipData'] as $data) {

            $check_exist = GetCheckpackStatus($data['slip_no']);

            if (empty($check_exist)) {
                array_push($shippingArr, array('slip_no' => $data['slip_no']));
                array_push($slip_data, $data['slip_no']);
                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_no'];
                $statusvalue[$key]['new_status'] = 4;
                $statusvalue[$key]['code'] = 'PK';
                $statusvalue[$key]['Activites'] = 'Order Packed';
                $statusvalue[$key]['Details'] = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                /* -------------/Status Array----------- */
                $picklistValueNew[$key]['slip_no'] = $data['slip_no'];
                $picklistValue[$key]['slip_no'] = $data['slip_no'];
                $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
                $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
                $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
                $picklistValue[$key]['pickup_status'] = 'Y';
                $picklistValue[$key]['packFile'] = $file_name;
                if ($SpecialArr['specialpack'] == TRUE)
                    $specialpack = "Y";
                else
                    $specialpack = "N";

                $picklistValue[$key]['specialpack'] = $specialpack;
                $picklistValueNew[$key]['special_packaging'] = $specialpack;

                if ($SpecialArr['specialpacktype']) {
                    $picklistValue[$key]['specialpacktype'] = $SpecialArr['specialpacktype'];
                    $picklistValueNew[$key]['pack_type'] = $SpecialArr['specialpacktype'];
                } else {
                    $picklistValue[$key]['specialpacktype'] = "";
                    $picklistValueNew[$key]['pack_type'] = "";
                }

                if (!empty($boxArr['box_no'])) {
                    $box_no = $boxArr['box_no'];
                } else {
                    $box_no = 1;
                }

                $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data['slip_no']);
                $totalPieces = $getallskuArray['pieces'];
                $seller_id = GetallCutomerBysellerId($getallskuArray['cust_id'], 'id');
                $token = GetallCutomerBysellerId($getallskuArray['cust_id'], 'manager_token');

                $PackagingCharge = getalluserfinanceRates($seller_id, 12, 'rate');
                $PinckingCharge = getalluserfinanceRates($seller_id, 13, 'rate');
                $special_packing_charge = getalluserfinanceRates($seller_id, 9, 'rate');
                $special_packing_charge_ware = getalluserfinanceRates($seller_id, 10, 'rate');
                $box_charge = getalluserfinanceRates($seller_id, 19, 'rate');
                $totalspecial_packing_charge = $special_packing_charge * 1; //* $totalPieces
                $totalpackaging = $PackagingCharge * 1; //$totalPieces
                $totalpacking = $PinckingCharge * 1; //$totalPieces
                $Pickingcharge[$key]['seller_id'] = $seller_id;
                $Pickingcharge[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $Pickingcharge[$key]['slip_no'] = $data['slip_no'];
                $picklistValue[$key]['tods_barcode'] = '';
                $picklistValue[$key]['weight'] = $boxArr['weight'];
                $picklistValueNew[$key]['no_of_boxes'] = $box_no;
                //  echo $data['box_no']."dddd". $box_no;
                if ($box_no > 0) {
                    $totalbox_charge = $box_charge * $box_no;
                    $Pickingcharge[$key]['box_charge'] = $totalbox_charge;
                }
                $Pickingcharge[$key]['packaging_charge'] = $totalpackaging;
                $Pickingcharge[$key]['picking_charge'] = $totalpacking;

                if ($SpecialArr['specialpacktype'] == 'seller')
                    $Pickingcharge[$key]['special_packing_charge'] = $totalspecial_packing_charge;
                else
                    $Pickingcharge[$key]['special_packing_charge'] = $special_packing_charge_ware;


                //=================zid status======================//
                $slipArr = GetshipmentRowsDetailsPage($data['slip_no']);
                if (!empty($token)) {
                    $zidStatus = "preparing";

                    $slip_no = $data['slip_no'];
                    $frwd_company_id = $slipArr['frwd_company_id'];
                    $booking_id = $slipArr['booking_id'];
                    if (!empty($slipArr['frwd_company_awb'])) {
                        $trackingurl = makeTrackUrl($frwd_company_id, $slipArr['frwd_company_awb']);

                        $lable = $data['frwd_company_label'];
                    } else {
                        $lable = 'https://api.diggipacks.com/API/print/' . $data['slip_no'];

                        $trackingurl = TRACKURL . $slip_no;
                    }


                    //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                    updateZidStatus($booking_id, $token, $zidStatus, $slip_no, $lable, $trackingurl);
                }


                $WB_Confing = webhook_settingsTable($slipArr['cust_id']);
                if ($WB_Confing['subscribe'] == 'Y') {
                    $wb_request = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        "code" => 'PK',
                        "status" => 'Order Packed',
                        "cc_name" => $data['frwd_company_id'],
                        "cc_awb" => $slipArr['frwd_company_awb'],
                        "cc_status" => null,
                        "cc_status_details" => null,
                        "slip_no" => $slipArr['slip_no'],
                        "booking_id" => $slipArr['booking_id'],
                        "cust_id" => $slipArr['cust_id'],
                        "WB_Confing" => $WB_Confing
                    );
                    array_push($wbh_array, $wb_request);
                }
                //=================================================//

                $Pickingcharge[$key]['entrydate'] = date("Y-m-d H:i:sa");
                $Pickingcharge[$key]['pieces'] = $totalPieces;

                $key++;
            }
        }

        //print_r($dataArray['exportData']); die;

        $statusvaluenew = array();
        // echo $dataArray['exportData'][0]['weight'].'//'.$boxArr['weight'];exit;
        if ($dataArray['exportData'][0]['weight'] != $boxArr['weight']) {
            //die;
            $statusvaluenew['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvaluenew['user_type'] = 'fulfillment';
            $statusvaluenew['slip_no'] = $dataArray['exportData'][0]['slip_no'];
            $statusvaluenew['new_status'] = 4;
            $statusvaluenew['code'] = 'PK';
            $statusvaluenew['Activites'] = 'Weight updated ';
            $statusvaluenew['Details'] = 'Weight updated from ' . $dataArray['exportData'][0]['weight'] . ' Kg  to ' . $boxArr['weight'] . ' Kg  by ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvaluenew['entry_date'] = date('Y-m-d H:i:s');
            $statusvaluenew['super_id'] = $this->session->userdata('user_details')['super_id'];

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
                'weight' => $boxArr['weight']
            );
        } else {

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
            );
        }


        $shipData = array();

        $shipData['where_in'] = $slip_data;
        $shipData['update'] = $updateArray;

        // echo json_encode($Pickingcharge);die;
        //echo '<pre>';
// print_r($picklistValue);
        // print_r($statusvalue);
        //print_r($Pickingcharge);
        //  die;
//die;

        if (!empty($reportArr)) {
            $this->Pickup_model->generatescanreport($reportArr);
        }
          if (!empty($serial_detailsArr)) {
           $this->Pickup_model->packing_serialQuery($serial_detailsArr);
        }

        if ($this->Pickup_model->packOrder($picklistValue)) {
            //print_r($picklistValueNew);
            $this->Pickup_model->packOrderNew($picklistValueNew);
            // GetrequestShippongCompany($shippingArr);
            //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
            if ($this->Status_model->insertStatus($statusvalue)) {




                //print_r($statusvalue);
                $this->Pickup_model->GetallDatapickingChargeAdded($Pickingcharge);

                $this->Shipment_model->updateStatus($shipData);
                if (!empty($statusvaluenew)) {
                    $this->Status_model->insertStatussingle($statusvaluenew);
                }
// $this->exportExcel($_POST['exportData']
                if (!empty($wbh_array)) {
                    $this->session->set_userdata(array('webhook_status' => $wbh_array));
                }
                echo json_encode($file_name);
            }
        }
    }

    public function packCheck_3pl() {

        $_POST = json_decode(file_get_contents('php://input'), true);
//          print "<pre>"; print_r( $_POST);die;
       
        $shipments = $this->PickupEN_model->pickListFilterNotPicked_3pl($_POST['slip_no'], $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination);
        // print "<pre>"; print_r($shipments);die;
        $ReturnArray = $shipments['result'];
        foreach ($ReturnArray as $key => $val) {
            $frwd_company_id = GetshpmentDataByawb($val['slip_no'], 'frwd_company_id');
            $invoice_label = GetshpmentDataByawb($val['slip_no'], 'invoice_label');
            // $sku_details = json_decode($val['sku'], true);
            $sku_details = $this->PickupEN_model->GetskuDetailspack($val['slip_no']);
            foreach ($sku_details as $newkey => $skudata) {
                $singleSku = $this->ItemInventory_model->GetshowingSkuMeadiaDataQry($skudata);
                $skuArrayDatap[$newkey]['sku'] = $skudata['sku'];
                $skuArrayDatap[$newkey]['ean_no'] = $skudata['ean_no'];
                $skuArrayDatap[$newkey]['piece'] = $skudata['piece'];
                $skuArrayDatap[$newkey]['cod'] = $skudata['cod'];
                if (file_exists($singleSku['item_path']) && $singleSku['item_path'] != '') {

                    $skuArrayDatap[$newkey]['item_path'] = base_url() . $singleSku['item_path'];
                } else {
                    $skuArrayDatap[$newkey]['item_path'] = base_url() . "assets/nfd.png";
                }
                // [{"sku":"TESTBOX","piece":"1","cod":"116.00"}]
            }
            $ReturnArray[$key]['sku'] = json_encode($skuArrayDatap);
            $ReturnArray[$key]['invoice_label'] = $invoice_label;

            if ($frwd_company_id != '') {
                $ReturnArray[$key]['frwd_company_id2'] = $frwd_company_id;
                $ReturnArray[$key]['frwd_company_id'] = GetCourCompanynameId($frwd_company_id, 'company');
                $ReturnArray[$key]['frwd_company_awb'] = GetshpmentDataByawb($val['slip_no'], 'frwd_company_awb');
                if ($ReturnArray[$key]['frwd_company_id'] == 'Bosta V2') {
                    $ReturnArray[$key]['print_url'] = base_url('PickUp/Printpicklist3PL_bulk/' . $val['slip_no']);
                } else {
                    $ReturnArray[$key]['print_url'] = base_url() . 'assets/all_labels/' . $val['slip_no'] . '.pdf';
                }
            }           
        }

        //  print "<pre>"; print_r($ReturnArray);die;
        $return['result'] = $ReturnArray;
        $return['count'] = 1;
        echo json_encode($return);
    }

    public function packFinishFwd() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        // print "<pre>"; print_r($_POST);die;
        $dataArray = $_POST;
        $boxArr = $dataArray['boxArr'];
        $SpecialArr = $dataArray['SpecialArr'];

        // print_r($dataArray); die;
        $slip_data = array();
        $Pickingcharge = array();
        $file_name = date('Ymdhis') . '.xls';
        // echo json_encode($_POST['exportData']);exit;
        //$dataArray['shipData']=array('slip_no'=>'TAM7368768739');
        $key = 0;
        //print_r($dataArray);
        $shippingArr = array();

        $reportArr = array();
        $wbh_array = array();
        foreach ($_POST['exportData'] as $reportrows) {
            $entrydate = date('Y-m-d H:i:s');
            $insertskureport = array(
                'slip_no' => $reportrows['slip_new'],
                'sku' => $reportrows['ean_no'],
                'ean_no' => $reportrows['sku'],
                'quantity' => $reportrows['piece'],
                'qty_scan' => $reportrows['scaned'],
                'qty_extra' => $reportrows['extra'],
                'updated_by' => $this->session->userdata('user_details')['user_id'],
                'entrydate' => $entrydate,
                'super_id' => $this->session->userdata('user_details')['super_id'],
            );
            array_push($reportArr, $insertskureport);
        }

        foreach ($dataArray['shipData'] as $data) {

            $check_exist = GetCheckpackStatus($data['slip_new']);

            if (empty($check_exist)) {
                array_push($shippingArr, array('slip_no' => $data['slip_new']));
                array_push($slip_data, $data['slip_new']);
                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_new'];
                $statusvalue[$key]['new_status'] = 4;
                $statusvalue[$key]['code'] = 'PK';
                $statusvalue[$key]['Activites'] = 'Order Packed';
                $statusvalue[$key]['Details'] = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                /* -------------/Status Array----------- */
                $picklistValueNew[$key]['slip_no'] = $data['slip_new'];
                $picklistValue[$key]['slip_no'] = $data['slip_new'];
                $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
                $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
                $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
                $picklistValue[$key]['pickup_status'] = 'Y';
                $picklistValue[$key]['packFile'] = $file_name;
                if ($SpecialArr['specialpack'] == TRUE)
                    $specialpack = "Y";
                else
                    $specialpack = "N";

                $picklistValue[$key]['specialpack'] = $specialpack;
                $picklistValueNew[$key]['special_packaging'] = $specialpack;

                if ($SpecialArr['specialpacktype']) {
                    $picklistValue[$key]['specialpacktype'] = $SpecialArr['specialpacktype'];
                    $picklistValueNew[$key]['pack_type'] = $SpecialArr['specialpacktype'];
                } else {
                    $picklistValue[$key]['specialpacktype'] = "";
                    $picklistValueNew[$key]['pack_type'] = "";
                }

                if (!empty($boxArr['box_no'])) {
                    $box_no = $boxArr['box_no'];
                } else {
                    $box_no = 1;
                }

                $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data['slip_new']);
                $totalPieces = $getallskuArray['pieces'];
                $seller_id = GetallCutomerBysellerId($getallskuArray['cust_id'], 'id');
                $token = GetallCutomerBysellerId($getallskuArray['cust_id'], 'manager_token');

                $PackagingCharge = getalluserfinanceRates($seller_id, 12, 'rate');
                $PinckingCharge = getalluserfinanceRates($seller_id, 13, 'rate');
                $special_packing_charge = getalluserfinanceRates($seller_id, 9, 'rate');
                $special_packing_charge_ware = getalluserfinanceRates($seller_id, 10, 'rate');
                $box_charge = getalluserfinanceRates($seller_id, 19, 'rate');
                $totalspecial_packing_charge = $special_packing_charge * 1; //* $totalPieces
                $totalpackaging = $PackagingCharge * 1; //$totalPieces
                $totalpacking = $PinckingCharge * 1; //$totalPieces
                $Pickingcharge[$key]['seller_id'] = $seller_id;
                $Pickingcharge[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $Pickingcharge[$key]['slip_no'] = $data['slip_new'];
                $picklistValue[$key]['tods_barcode'] = '';
                $picklistValue[$key]['weight'] = $boxArr['weight'];
                $picklistValueNew[$key]['no_of_boxes'] = $box_no;
                //  echo $data['box_no']."dddd". $box_no;
                if ($box_no > 0) {
                    $totalbox_charge = $box_charge * $box_no;
                    $Pickingcharge[$key]['box_charge'] = $totalbox_charge;
                }
                $Pickingcharge[$key]['packaging_charge'] = $totalpackaging;
                $Pickingcharge[$key]['picking_charge'] = $totalpacking;

                if ($SpecialArr['specialpacktype'] == 'seller')
                    $Pickingcharge[$key]['special_packing_charge'] = $totalspecial_packing_charge;
                else
                    $Pickingcharge[$key]['special_packing_charge'] = $special_packing_charge_ware;


                //=================zid status======================//
                $slipArr = GetshipmentRowsDetailsPage($data['slip_new']);
                if (!empty($token)) {
                    $zidStatus = "preparing";

                    $slip_no = $data['slip_new'];
                    $frwd_company_id = $slipArr['frwd_company_id'];
                    $booking_id = $slipArr['booking_id'];
                    if (!empty($slipArr['frwd_company_awb'])) {
                        $trackingurl = makeTrackUrl($frwd_company_id, $slipArr['frwd_company_awb']);

                        $lable = $data['frwd_company_label'];
                    } else {
                        $lable = 'https://api.diggipacks.com/API/print/' . $data['slip_new'];

                        $trackingurl = TRACKURL . $slip_no;
                    }


                    //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                    updateZidStatus($booking_id, $token, $zidStatus, $slip_no, $lable, $trackingurl);
                }


                $WB_Confing = webhook_settingsTable($slipArr['cust_id']);
                if ($WB_Confing['subscribe'] == 'Y') {
                    $wb_request = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        "code" => 'PK',
                        "status" => 'Order Packed',
                        "cc_name" => $data['frwd_company_id'],
                        "cc_awb" => $slipArr['frwd_company_awb'],
                        "cc_status" => null,
                        "cc_status_details" => null,
                        "slip_no" => $slipArr['slip_no'],
                        "booking_id" => $slipArr['booking_id'],
                        "cust_id" => $slipArr['cust_id'],
                        "WB_Confing" => $WB_Confing
                    );
                    array_push($wbh_array, $wb_request);
                }
                //=================================================//

                $Pickingcharge[$key]['entrydate'] = date("Y-m-d H:i:sa");
                $Pickingcharge[$key]['pieces'] = $totalPieces;

                $key++;
            }
        }

        //print_r($dataArray['exportData']); die;

        $statusvaluenew = array();
        // echo $dataArray['exportData'][0]['weight'].'//'.$boxArr['weight'];exit;
        if ($dataArray['exportData'][0]['weight'] != $boxArr['weight']) {
        //die;
            $statusvaluenew['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvaluenew['user_type'] = 'fulfillment';
            $statusvaluenew['slip_no'] = $dataArray['exportData'][0]['slip_new'];
            $statusvaluenew['new_status'] = 4;
            $statusvaluenew['code'] = 'PK';
            $statusvaluenew['Activites'] = 'Weight updated ';
            $statusvaluenew['Details'] = 'Weight updated from ' . $dataArray['exportData'][0]['weight'] . ' Kg  to ' . $boxArr['weight'] . ' Kg  by ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvaluenew['entry_date'] = date('Y-m-d H:i:s');
            $statusvaluenew['super_id'] = $this->session->userdata('user_details')['super_id'];

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
                'weight' => $boxArr['weight']
            );
        } else {

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
            );
        }


        $shipData = array();

        $shipData['where_in'] = $slip_data;
        $shipData['update'] = $updateArray;


        if (!empty($reportArr)) {
            $this->Pickup_model->generatescanreport($reportArr);
        }

        if ($this->Pickup_model->packOrder($picklistValue)) {
            //print_r($picklistValueNew);
            $this->Pickup_model->packOrderNew($picklistValueNew);
            // GetrequestShippongCompany($shippingArr);
            //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
            if ($this->Status_model->insertStatus($statusvalue)) {




                //print_r($statusvalue);
                $this->Pickup_model->GetallDatapickingChargeAdded($Pickingcharge);

                $this->Shipment_model->updateStatus($shipData);
                if (!empty($statusvaluenew)) {
                    $this->Status_model->insertStatussingle($statusvaluenew);
                }
// $this->exportExcel($_POST['exportData']
                if (!empty($wbh_array)) {
                    $this->session->set_userdata(array('webhook_status' => $wbh_array));
                }
                echo json_encode($file_name);
            }
        }
    }

    public function packFinishCPSFWD() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $boxArr = $dataArray['boxArr'];
        $SpecialArr = $dataArray['SpecialArr'];

       
        $slip_data = array();
        $Pickingcharge = array();
        $file_name = date('Ymdhis') . '.xls';
        $key = 0;
        $shippingArr = array();

        $reportArr = array();
        $serial_detailsArr = array();
        $wbh_array = array();
        foreach ($_POST['exportData'] as $reportrows) {
            $entrydate = date('Y-m-d H:i:s');
            $insertskureport = array(
                'slip_no' => $reportrows['slip_new'],
                'sku' => $reportrows['ean_no'],
                'ean_no' => $reportrows['sku'],
                'quantity' => $reportrows['piece'],
                'qty_scan' => $reportrows['scaned'],
                'qty_extra' => $reportrows['extra'],
                'updated_by' => $this->session->userdata('user_details')['user_id'],
                'entrydate' => $entrydate,
                'super_id' => $this->session->userdata('user_details')['super_id'],
            );
            $cust_id = GetshpmentDataByawb($reportrows['slip_no'], 'cust_id');
            
            $serial_detailsData = array(
                    'serial_no' => json_encode($reportrows['serial_details']),
                    'slip_no' => $reportrows['slip_new'],
                    'booking_id' => $reportrows['booking_id'],
                    'sku' => $reportrows['ean_no'],
                    'ean_no' => $reportrows['sku'],
                    'piece' => $reportrows['piece'],
                    'entry_date' => $entrydate,
                    'cust_id' => $cust_id,
                    'entry_date'=>$entrydate,
                    //'serial_no'=>$s_val,
                    'super_id' => $this->session->userdata('user_details')['super_id'],
                    'updated_by' => $this->session->userdata('user_details')['user_id'],
                );
                array_push($serial_detailsArr, $serial_detailsData);
                array_push($reportArr, $insertskureport);
        }
        
         
       
       
        
       
        foreach ($dataArray['shipData'] as $data) {

            $check_exist = GetCheckpackStatus($data['slip_no']);

            if (empty($check_exist)) {
                array_push($shippingArr, array('slip_no' => $data['slip_new']));
                array_push($slip_data, $data['slip_new']);
                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_new'];
                $statusvalue[$key]['new_status'] = 4;
                $statusvalue[$key]['code'] = 'PK';
                $statusvalue[$key]['Activites'] = 'Order Packed';
                $statusvalue[$key]['Details'] = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                /* -------------/Status Array----------- */
                $picklistValueNew[$key]['slip_no'] = $data['slip_new'];
                $picklistValue[$key]['slip_no'] = $data['slip_new'];
                $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
                $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
                $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
                $picklistValue[$key]['pickup_status'] = 'Y';
                $picklistValue[$key]['packFile'] = $file_name;
                if ($SpecialArr['specialpack'] == TRUE)
                    $specialpack = "Y";
                else
                    $specialpack = "N";

                $picklistValue[$key]['specialpack'] = $specialpack;
                $picklistValueNew[$key]['special_packaging'] = $specialpack;

                if ($SpecialArr['specialpacktype']) {
                    $picklistValue[$key]['specialpacktype'] = $SpecialArr['specialpacktype'];
                    $picklistValueNew[$key]['pack_type'] = $SpecialArr['specialpacktype'];
                } else {
                    $picklistValue[$key]['specialpacktype'] = "";
                    $picklistValueNew[$key]['pack_type'] = "";
                }

                if (!empty($boxArr['box_no'])) {
                    $box_no = $boxArr['box_no'];
                } else {
                    $box_no = 1;
                }

                $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data['slip_new']);
                $totalPieces = $getallskuArray['pieces'];
                $seller_id = GetallCutomerBysellerId($getallskuArray['cust_id'], 'id');
                $token = GetallCutomerBysellerId($getallskuArray['cust_id'], 'manager_token');

                $PackagingCharge = getalluserfinanceRates($seller_id, 12, 'rate');
                $PinckingCharge = getalluserfinanceRates($seller_id, 13, 'rate');
                $special_packing_charge = getalluserfinanceRates($seller_id, 9, 'rate');
                $special_packing_charge_ware = getalluserfinanceRates($seller_id, 10, 'rate');
                $box_charge = getalluserfinanceRates($seller_id, 19, 'rate');
                $totalspecial_packing_charge = $special_packing_charge * 1; //* $totalPieces
                $totalpackaging = $PackagingCharge * 1; //$totalPieces
                $totalpacking = $PinckingCharge * 1; //$totalPieces
                $Pickingcharge[$key]['seller_id'] = $seller_id;
                $Pickingcharge[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $Pickingcharge[$key]['slip_no'] = $data['slip_new'];
                $picklistValue[$key]['tods_barcode'] = '';
                $picklistValue[$key]['weight'] = $boxArr['weight'];
                $picklistValueNew[$key]['no_of_boxes'] = $box_no;
                //  echo $data['box_no']."dddd". $box_no;
                if ($box_no > 0) {
                    $totalbox_charge = $box_charge * $box_no;
                    $Pickingcharge[$key]['box_charge'] = $totalbox_charge;
                }
                $Pickingcharge[$key]['packaging_charge'] = $totalpackaging;
                $Pickingcharge[$key]['picking_charge'] = $totalpacking;

                if ($SpecialArr['specialpacktype'] == 'seller')
                    $Pickingcharge[$key]['special_packing_charge'] = $totalspecial_packing_charge;
                else
                    $Pickingcharge[$key]['special_packing_charge'] = $special_packing_charge_ware;


                //=================zid status======================//
                $slipArr = GetshipmentRowsDetailsPage($data['slip_new']);
                if (!empty($token)) {
                    $zidStatus = "preparing";

                    $slip_no = $data['slip_new'];
                    $frwd_company_id = $slipArr['frwd_company_id'];
                    $booking_id = $slipArr['booking_id'];
                    if (!empty($slipArr['frwd_company_awb'])) {
                        $trackingurl = makeTrackUrl($frwd_company_id, $slipArr['frwd_company_awb']);

                        $lable = $data['frwd_company_label'];
                    } else {
                        $lable = 'https://api.diggipacks.com/API/print/' . $data['slip_new'];

                        $trackingurl = TRACKURL . $slip_no;
                    }


                    updateZidStatus($booking_id, $token, $zidStatus, $slip_no, $lable, $trackingurl);
                }


                $WB_Confing = webhook_settingsTable($slipArr['cust_id']);
                if ($WB_Confing['subscribe'] == 'Y') {
                    $wb_request = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        "code" => 'PK',
                        "status" => 'Order Packed',
                        "cc_name" => $data['frwd_company_id'],
                        "cc_awb" => $slipArr['frwd_company_awb'],
                        "cc_status" => null,
                        "cc_status_details" => null,
                        "slip_no" => $slipArr['slip_no'],
                        "booking_id" => $slipArr['booking_id'],
                        "cust_id" => $slipArr['cust_id'],
                        "WB_Confing" => $WB_Confing
                    );
                    array_push($wbh_array, $wb_request);
                }
                //=================================================//

                $Pickingcharge[$key]['entrydate'] = date("Y-m-d H:i:sa");
                $Pickingcharge[$key]['pieces'] = $totalPieces;

                $key++;
            }
        }


        $statusvaluenew = array();
        if ($dataArray['exportData'][0]['weight'] != $boxArr['weight']) {
            $statusvaluenew['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvaluenew['user_type'] = 'fulfillment';
            $statusvaluenew['slip_no'] = $dataArray['exportData'][0]['slip_no'];
            $statusvaluenew['new_status'] = 4;
            $statusvaluenew['code'] = 'PK';
            $statusvaluenew['Activites'] = 'Weight updated ';
            $statusvaluenew['Details'] = 'Weight updated from ' . $dataArray['exportData'][0]['weight'] . ' Kg  to ' . $boxArr['weight'] . ' Kg  by ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvaluenew['entry_date'] = date('Y-m-d H:i:s');
            $statusvaluenew['super_id'] = $this->session->userdata('user_details')['super_id'];

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
                'weight' => $boxArr['weight']
            );
        } else {

            $updateArray = array(
                'code' => 'PK',
                'delivered' => 4,
            );
        }


        $shipData = array();

        $shipData['where_in'] = $slip_data;
        $shipData['update'] = $updateArray;

        if (!empty($reportArr)) {
            $this->Pickup_model->generatescanreport($reportArr);
        }
          if (!empty($serial_detailsArr)) {
           $this->Pickup_model->packing_serialQuery($serial_detailsArr);
        }

        if ($this->Pickup_model->packOrder($picklistValue)) {
            //print_r($picklistValueNew);
            $this->Pickup_model->packOrderNew($picklistValueNew);
            if ($this->Status_model->insertStatus($statusvalue)) {

                $this->Pickup_model->GetallDatapickingChargeAdded($Pickingcharge);

                $this->Shipment_model->updateStatus($shipData);
                if (!empty($statusvaluenew)) {
                    $this->Status_model->insertStatussingle($statusvaluenew);
                }
                if (!empty($wbh_array)) {
                    $this->session->set_userdata(array('webhook_status' => $wbh_array));
                }
                echo json_encode($file_name);
            }
        }
    }

}

?>