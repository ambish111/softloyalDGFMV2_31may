<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

function ForwardToweenkapp($ShipArr = array(), $counrierArr = array(),  $c_id = null, $box_pieces1 = null, $complete_sku = null, $super_id = null){

  // $DistrictId = DistrictId();
  // if ($DistrictId['Values'] == '0') {
  //   return array("status" => "false", "msg" => "id not given");
  // }
  $API_URL = $counrierArr['api_url'].'Partner_api.php';
  

$label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
if($label_info_from == '1'){
    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
    if($counrierArr['wharehouse_flag'] =='Y'){
        $sellername = $sellername ." - ". site_configTable('company_name'); 
    }
    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
}else{
    $sellername =  $ShipArr['sender_name'];
    if($counrierArr['wharehouse_flag'] =='Y'){
        $sellername = $sellername ." - ". site_configTable('company_name'); 
    }
    
    $senderphone = $ShipArr['sender_phone'];
}

if(!empty($ShipArr['label_sender_name'])){
    $sellername =  $ShipArr['label_sender_name'];    
    if($counrierArr['wharehouse_flag'] =='Y'){
        $sellername = $sellername ." - ". site_configTable('company_name'); 
    }
}

  $destrict_id = getdestinationfieldshow_auto_array($ShipArr['destination'], 'weenkapp_city_id',$super_id);

  if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) 
  {   $weight = 1;
    }else {
        $weight = $ShipArr['weight'];
    }
  $slipNo = $ShipArr['slip_no'];

  $box_pieces = empty($box_pieces1) ? 1 : $box_pieces1;
  //  $box_pieces = 2;
   for($i=0;$i<$box_pieces;$i++){
    $a[] = array(
          'item_Weight['.$i.']' => '0.1',
          'item_length['.$i.']' => '0.1',
          'item_width['.$i.']' => '0.1',
          'item_height['.$i.']' => '0.1',
      );
      
      //array_push($b,$a);
  }
  
  $pieceArray = []; 
  foreach ($a as $childArray) 
  { 
      foreach ($childArray as $key=>$value) 
      { 
        $pieceArray[$key] = $value;
      }
  }

  // print_r($singleArray); die;
    
  if($ShipArr['pay_mode'] == 'COD'){
    $payment_mode = 'Y';
  }else{
    $payment_mode = 'N';
  }


  $allData = array(
    'Targets' => 'Create',
    'PartnerId' => $counrierArr['service_code'],
    'Email' => $counrierArr['user_name'],
    'Password' => $counrierArr['password'],
    'ServiceId' => '2',
    'CustomerName' => $ShipArr['reciever_name'],
    'CustomerMobile' => $ShipArr['reciever_phone'],
    'AddressDesc' => $ShipArr['reciever_address'],
    'DistrictId' => $destrict_id,
    'PaymentOnDelivery' => $payment_mode,//$ShipArr['pay_mode'],
    'PaymentAmount' => $ShipArr['total_cod_amt'],
    'StoreName' =>  $sellername,
    'MobileStore' =>$senderphone,
    'YoNum' => $slipNo,
    'Content' => !empty($complete_sku)?$complete_sku:"Goods",
    'Weight' =>  $weight,
    'NumPieces' =>  $box_pieces,
   

  );
$result = array_merge($allData, $pieceArray);
// print_r($result);die;
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
    CURLOPT_POSTFIELDS =>  $result,
    CURLOPT_HTTPHEADER => array(),
  ));

  $response = curl_exec($curl);
  $error = curl_error($curl); 
  curl_close($curl);

  $responseArray = json_decode($response, true);

  // print_r($responseArray);die;

  if (!empty($responseArray['Values'])) {
    $successstatus = "Success";
    $client_awb = $responseArray['NewId'];
    //  echo $client_awb;die; 
    $media_data = $responseArray['link_pdf'];
    //  print_r( $media_data); die;
    $generated_pdf = file_get_contents($media_data);
    file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
    $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';

    $return_array =  array("status" => "true", "client_awb" => $client_awb, "fastcoolabel" => $fastcoolabel);

  } else {
    $successstatus = "Fail";
    $return_array =  array("status" => "false", "msg" => $responseArray['message']);
    // echo print_r( $return_array);
  }

  $data = json_encode($allData);
  $CI = &get_instance();
  $CI->load->model('Ccompany_model');
  $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $data);
  return $return_array;
}
// function DistrictId()
// {
 
//   // $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'business_flow_city', $super_id);
  
//   $curl = curl_init();

//   $requiredData = array(
//     'Targets' => 'City',
//     'City' => 'الرياض',
//     'District' =>'حي منفوحة',
//     'Sector' => ''

//   );

//   // print "<pre>"; print_r($requiredData);die;

//   curl_setopt_array($curl, array(
//     CURLOPT_URL => 'https://weenkapp.com/app/partner/Partner_api.php',
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => $requiredData,
//     CURLOPT_HTTPHEADER => array(),
//   ));

//   $response = curl_exec($curl);

//   curl_close($curl);

//   $respo = json_decode($response, true);
//   // print_r($respo);die;
//   return $respo;
// }
