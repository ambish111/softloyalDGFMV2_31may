<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function getTodayData($status = null) {


        $current_date = date('Y-m-d');
        $this->db->select("sum(case when delivered = 11 then 1 else 0 end) AS t_og,
    sum(case when delivered = 1 then 1 else 0 end) AS t_oc,
    sum(case when delivered = 2 then 1 else 0 end) AS t_pg,
    sum(case when delivered = 3 then 1 else 0 end) AS t_ap,
    sum(case when delivered = 4 then 1 else 0 end) AS t_pk,
    sum(case when delivered = 5 then 1 else 0 end) AS t_dl,
    sum(case when delivered = 7 then 1 else 0 end) AS t_pod,
    sum(case when delivered = 8 then 1 else 0 end) AS t_rtc");
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('DATE(entrydate)', $current_date);
        // $this->db->where('delivered', $status);
        $query = $this->db->get();
        $result = $query->row_array();
        //echo $this->db->last_query(); die;
        return $result;
    }

    public function count_shipment() {

        $conditions = array(
            'status' => 'Y',
            'deleted' => 'N',
            'super_id' => $this->session->userdata('user_details')['super_id'],
        );
         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        return $this->db->where($conditions)->from('shipment_fm')->count_all_results();
        ///echo $this->db->last_query(); die;
    }

    public function Getalltotalchartmonth($year = null) {
        if (!empty($year)) {
            $condition .= " YEAR(entrydate)='$year'";
        } else {
            $condition .= "YEAR(entrydate)='" . date('Y') . "'";
        }
         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select("MONTHNAME(entrydate) AS name,COUNT(DISTINCT id) as y");
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where("$condition");
        $this->db->group_by("name");
        $this->db->order_by("id",'asc');
        $query = $this->db->get();
       // echo $this->db->last_query(); die;
        $result = $query->result_array();
        return $result;
    }

    
     public function getallyData() {

        $this->db->select("sum(case when delivered = 11 then 1 else 0 end) AS t_og,
sum(case when delivered = 1 then 1 else 0 end) AS t_oc,
sum(case when delivered = 2 then 1 else 0 end) AS t_pg,
sum(case when delivered = 4 then 1 else 0 end) AS t_pk,
sum(case when delivered = 5 then 1 else 0 end) AS t_dl,
sum(case when delivered = 7 then 1 else 0 end) AS t_pod,
sum(case when delivered = 8 then 1 else 0 end) AS t_rtc,
sum(case when delivered = 22 then 1 else 0 end) AS t_ro,
sum(case when delivered = 17 then 1 else 0 end) AS t_d_to3pl");
        $this->db->from('shipment_fm');
         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.status', 'Y');
       // $this->db->where('DATE(entrydate)', $current_date);
        // $this->db->where('delivered', $status);
         $this->db->where('backorder', 0);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        $result = $query->row_array();
        //echo $this->db->last_query(); die;
        return $result;
    }

    
    public function getTodayShipment() {

         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $current_date = date('Y-m-d');
        $this->db->select("count(id) as total_today");
        $this->db->from('shipment_fm');
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('DATE(entrydate)', $current_date);
        // $this->db->where('delivered', $status);
        $query = $this->db->get();
        $result = $query->row_array();
        //echo $this->db->last_query(); die;
        return $result;
    }


}


