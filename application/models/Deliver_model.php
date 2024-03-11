<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Deliver_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function shipmetsInAwb($awb = array()) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_not_in('shipment_fm.code', array('LSD','POD','RTC'));
            $this->db->where_in('slip_no', $awb);
            // $this->db->where('code', 'DL');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
    public function shipmetsInAwb_new($awb = array()) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_not_in('shipment_fm.code', array('LSD','RTC')); //'POD'
            $this->db->where_in('slip_no', $awb);
            // $this->db->where('code', 'DL');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
    
    public function shipmetsInAwb_cancel($awb = array()) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('code,slip_no');
            $this->db->from('shipment_fm');
            $this->db->where_not_in('shipment_fm.code', array('LSD','POD','RTC'));
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('slip_no', $awb);
            // $this->db->where('code', 'DL');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
    public function shipmetsInAwb_og($awb = array()) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_not_in('shipment_fm.code', array('LSD','POD','RTC'));
            $this->db->where_in('slip_no', $awb);
            // $this->db->where('code', 'UNDR');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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

    public function shipmetsInAwbAll($awb = null) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->select('*');
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('shipment_fm.code', array('DL', 'ROP', 'DOP', 'FD','IT'));

            $this->db->where_in('slip_no', $awb);

            $query = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
    

    public function shipmetsInAwbAll_rop($awb = null) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->select('*');
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('shipment_fm.code', array('POD'));

            $this->db->where_in('slip_no', $awb);

            $query = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
    
    public function shipmetsInAwbAll_cancel($awb = null) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->select('slip_no');
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('shipment_fm.code', array('RPC','ROG','ROFD','RPOD','RIT','RPUC','FD'));
            

            
            $this->db->where_in('slip_no', $awb);

            $query = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
     public function shipmetsInAwbAll_og($awb = null) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->select('*');
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('shipment_fm.code', array('UNDR'));

            $this->db->where_in('slip_no', $awb);

            $query = $this->db->get();

            //echo $this->db->last_query(); die;
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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
    public function shipmetsInAwbAll_pack($awb = array()) {
        if (!empty($awb)) {
            if ($this->session->userdata('user_details')['user_type'] != 1) {
                $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
            }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('shipment_fm');
            $this->db->where('shipment_fm.deleted', 'N');
            $this->db->where_in('slip_no', $awb);
            $this->db->where_not_in('code', array('OG', 'OC', 'PK', 'C', 'POD', 'RTC'));
            // $this->db->where_not_in('code', array('OG','OC','PG','PK','DL','AP','C','POD','RTC'));
            $query = $this->db->get();
            if ($query->num_rows() > 0) {

                $data['result'] = $query->result_array();
                $data['count'] = $query->num_rows();
                return $data;
                // return $page_no.$this->db->last_query();
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

    public function removeDeliverymanifest($awb_array = array()) {
        $this->db->where('delivery_manifest.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('delivery_manifest.slip_no', $awb_array);
        return $this->db->update("delivery_manifest", array("deleted" => 'Y'));
    }
    
    
    public function update_damage_return_status($data=array(),$id=null)
    {
        $this->db->where('damage_history.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('damage_history.id', $id);
        $this->db->where('damage_history.return_status', "N");
       return $this->db->update("damage_history",$data);
    }

        public function update_damage_return_status_multiple($data=array(),$ids=array())
    {
        $this->db->where('damage_history.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('damage_history.id', $ids);
        $this->db->where('damage_history.return_status', "N");
       return $this->db->update("damage_history",$data);
    }
    

}

