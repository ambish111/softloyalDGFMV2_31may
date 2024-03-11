<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function filter($data = array()) {

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `name`, `no_of_orders`, `super_id`, `details`, `created_at`, `price`, `validity_days`, `updated_at`, `status`, `deleted`');
        $this->db->from('packages');

        if (!empty($data['name'])) {
            $this->db->where('name', trim($data['name']));
        }
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');

        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = 0;
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_assign($data = array()) {

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }

        $this->db->select('`id`, `cust_id`, `no_of_orders`, `order_limit`, `price`, `validity_days`, `super_id`, `entry_date`, `p_id`, `start_date`, `expiry`, `status`,expiry_status');
        $this->db->from('assign_package');

         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if (!empty($data['p_id'])) {
            $this->db->where('assign_package.p_id', trim($data['p_id']));
        }
        if (!empty($data['seller_id'])) {
            $this->db->where('assign_package.cust_id', trim($data['seller_id']));
        }
        if (!empty($data['status'])) {
            $this->db->where('assign_package.status', trim($data['status']));
        }

        //$this->db->where('status', 'Y');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->filter_assign_count($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    public function filter_assign_count($data = array()) {


        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if (!empty($data['p_id'])) {
            $this->db->where('assign_package.p_id', trim($data['p_id']));
        }
        if (!empty($data['seller_id'])) {
            $this->db->where('assign_package.cust_id', trim($data['seller_id']));
        }
        if (!empty($data['status'])) {
            $this->db->where('assign_package.status', trim($data['status']));
        }
        $this->db->select('COUNT(assign_package.id) as sh_count');
        $this->db->from('assign_package');

        if (!empty($data['name'])) {
            //$this->db->where('name', trim($data['name']));
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }
     public function filter_wallet($data = array()) {

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `cust_id`, `super_id`, `p_qty`, `new_qty`, `order_from`, `awb_no`, `booking_id`, `comment`, `type`, `added_by`,p_id,entry_date');
        $this->db->from('package_order_history');

        if (!empty($data['p_id'])) {
            $this->db->where('package_order_history.p_id', trim($data['p_id']));
        }
        if (!empty($data['seller_id'])) {
            $this->db->where('package_order_history.cust_id', trim($data['seller_id']));
        }
        if (!empty($data['status'])) {
            $this->db->where('package_order_history.type', trim($data['status']));
        }

        //$this->db->where('status', 'Y');
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;

        if ($query->num_rows() > 0) {
            $data['result'] = $query->result_array();
            $data['count'] = $this->filter_wallet_count($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filter_wallet_count($data = array()) {


        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if (!empty($data['p_id'])) {
            $this->db->where('package_order_history.p_id', trim($data['p_id']));
        }
        if (!empty($data['seller_id'])) {
            $this->db->where('package_order_history.cust_id', trim($data['seller_id']));
        }
        if (!empty($data['status'])) {
            $this->db->where('package_order_history.type', trim($data['status']));
        }
        $this->db->select('COUNT(package_order_history.id) as sh_count');
        $this->db->from('package_order_history');

        

        $query = $this->db->get();
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function add(array $data) {
        $this->db->insert("packages", $data);
        return $this->db->insert_id();
    }

    public function add_history(array $data) {
        return $this->db->insert("package_history", $data);
    }

    public function package_list($id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('`id`, `name`, `no_of_orders`,`details`, `price`, `validity_days`');
        $this->db->from('packages');
        if ($id > 0) {
            $this->db->where('id', $id);
        }
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function checkPackageActive($cust_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $date=date("Y-m-d");
        $this->db->select('id,expiry,order_limit');
        $this->db->from('assign_package');
        $this->db->where('cust_id', $cust_id);
        $this->db->where("order_limit>0");
        $this->db->where("DATE(expiry)>=",$date);
        $this->db->order_by("id",'desc');
       // $this->db->where('status', 'Y');
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function assign_customer(array $data) {
        return $this->db->insert("assign_package", $data);
    }

    public function package_assign_history(array $data) {
        return $this->db->insert("package_order_history", $data);
    }

    public function alllistexcelData($data = array()) {

        $this->load->dbutil();
        $this->load->helper('file');
        if (!empty($data['p_id'])) {
            $this->db->where('assign_package.p_id', trim($data['p_id']));
        }
        if (!empty($data['seller_id'])) {
            $this->db->where('assign_package.cust_id', trim($data['seller_id']));
        }
        if (!empty($data['status'])) {
            $this->db->where('assign_package.status', trim($data['status']));
        }
        $this->db->where('assign_package.super_id', $this->session->userdata('user_details')['super_id']);
        $selectQry = "";
        
        $selectQry .= " (select company from customer where customer.id=assign_package.cust_id) AS CUSTOMER_NAME ,";
        $selectQry .= " (select name from packages where packages.id=assign_package.p_id) AS PACKAGE_NAME ,";
        $selectQry .= " assign_package.no_of_orders AS Total Orders,";
        $selectQry .= " assign_package.order_limit AS Pending,";
        $selectQry .= " assign_package.validity_days AS Validity,";
        $selectQry .= " assign_package.entry_date AS Assign_date,";
        $selectQry .= " assign_package.start_date AS Start_date,";
        $selectQry .= " assign_package.expiry AS Expire_date,";
         $selectQry .= " IF (assign_package.status='Y','Active','Inactive') AS STATUS ,";
        $selectQry = rtrim($selectQry, ',');

        //echo $selectQry;die;
        $this->db->select($selectQry);

        $this->db->from('assign_package');
        $limit = 2000;
        $start = $data['exportlimit'] - $limit;
        $this->db->limit($limit, $start);
        //echo  $this->db->get_compiled_select(); exit;
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";
        
        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
     public function alllistexcelData_wallet($data = array()) {

        $this->load->dbutil();
        $this->load->helper('file');
        if (!empty($data['p_id'])) {
            $this->db->where('package_order_history.p_id', trim($data['p_id']));
        }
        if (!empty($data['seller_id'])) {
            $this->db->where('package_order_history.cust_id', trim($data['seller_id']));
        }
        if (!empty($data['status'])) {
            $this->db->where('package_order_history.type', trim($data['status']));
        }
        $this->db->where('package_order_history.super_id', $this->session->userdata('user_details')['super_id']);
        $selectQry = "";
        
        $selectQry .= " (select company from customer where customer.id=package_order_history.cust_id) AS CUSTOMER_NAME ,";
        $selectQry .= " (select name from packages where packages.id=package_order_history.p_id) AS PACKAGE_NAME ,";
        $selectQry .= " package_order_history.p_qty AS Previous_rders,";
        $selectQry .= " package_order_history.new_qty AS NEW_QTY,";
        $selectQry .= " package_order_history.awb_no AS AWB,";
        $selectQry .= " package_order_history.type AS Status,";
         $selectQry .= " (select company from user where user.id=package_order_history.added_by) AS Update_By ,";
        $selectQry .= " package_order_history.comment AS Comment,";
        $selectQry .= " package_order_history.entry_date AS DATE,";
       
        $selectQry = rtrim($selectQry, ',');

        //echo $selectQry;die;
        $this->db->select($selectQry);

        $this->db->from('package_order_history');
        $limit = 2000;
        $start = $data['exportlimit'] - $limit;
        $this->db->limit($limit, $start);
        //echo  $this->db->get_compiled_select(); exit;
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        $delimiter = ",";
        $newline = "\r\n";
        
        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }
    
    
     public function getSyncpackage($data = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $date=date("Y-m-d");
        $this->db->select('id,expiry,order_limit,cust_id');
        $this->db->from('assign_package');
        $this->db->where('cust_id', $data['seller_id']);
        $this->db->where('expiry_status','N');
        $this->db->where("order_limit>0");
        $this->db->where("DATE(expiry)>",$date);
        $this->db->order_by("id",'asc');
       // $this->db->where('status', 'Y');
        //$this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
        
    }
    public function activeplans($data = null) {
        
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $date=date("Y-m-d");
        $this->db->select('id,expiry,order_limit,cust_id');
        $this->db->from('assign_package');
        $this->db->where('cust_id', $data['seller_id']);
        $this->db->where('expiry_status','N');
        $this->db->where("order_limit>0");
        $this->db->where("DATE(expiry)>=",$date);
        //$this->db->order_by("id",'desc');
        $this->db->where('status', 'Y');
        //$this->db->limit(1);
        $query = $this->db->get();
       // echo $this->db->last_query(); die;
        return $query->row_array();
        
        
    }
    
    
    public function updateplan($data=array(),$data_w=array())
    {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("assign_package",$data,$data_w);
        
    }
    public function updatepackage($string=null)
    {
       return $this->db->query($string); 
    }
    
    

}
