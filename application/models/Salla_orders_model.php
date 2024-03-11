<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salla_orders_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function shipmCount($data = array()) {
       
        $this->db->where('sallaorders.super_id', $this->session->userdata('user_details')['super_id']);
      
        $this->db->select('COUNT(sallaorders.id) as sh_count');
        $this->db->from('sallaorders');

         if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(sallaorders.created_at) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";
            $this->db->where($where);
        }
        if (!empty($data['booking_id'])) {
            $this->db->where('sallaorders.booking_id', $data['booking_id']);
        }

        $this->db->order_by('sallaorders.id', 'desc');
        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
        }
        return 0;
    }

    public function filter($data = array()) {

        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
       
        $this->db->where('sallaorders.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('sallaorders');
        if (!empty($data['from']) && !empty($data['to'])) {
            $where = "DATE(sallaorders.created_at) BETWEEN '" . $data['from'] . "' AND '" . $data['to'] . "'";
            $this->db->where($where);
        }
        if (!empty($data['booking_id'])) {
            $this->db->where('sallaorders.booking_id', $data['booking_id']);
        }

        $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($data);
            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
     public function Getskudetails_ship($slip_no = null,$booking_id=null) {
        $this->db->where('diamention_fm_salla.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('deducted_shelve,sku,description,piece,cod,free_sku');
        $this->db->from('diamention_fm_salla');
        $this->db->where('diamention_fm_salla.deleted', 'N');
        //$this->db->join('items_m','items_m.sku = diamention.sku');
        $this->db->where('slip_no', $slip_no);
         $this->db->where('booking_id', $booking_id);
        $query = $this->db->get();
        return $query->result_array();


        //$this->db->order_by('shipment.id','ASC');
    }
    
    
    public  function BookingIdCheck_cust_fm($booking_id=null, $cust_id=null) {
       
        $site_query = "select slip_no from shipment_fm where booking_id='" . trim($booking_id) . "' and cust_id='" . $cust_id . "' and deleted='N'  ";
        $query = $this->db->query($site_query);
        $result = $query->row_array();
        return $result['slip_no'];
    }
    
   public function Getcustomerdata($uid = null) {
       
        $sql = "SELECT id,salla_athentication,salla_access FROM customer where salla_merchant_id='$uid' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die;
        $result = $query->row_array();
       return  $result;
    }

    
     public function getorderDetails($id = null) {
       
        $sql = "SELECT * FROM sallaorders where id='$id' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'";
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); die;
        $result = $query->row_array();
       return  $result;
    }

}
