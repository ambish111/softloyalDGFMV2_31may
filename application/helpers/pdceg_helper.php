<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function ForwardToPDC($ShipArr = array(), $counrierArr = array(), $c_id = null, $box_pieces1 = null, $complete_sku = null, $super_id = null, $pay_mode = null)
{
    $token = getPDCToken($counrierArr);
    // echo "<pre>"; print_r($ShipArr);die;
    if (empty($token)) {
        return array("status" => "false", "msg" => "Token Not Generated");
    } else {
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'pdc_eg_city', $super_id);
        $sender_region = getdestinationfieldshow_auto_array($ShipArr['origin'], 'pdc_region', $super_id);
        $sender_governorate = getdestinationfieldshow_auto_array($ShipArr['origin'], 'pdc_governorate', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'pdc_eg_city', $super_id);
        $reciver_governorate = getdestinationfieldshow_auto_array($ShipArr['destination'], 'pdc_governorate', $super_id);
        // print_r($reciever_country_code);die;
        $box_pieces = empty($box_pieces1) ? 1 : $box_pieces1;
        if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }
        if ($pay_mode == 'CC') {
            $cod_amount = 0;
        } elseif ($pay_mode == 'COD') {
            $cod_amount = $ShipArr['total_cod_amt'];
        }


        $seller_name = GetallCutomerBysellerId($ShipArr['cust_id'], 'name');
        $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'], 'address');
        $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'], 'phone');
        $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'], 'email');

        $sender = ltrim($sender_phone, '0');
        $senderphone = '0' . $sender;

        $reciever_phone = $ShipArr['reciever_phone'];
        $reciever = ltrim($reciever_phone, '0');
        $recieverphone = '0' . $reciever;


        $currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
        $date = $currentDateTime->format('Y-m-d\TH:i:s.u\Z');
        //  print_r($date);die;

        $slipNo = $ShipArr['slip_no'];
        $details = array(
            "shipperContactName" => $seller_name,
            "pickupType" => 1,
            "shipperContactPhone" => $senderphone,
            "shipperContactEmail" => $sender_email,
            "shipFromGovernorate" => $sender_governorate,
            "shipFromCity" => $sender_city,
            "shipFromRegion" => $sender_region, //$sender_city,
            "shipFromDistrict" => "",
            "shipFromAddress" => $sender_address,
            "pickupDate" => $date,
            "shipments" => array(
                array(
                    "referenceNumber" => $ShipArr['slip_no'],
                    "toGovernorate" => $reciver_governorate,
                    "toCity" => $receiver_city,
                    "toArea" => $ShipArr['area_name'],
                    "toDistrict" => "",
                    "toAddress" => $ShipArr['reciever_address'],
                    "productType" => $counrierArr['account_entity_code'],
                    "serviceType" => $counrierArr['service_code'],
                    "additionalServices" => "Next Day Delivery",
                    "shipToName" => $ShipArr['reciever_name'],
                    "shipToPhone" => $recieverphone,
                    "shipToEmail" => $ShipArr['reciever_email'],
                    "longitude" => "",
                    "latitude" => "",
                    "grossWight" => $weight,
                    "pieces" => $box_pieces,
                    "cashOnDelivery" => $cod_amount,
                    "declaredValue" => '',
                    "width" => '',
                    "height" => '',
                    "depth" => '',
                    "itemDescription" => $complete_sku,
                    "postalCode" => "",
                    "note" => $ShipArr['comment']
                )
            )
        );

        $json_final_data = json_encode($details);
        // echo "<pre>"; print_r($json_final_data);die;
        $api_url = $counrierArr['api_url'];
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $api_url . 'Integration/CreateShipments',
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
                    'Authorization:Bearer ' . $token
                ),

            )
        );
        $response = curl_exec($curl);
        curl_close($curl);

        // echo "<pre>"; print_r($response);die;
        $responseArray = json_decode($response, true);
        if ($responseArray['result']['summary'][0]['message'] == 'Success') {
            $successstatus = "Success";
            $client_awb = $responseArray['result']['summary'][0]['sequenceNumber'];
            // $media_data = $responseArray['result']['summary']['reportLink'];
            $media_data = $api_url . 'Integration/PrintShipmentReport/' . $client_awb;
            $generated_pdf = file_get_contents($media_data);
            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
            $return_array = array("status" => "true", "client_awb" => $client_awb, "fastcoolabel" => $fastcoolabel);

        } elseif(empty($responseArray['result']['summary'])) {
            $successstatus = "Fail";
            $return_array = array("status" => "false", "msg" => $responseArray['errors'][0]);
        } else {
            $successstatus = "Fail";
            $return_array = array("status" => "false", "msg" => $responseArray['result']['summary'][0]['message']);
        }
        $CI = &get_instance();
        $CI->load->model('Ccompany_model');
        $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $json_final_data);

        return $return_array;
    }
}

function getPDCToken($counrierArr = array())
{
    // print_r($counrierArr);die;
    $username = $counrierArr['user_name'];
    $password = $counrierArr['password'];

    $dataArr = array(
        'username' => $username,
        'password' => $password
    );
    $postdata = json_encode($dataArr, true);
    $api_url = $counrierArr['api_url'];
    $curl = curl_init();

    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => $api_url . 'Auth/GetToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        )
    );

    $Auth_response = curl_exec($curl);
    curl_close($curl);
    return $Auth_response;
}

