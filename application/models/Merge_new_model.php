<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Merge_new_model extends CI_Model {

    function __construct() {
        parent::__construct();
       
    }
    public function check($param=array()) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.sku', trim($param['sku']));
        $this->db->where('item_inventory.quantity<items_m.sku_size');
        $this->db->where('item_inventory.seller_id',$param['seller_id']);
        $this->db->where('item_inventory.stock_location', trim($param['stock_location']));
        $this->db->select('item_inventory.id , items_m.sku, item_inventory.stock_location,item_inventory.quantity , items_m.name,item_inventory.shelve_no');
        $this->db->from('item_inventory_new as item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');

        $query = $this->db->get();
       // echo $this->db->last_query();

            return $query->result_array();
        
    }

    public function Getallstocklocationdata_viewpage($seller_id = null,$stockLoction) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('stock_location',trim($stockLoction));
        $this->db->where('seller_id', 0);
        $this->db->where('item_sku',0);
        $this->db->where('quantity',0);
        $query = $this->db->select('id,stock_location,0 as quantity')->get('item_inventory_new');
        return $query->result_array();
        
    }
    public function filter($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no = null, $storage_id, $data = array()) {

        $page_no;
        $limit = 100;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.id,item_inventory.item_sku,item_inventory.shelve_no,item_inventory.stock_location , items_m.sku , item_inventory.quantity,item_inventory.update_date,item_inventory.expity_date,item_inventory.expiry , items_m.name,seller_m.name as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory.wh_id,item_inventory.seller_id,items_m.item_path');
        $this->db->from('item_inventory_new as item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        //$this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');
        // $this->db->where('item_inventory.quantity>',0);
         $this->db->where('seller_m.access_fm', 'Y');

        
          $this->db->where('item_inventory.quantity<items_m.sku_size');
          
        
        if (!empty($exact)) {
            $date = date("Y-m-d", strtotime($exact));
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $date = date("Y-m-d", strtotime($from));
            $date = date("Y-m-d", strtotime($to));
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


        //echo $quantity;

        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory.quantity', $quantity);
        }

        if (!empty($shelve_no)) {
            $this->db->where('item_inventory.shelve_no', $shelve_no);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }
       
            $this->db->where('items_m.sku', trim($sku));
       

        
            $this->db->where('seller_m.id', $seller);
        

        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $data['stock_location']);
        }

        if (!empty($data['wh_name'])) {
            $this->db->where('warehouse_category.name', $data['wh_name']);
        }

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory.expiry', $data['expiry']);
        }


        $this->db->order_by('item_inventory.id', 'DESC');


        $this->db->limit($limit, $start);

        $query = $this->db->get();

         // echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['query'] = "";
            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount($quantity, $sku, $seller, $to, $from, $exact, $page_no,$data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['query'] = "";
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }
    public function count_all($date = null) {

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->select('quantity')->get('item_inventory_new');
        //echo $this->db->last_query(); die;
        $count = 0;
        if ($query->num_rows() > 0) {

            for ($i = 0; $i < $query->num_rows(); $i++) {
                $count += $query->result()[$i]->quantity;
            }
            return $count;
        }
    }
     public function filterCount($quantity, $sku, $seller, $to, $from, $exact, $page_no,$data=array()) {

        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        }
        $this->db->select('COUNT(item_inventory.id) as idCount');
        $this->db->from('item_inventory_new as item_inventory');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory.seller_id');
        // $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');
        // $this->db->where('item_inventory.quantity>',0);
        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory.update_date)', $exact);
        }
        
        if (!empty($data['shelve_no'])) {
            $this->db->where('item_inventory.shelve_no', $data['shelve_no']);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }

         $this->db->where('item_inventory.quantity<items_m.sku_size');


        
        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory.quantity', $quantity);
        }


       
            $this->db->where('items_m.sku', trim($sku));
        

        
            $this->db->where('seller_m.id', $seller);
        
        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory.stock_location', $data['stock_location']);
        }

        if (!empty($data['wh_name'])) {
            $this->db->where('warehouse_category.name', $data['wh_name']);
        }

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory.expiry', $data['expiry']);
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
    public function GetstorageTypes() {
        $this->db->where('storage_table.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('storage_table');
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();


        return $query->result_array();
    }
    
    public function updatingData($data=array())
    {
      $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
      return $this->db->update_batch('item_inventory_new',$data,'id');
    }
    
    public function insertstockData($data=array())
    {
        $this->db->insert('item_inventory_new',$data);
    }
    public function inserthistoryData($data=array())
    {
        $this->db->insert_batch('inventory_activity_new',$data);
    }
    
    
    

}
