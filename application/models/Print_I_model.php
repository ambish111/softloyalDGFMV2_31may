<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Print_I_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function Get_shipdata($slip_nos = array()) {
        if (sizeof($slip_nos) > 0) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('slip_no,product_invoice');
            $this->db->where('product_invoice IS NOT NULL', NULL, FALSE);
            $this->db->from('shipment_fm');
            $this->db->where_in('slip_no', $slip_nos);
            $order_by_slip = "'" . implode("','", $slip_nos) . "'";
            $order = sprintf('FIELD(shipment_fm.slip_no, %s)', $order_by_slip);
            $this->db->order_by($order);
            $query = $this->db->get();
           // echo $this->db->last_query(); die;
            return $query->result_array();
        } else {
            return array();
        }
    }

}
