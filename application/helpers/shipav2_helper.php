<?php
if (!defined('BASEPATH'))
      exit('No direct script access allowed');
function ForwardToShipaV2($ShipArr = array(), $counrierArr = array(),  $c_id = null, $box_pieces1 = null, $complete_sku = null, $super_id = null)
{

      // $box_pieces1 =2;

      // print "<pre>"; print_r($ShipArr);die;
      $slipNo = $ShipArr['slip_no'];
      $url = $counrierArr['api_url'];
      $token = $counrierArr['auth_token'];

     

      $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'shipsa_city', $super_id);        
      $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);
      if($sender_country_code == 'SA' || $sender_country_code == 'KSA' ){
            $sender_country_code = 'SAU';
      }
      
      $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsa_city', $super_id);
      $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code',$super_id);
      if($receiver_country_code == 'SA' || $receiver_country_code == 'KSA' ){
            $receiver_country_code = 'SAU';
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


      
    $cod_amount= 0;
    if($ShipArr['mode'] == "COD"){
        $cod_amount = (float)$ShipArr['total_cod_amt'];
    }
    if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) 
    { $weight = 1;
    }else {
        $weight = $ShipArr['weight'];
    }

      $box_pieces = ($box_pieces1==0)?1:$box_pieces1;
      // echo "P=".$box_pieces;die;
      $complete_sku = empty($complete_sku) ? "Goods" : $complete_sku;

      for($i=1;$i<=$box_pieces;$i++)
      {
            $packageData[]= array(
                            "name" => $complete_sku,
                            "customerRef" => $ShipArr['slip_no'],
                            "weight"=> $weight //(float)$weight 
                        );
      }
      
      $reciever_email = empty($ShipArr['reciever_email'])?'no@no.com':$ShipArr['reciever_email'];
      $reciever_phone = $ShipArr['reciever_phone'];
      if(!empty($reciever_phone)){
            if(strlen($reciever_phone)>9){
                  $reciever_phone = substr($reciever_phone,-9);
            }
      }
      if(!empty($senderphone)){
            if(strlen($senderphone)>9){
                  $senderphone = substr($senderphone,-9);
            }
      }

      
      $data = array(
            "customerRef" => $ShipArr['slip_no'] , //
            "type" => "Delivery",
            "origin" => array(
                  "contactName" => $sellername,
                  "contactNo" => $senderphone,
                  "city" => $sender_city,
                  "country" => $sender_country_code,
                  "address" => $store_address,
                  "email"=>$senderemail,
                  "options"=>array(
                        "amountToCollect"=>0
                  )
            ),
            "destination" => array(
                  "contactName" => $ShipArr['reciever_name'],
                  "contactNo" => $reciever_phone,//$ShipArr['reciever_phone'],
                  "city" => $receiver_city,
                  "country" => $receiver_country_code,
                  "address" => $ShipArr['reciever_address'],
                  "email"=>$reciever_email,//$ShipArr['reciever_email'],
                  "options"=>array(
                        "amountToCollect"=>$cod_amount
                  )
                  
            ),
            "packages" => $packageData,
            "category" => "Next Day"
      );

      $finalData = json_encode($data);
      //  echo $finalData;die;
      // echo $url . '?apikey=' . $token;
//      echo  "https://api.shipadelivery.com/v2/orders"; 

      $curl = curl_init();
      curl_setopt_array($curl, array(
            CURLOPT_URL => $url . '?apikey=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $finalData,
            CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
            ),
      ));

       $response = curl_exec($curl);
// die; 
      curl_close($curl);
      $result = json_decode($response, true);
   


      if (!empty($result['shipaRef'])) {
      
            $frwd_awb_no =   $result['shipaRef'];
            $successstatus = "Success";
            $generated_pdf = GenerateLabel($url, $frwd_awb_no, $token);

            // $LabelResponse = file_get_contents($generated_pdf);
            file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
            $fastcoolabel = base_url() . 'assets/all_labels/' . $slipNo . '.pdf';
         

            $return_array =  array("status" => "true", "client_awb" => $frwd_awb_no, "shipaV2Label" => $fastcoolabel);
      } else {

            $successstatus = "Fail";
            $return_array =  array("status" => "false", "msg" => json_encode($result['message']));
         
      }

      $CI = &get_instance();

      $CI->load->model('Ccompany_model');
      $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$finalData);
      return $return_array;
}


function GenerateLabel($url, $shipaRef, $token)
{

      $apiUrl = $url . '/' . $shipaRef . '/pdf?apikey=' . $token."&mode=strem&template=4x6";
      // 'https://sandbox-api.shipadelivery.com/v2/orders/SBSD0239035/pdf?apikey=4HifkbzzAtIcZUWnnDBNeRQzyV9cTAE2%20&mode=strem&template=4x6&copies=1',
      $curl = curl_init();

      curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json'
            ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      return $response;
}
