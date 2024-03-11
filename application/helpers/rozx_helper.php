<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function ForwardToRozx($ShipArr = array(), $counrierArr = array(),  $c_id = null, $box_pieces1 = null, $complete_sku = null, $super_id = null)
{

    $token = getRozxToken($counrierArr);
    
    if (empty($token)) {
        return array("status" => "false", "msg" => "Token Not Generated");
    }
   
    $sender_city_code = getdestinationfieldshow($ShipArr['origin'], 'rozx_city_code', $super_id);
    $reciever_city_code = getdestinationfieldshow($ShipArr['destination'], 'rozx_city_code', $super_id);
    // print_r( $sender_city_code);die;
    $sender_city = getdestinationfieldshow($ShipArr['origin'], 'rozx_city', $super_id);
    $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'rozx_city', $super_id);
    // print_r( $receiver_city);die;
    $sender_country_code = getdestinationfieldshow($ShipArr['origin'], 'country_code', $super_id);
    $reciever_country_code = getdestinationfieldshow($ShipArr['destination'], 'country_code', $super_id);
    // print_r($reciever_country_code);die;
    $box_pieces = empty($box_pieces1) ? 1 : $box_pieces1;
    // $weight = ($ShipArr['weight'] == 0) ? 1 : (int)$ShipArr['weight'];
    if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
        $weight = 1;
    }else {
        $weight = $ShipArr['weight'];
    }

    $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
    if($label_info_from == '1'){
        $seller_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        if($counrierArr['wharehouse_flag'] =='Y'){
            $seller_name = $seller_name ." - ". site_configTable('company_name'); 
        }
        $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
        $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
        $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
    }else{
        $seller_name =  $ShipArr['sender_name'];
        if($counrierArr['wharehouse_flag'] =='Y'){
            $seller_name = $seller_name ." - ". site_configTable('company_name'); 
        }
        $sender_address = $ShipArr['sender_address'];
        $sender_phone = $ShipArr['sender_phone'];
        $sender_email = $ShipArr['sender_email'];
    }
    
    if(!empty($ShipArr['label_sender_name'])){
        $seller_name =  $ShipArr['label_sender_name'];    
        if($counrierArr['wharehouse_flag'] =='Y'){
            $seller_name = $seller_name ." - ". site_configTable('company_name'); 
        }
    }

    if( empty($reciever_city_code) || empty($reciever_city_code)){
        $successstatus = "Fail";
        $return_array =   array("error" => "true", "msg" => 'Receiver city and city code empty');
        return   $return_array ;
    }

// echo " reciever_city_code = ".$reciever_city_code; die ; 


    $sender_data = array(
        "name" => $seller_name,//$ShipArr['sender_name'], //$sellername,
        "country_code" => $sender_country_code,
        "city_code" => $sender_city_code, //"RUH",  
        "address" =>$sender_address . " " . $sender_city,  //$store_address,
        "phone" => $sender_phone,  //$senderphone,
        "email" =>$sender_email   //$senderemail     
    );

    $receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
    $receiverdata = array(
        "name" => $ShipArr['reciever_name'],
        "country_code" => $reciever_country_code,
        "city_code" => $reciever_city_code, //"RUH",
        "address" => $ShipArr['reciever_address'] . " " . $receiver_city,
        "zip_code" => $ShipArr['reciever_pincode'],
        "phone" => $ShipArr['reciever_phone'],
        "phone2" => $ShipArr['reciever_phone'],         //"09419518549",
        "email" => $receiver_email
    );

    $slipNo = $ShipArr['slip_no'];
    //    print "<pre>"; print_r($receiverdata);die;
    
    $details = array(
        "receiver" => $receiverdata,
        "sender" => $sender_data,
        "reference" => $slipNo,
        "pick_date" => "",
        "pickup_time" => "",
        "product_type" => $counrierArr['account_entity_code'],
        "payment_mode" => $ShipArr['mode'],
        "parcel_quantity" => $box_pieces,
        "parcel_weight" => $weight,
//        "service_id" => $counrierArr['service_code'],    // $ShipArr['service_id'], //6
        "service_id" => $counrierArr['courier_pin_no'],    // $ShipArr['service_id'], //6
        "description" => $ShipArr['status_describtion'],     //"Testing Create Shipment From API",
        "sku" => "ytdjd665677", //$ShipArr['sku'],
        "weight_total" => $weight,
        "total_cod_amount" => $ShipArr['total_cod_amt'],
        "customer_branch_id" => ""
    );

    $json_final_data = json_encode($details);
    // print_r($json_final_data);die;
    $api_url = $counrierArr['api_url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url . 'shipment/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_final_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization:Bearer ' . $token
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $responseArray = json_decode($response, true);
    //    print "<pre>";print_r($responseArray);die;
    if (!empty($responseArray['Shipment']) && !empty($responseArray['printLable'])) {
        $successstatus = "Success";
        $client_awb = $responseArray['TrackingNumber'];
        $media_data = $responseArray['printLable'];
        $generated_pdf = file_get_contents($media_data);
        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
        $return_array =  array("status" => "true", "client_awb" => $client_awb, "fastcoolabel" => $fastcoolabel);
    } else {
        $successstatus = "Fail";
        $return_array =  array("status" => "false", "msg" => $responseArray['message']);
    }
    $CI = &get_instance();
    $CI->load->model('Ccompany_model');
    $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $json_final_data);

    return $return_array;
}

function getRozxToken($counrierArr = array())
{
//     print "<pre>"; print_r($counrierArr);die;
    $client_secret = $counrierArr['password'];
//    $client_id =  $counrierArr['courier_account_no'];
    $client_id =  $counrierArr['courier_account_no'] ;
    $username = $counrierArr['user_name'];
    $password = $counrierArr['password'];

    $dataArr = array(
        'client_secret' => $client_secret,
        'client_id' =>  $client_id,
        'username' => $username,
        'password' => $password
    );
//    print "<pre>"; print_r($dataArr);die;
    $postdata = http_build_query($dataArr);
    $api_url = $counrierArr['api_url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url . 'authorize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "$postdata",
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));

    $Auth_response = curl_exec($curl);
//    echo $Auth_response;die;
    curl_close($curl);
    $responseArray = json_decode($Auth_response, true);
    $Auth_token = $responseArray['access_token'];
    // print_r($responseArray);die;
    return $Auth_token;
}
