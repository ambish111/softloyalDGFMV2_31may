<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
   
function SmsaReverse($ShipArr= array(), $counrierArr= array(), $c_id =null, $box_pieces1 =null, $complete_sku =null,$super_id=null){
    
     $api_url = $counrierArr['api_url'].'c2b/new';
    $token = $counrierArr['auth_token'];
    //$host = 'https://fm.diggipacks.com/';

    $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'samsa_city', $super_id);        
    $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);
    //    echo "<pre>"; print_r($ShipArr);
    //    die;  
    $store_address = $ShipArr['sender_address'];
    $senderemail =$ShipArr['sender_email'];
    $senderphone =$ShipArr['sender_phone'];

    $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'samsa_city',$super_id);
    $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code', $super_id);
    $currency = getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency', $super_id);
    
    if(empty($sender_city)){
        $return_array =  array("error"=>"true","msg"=>'Sender City is Empty.');
        return $return_array;
    }
    if(empty($sender_country_code)){
        $return_array =  array("error"=>"true","msg"=>'Sender Country Code is Empty.');
        return $return_array;
    }

    if(empty($receiver_city)){
        $return_array =  array("error"=>"true","msg"=>'Receiver City is Empty.');
        return $return_array;
    }
    if(empty($receiver_country_code)){
        $return_array =  array("error"=>"true","msg"=>'Receiver Country Code is Empty.');
        return $return_array;
    }
    $currency = "SAR";//"EGP"; 

    if (empty($box_pieces1)){
        $box_pieces = 1;
    } else {
        $box_pieces = $box_pieces1;
    }
    
    if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
    $weight = 1;
}else {
    $weight = $ShipArr['weight'];
}

    $complete_sku = !empty($complete_sku)?$complete_sku:'Goods';
    $cod_amount= 0;
    if($ShipArr['mode'] == "COD"){
        $cod_amount = "00";
    }
    $slipNo = $ShipArr['slip_no'];
    $sdendStartTime = date('Y-m-d')."T".date('H:i:s');
    // echo  $sdendStartTime; die;
    if($receiver_country_code == 'KSA'){
        $receiver_country_code = 'SA';
    }
    if($sender_country_code == 'KSA'){
        $sender_country_code = 'SA';
    }
    $pickup_array = array(
        "ContactName"=> $ShipArr['reciever_name'],
        "ContactPhoneNumber"=> $ShipArr['reciever_phone'],
        "Coordinates"=> "",
        "Country"=> $receiver_country_code,
        "District"=> $receiver_city,
        "PostalCode"=> "",
        "City"=> $receiver_city,
        "AddressLine1"=>  $ShipArr['reciever_address'],
        "AddressLine2"=> ""
    );

    $return_array = array(
        "ContactName"=> $ShipArr['sender_name'],
        "ContactPhoneNumber"=> $ShipArr['sender_phone'],
        "Coordinates"=> "",
        "Country"=> $sender_country_code,
        "District"=> $sender_city,
        "PostalCode"=> "",
        "City"=> $sender_city,
        "AddressLine1"=> $store_address,
        "AddressLine2"=> ""
    );
    $all_data = array(
        "PickupAddress"=>$return_array,
        "ReturnToAddress"=>$pickup_array,
        "OrderNumber"=> $slipNo,
        "DeclaredValue"=> 0.1,
        "Parcels"=> $box_pieces,
        "ShipDate"=> $sdendStartTime,
        "ShipmentCurrency"=> $currency,
        "SMSARetailID"=> "1",
        "WaybillType"=> "PDF",
        "Weight"=> $weight,
        "WeightUnit"=> "KG",
        "ContentDescription"=> $complete_sku
    );

    $json_final_data = json_encode($all_data);
    

        //ambika 

    $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://ecomapis.smsaexpress.com/api/c2b/new',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json_final_data,
        CURLOPT_HTTPHEADER => array(
            'apikey:'.$token,
            'Content-Type: application/json',
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
       
    $result = json_decode($response,true);
    //echo "<pre>"; print_r($result); die;
    if ($result['sawb'] != '') 
    {
            $successstatus = "Success";

            $client_awb = $result['waybills'][0]['awb'];           
            $label_response = $result['waybills'][0]['awbFile'];
            
            sleep(3);
            $img = base64_decode($label_response);
            file_put_contents("assets/all_labels/$slipNo.pdf", $img);
            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';            
            $return_array = array("msg"=>'success',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel));

    } else {
            $successstatus = "Fail";
            $return_array =  array("error"=>"true","msg"=>$result['msg']);
    }

    $CI =& get_instance();
    $CI->load->model('Ccompany_model');

    $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_data);
    return $return_array;
    
}



?>