<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Buk_update_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function ShipData($slip_no = null) {


        $this->db->select('id,slip_no,cod_received_3pl,code');
        $this->db->from('shipment_fm');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('slip_no', trim($slip_no));
        $this->db->where('deleted', 'N');
        // $this->db->where("(cod_received_3pl='0.00' or cod_received_3pl='')");
        $this->db->where_not_in('code', array('C'));
        $query = $this->db->get();
        //return  $this->db->result_array();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function Getupdateshipemnt($data = array(), $slip_no = null) {
        if (!empty($slip_no)) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('slip_no', trim($slip_no));
            return $this->db->update('shipment_fm', $data);
        }
    }

}
