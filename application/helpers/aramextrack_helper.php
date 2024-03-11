<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
        
	
		if(!empty($counrierArr_table)){
			
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			$api_url =  $counrierArr_table['api_url']."track/".$client_awb;
            $user_name = $counrierArr_table['user_name'];
            $password = $counrierArr_table['password'];
            $courier_pin_no = $counrierArr_table['courier_pin_no'];
            $AccountNumber = $counrierArr_table['courier_account_no'];

            $sender_country_code = 'SA';  $entity='RUH';
            if($cc_name == 'Aramex International'){
                $sender_country_code = getdestinationfieldshow_auto_array($shipData['origin'], 'aramex_country_code',$shipData['super_id']); 
                if($sender_country_code == 'EG'){
                    $entity='CAI';
                }
            }
            

            $data['ClientInfo']=array(
                'UserName' => $user_name,
                'Password' => $password,
                'Version' => 'v1',
                'AccountNumber' => $AccountNumber,
                'AccountPin' =>$courier_pin_no,
                'AccountEntity' => $entity,
                'AccountCountryCode' => $sender_country_code
            );
			

			$trackResult = getTrackingResponse($slipNo, $client_awb, $api_url, $data ); 
			
			if(!empty($trackResult)){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $allData) {
					
                    $status = $allData['UpdateCode'];                        
                    

					$arrayData = maptrackstatus($status);
                    if (!empty($arrayData) && $allData['UpdateCode']!='SH014') {
                        $date_time = str_replace('/', '', $allData['UpdateDateTime']);
                        $date_time = str_replace('Date(', '', $date_time);
                        $epoch = str_replace(')', '', $date_time);
                        $date_in_formate = strstr($epoch, '+', true);
                        $EPOCH_DATE = (int) $date_in_formate / 1000;
                        $CURRENT_DATE_TIME = date('Y-m-d H:i:s', $EPOCH_DATE);
                        $CURRENT_DATE = date('Y-m-d', $EPOCH_DATE);
                        $CURRENT_TIME = date('H:i:s', $EPOCH_DATE);
                        $details = $allData['UpdateDescription'];
                        $activity = $arrayData['FASTCOO'];
                        $deliverdate ="";

                        $checkStatus = $CI->Shipment_model->checkStatusFM($CURRENT_DATE_TIME, $slipNo, $arrayData['main_d']);
						//print "<pre>"; print_r($checkStatus);die;
						if(empty($checkStatus)){
                            $shipdata = $CI->Shipment_model->getSlipDataForTrack($slipNo);
                            if(!empty($shipdata)){
                                $CI->Shipment_model->updateTrackingStatus($arrayData,$CURRENT_DATE_TIME,$slipNo, $client_awb,$CURRENT_TIME, $details, $activity,$shipData, $cc_name); //Update Close for time being		
                                $updaterecords =  $CI->Shipment_model->update3plAdminReport($CURRENT_DATE_TIME,$arrayData, $slipNo,$cc_name);					
                            }
							
						}
                        
                    }

				}
			}

		}

	}


	function getTrackingResponse($awb=null, $forwarded=null, $api_url=null, $ClientInfo=array() ){

		$params = array(
            'ClientInfo'=>$ClientInfo['ClientInfo'],
            'GetLastTrackingUpdateOnly' => false,
            'Shipments' => array($forwarded),
            'Transaction' =>
            array(
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            )
        );
        $dataJson = json_encode($params);
        
        $headers = array(
            "Content-type:application/json",
            "Accept:application/json");
        $url = "https://ws.aramex.net/ShippingAPI.V2/Tracking/Service_1_0.svc/json/TrackShipments";
        //"https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments"; // geting from $apiurl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $awb_array = json_decode($response);
        $all_array = json_decode(json_encode($awb_array), TRUE);

        $final_array = array();
        if(!empty($all_array['TrackingResults'])){

            $fixed_array = $all_array['TrackingResults'][0]['Value'];
            $final_array = array_reverse($fixed_array);
            return $final_array;
        }
		
        return $final_array;

	}

	function maptrackstatus($status = null){
        
		$ARAMEX_Array = array(
            1 => array('ARAMEX' =>'SH003','FASTCOO' =>'Out For Delivery', 'code' =>'OFD','main_d' => '22'),						
            2 => array('ARAMEX' =>'SH014','FASTCOO' =>'Dispached to 3pl', 'code' =>'DL','main_d' => '5'),						
            3 => array('ARAMEX' =>'SH005','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            4 => array('ARAMEX' =>'SH006','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            5 => array('ARAMEX' =>'SH007','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            6 => array('ARAMEX' =>'SH154','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            7 => array('ARAMEX' =>'SH234','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            8 => array('ARAMEX' =>'SH236','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            9 => array('ARAMEX' =>'SH495','FASTCOO' =>'Returned On Process', 'code' =>'ROP','main_d' => '18'),						
            10 => array('ARAMEX' =>'SH496','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            11 => array('ARAMEX' =>'SH532','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            12 => array('ARAMEX' =>'SH539','FASTCOO' =>'Returned On Process', 'code' =>'ROP','main_d' => '18'),			
            14 => array('ARAMEX' => 'SH012', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'),
            15 => array('ARAMEX' =>'SH047','FASTCOO' =>'Received Inbound', 'code' =>'RI','main_d' => '19'),
            16=> array('ARAMEX' =>'SH022','FASTCOO' =>'In Transit', 'code' =>'IT','main_d' => '16'),
            17 => array('ARAMEX' =>'SH001','FASTCOO' =>'Delivery On Process', 'code' =>'DOP','main_d' => '19'),
            18 => array('ARAMEX' =>'SH077','FASTCOO' =>'Delivery On Process', 'code' =>'DOP','main_d' => '19'),
            19 => array('ARAMEX' =>'SH069','FASTCOO' =>'Return On Process', 'code' =>'ROP','main_d' => '18'),
            20 => array('ARAMEX' =>'SH164','FASTCOO' =>'Delivery On Process', 'code' =>'DOP','main_d' => '19'), 
            21 => array('ARAMEX' =>'SH407','FASTCOO' =>'Returned On Process', 'code' =>'ROP','main_d' => '18'),	
        );
        foreach ($ARAMEX_Array as $key => $val) {
            if ($ARAMEX_Array[$key]['ARAMEX'] == trim($status)) {
                $returnData=  $ARAMEX_Array[$key];
            }
        }
        if(!empty( $returnData)){
            return  $returnData;
        }else{
            return $returnData =array('ARAMEX' =>strtoupper( trim($status)), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
        }

	}
	
	


?>