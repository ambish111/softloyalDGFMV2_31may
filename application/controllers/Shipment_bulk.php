<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment_bulk extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->helper('stock');
        $this->load->helper('zid');
        $this->load->helper('utility');
        $this->load->model('Shipment_bulk_model');
    }

    public function bulk_create() {

        if (menuIdExitsInPrivilageArray(160) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/bulk_order_create');
    }

    public function checkorderValid() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $DataArray = $postData;
        $shipments = $this->Shipment_bulk_model->checkvalid($DataArray);
        $valid = array();
        $invalid = array();

        if (!empty($shipments)) {
            foreach ($shipments as $data) {
                if (!empty($data['slip_no'])) {
                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
        } else {

            array_push($invalid, $DataArray);
        }


        $returnData['valid'] = $valid;
        $returnData['invalid'] = $invalid;
        echo json_encode($returnData);
    }

    public function getsubmitdata() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $shipArr = array_unique($postData['awbArray']);
        $success_array = array();
        $error_array = array();
        $stockarray_new = array();
        ignore_user_abort();
        if (!file_exists('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'])) {
            mkdir('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'], 0777, true);
        }
        $file = fopen('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'] . '/' . ".lock", "w+");

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            // echo "Unable to obtain lock, the previous process is still going on."; 
            $mess = "Unable to obtain lock, the previous process is still going on.";
        } else {
            if (!empty($shipArr)) {

                $shipments = $this->Shipment_bulk_model->checkvalid($shipArr);
                // print_r($shipments);
                if (!empty($shipments)) {
                    $wbh_array = array();
                    $SlipUpdates = array();
                    $entrydate = date("Y-m-d H:i:s");
                    foreach ($shipments as $key => $data) {
                        if ($data['code'] == 'OG' && $data['delivered'] == 11) {
                            if ($data['origin'] > 0 && $data['destination'] > 0 && $data['pieces'] > 0 && $data['backorder'] == 0 && $data['code'] == 'OG' && $data['delivered'] == 11 && !empty($data['reciever_name']) && !empty($data['reciever_address'])) {
                                $r_city = getdestinationfieldshow($data['destination'], 'city');
                                if (!empty($r_city)) {

                                    $stockarray = array();
                                    $ReturnstockArray = array();
                                    $custmoerID = $data['cust_id'];
                                    $token = $data['manager_token'];
                                    $salatoken = $data['salla_athentication'];
                                    $data['skuData'] = $this->Shipment_model->GetDiamationDetailsBYslipNo_og($data['slip_no']);

                                    if (!empty($data['skuData'])) {
                                        $totalweight = 0;
                                        $data['weight'] = 0;
                                        foreach ($data['skuData'] as $new_key => $skuDetails) {
                                            
                                             $sku_main_arr=getalldataitemtablesSKU_single($skuDetails['sku']);
                                            $skuDetails['sku_id']=$sku_main_arr['sku_id'];
                                            $skuDetails['weight']=$sku_main_arr['weight'];
                                            if($skuDetails['sku_id']>0)
                                            {
                                            $stock_check = ordergenstock_check($data['cust_id'], trim($skuDetails['sku']), $skuDetails['piece'], $data['slip_no'], $custmoerID);
                                            if ($stock_check['succ'] == 1) {
                                                $ReturnstockArray[] = $stock_check['stArray'];
                                                $weightcount = $skuDetails['weight'];
                                                $totalweight = $totalweight + ($weightcount * $skuDetails['piece']);
                                                $data['weight'] = $totalweight;

                                                //die; 
                                                //==========update zid stock===============//                     
                                                if (!empty($token)) {
                                                    $zidReqArr = GetAllQtyforSeller($skuDetails['sku'], $custmoerID);

                                                    $quantity = $zidReqArr['quantity'] - $skuDetails['piece'];
                                                    $pid = $zidReqArr['zid_pid'];
                                                    $token = $token;
                                                    $storeID = $data['zid_store_id'];
                                                    update_zid_product($quantity, $pid, $token, $storeID, $custmoerID, $zidReqArr['sku']);
                                                }

                                                //==========update salla quantity===============//                        
                                                if (!empty($salatoken)) {
                                                    $sallaReqArr = GetAllQtyforSeller($skuDetails['sku'], $custmoerID);
                                                    $quantity = $sallaReqArr['quantity'] - $skuDetails['piece']; //+$fArray['qty'];
                                                    $pid = $sallaReqArr['sku'];
                                                    $sallatoken = $salatoken;
                                                    // echo "<pre>"; print_r($sallaReqArr);
                                                    $reszid = update_salla_qty_product($quantity, $pid, $sallatoken, $custmoerID);
                                                }
                                            } else {

                                                $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku'], 'error_type' => $stock_check);
                                                array_push($stockarray, $errorReturnArray);
                                                array_push($stockarray_new, $errorReturnArray);

                                                //array_push($error_array, $errorReturnArray);
                                            }
                                        }
                                        else
                                        {
                                              $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku'], 'error_type' => 'invalid_sku');
                                                array_push($stockarray, $errorReturnArray);
                                                array_push($stockarray_new, $errorReturnArray);
                                        }
                                        }
                                        // print_r($stockarray);
                                        //  print_r($ReturnstockArray); 
                                        if (empty($stockarray)) {

                                            $WB_Confing = webhook_settingsTable($data['cust_id']);
                                            if ($WB_Confing['subscribe'] == 'Y') {
                                                $wb_request = array(
                                                    'datetime' => $entrydate,
                                                    "code" => 'OC',
                                                    "status" => 'Order Created',
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
                                            array_push($SlipUpdates, $data['slip_no']);
                                            UpdateStockorderGen($ReturnstockArray, $data['weight']);
                                            //  $returnsucc['succ_awb'] = $data['slip_no'];
                                            array_push($success_array, $data['slip_no']);
                                        }
                                    } else {
                                        //  echo $key;
                                        //$invalidSkuArr = $this->Shipment_model->GetinVliadSkulist($data['slip_no']);
                                        // print_r($invalidSkuArr);
                                        $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku'], 'error_type' => 'invalid_sku');
                                        array_push($stockarray, $errorReturnArray);
                                        array_push($stockarray_new, $errorReturnArray);
                                        // array_push($error_array, $errorReturnArray);
                                    }
                                } else {
                                    // $returnError['destination_in_error'] = $data['slip_no'];
                                    array_push($error_array, array("destination_in_error" => $data['slip_no']));
                                }
                            } else {
                                if ($data['origin'] == 0) {
                                    // $returnError['origin_error'] = $data['slip_no'];
                                    array_push($error_array, array("origin_error" => $data['slip_no']));
                                }

                                if ($data['destination'] == 0) {
                                    // $returnError['destination_error'] = $data['slip_no'];
                                    array_push($error_array, array("destination_error" => $data['slip_no']));
                                }

                                if ($data['pieces'] == 0) {
                                    // $returnError['pieces_error'] = $data['slip_no'];
                                    array_push($error_array, array("pieces_error" => $data['slip_no']));
                                }
                                if ($data['backorder'] == 1) {
                                    //$returnError['backorder_error'] = $data['slip_no'];
                                    array_push($error_array, array("backorder_error" => $data['slip_no']));
                                }
                                if (empty($data['reciever_name'])) {
                                    //$returnError['reciever_name_error'] = $data['slip_no'];
                                    array_push($error_array, array("reciever_name_error" => $data['slip_no']));
                                }
                                if (empty($data['reciever_address'])) {
                                    //$returnError['reciever_address_error'] = $data['slip_no'];
                                    array_push($error_array, array("reciever_address_error" => $data['slip_no']));
                                }
                            }
                        } else {
                            if ($data['code'] != 'OG' && $data['delivered'] != 11) {
                                //$returnError['order_status_error'] = $data['slip_no'];
                                array_push($error_array, array("order_status_error" => $data['slip_no']));
                            }
                        }
                    }
                } else {

                     $mess = "Invalid AWB No.";
                }
            } else {
                $mess = "AWB No. is required!";
            }
            flock($file, LOCK_UN);
        }
        fclose($file);

        $destination_error = implode(',', array_column($error_array, 'destination_error'));
        $destination_in_error = implode(',', array_column($error_array, 'destination_in_error'));
        $origin_error = implode(',', array_column($error_array, 'origin_error'));
        $pieces_error = implode(',', array_column($error_array, 'pieces_error'));
        $backorder_error = implode(',', array_column($error_array, 'backorder_error'));
        $reciever_name_error = implode(',', array_column($error_array, 'reciever_name_error'));
        $reciever_address_error = implode(',', array_column($error_array, 'reciever_address_error'));
        $order_status_error = implode(',', array_column($error_array, 'order_status_error'));
        $error_array_1 = array(
            "destination_error" => $destination_error,
            "destination_in_error" => $destination_in_error,
            "origin_error" => $origin_error,
            "pieces_error" => $pieces_error,
            "backorder_error" => $backorder_error,
            "reciever_name_error" => $reciever_name_error,
            "reciever_address_error" => $reciever_address_error,
            "order_status_error" => $order_status_error
        );
        //   echo "<pre>";
        // print_r($destination_error);
        $return['awb_error'] = !empty($mess) ? $mess : "";
        $return['succ_awb'] = implode(',', $success_array);
        $return['error_all'][] = $error_array_1;
        $return['error_stock'] = $stockarray_new;

        echo json_encode($return);
    }

}

?>