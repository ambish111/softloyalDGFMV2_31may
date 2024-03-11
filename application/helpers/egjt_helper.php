<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
   
function JandTArr($sellername=null,$ShipArr= array(), $counrierArr= array(), $c_id =null, $box_pieces1 =null, $complete_sku =null,$super_id=null){
    
     $api_url = $counrierArr['api_url'].'addOrder';
     
    //$api_url = 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api/order/addOrder?uuid=a6363164b1cb4a2da8edc51c35105f1e';
    
    //print "<pre>"; print_r($ShipArr);die;
    // $ShipArr['origin'] = '55672';
    $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'jt_eg_city', $super_id);        
    $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);

//    echo "<pre>"; print_r($ShipArr['destination']);
//    die;  
    // $store_address = $ShipArr['sender_address'];
    // $senderemail =$ShipArr['sender_email'];
    // $senderphone =$ShipArr['sender_phone'];


    // $seller__name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');

    //    if($super_id = 20)
    //     { 
    //         $sellername_name = $seller__name; 

    //     }else {
    //         $sellername_name = $sellername;
    //     }


        

       // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sellername_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername_name = $sellername_name ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sellername_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername_name = $sellername_name ." - ". site_configTable('company_name'); 
            }
        
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername_name = $sellername_name ." - ". site_configTable('company_name'); 
            }
        }
    
    $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'jt_eg_city',$super_id);
  
    $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code', $super_id);
    $currency = getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency', $super_id);
    // $currency ="SAR";
    //  print_r($currency); die;
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
    //$currency = "SAR"; 

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

    $complete_sku = !empty($complete_sku)?$complete_sku:'Goods';
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
            "itemValue" => round($cod_amount),
            "priceCurrency" => $currency,
            "desc" => $complete_sku,
            "itemUrl" => ""
        );
        
    }


    $sdendStartTime = date('Y-m-d H:i:s');
    $sendEndTime =  date('Y-m-d H:i:s',strtotime('+4 hour',strtotime($sdendStartTime)));

    if($ShipArr['mode'] == "COD"){
        $cod_amount = $ShipArr['total_cod_amt'];
        $waybillinfoArr['itemsValue'] = $cod_amount;
    }

    $remark = $complete_sku .' - '. $ShipArr['comment'];
    

    $waybillinfoArr = array(
        "customerCode" =>  $counrierArr['courier_pin_no'],
        "digest" =>  $counrierArr['account_entity_code'], 
        "network"=> " ",
        "txlogisticId" => $ShipArr['slip_no'],
        "expressType" => "EZ",
        "orderType" =>"2",
        "serviceType"=>"02",
        "deliveryType" =>"04",
        "payType" => "PP_PM",  //PP_PM
        "goodsType" => "ITN1",
        "length" => '0',
        "weight" => $weight,
        "height"=>'0',
        "width"=>'0',
        "totalQuantity" => $box_pieces,
        "itemsValue"=> round($cod_amount),
        "priceCurrency" => $currency,
        "offerFee" => "",
        "remark" => $remark,
        "operateType" => 1,
        "area" => 0,
        "countryCode" =>$sender_country_code,
        
        "sender"=>array(
            "address" => $store_address,
            "name" => $sellername_name,
            "company" => $sellername_name,
            "postCode" => "",
            "mailBox" => $senderemail,
            "mobile"  => $senderphone,
            "phone" => $senderphone,
            "countryCode" =>$sender_country_code,
            "prov" => $sender_city,
            "city" => $sender_city,
            "street" => $store_address,
            "area" => 0,
          
        ),
        "receiver"=>array(
            "address"=> $ShipArr['reciever_address'],
            "name" => $ShipArr['reciever_name'],
            "company" => $ShipArr['reciever_name'],
            "postCode" => "",
            "mailBox"=> $ShipArr['reciever_email'],
            "mobile"=> $ShipArr['reciever_phone'],
            "phone" => $ShipArr['reciever_phone'],
            "countryCode"=> $receiver_country_code,
            "prov" => $receiver_city,
            "city"=>$receiver_city,  
            "area" => 0,         
            "street"=> $ShipArr['reciever_address'],
          
        ),
        
        "items"=> $item_array      
        

    );

    
    $waybillinfo = json_encode($waybillinfoArr);

       //echo "<pre>"; print_r($counrierArr['courier_pin_no']); die; 
    
    $key = $counrierArr['auth_token']; //"a0a1047cce70493c9d5d29704f05d0d9";
    $account= $counrierArr['courier_account_no'];//"292508153084379141";
    $customerCode = $counrierArr['courier_pin_no'];//'J0086024138';
    $pwd = $counrierArr['password'];//"Aa123456";
    $bizcontent_digest = $counrierArr['account_entity_code'];//"mPY0eLkI5vVd5bFhN7HpyA==";
    $slipNo =  $ShipArr['slip_no'];
     $orderData = create_order($customerCode,$pwd,$key,$account,$waybillinfo,$api_url);
    $resultData = $orderData['result'];
    $postData = $orderData['postdata'];
   // echo $postData;die;
    $result = json_decode($resultData,true);
    
    
    if ($result['msg'] == 'success' ) 
    {
            $successstatus = "Success";
            $client_awb = $result['data']['billCode'];           
            //$slipNo = 'FWL8310075081';
            sleep(2);
            $label_response = JandTLabel($counrierArr,$client_awb);
           // print_r( $label_response);// die;  
           file_put_contents("assets/all_labels/$slipNo.pdf", $label_response);
            $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';   
          // echo $fastcoolabel; die; 
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
    $bizcontent_digest = $counrierArr['account_entity_code'];//"mPY0eLkI5vVd5bFhN7HpyA==";

     $print_url = $counrierArr['api_url'].'printOrder';
    //$print_url = 'https://demoopenapi.jtjms-eg.com/webopenplatformapi/api/order/printOrder?uuid=a6363164b1cb4a2da8edc51c35105f1e';
     
     $print_info='{"customerCode":"'.$customerCode.'","digest":"'.$bizcontent_digest.'","billCode": "'.$client_awb.'","printSize": 0,"printCod": 0}';

    // print_r($print_info); 
    // die;
    $labelResult = print_order_label($customerCode,$pwd,$key,$account,$print_info,$print_url);
    $labeldata = json_decode($labelResult, true); 
     $labelprint = base64_decode( $labeldata['data']['base64EncodeContent']); 
    return $labelprint;
}


function print_order_label($customerCode,$pwd,$key,$account,$waybillinfo,$url){
    
    $post_data = $waybillinfo; //get_post_data($customerCode,$pwd,$key,$waybillinfo);
    
  
    $head_dagest = get_header_digest($post_data,$key);
    
    $post_content = array(
        'bizContent' => $waybillinfo
    );
    // print_r($head_dagest); die; 
  

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
            // 'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
   
    $context = stream_context_create($options);
   
    $result = file_get_contents($url, false, $context);
//    print_r($result); die; 
    return $result;
}


function create_order($customerCode,$pwd,$key,$account,$waybillinfo,$url){

    $post_data = $waybillinfo; //get_post_data($customerCode,$pwd,$key,$waybillinfo);
    
    $head_dagest = get_header_digest($post_data,$key);
    //   print "<pre>";
    //   echo $waybillinfo;
    //   die;

    // echo '<br>';
    
   
    $post_content = array(
        'bizContent' => $waybillinfo
    );
    // echo "<pre>";
//    print_r($post_content); 
    // die;
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

    // echo json_encode($options); die;
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





?>