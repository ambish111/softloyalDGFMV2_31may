<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class WooCommerce extends CI_Controller {

    public $CI;
    
    private $URL;
                function __construct() {
        //parent::__construct();
        $this->CI = & get_instance();
//        ini_set('display_errors', '1');
//        ini_set('display_startup_errors', '1');
//        error_reporting(E_ALL);
        $this->URL="https://api.diggipacks.com/";
    }

    /**
     * @access public
     * @depends methodName SendRequest
     * @link URL WooCommerce
     */
    public function SendRequest() {
        // echo "..";
        // echo "<pre>";
        // print_r($this->CI->session->userdata);
        //die;
        //  $this->CI->session->userdata('super_id');


        if ($this->CI->router->fetch_class() == 'PickUp' || $this->CI->router->fetch_class() == 'ReturnShipment' || $this->CI->router->fetch_class() == 'Deliver') {
            $tracking_Arr = $this->CI->session->userdata('wc_status_req');
            $super_id = $this->CI->session->userdata('user_details')['super_id'];
           // print_r($tracking_Arr);
            if (!empty($tracking_Arr)) {
                foreach ($tracking_Arr as $val) {
                    //  echo $val['customer_key'];
                    $data = array(
                        'customer_key' => $val['customer_key'],
                        'customer_secret' => $val['customer_secret'],
                        'store_url' => $val['store_url'],
                        'order_id' => $val['order_id'],
                        'status' => $val['status']
                    );
                    $data_note = array(
                        'customer_key' => $val['customer_key'],
                        'customer_secret' => $val['customer_secret'],
                        'store_url' => $val['store_url'],
                        'order_id' => $val['order_id'],
                        'note' => $val['status_des']
                    );
                    $return_log=$this->shipment_tracking_request($data);
                    $this->shipment_tracking_note_request($data_note);
                    $data_log = array(
                        'customer_key' => $val['customer_key'],
                        'customer_secret' => $val['customer_secret'],
                        'store_url' => $val['store_url'],
                        'order_id' => $val['order_id'],
                        'status' => $val['status'],
                        'note' => $val['status_des']
                    );
                    $s_data = json_encode($data_log);
                    $return_log = json_encode($return_log);
                    $api_log = array('booking_id' => $val['order_id'], 'req_log' => $s_data, 'super_id' => $super_id,'return_log'=>$return_log);
                    $this->CI->db->insert('WooCommerce_log', $api_log);
                    // echo $this->CI->db->last_query();
                }
            }
        }

        


         $this->CI->session->unset_userdata('wc_status_req');
    }

    /**
     * @access private
     * @method shipment_tracking_request
     * @param type $data
     * @param type $citc_mode
     * @return type $response
     */
    private function shipment_tracking_request($data = array()) {
        $curl = curl_init();

        $url = $this->URL."WooCommerce_Api/updateStatus";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $s_data = json_decode($response);
        return $s_data;
    }

    private function shipment_tracking_note_request($data = array()) {
        $curl = curl_init();

        $url = $this->URL."WooCommerce_Api/updateNote";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }

}
