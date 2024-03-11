<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CourierCompanyPartner_model extends CI_Model { 

    public $DG_CLIENT;

    function __construct() {
        parent::__construct();
        $this->DG_CLIENT = $this->load->database('dg_client',TRUE);
       
    }


    public function getFPCompanyDetails($cc_id=null){
        $this->DG_CLIENT->select('id,cc_description,company');
        $this->DG_CLIENT->where('cc_id',$cc_id);
        $query = $this->DG_CLIENT->get('courier_company_partner');
        
        //echo $this->DG_CLIENT->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        
    }

    public function all($data=array()) {

        
        if(!empty($data['id']))
        {
            $this->DG_CLIENT->where('id', $data['id']);
        }
        $this->DG_CLIENT->where('deleted', 'N');
        $this->DG_CLIENT->order_by('company');
        $this->DG_CLIENT->select('*');
        $query = $this->DG_CLIENT->get('courier_company_partner');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
      public function all_new($data=array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        
        if(!empty($data['cc_id']))
        {
            $this->db->where('id', $data['cc_id']);
        }
        $this->db->where('fp_flag', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->order_by('company');
        $this->db->select('*');
        $query = $this->db->get('courier_company');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }


    public function getFPstatus($cc_id=null){
        $this->db->select('*');
        $this->db->where('cc_id', $cc_id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('fp_flag', 'Y');
        $this->db->from('courier_company');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

    }

    public function InsertDeliveryCOmpany($data= array()){
        // print "<pre>"; print_r($data);die;
        return $this->db->insert('courier_company', $data); 
    }

     
    public function UnsubscribePartnerCompany($cc_id=null){

        
        $this->db->where('cc_id', $cc_id);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('fp_flag', 'Y');
        $this->db->delete('courier_company');
    //echo $this->db->last_query(); die;
    }


    public function GetUpdateDeliveryCOmpany($data = array(), $data_w) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('cc_id', $data_w['cc_id']);
        $this->db->where('fp_flag', 'Y');
        //print "<pre>"; print_r($data);die;
        return $this->db->update('courier_company', $data);
        //echo $this->db->last_query();
        //die;
    }

    

}
