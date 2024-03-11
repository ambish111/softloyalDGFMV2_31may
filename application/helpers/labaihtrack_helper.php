<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
        
	
		if(!empty($counrierArr_table)){
			$auth_token = $counrierArr_table['auth_token'];
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			$api_url =  $counrierArr_table['api_url'];
			
			$trackResult = getTrackingResponse($client_awb, $slipNo, $api_url, $auth_token); 
			//print "<pre>"; print_r($trackResult);die;
			if(!empty($trackResult)){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $allData) {

                    $arrayData = maptrackstatus($allData['status']);
                    if(!empty($arrayData)){

                            $date_time = str_replace('/', '', $allData['lastUpdated']);
                            $date_time = str_replace('Date(', '', $date_time);
                            $epoch = str_replace(')', '', $date_time);
                            $date_in_formate = strtotime($epoch, true);
                            $EPOCH_DATE = (int) $date_in_formate / 1000;
                            $CURRENT_DATE_TIME = date('Y-m-d H:i:s');
                            $CURRENT_DATE = date('Y-m-d');
                            $CURRENT_TIME = date('H:i:s');
            
                            header('Content-type: text/html; charset=UTF-8');
                            $details = !empty($allData['remarks'])?$allData['remarks']:$allData['status'];
                            $activity = $arrayData['FASTCOO'];                        

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


	function getTrackingResponse($client_awb=null, $slip_no=null,$api_url=null,$auth_token=null){

		$client_awb = trim($client_awb);
        $track_url= $api_url."order/get?api_key=".$auth_token."&consignmentNo=".$client_awb;
        
        $headers = array(
            "Content-Type: application/json"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$track_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        $response = curl_exec($ch);                 
        curl_close($ch);                           
        $array = json_decode($response, true);
		
        $final_array = array();
        if(!empty($array)){
            
            $final_array = array_reverse($array['data']);

        }

        return $final_array;

	}

	function maptrackstatus($status = null){
		$labaih_Array = array(
            // 0 => array('LABAIH'=>'PROCESSING', 'FASTCOO' =>'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
             1 => array('LABAIH'=>'REACH-HUB', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
             2 => array('LABAIH'=>'NOT DISPATCHED', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
             3 => array('LABAIH'=>'IN-TRANSIT', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
             4 => array('LABAIH'=>'DELIVERED','FASTCOO'=>'Delivered', 'code' =>'POD','main_d' =>'7'),
             5 => array('LABAIH'=>'ATTEMPTED','FASTCOO'=>'Failed Delivery','code'=>'FD','main_d'=>'20'),
             6 => array('LABAIH'=>'CANCELLED', 'FASTCOO' =>'Return On Process','code' => 'ROP', 'main_d' =>'18'),
             7 => array('LABAIH'=>'CANCELLED - RTM NOT DISPATCHED', 'FASTCOO' =>'Return On Process', 'code' =>'ROP', 'main_d' => '18'),
             8 => array('LABAIH'=>'CANCELLED - RTM DELIVERED', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
             9 => array('LABAIH'=>'PICKUP', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18')
     
         );
     
         foreach ($labaih_Array as $key => $val) {
             if ($labaih_Array[$key]['LABAIH'] == trim($status)) {
                 return $labaih_Array[$key];
             }
         }
         if(!empty( $returnData)){
             return  $returnData;
         }else{
             return $returnData =array('LABAIH' =>trim($status), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
         }	

	}
	
	


?>