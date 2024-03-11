<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Webhook {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    
    function Request() {
        $webhook_array = array(
            "Timestamp" => date("Y-m-d H:i:s"),
            "DiggipacksStatuscode" => $mapingcode,
            "DiggipacksStatus" => $activity,
            "CarrierName" => $company,
            "CarriertrackingNumber" => $client_awb,
            "CarrierStatus" => $arrayDatacode,
            "CarrierStatusdetails" => $details,
            "OrderId" => $awb,
            "Orderref_nunber" => $orderref_nunber
        );
        return 'okay';
    }   

    private function webhook_log() {
        $s_data = json_encode($request_array);
        $webhook_logs = array('s_data' => $s_data, 'slip_no' => $row['slip_no'], 'super_id' => $this->CI->session->userdata('super_id'), 'cust_id' => $row['cust_id'], 'url' => $url);
        $this->CI->db->insert('webbook_history', $webhook_logs);
    }

    private function curl_request($data_array = array(), $url = null) {
        $dataJson = json_encode($data_array);
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
    }

}
