<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function ForwardToshipsy($ShipArr = array(), $counrierArr = array(), $c_id = null, $box_pieces1 = null, $complete_sku = null, $super_id = null, $pay_mode = null)
{

    $slipNo = $ShipArr['slip_no'];
    $API_URL = $counrierArr['api_url'].'softdata'; 
    $token = $counrierArr['auth_token']; 
    $customer_code = $counrierArr['courier_account_no']; 
    $service_type = $counrierArr['service_code'];
    $company = $counrierArr['company'];


    $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'], 'label_info_from');

   
    if ($label_info_from == '1') {

        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'], 'company');
        if ($counrierArr['wharehouse_flag'] == 'Y') {
            $sellername = $sellername . " - " . site_configTable('company_name');
        }
        $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'], 'address');
        $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'], 'phone');
        $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'], 'email');
    } else {
        $sellername =  $ShipArr['sender_name'];
        if ($counrierArr['wharehouse_flag'] == 'Y') {
            $sellername = $sellername . " - " . site_configTable('company_name');
        }
        $sender_address = $ShipArr['sender_address'];
        $sender_phone = $ShipArr['sender_phone'];
        $sender_email = $ShipArr['sender_email'];
    }

    if (!empty($ShipArr['label_sender_name'])) {
        $sellername =  $ShipArr['label_sender_name'];
        if ($counrierArr['wharehouse_flag'] == 'Y') {
            $sellername = $sellername . " - " . site_configTable('company_name');
        }
    }
    $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsy_city', $super_id);
    $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'shipsy_city', $super_id);
    $sender_zipcode = getdestinationfieldshow_auto_array($ShipArr['origin'], 'shipsy_zipcode', $super_id);
    $receiver_zipcode = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsy_zipcode', $super_id);
    // echo "sender_city = ".$sender_city; 
    // echo "<br/>receiver_city = ".$receiver_city; //die;

    if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
        $weight = 1;
    } else {
        $weight = $ShipArr['weight'];
    }

    $box_pieces = empty($box_pieces1) ? 1 : $box_pieces1;

    if ($ShipArr['mode'] == "COD") {
        $codValue = $ShipArr['total_cod_amt'];
        $pay_mode = "cash";
    } elseif ($ShipArr['mode'] == 'CC') {
        $codValue = 0;
        $pay_mode = "cc";
    }
    $area_name = $ShipArr['area_name'];

    if(empty($receiver_city)){
        $successstatus = "Fail";
        $return_array =  array("error"=>"true","msg"=>'Receiver address/city empty');
        // return $return_array;	
    }
    if(empty($sender_city)){
        $successstatus = "Fail";
        $return_array =  array("error"=>"true","msg"=>'Sender address/city empty');
        // return $return_array;			
    }

    echo "sender_city = ".$sender_city; 
    echo "<br/>receiver_city = ".$receiver_city; 

    die; 

    $sender = ltrim($sender_phone, '0');
    $senderphone = '0' . $sender;

    $reciever_phone = $ShipArr['reciever_phone'];
    $reciever = ltrim($reciever_phone, '0');
    $recieverphone = '0' . $reciever;


    $currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
    $date = $currentDateTime->format('Y-m-d\TH:i:s.u\Z');
    //  print_r($date);die;

    $complete_sku = empty($complete_sku) ? $ShipArr['status_description'] : $complete_sku;
    $complete_sku = !empty($complete_sku) ? $complete_sku : 'Goods';

    

    $pieces_detail = array();
    for ($i = 1; $i <= $box_pieces; $i++) {
        $pieces_detail[] = array(
            "description" => $complete_sku,
            "declared_value" => $codValue,
            "weight" => $weight,
            "height" => '',
            "length" => '',
            "width" => '',
            "quantity" => 1,
            //"product_code"=> "",
            "piece_product_code" => "",
            "volume" => "",
            "volume_unit" => "",
            "dimension_unit" => "in",
            "weight_unit" => "kg",
            "additional_properties" => array(
                "ioss_number" => "",
                "ad_code" => "",
                "meis_applicable" => false,
                "hsn_code" => ""
            )
        );
    }
    $consignments[] = array(
        "customer_code" => $customer_code,
        "service_type_id" => $service_type,
        "load_type" => "NON-DOCUMENT",
        "description" => $complete_sku,
        "cod_favor_of" => "",
        "cod_collection_mode" => $pay_mode,
        "dimension_unit" => "cm",
        "length" => "",
        "width" => "",
        "height" => "",
        "weight_unit" => "kg",
        "weight" => $weight,
        "volumetric_weight" => $weight,
        "declared_value" => $codValue,
        'declared_price' => '',
        "cod_amount" => $codValue,
        'prepaid_amount' => '',
        "num_pieces" => $box_pieces,
        "customer_reference_number" => $ShipArr['slip_no'],
        "export_reference_number" => "",
        "is_risk_surcharge_applicable" => true,
        "inco_terms" => "",
        "hsn_code" => "",
        "invoice_url" => "",
        "invoice_number" => "",
        "invoice_date" => "",
        "origin_details" => array(
            "name" => $sellername,
            "phone" => $senderphone,
            "alternate_phone" => '',
            "address_line_1" => $sender_address,
            "address_line_2" => "",
            "pincode" => $sender_zipcode,
            "city" => $sender_city,
            "state" => '',
            "latitude" => "",
            "longitude" => "",

        ),

        "destination_details" => array(
            "name" => $ShipArr['reciever_name'],
            "phone" => $reciever_phone,
            "alternate_phone" => "",
            "address_line_1" => $ShipArr['reciever_address'],
            "address_line_2" => "",
            "pincode" => $receiver_zipcode,
            "city" => $receiver_city,
            "state" => '',
            "latitude" => "",
            "longitude" => "",
        ),

        "pieces_detail" => $pieces_detail


    );
    $all_param_array = array(
        "is_international" => '',
        "consignments" => $consignments

    );
    $json_final_data = json_encode($all_param_array);
    // echo "<pre>";
    // print_r($json_final_data);
    // die;


    // echo "<pre>";
    // print_r($counrierArr);
    // die;


    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $API_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
            'api-key:' . $token,
            'Content-Type: application/json'
        ),

    ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    $logresponse =   json_encode($response);
   
    $response_array = json_decode($response, true);
    $successres = $response_array['data'][0]['success'];

    if ($successres == 1) {
        $successstatus  = "Success";
        $slipNo = $ShipArr['slip_no'];
        $client_awb = $response_array['data'][0]['reference_number'];
        $LabelResponse = Shipsy_Label($counrierArr['api_url'], $client_awb, $counrierArr['auth_token'], $company, $super_id);

        file_put_contents("assets/all_labels/$slipNo.pdf", $LabelResponse);
        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
        $return_array = array("error" => 'false', "data" => array('client_awb' => $client_awb, 'label' => $fastcoolabel));
    } else {
        $successstatus  = "Fail";
        $res_error = $response_array['data'][0]['message'] . " - " . $response_array['data'][0]['reason'];
        $return_array =  array("error" => "true", "msg" => $res_error);
    }


    $CI = &get_instance();
    $CI->load->model('Ccompany_model');
    $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $json_final_data);

    return $return_array;
}
function Shipsy_Label($API_URL = null, $client_awb = null, $token = null, $company = null, $super_id = null)
{
    //$url = str_replace('softdata', 'shippinglabel/link?reference_number=', $counrierArr['api_url']);
    if ($company == 'Mahmool') {
        $url = $API_URL . 'shippinglabel/stream?reference_number=' . $client_awb . "&is_small=true";
    } elseif ($company == 'Zajil') {
        $url = $API_URL . "shippinglabel/stream?reference_number=$client_awb&is_small=true";
    } else {
        $url = $API_URL . "shippinglabel/stream?reference_number=" . $client_awb;
    }



    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'api-key:' . $token,
            'Content-Type: application/json'
        ),
    ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($curl);
    //$error = curl_error($curl);
    //echo "<pre>"; print_r($response); die;
    curl_close($curl);
    // $response = json_decode($response, true);

    // $labelURL = $response['data']['url'];
    //$labelURL = str_replace('isSmall=false', 'isSmall=true', $labelURL);

    return $response;
}
