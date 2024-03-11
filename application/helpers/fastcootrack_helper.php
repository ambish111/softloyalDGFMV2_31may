<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
	
		if(!empty($counrierArr_table)){
			
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			
			$trackResult = getTrackingResponse($client_awb, $slipNo); 
			
			if(!empty($trackResult['travel_history'])){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
                
				foreach ($trackResult['travel_history'] as $allData) {
                    $status = $allData['code'];
                    $arrayData = maptrackstatus($status);
                    if (!empty($arrayData) && ($allData['code'] != 'B') ) {

                            //print "<pre>"; print_r($arrayData); print "<hr />";

                            $date_time = strtotime($allData['entry_date']);                                
                                
                            $CURRENT_DATE_TIME = date('Y-m-d H:i:s',$date_time);
                            $CURRENT_DATE = date('Y-m-d',$date_time);
                            $CURRENT_TIME = date('H:i:s',$date_time);
                            
                            $activity = $allData['new_status'];
                            $detailss = $allData['Activites'];
                            $user_type = strtolower($allData['user_type']);
                            $details=	addslashes($detailss);
                            $statusDescription = $allData['new_status'];
                        
    
                        
                        //print "<pre>"; print_r($arrayData);die;
                        
    
                            if (preg_match("~\bDelivery Location\b~",$details) && ($allData['code'] == 'RFD' ||  $allData['code'] == 'RSD'))
                            {
                                $arrayData['code'] = 'IT';                                            
                            }
        
                            if (preg_match("~\bFeedback Link\b~",$details))
                            {
                                $arrayData['code'] = 'IT';                                            
                            }
        
                            if ( preg_match("~\bRTO Update By\b~",$details))
                            {
                                $arrayData['code'] = 'RI'; 
                                $activity = 'Received Inbound';                                           
                            }
    
                            $shipdata = $CI->Shipment_model->getSlipDataForTrack($slipNo);
                            if(!empty($shipdata)){
                                $checkStatus = $CI->Shipment_model->checkStatusFM($CURRENT_DATE_TIME, $slipNo, $arrayData['main_d']);
                                //print "<pre>"; print_r($checkStatus);die;
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


	function getTrackingResponse($client_awb=null, $slip_no=null){

		$param =array(
            'awb' => trim($client_awb)
        );
        $dataJson = json_encode($param);
        $headers = array(
             "Content-Type: application/json",
        );

        $track_url ='https://api.fastcoo-tech.com/API/trackShipment';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $track_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);

        $dataArray = json_decode($response, true);

        return $dataArray;

	}

	function maptrackstatus($status = null){

		$fastcoo_Array = array(
            0 => array('FASTCOOLM' =>'POD', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
            1 => array('FASTCOOLM' =>'OD', 'FASTCOO' => 'Out for Delivery', 'code' => 'OFD', 'main_d' => '22'),
            2 => array('FASTCOOLM' =>'RTC', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            3 => array('FASTCOOLM'=>'PC', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'),
            4 => array('FASTCOOLM'=>'RI', 'FASTCOO' => 'Received Inbound', 'code' => 'RI', 'main_d' => '19'),
            5 => array('FASTCOOLM'=>'OCA', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'), 
            6 => array('FASTCOOLM'=>'RF', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            7 => array('FASTCOOLM'=>'MW', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            8 => array('FASTCOOLM'=>'MC', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            9 => array('FASTCOOLM'=>'NA', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            10 => array('FASTCOOLM'=>'CNL', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            11 => array('FASTCOOLM'=>'CNC', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            12 => array('FASTCOOLM'=>'DD', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            13 => array('FASTCOOLM'=>'PD', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            14 => array('FASTCOOLM'=>'DO', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            15 => array('FASTCOOLM'=>'WAR', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            16 => array('FASTCOOLM'=>'CNI', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            17 => array('FASTCOOLM'=>'RFD', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            18 => array('FASTCOOLM'=>'RSD', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
            19 => array('FASTCOOLM'=>'RSP', 'FASTCOO' => 'Failed Delivery ', 'code' => 'FD', 'main_d' => '20'),
           
        );
    
    
        $returnData="";
        foreach ($fastcoo_Array as $key => $val) {
            if ($fastcoo_Array[$key]['FASTCOOLM'] == trim($status)) {
                return $fastcoo_Array[$key];
            }
        }

        if(!empty( $returnData)){
            return  $returnData;
        }else{
            return $returnData =array('FASTCOOLM' => trim($status), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
        }	

	}
	
	


?>