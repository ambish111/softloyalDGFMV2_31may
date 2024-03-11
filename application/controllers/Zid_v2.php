<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zid_v2 extends CI_Controller {

    // public $cust_auth;
    public function __construct() {
        parent::__construct();
        $this->load->helper('utility_helper');
        $this->load->helper('zid_helper');
        $this->load->model('Zid_model');
//        ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
    }

    public function getOrder($super_id = null) {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataJson = json_encode($_POST);
        if (!file_exists('zidLogNewV2/' . date('Y-m-d') . '/' . $super_id)) {
            mkdir('zidLogNewV2/' . date('Y-m-d') . '/' . $super_id, 0777, true);
        }
        if (!file_exists('zidLogNewV2/zidlock/' . date('Y-m-d') . '/' . $super_id)) {
            mkdir('zidLogNewV2/zidlock/' . date('Y-m-d') . '/' . $super_id, 0777, true);
        }
        //==================log write start========

        $fr = fopen('zidLogNewV2/' . date('Y-m-d') . '/' . $super_id . '/' . $_POST['id'] . '-' . date('ymdhis') . ' .json', 'w+');
        fwrite($fr, $dataJson);

        fclose($fr);

        ignore_user_abort();
        $file = fopen("zidLogNewV2/zidlock/" . date('Y-m-d') . "/" . $super_id . "/zidcron" . $_POST['id'] . ".lock", "w+");
        ;

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            echo "Unable to obtain lock, the previous process is still going on.";
        } else {

            //Lock obtained, start doing some work now
            // sleep(10);//sleep for 10 seconds
            if ($_POST['order_status']['code'] == 'cancelled') {
                $customers = $this->Zid_model->fetch_zid_customers_new($_POST['store_id'], $super_id);
                if (!empty($customers)) {
                    $result = $this->zidOrders_cancel($customers['uniqueid'], $_POST);
                }
            }
            // if ($_POST['order_status']['code'] == 'ready' || ) {
                $result = $this->zidOrders($super_id, $_POST);
            // }

            //echo "Work completed!";
            // echo $result;
            // release lock
            flock($file, LOCK_UN);
        }

        fclose($file);
    }

    private function zidOrders_cancel($uniqueid = null, $postData = array()) {
        $headers = array(
            "Content-type: application/json",
        );
        $dataJson = json_encode($postData);
        $url = "https://fm.diggipacks.com/Zid_new/getOrder/$uniqueid";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

        return $response = curl_exec($ch);
    }

    private function zidOrders($super_id = null, $postData = array()) {
        // echo $super_id;die;
        // print "<pre>"; print_r($postData);die;
        $Order = $postData;
        $customers = $this->Zid_model->fetch_zid_customers_new($postData['store_id'], $super_id);
       // print_r($customers); die;
        $micro_fm_order = $customers['micro_fm_order'];
        $manager_token = $customers['manager_token'];
        $zid_authorization = $customers['zid_authorization'];

        $order_status = $customers['zid_status'];
        // if(empty($order_status))
        // {
        //     $order_status="ready";
        // }

        // if($order_status==$Order['order_status']['code'])
        // {


            if ($zid_authorization != NULL) {
                $Bearer = $zid_authorization;
            } else {
                $Bearer = site_configTableSuper_id('zid_provider_token', $customers['super_id']);
            }
    
            // $super_id = $customers['super_id'];
            $secKey = $customers['secret_key'];
            $customerId = $customers['uniqueid'];
            $formate = "json";
            $method = "createOrder";
            $signMethod = "md5";
            $product = array();
    
            $booking_id = $Order['id'];
            $this->db->query("insert into zip_log_temp(r_data,booking_id)values('" . json_encode($Order) . "','" . $booking_id . "')");

            // print "<pre>"; print_r($customers);die;
            if (!empty($customers)) {
                if ($customers['access_fm'] == 'Y') {
                    $check_booking_id = exist_booking_id($booking_id, $customers['id']);
                }
    
                if ($customers['access_lm'] == 'Y') {
                    $check_booking_id = $this->Zid_model->existLmBookingId($booking_id, $customers['id']);
                }
    
                if (empty($check_booking_id)) {
    
    
                    $result1['order'] = $Order;
                    // echo $result1['order']['order_status']['code']."rrr"; 
    
                    if ($result1['order']['order_status']['code'] == 'new' || $result1['order']['order_status']['code'] == 'ready') {
    
                        //echo "ss";
    
                        $weight = 0;
                        foreach ($result1['order']['products'] as $ITEMs) {
                            $weight = $weight + $ITEMs['weight']['value'];
                        }
                        $product = array();
                        $sku_all_names = array();
                        foreach ($result1['order']['products'] as $products) {
    
                            $product[] = array(
                                "sku" => $products['sku'],
                                "description" => '',
                                "cod" => $products['total'],
                                "piece" => $products['quantity'],
                                "weight" => $products['weight']['value'],
                            );
                            //$skunames_quantity =$products['sku'] . "/ Qty:" . $products['quantity'];
                            $skunames_quantity = "Qty:" . $products['quantity'] . " / " . $products['sku'];
    
                            array_push($sku_all_names, $skunames_quantity);
                        }
    
                        $sku_all_names = implode(",", $sku_all_names);
    
                        if ($result1['order']['has_different_consignee'] == true) {
    
    
                            $recName = $result1['order']['consignee']['name'];
                            $recMobile = $result1['order']['consignee']['mobile'];
                            $recEmail = $result1['order']['consignee']['email'];
                        } else {
                            $recName = $result1['order']['customer']['name'];
                            $recMobile = $result1['order']['customer']['mobile'];
                            $recEmail = $result1['order']['customer']['email'];
                        }
                        $label_box = $Order['packages_count'];
                        $param = array(
                            "sender_name" => $customers['name'],
                            "sender_email" => $customers['email'],
                            "origin" => $this->Zid_model->getdestinationfieldshow($customers['city'], 'city', $customers['super_id']),
                            "sender_phone" => $customers['phone'],
                            "sender_address" => $customers['address'],
                            "receiver_name" => $recName,
                            "receiver_phone" => $recMobile,
                            "receiver_email" => $recEmail,
                            "description" => $sku_all_names,
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
                            "street_number" => isset($result1['order']['shipping']['address']['street']) ? $result1['order']['shipping']['address']['street'] : "",
                            "area_name" => isset($result1['order']['shipping']['address']['district']) ? $result1['order']['shipping']['address']['district'] : "",
                            "order_from" => "zid",
                            "comment" => $result1['order']['customer']['note'],
                            'label_box' => $label_box
                        );
    
                        $sign = create_sign($param, $secKey, $customerId, $formate, $method, $signMethod);
    
                        $data_array = array(
                            "sign" => $sign,
                            "format" => $formate,
                            "signMethod" => $signMethod,
                            "param" => $param,
                            "method" => $method,
                            "customerId" => $customerId,
                        );
                        // print_r($data_array); die;
                        $dataJson = json_encode($data_array);
                        $customers['zid_access'];
    
                        if ($customers['zid_access'] == 'FM' && $micro_fm_order == 'N') {
    
    
    
                            $url = "https://api.diggipacks.com/API/createOrder";
    
                            //$url = "http://apilm.com/API/createOrder";
                           echo $resps = $this->sendRequest($url, $dataJson);
                        } else if ($customers['zid_access'] == 'FM' && $micro_fm_order == 'Y') {
    
                            // echo"sss";
    
                            $url = "https://api.diggipacks.com/API_W/createOrder";
    
                            //$url = "http://apilm.com/API/createOrder";
                           echo $resps = $this->sendRequest($url, $dataJson);
                        }
    
                        if ($customers['zid_access'] == 'LM') {
    
                            $url = "https://api.diggipacks.com/API/createLmOrder";
                          echo  $resps = $this->sendRequest($url, $dataJson);
                            $responseN = json_decode($resps, true);
                            if (!empty($responseN['awb_no']) && $responseN['autoResponse']['status'] == 1) {
                                $new_awb_number = $responseN['awb_no'];
    
                                file_get_contents('https://api.diggipacks.com/API/Printnew/' . $new_awb_number);
                                $pdf_label = "https://api.diggipacks.com/printawb/" . $new_awb_number . ".pdf";
                                $tracking_link = "https://tracks.diggipacks.com/result_detail/" . $new_awb_number;
    
                                // $trackingurl=makeTrackUrl($check_booking_id['cc_id'],$check_booking_id['frwd_company_awb']);
    
                                echo '<br>' . $statusValue = strtolower($customers['zid_status']);
    
                                $this->updateZidStatus($booking_id, $manager_token, $statusValue, $responseN['awb_no'], $pdf_label, $tracking_link, $customers['id'], $Bearer, $customers['super_id']);
                            }
                        }
    
                        //print_r($resps); exit;
                        $data = array("Success" => "Order Created Sucessfull");
                        return json_encode($data);
                    } else {
                        $data = array("error" => "Something Went Wrong !");
                        return json_encode($data);
                    }
                }
            }




        // }else{
        //     $data = array("status"=>"fail","message" => "invalid status");
        //     return json_encode($data);
        // }

        
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

        return $response = curl_exec($ch);

        // echo '<pre>';
        // echo $response;
    }

    function updateZidStatus($orderID = null, $token = null, $status = null, $slip_no = null, $label = null, $trackingurl = null, $cust_id = null, $Bearer = null, $super_id = null) {
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
                'Authorization: Bearer ' . $Bearer,
                'X-MANAGER-TOKEN: ' . $token,
                'Accept-Language: en',
            ),
        ));

        echo $response = curl_exec($curl);

        curl_close($curl);

        $datalog = array(
            'slip_no' => $slip_no,
            'status_id' => $status,
            'note' => $trackingurl,
            'log' => $response,
            'cust_id' => $cust_id,
            'booking_id' => $orderID,
            'system_name' => 'zid- from controller App',
            'super_id' => $super_id
        );

        $this->db->insert('salla_out_log', $datalog);
    }

}

?>