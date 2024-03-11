<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ZidApp_model extends CI_Model {

    public function showasallatemplatelistQry($data) {
        
        if ($this->site_data->checkSystemType == 'Other') {
            $this->db->where('zid_app_conect.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('system', 'FSP');
        } else {
//            $this->db->where('system', 'DG');
            $this->db->where_not_in('super_id', array(271,20,301)); 
        }
        
        $this->db->select('*');
        $this->db->from('zid_app_conect');
        if (!empty($data['type'])) {
            $this->db->where('status', $data['type']);
        }
        $query = $this->db->get();
        // print_r($this->db->last_query());   die;
        return $query->result_array();
    }

    public function sellerdata() {
        $this->db->select('id,name,uniqueid');
        $this->db->where('`zid_sid` IS NULL');
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('access_fm', 'Y');
        
        if ($this->site_data->checkSystemType == 'Other') {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        }
        else
        {
           $this->db->where_not_in('super_id', array(271,20,301)); 
        }

        $this->db->where('super_id !=','6');
        //$this->db->order_by('name');
        $query = $this->db->get('customer');
        // echo $this->db->last_query(); die;
        return $query->result_array();
    }

    public function UpdateLinkToSalla($cid = null, $sallaId = null, $salla_shipping_cost = null) {
        $this->db->select('`access_token`, `token_type`, `expires_in`, `authorization`, `refresh_token`,store_id');
        $this->db->where('id', $sallaId);
        $query = $this->db->get('zid_app_conect');
        $result = $query->result_array();

        $sync_product= $this->zid_data($result[0]['store_id'],'sync_product');
        $dispatch_orders= $this->zid_data($result[0]['store_id'],'dispatch_orders');


        if (!empty($result)) {

            if($dispatch_orders['dispatch_orders']=='Y')
            {
             $data['zid_status'] ='new';      
            }
            else
            {
                 $data['zid_status'] ='ready';      
            }

            $data['sync_product_zid'] =$sync_product['sync_product'];
            $data['zid_sid'] = $result[0]['store_id'];
            $data['manager_token'] = $result[0]['access_token'];
            $data['zid_expires_in'] = $result[0]['expires_in'];
            $data['zid_authorization'] = $result[0]['authorization'];
            $data['zid_refresh_token'] = $result[0]['refresh_token'];
            $data['zid_access'] = 'FM';
            $data['zid_active'] = 'Y';

            if ($this->db->update('customer', $data, array('id' => $cid))) {
                $this->updateSallaStatus($sallaId, 'L', $cid);
            }
            //echo $this->db->last_query();
        }
    }

    public function zid_data($store_id=null,$column=null) {
        $this->db->select("$column");
        $this->db->where('store_id', $store_id);
        if($column=='sync_product')
        {
        $this->db->where("`sync_product` IS NOT NULL");
        }
        else
        {
             $this->db->where("`dispatch_orders` IS NOT NULL");
        }
         $this->db->limit(1);
        $this->db->order_by("id",'desc');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('zid_log_v2');
         // print($this->db->last_query());   die;
         return $query->row_array();
         
  }

    public function updateSallaStatus($id = null, $status = null, $cust_id = null) {
        if (!empty($cust_id)) {
            $data['status'] = $cust_id;
        }
        $data['status'] = $status;
        return $this->db->update('zid_app_conect', $data, array('id' => $id));
    }

    public function getcheckcustomerData($email = null) {
        $this->db->select('id');
        $this->db->where('email', $email);
        // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('customer');
        // print($this->db->last_query());   die;
        return $query->row_array();
    }

    
    public function UpdateCustomer($data = array(), $id = null) {
        if ($this->site_data->checkSystemType == 'Other') {
        $this->db->where("super_id", $this->session->userdata('user_details')['super_id']);
        }
        return $this->db->update('customer', $data, array('id' => $id));
    }

    public function addNewcustomer($data = array()) {

        return $this->db->insert('customer', $data);
    }

}
