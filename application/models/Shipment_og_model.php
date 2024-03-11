<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment_og_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function pickListFilterNotPicked($slip_no = null) {
        if (!empty($slip_no)) {

            $this->db->where('pickuplist_tbl.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('pickuplist_tbl.`id`, pickuplist_tbl.`pickupId`, pickuplist_tbl.`assigned_to`, pickuplist_tbl.`slip_no`, pickuplist_tbl.`origin`, pickuplist_tbl.`destination`, pickuplist_tbl.`reciever_name`, pickuplist_tbl.`reciever_address`, pickuplist_tbl.`reciever_phone`, pickuplist_tbl.`sku`, pickuplist_tbl.`pickup_status`, pickuplist_tbl.`piece`, pickuplist_tbl.`entrydate`, pickuplist_tbl.`pickupDate`,pickuplist_tbl.sender_name,pickuplist_tbl.print_url,pickuplist_tbl.weight,shipment_fm.pieces,shipment_fm.cust_id');
            $this->db->from('pickuplist_tbl');
            $this->db->join('shipment_fm', 'shipment_fm.slip_no = pickuplist_tbl.slip_no');

            $this->db->where('pickuplist_tbl.slip_no', $slip_no);
            $this->db->where('shipment_fm.code', 'AP');
            $this->db->where('pickuplist_tbl.deleted', 'N');

            $this->db->where("pickuplist_tbl.assigned_to>0");

            $this->db->where('pickuplist_tbl.picked_status', 'N');
            $this->db->where('pickuplist_tbl.pickup_status', 'N');
             $this->db->group_by('pickuplist_tbl.slip_no');
            $this->db->order_by('pickuplist_tbl.id', 'ASC');

            $query = $this->db->get();
            //  echo $this->db->last_query();exit;

            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = 1;
                return $data;
            } else {
                $data['result'] = '';
                $data['count'] = 0;
                return $data;
            }
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetskuDetailspack($slip_no) {
        if (!empty($slip_no)) {

            $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('diamention_fm.sku,diamention_fm.piece,diamention_fm.cod,items_m.item_path,items_m.id as item_sku,items_m.sku_size,diamention_fm.cust_id,items_m.expire_block');
            $this->db->from('diamention_fm');
            $this->db->join('items_m', 'items_m.sku = diamention_fm.sku');
            $this->db->where('diamention_fm.slip_no', $slip_no);
            $query = $this->db->get();
            /// echo $this->db->last_query(); die;
            return $query->result_array();
        } else {
            return array();
        }


        //$this->db->order_by('shipment.id','ASC');
    }

    public function GetcheckLocation($data = array()) {
        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('item_inventory_new.seller_id', $data['cust_id']);
            $this->db->where('item_inventory_new.item_sku', $data['item_sku']);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where('item_inventory_new.stock_location', trim($data['stock_location']));
            $query = $this->db->get();
            // echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function GetcheckLocation_new($data = array()) {
        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('item_inventory_new.seller_id', 0);
            $this->db->where('item_inventory_new.item_sku', 0);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where('item_inventory_new.stock_location', trim($data['stock_location']));
            $query = $this->db->get();
            // echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function GetupdateInventory($data = array(), $data_w = array()) {
        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("item_inventory_new", $data, $data_w);
    }

    public function UpdatediamantionArr($data = array(), $data_w = array()) {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("diamention_fm", $data, $data_w);
    }

    public function locationDetailsQry($data = array()) {

        return $this->db->insert("locationDetails", $data);
    }

    public function LocationdetailsUpdates($data = array()) {

        return $this->db->update("locationDetails", array('deleted' => 'Y'), $data);
    }

    public function GetUpdateShipment($data = array(), $slip_no = null) {


        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.slip_no', $slip_no);
        $this->db->where('shipment_fm.code', 'OC');
        return $this->db->update("shipment_fm", $data);
    }

    public function GetUpdateShipment_rtf($data = array(), $slip_no = null) {


        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.slip_no', $slip_no);
        //  $this->db->where('shipment_fm.code', 'OC');
         $this->db->update("shipment_fm", $data);
         return $this->db->last_query();
    }

    public function GetupdatePickupList($data = array(), $data_w = array()) {
        $this->db->where('pickuplist_tbl.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("pickuplist_tbl", $data, $data_w);
    }

    public function orderopencheckCheck($data = array()) {

        if (!empty($data['slip_no'])) {
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('shipment_fm.slip_no', $data['slip_no']);
            $this->db->select('id,slip_no,code,delivered');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.open_stock', 1);
            $this->db->where('shipment_fm.code', 'OC');
            $this->db->where('shipment_fm.delivered', 1);
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function orderopencheckCheck_without_stock($data = array()) {

        if (!empty($data['slip_no'])) {
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('shipment_fm.slip_no', $data['slip_no']);
            $this->db->select('id,slip_no,code,delivered');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.open_stock', 0);
            $this->db->where('shipment_fm.code', 'OC');
            $this->db->where('shipment_fm.delivered', 1);
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function updateStatus($data = array()) {

        return $this->db->insert("status_fm", $data);
    }

    public function GetCheckReturnFulfilstatus($awb = null) {
        if (!empty($awb)) {
            $this->db->select('slip_no,code,delivered,cust_id,booking_id,cust_id,frwd_company_id,frwd_company_awb,frwd_company_label');
            $this->db->from('shipment_fm');

            $this->db->where('slip_no', trim($awb));

            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('deleted', 'N');
            
           
            $this->db->where_not_in('code', array('OG', 'OC', 'RTC', 'C', 'POD', 'PK', 'AP', 'PG'));
            $this->db->order_by('slip_no', 'ASC');
            // $this->db->limit($limit, $start);
            $query = $this->db->get();
            // echo $this->db->last_query();exit;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = 1;
                return $data;
                //  $page_no.$this->db->last_query();
            } else {
                $data['result'] = '';
                $data['count'] = 0;
                return $data;
            }
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetskuDetailsRTF($slip_no = null) {

        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('diamention_fm.sku,diamention_fm.piece,diamention_fm.cod,items_m.item_path,items_m.sku_size,items_m.id as item_sku,diamention_fm.cust_id');
        $this->db->from('diamention_fm');
        $this->db->join('items_m', 'items_m.sku = diamention_fm.sku');
        $this->db->where('diamention_fm.slip_no', trim($slip_no));
        $query = $this->db->get();
        /// echo $this->db->last_query(); die;
        return $query->result_array();

        //$this->db->order_by('shipment.id','ASC');
    }

    public function GetcheckInventoryLocation($data = array()) {


        if ($data['item_sku'] > 0 && $data['cust_id'] > 0) {

            $expire_block = getalldataitemtablesBySku($data['sku'], 'expire_block');
            if ($expire_block == 'Y') {
                $current_date = date("Y-m-d");
                $conditionCheck = " expiry='N' and expity_date>='$current_date'";
                $this->db->where($conditionCheck);
            }
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where('item_inventory_new.item_sku', $data['item_sku']);
            $this->db->where('item_inventory_new.seller_id', $data['cust_id']);
            $this->db->where("item_inventory_new.quantity>=" . $data['piece']);
            $this->db->order_by("quantity", "asc");
            $this->db->limit(1);
            $query = $this->db->get();
            // echo $this->db->last_query(); die;

            if ($query->num_rows() > 0) {
                return $query->result_array();
            } else {

                if ($expire_block == 'Y') {
                    $current_date = date("Y-m-d");
                    $conditionCheck = " expiry='N' and expity_date>='$current_date'";
                    $this->db->where($conditionCheck);
                }
                $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
                $this->db->select('*');
                $this->db->from('item_inventory_new');
                $this->db->where('item_inventory_new.item_sku', $data['item_sku']);
                $this->db->where('item_inventory_new.seller_id', $data['cust_id']);
                $this->db->where("item_inventory_new.quantity>0");
                $this->db->order_by("quantity", "asc");
                $query2 = $this->db->get();
                return $query2->result_array();
            }
        } else {
            return array();
        }



        //$this->db->order_by('shipment.id','ASC');
    }

    public function Getupdatedamage_inventory($data = array()) {
        return $this->db->insert('damage_history_new', $data);
    }

    
    public function GetcheckLocation_rtf($data = array()) {
        if (!empty($data['item_sku'])) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('item_inventory_new.seller_id', $data['cust_id']);
            $this->db->where('item_inventory_new.item_sku', $data['item_sku']);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where('item_inventory_new.stock_location!=', '');
            $query = $this->db->get();
            // echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function GetcheckLocation_rtf_new() {

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('item_inventory_new.seller_id', 0);
        $this->db->where('item_inventory_new.item_sku', 0);
        $this->db->select('stock_location');
        $this->db->from('item_inventory_new');
        $this->db->where('item_inventory_new.stock_location!=', '');
        $query = $this->db->get();
        // echo $this->db->last_query(); die;
        return $query->row_array()['stock_location'];
    }

    public function GetcheckLocation_open($stock_location) {

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('item_inventory_new.seller_id', 0);
        $this->db->where('item_inventory_new.item_sku', 0);
        $this->db->select('stock_location');
        $this->db->from('item_inventory_new');
        $this->db->where('item_inventory_new.stock_location', $stock_location);
        $query = $this->db->get();
        // echo $this->db->last_query(); die;
        return $query->row_array()['stock_location'];
    }

    public function Getorderpromocode($slip_no = null,$sku=null) {
        if (!empty($slip_no)) {
            $this->db->where('promo_history.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('promo_code');
            $this->db->from('promo_history');
            $this->db->where('promo_history.slip_no', $slip_no);
            $this->db->where('promo_history.sku', $sku);
            $query = $this->db->get();
            return $query->row_array()['promo_code'];
        } else {
            return array();
        }


        //$this->db->order_by('shipment.id','ASC');
    }
    
    public function picking_log($data = array()) {

        return $this->db->insert("status_fm_picking", $data);
    }

     public function Query_log($data = array()) {

        return $this->db->insert("query_log", $data);
    }
    
    
    public function GetcheckLocation_sum_qty($data = array(),$cust_id=null) {
        if (!empty($data) && $cust_id>0) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('item_inventory_new.seller_id', $cust_id);
           // $this->db->where('item_inventory_new.item_sku', $data['item_sku']);
            $this->db->select('sum(item_inventory_new.quantity) as in_stock_running_location');
            $this->db->from('item_inventory_new');
            $this->db->where_in('item_inventory_new.stock_location', $data);
            $query = $this->db->get();
           //  echo $this->db->last_query(); die;
            return $query->row_array()['in_stock_running_location'];
        } else {
            return 0;
        }
    }
}
