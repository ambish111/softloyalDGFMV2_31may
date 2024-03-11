<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class StockUpdate extends CI_Controller {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    function orderRequest() {

         
        
        if ($this->CI->router->fetch_class() == 'ItemInventory' || $this->CI->router->fetch_class() == 'Mergestock' || $this->CI->router->fetch_class() == 'Manifest' || ($this->CI->router->fetch_class() == 'ItemInventory' && $this->CI->router->fetch_method() == 'add') || ($this->CI->router->fetch_class() == 'Excel_export' && $this->CI->router->fetch_method() == 'add_ItemInventory_bulk') || ($this->CI->router->fetch_class() == 'Manifest' && $this->CI->router->fetch_method() == 'GetSaveInventoryManifest_bk') || ($this->CI->router->fetch_class() == 'Shipment' && $this->CI->router->fetch_method() == 'CreateGenratedOrderCheck') || ($this->CI->router->fetch_class() == 'Shipment' && $this->CI->router->fetch_method() == 'filters')) 
        {
           // echo "mm";
            $session_array = $this->CI->session->userdata('webhook_stock_arr');
            $super_id = $this->CI->session->userdata('user_details')['super_id'];
            // print_r($session_array);  die;
            if (!empty($session_array)) {   
                foreach ($session_array as $data) {

                    $webhook_config = $data['WB_Confing'];
                    $subscribe = $webhook_config['subscribe'];
                    if ($subscribe == 'Y') {
                        $st_dataArr = $this->stock_data_webhook($data['cust_id'], $data['sku'], $super_id);
                        $webhook_array = array(
                            "Timestamp" => $data['datetime'],
                            "sku" => $data['sku_name'],
                            "qty" => $st_dataArr['quantity'],
                        );

                        $this->curl_request($webhook_array, $webhook_config['url'], $webhook_config);
                        $s_data = json_encode($webhook_array);
                        $webhook_logs = array('s_data' => $s_data, 'order_from' => $data['order_from'], 'super_id' => $super_id, 'cust_id' => $data['cust_id'], 'url' => $webhook_config['url'],'comment'=> isset($data['comment'])?$data['comment']:"");
                        $this->CI->db->insert('webhook_history_in', $webhook_logs);
                        //echo $this->CI->db->last_query(); die;
                    }
                }
            }



            /// $slipArr = $session_array['slip_no'];
        }
        $this->CI->session->unset_userdata('webhook_stock_arr');
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
        $this->CI->db->where("webhook_settings_inventory.customer_id", $cust_id);
        $this->CI->db->select('*');
        $this->CI->db->where("webhook_settings_inventory.subscribe", 'Y');
        $this->CI->db->from('webhook_settings_inventory');
        $query = $this->CI->db->get();
        // echo $this->CI->db->last_query(); die;
        return $query->row_array();
    }

    private function stock_data_webhook($cust_id = null, $item_sku = null, $super_id = null) {
        $this->CI->db->where("item_inventory.super_id", $super_id);
        $this->CI->db->where("item_inventory.seller_id", $cust_id);
        $this->CI->db->where("item_inventory.item_sku", $item_sku);
        $this->CI->db->select('SUM(quantity) as quantity');
        $this->CI->db->from('item_inventory');
        $query = $this->CI->db->get();
        // echo $this->CI->db->last_query(); die;
        return $query->row_array();
    }

}

?>
