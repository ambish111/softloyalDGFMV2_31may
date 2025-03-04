<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CourierCompany extends MY_Controller
{
    function __construct()
    {
        // error_reporting(-1);
        // ini_set('display_errors', 1);
        parent::__construct();
        if (menuIdExitsInPrivilageArray(22) == 'N') {
            //redirect(base_url().'notfound'); die;
        }

        $this->load->model('Ccompany_model');
        $this->load->model('Shipment_model');
        $this->load->model('ItemInventory_model');
        $this->load->model('Seller_model');
        $this->load->library('form_validation');
        $this->load->model('General_model');
    }


    public function cCompany()
    {
        //unlink('assets/all_labels/DGF18106238561.pdf');
        //         $username = "fwrun";
        // $password = "}9h$=bRS$OWe1";
        // //echo 'grant_type=password&username='.$username.'&password='.$password;die;
        // $curl = curl_init();

        //         curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'http://41.33.122.61:8888/MylerzIntegrationStaging/token',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         //CURLOPT_SSL_VERIFYHOST=>0,
        //         //CURLOPT_SSL_VERIFYPEER=>0,
        //         CURLOPT_POSTFIELDS => 'grant_type=password&username='.$username.'&password=}9h$=bRS$OWe1',
        //         CURLOPT_HTTPHEADER => array(
        //             'Content-Type: application/x-www-form-urlencoded',
        //             'Cookie: .AspNet.Cookies=oULfDaCQI0rE81rS2OztApqfljQYkOQTRvh-IKtdmmHBu6DjOOy1J-vp0kiQJmwTk_3bjkvHmobrkCHTL1gQt2gnHGwk2zy3MMeYsGO1UPzW6yjKV2ipAd9Ls9LbctisKrEHsGCetHF85A7pdGeI6Y2L68sE0e5b2Lt6ZRDAUPREKdi5Jl9UONIb_zahiWHI0DbmYByDpLWhoXDFhDoaMvRz7SNYvTEYpRCJ_LyCJ54tkHqOSdCliL_ZwrXIMCAxNhWckJnR9fDGS_gLJjeF1vH3kvBZ8hlO_86rqLbVMtvnciapu_WIj9AnGRH75Nou88LeyOAJQg-g8mDsds9g_Yuac7M9WtFl7K9eP2GFq2J6Dp9gezBIWsTKHKIyNLu6J3T2GPPDTFoaHqpQXYGWVbcCrKSBuXjRjLuOTTLE8GtDkzWfGBP1pAJQNmJKYMH0YrABl7WlNOPlkMuFSRgEoQ'
        //         ),
        //         ));

        //         $response = curl_exec($curl); 
        //         //echo "Error=".curl_error();die;
        //         echo $response;die;
        //         curl_close($curl);
        $this->load->view('courierCompany/view_company');
    }


    public function forwardshipments()
    {
        $data['sellers'] = $this->Seller_model->find2();
        $this->load->view("ShipmentM/forward_shipments", $data);
    }


    public function forwardedshipments()
    {
        $this->load->view("ShipmentM/forwarded_shipments");
    }

    public function GetWarehouselistDrop()
    {
        $return=$this->Ccompany_model->GetWarehouselistDrop();
        echo json_encode($return);
    }

    public function GetCompanylistDrop()
    {

        $fp_partner =  site_configTable('fastcoo_partner_status', $this->session->userdata('user_details')['super_id']);
        if ($fp_partner == 'Y') {
            $return = $this->Ccompany_model->GetCompanylistDropQryFPFlag();
        } else {
            $return = $this->Ccompany_model->GetCompanylistDropQry();
        }
        //$return=$this->Ccompany_model->GetCompanylistDropQry();
        echo json_encode($return);
    }

    public function GetshowcompanyList()
    {
        $postArr = json_decode(file_get_contents('php://input'), true);
        $return = $this->Ccompany_model->all();
        echo json_encode($return);
    }
   
    public function GetshowcompanySeller()
    {
        $postArr = json_decode(file_get_contents('php://input'), true);
        $return = $this->Ccompany_model->all_ccSeller($postArr['id']);
        echo json_encode($return);
    }

    public function forwardedfilter()
    {
        //print("heelo"); 
        //exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        $page_no = $_POST['page_no'];
        $awb = $_POST['s_type_val'];
        $warehouse = $_POST['warehouse'];
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];
        $forwarded_type = $_POST['forwarded_type'];
        $mode = isset($_POST['mode']) ? $_POST['mode'] : '';
        $sku = isset($_POST['sku_val']) ? $_POST['sku_val'] : '';
        $booking_id = isset($_POST['booking_id']) ? $_POST['booking_id'] : '';
        $seller_id = isset($_POST['seller']) ? $_POST['seller'] : '';
        $typeship = isset($_POST['typeship']) ? $_POST['typeship'] : '';

        $shipments = $this->Ccompany_model->forwardshfilter($awb, $warehouse, $origin, $destination, $forwarded_type, $mode, $sku, $booking_id, $page_no, $seller_id,$typeship);

        //echo '<pre>';
        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
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
        foreach ($shipments['result'] as $rdata) {


            if ($rdata['order_type'] == '') {
                $itemID = getallitemskubyid($rdata['sku']);
                $itemtypes = getalldataitemtables($itemID, 'type');
                $shiparray[$ii]['order_type'] = $itemtypes;

                //$shiparray[$ii]['order_type']="";
            } else
                $shiparray[$ii]['order_type'] = $rdata['order_type'];
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            //$shiparray[$ii]['expire_details'] = $expire_data;
            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            $shiparray[$ii]['sku'] = $rdata['sku'];
            $shiparray[$ii]['mode'] = $rdata['mode'];

            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
        }




        //echo '<pre>';
        //print_r($shiparray);
        //echo json_encode($shiparray);
        // die;
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }


    public function GetCompanyChnagesSave()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $RequestArr = $_POST;
        $UpdateArr = array(
            'user_name' => $RequestArr['user_name'],
            'company_url' => $RequestArr['company_url'],
            'password' => $RequestArr['password'],
            'courier_account_no' => $RequestArr['courier_account_no'],
            'courier_pin_no' => $RequestArr['courier_pin_no'],
            'start_awb_sequence' => $RequestArr['start_awb_sequence'],
            'end_awb_sequence' => $RequestArr['end_awb_sequence'],
            'auth_token' => $RequestArr['auth_token'],
            'api_url' => $RequestArr['api_url'],
            'user_name_t' => $RequestArr['user_name_t'],
            'password_t' => $RequestArr['password_t'],
            'courier_account_no_t' => $RequestArr['courier_account_no_t'],
            'courier_pin_no_t' => $RequestArr['courier_pin_no_t'],
            'start_awb_sequence_t' => $RequestArr['start_awb_sequence_t'],
            'end_awb_sequence_t' => $RequestArr['end_awb_sequence_t'],
            'auth_token_t' => $RequestArr['auth_token_t'],
            'api_url_t' => $RequestArr['api_url_t'],
            'customer_care' => $RequestArr['customer_care'],
            'account_entity_code' => $RequestArr['account_entity_code'],
            'account_entity_code_t' => $RequestArr['account_entity_code_t'],
            'account_country_code' => $RequestArr['account_country_code'],
            'account_country_code_t' => $RequestArr['account_country_code_t'],
            'service_code' => $RequestArr['service_code'],
            'service_code_t' => $RequestArr['service_code_t'],
            'wharehouse_flag' => $RequestArr['wharehouse_flag'],
            'webhook_status'  => $RequestArr['webhook_status'],
        );

        if (!empty($RequestArr['webhook_status'])) {
            $id = $RequestArr['cc_id'];
            $status = $RequestArr['webhook_status'];
            //  print_r($status);die;
            $return = $this->Ccompany_model->webhookstatus_Insert($id, $status);
        }
        $UpdateArr_w = array('id' => $RequestArr['id']);

        $return = $this->Ccompany_model->GetUpdateDeliveryCOmpany($UpdateArr, $UpdateArr_w);
        echo json_encode($return);
    }

    public function GetCompanyChnagesSaveSeller()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $RequestArr = $_POST;
        $UpdateArr = array(
            'user_name' => $RequestArr['user_name'],
            'company_url' => $RequestArr['company_url'],
            'password' => $RequestArr['password'],
            'courier_account_no' => $RequestArr['courier_account_no'],
            'courier_pin_no' => $RequestArr['courier_pin_no'],
            'start_awb_sequence' => $RequestArr['start_awb_sequence'],
            'end_awb_sequence' => $RequestArr['end_awb_sequence'],
            'auth_token' => $RequestArr['auth_token'],
            'api_url' => $RequestArr['api_url'],
            'user_name_t' => $RequestArr['user_name_t'],
            'password_t' => $RequestArr['password_t'],
            'courier_account_no_t' => $RequestArr['courier_account_no_t'],
            'courier_pin_no_t' => $RequestArr['courier_pin_no_t'],
            'start_awb_sequence_t' => $RequestArr['start_awb_sequence_t'],
            'end_awb_sequence_t' => $RequestArr['end_awb_sequence_t'],
            'auth_token_t' => $RequestArr['auth_token_t'],
            'api_url_t' => $RequestArr['api_url_t'],
            'customer_care' => $RequestArr['customer_care'],
        );
        $UpdateArr_w = array('id' => $RequestArr['id']);

        $return = $this->Ccompany_model->GetUpdateDeliveryCOmpanySeller($UpdateArr, $UpdateArr_w);
        echo json_encode($return);
    }


    public function GetUpdateActiveStatus()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = array('status' => $_POST['status']);
        $data_w = array('id' => $_POST['id']);
        $return = $this->Ccompany_model->GetUpdateDeliveryCOmpany($data, $data_w);
        echo json_encode($return);
    }


    public function GetUpdateActiveStatusSeller()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = array('status' => $_POST['status']);
        $data_w = array('id' => $_POST['id']);
        $return = $this->Ccompany_model->GetUpdateDeliveryCOmpanySeller($data, $data_w);
        echo json_encode($return);
    }

    public function GetUpdateLIveStatus()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = array('type' => $_POST['status']);
        $data_w = array('id' => $_POST['id']);
        $return = $this->Ccompany_model->GetUpdateDeliveryCOmpany($data, $data_w);
        echo json_encode($return);
    }

    public function GetUpdateLIveStatusSeller()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = array('type' => $_POST['status']);
        $data_w = array('id' => $_POST['id']);
        $return = $this->Ccompany_model->GetUpdateDeliveryCOmpanySeller($data, $data_w);
        echo json_encode($return);
    }

    //============3pl order calcel=============//
    public function GetCanelBplOrder()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        if (!empty($_POST['slip_no'])) {
            $slipArray = array('forwarded' => 0);
            $this->Ccompany_model->GetshipmentUpdate_forward($slipArray, $_POST['slip_no']);
            $return = true;
        } else
            $return = true;
        echo json_encode($return);
    }

    //=========================================//

    public function BulkForwardCompanyReady()
    {
        //   echo "khkhkh"; die; 
        $postData = json_decode(file_get_contents('php://input'), true);
        // print "<pre>"; print_r($postData);die;
        $shipmentLoopArray[] = $postData['slip_no'];
        $super_id = $postData['super_id'];
        $courier_id = $postData['cc_id'];
        $warehouse_id = $postData['id'];
        $CURRENT_TIME = date('H:i:s');
        $CURRENT_DATE = date('Y-m-d H:i:s');
        $box_pieces1 = $postData['box_pieces'];
        $comment = $postData['comment'];
        // print_r($postData); die;

        $CURRENT_TIME = date('H:i:s');
        $CURRENT_DATE = date('Y-m-d H:i:s');

        if (!empty($postData['super_id'])) {

            $user_details['super_id'] = $postData['super_id'];
            $this->session->set_userdata('user_details', $user_details);
            $shipmentLoopArray[] = $postData['slip_no'];

            $super_id = $postData['super_id'];
            $CURRENT_TIME = date('H:i:s');
            $CURRENT_DATE = date('Y-m-d H:i:s');
            //  print_r($shipmentLoopArray);exit; 
        } else {
            $super_id = $this->session->userdata('user_details')['super_id'];
            if (!empty($postData['slip_arr']) && !empty($postData['otherArr'])) 
            {
                $shipmentLoopArray = $postData['slip_arr'];
                $postData['cc_id'] = $postData['otherArr']['cc_id'];
                $postData['id'] = $postData['otherArr']['id'];

            } else {
                $slipData = explode("\n", $postData['slip_no']);
                $shipmentLoopArray = array_unique($slipData);
            }
        }
        //  print_r($shipmentLoopArray);exit; 
        $invalid_slipNO = array();
        $succssArray = array();
        if ($postData['comment'] != '') {
            $comment = $postData['comment'];
        } else {
            $comment = '';
        }

        $open_package_flag = $postData['open_package_flag'];

        if (!empty($shipmentLoopArray)) {

            if (!empty($postData)) {


                $box_pieces = $postData['otherArr']['box_pieces'];
                $box_pieces1 = $postData['box_pieces'];

                foreach ($shipmentLoopArray as $key => $slipNo) {
                    // print_r($shipmentLoopArray);exit; 


                    $ShipArr = $this->Ccompany_model->GetSlipNoDetailsQry(trim($slipNo), $super_id);
                    //  print "<pre>"; print_r($ShipArr); print "<hr>";die;
                    if (!empty($postData['cc_id'])) {
                        $courier_id = $postData['cc_id'];
                    } else {

                        $courier_data = $this->forwardShipment($postData['slip_no'], $super_id);
                        // print "<pre>"; print_r($courier_data);die;
                        //  echo json_encode($super_id);die;
                        $courier_id = $courier_data[0]['cc_id'];
                        $zone_id = $courier_data[0]['id'];
                        $zone_cust_id = $courier_data[0]['cust_id'];
                        //  print "<pre>"; print_r($courier_data);die; 
                    }



                    $ShipArr_custid =  $ShipArr['cust_id'];
                    $fp_status = site_configTable('fastcoo_partner_status');

                    $counrierArr_table = $this->Ccompany_model->GetdeliveryCompanyUpdateQry($courier_id, $ShipArr_custid, $super_id, $fp_status);
                    //   print "<pre>";print_r($counrierArr_table);die;
                    $c_id = $counrierArr_table['cc_id'];
                    $cc_id = $counrierArr_table['cc_id'];

                    if ($counrierArr_table['type'] == 'test') {
                        $user_name = $counrierArr_table['user_name_t'];
                        $password = $counrierArr_table['password_t'];
                        $courier_account_no = $counrierArr_table['courier_account_no_t'];
                        $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
                        $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
                        $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
                        $company = $counrierArr_table['company'];
                        $api_url = $counrierArr_table['api_url_t'];
                        $company_type  = $counrierArr_table['company_type'];
                        $create_order_url = $counrierArr_table['create_order_url'];
                        $auth_token = $counrierArr_table['auth_token_t'];
                        $account_entity_code = $counrierArr_table['account_entity_code_t'];
                        $account_country_code = $counrierArr_table['account_country_code_t'];
                        $service_code = $counrierArr_table['service_code_t'];
                        $ProductType = $counrierArr_table['ProductType_t'];
                        $ProductGroup = $counrierArr_table['ProductGroup_t'];
                        $aramex_payment_type = $counrierArr_table['aramex_payment_type_t'];

                    } else {
                        $user_name = $counrierArr_table['user_name'];
                        $password = $counrierArr_table['password'];
                        $courier_account_no = $counrierArr_table['courier_account_no'];
                        $courier_pin_no = $counrierArr_table['courier_pin_no'];
                        $start_awb_sequence = $counrierArr_table['start_awb_sequence'];
                        $end_awb_sequence = $counrierArr_table['end_awb_sequence'];
                        $company = $counrierArr_table['company'];
                        $api_url = $counrierArr_table['api_url'];
                        $auth_token = $counrierArr_table['auth_token'];
                        $company_type  = $counrierArr_table['company_type'];
                        $create_order_url = $counrierArr_table['create_order_url'];
                        $account_entity_code = $counrierArr_table['account_entity_code'];
                        $account_country_code = $counrierArr_table['account_country_code'];
                        $service_code = $counrierArr_table['service_code'];
                        $ProductType = $counrierArr_table['ProductType'];
                        $ProductGroup = $counrierArr_table['ProductGroup'];
                        $aramex_payment_type = $counrierArr_table['aramex_payment_type'];
                    }

                   
                    $counrierArr['user_name'] = $user_name;
                    $counrierArr['password'] = $password;
                    $counrierArr['courier_account_no'] = $courier_account_no;
                    $counrierArr['courier_pin_no'] = $courier_pin_no;
                    $counrierArr['courier_pin_no'] = $courier_pin_no;
                    $counrierArr['start_awb_sequence'] = $start_awb_sequence;
                    $counrierArr['end_awb_sequence'] = $end_awb_sequence;
                    $counrierArr['company'] = $company;
                    $counrierArr['api_url'] = $api_url;
                    $counrierArr['create_order_url'] = $create_order_url;
                    $counrierArr['company_type'] = $company_type;
                    $counrierArr['auth_token'] = $auth_token;
                    $counrierArr['type'] = $counrierArr_table['type'];
                    $super_id = $ShipArr['super_id'];
                    $counrierArr['account_entity_code'] = $account_entity_code;
                    $counrierArr['account_country_code'] = $account_country_code;
                    $counrierArr['service_code'] = $service_code;
                    $counrierArr['fp_status'] = $counrierArr_table['fp_flag'];
                    $counrierArr['fp_partner_type'] = $counrierArr_table['fp_partner_type'];
                    $counrierArr['wharehouse_flag'] = $counrierArr_table['wharehouse_flag'];
                    $counrierArr['ProductType']=  $ProductType;
                    $counrierArr['ProductGroup']= $ProductGroup ;
                    $counrierArr['aramex_payment_type']= $aramex_payment_type;



                    //    echo "<pre>"; print_r($ShipArr); die; 


                    if (!empty($ShipArr)) {
                        $sku_data = $this->Ccompany_model->Getskudetails_forward($slipNo);
                        $sku_all_names = array();
                        $sku_total = 0;
                        $total_weight = 0;
                        $totalcustomerAmt = 0;
                        foreach ($sku_data as $key => $val) {
                            $totalcustomerAmt += $sku_data[$key]['cod'];
                            $skunames_quantity = $sku_data[$key]['name'] . "/ Qty:" . $sku_data[$key]['piece'];
                            $sku_total = $sku_total + $sku_data[$key]['piece'];
                            $total_weight += ($sku_data[$key]['weight'] * $sku_data[$key]['piece']);

                            array_push($sku_all_names, $skunames_quantity);
                        }

                        //   echo  " total_weight = ".$total_weight;  die ;
                        // echo  " <pre> = "; print_r($sku_data);  die ;
                        $sku_all_names = implode(",", $sku_all_names);
                        if ($sku_total != 0) {
                            $complete_sku = $sku_all_names;
                        } else {
                            $complete_sku = $sku_all_names;
                        }
                        $pay_mode = trim($ShipArr['mode']);
                        $cod_amount = $ShipArr['total_cod_amt'];
                        if ($pay_mode == 'COD') {
                            //   $pay_mode = 'P';
                            $CashOnDeliveryAmount = array(
                                "Value" => $cod_amount,
                                "CurrencyCode" => site_configTableSuper_id("default_currency", $super_id)
                            );
                            $services = 'CODS';
                        } elseif ($pay_mode == 'CC') {
                            //$pay_mode = 'P';
                            $CashOnDeliveryAmount = NULL;
                            $services = '';
                        }


                        if ($total_weight > 0) {
                            $weight = $total_weight;
                        } else {
                            $weight = 1;
                        }



                        $CURRENT_TIME = date('H:i:s');
                        $CURRENT_DATE = date('Y-m-d H:i:s');
                        $sender_default_city = Getselletdetails_new($super_id);
                        $sender_origin = $sender_default_city['0']['branch_location'];

                        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                        //$sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company'); 

                        // $sellername = site_configTableSuper_id("company_name",$super_id);

                        // $sellername = site_configTableSuper_id("company_name",$super_id);

                        $sender_address = site_configTableSuper_id("company_address", $super_id);

                        if ($ShipArr['cust_id'] == 193) {
                            $sender_phone =  GetallCutomerBysellerId($ShipArr['cust_id'], 'phone');
                        } else {
                            $sender_phone =  site_configTableSuper_id("phone", $super_id);
                        }

                        $store_name = GetallCutomerBysellerId($ShipArr['cust_id'], 'company');

                        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'], 'company') . ' - ' . site_configTableSuper_id("company_name", $super_id);


                        if ($super_id == 20) {
                            // if($super_id == 20  ||  $super_id == 54 ){ 

                            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'], 'company');
                            $sender_phone =  GetallCutomerBysellerId($ShipArr['cust_id'], 'phone');
                        }
 
                        if($super_id == 333)
                        {  
                            $warehouse_id = $postData['id'];
                            $wh_address =  $this->Ccompany_model->Warehouse_field($warehouse_id); 
                            $wh_city = json_decode($wh_address['city_id'], true);                    
                            $sender_address =  $wh_address['wh_address'] ;
                            $sender_origin = $wh_city[0];
//                            echo "ciyt=".$sender_origin;die;
                            $sender_phone = $ShipArr['sender_phone'];
                        } 
                        else 
                        {
                            $sender_address =  $sender_address;
                            $sender_origin = $sender_origin; 
                        }



                        if ($super_id == 271) {
                            $sellername = $ShipArr['sender_name'];
                        }




                        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');     

                        //   echo $sellername;  die;

                        $ShipArr = array(
                            'sender_name' =>  $sellername,
                            'store_name' =>  $store_name,
                            'sender_address' => $sender_address,
                            'sender_phone' =>  $sender_phone,
                            'sender_email' =>  $ShipArr['sender_email'],
                            'origin' => $sender_origin,
                            'slip_no' => $ShipArr['slip_no'],
                            'mode' => $pay_mode,
                            'area_name' => $ShipArr['area_name'],
                            'pay_mode' => $ShipArr['mode'],
                            'total_cod_amt' => $ShipArr['total_cod_amt'],
                            'pieces' =>  $box_pieces1,
                            'status_describtion' => empty($complete_sku) ? $ShipArr['status_describtion'] : $complete_sku,
                            'status_descr' => $ShipArr['status_describtion'],
                            'weight' => $weight,
                            'shippers_ac_no' => $ShipArr['shippers_ac_no'],
                            'cust_id' => $ShipArr['cust_id'],
                            'service_id' => $ShipArr['service'],
                            'reciever_name' => $ShipArr['reciever_name'],
                            'reciever_pincode' => $ShipArr['reciever_pincode'],
                            'reciever_address' =>   $ShipArr['reciever_address'],
                            'reciever_phone' => preg_replace('/\s+/', '', $ShipArr['reciever_phone']),
                            'reciever_email' => $ShipArr['reciever_email'],
                            'destination' => $ShipArr['destination'],
                            'sku' => $ShipArr['sku'],
                            'booking_id' => $ShipArr['booking_id'],
                            'promise_deliver_date' => $ShipArr['promise_deliver_date'],
                            'sku_data' => $sku_data,
                            'pack_type' => $ShipArr['pack_type'],
                            'comment' => $comment,
                            'shipment_seller_name' => $ShipArr['sender_name'],
                            'label_sender_name' => $ShipArr['label_sender_name']
                        );
                        //  print_r($ShipArr);die;
                        $imileordertype = 100;
                        $reciever_city = getdestinationfieldshow($ShipArr['destination'], 'city', $super_id);
                        $Restricted_city = Restrictedcity($c_id, $super_id);
                        // print_r($Restricted_city);die;
                        $Restricted = json_decode($Restricted_city);
                        // print_r($Restricted);die;
                        if (in_array($ShipArr['destination'], $Restricted, TRUE)) {
                            $returnArr['responseError'][] = $slipNo . ': Receiver city is in Restricted city zone';
                            $company = '';
                            $successstatus = "Fail";
                            $logresponse = $reciever_city . " Receiver city is in Restricted city zone";
                            $this->Ccompany_model->shipmentLog($c_id, $logresponse, $successstatus, $ShipArr['slip_no'],'');
                        }

                        // echo $company;die;
                        $ccRetrundata = $this->courierComanyForward($sellername, $Auth_token, $company, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1, $super_id, $company_type, $c_id, $api_url, $imileordertype, $open_package_flag);

                        if ($ccRetrundata['status'] == 200) {

                            $cc_fp_flag = $counrierArr['fp_status'];
                            $torod_order_id = $ccRetrundata['torod_order_id'];

                            $Update_data = $this->Ccompany_model->Update_Shipment_Status($slipNo, $ccRetrundata['client_awb'], $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $ccRetrundata['label'], $c_id, $ccRetrundata['barq_order_id'], $api_url, $ccRetrundata['bosta_label_id'], $cc_fp_flag, $counrierArr['fp_partner_type'], $torod_order_id);

                            if (!empty($zone_id)) {
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id, $zone_id, $super_id);
                            }

                            array_push($succssArray, $slipNo);
                        } else {
                            //echo'<pre>'; print_r($ccRetrundata);
                            $returnArr['responseError'][] = $ccRetrundata['error']['responseError'];
                        }
                    } else {
                        //echo'<pre>'; print_r($ccRetrundata);
                        //$returnArr['responseError'][]= "Shipment id already forwarded  in another courier company";
                        $returnArr['responseError'][] = "Invalid Status";
                    }
                }
            }
        }
        $return['invalid_slipNO'] = $invalid_slipNO;
        $return['Error_msg'] = $returnArr['responseError'];
        //$return['Success_msg']=$returnArr['successAwb'];
        $return['Success_msg'] = $succssArray;

        echo json_encode($return);
    }

    public function BulkForwardCompanyReverse()
    {


        $postData = json_decode(file_get_contents('php://input'), true);
        $CURRENT_TIME = date('H:i:s');
        $CURRENT_DATE = date('Y-m-d H:i:s');

        if (!empty($postData['super_id'])) {

            $user_details['super_id'] = $postData['super_id'];
            $this->session->set_userdata('user_details', $user_details);
            $shipmentLoopArray[] = $postData['slip_no'];

            $super_id = $postData['super_id'];
            $CURRENT_TIME = date('H:i:s');
            $CURRENT_DATE = date('Y-m-d H:i:s');
        } else {
            $super_id = $this->session->userdata('user_details')['super_id'];

            if (!empty($postData['slip_arr']) && !empty($postData['otherArr'])) {
                $shipmentLoopArray = $postData['slip_arr'];
                $postData['cc_id'] = $postData['otherArr']['cc_id'];
            } else {
                $slipData = explode("\n", $postData['slip_no']);
                $shipmentLoopArray = array_unique($slipData);
            }
        }
        //print_r($shipmentLoopArray);exit; 
        $invalid_slipNO = array();
        $succssArray = array();
        if ($postData['comment'] != '') {
            $comment = $postData['comment'];
        } else {
            $comment = '';
        }

        if (!empty($shipmentLoopArray)) {

            if (!empty($postData)) {
                $box_pieces = $postData['otherArr']['box_pieces'];
                $box_pieces1 = $postData['box_pieces'];

                foreach ($shipmentLoopArray as $key => $slipNo) {


                    $ShipArr = $this->Ccompany_model->GetSlipNoDetailsReverse(trim($slipNo), $super_id);
                    //  print_r($ShipArr);exit; 
                    if (!empty($postData['cc_id'])) {
                        $courier_id = $postData['cc_id'];
                    } else {
                        $courier_data = $this->forwardShipment($postData['slip_no'], $super_id);
                        $courier_id = $courier_data[0]['cc_id'];
                        $zone_id = $courier_data[0]['id'];
                        $zone_cust_id = $courier_data[0]['cust_id'];
                    }
                    $ShipArr_custid =  $ShipArr['cust_id'];
                    $counrierArr_table = $this->Ccompany_model->GetdeliveryCompanyUpdateQry($courier_id, $ShipArr_custid, $super_id);
                    $c_id = $counrierArr_table['cc_id'];
                    //$cc_id = $counrierArr_table['cc_id'];
                    if ($counrierArr_table['type'] == 'test') {
                        $user_name = $counrierArr_table['user_name_t'];
                        $password = $counrierArr_table['password_t'];
                        $courier_account_no = $counrierArr_table['courier_account_no_t'];
                        $courier_pin_no = $counrierArr_table['courier_pin_no_t'];
                        $start_awb_sequence = $counrierArr_table['start_awb_sequence_t'];
                        $end_awb_sequence = $counrierArr_table['end_awb_sequence_t'];
                        $company = $counrierArr_table['company'];
                        $api_url = $counrierArr_table['api_url_t'];
                        $company_type  = $counrierArr_table['company_type'];
                        $create_order_url = $counrierArr_table['create_order_url'];
                        $auth_token = $counrierArr_table['auth_token_t'];
                    } else {
                        $user_name = $counrierArr_table['user_name'];
                        $password = $counrierArr_table['password'];
                        $courier_account_no = $counrierArr_table['courier_account_no'];
                        $courier_pin_no = $counrierArr_table['courier_pin_no'];
                        $start_awb_sequence = $counrierArr_table['start_awb_sequence'];
                        $end_awb_sequence = $counrierArr_table['end_awb_sequence'];
                        $company = $counrierArr_table['company'];
                        $api_url = $counrierArr_table['api_url'];
                        $auth_token = $counrierArr_table['auth_token'];
                        $company_type  = $counrierArr_table['company_type'];
                        $create_order_url = $counrierArr_table['create_order_url'];
                    }
                    $counrierArr['user_name'] = $user_name;
                    $counrierArr['password'] = $password;
                    $counrierArr['courier_account_no'] = $courier_account_no;
                    $counrierArr['courier_pin_no'] = $courier_pin_no;
                    $counrierArr['courier_pin_no'] = $courier_pin_no;
                    $counrierArr['start_awb_sequence'] = $start_awb_sequence;
                    $counrierArr['end_awb_sequence'] = $end_awb_sequence;
                    $counrierArr['company'] = $company;
                    $counrierArr['api_url'] = $api_url;
                    $counrierArr['create_order_url'] = $create_order_url;
                    $counrierArr['company_type'] = $company_type;
                    $counrierArr['auth_token'] = $auth_token;
                    $super_id = $ShipArr['super_id'];
                    $slip_no = $ShipArr['slip_no'];

                    //echo "<pre>"; print_r($ShipArr); die; 

                    if (!empty($ShipArr))
                    //if(!empty($ShipArr) &&($ShipArr['code']=='POD'))
                    {


                        $sku_data = $this->Ccompany_model->Getskudetails_forward($slipNo);
                        $sku_all_names = array();
                        $sku_total = 0;
                        $total_weight = 0;
                        $totalcustomerAmt = 0;
                        foreach ($sku_data as $key => $val) {
                            $totalcustomerAmt += $sku_data[$key]['cod'];
                            $skunames_quantity = $sku_data[$key]['name'] . "/ Qty:" . $sku_data[$key]['piece'];
                            $sku_total = $sku_total + $sku_data[$key]['piece'];
                            $total_weight += ($sku_data[$key]['weight'] * $sku_data[$key]['piece']);
                            array_push($sku_all_names, $skunames_quantity);
                        }
                        $sku_all_names = implode(",", $sku_all_names);
                        if ($sku_total != 0) {
                            $complete_sku = $sku_all_names;
                        } else {
                            $complete_sku = $sku_all_names;
                        }
                        $pay_mode = 'CC';
                        $cod_amount = 0;
                        if ($pay_mode == 'COD') {
                            // $pay_mode = 'P';
                            $CashOnDeliveryAmount = array(
                                "Value" => $cod_amount,
                                "CurrencyCode" => site_configTableSuper_id("default_currency", $super_id)
                            );
                            $services = 'CODS';
                        } elseif ($pay_mode == 'CC') {
                            // $pay_mode = 'P';
                            $CashOnDeliveryAmount = NULL;
                            $services = '';
                        }
                        $ShipArr['mode'] = 'CC';
                        $ShipArr['total_cod_amt'] = 0;
                        $new_awb_number = $this->Ccompany_model->Generate_awb_number_new_fm($super_id);
                        $sellername = $ShipArr['reciever_name'];
                        $recDetail = Getselletdetails_new($super_id);

                        $receiver_name = site_configTableSuper_id("company_name", $super_id);
                        $receiver_address = site_configTableSuper_id("company_address", $super_id);
                        $receiver_mobile =  site_configTableSuper_id("phone", $super_id);
                        if ($total_weight > 0) {
                            $weight = $total_weight;
                        } else {
                            $weight = 1;
                        }

                        $ShipArr = array(
                            'sender_name' =>  $ShipArr['reciever_name'],
                            'sender_address' =>  $ShipArr['reciever_address'],
                            'sender_phone' =>  $ShipArr['reciever_phone'],
                            'sender_email' =>  $ShipArr['reciever_email'],
                            'origin' => $ShipArr['destination'],
                            'slip_no' => $new_awb_number,
                            'mode' => $ShipArr['mode'],
                            'pay_mode' => $ShipArr['mode'],
                            'total_cod_amt' => 0, //$ShipArr['total_cod_amt'],
                            'pieces' =>  $box_pieces1,
                            'status_describtion' => $complete_sku,
                            'weight' => $weight, //$ShipArr['weight'],
                            'shippers_ac_no' => $ShipArr['shippers_ac_no'],
                            'cust_id' => $ShipArr['cust_id'],
                            'service_id' => $ShipArr['service'],
                            'reciever_name' => $receiver_name,
                            'reciever_address' => $receiver_address,
                            'reciever_phone' =>   $receiver_mobile,
                            'reciever_email' => $recDetail['0']['email'],
                            'destination' => $recDetail['0']['branch_location'],
                            'sku' => $ShipArr['sku'],
                            'booking_id' => $ShipArr['booking_id'],
                            'old_slip_no' => $ShipArr['slip_no'],
                            'sku_data' => $sku_data,
                            'promise_deliver_date' => $ShipArr['promise_deliver_date'],
                            'frwd_company_awb' => $ShipArr['frwd_company_awb']
                        );

                        // echo "<pre> sdfsd"; print_r($ShipArr); die;

                        // $sellername = "DIGGIPACKS FULFILLMENT- ".$sellername;

                        $CURRENT_TIME = date('H:i:s');
                        $CURRENT_DATE = date('Y-m-d H:i:s');
                        $imileordertype = 200;
                        // echo $company;die; 
                        $ccRetrundata = $this->courierComanyForward($sellername, $auth_token, $company, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1, $super_id, $company_type, $c_id, $api_url, $imileordertype, $open_package_flag);
                        //                    error_reporting(-1);
                        // ini_set('display_errors', 1);
                        if ($ccRetrundata['status'] == 200) {
                            $Update_data = $this->Ccompany_model->Insert_Reverse_Shipment($sku_data, $ShipArr, $slipNo, $ccRetrundata['client_awb'], $CURRENT_TIME, $CURRENT_DATE, $company, $comment, $ccRetrundata['label'], $c_id, $ccRetrundata['barq_order_id'], $api_url);

                            if (!empty($zone_id)) {
                                $updateZone = $this->Ccompany_model->CapacityUpdate($zone_cust_id, $zone_id, $super_id);
                            }
                            $slipArray = array('reverse_forwarded' => 1);
                            $this->Ccompany_model->GetshipmentUpdate_forward($slipArray, $slipNo);
                            array_push($succssArray, $slipNo);
                        } else {
                            $returnArr['responseError'][] = $ccRetrundata['error']['responseError'];
                        }
                    } else {
                        $returnArr['responseError'][] = "Shipment status is not Delivered or Already Forwarded";
                    }
                }
            }
        }
        $return['invalid_slipNO'] = $invalid_slipNO;
        $return['Error_msg'] = $returnArr['responseError'];
        //$return['Success_msg']=$returnArr['successAwb'];
        $return['Success_msg'] = $succssArray;


        echo json_encode($return);
    }





    public function performance_details($frwd_throw = null, $status = null, $from = null, $to = null)
    {

        $data['DetailsArr'] = $this->Ccompany_model->GetallperformationDetailsQry($frwd_throw, $status, $from, $to);
        $this->load->view('courierCompany/performance_details', $data);
    }



    public function performance()
    {

        $data['postData'] = $this->input->post();

        if ($data['postData']['clfilter'] == 1) {
            $data['postData'] = array();
        }

        $data['sellers'] = $this->Ccompany_model->all($data['postData']);

        $this->load->view('courierCompany/performance', $data);
    }


    public function forwardShipment($awb = null, $super_id = null)
    {

        $fullData = $this->shipDetail($awb, $super_id);

        // print_r($fullData);exit;
        if (!empty($fullData)) {

            // echo "customer default <br/>" ; 
            $lastArray = array();
            foreach ($fullData as $data) {
                //  echo '<pre>';
                //  print_r($data);exit;

                $dataArray = $this->zonListDatacustomer($data['cc_id'], $data['destination'], $super_id, $data['cust_id']);


                if (!empty($dataArray)) {
                    return $dataArray;
                    break;
                } else {
                    $dataArray = $this->zonListDatadefault($data['cc_id'], $data['destination'], $super_id, $data['cust_id']);
                    if (!empty($dataArray)) {
                        return $dataArray;
                        break;
                    }
                }
                // echo '<pre>';
                // print_r($dataArray);exit;
            }
        } else {
            //echo "default <br/>" ;  

            $fullData = $this->shipDetailDefault($awb, $super_id);


            //   echo '<pre>';
            //      print_r($fullData);exit;



            $lastArray = array();
            foreach ($fullData as $data) {
                $cc_id = GetCourCompanynameIdbulkprint($data['cc_id'], "cc_id");



                // $dataArray = $this->zonListDatadefault($data['cc_id'], $data['destination'], $super_id,$data['cust_id']);           
                $dataArray = $this->zonListDatadefault($cc_id, $data['destination'], $super_id, $data['cust_id']);
                // echo json_encode($dataArray);die;
                if (!empty($dataArray)) {
                    return $dataArray;
                    break;
                }
            }
        }
    }

    public function zonListDatadefault($ccid, $dest, $super_id, $cust_id)
    {
        //echo $dest."<br>";

        $this->db->select('id,cc_id,city_id');
        $this->db->from('zone_list_fm');
        $this->db->where('zone_list_fm.super_id', $super_id);
        $this->db->where('capacity>todayCount');
        $this->db->where('cc_id', $ccid);

        $query1 = $this->db->get();
        //   echo $this->db->last_query()."<br>"; die; 
        $result = $query1->result_array();
        if ($query1->num_rows() > 0) {
            $result = $query1->result_array();

            $rData = array();
            foreach ($result as $n) {
                if (in_array($dest, json_decode($n['city_id'], true))) {
                    array_push($rData, $n);
                }
            }
        }

        if (!empty($rData)) {
            return $rData;
        } else {
            return false;
        }
    }



    public function zonListDatacustomer($ccid, $dest, $super_id, $cust_id)
    {
        //echo $dest."<br>";
        $this->db->select('id,cc_id,city_id,cust_id');
        $this->db->from('zone_list_customer_fm');
        $this->db->where('zone_list_customer_fm.super_id', $super_id);
        $this->db->where('capacity > todayCount');
        $this->db->where('cust_id', $cust_id);
        $this->db->where('cc_id', $ccid);

        $query = $this->db->get();
        // echo $this->db->last_query()."<br>"; die ; 

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $rData = array();
            foreach ($result as $n) {
                if (in_array($dest, json_decode($n['city_id'], true))) {
                    array_push($rData, $n);
                }
            }
        }

        if (!empty($rData)) {
            return $rData;
        } else {
            return false;
        }
    }


    public function shipDetailDefault($slip_no, $super_id)
    {

        $this->db->select('shipment_fm.cust_id,shipment_fm.destination,sellerDefaultCourier.cc_id,sellerDefaultCourier.priority');
        $this->db->from('shipment_fm');
        $this->db->join('sellerDefaultCourier', 'sellerDefaultCourier.super_id = shipment_fm.super_id');
        $this->db->where('shipment_fm.slip_no', $slip_no);
        $this->db->where('shipment_fm.super_id', $super_id);
        $this->db->where('sellerDefaultCourier.status', '0');
        $this->db->order_by('sellerDefaultCourier.priority', 'ASC');
        $query = $this->db->get();
        //  echo "shipDetailDefault = ". $this->db->last_query(); die;
        $result = $query->result_array();

        return $result;
    }

    public function shipDetail($slip_no, $super_id)
    {

        $this->db->select('shipment_fm.cust_id,shipment_fm.destination,sellerCourier.cc_id,sellerCourier.priority');
        $this->db->from('shipment_fm');
        $this->db->join('sellerCourier', 'sellerCourier.seller_id = shipment_fm.cust_id');
        $this->db->where('shipment_fm.slip_no', $slip_no);
        $this->db->where('shipment_fm.super_id', $super_id);
        $this->db->where('sellerCourier.status', '0');
        $this->db->order_by('sellerCourier.priority', 'ASC');
        $query = $this->db->get();
        //echo "shipDetail = ". $this->db->last_query(); die; 
        $result = $query->result_array();

        return $result;
    }



    public function courierComanyForward($sellername, $Auth_token, $company, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1, $super_id, $company_type, $c_id, $api_url, $imileordertype, $open_package_flag)
    {

        $slipNo = $ShipArr['slip_no'];


        if ($company == 'Aramex') {
            $params = $this->Ccompany_model->AramexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1, $super_id);
            $dataJson = json_encode($params);
            // echo $dataJson;//die;
            $headers = array("Content-type:application/json");
            $url = $api_url;
            // echo $url;die;
            $awb_array = $this->Ccompany_model->AxamexCurl($url, $headers, $dataJson, $c_id, $ShipArr);
            // print "<pre>"; print_r($awb_array);
            // die;
            $check_error = $awb_array['HasErrors'];
            if ($check_error == 'true') {
                // echo 'xxxx';
                //   print "<pre>"; print_r($awb_array['Shipments']['ProcessedShipment']);
                if ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Code'] == 'ERR30') {
                    $str =   $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'];
                    $strAry = explode('(', $str);
                    $str2 = $strAry[1];
                    $strAry1 = explode(')', $str2);
                    $str3 = $strAry1[0];
                    if ($str3 != '') {
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        return  $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $str3);
                    }
                } else {

                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {

                $main_result = $awb_array['Shipments']['ProcessedShipment'];

                $Check_inner_error = $main_result['HasErrors'];

                // if ($Check_inner_error == 'false') {
                $client_awb = $awb_array['Shipments']['ProcessedShipment']['ID'];
                $awb_label = $awb_array['Shipments']['ProcessedShipment']['ShipmentLabel']['LabelURL'];

                $generated_pdf = file_get_contents($awb_label);
                $encoded = base64_decode($generated_pdf);
                header('Content-Type: application/pdf');
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
                // }
            }
        } elseif ($company == 'Aramex International') { 

            $params = $this->Ccompany_model->AramexArrayAdvance($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1, $cod_amount, $super_id);

            // $params = $this->Ccompany_model->AramexArrayAdvance($sellername,$ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $box_pieces1,$cod_amount,$super_id);
            $dataJson = json_encode($params);
            // print "<pre>"; print_r($dataJson); 
//            echo $dataJson;die;
            $headers = array("Content-type:application/json");
            $url = $api_url;
            //echo  $url;die;                 
            $awb_array = $this->Ccompany_model->AxamexCurl($url, $headers, $dataJson, $c_id, $ShipArr);
            // print "<pre>"; print_r($awb_array); //die;

            $check_error = $awb_array['HasErrors'];
            if ($check_error == 'false') {
                $main_result = $awb_array['Shipments']['ProcessedShipment'];
                $Check_inner_error = $main_result['HasErrors'];

                if ($Check_inner_error == 'false') {
                    $client_awb = $main_result['ID'];
                    $awb_label = $main_result['ShipmentLabel']['LabelURL'];
                    $generated_pdf = file_get_contents($awb_label);
                    $encoded = base64_decode($generated_pdf);

                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                }
            } else if ($check_error == 'true' || $check_error == TRUE) {

                if (empty($awb_array['Shipments'])) {

                    $error_response = $awb_array['Notifications']['Notification']['Message'];
                    $error_response = json_encode($error_response);
                    $returnArr['responseError'] = $slipNo . ':' . $error_response;
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                } else {
                    if ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'] == '') {

                        foreach ($awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification'] as $error_response) {
                            //echo $error_response['Message'];die;
                            //print "<pre>"; print_r($error_response);die;
                            // array_push($error_array, $slipNo . ':' . $error_response['Message']);
                            $returnArr['responseError'] = $slipNo . ':' . $error_response['Message'];
                            //$return= array('status'=>201); 
                            $return = array('status' => 201, 'error' => $returnArr);
                            return $return;
                        }
                    } else {
                        $error_response = $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification']['Message'];

                        $error_response = json_encode($error_response);
                        //  array_push($error_array, $slipNo . ':' . $error_response);
                        $returnArr['responseError'] = $slipNo . ':' . $error_response;
                        $return = array('status' => 201, 'error' => $returnArr);
                        return $return;
                    }
                }

                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } 
        elseif( $company == 'TD Logistics' ) //shipsy 3pl company
        { 
            // echo "djsfkjsdgf"; die; 
            $this->load->helper('shipsy'); 
            $responseArr = ForwardToshipsy($ShipArr, $counrierArr,  $c_id , $box_pieces1, $complete_sku, $super_id,$pay_mode);


            if ($responseArr['error'] == "false"){
                $return= array('status'=>200,'label'=> $responseArr['data']['fastcoolabel'],'client_awb'=>$responseArr['data']['client_awb']); 
                return $return;
            }else{
                $returnArr['responseError'][] = $slipNo . ':' .$responseArr['msg'];
                $return= array('status'=>201,'error'=> $returnArr);
                return $return;
            } 
        }
        elseif($company == 'PDC-EG'){
            // echo "djsfkjsdgf"; die; 
            $this->load->helper('pdceg');
            $responseArr = ForwardToPDC($ShipArr, $counrierArr,  $c_id , $box_pieces1, $complete_sku, $super_id,$pay_mode);
            if ($responseArr['status'] == 'true'){
                $return= array('status'=>200,'label'=> $responseArr['fastcoolabel'],'client_awb'=>$responseArr['client_awb']); 
                return $return;
            }else{
                $returnArr['responseError'][] = $slipNo . ':' .$responseArr['msg'];
                $return= array('status'=>201,'error'=> $returnArr);
                return $return;
            } 
        }
        
        elseif ($company == 'Safearrival') {

            $charge_items = array();
            $Auth_response = SafeArrival_Auth_cURL($counrierArr);

            $responseArray = json_decode($Auth_response, true);
            $Auth_token = $responseArray['data']['id_token'];
            $response = $this->Ccompany_model->SafeArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);
            $safe_response = json_decode($response, true);

            if ($safe_response['status'] == 'success') {
                $safe_arrival_ID = $safe_response['data']['id'];
                $client_awb = $safe_response['data']['order_number'];

                //****************************safe arrival label print cURL****************************

                $label_response = safearrival_label_curl($safe_arrival_ID, $Auth_token, $counrierArr['api_url']);

                $safe_label_response = json_decode($label_response, true);
                $safe_Label = $safe_label_response['data']['value'];

                $generated_pdf = file_get_contents($safe_Label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                //****************************safe arrival label print cURL****************************
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else if ($safe_response['status'] == 'error') {
                $returnArr['responseError'] = $slipNo . ':' . $safe_response['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Thabit') {

            $charge_items = array();
            $Auth_response = Thabit_Auth_cURL($counrierArr);
            $responseArray = json_decode($Auth_response, true);
            $Auth_token = $responseArray['data']['id_token'];

            $thabit_response = $this->Ccompany_model->ThabitArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);

            if ($thabit_response['status'] == 'success') {
                $thabit_order_ID = $thabit_response['data']['id'];
                $client_awb = $thabit_response['data']['order_number'];

                //**************************** Thabit label print cURL****************************
                $label_response = thabit_label_curl($thabit_order_ID, $Auth_token, $counrierArr['api_url']);
                $safe_label_response = json_decode($label_response, true);
                $safe_Label = $safe_label_response['data']['value'];

                $generated_pdf = file_get_contents($safe_Label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                //**************************** Thabit label print cURL****************************
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else if ($thabit_response['status'] == 'error') {

                $returnArr['responseError'] = $slipNo . ':' . $thabit_response['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } else if ($company == 'Esnad') {
            $esnad_awb_number = Get_esnad_awb($start_awb_sequence, $end_awb_sequence);
            $esnad_awb_number = $esnad_awb_number - 1;
            $Auth_token = $counrierArr['auth_token'];
            $response = $this->Ccompany_model->EsnadArray($sellername, $ShipArr, $counrierArr, $esnad_awb_number, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);
            $responseArray = json_decode($response, true);

            $status = $responseArray['success'];
            if ($status == false) {
                $error_array = array(
                    "Error_Message " => $responseArray['message'],
                );
                $error_response = json_encode($error_msg);
                $returnArr['responseError'] = $slipNo . ':' . $responseArray['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
            if ($status == true) {

                $description = $responseArray['message'];
                $client_awb = $responseArray['dataObj']['trackingNo'];
                $esnad_awb_link = $responseArray['dataObj']['labelUrl'];

                $generated_pdf = file_get_contents($esnad_awb_link);

                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            }
        } elseif ($company == 'Barqfleet') {
            $response_ww = $this->Ccompany_model->BarqfleethArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $CashOnDeliveryAmount, $services, $c_id, $box_pieces1, $super_id);
            $response_array = json_decode($response_ww, TRUE);

            if ($response_array['code'] != '') {
                $returnArr['responseError'] = $slipNo . ':' . $response_array['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            } else {
                $Authorization = $counrierArr['auth_token'];
                $request_url_label = $counrierArr['api_url'] . "/orders/airwaybill/" . $response_array['id'];
                $headers = array("Content-type:application/json");
                $firsthead = array(
                    "Content-Type: application/json",
                    "Authorization: " . $Authorization,
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request_url_label);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $firsthead);
                $response_label = curl_exec($ch);
                $info = curl_getinfo($ch);
                curl_close($ch);
                $client_awb = $response_array['tracking_no'];
                $slip_no = $response_array['merchant_order_id'];
                $barq_order_id = $response_array['id'];
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");
                $generated_pdf = file_get_contents($response_label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $response_label);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb, 'barq_order_id' => $barq_order_id);
                return $return;
            }

            //end
        } elseif ($company == 'Makhdoom') {
            $Auth_response = MakdoomArrival_Auth_cURL($counrierArr);

            $responseArray = json_decode($Auth_response, true);
            $Auth_token = $responseArray['data']['id_token'];

            $response = $this->Ccompany_model->MakdoonArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);

            $safe_response = json_decode($response, true);


            if ($safe_response['status'] == 'success') {
                $safe_arrival_ID = $safe_response['data']['id'];
                $client_awb = $safe_response['data']['order_number'];

                //****************************makdoom arrival label print cURL****************************

                $label_response = makdoom_label_curl($client_awb, $Auth_token);
                $safe_label_response = json_decode($label_response, true);
                $safe_Label = $safe_label_response['data']['value'];

                $generated_pdf = file_get_contents($safe_Label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                //****************************makdoom label print cURL****************************

                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'] = $slipNo . ':' . 'error found ';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Makhdoom V2') {

            $responseArray = $this->Ccompany_model->MakdoonArrayVer1($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id);
            $successFlag = TRUE;
            if (empty($responseArray)) {
                $successFlag = false;
            } else {
                if (isset($responseArray['errors']) && !empty($responseArray['errors'])) {
                    $successFlag = false;
                }
            }


            //if($responseArray['status'] == 'created'){
            if ($successFlag) {

                $client_awb = $responseArray['tracking_number'];
                $generated_pdf = file_get_contents($responseArray['waybill']);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . json_encode($responseArray);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Zajil') {
            $response = $this->Ccompany_model->ZajilArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id);
            if (!empty($response['data'])) {
                $success = $response['data'][0]['success'];
                if ($response['status'] == 'OK' && $success == true) {
                    $client_awb = $response['data'][0]['reference_number'];

                    $label_response = zajil_label_curl($auth_token, $client_awb);
                    header("Content-type:application/pdf");
                    file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {
                    $returnArr['responseError'] = $slipNo . ':' . $response['data'][0]['reason'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'] = $slipNo . ':' . "invalid details";
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'NAQEL') {
            // echo "kjsdhjkhjdfsdf" ; die; 
            $awb_array = $this->Ccompany_model->NaqelArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $Auth_token, $c_id, $super_id);
            $HasError = $awb_array['HasError'];
            $error_message = $awb_array['Message'];

            if ($awb_array['HasError'] == 'false') {

                $client_awb = $awb_array['WaybillNo'];
                if (!empty($client_awb)) {
                    $user_name = $counrierArr['user_name'];
                    $password = $counrierArr['password'];
                    $xml_for_label = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                                        <soapenv:Header/>
                                        <soapenv:Body>
                                        <tem:GetWaybillSticker>
                                            <tem:clientInfo>
                                                <tem:ClientAddress>
                                                    <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                                    <tem:POBox>0</tem:POBox>
                                                    <tem:ZipCode>0</tem:ZipCode>
                                                    <tem:Fax>0</tem:Fax>
                                                    <tem:FirstAddress>' . $ShipArr['sender_address'] . '</tem:FirstAddress>
                                                    <tem:Location>' . $sender_city . '</tem:Location>
                                                    <tem:CountryCode>KSA</tem:CountryCode>
                                                    <tem:CityCode>RUH</tem:CityCode>
                                                </tem:ClientAddress>
                                                <tem:ClientContact>
                                                    <tem:Name>' . $ShipArr['sender_name'] . '</tem:Name>
                                                    <tem:Email>' . $ShipArr['sender_email'] . '</tem:Email>
                                                    <tem:PhoneNumber>' . $ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                                    <tem:MobileNo>' . $ShipArr['sender_phone'] . '</tem:MobileNo>
                                                </tem:ClientContact>
                                                <tem:ClientID>' . $user_name . '</tem:ClientID>
                                                <tem:Password>' . $password . '</tem:Password>
                                                <tem:Version>9.0</tem:Version>
                                            </tem:clientInfo>
                                            <tem:WaybillNo>' . $client_awb . '</tem:WaybillNo>
                                            <tem:Reference1>' . $ShipArr['booking_id'] . '</tem:Reference1>
                                            <tem:StickerSize>FourMSixthInches</tem:StickerSize>
                                        </tem:GetWaybillSticker>
                                        </soapenv:Body>
                                        </soapenv:Envelope>';

                    $headers = array(
                        "Content-type: text/xml",
                        "Content-length: " . strlen($xml_for_label),
                    );

                    $url = $counrierArr['api_url'] . "?op=GetWaybillSticker";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_for_label);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $response = trim(curl_exec($ch));

                    curl_close($ch);

                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $response));
                    $mediaData = $xml_data->Body->GetWaybillStickerResponse->GetWaybillStickerResult[0];

                    if (!empty($mediaData)) {
                        $pdf_label = json_decode(json_encode((array) $mediaData), TRUE);
                        header('Content-Type: application/pdf');
                        $img = base64_decode($pdf_label[0]);
                        $savefolder = $img;
                        file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);
                        //*********NAQEL arrival label print cURL****************************

                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                        //****************NAQEL label print cURL****************************

                        $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                        return $return;
                    }
                } else {
                    $returnArr['responseError'] = $slipNo . ':' . $awb_array['Message'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            }
        } elseif ($company == 'Saee') {

            $response = $this->Ccompany_model->SaeeArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id);
            $safe_response =  $response;
            if ($safe_response['success'] == 'true') {
                //****************************Saee arrival label print cURL****************************
                $client_awb = $safe_response['waybill'];
                $API_URL =  $counrierArr['api_url'];
                $Auth_token = $counrierArr['auth_token'];
                $label_response = saee_label_curl($client_awb, $Auth_token, $API_URL);
                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                //****************************Saee label print cURL****************************

                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $response['error'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Smsa') {

            $response = $this->Ccompany_model->SMSAArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);

            $xml2 = new SimpleXMLElement($response);
            $again = $xml2;
            $a = array("qwb" => $again);

            //$complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
            $complicated = ($a['qwb']->Body->addShipMPSResponse->addShipMPSResult[0]);

            if (preg_match('/\bFailed\b/', $complicated)) {
                $returnArr['responseError'][] = $slipNo . ':' . $complicated;
            } else {
                if ($response != 'Bad Request') {
                    $xml2 = new SimpleXMLElement($response);

                    $again = $xml2;
                    $a = array("qwb" => $again);

                    $complicated = ($a['qwb']->Body->addShipMPSResponse->addShipMPSResult[0]);
                    //print_r($complicated); exit;   
                    $abc = array("qwber" => $complicated);

                    $client_awb = (implode(" ", $abc));
                    // print_r($abc); exit;
                    $newRes = explode('#', $client_awb);
                    //print_r($newRes[0]); exit;

                    if (!empty($newRes[0])) {
                        $fRes = explode(',', $newRes[0]);
                        // print_r( $fRes); exit;
                        $client_awb = trim($fRes[0]);
                    }


                    $printLabel =  $this->Ccompany_model->PrintLabel($client_awb, $counrierArr['auth_token'], $counrierArr['api_url']);
                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $printLabel));
                    $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                    header('Content-Type: application/pdf');
                    $img = base64_decode($mediaData);

                    if (!empty($mediaData)) {
                        $savefolder = $img;

                        file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);

                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                        return $return;
                    }
                } else {
                    $returnArr['responseError'] = $slipNo . ':' . $response;
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            }
        } elseif ($company == 'Labaih') {
            $response = $this->Ccompany_model->LabaihArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);

            if ($response['status'] == 200) {
                $client_awb = $response['consignmentNo'];



                if (!empty($response['shipmentLabel'])) {
                    $shipmentLabel_url = $response['shipmentLabel'];
                } else {
                    $shipmentLabel_url = $counrierArr['api_url'] . '/order/printlabel?consignmentNo=' . $client_awb . '&api_key=' . $Auth_token;
                }

                $generated_pdf = file_get_contents($shipmentLabel_url);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $response['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Clex') {
            $auth_token = $this->Ccompany_model->GetClexToken($counrierArr);
            if (!empty($auth_token)) {
                $response = $this->Ccompany_model->ClexArrayVer1($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id, $auth_token);
                if ($response['success'] === true) {
                    $client_awb = $response['data']['id'];
                    $label_api_url = $counrierArr['api_url'] . 'consignments/print?consignmentId[]=' . $client_awb;
                    $label_url_new = $this->Ccompany_model->getLable($auth_token, $client_awb, 'GET', $label_api_url);
                    $clexLabel = '';
                    if (!empty($label_url_new)) {

                        $generated_pdf = file_get_contents($label_url_new);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                        $clexLabel = base_url() . "assets/all_labels/$slipNo.pdf";
                    }
                    $return = array('status' => 200, 'label' => $clexLabel, 'client_awb' => $client_awb);
                    return $return;
                } else {
                    $returnArr['responseError'][] = $slipNo . ':' . $response['message'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'][] = $slipNo . ':Token not generatd';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }

            // $response = $this->Ccompany_model->ClexArray($sellername,$ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id);
            // if ($response['data'][0]['cn_id']) {
            //     $client_awb = $response['data'][0]['cn_id'];
            //      $label_url_new = clex_label_curl($Auth_token, $client_awb);
            //      $generated_pdf = file_get_contents($label_url_new);
            //     file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

            //     $fastcoolabel = base_url()."assets/all_labels/$slipNo.pdf";
            //     $return= array('status'=>200,'label'=> $fastcoolabel,'client_awb'=>$client_awb); 
            //     return $return;  

            //     } else {
            //         if($response['already_exist'])
            //         {
            //         $label_url_new = clex_label_curl($Auth_token, $response['consignment_id'][0]);
            //         $generated_pdf = file_get_contents($label_url_new);
            //         file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

            //     $returnArr['responseError'][] = $slipNo . ':' . $response['already_exist'][0]." ".$response['consignment_id'][0];
            //     $return= array('status'=>201,'error'=> $returnArr); 
            //     return $return;
            //     }
            //     elseif($response['origin_city']){
            //         $returnArr['responseError'] = $slipNo . ':' . $response['origin_city'][0];
            //         $return= array('status'=>201,'error'=> $returnArr); 
            //         return $return;
            //     }
            //     elseif($response['destination_city']){
            //         $returnArr['responseError'] = $slipNo . ':' . $response['destination_city'][0];
            //         $return= array('status'=>201,'error'=> $returnArr); 
            //         return $return;}
            //     else
            //     {   
            //         $returnArr['responseError'] = $slipNo . ':' . $response['message'];
            //         $return= array('status'=>201,'error'=> $returnArr); 
            //         return $return;}

            // }

        } elseif ($company == 'Ajeek') {

            $response = $this->Ccompany_model->AjeekArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);
            if ($response['contents']['order_id']) {
                $response['contents']['order_id'];
                $Auth_token = $counrierArr['auth_token'];
                $vendor_id = $counrierArr['courier_pin_no'];
                $client_awb = $response['contents']['order_id'];

                //****************************Saee arrival label print cURL****************************
                $label_response = ajeek_label_curl($Auth_token, $client_awb, $vendor_id);

                file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                //****************************Saee label print cURL****************************
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'] = $slipNo . ':' . $response['description'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Aymakan') {

            // die("hi");

            $Auth_token = $counrierArr['auth_token'];;

            $response = $this->Ccompany_model->AymakanArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);
            $responseArray = json_decode($response, true);

            //if (empty($responseArray['message'])) 
            if ($responseArray['success']) {
                $client_awb = $responseArray['data']['shipping']['tracking_number'];
                $tracking_url = $counrierArr['api_url'] . "bulk_awb/trackings/";

                $aymakanlabel = $this->Ccompany_model->Aymakan_tracking($client_awb, $tracking_url, $Auth_token);
                $label = json_decode($aymakanlabel, TRUE);
                $mediaData = $label['data']['bulk_awb_url'];
                //****************************aymakan arrival label print cURL****************************
                // $generated_pdf = file_get_contents($mediaData);
                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                //****************************aymakan label print cURL****************************
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'] = $slipNo . ':' . $responseArray['message'] . ':' . json_encode($responseArray['response']);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Shipsy') {

            $response = $this->Ccompany_model->ShipsyArray($sellername, $ShipArr, $counrierArr, $Auth_token, $box_pieces1, $c_id, $super_id);

            $response_array = json_decode($response, true);

            if ($response_array['data'][0]['success'] == 1) {
                $client_awb = $response_array['data'][0]['reference_number'];

                //****************************Shipsy label print cURL****************************

                $shipsyLabel = $this->Ccompany_model->ShipsyLabelcURL($counrierArr, $client_awb);

                $mediaData = $shipsyLabel;

                file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'] = $slipNo . ':' . $response_array['error']['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif (trim($company) == 'Shipadelivery') {

            $response = $this->Ccompany_model->ShipadeliveryArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $super_id);

            // $response='[{"id":"JDK5372304412","code":0,"info":"Success","deliveryInfo":{"reference":"SD002543238","codeStatus":"orderCreatedinNetSuite","startTime":"NA","endTime":"NA","expectedTime":"NA"}}]';
            $response_array = json_decode($response, true);
            // print_r( $response_array);

            if (empty($response_array)) {

                $returnArr['responseError'] = $slipNo . ':' . 'Receiver City Empty ';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            } else {

                if ($response_array[0]['code'] == 0) {
                    // echo 'xxx';exit;
                    $client_awb = $response_array[0]['deliveryInfo']['reference'];
                    $responsepie = $this->Ccompany_model->ShipaDelupdatecURL($sellername, $counrierArr, $ShipArr, $client_awb, $box_pieces1, $super_id);
                    $responsepieces = json_decode($responsepie, true);
                    if ($responsepieces['status'] == 'Success') {
                        $shipaLabel = $this->Ccompany_model->ShipaDelLabelcURL($counrierArr, $client_awb);

                        header('Content-Type: application/pdf');

                        file_put_contents("assets/all_labels/$slipNo.pdf", $shipaLabel);
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                        return $return;
                    } else {

                        $returnArr['responseError'] = $slipNo . ':' . $responsepieces['action'];
                        $return = array('status' => 201, 'error' => $returnArr);
                        return $return;
                    }
                } else {

                    $returnArr['responseError'] = $slipNo . ':' . $response_array['info'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            }
        } elseif ($company == 'Saudi Post') {
            $response = $this->Ccompany_model->SPArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);
            $response = json_decode($response, true);

            if ($response['Items'][0]['Message'] == 'Success') {
                $client_awb = $response['Items'][0]['Barcode'];


                $fastcoolabel = 'SP';
                header('Content-Type: application/pdf');
                $lableSp =   file_get_contents(base_url() . 'awbPrint1/' . $slipNo);
                file_put_contents("assets/all_labels/$slipNo.pdf", $lableSp);
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $errre_response = $response['Items'][0]['Message'];
                if ($errre_response == '') {
                    $errre_response = $response['Message'];
                }

                $returnArr['responseError'] = $slipNo . ':' . $errre_response;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Beez') {
            // error_reporting(-1);
            // ini_set('display_errors', 1);
            $response = $this->Ccompany_model->BeezArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $sku_data, $super_id);
            if (isset($response['Message']) && !empty($response['Message'])) {

                $returnArr['responseError'] = $slipNo . ':' . $response['Message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            } else {

                $client_awb = $response;
                //$url = 'https://login.beezerp.com/label/pdf/awb/?t='.$client_awb;
                $url = 'https://beezerp.com/login/label/pdf/awb/?t=' . $client_awb;
                $generated_pdf = file_get_contents($url);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            }
        } elseif ($company == 'GLT') {

            $responseArray = $this->Ccompany_model->GLTArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);
            $successres = $responseArray['data']['orders'][0]['status'];
            $error_status = $responseArray['data']['orders'][0]['msg'];

            if (!empty($successres) && $successres == 'success') {

                $client_awb = $responseArray['data']['orders'][0]['orderTrackingNumber'];
                $innser_status = $responseArray['data']['orders'][0]['status'];
                $GltLabel = $this->Ccompany_model->GLT_label($client_awb, $counrierArr, $auth_token);

                file_put_contents("assets/all_labels/$slipNo.pdf", $GltLabel);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'KwickBox') {
            $responseArray = $this->Ccompany_model->KwickBoxArray($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);

            $successres = $responseArray['number'];
            $error_status = $responseArray['field.'][0];
            $auth_token = $counrierArr['auth_token'];
            if (!empty($successres)) {
                $client_awb = $responseArray['number'];
                $media_data = $responseArray['labelUrl'];

                $shipmentLabel_url = $this->Ccompany_model->KwickBoxLabel($slipNo, $auth_token, $api_url);
                $fastcoolabel = file_get_contents($shipmentLabel_url);
                file_put_contents("assets/all_labels/$slipNo.pdf", $fastcoolabel);
                $fastcoo_label = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoo_label, 'client_awb' => $client_awb);
                return $return;

                // if (file_put_contents( "assets/all_labels/$slipNo.pdf",file_get_contents($media_data))){
                //     $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';                             
                //     $CURRENT_DATE = date("Y-m-d H:i:s");
                //     $CURRENT_TIME = date("H:i:s");                                                      
                // } 
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'DHL JONES') {
            if (!empty($counrierArr)) {
                $api_response = $this->Ccompany_model->DhlJonesArray($sellername, $ShipArr, $counrierArr, $token, $complete_sku, $box_pieces1, $c_id, $super_id);

                if ($api_response['error'] == FALSE) {
                    $client_awb = $api_response['data']['ShipmentResponse']['ShipmentIdentificationNumber'];
                    $lableData = $api_response['data']['ShipmentResponse']['Documents'][0]['Document'];

                    $dhlLabel = '';

                    if (!empty($lableData['DocumentImage'])) {
                        $encoded = base64_decode($lableData['DocumentImage']);
                        header('Content-Type: application/pdf');
                        file_put_contents("assets/all_labels/$slipNo.pdf", $encoded);

                        $dhlLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    }


                    $return = array('status' => 200, 'label' => $dhlLabel, 'client_awb' => $client_awb);

                    return $return;
                } else {
                    $returnArr['responseError'] = $slipNo . ':' . $api_response['data']['ShipmentResponse']['Notification'][0]['Message'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'] = 'Courier Details Not Founds.';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Tamex') {
            $response = $this->Ccompany_model->tamexArray($sellername, $ShipArr, $counrierArr, $complete_sku, $pay_mode, $c_id, $box_pieces1, $super_id);
            $responseArray = json_decode($response, true);

            if ($responseArray['code'] != 0 || empty($response)) {
                $returnArr['responseError'] = $slipNo . ':' . $responseArray['data'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            } elseif ($responseArray['code'] == 0) {

                $client_awb = $responseArray['tmxAWB'];
                $API_URL = $counrierArr['api_url'] . 'print';

                $generated_pdf = $this->Ccompany_model->Tamex_label($client_awb, $counrierArr['auth_token'], $API_URL);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            }
        } elseif ($company == 'Fetchr') {

            $responseData = $this->Ccompany_model->fetchrArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id);
            if ($responseData['data'][0]['status'] == 'success') {
                $client_awb = $responseData['data'][0]['tracking_no'];
                $label = $responseData['data'][0]['awb_link'];
                $generated_pdf = file_get_contents($label);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $responseData['data'][0]['message'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'IMile') {
            //print "<pre>"; print_r($sku_data);die;
            $auth_token = $this->Ccompany_model->iMileToken($counrierArr);
            //echo $auth_token;die;
            if (empty($auth_token)) {
                $returnArr['responseError'] = $slipNo . ': Token not genrated';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            } else {
                $response = $this->Ccompany_model->iMileArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $auth_token, $super_id, $imileordertype);
                if ($response['code'] == 200  && $response['message'] == 'success') {
                    $client_awb = $response['data']['expressNo'];
                    $pdf_encoded_base64 = $response['data']['imileAwb'];
                    $pdf_file = base64_decode($pdf_encoded_base64);

                    file_put_contents("assets/all_labels/" . $slipNo . ".pdf", $pdf_file);
                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else if ($response['code'] == 30001) {
                    $returnArr['responseError'] = $slipNo . ': Customer order number repeated error code';
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                } else {
                    $returnArr['responseError'] = $slipNo . ':' . $response['message'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            }
        } elseif ($company == 'Wadha') {
            $counrierArr['user_name'] = $user_name;
            $counrierArr['password'] = $password;
            $counrierArr['api_url'] = $api_url;
            $Auth_token = $this->Ccompany_model->Wadha_auth($user_name, $password, $api_url);

            $responseArray = $this->Ccompany_model->WadhaArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id);
            $successres = $responseArray['status'];
            $error_status = $responseArray['message'];

            if (!empty($successres) && $successres == 'success') {

                $client_awb = $responseArray['data']['order_number'];
                $WadhaLabel = $this->Ccompany_model->Wadha_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($WadhaLabel, TRUE);
                $media_data = $label['data']['value'];

                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'FDA') {

            $Auth_token = $this->Ccompany_model->FDA_auth($counrierArr);

            $responseArray = $this->Ccompany_model->FDAArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id, $complete_sku);

            // echo '<pre>'; print_r( $responseArray); die;


            $successres = $responseArray['status'];
            $error_status = $responseArray['message'];

            if (!empty($successres) && $successres == 'success') {

                $client_awb = $responseArray['data']['order_number'];
                $FDALabel = $this->Ccompany_model->FDA_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($FDALabel, TRUE);
                $media_data = $label['data']['value'];
                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'MMCCO') {
            // print_r($counrierArr);die;
            $Auth_token = $this->Ccompany_model->MMCCO_auth($counrierArr['user_name'], $counrierArr['password'], $counrierArr['api_url']);

            $responseArray = $this->Ccompany_model->MMCCOArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id, $complete_sku);
            //   echo "<br><br><pre>";
            // print_r($responseArray); DIE;

            $successres = $responseArray['status'];

            $error_status = $responseArray['message'];

            if (!empty($successres) && $successres == 'success') {

                $client_awb = $responseArray['data']['order_number'];
                $MMCCOLabel = $this->Ccompany_model->MMCCO_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($MMCCOLabel, TRUE);
                $media_data = $label['data']['value'];

                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $CURRENT_DATE = date("Y-m-d H:i:s");
                $CURRENT_TIME = date("H:i:s");
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'SMSA International') {

            $response = $this->Ccompany_model->SMSAEgyptArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);

            $xml2 = new SimpleXMLElement($response);
            $again = $xml2; //print_r($again);die;
            $a = array("qwb" => $again);

            //$complicated = ($a['qwb']->Body->addShipResponse->addShipResult);
            $complicated = ($a['qwb']->Body->addShipMPSResponse->addShipMPSResult[0]);
            //print_r($a);die;

            if (preg_match('/\bFailed\b/', $complicated)) {
                $returnArr['responseError'] = $slipNo . ':' . $complicated;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            } else {
                if ($response != 'Bad Request') {
                    $xml2 = new SimpleXMLElement($response);
                    //echo "<pre>";
                    //print_r($xml2);
                    $again = $xml2;
                    $a = array("qwb" => $again);

                    //$complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
                    $complicated = ($a['qwb']->Body->addShipMPSResponse->addShipMPSResult[0]);
                    //print_r($complicated); exit;   
                    $abc = array("qwber" => $complicated);

                    $client_awb = (implode(" ", $abc));
                    //print_r($abc);die;
                    $newRes = explode('#', $client_awb);


                    if (!empty($newRes[1])) {
                        $client_awb = trim($newRes[1]);
                    }

                    $printLabel = $this->Ccompany_model->SamsaPrintLabel($client_awb, $counrierArr['auth_token'], $counrierArr['api_url']);


                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>"), "", $printLabel));
                    $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                    header('Content-Type:  text/xml; charset=utf-8');
                    $img = base64_decode($mediaData);

                    if (!empty($mediaData)) {
                        $savefolder = $img;
                        //echo $mediaData;die;


                        file_put_contents("assets/all_labels/$slipNo.pdf", $savefolder);

                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                        $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                        return $return;
                    } else {
                        //array_push($error_array, $slipNo . ':' . $client_awb);
                        $returnArr['responseError'] = $slipNo . ':' . $client_awb;
                        $return = array('status' => 201, 'error' => $returnArr);
                        //rint_r($return);die;
                        return $return;
                    }
                } else {
                    $returnArr['responseError'] = $slipNo . ':' . $response;
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            }
        } elseif ($company == 'FedEX') {

            $responseArray = $this->Ccompany_model->FedEX($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);
            //  echo "<pre>" ; print_r($responseArray); //die;
            $successres = $responseArray['Code'];
            $error_status = $responseArray['Description'];

            if (!empty($successres) && $successres == 1) {
                $client_awb = $responseArray['AirwayBillNumber'];

                $label_response = $this->Ccompany_model->FedEX_label($client_awb, $counrierArr, $ShipArr);
                $pdf_encoded_base64 = $label_response['ReportDoc'];
                $pdf_file = base64_decode($pdf_encoded_base64);

                file_put_contents("assets/all_labels/" . $slipNo . ".pdf", $pdf_file);
                $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'MomentsKsa') {

            $Auth_token = $this->Ccompany_model->Moments_auth($counrierArr);
            $responseArray = $this->Ccompany_model->MomentsArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);
            $successres = $responseArray['errors'];
            $error_status = $responseArray['message'];

            if (empty($successres)) {

                $client_awb = $responseArray['TrackingNumber'];
                $MomentLabel = $responseArray['printLableUrl'];

                $generated_pdf = file_get_contents($MomentLabel);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Postagexp') {

            $Auth_token = $this->Ccompany_model->Postagexp_auth($counrierArr);

            $responseArray = $this->Ccompany_model->PostagexpArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);
            $successres = $responseArray['errors'];
            $error_status = $responseArray['message'];

            if (empty($successres)) {

                $client_awb = $responseArray['TrackingNumber'];
                $PostagexpLabel = $responseArray['printLable'];

                $generated_pdf = file_get_contents($PostagexpLabel);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'SLS') {

            $responseArray = $this->Ccompany_model->SLSArray($sellername, $ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);
            //  echo "<pre>" ; print_r($responseArray); //die;
            $successres = $responseArray['status'];
            $error_status = json_encode($responseArray);

            if (!empty($successres) && $successres == 1) {
                $client_awb = $responseArray['tracking_number'];
                $SLSLabel = $this->Ccompany_model->SLS_label($client_awb, $counrierArr);

                file_put_contents("assets/all_labels/$slipNo.pdf", $SLSLabel);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Bosta') {
            if (!empty($counrierArr)) {
                $tokenResponse =  $this->Ccompany_model->Bosta_token_api($counrierArr);
                if ($tokenResponse['success'] === true) {
                    $token = $tokenResponse['token'];

                    $api_response = $this->Ccompany_model->BostaArray($sellername, $ShipArr, $counrierArr, $token, $complete_sku, $box_pieces1, $c_id, $super_id);
                    //print "<pre>"; print_r($api_response);die;
                    if ($api_response['error'] == FALSE) {
                        $client_awb = $api_response['data']['_id'];
                        $lableInfo =  $this->Ccompany_model->Bosta_Label_api($counrierArr, $token, $client_awb);
                        $bostaLabel = '';
                        if (!empty($lableInfo['data'])) {
                            $encoded = base64_decode($lableInfo['data']);
                            header('Content-Type: application/pdf');
                            file_put_contents("assets/all_labels/$slipNo.pdf", $encoded);

                            $bostaLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                        }


                        $return = array('status' => 200, 'label' => $bostaLabel, 'client_awb' => $client_awb);
                        return $return;
                    } else {
                        $returnArr['responseError'] = $slipNo . ':' . $api_response['data']['message'];
                        $return = array('status' => 201, 'error' => $returnArr);
                        return $return;
                    }
                } else {
                    $returnArr['responseError'] = 'Courier Details Not Founds.';
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'] = 'Courier Details Not Founds.';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'MICGO') {

            $Auth_token = $this->Ccompany_model->MICGO_AUTH($counrierArr);
            $responseArray = $this->Ccompany_model->MICGOarray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $Auth_token, $super_id);
            //print "<pre>"; print_r($responseArray);die;
            $successres = $responseArray['error'];
            $error_status = $responseArray['message'];
            if (empty($successres)) {
                sleep(2);

                $client_awb = $responseArray['shipments'][0]['waybill'];
                $Label = $responseArray['shipments'][0]['shippingLabelUrl'];

                $generated_pdf = file_get_contents($Label);

                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);


                $micGoLabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                $return = array('status' => 200, 'label' => $micGoLabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Dots') {
            sleep('1');
            $responseArray = $this->Ccompany_model->DOTSarray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id);

            $statusCode = $responseArray['status'];

            if ($statusCode == 'OK' && $responseArray['code'] == '200') {
                sleep('2');
                $client_awb = $responseArray['payload']['awbs'][0]['code'];
                $LabelUrl = $responseArray['payload']['awbs'][0]['label_url'];;
                $return = array('status' => 200, 'label' => $LabelUrl, 'client_awb' => $client_awb);
                return $return;
            } else {
                $error_status = json_encode($responseArray['payload']);
                $returnArr['responseError'][] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Bawani') {
            $Auth_token = $this->Ccompany_model->BAWANI_AUTH($counrierArr);

            $responseArray = $this->Ccompany_model->BAWANIArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);

            $successres = $responseArray['status'];
            $error_status = $responseArray['message'];

            if (!empty($successres) && $successres == 'success') {

                $client_awb = $responseArray['data']['order_number'];
                $BAWANILabel = $this->Ccompany_model->BAWANI_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($BAWANILabel, TRUE);
                $media_data = $label['data']['value'];

                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Lastpoint') {

            $Auth_token = $this->Ccompany_model->shipox_auth($counrierArr);

            $responseArray = $this->Ccompany_model->lastpointArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id);

            $successres = $responseArray['status'];
            $error_status = $responseArray['message'];
            if (!empty($successres) && $successres == 'success') {

                $client_awb = $responseArray['data']['order_number'];
                $WadhaLabel = $this->Ccompany_model->shipox_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($WadhaLabel, TRUE);
                $media_data = $label['data']['value'];

                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'LAFASTA') {

            $user_name = $counrierArr['user_name'];
            $password = $counrierArr['password'];
            $api_url = $counrierArr['api_url'];
            $Auth_token = $this->Ccompany_model->LAFASTA_AUTH($user_name, $password, $api_url);
            if (!empty($Auth_token)) {

                $responseArray = $this->Ccompany_model->LAFASTA_Array($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $sku_data, $super_id);
                if ($responseArray['isSuccess']) {

                    $client_awb = $responseArray['resultData']['id'];

                    $labelInfo = $this->Ccompany_model->LAFASTA_Label($client_awb, $Auth_token, $api_url);
                    if ($labelInfo['isSuccess']) {
                        $media_data = $labelInfo['resultData']['shippingLabelUrl'];

                        $generated_pdf = file_get_contents($media_data);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    }
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {
                    $returnArr['responseError'][] = $slipNo . ': ' . $responseArray['messageEn'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {

                $returnArr['responseError'][] = $slipNo . ':Token not gererated';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        }
        // elseif (($company == 'SMB')){
        elseif (($company == 'SMB') || ($company == 'SMB COLD')) {

            if ($company == 'SMB COLD') {
                $service_type = 'SR';
            } else {
                $service_type = 'SEC';
            }

            $responseArray = $this->Ccompany_model->SMB_Array($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id, $service_type);

            if ($responseArray['isSuccess'] == 'true') {
                $orderID = $responseArray['orderID'];

                $confirmOrder = $this->Ccompany_model->SMB_confirm($orderID, $counrierArr, $ShipArr['slip_no'], $c_id);
                if (!empty($confirmOrder['data']['barcode'])) {
                    $client_awb = $confirmOrder['data']['barcode'];
                    $labelData = $this->Ccompany_model->SMB_Label($orderID, $counrierArr);

                    file_put_contents("assets/all_labels/$slipNo.pdf", $labelData);
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {
                    $returnArr['responseError'][] = $slipNo . ': ' . $responseArray['error'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $responseArray['messageEn'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'AJA') {

            $user_name = $counrierArr['user_name'];
            $password = $counrierArr['password'];
            $api_url = $counrierArr['api_url'];

            $Auth_tokenData = $this->Ccompany_model->AJA_AUTH($user_name, $password, $api_url);
            if ($Auth_tokenData['success']) {
                $Auth_token = $Auth_tokenData['result'];
                $responseArray = $this->Ccompany_model->AJAArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);
                if ($responseArray['success']) {
                    $client_awb = $responseArray['trackNo'];
                    $media_data = $responseArray['printUrl'];
                    $generated_pdf = file_get_contents($media_data);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {
                    $returnArr['responseError'][] = $slipNo . ': ' . $responseArray['message'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'][] = $slipNo . ':Token not gererated';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Flamingo') {

            $Auth_token = $this->Ccompany_model->shipox_auth($counrierArr);

            $responseArray = $this->Ccompany_model->flamingoArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $super_id);
            $successres = $responseArray['status'];
            $error_status = $responseArray['message'];

            if (!empty($successres) && $successres == 'success') {
                $client_awb = $responseArray['data']['order_number'];
                $falmingoLabel = $this->Ccompany_model->shipox_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($falmingoLabel, TRUE);
                $media_data = $label['data']['value'];

                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $comment = '';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'AJOUL') {

            $responseArray = $this->Ccompany_model->AJOUL_AUTH($sellername, $counrierArr, $ShipArr, $c_id, $box_pieces1, $complete_sku, $super_id);
            if (isset($responseArray['Shipment']) && !empty($responseArray['Shipment'])) {
                $client_awb = $responseArray['TrackingNumber'];
                $media_data = $responseArray['printLable'];
                // $generated_pdf = file_get_contents($media_data);
                // file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                // $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';

                $return = array('status' => 200, 'label' => $media_data, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . json_encode($responseArray['errors']);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'FLOW' || $company == 'Flow (Installation)') {

            $responseArray = $this->Ccompany_model->ShipsyDataArray($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($responseArray['data'][0]['success'] == true) {

                $client_awb = $responseArray['data'][0]['reference_number'];

                $label = $this->Ccompany_model->ShipsyLabel($counrierArr, $client_awb);

                file_put_contents("assets/all_labels/$slipNo.pdf", $label);


                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'][] = $slipNo . ': ' . json_encode($responseArray['data'][0]['message']);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Mahmool') {
            $responseArray = $this->Ccompany_model->ShipsyDataArray($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($responseArray['data'][0]['success'] == true) {

                $client_awb = $responseArray['data'][0]['reference_number'];

                $label = $this->Ccompany_model->ShipsyLabel($counrierArr, $client_awb);

                file_put_contents("assets/all_labels/$slipNo.pdf", $label);


                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {

                $returnArr['responseError'][] = $slipNo . ': ' . json_encode($responseArray['data'][0]['message']);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'UPS') {

            $responseArray = $this->Ccompany_model->UPSArray($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);

            if (isset($responseArray['ShipmentResponse']['Response']['ResponseStatus']) && $responseArray['ShipmentResponse']['Response']['ResponseStatus']['Code'] == 1) {
                $client_awb = $responseArray['ShipmentResponse']['ShipmentResults']['PackageResults']['TrackingNumber'];
                sleep(2);
                $labelResponse = $this->Ccompany_model->UPSLabel($client_awb, $counrierArr);

                $GI = $labelResponse['LabelRecoveryResponse']['LabelResults']['LabelImage']['GraphicImage'];

                $response_label = base64_decode($GI);

                $generated_pdf = file_get_contents($response_label);

                //file_put_contents("assets/all_labels/$slipNo.pdf", $response_label);
                $dd = file_put_contents("/var/www/html/diggipack_new/micro/assets/all_labels/$slipNo.pdf", $response_label);

                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . json_encode($responseArray['response']['errors']);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Kudhha') {
            $Auth_token = $this->Ccompany_model->shipox_auth($counrierArr);
            $responseArray = $this->Ccompany_model->shipoxDataArray($sellername, $ShipArr, $counrierArr, $Auth_token, $c_id, $box_pieces1, $complete_sku, $super_id);

            $successres = $responseArray['status'];
            $error_status = $responseArray['message'];

            if (!empty($successres) && $successres == 'success') {
                $client_awb = $responseArray['data']['order_number'];
                $WadhaLabel = $this->Ccompany_model->shipox_label($client_awb, $counrierArr, $Auth_token);
                $label = json_decode($WadhaLabel, TRUE);
                $media_data = $label['data']['value'];

                $generated_pdf = file_get_contents($media_data);
                file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
                $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $error_status;
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Mylerz') {

            $this->load->library('mylerzClass'); //load custome library 
            // $password= urlencode($counrierArr['password']);
            //print "<pre>"; print_r($counrierArr);die;
            $token_response = $this->mylerzclass->getToken($counrierArr['user_name'], $counrierArr['password'], $counrierArr['api_url']);

            if (!empty($token_response['access_token'])) {
                $token = $token_response['access_token'];

                $response = $this->mylerzclass->forwardShipment($sellername, $ShipArr, $counrierArr, $token, $complete_sku, $c_id, $box_pieces1, $super_id);
                //print "<pre>"; print_r($response);die;    
                if ($response['IsErrorState'] === false) {
                    //print "<pre>"; print_r($response);die;    
                    $client_awb = $response['Value']['Packages'][0]['BarCode'];

                    $label_response = $this->mylerzclass->getLabel($client_awb, $token, $api_url, $slipNo);
                    //print "<pre>"; print_r($label_response);die;
                    $fastcoolabel = '';
                    if (!empty($label_response['Value'])) {
                        $label_data = base64_decode($label_response['Value']);
                        file_put_contents("assets/all_labels/$slipNo.pdf", $label_data);
                        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
                    }
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {
                    //print "surendra<pre>"; print_r($response);die;
                    $returnArr['responseError'][] = $slipNo . ':' . $response['ErrorDescription'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {
                $returnArr['responseError'][] = $slipNo . ': Token not generated';
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'DRB Logistics') {

            // echo "djhjsdhf"; die; 
            $this->load->helper("sendexpress"); //load custom helper 

            $response = ForwardToSendExpress($ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);


            if ($response['status'] == 'true') {

                $return = array('status' => 200, 'label' => $response['fastcoolabel'], 'client_awb' => $response['client_awb']);
                return $return;
            } else {


                $returnArr['responseError'][] = $slipNo . ': ' . $response['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Bosta V2') {

            $this->load->helper('bosta'); //load custom helper 


            $response = BostaForward($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id, $open_package_flag);

            if ($response['error'] == 'false') {

                $return = array('status' => 200, 'label' => $response['data']['label'], 'client_awb' => $response['data']['client_awb'], 'bosta_label_id' => $response['data']['bosta_label_id']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $response['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'J&T' || $company == 'J&T Reverse') {
            $this->load->helper('jt');
            $responseArr = JandTArr($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id, $company);
            if ($responseArr['msg'] == 'success') {

                $return = array('status' => 200, 'label' => $responseArr['data']['label'], 'client_awb' => $responseArr['data']['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $responseArr['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'EgyptExpress') {
            $this->load->helper('egyptexpress');
            $response = EgyptExpressArr($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id);
            if ($response['error'] == 'false') {

                $return = array('status' => 200, 'label' => $response['data']['label'], 'client_awb' => $response['data']['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $response['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'J&T EG') {
            //echo "testing"; die;
            $this->load->helper('egjt');
            $responseArr = JandTArr($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($responseArr['msg'] == 'success') {

                $return = array('status' => 200, 'label' => $responseArr['data']['label'], 'client_awb' => $responseArr['data']['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $responseArr['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Business Flow' || $company == 'Nashmi' || $company == 'Saudi Hajer' || $company == 'ColdT') {
            // echo "testing"; die;
            $this->load->helper('shipox');
            $responseArr = ForwardToShipox($sellername, $ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);

            if ($responseArr['status'] == "true") {
                $return = array('status' => 200, 'label' => $responseArr['fastcoolabel'], 'client_awb' => $responseArr['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $responseArr['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'ProConnect') {

            $this->load->helper('proconnect');

            $response = ProConnectForword($ShipArr, $counrierArr, $box_pieces1, $super_id, $c_id);
            //print "<pre>"; print_r($response);die;
            if ($response['Status'] == 200) {

                $return = array('status' => 200, 'label' => '', 'client_awb' => $response['data']['InForSOReference']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . json_encode($response['data']['msg']);
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'SMSA Reverse') {

            //    echo "test";
            $this->load->helper('smsa');

            $responseArr = SmsaReverse($ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);

            if ($responseArr['msg'] == 'success') {

                $return = array('status' => 200, 'label' => $responseArr['data']['label'], 'client_awb' => $responseArr['data']['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ': ' . $responseArr['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Weenkapp') {
            //    echo "<pre>"; print_r($ShipArr); die;
            $this->load->helper('weenkapp_helper');

            $responseArr =  ForwardToweenkapp($ShipArr, $counrierArr,  $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($responseArr['status'] == 'true') {
                $return = array('status' => 200, 'label' => $responseArr['fastcoolabel'], 'client_awb' => $responseArr['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $responseArr['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'Roz Express' || $company == 'Saudi Hajer V2') {
            $this->load->helper("rozx");
            $responseArray = ForwardToRozx($ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($responseArray['status'] == 'true') {
                $return_array = array('status' => 200, 'label' => $responseArray['fastcoolabel'], 'client_awb' => $responseArray['client_awb']);

                return $return_array;
            } else {
                $return_array['responseError'][] = $slipNo . ':' . $responseArray['msg'];
                $return = array('status' => 201, 'error' => $return_array);

                return $return;
            }
        } elseif ($company == 'Sprint') {
            $this->load->helper("sprint");
            $responseArray = ForwardToSprint($ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);

            if ($responseArray['status'] == 'true') {
                $return_array = array('status' => 200, 'label' => $responseArray['data']['label'], 'client_awb' => $responseArray['data']['client_awb']);
                return $return_array;
            } else {
                $return_array['responseError'][] = $slipNo . ':' . $responseArray['msg'];
                $return = array('status' => 201, 'error' => $return_array);
                return $return;
            }
        } elseif ($company == 'Torod') {

            $this->load->helper('torod');
            $response = ForwardToTorod($ShipArr, $counrierArr, $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($response['error'] == "false") {
                $return_array = array('status' => 200, 'label' => $response['data']['label'], 'client_awb' => $response['data']['client_awb'], 'torod_order_id' => $response['data']['torod_order_id']);
                return $return_array;
            } else {
                $return_array['responseError'][] = $slipNo . ':' . $response['msg'];
                $return = array('status' => 201, 'error' => $return_array);
                return $return;
            }
        } elseif ($company == 'Shipadelivery v2') {
            // echo "djsfkjsdgf"; die; 
            $this->load->helper('shipav2');
            $responseArr = ForwardToShipaV2($ShipArr, $counrierArr,  $c_id, $box_pieces1, $complete_sku, $super_id);
            if ($responseArr['status'] == 'true') {
                $return = array('status' => 200, 'label' => $responseArr['shipaV2Label'], 'client_awb' => $responseArr['client_awb']);
                return $return;
            } else {
                $returnArr['responseError'][] = $slipNo . ':' . $responseArr['msg'];
                $return = array('status' => 201, 'error' => $returnArr);
                return $return;
            }
        } elseif ($company == 'DAL') {
            $this->load->helper('dal');
            $responseArray = dalArray($ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);
            // print_r($responseArray);die;
            if ($responseArray['status'] == 'true') {
                $return_array = array('status' => 200, 'label' => $responseArray['fastcoolabel'], 'client_awb' => $responseArray['client_awb']);
                return $return_array;
            } else {
                $return_array['responseError'][] = $slipNo . ':' . $responseArray['msg'];
                $return = array('status' => 201, 'error' => $return_array);
                return $return;
            }
        } elseif ($company == 'Ajex') {
            // echo "sjkgdfjhsgdfh"; die; 
            $this->load->helper('ajex');
            $responseArray  = AjexForward($ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id, $super_id);

            if ($responseArray['status'] == 'true') {
                $return_array = array('status' => 200, 'label' => $responseArray['fastcoolabel'], 'client_awb' => $responseArray['client_awb']);
                return $return_array;
            } else {
                $return_array['responseError'][] = $slipNo . ':' . $responseArray['msg'];
                $return = array('status' => 201, 'error' => $return_array);
                return $return;
            }
        } elseif ($company_type == 'F') { // for all fastcoo clients treat as a CC 
            if ($company == 'Ejack') {
                $response = $this->Ccompany_model->Ejack($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1, $super_id);
                $response = json_decode($response, true);
                if ($response['error'] == '') {
                    $generated_pdf = file_get_contents($response['awb_print_url']);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                    $client_awb = $response['awb'];

                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {

                    $returnArr['responseError'] = $slipNo . ':' . $response['refrence_id'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else if ($company == 'Emdad') {
                $response = $this->Ccompany_model->EmdadArray($sellername, $ShipArr, $counrierArr, $complete_sku, $c_id, $box_pieces1);
                $response = json_decode($response, true);
                if ($response['error'] == '') {
                    $generated_pdf = file_get_contents($response['awb_print_url']);
                    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);

                    $client_awb = $response['awb'];

                    $fastcoolabel = base_url() . "assets/all_labels/$slipNo.pdf";
                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {

                    $returnArr['responseError'] = $slipNo . ':' . $response['refrence_id'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            } else {

                $response = $this->Ccompany_model->fastcooArray($sellername, $ShipArr, $counrierArr, $complete_sku, $Auth_token, $c_id, $box_pieces1, $super_id);
                $responseArray = json_decode($response, true);
                if ($responseArray['status'] == 200) {
                    $client_awb = $responseArray['awb_no'];
                    $mediaData = $responseArray['label_print'];
                    //****************************fastcoo label print cURL****************************


                    file_put_contents("assets/all_labels/$slipNo.pdf", file_get_contents($mediaData));
                    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

                    //****************************fastcoo label print cURL****************************


                    $return = array('status' => 200, 'label' => $fastcoolabel, 'client_awb' => $client_awb);
                    return $return;
                } else {

                    $returnArr['responseError'] = $slipNo . ':' . $responseArray['msg'];
                    $return = array('status' => 201, 'error' => $returnArr);
                    return $return;
                }
            }
        } //end company type F code 

    }

    public function GetCompanylistDropTorod()
    {
        $CourierArr = $this->Ccompany_model->GetCompanylistDropQryTorod();
        // echo "<pre>"; print_r($CourierArr); die;
        $this->load->helper('torod');
        $token = TorodgetToken($CourierArr);
        // print_r($token);die;
        $API_URL = $CourierArr['api_url_t'] . "get-all/courier/partners";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $API_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer ' . $token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response, true);
        // echo "<pre>"; print_r($result['data']); die;
        echo json_encode($result['data']);
    }


    public function ShipmentLogview()
    {
        $this->load->view('generalsetting/ShipmentLogview', $data);
    }

    public function filter()
    {

        $_POST = json_decode(file_get_contents('php://input'), true);

        $delivered = $_POST['status'];
        $page_no = $_POST['page_no'];
        $awb = $_POST['slip_no'];
        $cc_id = $_POST['cc_id'];
        $status = $_POST['status'];

        $shipments = $this->General_model->getShipmentLogview($awb, $page_no, $cc_id, $status);

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
        foreach ($shipments['result'] as $rdata) {
            $shiparray[$ii]['cc_name'] = GetCCompanyNameById($rdata['cc_id'], 'company');
            $shiparray[$ii]['update_date'] =  date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($rdata['update_date'])));
            $ii++;
        }

        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        echo json_encode($dataArray);
    }

    public function WebhookCourier()
    {
        $postArr = json_decode(file_get_contents('php://input'), true);
        // print_r($postArr);die;
        $page_no = $postArr['page_no'];
        $cc_id = $postArr['cc_id'][0];
        $return = $this->Ccompany_model->WebhookCourierlist($cc_id, $page_no, $_POST);
        //   print_r($return);die;
        echo json_encode($return);
    }
    public function updatewebhookurl()
    {
        $postArr = json_decode(file_get_contents('php://input'), true);
        $cc_name = $postArr['cc_name'];
        $wehbookurl = $postArr['WEBHOOKURL'];
        $return = $this->Ccompany_model->updatewebhookurlqury($cc_name, $wehbookurl);
        echo json_encode($return);
    }

    public function Webhook()
    {
        $this->load->view('ShipmentM/Webhook');
    }
}
