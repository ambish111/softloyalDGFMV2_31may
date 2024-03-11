<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	function updateTrackingStatus($shipData =array(), $counrierArr_table= array()){
	
	
		if(!empty($counrierArr_table)){
			$auth_token = $counrierArr_table['auth_token'];
			$client_awb = $shipData['frwd_company_awb'];
			$slipNo = $shipData['slip_no'];
			$cc_name = $counrierArr_table['company'];
			
			$trackResult = getTrackingResponse($client_awb, $slipNo, $auth_token ); 
			//print "<pre>"; print_r($trackResult);die;
			if(!empty($trackResult[0])){
				$CI =& get_instance();
				$CI->load->model('Shipment_model');
				foreach ($trackResult as $allData) {

                    $arrayData = maptrackstatus($allData['Activity']);
                    if(!empty($arrayData)){
						
                        
                        $date = date_create($allData['Date']);
                        $CURRENT_DATE_TIME =  date_format($date,"Y-m-d H:i:s");
                        
                        $CURRENT_TIME = date('H:i:s',strtotime($CURRENT_DATE_TIME));
                        
                        $det = stripInvalidXml($allData['Details']);
                        $act = stripInvalidXml($allData['Activity']);
            
                        $details = addslashes($act.'  '. $det);
                      
                        $activity = addslashes($arrayData['FASTCOO']);

                        
						$checkStatus = $CI->Shipment_model->checkStatusFM($CURRENT_DATE_TIME, $slipNo, $arrayData['main_d']);
						
						if(empty($checkStatus)){

                            $shipdata = $CI->Shipment_model->getSlipDataForTrack($slipNo);
                            if(!empty($shipdata)){
                                $CI->Shipment_model->updateTrackingStatus($arrayData,$CURRENT_DATE_TIME,$slipNo, $client_awb,$CURRENT_TIME, $details, $activity,$shipData, $cc_name); //Update Close for time being
                                $updaterecords =  $CI->Shipment_model->update3plAdminReport($CURRENT_DATE_TIME,$arrayData, $slipNo,$cc_name);
                            }
							
						}
					}
				}
			}else{
                $arrayData = maptrackstatus($trackResult['Activity']);
                if(!empty($arrayData)){
                    

                    $date = date_create($trackResult['Date']);
                    $CURRENT_DATE_TIME =  date_format($date,"Y-m-d H:i:s");
                    
                    $CURRENT_TIME = date('H:i:s',strtotime($CURRENT_DATE_TIME));
                    
                    $det = stripInvalidXml($trackResult['Details']);
                    $act = stripInvalidXml($trackResult['Activity']);
        
                    $details = addslashes($act.'  '. $det);
                    
                    $activity = addslashes($arrayData['FASTCOO']);
                   

                    $checkStatus = $CI->Shipment_model->checkStatusFM($CURRENT_DATE_TIME, $slipNo, $arrayData['main_d']);
                    //print "<pre>"; print_r($checkStatus);die;
                    if(empty($checkStatus)){

                        $shipdata = $CI->Shipment_model->getSlipDataForTrack($slipNo);
                        if(!empty($shipdata)){
                            $CI->Shipment_model->updateTrackingStatus($arrayData,$CURRENT_DATE_TIME,$slipNo, $client_awb,$CURRENT_TIME, $details, $activity,$shipData, $cc_name); //Update Close for time being
                        }
                        
                    }
                }
            }

		}

	}
    function stripInvalidXml($value)
    {
        return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $value);
    }

	function getTrackingResponse($client_awb, $slip_no=null,$passkey=null){

		$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                      <soap:Body>
                        <getTracking xmlns="http://track.smsaexpress.com/secom/"> 
                          <awbNo>'.$client_awb.'</awbNo>
                          <passkey>'.$passkey.'</passkey>
                        </getTracking>
                      </soap:Body>
                    </soap:Envelope>';   // data from the form, e.g. some ID number

                   $headers = $headers = array(
                        "Content-type: text/xml;charset=utf-8",
                        "Accept: application/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction:http://track.smsaexpress.com/secom/getTracking",
                        "Content-length: ".strlen($xml_post_string),
                    );
           //echo $xml_post_string;die;        
           $url="http://track.smsaexpress.com/SECOM/SMSAwebService.asmx";

            // PHP cURL  for https connection with auth
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          

            // converting
            $response = curl_exec($ch); 
            $ip = getenv('REMOTE_ADDR');
            
            curl_close($ch);
            
 
        $sxe = new SimpleXMLElement($response);
        $sxe->registerXPathNamespace('d', 'urn:schemas-microsoft-com:xml-diffgram-v1');
        $result = $sxe->xpath("//NewDataSet");
        //echo "<pre>";
        $array = json_decode(json_encode($result), TRUE);
               

		
        $final_array = array();
        if(isset($array[0]['Tracking']) && !empty($array[0]['Tracking'])){
            $final_array = array_reverse($array[0]['Tracking']);
        }

        return $final_array;

	}

	function maptrackstatus($status = null){
		$statusData = array(
            0 => array('SMSA' => 'PROOF OF DELIVERY CAPTURED', 'FASTCOO' => 'Delivered', 'code' => 'POD', 'main_d' => '7'),
            1 => array('SMSA' =>'Awaiting Consignee for Collection','FASTCOO' => 'Delivery On Process','code' =>'DOP','main_d' =>'19'),
            2 => array('SMSA' => 'Processing for Consignee Collection', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP','main_d' => '19'),
            3 => array('SMSA' => 'Out for Delivery', 'FASTCOO' => 'Out for delivery', 'code' => 'OFD', 'main_d' => '22'),
            9 => array('SMSA' => 'Consignee did not Wait', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            10 => array('SMSA' => 'Consignee Do not Want the Shipment', 'FASTCOO' =>'Failed Delivery','code' => 'FD','main_d' => '20'),
            11 => array('SMSA' => 'Refused Due to Incorrect COD Amount', 'FASTCOO' => 'Failed Delivery','code' => 'FD', 'main_d' => '20'),
            12 => array('SMSA' => 'Refused Due to Duplicate Shipment', 'FASTCOO' =>'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            13 => array('SMSA' => 'Consignee Request to Open Before POD', 'FASTCOO' =>'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            14 => array('SMSA' => 'Refused Due to Contents Mismatch', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            15 => array('SMSA' => 'Shipment Refuse By Recipient', 'FASTCOO' =>'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            16 => array('SMSA' => 'Consignee Unable to Pay Custom Duty', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            17 => array('SMSA' => 'Consignee Unable to Pay COD Charges', 'FASTCOO' =>'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            18 => array('SMSA' => 'Consignee Refuse to Pay Custom Duty', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            19 => array('SMSA' => 'Consignee Refuse to Pay COD Charges', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            20 => array('SMSA' => 'Shipment On Hold', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            21 => array('SMSA' => 'Return Process Started', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            22 => array('SMSA' => 'No Contact Number', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            23 => array('SMSA' => 'Incorrect Contact Number', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            24 => array('SMSA' => 'Consignee Contact out of Service', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            25 => array('SMSA' => 'Consignee Unknown', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            26 => array('SMSA' => 'Picked Up', 'FASTCOO' => 'Pickup Collected', 'code' => 'PC', 'main_d' => '19'),
            27 => array('SMSA' => 'Arrived Delivery Facility', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            28 => array('SMSA' => 'Arrived HUB Facility', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            29 => array('SMSA' => 'At SMSA Facility', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            33 => array('SMSA' => 'RETURNED TO CLIENT', 'FASTCOO' => 'Return On Process', 'code' => 'ROP', 'main_d' => '18'),
            34 => array('SMSA' => 'In SMSA Facility', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            35 => array('SMSA' => 'Consignee No Response', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            36 => array('SMSA' => 'Consignee Mobile Off', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            37 => array('SMSA' => 'Consignee not Available', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            39 => array('SMSA' => 'Consignee Request to Call Later', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            40 => array('SMSA' => 'Consignee out of City / Country', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            41 => array('SMSA' => 'Incorrect Delivery Address', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            42 => array('SMSA' => 'Recipient not available at residence', 'FASTCOO' => 'Failed Delivery', 'code' => 'FD', 'main_d' => '20'),
            43 => array('SMSA' => 'At SMSA Retail Center', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            44 => array('SMSA' => 'Holiday/Weekend Closed', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),
            45 => array('SMSA' => 'Consignee Address Changed', 'FASTCOO' => 'Delivery On Process', 'code' => 'DOP', 'main_d' => '19'),        
           // 46 => array('SMSA' => 'DATA RECEIVED', 'FASTCOO' => 'Delivery On TEst', 'code' => 'DOP', 'main_d' => '19'),   //test      
        );
    
     //echo json_encode($statusData);die;
     $returnData="";
        foreach ($statusData as $key => $val) {
            if (strtoupper($statusData[$key]['SMSA']) == strtoupper( trim($status))) {
                // print_r($ARAMEX_Array[$key]);exit;
                $returnData= $statusData[$key];
            }
        }

        if(!empty( $returnData))
        {
            return  $returnData;
        }
        else
        {
            return $returnData =array('SMSA' =>strtoupper( trim($status)), 'FASTCOO' =>'In transit', 'code' => 'IT', 'main_d' => '16');
        }

	}
	
	


?>