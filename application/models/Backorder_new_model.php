<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backorder_new_model extends CI_Model {

    public $FULFIL;

    function __construct() {
        parent::__construct();
        //error_reporting(0);
        $this->load->database();
    }

    public function updateShipBatch($data, $super_id) {

        $this->db->where('super_id', $super_id);
        $this->db->update_batch('shipment_fm', $data, 'slip_no');
        // echo $this->db->last_query(); 
    }
    

    public function updateShipBatch_dimension($data, $super_id) {

        if (!empty($data)) {
            $this->db->where('super_id', $super_id);
            $this->db->where_in("slip_no", $data);
            $this->db->update('diamention_fm', array("back_reason" => ""));
            // echo $this->db->last_query(); 
        }
    }

    public function updateDiaBatch($data, $super_id) {

        $this->db->where('super_id', $super_id);
        $this->db->where('slip_no', $data['slip_no']);
        $this->db->where('sku', $data['sku']);

        $this->db->update('diamention_fm', array('back_reason' => $data['back_reason']));
        //    echo '<br>'.$this->db->last_query(); 
        //    if($this->db->affected_rows() >0){
        //     echo '<br> updated'; //add your code here
        //   }else{
        //     echo '<br> NOT updated';  //add your your code here
        //   }
    }

    public function getcheckSku($sku = null, $seller_id = null, $super_id = null) {
        $this->db->where('receive_inventory.seller_id', $seller_id);
        $this->db->where('items_m.super_id', $super_id);
        $this->db->where('receive_inventory.super_id', $super_id);
        $this->db->select('SUM(receive_inventory.qty) as tqty,items_m.sku,items_m.id');
        $this->db->from('items_m');
        $this->db->join('receive_inventory', 'items_m.sku=receive_inventory.sku', 'LEFT');
        $this->db->where('items_m.sku', $sku);
        $query = $this->db->get();
        // echo $this->db->last_query();// die;
        $arrayData = $query->row_array();
        if (!empty($arrayData['id'])) {
            return $arrayData;
        } else {

            $this->db->where('items_m.super_id', $super_id);

            $this->db->select('0 as tqty,items_m.sku,items_m.id');
            $this->db->from('items_m');

            $this->db->where('items_m.sku', $sku);
            $query = $this->db->get();
            // echo $this->db->last_query();// die;
            return $query->row_array();
        }
    }

    public function getcheckStock($sku = null, $seller_id = null) {
        $this->db->where('item_inventory.seller_id', $seller_id);
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('SUM(item_inventory.quantity) as tqty')->from('item_inventory');
        $this->db->where('item_inventory.item_sku', $sku);
        $query = $this->db->get();
        // echo $this->db->last_query();// die; 
        return $query->row_array()['tqty'];
    }

    public function filter_new($data = array()) {
        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }


        if ((!empty($data['from_date'])) && (!empty($data['to_date']))) {
            $this->db->where("DATE(entrydate) BETWEEN '" . date('Y-m-d', strtotime($data['from_date'])) . "' AND '" . date('Y-m-d', strtotime($data['to_date'])) . "' ");
        }
        if (!empty($data['slip_no'])) {
            $this->db->where("slip_no", trim($data['slip_no']));
        }
        if (!empty($data['booking_id'])) {
            $this->db->where("booking_id", trim($data['booking_id']));
        }
        if (!empty($data['mode'])) {

            $this->db->where("mode", $data['mode']);
        }

        if (!empty($data['seller'])) {
            $seller = array_filter($data['seller']);
            $this->db->where_in('shipment_fm.cust_id', $data['seller']);
        }
        if (!empty($data['reciever_name'])) {

            $this->db->where("reciever_name", $data['reciever_name']);
        }
        if (!empty($data['sku'])) {

            $this->db->where("sku", $data['sku']);
        }

        if (!empty($data['destination'])) {
            $city = $data['destination'];
            //$this->db->where('cust_id',$this->session->userdata('user_details_fm')['user_id']);  
            $this->db->where("destination", $city);
        }
        //$this->db->where('destination', $city);  
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*,shipment_fm.created_at, IFNULL(DATEDIFF(created_at, entrydate) , DATEDIFF(CURRENT_TIMESTAMP(), entrydate) ) AS diff_days ')->from('shipment_fm');
        $this->db->where('fulfillment', 'Y');
        $this->db->where('code', 'OG');
        $this->db->where('out_of_stock', '1');
        $this->db->where('deleted', 'N');
        $this->db->order_by('id', 'asc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); die; 
        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->FullfillmentDatacount($data);
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter($data = array()) {

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }


        if ((!empty($data['from_date'])) && (!empty($data['to_date']))) {
            $this->db->where("DATE(entrydate) BETWEEN '" . date('Y-m-d', strtotime($data['from_date'])) . "' AND '" . date('Y-m-d', strtotime($data['to_date'])) . "' ");
        }
        if (!empty($data['slip_no'])) {
            $this->db->where("slip_no", trim($data['slip_no']));
        }
        if (!empty($data['booking_id'])) {
            $this->db->where("booking_id", trim($data['booking_id']));
        }
        if (!empty($data['mode'])) {

            $this->db->where("mode", $data['mode']);
        }

        if (!empty($data['seller'])) {
            $seller = array_filter($data['seller']);
            $this->db->where_in('shipment_fm.cust_id', $data['seller']);
        }
        if (!empty($data['reciever_name'])) {

            $this->db->where("reciever_name", $data['reciever_name']);
        }
        if (!empty($data['sku'])) {

            $this->db->where("sku", $data['sku']);
        }

        if (!empty($data['destination'])) {
            $city = $data['destination'];
            //$this->db->where('cust_id',$this->session->userdata('user_details_fm')['user_id']);  
            $this->db->where("destination", $city);
        }
        //$this->db->where('destination', $city);  
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*')->from('shipment_fm');
        $this->db->where('fulfillment', 'Y');
        $this->db->where('code', 'OG');
        $this->db->where('out_of_stock', '0');
        $this->db->where('deleted', 'N');
        $this->db->order_by('id', 'asc');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); die; 
        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            // $data['count'] = $this->FullfillmentDatacount($data);
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function allOgOrders($super_id) {

            
        $in_cust=$this->GetnewProcessUser($super_id);
       //print_r($in_cust);
        if(!empty($in_cust))
        {
        $this->db->where_in('cust_id', $in_cust);
        $this->db->where('super_id', $super_id);
        $this->db->select('id,slip_no,cust_id')->from('shipment_fm');
        $this->db->where('code', 'OG');
        //  $this->db->where('slip_no', 'DGF19806353301');

        $this->db->where('deleted', 'N');
        $this->db->order_by('id', 'asc');
        // $this->db->limit($limit, $start);
        $query = $this->db->get();
       // echo $this->db->last_query(); die; 
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            // $data['count'] = $this->FullfillmentDatacount($data);
            return $data;
        }
        }
        
        else
        {
            return array();
        }
    }

    public function FullfillmentDatacount($data) {
        if ((!empty($data['from_date'])) && (!empty($data['to_date']))) {
            $this->db->where("entrydate BETWEEN '" . date('Y-m-d', strtotime($data['from_date'])) . "' AND '" . date('Y-m-d', strtotime($data['to_date'])) . "' ");
        }
        if (!empty($data['slip_no'])) {
            $this->db->where("slip_no", $data['slip_no']);
        }
        if (!empty($data['booking_id'])) {
            $this->db->where("booking_id", $data['booking_id']);
        }
        if (!empty($data['main_status'])) {

            $this->db->where("delivered", $data['main_status']);
        }
        if (!empty($data['seller'])) {
            $seller = array_filter($data['seller']);
            $this->db->where_in('shipment_fm.cust_id', $data['seller']);
        }
        if (!empty($data['destination'])) {
            $city = $data['destination'];
            $this->db->where('destination', $city);
            //$this->db->where("destination",$data['city']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(id) as t_count')->from('shipment_fm');
        $this->db->where('fulfillment', 'Y');
        $this->db->where('code', 'OG');
        $this->db->where('deleted', 'N');
        $this->db->where('out_of_stock', '1');
        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['t_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function UpdateShipment($stock_update = array(), $out_of_stock_slip = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('slip_no', $out_of_stock_slip);
        $this->db->update("shipment_fm", $stock_update);
    }

    private function GetnewProcessUser($super_id=null) {
        //echo $this->session->userdata('user_details')['super_id']; die;
        $this->db->where('new_process_customer.super_id', $super_id);
        $this->db->select('cust_id')->from('new_process_customer');
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
        $a= $query->result_array();
        return array_column($a, 'cust_id');
        }
        else{
           return array();
        }
    }

}
