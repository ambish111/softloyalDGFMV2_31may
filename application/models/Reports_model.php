<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }
    
    function GetClientReportDispatchQry($postData=array())
    {
        $limit=ROWLIMIT;
        if (empty($postData['page_no'])) {
            $start = 0;
        } else {
            $start = ($postData['page_no'] - 1) * $limit;
        }
         if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        
        
         if($postData['wh_id'])
        {
            $this->db->where('shipment_fm.wh_id', $postData['wh_id']); 
        }
        
        if($postData['slip_no'])
        {
            $this->db->where('shipment_fm.slip_no', $postData['slip_no']); 
        }
        
        if($postData['destination'])
        {
            $this->db->where('shipment_fm.destination', $postData['destination']); 
        }
         if($postData['booking_id'])
        {
            $this->db->where('shipment_fm.booking_id', $postData['booking_id']); 
        }
        if($postData['cc_id'])
        {
            $this->db->where('shipment_fm.frwd_company_id', $postData['cc_id']); 
        }
        if($postData['mode'])
        {
            $this->db->where('shipment_fm.mode', $postData['mode']); 
        }
        
        if(!empty($postData['year']))
        {
            $this->db->where('YEAR(shipment_fm.entrydate)', $postData['year']); 
        }
        if(!empty($postData['month']))
        {
            $this->db->where('MONTH(shipment_fm.entrydate)', $postData['month']); 
        }
        if(!empty($postData['day']))
        {
            $this->db->where('DAY(shipment_fm.entrydate)', $postData['day']); 
        }
        $fulfillment='Y';
         $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('shipment_fm.id,shipment_fm.service_id,shipment_fm.booking_id,shipment_fm.slip_no,status_main_cat_fm.main_status,customer.name,customer.company,customer.seller_id,customer.uniqueid,shipment_fm.entrydate,shipment_fm.origin,shipment_fm.destination,shipment_fm.reciever_name,shipment_fm.reciever_address,shipment_fm.reciever_phone,`shipment_fm.sender_name`, `shipment_fm.sender_address`, `shipment_fm.sender_phone`,`shipment_fm.order_type`, `shipment_fm.sender_email`, `shipment_fm.mode`, `shipment_fm.total_cod_amt`,shipment_fm.weight,shipment_fm.pieces,shipment_fm.cust_id,shipment_fm.shippers_ac_no,shipment_fm.frwd_company_awb,shipment_fm.frwd_company_id,shipment_fm.wh_id,shipment_fm.frwd_company_label,shipment_fm.frwd_date,shipment_fm.is_menifest');
        $this->db->from('shipment_fm');
        $this->db->join('status_fm','status_fm.slip_no = shipment_fm.slip_no');
         $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        $this->db->where('shipment_fm.backorder', 0);
        $this->db->where('status_fm.code', 'DL');
         //$this->db->group_by('status_fm.slip_no');
         $this->db->order_by('shipment_fm.id','desc');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.cust_id', $postData['cust_id']);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
      //  echo $this->db->last_query(); die;
       if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($postData);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
        public function shipmCount($postData=array()) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

         if($postData['wh_id'])
        {
            $this->db->where('shipment_fm.wh_id', $postData['wh_id']); 
        }
        if($postData['slip_no'])
        {
            $this->db->where('shipment_fm.slip_no', $postData['slip_no']); 
        }
        
        if($postData['destination'])
        {
            $this->db->where('shipment_fm.destination', $postData['destination']); 
        }
         if($postData['booking_id'])
        {
            $this->db->where('shipment_fm.booking_id', $postData['booking_id']); 
        }
        if($postData['cc_id'])
        {
            $this->db->where('shipment_fm.frwd_company_id', $postData['cc_id']); 
        }
        if($postData['mode'])
        {
            $this->db->where('shipment_fm.mode', $postData['mode']); 
        }
        
        if(!empty($postData['year']))
        {
            $this->db->where('YEAR(shipment_fm.entrydate)', $postData['year']); 
        }
        if(!empty($postData['month']))
        {
            $this->db->where('MONTH(shipment_fm.entrydate)', $postData['month']); 
        }
        if(!empty($postData['day']))
        {
            $this->db->where('DAY(shipment_fm.entrydate)', $postData['day']); 
        }
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
         $this->db->join('status_fm','status_fm.slip_no = shipment_fm.slip_no');
       
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
         //$this->db->group_by('status_fm.slip_no');
        
         $this->db->where('status_fm.code', 'DL');


        $query = $this->db->get();

        //echo $this->db->last_query(); die;  
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }
    
     public function all_3pl($data=array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        
        if(!empty($data['cc_id']))
        {
           $this->db->where('id', $data['cc_id']);  
        }
        $this->db->order_by('company');
        $this->db->select('*');
        $query = $this->db->get('courier_company');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }
    
     public function GetallperformationDetailsQry_3pl($frwd_throw = null, $status = null, $from = null, $to = null) {
     if ($frwd_throw != 0)
            $condition .= " and shipment_fm.frwd_company_id='" . $frwd_throw . "'";
        //$objSmarty->assign("frwd_throw", $_REQUEST['frwd_throw']);

        $from_date = $from;
        $to_date = $to;
        if ($from_date != 0 && $to_date != 0) {
            $condition .= " and DATE(shipment_fm.entrydate) BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
        }

        $delivered = $status;
        if ($delivered == 'running') {
            $condition .= " and  shipment_fm.delivered in(1,2,3,4,5)";
        } else {
            $condition .= " and shipment_fm.delivered='$delivered'";
        }

        $query = $this->db->query("SELECT courier_company.company,shipment_fm.* FROM shipment_fm join courier_company on shipment_fm.frwd_company_id= courier_company.id WHERE  shipment_fm.deleted='N' and shipment_fm.status='Y' and shipment_fm.super_id='".$this->session->userdata('user_details')['super_id']."'   $condition");
        // echo $this->db->last_query(); 
        return $query->result_array();
    }
    
    
    
      public function GetdimationDetails($slip_no=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('slip_no', $slip_no);
       
        
        $this->db->select('sku,piece,cod,deducted_shelve');
        $query = $this->db->get('diamention_fm');
        //cho $this->db->last_query(); die;
      
            return $query->result_array();
       
    }
    
    
     public function GetallperformationDetailsQry_filter($data = array()) {
        //$frwd_throw = null, $status = null, $from = null, $to = null

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }

        if ($data['frwd_throw'] != 0) {
            $condition_id = "shipment_fm.frwd_company_id='" . $data['frwd_throw'] . "'";
            $this->db->where($condition_id);
        }
        //$objSmarty->assign("frwd_throw", $_REQUEST['frwd_throw']);

        $from_date = $data['from'];
        $to_date = $data['to'];
        if ($from_date != 0 && $to_date != 0) {
            $condition_date = " DATE(shipment_fm.entrydate) BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
            $this->db->where($condition_date);
        }

        $delivered = $data['status'];
        if ($delivered == 'running') {
            $condition_del = " shipment_fm.delivered in(1,2,3,4,5)";
            $this->db->where($condition_del);
        } else {
            $condition_del = " shipment_fm.delivered='$delivered'";
            $this->db->where($condition_del);
        }

        if ($data['searchval']) {
            $searchval = trim($data['searchval']);
            $this->db->where("(shipment_fm.slip_no='$searchval' or shipment_fm.frwd_company_awb='$searchval')");
        }
        $this->db->where('shipment_fm.super_id',$this->session->userdata('user_details')['super_id']);
        $this->db->select('courier_company.company,status_main_cat_fm.main_status,shipment_fm.*');
        $this->db->from('shipment_fm');
        $this->db->join('courier_company', 'shipment_fm.frwd_company_id= courier_company.cc_id', 'left');
        $this->db->join('status_main_cat_fm', 'shipment_fm.delivered= status_main_cat_fm.id', 'left');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->group_by("shipment_fm.slip_no");
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data1['result'] = $query->result_array();
            $data1['count'] = $this->GetallperformationDetailsQry_filter_count($data);
            return $data1;
            // return $page_no.$this->db->last_query();
        } else {
            $data1['result'] = '';
            $data1['count'] = 0;
            return $data1;
        }


//        $query = $this->db->query("SELECT courier_company.company,shipment_fm.* FROM shipment_fm join courier_company on shipment_fm.frwd_company_id= courier_company.id WHERE  shipment_fm.deleted='N' and shipment_fm.status='Y'   $condition");
//        // echo $this->db->last_query(); 
//        return $query->result_array();
    }

    public function GetallperformationDetailsQry_filter_count($data = array()) {
        if ($data['frwd_throw'] != 0) {
            $condition_id = "shipment_fm.frwd_company_id='" . $data['frwd_throw'] . "'";
            $this->db->where($condition_id);
        }
        //$objSmarty->assign("frwd_throw", $_REQUEST['frwd_throw']);

        $from_date = $data['from'];
        $to_date = $data['to'];
        if ($from_date != 0 && $to_date != 0) {
            $condition_date = " DATE(shipment_fm.entrydate) BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
            $this->db->where($condition_date);
        }

        $delivered = $data['status'];
        if ($delivered == 'running') {
            $condition_del = " shipment_fm.delivered in(1,2,3,4,5)";
            $this->db->where($condition_del);
        } else {
            $condition_del = " shipment_fm.delivered='$delivered'";
            $this->db->where($condition_del);
        }
        if ($data['searchval']) {
            $searchval = trim($data['searchval']);
            $this->db->where("(shipment_fm.slip_no='$searchval' or shipment_fm.frwd_company_awb='$searchval')");
        }
        $this->db->where('shipment_fm.super_id',$this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('courier_company', 'shipment_fm.frwd_company_id= courier_company.cc_id', 'left');
        $this->db->where('shipment_fm.deleted', 'N');
        $this->db->where('shipment_fm.status', 'Y');
        $this->db->group_by("shipment_fm.slip_no");

        $query = $this->db->get();
       // echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
           // $data = $query->result_array();
            return $query->num_rows() ;
            // return $page_no.$this->db->last_query();
        }
        else
        {
           return 0;  
        }
       
    }
  public function filter_damage($data = array()) {




        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('damage_history.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('damage_history.*,items_m.sku,items_m.name,seller_m.company as seller_name,seller_m.id as sid,items_m.item_path,items_m.storage_id,items_m.type,items_m.sku_size,items_m.description');
        $this->db->from('damage_history');
        $this->db->join('items_m', 'items_m.id = damage_history.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = damage_history.seller_id');

        if (!empty($data['sku'])) {
            $this->db->where('items_m.sku', $data['sku']);
        }


       // $this->db->where('damage_history.status_type', 'Damage');

        if (!empty($data['seller'])) {
            $this->db->where('seller_m.id', $data['seller']);
        }
        
        if ($data['quantity'] || $data['quantity'] == '0') {
            $this->db->where('damage_history.return_status', $data['quantity']);
        }
        if (!empty($data['order_no'])) {
            $this->db->where('damage_history.order_no', $data['order_no']);
        }
        if (!empty($data['return_status'])) {
            $this->db->where('damage_history.return_status', $data['return_status']);
        }
        
        //$this->db->group_by('damage_history.item_sku');
        $this->db->order_by('damage_history.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_damage($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
    public function filterCount_damage($data = array()) {

        $this->db->where('damage_history.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('COUNT(damage_history.id) as idCount');
        $this->db->from('damage_history');
        $this->db->join('items_m', 'items_m.id = damage_history.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = damage_history.seller_id');

        if (!empty($data['sku'])) {
            $this->db->where('items_m.sku', $data['sku']);
        }
        ///$this->db->group_by('inventory_damage.item_sku');
       // $this->db->where('damage_history.status_type', 'Damage');
 if (!empty($data['order_no'])) {
            $this->db->where('damage_history.order_no', $data['order_no']);
        }
        if (!empty($data['seller'])) {
            $this->db->where('seller_m.id', $data['seller']);
        }
if ($data['quantity'] || $data['quantity'] == '0') {
            $this->db->where('damage_history.quantity', $data['quantity']);
        }
        if (!empty($data['return_status'])) {
            $this->db->where('damage_history.return_status', $data['return_status']);
        }

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {


            return $query->row_array()['idCount'];
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    public function filter_damage_history($data = array()) {




        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('damage_history_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('damage_history_new.*,items_m.sku,items_m.name,seller_m.company as seller_name,seller_m.id as sid,items_m.item_path,items_m.storage_id,items_m.type,items_m.sku_size,items_m.description');
        $this->db->from('damage_history_new');
        $this->db->join('items_m', 'items_m.id = damage_history_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = damage_history_new.seller_id');

        if (!empty($data['sku'])) {
            $this->db->where('items_m.sku', $data['sku']);
        }


       // $this->db->where('damage_history.status_type', 'Damage');

        if (!empty($data['seller'])) {
            $this->db->where('seller_m.id', $data['seller']);
        }
        
        if ($data['quantity'] || $data['quantity'] == '0') {
            $this->db->where('damage_history_new.quantity', $data['quantity']);
        }
        if (!empty($data['order_no'])) {
            $this->db->where('damage_history_new.order_no', $data['order_no']);
        }
        
        //$this->db->group_by('damage_history.item_sku');
        $this->db->order_by('damage_history_new.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_damage($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    
        public function GetallPackagingQuery($page_no, $slip_no = null, $otherData = array()) {
        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        // $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('packing_serial');
       
        if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entry_date) BETWEEN "' . $otherData['packing_serial'] . '" and "' . $otherData['todate'] . '"');
        }
        if (!empty($slip_no)) {
            $this->db->where('slip_no', $slip_no);
        }

        // $this->db->group_by('entrydate');    
        $tempdb = clone $this->db;
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->GetallPackagingQueryCount($slip_no,$otherData);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function GetallPackagingQueryCount($slip_no = null,$otherData = array()) {
        //  $this->db->where('deleted','N');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(id) as sh_count');
        $this->db->from('packing_serial');
       if (!empty($otherData['fromdate']) && !empty($otherData['todate'])) {
            $this->db->where('DATE(entry_date) BETWEEN "' . $otherData['fromdate'] . '" and "' . $otherData['todate'] . '"');
        }
        if (!empty($slip_no)) {
            $this->db->where('slip_no', $slip_no);
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }
    
    public function getPackagingExcelReport($filter = array()){
      
        $selectQry .= " packing_serial.slip_no as AWB, packing_serial.sku, packing_serial.piece as quantity, user.username as UpdatedBy, packing_serial.entry_date as Date,REPLACE( REPLACE( REPLACE( packing_serial.serial_no, '[', '' ), ']', '' ), '\"', '' ) as serial_no";  
        $this->db->where('packing_serial.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select($selectQry);
        $this->db->from('packing_serial');
        $this->db->join('user', 'user.id=packing_serial.updated_by');
        
        if (!empty($filter['filterData']['from_date']) && !empty($filter['filterData']['to_date'])) {
            $this->db->where('DATE(package_report.entry_date) BETWEEN "' . $filter['filterData']['from_date'] . '" and "' . $filter['filterData']['to_date'] . '"');
        }
        if (!empty($filter['filterData']['slip_no'])) {
            $this->db->where('packing_serial.slip_no', $filter['filterData']['slip_no']);
        }
        $limit = 2000;   
        $start = $filter['filterData']['exportlimit'] - $limit; 
        $this->db->limit($limit, $start); 

      //  $this->db->limit($limit);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";
        $this->load->dbutil();

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

}
