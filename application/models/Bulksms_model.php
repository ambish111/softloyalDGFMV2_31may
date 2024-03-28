<?php

defined('BASEPATH') or exit ('No direct script access allowed');

class Bulksms_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }


    public function shipmentdetail(array $AWB_array)
    {
        $this->db->select('id,slip_no,reciever_phone');
        $this->db->from('shipment_fm');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where_in('slip_no', $AWB_array);
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function bulksmselog($senderdata, $smsslipno, $SMS)
    {
        // print_r($sm);die;
        $sup_id =$this->session->userdata('user_details')['super_id'];
        $currentDateTime = date('Y-m-d H:i:s');
        $addLog = " INSERT INTO `bulk_sms_logs`(`super_id`, `ph_number`, `slip_no`, `message`,`date_time`) Values ('" . $sup_id . "', '" . $senderdata . "','" . $smsslipno . "','" . $SMS . "','" . $currentDateTime . "') ";
        $query =$this->db->query($addLog);
        // echo $this->db->last_query(); die;
        return true;
    }

}
