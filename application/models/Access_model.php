<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Access_model extends CI_Model {

    function __construct() {
        parent::__construct();
       
    }

     public function GetCheckpages() {
        $this->db->where('user_id', $this->session->userdata('user_details')['user_id']);
        $this->db->select('id,picking_count');
        $this->db->from('tabs_count');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $co_row=$query->row_array();
            $new_count=$co_row['picking_count']+1;
            $this->db->update("tabs_count", array("picking_count" =>$new_count, 'entry_date' => date("Y-m-d H:i:s")), array("user_id" => $this->session->userdata('user_details')['user_id']));
           
        } else {
            $this->db->insert("tabs_count", array("picking_count" => 1,"user_id" => $this->session->userdata('user_details')['user_id'],"super_id" => $this->session->userdata('user_details')['super_id'], 'entry_date' => date("Y-m-d H:i:s")));
        }
       // return true;
    }
}
