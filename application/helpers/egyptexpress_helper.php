<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

  
	
	function EgyptExpressArr($sellername=null,$ShipArr=array(), $counrierArr=array(), $complete_sku=null,$c_id=null,$box_pieces1=null,$super_id=null){
		
		
		$API_URL = $counrierArr['api_url'].'/CreateAirwayBill';
		$userName = $counrierArr['user_name'];
		$password = $counrierArr['password'];
		$AccountNo = $counrierArr['courier_account_no'];
		
		$currency = site_configTable("default_currency");//"EGP"; 

		$receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'egyptexpress_city',$super_id);
		
		
		if(empty($receiver_city)){
			return array("error"=>'true',"msg"=>'Receiver city empty');
		}


		$box_pieces = empty($box_pieces1)?1:$box_pieces1;
		$weight = ($ShipArr['weight'] == 0)?1:$ShipArr['weight'];

		$cod_amount = ($ShipArr['pay_mode'] == "COD")?$ShipArr['total_cod_amt']:0;

		$complete_sku = empty($complete_sku)?$ShipArr['status_description']:$complete_sku;

		
		if(empty($complete_sku)){
			$complete_sku = "Goods / Weight: ".$weight." kg";
		}else{
			$complete_sku = $complete_sku;
		}

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
		

		

		//$ShipArr['origin'] = $ShipArr['destination'];
		$sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'egyptexpress_city', $super_id);
		//echo "sender_city=".$sender_city;die;
		$receiver_country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
		$sender_country = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country',$super_id);

		$receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code',$super_id);
		$sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code',$super_id);
		$sender_city_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'egyptexpress_city_code',$super_id);
		$receiver_city_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'egyptexpress_city_code',$super_id);

		if(empty($sender_city)){
			return array("error"=>'true',"msg"=>'Sender city empty');
		}
		$request_params_array = array(
            "UserName"=> $userName,
			"Password"=> $password,
			"AccountNo"=> $AccountNo,
            "AirwayBillData"=> array( 
                    "AirWayBillCreatedBy"=>$sellername,
					"CODAmount"=> $cod_amount,
					"CODCurrency"=> $currency,
					"Destination"=>$receiver_city_code,//"ALX",
					"DutyConsigneePay"=> 0,
					"GoodsDescription"=>$complete_sku,
					"NumberofPeices"=> $box_pieces,
					"Origin"=> $sender_city_code,//"CAI",
					"ProductType"=> "FRE",
					"ReceiversAddress1"=> $ShipArr['reciever_address'],
					"ReceiversAddress2"=> $ShipArr['reciever_address'],
					"ReceiversCity"=> $receiver_city,
					"ReceiversCompany"=> $ShipArr['reciever_name'],
					"ReceiversContactPerson"=> $ShipArr['reciever_name'],
					"ReceiversCountry"=>$receiver_country,
					"ReceiversEmail"=> !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
					"ReceiversGeoLocation"=> "",
					"ReceiversMobile"=>$ShipArr['reciever_phone'],
					"ReceiversPhone"=> $ShipArr['reciever_phone'],
					"ReceiversPinCode"=> "",
					"ReceiversProvince"=> "",
					"ReceiversSubCity"=> $receiver_country_code,
					"SendersAddress1"=>$sender_address,
					"SendersAddress2"=> "",
					"SendersCity"=> $sender_city,
					"SendersCompany"=> $sellername,
					"SendersContactPerson"=> $sellername,
					"SendersCountry"=>$sender_country,
					"SendersEmail"=>$senderemail,
					"SendersGeoLocation"=> "",
					"SendersMobile"=>$senderphone,
					"SendersPhone"=> $senderphone,
					"SendersPinCode"=> "",
					"SendersSubCity"=> $sender_country_code,//"EG",
					"ServiceType"=> "FRG",
					"ShipmentDimension"=> "",
					"ShipmentInvoiceCurrency"=> $currency,
					"ShipmentInvoiceValue"=> $cod_amount,
					"ShipperReference"=> $ShipArr['slip_no'],
					"ShipperVatAccount"=> "",
					"SpecialInstruction"=> "",
					"Weight"=> $weight
            	)
			);
			

		$json_string = json_encode($request_params_array);



		$Allarra = array('api_url' => $API_URL,'data'=>$json_string,'action'=>'do_forward');                                
            
		$dataJson = json_encode($Allarra);

		$url = $url =  "http://fastcoo.net/fastcoo-tech/fs_files/egypt_express_api.php"; 

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


		curl_close($curl);
		$responseArray = json_decode($response, true);
		
		
		if($responseArray['Code'] == 1  || $responseArray['Code'] == "1"){
			$slipNo = $ShipArr['slip_no'];
			$successstatus = "Success";
			
			$client_awb = $responseArray['AirwayBillNumber'];
			
			$labelResponse = getEgExpressLabel($client_awb,$userName, $password, $AccountNo,$counrierArr['api_url']);
			$generated_pdf = base64_decode($labelResponse);
			//$generated_pdf = file_get_contents($labelResponse);
			file_put_contents("assets/all_labels/$slipNo.pdf", $generated_pdf);
			$fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';
			$return_array = array("error"=>'false',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel));
		}else{
			$successstatus = "Fail";
			$return_array =  array("error"=>"true","msg"=>$responseArray['Description']);
		}

		$CI =& get_instance();
		$CI->load->model('Ccompany_model');
		$CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_string);

		return $return_array;

	}


	function getEgExpressLabel($client_awb=null,$userName=null, $password=null, $AccountNo=null,$api_url=null){

		$api_url = $api_url ."/AirwayBillPDFFormat";
		
		$params = array(

				"AccountNo" =>$AccountNo,
				"AirwayBillNumber"=>$client_awb,
				"Country"=>"",
				"Password"=>$password,
				"RequestUser"=>"",
				"UserName"=>$userName
				
		);
		$json_string = json_encode($params);

		$Allarra = array('api_url' => $api_url,'data'=>$json_string,'action'=>'get_label');                                
            
		$dataJson = json_encode($Allarra);

		$url = $url =  "http://fastcoo.net/fastcoo-tech/fs_files/egypt_express_api.php"; 

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

		curl_close($curl);

		$responseArray = json_decode($response, true);
		if($responseArray['Code'] == 1  || $responseArray['Code'] == "1"){
			return $responseArray['ReportDoc'];
		}
		return '';
	}


?>