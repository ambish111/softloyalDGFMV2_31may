<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SallaApp_model extends CI_Model {

    
   public function showasallatemplatelistQry($data) {

        if ($this->site_data->checkSystemType == 'Other') {
          $this->db->where('zid_app_conect.super_id', $this->session->userdata('user_details')['super_id']);
          $this->db->where('system', 'FSP');
        }else{
          $this->db->where('system', 'DG');
          $this->db->where_not_in('super_id', array(271,20,301,302)); 

        }
        // $this->db->where('salla_app_conect.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('salla_app_conect');
        if(!empty($data['type'])) {
            $this->db->where('status',$data['type']);
        } 
        $query = $this->db->get();
        //print_r($this->db->last_query());   die;
        return $query->result_array();
    }

    public function sellerdata() {
            $this->db->select('id,company,name');
            $this->db->where('salla_merchant_id', NULL);
            $this->db->where('deleted', 'N');
            $this->db->where('status', 'Y');
            $this->db->where('access_fm', 'Y');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $query = $this->db->get('customer');
              // print($this->db->last_query());   die;
             return $query->result_array();
      }

      public function UpdateLinkToSalla($cid=null, $sallaId = null, $salla_shipping_cost=null,$salla_new=null){
            $this->db->select('merchant_id,token,salla_refresh_token,salla_expiry');
            $this->db->where('id', $sallaId);
            $query = $this->db->get('salla_app_conect');
            $result =  $query->result_array();

            if(!empty($result)) {
                $data['salla_merchant_id'] = $result[0]['merchant_id'];
                $data['salla_athentication'] = $result[0]['token'];
                $data['sala_refresh_token'] = $result[0]['salla_refresh_token'];
                $data['sala_token_expiry'] = $result[0]['salla_expiry'];
                $data['salla_access'] ='FM';
                $data['salla_active'] ='Y';
                $data['salla_new'] =$salla_new;
                $data['salla_shipping_cost'] =$salla_shipping_cost;
                if( $this->db->update('customer',$data,array('id'=>$cid)))
                {
                    $this->updateSallaStatus($sallaId,'L');
                }   
                //echo $this->db->last_query();

            }

      }

      public function updateSallaStatus($id =null, $status=null){
        $data['status'] = $status;  
        return $this->db->update('salla_app_conect',$data,array('id'=>$id));    
      }
      
      public function getcheckcustomerData($email=null,$salla_merchant_id=null) {
            $this->db->select('id');
            $this->db->where('email', $email);
            //$this->db->where('salla_merchant_id', $salla_merchant_id);
            $query = $this->db->get('customer');
            //  print($this->db->last_query());   die;
            return $query->row_array();
      }
      
      
      public function UpdateCustomer($data=array(),$id=null)
      {
        $this->db->where("super_id",$this->session->userdata('user_details')['super_id']); 
        return $this->db->update('customer',$data,array('id'=>$id));   
      }
       public function addNewcustomer($data=array())
      {
        
        return $this->db->insert('customer',$data);   
      }


}
