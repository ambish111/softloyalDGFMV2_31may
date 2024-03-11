<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  
	
	function BostaForward($sellername=null,$ShipArr=array(), $counrierArr=array(), $complete_sku=null,$c_id=null,$box_pieces1=null,$super_id=null,$open_package_flag){
		
		$API_URL = $counrierArr['api_url'].'v2/deliveries';
		$token = $counrierArr['auth_token'];
		
		$receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'bosta_city',$super_id);
		
		if(empty($receiver_city)){
			return array("error"=>'true',"msg"=>'Receiver city empty');
		}


		$box_pieces = empty($box_pieces1)?1:$box_pieces1;
		if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) 
			{
       		 $weight = 1;
				}else {
					$weight = $ShipArr['weight'];
				}

		$cod_amount = ($ShipArr['pay_mode'] == "COD")?$ShipArr['total_cod_amt']:0;

		$complete_sku = empty($complete_sku)?$ShipArr['status_description']:$complete_sku;

		$reciver_phone_number = str_replace("+", "", $ShipArr['reciever_phone']);
		if(empty($complete_sku)){
			$complete_sku = "Goods / Weight: ".$weight." kg";
		}else{
			$complete_sku = $complete_sku ."/ Weight: ".$weight." kg";
		}

		// $sender_address =$ShipArr['sender_address'];
		// $senderemail =$ShipArr['sender_email'];
		// $senderphone =$ShipArr['sender_phone'];
		if($super_id == 271){
			$notes = $sellername." | DeliveryDate :".$ShipArr['promise_deliver_date'];
		}else{

			$notes = $sellername." | DeliveryDate :".$ShipArr['promise_deliver_date'];
		}



		$label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
		if($label_info_from == '1'){
			$sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
			if($counrierArr['wharehouse_flag'] =='Y'){
				$notes = $sellername ." - ". site_configTable('company_name')." | DeliveryDate :".$ShipArr['promise_deliver_date'];
			}else{
				$notes = $sellername ." | DeliveryDate :".$ShipArr['promise_deliver_date'];
			}
			$sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
			$senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
			$senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
		}else{
			$sellername =  $ShipArr['sender_name'];
			if($counrierArr['wharehouse_flag'] =='Y'){
				$notes = $sellername ." - ". site_configTable('company_name'); 
			}else{
				$notes =  $sellername." | DeliveryDate :".$ShipArr['promise_deliver_date'];
			}
			$sender_address = $ShipArr['sender_address'];
			$senderphone = $ShipArr['sender_phone'];
			$senderemail = $ShipArr['sender_email'];
		}
		
		if(!empty($ShipArr['label_sender_name'])){
			$sellername =  $ShipArr['label_sender_name'];    
			if($counrierArr['wharehouse_flag'] =='Y'){
				$notes = $sellername ." - ". site_configTable('company_name')." | DeliveryDate :".$ShipArr['promise_deliver_date'];
			}else{
				$notes = $sellername ." | DeliveryDate :".$ShipArr['promise_deliver_date'];
			}
		}
		



		$sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'bosta_city', $super_id);

		if(empty($sender_city)){
			return array("error"=>'true',"msg"=>'Sender city empty');
		}

		
		if($ShipArr['cust_id'] == 305){
			$sellername = $ShipArr['shipment_seller_name']." - ".site_configTableSuper_id("company_name",$super_id);
			$notes = $sellername." | DeliveryDate :".$ShipArr['promise_deliver_date'];
		}

		// echo $notes;die;


		$request_params_array = array(
            "type"=> 10, //10: Delivery that has two endpoints (pickup and drop off), 15 : Delivery that has one endpoint (cash pickup point).
            "specs"=> array( 
                    "size"=>"SMALL", 
                    "weight"=>$weight,
                    "packageDetails"=> array(
                            "itemsCount"=> $box_pieces, 
                            "document"=>"Small Box", 
                            //"description"=> !empty($complete_sku)?$complete_sku:"Goods" 
							"description"=> $complete_sku 
                    ) 
            ),
			"returnSpecs"=> array( 
				"size"=>"SMALL", 
				"weight"=>$weight,
				"packageDetails"=> array(
						"itemsCount"=> $box_pieces, 
						"document"=>"Small Box", 
						//"description"=> !empty($complete_sku)?$complete_sku:"Goods" 
						"description"=> $complete_sku 
				) 
			), 
            "notes"=> $notes,//"DIGGIPACKS FULFILLMENT | DeliveryDate :".$ShipArr['promise_deliver_date'],
            "cod"=> $cod_amount, 
            "dropOffAddress"=> array(
                "city"=> $receiver_city, 
                "zone"=> "", 
                "district"=> "", 
                "firstLine"=> $ShipArr['reciever_address'],
                "secondLine"=> "",
                "buildingNumber"=> "", 
                "floor"=>"", 
				"merchantName" =>  $sellername ,//as per client sahred to add new param
                "apartment"=> "" 
            ), 
			"returnAddress"=> array(
                "city"=> $sender_city, 
                "zone"=> "", 
                "district"=> "", 
                "firstLine"=> $sender_address,
                "secondLine"=> "",
                "buildingNumber"=> "", 
                "floor"=>"", 
                "apartment"=> "",
				"merchantName" =>  $sellername //as per client sahred to add new param
            ),
			"merchantName" =>  $sellername,  //as per client sahred to add new param
            "businessReference"=> $ShipArr['slip_no'],
			"allowToOpenPackage"=>$open_package_flag,
			"webhookUrl"=> 'https://api.diggipacks.com/BostaAPI/trackResponse',
            "receiver"=> array(
                    "firstName"=> $ShipArr['reciever_name'],
                    "lastName"=> "",
                    "phone"=> "01".remove_phone_format($reciver_phone_number), // use  less then 13 chanracter for phone string
                    "email"=> !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
			)
		);

		$json_string = json_encode($request_params_array);
		// echo $json_string; die;
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
			'Authorization: '.$token,
			'Content-Type: application/json'
		  ),
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		$responseArray = json_decode($response, true);

		if($responseArray['success'] === true ){
			$slipNo = $ShipArr['slip_no'];
			$successstatus = "Success";
			$label_client_awb = $responseArray['data']['_id'];
			$client_awb = $responseArray['data']['trackingNumber'];
			//$token_api_url = $counrierArr['api_url'].'v0/deliveries/awb/'.$client_awb;
			$token_api_url = $counrierArr['api_url'].'v0/deliveries/awb/'.$label_client_awb;
			// $labelResponse = getBostaLabel($token,$token_api_url);
			$labelResponse = getBulkBostaLabel($token,$label_client_awb);
			
			$label = base64_decode($labelResponse['data']);
			
			//$generated_pdf = file_get_contents($label);
			//echo $generated_pdf;die;
			file_put_contents("assets/all_labels/$slipNo.pdf", $label);
			$fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';
			$return_array = array("error"=>'false',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel,'bosta_label_id'=>$label_client_awb));
		}else{
			$successstatus = "Fail";
			$return_array =  array("error"=>"true","msg"=>$responseArray['message']);
		}

		$CI =& get_instance();
		$CI->load->model('Ccompany_model');
		$CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_string);

		return $return_array;

	}


	function getBostaLabel($token=null, $token_api_url=null){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $token_api_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Authorization: '.$token
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$responseArray = json_decode($response, true);
		return $responseArray;
	}

	function getBulkBostaLabel($token=null,$label_client_awb=null){

		$ship_data = array(
			"trackingNumbers"=> $label_client_awb,
			"requestedAwbType" => "A6", 
			"lang" => "en" 
		); 
		$json_data = json_encode($ship_data);
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://app.bosta.co/api/v2/deliveries/mass-awb',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>$json_data,
		CURLOPT_HTTPHEADER => array(
			'Authorization: '.$token,
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$responseArray = json_decode($response, true);
		return $responseArray;
	}


?>