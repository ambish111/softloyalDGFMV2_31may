<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
       // print "<pre>"; print_r($counrierArr_table);die;
		if(!empty($counrierArr_table)){

			$key = $counrierArr_table['auth_token'];
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			$api_url =  $counrierArr_table['api_url'];
            $password = $counrierArr_table['password'];
            $account = $counrierArr_table['courier_account_no'];
            $customerCode = $counrierArr_table['courier_pin_no'];                
			
			$trackResult = getTrackingResponse($client_awb,$slipNo,$key , $api_url, $account,$password,$customerCode);
			//print "<pre>"; print_r($trackResult);die;
			if(!empty($trackResult)){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $allData) {

					
                    $date_time = strtotime($allData['scanTime']);
            
                    $CURRENT_DATE_TIME = date('Y-m-d H:i:s',$date_time);
                    $CURRENT_DATE = date('Y-m-d',$date_time);
                    $CURRENT_TIME = date('H:i:s',$date_time);
                    $status = $allData['scanType'];
                    
                    $details = addslashes($allData['desc']);
                    if($cc_name == 'J&T'){
                        $arrayData = maptrackstatus($status);
                    }else if($cc_name == 'J&T EG'){
                        $arrayData = mapJTEGtrackstatus($status);
                    }
                    
					
					//print "<pre>"; print_r($arrayData);die;
					if(!empty($arrayData)){
                        $activity = addslashes($arrayData['FASTCOO']);
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


	function getTrackingResponse($client_awb=null, $slip_no=null, $key = null, $api_url = null, $account= null,$password=null,$customerCode=null){

		$track_url = "https://openapi.jtjms-sa.com/webopenplatformapi/api/logistics/trace";
        
        $track_info='{"billCodes":"'.$client_awb.'"}';
        
        $post_data = get_post_data($customerCode,$password,$key,$track_info);

        $head_dagest = get_header_digest($post_data,$key);

        $post_content = array(
            'bizContent' => $post_data
        );

        $postdata = http_build_query($post_content);
            
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' =>
                    array('Content-type: application/x-www-form-urlencoded',
                        'apiAccount:' . $account,
                        'digest:' . $head_dagest,
                        'timestamp: '.time()),
                'content' => $postdata,
                'timeout' => 15 * 60 // è¶…æ—¶æ—¶é—´ï¼ˆå�•ä½�:sï¼‰
            )
        );

        $context = stream_context_create($options);

        $result = file_get_contents($track_url, false, $context);
        $resultArr = json_decode($result,TRUE);

        $finalArr = array();
        if(isset($resultArr['data']) && !empty($resultArr['data'])){
            $finalArr = array_reverse($resultArr['data'][0]['details']);
        }
        return $finalArr;

	}


    function get_post_data($customerCode,$pwd,$key,$waybillinfo){
        $postdata = json_decode($waybillinfo,true);
        $postdata['customerCode'] = $customerCode;
        $postdata['digest'] = get_content_digest($customerCode,$pwd,$key);
    
        return json_encode($postdata);
    }

    function get_content_digest($customerCode,$pwd,$key){
        $str = strtoupper($customerCode . md5($pwd . 'jadada236t2')) . $key;

        return base64_encode(pack('H*', strtoupper(md5($str))));
    }

    function get_header_digest($post,$key){
        $digest = base64_encode(pack('H*',strtoupper(md5($post.$key))));
        return $digest;
    }


    function mapJTEGtrackstatus($status = null){
        $statusData = array(

                0 => array('J&T' => 'Pickup scan', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
                1 => array('J&T' =>'Station sending scan/DC sending scan','FASTCOO' => 'In Tranist','code' =>'IT','main_d' =>'16'),
                2 => array('J&T' => 'Station arrival', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP','main_d' => '19'),
                3 => array('J&T' => 'DC arrival', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP','main_d' => '19'),
                4 => array('J&T' => 'Delivery scan', 'FASTCOO' => 'Out for delivery', 'code' => 'OFD', 'main_d' => '22'),
                6 => array('J&T' => 'Abnormal parcel scan', 'FASTCOO' =>'Failed Delivery','code' => 'FD','main_d' => '20'),
                7 => array('J&T' => 'Returned parcel scan', 'FASTCOO' => 'Return On Process','code' => 'ROP', 'main_d' => '18'),
                8 => array('J&T' => 'Change Add. Scan', 'FASTCOO' =>'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
                9 => array('J&T' => 'Signing scan', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
            );
    
        $returnData="";
        foreach ($statusData as $key => $val) {
            if (strtoupper($statusData[$key]['J&T']) == strtoupper( trim($status))) {
                $returnData= $statusData[$key];
            }
        }

        if(!empty( $returnData))
        {
            return  $returnData;
        }
        else
        {
            return $returnData =array('J&T' =>strtoupper( trim($status)), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16');

        }
    }

	function maptrackstatus($status = null){
		$statusData = array(
            0 => array('J&T' => 'Pickup scan', 'FASTCOO' => 'Pickup Collected', 'code' => 'PC', 'main_d' => '19'),
            1 => array('J&T' =>'Station sending scan/DC sending scan','FASTCOO' => 'In Tranist','code' =>'IT','main_d' =>'16'),
            2 => array('J&T' => 'Station arrival', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP','main_d' => '19'),
            3 => array('J&T' => 'DC arrival', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP','main_d' => '19'),
            4 => array('J&T' => 'Delivery scan', 'FASTCOO' => 'Out for delivery', 'code' => 'OFD', 'main_d' => '22'),
            5 => array('J&T' => 'Sign scan', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
            6 => array('J&T' => 'Abnormal parcel scan', 'FASTCOO' =>'Failed Delivery','code' => 'FD','main_d' => '20'),
            7 => array('J&T' => 'Returned parcel scan', 'FASTCOO' => 'Return On Process','code' => 'ROP', 'main_d' => '18'),
            8 => array('J&T' => 'Change Add. Scan', 'FASTCOO' =>'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
        );
    
        $returnData="";
        foreach ($statusData as $key => $val) {
            if (strtoupper($statusData[$key]['J&T']) == strtoupper( trim($status))) {
                $returnData= $statusData[$key];
            }
        }

        if(!empty( $returnData))
        {
            return  $returnData;
        }
        else
        {
            return $returnData =array('J&T' =>strtoupper( trim($status)), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16');

        }

	}
	
	


?>