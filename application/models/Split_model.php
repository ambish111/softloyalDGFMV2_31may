<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Split_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function GetorderData($slip_no = null) {

        if (!empty($slip_no)) {
            $this->db->select('*,"false" as check_item');
            $this->db->from('diamention_fm');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('deleted', 'N');
            $this->db->where('slip_no', $slip_no);
            $query = $this->db->get();
            //echo $this->db->last_query(); die;        
            if ($query->num_rows() > 0) {
                return $query->result_array();
            }
        }
        return array();
    }

    public function Getshipdata($slip_no = null) {

        
        if (!empty($slip_no)) {
            $this->db->select('`code`, `updated_by`, `booking_id`, `user_id`, `sku`, `cust_id`, `shippers_ac_no`, `shippers_ref_no`, `nrd`, `slip_no`, `origin`, `destination`, `pieces`, `weight`, `volumetric_weight`, `sender_name`, `sender_address`, `sender_phone`, `sender_email`, `reciever_name`, `reciever_address`, `reciever_phone`, `reciever_email`, `reciever_pincode`, `service_charge`, `service_id`, `mode`, `total_cod_amt`, `entrydate`, `status_describtion`, `delivered`, `status`, `deleted`, `fulfillment`, `stocklcount`, `order_type`, `Api_Integration`, `frwd_date`, `frwd_company_id`, `frwd_company_awb`, `frwd_company_label`, `backorder`, `wh_id`, `invoice`, `latitude`, `longitude`, `back_reasons`, `super_id`, `forwarded`, `label_type`, `is_menifest`, `menifest_date`, `barq_order_id`, `address2`, `area_name`, `special_packaging`, `pack_type`, `salla_updated`, `zid_status_update`, `no_of_boxes`, `cancel_fee`, `close_date`, `zid_store_id`, `order_from`, `shipment_value`, `pay_invoice_status`, `pay_invoice_no`, `rec_invoice_status`, `return_invoice_check`, `invoice_check`, `cancel_check`, `deliver_status`, `3pl_close_date`, `3pl_pickup_date`, `no_of_attempt`, `reverse_forwarded`, `pallet_count`, `shopify_order_id`, `sms_sent`, `salla_track_status_updated`, `last_status_3pl`, `product_invoice`, `dispatch_date`, `reverse_type`, `reverse_awb`, `created_at`, `out_of_stock`, `laststatus_first`, `laststatus_second`, `laststatus_last`, `no_of_fd`, `fd1_date`, `fd2_date`, `fd3_date`, `bosta_label_id`, `audit_status`, `picking_date`, `picking_status`, `promise_deliver_date`, `address_url`, `open_stock`, `declare_value`, `salla_invoice`, `suggest_company`, `cod_received_3pl`, `cod_received_date`, `reship_awb`, `reship_type`, `street_number`, `ms_awb`, `ms_type`');
            $this->db->from('shipment_fm');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('deleted', 'N');
            $this->db->where_in('cust_id', array('214','31'));
            $this->db->where('status', 'Y');
            $this->db->where('code', 'OG');
            $this->db->where('backorder', 1);
            $this->db->where('mode', 'CC');
            $this->db->where('slip_no', $slip_no);
            $query = $this->db->get();
            // echo $this->db->last_query(); die;        
            if ($query->num_rows() > 0) {
                return $query->row_array();
            }
        }
        return array();
    }

    public function updateshipment($data = array()) {

        return $this->db->insert('shipment_fm', $data);
        // echo $this->db->last_query(); die;
    }

    public function updatediamention_fm($data = array(), $ids = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('id', $ids);
        return $this->db->update('diamention_fm', $data);
        // echo $this->db->last_query();
        //die;
    }

    public function updatestaus($data = array()) {

        return $this->db->insert_batch('status_fm', $data);
        // echo $this->db->last_query(); die;
    }

    public function old_shipment_update($data = array(), $data_w = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where($data_w);
        return $this->db->update('shipment_fm', $data);
        // echo $this->db->last_query(); 
    }

}
