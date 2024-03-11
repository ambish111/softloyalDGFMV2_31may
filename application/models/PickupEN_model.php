<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PickupEN_model extends CI_Model {

    function __construct() {
        parent::__construct();
       
    }

    public function GetskuDetailspack($slip_no) {

        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('diamention_fm.sku,diamention_fm.piece,diamention_fm.cod,items_m.item_path,items_m.ean_no');
        $this->db->from('diamention_fm');
        $this->db->join('items_m', 'items_m.sku = diamention_fm.sku');
        $this->db->where('diamention_fm.slip_no', $slip_no);
        $query = $this->db->get();
        /// echo $this->db->last_query(); die;
        return $query->result_array();

        //$this->db->order_by('shipment.id','ASC');
    }

    public function pickListFilterNotPicked($awb = null) {
        if ($this->session->userdata('user_details')['user_type'] != 1) {
             $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `pickupId`, `assigned_to`, `slip_no`, `origin`, `destination`, `reciever_name`, `reciever_address`, `reciever_phone`, `sku`, `pickup_status`, `piece`, `entrydate`, `pickupDate`,sender_name,print_url,weight');
        $this->db->from('pickuplist_tbl');
        $this->db->where('slip_no', $awb);
        $this->db->where('deleted', 'N');
        $this->db->where("assigned_to>0");
        $this->db->where('pickup_status', 'N');
        $this->db->where('picked_status', 'Y');
        $query = $this->db->get();
        //  echo $this->db->last_query();exit;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function pickListFilterNotPicked_3pl($awb = null, $sku = null, $delivered = null, $seller = null, $to = null, $from = null, $exact = null, $page_no = null, $destination = null, $pickupId = null) {

        $awb = GetshpmentDataByawb_3pl($awb, 'slip_no');
        
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickuplist_tbl.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `pickupId`, `assigned_to`, `slip_no`, `origin`, `destination`, `reciever_name`, `reciever_address`, `reciever_phone`, `sku`, `pickup_status`, `piece`, `entrydate`, `pickupDate`,sender_name,print_url');
        $this->db->from('pickuplist_tbl');

        $this->db->where('slip_no', $awb);

        $this->db->where('deleted', 'N');
        if (!empty($sku)) {
            $this->db->where('sku', $sku);
        }
        if (!empty($pickupId)) {
            $this->db->where('pickupId', $pickupId);
        }
        $this->db->where("assigned_to>0");

        $this->db->where('pickup_status', 'N');

        $this->db->order_by('pickuplist_tbl.id', 'ASC');
        $this->db->limit(1);
        $query = $this->db->get();
//         echo $this->db->last_query();exit;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = 1;
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

}
