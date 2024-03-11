<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
function ForwardToSprint($ShipArr = array(), $counrierArr = array(),  $c_id = null, $box_pieces1 = null, $complete_sku = null, $super_id = null)
{

    // echo "<pre>";print_r($counrierArr);die;
    $CustomerCode=$counrierArr['courier_account_no'];
    $ClientCode=$counrierArr['courier_pin_no'];

    // $selleraddress= GetallCutomerBysellerId($ShipArr['cust_id'],'address');
    // if(empty($selleraddress)){
    //   $selleraddress = $ShipArr['sender_address'];
    // }


    

  $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
  if($label_info_from == '1'){
      // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
      // if($counrierArr['wharehouse_flag'] =='Y'){
      //     $sellername = $sellername ." - ". site_configTable('company_name'); 
      // }
      $selleraddress = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
  }else{
      // $sellername =  $ShipArr['sender_name'];
      // if($counrierArr['wharehouse_flag'] =='Y'){
      //     $sellername = $sellername ." - ". site_configTable('company_name'); 
      // }
      $selleraddress = $ShipArr['sender_address'];
  }

  // if(!empty($ShipArr['label_sender_name'])){
  //     $sellername =  $ShipArr['label_sender_name'];    
  //     if($counrierArr['wharehouse_flag'] =='Y'){
  //         $sellername = $sellername ." - ". site_configTable('company_name'); 
  //     }
  // }

    $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'sprint_city', $super_id);
    $receiver_state = getdestinationfieldshow_auto_array($ShipArr['destination'], 'sprint_state', $super_id);

    $reciever_country_code = getdestinationfieldshow($ShipArr['destination'], 'country_code', $super_id); 

    if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
    $weight = 1;
}else {
    $weight = $ShipArr['weight'];
}
    
    $package_count=($box_pieces1==0)? 1 : $box_pieces1;
    $reciever_pincode=(strlen($ShipArr['reciever_pincode'])==0)?"00000" : $ShipArr['reciever_pincode'];
    // echo $reciever_pincode;die;
    $service_code=($counrierArr['service_code']==0)?"PUD":$counrierArr['service_code'];
    // $bookingDate = date('Y-m-d');  // Get the current date
    // $nextDay = date('Y-m-d', strtotime($bookingDate . ' +1 day'));
    $slipNo=$ShipArr['slip_no'];

    if(empty($complete_sku)){
      $complete_sku = $ShipArr['status_describtion'];
    }
    


     // $receiver_city=(strlen($receiver_city)==0)?"Cairo":$receiver_city;
     if(empty( $receiver_city)){
        $return_array =  array("status"=>"false","msg"=>"Receiver City Empty");
        return $return_array;
     }


     if(empty($receiver_state)){
      $receiver_state = $receiver_city;
     }
     $reciever_name=$ShipArr["reciever_name"];
     $reciever_phone=$ShipArr["reciever_phone"];
     $reciever_address=$ShipArr["reciever_address"];


     $data = array(
      "waybillRequestData" => array(
          "FromOU" => $selleraddress,      
          "WaybillNumber" => "",
          "DeliveryDate" => "",  //$nextDay
          "ClientCode" => $ClientCode,
          "CustomerCode" => $CustomerCode,
          "ConsigneeCode" => $reciever_pincode,
          "ConsigneeAddress" => $reciever_address,
          "ConsigneeCountry" => $reciever_country_code,
          "ConsigneeState" => $receiver_state,//$receiver_city,
          "ConsigneeCity" => $receiver_city,
          "ConsigneeName" => $reciever_name,
          "ConsigneePhone" => $reciever_phone,
          "NumberOfPackages" => $package_count,
          "ActualWeight" => $weight,
          "ChargedWeight" => "",
          "ReferenceNumber" => $slipNo,
          "PaymentMode" => "TBB",
          "ServiceCode" => $service_code,
          "Description" => $complete_sku,
          "CargoValue" => "",
          "COD" => $ShipArr['total_cod_amt'],
          "CODPaymentMode" => "CASH",
      )
  );
  // print "<pre>"; print_r($data);die;
  $finaldata=json_encode($data);

  // print_r($finaldata);die;
  $url= $counrierArr['api_url'] .'CreateWaybill?secureKey=' . $counrierArr['auth_token'];

  $curl = curl_init();
  // creating waybill
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $finaldata,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);
  // echo $response;die;
  curl_close($curl);
  $res = json_decode($response,true);
  // print_r($res);die;
  if($res['messageType'] == "Success")
  {
    // echo "inside if";die;
    $successstatus  = "Success";
    $LabelResponse = file_get_contents($res['labelURL']);

    file_put_contents("assets/all_labels/$slipNo.pdf",$LabelResponse );
    $sprintLabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';

    $waybillNumber=$res['waybillNumber'];
    // echo $waybillNumber;die;

     
    $return_array = array("status"=>'true',"data"=>array('client_awb'=>$waybillNumber,'label'=>$sprintLabel));
    // print_r($return_array);die;
  }else{
    $successstatus  = "Fail";
    $return_array =  array("status"=>"false","msg"=>$res['message']);
  }

  $CI =& get_instance();
  $CI->load->model('Ccompany_model');
  $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $finaldata);
  return $return_array;
}





