<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zid_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function fetch_zid_customers($uniqueid = null) {
        $this->db->select('*');
        $this->db->where('uniqueid', $uniqueid);
        $this->db->where('deleted', 'N');
        $this->db->where('zid_active', 'Y');
        $this->db->where('manager_token !=', '');
        $query = $this->db->get('customer');
     // echo  $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }
       public function fetch_zid_customers_new($store_id = null,$super_id=null) {
        $this->db->select('*');
        $this->db->where('zid_sid', $store_id);
       /// $this->db->where('super_id', $super_id);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('zid_active', 'Y');
        $this->db->where('manager_token !=', '');
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function webhook_log($data) {
        $this->db->insert('webhook_log', $data);
        return $this->db->insert_id();
    }
    
    public function existLmBookingId($booking_id, $cust_id) {

        $sql = "select id from shipment where booking_id='" . $booking_id . "' and cust_id='" . $cust_id . "' and deleted='N'";
        $query = $this->db->query($sql);
        $countdata = $query->num_rows();
        $row = $query->row_array();
        if ($countdata > 0)
            return $row['id'];
        else
            return false;
    }
    
     public function existLmBookingId_new($booking_id, $cust_id) {

        $sql = "select id,code,slip_no,delivered from shipment where booking_id='" . $booking_id . "' and cust_id='" . $cust_id . "' and deleted='N'";
        $query = $this->db->query($sql);
        $countdata = $query->num_rows();
        $row = $query->row_array();
        if ($countdata > 0)
            return $row;
        else
            return false;
    }
    
    public function getdestinationfieldshow($id = null, $field = null, $super_id) {
        $sql = "SELECT $field FROM country where id='$id' and super_id='" . $super_id . "'";
        $query = $this->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

    
    public function addorder_history($data = array()) {
        return $this->db->insert('status_fm', $data);
    }

    
    public function updateShipmentCancel($data = array(), $slip_no = null, $super_id = null) {
        $this->db->where("super_id", $super_id);
        return $this->db->update('shipment_fm', $data, array("slip_no" => $slip_no));
    }
     public function addorder_history_lm($data = array()) {
        return $this->db->insert('status', $data);
    }

    
    public function updateShipmentCancel_lm($data = array(), $slip_no = null, $super_id = null) {
        $this->db->where("super_id", $super_id);
        return $this->db->update('shipment', $data, array("slip_no" => $slip_no));
    }
}
