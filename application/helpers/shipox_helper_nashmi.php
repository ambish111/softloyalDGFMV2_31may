<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



function ForwardToShipox($sellername = null, $ShipArr= array(), $counrierArr= array(),  $c_id =null, $box_pieces1=null, $complete_sku =null, $super_id=null){
    
    $token = getShipoxToken($counrierArr);
    // print_r($token); die;
    if(empty($token)){
        return array("status"=>"false","msg"=>"Token Not Generated");
    }

   

    $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
    if($label_info_from == '1'){

        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        if($counrierArr['wharehouse_flag'] =='Y'){
            $sellername = $sellername ." - ". site_configTable('company_name'); 
        }
        $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
        $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
        $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

    }else{
        $sellername =  $ShipArr['sender_name'];
        if($counrierArr['wharehouse_flag'] =='Y'){
            $sellername = $sellername ." - ". site_configTable('company_name'); 
        }
    
        $store_address = $ShipArr['sender_address'];
        $senderphone = $ShipArr['sender_phone'];
        $senderemail = $ShipArr['sender_email'];
    }

    if(!empty($ShipArr['label_sender_name'])){
        $sellername =  $ShipArr['label_sender_name'];    
        if($counrierArr['wharehouse_flag'] =='Y'){
            $sellername = $sellername ." - ". site_configTable('company_name'); 
        }
    }

     $store_city = GetallCutomerBysellerId($ShipArr['cust_id'], 'city');
    // $seller__name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
    //  echo $store_city; die;

    
    // switch($counrierArr['company']){
    //     case 'Kudhha':          $city_column = 'kudhha_city';       break;
    //     case 'kudhha':          $city_column = 'kudhha_city';       break;
    //     case 'Flamingo':        $city_column = 'flamingo_city';     break;
    //     case 'Wadha':           $city_column = 'Wadha';             break;
    //     case 'Makhdoom':        $city_column = 'makhdoom';          break;
    //     case 'Gazal':           $city_column = 'gazal_city';        break;
    //     case 'Business Flow':   $city_column = 'business_flow_city';    break;
    //     case 'Safearrival':     $city_column = 'safe_arrival';        break;
    //     case 'SpeedMile':       $city_column = 'speed_mile';        break;

    //     default: $city_column = 'kudhha_city'; break;
    // }

    // if($super_id >0){
        
    //     $sender_city = getdestinationfieldshow_auto_array($store_city, 'shipox_city_name',$super_id);
    //     $coutry_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipox_country_code',$super_id);
    //     $receiver_city= getdestinationfieldshow_auto_array($ShipArr['destination'], $city_column, $super_id);
    //     $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);        
    //     $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);
    // }else{
    //     $sender_city = getdestinationfieldshow_auto_array($store_city, 'city');        
    //     $coutry_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code');
    //     $receiver_city= getdestinationfieldshow_auto_array($ShipArr['destination'], $city_column);
    // }
    // echo $receiver_city; die;
    

    if(empty($receiver_city)  || empty($coutry_code)){
        return array("status"=>"false","msg"=>"Receiver city or Country code is empty");
    }

    $allowed_city = array("Riyadh","RIYADH", "Ad Diriyah", "Diriyah","Dereiyeh","Dammam");
                
    if(in_array($receiver_city,$allowed_city)){
        switch($counrierArr['company']){
            case 'Kudhha':          $courier_type = 'INSIDE_RIYADH';        break;
            case 'kudhha':          $courier_type = 'INSIDE_RIYADH';        break;
            case 'Flamingo':        $courier_type = 'E_COMMERCE_DELIVERY';  break;
            case 'Wadha':           $courier_type = '01';                   break;
            case 'Makhdoom':        $courier_type = 'EXPRESS_DELIVERY';     break;
            case 'Business Flow':        $courier_type = 'EXPRESS_DELIVERY';     break;
            case 'Gazal':           $courier_type = 'SAME';                 break;
            case 'Lastpoint':       $courier_type = 'NEXT_DAY_DELIVERY';    break;
            case 'Safearrival':     $courier_type = 'IN_5_DAYS';            break;
            case 'SpeedMile':       $courier_type = 'NEXT_DAY_DELIVERY';    break;
            default: $courier_type = 'NEXT_DAY_DELIVERY'; break;
        }
    }
    else if($coutry_code != "SA" ){
        $courier_type = 'INTERNATIONAL_DELIVERY';
    }else{
        $courier_type = 'DOMESTIC_DELIVERY';                    
    }
    if($coutry_code == "SA" || $coutry_code == "KSA"){
        if(empty($coutry_ID)){
            $coutry_ID = 191;
        }
    }
    // echo  $courier_type; die;
    $currency = "SAR";
        
    $box_pieces = empty($box_pieces1)?1:$box_pieces1;
    $weight = ($ShipArr['weight'] == 0)?1:$ShipArr['weight'];
    
    if($ShipArr['mode'] == "COD"){
        $pay_mode = "credit_balance";
        if($counrierArr['company']  == 'SpeedMile'){
            $pay_mode = "cash";
        }
        
        $cod_amount = $ShipArr['total_cod_amt'];
        $paid = FALSE;
    }
    elseif ($ShipArr['mode'] == 'CC'){
        $pay_mode = "credit_balance";
        if($counrierArr['company']  == 'SpeedMile'){
            $pay_mode = "cash";
        }
        $paid = TRUE;
        $cod_amount = 0;
    }

    
    if(($counrierArr['company']  == 'Lastpoint') || ($courier_type == 'INTERNATIONAL_DELIVERY')){
        $cod_amount_parcel = 250;
    }
    else {
        $cod_amount_parcel = $cod_amount;
    }
    // echo $cod_amount ; 
    $API_URL = $counrierArr['api_url'] . "v2/customer/order";

    $sender_data = array(
            'address_type' => 'residential',
            'name' =>$sellername,
            'email' => $senderemail,
            'apartment'=> "",
            'building' => '',
            'street' => $store_address,
            "city" => array(
                "name" =>$sender_city
            ),
            "country" => array(
                "id" => 191
            ),
            'phone' =>$senderphone,
        );
          

        $receiverdata = array(
        'address_type' => 'residential',
        'name'=> $ShipArr['reciever_name'],
        'street' => $ShipArr['reciever_address'],
        'city' => array(
                'name' => $receiver_city
            ),
        'phone' => $ShipArr['reciever_phone'],
        'landmark' => $ShipArr['reciever_address']);


    $dimensions = array(
        'weight' => $weight,
        'width' =>  '',
        'length' => '',
        'height' =>'' ,
        'unit' => '',
        'domestic' => true
    );
    if($counrierArr['company'] == 'Business Flow'){
        $package_type = array(
            "id"=> "1259830076",
            'courier_type' => $courier_type,//"NEXT_DAY_DELIVERY"
             "package_price"=>array(
                    'id' =>  "13" //1235869296,
                )
        );
    }else{
        $package_type = array(
            "id"=> "546237328",
            'courier_type' => $courier_type,//"NEXT_DAY_DELIVERY"
             "package_price"=>array(
                    'id' =>  "1093378771" //1235869296,
                )
        );
    }
    

    $charge_items[] = array(
        'paid' => $paid,
        'charge' => $cod_amount,
        'charge_type' => "COD"               
    );

    $details = array(
        'sender_data' => $sender_data,
        'recipient_data' => $receiverdata,
        'dimensions' => $dimensions,
        'package_type' => $package_type,
        'charge_items' => $charge_items,
        'recipient_not_available' => 'do_not_deliver',
        'payment_type' => $pay_mode,
        'payer' => 'sender',
        'parcel_value' => $cod_amount_parcel,
        'fragile' => true,
        'note' => $complete_sku,
        'piece_count' => $box_pieces,
        'force_create' => true,
        "reference_id" => $ShipArr['slip_no']
    );

    $slipNo = $ShipArr['slip_no'];
    $json_final_data = json_encode($details);
    //   echo $json_final_data;die;
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
        CURLOPT_POSTFIELDS => $json_final_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization:Bearer ' .$token),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $responseArray = json_decode($response, true);
    // print "<pre>";print_r($responseArray);die;
    

    if(!empty($responseArray['status']) && $responseArray['status'] == 'success'){
        $successstatus = "Success";
        $client_awb = $responseArray['data']['order_number'];
        $label_response = shipoxLabelGenerate($client_awb, $counrierArr, $token);
        $label= json_decode($label_response,TRUE);
        $media_data = $label['data']['value'];
        // print_r( $media_data); die;
        $generated_pdf = file_get_contents($media_data);
        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
        $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';

        $return_array =  array("status"=>"true","client_awb"=>$client_awb,"fastcoolabel"=>$fastcoolabel);
    }else{
        $successstatus = "Fail";
        $return_array =  array("status"=>"false","msg"=>$responseArray['message']);
    }

    $CI =& get_instance();
    $CI->load->model('Ccompany_model');
    $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_data);
    return $return_array;
}


function shipoxLabelGenerate($client_awb =null, $counrierArr= array(), $token=null){

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => $counrierArr['api_url']."v1/customer/orders/airwaybill_mini?ids=&order_numbers=".$client_awb,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization:Bearer ' .$token),
            ));



        $label_response = curl_exec($curl);
        
        curl_close($curl);
        return  $label_response;


}


function getShipoxToken($counrierArr = array()){

        $user_name = $counrierArr['user_name'] ;
        $password =  $counrierArr['password'] ;
        $api_url = $counrierArr['api_url'];

        $param= array(  'username'=>$user_name,
                        'password'=>  $password,
                        'remember_me'=>true
                    );
        $dataJson =json_encode($param);
    
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url."v1/customer/authenticate",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$dataJson,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Accept: application/json'
        ),
        ));

        $Auth_response = curl_exec($curl);

        curl_close($curl);
        $responseArray = json_decode($Auth_response, true);
        $Auth_token = $responseArray['data']['id_token'];
        
    return $Auth_token;
}


function SMSAArr_BAK($ShipArr =array(), $counrierArr= array(), $complete_sku=null,$box_pieces1=null,$c_id=null,$super_id=null){
    
        $api_url = $counrierArr['api_url'].'GenerateAWBWithLabel';
        
        if($super_id >0){
            
            $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'samsa_city', $super_id);
            $sender_city = getdestinationfieldshow($ShipArr['origin'], 'samsa_city' ,$super_id);
    
            $sender_country_Code = getdestinationfieldshow($ShipArr['origin'], 'country_code' ,$super_id);
            $receiver_country_Code = getdestinationfieldshow($ShipArr['destination'], 'country_code', $super_id);
            $currency = getdestinationfieldshow($ShipArr['destination'], 'currency', $super_id);

        }else{

            $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'samsa_city');
            $sender_city = getdestinationfieldshow($ShipArr['origin'], 'samsa_city');

            $sender_country_Code = getdestinationfieldshow($ShipArr['origin'], 'country_code');
            $receiver_country_Code = getdestinationfieldshow($ShipArr['destination'], 'country_code');
            $currency = getdestinationfieldshow($ShipArr['destination'], 'currency');
        }
        

        $store_address = GetallCutomerBysellerId($ShipArr['cust_id'], 'address'); 
        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
        $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        
        
        if (empty($box_pieces1)){
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }
        
        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        $complete_sku = empty($complete_sku)?$complete_sku:'Goods';
        
        $CI =& get_instance();
        $CI->load->model('Ccompany_model');


        if($CI->session->userdata('user_details')['super_id']=='490')
	        $complete_sku=	mb_substr($complete_sku,0,50,"UTF-8"); 


        $date = date('Y-m-d');
        $time = date('H:i:s');
        $date_time=  $date."T".$time;

        $complete_sku =$CI->Ccompany_model->stripInvalidXml($complete_sku);
        $reciever_address = $CI->Ccompany_model->stripInvalidXml($ShipArr['reciever_address']);
        $receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
        $receiver_name = $CI->Ccompany_model->stripInvalidXml($ShipArr['reciever_name']);
        
        $xml_string = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:way=\"http://smsaexpress.com/waybills/\">
                                <soapenv:Header/>
                                    <soapenv:Body>
                                        <way:GenerateAWBWithLabel>
                                            <way:username>".$counrierArr['user_name']."</way:username>
                                            <way:password>".$counrierArr['password']."</way:password>
                                            <way:Reference>".$ShipArr['slip_no']."</way:Reference>
                                            <way:senderName>".$sellername."</way:senderName>
                                            <way:senderPhone>".$senderphone."</way:senderPhone>
                                            <way:senderAddress1>".$store_address."</way:senderAddress1>
                                            <way:senderAddress2></way:senderAddress2>
                                            <way:senderCity>".$sender_city."</way:senderCity>
                                            <way:senderCountry>".$sender_country_Code."</way:senderCountry>
                                            <way:recName>".$receiver_name."</way:recName>
                                            <way:recPhone>".$ShipArr['reciever_phone']."</way:recPhone>
                                            <way:recAddress1>".$reciever_address."</way:recAddress1>
                                            <way:recAddress2></way:recAddress2>
                                            <way:recCity>".$receiver_city."</way:recCity>
                                            <way:recCountry>".$receiver_country_Code."</way:recCountry>
                                            <way:ShipDate>".$date_time."</way:ShipDate>
                                            <way:parcels>".$box_pieces."</way:parcels>
                                            <way:weight>".$weight."</way:weight>
                                            <way:weightUnit>KG</way:weightUnit>
                                            <way:DV>".$cod_amount."</way:DV>
                                            <way:Lat></way:Lat>
                                            <way:Lng></way:Lng>
                                            <way:ContentDesc>".$complete_sku."</way:ContentDesc>
                                            <way:ServiceCode>SI</way:ServiceCode>
                                            <way:fileType>2</way:fileType>
                                        </way:GenerateAWBWithLabel>
                                    </soapenv:Body>
                                </soapenv:Envelope>";



            $comp_api_url = "http://test-smsa.cloudapp.net:8082/WaybillService.svc";
            $Allarra = array('api_url' => $api_url,'comp_api_url'=>$comp_api_url,'data'=>$xml_string);                                
            
            $dataJson = json_encode($Allarra);

            $url = $url =  "http://fastcoo.net/fastcoo-tech/fs_files/smsa_b2b_api.php"; 

            $headers = array("Content-type: application/json");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
            $response = curl_exec($ch);
            
            curl_close($ch);

            $respon = trim($response);
            $respon = str_ireplace(array("s:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $response);
            
            $xml2 = new SimpleXMLElement($respon);
            
            $again = $xml2;
            $a = array("qwb" => $again);
            
            $complicated = ($a['qwb']->Body->GenerateAWBWithLabelResponse->GenerateAWBWithLabelResult);
            
            if ($complicated->Status == 1) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            $CI =& get_instance();
            $CI->load->model('Ccompany_model');

            $CI->Ccompany_model->shipmentLog($c_id, $complicated,$successstatus, $ShipArr['slip_no'], $xml_string);
    return $complicated;
    
}



?>