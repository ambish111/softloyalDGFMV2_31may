<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// error_reporting(-1);
// 		ini_set('display_errors', 1);
class Zid extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('utility_helper');
       
        $this->load->helper('zid_helper');
        $this->load->model('Zid_model');
    }
    public function getOrder($uniqueid=null,$shipId=null) { 
       
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataJson = json_encode($_POST);
        //==================log write start========
        
        if (!file_exists('zidLogNew/'.date('Y-m-d').'/'.$uniqueid)) {
            mkdir('zidLogNew/'.date('Y-m-d').'/'.$uniqueid, 0777, true);
           
        }
        if (!file_exists('zidLogNew/zidlock/'.date('Y-m-d').'/'.$uniqueid)) {
            mkdir('zidLogNew/zidlock/'.date('Y-m-d').'/'.$uniqueid, 0777, true);
           
        }
         //==================log write start========
         
         $fr = fopen('zidLogNew/'.date('Y-m-d').'/'.$uniqueid.'/'. $_POST['id'].'-'.date('ymdhis') .' .json', 'w+');
         fwrite($fr, $dataJson);
          
         fclose($fr);
 
         ignore_user_abort();
         $file = fopen("zidLogNew/zidlock/".date('Y-m-d')."/".$uniqueid."/zidcron".$_POST['id'].".lock", "w+");;

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file,LOCK_EX|LOCK_NB))
        {
            echo "Unable to obtain lock, the previous process is still going on."; 
        }
        else
        {
            //Lock obtained, start doing some work now
           // sleep(10);//sleep for 10 seconds
           $return_req=$this->zidOrders($uniqueid,$_POST,$shipId);
            echo json_encode($return_req);
            exit;
            echo "Work completed!";
             // release lock
            flock($file,LOCK_UN);
        }

        fclose($file);
    }


        private function zidOrders($uniqueid,$postData,$shipId=null) {
            $Order=$postData;
            $customers = $this->Zid_model->fetch_zid_customers($uniqueid);
           
           // $customers['zid_status']='Ready';
        //  print "<pre>"; print_r($customers); exit;
            $micro_fm_order=$customers['micro_fm_order'];
            $deliveryOption_shipping_method_id=deliveryOption_shipping_method_id($customers['id']); 
            // print "<pre>"; print_r($deliveryOption_shipping_method_id);die;
             $deliveryOption=deliveryOption_new($customers['id']); 
              //  print_r($deliveryOption); 
            $manager_token = $customers['manager_token'];
            $Bearer = site_configTableSuper_id('zid_provider_token',$customers['super_id']); 
              
                  
                    $secKey = $customers['secret_key'];
                    $customerId = $customers['uniqueid'];
                    $formate = "json";
                    $method = "createOrder";
                    $signMethod = "md5";
                    $product = array();
                 
                  
                 $booking_id = $Order['id'];
                   
                    $this->db->query("insert into zip_log_temp(r_data,booking_id)values('".json_encode($Order)."','".$booking_id."')"); 
                   
    
                    if ($customers['access_fm'] == 'Y') {
                        
                        $check_booking_id = exist_booking_id($booking_id, $customers['id']);
    
                       // print_r( $check_booking_id);
                    }
    
                    if ($customers['access_lm'] == 'Y') {
                        $check_booking_id = $this->Zid_model->existLmBookingId($booking_id, $customers['id']);
                       // print_r( $check_booking_id);
                    }
    
                    if (!empty($check_booking_id)) {
    
                      
                       
                        if($check_booking_id['code']=='POD')
                        {
                           
                        // if(!empty($check_booking_id['frwd_company_label']))
                        // $lable=$check_booking_id['frwd_company_label'];
                        // else
                        // $lable='https://api.fastcoo-tech.com/API/print/'.$check_booking_id['slip_no'];
    
    
                        // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
                      
                        
                        // updateZidStatus($booking_id, $manager_token, 'delivered', $check_booking_id['slip_no'], $lable, $trackingurl);
                        }
                        elseif($check_booking_id['code']=='B')
                        {
                        //     $lable='https://api.fastcoo-tech.com/API/print/'.$check_booking_id['slip_no'];
    
                        //     $trackingurl=TRACKURL_LM.$check_booking_id['awb_no'];
       
                        //    // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
                         
                           
                        //     updateZidStatus($booking_id, $manager_token, 'preparing', $check_booking_id['awb_no'], $lable, $trackingurl);
           
                        }
    
                        echo $booking_id . ' Exist<br>';
                    } else {
                        
                        $addShipValid=false;
                       // echo 'xxxxx'; exit;
                        $result1['order'] = $Order; 
                    // if(!empty($shipId))
                    // {
                    //    if($shipId==$result1['order']['shipping']['method']['id']) 
                    //    $addShipValid=true;
                    // }
                    // else
                    // {

                       // echo strpos(trim( strtoupper ($result1['order']['shipping']['method']['name'])),'DIGGIPACKS');
                       // print_r($deliveryOption);
                        if(in_array($result1['order']['shipping']['method']['id'],$deliveryOption)|| in_array($result1['order']['shipping']['method']['id'],$deliveryOption_shipping_method_id)  || strpos(strtoupper($result1['order']['shipping']['method']['name']),'DIGGIPACK')!==false  || strpos(strtoupper($result1['order']['shipping']['method']['name']),'DIGGIPACKS')!==false  )
                        {

                  
                        $addShipValid=true;
                    }
                    //}
                    // echo var_dump($addShipValid);die;
                    // echo   $addShipValid .'//'.$result1['order']['order_status']['code'].' =='. $customers['zid_status'];die;
                    if (($result1['order']['order_status']['code'] == $customers['zid_status'] || $result1['order']['order_status']['code'] =='ready')  &&  $addShipValid==true ) 
                    {
                   
                        $weight = 0;
                        foreach ($result1['order']['products'] as $ITEMs) {
                            $weight = $weight + $ITEMs['weight']['value'];
                        }
                        $product = array();

                        foreach ($result1['order']['products'] as $products) {

                            $product[] = array(
                                "sku" => $products['sku'],
                                "description" => '',
                                "cod" => $products['total'],
                                "piece" => $products['quantity'],
                                "wieght" => $products['weight']['value'],
                            );
                        }
                        if($result1['order']['has_different_consignee']==true )
                        {

                           
                            $recName=$result1['order']['consignee']['name'];
                            $recMobile=$result1['order']['consignee']['mobile'];
                            $recEmail=$result1['order']['consignee']['email'];
                        }
                        else
                        {
                            $recName=$result1['order']['customer']['name'];
                            $recMobile=$result1['order']['customer']['mobile'];
                            $recEmail=$result1['order']['customer']['email'];
                           
                        }
                         if ($customers['zid_access'] == 'LM') {
                            $param = array(
                            "sender_name" => $customers['company'],
                            "sender_email" => $customers['email'],
                            "origin" => $this->Zid_model->getdestinationfieldshow($customers['city'], 'city', $customers['super_id']),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $recName,
                             "pieces" => $result1['order']['packages_count'],
                            "receiver_phone" => $recMobile,
                            "receiver_email" => $recEmail,
                            "description" => $result1['message']['description'],
                            "destination" => $result1['order']['shipping']['address']['city']['name'],
                            "BookingMode" => ($result1['order']['payment']['method']['code'] == 'zid_cod' ? 'COD' : 'CC'),
                            "receiver_address" => $result1['order']['shipping']['address']['formatted_address'] . ' ' . $result1['order']['shipping']['address']['street'] . ' ' . $result1['order']['shipping']['address']['district'],
                            "reference_id" => $booking_id,
                            "codValue" => $result1['order']['order_total'],
                            "productType" => 'parcel',
                            "service" => 3,
                            "weight" => $weight,
                            "skudetails" => $product,
                            "zid_store_id" => $result1['order']['store_id'],
                             "street_number" => isset($result1['order']['shipping']['address']['street'])?$result1['order']['shipping']['address']['street']:"",
                             "area_name" => isset($result1['order']['shipping']['address']['district'])?$result1['order']['shipping']['address']['district']:"",
                            "order_from" => "zid"
                        );      
                         }
                         else
                         {
                            
                        $param = array(
                            "sender_name" => $customers['company'],
                            "sender_email" => $customers['email'],
                            "origin" => $this->Zid_model->getdestinationfieldshow($customers['city'], 'city', $customers['super_id']),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $recName,
                            // "pieces" => $result1['order']['packages_count'],
                            "receiver_phone" => $recMobile,
                            "receiver_email" => $recEmail,
                            "description" => $result1['message']['description'],
                            "destination" => $result1['order']['shipping']['address']['city']['name'],
                            "BookingMode" => ($result1['order']['payment']['method']['code'] == 'zid_cod' ? 'COD' : 'CC'),
                            "receiver_address" => $result1['order']['shipping']['address']['formatted_address'] . ' ' . $result1['order']['shipping']['address']['street'] . ' ' . $result1['order']['shipping']['address']['district'],
                            "reference_id" => $booking_id,
                            "codValue" => $result1['order']['order_total'],
                            "productType" => 'parcel',
                            "service" => 3,
                            "weight" => $weight,
                            "skudetails" => $product,
                            "zid_store_id" => $result1['order']['store_id'],
                             "street_number" => isset($result1['order']['shipping']['address']['street'])?$result1['order']['shipping']['address']['street']:"",
                             "area_name" => isset($result1['order']['shipping']['address']['district'])?$result1['order']['shipping']['address']['district']:"",
                            "order_from" => "zid"
                        );    
                         }


                        $sign = create_sign($param, $secKey, $customerId, $formate, $method, $signMethod);

                        $data_array = array(
                            "sign" => $sign,
                            "format" => $formate,
                            "signMethod" => $signMethod,
                            "param" => $param,
                            "method" => $method,
                            "customerId" => $customerId,
                        );
                        // print "<pre>"; print_r($data_array);die;
                        $dataJson = json_encode($data_array); 
                    
                      $customers['zid_access']; 
                        if ($customers['zid_access'] == 'FM' && $micro_fm_order=='N') {

                        
                           
                                 $url = "https://api.diggipacks.com/API/createOrder";
                        

                            //$url = "http://apilm.com/API/createOrder";
                            $resps = $this->sendRequest($url, $dataJson);
                        }
                        else if ($customers['zid_access'] == 'FM' && $micro_fm_order=='Y') {

                       // echo"sss";
                                $url = "https://api.diggipacks.com/API/createOrder";
                                //  $url = "https://api.diggipacks.com/API_W/createOrder";
                        

                            //$url = "http://apilm.com/API/createOrder";
                            $resps = $this->sendRequest($url, $dataJson);
                        }
                        //echo " <br><br><br><br>resp = <pre>"; echo $resps ;  exit;

                     


                        if ($customers['zid_access'] == 'LM') {
                        
                            $url = "https://api.diggipacks.com/API/createLmOrder";
                            $resps =  $this->sendRequest($url, $dataJson);
                          $responseN=json_decode($resps,true);
                            if(!empty($responseN['awb_no']) &&  $responseN['autoResponse']['status']==1) 
                            {
                                $new_awb_number=$responseN['awb_no'];

                                file_get_contents('https://api.diggipacks.com/API/Printnew/'.$new_awb_number);
                                $pdf_label="https://api.diggipacks.com/printawb/".$new_awb_number.".pdf";
                                $tracking_link="https://tracks.diggipacks.com/result_detail/".$new_awb_number;

                            // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
                           
                            echo '<br>'.    $statusValue=strtolower($customers['zid_status']);

                            $this->updateZidStatus($booking_id, $manager_token,$statusValue, $responseN['awb_no'], $pdf_label, $tracking_link, $customers['id'],$Bearer,$customers['super_id']);
                            }
                        }
                       
                        return $resps;
                    }
                
            
        }
    }

    private function sendRequest($url, $dataJson) {


        $headers = array(
            "Content-type: application/json",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        $response = curl_exec($ch);

        //echo '<pre>';
        return $response;
    }
     function updateZidStatus($orderID = null, $token = null, $status = null, $slip_no = null, $label = null, $trackingurl = null,$cust_id=null,$Bearer=null,$super_id=null) {
        //echo 'werwqerwqrewqerwqrwqerqew'.$token.'testerewrwrwerewrwererweer';
        $url = 'https://api.zid.sa/v1/managers/store/orders/' . $orderID . '/change-order-status';
        $curl = curl_init();
      
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('order_status' => $status, 'waybill_url' => $label, 'tracking_url' => $trackingurl, 'tracking_number' => $slip_no),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$Bearer,
                'X-MANAGER-TOKEN: ' . $token,
               'Accept-Language: en',
            ),
        ));
    
       echo $response = curl_exec($curl);
    
        curl_close($curl);
      
       
        $datalog = array(
            'slip_no' =>  $slip_no,
            'status_id' =>  $status,
            'note' =>  $trackingurl,
            'log'=> $response ,
            'cust_id'=>  $cust_id,
            'booking_id'=> $orderID,
            'system_name'=> 'zid- from controller',
            'super_id'=>  $super_id
        );
        
        
        
        
       
        
        $this->db->insert('salla_out_log', $datalog);
    }



}

?>