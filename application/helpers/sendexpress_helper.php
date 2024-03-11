<?php 
if (!defined('BASEPATH'))
 exit('No direct script access allowed');
//  error_reporting(E_ALL); 
//  ini_set('display_errors', true);
//  ini_set('display_startup_errors', true);
//  date_default_timezone_set("Asia/Riyadh");
function ForwardToSendExpress($ShipArr= array(), $counrierArr= array(),  $c_id =null, $box_pieces1=null, $complete_sku =null, $super_id=null){
    // print_r($counrierArr);die;
    $store_city = GetallCutomerBysellerId($ShipArr['cust_id'],'city');
    
    if($counrierArr['company'] == 'Send Express'){
        $sender_city = getdestinationfieldshow($store_city, 'send_express_city',$super_id);
        $reciever_city = getdestinationfieldshow($ShipArr['destination'], 'send_express_city',$super_id);
    }
    elseif($counrierArr['company'] == 'DRB Logistics'){
        $sender_city = getdestinationfieldshow($store_city, 'drb_logistics_city',$super_id);
        $reciever_city = getdestinationfieldshow($ShipArr['destination'], 'drb_logistics_city',$super_id);
    }

    // echo $sender_city." <br>reciever_city = ". $reciever_city;  die;

    // $sender_city = getdestinationfieldshow($store_city, 'send_express_city',$super_id);
    // $reciever_city = getdestinationfieldshow($ShipArr['destination'], 'send_express_city',$super_id);
    // print_r($reciever_city);die;
    $senderIds=getcityids($sender_city,$counrierArr);
    // echo "<pre> senderIds ";print_r($senderIds);
    if(empty($senderIds['data'][0]['id']))
    {
        return array("status"=>"false","msg"=>"Invalid Sender City");
    }
    $senderVillageId=$senderIds['data'][0]['id'];
    $senderCityId=$senderIds['data'][0]['cityId'];
    $senderRegionId=$senderIds['data'][0]['regionId'];
    $receiverIds=getcityids($reciever_city,$counrierArr);
    // echo "<pre> receiverIds "; print_r($receiverIds);die;


    if(empty($receiverIds['data'][0]['id'])){
        return array("status"=>"false","msg"=>"Invalid Reciever City");
    }
    $recieverVillageId=$receiverIds['data'][0]['id'];
    $recieverCityId=$receiverIds['data'][0]['cityId'];
    $recieverRegionId=$receiverIds['data'][0]['regionId'];
    $slipNo=$ShipArr['slip_no'];
    $box_pieces = empty($box_pieces1)?1:$box_pieces1;

    if($ShipArr['mode'] == "COD"){
        $pay_mode = "COD";   
    }elseif($ShipArr['mode'] == "REGULAR"){
        $pay_mode="REGULAR";
    }elseif($ShipArr['mode'] == "SWAP"){
        $pay_mode="SWAP";
    }elseif($ShipArr['mode'] == "BRING"){
        $pay_mode="BRING";
    }else{
        $pay_mode="COD";
    }
    
    $complete_sku = mb_substr($complete_sku,0,200,"UTF-8"); 
    $status_describtion = mb_substr($ShipArr['status_describtion'],0,200,"UTF-8"); 
  

    $data=array(
        "email"=>$counrierArr['user_name'],
        "password"=> $counrierArr['password'],
        "pkg"=> array(
                "cod"=> $ShipArr['total_cod_amt'], //"600",
                "notes"=> $complete_sku, //substr($complete_sku,200),
                "invoiceNumber"=> $slipNo,
                "senderName"=> $ShipArr['sender_name'], 
                "businessSenderName"=>$ShipArr['sender_name'],
                "senderPhone"=> $ShipArr['sender_phone'],
                "receiverName"=>$ShipArr['reciever_name'],
                "receiverPhone"=> $ShipArr['reciever_phone'],
                "receiverPhone2"=> "",
                "serviceType"=> "STANDARD",
                "shipmentType"=>$pay_mode,
                "quantity"=>$box_pieces,
                "description"=>$status_describtion ,
            ),
        "destinationAddress"=> array(
                "addressLine1"=> $ShipArr['reciever_address'], 
                "cityId"=>$recieverCityId,
                "villageId"=>$recieverVillageId,
                "regionId"=>$recieverRegionId
        ),
        "pkgUnitType"=> "METRIC",
        "originAddress"=> array(
                "addressLine1"=>$ShipArr['sender_address'],
                "addressLine2"=> "",
                "cityId"=>$senderCityId, 
                "regionId"=>$senderRegionId,
                "villageId"=>$senderVillageId
        )   
    );
    $postdata=json_encode($data);
    // print_r($postdata);die;


    $api_url=$counrierArr['api_url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => $api_url.'ship/request/by-email',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$postdata,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Company-ID: '.$counrierArr['courier_pin_no']
    ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    // echo $response;
    $responseArray = json_decode($response, true);
    //    print "<pre>";print_r($responseArray);die;
    if(!empty($responseArray['barcode'])){
        $successstatus = "Success";
        $client_awb = $responseArray['barcode'];
        $ids= $responseArray['id'];
        $media_data = generateLabel($ids,$counrierArr);
        
        $lebleurl=$media_data['url'];
        
        $generated_pdf = file_get_contents($lebleurl);
        file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
        $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';
        $return_array =  array("status"=>"true","client_awb"=>$client_awb,"fastcoolabel"=>$fastcoolabel);
    }else{
        $successstatus = "Fail";
        $return_array =  array("status"=>"false","msg"=>$responseArray['error']);
    }
    if($super_id >0){
        $CI =& get_instance();
        $CI->load->model('Ccompany_auto_model');
        $CI->Ccompany_auto_model->shipmentLog($c_id,$response,$successstatus, $ShipArr['slip_no'], $super_id, $postdata,$client_awb);

    }else{
        $CI =& get_instance();
        $CI->load->model('Ccompany_model');
        $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $postdata,'',$client_awb);
    }
    return $return_array;
}

function getcityids($city=null,$counrierArr=array()){

    $api_url=$counrierArr['api_url'];
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url.'addresses/villages?search='.$city,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Company-ID: '.$counrierArr['courier_pin_no']
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result=json_decode($response,true);
    // print_r($result);die;
    return $result;
}

function generateLabel($ids=null,$counrierArr= array()){

    // print_r($ids);die;
    $id=array(
        "ids"=>[
            $ids,
        ]
    );
    $company_id = $counrierArr['courier_pin_no'];
    $api_url=$counrierArr['api_url'];
    // print_r($api_url);die;
    $_id=json_encode($id,true);
    
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url."guests/$company_id/packages/pdf",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$_id,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    $lebels=json_decode($response,true);
    return $lebels;
    
    
}