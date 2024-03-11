<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ccompany_auto_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

      
    public function GetSlipNoDetailsQry($slip_no=null,$super_id) {
        
        $this->db->where('super_id', $super_id);
       
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('slip_no', $slip_no);
        $this->db->where('deleted', 'N');
       // $this->db->where('status', 'Y');
        $query = $this->db->get();
		//echo $this->db->last_query();exit;
      return  $query->row_array();
    }
      
    
    public function GetdeliveryCompanyUpdateQry($cc_id=null,$super_id=null,$ShipArr_custid = null) {
        
         $this->db->where('super_id', $super_id);      
         $this->db->where('cc_id', $cc_id);
         $this->db->select('*');
         $this->db->from('courier_company_seller');
         $this->db->where('deleted', 'N');
         $this->db->where('status', 'Y');
         $this->db->where('cust_id', $ShipArr_custid);
         $this->db->order_by("company");
         $query = $this->db->get();
     //   echo $this->db->last_query(); exit;
 
         if ($query->num_rows()> 0)
         {
             return $query->row_array();
         }
         else 
         {
            $this->db->where('super_id', $super_id);
             $this->db->where('cc_id', $cc_id);
             $this->db->select('*');
             $this->db->from('courier_company');
             $this->db->where('deleted', 'N');
             $this->db->where('status', 'Y');
             $this->db->order_by("company");
             $query = $this->db->get();
        // echo $this->db->last_query();exit;
             return $query->row_array(); 
         }
    }
	
	
	
	public function getdestinationfieldshow_auto_array($id=null,$field=null,$super_id){
	
                
		 $sql ="SELECT $field FROM country where id='$id' and super_id='".$super_id."'";
		$query = $this->db->query($sql);
		$result=$query->row_array();
		return $result[$field];
	}
	
	public function Getskudetails_forward($slip_no=null,$super_id)	{
            $this->db->where('super_id', $super_id);

	$this->db->select('sku,description,piece,cod');
	$this->db->from('diamention_fm');
	$this->db->where('slip_no',$slip_no);
	$query = $this->db->get();
	return $query->result_array();
	}
	
	public function GetshipmentUpdate_forward(array $data,$awb=null){
           // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
		 $this->db->update('shipment_fm',$data,array('slip_no'=>$awb));
               //  $this->db->query("UPDATE `zone_list_fm` SET `todayCount`=todayCount+1 where cc_id='".$data['frwd_company_id']."'");
              // echo $this->db->last_query(); die;
	}
	
	public function GetstatuInsert_forward(array $data){
            
		$this->db->insert('status_fm',$data);
		//echo $this->db->last_query();
        }
	
	public function AramexArray(array $ShipArr, array $counrierArr, $complete_sku=null, $pay_mode=null, $CashOnDeliveryAmount=null, $services=null,$super_id=null){
		$sender_city = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'aramex_city',$super_id);
		$reciever_city = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'aramex_city',$super_id);
		$date = (int) microtime(true) * 1000;
        if($ShipArr['weight']==0)
        {  $weight= 1;
        }
        else { $weight = $ShipArr['weight'] ; }
		$params = array(
			'ClientInfo' =>array(
				'UserName' => $counrierArr['user_name'],
				'Password' => $counrierArr['password'],
				'Version' => 'v1',
				'AccountNumber' => $counrierArr['courier_account_no'],
				'AccountPin' => $counrierArr['courier_pin_no'],
				'AccountEntity' => 'RUH',
				'AccountCountryCode' => 'SA'
			),
			'LabelInfo' => array("ReportID" => 9729, "ReportType" => "URL"),
			'Shipments' =>array(
				0 =>array(
					'Reference1' => '',
					'Reference2' => '',
					'Reference3' => '',
					'Shipper' =>array(
						'Reference1' => $ShipArr['slip_no'],
						'Reference2' => '',
						'AccountNumber' => $counrierArr['courier_account_no'],
						'PartyAddress' =>array(
							'Line1' => $ShipArr['sender_address'],
							'Line2' => '',
							'Line3' => '',
							'City' => $sender_city,
							'StateOrProvinceCode' => '',
							'PostCode' => '0000',
							'CountryCode' => 'SA',
							'Longitude' => 0,
							'Latitude' => 0,
							'BuildingNumber' => NULL,
							'BuildingName' => NULL,
							'Floor' => NULL,
							'Apartment' => NULL,
							'POBox' => NULL,
							'Description' => NULL,
						),
						'Contact' =>array(
							'Department' => '',
							'PersonName' => $ShipArr['sender_name'],
							'Title' => '',
							'CompanyName' => $ShipArr['sender_name'],
							'PhoneNumber1' => $ShipArr['sender_phone'],
							'PhoneNumber1Ext' => '',
							'PhoneNumber2' => '',
							'PhoneNumber2Ext' => '',
							'FaxNumber' => '',
							'CellPhone' => $ShipArr['sender_phone'],
							'EmailAddress' => $ShipArr['sender_email'],
							'Type' => '',
						),
					),
					'Consignee' =>array(
						'Reference1' => '',
						'Reference2' => '',
						'AccountNumber' => '',
						'PartyAddress' =>array(
							'Line1' => $ShipArr['reciever_address'],
							'Line2' => '',
							'Line3' => '',
							'City' => $reciever_city,
							'StateOrProvinceCode' => '',
							'PostCode' => '0000',
							'CountryCode' => 'SA',
							'Longitude' => 0,
							'Latitude' => 0,
							'BuildingNumber' => '',
							'BuildingName' => '',
							'Floor' => '',
							'Apartment' => '',
							'POBox' => NULL,
							'Description' => '',
							),
						'Contact' =>array(
							'Department' => '',
							'PersonName' => $ShipArr['reciever_name'],
							'Title' => '',
							'CompanyName' => $ShipArr['reciever_name'],
							'PhoneNumber1' => $ShipArr['reciever_phone'],
							'PhoneNumber1Ext' => '',
							'PhoneNumber2' => '',
							'PhoneNumber2Ext' => '',
							'FaxNumber' => '',
							'CellPhone' => $ShipArr['reciever_phone'],
							'EmailAddress' => $ShipArr['reciever_email'],
							'Type' => '',
						),
					),
					'ThirdParty' =>array(
						'Reference1' => '',
						'Reference2' => '',
						'AccountNumber' => '',
						'PartyAddress' =>array(
							'Line1' => '',
							'Line2' => '',
							'Line3' => '',
							'City' => '',
							'StateOrProvinceCode' => '',
							'PostCode' => '',
							'CountryCode' => '',
							'Longitude' => 0,
							'Latitude' => 0,
							'BuildingNumber' => NULL,
							'BuildingName' => NULL,
							'Floor' => NULL,
							'Apartment' => NULL,
							'POBox' => NULL,
							'Description' => NULL,
						),
						'Contact' =>array(
							'Department' => '',
							'PersonName' => '',
							'Title' => '',
							'CompanyName' => '',
							'PhoneNumber1' => '',
							'PhoneNumber1Ext' => '',
							'PhoneNumber2' => '',
							'PhoneNumber2Ext' => '',
							'FaxNumber' => '',
							'CellPhone' => '',
							'EmailAddress' => '',
							'Type' => '',
						),
					),
					'ShippingDateTime' => "/Date(" . $date . ")/",
					'DueDate' => "/Date(" . $date . ")/",
					'Comments' => '',
					'PickupLocation' => '',
					'OperationsInstructions' => '',
					'AccountingInstrcutions' => '',
					'Details' =>array(
						'Dimensions' => NULL,
						'ActualWeight' =>array(
							'Unit' => 'KG',
							'Value' => $weight,
							//'Value' => '1',
						),
						'ChargeableWeight' => NULL,
						'DescriptionOfGoods' => $complete_sku,
						'GoodsOriginCountry' => 'SA',
						'NumberOfPieces' => 1,
						'ProductGroup' => 'DOM',
						'ProductType' => 'CDS',
						'PaymentType' => $pay_mode,
						'PaymentOptions' => "",
						'CustomsValueAmount' => NULL,
						'CashOnDeliveryAmount' => $CashOnDeliveryAmount,
						'InsuranceAmount' => NULL,
						'CashAdditionalAmount' => NULL,
						'CashAdditionalAmountDescription' => '',
						'CollectAmount' => NULL,
						'Services' => $services,
						'Items' =>array(),
					),
					'Attachments' =>array(),
					'ForeignHAWB' => $ShipArr['slip_no'],
					'TransportType ' => 0,
					'PickupGUID' => '',
					'Number' => NULL,
					'ScheduledDelivery' => NULL,
				),
			),
			'Transaction' =>array(
				'Reference1' => '',
				'Reference2' => '',
				'Reference3' => '',
				'Reference4' => '',
				'Reference5' => '',
			)
		);
		
		return $params;
	}
	
	public function AxamexCurl($url = null, array $headers, $dataJson = null, $c_id = null, array $ShipArr,$super_id=null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $xml2 = new SimpleXMLElement($response);
        $awb_array = json_decode(json_encode((array) $xml2), TRUE);
        $logresponse =   json_encode($awb_array);
        $successres = $awb_array['HasErrors'];
       
            if($successres == 'true') 
            {
                $successstatus  = "Fail";
            }else {
                $successstatus  = "Success";
            }
            
        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $dataJson);

        return $awb_array;
    }
	
	public function Update_Shipment_Status($slipNo=null, $client_awb=null, $CURRENT_TIME=null, $CURRENT_DATE=null, $company=null, $comment=null, $fastcoolabel=null,$c_id=null,$super_id=null){
		
             if($company=='Esnad' || $company=='Labaih' || $company=='Clex')
             {
            $label_type='1';
             }
        else
        {
            $label_type='0';
        }
		$updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type);
                
                

		///echo "<br/><pre>";
		//print_r($updateArr);


		$this->GetshipmentUpdate_forward($updateArr, $slipNo,$super_id);
		
		//$returnArr['successAwb'][] = 'AWB No.' . $slipNo . ' forwarded to ARAMEX';
		
		$details = 'Forwarded to ' . $company;
		$statusArr = array(
			'slip_no' => $slipNo,
			'new_location' => $this->session->userdata('user_details')['city'],
			'new_status' => 10,
			'pickup_time' => $CURRENT_TIME,
			'pickup_date' => $CURRENT_DATE,
			'Activites' => 'Forward to Delivery Station',
			'Details' => $details,
			'entry_date' => $CURRENT_DATE,
			'user_id' => $super_id,
			'user_type' => 'fulfillment',
			'comment' => $comment,
			'code' => 'FWD',
			'super_id' => $super_id,
		);
		$this->GetstatuInsert_forward($statusArr);
		//send_message($slipNo);
		
		return true;
	}
	
	public function SafeArray(array $ShipArr, array $counrierArr, $complete_sku=null, $Auth_token=null,$super_id=null){
		$sender_city_safe = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'safe_arrival',$super_id);
		$receiver_city_safe = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'safe_arrival',$super_id);
		
		$API_URL = $counrierArr['api_url'];

		$sender_data = array(
			"address_type" => "residential",
			"name" => $ShipArr['sender_name'],
			"email" => $ShipArr['sender_email'],
			"street" => $ShipArr['sender_address'],
			"city" => array(
				"id" => $sender_city_safe
			),
			"phone" => $ShipArr['sender_phone']
		);
		$recipient_data = array(
			"address_type" => "residential",
			"name" => $ShipArr['reciever_name'],
			"email" => $ShipArr['reciever_email'],
			"street" => $ShipArr['reciever_address'],
			"city" => array(
				"id" => $receiver_city_safe
			),
			"phone" => $ShipArr['reciever_phone']
		);
		$dimensions = array(
			"weight" => $ShipArr['weight']
		);
		$package_type = array(
			"courier_type" => 'IN_5_DAYS'
		);
		$charge_items = array(
			array(
				"paid"=> false,
				"charge" => $ShipArr['total_cod_amt'],
				"charge_type" => $ShipArr['mode']
			),
			array(
				"paid"=> false,
				"charge" => 0,
				"charge_type" => 'service_custom'
			)
		);

		$param = array(
			"sender_data" => $sender_data,
			"recipient_data" => $recipient_data,
			"dimensions" => $dimensions,
			"package_type" => $package_type,
			"charge_items" => $charge_items,
			"recipient_not_available" => "do_not_deliver",
			"payment_type" => "cash",
			"payer" => "recipient",
			//"parcel_value" => 100,
			"fragile" => true,
			"note" => $complete_sku,
			"piece_count" => 1,  //$ShipArr['pieces'],
			"force_create" => true,
			"reference_id" => $ShipArr['slip_no']
		);

		$header = array(
			"Authorization" => "Bearer " . $responseArray['data']['id_token'],
			"Content-Type" => "application/json",
			"Accept" => "application/json"
		);

		$dataJson = json_encode($param);

		$response = send_data_to_safe_curl($dataJson, $Auth_token, $API_URL);
		$logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

          $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $dataJson);
		return $response;
	}

	public function EsnadArray(array $ShipArr, array $counrierArr, $esnad_awb_number=null, $complete_sku=null, $Auth_token=null,$c_id=null,$box_pieces=NULL,$super_id=null){
		$receiver_city = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'esnad_city',$super_id);
		 $sender_city = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'esnad_city',$super_id); 
		 $declared_charge = $ShipArr['total_cod_amt'];
        $iscod = false;
        $cod_amount = $ShipArr['total_cod_amt'];
        
        if ($ShipArr['mode'] == 'COD') {
            $pay_mode = "COD";
            $declared_charge =$ShipArr['total_cod_amt'];
            $iscod = true;
        } else {
            $pay_mode = "PP";
            $cod_amount = 0;
            $iscod = false;
            if( $ShipArr['total_cod_amt']>0)
            $declared_charge = $ShipArr['total_cod_amt'];
            else
            $declared_charge =1;
        }
        $comp_api_url = $counrierArr['api_url'];  
        
        $Auth_token = $counrierArr['auth_token'];     
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
        
        $param = array(
                "currency"=>"SAR",
                "codAmount"=>$cod_amount,
                "customerCode"=> "Kedan",
                "customerNo"=> $ShipArr['slip_no'],
                "trackingNo"=> $ShipArr['slip_no'],
                "ifpickup"=> false,
                "token"=>$Auth_token,
                "isCod"=> $iscod,
                "orderAmount" =>$declared_charge,
                "packageList"=> array(array(
                    "packageCode"=> $complete_sku,
                    "packageHeight"=> 0,
                    "packageLength"=>0,
                    "packageVolume"=> $box_pieces,
                    "packageWeight"=> (float)$weight,
                    "packageWidth"=>0
                    )
                ),
                "receiver"=>array(
                    "address"=>html_entity_decode($ShipArr['reciever_address']) ,
                    "cityId"=> $receiver_cityID,//(int)$ShipArr['destination'],
                    "cityName"=> $receiver_city,
                    "countryId"=> 1876,
                    "countryName"=> "Saudi Arabia",
                    "name"=> $ShipArr['reciever_name'],
                    "phone"=> $ShipArr['reciever_phone']
                ),
                "sender"=>array(
                    "address"=> html_entity_decode($ShipArr['sender_address']),
                    "cityName"=> $sender_city,
                    "countryId"=> 1876,
                    "countryName"=> "Saudi Arabia",
                    "name"=>$ShipArr['sender_name'],
                    "phone"=> $ShipArr['sender_phone']
                ),
                
                "totalInnerCount"=>1,
                "totalPackageCount"=> 1,
                "totalWeight"=>$weight,
                "totalVolume"=>$box_pieces,
                
            
        );

        
          $dataJson = json_encode($param); 
  

        $headers = array(
            "Content-Type: application/json",
            "token: $Auth_token"
        );
       // echo $comp_api_url; 
        $response = send_data_to_curl($dataJson, $comp_api_url, $headers);
               
        //exit($response);
        $logresponse =   json_encode($response); 
        $responseArray = json_decode($response, true);
        $successres = $responseArray['code'];
        if($successres  == "1000") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus,$ShipArr['slip_no'],$super_id,$dataJson );
        return $response;
	}
        
        
    public function LabaihArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$super_id=null,$c_id=null) {
        $receiver_city = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'labaih',$super_id);
        $sender_city = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'labaih',$super_id);
        $lat = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'COD';
            // $cod_amount=0;
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }
       //echo $box_pieces; die;
        if($box_pieces>0)
        {
          $pieces=$box_pieces;
        }
        else
        {
          $pieces= $ShipArr['pieces'];
        }

        
        $comp_api_url = $counrierArr['api_url'];
        
        $pickupDate=date("Y-m-d");
        $deliveryDate=date('Y-m-d',strtotime($pickupDate.'+ 2 days'));

        $Data_array=
     array(
        'api_key'=>$counrierArr['auth_token'], /***/
        'pickupDate'=>$pickupDate, /***/
        'deliveryDate'=>$deliveryDate, /***/
        'customerOrderNo'=>$ShipArr['slip_no'], /***/
        'noOfPieces'=>$pieces,
        'weightKg'=>$ShipArr['weight'],
        'dimensionsCm'=>$complete_sku,
        'itemDescription'=>$ShipArr['status_describtion'],
        'paymentMethod'=>$cod_collection_mode,
        'paymentAmount'=>$cod_amount,
        'consigneeName'=>$ShipArr['reciever_name'], /***/
        'consigneeEmail'=>$ShipArr['reciever_email'],
        'consigneeMobile'=>$ShipArr['reciever_phone'],
        'consigneePhone'=>$ShipArr['reciever_phone'],  /***/
        'consigneeCity'=>$receiver_city, /***/
        'consigneeCommunity'=>$receiver_city,
        'consigneeAddress'=>$ShipArr['reciever_address'], /***/
        'consigneeFlatFloor'=>'',
        'consigneeLatLong'=>$ShipArr['dest_lat'].','.$ShipArr['dest_lng'],
        'consigneeSplInstructions'=>$ShipArr['status_describtion'],
        'store'=>$ShipArr['sender_name'], /***/
        'shipperName'=>$ShipArr['sender_name'], /***/
        'shipperMobile'=>$ShipArr['sender_phone'], /***/
        'shipperEmail'=>$ShipArr['sender_email'],
        'shipperCity'=>$sender_city,
        'shipperDistrict'=>$sender_city,
        'shipperAddress'=>$ShipArr['sender_address'],
        'shipperLatLong'=>$lat.','.$lang,
        
    );
//print_r($Data_array); die;
  $headers = array(
            "Content-type:application/x-www-form-urlencoded",
           "cache-control: no-cache"
           );
  
  
   $dataJson=http_build_query($Data_array);
  // echo $dataJson;  die;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        curl_close($ch);
      //  print_r($response);
        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['status'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == 200) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

         $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $dataJson);
        return $response_array;
    }

    public function ClexArray($ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id) 
    {
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'clex',$super_id);
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'clex',$super_id);
        $comp_api_url = $counrierArr['api_url'];
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] == 'COD') {
            $billing_type = 'COD';
            // $cod_amount=0;
        } else {
            $billing_type = 'PREPAID';
            $cod_amount = 0;
        }
              
        
            $box_pieces = 1;
      

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       

        $request_data = array(
            'shipment_reference_number' => $ShipArr['slip_no'],
            'shipment_type' => 'delivery',
            'billing_type' => $billing_type,
            'collect_amount' => $cod_amount,
            'primary_service' => 'delivery',
            'secondary_service' => '',
            'item_value' => '',
            'consignor' => $ShipArr['sender_name'],
            'consignor_email' => $ShipArr['sender_email'],
            'origin_city' => $sender_city,
            'origin_area_new' => '',
            'consignor_street_name' => $ShipArr['sender_address'],
            'consignor_building_name' => '',
            'consignor_address_house_appartment' => '',
            'consignor_address_landmark' => '',
            'consignor_country_code' => '+966',
            'consignor_phone' => remove_phone_format($ShipArr['sender_phone']),
            'consignor_alternate_country_code' => '',
            'consignor_alternate_phone' => '',
            'consignee' => $ShipArr['reciever_name'],
            'consignee_email' => $ShipArr['receiver_email'],
            'destination_city' => $receiver_city,
            'destination_area_new' => '',
            'consignee_street_name' => $ShipArr['reciever_address'],
            'consignee_building_name' => '',
            'consignee_address_house_appartment' => '',
            'consignee_address_landmark' => '',
            'consignee_country_code' => '+966',
            'consignee_phone' => $ShipArr['reciever_phone'],
            'consignee_alternate_country_code' => '',
            'consignee_alternate_phone' => '',
            'pieces_count' => $box_pieces,
            'order_date' => date('d-m-Y'),
            'commodity_description' => $complete_sku,
            'pieces' => array(array(
                    'weight_actual' => $weight,
                    'volumetric_width' => '',
                    'volumetric_height' =>'',
                    'volumetric_depth' =>'',
                ))
        );
        $dataJson = json_encode($request_data);
        $access_token = $counrierArr['auth_token'];

        $headers = array(
            "Content-type:application/json",
            "Access-token:$access_token");

        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);

        $response_array = json_decode($response, true);
        //print "<pre>"; print_r($response_array);die;
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['message'];
        $error = isset($response_array['error'])?$response_array['error']:true;

        if($successres == 'Succesfully added.' || $error == false) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],  $super_id,$dataJson);
        return $response_array;
    }
 
   

public function BarqfleethArray(array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null, $c_id = null, $super_id = null) {
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city');
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city');
        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute');
        $declared_charge = $ShipArr['total_cod_amt'];

        //echo "sadsdsad"; print_r($ShipArr); 
        //die;

        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'COD';
            $cod_amount = 0;
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }

        $comp_api_url = $counrierArr['api_url'];

        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));

        $params = array(
            "email" => $counrierArr['user_name'],
            "password" => $counrierArr['password']
        );

        $data = json_encode($params);
        $request_url = "https://staging.barqfleet.com/api/v1/merchants/login";
        $firstheader = array(
            "Authorization: " . $counrierArr['auth_token'],
            "Content-Type: application/json",
            "Accept: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $firstheader);
        $response = curl_exec($ch);
        curl_close($ch);

        $response2 = json_decode($response, true);
        $Authorization = $response2['token'];
        $params = array(
            "invoice_total" => $ShipArr['total_cod_amt'],
            "payment_type" =>$cod_amount,
            "shipment_type" => "instant_delivery",
            "hub_id" => 240,
            "hub_code" => "FASTCOO",
            "merchant_order_id" => $ShipArr['slip_no'],
            "customer_details" => array(
                "first_name" => $ShipArr['sender_name'],
                "last_name" => "",
                "country" => "Saudi Arabia",
                "city" => $receiver_city,
                "mobile" => $ShipArr['reciever_phone'],
                "address" => $ShipArr['reciever_address']
            ),
            "products" => array(
                array(
                    "serial_no" => $sku_name,
                    "qty" => 1,
                    "sku" => '',
                    "color" => '',
                    "brand" => '',
                    "name" => '',
                    "price" => ''
                )
            ),
            "destination" => array(
                "latitude" => '',
                "longitude" => ''
            )
        );

        $dataJson = json_encode($params);

        //echo "<pre>"; print_r($params); 
        //die; 

        $headers = array("Content-type:application/json");
        $url = "https://staging.barqfleet.com/api/v1/merchants/orders";
        $firstheaderr = array(
            "Authorization: " . $Authorization,
            "Content-Type: application/json",
            "Accept: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $firstheaderr);
        $response_ww = curl_exec($ch);
       curl_close($ch);
        $logresponse =   json_encode($response_ww);  
        $response_array = json_decode($response_ww, TRUE); 
        $successres = $response_array['code'];
    
        if($successres != '') 
        {
            $successstatus  = "Fail";
        }else {
            $successstatus  = "Success";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $dataJson);
        return $response_ww;
    }

    public function MakdoonArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null,$super_id,$c_id) {
        $sender_city = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'makhdoom',$super_id);
        $receiver_city = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'makhdoom',$super_id);
        $API_URL = $counrierArr['api_url'];

        $sender_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['sender_name'],
            "email" => $ShipArr['sender_email'],
            'apartment' => '',
            'building' => '',
            "street" => $ShipArr['sender_address'],
            "city" => array(
                "code" => $sender_city
            ),
            'country' => array(
                    'id' => 191,
                ),
            "phone" => $ShipArr['sender_phone']
        );
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => $ShipArr['reciever_email'],
            "street" => $ShipArr['reciever_address'],
            "city" => array(
                "code" => $receiver_city
            ),
            'country' => array(
                    'id' => 191,
                ),
            "phone" => $ShipArr['reciever_phone'],
            'landmark' => '',
        );
        $dimensions = array(
            "weight" => $ShipArr['weight'],
            'width' => 0,
            'length' => 0,
            'height' => 0,
            'unit' => '',
            'domestic' => true,
        );
        $package_type = array(
            "courier_type" => 'EXPRESS_DELIVERY'
        );
        $charge_items = array(
            array(
                "paid" => false,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode'],
                'payer' => 'sender',
            ),
            array(
                "paid" => false,
                "charge" => 0,
                "charge_type" => 'service_custom'
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "cash",
            "payer" => "recipient",
            //"parcel_value" => 100,
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => 1, //$ShipArr['pieces'],
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
          // echo '<pre>';
           //print_r($param);
            // die;
        $dataJson = json_encode($param);

        $response = send_data_to_makdoom_curl($dataJson, $Auth_token, $API_URL);
         $logresponse =   json_encode($response);  
        $successres = $response['status'];
       // echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres == 'success') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $dataJson);
            
        return $response;
    }

	public function SMSAArray($ShipArr, $counrierArr, $complete_sku,$box_pieces,$super_id) {
        $receiver_city = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'samsa_city',$super_id,$c_id);

        $currency_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency',$super_id);

        $sender_city = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'samsa_city',$super_id);
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
       

       // print_r($ShipArr); exit;
        if ($ShipArr['mode'] == 'COD') {
            $codValue = $cod_amount;
        } else {
            $codValue = 0;
        }
        if ($complete_sku == '') {
            $complete_sku = 'Goods';
        }
        $comp_api_url = $counrierArr['api_url'];

        $SMSAXML = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <addShip xmlns="http://track.smsaexpress.com/secom/">
                  <passKey>' . $counrierArr['auth_token'] . '</passKey>
                  <refNo>' . $ShipArr['slip_no'] . '</refNo>
                  <sentDate>' . date('d/m/Y') . '</sentDate>
                  <idNo>' . $ShipArr['booking_id'] . '</idNo>
                  <cName>' . $ShipArr['reciever_name'] . '</cName>
                  <cntry>KSA</cntry>
                  <cCity>' . $receiver_city . '</cCity>
                  <cZip>' . $ShipArr['sender_zip'] . '</cZip>
                  <cPOBox>45</cPOBox>
                  <cMobile>' . $ShipArr['reciever_phone'] . '</cMobile>
                  <cTel1>' . $ShipArr['reciever_phone'] . '</cTel1>
                  <cTel2>' . $ShipArr['reciever_phone'] . '</cTel2>
                  <cAddr1>' . htmlentities(strip_tags($ShipArr['reciever_address'], ENT_COMPAT, 'UTF-8')) . '</cAddr1>
                  <cAddr2>' . htmlentities(strip_tags($ShipArr['reciever_address'], ENT_COMPAT, 'UTF-8')) . '</cAddr2>
                  <shipType>DLV</shipType>
                  <PCs>' . $ShipArr['pieces'] . '</PCs>
                  <cEmail>' . $ShipArr['reciever_email'] . '</cEmail>
                  <carrValue>2</carrValue>
                  <carrCurr>2</carrCurr>
                  <codAmt>' . $codValue . '</codAmt>
                  <weight>' . $ShipArr['weight'] . '</weight>
                  <custVal>2</custVal>
                  <custCurr>'. $currency_code .'</custCurr>
                  <insrAmt>34</insrAmt>
                  <insrCurr>3</insrCurr>
                  <itemDesc>' . htmlentities(strip_tags($ShipArr['status_describtion'], ENT_COMPAT, 'UTF-8')) . '</itemDesc>
                  <sName>' . htmlentities(strip_tags($ShipArr['sender_name'])) . '</sName>
                  <sContact>' . $ShipArr['sender_name'] . '</sContact>
                  <sAddr1>' . htmlentities(strip_tags($ShipArr['sender_address'], ENT_COMPAT, 'UTF-8')) . '</sAddr1>
                  <sAddr2>' . htmlentities(strip_tags($ShipArr['sender_address'], ENT_COMPAT, 'UTF-8')) . '</sAddr2>
                  <sCity>' . $sender_city . '</sCity>
                  <sPhone>' . $ShipArr['sender_phone'] . '</sPhone>
                  <sCntry>KSA</sCntry>
                  <prefDelvDate>20/02/2019</prefDelvDate>
                  <gpsPoints>2</gpsPoints>
                </addShip>
                 <getPDF xmlns="http://track.smsaexpress.com/secom/">
                  <awbNo>' . $pdfawb . '</awbNo>
                  <passKey>' . $counrierArr['auth_token'] . '</passKey>
                </getPDF>
            </soap:Body>
        </soap:Envelope>';


        $headers = array(
            "Content-type: text/xml;charset=utf-8",
            "Accept: application/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://track.smsaexpress.com/secom/addShip",
            "Content-length: " . strlen($SMSAXML),
        );
        $cookiePath = tempnam('/tmp', 'cookie');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $SMSAXML);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        curl_close($ch);

       
        $check = $response;
        $respon = trim($check);
        $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $response);

       
        $xml2 = new SimpleXMLElement($respon);
        $again = $xml2;
        $a = array("qwb" => $again);

        $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);

        if (preg_match('/\bFailed\b/', $complicated)) {
            $ret = $complicated;

        } 
             if (empty($ret)) 
            {
                $successstatus  = "Success";
            }else {
                $successstatus  = "Fail";
            }


         $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id,$SMSAXML);
        return $respon;
    }

  	public function ZajilArray($ShipArr, $counrierArr, $complete_sku, $c_id, $super_id) {
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'zajil');
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'zajil');
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] === 'COD') {
            $cod_collection_mode = 'CASH';
            // $cod_amount=0;
        } else {
            $cod_collection_mode = '';
            $cod_amount = 0;
        }


        $comp_api_url = $counrierArr['api_url'];

        $data_request = array(
            'consignments' => array(
                array(
                    'customer_code' => $counrierArr['user_name'],
                    'reference_number' => '',
                    'load_type' => 'NON-DOCUMENT',
                    'description' => $complete_sku,
                    'service_type_id' => 'B2B',
                    'cod_favor_of' => '',
                    'dimension_unit' => 'cm',
                    'length' => '',
                    'width' => '',
                    'height' => '',
                    'weight_unit' => 'kg',
                    'weight' => $ShipArr['weight'],
                    'declared_value' => $declared_charge,
                    'declared_price' => '',
                    'cod_amount' => $cod_amount,
                    'cod_collection_mode' => $cod_collection_mode,
                    'prepaid_amount' => '',
                    'num_pieces' => $ShipArr['pieces'],
                    'customer_reference_number' => $ShipArr['slip_no'],
                    'is_risk_surcharge_applicable' => true,
                    'origin_details' =>
                    array(
                        'name' => $ShipArr['sender_name'],
                        'phone' => $ShipArr['sender_phone'],
                        'alternate_phone' => '',
                        'address_line_1' => $ShipArr['sender_address'],
                        'address_line_2' => '',
                        'city' => $sender_city,
                        'state' => ''
                    ),
                    'destination_details' =>
                    array(
                        'name' => $ShipArr['reciever_name'],
                        'phone' => $ShipArr['reciever_phone'],
                        'alternate_phone' => '',
                        'address_line_1' => $ShipArr['reciever_address'],
                        'address_line_2' => '',
                        'city' => $receiver_city,
                        'state' => ''
                    ),
                    'pieces_detail' =>
                    array(
                        'description' => $description,
                        'declared_value' => $declared_charge,
                        'weight' => $ShipArr['weight'],
                        'height' => '',
                        'length' => '',
                        'width' => ''
                    )
        )));
        $comp_auth_token = $counrierArr['auth_token'];
        $headers = array(
            "Content-type:application/json",
            "api-key:$comp_auth_token");

        $dataJson = json_encode($data_request);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        curl_close($ch);
        //print_r($response);
        $response_array = json_decode($response, true);
          $logresponse =   json_encode($response_array);  
        $successres = $response_array['data'][0]['success'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($response['status'] == 'OK' && $successres == true) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

         $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $dataJson);
        return $response_array;
    }
   
    
    public function NaqelArray(array $ShipArr, array $counrierArr, $complete_sku = null, $box_pieces1 = null, $Auth_token = null, $c_id = null) 
    {       
            //print "<pre>"; print_r($ShipArr);die; 
            $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'naqel_city_code',$ShipArr['super_id']);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'naqel_city_code',$ShipArr['super_id']); 
           
               if ($ShipArr['mode'] == 'CC') {
                       $BillingType = 1;
                   } elseif ($ShipArr['mode'] == "COD") {
                       $BillingType = 5;
                   }
              
                       $box_pieces = 1;
                 
           
               if($ShipArr['weight']==0)
                   {  
                       $weight= 1;
                   }
                   else { 
                       $weight = $ShipArr['weight'] ; 
                   }
                  
                $API_URL = $counrierArr['api_url'];    
                $user_name = $counrierArr['user_name'];    
                $password = $counrierArr['password'];
                $xml_new = '<?xml version="1.0" encoding="utf-8"?>
                       <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                           <soapenv:Header/>
                           <soapenv:Body>
                               <tem:CreateWaybill>
                                   <tem:_ManifestShipmentDetails>
                                       <tem:ClientInfo>
                                       <tem:ClientAddress>
                                           <tem:PhoneNumber>'.$ShipArr['sender_phone'].'</tem:PhoneNumber>
                                           <tem:POBox></tem:POBox>
                                           <tem:ZipCode></tem:ZipCode>
                                           <tem:Fax></tem:Fax>
                                           <tem:FirstAddress>'.$ShipArr['sender_address'].'</tem:FirstAddress>
                                           <tem:Location>' . $sender_city . '</tem:Location>
                                           <tem:CountryCode>KSA</tem:CountryCode>
                                           <tem:CityCode>' . $sender_city . '</tem:CityCode>
                                       </tem:ClientAddress>
   
                                       <tem:ClientContact>
                                           <tem:Name>' . $ShipArr['sender_name'] . '</tem:Name>
                                           <tem:Email>' . $ShipArr['sender_email'] . '</tem:Email>
                                           <tem:PhoneNumber>'.$ShipArr['sender_phone'] . '</tem:PhoneNumber>
                                           <tem:MobileNo>' . $ShipArr['sender_phone'] . '</tem:MobileNo>
                                       </tem:ClientContact>
   
                                       <tem:ClientID>'.$user_name.'</tem:ClientID>
                                       <tem:Password>'.$password.'</tem:Password>
                                       <tem:Version>9.0</tem:Version>
                                       </tem:ClientInfo>
   
                                       <tem:ConsigneeInfo>
                                       <tem:ConsigneeName>' .$ShipArr['reciever_name'].'</tem:ConsigneeName>
                                       <tem:Email>' . $ShipArr['reciever_email'] . '</tem:Email>
                                       <tem:Mobile>' . $ShipArr['reciever_phone'] . '</tem:Mobile>
                                       <tem:PhoneNumber>' . $ShipArr['reciever_phone'] . '</tem:PhoneNumber>
                                       <tem:Address>' .$receiver_city . '</tem:Address>
                                       <tem:CountryCode>KSA</tem:CountryCode>
                                       <tem:CityCode>' . $receiver_city .'</tem:CityCode>
                                       </tem:ConsigneeInfo>
   
                                       <tem:BillingType>' . $BillingType . '</tem:BillingType>
                                       <tem:PicesCount>' . $box_pieces . '</tem:PicesCount>
                                       <tem:Weight>' . $weight. '</tem:Weight>
                                       <tem:DeliveryInstruction> </tem:DeliveryInstruction>
                                       <tem:CODCharge>' . $ShipArr['total_cod_amt'] . '</tem:CODCharge>
                                       <tem:CreateBooking>false</tem:CreateBooking>
                                       <tem:isRTO>false</tem:isRTO>
                                       <tem:GeneratePiecesBarCodes>false</tem:GeneratePiecesBarCodes>
                                       <tem:LoadTypeID>36</tem:LoadTypeID>
                                       <tem:DeclareValue>0</tem:DeclareValue>
                                       <tem:GoodDesc>' . $complete_sku . '</tem:GoodDesc>
                                       <tem:RefNo>' .  $ShipArr['slip_no'] . '</tem:RefNo>
                                       <tem:InsuredValue>0</tem:InsuredValue>
                                       <tem:GoodsVATAmount>0</tem:GoodsVATAmount>
                                       <tem:IsCustomDutyPayByConsignee>false</tem:IsCustomDutyPayByConsignee>
                                   </tem:_ManifestShipmentDetails>
                               </tem:CreateWaybill>
                           </soapenv:Body>
                           </soapenv:Envelope>';   
                  
                   $headers = array(
                       "Content-type: text/xml; charset=utf-8",
                       "Content-length: ".strlen($xml_new),
                   );
   
                   $url = $API_URL;
                   $ch = curl_init();
                   curl_setopt($ch, CURLOPT_URL, $url);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                   curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                   curl_setopt($ch, CURLOPT_POST, true);
                   curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_new);
                   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                   $response = curl_exec($ch);
                   $check = $response;
                   $respon = trim($check);
                   $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $respon);
                   $xml2 = new SimpleXMLElement($respon);  
                   $again = $xml2;
                   $a = array("qwb" => $again);
   
                   $complicated_awb = ($a['qwb']->Body->CreateWaybillResponse->CreateWaybillResult);
                   curl_close($ch);
   
                    $awb_array = json_decode(json_encode((array) $complicated_awb), TRUE);
                    $logresponse =   json_encode($awb_array);  
                       $successres = $awb_array['HasError'];
                      
                       //if($successres!== true) 
                       if($successres == "false" || $successres == false) 
                       {
                           $successstatus  = "Success";
                       } else {
                           $successstatus  = "Fail";
                       }
   
                       $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $super_id, $xml_new);
                              
   
                   return $awb_array;
               
               
   
   }
    public function SaeeArray(array $ShipArr, array $counrierArr, $Auth_token = null,$c_id,$box_pieces1) {
     
       $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'saee_city',$ShipArr['super_id']);
       $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'saee_city',$ShipArr['super_id']); 


        $lat = getdestinationfieldshow($ShipArr['origin'], 'latitute');
        $lang = getdestinationfieldshow($ShipArr['origin'], 'longitute');
        $API_URL = $counrierArr['api_url'];
        $Secretkey = $counrierArr['auth_token'];
         $ShipArr['cust_id'];
         $store = getallsellerdatabyID($ShipArr['cust_id'], 'company');   

             $box_pieces = 1 ; 
        

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
       
        if ($ShipArr['mode'] == 'COD') {
            $BookingMode = 'COD';
            $codValue = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['mode'] == 'CC') {
            $BookingMode = 'CC';
            $codValue = 0;
        }

 
        $param = array(
            "ordernumber" => $ShipArr['slip_no'],
            "cashondelivery" => $codValue,
            "name" => $ShipArr['reciever_name'],
            "mobile" => $ShipArr['reciever_phone'],
            "mobile2" => '',
            "streetaddress" => $ShipArr['reciever_address'],
            "streetaddress2" => '',
            "district" => '',
            "city" => $receiver_city,
            "state" => '',
            "zipcode" => $ShipArr['reciever_zip'],
            "custom_value" => '',
            "hs_code" => 'FASTCOO',
            "category_id" => '',
            "weight" => $weight,
            "quantity" => $box_pieces,
            "description" => "",
            "email" => $ShipArr['reciever_email'],
            "pickup_address_id" => '',
            "Pickup_address_code" => '',
            "sendername" => "DIGGIPACKS-".$store,
            "sendermail" => $ShipArr['sender_email'],
            "senderphone" => $ShipArr['sender_phone'],
            "senderaddress" => $ShipArr['sender_address'],
            "sendercity" => $sender_city,
            'sendercountry' => '',
            "sender_hub" => '',
            "latitude" => $lat,
            "longitude" => $lang,
        );
        $all_param_data = json_encode($param); 

      //  Echo "<pre>"; print_r($param); exit; 
        $live_url = $API_URL."/new?secret=$Secretkey";
        $headers = array("Content-type:application/json");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $live_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        $response = curl_exec($ch);
        curl_close($ch);      

        $response = json_decode($response, true);
        $logresponse =   json_encode($response);  
        $successres = $response['success']; 

        if($successres == 'true' || $successres == true) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $super_id, $all_param_data);
        return $response;
    }


    public function EmdadArray($ShipArr, $counrierArr, $complete_sku, $c_id, $super_id) {
        $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
        $password = $counrierArr['password'];
        $url = $counrierArr['api_url'];
        //print_r($ShipArr);exit;
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = $ShipArr['reciever_email'];
        $Receiver_phone = $ShipArr['reciever_phone'];
        $Receiver_address = $ShipArr['reciever_address'];
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }

        $Reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'emdad_city');
        
        $product_type = 'Parcel'; //beone ka database
        $service = '2'; // beone wali
        $description = $ShipArr['status_describtion'];
        if (empty($description)) {
            $description = 'N/A';
        }

        // this is prodect name (column name: status_describtion

        $ajoul_booking_id = $ShipArr['booking_id'];
        $s_name = $ShipArr['sender_name'];
        $s_address = $ShipArr['sender_address'];
        $s_zip = $ShipArr['sender_zip'];
        $s_phone = $ShipArr['sender_phone'];
        $s_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'emdad_city');

        $pay_mode = $ShipArr['mode']; //paymode either CASH or COD:(column name: mode || date
        $codValue = $ShipArr['total_cod_amt']; //COD charges.  :  (column name:     total_cod_amt || date type:
        $product_price = $ShipArr['declared_charge']; //(column name: declared_charge || date type: int || value: 11)
        $booking_id = $ShipArr['slip_no']; // send awb number ajoul
        $shipper_refer_number = $ShipArr['booking_id']; // ajoul ki booking id
        $weight = $ShipArr['weight'];
        if ($weight == 0) {
            $weight = 1;
        }

        $data_string = "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number";

        //weight should be in kg.:(column name: weight || date type
        $NumberOfParcel = $ShipArr['pieces']; //(column name: pieces || date type: int || value: 5)

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

        $response = curl_exec($ch);
      // print_r($response);exit;
         $logresponse =   json_encode($response);  
        $successres = $response['error'];
        //echo "<pre>"; print_r($response)  ;    die;
        if($successres == '') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id,$data_string);
        curl_close($ch);
        
        return $response;
    }
    public function AjeekArray($ShipArr, $counrierArr, $complete_sku, $box_pieces, $c_id, $super_id) {
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'ajeek_city');
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'ajeek_city');
       $latitude = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute');
       $Longitude = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute');
        $api_key = $counrierArr['auth_token'];
        $vendor_id = $counrierArr['courier_pin_no'];
        $user_id = $counrierArr['courier_account_no'];
        $branch_id = $counrierArr['password'];
        $comp_api_url = $counrierArr['api_url'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['mode'] == 'COD') {
            $billing_type = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } else {
            $billing_type = 2;
            $cod_amount = 0;
        }

        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        if ($box_pieces > 0) {
            $pieces = $box_pieces;
        } else {
            $pieces = $ShipArr['pieces'];
        }

        $items_detail = array(
                array(
                    "description" => "parcel1",
                    "length" => $weight,
                    "width" => $weight,
                    "height" => $weight
                )
        );
        $number = $ShipArr['reciever_phone'];
        $number = ltrim($number, '966');
        $number = ltrim($number, '0');
        $number = '00966' . $number;
        $number = str_replace(' ', '', $number);


        
        $request_data = array(
                "user_id" => $user_id,
                "cust_first_name" => $ShipArr['reciever_name'],
                "cust_last_name" => " ",
                "cust_mobil" => $number,
                "vendor_id" => $vendor_id,
                "branch_id" => $branch_id,
                "payment_type_id" => 1,
                "cords" => $Longitude.','.$latitude,
                "address" => 'KSA '.$receiver_city,
                "bill_amount" => $cod_amount,
                "preorder" => "false",
                "bill_reference_no " => $ShipArr['slip_no'],
                "pieces" => $pieces,
                "total_weight" => $weight,
                "order_items_detail" => $items_detail,
                "api_key" => $api_key,
        );
       // echo "<pre>"; print_r($request_data); exit; 
        $dataJson = json_encode($request_data);
        $headers = array (
            "Content-Type: application/json"
        );
        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['description'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres =="Done") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse, $successstatus, $ShipArr['slip_no'],$super_id, $dataJson );
        return $response_array;
    }
    public function AymakanArray(array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $super_id = null) {
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'aymakan',$ShipArr['super_id']);

       
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'aymakan',$ShipArr['super_id']); 
         $store = getallsellerdatabyID($ShipArr['cust_id'], 'company');
        
        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));

        $API_URL = $counrierArr['api_url']; 
        $api_key = $counrierArr['auth_token'];
         $currency = "SAR";

        $weight = $ShipArr['weight'];

        if ($ShipArr['mode'] == 'COD') {
            $price_set = 113;
            $is_cod = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['mode'] == 'CC') {
            $is_cod = 0;
            $price_set = 364;
            $cod_amount = 0;
        }

        //echo "<pre>";
        $all_param_data = array(
           "requested_by" => $ShipArr['sender_name'],
           "fulfilment_customer_name" => $store,
            "declared_value" => $ShipArr['total_cod_amt'],
            "declared_value_currency" => $currency,
            "price_set" => $price_set,
            "reference" => $ShipArr['slip_no'],
            "is_cod" => $is_cod,
            "cod_amount" => $cod_amount,
            "currency" => $currency,
            "delivery_name" => $ShipArr['reciever_name'],
            "delivery_email" => $ShipArr['reciever_email'],
            "delivery_city" => $receiver_city,
            "delivery_address" => $ShipArr['reciever_address'],
            "delivery_country" => 'SA',
            "delivery_phone" => $ShipArr['reciever_phone'],
            "delivery_description" => $item_description,
            "collection_name" => $ShipArr['sender_name'],
            "collection_address" => $ShipArr['sender_address'],
            "collection_email" => empty($ShipArr['sender_email'])?'Support@diggipacks.com':$ShipArr['sender_email'],
            "collection_city" => $sender_city,
            "collection_postcode" => $s_zip,
            "collection_country" => 'SA',
            "collection_phone" => $ShipArr['sender_phone'],
            "pickup_date" => $pickup_date,
            "weight" => $ShipArr['weight'],
            "pieces" =>$ShipArr['pieces']
        );
    //    print_r($all_param_data);
    //     exit;
        $json_final_date = json_encode($all_param_data);
    // print_r($json_final_date);exit;
        $headers = array(
            "Accept:application/json",
            "Authorization:". $api_key);
        
        if(empty($receiver_city))
        {
            $resp = array(
                'errors' => array(
                    'reference' => array(                       
                                'Receiver city is empty'                       
                    ),
                ),
            );
            $response =   json_encode($resp); 
            return $response; 
        }
        else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $API_URL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
            $response = curl_exec($ch);
            //echo "<br><br><br>";    print_r($response); //exit; 
            curl_close($ch);
            $responseArray = json_decode($response, true);
            $logresponse =   json_encode($response);  
            $successres = $responseArray['errors'];
           // echo "<pre>"; print_r($response)   ;   
            if(empty($successres)) 
            {
                $successstatus  = "Success";
            }else {
                $successstatus  = "Fail";
            }

            $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $all_param_data);
            return $response;
        }
    }

    public function Aymakan_tracking($client_awb= null, $tracking_url= null,$auth_token=null)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => $tracking_url.$client_awb,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: '.$auth_token
              ),
            ));

            $response = curl_exec($curl);
             
            curl_close($curl);
            return $response;
    }

    public function ShipsyArray(array $ShipArr, array $counrierArr, $box_pieces = null, $c_id = null, $super_id = null) {
        //print_r($ShipArr);exit;
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'shipsy_city');
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsy_city');
            if ($ShipArr['mode'] == 'COD') {
                    $total_cod_amt = $ShipArr['total_cod_amt'];
                } elseif ($ShipArr['mode'] == "CC") {
                    $total_cod_amt = 0;
                }
				if($box_pieces==0){
					$box_pieces = $ShipArr['pieces'];
				}else{
					$box_pieces = $box_pieces;
				}
                $consignments[] = Array
                                (
                                    //[0] => Array
                                        //(
                                            "customer_code" => "FASTCOO",
                                            "reference_number" => '',
                                            "service_type_id" => "PREMIUM",
                                            "load_type" => "NON-DOCUMENT",
                                            "description" => "",
                                            "inco_terms" => "",
                                            "shipment_purpose" => "",
                                            "product_code" => "",
                                            "cod_favor_of" => "",
                                            "cod_collection_mode" => "",
                                            "dimension_unit" => "",
                                            "length" => "",
                                            "width" => "",
                                            "height" => "",
                                            "weight_unit" => "kg",
                                            "weight" => $ShipArr['weight'],
                                            "declared_value" =>"", 
                                            "cod_amount" => $total_cod_amt,
                                            "num_pieces" => $box_pieces,
                                            "customer_reference_number" => $ShipArr['slip_no'],
                                            "is_risk_surcharge_applicable" => 1,
                                            "origin_details" => Array
                                                (
                                                    "name" => $ShipArr['sender_name'],
                                                    "phone" => $ShipArr['sender_phone'],
                                                    "alternate_phone" => '',
                                                    "address_line_1" => $ShipArr['sender_address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $sender_city,
                                                    "state" => '',
                                                    "email" => $ShipArr['sender_email'],
													
                                                ),

                                            "destination_details" => Array
                                                (
                                                    "name" => $ShipArr['reciever_name'],
                                                    "phone" => $ShipArr['reciever_phone'],
                                                    "alternate_phone" => "",
                                                    "address_line_1" => $ShipArr['reciever_address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $receiver_city,
                                                    "state" => '',
                                                    "email" => $ShipArr['reciever_email'],
                                                ),

                                            "pieces_detail" => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            "description" => $ShipArr['sku'],
                                                            "declared_value" => $total_cod_amt,
                                                            "weight" => $ShipArr['volumetric_weight'],
                                                            "height" => '',
                                                            "length" =>'',
                                                            "width" => ''
                                                        )

                                                )

                                        //)

                                );
        $all_param_array = Array(
                            "is_international" => '',
                            "consignments" => $consignments

                        );
        $param = json_encode($all_param_array);
        //echo $param;exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'api-key:'.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
         $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response_array['data'][0]['success'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres==1) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $param);
        return $response;
        //exit;
    }
    
    public function ShipsyLabelcURL(array $counrierArr, $client_awb = null) {
        $url = str_replace('softdata', 'shippinglabel/link?reference_number=', $counrierArr['api_url']);
        $url = $url.$client_awb;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'api-key:'.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response, true);
        
        $labelURL = $response['data']['url'];
        $labelURL = str_replace('isSmall=false', 'isSmall=true', $labelURL);
        
        return $labelURL;
        
    }
    
    public function ShipadeliveryArray(array $ShipArr, array $counrierArr, $auth_token = null, $c_id = null) {
        
        ini_set('default_charset', 'UTF-8');
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'shipsa_city');
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsa_city');

       // echo  "<br/>receiver_city = ".$receiver_city;
               
        
        if ($ShipArr['mode'] == 'COD') {
            $total_cod_amt = $ShipArr['total_cod_amt'];
            $paymentMethod = 'CashOnDelivery';
        }elseif ($ShipArr['mode'] == "CC") {
            $total_cod_amt = 0;
            $paymentMethod = 'Prepaid';
        }
        $description =  $ShipArr['status_describtion'];

        if($description==''){
            $description = 'GOODS';
        }
        
        $number  =  $ShipArr['reciever_phone']; 
        $number = ltrim($number, '966 ');
        $number = ltrim($number, '0');
        $number = '0' . $number;
        $number = str_replace(' ', '', $number);
        
        $Sender = array(
            'name' => $ShipArr['sender_name'],
            'address' => $ShipArr['sender_address'],
            'phone' => $ShipArr['sender_phone'],
            'email' => $ShipArr['sender_email'],
        );
        $Recipient = array(
            'name' => $ShipArr['reciever_name'],
            'address' => $ShipArr['reciever_address'],
            'phone' => $number,
            'email' => $ShipArr['reciever_email'],
            'city' => $receiver_city,
        );
        $param[] = array(
            'id' => $ShipArr['slip_no'],
            'amount' => (float)$total_cod_amt,
            'paymentMethod' => $paymentMethod,
            'orderCategory' => 'NEXTDAY',
            'description' => $description,
            'typeDelivery' => 'forward',
            'sender' => $Sender,
            'recipient' => $Recipient
        );
        
        $paramArray = json_encode($param);

       //echo "<br><pre> city = "; print_r($param); 
        //die; 
        if (empty($param[0]['recipient']['city']))
        {
            //echo "<br><pre> Response if = "; print_r($response);  die; 
            $response = $this->shipmentLog($c_id,'receiver city empty ','Fail', $ShipArr['slip_no'],$super_id,'');
            return $response;
        }
        else {
             //echo "<br><pre> Response else = "; die; 
              $curl = curl_init();        
                  curl_setopt_array($curl, array(
                  CURLOPT_URL => $counrierArr['api_url']."?apikey=".$counrierArr['auth_token'],
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$paramArray,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'x-api-key:'.$counrierArr['auth_token'],
                    'Accept: application/json'
                  ),
                ));

                $response = curl_exec($curl);
                 curl_close($curl);
                 $logresponse =   json_encode($response);  
                 // echo "<pre>"; print_r($response);   // die;
                 $response_array = json_decode($response, true);
                 $successres = $response_array[0]['code'];
                    if($successres==0) 
                        {
                           $successstatus  = "Success";
                        }
                    else 
                        {
                            $successstatus  = "Fail";
                        }

                $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id,$paramArray);
                return $response;
        }


        
      
    }
   
    public function ShipaDelupdatecURL(array $counrierArr,array $ShipArr,$client_awb = null) {
                    

        if ($ShipArr['mode'] == 'COD') {
           $total_cod_amt = $ShipArr['total_cod_amt'];
           $paymentMethod = 'CashOnDelivery';
       }
       elseif ($ShipArr['mode'] == "CC") {
           $total_cod_amt = 0;
           $paymentMethod = 'Prepaid';
       }
      // $client_awb = "SD001351088";
       $valpiecesarray =  array (
             'ready' => true,
             'weight' => (float)$ShipArr['weight'],
             'quantity' => 1                     
           );

      // echo "<br/> <pre>"; print_r($valpiecesarray);  exit;

       $valpieces =  json_encode($valpiecesarray);
     // $client_awb = "SD001351088"; 
           
            $curl = curl_init();
            $client_awb = trim($client_awb); 

           curl_setopt_array($curl, array(
             CURLOPT_URL => $counrierArr['api_url'].'/'.$client_awb."?apikey=".$counrierArr['auth_token'],
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'PATCH',
             CURLOPT_POSTFIELDS => $valpieces,
             CURLOPT_HTTPHEADER => array(
               'Accept: application/json',
               'Content-Type: application/json',
               'x-api-key:'. $counrierArr['auth_token']
             ),
           ));

           $responsepieces = curl_exec($curl);
               curl_close($curl);
          //  $responsepie = json_encode($responsepieces);
          // echo "responsepieces = "; $responsepieces;  die; 
          return  $responsepieces;                         
         


    }

public function ShipaDelLabelcURL(array $counrierArr, $client_awb = null) 
{

        $cURL12 = $counrierArr['api_url'].'/'.$client_awb."/pdf?apikey=".$counrierArr['auth_token']."&template=sticker-6x4&copies=1";

        // https://sandbox-api.shipadelivery.com/orders/{orderId}/pdf
        // $cURL1 = str_replace("?apikey=", "/$client_awb/pdf?apikey=", $cURL12);        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $cURL12,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
}
    
    public function SPArray(array $ShipArr, array $counrierArr, $complete_sku = null, $c_id = null, $super_id = null){
        
        $username = $counrierArr['user_name'];
        $password = $counrierArr['password'];
        $authdata = 'grant_type=password&UserName='.$username.'&password='.$password;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://updsstg.sp.com.sa/csapi/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $authdata,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $token = json_decode($response, true);
        
        
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'saudipost_id');
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'saudipost_id');
        
        if ($ShipArr['mode'] == "COD") {
            $PaymentType = 2;
            $total_cod_amt = $ShipArr['total_cod_amt'];
        }else
        {
            $PaymentType = 1;
            $total_cod_amt = 0;
        }

        $param = array(
            "CRMAccountId" => $counrierArr['courier_account_no'],
            "BranchId"=> 0,
            "PickupType"=> 1,
            "RequestTypeId"=> 1,
            "CustomerName"=> $ShipArr['reciever_name'],
            "CustomerMobileNumber"=> $ShipArr['reciever_phone'],
            "SenderName"=> $ShipArr['sender_name'],
            "SenderMobileNumber"=> $ShipArr['sender_phone'],
            "Items"=> array(
                array(
                    "ReferenceId"=> $ShipArr['slip_no'],
                    "Barcode"=> null,
                    "PaymentType"=> $PaymentType,
                    "ContentPrice"=> 0,
                    "ContentDescription"=> "Goods",
                    "Weight"=> $ShipArr['weight'],
                    "BoxLength"=> 0,
                    "BoxWidth"=> 0,
                    "BoxHeight"=> 0,
                    "ContentPriceVAT"=> 0,
                    "DeliveryCost"=> 0,
                    "DeliveryCostVAT"=> 0,
                    "TotalAmount"=> $total_cod_amt,
                    "CustomerVAT"=> 0,
                    "SaudiPostVAT"=> 0,

                    "SenderAddressDetail"=> array(
                        "AddressTypeID"=> "6",
                        "AddressLine1"=> $ShipArr['sender_address'],
                        "AddressLine2"=> "SP",
                        "LocationID"=> $sender_city
                    ),
                    "ReceiverAddressDetail"=> array(
                        "AddressTypeID"=> "6",
                        "AddressLine1"=> $ShipArr['reciever_address'],
                        "AddressLine2"=> "SP",
                        "LocationID"=> $receiver_city
                    )
                )
            )
        );

        $param = json_encode($param);
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer '.$token['access_token'],
            'Content-Type: application/json',
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response['Items'][0]['Message'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres=='Success') 
        {
            $successstatus  = "Success";
        } else {
            $successstatus  = "Fail";
        }

           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $param);
        return $response;
    }


 	public function PrintLabel($SMSAAWB, $Passkey, $url) {
        $xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <getPDF xmlns="http://track.smsaexpress.com/secom/">
                    <awbNo>' . $SMSAAWB . '</awbNo>
                    <passKey>' . $Passkey . '</passKey>
                </getPDF>
            </soap:Body>
        </soap:Envelope>';
        $headers = array(
            "Content-type: text/xml;charset=utf-8",
            "Accept: application/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://track.smsaexpress.com/secom/getPDF",
            "Content-length: " . strlen($xml),
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = trim(curl_exec($ch));
        return $response;
    }

 public function shipmentLog($c_id = null,$description= null,$status= null,$slip_no= null,$super_id= null ,$requestData = null){
        
        $CURRENT_DATE = date("Y-m-d H:i:s");
        $logarr  = array(
            'slip_no' => $slip_no, 
            'cc_id' => $c_id, 
            'log' => $description,
            'request'=>$requestData, 
            'status' =>$status, 
            'super_id' => $super_id, 
            'entry_date' =>$CURRENT_DATE, 
        );       

        $retr = $this->GetlogInsert($logarr); 

    }
     public function GetlogInsert($data = array()) {

            $this->db->insert('frwd_shipment_log', $data);
            //echo $this->db->last_query(); die;
        }




public function Ejack($ShipArr, $counrierArr, $complete_sku, $c_id,$super_id = null) 
{
    $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
    $password = $counrierArr['password'];
    $url = $counrierArr['api_url'];
           
    if(empty($box_pieces1))
    {
        $box_pieces = 1;
    }
    else
    { 
         $box_pieces = $box_pieces1 ; 
    }

    if($ShipArr['weight']==0)
    {  
        $weight= 1;
    }
    else { 
        $weight = $ShipArr['weight'] ; 
    }
   
    $Receiver_name = $ShipArr['reciever_name'];
    $Receiver_email = $ShipArr['reciever_email'];
    $Receiver_phone = $ShipArr['reciever_phone'];
    $Receiver_address = $ShipArr['reciever_address'];
    if (empty($Receiver_address)) {
        $Receiver_address = 'N/A';
    }

    $Reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city');
    
    $product_type = 'Parcel'; //beone ka database
    $service = '2'; // beone wali
    $description = $ShipArr['status_describtion'];
    if (empty($description)) {
        $description = 'N/A';
    }

    // this is prodect name (column name: status_describtion

    $ajoul_booking_id = $ShipArr['booking_id'];
    $s_name = $ShipArr['sender_name'];
    $s_address = $ShipArr['sender_address'];
    $s_zip = $ShipArr['sender_zip'];
    $s_phone = $ShipArr['sender_phone'];
    $s_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city');

    $pay_mode = $ShipArr['mode']; //paymode either CASH or COD:(column name: mode || date
    $codValue = $ShipArr['total_cod_amt']; //COD charges.  :  (column name:     total_cod_amt || date type:
    $product_price = $ShipArr['declared_charge']; //(column name: declared_charge || date type: int || value: 11)
    $booking_id = $ShipArr['slip_no']; // send awb number ajoul
    $shipper_refer_number = $ShipArr['booking_id']; // ajoul ki booking id
    $weight = $weight;
   

    //weight should be in kg.:(column name: weight || date type
    $NumberOfParcel =  $ShipArr['pieces']; //(column name: pieces || date type: int || value: 5)

    $data_string = "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

    $response = curl_exec($ch);

    curl_close($ch);
    $logresponse =   json_encode($response);  
    $successres = $response['error'];
 
    if($successres == '') 
    {
        $successstatus  = "Success";
    }else {
        $successstatus  = "Fail";
    }

    $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $super_id, $data_string);
    
    return $response;
}

public function fastcooArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$super_id = null) {

    $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city');
    $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city');
    $entry_date = date('Y-m-d H:i:s');
    $pickup_date = date("Y-m-d", strtotime($entry_date));

    $url =  $counrierArr['api_url'];
    $secKey = $counrierArr['auth_token'];
    $customerId =$counrierArr['courier_account_no'];
    $formate    = "json";
    $method     = "createOrder";
    $signMethod = "md5";


    if ($ShipArr['mode'] == 'COD') {
        $cod_amount = $ShipArr['total_cod_amt'];

    } elseif ($ShipArr['mode'] == 'CC') {         
        $cod_amount = 0;
    }
    if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight']==0)
        {  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }

    if (empty($receiver_city)){
        $resp = array('msg' => 'receiver city empty');
        $response = json_encode($resp);
        return $response ;
    }
    else {
        $skudetails = array(array(
            "piece"=>   $ShipArr['pieces'],
            "weight"=> $weight,
            "BookingMode"=> $ShipArr['mode'],
            ));
        $alldata = array(
                "customerId" => $customerId ,
                "secret_key" => $secKey, 
                "BookingMode" => $ShipArr['mode'],
                "codValue" => $cod_amount,
                "reference_id" => $ShipArr['slip_no'],
                "origin" => $sender_city,
                "destination" => $receiver_city,
                "service" => 3,
                "sender_name" =>  $ShipArr['sender_name'],
                "sender_address" => $ShipArr['sender_address'],
                "sender_phone" =>  $ShipArr['sender_phone'],
                "sender_email" => $ShipArr['sender_email'],
                "receiver_name" => $ShipArr['reciever_name'],
                "receiver_address" => $ShipArr['reciever_address'],
                "receiver_phone" => $ShipArr['reciever_phone'],
                "receiver_email" =>  $ShipArr['reciever_email'],
                "description" => $complete_sku,
                "pieces"=>   $ShipArr['pieces'],
                "weight"=> $weight,
                "skudetails" => $skudetails,                    
            );
          
            $sign = create_sign($alldata, $secKey, $customerId, $formate, $method, $signMethod);
            $data_array = array(
              "sign"       => $sign,
              "format"     => $formate,
              "signMethod" => $signMethod,
              "param"      => $alldata,
              "method"     => $method,
              "customerId" => $customerId,
             );
     
        $dataJson = json_encode($data_array);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json"
        ));
        $response = curl_exec($ch);

        curl_close($ch);
   
        return $response;
    }
    }
    public function Moments_auth($counrierArr=null){
      
        $param= array(  
                        "client_secret"=>$counrierArr['password'],
                        "client_id"=> $counrierArr['courier_account_no'],
                        "username"=>$counrierArr['user_name'],
                        "password"=>$counrierArr['password'] 
                    );
        $dataJson =json_encode($param);
      
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $counrierArr['api_url']."authorize",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$dataJson,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));

            $Auth_response = curl_exec($curl);
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
            //print_r($responseArray);die;
    
            $Auth_token = $responseArray['access_token'];
            //print_r($Auth_token);die;
             return $Auth_token;
    
    }
     public function MomentsArray(array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,$complete_sku=null,$super_id) 
    {
            $sender_default_city = Getselletdetails_new($super_id);
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_address = $sender_default_city['0']['address'];
            $sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city_code', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'momentsKsa_city',$super_id);
            $API_URL = $counrierArr['api_url'] . "shipment/create";
            $currency = "SAR";
                
                if (empty($box_pieces1)) {
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if ($ShipArr['weight'] == 0) {
                $weight = 1;
                } else {
                $weight = $ShipArr['weight'];
                }
                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }
                

    
            $sender_data = array(
                        "name"=>"DIGGIPACKS FULFILLMENT - ".$sellername,
                        "country_code"=> "SA",
                        "city_code"=> $sender_city,
                        "address"=>$sender_address,
                        "phone"=>$senderphone,
                        "email"=> $senderemail
                    );
                  
    
                $receiver_data = array(
                        "name"=>$ShipArr['reciever_name'],
                        "country_code"=> "SA",
                        "city_code"=> $receiver_city,
                        "address"=>  $ShipArr['reciever_address'],
                        "zip_code"=>$ShipArr['reciever_zip'],
                        "phone"=> $ShipArr['reciever_phone'],
                        "email"=>$ShipArr['reciever_email']);
    
    
           
            $details = array(
                'receiver' => $receiver_data,
                'sender' => $sender_data,
                "reference"=>  $ShipArr['slip_no'],
                "pick_date"=> "",
                "pickup_time"=> "",
                "product_type"=> "104",
                "payment_mode"=> $ShipArr['mode'],
                "parcel_quantity"=> $box_pieces,
                "parcel_weight"=> $weight,
                "service_id"=> "1",
                "description"=> $complete_sku,
                "sku"=> $complete_sku,
                "weight_total"=> $weight,
                "total_cod_amount"=> $cod_amount
            );

          $json_final_date = json_encode($details);
             //print_r($json_final_date);  die;
               $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $API_URL ,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$json_final_date,
                  CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer ' .$Auth_token,
                    'Content-Type: application/json'
                  ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            
            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;
            $logresponse =   json_encode($response);  
            $successres = $responseArray['errors'];
            
            if (empty($successres)) 
                {
                        $successstatus = "Success";
                } else {
                        $successstatus = "Fail";
                }
                $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id, $json_final_date);
                return $responseArray;
    }
     public function FedEX(array $ShipArr, array $counrierArr, $complete_sku = null,$box_pieces1 = null,$c_id=null,$super_id=null){
        
            $sender_default_city = Getselletdetails_new($super_id);
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            $sender_address = $sender_default_city['0']['address'];
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_city_code = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'fedex_city_code', $super_id);
            $sendercity = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'fedex_city', $super_id);
            $receiver_city_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'fedex_city_code',$super_id);
            $receivercity = getdestinationfieldshow_auto_array($ShipArr['destination'], 'fedex_city',$super_id);
            $currency = site_configTable("default_currency");//"EGP";  
            $sender_country_code = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'country_code', $super_id);
            
            $api_url = ($counrierArr['api_url'])."CreateAirwayBill";
           
            if (empty($box_pieces1)) {
                $box_pieces = 1;
            } else {
                $box_pieces = $box_pieces1;
            }
            
            if ($ShipArr['weight'] == 0) {
                $weight = 1;
            } else {
                $weight = $ShipArr['weight'];
            }
            
            if ($ShipArr['mode'] == 'COD') {
                $cod_amount = $ShipArr['total_cod_amt'];
            } elseif ($ShipArr['mode'] == 'CC') {
                $cod_amount = 0;
            }
            
                $details= array(
                "UserName"=>$counrierArr['user_name'],
                "Password"=> $counrierArr['password'],
                "AccountNo"=> $counrierArr['courier_account_no'],
                "AirwayBillData"=> array(
                "AirWayBillCreatedBy"=>$sellername,
                "CODAmount" =>$cod_amount ,
                "CODCurrency"=>$currency,
                "Destination"=>$receiver_city_code,
                "DutyConsigneePay" =>0,
                "GoodsDescription"=>$complete_sku,
                "NumberofPeices" =>$box_pieces1,
                "Origin"=>$sender_city_code,
                "ProductType"=>"FRE",
                "ReceiversAddress1"=>$ShipArr['reciever_address'],
                "ReceiversAddress2"=>$ShipArr['reciever_address'],
                "ReceiversCity"=>$receivercity,
                "ReceiversCompany"=>$ShipArr['reciever_name'],
                "ReceiversContactPerson"=>$ShipArr['reciever_name'],
                "ReceiversCountry"=>'Egypt',
                "ReceiversEmail"=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                "ReceiversGeoLocation"=>"",
                "ReceiversMobile"=>$ShipArr['reciever_phone'],
                "ReceiversPhone"=>$ShipArr['reciever_phone'],
                "ReceiversPinCode"=>"",
                "ReceiversProvince"=>"",
                "ReceiversSubCity"=>"",
                "SendersAddress1"=>$sender_address,
                "SendersAddress2"=>$sender_address,
                "SendersCity"=>$sendercity,
                "SendersCompany"=>"DIGGIPACKS FULFILLMENT - ".$sellername,
                "SendersContactPerson"=>"DIGGIPACKS FULFILLMENT - ".$sellername,
                "SendersCountry"=>'Egypt',
                "SendersEmail"=>$senderemail,
                "SendersGeoLocation"=>"",
                "SendersMobile"=>$senderphone,
                "SendersPhone"=>$senderphone,
                "SendersPinCode"=>"",
                "SendersSubCity"=>$sender_country_code,
                "ServiceType"=>"FRG",
                "ShipmentDimension"=>"",
                "ShipmentInvoiceCurrency"=>$currency,
                "ShipmentInvoiceValue" =>0,
                "ShipperReference"=>$ShipArr['slip_no'],
                "ShipperVatAccount"=>"",
                "SpecialInstruction"=>"",
                "Weight" =>$weight
                ));
               $details_encode= json_encode($details);
               //print_r($details);die;
               if(!empty($receiver_city)){
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL =>$api_url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$details_encode,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                  ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
                }
        else 
        {
           
            $response = array('description'=> 'The Receivers City field is required.'); 
            $response = json_encode($response);  
        }      
                  
                
                $responseArray = json_decode($response, true);
                //print_r($responseArray);die;
                $logresponse = json_encode($response);
                $successres = $responseArray['Code'];

                if ($successres == 1) 
                {
                    $successstatus = "Success";
                } else {
                    $successstatus = "Fail";
                }
                $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id,$details_encode);
                return $responseArray;

            
            

            }
            
        public function FedEX_label($client_awb = null,$counrierArr= null,$ShipArr=null){
            
            $api_url = ($counrierArr['api_url'])."AirwayBillPDFFormat";
            
            $details= array(
                
                        "AccountNo"=>$counrierArr['courier_account_no'],
                        "AirwayBillNumber"=> $client_awb,
                        "Country"=>"SA",
                        "Password"=> $counrierArr['password'],
                        "RequestUser"=>$ShipArr['sender_name'],
                        "UserName"=>$counrierArr['user_name']
                
            );
            $label_details = json_encode($details);

                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $api_url,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$label_details,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                  ),
                ));

                $response = curl_exec($curl);
                

                curl_close($curl);
                $response_label = json_decode($response, true);
                return  $response_label;
                        }
    public function Postagexp_auth($counrierArr=null){
      
        $param= array(  
                        "client_secret"=>$counrierArr['password'],
                        "client_id"=> $counrierArr['courier_account_no'],
                        "username"=>$counrierArr['user_name'],
                        "password"=>$counrierArr['password'] 
                    );
        $dataJson =json_encode($param);
      
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $counrierArr['api_url']."authorize",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$dataJson,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));

            $Auth_response = curl_exec($curl);
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
           // print_r($responseArray);die;
    
            $Auth_token = $responseArray['access_token'];
            //print_r($Auth_token);die;
             return $Auth_token;
    
    }
     public function PostagexpArray(array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,$complete_sku=null,$super_id= null) 
    {
            $sender_default_city = Getselletdetails_new($super_id);
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_address = $sender_default_city['0']['address'];
            $sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city_code', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'Postagexp_city',$super_id);
            $API_URL = $counrierArr['api_url'] . "shipment/create";
              
                if (empty($box_pieces1)) {
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if ($ShipArr['weight'] == 0) {
                $weight = 1;
                } else {
                $weight = $ShipArr['weight'];
                }


                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

                if(empty($complete_sku)){
                 $complete_sku = $ShipArr['status_description'];

                }else {
                    $complete_sku =  $complete_sku;
                }
    
            $sender_data = array(
                        "name"=>"DIGGIPACKS FULFILLMENT - ".$sellername,
                        "country_code"=> "SA",
                        "city_code"=> $sender_city,
                        "address"=>  $sender_address,
                        "phone"=> $senderphone,
                        "email"=>  $senderemail
                    );
                  
    
                $receiver_data = array(
                        "name"=>$ShipArr['reciever_name'],
                        "country_code"=> "SA",
                        "city_code"=> $receiver_city,
                        "address"=>  $ShipArr['reciever_address'],
                        "zip_code"=>$ShipArr['reciever_zip'],
                        "phone"=> $ShipArr['reciever_phone'],
                        "email"=>$ShipArr['reciever_email']);
    
    
           
            $details = array(
                'receiver' => $receiver_data,
                'sender' => $sender_data,
                "reference"=>  $ShipArr['slip_no'],
                "pick_date"=> "",
                "pickup_time"=> "",
                "product_type"=> "104",
                "payment_mode"=> $ShipArr['mode'],
                "parcel_quantity"=> $box_pieces,
                "parcel_weight"=> $weight,
                "service_id"=> "2",
                "description"=> $complete_sku,
                "sku"=> $complete_sku,
                "weight_total"=> $weight,
                "total_cod_amount"=> $cod_amount
            );

            $json_final_date = json_encode($details);
            // print_r($json_final_date);  die;
               $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $API_URL ,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$json_final_date,
                  CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer ' .$Auth_token,
                    'Content-Type: application/json'
                  ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
            
            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['errors'];
            //print_r($successres);die;
    
             if (empty($successres)) 
                {
                        $successstatus = "Success";
                } else {
                        $successstatus = "Fail";
                }
                $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $super_id, $json_final_date);
                return $responseArray;
    }
    
    public function ThabitArray(array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null, $super_id) 
    {
      
        $sender_city_safe = getdestinationfieldshow($ShipArr['origin'], 'city');
        $receiver_city_safe = getdestinationfieldshow($ShipArr['destination'], 'safe_arrival');
        

       $API_URL = $counrierArr['api_url'];
        if(empty($box_pieces1))
       { $box_pieces = 1;  }
         else { $box_pieces = $box_pieces1 ; }

        if($ShipArr['weight']==0)
            {  $weight= 1;
            }
            else { $weight = $ShipArr['weight'] ; }

        if($ShipArr['mode'] == "COD"){
            $pay_mode = "cash";
            $paid = 0;
        }
        else {
            $pay_mode = "credit_balance";
            $paid = 1;
        }

        $sender_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['sender_name'],
            "email" => $ShipArr['sender_email'],
            "street" => html_entity_decode($ShipArr['sender_address']),
            "city" => array(
                "code" =>strtolower($sender_city_safe)
            ),
            "country" => array(
                "id" => 191
            ),
           "phone" =>$ShipArr['sender_phone'],
        );
        
        
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => $ShipArr['reciever_email'],
            "street" => html_entity_decode($ShipArr['reciever_address']),
            "city" => array(
                "id" => $receiver_city_safe
            ),
            "country" => array(
                "id" => 191
            ),

            "phone" => $ShipArr['reciever_phone'],
        );
        $dimensions = array(
            "weight" => $weight
        );
        $package_type = array(
            "courier_type" => 'express_delivery'
        );
        $charge_items = array(
            array(
                "paid" => $paid,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode']
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "credit_balance",
            "payer" => "recipient",
            "parcel_value" => $ShipArr['total_cod_amt'],
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
        
        $header = array(
            "Authorization" => "Bearer ".$Auth_token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );

        $dataJson = json_encode($param);
        $response = send_data_to_thabit_curl($dataJson, $Auth_token, $API_URL);
        $safe_response =   json_decode($response, TRUE);
        
        $logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
       
       
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $super_id, $dataJson);

        return $safe_response;
    }

    public function tamexArray(array $ShipArr, array $counrierArr, $complete_sku = null,$c_id=null,$box_pieces1=null,$super_id=null) 
    {

        $sender_default_city = Getselletdetails_new($super_id);
        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        
        
        $sender_address = $sender_default_city['0']['address'];
        
        $sender_city = $this->getdestinationfieldshow_auto_array($ShipArr['origin'], 'tamex_city',$super_id); 

        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'tamex_city',$super_id);

        if(!empty($receiver_city))
        {
            $API_URL = $counrierArr['api_url'].'create';
            if ($ShipArr['pay_mode'] == 'COD') {
                $codValue = $ShipArr['total_cod_amt'];
            } else {
                $codValue = 0;
            }
        

            if(empty($box_pieces1))
            {
                $box_pieces = 1;
            }
            else
            { 
                $box_pieces = $box_pieces1 ; 
            }

            if($ShipArr['weight']==0)
            {  
                $weight= 1;
            }
            else { 
                $weight = $ShipArr['weight'] ; 
            }
        $currency = site_configTable("default_currency");    
        $param = array(
            "apikey" => $counrierArr['auth_token'],          
            "pack_type" => 1,
            "pack_awb" => $ShipArr['slip_no'],
            "pack_vendor_id" => "Diggipacks",
            "pack_reciver_name" => $ShipArr['reciever_name'],
            "pack_reciver_phone" => $ShipArr['reciever_phone'],
            "pack_reciver_country" => "SA",
            "pack_reciver_city" => $receiver_city,
            "pack_reciver_dist" => '',
            "pack_desc" => $complete_sku,
            "pack_num_pcs" => $box_pieces,
            "pack_weight" => $weight,
            "pack_cod_amount" => $codValue,
            "pack_currency_code" => $currency,
            "pack_extra_note" => "OK",
            "pack_live_time" => "4",
            "pack_sender_name" => $sellername,//"Diggipacks",
            "pack_sender_phone" => $ShipArr['reciever_phone'],
            "pack_sender_email" =>$ShipArr['sender_email'],
            "pack_send_country" => "SA",
            "pack_send_city" =>  $sender_city,
            "pack_sender_dist" =>  $sender_city,
            "pack_sender_street" => $sender_address,
            "pack_sender_zipcode" => "",
            "pack_sender_building" => "NA",
            "pack_sender_extra" => "NA",
            "pack_sender_extar_address" => "NA",
            "pack_sender_longitude" => "NA",
            "pack_sender_latitude" => "NA",
            "pack_reciver_email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "pack_reciver_street" => $ShipArr['reciever_address'],
            "pack_reciver_zipcode" => "",
            "pack_reciver_building" => "",
            "pack_reciver_extra" => "NA",
            "pack_reciver_extar_address" => "NA",
            "pack_reciver_longitude" => "",
            "pack_reciver_latitude" => "",
            "pack_dimention" => "",
            "pack_invoice_no" => "",
        );
        $all_param_data = json_encode($param);
        $headers = array(
            "Accept:application/json"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        $response = curl_exec($ch);
        curl_close($ch);
        $responseArray = json_decode($response, true);     
        $logresponse =   json_encode($response);  
    

        if ($responseArray['code'] == 0) {
            $successstatus  = "Success";
        
        } elseif ($response['code'] != 0 || empty($response)) {
            $successstatus  = "Fail";
        }else{
            $successstatus  = "Fail";
        }


        $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id,$all_param_data);
        
        return $responseArray;
        }else{ 
            $responseArray = array('data'=> 'Reciver City Empty', 'code'=>1) ;
            return $responseArray;
        }
    }


    public function AJOUL_AUTH($courierArr = array(),$ShipArr = array(), $c_id= null, $box_pieces1= null, $complete_sku= null, $super_id = null){



        $sender_default_city = Getselletdetails_new($super_id);
        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
        $store_address = $sender_default_city['0']['address'];
        $sender_city_code = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city_code', $super_id);        

        $sender_country_code = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'country_code', $super_id);
        

        $receiver_city_code = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'ajoul_city_code', $super_id);
        $receiver_country_code = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code' , $super_id);
        
        
        
        

        $API_URL = $courierArr['api_url'] . "shipment/create";


        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }

        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        
        if($ShipArr['mode'] == "COD"){
            $cod_amount = $ShipArr['total_cod_amt'];

        }
        elseif ($ShipArr['mode'] == 'CC'){
            $cod_amount = 0;
        }
        
        $date_time = strtotime(date('Y-m-d h:i:s'));                              
       
        $CURRENT_DATE = date('Y-m-d',$date_time);
        $CURRENT_TIME = date('H:i',$date_time);
      
        $details = array(
            'receiver'=>array(
                'name'=>$ShipArr['reciever_name'],
                'country_code'=>$receiver_country_code,
                'city_code'=>$receiver_city_code,
                'address'=>$ShipArr['reciever_address'],
                'zip_code'=>'',
                'phone'=>$ShipArr['reciever_phone'],
                'email'=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
            ),
            'sender'=>array(
                'name'=>$sellername,
                'country_code'=>$sender_country_code,
                'city_code'=>$sender_city_code,
                'address'=>$store_address,
                'zip_code'=>'',
                'phone'=>$senderphone,
                'email'=>$senderemail
            ),
            "reference"=> $ShipArr['slip_no'],
            "pick_date"=>$CURRENT_DATE,//"2018-08-06",
            "pickup_time"=> $CURRENT_TIME,//"12:49",
            "product_type"=> "104",
            "payment_mode"=> $ShipArr['mode'],
            "parcel_quantity"=> $box_pieces,
            "parcel_weight"=> $weight,
            "service_id"=> "1",
            "description"=> $complete_sku,
            "sku"=> $complete_sku,
            "weight_total"=> $weight,
            "total_cod_amount"=> $cod_amount
        );    

        if(empty($receiver_city_code)){
            $responseArray = array('errors'=>'Receiver City Code is empty');
            $this->shipmentLog($c_id, json_encode($responseArray),'Fail', $ShipArr['slip_no'], $super_id,'');
            return $responseArray;
        }

        $json_final_date = json_encode($details);
        //echo $json_final_date;die;

        $url =  "http://fastcoo.net/fastcoo-tech/fs_files/ajoul_api.php";
        $token_api_url = $courierArr['api_url']."authorize?client_secret=".$courierArr['courier_pin_no']."&client_id=".$courierArr['courier_account_no']."&username=".$courierArr['user_name']."&password=".$courierArr['password'];
        $Allarra = array('token_api_url' => $token_api_url,'action'=>'get_token','ship_url'=>$API_URL,'data'=>$json_final_date);

        

        $dataJson = json_encode($Allarra);
        
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
        $responseArray = json_decode($response,true);
        if (isset($responseArray['Shipment']) && !empty($responseArray['Shipment'])) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }
        $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id, $json_final_date);
        return $responseArray;
    }


    public function ShipsyDataArray($ShipArr =array(), $counrierArr =array(), $c_id=null, $box_pieces1=null, $complete_sku=null, $super_id=null){

        $api_url = $counrierArr['api_url']."softdata";
         
        $xapi = $counrierArr['auth_token'];
        
        $customer_code = $counrierArr['courier_pin_no'];
        
        $sender_default_city = Getselletdetails_new($super_id);
        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
        $store_address = $sender_default_city['0']['address'];
        $sender_city = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);        

        
        switch($counrierArr['company']){
            case 'FLOW' :       
                $city_column = 'flow_city'; 
                $service_type_id = 'DELIVERY';
                break;
            case 'Mahmool' :    
                $city_column = 'mahmool_city'; 
                $service_type_id = 'Dry';
                break;
            case 'Flow (Installation)' :       
                $city_column = 'flow_city'; 
                $service_type_id = 'DELIVERY&ASSEMBLY';
                break;
            default:            
                $city_column = '';       
                $service_type_id = '';         
                break;
        }
        

        $receiver_city = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], $city_column, $super_id);
       

       if (empty($box_pieces1)) {
           $box_pieces = 1;
       } else {
           $box_pieces = $box_pieces1;
       }

       if ($ShipArr['weight'] == 0) {
           $weight = 1;
       } else {
           $weight = $ShipArr['weight'];
       }

       $pay_mode=$ShipArr['mode'];
       if($ShipArr['mode'] == "COD"){
           $cod_amount = $ShipArr['total_cod_amt'];
           $pay_mode = "cash";
       }
       elseif ($ShipArr['mode'] == 'CC'){
           $cod_amount = 0;
           $pay_mode = "cc";
       }
       
       if(empty( $receiver_city)){
           $logresponse = "Receiver city empty";
           $successstatus  = "Fail";
           $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$super_id,'');
           return array('data'=>array(array('message'=>'receiver city empty')));
       }
       $data_array = array(
           'consignments'=>array(
               array(
                   "customer_code"=> $customer_code,
                   "customer_reference_number"=> $ShipArr['slip_no'],
                   "service_type_id" => $service_type_id,//"DELIVERY",
                   "load_type" => "NON-DOCUMENT",
                   "description" => $complete_sku,
                   "cod_favor_of"=> "",
                   "cod_collection_mode"=> $pay_mode,
                   "dimension_unit"=>"",
                   "length"=> "",
                   "width"=>"",
                   "height"=> "",
                   "weight_unit"=> "kg",
                   "weight"=> $weight,
                   "declared_value"=> $cod_amount,
                   "cod_amount"=>$cod_amount,
                   "num_pieces"=>$box_pieces,
                   "is_risk_surcharge_applicable"=>"",
                   "origin_details"=>array(

                       "name" => $sellername,
                       "phone"=> $senderphone,
                       "alternate_phone"=> "",
                       "address_line_1"=> $store_address,
                       "address_line_2"=> "",
                       "pincode"=> $sender_city,
                       "city"=> $sender_city,
                       "state"=> ""
                   ),
                   "destination_details"=>array(
                       "name" => $ShipArr['reciever_name'],
                       "phone"=> $ShipArr['reciever_phone'],
                       "alternate_phone" => "",
                       "address_line_1" => $ShipArr['reciever_address'],
                       "address_line_2" => "",
                       "pincode" => $receiver_city,
                       "city" => $receiver_city,
                       "state" => ""
                   ),
                   "return_details"=>array(
                       "name" => null,
                       "phone"=> null,
                       "alternate_phone"=> null,
                       "address_line_1"=> null,
                       "address_line_2"=> null,
                       "pincode"=> null,
                       "city"=> null,
                       "state" => null
                   ),
                   "exceptional_return_details"=>array(
                       "name" => null,
                       "phone" => null,
                       "alternate_phone" => null,
                       "address_line_1" => null,
                       "address_line_2" => null,
                       "pincode" => null,
                       "city" => null,
                       "state" => null
                   ),
                   "type_of_delivery"=>null,
                   "pieces_detail"=>array(
                       array(
                           "description"=> $complete_sku,
                           "declared_value"=> $cod_amount,
                           "weight"=>$weight,
                           "height"=> "",
                           "length"=> "",
                           "width"=> "",
                           "quantity"=> $box_pieces,
                           "product_code"=> "",
                           "volume"=>"",
                           "volume_unit"=> "",
                           "dimension_unit"=>"",
                           "weight_unit"=> "kg"                            
                       )
                   ),
                   "eway_bill"=>null,
                   "invoice_number" => null,
                   "invoice_date" => null,
                   "commodity_name" => null,
                   "consignment_type" => null,
                   "rescheduled_date" => ""
               )
           )
       );

       $json_string= json_encode($data_array);

       $curl = curl_init();

       curl_setopt_array($curl, array(
       CURLOPT_URL => $api_url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS =>$json_string,
       CURLOPT_HTTPHEADER => array(
           'api-key: '.$xapi,
           'Content-Type: application/json'
         ),
       ));
       
       $response = curl_exec($curl);
       curl_close($curl);
       //echo $response;die;
       $response_array = json_decode($response,true);
       if($response_array['data'][0]['success'] == true){
           $successstatus  = "Success";
       }else{
           $successstatus  = "Fail";
       }


       
       $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id, $json_string);
       return $response_array;

    }

    public function ShipsyLabel($counrierArr= array(), $client_awb =null){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $counrierArr['api_url'].'shippinglabel/stream?reference_number='.$client_awb,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'api-key: '.$counrierArr['auth_token'],
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function UPSLabel($client_awb, $courierArr = array()){
        $api_url = $courierArr['api_url']."/labels";

        $data_array = array(
            "LabelRecoveryRequest"=>array(
                "LabelSpecification"=>array(
                    "HTTPUserAgent"=>"",
                    "LabelImageFormat"=>array(
                        "Code"=>"PDF"
                    )
                ),
                "Translate"=>array(
                    "LanguageCode"=>"eng",
                    "DialectCode"=>"US",
                    "Code"=>"01"
                ),
                "LabelDelivery"=>array(
                    "LabelLinkIndicator"=>"",
                    "ResendEMailIndicator"=>"",
                    "EMailMessage"=>array(
                        "EMailAddress"=>""
                    )
                ),
                "TrackingNumber"=>$client_awb
            )

        );
        $username = $courierArr['user_name'];
        $password = $courierArr['password'];
        $AccessLicenseNumber = $courierArr['courier_account_no'];
        $transactionSrc = $courierArr['courier_pin_no'];
        

        $data_json = json_encode($data_array);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$data_json,
        CURLOPT_HTTPHEADER => array(
            'AccessLicenseNumber: '.$AccessLicenseNumber,
            'Password: '.$password,
            'transactionSrc: '.$transactionSrc,
            'Username: '.$username,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response_array =  json_decode($response, TRUE); 
        return $response_array;

    }
    public function UPSArray(array $ShipArr, array $counrierArr, $c_id = null, $box_pieces1 = null,  $complete_sku = null, $super_id = null){


        //print "<pre>"; print_r($counrierArr);die;
        $api_url = $counrierArr['api_url'];
        $username = $counrierArr['user_name'];
        $password = $counrierArr['password'];
        $AccessLicenseNumber = $counrierArr['courier_account_no'];
        $transactionSrc = $counrierArr['courier_pin_no'];
        //$transId = $counrierArr['auth_token'];
        $account_number = $counrierArr['account_entity_code'];
        $service_code= $counrierArr['service_code'];
        //print "<pre>"; print_r($counrierArr);die;


        



        $sender_default_city = Getselletdetails_new($super_id);
        $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
        $store_address = $sender_default_city['0']['address'];
        $sender_city = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);

        
        
        
        $sender_country_code = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'country_code', $super_id);
        
        
        $receiver_city= $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'ups_city',$super_id);
        
        
        $receiver_country_code = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code',$super_id);

        $currency = $this->getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency',$super_id); //"USD";


        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }

        if ($ShipArr['weight'] == 0) {
            $weight = 1;
        } else {
            $weight = $ShipArr['weight'];
        }

        $pay_mode=$ShipArr['mode'];
        $total_amount_to_pay ='';
        if($ShipArr['mode'] == "COD"){
            $cod_amount = $ShipArr['total_cod_amt'];
            $total_amount_to_pay = "COD ".$currency." ".$cod_amount." ".$currency;

        }
        elseif ($ShipArr['mode'] == 'CC'){
            $cod_amount = 0;
            $total_amount_to_pay = "CC ".$currency." ".$cod_amount." ".$currency;
        }

        if(empty($complete_sku))  {
            $complete_sku = 'Goods';
        }

        $post_array = array(
            "ShipmentRequest"=>array(
                "Shipment"=>array(
                    "Description"=>$complete_sku,
                    "Ref1"=>$pay_mode,
                    "Ref2"=>$cod_amount,
                    "ReferenceNumber"=>array(
                        array(
                            "Value"=>$ShipArr['slip_no'],
                            "Value"=>$total_amount_to_pay,
                        )
                    ),
                    // "ReferenceNumber"=>array(
                    //     "BarCodeIndicator"=>$ShipArr['slip_no'],
                    //     "Code"=>"",
                    //     "Value"=>$ShipArr['slip_no']
                    // ),
                    "NumOfPiecesInShipment"=>$box_pieces,
                    "Shipper"=>array(
                        "Name"=>$sellername,
                        "AttentionName"=>$sellername,
                        "CompanyDisplayableName"=>$sellername,
                        "TaxIdentificationNumber"=>"",
                        "Phone"=>array(
                            "Number"=>$senderphone
                        ),
                        "ShipperNumber"=>$account_number,
                        "EMailAddress"=>$senderemail,
                        "Address"=>array(
                            "AddressLine"=>$store_address,
                            "City"=>$sender_city,
                            "StateProvinceCode"=>$sender_country_code,
                            "PostalCode"=>"",
                            "CountryCode"=>$sender_country_code,
                        )
                    ),
                    "ShipTo"=>array(
                        "Name"=>$ShipArr['reciever_name'],
                        "AttentionName"=>$ShipArr['reciever_name'],
                        "Phone"=>array(
                            "Number"=>$ShipArr['reciever_phone']
                        ),
                        "FaxNumber"=>"",
                        "TaxIdentificationNumber"=>"",
                        "Address"=>array(
                            "AddressLine"=>$ShipArr['reciever_address'],
                            "City"=>$receiver_city,
                            "StateProvinceCode"=>$receiver_country_code,
                            "PostalCode"=>"",
                            "CountryCode"=>$receiver_country_code
                        )
                    ),
                    "ShipFrom"=>array(
                        "Name"=>$sellername,
                        "AttentionName"=>$sellername,
                        "Phone"=>array(
                            "Number"=>$senderphone
                        ),
                        "FaxNumber"=>"",
                        "TaxIdentificationNumber"=>"",
                        "Address"=>array(
                            "AddressLine"=>$store_address,
                            "City"=>$sender_city,
                            "StateProvinceCode"=>$sender_country_code,
                            "PostalCode"=>"",
                            "CountryCode"=>$sender_country_code
                        )
                    ),
                    "PaymentInformation"=>array(
                        "ShipmentCharge"=>array(
                            "Type"=>"01",
                            "BillShipper"=>array(
                                "AccountNumber"=>$account_number
                            )
                        )
                    ),
                    "Service"=>array(
                        "Code"=>$service_code,
                        "Description"=>""
                    ),
                    "Package"=>array(
                            "Description"=>$complete_sku,
                            "Packaging"=>array(
                                "Code"=>"02"
                            ),
                            "PackageWeight"=>array(
                                "UnitOfMeasurement"=>array(
                                    "Code"=>"KGS"
                                ),
                                "Weight"=>(string)$weight
                            ),
                            "PackageServiceOptions"=>""
                        ),
                    "ItemizedChargesRequestedIndicator"=>"",
                    "RatingMethodRequestedIndicator"=>"",
                    "TaxInformationIndicator"=>"",
                    "ShipmentRatingOptions"=>array(
                        "NegotiatedRatesIndicator"=>""
                    )
                    

                ),
                "LabelSpecification"=>array(
                    "LabelImageFormat"=>array(
                        "Code"=>"PNG"
                    )
                )
            )
        );

        $json_string = json_encode($post_array);
        //echo $json_string;die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json_string,
        CURLOPT_HTTPHEADER => array(
            'AccessLicenseNumber: '.$AccessLicenseNumber,
            'Password: '.$password,
            //'transId: '.$transId,
            'transactionSrc: '.$transactionSrc,
            'Username: '.$username,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $responseArray = json_decode($response,TRUE);

        curl_close($curl);

        if (isset($responseArray['ShipmentResponse']['Response']['ResponseStatus']) && $responseArray['ShipmentResponse']['Response']['ResponseStatus']['Code'] == 1) 
        {
                $successstatus = "Success";
        } else {
                $successstatus = "Fail";
        }
        $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id, $json_string);
        return $responseArray;
    
    }


    public function shipox_auth(array $counrierArr)
    { 
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

    public function shipoxDataArray(array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $complete_sku=null, $super_id= null) 
    {


                $sender_default_city = Getselletdetails_new($super_id);
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $store_address = $sender_default_city['0']['address'];
                $sender_city = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
                $coutry_code = $this->getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'country_code', $super_id);
                
                
                $API_URL = $counrierArr['api_url'] . "v2/customer/order";
                
                switch($counrierArr['company']){
                    case 'Kudhha': 
                            $city_column = 'kudhha_city';
                        break;
                    default: 
                            $city_column = 'kudhha_city';
                        break;
                }

                
                

                $receiver_city= $this->getdestinationfieldshow_auto_array($ShipArr['destination'], $city_column, $super_id);

                
                $allowed_city = array("Riyadh","RIYADH", "Ad Diriyah", "Diriyah");
                
                if(in_array($receiver_city,$allowed_city)){
                    switch($counrierArr['company']){
                        case 'Kudhha': 
                                $courier_type = 'INSIDE_RIYADH'; 
                            break;
                        default: $courier_type = 'NEXT_DAY_DELIVERY'; 
                            break;
                    }
                }else if($coutry_code != "SA" ){
                    $courier_type = 'INTERNATIONAL_DELIVERY';
                }else{
                    $courier_type = 'DOMESTIC_DELIVERY';                    
                }

                //echo "city=".$courier_type;die;

                
                
                //echo $courier_type;die;
                
                $currency = "SAR";
                
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


                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

               
    
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
            $package_type = array(
                'courier_type' => $courier_type
            );

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
                'parcel_value' => $cod_amount,
                'fragile' => true,
                'note' => $complete_sku,
                'piece_count' => $box_pieces,
                'force_create' => true,
                "reference_id" => $ShipArr['slip_no']
            );

            $json_final_date = json_encode($details);
            //echo $json_final_date;die;
            //  echo "<pre>";  print_r($json_final_date);  die;
              
                     
            if (empty($receiver_city) || empty($coutry_code))
            {
                $successstatus = 'Fail';
                $responseArray = array('message'=> 'Receiver city or country code empty','status'=>$successstatus); 
                $response = json_encode($response);
                $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_date);
                return $responseArray;
            }
            else {
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
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
            //print "<pre>"; print_r($responseArray);die;
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
            if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }
            $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$super_id, $json_final_date);
            return $responseArray;
    }

    public function shipox_label($client_awb = null,$counrierArr= null, $Auth_token=null) 
    {
        
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
                        'Authorization:Bearer ' .$Auth_token),
                ));
    
    
    
            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
    }

}
