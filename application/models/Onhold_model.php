<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Onhold_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

     public function updateStatusBatch($data) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('cust_id', $this->session->userdata('user_details')['seller_id']);
        $this->db->update_batch('shipment_fm', $data, 'slip_no');
       //echo $this->db->last_query(); 
    }
    
     public function GetUpdateShipment_status_batch($data = array()) {

        return $this->db->insert_batch('status_fm', $data);
        // echo $this->db->last_query();    die; 
    }

    
    public function shipmetsInAwb_new($awb = array(), $type = null) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('slip_no,code');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            if ($type == 'Yes') {
                $this->db->where('shipment_fm.on_hold', 'No');
            } else {
                $this->db->where('shipment_fm.on_hold', 'Yes');
            }

            $this->db->where_in('shipment_fm.code', array('OG', 'OC'));
            $this->db->where_in('shipment_fm.delivered', array(1, 11));
            $this->db->where_in('slip_no', $awb);
            // $this->db->where('code', 'DL');
            $query = $this->db->get();
            // echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
            } else {
                $data['result'] = array();
                $data['count'] = 0;
                return $data;
            }
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }

    public function updateOnHold($awb = array(),$type=null) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('code,slip_no,delivered');
            $this->db->from('shipment_fm');
            if ($type == 'Yes') {
                $this->db->where('shipment_fm.on_hold', 'No');
            } else {
                $this->db->where('shipment_fm.on_hold', 'Yes');
            }
            $this->db->where_in('shipment_fm.code', array('OG', 'OC'));
            $this->db->where_in('shipment_fm.delivered', array(1, 11));
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('slip_no', $awb);
           
            $query = $this->db->get();
            // echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
               
            } else {
                $data['result'] = array();
                $data['count'] = 0;
                return $data;
            }
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }

}
