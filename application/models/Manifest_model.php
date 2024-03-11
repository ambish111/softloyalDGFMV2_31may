<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manifest_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('ItemInventory_model');
        $this->load->model('Item_model');
        $this->load->model('Cartoon_model');
        $this->load->model('Seller_model');
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function filterUpdate($page_no, $filterarray = array()) {


         $this->db->where('pickup_request.deleted','N');
        $this->db->select('pickup_request.id,pickup_request.uniqueid,pickup_request.sku,pickup_request.qty,items_m.id as item_sku, storage_table.storage_type,items_m.sku_size,items_m.item_path,pickup_request.seller_id');
        $this->db->from('pickup_request');
        $this->db->join('items_m', 'items_m.sku=pickup_request.sku');
        $this->db->join('storage_table', 'storage_table.id=items_m.storage_id');
        $this->db->where('pickup_request.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('pickup_request.confirmO', 'N');
     $this->db->where('pickup_request.code', 'RI');
         $this->db->where("pickup_request.received_qty>0");


        if ($filterarray['manifestid'])
            $this->db->where('pickup_request.uniqueid', $filterarray['manifestid']);
        $this->db->group_by('pickup_request.sku');

        $query = $this->db->get();
    //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            return $data;
            //return $page_no.$this->db->last_query();
        }
    }

    public function filter($page_no, $filterarray = array()) {

        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $this->db->where('pickup_request.deleted','N');
        $this->db->select('COUNT(id) as id_count,id,SUM(qty) as qtyall,SUM(missing_qty) as m_qty,SUM(damage_qty) as d_qty,manifest_type,SUM(received_qty) as r_qty,id,uniqueid,sku,qty,assign_to,req_date,pstatus,code,seller_id,on_hold,itemupdated,confirmO,pickimg,address,city,3pl_awb,3pl_name,3pl_label,3pl_date,boxes,description,return_type,staff_id,vehicle_type,assign_date,pack_type,schedule_date');
        $this->db->from('pickup_request');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] == 9)
            $this->db->where('assign_to', $this->session->userdata('user_details')['user_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1 && $this->session->userdata('user_details')['user_type'] != 3 ) {
            $this->db->where('staff_id', $this->session->userdata('user_details')['user_id']);
        }
        
         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickup_request.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['driverid'])
            $this->db->where('assign_to', $filterarray['driverid']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
            if ($filterarray['sku'])
            $this->db->where('sku', $filterarray['sku']);    
       // $this->db->where_in('pstatus', array(5, 2));
//        if($this->session->userdata('user_details')['super_id'] == 333){
//            if ($filterarray['staffpage'] == 'yes'){
//                $this->db->where('assign_to >', 0);
//            }    
//        }    
        
            
        $this->db->group_by('uniqueid');

        // print_r($filterarray);
        if ($filterarray['sort_list'] == 'NO') {
            $this->db->order_by('pickup_request.id', 'desc');
        } else if ($filterarray['sort_list'] == 'OLD') {
            $this->db->order_by('pickup_request.id', 'asc');
        } else {
            $this->db->order_by('pickup_request.id', 'desc');
        }
        //$this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
       // echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->manifestCount($page_no, $filterarray);
            return $data;
            //return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_return($page_no, $filterarray = array()) {

        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $this->db->where('pickup_request.deleted','N');
        $this->db->select('qty as qtyall,id,uniqueid,sku,qty,assign_to,req_date,pstatus,code,seller_id,on_hold,itemupdated,confirmO,pickimg,address,city,3pl_awb,3pl_name,3pl_label,3pl_date,r_3pl_awb,r_3pl_name,r_3pl_label,r_3pl_date,boxes,description,return_type');
        $this->db->from('pickup_request');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] == 9)
            $this->db->where('assign_to', $this->session->userdata('user_details')['user_id']);
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['driverid'])
            $this->db->where('assign_to', $filterarray['driverid']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
        $this->db->where('return_type', 'Y');
        // $this->db->group_by('uniqueid');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->manifestCount_return($page_no, $filterarray);
            return $data;
            //return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function manifestCount_return($page_no, $filterarray = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['driverid'])
            $this->db->where('assign_to', $filterarray['driverid']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
        $this->db->where('return_type', 'Y');
        // $this->db->group_by('uniqueid');
        $this->db->order_by('id', 'ASC');


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function manifestCount($page_no, $filterarray = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select(' count( DISTINCT(uniqueid) )  AS tcount');
        $this->db->from('pickup_request');
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('pickup_request.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['driverid'])
            $this->db->where('assign_to', $filterarray['driverid']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
            if ($filterarray['sku'])
            $this->db->where('sku', $filterarray['sku']);  
           // $this->db->where_in('pstatus', array(5, 2));
       // $this->db->where('pstatus', 5);
       // $this->db->group_by('uniqueid');
        $this->db->order_by('id', 'ASC');


        $query = $this->db->get();

       // return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data =  $query->result_array();
            return $data[0]['tcount'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function getpickuplistdatashow($to, $from, $page_no, $filterarray = array()) {

        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(id) as id_count,SUM(qty) as qtyall,id,uniqueid,sku,qty,assign_to,req_date,pstatus,code,seller_id,on_hold,itemupdated,address,city,3pl_awb,3pl_name,3pl_label,3pl_date,boxes,description,manifest_type');
        $this->db->from('pickup_request');
        //if($this->session->userdata('user_details')['user_type']==)
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['driverid'])
            $this->db->where('assign_to', $filterarray['driverid']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);

            if ($filterarray['sku'])
            $this->db->where('sku', $filterarray['sku']);  
        $this->db->where('pstatus', 6);
        $this->db->group_by('uniqueid');
        if ($filterarray['sort_list'] == 'NO') {
            $this->db->order_by('pickup_request.id', 'desc');
        } else if ($filterarray['sort_list'] == 'OLD') {
            $this->db->order_by('pickup_request.id', 'asc');
        } else {
            $this->db->order_by('pickup_request.id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        // echo  $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            $data['count'] = $this->paikuplistcount($to, $from, $page_no, $filterarray);

            return $data;
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function paikuplistcount($to, $from, $page_no, $filterarray = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['driverid'])
            $this->db->where('assign_to', $filterarray['driverid']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
            if ($filterarray['sku'])
            $this->db->where('sku', $filterarray['sku']); 
        $this->db->group_by('uniqueid');
        $this->db->order_by('id', 'ASC');
        $this->db->where('pstatus', 6);

        $query = $this->db->get();

        // return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function getnewgenratemanifestdata($to, $from, $page_no, $filterarray = array()) {

        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('COUNT(id) as id_count,id,SUM(qty) as qtyall,SUM(missing_qty) as m_qty,SUM(damage_qty) as d_qty,SUM(received_qty) as r_qty,id,uniqueid,sku,qty,assign_to,req_date,pstatus,code,seller_id,on_hold,itemupdated,schedule_date,vehicle_type,boxes,pack_type,manifest_type');
        $this->db->from('pickup_request');
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['sku'])
            $this->db->where('sku', $filterarray['sku']);
            if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
        $this->db->where('pstatus', 1);
        $this->db->group_by('uniqueid');
        if ($filterarray['sort_list'] == 'NO') {
            $this->db->order_by('pickup_request.id', 'desc');
        } else if ($filterarray['sort_list'] == 'OLD') {
            $this->db->order_by('pickup_request.id', 'asc');
        } else {
            $this->db->order_by('pickup_request.id', 'desc');
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get();
  //  echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            $data['count'] = $this->newrequestmanifestCount($to, $from, $page_no, $filterarray);
            return $data;
            //return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function newrequestmanifestCount($to, $from, $page_no, $filterarray = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        if ($filterarray['seller_id'])
            $this->db->where('seller_id', $filterarray['seller_id']);
        if ($filterarray['manifestid'])
            $this->db->where('uniqueid', $filterarray['manifestid']);
            if ($filterarray['sku'])
            $this->db->where('sku', $filterarray['sku']);
        $this->db->group_by('uniqueid');
        $this->db->order_by('id', 'ASC');
        $this->db->where('pstatus', 1);

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function manifestviewListFilter($data = array()) {

        $page_no;
        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $data['manifest_id']);
        if ($data['sku']) {
            $this->db->where('sku', $data['sku']);
        }
//        if ($data['type'] == 'PS') {
//            $this->db->where('code', 'PU');
//        }
//        if ($data['type'] == 'RS') {
//            $this->db->where('code', 'RI');
//            $this->db->group_by('sku', 'code');
//        }
//        if ($data['type'] == 'DM') {
//            //   $this->db->group_by('sku','code');
//           // $this->db->where_in('code', array('MSI', 'DI'));
//        }

        $this->db->order_by('id', 'desc');
         $this->db->limit($limit, $start);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->manifestlistviewCount($data);

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function manifestlistviewCount($data = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');


        $this->db->where('pickup_request.deleted','N');
        if ($data['sku']) {
            $this->db->where('sku', $data['sku']);
        }
        $this->db->where('uniqueid', $data['manifest_id']);

        if ($data['type'] == 'PS') {
            $this->db->where('code', 'PU');
        }
        if ($data['type'] == 'RS') {
            $this->db->where('code', 'RI');
            $this->db->group_by('sku', 'code');
        }
        if ($data['type'] == 'DM') {
            //   $this->db->group_by('sku','code');
            $this->db->where_in('code', array('MSI', 'DI'));
        }

        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        if ($query->num_rows() > 0) {
            $data = $query->num_rows();
            return $data;
        }
        return 0;
    }

    public function ManifestStatusUpdate($data = array(), $id = null, $sku = null) {

        ///$this->db->get_compiled_select();
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('sku', $sku);
        $this->db->where("pstatus!=2");
        $this->db->where('id', $id);
        $query2 = $this->db->get();
        //$this->db->get_compiled_select();
        if ($query2->num_rows() > 0) {
            // $this->db->where('id',$id);

            $query = $this->db->update('pickup_request', $data, array('id' => $id));
            return true;
        } else
            return false;
    }

    public function ManifestDMUpdate($data = array(), $id = null) {

            $query = $this->db->update('pickup_request', $data, array('id' => $id));
            //echo $this->db->last_query(); die; 
            return true;
       
    }

    public function getpickedupupdatestatus($data = array(), $id = null) {

        ///$this->db->get_compiled_select();
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $id);
        $this->db->where('pstatus', 6);
        $query2 = $this->db->get();
        //$this->db->get_compiled_select();
        if ($query2->num_rows() > 0) {
            // $this->db->where('id',$id);

            $query = $this->db->update('pickup_request', $data, array('uniqueid' => $id));
            return true;
        } else
            return false;
    }

    public function Getdriverassignupdate($data = array(), $uid = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->update('pickup_request', $data, array('uniqueid' => $uid));
        return $query;
    }

    public function GetUpdateDamageInventory($data = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->update_batch('inventory_damage', $data, 'id');
    }

    public function Getdriverassignupdate_return($data = array(), $uid = null) {

        $query = $this->db->insert_batch('pickup_request_return', $data);
        return $query;
    }
    public function insertManifest($data = array()) {

        $query = $this->db->insert_batch('pickup_request', $data);
        return $query;
    }

    public function GetNotfoundStatusUpdates($data = array(), $id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->update('pickup_request', $data, array('id' => $id, 'code' => 'PU', 'pstatus' => 5));
        return $query;
    }

    public function getManifestReceviedUpdates($updateData = array(), $data = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $data['uniqueid']);
        $this->db->where('pstatus', 5);
        $this->db->where('code', 'PU');
        $this->db->where('sku', $data['sku']);
        $this->db->order_by("id", "asc");
        $this->db->limit(1, 0);
        $query2 = $this->db->get();
        // $this->db->last_query(); die; 
        //$this->db->get_compiled_select();
        if ($query2->num_rows() > 0) {
            $rows = $query2->row_array();
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $query = $this->db->update('pickup_request', $updateData, array('id' => $rows['id'], 'uniqueid' => $data['uniqueid'], 'sku' => $data['sku']));
            return true;
        } else {
            return false;
        }
    }

    public function getManifestReceviedUpdates_new($data = array(), $data_w = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where($data_w);
        return $this->db->update_batch('pickup_request', $data, 'sku');
    }

    public function getManifestReceviedUpdates_new_single($data = array(), $data_w = array(), $limit = 0) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->limit($limit);
        $this->db->where($data_w);
        return $this->db->update('pickup_request', $data);
    }

    public function getManifestReceviedUpdatesCount($data = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $data['uniqueid']);
        $this->db->where("pstatus!='2'");
        $this->db->where("code!='RI'");
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        //return $this->db->last_query(); die;
        return $query2->num_rows();
        //$this->db->get_compiled_select();
    }
    public function  checkSplitManifest($id=null) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $id);
      
        $query2 = $this->db->get();

       
        return $query2->num_rows();
       
    }

    public function getManifestReceviedUpdatesCountComp($data = array()) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $data['uniqueid']);
        $this->db->where('pstatus', 2);
        $this->db->where('code', 'RI');
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        //return $this->db->last_query(); die;
        return $query2->num_rows();
        //$this->db->get_compiled_select();
    }

    public function GetallpickupRequestData_imtemCheck($uid = null) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('count(id)');
        $this->db->from('pickup_request');
        //$this->db->join("items_m as IMS","IMS.sku!=pickup_request.sku","inner");
        //$this->db->where("sku not in (select sku from items_m )");

    
        $this->db->where('confirmO','N');
        $this->db->where('pickup_request.uniqueid', $uid);
        $this->db->group_by('pickup_request.sku');
        //$this->db->where('pstatus',2);
        ///$this->db->where('code','RI');
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();
        if( $query2->num_rows()>0)
        {
            return true;
        }
        else
        {
            return false;  
        }
        //echo  $this->db->last_query(); die;
        // $query2->num_rows();
       
        //$this->db->get_compiled_select();
    }

    public function getupdateconfirmstatus($uid = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("pickup_request", array('confirmO' => 'Y', 'on_hold' => 'N'), array('uniqueid' => $uid));
    }

    public function GetUpdatePickupchargeInvocie($data = array(), $newlimitcheck, $qty = null, $sku_id = null) {
        $entrydate = date("Y-m-d H:i:sa");

        $noofpallets = $newlimitcheck; //Getallstoragetablefield($data['storagetype'],'no_of_pallet')
        $SinglePickupChage = getalluserfinanceRates($data['sid'], 5, 'rate');
        $SingleInboundChage = getalluserfinanceRates($data['sid'], 6, 'rate');
        $Singleinventory_charge = getalluserfinanceRates($data['sid'], 14, 'rate');
        $totalpallets = $noofpallets * $noofpallets;
        $totalpickupCharge = $SinglePickupChage * $noofpallets;
        $totalInboundChage = $SingleInboundChage * $noofpallets;
        $totalinventoryCharge = $Singleinventory_charge * $noofpallets;
        $addedArray = array('pickup_id' => $data['uid'], 'pickupcharge' => $totalpickupCharge, 'seller_id' => $data['sid'], 'no_of_pallets' => $noofpallets, 'inbound_charge' => $totalInboundChage, 'entrydate' => $entrydate, 'qty_count' => $noofpallets, 'inventory_charge' => $totalinventoryCharge, 'sku_id' => $sku_id, 'super_id' => $this->session->userdata('user_details')['super_id']);
        //return $addedArray;
        $this->db->insert("orderpickupinvoice", $addedArray);
    }

    public function GetallstockLocation($sid = null, $limit = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,seller_id,stock_location');
        $this->db->from('stockLocation');
        $this->db->where('seller_id', $sid);
        $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory` where super_id= '.$this->session->userdata('user_details')['super_id'].'  and stock_location!="" )', NULL, FALSE);
        if (!empty($limit))
            $this->db->limit($limit, 0);
        $query2 = $this->db->get();

        return $query2->result();
    }

    public function getUpdateHoldOnData($uid = null, $seller_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("pickup_request", array('on_hold' => 'Y', 'on_hold_date' => date("Y-m-d")), array('uniqueid' => $uid, 'seller_id' => $seller_id));
    }

    public function getallStoragesTypesData($sid = null) {
        $this->db->select('id,storage_type');
        $this->db->from('storage_table');
        $this->db->where('deleted', 'N');
        //$this->db->where_not_in('code',array('MSI','DI'));
        ///$this->db->where('code','RI');
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        //return $this->db->last_query(); die;
        // $query2->num_rows();
        return $query2->result();
    }

    public function GetallmanifestskuData($uid = null, $sid = null) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,sku,qty,expire_date,code');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $uid);
        $this->db->where('seller_id', $sid);
        $this->db->group_by('sku');
        //$this->db->where_not_in('code',array('MSI','DI'));
        $this->db->where('code', 'RI');
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        //echo  $this->db->last_query(); die;
        // $query2->num_rows();
        return $query2->result_array();
    }

    public function GetallmanifestskuData_new($uid = null, $sid = null, $sku = null) {
        $this->db->where('pickup_request.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('storage_table.super_id', $this->session->userdata('user_details')['super_id']);
        // $this->db->select('pickup_request.id,pickup_request.sku,pickup_request.qty,pickup_request.expire_date,pickup_request.code');


        $this->db->where('pickup_request.deleted','N');
        $this->db->select('pickup_request.id,pickup_request.uniqueid,pickup_request.sku,pickup_request.qty,items_m.id as item_sku, storage_table.storage_type,items_m.sku_size,items_m.item_path,pickup_request.seller_id,items_m.wh_id,pickup_request.expire_date');
        $this->db->from('pickup_request');
        $this->db->join('items_m', 'items_m.sku=pickup_request.sku');
        $this->db->join('storage_table', 'storage_table.id=items_m.storage_id');
        $this->db->where('pickup_request.uniqueid', $uid);
        $this->db->where('pickup_request.sku', $sku);
        $this->db->where('pickup_request.seller_id', $sid);
        $this->db->group_by('pickup_request.sku');
        //$this->db->where_not_in('code',array('MSI','DI'));
        $this->db->where('pickup_request.code', 'RI');
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        //  echo  $this->db->last_query(); die;
        // $query2->num_rows();
        return $query2->result_array();
    }

    public function GetmissingQtyCHeck($uid = null, $sid = null, $sku = null) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('id');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid', $uid);
        $this->db->where('seller_id', $sid);
        //$this->db->group_by('sku');
        //$this->db->where_not_in('code',array('MSI','DI'));
        $this->db->where_in('code', array('MSI', 'DI'));
        $this->db->where('sku', $sku);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        //echo  $this->db->last_query(); die;
        // $query2->num_rows();
        return count($query2->result_array());
    }

    public function GetallskuDetailsByOneGroupQry($mid = null) {
        $this->db->where('pickup_request.deleted','N');
        $this->db->where('pickup_request.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('SUM(pickup_request.qty-pickup_request.damage_qty-pickup_request.missing_qty) as qty ,pickup_request.sku,items_m.item_path,pickup_request.id as o_id');
        $this->db->from('pickup_request');
        $this->db->join('items_m', 'items_m.sku=pickup_request.sku');
        $this->db->where('pickup_request.uniqueid', $mid);
         $this->db->where("pickup_request.received_qty=0");
       // $this->db->where_not_in('pickup_request.code', array('DI', 'MSI', 'RI'));
        $this->db->group_by('pickup_request.sku');
        $query2 = $this->db->get();

        return $query2->result_array();
    }

    public function GetUpdateStaffAssignQry($data = array(), $mid = null) {
        return $this->db->update('pickup_request', $data, array('uniqueid' => $mid));

       // echo $this->db->last_query();
    }

    public function GetManifestUpdateDamageMissiing($data = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
         $this->db->update_batch('pickup_request', $data, 'id');
       //  echo $this->db->last_query();
    }

    public function GetallstockLocation_bk($sid = null, $limit = null, $stockArr = array(), $shelveLimit = 0, $totalsku_size = 0, $skuid = null, $otherMatchInventory = array()) {

        $this->db->where($otherMatchInventory);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('id,stock_location,quantity,shelve_no');
        $this->db->from('item_inventory');
        if (!empty($stockArr))
            $this->db->where_not_in('stock_location', $stockArr);
        $this->db->where('stock_location!=', '');
        $this->db->where('seller_id', $sid);
        $this->db->where('quantity<', $totalsku_size);
        $this->db->where('item_sku', $skuid);
        $this->db->order_by("id", "asc");
       // $this->db->limit($shelveLimit);
        $query3 = $this->db->get();
    //echo  $this->db->last_query(); die;
        if($query3->num_rows()>0)
        {
          return  $totalGetlocation = $query3->result_array();
        }
        else
        {
            return false;
        }


    
        

        
    }


    public function GetallstockLocation_new($sid = null, $limit = null, $stockArr = array(), $shelveLimit = 0, $totalsku_size = 0, $skuid = null, $otherMatchInventory = array(),$newQty=null) {

        $pending_location = $shelveLimit;
        $StockArr1 = array();
       
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('stock_location , '.$totalsku_size.' as quantity , 0 as filled, 0 as id, 0 as shelve_no');
            $this->db->from('stockLocation');
            $this->db->where('seller_id', $sid);
            // if (!empty($stockArr)) {
            //     $this->db->where_not_in('stock_location', $stockArr);
            // }
            $this->db->where('`stock_location` NOT IN (SELECT `stock_location` FROM `item_inventory` where super_id= '.$this->session->userdata('user_details')['super_id'].'  and stock_location!="" )', NULL, FALSE);
            if (!empty($pending_location))
                $this->db->limit($pending_location, 0);
            $query2 = $this->db->get();

            //echo  $this->db->last_query(); die;
            if ($query2->num_rows() > 0) {
                //echo  $this->db->last_query(); die;
                $locationArr2 = $query2->result_array();
            } else {
                $locationArr2 = array();
            }
        


        $finalArr = $locationArr2;
        // print_r($finalArr);
        return $finalArr;
    }

    public function GetCheckInventoryShelveNo($sid = null, $skuid = null, $shelveLimit = 0, $totalsku_size = 0, $shelveArr = array()) {

        //  echo $shelveLimit."rrrrrr";
        // $shelveLimit=2;
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('shelve_no as shelv_no,stock_location');
        $this->db->from('item_inventory');
        if (!empty($shelveArr))
            $this->db->where_not_in('shelve_no', $shelveArr);
        $this->db->where('shelve_no!=', '');
        $this->db->where('seller_id', $sid);
        $this->db->where('quantity<', $totalsku_size);
        $this->db->where('item_sku', $skuid);
        $this->db->order_by("id", "asc");
        $this->db->limit($shelveLimit);
        $query2 = $this->db->get();

        //echo  $this->db->last_query(); die;
        $totalGetShelve = $query2->num_rows();
        //$pending_shelve=$shelveLimit-$totalGetShelve;
        $pending_shelve = $shelveLimit;
        $shelveArr1 = array(); //$query2->result_array();
        if ($pending_shelve > 0 && $shelveLimit >= $pending_shelve) {

            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

            $this->db->select('shelv_no');
            $this->db->from('warehous_shelve_no_fm');
            $this->db->where('`shelv_no` NOT IN (SELECT `shelve_no` FROM `item_inventory`)', NULL, FALSE);
            if (!empty($shelveArr))
                $this->db->where_not_in('shelv_no', $shelveArr);
            //$this->db->where("shelv_no not in (select id from item_inventory where shelve_no!=shelv_no and seller_id='$sid')");

            $this->db->limit($pending_shelve);
            $query3 = $this->db->get();
            if ($query3->num_rows() > 0) {
                // echo  $this->db->last_query(); die;
                $shelveArr2 = $query3->result_array();
            } else {
                $shelveArr2 = array();
            }
        } else {
            $shelveArr2 = array();
        }


        $finalArr = array_merge_recursive($shelveArr1, $shelveArr2);
        // print_r($finalArr);
        return $finalArr;
    }

    public function getupdateconfirmstatus_new($uid = null, $data = array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('uniqueid', $uid);
         $this->db->update_batch("pickup_request", $data, 'sku');

        //echo  $this->db->last_query(); 

    }

    public function getPreQuantity($id=null) {

        
        $this->db->select('quantity');
        $this->db->from('item_inventory');
       
        $this->db->where('id', $id);
        $query3 = $this->db->get();
       // echo  $this->db->last_query(); 
         if ($query3->num_rows() > 0) {
            return $query3->row_array();
         }
        
    }

    public function GetCheckValidShelveNoIn($shelve_no = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('shelv_no');
        $this->db->from('warehous_shelve_no_fm');
        $this->db->where('`shelv_no` NOT IN (SELECT `shelve_no` FROM `item_inventory`)', NULL, FALSE);
        $this->db->where('shelv_no', $shelve_no);
        $query3 = $this->db->get();
       // echo  $this->db->last_query(); 
         if ($query3->num_rows() > 0) {
             return true;
         }
         else
         {
             return false;
         }
    }

    public function GetMidDetailsQry($mid = null) {

        $this->db->where('pickup_request.deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid ', $mid);
        $query = $this->db->get();
      //  echo $this->db->last_query(); exit;
        return $query->row_array();
    }
    
     public function GetallcustomerManifestResults($uniqueid = null) {
        $this->db->where("uniqueid", $uniqueid);
        $this->db->where("deleted", 'N');
       
        $this->db->where("super_id", $this->session->userdata('user_details')['super_id']);
        $this->db->select('count(id) as newqty,`id`, `uniqueid`, `seller_id`, `sku`, `qty`, `assign_to`, `expire_date`, `req_date`, `pstatus`, `code`, `user_id`, `user_type`, `pickimg`, `on_hold`, `on_hold_date`, `itemupdated`, `confirmO`, `schedule_date`, `super_id`, `lat`, `lng`, `address`, `city`, `3pl_awb`, `3pl_name`, `3pl_label`, `3pl_date`, `boxes`, `pack_type`, `description`, `label_type`, `r_3pl_awb`, `r_3pl_name`, `r_3pl_label`, `r_3pl_date`, `return_type`,(select description from items_m where items_m.sku=pickup_request.sku limit 1) as m_des')->from('pickup_request');
        $this->db->group_by('sku,code');
        $query = $this->db->get();
        //echo $this->FULFIL->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }
    
    
    public function getupdateTempstock($qty=null,$seller_id=null,$sku=null)
    {
         
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('stocks');
        $this->db->where('sku ', $sku);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $this->db->query("update stocks set qty=qty+$qty where cust_id='$seller_id' and super_id='".$this->session->userdata('user_details')['super_id']."' and sku='$sku'");
        }
        else
        {
            $this->db->insert('stocks',array('sku'=>$sku,'cust_id'=>$seller_id,'super_id'=>$this->session->userdata('user_details')['super_id'],'qty'=>$qty));
        }
    }
        public function getupdateTempstock_deducted($sku_id=null,$seller_id=null,$sku=null)
    {
         
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,qty');
        $this->db->from('stocks');
        $this->db->where('sku ', $sku);
        $query = $this->db->get();
         $qty_new=$this->Gettotalstock($sku_id,$seller_id);
        if ($query->num_rows() > 0) {
             //$result = $query->row_array();
             
          
            $this->db->query("update stocks set qty='$qty_new' where cust_id='$seller_id' and super_id='".$this->session->userdata('user_details')['super_id']."' and sku='$sku'");
        }
        else
        {
            $this->db->insert('stocks',array('sku'=>$sku,'cust_id'=>$seller_id,'super_id'=>$this->session->userdata('user_details')['super_id'],'qty'=>$qty_new));
        }
    }
    
        public function GetTempStockData($sku = null, $cust_id = null) {

        $this->db->select('*');
        $this->db->from('stocks');
        $this->db->where('sku', $sku);
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('cust_id', $cust_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
     public function Gettotalstock($sku = null, $cust_id = null) {

        $this->db->select('SUM(quantity) as tqty');
        $this->db->from('item_inventory');
        $this->db->where('item_sku', $sku);
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('seller_id', $cust_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['tqty'];
    }

}
