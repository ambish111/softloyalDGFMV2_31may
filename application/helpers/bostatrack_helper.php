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
			
			$trackResult = getTrackingResponse($client_awb, $slipNo,$api_url,$auth_token ); 
			//print "<pre>"; print_r($trackResult);die;
			if(!empty($trackResult)){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $obj) {


                    $timelinedone =  $obj->done ;
                    if(!empty($timelinedone) || ($timelinedone == 1) ) {
                            $timelinecode =  $obj->code;
                            

                            $date_time =   strtotime($obj->date);
                            $CURRENT_DATE_TIME = date('Y-m-d H:i:s',$date_time);
                            $CURRENT_DATE = date('Y-m-d',$date_time);
                            $CURRENT_TIME = date('H:i:s',$date_time);
                            $details =  $obj->value;
                            
                            $deliverdate ="";

                            $arrayData = maptrackstatus($timelinecode);
                            //print "<pre>"; print_r($arrayData);die;
                            if(!empty($arrayData)){
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

	}


	function getTrackingResponse($client_awb=null, $slip_no=null,$api_url=null,$auth_token=null){

		$api_url = $api_url."v0/deliveries/".$client_awb; 
         
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
                'Authorization: '.$auth_token,
                'Content-Type: application/json'
            ),
        ));
    
        $response = curl_exec($curl);
        curl_close($curl);

        $all_array = json_decode($response);


        $final_array = array();
        if(isset($all_array->timeline)){
            $final_array = $all_array->timeline;
        }
		
        return $final_array;

	}

	function maptrackstatus($status = null){
		$Bosta_Array = array(
            1 => array('BOSTA' =>'45','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),
            2=> array('BOSTA' =>'30','FASTCOO' =>'In Transit', 'code' =>'IT','main_d' => '16'),
            3=> array('BOSTA' =>'11','FASTCOO' =>'In Transit', 'code' =>'IT','main_d' => '16'),
            4 => array('BOSTA' => '40', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'),
            5 => array('BOSTA' => '21', 'FASTCOO' => 'Pickup collected', 'code' => 'PC', 'main_d' => '19'),
            6 => array('BOSTA' =>'20','FASTCOO' =>'Delivery On Process', 'code' =>'DOP','main_d' => '19'),
            7 => array('BOSTA'=>'41', 'FASTCOO' =>'Out For Delivery', 'code' => 'OFD', 'main_d' => '22'),
            8 => array('BOSTA' =>'22','FASTCOO' =>'Delivery On Process', 'code' =>'DOP','main_d' => '19'),
            9 => array('BOSTA' =>'23','FASTCOO' =>'Delivery On Process', 'code' =>'DOP','main_d' => '19'),
            10 => array('BOSTA' =>'24','FASTCOO' =>'Failed Delivery', 'code' =>'FD','main_d' => '20'),
            11 => array('BOSTA' =>'45','FASTCOO' =>'Delivered', 'code' =>'POD','main_d' => '7'),						
            12 => array('BOSTA' =>'48','FASTCOO' =>'Failed Delivery', 'code' =>'FD','main_d' => '20'),						
            13 => array('BOSTA' =>'46','FASTCOO' =>'Returned On Process', 'code' =>'ROP','main_d' => '18')
    
        );
        $returnData  = '';   
        foreach ($Bosta_Array as $key => $val) {
            if ($Bosta_Array[$key]['BOSTA'] == trim($status)) {
                $returnData =  $Bosta_Array[$key];
            }
        }
        if(!empty( $returnData)){
            return  $returnData;
        }else{
            return $returnData =array('BOSTA' =>trim($status), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16'); // set default status 
        }	

	}
	
	


?>