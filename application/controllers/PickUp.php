<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PickUp extends MY_Controller {

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
        $this->load->model('Item_model');
        $this->load->model('Status_model');
        $this->load->model('Pickup_model');
        $this->load->model('User_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
        $this->load->model('Ccompany_model');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index() {
        // sms_prepared("TRL8798176743");
    }

    public function pickedViewSingle($slip_no = null) {
        if (menuIdExitsInPrivilageArray(98) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $view['slip_no_url'] = $slip_no;
        $this->load->view('pickup/pickedSingle', $view);
    }

    public function packing_report() {

        // echo "ssssss";  die;
        //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
        $this->load->view('pickup/packaging_report');
    }

    public function Staff_report() {

        // echo "ssssss";  die;
        //$view['sellerData']=$this->Finance_model->GetallskuandStorageData(); 
        $this->load->view('pickup/Staff_report');
    }

    public function pickedViewSingleList() {
        if (menuIdExitsInPrivilageArray(98) == 'N') {
            // redirect(base_url() . 'notfound');
            // die;
        }
        $view['result'] = array();
        $this->load->view('pickup/pickedSingleView', $view);
    }

    public function dispacth3pl() {
        redirect(base_url() . 'dispatch');
        $view['result'] = array();
        $this->load->view('pickup/dispacth3pl', $view);
    }

    public function run_pickup_cron() {

        exec("php /var/www/html/diggipack_new/fs_files/AAP_sync.php > /dev/null 2>&1 &");
        return true;
    }

    public function pickedBatchView() {
        if (menuIdExitsInPrivilageArray(99) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $view['result'] = array();
        $this->load->view('pickup/pickedbatchView', $view);
    }

    public function pickedcompletedView() {
        if (menuIdExitsInPrivilageArray(100) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $view['result'] = array();
        $this->load->view('pickup/Pcompletedview', $view);
    }

    public function pickedViewbatch($pickupId = null) {
        if (menuIdExitsInPrivilageArray(99) == 'N') {
            //  redirect(base_url() . 'notfound');
            // die;
        }
        $view['pickupId'] = $pickupId;
        $this->load->view('pickup/picked', $view);
    }

    public function packFinish() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); exit;

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
                'slip_no' => $reportrows['slip_no'],
                'sku' => $reportrows['sku'],
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

                        $lable = $slipArr['frwd_company_label'];
                    } else {
                        $lable = 'https://api.diggipacks.com/API/print/' . $data['slip_no'];

                        $trackingurl = TRACKURL . $slip_no;
                    }


                    //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                    updateZidStatus($booking_id, $token, $zidStatus, $slip_no, $lable, $trackingurl,$seller_id);
                }


                $WB_Confing = webhook_settingsTable($slipArr['cust_id']);
                if ($WB_Confing['subscribe'] == 'Y') {
                    $wb_request = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        "code" => 'PK',
                        "status" => 'Order Packed',
                        "cc_name" => $slipArr['frwd_company_id'],
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

        foreach ($slip_data as $awb_data) {

            // echo $slip_data[$key]['slip_no'];exit;
            //sms_prepared($awb_data);
        }
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

    public function packFinish_tod() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST); die; 

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
        $wbh_array = array();
        $reportArr = array();
        foreach ($_POST['exportData'] as $reportrows) {
            $entrydate = date('Y-m-d H:i:s');
            $insertskureport = array(
                'slip_no' => $reportrows['slip_no_check'],
                'sku' => $reportrows['sku'],
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
            array_push($shippingArr, array('slip_no' => $data['slip_no_check']));
            array_push($slip_data, $data['slip_no_check']);
            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $data['slip_no_check'];
            $statusvalue[$key]['new_status'] = 4;
            $statusvalue[$key]['code'] = 'PK';
            $statusvalue[$key]['Activites'] = 'Order Packed';
            $statusvalue[$key]['Details'] = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            /* -------------/Status Array----------- */
            $picklistValueNew[$key]['slip_no'] = $data['slip_no_check'];
            $picklistValue[$key]['slip_no'] = $data['slip_no_check'];
            $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
            $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
            $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
            $picklistValue[$key]['pickup_status'] = 'Y';
            $picklistValue[$key]['tods_barcode'] = '';

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
            $box_no = $boxArr['box_no'] - 1;

            $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data['slip_no_check']);
            $totalPieces = $getallskuArray['pieces'];
            $seller_id = GetallCutomerBysellerId($getallskuArray['cust_id'], 'id');
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
            $Pickingcharge[$key]['slip_no'] = $data['slip_no_check'];
            $picklistValueNew[$key]['no_of_boxes'] = $totalPieces;
            //  echo $data['box_no']."dddd". $box_no;
            if ($box_no > 0) {
                $totalbox_charge = $box_charge * $box_no;
                $Pickingcharge[$key]['box_charge'] = $totalbox_charge;
            }
            $Pickingcharge[$key]['packaging_charge'] = $totalpackaging;
            $Pickingcharge[$key]['picking_charge'] = $totalpacking;

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
                updateZidStatus($booking_id, $token, $zidStatus, $slip_no, $lable, $trackingurl,$seller_id);
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

            if ($SpecialArr['specialpacktype'] == 'seller')
                $Pickingcharge[$key]['special_packing_charge'] = $totalspecial_packing_charge;
            else
                $Pickingcharge[$key]['special_packing_charge'] = $special_packing_charge_ware;

            $Pickingcharge[$key]['entrydate'] = date("Y-m-d H:i:sa");
            $Pickingcharge[$key]['pieces'] = $totalPieces;

            $key++;
        }

        //print_r($Pickingcharge); die;

        foreach ($slip_data as $awb_data) {

            // echo $slip_data[$key]['slip_no'];exit;
            //sms_prepared($awb_data);
        }
        //die;
        $shipData = array();
        if ($boxArr['weight'] > 0) {
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

        if ($this->Pickup_model->packOrder($picklistValue)) {
            //print_r($picklistValueNew);
            $this->Pickup_model->packOrderNew($picklistValueNew);
            // GetrequestShippongCompany($shippingArr);
            //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
            if ($this->Status_model->insertStatus($statusvalue)) {


                //print_r($statusvalue);
                $this->Pickup_model->GetallDatapickingChargeAdded($Pickingcharge);

                $this->Shipment_model->updateStatus($shipData);
                if (!empty($wbh_array)) {
                    $this->session->set_userdata(array('webhook_status' => $wbh_array));
                }
// 
                echo json_encode($this->exportExcel($_POST['exportData'], $file_name));
            }
        }
    }

    public function validateDispatch() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST;
        $shipments = $this->Shipment_model->shipmetsInAwb_valid($DataArray);
        $valid = array();
        $invalid = array();
        $invalidpallet = array();
        $error_check = array();
        //print_r($shipments['result']);exit;
        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {

                array_push($error_check, $data['slip_no']);
                array_push($error_check, $data['frwd_company_awb']);
                //|| trim($data['code']) == 'DL' 
                
                
               if (menuIdExitsInPrivilageArray(255) == 'Y') {
                   
                if (trim($data['code']) == 'PK' || trim($data['code']) == 'PG' || trim($data['code']) == 'OC' || trim($data['code']) == 'AP') {

                    if ($data['order_type'] == 'B2B') {
                        if ($data['stocklcount'] > 0) {

                            array_push($valid, $data);
                        } else {
                            array_push($invalidpallet, $data);
                        }
                    }
                } else {

                    array_push($invalid, $data);
                }
               }
               else
               {
                   if (trim($data['code']) == 'PK') {

                    if ($data['order_type'] == 'B2B') {
                        if ($data['stocklcount'] > 0) {

                            array_push($valid, $data);
                        } else {
                            array_push($invalidpallet, $data);
                        }
                    }
                } else {

                    array_push($invalid, $data);
                }  
               }
            }
        } else {

            $returnData['status'] = "error";
            foreach ($DataArray as $e_val) {
                array_push($invalid, array('slip_no' => $e_val));
                // array_push($invalid, $DataArray);
            }
        }

        foreach ($DataArray as $e_val) {
            if (!in_array($e_val, $error_check)) {
                array_push($invalid, array('slip_no' => $e_val));
            }
        }

        //print_r($invalid);
        $returnData['valid'] = $valid;
        $returnData['invalidpallet'] = $invalidpallet;
        $returnData['invalid'] = $invalid;
        echo json_encode($returnData);
    }

    public function validatereturn() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST); exit;
        $shipments = $this->Shipment_model->shipmetsInAwb($_POST['awbArray']);
        $valid = array();
        $invalid = array();
        $slipArray = array();
        // print_r($shipments['result']);exit;

        foreach ($shipments['result'] as $data) {

            array_push($slipArray, $data['slip_no']);
            if ($_POST['type'] == 'RTC') {

                if (trim($data['code']) == 'DL') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            } else {
                if (trim($data['code']) == 'OC' || trim($data['code']) == 'PK' || trim($data['code']) == 'AP' || trim($data['code']) == 'PG') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
        }

        foreach ($_POST['awbArray'] as $newData) {
            if (!in_array($newData, $slipArray)) {
                array_push($invalid, array('slip_no' => $newData, 'code' => 'Wrong Awb'));
            }
        }

        $returnData['valid'] = $valid;
        $returnData['invalid'] = $invalid;
        echo json_encode($returnData);
    }

    function addShipIntegration($shipmentData) {
        return true;
        die;
        $newShiparray = $shipmentData;
        foreach ($newShiparray as $key => $val) {
            $newShiparray[$key]['uniqueid'] = Getuniqueidbycustid($val['cust_id']);
            $newShiparray[$key]['destination'] = getdestinationfieldshow($val['destination'], 'city');
            $newShiparray[$key]['origin'] = getdestinationfieldshow($val['origin'], 'city');
            $newShiparray[$key]['frwd_company_id'] = $val['frwd_company_id'];
            $newShiparray[$key]['frwd_date'] = date("Y-m-d", strtotime($val['frwd_date']));
        }

        $url = 'https://api.diggipacks.com/FM_bookingApi/createOrder';

        $RequestArr = array('super_id' => $this->session->userdata('user_details')['super_id'], 'mainArr' => $newShiparray);
        $dataJson = json_encode($RequestArr);
        $headers = array("Content-type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        //  print_r($response);
        return $responseArray = json_decode($response, true);
    }

    public function sendSms() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $this->load->model('Templates_model');
        $messageData = $this->Templates_model->getTempateByStatus(5);
        // print_r( $messageData); die;
        $shipData = $this->Shipment_model->shipmetsInAwbAll_sms($_POST['awbArray']);
        //print_r( $shipData); die;
        $smsSentArray = array();
        foreach ($shipData['result'] as $shData) {
            $sender_name = getallsellerdatabyID($shData['cust_id'], 'company');

            $cc_id = $shData['frwd_company_id'];
            if ($cc_id > 0) {
                $ccData = GetCourCompanynameIdAll($cc_id);
                $companyName = $ccData['company'];
                $trackLink = $ccData['company_url'] . $shData['frwd_company_awb'];
            } else {
                $url = $_SERVER['HTTP_HOST'];
                $url = ltrim($url, 'fm.');
                $companyName = 'Diggipacks';
                $trackLink = 'https://track.' . $url . '/result_detailfm/' . $shData['slip_no'];
            }




            $param['CUSTOMER_NAME'] = $shData['reciever_name'];
            $param['TRACKING_URL'] = $trackLink;
            $param['3PL_COMPANY'] = $companyName;

            $param['CUST_CARE_MOBILE'] = '1234567890';

            $param['SENDER_NAME'] = $sender_name;
            $param['AWB_NO'] = $shData['slip_no'];
            $messageAr = $messageData['arabic_sms'];

            $phone_no = $shData['reciever_phone'];
            $param['SHIPPER_REFERENCE'] = $shData['shippers_ref_no'];
            $param['TYPE_SHIP'] = $shData['typeship'];
            $param['REFERENCE'] = $shData['booking_id'];
            $param['COD_AMOUNT'] = $shData['total_cod_amt'];
            $param['3PL_COMPANY_AWB'] = $shData['frwd_company_awb'];
            $param['REFERENCE_NUMBER'] = $shData['booking_id'];

            $datamSMS = makeSms($messageAr, $param);
            SEND_SMS($phone_no, $datamSMS);
            $this->Shipment_model->updateStatusSms($shData['slip_no']);
        }
        echo json_encode($datamSMS);
        exit;
    }

    public function dispatchOrder() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        //$_POST=array('awbArray'=>array('STF9252413194'));
        // echo '<pre>';print_r($_POST);exit;




        $shipments = $this->Shipment_model->shipmetsInAwbAll_dispatch($_POST['awbArray']);
        // print "<pre>"; print_r($shipments); die;
        // $valid=array();
        $super_id = $this->session->userdata('user_details')['super_id'];
        $req_awb = array();
        $salla_provider_token = site_configTableSuper_id('salla_provider_token', $super_id);

        $siteUrl = site_configTableSuper_id('site_url', $super_id);
        $superAcccess=='N';

        $direct_dispatch_check=0;
        if ($_POST['type'] == 'DL') {
            if (menuIdExitsInPrivilageArray(255) == 'Y') {
                $direct_dispatch_check=1;
            }
            $superAcccess= getUserNameById_field($super_id,'system_access');
            $code = 'DL';
            $new_status = 5;
            $zidStatus = "indelivery";
            $sallaStatus = 349994915;//8; 
            $note= "جاري التوصيل";
            // $note = "delivering"; //"جاري التوصيل";
            $Activites = "Order Dispatched";
            $self_pickup = 0;
            $Details = 'Order Dispatched By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $salla_new_update=array("status"=>"in_progress");
        }
        if ($_POST['type'] == 'SFP') {
            $direct_dispatch_check=0;
            $code = 'POD';
            $new_status = 7;
            $self_pickup = 1;
            $zidStatus = "delivered";
            // $sallaStatus = 9; //1723506348;
            $sallaStatus=1723506348;
            $note="تم التوصيل";
            // $note = "delivered"; //"تم التوصيل";
            $Activites = "Order Delivered";
            $Details = 'Order Delivered By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $salla_new_update=array("status"=>"delivered");
        }
        if ($_POST['type'] == 'POD') {
            $direct_dispatch_check=0;
            $code = 'POD';
            $new_status = 7;
            $self_pickup = 0;

            $zidStatus = "delivered";
            // $sallaStatus = 9; //1723506348;
            $sallaStatus=1723506348;
            // $note = "delivered"; //"تم التوصيل";
            $note="تم التوصيل";
            $Activites = "Order Delivered";
            $Details = 'Order Delivered By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $salla_new_update=array("status"=>"delivered");
        }
        $slip_data = array();
        $OutboundArray = array();
        $key = 0;
        $key1 = 0;
        $wbh_array = array();
        $send_requestArr = array();
        if (!empty($shipments['result'])) {

            $req_awb_sms_new['super_id'] = $super_id;
            $req_awb_sms_new['host_url']=$_SERVER['HTTP_HOST'];
            $req_awb_sms_new['code']=$code;
            $req_awb_sms_new['status_id']=$new_status;
            $direct_dispatch=0;
            foreach ($shipments['result'] as $data) {
               
                if($direct_dispatch_check==1)
                {
                  
                    if ($data['code'] == 'PK'  || $data['code'] == 'ROG' || $data['code'] == 'OC' || $data['code'] == 'AP' || $data['code'] == 'PG') {
                      $direct_dispatch=1;  
                     
                    } 
                }
                //echo $direct_dispatch; die;
                //  echo   $_POST[$data['slip_no']]['pallet'];
                //   die;

                if ($data['code'] == 'PK'  || $data['code'] == 'ROG' || $direct_dispatch==1) {
                    if ($new_status == 5) {

                        if($superAcccess=='Y')
                        {

                            // $d1 = $this->Shipment_model->filter($data['slip_no']);
                            $d1 = $this->Shipment_model->filter_dispatchdata($data['slip_no']);
                        }
                        //   print_r($d1);exit;
                        // $responseData = $this->addShipIntegration($d1['result']);


                        $responseData['status'] = 200;
                        // $responseData['awb'] = "";
                    } else {
                        $responseData['status'] = 200;
                        $responseData['awb'] = "";
                    }
                    //print_r( $responseData);exit;

                    if ($responseData['status'] == 200) {

                        array_push($req_awb, $data['slip_no']);

                        if ($_POST[$data['slip_no']]['pallet'] > 0)
                            $newUpdatePallet[$key]['pallet_count'] = $_POST[$data['slip_no']]['pallet'];
                        else
                            $newUpdatePallet[$key]['pallet_count'] = 0;
                        $newUpdatePallet[$key]['slip_no'] = $data['slip_no'];
                        $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $statusvalue[$key]['user_type'] = 'fulfillment';
                        $statusvalue[$key]['slip_no'] = $data['slip_no'];
                        $statusvalue[$key]['new_status'] = $new_status;
                        $statusvalue[$key]['code'] = $code;
                        $statusvalue[$key]['Activites'] = $Activites;
                        if (!empty($_POST['comments'])) {
                            $statusvalue[$key]['comment'] = $_POST['comments'];
                        } else {
                            $statusvalue[$key]['comment'] = "";
                        }
                        $statusvalue[$key]['Details'] = $Details;
                        $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                        $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                        /* -------------/Status Array----------- */


                        $slip_data[$key] ['slip_no'] = $data['slip_no'];
                        $slip_data[$key]['self_pickup'] = $self_pickup;
                        $slip_data[$key]['code'] = $code;
                        $slip_data[$key]['delivered'] = $new_status;
                        $slip_data[$key]['dispatch_date'] = date('Y-m-d H:i:s');
                        if ($new_status == 7) {
                            $slip_data[$key]['close_date'] = date('Y-m-d H:i:s');
                        }
                        //======================outbound charges=====================================///
                        $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data['slip_no']);
                        $totalLocationboxes = GetshpmentDataByawb($data['slip_no'], 'stocklcount');

                        $SkuArray = Getallskudatadetails($data['slip_no']);
                        $sku_ID = $SkuArray[0]['itmSku'];
                        $item_type = getalldataitemtables($sku_ID, 'type');
                        $totalPieces = $getallskuArray['pieces'];
                        $totalPiecesF = $getallskuArray['pieces'] - 1;

                        // $sku_size=getalldataitemtables($sku_ID,'sku_size');
                        // $locationLimit1=$totalPieces/$sku_size;
                        //$locationLimit=ceil($locationLimit1);
                        $seller_id = $getallskuArray['cust_id']; //GetallCutomerBysellerId($getallskuArray['cust_id'], 'seller_id');


                        if ($item_type == 'B2B') {
                            $GetchecktodayfirstCharge = $this->Pickup_model->GetallcheckFirstEntry($seller_id);
                            if ($GetchecktodayfirstCharge == 0) {
                                $OutboundCharge = getalluserfinanceRates($seller_id, 8, 'rate');
                                if ($OutboundCharge > 0)
                                    $OutboundCharge = $OutboundCharge;
                                else
                                    $OutboundCharge = getalluserfinanceRates($seller_id, 7, 'rate');
                            } else
                                $OutboundCharge = getalluserfinanceRates($seller_id, 7, 'rate');



                            $totalOutboundCharge = $OutboundCharge * $totalLocationboxes;
                        } else {
                            $OutboundChargeA = getalluserfinanceRates($seller_id, 8, 'rate');
                            $OutboundCharge = getalluserfinanceRates($seller_id, 7, 'rate');
                            $OutBoundPieces = getalluserfinanceRates($seller_id, 7, 'setpiece');

                            if ($OutBoundPieces >= $totalPieces)
                                $addisnalOutboundChargeQty = 1;
                            else
                                $addisnalOutboundChargeQty = $totalPieces - $OutBoundPieces;

                            $totalOutboundCharge1 = $OutboundCharge;
                            $totalOutboundCharge2 = $OutboundChargeA * $addisnalOutboundChargeQty;

                            if ($totalPieces == 1) {
                                $totalOutboundCharge = $totalOutboundCharge1;
                            } else {
                                $totalOutboundCharge = $totalOutboundCharge1 + $totalOutboundCharge2;
                            }

                            /* $totalOutboundCharge1=$OutboundCharge;
                              $totalOutboundCharge2=$OutboundChargeA*$totalPiecesF;
                              $totalOutboundCharge=$totalOutboundCharge1+$totalOutboundCharge2; */
                        }


                        $OutboundArray[$key]['seller_id'] = $seller_id;
                        $OutboundArray[$key]['slip_no'] = $data['slip_no'];
                        $OutboundArray[$key]['outcharge'] = $totalOutboundCharge;
                        $OutboundArray[$key]['entrydate'] = date("Y-m-d H:i:sa");
                        $OutboundArray[$key]['pieces'] = $totalPieces;
                        $OutboundArray[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                        $sellerDetails = GetSinglesellerdata($seller_id, $this->session->userdata('user_details')['super_id']);

                        $token = $sellerDetails['manager_token'];
                        $salatoken = $sellerDetails['salla_athentication'];
                        $zid_update_details = $sellerDetails['zid_update_details'];

                        //========WooCommerce Status===========//
                        $wc_active = $sellerDetails['wc_active'];

                        if ($_POST['type'] == 'POD' && $wc_active == 1 && !empty($sellerDetails['wc_statues'])) {
                            $wc_consumer_key = $sellerDetails['wc_consumer_key'];
                            $wc_secreat_key = $sellerDetails['wc_secreat_key'];
                            $wc_store_url = $sellerDetails['wc_store_url'];
                            $wc_statues = json_decode($sellerDetails['wc_statues']);
                            $Status_WC = $this->GetWC_status($wc_statues, 'POD');
                            $data_wc = array(
                                'customer_key' => $wc_consumer_key,
                                'customer_secret' => $wc_secreat_key,
                                'store_url' => $wc_store_url,
                                'order_id' => $data['booking_id'],
                                'status' => $Status_WC,
                                'status_des' => "Delivered By Diggipacks",
                            );
                            array_push($send_requestArr, $data_wc);
                        }
                        //=====================================//

                        $WB_Confing = webhook_settingsTable($data['cust_id']);
                        if ($WB_Confing['subscribe'] == 'Y') {
                            $wb_request = array(
                                'datetime' => date('Y-m-d H:i:s'),
                                "code" => $code,
                                "status" => $Activites,
                                "cc_name" => GetCourCompanynameId($data['frwd_company_id'], 'company'),
                                "cc_awb" => $data['frwd_company_awb'],
                                "cc_status" => null,
                                "cc_status_details" => null,
                                "slip_no" => $data['slip_no'],
                                "booking_id" => $data['booking_id'],
                                "cust_id" => $data['cust_id'],
                                "WB_Confing" => $WB_Confing
                            );
                            array_push($wbh_array, $wb_request);
                        }

                        if ($sellerDetails['is_shopify_active'] == 1 && !empty($data['shopify_order_id'])) {
                            shopifyFulfill($data['slip_no'], $data['shopify_order_id'], $sellerDetails);
                        }

                        if (empty($siteUrl) || $siteUrl == '1')
                                $siteUrl = 'diggipacks.com';

                                $new_awb_number = $data['slip_no'];
                                $pdf_label = "https://api." . $siteUrl . "/API/Print/" . $new_awb_number;
                                $tracking_link1 = "https://track." . $siteUrl . "/result_detailfm/" . $new_awb_number;
                                $tracking_link = "https://track." . $siteUrl . "/result_detailfm/" . $new_awb_number;
                                if (!empty($data['frwd_company_awb'])) {
                                    $tracking_link = makeTrackUrl($data['frwd_company_id'], $data['frwd_company_awb']);
                                    $new_awb_number = $data['frwd_company_awb'];
                                }
                                if (empty($tracking_link)) {
                                    $tracking_link = $tracking_link1;
                                }


                        if (!empty($data['shippers_ref_no']) && $data['shippers_ref_no'] != $data['booking_id']) {
                    
                            if (!empty($salatoken)) {
                                $salla_new_update['slip_no']=$data['slip_no'];
                                $salla_new_update['salla_order_id']=$data['salla_order_id'];

                                if ($sellerDetails['salla_update_details'] == 'Y') {
                                    update_shipment_salla($tracking_link, $salatoken, $data['shippers_ref_no'], $seller_id, $new_awb_number, $pdf_label, $super_id,$salla_new_update);
                                }
                                update_status_salla($sallaStatus, $note, $salatoken, $data['shippers_ref_no'], $seller_id, $data['slip_no'],$pdf_label, $super_id,$salla_new_update);
                            }
                        }                                


                        // if (!empty($salatoken)) {
                        //     $Salla_tracking_url = $data['frwd_company_label'];
                        //     $frwd_company_awb = ($data['frwd_company_awb'] != '') ? $data['frwd_company_awb'] : '';
                        //     $Salla_status = "DL";
                        //     $Salla_note = "delivering";
                        //     $sallaStatus = Salla_StatusUpdate($data['booking_id'], $Salla_status, $Salla_note, $frwd_company_awb, $Salla_tracking_url);
                        // }





                        //update_status_salla($sallaStatus , $note, $salatoken,$data['shippers_ref_no'],$seller_id,$data['slip_no'] ); 


                        if (!empty($token)) {
                            $slip_no = $data['slip_no'];
                            if (!empty($data['frwd_company_awb'])) {
                                $trackingurl = makeTrackUrl($data['frwd_company_id'], $data['frwd_company_awb']);

                                $lable = $data['frwd_company_label'];
                            } else {
                                $lable = 'https://api.diggipacks.com/API/print/' . $data['slip_no'];

                                $trackingurl = TRACKURL . $slip_no;
                            }


                            //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                            updateZidStatus($data['booking_id'], $token, $zidStatus, $slip_no, $lable, $trackingurl,$seller_id);
                        }


                        //=========================================================================//     
                        $key++;
                    } else {
                        //echo print_r($responseData) ; exit;
                        $error_data[$key1]['slip_no'] = $data['slip_no'];
                        $error_data[$key1]['error'] = $responseData['error'];

                        $key1++;
                    }
                }
            }
        } else {
            // $error_data[0]['slip_no'] = $_POST['awbArray'];
            $error_data = "invalid status";
        }

        // print_r($req_awb);
        // die;
        if (!empty($statusvalue) && !empty($slip_data)) {
            // echo "test"; die;
            $citc_req['t_slip_no'] = $req_awb;
            $this->session->set_userdata(array('tracking_citc_req' => $citc_req));

            $this->Status_model->insertStatus($statusvalue);
            $this->Shipment_model->updateStatusBatch($slip_data);
            if (!empty($newUpdatePallet)) {
                $this->Shipment_model->updateStatusBatch($newUpdatePallet);
            }
            $this->Pickup_model->GetalloutboundDataAdded($OutboundArray);
            if (!empty($send_requestArr)) {
                $this->session->set_userdata(array('wc_status_req' => $send_requestArr));
            }

            if(!empty($req_awb))
            {
                $req_awb_sms_new['slip_nos']=$req_awb;
                $sms_final=json_encode($req_awb_sms_new);
                shell_exec('/usr/bin/php '.SENDSMSPATH.'SendSms.php ' . escapeshellarg(serialize($sms_final)) . ' > /dev/null 2>/dev/null & ');
            }

            // if(!empty($req_awb) &&  $this->session->userdata('user_details')['super_id'] == 6)
            // {
            //     $req_awb_sms_new['slip_nos']=$req_awb;
            //     $sms_final=json_encode($req_awb_sms_new);
            //     // shell_exec('/usr/bin/php '.SENDSMSPATH.'SendSms.php ' . escapeshellarg(serialize($sms_final)) . ' > /dev/null 2>/dev/null & ');
                
            //     exec('/usr/bin/php  '.SENDSMSPATH.'SendSmsNew.php ' . escapeshellarg(serialize($sms_final)) . '  2>&1 &/dev/null 2>&1 & ',$output);
            //     print_r($output);die;
            // }

            if (!empty($wbh_array)) {
                $this->webhook_request($wbh_array);
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }
        }







        echo json_encode($error_data);
    }

    private function webhook_request($data=array())
    {
        $requestArr['webhook_status']=$data;
        $req_final=json_encode($requestArr);
       //exec('/usr/bin/php '.SENDWEBHOOK.'Webhook.php '. escapeshellarg(serialize($req_final)) . '  2>&1 &/dev/null 2>&1 & ',$output);//,$output
        shell_exec('/usr/bin/php '.SENDWEBHOOK.'Webhook.php ' . escapeshellarg(serialize($req_final)) . ' > /dev/null 2>/dev/null & '); 
       // print_r($output); 
    }

    private function curl_request_WC($data_array = array()) {
        $dataJson = json_encode($data_array);
        $headers = array(
            "Content-type: application/json",
        );
        $url = "https://dg-api.fastcoo.net/WooCommerce_Api/updateStatus";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        return $response = curl_exec($ch);
    }

    private function GetWC_status($data = array(), $cod = null) {
        foreach ($data as $key => $val) {
            if ($val->code == $cod) {
                return $val->wc_status;
            }
        }
    }

    public function returnformlmOrder() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST);
        //die();
        $shipments = $this->Shipment_model->shipmetsInAwbAll($_POST['awbArray']);
        if ($_POST['type'] == 'RTC') {
            //echo json_encode($_POST['awbArray']); exit;
            // $valid=array();
            $slip_data = array();
            foreach ($shipments['result'] as $data) {

                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_no'];
                $statusvalue[$key]['new_status'] = 8;
                $statusvalue[$key]['code'] = 'RTC';
                $statusvalue[$key]['Activites'] = 'Order Return';
                $statusvalue[$key]['Details'] = 'Order Return By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                /* -------------/Status Array----------- */


                $slip_data[$key] ['slip_no'] = $data['slip_no'];

                $slip_data[$key]['code'] = 'RTC';
                $slip_data[$key]['delivered'] = '8';
                $slip_data[$key]['close_date'] = date('Y-m-d H:i:s');
                ;

                $SkudataArray = Getallskudatadetails($data['slip_no']);
                foreach ($SkudataArray as $UPdata) {
                    // $updatearray=array("");
                    $this->Shipment_model->GetupdatedeletedInventory_LM($UPdata, $data['slip_no']);
                    //echo json_encode($UPdata) ; 
                }
                $key++;
            }

            // echo json_encode($slip_data) ; exit;
            //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
            if ($this->Status_model->insertStatus($statusvalue)) {
                $this->Shipment_model->updateStatusBatch($slip_data);
                //echo json_encode($this->exportExcel($_POST['exportData'],$file_name)) ;
            }
        } else {

            foreach ($shipments['result'] as $data) {
                if (($data['code'] == 'PK' && $data['delivered'] == '4') || ($data['code'] == 'OC' && $data['delivered'] == '1') || ($data['code'] == 'PG' && $data['delivered'] == '2') || ($data['code'] == 'AP' && $data['delivered'] == '3')) {
                    $SkudataArray = Getallskudatadetails($data['slip_no']);
                    foreach ($SkudataArray as $UPdata) {
                        $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $statusvalue[$key]['user_type'] = 'fulfillment';
                        $statusvalue[$key]['slip_no'] = $data['slip_no'];
                        $statusvalue[$key]['new_status'] = 9;
                        $statusvalue[$key]['code'] = 'C';
                        $statusvalue[$key]['Activites'] = 'Order Canceled';
                        $statusvalue[$key]['Details'] = 'Order Canceled By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                        $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                        $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                    }
                    $this->Status_model->insertStatus($statusvalue);
                    foreach ($SkudataArray as $UPdata) {
                        // $updatearray=array("");
                        $this->Shipment_model->GetupdatedeletedInventory($UPdata, $data['slip_no']);
                        //echo json_encode($UPdata) ; 
                    }
                }
            }
        }
    }

    public function packCheck() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Pickup_model->pickListFilterNotPicked($_POST['slip_no'], $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination);
        $ReturnArray = $shipments['result'];

        // print_r( $shipments); exit;
        foreach ($ReturnArray as $key => $val) {
            $frwd_company_id = GetshpmentDataByawb($val['slip_no'], 'frwd_company_id');
            $sku_details = $this->Pickup_model->GetskuDetailspack_new($val['slip_no']);
           
            foreach ($sku_details as $newkey => $skudata) {
                $singleSku = $this->ItemInventory_model->GetshowingSkuMeadiaDataQry($skudata);
                $skuArrayDatap[$newkey]['sku'] = $skudata['sku'];
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
            if ($frwd_company_id != '') {
                $ReturnArray[$key]['frwd_company_id2'] = $frwd_company_id;
                $ReturnArray[$key]['frwd_company_id'] = GetCourCompanynameId($frwd_company_id, 'company');
                $ReturnArray[$key]['frwd_company_awb'] = GetshpmentDataByawb($val['slip_no'], 'frwd_company_awb');
                $ReturnArray[$key]['frwd_company_label'] = GetshpmentDataByawb($val['slip_no'], 'frwd_company_label');
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

    public function packCheck_new() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Pickup_model->pickListFilterNotPicked_new($_POST['slip_no']);
        $ReturnArray = $shipments['result'];

        // print_r( $shipments); exit;
        foreach ($ReturnArray as $key => $val) {
            $frwd_company_id = GetshpmentDataByawb($val['slip_no'], 'frwd_company_id');
            $sku_details = $this->Pickup_model->GetskuDetailspack($val['slip_no']);
            foreach ($sku_details as $newkey => $skudata) {
                $singleSku = $this->ItemInventory_model->GetshowingSkuMeadiaDataQry($skudata);
                $skuArrayDatap[$newkey]['sku'] = $skudata['sku'];
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
        $return['count'] = $shipments['count'];
        echo json_encode($return);
    }

    public function packCheck_tod() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Pickup_model->pickListFilterNotPicked_tod($_POST['slip_no'], $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination);
        $ReturnArray = $shipments['result'];
        foreach ($ReturnArray as $key => $val) {
            $frwd_company_id = GetshpmentDataByawb($val['slip_no'], 'frwd_company_id');
            $sku_details = json_decode($val['sku'], true);
            foreach ($sku_details as $newkey => $skudata) {
                $singleSku = $this->ItemInventory_model->GetshowingSkuMeadiaDataQry($skudata);
                $skuArrayDatap[$newkey]['sku'] = $skudata['sku'];
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

            $ReturnArray[$key]['frwd_company_id'] = GetCourCompanynameId($frwd_company_id, 'company');
            $ReturnArray[$key]['frwd_company_awb'] = GetshpmentDataByawb($val['slip_no'], 'frwd_company_awb');
        }
        $return['result'] = $ReturnArray;
        $return['count'] = 1;
        echo json_encode($return);
    }

    public function CheckReturnFulfil() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        // echo json_encode($_POST);die;
        $shipments = $this->Pickup_model->GetCheckReturnFulfilstatus($_POST['slip_no']);
        $newarray = $shipments['result'];
        foreach ($newarray as $key => $val) {
            $newarray[$key]['sku'] = json_encode($this->Pickup_model->GetskuDetailsRTF($val['slip_no']));
        }

        $returnArr['result'] = $newarray;
        $returnArr['count'] = $shipments['count'];
        echo json_encode($returnArr);
    }

    public function CheckCancelOrder() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Pickup_model->GetCheckCancelOrderQry($_POST['slip_no']);
        $newarray = $shipments['result'];
        foreach ($newarray as $key => $val) {
            $newarray[$key]['origin_name'] = getdestinationfieldshow($val['origin'], 'city');
            $newarray[$key]['destination_name'] = getdestinationfieldshow($val['destination'], 'city');
            $newarray[$key]['cust_name'] = getallsellerdatabyID($val['cust_id'], 'name');
        }

        $returnArr['result'] = $newarray;
        $returnArr['count'] = $shipments['count'];
        echo json_encode($returnArr);
    }

    public function GetallstockLocationUser() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $locationLimitFinal = 0;
        $sliNO = $_POST[0]['slip_no'];
        $sku_no = $_POST[0]['sku'];

        $dataArray = $this->Shipment_model->stocklocation_details($sliNO, $sku_no);
        //echo "<br><pre>"; print_r($dataArray);
        if (!empty($dataArray)) {
            //echo " Sku no  = ".$dataArray[0]['sku'];
            foreach ($dataArray as $key => $stck) {

                $skuno = $stck['sku'];
                $shelve_no = $stck['deducted_shelve'];
                $seller_id = $stck['cust_id'];
                $dataArray[$key]['missing'] = 0;
                $dataArray[$key]['damage'] = 0;
                $dataArray[$key]['up_location'] = "N";

                $dataArray[$key]['othertotal'] = $stck['piece'];

                $dataArray[$key]['seller'] = getallsellerdatabyID($seller_id, 'company');
                $slip_no = $stck['slip_no'];
                $piece = $stck['piece'];
                $piece_new = $piece;
                $sku_size = $this->Shipment_model->skuSize($skuno);
                $skuId = $this->Shipment_model->skuId($skuno);

                $dataArray[$key]['sku_size'] = $sku_size;
//                if($sku_size>$piece)
//                $dataArray[$key]['stock_need']=1;
//                else
//                $dataArray[$key]['stock_need'] =round($piece/$sku_size);

                if ($sku_size >= $piece)
                    $dataArray[$key]['stock_need'] = 1;
                else {
                    $locationLimit1 = $piece / $sku_size;
                    $dataArray[$key]['stock_need'] = ceil($locationLimit1);
                }

                $preId = array();
                for ($i = 0; $i < $dataArray[$key]['stock_need']; $i++) {


                    if ($piece_new > $sku_size) {
                        $sendPiece = $sku_size;
                        $piece_new = $piece_new - $sku_size;
                    } else {
                        $sendPiece = $piece_new;
                        $piece_new = 0;
                    }

                    $locArray = $this->Shipment_model->stockInventory($skuno, $shelve_no, $seller_id, $sendPiece, $preId);
                    $dataArray[$key]['local'][][] = array('qty' => $sendPiece, 'location' => $locArray[0]['id'], 'stock_location' => $locArray[0]['stock_location'], 'stock_location_old' => $locArray[0]['stock_location'], 'shelve_no' => $locArray[0]['shelve_no'], 'super_id' => $this->session->userdata('user_details')['super_id'], 'seller_id' => $seller_id, 'item_sku' => $skuId, 'sku_size' => $sku_size);

                    $preId[] = $locArray[0]['id'];
                    //$datastock[]= $locArray;  
                }

                // $dataArray[$key]['local'] = $datastock;   
                // $dataArray[$key]['location'] = $datastock[0]['id'];   
            }
            // echo json_encode($dataArray);
            //echo "<br><pre>"; print_r($datastock); die; 
            //$ers = $datastock['ers'];
        }
        echo json_encode($dataArray);
    }

    public function save_details() {


        $RequestArr = json_decode(file_get_contents('php://input'), true);

        $postData = $RequestArr['mainlist'];
        $remarkbox = $RequestArr['remarkbox'];
        $slip_no = $postData[0]['slip_no'];

        $req_awb= array();
        $new_wh = $RequestArr['wh_id'];
        $wbh_array = array();
        $wbh_array_in = array();



        $check_slipNo = $this->Shipment_model->getallshipmentdatashow($slip_no);
        //$check_slipNo = $this->Shipment_model->getallshipmentdatashow($slip_no);

        $token = GetallCutomerBysellerId($check_slipNo['cust_id'], 'manager_token');
        $uniqueid = GetallCutomerBysellerId($check_slipNo['cust_id'], 'uniqueid');
        $salatoken = GetallCutomerBysellerId($check_slipNo['cust_id'], 'salla_athentication');
        $zid_store_id = GetallCutomerBysellerId($check_slipNo['cust_id'], 'zid_sid');
        $send_requestArr = array();
        
        $salla_return_status = GetallCutomerBysellerId($check_slipNo['cust_id'], 'salla_return_status');
        if (($salla_return_status == null || $salla_return_status == '' ) && !empty($salatoken)) {

            $salla_return_status = getSallaStatus($salatoken, $check_slipNo['cust_id']);
        }        

        $req_awb_sms_new['super_id']=$this->session->userdata('user_details')['super_id'];
        $req_awb_sms_new['host_url']=$_SERVER['HTTP_HOST'];
        $req_awb_sms_new['code']=$check_slipNo['code'];
        $req_awb_sms_new['status_id']=8;


        //|| $check_slipNo['code'] == 'POD'
        if ($check_slipNo['code'] == 'DL' || $check_slipNo['code'] == 'RPOD' || $check_slipNo['code'] == 'D3PL' || $check_slipNo['code'] == 'ROP' || $check_slipNo['code'] == 'OFD' || $check_slipNo['code'] == 'DOP' || $check_slipNo['code'] == 'FD' || $check_slipNo['code'] == 'PC' || $check_slipNo['delivered'] == '16' || $check_slipNo['code'] == 'IT' || $check_slipNo['code'] == 'DLM' || $check_slipNo['code'] == 'OP' || $check_slipNo['code'] == 'RI' || $check_slipNo['code'] == 'OH' || $check_slipNo['code'] == 'OU' || $check_slipNo['code'] == 'UAR' || $check_slipNo['code'] == 'RSD' || $check_slipNo['code'] == 'UK' || $check_slipNo['code'] == 'CANC' || $check_slipNo['code'] == 'PDD' || $check_slipNo['code'] == 'RPC' || $check_slipNo['code'] == 'ROG' || $check_slipNo['code'] == 'ROFD' || $check_slipNo['code'] == 'RPOD' || $check_slipNo['code'] == 'RIT' || $check_slipNo['code'] == 'RPUC') {


            $WB_Confing_in = webhook_settingsTable_in($check_slipNo['cust_id']);
            array_push($req_awb,$check_slipNo['slip_no']);

            foreach ($postData as $SaveStock) {
                $total_damage = $SaveStock['missing'] + $SaveStock['damage'];

                foreach ($SaveStock['local'] as $fzArray) {
                    foreach ($fzArray as $fArray) {
                        if (empty($fArray['shelve_no']))
                            $fArray['shelve_no'] = "";
                        if ($total_damage > 0) {
                            $total_qty = $fArray['qty'] - $total_damage;
                        } else {
                            $total_qty = $fArray['qty'];
                        }
                        $sku_id = getalldataitemtablesSKU($SaveStock['sku'], 'id');
                        $wh_id = getalldataitemtablesSKU($SaveStock['sku'], 'wh_id');
                        if ($total_damage > 0) {


                            $damage_iniventory[] = array(
                                'item_sku' => $sku_id,
                                'quantity' => $total_damage,
                                'd_qty' => $SaveStock['damage'],
                                'm_qty' => $SaveStock['missing'],
                                'order_no' => $SaveStock['slip_no'],
                                'shelve_no' => !empty($fArray['shelve_no']) ? $fArray['shelve_no'] : '',
                                'stock_location' => !empty($fArray['stock_location']) ? $fArray['stock_location'] : '',
                                'itype' => 'B2C',
                                'super_id' => $this->session->userdata('user_details')['super_id'],
                                'updated_by' => $this->session->userdata('user_details')['user_id'],
                                'seller_id' => $SaveStock['cust_id'],
                                'update_date' => date("Y-m-d H:i:s"),
                                'order_type' => 'shipment'
                            );
                        }

                        if (!empty($fArray['location'])) {
                            $updateLoc[] = array(
                                'id' => $fArray['location'],
                                'quantity' => $total_qty,
                                'stock_location' => $fArray['stock_location'],
                                'stock_location_old' => $fArray['stock_location_old'],
                                'shelve_no' => $fArray['shelve_no'],
                                'slip_no' => $slip_no,
                                'seller_id' => $check_slipNo['cust_id'],
                                'item_sku' => $fArray['item_sku'],
                                'shelve_no' => $fArray['shelve_no'],
                                'wh_id' => $wh_id
                            );
                        } else {

                            //  echo "not accpted"; die;

                            $addLoc[] = array(
                                'quantity' => $total_qty,
                                'stock_location' => $fArray['stock_location'],
                                'shelve_no' => $fArray['shelve_no'],
                                'super_id' => $check_slipNo['super_id'],
                                'seller_id' => $check_slipNo['cust_id'],
                                'item_sku' => $fArray['item_sku'],
                                'wh_id' => $wh_id
                            );

                            $activitiesArr[] = array('st_location' => $fArray['stock_location'], 'item_sku' => $fArray['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $check_slipNo['cust_id'], 'qty' => $total_qty, 'p_qty' => 0, 'qty_used' => $total_qty, 'type' => 'return', 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $slip_no, 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $fArray['shelve_no'], 'comment' => 'RTF');
                        }

                        // if($this->session->userdata('user_details')['super_id'] == 256){
                        //         $Salla_tracking_url = $check_slipNo['frwd_company_label'];                              
                        //         $Salla_status = 9;
                        //         $Salla_note = "delivered";
                        //         $sallaStatus = Salla_StatusUpdate($check_slipNo['booking_id'], $Salla_status, $Salla_note, $check_slipNo['slip_no'], $Salla_tracking_url);
                        // }
                        // if (!empty($salatoken) && $this->session->userdata('user_details')['super_id'] != 256) {
                        //     $sallaStatus = 525144736; 
                        //     $note = "ملغي";
                        //     update_status_salla($sallaStatus , $note, $salatoken,$check_slipNo['shippers_ref_no'],$check_slipNo['cust_id'],$check_slipNo['slip_no'] );
                        //     }

                        if (!empty($salatoken)) {
                            $sallaStatus = $salla_return_status;
                            $note = "مسترجع";
                            $salla_new_update=array("status"=>"returned");
                            update_status_salla($sallaStatus, $note, $salatoken, $check_slipNo['shippers_ref_no'], $check_slipNo['cust_id'], $check_slipNo['slip_no'],'','',$salla_new_update);
                        }

                        if ($check_slipNo['cust_id'] == '214' || $check_slipNo['cust_id'] == '31') {
                            $this->load->model('Manifest_model');
                            $this->Manifest_model->getupdateTempstock($total_qty, $check_slipNo['cust_id'], $SaveStock['sku']);
                            $temp_stock = $this->Manifest_model->GetTempStockData($SaveStock['sku'], $check_slipNo['cust_id']);
                            //$sall_update_qty=$temp_stock['qty']+$skuqtyval['totalqty'];
                            salla_provider_qty_update($temp_stock['qty'], $uniqueid, $SaveStock['sku'], $check_slipNo['cust_id'], $slip_no, $total_qty);
                        } else if (!empty($salatoken)) {



                            //==========update zid stock===============//
                            $sallaReqArr = GetAllQtyforSellerby_ID($fArray['item_sku'], $check_slipNo['cust_id']);
                            $quantity = $sallaReqArr['quantity'] + $total_qty;
                            $pid = $sallaReqArr['sku'];
                            $sallatoken = $salatoken;
                            //echo "<pre>"; print_r($sallaReqArr); exit;

                            $reszid = update_salla_qty_product($quantity, $pid, $sallatoken);
                            //=========================================//
                        }

                        if (!empty($token)) {
                            //==========update zid stock===============//
                            $zidReqArr = GetAllQtyforSellerby_ID($fArray['item_sku'], $check_slipNo['cust_id']);
                            //  print_r(  $zidReqArr);
                            $quantity = $zidReqArr['quantity'] + $total_qty;
                            $pid = $zidReqArr['zid_pid'];
                            $token = $token;
                            $storeID = $zid_store_id;
                            update_zid_product($quantity, $pid, $token, $storeID,$check_slipNo['cust_id']);

                            //=========================================//
                        }


                        if ($WB_Confing_in['subscribe'] == 'Y') {
                            // $wbh_array = array();
                            $wb_request_in = array(
                                'datetime' => date('Y-m-d H:i:s'),
                                "sku" =>$sku_id,
                                "cust_id" => $check_slipNo['cust_id'],
                                'sku_name'=>$SaveStock['sku'],
                                "order_from" => "RTC",
                                "WB_Confing" => $WB_Confing_in,
                                'comment'=>$check_slipNo['slip_no'],
                                'super_id'=>$this->session->userdata('user_details')['super_id']
                            );
                            array_push($wbh_array_in, $wb_request_in);
                        }


                    }
                }
            }

            $sellerDetails = GetSinglesellerdata($check_slipNo['cust_id'], $this->session->userdata('user_details')['super_id']);

            //========WooCommerce Status===========//
            $wc_active = $sellerDetails['wc_active'];
            if ($wc_active == 1 && !empty($sellerDetails['wc_statues'])) {
                $wc_consumer_key = $sellerDetails['wc_consumer_key'];
                $wc_secreat_key = $sellerDetails['wc_secreat_key'];
                $wc_store_url = $sellerDetails['wc_store_url'];
                $wc_statues = json_decode($sellerDetails['wc_statues']);
                $Status_WC = $this->GetWC_status($wc_statues, 'RTC');
                $data_wc = array(
                    'customer_key' => $wc_consumer_key,
                    'customer_secret' => $wc_secreat_key,
                    'store_url' => $wc_store_url,
                    'order_id' => $check_slipNo['booking_id'],
                    'status' => $Status_WC,
                    'status_des' => "Return By Diggipacks",
                );
                array_push($send_requestArr, $data_wc);
            }
            //=====================================//

            $WB_Confing = webhook_settingsTable($check_slipNo['cust_id']);
            if ($WB_Confing['subscribe'] == 'Y') {
                $wb_request = array(
                    'datetime' => date('Y-m-d H:i:s'),
                    "code" => 'RTC',
                    "status" => 'Order Return',
                    "cc_name" => GetCourCompanynameId($check_slipNo['frwd_company_id'], 'company'),
                    "cc_awb" => $check_slipNo['frwd_company_awb'],
                    "cc_status" => null,
                    "cc_status_details" => null,
                    "slip_no" => $check_slipNo['slip_no'],
                    "booking_id" => $check_slipNo['booking_id'],
                    "cust_id" => $check_slipNo['cust_id'],
                    "WB_Confing" => $WB_Confing
                );
                array_push($wbh_array, $wb_request);
            }
            $statusvalue[0]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[0]['user_type'] = 'fulfillment';
            $statusvalue[0]['slip_no'] = $slip_no;
            $statusvalue[0]['new_status'] = 8;
            $statusvalue[0]['code'] = 'RTC';
            if (!empty($remarkbox)) {
                $statusvalue[0]['comment'] = $remarkbox;
            }
            $statusvalue[0]['Activites'] = 'Return';
            $statusvalue[0]['Details'] = 'Order Return, Update By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvalue[0]['entry_date'] = date('Y-m-d H:i:s');
            $statusvalue[0]['super_id'] = $this->session->userdata('user_details')['super_id'];
            $shipData = array();

            $updateArray = array('code' => 'RTC', 'delivered' => 8, 'close_date' => date('Y-m-d H:i:s'));
            $shipData['where_in'] = $slip_no;
            $shipData['update'] = $updateArray;
            $shipData['close_date'] = date('Y-m-d H:i:s');
            if ($this->Status_model->insertStatus($statusvalue)) {
                $req_data = $this->Shipment_model->updateStatus($shipData);
                $query_log = array("awb" => $slip_no, "q_data" => $req_data, "super_id" => $this->session->userdata('user_details')['super_id']);
                $this->Shipment_model->Query_log($query_log);
                $this->Shipment_model->stockdeletepicklistFM($slip_no);

                if (!empty($token)) {

                    if (!empty($check_slipNo['frwd_company_awb'])) {
                        $trackingurl = makeTrackUrl($data['frwd_company_id'], $check_slipNo['frwd_company_awb']);
                        $lable = $check_slipNo['frwd_company_label'];
                    } else {
                        $lable = 'https://api.diggipacks.com/API/print/' . $check_slipNo['slip_no'];
                    }
                    $trackingurl = TRACKURL . $slip_no;
                    //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                    updateZidStatus($check_slipNo['booking_id'], $token, 'cancelled', $slip_no, $lable, $trackingurl, $check_slipNo['cust_id']);
                }
            }


            if (!empty($updateLoc)) {
                $this->Shipment_model->stockSaveShipmentFM($updateLoc);
            }


            if (!empty($addLoc)) {
                $this->Shipment_model->addInventory($addLoc, $activitiesArr);
            }

            if (!empty($damage_iniventory)) {
                $this->Shipment_model->Getupdatedamage_inventory($damage_iniventory);
            }

            if (!empty($send_requestArr)) {
                $this->session->set_userdata(array('wc_status_req' => $send_requestArr));
            }

            if (!empty($wbh_array)) {
                $this->webhook_request($wbh_array);
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }

            if(!empty($req_awb))
            {
                $req_awb_sms_new['slip_nos']=$req_awb;
                $sms_final=json_encode($req_awb_sms_new);
                // shell_exec('/usr/bin/php '.SENDSMSPATH.'SendSms.php ' . escapeshellarg(serialize($sms_final)) . ' > /dev/null 2>/dev/null & ');
            }
            
            if(!empty($wbh_array_in))
            {
               $this->webhook_request_in($wbh_array_in);  
            }            

            $error_status = array('status' => true);
            echo json_encode($error_status);
        }//endif 
        else {
            $error_status = array('status' => false);
            echo json_encode($error_status);
            //  echo $this->db->last_query();
        }
    }


    private function webhook_request_in($data=array())
    {
        $requestArr['webhook_status']=$data;
        $req_final=json_encode($requestArr);
       //exec('/usr/bin/php '.SENDWEBHOOK.'Webhook.php '. escapeshellarg(serialize($req_final)) . '  2>&1 &/dev/null 2>&1 & ',$output);//,$output
        shell_exec('/usr/bin/php '.SENDWEBHOOK.'Webhook_in.php ' . escapeshellarg(serialize($req_final)) . ' > /dev/null 2>/dev/null & '); 
       // print_r($output); 
    } 

    
    public function GetCheckStockLOcationValid() {
        $postData = json_decode(file_get_contents('php://input'), true);
        if (!empty($postData['stock_location'])) {
            $location_data_valid = $this->ItemInventory_model->Getallstocklocationdata_return($postData['seller_id'], $postData['stock_location']);
            if (!empty($location_data_valid)) {
                $total_qty = $postData['qty'];
                $stock_rows = Getinventorytable_data($postData['item_sku'], $postData['seller_id'], $postData['stock_location']);
                // print "<pre>"; print_r($stock_rows);die;

                $sku_size = $postData['sku_size'];
                $quantity_in = $stock_rows['quantity'];
                $totalQTY_size_in = $quantity_in + $total_qty;
                $in_sku = $stock_rows['item_sku'];
                // echo "{$totalQTY_size_in}=={$sku_size}";

                if (!empty($stock_rows)) {
                    if ($in_sku == $postData['item_sku']) {
                        if ($totalQTY_size_in <= $sku_size) {
                            $return = array("error" => "valid");
                        } else {
                            $return = array("error" => "capacity_full");
                        }
                    } else {
                        $return = array("error" => "invalid_location");
                    }
                } else {
                    if ($totalQTY_size_in <= $sku_size) {
                        $return = array("error" => "valid");
                    } else {
                        $return = array("error" => "capacity_full");
                    }
                }
            } else {
                $return = array("error" => "invalid_location");
            }
        }
        //echo "<pre>";
        echo json_encode($return);
        die;
    }

    public function ReturnLMtoFMOrder() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST);die; 

        $exportData = $_POST['exportData'];
        $OtherArr = $_POST['otherdata'][0];
        if (!empty($_POST['otherdata'][2]))
            $status_type = trim($_POST['otherdata'][1]);
        else
            $status_type = "";
        $expdatesArr = $OtherArr['expire_date'];
        $locationcount = $OtherArr['locationcount'];
        $shelve_noArr = $OtherArr['shelve_no'];
        $locationGet = sizeof($OtherArr['stock_location']);

        if ($locationcount == $locationGet) {
            $StockArray = $OtherArr['stock_location'];
            $stocklocation = $StockArray;
            // echo '<pre>';
            //print_r($exportData); 
            $key2 = 0;
            foreach ($_POST['exportData'] as $val) {


                $item_sku = trim($val['sku']);

                //print_r($exportData);
                $shelve_no = $shelve_noArr[$item_sku];
                $expity_date = $expdatesArr[$item_sku];
                $PalletsCheck = $this->ItemInventory_model->GetcheckvalidPalletNo($shelve_no);
                if ($PalletsCheck == true) {

                    $quantity = trim($val['piece']);
                    $file_name = date('Ymdhis') . '.xls';
                    $shipments = $this->Shipment_model->shipmetsInAwbAll_return(trim($val['slip_no']));
                    $data = $shipments['result'];
                    // print_r($shipments); 
                    // $slip_data=array();
                    $chargeQty = $val['piece'];
                    $totalPieces = $data[0]['pieces'];
                    $cust_id = $data[0]['cust_id'];
                    $seller_id = $cust_id;

                    if ($key2 == 0) {
                        $statusvalue[$key2]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $statusvalue[$key2]['user_type'] = 'fulfillment';
                        $statusvalue[$key2]['slip_no'] = $val['slip_no'];
                        $statusvalue[$key2]['new_status'] = 8;
                        $statusvalue[$key2]['code'] = 'RTC';
                        $statusvalue[$key2]['Activites'] = 'Order Return';
                        $statusvalue[$key2]['Details'] = 'Order Return By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                        $statusvalue[$key2]['entry_date'] = date('Y-m-d H:i:s');
                        $statusvalue[$key2]['deleted'] = 'Y';
                        $statusvalue[$key2]['super_id'] = $this->session->userdata('user_details')['super_id'];
                        /* -------------/Status Array----------- */


                        $slip_data[$key2]['slip_no'] = $val['slip_no'];
                        $slip_data[$key2]['code'] = 'RTC';
                        $slip_data[$key2]['delivered'] = '8';
                        //$slip_data[$key2]['deleted']='Y';
                        //$diamentionArr[$key2]['slip_no']=$val['slip_no'];       
                        //$diamentionArr[$key2]['deleted']='Y';
                        //===============return charge===========//
                        $ReturnChargeA = getalluserfinanceRates($seller_id, 18, 'rate');
                        $ReturnCharge = getalluserfinanceRates($seller_id, 17, 'rate');
                        $ReturnPieces = getalluserfinanceRates($seller_id, 17, 'setpiece');
                        if ($ReturnPieces >= $totalPieces)
                            $additionalreturnChargeQty = 1;
                        else {
                            $additionalreturnChargeQty = $totalPieces - $ReturnPieces;
                        }
                        $totalreturnCharge1 = $ReturnCharge;
                        $totalreturnCharge2 = $ReturnChargeA * $additionalreturnChargeQty;
                        $totalreturnCharge = $totalreturnCharge1 + $totalreturnCharge2;

                        $ReturnArrayCH[$key2]['seller_id'] = $seller_id;
                        $ReturnArrayCH[$key2]['slip_no'] = $val['slip_no'];
                        $ReturnArrayCH[$key2]['returncharge'] = $totalreturnCharge;
                        $ReturnArrayCH[$key2]['entrydate'] = date("Y-m-d H:i:sa");
                        $ReturnArrayCH[$key2]['pieces'] = $totalPieces;
                        $ReturnArrayCH[$key2]['super_id'] = $this->session->userdata('user_details')['super_id'];
                        //======================================//
                    }


                    $SkuID = getalldataitemtablesSKU($item_sku, 'id');
                    $wh_id = getalldataitemtablesSKU($item_sku, 'wh_id');
                    $sku_size = getalldataitemtablesSKU($item_sku, 'sku_size');
                    $item_type = getalldataitemtablesSKU($item_sku, 'type');
                    $first_out = getallsellerdatabyID($seller_id, 'first_out');
                    $qty = $chargeQty;
                    if (empty($expity_date))
                        $expdate = "0000-00-00";
                    else
                        $expdate = date('Y-m-d', strtotime($expity_date));

                    if ($first_out == 'N') {



                        $dataNew = $this->ItemInventory_model->find(array('item_sku' => $SkuID, 'expity_date' => $expdate, 'seller_id' => $seller_id, 'itype' => $item_type));

                        if ($status_type == '') {
                            foreach ($dataNew as $val2) {
                                if ($val2->quantity < $sku_size) {

                                    //echo '<br> 2//'.$qty.'//'. $val->quantity.'//';
                                    $check = $qty + $val2->quantity;
                                    $shelve_no = $val->shelve_no;
                                    if (empty($shelve_no)) {
                                        $shelve_no = "";
                                    }
                                    if ($check <= $sku_size) {

                                        $lastQtyUp = GetuserToatalLOcationQty($val2->id, 'quantity');
                                        $stock_location_upHistory = GetuserToatalLOcationQty($val2->id, 'stock_location');
                                        $lastQtyUp_up = $lastQtyUp;
                                        $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $SkuID, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $seller_id, 'qty' => $check, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'return', 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $val['slip_no'], 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);
                                        // print_r($activitiesArr);
                                        GetAddInventoryActivities($activitiesArr);
                                        $this->ItemInventory_model->updateInventory(array('quantity' => $check, 'id' => $val2->id));
                                        //echo $this->db->last_query();
                                        $qty = 0;
                                    } else {

                                        $diff = $sku_size - $val2->quantity;
                                        $lastQtyUp = GetuserToatalLOcationQty($val2->id, 'quantity');
                                        $stock_location_upHistory = GetuserToatalLOcationQty($val2->id, 'stock_location');
                                        $lastQtyUp_up = $lastQtyUp;
                                        $activitiesArr = array('exp_date' => $expdate, 'st_location' => $stock_location_upHistory, 'item_sku' => $SkuID, 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $seller_id, 'qty' => $sku_size, 'p_qty' => $lastQtyUp, 'qty_used' => $qty, 'type' => 'return', 'entrydate' => date("Y-m-d h:i:s"), 'awb_no' => $val['slip_no'], 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => $shelve_no);
                                        //print_r($activitiesArr);

                                        GetAddInventoryActivities($activitiesArr);
                                        $this->ItemInventory_model->updateInventory(array('quantity' => $sku_size, 'id' => $val2->id));
                                        //echo $this->db->last_query();
                                        $qty = $qty - $diff;
                                    }
                                }

                                // echo $val['item_sku'];  
                            }
                        }

                        //echo $qty; 
                    }

                    if ($qty > 0) {
                        if ($sku_size >= $qty)
                            $locationLimit = 1;
                        else {
                            $locationLimit1 = $qty / $sku_size;
                            $locationLimit = ceil($locationLimit1);
                        }

                        $updateaty = $qty;
                        $AddQty = 0;
                        for ($ii = 0; $ii < $locationLimit; $ii++) {
                            if ($sku_size <= $updateaty) {
                                $AddQty = $sku_size;
                                $updateaty = $updateaty - $sku_size;
                            } else {
                                $AddQty = $updateaty;
                                $updateaty = $updateaty;
                            }
                            // $stocklocation=$_POST['location_st'];
                            $addinventory[] = array(
                                'itype' => $item_type,
                                'item_sku' => $SkuID,
                                'seller_id' => $seller_id,
                                'quantity' => $AddQty,
                                'update_date' => date("Y/m/d h:i:sa"),
                                'stock_location' => $StockArray[$key2],
                                'expity_date' => $expdate,
                                'awb_no' => $val['slip_no'],
                                'status_type' => $status_type,
                                'shelve_no' => $shelve_no,
                                'wh_id' => $wh_id,
                                'super_id' => $this->session->userdata('user_details')['super_id']
                            );
                        }
                    }


                    $key2++;
                } else
                    $return = array('shelvevalid');
            }

            // print_r($addinventory); die;
            // echo "ssssss".$status_type; die;



            if ($this->Status_model->insertStatus($statusvalue)) {
                $this->Shipment_model->updateStatusBatch($slip_data);

                $this->Pickup_model->GetalloutboundDataAdded($ReturnArrayCH);

                if ($status_type == 'Damage' || $status_type == 'Mising')
                    $this->ItemInventory_model->add_damage($addinventory, 'return');
                else
                    $this->ItemInventory_model->add($addinventory, 'return');

                echo json_encode($this->exportExcel($_POST['exportData'], $file_name));
            }
        } else
            $return = array('error');
        // }
        // else
        echo json_encode($return);
    }

    public function GetcancelOrderFinal() {

        $postData = json_decode(file_get_contents('php://input'), true);
        // echo "<br><pre>"; 
        // print_r($postData);
        // echo "<br><pre>"; 
        // print_r($postData[0]['slip_no']);
        $shipmentArr = $postData['shipData'];
        $shipmentChrg = $postData['postdata']['charge_type'];
        $shipupdateAray = array();
        $statusvalue = array();
        $wbh_array = array();
        foreach ($shipmentArr as $key => $val) {
            if ($val['code'] == 'OG') {

                //======================status update====================//

                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $val['slip_no'];
                $statusvalue[$key]['new_status'] = 9;
                $statusvalue[$key]['deleted'] = 'N';
                $statusvalue[$key]['code'] = 'C';
                $statusvalue[$key]['Activites'] = 'Order Canceled';
                $statusvalue[$key]['Details'] = 'Order Canceled';
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                // $statusvalue[$key]['close_date'] = date('Y-m-d');
                //=========shipment update===================//

                $shipupdateAray[$key]['code'] = 'C';
                // $shipupdateAray[$key]['deleted'] = 'Y';
                $shipupdateAray[$key]['delivered'] = 9;
                $shipupdateAray[$key]['cancel_fee'] = $shipmentChrg;
                $shipupdateAray[$key]['slip_no'] = $val['slip_no'];
                $shipupdateAray[$key]['close_date'] = date('Y-m-d');

                $WB_Confing = webhook_settingsTable($val['cust_id']);
                if ($WB_Confing['subscribe'] == 'Y') {
                    $wb_request = array(
                        'datetime' => date('Y-m-d H:i:s'),
                        "code" => 'C',
                        "status" => 'Order Canceled',
                        "cc_name" => GetCourCompanynameId($val['frwd_company_id'], 'company'),
                        "cc_awb" => $val['frwd_company_awb'],
                        "cc_status" => null,
                        "cc_status_details" => null,
                        "slip_no" => $val['slip_no'],
                        "booking_id" => $val['booking_id'],
                        "cust_id" => $val['cust_id'],
                        "WB_Confing" => $WB_Confing
                    );
                    array_push($wbh_array, $wb_request);
                }
            }
        }

        // echo "<br/><pre>"; print_r($statusvalue);
        // echo "<br/><pre>"; print_r($shipupdateAray);
        // die;
        if (!empty($this->Status_model->insertStatus($statusvalue))) {

            $this->Shipment_model->updateStatusBatch($shipupdateAray);

            if (!empty($wbh_array)) {
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }
            $error_status = array('status' => true);

            echo json_encode($error_status);
        }//endif 
        else {
            $error_status = array('status' => false);
            echo json_encode($error_status);
        }


        /* $shipmentArr=$postData['shipData'];
          $chargetype=$postData['postdata']['charge_type'];
          $CheckOtherLocation=array();
          foreach($shipmentArr as $key=>$val)
          {


          if($val['code']=='PK')
          {

          //===================remove charge================//
          if($chargetype=='WF')
          {
          $removeExtraCharges[$key]['slip_no'] = $val['slip_no'];
          $removeExtraCharges[$key]['packaging_charge'] = 0;
          $removeExtraCharges[$key]['picking_charge'] = 0;
          $removeExtraCharges[$key]['special_packing_charge'] = 0;
          $removeExtraCharges[$key]['box_charge'] = 0;
          }

          }



          //=========shipment update===================//

          $shipupdateAray[$key]['code'] = 'C';
          $shipupdateAray[$key]['deleted'] = 'Y';
          $shipupdateAray[$key]['delivered'] =9;
          $shipupdateAray[$key]['slip_no'] = $val['slip_no'];
          //======================status update====================//

          $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
          $statusvalue[$key]['user_type'] = 'fulfillment';
          $statusvalue[$key]['slip_no'] = $val['slip_no'];
          $statusvalue[$key]['new_status'] =9;
          $statusvalue[$key]['deleted'] = 'Y';
          $statusvalue[$key]['code'] = 'C';
          $statusvalue[$key]['Activites'] = 'Order Canceled';
          $statusvalue[$key]['Details'] = 'Order Canceled';
          $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
          $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

          //===============stock history====================//
          //  $inventoryHistory[$key]['']

          //=============inventory add===================//

          $SkuArrData= $this->Shipment_model->diamention_fmDeleteProcessQry($val['slip_no']);
          // print_r($SkuArrData);
          $expdate = "0000-00-00";


          foreach($SkuArrData as $newkey=>$skudata)
          {
          $qty=$skudata['piece'];
          $sku_size=$skudata['sku_size'];
          $item_type=$skudata['type'];
          $SkuID=$skudata['id'];
          $wh_id=$skudata['wh_id'];




          if ($qty > 0) {
          if ($sku_size >= $qty)
          {
          $locationLimit = 1;
          }
          else {
          $locationLimit1 = $qty / $sku_size;
          $locationLimit = ceil($locationLimit1);
          }
          $StockArray = $this->ItemInventory_model->Getallstocklocationdata($val['cust_id'], $locationLimit, $CheckOtherLocation);

          // print_r($StockArray);

          $updateaty = $qty;
          $AddQty = 0;
          for ($ii = 0; $ii < $locationLimit; $ii++) {
          if ($sku_size <= $updateaty) {
          $AddQty = $sku_size;
          $updateaty = $updateaty - $sku_size;
          } else {
          $AddQty = $updateaty;
          $updateaty = $updateaty;
          }
          // $stocklocation=$_POST['location_st'];
          $addinventory[] = array(
          'itype' => $item_type,
          'item_sku' => $SkuID,
          'seller_id' => $val['cust_id'],
          'quantity' => $AddQty,
          'update_date' => date("Y/m/d h:i:sa"),
          'stock_location' => $StockArray[$ii]->stock_location,
          'expity_date' => $expdate,
          'awb_no' => $val['slip_no'],
          'status_type' => 'delete',
          'wh_id' => $wh_id,
          'super_id' => $this->session->userdata('user_details')['super_id']
          );
          array_push($CheckOtherLocation, $StockArray[$ii]->stock_location);
          }
          }
          }
          // echo '<pre>';
          // // print_r($addinventory);
          //  die;



          if (!empty($shipupdateAray)) {
          $request = $this->Shipment_model->updateStatusBatch($shipupdateAray);
          $this->Shipment_model->DeleteUpdateShipmentData($statusvalue);
          if($dataAray['code']=='PG' || $dataAray['code']=='AP' || $dataAray['code']=='PK')
          {
          $this->Shipment_model->DeletePickListProcess($dataAray['slip_no']);
          }
          if(!empty($removeExtraCharges))
          {
          $this->Shipment_model->RemmoveChargesDeleteOrder($removeExtraCharges);
          }
          if(!empty($addinventory))
          {
          $this->ItemInventory_model->add($addinventory, 'delete');
          }
          }

          } */
    }

    public function pickupList() {
        //echo $this->session->userdata('user_details')['user_type']; die;
        if (menuIdExitsInPrivilageArray(14) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('pickup/pickupList');
    }

    public function dispatch() {
        if (menuIdExitsInPrivilageArray(10) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/dispatch');
    }

    public function dispatch_b2b() {
        if (menuIdExitsInPrivilageArray(10) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/dispatch_b2b');
    }

    public function returnLM() {
        if (menuIdExitsInPrivilageArray(78) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $data['seller_drp'] = $this->Pickup_model->fetch_all_seller();
        $this->load->view('pickup/returnLM', $data);
    }

    public function CancelOrderView() {
        if (menuIdExitsInPrivilageArray(142) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $data['seller_drp'] = $this->Pickup_model->fetch_all_seller();
        $this->load->view('pickup/cancel_order', $data);
    }

    public function packing() {
        if (menuIdExitsInPrivilageArray(9) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/packing');
    }

    public function packing_ean() {
        if (menuIdExitsInPrivilageArray(230) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/packing_ean');
    }
    public function packing_CPS() {
        if (menuIdExitsInPrivilageArray(230) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/packing_CPS');
    }

    public function packing_new() {
        if (menuIdExitsInPrivilageArray(9) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/packing_new');
    }

    public function packing_tod() {
        if (menuIdExitsInPrivilageArray(9) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/packing_tod');
    }

    public function pickListView($pickUpId = NULL) {

        if (menuIdExitsInPrivilageArray(14) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $data['pickupId'] = $pickUpId;
        //echo $pickUpId;
        $this->load->view('pickup/pickListView', $data);
    }

    public function printawbTrack($pickUpId = NULL, $type = 'FS') {


        $this->load->library('M_pdf');
        $shipment = $this->Pickup_model->pickListFilterAll_awb_track($pickUpId);

        $datap['shipment'] = $shipment;
        $datap['sku_per_shipment'] = $sku_per_shipment;

        $html = $this->load->view('PrintAWB46', $datap, true);

        //echo $html; die;

        if ($type == 'FS') {
            $mpdf = new mPDF('utf-8', array(101, 152), 0, '', 0, 0, 0, 0, 0, 0);
        } else {
            $mpdf = new mPDF('utf-8', 'A4');
        }
        //$mpdf = new mPDF('utf-8','A4');
        $mpdf->WriteHTML($html);
        //$mpdf->SetDisplayMode('fullpage'); 
        //$mpdf->Output();
        $mpdf->Output('awb_print.pdf', 'I');

        die;
    }

    public function awbPickupPrint($pickUpId = NULL, $type = null) {


        $this->load->library('M_pdf');
        $shipment = $this->Pickup_model->pickListFilterAll_awb($pickUpId);

        $datap['shipment'] = $shipment;
        $datap['sku_per_shipment'] = $sku_per_shipment;

        $html = $this->load->view('PrintAWB46', $datap, true);

        //echo $html; die;

        if ($type == 'FS') {
            $mpdf = new mPDF('utf-8', array(101, 152), 0, '', 0, 0, 0, 0, 0, 0);
        } else {
            $mpdf = new mPDF('utf-8', 'A4');
        }
        //$mpdf = new mPDF('utf-8','A4');
        $mpdf->WriteHTML($html);
        //$mpdf->SetDisplayMode('fullpage'); 
        //$mpdf->Output();
        $mpdf->Output('awb_print.pdf', 'I');

        die;

        $this->load->helper('pdf_helper');
        $this->load->library('pagination');
        $this->load->model('Pdf_export_model');
        $data['pickupId'] = $pickUpId;
        $shipment = $this->Pickup_model->pickListFilterShip($pickUpId);
        // print_r($shipment); exit;
        $shipment = json_decode(json_encode($shipment));
        for ($i = 0; $i < count($shipment); $i++) {
            $sku_per_shipment[$i] = $this->Pdf_export_model->find_by_slip_no_for_sku($shipment[$i]->slip_no);
        }

        tcpdf();
        $custom_layout = array('101.6', '152.4');
        $obj_pdf = new TCPDF('P', PDF_UNIT, $custom_layout, true, 'UTF-8', false);
        ob_start();

        if (!empty($shipment)) {
            for ($i = 0; $i < count($shipment); $i++) {


                $obj_pdf->SetCreator(PDF_CREATOR);
                //$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                //$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
                $obj_pdf->SetDefaultMonospacedFont('helvetica');
                //$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                //$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
                $obj_pdf->setPrintHeader(false);
                $obj_pdf->setPrintFooter(false);
                $obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

                $obj_pdf->SetFont('helvetica', '', 9);

                $obj_pdf->setFontSubsetting(false);

                $obj_pdf->AddPage();
                $obj_pdf->Rect('1', '1', '100', '130');
                $obj_pdf->Rect('1.5', '1.5', '99', '43');
                ///////Column 1///////////////////////
                $obj_pdf->Rect('1.5', '1.5', '33', '20');
                $obj_pdf->Rect('1.5', '21.5', '49.5', '23');
                ////////Column 2////////////////////
                $obj_pdf->Rect('34.5', '1.5', '33', '20');

                ////////Column 3////////////////////
                $obj_pdf->Rect('67.5', '1.5', '33', '20');
                $obj_pdf->Rect('51', '21.5', '49.5', '23');

                /////////AWB Bar Code//////////
                $obj_pdf->Rect('1.5', '52.5', '99', '20');
                ///////////AWB No//////////////////////
                $obj_pdf->Rect('1.5', '72.5', '99', '6');
                /////////Acount No AND DATE//////////////////////
                $obj_pdf->Rect('1.5', '78.5', '49.5', '8');
                $obj_pdf->Rect('51', '78.5', '49.5', '8');
                /////////Weight AND Pieces/////////////////////
                $obj_pdf->Rect('1.5', '86.5', '49.5', '8');
                $obj_pdf->Rect('51', '86.5', '49.5', '8');

                //////////REFRENCE BAR CODE///////////////
                $obj_pdf->Rect('1.5', '94.5', '99', '14');
                //////////Reference Number//////////////
                $obj_pdf->Rect('1.5', '108.5', '99', '8');
                ///////////////Code Value/////////////////
                $obj_pdf->Rect('1.5', '44.5', '99', '8');
                // /////////Description ////////////////
                // $obj_pdf->Rect('1.5','134.5','99','6');
                // ob_start();	
                ///**********Working For Image*******///


                $image_file = file_get_contents(SUPERPATH . Getsite_configData_field('logo'));
                $obj_pdf->Image('@' . $image_file, 3, 2, 30, 18);

                // $content = ob_get_contents();
                // ob_end_clean();

                $style['position'] = 'C';
/////////////////////////here QR Code No 2d ////////////////
                $obj_pdf->write2DBarcode($shipment[$i]->slip_no, 'QRCODE,H', 70.5, 4, 33, 20, $style, 'N');
//////////////////////here Pass AWB NO too////////////////////////////////////////
                $obj_pdf->write1DBarcode($shipment[$i]->slip_no, 'C128', 3.5, 54.5, 62, 16, 0.7, $style, 'N');
///////////////////////here Pass Reference No ////////////////////////////////
                $obj_pdf->write1DBarcode($shipment[$i]->booking_id, 'C128', 3.5, 95.5, 62, 12, 0.7, $style, 'N');
//$obj_pdf->SetFont('aealarabiya','',9);
//////////////////////here////////////////////////////////////////
                $obj_pdf->SetTitle($shipment[$i]->slip_no);

                $obj_pdf->Text(2, 22, 'From: ');
                $obj_pdf->Text(2, 28, 'Mobile: ');
                $obj_pdf->Text(2, 31, 'Address: ');
                $obj_pdf->Text(2, 80, 'Account No: ');
                $obj_pdf->Text(2, 89, 'Weight: ');

                $obj_pdf->Text(52, 80, 'Booking Date: ');
                $obj_pdf->Text(37, 47, 'COD: ');
                $obj_pdf->Text(52, 89, 'Pieces: ');
                $obj_pdf->Text(52, 22, 'To: ');
                $obj_pdf->Text(52, 28, 'Mobile: ');
                $obj_pdf->Text(52, 31, 'Address: ');
                $obj_pdf->Text(2, 120, 'Description: ');
                $number++;
                $obj_pdf->Text(37, 73.5, $shipment[$i]->slip_no);
                //$obj_pdf->Text(64,73.5,$number.'/'.$shipment[$i]->pieces);

                $obj_pdf->Text(37, 110.5, $shipment[$i]->booking_id);

//////////////////////here////////////////////////////////////////
                $obj_pdf->SetFont('helvetica', '', 7);
                $dcode = getdestinationfieldshow($shipment[$i]->destination, 'city_code');
                $obj_pdf->SetFont('aealarabiya', 'B', 20);
                $obj_pdf->Text(75, 10, $dcode);
                $obj_pdf->SetFont('aealarabiya', '', 7);
                $obj_pdf->Text(12, 22.6, $shipment[$i]->sender_name);

                $obj_pdf->Text(14, 28.5, $shipment[$i]->sender_phone);
                $obj_pdf->MultiCell(42, 10, $shipment[$i]->sender_address . ', ' . $data['city_code1'], 0, 'L', false, 2, 3, 35, '', true);

                $obj_pdf->Text(15, 89.5, $shipment[$i]->weight . ' (KG)');
                if ($shipment[$i]->mode == 'COD')
                    $codamt = $shipment[$i]->total_cod_amt;
                else
                    $codamt = 0;
                $obj_pdf->Text(46, 47.5, $codamt . ' (SR)');
                $obj_pdf->Text(73, 80.7, $shipment[$i]->entrydate);
                $obj_pdf->Text(20, 80.7, $data['account_no']);
                $obj_pdf->Text(58, 22.6, $shipment[$i]->reciever_name);

                $obj_pdf->Text(64, 28.5, $shipment[$i]->reciever_phone);
                $obj_pdf->MultiCell(42, 10, $shipment[$i]->reciever_address . ', ' . $data['city_code2'], 0, 'L', false, 2, 53, 35, '', true);

                $obj_pdf->Text(65, 89.5, $shipment[$i]->pieces . ' (PCS)');
                $obj_pdf->Text(20, 120.7, $shipment[$i]->status_describtion);
            }
            $content = ob_get_contents();
            ob_end_clean();
            $obj_pdf->writeHTML($content, true, false, true, false, '');
            $obj_pdf->Output(Date('d-M') . '_Shipments-Report.pdf', 'I');
        }

        $content = ob_get_contents();
        ob_end_clean();
        $obj_pdf->writeHTML($content, true, false, true, false, '');
        $obj_pdf->Output(Date('d-M') . '_Shipments-Report.pdf', 'I');
    }

    public function pickListPrint($pickUpId = NULL) {
        if (menuIdExitsInPrivilageArray(14) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //echo "sssssss"; die;
        $this->load->model('Pdf_export_model');
        $this->load->library('M_pdf');
        $shipment = $this->Pickup_model->pickListFilterAll($pickUpId);

        //print_r($shipment); die();

        for ($i = 0; $i < count($shipment); $i++) {
            $sku_per_shipment[$i] = json_decode($shipment[$i]['sku']); //$this->Pdf_export_model->find_by_slip_no_for_sku($shipment[$i]['slip_no']);
        }
        // echo '<pre>';
        // print_r($sku_per_shipment); die();
        $datap['shipment'] = $shipment;
        $datap['sku_per_shipment'] = $sku_per_shipment;

        $html = $this->load->view('picklistPrint', $datap, true);

        // echo $html; die;


        $mpdf = new mPDF('utf-8', array(101, 152), 0, '', 0, 0, 0, 0, 0, 0);
        //$mpdf = new mPDF('utf-8','A4');
        $mpdf->WriteHTML($html);
        //$mpdf->autoPageBreak = false;
        //$mpdf->SetDisplayMode('fullpage'); 
        //$mpdf->Output();
        $mpdf->Output('picklist_print.pdf', 'I');
        die;
    }

    public function pickListPrintA4($pickUpId = NULL) {
        if (menuIdExitsInPrivilageArray(14) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        //echo "sssssss"; die;
        $this->load->model('Pdf_export_model');
        $this->load->library('M_pdf');
        $shipment = $this->Pickup_model->pickListFilterAll($pickUpId);

        for ($i = 0; $i < count($shipment); $i++) {
            $sku_per_shipment[$i] = $this->Pdf_export_model->find_by_slip_no_for_sku($shipment[$i]['slip_no']);
        }
        $datap['shipment'] = $shipment;
        $datap['sku_per_shipment'] = $sku_per_shipment;

        $html = $this->load->view('picklistPrint', $datap, true);

        $mpdf = new mPDF('utf-8', 'A4');
        //$mpdf = new mPDF('utf-8','A4');
        $mpdf->WriteHTML($html);
        //$mpdf->SetDisplayMode('fullpage'); 
        //$mpdf->Output();
        $mpdf->Output('picklist_print.pdf', 'I');
        die;
    }

    public function picklistremove($pickupId = null) {
        if (!empty($pickupId)) {
            $param['update']['deleted'] = 'Y';
            $param['where']['pickupId'] = $pickupId;
            $this->Pickup_model->assignPicker($param);
            $this->session->set_flashdata('msg', 'successfully remove!');
        } else
            $this->session->set_flashdata('msg', 'try again');
        redirect(base_url() . 'pickupList');
    }

    public function pickListFilter() {
        // print("heelo");
        // exit();
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $pickupId = $_POST['pickupId'];

        //echo json_encode($_POST);
        // print($exact);
        // print($awb);
        // print($sku);
        // print($from);
        // print($to);
        // print($delivered);
        // print($seller);
        // exit();
        $picker = $this->User_model->userDropval(4);
        $shipments = $this->Pickup_model->pickListFilter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $pickupId, $_POST);

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $shipments['result'];
        $ii = 0;
        foreach ($shipments['result'] as $rdata) {
            $shiparray[$ii]['booking_id'] = GetshpmentDataByawb($rdata['slip_no'], 'booking_id');
            $shiparray[$ii]['pickup_print'] = barcodeRuntime($rdata['pickupId']);

            $cc_id = GetshpmentDataByawb($rdata['slip_no'], 'frwd_company_id');
            $shiparray[$ii]['frwd_company'] = GetCCompanyNameById($cc_id,'company');

            $offer_data = $this->Pickup_model->Getorderpromocode($rdata['slip_no']);
            if (!empty($offer_data)) {
                $offer = "Yes";
                $pcode = $offer_data;
            } else {
                $offer = "No";
                $pcode = "N/A";
            }
            //$shiparray[$ii]['expire_details']=json_decode($shiparray[$ii]['exp_details'],true);
            $shiparray[$ii]['offer'] = $offer;
            $shiparray[$ii]['pcode'] = $pcode;
            $shiparray[$ii]['sku'] = json_decode($shiparray[$ii]['sku'], true);
            $shiparray[$ii]['packedBy'] = getUserNameById($rdata['packedBy']);
            $shiparray[$ii]['assigned_to'] = getUserNameById($rdata['assigned_to']);
            if ($rdata['pickup_status'] == 'Y')
                $shiparray[$ii]['pickup_status'] = 'Yes';
            else
                $shiparray[$ii]['pickup_status'] = 'No';
            if ($rdata['picked_status'] == 'Y')
                $shiparray[$ii]['picked_status'] = 'Yes';
            else
                $shiparray[$ii]['picked_status'] = 'No';


            $shiparray[$ii]['deducted_shelve_no'] = $this->Pickup_model->get_deducted_shelve_no($rdata['slip_no']);
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $ii++;
        }

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['picker'] = $picker;

        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function filter() {
        // print("heelo");
        // exit();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
        $this->load->model('User_model');
        $picker = $this->User_model->userDropval(4);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $slip_no = $_POST['slip_no'];
        //echo json_encode($_POST);
        // print($exact);
        // print($awb);
        // print($sku);
        // print($from);
        // print($to);
        // print($delivered);
        // print($seller);
        // exit();

        $shipments = $this->Pickup_model->filter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $slip_no, $_POST);

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $shipments['result'];
        $ii = 0;
        $jj = 0;

        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        $SiteConfingData = Getsite_configData();
        $e_city_ids = explode(',', $SiteConfingData['e_city']);
        foreach ($shipments['result'] as $rdata) {

            $forwardedArr = GetcheckSlipNo3plButton($rdata['pickupId']);
            $e_city = Get_e_citySlipCheck($rdata['pickupId'], $e_city_ids);
            if ($e_city == true) {
                $E_city_button = 'Y';
            } else {
                $E_city_button = 'N';
            }
            // print_r($forwardedArr);
            $pack_button = GetCheckPackingOrderBtn($rdata['pickupId']);

            $shiparray[$ii]['pack_button'] = $pack_button;

            $shiparray[$ii]['E_city_button'] = $E_city_button;
            $shiparray[$ii]['forwardedArr'] = $forwardedArr;
            $shiparray[$ii]['packedcount'] = packedcount($rdata['pickupId']);
            $shiparray[$ii]['unpackedcount'] = unpackedcount($rdata['pickupId']);
            $shiparray[$ii]['pickup_print'] = barcodeRuntime($rdata['pickupId']);
            if ($rdata['assigned_to'] > 0)
                $shiparray[$ii]['assigned_to'] = getUserNameById($rdata['assigned_to']);
            else
                $shiparray[$ii]['assigned_to'] = 'N/A';
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $ii++;
        }
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['picker'] = $picker;
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    function exportExcelpick() {
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exportlimit = $_POST['exportlimit'];
        //$delivered = $_POST['status'];


        $shipmentsexcel = $this->Pickup_model->filterexcel1($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        foreach ($shipmentsexcel['result'] as $rdata) {
            $shiparray1[$ii]['packedcount'] = packedcount($rdata['pickupId']);
            $shiparray1[$ii]['unpackedcount'] = unpackedcount($rdata['pickupId']);
            $shiparray1[$ii]['pickup_print'] = barcodeRuntime($rdata['pickupId']);
            if ($rdata['assigned_to'] > 0)
                $shiparray1[$ii]['assigned_to'] = getUserNameById($rdata['assigned_to']);
            else
                $shiparray1[$ii]['assigned_to'] = 'N/A';

            $ii++;
        }

        $dataArray['result'] = $shiparray1;
        echo json_encode($dataArray);
    }

    public function pickListViewExport() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataArray = $_POST;
        $slip_data = array();
        $file_name = date('Ymdhis') . '.xls';
        //echo json_encode($_POST);exit;
        $key = 0;
//     foreach($dataArray as $data)
//    { 
//     array_push( $slip_data ,$data['slip_no']);
//     $statusvalue[$key]['user_id']=$this->session->userdata('user_details')['user_id'];  
//     $statusvalue[$key]['user_type']='fulfillment';      
//     $statusvalue[$key]['slip_no']=$data['slip_no'];
//     $statusvalue[$key]['new_status']=4;
//     $statusvalue[$key]['code']='PK';
//     $statusvalue[$key]['Activites']='Order Packed';
//     $statusvalue[$key]['Details']='Order Packed By '. getUserNameById($this->session->userdata('user_details')['user_id']);
//     $statusvalue[$key]['entry_date']=date('Y-m-d H:i:s');
//    /*-------------/Status Array-----------*/  
//      $picklistValue[$key]['slip_no']=$data['slip_no'];
//     $picklistValue[$key]['packedBy']=$this->session->userdata('user_details')['user_id'];  
//     $picklistValue[$key]['packDate']=date('Y-m-d H:i:s');
//     $picklistValue[$key]['pickupDate']=date('Y-m-d H:i:s');
//     $picklistValue[$key]['pickup_status']='Y';
//     $picklistValue[$key]['packFile']=$file_name;     
//        
//    $key++;
//    } 

        echo json_encode($this->exportExcelpickList($dataArray, $file_name));
    }

    function assignPicker() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        // print_r($dataArray); die;
        $param['update']['assigned_to'] = $dataArray['selectedPicker'];
        $param['where']['pickupId'] = $dataArray['pickId'];
        $shipments = $this->Pickup_model->pickListFilter_assign($dataArray['pickId']);
        $slip_data = array();
        //echo json_encode($shipments);exit;
        $key = 0;
        $wbh_array = array();
        foreach ($shipments['result'] as $data) {

            array_push($slip_data, $data['slip_no']);
            $slipArr = GetshipmentRowsDetailsPage($data['slip_no']);
            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $data['slip_no'];
            $statusvalue[$key]['new_status'] = 3;
            $statusvalue[$key]['code'] = 'AP';
            $statusvalue[$key]['Activites'] = 'Picker Assigned';
            $statusvalue[$key]['Details'] = 'Pick List Assigned to ' . getUserNameById($dataArray['selectedPicker']);
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            /* -------------/Status Array----------- */

            $WB_Confing = webhook_settingsTable($slipArr['cust_id']);
            if ($WB_Confing['subscribe'] == 'Y') {
                $wb_request = array(
                    'datetime' => date('Y-m-d H:i'),
                    "code" => 'AP',
                    "status" => 'Picker Assigned',
                    "cc_name" => GetCourCompanynameId($slipArr['frwd_company_id'], 'company'),
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



            $key++;
        }

        $shipData = array();
        $updateArray = array(
            'code' => 'AP',
            'delivered' => 3
        );

        $shipData['where_in'] = $slip_data;
        $shipData['update'] = $updateArray;

        //print_r($statusvalue);

        
        if ($this->Pickup_model->assignPicker($param)) {
            //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
            if ($this->Status_model->insertStatus($statusvalue)) {
                $this->Shipment_model->updateStatus($shipData);
            }
            if (!empty($wbh_array)) {
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }
        }
    }

    function exportExcelpickList($dataEx, $file_name) {



        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {
            $dataArray[$i]['pickupId'] = $data['pickupId'];
            $dataArray[$i]['assigned_to'] = $data['assigned_to'];
            $dataArray[$i]['slip_no'] = $data['slip_no'];
            $dataArray[$i]['pickup_status'] = $data['pickup_status'];
            $dataArray[$i]['packedBy'] = $data['packedBy'];
            $dataArray[$i]['entrydate'] = $data['entrydate'];
            $dataArray[$i]['pickupDate'] = $data['pickupDate'];
            $dataArray[$i]['packDate'] = $data['packDate'];
            $i++;
        }

        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'pickupId')
                ->setCellValue('B1', 'assigned_to')
                ->setCellValue('C1', 'Order No.')
                ->setCellValue('D1', 'Pickup Status')
                ->setCellValue('E1', 'Packed By')
                ->setCellValue('F1', 'Create date')
                ->setCellValue('G1', 'PickDate')
                ->setCellValue('H1', 'Pack date');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

//die(json_encode($response));
    }

    function exportExcel($dataArray, $file_name) {



        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Order No.')
                ->setCellValue('B1', 'SKU')
                ->setCellValue('C1', 'Qty')
                ->setCellValue('D1', 'Scaned')
                ->setCellValue('E1', 'Extra');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

//die(json_encode($response));
    }

    function exportExcel_picklist() {

        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exportlimit = $_POST['exportlimit'];
        $shipmentsexcel = $this->Pickup_model->filterexcel1($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $dataArray = $shiparray1;
        //	print_r($dataArray); die;
        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {
            $DatafileArray[$i]['pickupId'] = $data['pickupId'];
            $DatafileArray[$i]['slip_no'] = $data['slip_no'];

            $DatafileArray[$i]['entrydate'] = $data['entrydate'];
            $DatafileArray[$i]['pickup_status'] = $data['pickup_status'];
            //$dataArray[$i]['packedBy']=$data['packedBy'];
            $DatafileArray[$i]['packedBy'] = getUserNameById($data['packedBy']);
            $DatafileArray[$i]['packDate'] = $data['packDate'];
            if ($data['assigned_to'] > 0)
                $DatafileArray[$i]['assigned_to'] = getUserNameById($data['assigned_to']);
            else
                $DatafileArray[$i]['assigned_to'] = 'N/A';
            $i++;
        }
        //  print_r($DatafileArray); die;
        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "G1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'PickUp ID')
                ->setCellValue('B1', 'Slip No.')
                ->setCellValue('C1', 'Date')
                ->setCellValue('D1', 'Picked Up')
                ->setCellValue('E1', 'Packed By')
                ->setCellValue('F1', 'Pack Date')
                ->setCellValue('G1', 'Assigned To');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));

//die(json_encode($response));
    }

    function generatePickup() {
        echo "sssss";
        die;
        $this->load->model('Pickup_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        $uid = uniqid();
        $picklistValue = array();
        $statusvalue = array();
        $key = 0;
        foreach ($dataArray['listData'] as $data) {
            /* -------------Picklist Array----------- */
            $picklistValue[$key]['pickupId'] = $uid;
            $picklistValue[$key]['slip_no'] = $data['slip_no'];
            $picklistValue[$key]['destination'] = $data['destination'];
            $picklistValue[$key]['origin'] = $data['origin'];
            $picklistValue[$key]['reciever_name'] = $data['reciever_name'];
            $picklistValue[$key]['reciever_address'] = $data['reciever_address'];
            $picklistValue[$key]['reciever_phone'] = $data['reciever_phone'];
            $picklistValue[$key]['sku'] = $data['skuData'];
            $picklistValue[$key]['piece'] = $data['piece'];
            if (!empty($data['frwd_company_label']))
                $picklistValue[$key]['print_url'] = $data['frwd_company_label'];
            else
                $picklistValue[$key]['print_url'] = base_url() . 'PrintPacking/' . $data['slip_no'];
            $picklistValue[$key]['entrydate'] = date('Y-m-d H:i');
            if (!empty($data['wh_ids']))
                $picklistValue[$key]['wh_id'] = $data['wh_ids'];
            else
                $picklistValue[$key]['wh_id'] = 0;

            /* -------------/Picklist Array----------- */
            //`user_id`, `user_type`, `slip_no`, `new_location`, `city_code`, `new_status`, `code`, `pickup_time`, `pickup_date`, `Activites`, `Details`, `comment`, `entry_date`,
            /* -------------Status Array----------- */
            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $data['slip_no'];
            $statusvalue[$key]['new_status'] = 2;
            $statusvalue[$key]['code'] = 'PG';
            $statusvalue[$key]['Activites'] = 'Pick List Generated';
            $statusvalue[$key]['Details'] = 'Pick List Generated';
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
            /* -------------/Status Array----------- */


            $key++;
        }

        $shipData = array();
        $updateArray = array(
            'code' => 'PG',
            'delivered' => 2
        );

        $shipData['where_in'] = $dataArray['slipData'];
        $shipData['update'] = $updateArray;
        // print_r($picklistValue);
        // die;
        if ($this->Pickup_model->generatePicup($picklistValue)) {
            if ($this->Status_model->insertStatus($statusvalue)) {
                echo $this->Shipment_model->updateStatus($shipData);
            }
        }
    }

    public function BulkPrintAllLabels_p() {


        $PrintAllAWB = $this->input->post('PrintAllAWB');
        $slipData = json_decode($PrintAllAWB, true);

        //echo $company ; die();
        //  print_r($slipData);die;

        $slipData = array_unique($slipData);
        if (!empty($slipData)) {

            // echo implode(',',$newslipArray);
            $this->awbPickupPrint_bulk($slipData);
        }
    }

    public function BulkPrintAllLabels() {


        $company = $this->input->post('print_ready');
        $show_awb_no = $this->input->post('show_awb_no');
        $company_id = $this->input->post($company);
        $a = trim(' ', $show_awb_no);
        // echo "testing";
        // echo  $show_awb_no ; die();
        if ($a != '') {
            if (strpos($a, PHP_EOL) !== '') {

                $slipData = explode(PHP_EOL, $show_awb_no);
            } elseif (strpos($a, ',') !== '') {

                $slipData = explode(",", $show_awb_no);
            }
        }
        $slipData = array_unique($slipData);
        if (!empty($slipData)) {

            $newslipArray = array();
            foreach ($slipData as $val) {
                if (trim($val) != '') {

                    //array_push($newslipArray, "'" . trim($val) . "'");
                    array_push($newslipArray, trim($val));
                }
            }
            if ($company == "Label A4") {
                // echo "ssssss"; die;
                $this->awbPickupPrint_bulk_new($newslipArray, 'A4');
                ///$this->awbPickupPrint_bulkA46($newslipArray);
            } else if ($company == "Label 4*6") {
                // echo implode(',',$newslipArray);
                $this->awbPickupPrint_bulk($newslipArray, 'A46');
            } else {
                // echo $company_id;
                //    echo "<br><pre>";
                // print_r($newslipArray);
                //  echo "other";
                //$this->Printpicklist3PL_bulk($newslipArray, $company_id);
                $returncondition = $this->Printpicklist3PL_bulk($newslipArray, $company_id);
                //  print_r($returncondition); 
                // die; 

                if (empty($returncondition)) {
                    // echo "Empty";
                    $this->session->set_flashdata('something', 'please enter AWB No.');
                    redirect(base_url() . 'bulkprint');
                } else {
                    //echo "Not Empty"; 
                    $this->Printpicklist3PL_bulk($newslipArray, $company_id);
                    //echo "Not empty "; exit; 
                }
            }
        } else {
            $this->session->set_flashdata('something', 'please enter AWB No.');
            redirect(base_url() . 'bulkprint');
        }
    }

    public function awbPickupPrint_bulkA46($pickUpId = array(), $page = null) {

        // print_r($pickUpId); die;
        $this->load->helper('pdf_helper');
        // $this->load->library('pagination');
        $this->load->library('M_pdf');

        $data['pickupId'] = $pickUpId;

        $status_update_data = $this->Pickup_model->pickListFilterShip_bulk($pickUpId);
        //  $status_update_data = $this->ShipmenetManagement_model->PrintawbFilterShip($pickUpId);
        //print_r($status_update_data); die(); 
        if (!empty($status_update_data)) {

            $html .= '
			<!doctype html>
				<html>
					<head>
						<meta charset="utf-8">';
            $html .= '<title>AWB print of ' . $awb . ' </title> ';

            $html .= '	<style>
						.invoice-box {
							max-width: 800px;
							margin: auto;
							padding: 10px;
							 
							font-size: 12px;
							line-height: 24px;
							font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
							color: #555;
							height:850px
						}
						
						.invoice-box table {
							width: 100%;
							line-height: inherit;
							text-align: left;
						}
						
						.invoice-box table td {
							padding: 5px;
							vertical-align: top;
						}
						
						.invoice-box table tr td:nth-child(2) {
							text-align: right;
						}
						
						.invoice-box table tr.top table td {
							padding-bottom: 10px;
						}
						
						.invoice-box table tr.top table td.title {
							font-size: 45px;
							line-height: 45px;
							color: #333;
						}
						table {
							font-family: arial, sans-serif;
							border-collapse: collapse;
							width: 100%;
						}

						td, th {
							
							text-align: left;
							padding: 0px;
						} 
						.lineheaig1 { line-height:1px;}
					 .cod_box{
								background:#CCC !important;
								border-radius:10px;
								padding:10px;
								text-align:center;
								-webkit-print-color-adjust: exact; 
								font-size:18px;

							} 
						</style>
					</head> 
					<body>';
            $tollfree_fm = site_configTable('tollfree_fm');
            foreach ($status_update_data as $key => $val) {


                $destination = getdestinationfieldshow($status_update_data[$key]['destination'], 'city');
                $origin = getdestinationfieldshow($status_update_data[$key]['origin'], 'city');
                $dcode = getCityCode($status_update_data[$key]['destination']);
                $oringincode = getCityCode($status_update_data[$key]['origin']);
                $address = getdestinationfieldshow('city', $status_update_data[$key]['destination']) . ',<br />' . getdestinationfieldshow('country', $status_update_data[$key]['origin']);
                $accountNo = Get_cust_uid($status_update_data[$key]['cust_id']);
                $limit = $status_update_data[$key]['pieces'];
                for ($i = 1; $i <= $limit; $i++) {



                    if ($status_update_data[$key]['weight'] > $status_update_data[$key]['volumetric_weight'])
                        $weight = $status_update_data[$key]['weight'];
                    else
                        $weight = $status_update_data[$key]['volumetric_weight'];

                    /* 	$listingQry1="select * from status where deleted='N' and slip_no='".$status_update_data[$key]['slip_no']."'";
                      $this->dbh_read->Query($listingQry1);
                      if($this->dbh_read->num_rows)
                      {
                      $status_update_data1=$this->dbh_read->FetchAllResults($listingQry1);
                      //$objSmarty->assign("status_update_data1", $status_update_data1);
                      } */
                    $barcode_image = '128barcode_image/' . $status_update_data[$key]['bar_code_img'];
                    $zip_barcode = '128barcode_image/' . $status_update_data[$key]['zip_code_image'];

                    $Total_amount = $status_update_data[$key]['cod_fees'] + $status_update_data[$key]['service_charge'] + $status_update_data[$key]['total_cod_amt'];
                    //$destination_city=$functions->Get_city_idd($status_update_data[$key]['country_city']);
                    $html .= '<div class="invoice-box">
							<table cellpadding="0" cellspacing="0" border="1">
								<tr class="top">
									<td colspan="18">
										<table border="1">
											<tr>
											<td colspan=""  align="center"> 
												  <img src="https://super.diggipacks.com/assets/clientlogo/1615581471.png"  style="height:17% !الاسم  الحالةant; width:100px !الاسم  الحالةant">  
												</td>
												<td  > 
												From <h1>' . $oringincode . '</h1>  
												</td> 
												
												<td > 
												To <h1>' . $dcode . '</h1>  
												</td>
												<td   >
										  
<img src="https://lm.diggipacks.com/application/third_party/qrcodegen.php?data=' . $status_update_data[$key]['slip_no'] . '"  style="height:17% !الاسم  الحالةant; width:17% !الاسم  الحالةant;" /> 
										</td>			
																						
											</tr>
											<tr  align="center">
									<td style="font-size:14px;" >
										 <strong> From</strong>  :  <br />
										' . $status_update_data[$key]['sender_name'] . '  <br />
										' . $status_update_data[$key]['sender_address'] . '
										 <br /><strong>Mobile</strong>: ' . $status_update_data[$key]['sender_phone'] . '
										 <br /><strong>City</strong>: ' . $origin . '
									</td>
									
									<td  colspan=3 style="font-size:14px;">
										 <strong>Pieces : </strong>
										' . $status_update_data[$key]['pieces'] . '
										 <br /><strong>Weight : </strong>: ' . $weight . '
										 <br /><strong>City</strong>: ' . $destination . '
										 <br /><strong>Total Weight</strong>: ' . $status_update_data[$key]['weight'] . '
										 <br /><strong>Sender </strong>: ' . $status_update_data[$key]['sender_name'] . '
									</td>
									
								</tr> 
										</table>
									</td>
								</tr> 
							</table>
                            <table cellpadding="0" cellspacing="0"  border="1">
                            <tr >
                                
                                        
                                        <td align="center">Toll free No :' . $tollfree_fm . ' </td>
                                    
                                
                            </tr>
                            </table>

							<table cellpadding="0" cellspacing="0" border="1">
							 
								<tr> 
									<td colspan="2"> 
										
										<h6 class="lineheaig1 ">TO</h6>
											' . $status_update_data[$key]['reciever_name'] . '<br />
											' . $status_update_data[$key]['reciever_address'] . '<br />
											' . $address . "<br />" . $status_update_data[$key]['reciever_zip'] . '
										</h2> 
									
										<P class="bold"> Mobile:' . $status_update_data[$key]['reciever_phone'] . ' </P></td>
										
										
										</tr>';
                    if ($status_update_data[$key]['total_cod_amt'] != '' && $status_update_data[$key]['total_cod_amt'] != '0') {
                        if ($status_update_data[$key]['client_type'] == 'B2C') {
                            $html .= '<tr> 
															<td class=" " colspan="2" align="center">
																<strong> COD </strong>: ' . $status_update_data[$key]['total_cod_amt'] . '
															</td>  
														</tr>';
                        } else {
                            $html .= '<tr>
															<td class=" "colspan="2" align="center">
															<div class="col-md-3">
													<div class="cod_box">	<strong> COD </strong>: ' . $Total_amount . ' ' . site_configTable("default_currency") . '</div></div>
															</td>  
														</tr>';
                        }
                    }
                    '</td>
									</tr>
								 ';
                    if ($status_update_data[$key]['booking_id'] != '')
                        $html .= '<br /><tr align="center">
									<td colspan="2"  style="font-size:14px; align:center" align="center">
										
										' . barcodeRuntime_new($status_update_data[$key]['booking_id']) . '
										</td>
										</tr>';

                    $html .= '<tr> 
										<td colspan="2" style="font-size:14px; align:center" align="center" >' . $status_update_data[$key]['booking_id'] . '
									</td></tr><tr   align="center">	<td colspan="2" align="center"> <div class="col-md-3">
													<div class="cod_box" style="text-align:center !import;">
														<p align="center" style="text-align:center;">
															Schedule Status : .';
                    if ($status_update_data[$key]['schedule_status'] == 'Y') {
                        $html .= $status_update_data[$key]['schedule_status'];
                    } else {
                        $html .= 'N/A';
                    }
                    $html .= '|| Time Slot :';
                    if ($status_update_data[$key]['time_slot'] != '') {
                        $html .= $status_update_data[$key]['time_slot'];
                    } else {
                        $html .= 'N/A';
                    }
                    $html .= '|| Area Street :';
                    if ($status_update_data[$key]['area_street'] != '') {
                        $html .= $status_update_data[$key]['area_street'];
                    } else {
                        $html .= 'N/A';
                    }
                    $html .= '</p>
													</div>
												</div>     
												</div>	</td>  </tr>';

                    $html .= ' 
							</table>							
							<table cellpadding="0" cellspacing="0" border="1">	 
							

								<tr> 
									<td class=" " style="font-size:14px;">
										<strong> Account number </strong>: ' . $accountNo . '
									</td>
							 
									<td class=" " style="font-size:14px;"> 
										<strong> Date </strong> : ' . date("d-m-Y H:i:s", strtotime($status_update_data[$key]['entrydate'])) . '
									</td>  
								</tr>
								<tr>
									<td class=" " style="font-size:14px;">
										<strong> Weight</strong> : ' . $weight . 'Kg' . '
									</td>  
							 
									<td class=" "style="font-size:14px;">
										<strong> Sku Qty </strong>: ' . $status_update_data[$key]['pieces'] . '
									</td>  
									
								</tr>
								<!--<tr>
									<td class=" " style="font-size:14px;">
										<strong> Reference number </strong>: ' . $status_update_data[$key]['booking_id'] . '
									</td> 
								</tr>-->  ';

                    $html .= '  
							
								 
									<tr> <td colspan="2"  style="font-size:14px; align:center" align="center" >
										  
										' . barcodeRuntime_new($status_update_data[$key]['slip_no']) . '</td>
											</tr> <tr> 
										<td colspan="2" style="font-size:14px; align:center" align="center" >' . $status_update_data[$key]['slip_no'] . '  ' . $i . '/' . $status_update_data[$key]['pieces'] . '
									</td>
									
								</tr>
								
								
								   <tr  style=""><td colspan="2" style="font-size:14px; height:200px;" >
										<strong> Description </strong>:' . $status_update_data[$key]['status_describtion'] . '
									</td></tr></table>
						</div> ';
                }
            }
            $html .= '
							
					</body>
				</html> ';
            // print_r($html); die;
            // $mpdf=new mPDF('utf-8', array(101,152)); 
            $mpdf = new mPDF('utf-8');
            $mpdf->WriteHTML($html);
            //$mpdf->SetDisplayMode('fullpage'); 
            //$mpdf->Output();
            $mpdf->Output('AWB_print.pdf', 'I');
        }
    }

    public function awbPickupPrint_bulk_new($awb = array(), $type = null) {
        $view['data'] = $awb;
        $view['type'] = $type;

        $this->load->view('bulk_print_labels', $view);
    }

    public function awbPickupPrint_bulk($awb = array(), $type = null) {
        //exit('here');
        //  error_reporting(E_ALL); 
        // ini_set('display_errors', true);
        // ini_set('display_startup_errors', true);
        $this->load->library('M_pdf');

        // print_r($_REQUEST['content']);
        if (!empty($_REQUEST['content'])) {
            $awb = explode(',', $_REQUEST['content']);
            $type = $_REQUEST['type'];
        }





        $shipment = $this->Pickup_model->pickListFilterShip_bulk($awb);
        $data['status_update_data'] = $shipment;
        $data['type'] = $type;
        //print_r($shipment); die;
        if (!empty($shipment)) {
            $body = $this->load->view('printAwblist', $data, true);
            // echo $body; die;
            ini_set('memory_limit', '-1');
            if ($type == "A4") {
                $mpdf = new mPDF('utf-8', 'A4-L');
            } else {
                $mpdf = new mPDF('utf-8', array(101, 152), 0, '', 0, 0, 0, 0, 0, 0);
            }

            $mpdf->WriteHTML($body);
            //$mpdf->SetDisplayMode('fullpage'); 
            //$mpdf->Output();
            if (!empty($_REQUEST['content'])) {
                //  header('Content-Disposition: attachment; filename="AWB_print.pdf"');
                // $mpdf->Output('AWB_print.pdf', 'I');
                $mpdf->Output();
            } else {
                $mpdf->Output('AWB_print.pdf', 'I');
            }
            //header('Content-Disposition: attachment; filename="AWB_print.pdf"');
            //
            //
            // $mpdf->Output();
            die;
        } else {
            $this->session->set_flashdata('something', 'please enter valid AWB No.');
            redirect(base_url() . 'bulkprint');
        }
    }

    public function GetlabelPrint4_6($awb = null) {
        $this->load->library('M_pdf');

        $sku_data = $this->Ccompany_model->Getskudetails_forward($awb);

        $total_weight = 0;
        foreach ($sku_data as $key => $val) {
            $total_weight += ($sku_data[$key]['weight'] * $sku_data[$key]['piece']);
        }
        if ($total_weight > 0) {
            $weight = $total_weight;
        } else {
            $weight = 1;
        }
        $shipment = $this->Pickup_model->pickListFilterShip_bulk($awb);
        //print "<pre>"; print_r($shipment);die;
        $data['status_update_data'] = $shipment;
        $data['status_update_data'][0]['weight'] = $weight;

        //print "<pre>"; print_r($data);die; 
        // $total_weight = 0; 
        // foreach ($sku_data as $key => $val) {
        //         $total_weight += ($sku_data[$key]['weight'] * $sku_data[$key]['piece']);
        // }
        //echo $total_weight;die;
        //print_r($shipment); die;
        if (!empty($shipment)) {
            $body = $this->load->view('printAwblist', $data, true);
            // echo $body; die;
            $mpdf = new mPDF('utf-8', array(101, 152), 0, '', 0, 0, 0, 0, 0, 0);
            $mpdf->WriteHTML($body);
            //$mpdf->SetDisplayMode('fullpage'); 
            //$mpdf->Output();
            $mpdf->Output('AWB_print.pdf', 'I');
            //header('Content-Disposition: attachment; filename="AWB_print.pdf"');
            // $mpdf->Output();
            die;
        } else {
            $this->session->set_flashdata('something', 'please enter valid AWB No.');
            redirect(base_url() . 'bulkprint');
        }
    }

    public function Printpicklist3PL_bulk($slip_no = array(), $frwd_company_id = null) {

        // print_r($frwd_company_id); exit;  
        if (!empty($slip_no)) {
            //   print_r($slip_no); exit;
            return PrintPiclist3PL_bulk($slip_no, $frwd_company_id);
        }
    }

    public function PickupSingleListView() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $picker = $this->User_model->userDropval(4);
        $resultArray = $this->Pickup_model->PickedListSingleviewDataQry($_POST);

        $newresultArray = $resultArray['result'];
        //$newresultArray = $resultArray;
        foreach ($newresultArray as $key => $val) {
            $SkuArray_new = json_decode($val['sku'], true);
            foreach ($SkuArray_new as $newkey => $rows) {
                //echo $rows['sku'];
                $SkuArray_new[$newkey]['item_path'] = getalldataitemtablesSKU($rows['sku'], 'item_path');
            }
            $detailsArr = json_decode($val['exp_details'], true);
            $newresultArray[$key]['skuDetails'] = $SkuArray_new;
            $newresultArray[$key]['location'] = $detailsArr[0]['shelve_no'];
            $newresultArray[$key]['picker'] = getUserNameById($val['assigned_to']);
        }
        $return['result'] = $newresultArray;
        $return['picker'] = $picker;
        $return['count'] = $resultArray['count'];
        echo json_encode($return);
    }

    public function PickupCheckSingle() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Pickup_model->pickListFilterNotPicked_single($_POST['slip_no'], $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination);
        $newArray = $shipments['result'];
        foreach ($newArray as $key => $val) {
            $newArray[$key]['booking_id'] = GetshpmentDataByawb($val['slip_no'], 'booking_id');
        }
        $return['result'] = $newArray;
        $return['count'] = $shipments['count'];
        echo json_encode($return);
    }

    public function PickedListBatchviewData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $picker = $this->User_model->userDropval(4);
        $resultArray = $this->Pickup_model->PickedListBatchviewDataQry($_POST);

        $newresultArray = $resultArray['result'];
        foreach ($newresultArray as $key => $val) {
            $SkuArray = json_decode($val['sku'], true);
            $detailsArr = json_decode($val['exp_details'], true);
            $newresultArray[$key]['packedcount'] = packedcount_batch($val['pickupId']);
            $newresultArray[$key]['unpackedcount'] = unpackedcount_batch($val['pickupId']);
            $newresultArray[$key]['skuDetails'] = $SkuArray;
            $newresultArray[$key]['location'] = $detailsArr[0]['shelve_no'];
            $newresultArray[$key]['picker'] = getUserNameById($val['assigned_to']);
        }
        $return['result'] = $newresultArray;
        $return['count'] = $resultArray['count'];
        $return['picker'] = $picker;
        echo json_encode($return);
    }

    public function PickedListData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $resultArray = $this->Pickup_model->PickedListDataQry($_POST);
        $newresultArray = $resultArray;
        //   echo '<pre>';
        $SkuArrayNew = array();
        $acheckArray = array();
        $ii = 0;
        $piece = 0;
        foreach ($newresultArray as $key => $val) {
            $SkuArray = json_decode($val['sku'], true);
            // print_r($SkuArray);

            $detailsArr = json_decode($val['exp_details'], true);
            //$counts = array_count_values(array_flip(array_column($SkuArray, 'piece')));
            //print_r($counts);

            foreach ($SkuArray as $key1 => $skurow) {
                $piece += $skurow['piece'];
                //$piece=$skurow['piece'];
                $booking_id = GetshpmentDataByawb($val['slip_no'], 'booking_id');
                $acheckArray[] = array('pickupId' => $val['pickupId'], 'slip_no' => $val['slip_no'], 'booking_id' => $booking_id, 'sku' => $skurow['sku'], 'ean_no' => $skurow['ean_no'], 'piece' => $skurow['piece'], 'location' => $detailsArr[0]['shelve_no'], 'picker' => getUserNameById($val['assigned_to']));
            }


            $ii++;
        }

        //print_r($acheckArray);
        $SingleArray = $this->unique_multidim_array($acheckArray, 'sku', 'piece');

        //   print_r($SingleArray);
        /// print_r($result);
        $return['result'] = $SingleArray;
        $return['tpiece'] = $piece;

        echo json_encode($return);
    }

    public function unique_multidim_array($array, $key, $key2) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        $key_array2 = array();
        $piece = 0;

        foreach ($array as $val) {

            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
                $temp_array[$i]['slip_details'][] = $val['booking_id'];
                $i++;
            } else {
                $lastkey = array_search($val[$key], $key_array);
                $temp_array[$lastkey]['slip_details'][] = $val['booking_id'];
                $temp_array[$lastkey]['piece'] = $temp_array[$lastkey]['piece'] + $val['piece'];
            }
        }
        return $temp_array;
    }

    public function ppickFinishSingle() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        
        
        $dataArray = $_POST;
        $slip_data = array();
        $Pickingcharge = array();
        $file_name = date('Ymdhis') . '.xls';
         $serial_detailsArr = array();
        $key = 0;
        foreach ($_POST['exportData'] as $reportrows) {
            $entrydate = date('Y-m-d H:i:s');
          
            $cust_id = GetshpmentDataByawb($reportrows['slip_no'], 'cust_id');
            
//            $serial_detailsData = array(
//                    'serial_no' => json_encode($reportrows['serial_details']),
//                    'slip_no' => $reportrows['slip_no'],
//                    'booking_id' => $reportrows['booking_id'],
//                    'sku' => $reportrows['ean_no'],
//                    'ean_no' => $reportrows['sku'],
//                    'piece' => $reportrows['piece'],
//                    'entry_date' => $entrydate,
//                    'cust_id' => $cust_id,
//                    'entry_date'=>$entrydate,
//                    'type'=>'PI',
//                    //'serial_no'=>$s_val,
//                    'super_id' => $this->session->userdata('user_details')['super_id'],
//                    'updated_by' => $this->session->userdata('user_details')['user_id'],
//                );
//                array_push($serial_detailsArr, $serial_detailsData);

        }
        foreach ($dataArray['shipData'] as $data) {
            $picklistValue[$key]['slip_no'] = $data['slip_no'];
            $picklistValue[$key]['pickedDate'] = date('Y-m-d H:i:s');
            $picklistValue[$key]['picked_status'] = 'Y';

            $key++;
        }


        
        //echo "<pre>";print_r($serial_detailsArr); die;
        if ($this->Pickup_model->packOrder($picklistValue)) {
//            if (!empty($serial_detailsArr)) {
//           $this->Pickup_model->packing_serialQuery($serial_detailsArr);
//        }
            // echo json_encode($this->exportExcel($_POST['exportData'], $file_name));
            echo json_encode($this->exportExcel_single($_POST['exportData'], $file_name));
        }
    }

    public function PickedBatchFinish() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        // echo json_encode($_POST);die;
        //echo "<pre>";print_r($_POST); die;
        $dataArray = $_POST;
        $slip_data = array();
        $Pickingcharge = array();
        $serial_detailsArr=array();
//        foreach ($_POST['exportData'] as $reportrows) {
//            $entrydate = date('Y-m-d H:i:s');
//          
//                $cust_id = GetshpmentDataByawb($reportrows['slip_no'], 'cust_id');
//             $booking_id = GetshpmentDataByawb($reportrows['slip_no'], 'booking_id');
//            
//            $serial_detailsData = array(
//                    'serial_no' => json_encode($reportrows['serial_details']),
//                    'slip_no' => $reportrows['slip_no'],
//                    'booking_id' => $booking_id,
//                    'sku' => $reportrows['ean_no'],
//                    'ean_no' => $reportrows['sku'],
//                    'piece' => $reportrows['piece'],
//                    'entry_date' => $entrydate,
//                    'cust_id' => $cust_id,
//                    'entry_date'=>$entrydate,
//                    'type'=>'PI',
//                    //'serial_no'=>$s_val,
//                    'super_id' => $this->session->userdata('user_details')['super_id'],
//                    'updated_by' => $this->session->userdata('user_details')['user_id'],
//                );
//                array_push($serial_detailsArr, $serial_detailsData);
//
//        }
        
        $file_name = date('Ymdhis') . '.xls';
        // echo json_encode($_POST['exportData']);exit;
        //$dataArray['shipData']=array('slip_no'=>'TAM7368768739');

        $picklistValue['pickedDate'] = date('Y-m-d H:i:s');
        $picklistValue['picked_status'] = 'Y';
        // print_r($picklistValue);


        if ($this->Pickup_model->PickedOrderbatch($picklistValue, $dataArray['pickupId'])) {
//            if (!empty($serial_detailsArr)) {
//           $this->Pickup_model->packing_serialQuery($serial_detailsArr);
//        }
            echo json_encode($this->exportExcel_batch($_POST['shipData'], $file_name));

            //}
        }
    }

    function exportExcel_batch($dataArray, $file_name) {




        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'pickup Id')
                ->setCellValue('B1', 'SKU')
                ->setCellValue('C1', 'Qty')
                ->setCellValue('D1', 'Scaned')
                ->setCellValue('E1', 'Location')
                ->setCellValue('F1', 'Picker');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

//die(json_encode($response));
    }

    function exportExcel_single($dataArray, $file_name) {

        //echo "<pre>";
        // print_r($dataArray);
        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Order No.')
                ->setCellValue('B1', 'Ref. No.')
                ->setCellValue('C1', 'EAN NO.')
                ->setCellValue('D1', 'SKU')
                ->setCellValue('E1', 'Qty')
                ->setCellValue('F1', 'Scaned')
                ->setCellValue('G1', 'Extra');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

//die(json_encode($response));
    }

    public function Printpicklist3PL($pickUpId = NULL, $frwd_company_id = null) {
        $pickUpId = $pickUpId;
        //$print_ready = $this->input->post('print_ready');

        if ($pickUpId != '' && $frwd_company_id != '') {
            //print_r($awbData); exit;
            PrintPiclist3PL($pickUpId, $frwd_company_id);
        }
    }

    public function GetCheck3PLDispatchData() {
        $postData = json_decode(file_get_contents('php://input'), true);

        $returnData = $this->Pickup_model->Get3pldispatchCheckData($postData);
        $newCreateArr = $returnData;
        foreach ($newCreateArr as $key => $val) {
            //=============shipment update==============//
            $updateSlip[$key]['code'] = 'D3PL';
            $updateSlip[$key]['delivered'] = '17';
            $updateSlip[$key]['slip_no'] = $val['slip_no'];
            //===============status update==================//
            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $val['slip_no'];
            $statusvalue[$key]['new_status'] = 17;
            $statusvalue[$key]['code'] = 'D3PL';
            $statusvalue[$key]['Activites'] = 'Dispatched to 3PL';
            $statusvalue[$key]['Details'] = 'Dispatched to 3PL By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            //==============================================//
            $newCreateArr[$key]['destination'] = getdestinationfieldshow($val['destination'], 'city');
            $newCreateArr[$key]['origin'] = getdestinationfieldshow($val['origin'], 'city');
            $newCreateArr[$key]['frwd_company_id'] = GetCourCompanynameId($val['frwd_company_id'], 'company');
            $newCreateArr[$key]['cust_name'] = getallsellerdatabyID($val['cust_id'], 'name');

            $seller_id = $val['cust_id'];
            $token = GetallCutomerBysellerId($seller_id, 'manager_token');
            if (!empty($token)) {
                $slip_no = $val['slip_no'];

                $zidStatus = "indelivery";

                if (!empty($data['frwd_company_awb'])) {
                    $trackingurl = makeTrackUrl($val['frwd_company_id'], $val['frwd_company_awb']);
                    $lable = $data['frwd_company_label'];
                } else {
                    $lable = 'https://api.diggipacks.com/API/print/' . $val['slip_no'];
                }
                $trackingurl = TRACKURL . $slip_no;
                //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                updateZidStatus($val['booking_id'], $token, $zidStatus, $slip_no, $lable, $trackingurl, $seller_id);
            }
        }
        if (!empty($updateSlip)) {
            $this->Pickup_model->Update3PLOrder($updateSlip, $statusvalue);
        }
        $returnArr['result'] = $newCreateArr;
        $returnArr['count'] = count($returnData);
        echo json_encode($returnArr);
    }

    public function pageshortDropData($maxval = 0) {
        //echo $maxval; die;

        $min = 100;
        $max = $maxval; // Just chenge this val;
        $s_val = array();
        if ($max <= 100) {
            $sval = array('100');
        } elseif ($max > 100 && $max <= 200) {
            $sval = array('0' => '100', '100' => 200);
        } elseif ($max > 200 && $max <= 500) {
            $sval = array('0' => 100, '100' => '200', '200' => '500');
        } elseif ($max > 500 && $max <= 1000) {
            $sval = array('0' => 100, '100' => '200', '200' => '500', '500' => 1000);
        } elseif ($max > 1000) {
            $repeat = round(($max - 1000) / 500);

            $l = 1000;
            $sval = array('0' => 100, '100' => '200', '200' => '500', '500' => 1000);
            for ($i = 1; $i <= $repeat; $i++) {
                $l = $l + 500;
                $sval[$l - 500] = $l;
            }
        }
        return $sval;
    }

    public function getSkuDetails() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $page_no = $_POST['page_no'];
        $slip_no = $_POST['slip_no'];
        $fromdate = $_POST['from_date'];
        $todate = $_POST['to_date'];

        if (!empty($_POST['limit']))
            $limit = $_POST['limit'] + 1;
        else
            $limit = 0;


        $otherfilter = array('fromdate' => $fromdate, 'todate' => $todate);
        $QueryData = $this->Pickup_model->GetallPackagingQuery($page_no, $slip_no, $otherfilter);
        $returnArray = $QueryData['result'];
        //print "<pre>"; print_r($QueryData);die;
        $tolalShip = $QueryData['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        $pageShortArr = $this->pageshortDropData($tolalShip);

        $ii = 0;
        foreach ($returnArray as $rdata) {
            $returnArray[$ii]['updated_by'] = getUserNameById($rdata['updated_by'], 'username');
            /// $returnArray[$ii]['quantityScan']=getallscanQunatitybyID($rdata['slip_no']);
            //$returnArray[$ii]['quantityRed']=getallskuQuantitybyID($rdata['slip_no']);
            $ii++;
        }
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $returnArray;
        $dataArray['count'] = $QueryData['count'];
        echo json_encode($dataArray);
    }

    public function StaffpickingReport() {

        $PostData = json_decode(file_get_contents('php://input'), true);

        // print_r($PostData);

        $picker = $this->Pickup_model->userDropval(4, $PostData);
        // $shipments = $this->Pickup_model->StaffpickingReportQry($PostData);
        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $picker;
        $ii = 0;
        //echo '<pre>';
        $PicklistArr = $this->Pickup_model->StaffpickingReportQry($PostData);
        $shiparray = $PicklistArr;
        foreach ($shiparray as $rdata) {
            $per_day_target = getUserNameById_field($rdata['assigned_to'], 'per_day_target');
            $skuData = $this->Pickup_model->StaffpickingReportQry($rdata['assigned_to'], $rdata['pickedDate']);
            $toatal_sku = 0;
            foreach ($skuData as $skuval) {
                $skuArray = json_decode($skuval['sku']);
                $toatal_sku += count($skuArray);
            }
            if ($rdata['total_orders'] > $per_day_target) {
                $shiparray[$ii]['average'] = '100';
            } else {
                $shiparray[$ii]['average'] = round($rdata['total_orders'] / $per_day_target * 100);
            }
            $shiparray[$ii]['total_sku'] = $toatal_sku;
            $shiparray[$ii]['name'] = getUserNameById($rdata['assigned_to']);
            $ii++;
        }

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = count($PicklistArr);

        echo json_encode($dataArray);
    }

    public function Getpickerdata() {
        $picker = $this->Pickup_model->userDropval(4);
        echo json_encode($picker);
    }

    public function getPackagingExcelDetails() {
        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Pickup_model->getPackagingExcelReport($request);
        $file_name = 'Packaging_report.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
        //print "<pre>"; print_r($request);die;
    }

    public function packing_3pl() {
        if (menuIdExitsInPrivilageArray(258) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->view('pickup/packing_3pl');
    }

    public function packing_CPS_3pl() {
        if (menuIdExitsInPrivilageArray(259) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('pickup/packing_CPS_3pl');
    }

    

}

?>