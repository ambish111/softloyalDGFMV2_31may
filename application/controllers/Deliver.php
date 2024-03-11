<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Status_model');
        $this->load->model('Pickup_model');
        $this->load->model('Deliver_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
    }

    public function validateDispatch() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST;
        $shipments = $this->Deliver_model->shipmetsInAwb($DataArray);

        $valid = array();
        $invalid = array();
        $invalidpallet = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {


                if (trim($data['code']) == 'DL' || trim($data['code']) == 'ROP' || trim($data['code']) == 'DOP'  || trim($data['code']) == 'IT' || trim($data['code']) == 'FD') {
                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
        } else {

            foreach ($DataArray as $key => $val) {
                $new_invallid = array('slip_no' => $val);
                array_push($invalid, $new_invallid);
            }
            //  print_r($new_invallid);
        }


        $returnData['valid'] = $valid;
        $returnData['invalidpallet'] = $invalidpallet;
        $returnData['invalid'] = $invalid;
        echo json_encode($returnData);
    }

    public function dispatchOrder() {
        
        
        $_POST = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Deliver_model->shipmetsInAwbAll($_POST['awbArray']);
        //print_r($shipments); die;
        $code = 'POD';
        $new_status = 7;
        $zidStatus = "delivered";
        $sallaStatus = 1723506348;
        $note = "تم التوصيل";

        $salla_new_update = array("status" => "delivered");

        $activity = "Order Delivered";
        $details = 'Order Delivered By ' . getUserNameById($this->session->userdata('user_details')['user_id']);

        $slip_data = array();
        $OutboundArray = array();
        $key = 0;
        $key1 = 0;
        $wbh_array=array();
         $req_awb= array();
          $send_requestArr=array();
        foreach ($shipments['result'] as $data) {
            $responseData['status'] = 200;
            $responseData['awb'] = "";

            if ($responseData['status'] == 200) {
                array_push($req_awb,$data['slip_no']);
                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_no'];
                $statusvalue[$key]['new_status'] = $new_status;
                $statusvalue[$key]['code'] = $code;
                $statusvalue[$key]['Activites'] = $activity;
                if (!empty($_POST['comments'])) {
                    $statusvalue[$key]['comment'] = $_POST['comments'];
                } else {
                    $statusvalue[$key]['comment'] = "";
                }
                $statusvalue[$key]['Details'] = $details;
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                /* -------------/Status Array----------- */


                $slip_data[$key] ['slip_no'] = $data['slip_no'];
                $slip_data[$key]['code'] = $code;
                $slip_data[$key]['delivered'] = $new_status;
                $slip_data[$key]['close_date'] = date('Y-m-d H:i:s');

                // $slip_data[$key]['close_date'] = date('Y-m-d');
                //======================outbound charges=====================================///
                $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data['slip_no']);
                $totalLocationboxes = GetshpmentDataByawb($data['slip_no'], 'stocklcount');

                $SkuArray = Getallskudatadetails($data['slip_no']);
                $sku_ID = $SkuArray[0]['itmSku'];
                $item_type = getalldataitemtables($sku_ID, 'type');
                $totalPieces = $getallskuArray['pieces'];
                $totalPiecesF = $getallskuArray['pieces'] - 1;

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
                }

                
                 $sellerDetails=GetSinglesellerdata($data['cust_id'],$this->session->userdata('user_details')['super_id']);
            
            //========WooCommerce Status===========//
                $wc_active=$sellerDetails['wc_active'];
                if($wc_active==1 && !empty($sellerDetails['wc_statues']))
                {
                    $wc_consumer_key=$sellerDetails['wc_consumer_key'];
                    $wc_secreat_key=$sellerDetails['wc_secreat_key'];
                    $wc_store_url=$sellerDetails['wc_store_url'];
                    $wc_statues=json_decode($sellerDetails['wc_statues']);
                    $Status_WC=$this->GetWC_status($wc_statues,'POD');
                    $data_wc=array(
                        'customer_key'=>$wc_consumer_key,
                        'customer_secret'=>$wc_secreat_key,
                        'store_url'=>$wc_store_url,
                        'order_id'=>$data['booking_id'],
                        'status'=>$Status_WC,
                        'status_des'=>"Delivered By Diggipacks",
                        );
                    array_push($send_requestArr,$data_wc);
                   
                    
                }
                //=====================================//
                 $WB_Confing = webhook_settingsTable($data['cust_id']);
                        if ($WB_Confing['subscribe'] == 'Y') {
                            $wb_request = array(
                                'datetime' => date('Y-m-d H:i:s'),
                                "code" => 'POD',
                                "status" => 'Order Delivered',
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
                        
                $OutboundArray[$key]['seller_id'] = $seller_id;
                $OutboundArray[$key]['slip_no'] = $data['slip_no'];
                $OutboundArray[$key]['outcharge'] = $totalOutboundCharge;
                $OutboundArray[$key]['entrydate'] = date("Y-m-d H:i:sa");
                $OutboundArray[$key]['pieces'] = $totalPieces;
                $OutboundArray[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                $token = GetallCutomerBysellerId($seller_id, 'manager_token');
                $salatoken = GetallCutomerBysellerId($seller_id, 'salla_athentication');

                if (!empty($salatoken)) {
                    update_status_salla($sallaStatus, $note, $salatoken, $data['shippers_ref_no'], $seller_id, $data['slip_no'],'','',$salla_new_update);
                }

                if (!empty($token)) {
                    $slip_no = $data['slip_no'];
                    if(!empty($data['frwd_company_awb']))
                    {
                        $trackingurl=makeTrackUrl($data['frwd_company_id'],$data['frwd_company_awb']); 
                      
                        
                        $lable=$data['frwd_company_label'];
                    }else
                    {
                        $lable='https://api.diggipacks.com/API/print/'.$data['slip_no'];
                        
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

//print_r($OutboundArray);
//die;
        
        if (!empty($statusvalue) && !empty($slip_data)) {
            $citc_req['t_slip_no']=$req_awb;
             $this->session->set_userdata(array('tracking_citc_req'=>$citc_req));
            $this->Status_model->insertStatus($statusvalue);
            $this->Shipment_model->updateStatusBatch($slip_data);
            $this->Pickup_model->GetalloutboundDataAdded($OutboundArray);
            
            if(!empty($send_requestArr))
            {
            $this->session->set_userdata(array('wc_status_req'=>$send_requestArr));
            }
            if (!empty($wbh_array)) {
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }
        }

         echo json_encode($error_data);
    }

       private function GetWC_status($data=array(),$cod=null)
    {
       foreach($data as $key=>$val)
       {
          if($val->code==$cod)
          {
              return $val->wc_status;
          }
       }
        
    }
    public function deliver() {
        if (menuIdExitsInPrivilageArray(10) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/deliver');
    }

}

?>