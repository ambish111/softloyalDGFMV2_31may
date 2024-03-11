<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diggistores_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function custList() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('access_fm', 'Y');
        $this->db->where('diggistore_connected', 'N');
        $this->db->where('company!=', '');
        $this->db->select('id,company as name');
        $this->db->order_by('id', 'desc');

        $query = $this->db->get('customer');
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function CountryList(){
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,country');
        $this->db->where('deleted','N');
        $this->db->where('status','Y');
        $this->db->where('state!=','');
        $this->db->group_by('country');
        $this->db->order_by('country', 'asc');
        $query = $this->db->get('country');
        
        if ($query->num_rows() > 0) {
            return $query->result();
        }

    }

    public function get_cities_by_country($country = NULL) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,city');
        $this->db->where('city!=', '');
        $this->db->where('deleted', 'N');
        $this->db->where('country',$country);
        $this->db->order_by('city');
       // $this->db->group_by('city');
        $query = $this->db->get('country');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function add_stores_customer($data) {


        $this->db->trans_start();
        $this->db->insert('diggistores_connect', $data);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }

    public function update_store_chanel($cust_id=null){

        $this->db->where('id', $cust_id);
        $data = array("diggistore_connected"=>"Y");
        $this->db->update('customer', $data);
        //   echo $this->db->last_query(); die;
    }

    public function store_customer(){
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->order_by('id', 'desc');

        $query = $this->db->get('diggistores_connect');
        if ($query->num_rows() > 0) {
            return $query->result();
        }        
    }

    public function get_customer_details($id) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('cust_id', $id);
        $query = $this->db->get('diggistores_connect');
        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function get_cities_by_cc_city() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,city');
        $this->db->where('city!=', '');
        $this->db->where('deleted', 'N');
        $this->db->order_by('city');
       // $this->db->group_by('city');
        $query = $this->db->get('country');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function UpdateConnectedCustomer(array $data, $id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('diggistores_connect', $data, array('cust_id' => $id));
    }

}
