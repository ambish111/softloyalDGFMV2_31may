<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function dalArray($ShipArr = array(), $counrierArr = array(), $complete_sku = null, $box_pieces1 = null, $c_id = null, $super_id = null )
{

    $token = $counrierArr['auth_token'];
    $url = $counrierArr['api_url'];
    //    print_r($counrierArr); die;
    $slipNo = $ShipArr['slip_no'];
    $complete_sku = empty($complete_sku) ? 'Goods' : $complete_sku;
    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'], 'company');
    $seller =  $ShipArr['sender_name'];
    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'], 'email');
    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'], 'phone');
    $sender_lat =   getdestinationfieldshow($ShipArr['origin'], 'latitute', $super_id); // GetallCutomerBysellerId($ShipArr['cust_id'], 'lat');
    $sender_longi = getdestinationfieldshow($ShipArr['origin'], 'longitute', $super_id); // GetallCutomerBysellerId($ShipArr['cust_id'], 'lng');
    $receiver_name = $ShipArr['reciever_name'];
    $cod_amount = $ShipArr['total_cod_amt'];
    $reciever_city = getdestinationfieldshow($ShipArr['destination'], 'dal_city', $super_id);
    $sender_city = getdestinationfieldshow($ShipArr['origin'], 'dal_city', $super_id);
    // print_r($longitude); die;
    if(empty($ShipArr['reciever_email'])){
        $receiver_email= 'no@no.com';
    }else {
        $receiver_email= $ShipArr['reciever_email'];
    }

    $itemArr = array(
            array(
            "name"=>$complete_sku,
            "serial"=>$slipNo,
            "price"=>$ShipArr['total_cod_amt'],
            "requires_scan_before_dispatch"=>0,
            "requires_scan_upon_delivery"=>0,
            "requires_activation"=>0,
            "scanned_for_dispatch"=>0
        )
    );
    $journey_code = $counrierArr['service_code'];
    $organization_code = $counrierArr['courier_account_no'];
    $lmd_code = $counrierArr['account_entity_code'];
    // print_r($organization_code);die;
    $item_string = json_encode($itemArr);

    $shipment1 = array(
        "organization_code" => $organization_code, // 'fastcoo',
        "lmd_code" => $lmd_code, // "HCC109",
        "journey_code" => $journey_code, // "delivery",
        "sender_full_name" => $sellername,
        "sender_long" =>$sender_lat ,
        "sender_lat" =>$sender_longi ,
        "sender_mobile" => $senderphone,
        "receiver_full_name" => $receiver_name,
        "receiver_long" => $ShipArr['longitude'],
        "receiver_lat" => $ShipArr['latitude'],
        "receiver_mobile" => $ShipArr['reciever_phone'],
        "bill_reference_no" => $ShipArr['slip_no'],
        "bill_amount" => $cod_amount,
        "collect_amount" => $cod_amount,
        'items' =>$item_string,
        "sender_address" => $ShipArr['sender_address'] . " " . $sender_city,
        "receiver_address" => $ShipArr['reciever_address'] . " " . $reciever_city,
        "external_otp" => "",
        "sender_email" => $senderemail,
        "receiver_email" => $receiver_email,
        "created_manually" => "",
        "agent_groups" =>"[$journey_code]",

    );
    // print_r($shipment1);die;
    $json_string = json_encode($shipment1);

    // echo "<pre>"; print_r($shipment1); die;
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url.'/create',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $shipment1,

        CURLOPT_HTTPHEADER => array(
            'Api-Key:'.$token,
            'lang: ar',
            'App-Version: 100',
            'User-Agent:PostmanRuntime/7.32.3'
        ),
    )
    );

    $response = curl_exec($curl);
   
    curl_close($curl);

    $responsssse = json_decode($response, true);
    // print_r($responsssse); // die;
    if (!empty($responsssse['response']['shipment_id'])) {
        $client_awb = $responsssse['response']['shipment_id'];
        $successstatus = "Success";
        $generated_pdf = dalLabelGenerate($url, $client_awb, $token);
        $label = file_get_contents($generated_pdf);
        file_put_contents("assets/all_labels/$slipNo.pdf", $label);
        $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
        $return_array = array("status" => "true", "client_awb" => $client_awb, "fastcoolabel" => $fastcoolabel);
    } else {
        $successstatus = "Fail";
        $return_array = array("status" => "false", "msg" => $responsssse['message']);
    }

    $CI =& get_instance();
    $CI->load->model('Ccompany_model');
    $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $json_string);
    return $return_array;

}



function dalLabelGenerate($url, $client_awb, $token)
{
    $curl = curl_init();
    $apiurl = $url.'/getShipmentLabelLink';
    // print_r($token);die;
    $awb = array (
        "shipment_id"=>$client_awb
    );
    $shipmentid = json_encode($awb);
    curl_setopt_array($curl, array(
      CURLOPT_URL => $apiurl,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$shipmentid,
      CURLOPT_HTTPHEADER => array(
        'Api-Key:'.$token,
        'App-Version: 100',
        'Content-Type: application/json',  
        'User-Agent:PostmanRuntime/7.32.3'
      ),
    ));
    
    $response = curl_exec($curl);
    
    
    curl_close($curl);
    $data = json_decode($response, true);
    // print_r($data); die;
    return $data['response'][0]['parcel_label'];
}
?>