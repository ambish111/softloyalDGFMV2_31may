<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function insertStockLocation(array $data) {

        // return $data; exit;
        $query1 = $this->db->insert_batch('item_inventory_new', $data);
        return $this->db->last_query();
        exit;
    }

    public function inserttodsLocation(array $data) {

        // return $data; exit;
        $query1 = $this->db->insert_batch('tods_tbl', $data);
        return $this->db->last_query();
    }

    public function stockLocationFilter($data = array()) {

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);

        if ($data['type'] == 'AS') {
            $this->db->where("seller_id>0");
        }
        if ($data['type'] == 'UN') {
            $this->db->where("seller_id=0");
        }
        $this->db->select('`item_inventory_new`.`stock_location`');
        $this->db->from('item_inventory_new');

        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory_new.stock_location', $data['stock_location']);
        }



        $this->db->order_by('item_inventory_new.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->stockLocationFilterCount($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function stockLocationFilterCount($data = array()) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('item_inventory_new');

        if ($data['type'] == 'AS') {
            $this->db->where("seller_id>0");
        }
        if ($data['type'] == 'UN') {
            $this->db->where("seller_id=0");
        }


        if (!empty($data['stock_location'])) {
            $this->db->where('stock_location', $data['stock_location']);
        }


        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function todsfiltershowQry($data = array()) {

        $limit = ROWLIMIT;
        if (empty($data['page_no'])) {
            $start = 0;
        } else {
            $start = ($data['page_no'] - 1) * $limit;
        }
        $this->db->where('tods_tbl.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('*');
        $this->db->from('tods_tbl');
        $this->db->order_by('tods_tbl.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->todsCount($data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function selveFilter($city, $shelve, $page_no, $querycheck = false) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); // return "ssssssss"; die;
        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        $this->db->select('*');

        $this->db->from('warehous_shelve_no_fm');
        //$this->db->where_not_in('shelv_no','(select shelve_no from item_inventory)');


        if (!empty($city)) {
            $city = array_filter($city);

            $this->db->where_in('city_id', $city);
        }

        if (!empty($shelve)) {
            $this->db->where('shelv_no', $shelve);
        }


        $this->db->order_by('warehous_shelve_no_fm.id', 'ASC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();

        //return $this->db->last_query();exit;

        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->shelveFilterCount($city, $shelve, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function shelveFilterCount($city, $shelve, $page_no) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('warehous_shelve_no_fm');
        if (!empty($city)) {
            $city = array_filter($city);

            $this->db->where_in('city_id', $city);
        }
        if (!empty($shelve)) {
            $this->db->where('shelv_no', $shelve);
        }
        //$this->db->group_by('pickupId');
        $this->db->order_by('warehous_shelve_no_fm.id', 'ASC');

        $query = $this->db->get();

        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {

            $data = $query->num_rows();
            return $data;
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function add_bulk_shelve_data($data) {

        if ($this->db->insert_batch('warehous_shelve_no_fm', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkShelve($id = null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        if ($id != null)
            $this->db->where('shelv_no', $id);
        $query = $this->db->get('warehous_shelve_no_fm');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function GetcheckshelaveUse($shelv_no = null) {
        $query = $this->db->select('seller_id')->from('item_inventory')->where('super_id', $this->session->userdata('user_details')['super_id'])->where('shelve_no', $shelv_no)->where("quantity>0")->limit(1)->get();
        // echo $this->db->last_query();
        return $query->row_array()['seller_id'];
    }

    public function chekinventoryshalveno($stocklocation = null) {
        $query = $this->db->select('shelve_no,seller_id')->from('item_inventory')->where('stock_location', $stocklocation)->where('super_id', $this->session->userdata('user_details')['super_id'])->where("quantity>0")->get();
        // echo $this->db->last_query(); die;
        return $query->row_array();
    }

    public function filterUpdate($page_no, $filterarray = array()) {


        $this->db->where('pickup_request.deleted', 'N');
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

    public function GetallmanifestskuData_new($uid = null, $sid = null, $sku = null) {
        $this->db->where('pickup_request.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('storage_table.super_id', $this->session->userdata('user_details')['super_id']);
        // $this->db->select('pickup_request.id,pickup_request.sku,pickup_request.qty,pickup_request.expire_date,pickup_request.code');


        $this->db->where('pickup_request.deleted', 'N');
        $this->db->select('pickup_request.id,pickup_request.uniqueid,pickup_request.sku,pickup_request.qty,pickup_request.missing_qty,pickup_request.damage_qty,pickup_request.received_qty,items_m.id as item_sku, storage_table.storage_type,items_m.sku_size,items_m.item_path,pickup_request.seller_id,items_m.wh_id,pickup_request.expire_date');
        $this->db->from('pickup_request');
        $this->db->join('items_m', 'items_m.sku=pickup_request.sku');
        $this->db->join('storage_table', 'storage_table.id=items_m.storage_id');
        $this->db->where('pickup_request.uniqueid', $uid);
        $this->db->where('pickup_request.sku', $sku);
        $this->db->where('pickup_request.confirmO', 'N');
        $this->db->where("pickup_request.received_qty>0");

        $this->db->where('pickup_request.seller_id', $sid);
        $this->db->group_by('pickup_request.sku');
        //$this->db->where_not_in('code',array('MSI','DI'));
        $this->db->where('pickup_request.code', 'RI');
        //$this->db->where('sku',$data['sku']);
        //$this->db->order_by("id", "asc");
        //$this->db->limit(1, 0);
        $query2 = $this->db->get();

        // echo  $this->db->last_query(); die;
        // $query2->num_rows();
        return $query2->result_array();
    }

    public function GetcheckLocation($data = array()) {
        if (!empty($data['stockLocation'])) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where('item_inventory_new.stock_location', trim($data['stockLocation']));
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function GetcheckshelvekLocation($data = array()) {
        if (!empty($data['shelveNo'])) {
            $this->db->where('warehous_shelve_no_fm.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('warehous_shelve_no_fm');
            $this->db->where('warehous_shelve_no_fm.shelv_no', trim($data['shelveNo']));
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->row_array();
        } else {
            return array();
        }
    }

    public function GetcheckLocationData($location = null) {
        if (!empty($location)) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where_in('item_inventory_new.stock_location', $location);
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->num_rows(); //$query->row_array();
        } else {
            return array();
        }
    }

    public function GetUpdateInventory($updateQry = array(), $updateQry_w = array(), $qty = null) {
        if ($qty > 0) {
            $this->db->set('quantity', '`quantity`+' . $qty, FALSE);
            $this->db->update("item_inventory_new", $updateQry, $updateQry_w);
        }
        return true;
    }

    public function AddInventoryHistory($data = array()) {
        $this->db->insert("inventory_activity_new", $data);
        return true;
    }

    public function getupdateconfirmstatus_new($uid = null, $data = array(), $sku = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('uniqueid', $uid);
        $this->db->where('sku', $sku);
        return $this->db->update("pickup_request", $data);

        //echo  $this->db->last_query(); 
    }

    public function addcustomerInventory($data = array()) {
        $this->db->insert("receive_inventory", $data);
        return true;
    }

    public function upcustomerInventory($data = array(), $data_w) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update("receive_inventory", $data, array("id" => $data_w['id']));
        return true;
    }

    public function inventory_activity_user($data = array()) {
        $this->db->insert("inventory_activity_user", $data);
        return true;
    }

    public function inventory_activity_user_batch($data = array()) {
        $this->db->insert_batch("inventory_activity_user", $data);
        return true;
    }

    public function AddInventoryHistory_batch($data = array()) {
        $this->db->insert_batch("inventory_activity_new", $data);
        return true;
    }

    public function insertStockLocation_single(array $data) {

        // return $data; exit;
        $this->db->insert('item_inventory_new', $data);
        return $this->db->last_query();
        exit;
    }

    public function GetcheckLocationData_print($location = null) {
        if (!empty($location)) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('stock_location');
            $this->db->from('item_inventory_new');
            $this->db->where_in('item_inventory_new.stock_location', $location);
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->result_array(); //$query->row_array();
        } else {
            return array();
        }
    }

    public function exportlocation($filter) {

        $this->load->dbutil();
        $limit = 2000;
        $start = $filter['exportlimit'] - $limit;
        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        if (isset($filter['stock_location']) && !empty($filter['stock_location'])) {
            $this->db->where('item_inventory_new.stock_location', $filter['stock_location']);
        }
        $selectQry[] = " item_inventory_new.stock_location AS StockLocation";
        $select_str = implode(',', $selectQry);
        $this->db->select($select_str);
        $this->db->from('item_inventory_new');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function GetcheckLocationData_add($location = null) {
        if (!empty($location)) {
            $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->select('*');
            $this->db->from('item_inventory_new');
            $this->db->where('item_inventory_new.quantity', 0);
            $this->db->where('item_inventory_new.seller_id', 0);
            $this->db->where('item_inventory_new.item_sku', 0);
            $this->db->where('item_inventory_new.stock_location', $location);
            $query = $this->db->get();
            //  echo $this->db->last_query(); die;
            return $query->row_array(); //$query->row_array();
        } else {

            return array();
        }
    }

    public function GetUpdateInventory_bulk(array $data, $st_location = null, $id = null) {

        // return $data; exit;
        $this->db->where('item_inventory_new.id', $id);
        $this->db->where('item_inventory_new.stock_location', $st_location);
        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update('item_inventory_new', $data);
    }
    
    public function inventoryCheckQry($data = array()) {
        $this->db->where('item_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('storage_table.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory.*,customer.company as cust_name,items_m.sku,items_m.sku_size,customer.id as cust_id,storage_table.storage_type');
        $this->db->from('item_inventory_new as item_inventory');
        $this->db->join('customer', 'customer.id = item_inventory.seller_id');
        $this->db->join('items_m', 'items_m.id = item_inventory.item_sku');
        $this->db->join('storage_table', 'storage_table.id = items_m.storage_id');

        $this->db->where('customer.id', $data['cust_name']);
        $this->db->where('item_inventory.stock_location!=', '');
        //$this->db->group_by('item_inventory.seller_id');
        $query = $this->db->get();
        $return = $query->result_array();
        // echo $this->db->last_query(); die;
        return $return;
    }

}
