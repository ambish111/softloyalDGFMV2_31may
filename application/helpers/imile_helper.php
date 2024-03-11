<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
        
	
		if(!empty($counrierArr_table)){
			$sign = $counrierArr_table['auth_token'];
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			$api_url =  $counrierArr_table['api_url'];
            $customerID = $counrierArr_table['courier_account_no'];
			
            $responseArray = iMileToken($sign,$customerID,$api_url);

            if($responseArray['code'] == 200  && $responseArray['message'] == 'success'){

                $token = $responseArray['data']['accessToken'];
                if(!empty($token)){

                    $trackResult = getTrackingResponse($client_awb,$slipNo,$token,$api_url , $sign,$customerID); 
                    if(!empty($trackResult)){
                        $CI =& get_instance();
                        $CI->load->model('Shipment_model');
                        foreach ($trackResult as $allData) {

                            $status = strtoupper($allData['latestStatus']);
                            $arrayData = maptrackstatus($status);

                            if(!empty($arrayData) && $status!='SO'){  

                                $date_time = strtotime($allData['latestStatusTime']);
                                $date_time = str_replace('Date(', '', $date_time);
                                $epoch = str_replace(')', '', $date_time);
                                $date_in_formate = strtotime($epoch, true);
                                $EPOCH_DATE = (int) $date_in_formate / 1000;
                                 $CURRENT_DATE_TIME = date('Y-m-d H:i:s',$date_time); 
                                $CURRENT_DATE = date('Y-m-d',$date_time);
                                $CURRENT_TIME = date('H:i:s',$date_time);
                                $details = $allData['locusDetailed'].' '. $allData['latestSite'];
                                if(empty($allData['locusDetailed'])){
                                        $details = $allData['locusType'].' '. $allData['latestSite'];
                                }
                    
                                $details = str_replace( array(  '<', '>' ), ' ', $details);
                                
                                $activity = $arrayData['FASTCOO']; 
                                $checkStatus = $CI->Shipment_model->checkStatusFM($CURRENT_DATE_TIME, $slipNo, $arrayData['main_d']); 
                                if(empty($checkStatus)){
                                    $shipdata = $CI->Shipment_model->getSlipDataForTrack($slipNo);
                                    if(!empty($shipdata)){
                                        $CI->Shipment_model->updateTrackingStatus($arrayData,$CURRENT_DATE_TIME,$slipNo, $client_awb,$CURRENT_TIME, $details, $activity,$shipData, $cc_name);
                                        $updaterecords =  $CI->Shipment_model->update3plAdminReport($CURRENT_DATE_TIME,$arrayData, $slipNo,$cc_name);
                                    }
                                    
                                }
                            }
                        }
                    }
                }

            }
		}
	}

    function iMileToken($sign=null,$customerID=null,$api_url=null){
        $apiUrl = $api_url ."auth/accessToken/grant";
        
        $timestamp =  strtotime(date("Y-m-d H:i:s")) * 1000;
        $requestParams = array(
           "customerId"=>$customerID,
           "format"=>"json",
           "signMethod"=>"SimpleKey",
           "version"=>"1.0.0",
           "timestamp"=>$timestamp,//strtotime(date('Y-m-d :i:s')),
           "timeZone"=>"+3",
           "Sign"=>$sign,
           "param"=>array(
             "grantType"=> "clientCredential"  
           ) 
        );
        $params = json_encode($requestParams);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json"
        ));
         $response = curl_exec($ch); 
        
        curl_close($ch);
        $responseArray = json_decode($response,TRUE);
        return $responseArray;
        
        
    }

	function getTrackingResponse($client_awb = null, $awb = null, $auth_token = null, $api_url=null, $sign=null,$customerID=null){

		$awb = trim($awb);
        $client_awb = trim($client_awb);
        
        $track_url = $api_url."client/order/queryTrackOneByOne";
        
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json"
        );
        
        $timestamp =  strtotime(date("Y-m-d H:i:s")) * 1000;
        $requestParams = array(
               "customerId"=>$customerID,
               "accessToken"=>$auth_token,
               "format"=>"json",
               "signMethod"=>"SimpleKey",
               "version"=>"1.0.0",
               "timestamp"=>$timestamp,//strtotime(date('Y-m-d :i:s')),
               "timeZone"=>"+3",
               "Sign"=>$sign,
               "param"=>array(
                 "orderType"=>"1",
                 "language"=>"2",
                 "orderNo"=>$client_awb
               ) 
            );
        $params = json_encode($requestParams);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $track_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($ch);
        
        curl_close($ch);
        
        $arrayData = json_decode($response, true);  

        $final_array = array();
        if (!empty($arrayData)){
            if($arrayData['code'] == 200 && $arrayData['message'] == "success"){
                $final_array = array_reverse($arrayData['data']['locus']);
            }   
        }
        return $final_array;

	}

	function maptrackstatus($status = null){
		$imile_Array = array(
            0 => array('IMILE' =>  '200','FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'),
            1 => array('IMILE' =>  '400','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            2 => array('IMILE' =>  '500','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            3 => array('IMILE' =>  '600','FASTCOO' => 'Out for Delivery', 'code' => 'OFD', 'main_d' => '22'),
            4 => array('IMILE' =>  '700','FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            5 => array('IMILE' =>  'DELIVERY','FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
            6 => array('IMILE' =>  '900','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            7 => array('IMILE' =>  '1000','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            8 => array('IMILE' =>  '1100','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            9 => array('IMILE' =>  '1200','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            10 => array('IMILE' =>  '1300','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            11 => array('IMILE' =>  '1400','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            12 => array('IMILE' =>  '1600','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            13 => array('IMILE' =>  'CANCELORDER','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            14 => array('IMILE' =>  'OPEN','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            15 => array('IMILE' =>  'AUDITED','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            16 => array('IMILE' =>  'CANCELED','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            17 => array('IMILE' =>  'PART_ALLOCATED','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            18 => array('IMILE' =>  'ALLOCATED','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            19 => array('IMILE' =>  'PICKING','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            20 => array('IMILE' =>  'PICKED', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'), //pickup collected         
            21 => array('IMILE' =>  'PACKAGED','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            22 => array('IMILE' =>  'SENDING','FASTCOO' => 'Out for Delivery', 'code' => 'OFD', 'main_d' => '22'),
            23 => array('IMILE' =>  'SENDED','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            24 => array('IMILE' =>  'OPERATE_EXCEPTION','FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'),
            25 => array('IMILE' =>  'CANCEL_AUDIT','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            26 => array('IMILE' =>  'CANCEL_ALLOCATE','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            27 => array('IMILE' =>  'CANCEL_PACKAGE','FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            28 => array('IMILE' =>  'PD', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'), //pickup collected 
            29 => array('IMILE' =>  'PR', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
    
        );
    
    
        $returnData="";
    
        foreach ($imile_Array as $key => $val) {
            if ($imile_Array[$key]['IMILE'] == trim($status)) {
                $returnData= $imile_Array[$key];
            }
        }
    
    
        if(!empty( $returnData)){
            return  $returnData;
        }else{
            return $returnData =array('IMILE' =>strtoupper( trim($status)), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
        }

	}
	
	


?>