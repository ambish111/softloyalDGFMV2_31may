<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
	function ForwardToTorod($ShipArr=array(), $counrierArr=array(), $c_id=null, $box_pieces1=null, $complete_sku=null, $super_id=null){
    //    echo "<pre>"; print_r($ShipArr); die;
        $token = TorodgetToken($counrierArr);
        // echo $token; die;
	  	$API_URL = $counrierArr['api_url'].'order/create';

        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        // $selleraddress= GetallCutomerBysellerId($ShipArr['cust_id'],'address');
        // print_r($selleraddress);die;
        $receiver_city= getdestinationfieldshow($ShipArr['destination'], 'torod_city',$super_id);  
        $receiver_country= getdestinationfieldshow($ShipArr['destination'], 'country',$super_id);           
        // $receiver_city_code = getdestinationfieldshow($ShipArr['destination'], 'torod_city_id',$super_id);
        // print_r($receiver_city);die;
		if(empty($ShipArr['reciever_address'])){
			$successstatus = "Fail";
			$return_array =  array("error"=>"true","msg"=>'Receiver address empty');
			return $return_array;			
		}

        if(empty($ShipArr['reciever_phone'])){
            $successstatus = "Fail";
            $result =  array("error"=>"true","msg"=>'Receiver phone empty');
            return $result;			
        } else {
            $reciever_phone =$ShipArr['reciever_phone']; 
        }

        $complete_sku = !empty($complete_sku)?$complete_sku:'Goods';

		if(empty($box_pieces1)){
            $box_pieces = 1;
        }else{ 
            $box_pieces = $box_pieces1 ; 
        }
        
        if($ShipArr['weight']==0){  
            $weight= 1;
        }
        else { 
            $weight = $ShipArr['weight'] ; 
        }
        if($ShipArr['mode'] == 'COD'){
            $payment = "COD";
            $amount = $ShipArr['total_cod_amt'];      //$ShipArr['declared_charge'];
        }else{
            $payment = "Prepaid";
            $amount = "0";
        }
        $reciever_email = empty($ShipArr['reciever_email'])?"no@no.com":$ShipArr['reciever_email'];
        // echo $ShipArr['reciever_address']." ".$receiver_city ." ".$receiver_country; die; 
        $mobile_no = substr($ShipArr['reciever_phone'],-9);
        $param = array(
            'name' => $ShipArr['reciever_name'],
            'email' => $reciever_email,
            'phone_number' => "966".$mobile_no,
            'item_description' => $complete_sku,
            'order_total' => $amount,
            'payment' => $payment,
            'weight' =>  $weight,
            'no_of_box' => $box_pieces,
            'type' => 'address',
            // 'district_id' => '51',
            'locate_address' =>$ShipArr['reciever_address']." ".$receiver_city ." ".$receiver_country,  //  'Riyadh Zoo, Mosab Ibn Umair St, Riyadh Saudi Arabia'
        );
            
    //   print_r($param);die;

        // $courier_zone = getDetailsByZone($c_id,$ShipArr['cust_id'],$super_id,$ShipArr['destination']);
        // //print "<pre>"; print_r($courier_zone);die;
        // if($courier_zone == false){
        //     $successstatus  = "Fail";
        //     $CI =& get_instance();
        //     $CI->load->model('Ccompany_model');
        //     $CI->Ccompany_model->shipmentLog($c_id, 'Destination not covered by selected 3pl company',$successstatus, $ShipArr['slip_no'], $param);
        //     $return_array =  array("error"=>"true","msg"=>'Destination not covered by selected 3pl company');
        //     return $return_array;
        // }
		
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
        CURLOPT_POSTFIELDS => $param,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $error = curl_error($curl);
        //$logresponse =   json_encode($response);  
        $response_array = json_decode($response, true);
        $successres = $response_array['data']['order_id'];
		// echo "<pre>"; print_r($response_array); die;
        $torod_automation = site_configTable('torod_automation_flag');

        if($torod_automation == 'N'){
            if(!empty($successres)) 
            {
                // echo "test";die;
                $successstatus  = "Success";
                $slipNo = $ShipArr['slip_no'];
                $client_awb = '';
                $fastcoolabel = '';
                $torod_order_id  = $response_array['data']['order_id'];
                if(!empty($response_array['data']['tracking_id'])){
                    $LabelResponse = file_get_contents($response_array['data']['aws_label']);
                    file_put_contents("assets/all_labels/$slipNo.pdf",$LabelResponse );
                    $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';
                    $client_awb = $response_array['data']['tracking_id'];
                }

                $return_array = array("error"=>'false',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel,'torod_order_id' => $torod_order_id));

            }else {
                $successstatus  = "Fail";
                $return_array =  array("error"=>"true","msg"=>json_encode($response_array['message']));
            }
        }else{
            if($response_array['code'] == 200){

                $successstatus  = "Success";
                $slipNo = $ShipArr['slip_no'];
                $client_awb = $response_array['data']['tracking_id'];
                $LabelResponse = file_get_contents($response_array['data']['aws_label']);
                $torod_order_id  = $response_array['data']['order_id'];
                //  echo $LabelResponse; die;
                file_put_contents("assets/all_labels/$slipNo.pdf",$LabelResponse );
                $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';
                $return_array = array("error"=>'false',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel,'torod_order_id' => $torod_order_id));
            }else {
                $successstatus  = "Fail";
                $return_array =  array("error"=>"true","msg"=>json_encode($response_array['message']));
            }
        }

        $param_data = json_encode($param);

        $CI = &get_instance();
        $CI->load->model('Ccompany_model');
        $CI->Ccompany_model->shipmentLog($c_id, $response, $successstatus, $ShipArr['slip_no'], $param_data);
    
        return $return_array;

	}

     function TorodgetToken($counrierArr=array()){
        // echo "<pre>"; print_r($counrierArr); die;
        // echo "test";die;
        if(empty($counrierArr['api_url'])){
            $API_URL= $counrierArr['api_url_t']."token";
        }else{
            $API_URL = $counrierArr['api_url']."token";
        }
        
        if(empty($counrierArr['courier_account_no'])){
           $client_id= $counrierArr['courier_account_no_t'];
        }else{
            $client_id= $counrierArr['courier_account_no'];
        }
        
        if(empty($counrierArr['courier_pin_no'])){
         $client_secret=$counrierArr['courier_pin_no_t'];
        }else{
            $client_secret=$counrierArr['courier_pin_no'];
        }
        $data = array(
            'client_id' =>$client_id,
            'client_secret' =>$client_secret
        );
// print_r($data);die;
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
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response,true);
        // echo "<pre>"; print_r($response); die;
        return $result['data']['bearer_token'];


    }

    function OrderShipToTorod($ShipArr=array(), $counrierArr=array(),$torod_cc_id=null,$c_id=null,$super_id=null,$warehouse_name=null){
        // print_r($counrierArr);die;
            $token = TorodgetToken($counrierArr);
            // echo $c_id; die;
            $API_URL = $counrierArr['api_url']."order/ship/process"; 

            $param = array(
                'order_id' => $ShipArr['torod_order_id'],
                'warehouse' => $warehouse_name,
                'type' => 'normal',
                'courier_partner_id' => $torod_cc_id,
                'is_own' => '0',
                'is_insurance' => '0'
            );
            // print "<pre>"; print_r($param); die;
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
            CURLOPT_POSTFIELDS => $param,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer '.$token
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $result = json_decode($response,true);
            // echo "<pre>"; print_r($result); die;
            if($result['code'] == 200){

                $successstatus  = "Success";
                $slipNo = $ShipArr['slip_no'];
                $client_awb = $result['data']['tracking_id'];
                $LabelResponse = file_get_contents($result['data']['aws_label']);
                //  echo $LabelResponse; die;
                file_put_contents("assets/all_labels/$slipNo.pdf",$LabelResponse );
                $fastcoolabel = base_url().'assets/all_labels/'.$slipNo.'.pdf';
                $return_array = array("error"=>'false',"data"=>array('client_awb'=>$client_awb,'label'=>$fastcoolabel));
            }else {
                $successstatus  = "Fail";
                $return_array =  array("error"=>"true","msg"=>$result['message']);
            }
            $param_data = json_encode($param);
            $CI =& get_instance();
            $CI->load->model('Ccompany_model');
            $CI->Ccompany_model->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $param_data);
            return $return_array;
    }

?>