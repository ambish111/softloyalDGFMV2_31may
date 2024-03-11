<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  
	
	function ProConnectForword($ShipArr=array(), $counrierArr=array(),$box_pieces1=null, $super_id=null, $c_id=null){
       //FASTEST0012643
	  	$API_URL = $counrierArr['api_url'].'Transaction/v2/CreateSalesOrder';
        
	    $subscription_id = $counrierArr['user_name']; 
        $subscription_key = $counrierArr['password']; 
        $ConsumerKey = $counrierArr['auth_token']; 
        $ClientSecret = $counrierArr['courier_account_no']; 
        

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

		$store_city = getallsellerdatabyID($ShipArr['cust_id'], 'city'); 


        $receiver_city = getdestinationfieldshow($ShipArr['destination'], 'proconnect_city',$super_id);
        
        $sender_city = getdestinationfieldshow($store_city, 'proconnect_city', $super_id); 
        $currency = getdestinationfieldshow($ShipArr['destination'], 'currency',$super_id);
        $reciever_country = getdestinationfieldshow($ShipArr['destination'], 'country',$super_id);


        
        if(empty($receiver_city)){
			return array("Status"=>'100',"data"=>array('msg'=>'Receiver city empty'));
		}


		if(empty($ShipArr['reciever_address'])){
			$return_array =  array("Status"=>'100',"data"=>array("msg"=>'Receiver address empty'));
			return $return_array;			
		}
		
        if(empty($ShipArr['reciever_phone'])){
            $result =  array("Status"=>'100',"data"=>array("msg"=>'Receiver phone empty'));
            return $result;			
        }


        $sku_all_names = array();
        $sku_total = 0;
        $pay_mode=$ShipArr['mode'];
        if($ShipArr['mode'] == "COD"){
            $codValue = $ShipArr['total_cod_amt'];
            $pay_mode = "cash";
        }
        elseif ($ShipArr['mode'] == 'CC'){
            $codValue = 0;
            $pay_mode = "cc";
        }

        
        $sku_arr = array();
        
        foreach ($ShipArr['sku_data'] as $key => $val) {

                    createItemSku($val,$counrierArr,$codValue);
                    sleep(3);
                    $skunames_quantity = $val['sku'] . "/ Qty:" . $val['piece'];
                
                    $sku_arr[] = array(
                        "CustomerOrder"=> $ShipArr['slip_no'],
                        "PositionNumber"=> $ShipArr['slip_no'],
                        "ItemCode"=> $val['sku'],
                        "OrderedQuantity"=>  $val['piece'],
                        "UnitPrice"=>  $codValue,
                        "expirydate"=>  null,
                        "ALOCVAR1"=>  null,
                        "ALOCVAR2"=>  null,
                        "ALOCVAR3"=>  "",
                        "UDF1"=>  null,
                        "UDF2"=>  null,
                        "UDF3"=>  null,
                        "UDF4"=>  null,
                        "UDF5"=>  null
                    );
                    $sku_total = $sku_total + $val['piece'];
                    array_push($sku_all_names, $skunames_quantity);
                    
                
                

        }
        $sku_all_names = implode(",", $sku_all_names);
        $complete_sku = $sku_all_names;

        if(empty($complete_sku)){
            $result =  array("Status"=>'100',"data"=>array("msg"=>'Sku Not Found.'));
            return $result;			
        }

        

		
		if(empty($box_pieces1)){
            $box_pieces = 1;
        }else{ 
            $box_pieces = $box_pieces1 ; 
        }
        
        if($ShipArr['weight']==0){  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }

        $date = date('Y-m-d');
        $time = date('H:i:s');
        $date_time=  $date."T".$time;

        $json_array = array(
            "LocationId"=>"RIYADH",
            "ShipToCode"=>"ONL1001",
            "CompanyDescription"=> $ShipArr['reciever_name'],
            "Address1"=>$ShipArr['reciever_address'],
            "City"=> $receiver_city,
            "Country"=> $reciever_country,//"Saudi Arabia",
            "Phone"=> $ShipArr['reciever_phone'],
            "CustomerSalesOrderNumber"=> $ShipArr['slip_no'],
            "CustomerOrderRef2"=> $ShipArr['booking_id'],
            "UDF3"=> "None",
            "UDF4"=> "",
            "SaleOrderId"=> 0,
            "OPENQTY"=> $box_pieces,
            "ACTUALARRIVALDATE"=> $date_time,//"2022-11-28T00:00:00",
            "CustomerSODate"=> $date_time,//"2020-08-12T00:00:00",
            "ExpectedDeliverydate"=> "",//"2020-08-12T00:00:00",
            "CustomerPONumber"=>"",
            "Currency"=>$currency,//"SAR",
            "SaleOrderDetails"=>$sku_arr,
            "Status"=> 0
        );

        $json_string = json_encode($json_array);  
        //echo $json_string;die;
        
        //$API_URL = 'https://testapi.proconnectlogistics.com/api/ProConnectAPI/Transaction/v2/CreateSalesOrder';
        
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
          CURLOPT_POSTFIELDS =>$json_string,
          CURLOPT_HTTPHEADER => array(
            'subscription_id: '.$subscription_id,
            'subscription_key: '.$subscription_key,
            'ConsumerKey: '.$ConsumerKey,
            'ClientSecret: '.$ClientSecret,
            'Content-Type: application/json'
          ),
        ));
        
        

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response_array = json_decode($response, true);
        
        if($response_array['Status'] == 200 && $response_array['InforStatus'] == 'Accepted'){
            $successstatus  = "Success";
            $client_awb = $response_array['InForSOReference'];
            $return_array =  array("Status"=>"200","data"=>$response_array);
        }else{
            $successstatus  = "Fail";
            $return_array =  array("Status"=>"100","data"=>array("msg"=>$response_array));
        }

        $CI =& get_instance();
        $CI->load->model('Ccompany_model');
        $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], 
        $json_string);
        

        return $return_array;
        //print "<pre>"; print_r($response_array);die;

	}


    function createItemSku($skuData = array(),$cArray = array(),$codValue=null){

            $curl = curl_init();

            $API_URL = $cArray['api_url'].'Master/v2/CreateNewItem';
            $barcode = time();
            $itemArr = array(
                "ItemCode" => $skuData['sku'],
                "ItemDescription"=>"Desc. of the Item",
                "BrandCode"=>"",
                "BrandName"=>"",
                "ProductAttribute1"=>"",
                "ProductAttribute2"=>"",
                "ProductAttribute3"=>"",
                "ProductAttribute4"=>"",
                "ProductAttribute5"=>"",
                "ProductAttribute6"=>"",
                "Barcode"=>$barcode,
                "Hscode"=>"",
                "HsDescription"=>"",
                "InboundSerialCapturing"=>"Y",
                "OutboundSerialCapturing"=>"N",
                "ItemPrice"=>$codValue,
                "Length"=>"",
                "Width"=>"",
                "Height"=>""                
            );


            $itemjson = json_encode($itemArr);
            //echo $itemjson;die;

            $subscription_id = $cArray['user_name']; 
            $subscription_key = $cArray['password']; 
            $ConsumerKey = $cArray['auth_token']; 
            $ClientSecret = $cArray['courier_account_no']; 

            curl_setopt_array($curl, array(
                CURLOPT_URL => $API_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$itemjson,
                CURLOPT_HTTPHEADER => array(
                    'subscription_id: '.$subscription_id,
                    'subscription_key: '.$subscription_key,
                    'ConsumerKey: '.$ConsumerKey,
                    'ClientSecret: '.$ClientSecret,
                    'Content-Type: application/json'
                ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $res = json_decode($response,true);

            //print "<pre>"; print_r($res );die;
            //echo $response;
    }


?>