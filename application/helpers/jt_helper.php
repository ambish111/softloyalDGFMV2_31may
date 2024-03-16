<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
   
function JandTArr($sellername=null,$ShipArr= array(), $counrierArr= array(), $c_id =null, $box_pieces1 =null, $complete_sku =null,$super_id=null,$company=null){
    
     $api_url = $counrierArr['api_url'].'addOrder';
     
    if($ShipArr['cust_id'] == 305){
        $sellername = $ShipArr['shipment_seller_name']." - ".site_configTableSuper_id("company_name",$super_id);
    }
    


    //$api_url = 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api/order/addOrder?uuid=a6363164b1cb4a2da8edc51c35105f1e';
    
    
    $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'jt_city', $super_id);        
     $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'jt_country_code', $super_id);
    //    echo "<pre>"; print_r($ShipArr);
    //    die;  
    
    //$store_address = $ShipArr['sender_address'];
    //$senderemail =$ShipArr['sender_email'];
    //$senderphone =$ShipArr['sender_phone'];

   

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
    

    $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'jt_city',$super_id);
    $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'jt_country_code', $super_id);
    $currency = getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency', $super_id);

    
    $CI =& get_instance();
    $CI->load->model('Ccompany_model');

    if(empty($sender_city)){
        $return_array =  array("error"=>"true","msg"=>'Sender City is Empty.');
        

        $CI->Ccompany_model->shipmentLog($c_id, json_encode($return_array),'Fail', $ShipArr['slip_no'], "");

        return $return_array;
    }
    if(empty($sender_country_code)){
        $return_array =  array("error"=>"true","msg"=>'Sender Country Code is Empty.');
        $CI->Ccompany_model->shipmentLog($c_id, json_encode($return_array),'Fail', $ShipArr['slip_no'], "");
        return $return_array;
    }

    if(empty($receiver_city)){
        $return_array =  array("error"=>"true","msg"=>'Receiver City is Empty.');
        $CI->Ccompany_model->shipmentLog($c_id, json_encode($return_array),'Fail', $ShipArr['slip_no'], "");
        return $return_array;
    }
    if(empty($receiver_country_code)){
        $return_array =  array("error"=>"true","msg"=>'Receiver Country Code is Empty.');
        $CI->Ccompany_model->shipmentLog($c_id, json_encode($return_array),'Fail', $ShipArr['slip_no'], "");
        return $return_array;
    }
    $currency = "SAR";//"EGP"; 

    if (empty($box_pieces1)){
        $box_pieces = 1;
    } else {
        $box_pieces = $box_pieces1;
    }
    
  
  

    if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) 
    {
         $weight = 1;
    }else {
         $weight = $ShipArr['weight'];
    }

   


    if ($ShipArr['weight'] > 31) {
        $weight = 30;
    } else {
        $weight = $ShipArr['weight'];
    }

   
    
    $complete_sku = !empty($complete_sku)?$complete_sku:'Goods';

    $complete_sku = stripInvalidXml($complete_sku);
    $complete_sku=	mb_substr($complete_sku,0,50,"UTF-8"); 

    $cod_amount= 0;
    if($ShipArr['mode'] == "COD"){
        $cod_amount = $ShipArr['total_cod_amt'];
    }
    
    $item_array = array();
    for($i=0;$i<$box_pieces;$i++){
        $item_array[] = array(
            "itemType" => "ITN1",
            "itemName" => $complete_sku,
            "chineseName" => $complete_sku,
            "englishName" => $complete_sku,
            "number" => 1,
            "itemValue" => $cod_amount,
            "priceCurrency" => $currency,
            "desc" => $complete_sku,
            "itemUrl" => ""
        );
        
    }


    $sdendStartTime = date('Y-m-d H:i:s');
    $sendEndTime =  date('Y-m-d H:i:s',strtotime('+4 hour',strtotime($sdendStartTime)));

    $expressType = 'EZKSA';
    if($company == 'J&T Reverse'){
        $expressType = 'Reverse';
    }


    $waybillinfoArr = array(
        //"network"=> " ",
        "serviceType" => "02",
        "orderType" => "2",
        "deliveryType" => "04",
        "countryCode" => $sender_country_code,
        "receiver" => array(
            "address"=> $ShipArr['reciever_address'],
            "street"=> "",
            "city"=>$receiver_city,
            "mobile"=> $ShipArr['reciever_phone'],
            "mailBox"=> $ShipArr['reciever_email'],
            "phone" => $ShipArr['reciever_phone'],
            "countryCode"=> $receiver_country_code,
            "name" => $ShipArr['reciever_name'],
            "company" => $ShipArr['reciever_name'],
            "postCode" => "000000",
            "prov" => $receiver_city,
        ),
        "expressType" => $expressType,
        "length" => '0',
        "weight" => $weight,
        "remark" => $complete_sku,
        "txlogisticId" => $ShipArr['slip_no'],
        "goodsType" => "ITN1",
        "priceCurrency" => $currency,
        "totalQuantity" => $box_pieces,
        "sender"=>array(
            "address" => $store_address,
            "street" => "",
            "city" => $sender_city,
            "mobile"  => $senderphone,
            "mailBox" => $senderemail,
            "phone" => $senderphone,
            "countryCode" =>$sender_country_code,
            "name" => $sellername,
            "company" => $sellername,
            "postCode" => "",
            "prov" => $sender_city,
         ),
        "offerFee" => 0,
        "items"=> $item_array,
        "operateType" => 1,
        "payType" => "PP_PM",  //PP_PM
        "isUnpackEnabled"=> 0
      

    );

    //      echo "<pre>"; print_r($waybillinfoArr);
    //    die;  
    
    if($ShipArr['mode'] == "COD"){
        $cod_amount = $ShipArr['total_cod_amt'];
        $waybillinfoArr['itemsValue'] = $cod_amount;
    }
    $waybillinfo = json_encode($waybillinfoArr);

    //  echo $waybillinfo ; die; 
    
    $key = $counrierArr['auth_token']; //"a0a1047cce70493c9d5d29704f05d0d9";
    $account= $counrierArr['courier_account_no'];//"292508153084379141";
    $customerCode = $counrierArr['courier_pin_no'];//'J0086024138';
    $pwd = $counrierArr['password'];//"Aa123456";
    $slipNo =  $ShipArr['slip_no'];
    $orderData = create_order($customerCode,$pwd,$key,$account,$waybillinfo,$api_url);
    $resultData = $orderData['result'];
    $postData = $orderData['postdata'];

    $result = json_decode($resultData,true);
    if ($result['msg'] == 'success') 
    {
            $successstatus = "Success";

            $client_awb = $result['data']['billCode'];
            sleep(3);
            $label_response = JandTLabel($counrierArr,$client_awb);

            file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';            
            $return_array = array("msg"=>'success',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel));

    } else {
            $successstatus = "Fail";
            $return_array =  array("error"=>"true","msg"=>$result['msg']);
    }

    $CI =& get_instance();
    $CI->load->model('Ccompany_model');

    $CI->Ccompany_model->shipmentLog($c_id, $resultData,$successstatus, $ShipArr['slip_no'], $postData);
    return $return_array;
    
}

function JandTLabel($counrierArr = array(),$client_awb =null){
    
    $key = $counrierArr['auth_token']; //"a0a1047cce70493c9d5d29704f05d0d9";
    $account= $counrierArr['courier_account_no'];//"292508153084379141";
    $customerCode = $counrierArr['courier_pin_no'];//'J0086024173';
    $pwd = $counrierArr['password'];//"Aa123456";

    // print_r($counrierArr); die; 

      $print_url = $counrierArr['api_url'].'printOrder';
//      echo $print_url ;die;
    //$print_url = 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api/order/printOrder?uuid=a6363164b1cb4a2da8edc51c35105f1e';

        $print_info='{"billCode": "'.$client_awb.'"}';

      $labelResult = print_order_label($customerCode,$pwd,$key,$account,$print_info,$print_url);
//      echo $labelResult;die;
    return $labelResult;
}


function print_order_label($customerCode,$pwd,$key,$account,$waybillinfo,$url){
    $post_data = get_post_data($customerCode,$pwd,$key,$waybillinfo);
    
  
    $head_dagest = get_header_digest($post_data,$key);
    // print_r($post_data); die; 

    $post_content = array(
        'bizContent' => $post_data
    );
  

    $postdata = http_build_query($post_content);
    
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' =>
                array('Content-type: application/x-www-form-urlencoded',
                    'apiAccount:' . $account,
                    'digest:' . $head_dagest,
                    'timestamp: '.time()),
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
     $context = stream_context_create($options);
// print_r($post_data);
//  die; 
    $result = file_get_contents($url, false, $context);
    return $result;
}


function create_order($customerCode,$pwd,$key,$account,$waybillinfo,$url){

    $post_data = get_post_data($customerCode,$pwd,$key,$waybillinfo);
    
    $head_dagest = get_header_digest($post_data,$key);
//      print "<pre>";
//      echo $head_dagest;
//      //die;

// echo '<br>';
    
   
    $post_content = array(
        'bizContent' => $post_data
    );
//    print_r($post_data); 
//     die;
    $postdata = http_build_query($post_content);
    
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' =>
                array('Content-type: application/x-www-form-urlencoded',
                    'apiAccount:' . $account,
                    'digest:' . $head_dagest,
                    'timestamp: '.time()),
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );

     json_encode($options); //die;
    $context = stream_context_create($options);    
    $result = file_get_contents($url, false, $context);
    return array("result"=>$result,"postdata"=>$post_data);
    //return $result;

}

function get_post_data($customerCode,$pwd,$key,$waybillinfo){

    $postdata = json_decode($waybillinfo,true);
    $postdata['customerCode'] = $customerCode;
    $postdata['digest'] = get_content_digest($customerCode,$pwd,$key);

    return json_encode($postdata);
}

function get_content_digest($customerCode,$pwd,$key)
{
    $str = strtoupper($customerCode . md5($pwd . 'jadada236t2')) . $key;

    return base64_encode(pack('H*', strtoupper(md5($str))));
}
/**
 * 头部请求部分加密
 * param array $post
 * 
 */
function get_header_digest($post,$key){
    $digest = base64_encode(pack('H*',strtoupper(md5($post.$key))));
    return $digest;
}


function JandTReverse($ShipArr=array(), $counrierArr=array(), $c_id=null, $box_pieces1=null, $complete_sku=null,$super_id=null){
    // echo "test1111"; die;
    $url = $counrierArr['api_url'].'returnAndExchange'; 
    $key = $counrierArr['auth_token'];  //"a0a1047cce70493c9d5d29704f05d0d9"; die;
    $account= $counrierArr['courier_account_no'];  //"292508153084379141";
    $customerCode = $counrierArr['courier_pin_no'];  //'J0086024173';
    $pwd = $counrierArr['password'];  //"Aa123456";
    $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code', $super_id);
    $exprestype = "Reverse(".$receiver_country_code.")";


    if(empty($complete_sku)){
        $complete_sku = "Goods";
    }
    $waybillinfoArr = array(
        "customerCode" => $customerCode,
        'txlogisticId' => $ShipArr['slip_no'],
        'billCode' => $ShipArr['frwd_company_awb'], 
        'returnAndExchangeType'=> "1",
        "expressType" => $exprestype,
        'itemDescription'=>$complete_sku
    );

    $wayBillJson =  json_encode($waybillinfoArr);

    $post_data = get_post_data($customerCode,$pwd,$key,$wayBillJson);
    $head_dagest = get_header_digest($post_data,$key);
    // echo $wayBillJson; die;

    $post_content = array(
        'bizContent' => $post_data
    );
    $postdata = http_build_query($post_content);
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' =>
                array('Content-type: application/x-www-form-urlencoded',
                    'apiAccount:' . $account,
                    'digest:' . $head_dagest,
                    'timestamp: '.time()),
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);

    $resultData = file_get_contents($url, false, $context);

    $result = json_decode($resultData,true);
    // echo "<pre>"; print_r($result); die;
    if ($result['msg'] == 'success') 
    {
            $slipNo = $ShipArr['slip_no'];
            $successstatus = "Success";

            $client_awb = $result['data']['returnBillCode'];
            sleep(3);
            $label_response = JandTLabel($counrierArr,$client_awb);

            file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';            
            $return_array = array("msg"=>'success',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel));

    } else {
            $successstatus = "Fail";
            $return_array =  array("error"=>"true","msg"=>$result['msg']);
    }

    $CI =& get_instance();
    $CI->load->model('Ccompany_model');

    $CI->Ccompany_model->shipmentLog($c_id, $resultData,$successstatus, $ShipArr['slip_no'], $wayBillJson);
    return $return_array;


}

function stripInvalidXml($value)
{
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $value);
}


?>