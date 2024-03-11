<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment_bulk_new_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    
    public function checkvalid($awb = array()) {
        if (!empty($awb) && count($awb)>0) {
              if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
            $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('shipment_fm.slip_no,shipment_fm.code,shipment_fm.delivered,shipment_fm.pieces,shipment_fm.destination,shipment_fm.origin,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.cust_id,shipment_fm.booking_id,customer.manager_token,customer.salla_athentication,shipment_fm.weight,shipment_fm.frwd_company_id,shipment_fm.frwd_company_awb,shipment_fm.super_id,wh_id');
            $this->db->from('shipment_fm');
            $this->db->join("customer","customer.id=shipment_fm.cust_id");
            $this->db->where('shipment_fm.deleted', 'N');
              $this->db->where("shipment_fm.slip_no!=''");
            $this->db->where('shipment_fm.status', 'Y');
            //$this->db->where('shipment_fm.code', 'OG');
            //$this->db->where('shipment_fm.backorder', 0);
           // $this->db->where('shipment_fm.delivered', 11);
            //$this->db->where('shipment_fm.pieces>', 0);
            //$this->db->where('shipment_fm.origin>', 0);
            //$this->db->where('shipment_fm.destination>', 0);
            //$this->db->where('shipment_fm.reciever_name!=', '');
            //$this->db->where('shipment_fm.reciever_address!', '');
            $this->db->where_in("slip_no", $awb);
            $query = $this->db->get();
          //  echo $this->db->last_query(); die;

            return $query->result_array();
        } else {
            return array();
        }
    }

}
