<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment_og extends MY_Controller {

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
        // $this->load->model('Item_model');
        //$this->load->model('Status_model');
        $this->load->model('Pickup_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
        //$this->load->model('User_model');
        $this->load->model('ItemInventory_model');
        // $this->load->model('Ccompany_model');
        $this->load->helper('stock');
        $this->load->model('Shipment_og_model');

        // $this->user_id = isset($this->sess
        // 
        // ion->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
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

     public function filter_orderGen() {
        // print("heelo");
        // exit();
        
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);

        $shipments = $this->Shipment_model->filter_orderGen($_POST,'New');

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        // echo "<pre>" ;
        // print_r($_POST);  die;

        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = count($shipments);
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }

        $tolalShip1 = count($shipments);
        if ($tolalShip1 <= 100) {
            $downlaoadData1 = 10;
            $m = 1;
            for ($im = 0; $im < $tolalShip1;) {
                $im = $im + $downlaoadData1;
                if ($i > 1) {
                    $expoertdropArr1[] = array('j' => $m, 'i' => $im);
                }
                $m = $im;
            }
        }



        $pageShortArr = $this->pageshortDropData($tolalShip);
      
        $originArray=array();
        $destinationArray=array();
        $destinationCountry=array();
        $ccNameArray=array();
        $customerArray=array();
        
        foreach ($shipments['result'] as $rdata) {


            if(empty( $customerArray[$rdata['cust_id']]))
            {
            $customerArray[$rdata['cust_id']]= GetSinglesellerdata($rdata['cust_id'],$rdata['super_id']);
            //print_r($customerArray[$rdata['cust_id']]);exit;
            }
            
            $shiparray[$ii]['seller_id']=$customerArray[$rdata['cust_id']]['id'];
            $shiparray[$ii]['name']=$customerArray[$rdata['cust_id']]['name'];
            $shiparray[$ii]['company']=$customerArray[$rdata['cust_id']]['company'];
            $shiparray[$ii]['uniqueid']=$customerArray[$rdata['cust_id']]['uniqueid'];
            if (empty($ccNameArray[$rdata['frwd_company_id']]) && $rdata['frwd_company_id']>0 ) {
                $ccNameArray[$rdata['frwd_company_id']] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');
            }

            if( empty($originArray[$rdata['origin']]))
    {
        $originArray[$rdata['origin']]= getdestinationfieldshow($rdata['origin'], 'city');
    }
    if( empty($destinationArray[$rdata['destination']]))
    {
        $destinationArray[$rdata['destination']]= getdestinationfieldshow($rdata['destination'], 'city');
    }
    if( empty($destinationCountry[$rdata['destination']]))
    {
        $destinationCountry[$rdata['destination']]= getdestinationfieldshow($rdata['destination'], 'country');
    }

            //$expire_data=$this->Shipment_model->GetallexpredataQuery($rdata['seller_id'],$rdata['sku']);
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            //$shiparray[$ii]['expire_details']=$expire_data;
            if (!empty($rdata['order_type'])) {
                $shiparray[$ii]['order_type'] = $rdata['order_type'];
            } else {
                $shiparray[$ii]['order_type'] = 'B2C';
            }
            $shiparray[$ii]['sku_id'] = getalldataitemtablesBySku($rdata['sku'], 'id');
            //$shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['origin_valid'] = $rdata['origin'];
            $shiparray[$ii]['destination_valid'] = $rdata['destination'];
            if ($rdata['origin'] > 0) {
                $shiparray[$ii]['origin'] = $originArray[$rdata['origin']];
            } else {
                $shiparray[$ii]['origin'] = GetErrorShowShipment($rdata['slip_no'], $rdata['booking_id'], 'origin');
            }
            if ($rdata['destination'] > 0) {
                $shiparray[$ii]['destination'] = $destinationArray[$rdata['destination']];
            } else {
                $shiparray[$ii]['destination'] = GetErrorShowShipment($rdata['slip_no'], $rdata['booking_id'], 'destination');
            }
            if(empty($destinationCountry[$rdata['destination']]))
            {
                $destinationCountry[$rdata['destination']]=$destinationCountry[$rdata['destination']];
            }
            $shiparray[$ii]['main_status']='Order Generated';
            $shiparray[$ii]['destination_id'] = $rdata['destination'];
            $shiparray[$ii]['sender_name'] = $rdata['sender_name']; //getallsellerdatabyID($rdata['cust_id'],'company',$rdata['super_id']);
            $shiparray[$ii]['total_cod_amt'] = $rdata['total_cod_amt'];
            $shiparray[$ii]['whid'] = $rdata['wh_id'];
            $shiparray[$ii]['cc_name'] =  $ccNameArray[$rdata['frwd_company_id']];
            $shiparray[$ii]['country_name'] = $destinationCountry[$rdata['destination']];
            // $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);
            // $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            //$shiparray='rith';
            $ii++;
        }


        $dataArray['dropexport_checkbox'] = $expoertdropArr1;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];


        echo json_encode($dataArray);
    }

    public function ordergeneratedView() {
        if (menuIdExitsInPrivilageArray(80) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $sellers = $this->Seller_model->find2();
        $data['sellers'] = $sellers;
        $this->load->view('ShipmentM/orderGen_new', $data);
    }

    function CreateGenratedOrderCheck() {

        $this->load->model('Pickup_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        // echo '<pre>';
        // print_r($dataArray);die;

        ignore_user_abort();
        if (!file_exists('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'])) {
            mkdir('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'], 0777, true);
        }
        $file = fopen('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'] . '/' . ".lock", "w+");

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            // echo "Unable to obtain lock, the previous process is still going on."; 
            $stockarray = array('status' => 205, "Unable to obtain lock, the previous process is still going on.");
        } else {

            $key = 0;

            $entrydate = date("Y-m-d H:i:s");

            $arar = $dataArray['slipData'];
            $wbh_array = array();
            $SlipUpdates = array();
            $ayte['listData'] = $this->Shipment_model->ShipData($arar);
            //echo "<pre>";

            foreach ($ayte['listData'] as $data) {
                if ($data['origin'] > 0 && $data['destination'] > 0 && $data['pieces'] > 0 && $data['skubtnDs'] != 'Y') {


                    $stockarray = array();
                    $ReturnstockArray = array();
                    $data['skuData'] = $this->Shipment_model->GetDiamationDetailsBYslipNo_og($data['slip_no']);

                    $custmoerID = $data['cust_id'];
                    // print_r($data['skuData']);
                    $token = GetallCutomerBysellerId($custmoerID, 'manager_token');
                    $salatoken = GetallCutomerBysellerId($custmoerID, 'salla_athentication');
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
                                $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku']);
                                array_push($stockarray, $errorReturnArray);
                            }
                        }
                        else
                    {
                            $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku']);
                            array_push($stockarray, $errorReturnArray);   
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
                        }
                    } else {
                        //  echo $key;
                        $invalidSkuArr = $this->Shipment_model->GetinVliadSkulist($data['slip_no']);
                        // print_r($invalidSkuArr);
                        $errorReturnArray = array("slip_no" => $data['slip_no'], "sku_invalid" => implode(',', $invalidSkuArr));
                        array_push($stockarray, $errorReturnArray);
                    }




                    $key++;
                }
            }
            //print_r($ReturnstockArray); 

            flock($file, LOCK_UN);
        }
        fclose($file);
        //    // die;
        //  echo '<pre>';
        //  print_r($ReturnstockArray);
        //  print_r($errorReturnArray);
        //   die;




        if (!empty($SlipUpdates)) {
            $this->Shipment_model->AddtempProcess($SlipUpdates);
            if (!empty($wbh_array)) {
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }
        }
        // die;

        echo json_encode($stockarray);
    }

    public function pickup_order($uid = null) {
        $data['uid'] = $uid;
        $this->load->view('pickup/picking_order', $data);
    }

    public function pickingCheck() {

        $postData = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST);
        $shipments = $this->Shipment_og_model->pickListFilterNotPicked($postData['slip_no']);
        $ReturnArray = $shipments['result'];
        $ReturnstockArray = array();
        $stockarray = array();
        // print_r( $shipments); exit;
        //echo "<pre>";

        foreach ($ReturnArray as $key => $val) {


            $sku_details = $this->Shipment_og_model->GetskuDetailspack($val['slip_no']);
            // print_r($sku_details);
            foreach ($sku_details as $skudata) {
                $offer_data = $this->Shipment_og_model->Getorderpromocode($val['slip_no'], $skudata['sku']);
                if (!empty($offer_data)) {
                    $offer = "Yes";
                    $pcode = $offer_data;
                } else {
                    $offer = "No";
                    $pcode = "N/A";
                }
                $stock_check = $this->GetcheckInventoryLocation($skudata, $val['slip_no'], $offer, $pcode);
                // print_r($stock_check);
                if ($stock_check['succ'] == 1) {

                    $ReturnstockArray[] = $stock_check['stArray'];
                } else {
                    $errorReturnArray = array("slip_no" => $val['slip_no'], "sku" => $skudata['sku'], 'error' => $stock_check);
                    array_push($stockarray, $errorReturnArray);
                }
            }

            //  print_r($stockarray);
            $ReturnArray[$key]['sku'] = $ReturnstockArray;
            $ReturnArray[$key]['sku_count'] = count($sku_details);
        }

        if (empty($stockarray)) {
            $return['result'] = $ReturnArray;
            $return['error_sku'] = array();
            $return['count'] = $shipments['count'];
        } else {
            $return['result'] = null;
            $return['error_sku'] = $stockarray;
            $return['count'] = 0;
        }
        echo json_encode($return);
    }

    private function GetcheckInventoryLocation($data = array(), $slip_no = null, $offer = null, $pcode = null) {
        $result = $this->Shipment_og_model->GetcheckInventoryLocation($data);
        $pieces = $data['piece'];
        $totalqty = 0;
        $show_piece = 0;
        $error_array = array();
        $returnarray = array();
        $stock_location_new = array();

        foreach ($result as $row) {
            $totalqty += $row['quantity'];
        }
        if (empty($error_array) && $pieces > 0) {
            if ($totalqty > 0) {
                if ($pieces <= $totalqty) {
                    $newpcs = $pieces;

                    foreach ($result as $key => $rdata) {

                        if ($pieces >= $rdata['quantity']) {
                            //echo "ssssss";
                            $returnarray[$key]['upqty'] = 0;
                            $oldPeice = $pieces;
                            $pieces = $pieces - $rdata['quantity'];
                            if ($returnarray[$key]['upqty'] == 0) {
                                $oldPeice = $rdata['quantity'];
                            }
                            $returnarray[$key]['expire_block'] = $data['expire_block'];
                            $returnarray[$key]['cod'] = $data['cod'];
                            $returnarray[$key]['sku_size'] = $data['sku_size'];
                            $returnarray[$key]['cust_id'] = $rdata['seller_id'];
                            $returnarray[$key]['tableid'] = $rdata['id'];
                            $returnarray[$key]['item_sku'] = $rdata['item_sku'];
                            $returnarray[$key]['skuid'] = $rdata['item_sku'];
                            $returnarray[$key]['quantity'] = $rdata['quantity'];
                            $returnarray[$key]['slip_no'] = $slip_no;
                            $returnarray[$key]['seller_id'] = $rdata['seller_id'];
                            $returnarray[$key]['sku'] = $data['sku'];
                            $returnarray[$key]['totalqty'] = $totalqty;
                            $returnarray[$key]['pieces'] = $pieces;
                            $returnarray[$key]['offer'] = $offer;
                            $returnarray[$key]['pcode'] = $pcode;
                            $returnarray[$key]['piece'] = $oldPeice;
                            $returnarray[$key]['shelve_no'] = $rdata['shelve_no'];
                            $returnarray[$key]['oldPeice'] = $oldPeice;
                            $returnarray[$key]['st_location'] = $rdata['stock_location'];

                            array_push($stock_location_new, array('slip_no' => $data['slip_no'], 'sku' => $data['sku']));
                        } else {
                            if ($pieces > 0) {
                                $oldPeice = $pieces;
                                $returnarray[$key]['upqty'] = $rdata['quantity'] - $pieces;
                                if ($returnarray[$key]['upqty'] == 0) {
                                    $oldPeice = $rdata['quantity'];
                                }
                                $returnarray[$key]['expire_block'] = $data['expire_block'];
                                $returnarray[$key]['cod'] = $data['cod'];
                                $returnarray[$key]['sku_size'] = $data['sku_size'];
                                $returnarray[$key]['cust_id'] = $rdata['seller_id'];
                                $returnarray[$key]['tableid'] = $rdata['id'];
                                $returnarray[$key]['skuid'] = $rdata['item_sku'];
                                $returnarray[$key]['item_sku'] = $rdata['item_sku'];
                                $returnarray[$key]['quantity'] = $rdata['quantity'];
                                $returnarray[$key]['sku'] = $data['sku'];
                                $returnarray[$key]['slip_no'] = $slip_no;
                                $returnarray[$key]['seller_id'] = $rdata['seller_id'];
                                $returnarray[$key]['shelve_no'] = $rdata['shelve_no'];
                                $returnarray[$key]['totalqty'] = $totalqty;
                                $returnarray[$key]['pieces'] = $pieces;
                                $returnarray[$key]['offer'] = $offer;
                                $returnarray[$key]['pcode'] = $pcode;
                                $returnarray[$key]['piece'] = $oldPeice;
                                $returnarray[$key]['oldPeice'] = $oldPeice;
                                $returnarray[$key]['st_location'] = $rdata['stock_location'];

                                //array_push($stock_location_new, array('slip_no' => $data['slip_no'], 'sku' => $data['sku'], 'stock_location' => $rdata['stock_location']));

                                $pieces = 0;
                            }
                        }
                    }

                    return array('succ' => 1, 'stArray' => $returnarray);
                } else {
                    return 'Less Stock or item expired';
                }
            } else {
                return 'Less Stock or item expired';
            }
        } else {
            return 'Invalid Order Piece';
        }
    }

    public function GetcheckStockLocation() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $sku_data = $postData['list'];
        $stockLocation = $sku_data['stock_location'];
        $expire_block = $sku_data['expire_block'];

        if (!empty($sku_data)) {
            $inventory_arr = $this->Shipment_og_model->GetcheckLocation($sku_data);
            if (!empty($inventory_arr)) {

                if ($inventory_arr['quantity'] >= $sku_data['piece']) {
                    $current_date = date("Y-m-d");
                    $expity_date = $inventory_arr['expity_date'];
                    if ($expire_block == 'Y') {
                        if ($expity_date >= $current_date) {
                            $return = array("error" => "item_expired", "location" => $stockLocation, "result" => null);
                        } else {
                            $return = array("error" => "valid", "location" => $stockLocation, "result" => $inventory_arr);
                        }
                    } else {
                        $return = array("error" => "valid", "location" => $stockLocation, "result" => $inventory_arr);
                    }
                } else {
                    $return = array("error" => "invalid_stock", "location" => $stockLocation, "result" => $inventory_arr);
                }
            } else {
                $return = array("error" => "invalid_stock_location", "location" => $stockLocation, "result" => $inventory_arr);
            }
        }
        echo json_encode($return);
    }

    public function picking_finish() {

        $this->load->model('Stock_model');
        $postData = json_decode(file_get_contents('php://input'), true);
        //echo "<pre>";
        //print_r($postData); die;
        $sku_data = $postData['sku_data'];
        $slip_no = $postData['slip_no'];

        
        $shipments = $this->Shipment_og_model->pickListFilterNotPicked($slip_no);
        $checkPickupStatus = $shipments['result'][0];
        $total_piece=$checkPickupStatus['pieces'];
        $cust_id=$checkPickupStatus['cust_id'];
        $temp_st_location = array_unique(array_column($sku_data, 'stock_location'));
       $in_stock_running_location= $this->Shipment_og_model->GetcheckLocation_sum_qty($temp_st_location,$cust_id);
       
       $check_valid_sku= array();
       $check_Invalid_sku= array();
       if($in_stock_running_location>=$total_piece)
       {
        if (!empty($checkPickupStatus)) {
            if (!empty($sku_data)) {
                 
                // print_r($temp);\
                $status_fm_picking=array(
                    'user_id'=>$this->session->userdata('user_details')['user_id'],
                    'slip_no'=>!empty($slip_no)?$slip_no:"No",
                    'request_p'=>json_encode($postData),
                    'entry_date'=>date("Y-m-d H:i:s"),
                    'super_id'=>$this->session->userdata('user_details')['super_id']);
                $this->Shipment_og_model->picking_log($status_fm_picking);
                
                 foreach ($sku_data as $row) {
                     $inventory_arr = $this->Shipment_og_model->GetcheckLocation($row);
                     
                     $check_old_qty = $inventory_arr['quantity'];
                     $check_piece=$row['piece'];
                     if($check_old_qty>=$check_piece)
                     {
                        array_push($check_valid_sku,$row['sku']);
                     }
                     else
                     {
                         array_push($check_Invalid_sku,$row['sku']);
                     }
                 }
                 if(empty($check_Invalid_sku)){
              //  echo "sssss"; die;
                foreach ($sku_data as $row) {
                    $inventory_arr = $this->Shipment_og_model->GetcheckLocation($row);
                    $old_qty = $inventory_arr['quantity'];
                    $used_qty = $row['piece'];
                    $new_qty = $old_qty-$row['piece'];//$row['upqty'];
                    $stock_location = trim($row['stock_location']);
                    $shelve_no = $inventory_arr['shelve_no'];
                    $expity_date = $inventory_arr['expity_date'];
                    $sku = $row['sku'];

                    $updateArr = array("quantity" => $new_qty);
                    $updateArr_w = array("stock_location" => $row['stock_location'], "seller_id" => $row['cust_id'], "item_sku" => $row['item_sku']);
                    $activitiesArr = array(
                        'exp_date' => $expity_date,
                        'st_location' => $stock_location,
                        'item_sku' => $row['item_sku'],
                        'user_id' => $this->session->userdata('user_details')['user_id'],
                        'seller_id' => !empty($row['cust_id'])?$row['cust_id']:0,
                        'qty' => $new_qty,
                        'p_qty' => $old_qty,
                        'qty_used' => $used_qty,
                        'type' => 'deducted',
                        'entrydate' => date("Y-m-d h:i:s"),
                        'awb_no' => !empty($slip_no)?$slip_no:"",
                        'super_id' => $this->session->userdata('user_details')['super_id'],
                        'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                        "comment" => "Picking"
                    );

                    $locationDetails = array(
                        "slip_no" => $slip_no,
                        "stock_location" => $stock_location,
                        'sku' => $sku,
                        "shelve_no" => !empty($shelve_no) ? $shelve_no : ""
                    );
                    $diamantionArr = array("wh_id" => $row['wh_id'], "deducted_shelve" => !empty($shelve_no) ? $shelve_no : "");
                    $diamantionArr_w = array("sku" => $sku, "slip_no" => $slip_no);

                    $this->Shipment_og_model->GetupdateInventory($updateArr, $updateArr_w);
                    $this->Stock_model->AddInventoryHistory($activitiesArr);
                    $this->Shipment_og_model->locationDetailsQry($locationDetails);
                    $this->Shipment_og_model->UpdatediamantionArr($diamantionArr, $diamantionArr_w);
                }
                if(!empty($updateArr))
                {
                $pikupArr = array("picked_status" => 'Y', "pickedDate" => date("Y-m-d H:i:s"));
                $pikupArr_w = array("slip_no" => $slip_no, 'pickupId' => $postData['uid']);
                $this->Shipment_og_model->GetupdatePickupList($pikupArr, $pikupArr_w);
                }
                $return=array("status"=>"succ");
            }
            else
            {
                $return=array("status"=>"failed");
            }
                //echo $this->db->last_query();
            }
            else
            {
                $return=array("status"=>"failed");
            }
        }
       }else
       {
          $return=array("status"=>"failed");
       }
      // echo "<pre>";
      // print_r($check_valid_sku);
       // print_r($check_Invalid_sku);
        
        echo json_encode($return);
    }

    public function picking_finish_old() {

        $this->load->model('Stock_model');
        $postData = json_decode(file_get_contents('php://input'), true);
        // echo "<pre>";
        //print_r($postData); die;
        $sku_data = $postData['sku_data'];
        $slip_no = $postData['slip_no'];
        $shipments = $this->Shipment_og_model->pickListFilterNotPicked($slip_no);
        $checkPickupStatus = $shipments['result'][0];
        if (!empty($checkPickupStatus)) {
            if (!empty($sku_data)) {
                // $temp = array_unique(array_column($sku_data, 'sku'));
                // print_r($temp);\
                foreach ($sku_data as $row) {
                    $inventory_arr = $this->Shipment_og_model->GetcheckLocation($row);
                    $old_qty = $inventory_arr['quantity'];
                    $new_qty = $old_qty - $row['piece'];
                    $stock_location = trim($row['stock_location']);
                    $shelve_no = $inventory_arr['shelve_no'];
                    $expity_date = $inventory_arr['expity_date'];
                    $sku = $row['sku'];

                    $updateArr = array("quantity" => $new_qty);
                    $updateArr_w = array("stock_location" => $row['stock_location'], "seller_id" => $row['cust_id'], "item_sku" => $row['item_sku']);
                    $activitiesArr = array(
                        'exp_date' => $expity_date,
                        'st_location' => $stock_location,
                        'item_sku' => $row['item_sku'],
                        'user_id' => $this->session->userdata('user_details')['user_id'],
                        'seller_id' => $row['cust_id'],
                        'qty' => $new_qty,
                        'p_qty' => $old_qty,
                        'qty_used' => $row['piece'],
                        'type' => 'deducted',
                        'entrydate' => date("Y-m-d h:i:s"),
                        'awb_no' => $slip_no,
                        'super_id' => $this->session->userdata('user_details')['super_id'],
                        'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                        "comment" => "Picking"
                    );

                    $locationDetails = array(
                        "slip_no" => $slip_no,
                        "stock_location" => $stock_location,
                        'sku' => $sku,
                        "shelve_no" => !empty($shelve_no) ? $shelve_no : ""
                    );
                    $diamantionArr = array("wh_id" => $row['wh_id'], "deducted_shelve" => !empty($shelve_no) ? $shelve_no : "");
                    $diamantionArr_w = array("sku" => $sku, "slip_no" => $slip_no);

                    $this->Shipment_og_model->GetupdateInventory($updateArr, $updateArr_w);
                    $this->Stock_model->AddInventoryHistory($activitiesArr);
                    $this->Shipment_og_model->locationDetailsQry($locationDetails);
                    $this->Shipment_og_model->UpdatediamantionArr($diamantionArr, $diamantionArr_w);
                }

                $pikupArr = array("picked_status" => 'Y', "pickedDate" => date("Y-m-d H:i:s"));
                $pikupArr_w = array("slip_no" => $slip_no, 'pickupId' => $postData['uid']);
                $this->Shipment_og_model->GetupdatePickupList($pikupArr, $pikupArr_w);
                //echo $this->db->last_query();
            }
        }
        echo json_encode(true);
    }

    public function open_order($slip_no = null) {
        $data['slip_no'] = $slip_no;
        $this->load->view('pickup/open_order', $data);
    }

    public function getcheckSimpleOpenOrder() {
        $this->load->model('Stock_model');
        $postData = json_decode(file_get_contents('php://input'), true);
        $ship_data = $this->Shipment_og_model->orderopencheckCheck_without_stock($postData);
        if (!empty($ship_data)) {
            $sku_data = $this->Shipment_og_model->GetskuDetailspack($ship_data['slip_no']);

            if (!empty($sku_data)) {

                foreach ($sku_data as $row) {
                    $mainStock = GetcheckMainStock($row['sku'], $row['cust_id']);
                    if (empty($mainStock)) {
                        $ins_data = array('sku' => $row['sku'], "qty" => $row['piece'], "seller_id" => $row['cust_id'], "super_id" => $this->session->userdata('user_details')['super_id']);
                        $this->Stock_model->addcustomerInventory($ins_data);
                        $type_data = "return";
                        $act_qty = $row['piece'];
                    } else {
                        $oldqty = $mainStock['qty'] + $row['piece'];
                        $up_data = array("qty" => $oldqty);
                        $this->Stock_model->upcustomerInventory($up_data, $mainStock);
                        $type_data = "return";
                        $act_qty = $oldqty;
                    }

                    $inventory_activity_user = array(
                        'item_sku' => $row['item_sku'],
                        'user_id' => $this->session->userdata('user_details')['user_id'],
                        'seller_id' => $row['cust_id'],
                        'qty' => $act_qty,
                        'p_qty' => !empty($mainStock['qty']) ? $mainStock['qty'] : 0,
                        'qty_used' => $row['piece'],
                        'type' => $type_data,
                        'entrydate' => date("Y-m-d h:i:s"),
                        'awb_no' => $ship_data['slip_no'],
                        'super_id' => $this->session->userdata('user_details')['super_id'],
                        "comment" => "Open Order"
                    );

                    // echo "sssssss";
                    $this->Stock_model->inventory_activity_user($inventory_activity_user);
                    //echo $this->db->last_query();

                    $shipmentArr = array("code" => "OG", "delivered" => 11, 'open_stock' => 0);
                    $StatusArray['slip_no'] = $ship_data['slip_no'];
                    $StatusArray['new_status'] = 11;
                    $StatusArray['pickup_time'] = date("H:i:s");
                    $StatusArray['pickup_date'] = date('Y-m-d H:i:s');
                    $StatusArray['Activites'] = "Order Generated";
                    $StatusArray['Details'] = "Open Order";
                    $StatusArray['Details'] = "Open Order";
                    $StatusArray['comment'] = "Open Order";
                    $StatusArray['entry_date'] = date('Y-m-d H:i:s');
                    $StatusArray['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $StatusArray['user_type'] = 'fulfillment';
                    $StatusArray['code'] = 'OG';
                    $StatusArray['super_id'] = $this->session->userdata('user_details')['super_id'];
                }
            }



//echo $slip_no; die;


            if (!empty($shipmentArr)) {
                 $this->Shipment_og_model->GetUpdateShipment($shipmentArr, $ship_data['slip_no']);
                // echo $this->db->last_query(); die;
                 $this->Shipment_og_model->updateStatus($StatusArray);
            }
            $return = array("status" => "succ", "mess" => 'Updated');
        } else {
            $return = array("status" => "error", "mess" => 'Invalid AWB');
        }
        echo json_encode($return);
    }

    public function orderopencheckCheck() {
        $postData = json_decode(file_get_contents('php://input'), true);
        if (!empty($postData['slip_no'])) {
            $ship_data = $this->Shipment_og_model->orderopencheckCheck($postData);
            if ($ship_data['id'] > 0) {
                $skudata = $this->Shipment_og_model->GetskuDetailspack($ship_data['slip_no']);
                $return = array("status" => true, "result" => $skudata, "count" => 1, "sku_count" => count($skudata), 'slip_no' => $ship_data['slip_no']);
            } else {
                $return = array("status" => false, "result" => null, "count" => 0, "sku_count" => count($skudata), 'slip_no' => $postData['slip_no']);
            }
        }

        echo json_encode($return);
    }

    public function GetcheckStockLocation_open() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $sku_data = $postData['list'];
        $stockLocation = $sku_data['stock_location'];
        $update_qty = $sku_data['piece'];
        $capacity = $sku_data['sku_size'];

        if (!empty($sku_data)) {
            $inventory_arr = $this->Shipment_og_model->GetcheckLocation($sku_data);
            if (!empty($inventory_arr)) {
                $old_qty = $inventory_arr['quantity'];
                $totalQTY_size_in = $old_qty + $update_qty;
                if ($totalQTY_size_in <= $capacity) {
                    $return = array("error" => "valid", "location" => $stockLocation, "result" => $inventory_arr);
                } else {
                    $return = array("error" => "invalid_stock", "location" => $stockLocation, "result" => null);
                }
            } else {
                $return = array("error" => "invalid_stock_location", "location" => $stockLocation, "result" => null);
            }
        }
        echo json_encode($return);
    }

    public function GetcheckStockLocation_return() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $sku_data = $postData['list'];
        $stockLocation = $sku_data['stock_location'];
        $update_qty = $sku_data['piece'];
        $capacity = $sku_data['sku_size'];
        $fillStockLocations = $postData['fillStockLocations'];
        if (!empty($sku_data)) {
            if (!in_array($stockLocation, $fillStockLocations)) {
                $inventory_arr = $this->Shipment_og_model->GetcheckLocation($sku_data);
                if (!empty($inventory_arr)) {
                    $old_qty = $inventory_arr['quantity'];
                    $totalQTY_size_in = $old_qty + $update_qty;
                    if ($totalQTY_size_in <= $capacity) {
                        $inventory_arr['loc_new'] = 'Old';
                        $return = array("error" => "valid", "location" => $stockLocation, "result" => $inventory_arr);
                    } else {
                        $inventory_arr['loc_new'] = '';
                        $return = array("error" => "invalid_stock", "location" => $stockLocation, "result" => null);
                    }
                } else {

                    $new_location = $this->Shipment_og_model->GetcheckLocation_open($stockLocation);
                    if (!empty($new_location)) {
                         $inventory_arr['quantity'] = 0;
                         $inventory_arr['loc_new'] = 'New';
                        $return = array("error" => "valid", "location" => $stockLocation, "result" => $inventory_arr);
                    } else {
                        $inventory_arr['loc_new'] = '';
                        $return = array("error" => "invalid_stock_location", "location" => $stockLocation, "result" => null);
                    }
                }
            } else {
                $return = array("error" => "invalid_stock_location", "location" => $stockLocation, "result" => null);
            }
        }
        echo json_encode($return);
    }

    public function openorder_finish() {
        $this->load->model('Stock_model');
        $postData = json_decode(file_get_contents('php://input'), true);
        $sku_data = $postData['sku_data'];
        $slip_no = $postData['slip_no'];
        $ship_data = $this->Shipment_og_model->orderopencheckCheck(array('slip_no' => $slip_no));
        if (!empty($ship_data)) {
            if (!empty($sku_data) && !empty($slip_no)) {
                // $temp = array_unique(array_column($sku_data, 'sku'));
                // print_r($temp);\
                foreach ($sku_data as $row) {
                    $inventory_arr = $this->Shipment_og_model->GetcheckLocation($row);
                    $old_qty = $inventory_arr['quantity'];

                    $update_qty = $row['piece'];
                    $totalQTY_size_in = $old_qty + $update_qty;
                    $capacity = $row['sku_size'];
                    $stock_location = trim($row['stock_location']);
                    $shelve_no = $inventory_arr['shelve_no'];
                    $expity_date = $inventory_arr['expity_date'];
                    $sku = $row['sku'];
                    if ($totalQTY_size_in <= $capacity) {

                        $updateArr = array("quantity" => $totalQTY_size_in);
                        $updateArr_w = array("stock_location" => $row['stock_location'], "seller_id" => $row['cust_id'], "item_sku" => $row['item_sku']);
                        $activitiesArr = array(
                           // 'exp_date' => $expity_date,
                            'st_location' => $stock_location,
                            'item_sku' => $row['item_sku'],
                            'user_id' => $this->session->userdata('user_details')['user_id'],
                            'seller_id' => $row['cust_id'],
                            'qty' => $totalQTY_size_in,
                            'p_qty' => $old_qty,
                            'qty_used' => $row['piece'],
                            'type' => 'return',
                            'entrydate' => date("Y-m-d h:i:s"),
                            'super_id' => $this->session->userdata('user_details')['super_id'],
                            'awb_no' => $slip_no,
                            'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                            "comment" => "open Order"
                        );

                        $locationDetails = array(
                            "slip_no" => $slip_no,
                                // "stock_location" => $stock_location,
                        );

                        $mainStock = GetcheckMainStock($sku, $row['cust_id']);
                        if (empty($mainStock)) {
                            $ins_data = array('sku' => $sku, "qty" => $row['piece'], "seller_id" => $row['cust_id'], "super_id" => $this->session->userdata('user_details')['super_id']);
                            $this->Stock_model->addcustomerInventory($ins_data);
                            $type_data = "return";
                            $act_qty = $row['piece'];
                        } else {
                            $oldqty = $mainStock['qty'] + $row['piece'];
                            $up_data = array("qty" => $oldqty);
                            $this->Stock_model->upcustomerInventory($up_data, $mainStock);
                            $type_data = "return";
                            $act_qty = $oldqty;
                        }

                        $inventory_activity_user = array(
                            'item_sku' => $row['item_sku'],
                            'user_id' => $this->session->userdata('user_details')['user_id'],
                            'seller_id' => $row['cust_id'],
                            'qty' => $act_qty,
                            'p_qty' => !empty($mainStock['qty']) ? $mainStock['qty'] : 0,
                            'qty_used' => $row['piece'],
                            'type' => $type_data,
                            'entrydate' => date("Y-m-d h:i:s"),
                            'awb_no' => $slip_no,
                            'super_id' => $this->session->userdata('user_details')['super_id'],
                            "comment" => "Open Order"
                        );

                        $shipmentArr = array("code" => "OG", "delivered" => 11, 'open_stock' => 0);

                        $this->Shipment_og_model->GetupdateInventory($updateArr, $updateArr_w);
                        $this->Stock_model->AddInventoryHistory($activitiesArr);
                        // echo $this->db->last_query();
                        $this->Stock_model->inventory_activity_user($inventory_activity_user);
                        //echo $this->db->last_query();
                        $this->Shipment_og_model->LocationdetailsUpdates($locationDetails);
                    }
                }
                if (!empty($shipmentArr)) {
                    $StatusArray['slip_no'] = $slip_no;
                    $StatusArray['new_status'] = 11;
                    $StatusArray['pickup_time'] = date("H:i:s");
                    $StatusArray['pickup_date'] = date('Y-m-d H:i:s');
                    $StatusArray['Activites'] = "Order Generated";
                    $StatusArray['Details'] = "Open Order";
                    $StatusArray['comment'] = "Open with Stock";
                    $StatusArray['entry_date'] = date('Y-m-d H:i:s');
                    $StatusArray['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $StatusArray['user_type'] = 'fulfillment';
                    $StatusArray['code'] = 'OG';
                    $StatusArray['super_id'] = $this->session->userdata('user_details')['super_id'];
//echo $slip_no; die;
                    $this->Shipment_og_model->GetUpdateShipment($shipmentArr, $slip_no);
                    // echo $this->db->last_query(); die;
                    $this->Shipment_og_model->updateStatus($StatusArray);
                }
            }
        }


        echo json_encode(true);
    }

    public function return_order($slip_no = null) {
        $data['slip_no'] = $slip_no;
        $this->load->view('pickup/returnLM_new', $data);
    }

    public function CheckReturnFulfil() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Shipment_og_model->GetCheckReturnFulfilstatus($postData['slip_no']);
        $newarray = $shipments['result'];
        foreach ($newarray as $key => $val) {
            $sku_data = $this->Shipment_og_model->GetskuDetailsRTF($val['slip_no']);

            foreach ($sku_data as $key1 => $row) {
                $item_size = $row['sku_size'];
                $totalqty = $row['piece'];
                $in_data = $this->Shipment_og_model->GetcheckLocation_rtf($row);
                //print_r($in_data);
                if (!empty($in_data)) {
                    $new_qty = $totalqty + $in_data['quantity'];
                    if ($new_qty <= $item_size) {
                        $sku_data[$key1]['local_type'] = 'Old';
                        $sku_data[$key1]['in_stock_new'] = $in_data['quantity'];
                        $sku_data[$key1]['in_stock'] = 0;
                        $sku_data[$key1]['st_location'] = $in_data['stock_location'];
                    } else {
                        $sku_data[$key1]['in_stock_new'] = 0;
                        $sku_data[$key1]['in_stock'] = 0;
                        $sku_data[$key1]['local_type'] = 'New';
                        $sku_data[$key1]['st_location'] = $this->Shipment_og_model->GetcheckLocation_rtf_new();
                    }
                } else {
                    $sku_data[$key1]['in_stock_new'] = 0;
                    $sku_data[$key1]['in_stock'] = 0;
                    $sku_data[$key1]['local_type'] = 'New';
                    $sku_data[$key1]['st_location'] = $this->Shipment_og_model->GetcheckLocation_rtf_new();
                }
            }

            //  print_r($sku_data);
            $newarray[$key]['sku'] = $sku_data;
        }

        $returnArr['result'] = $newarray;
        $returnArr['count'] = $shipments['count'];
        $returnArr['count_sku'] = count($this->Shipment_og_model->GetskuDetailsRTF($postData['slip_no']));

        echo json_encode($returnArr);
    }

    public function save_details() {
        $this->load->model('Stock_model');
        $postData = json_decode(file_get_contents('php://input'), true);
        $sku_data = $postData['sku_data'];
        $slip_no = $postData['slip_no'];
        $shipments = $this->Shipment_og_model->GetCheckReturnFulfilstatus($slip_no);
        $ship_data = $shipments['result'][0];
        $error_data = array();
        $valid_data = array();
        if (!empty($ship_data)) {
            if (!empty($sku_data) && !empty($slip_no)) {
                $send_requestArr = array();
                $wbh_array = array();
                
                 $status_fm_picking=array(
                    'user_id'=>$this->session->userdata('user_details')['user_id'],
                    'slip_no'=>!empty($slip_no)?$slip_no:"No",
                    'request_p'=>json_encode($postData),
                    'entry_date'=>date("Y-m-d H:i:s"),
                     'type'=>'R',
                    'super_id'=>$this->session->userdata('user_details')['super_id']);
                $this->Shipment_og_model->picking_log($status_fm_picking);
                foreach ($sku_data as $row) {
                    if ($row['local_type'] == 'New') {
                        $inventory_arr = $this->Shipment_og_model->GetcheckLocation_new($row);
                    } else {
                        $inventory_arr = $this->Shipment_og_model->GetcheckLocation($row);
                    }
                    //echo $row['local_type'];
                   //print_r($inventory_arr); die;
                    $old_qty = $inventory_arr['quantity'];
                    $update_qty = $row['piece'];
                    $totalQTY_size_in = $old_qty + $update_qty;
                    $capacity = $row['sku_size'];
                    $stock_location = trim($row['stock_location']);
                    $shelve_no = $inventory_arr['shelve_no'];
                    $expity_date = $inventory_arr['expity_date'];
                    $sku = $row['sku'];
                    if ($totalQTY_size_in <= $capacity) {

                        $damage = $row['damage'];
                        $missing = $row['missing'];

                        $total_damage_qty = $damage + $missing;

                        if ($total_damage_qty <= $update_qty) {
                            if ($total_damage_qty > 0) {
                                $update_qty_new = $update_qty - $total_damage_qty;

                                $damage_iniventory = array(
                                    'item_sku' => $row['item_sku'],
                                    'quantity' => $total_damage_qty,
                                    'd_qty' => $damage,
                                    'm_qty' => $missing,
                                    'order_no' => $slip_no,
                                    'shelve_no' => !empty($shelve_no) ? $shelve_no : '',
                                    'stock_location' => !empty($row['stock_location']) ? $row['stock_location'] : '',
                                    'itype' => 'B2C',
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    'updated_by' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'update_date' => date("Y-m-d H:i:s"),
                                    'order_type' => 'shipment'
                                );
                                $this->Shipment_og_model->Getupdatedamage_inventory($damage_iniventory);
                            } else {
                                $update_qty_new = $update_qty;
                            }

                            if ($update_qty_new > 0) {
                                $mainStock = GetcheckMainStock($sku, $row['cust_id']);
                                if (empty($mainStock)) {
                                    $ins_data = array('sku' => $sku, "qty" => $update_qty_new, "seller_id" => $row['cust_id'], "super_id" => $this->session->userdata('user_details')['super_id']);
                                    $this->Stock_model->addcustomerInventory($ins_data);
                                    $type_data = "return";
                                    $act_qty = $update_qty_new;
                                } else {
                                    $oldqty = $mainStock['qty'] + $update_qty_new;
                                    $up_data = array("qty" => $oldqty);
                                    $this->Stock_model->upcustomerInventory($up_data, $mainStock);
                                    $type_data = "return";
                                    $act_qty = $oldqty;
                                }

                                $updateQty = $old_qty + $update_qty_new;
                               
                                
                                if ($row['local_type'] == 'New') {
                                     $updateArr = array("quantity" => $updateQty,'seller_id'=>$row['cust_id'],"item_sku" => $row['item_sku']);
                                    $updateArr_w = array("stock_location" => $stock_location, "id" => $inventory_arr['id']);
                                    
                                } else {
                                     $updateArr = array("quantity" => $updateQty);
                                    $updateArr_w = array("stock_location" => $stock_location, "seller_id" => $row['cust_id'], "item_sku" => $row['item_sku']);
                                }
                                $this->Shipment_og_model->GetupdateInventory($updateArr, $updateArr_w);

                                $inventory_activity_user = array(
                                    'item_sku' => $row['item_sku'],
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'qty' => $act_qty,
                                    'p_qty' => !empty($mainStock['qty']) ? $mainStock['qty'] : 0,
                                    'qty_used' => $update_qty_new,
                                    'type' => $type_data,
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'awb_no' => $slip_no,
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    "comment" => "RTF"
                                );
                                $this->Stock_model->inventory_activity_user($inventory_activity_user);
                                
                                $activitiesArr = array(
                                   'st_location' => addslashes($stock_location),
                                    'item_sku' => $row['item_sku'],
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'qty' => $updateQty,
                                    'p_qty' => $old_qty,
                                    'qty_used' => $update_qty_new,
                                    'type' => 'return',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    'awb_no' => $slip_no,
                                    'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                                    "comment" => "RTF Single"
                                );

                                $this->Stock_model->AddInventoryHistory($activitiesArr);
                                // echo $this->db->last_query();
                            }

                            //=======================missing damage history=======================//
                            if ($damage > 0) {

                                $inventory_activity_user_d = array(
                                    'item_sku' => $row['item_sku'],
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'qty' => 0,
                                    'p_qty' => 0,
                                    'qty_used' => $damage,
                                    'type' => 'Damage',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'awb_no' => $slip_no,
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    "comment" => "RTF Damage"
                                );
                                $this->Stock_model->inventory_activity_user($inventory_activity_user_d);
                                $activitiesArr_d = array(
                                  //  'exp_date' => $expity_date,
                                    'st_location' => $stock_location,
                                    'item_sku' => $row['item_sku'],
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'qty' => 0,
                                    'p_qty' => 0,
                                    'qty_used' => $damage,
                                    'type' => 'Damage',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    'awb_no' => $slip_no,
                                    'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                                    "comment" => "RTF Damage"
                                );
                                $this->Stock_model->AddInventoryHistory($activitiesArr_d);
                            }
                            if ($missing > 0) {
                                $inventory_activity_user_m = array(
                                    'item_sku' => $row['item_sku'],
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'qty' => 0,
                                    'p_qty' => 0,
                                    'qty_used' => $missing,
                                    'type' => 'Missing',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'awb_no' => $slip_no,
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    "comment" => "RTF Missing"
                                );
                                $this->Stock_model->inventory_activity_user($inventory_activity_user_m);
                                $activitiesArr_m = array(
                                    //'exp_date' => $expity_date,
                                    'st_location' => $stock_location,
                                    'item_sku' => $row['item_sku'],
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $row['cust_id'],
                                    'qty' => 0,
                                    'p_qty' => 0,
                                    'qty_used' => $missing,
                                    'type' => 'Missing',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    'awb_no' => $slip_no,
                                    'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                                    "comment" => "RTF Missing"
                                );
                                $this->Stock_model->AddInventoryHistory($activitiesArr_m);
                            }
                            $sellerDetails = GetSinglesellerdata($row['cust_id'], $this->session->userdata('user_details')['super_id']);
                            $zid_store_id = $sellerDetails['zid_sid'];
                            $token = $sellerDetails['manager_token'];
                            $salatoken = $sellerDetails['salla_athentication'];
                            //=============================================================================//
                            if (!empty($salatoken)) {


                                //==========update salla stock===============//
                                $sallaReqArr = GetAllQtyforSellerby_ID($fArray['item_sku'], $row['cust_id']);
                                $quantity = $sallaReqArr['quantity'] + $update_qty_new;
                                $pid = $sallaReqArr['sku'];
                                $sallatoken = $salatoken;
                                //echo "<pre>"; print_r($sallaReqArr); exit;

                                $reszid = update_salla_qty_product($quantity, $pid, $sallatoken);
                                //=========================================//
                            }

                            if (!empty($token)) {
                                //==========update zid stock===============//
                                $zidReqArr = GetAllQtyforSellerby_ID($fArray['item_sku'], $row['cust_id']);
                                //  print_r(  $zidReqArr);
                                $quantity = $zidReqArr['quantity'] + $update_qty_new;
                                $pid = $zidReqArr['zid_pid'];
                                $token = $token;
                                $storeID = $zid_store_id;
                                update_zid_product($quantity, $pid, $token, $storeID);

                                //=========================================//
                            }
                        }
                    } else {
                        array_push($error_data, $row);
                    }
                }

                if (empty($error_data)) {
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
                            'order_id' => $ship_data['booking_id'],
                            'status' => $Status_WC,
                            'status_des' => "Return By Diggipacks",
                        );
                        array_push($send_requestArr, $data_wc);
                    }


                    //==================webhook===================//
                    $WB_Confing = webhook_settingsTable($ship_data['cust_id']);
                    if ($WB_Confing['subscribe'] == 'Y') {
                        $wb_request = array(
                            'datetime' => date('Y-m-d H:i:s'),
                            "code" => 'RTC',
                            "status" => 'Order Return',
                            "cc_name" => GetCourCompanynameId($ship_data['frwd_company_id'], 'company'),
                            "cc_awb" => $ship_data['frwd_company_awb'],
                            "cc_status" => null,
                            "cc_status_details" => null,
                            "slip_no" => $ship_data['slip_no'],
                            "booking_id" => $ship_data['booking_id'],
                            "cust_id" => $ship_data['cust_id'],
                            "WB_Confing" => $WB_Confing
                        );
                        array_push($wbh_array, $wb_request);
                    }

                    $statusvalue['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $statusvalue['user_type'] = 'fulfillment';
                    $statusvalue['slip_no'] = $slip_no;
                    $statusvalue['new_status'] = 8;
                    $statusvalue['code'] = 'RTC';
                    if (!empty($postData['remarkbox'])) {
                        $statusvalue['comment'] = $postData['remarkbox'];
                    }
                    $statusvalue['Activites'] = 'Return';
                    $statusvalue['Details'] = 'Order Return, Update By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                    $statusvalue['entry_date'] = date('Y-m-d H:i:s');
                    $statusvalue['super_id'] = $this->session->userdata('user_details')['super_id'];

                    $updateArray = array('code' => 'RTC', 'delivered' => 8, 'close_date' => date('Y-m-d H:i:s'));

                    $req_data=$this->Shipment_og_model->GetUpdateShipment_rtf($updateArray, $slip_no);
                    $this->Shipment_og_model->updateStatus($statusvalue);
                    $query_log=array("awb"=>$slip_no,"q_data"=>$req_data,"super_id"=>$this->session->userdata('user_details')['super_id']);
                    $this->Shipment_og_model->Query_log($query_log);

                    if (!empty($token)) {
                        if (!empty($ship_data['frwd_company_awb'])) {
                            $trackingurl = makeTrackUrl($ship_data['frwd_company_id'], $ship_data['frwd_company_awb']);
                            $lable = $ship_data['frwd_company_label'];
                        } else {
                            $lable = 'https://api.diggipacks.com/API/print/' . $ship_data['slip_no'];
                        }
                        $trackingurl = TRACKURL . $slip_no;
                        //updateZidStatus($orderID=null, $token=null, $status=null, $code=null, $label=null, $trackingurl=null)
                        updateZidStatus($ship_data['booking_id'], $token, 'cancelled', $slip_no, $lable, $trackingurl, $ship_data['cust_id']);
                    }
                    if (!empty($send_requestArr)) {
                        $this->session->set_userdata(array('wc_status_req' => $send_requestArr));
                    }


                    if (!empty($wbh_array)) {
                        $this->session->set_userdata(array('webhook_status' => $wbh_array));
                    }
                }
            }
        }
        echo json_encode($error_data);
    }

    private function GetWC_status($data = array(), $cod = null) {
        foreach ($data as $key => $val) {
            if ($val->code == $cod) {
                return $val->wc_status;
            }
        }
    }

    public function showall($slip_no = null) {
        $data['slip_no'] = $slip_no;
        $this->load->view('pickup/links', $data);
    }

    public function bulk_location($slip_no = null) {
        $data['slip_no'] = $slip_no;
        $this->load->view('stocks/bulk_location', $data);
    }

    public function show_manifest($slip_no = null) {
        $data['slip_no'] = $slip_no;
        $this->load->view('manifest/menifestlist_assign_new', $data);
    }
    
      public function runshellbackorder() {

        $url = base_url('Backorder_new/checkBackOrders/' . $this->session->userdata('user_details')['super_id']);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        echo $response = curl_exec($curl);

        curl_close($curl);
    }

}

?>