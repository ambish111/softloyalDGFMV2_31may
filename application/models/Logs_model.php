<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function filter($data = array()) {


        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
       // $this->db->where('order_from', 'A');
        if (!empty($data['entry_date_f']) && !empty($data['entry_date_t'])) {
            $where = "DATE(salla_qty.entry_date) BETWEEN '" . $data['entry_date_f'] . "' AND '" . $data['entry_date_t'] . "'";

            $this->db->where($where);
        }
        $this->db->where('cust_id', '214');
        $this->db->select('*');
        $this->db->from('salla_qty');
        if (!empty($data['temp_order_no'])) {
            $this->db->where('temp_order_no', $data['temp_order_no']);
        }
        if (!empty($data['sku'])) {
            $this->db->where('sku', $data['sku']);
        }
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($data);
            return $data;
        } else {
            $data['result'] = array();
            $data['count'] = 0;
            return $data;
        }
    }

    public function shipmCount($data = array()) {
        if (!empty($data['entry_date_f']) && !empty($data['entry_date_t'])) {
            $where = "DATE(salla_qty.entry_date) BETWEEN '" . $data['entry_date_f'] . "' AND '" . $data['entry_date_t'] . "'";

            $this->db->where($where);
        }
        $this->db->where('cust_id', '214');
        $this->db->where('salla_qty.super_id', $this->session->userdata('user_details')['super_id']);
       // $this->db->where('order_from', 'A');
        $this->db->select('COUNT(salla_qty.id) as sh_count');
        $this->db->from('salla_qty');
        if (!empty($data['temp_order_no'])) {
            $this->db->where('temp_order_no', $data['temp_order_no']);
        }
        if (!empty($data['sku'])) {
            $this->db->where('sku', $data['sku']);
        }
        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

}
