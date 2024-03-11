<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook extends CI_Controller {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    function orderRequest() {

        // if ($this->CI->router->fetch_class() == 'Shipment')
        {
            $session_array = $this->CI->session->userdata('webhook_status');
            $super_id = $this->CI->session->userdata('user_details')['super_id'];
            // print_r($session_array);  die;
            if (!empty($session_array)) {
                foreach ($session_array as $data) {

                    $webhook_config = $data['WB_Confing'];
                    $subscribe = $webhook_config['subscribe'];
                    if ($subscribe == 'Y') {
                        $auth = $webhook_config['auth'];
                        $auth_token = $webhook_config['auth_token'];

                        $webhook_array = array(
                            "Timestamp" => $data['datetime'],
                            "DiggipacksStatuscode" => $data['code'],
                            "DiggipacksStatus" => $data['status'],
                            "CarrierName" => $data['cc_name'],
                            "CarriertrackingNumber" => $data['cc_awb'],
                            "CarrierStatus" => $data['cc_status'],
                            "CarrierStatusdetails" => $data['cc_status_details'],
                            "OrderId" => $data['slip_no'],
                            "Orderref_number" => $data['booking_id']
                        );

                        $this->curl_request($webhook_array, $webhook_config['url'], $webhook_config);
                        $s_data = json_encode($webhook_array);
                        $webhook_logs = array('s_data' => $s_data, 'slip_no' => $data['slip_no'], 'super_id' => $super_id, 'cust_id' => $data['cust_id'], 'url' => $webhook_config['url'], 'code' => $data['code']);
                        $this->CI->db->insert('webhook_history', $webhook_logs);
                        //echo $this->CI->db->last_query(); die;
                    }
                }
            }





            /// $slipArr = $session_array['slip_no'];
        }
        $this->CI->session->unset_userdata('webhook_status');
        //return 'okay';
    }

    private function curl_request($data_array = array(), $url = null, $webhook_config) {
        $auth = $webhook_config['auth'];
        $auth_token = $webhook_config['auth_token'];
        $dataJson = json_encode($data_array);
        if ($auth == 'Y' && !empty($auth_token)) {
            $headers = array(
                "Content-type: application/json",
                "Authorization:$auth_token"
            );
        } else {
            $headers = array(
                "Content-type: application/json",
            );
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        return $response = curl_exec($ch);
    }

    private function getCustomerData($cust_id = null) {
        $this->CI->db->where("webhook_settings.customer_id", $cust_id);
        $this->CI->db->select('*');
        $this->CI->db->where("webhook_settings.subscribe", 'Y');
        $this->CI->db->from('webhook_settings');
        $query = $this->CI->db->get();
        // echo $this->CI->db->last_query(); die;
        return $query->row_array();
    }

}

?>
