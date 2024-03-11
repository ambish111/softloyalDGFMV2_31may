<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
	
		if(!empty($counrierArr_table)){
			$auth_token = $counrierArr_table['auth_token'];
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			$api_url =  $counrierArr_table['api_url']."track/".$client_awb;
			
			$trackResult = getTrackingResponse($slipNo,$api_url,$auth_token ); 
			//print "<pre>"; print_r($trackResult);die;
			if(!empty($trackResult)){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $allData) {
					$date_time = strtotime($allData['created_at']);  // date not received from aymakan webhook response                           
                    $CURRENT_DATE_TIME = date('Y-m-d H:i:s',$date_time);
                    $CURRENT_DATE = date('Y-m-d',$date_time);
                    $CURRENT_TIME = date('H:i:s',$date_time);
                    $status = $allData['status_code'];                        
                    $details = addslashes($allData['description']);

					$arrayData = maptrackstatus($status);
					//print "<pre>"; print_r($arrayData);die;
					if(!empty($arrayData)){
						$activity = $arrayData['FASTCOO'];
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


	function getTrackingResponse($slip_no=null,$api_url=null,$auth_token=null){

		$param=array("shipment_id"=>$slip_no);

        $dataJson =json_encode($param);
        $headers = array(
            "Accept: application/json",
            "Authorization:".$auth_token
        );
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch); 
        curl_close ($ch);                           
        $array = json_decode($response, true);
		
        $final_array = array();
        if(isset($array['data']['shipments']) && !empty($array['data']['shipments'])){
            $dataArray=$array['data']['shipments'][0];
            $dataArray=$dataArray['tracking_info'];
            $final_array = array_reverse($dataArray);

        }

        return $final_array;

	}

	function maptrackstatus($status = null){
		$Aymakan_Array=array(
                
            //0 => array('AYMAKAN' => 'AY-0001', 'FASTCOO' => 'Submitted', 'code' => '', 'main_d' => ''),
                1 => array('AYMAKAN' => 'AY-0002', 'FASTCOO' => 'Pickup Collected', 'code' => 'PC', 'main_d' => '19'),
                2 => array('AYMAKAN' => 'AY-0003', 'FASTCOO' => 'Receive Inbound', 'code' => 'RI', 'main_d' => '19'),
                3 => array('AYMAKAN' => 'AY-0005', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
                4 => array('AYMAKAN' => 'AY-0004', 'FASTCOO' => 'Out for Delivery', 'code' => 'OFD', 'main_d' => '22'),
                5 => array('AYMAKAN' => 'AY-0006', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                6 => array('AYMAKAN' => 'AY-0008', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
                7 => array('AYMAKAN' => 'AY-0009', 'FASTCOO' => 'In Transit', 'code' => 'IT', 'main_d' => '16'),
                8 => array('AYMAKAN' => 'AY-0010', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
                9 => array('AYMAKAN' => 'AY-0011', 'FASTCOO' => 'Canceled', 'code' => 'CANC', 'main_d' => '19'),
                10 => array('AYMAKAN' => 'AY-0012', 'FASTCOO' => 'Receive Inbound', 'code' => 'RI', 'main_d' => '19'),
                11 => array('AYMAKAN' => 'AY-0013', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                12 => array('AYMAKAN' => 'AY-102', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                13 => array('AYMAKAN' => 'AY-103', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                14 => array('AYMAKAN' => 'AY-104', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                15 => array('AYMAKAN' => 'AY-22', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                16 => array('AYMAKAN' => 'AY-23', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                17 => array('AYMAKAN' => 'AY-24', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                18 => array('AYMAKAN' => 'AY-0007', 'FASTCOO' => 'Canceled', 'code' => 'CANC', 'main_d' => '19'),
                19 => array('AYMAKAN' => 'AY-0014', 'FASTCOO' => 'Canceled', 'code' => 'CANC', 'main_d' => '19'), 
                20 => array('AYMAKAN' => 'AY-0025', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
                21 => array('AYMAKAN' => 'AY-0026', 'FASTCOO' => 'Receive Inbound', 'code' => 'RI', 'main_d' => '19'),
                22 => array('AYMAKAN' => 'AY-0027', 'FASTCOO' => 'Receive Inbound', 'code' => 'RI', 'main_d' => '19'), 
                23 => array('AYMAKAN' => 'AY-0028', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
                //24 => array('AYMAKAN' => 'AY-0029', 'FASTCOO' => '', 'code' => '', 'main_d' => ''),
                25 => array('AYMAKAN' => 'AY-0030', 'FASTCOO' => 'Receive Inbound', 'code' => 'RI', 'main_d' => '19'), 
                26 => array('AYMAKAN' => 'AY-0031', 'FASTCOO' => 'Update Address Requested', 'code' => 'UAR', 'main_d' => '19'), 
                
                
        );
        foreach($Aymakan_Array as $key=>$val)
        {
            if($Aymakan_Array[$key]['AYMAKAN']==trim($status))
            {
                $returnData=	 $Aymakan_Array[$key];
            }
            
        }
        if(!empty( $returnData)){
                return  $returnData;
        }else{

            return $returnData =array('AYMAKAN' =>trim($status), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
        }	

	}
	
	


?>