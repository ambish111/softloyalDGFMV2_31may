<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function AjexForward($ShipArr = array(), $counrierArr = array(), $complete_sku = null, $box_pieces1 = null, $c_id = null, $super_id=null)
{
    // print_r($ShipArr);die;
    $token = getajextoken($counrierArr);
    $API_URL = $counrierArr['api_url'] . "order-management/api/v2/order";

    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'], 'email');
    $receiver_email = !empty($ShipArr['reciever_email']) ? $ShipArr['reciever_email'] : 'no@no.com';
    $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
    $sender_phone = ltrim($sender_phone, '+966');    
    $sender_phone = ltrim($sender_phone, '0');    
    $sender_phone = '+966' . $sender_phone;    
    $sender_phone = str_replace(' ', '', $sender_phone);
    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');


    $senderaddress = GetallCutomerBysellerId($ShipArr['cust_id'], 'address');
    // print_r($senderaddress);die;
    $store_city = getallsellerdatabyID($ShipArr['cust_id'], 'city', $super_id);
    $sender_city = getdestinationfieldshow($store_city, 'ajex_city', $super_id);
    $sender_city_code = getdestinationfieldshow($ShipArr['origin'], 'ajex_city_code', $super_id);
    $sender_province = getdestinationfieldshow($ShipArr['origin'], 'ajex_province', $super_id);
    $sender_country = getdestinationfieldshow($ShipArr['origin'], 'country', $super_id);
    $sender_country_code = getdestinationfieldshow($store_city, 'country_code', $super_id);

    $reciver_province = getdestinationfieldshow($ShipArr['destination'], 'ajex_province', $super_id);
    $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'ajex_city', $super_id);
    $receiver_city_code = getdestinationfieldshow($ShipArr['destination'], 'ajex_city_code', $super_id);
    $receiver_country = getdestinationfieldshow($ShipArr['destination'], 'country', $super_id);
    $receiver_country_code = getdestinationfieldshow($ShipArr['destination'], 'country_code', $super_id);

    $codValue = ($ShipArr['mode'] == "COD") ? $ShipArr['total_cod_amt'] : 0;
    $box_pieces = empty($box_pieces1) ? 1 : $box_pieces1;
    $complete_sku = empty($complete_sku) ? 'Goods' : $complete_sku;
    $complete_sku = substr($complete_sku,0,100);
    $complete_sku = $utf8ArabicText = mb_convert_encoding($complete_sku, 'UTF-8', 'auto');
    if(empty($receiver_city)){
        return array("error" => "true", "msg" => 'Receiver city empty');
    }

    if ($ShipArr['weight'] == 0) {
        $weight = 1;
    } else if (($ShipArr['weight'] > 30)) {
        $weight = 30;
    } else {
        $weight = $ShipArr['weight'];
    }

    if ($ShipArr['mode'] == "COD") {
        $payment_mode = "";
        $cod_payment_mode = "CASH";

        $cod_details =  [
            [
            "serviceName"=> "IN01",
            "val1"=> $codValue,
            "val2"=> "SAR"
    ]];


    } else {
        $payment_mode = "PAID";
        $cod_payment_mode = "PAID";
        $cod_details = '';
    }



    $slipNo = $ShipArr['slip_no'];

    $currentDateTime = new DateTime();
    $timezone = new DateTimeZone('Asia/Tehran');
    $currentDateTime->setTimezone($timezone);
    $formattedDate = $currentDateTime->format('Y-m-d\TH:i:s.uP');

    if (empty($ShipArr['reciever_phone'])) {
        $successstatus = "Fail";
        $result = array("error" => "true", "msg" => 'Receiver phone empty');
        return $result;
    } else {
        $reciever_phone = $ShipArr['reciever_phone'];
        $reciever_phone = ltrim($reciever_phone, '+966');
        $reciever_phone = ltrim($reciever_phone, '0');
        $reciever_phone = '+966' . $reciever_phone;
        $reciever_phone = str_replace(' ', '', $reciever_phone);
    }
    $cargoInfo = array();
    for($i=0; $i<$box_pieces; $i++){ 
        
        // $cargoInfo[] = 
        //     array(
        //         "name" => $complete_sku,
        //         "count" => $box_pieces,
        //         "totalValue" => "",
        //         "sku" => $ShipArr['sku'],
        //         "hsCode" => "code",
        //         "countryOfOrigin" => $sender_country
        //     );

            $parcels[] = array(
                    "weight" => $weight,
                    "quantity" => $box_pieces,
                    "cargoInfo" => array(
                    array(
                        "name" => $complete_sku,
                        "count" => $box_pieces,
                        "totalValue" => "",
                        "sku" => $ShipArr['sku'],
                        "hsCode" => "code",
                        "countryOfOrigin" => $sender_country,
                    )
                    )
                );
    
                 
    }
    


    $param = array(
        "orderId" => $slipNo,
        "orderTime" => $formattedDate,
        "productCode" => $counrierArr['courier_pin_no'],
        "expressType" => $counrierArr['service_code'],
        "totalDeclaredValue" => $codValue,
        "declaredCurrency" => "SAR",
        "parcelTotalWeight" => $weight,
        "pickupMethod" => "PICKUP",
        "paymentMethod" => "SENDER_INSTALLMENT",
        "customerAccount" => $counrierArr['courier_account_no'],
        "senderInfo" => array(
            "name" => $sellername, //$ShipArr['sender_name'],
            "phone" => $sender_phone,
            "email" => $senderemail,
            "contactType" => "INDIVIDUAL",
            "addressType" => "LOOKUP",
            "country" => $sender_country,
            "countryCode" => $sender_country_code,
            "province" => $sender_province,
            "city" => $sender_city,
            "cityCode" => $sender_city_code,
            "district" => $sender_city,
            "detailedAddress" => $senderaddress
        ),
        "receiverInfo" => array(
            "name" => $ShipArr['reciever_name'],
            "phone" => $reciever_phone,
            "email" => $receiver_email,
            "contactType" => "INDIVIDUAL",
            "addressType" => "LOOKUP",
            "country" => $receiver_country,
            "countryCode" => $receiver_country_code,
            "province" => $reciver_province,
            "city" => $receiver_city,
            "cityCode" => $receiver_city_code,
            "district" => $receiver_city,
            "detailedAddress" => $ShipArr['reciever_address']
        ),
        "parcels" => $parcels
                        
    );
 

    if ($ShipArr['mode'] == "COD") {
        $param["addedServices"] = $cod_details;
    }
    
    //  echo "<pre>"; print_r($$API_URL);  
    //  echo  $API_URL ; die;
    $all_param_array = json_encode($param);
    // print_r($all_param_array);die;
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $API_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $all_param_array,

            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ),
        )
    );

    $response = curl_exec($curl);
    // print_r($response);die;
    curl_close($curl);

    $responseArray = json_decode($response, true);
    // print_r($responseArray);
    // die;

    if ($responseArray['responseMessage'] == 'Success') {
        $successstatus = "Success";
        $slipNo = $ShipArr['slip_no'];
        $client_awb = $responseArray['waybillNumber'];
        $LabelResponse = $responseArray['waybillFileUrl'];
        $gen_pdf = file_get_contents($LabelResponse);
        file_put_contents("assets/all_labels/$slipNo.pdf", $gen_pdf);
        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
        $return_array = array("status" => "true", "client_awb" => $client_awb, "fastcoolabel" => $fastcoolabel);
    } else {
        $successstatus = "Fail";
        $return_array = array("status" => "false", "msg" => $responseArray['responseMessage']);
    }

    $CI =& get_instance();
    $CI->load->model('Ccompany_model');
    $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $all_param_array);
    return $return_array;

}



function getajextoken($counrierArr=array())
{
    $api_url = $counrierArr['api_url'] . "authentication-service/api/auth/login";
    $data = array(
        "username" => $counrierArr['user_name'],
        "password" => $counrierArr['password']
    );
    $param = json_encode($data);
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        )
    );

    $response = curl_exec($curl);
    curl_close($curl);

    $responsearray = json_decode($response, true);
    if (!empty($responsearray)) {
        $token = $responsearray['accessToken'];
    } else {
        $token = "";
    }
    return $token;
}

?>