<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
        //print "<pre>"; print_r($shipData);die;
		if(!empty($counrierArr_table)){
			$auth_token = $counrierArr_table['auth_token'];
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			$api_url =  $counrierArr_table['api_url'];
            $barq_order_id = $shipData['barq_order_id'];
			
			$trackResult = getTrackingResponse($client_awb,$slipNo,$api_url,$auth_token,$barq_order_id ); 
			//print "<pre>"; print_r($trackResult);die;
			if(!empty($trackResult)){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $allData) {

					$date_time = str_replace('/', '', $allData['created_at']);
                    $date_time = str_replace('Date(', '', $date_time);
                    $epoch = str_replace(')', '', $date_time);
                    $date_in_formate = strtotime($epoch, true);
                    $EPOCH_DATE = (int) $date_in_formate / 1000;
                    $CURRENT_DATE_TIME = date('Y-m-d H:i:s', $date_in_formate);
                    $CURRENT_DATE = date('Y-m-d', $date_in_formate);
                    $CURRENT_TIME = date('H:i:s', $date_in_formate);
                    header('Content-type: text/html; charset=UTF-8');
                    $details = $allData['status_reason'];
                    $status = $allData['status'];
                    $arrayData = maptrackstatus($status);
					
					//print "<pre>"; print_r($arrayData);die;
					if(!empty($arrayData)){
						$activity = $arrayData['FASTCOO'];
						$checkStatus = $CI->Shipment_model->checkStatusFM($CURRENT_DATE_TIME, $slipNo, $arrayData['main_d']);
						$shipdata = $CI->Shipment_model->getSlipDataForTrack($slipNo);
                        if(!empty($shipdata)){
                            if(empty($checkStatus)){
                                $CI->Shipment_model->updateTrackingStatus($arrayData,$CURRENT_DATE_TIME,$slipNo, $client_awb,$CURRENT_TIME, $details, $activity,$shipData, $cc_name); //Update Close for time being		
                                $updaterecords =  $CI->Shipment_model->update3plAdminReport($CURRENT_DATE_TIME,$arrayData, $slipNo,$cc_name);					
                            }
                        }
						
					}

				}
			}

		}

	}


	function getTrackingResponse($client_awb=null, $slip_no=null,$api_url=null,$auth_token=null,$barq_order_id =null){

		$track_url = $api_url."/orders/".$barq_order_id."/order_history";
        $headers = array(
            "Content-Type: application/json",
            "Authorization:".$auth_token
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $track_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers
        ));
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        $array = json_decode($response, true);
        $final_array1 = array_reverse($array);

        return $final_array1;

	}

	function maptrackstatus($status = null){
		$Barq_Array = array(
            1 => array('Barq'=>'completed', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
            2 => array('Barq'=>'ready_for_delivery','FASTCOO'=>'Delivery On Process','code'=>'DOP','main_d'=>'19'),
            // 3 => array('Barq'=>'intransit','FASTCOO' =>'Delivery On Process','code'=>'DOP','main_d'=>'19'),		
            3 => array('Barq'=>'intransit','FASTCOO' =>'In transit ','code'=>'IT','main_d'=>'16'),
            4 => array('Barq'=>'processing', 'FASTCOO' =>'Delivery On Process','code'=>'DOP','main_d'=>'19'),
            5 => array('Barq'=>'pickup', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'), 
            6 => array('Barq'=>'RETURNED', 'FASTCOO' => 'Return', 'code' => 'RTC', 'main_d' => '8'),
            // 6 => array('Barq'=>'picked_up', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'),//pickup collected 
        );
        $returnData = '';
        foreach ($Barq_Array as $key => $val) {
            
            if (strtoupper($Barq_Array[$key]['Barq']) == strtoupper(trim($status))) {
                $returnData=  $Barq_Array[$key];
            }
        }
        if(!empty( $returnData))
        {
            return  $returnData;
        }
        else
        {
            return $returnData =array('Barq' =>strtoupper( trim($status)), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
        }

	}
	
	


?>