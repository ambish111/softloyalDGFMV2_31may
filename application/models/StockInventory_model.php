<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class StockInventory_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function count_all($date = null) {

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->select('quantity')->get('item_inventory_new');
        // echo $this->db->last_query(); die;
        $count = 0;
        if ($query->num_rows() > 0) {

            for ($i = 0; $i < $query->num_rows(); $i++) {
                $count += $query->result()[$i]->quantity;
            }
            return $count;
        }
    }
public function edit_view($id) {
        $this->db->where('item_inventory_new.id', $id);
        $this->db->select('item_inventory_new.id , items_m.sku , item_inventory_new.quantity,item_inventory_new.update_date , items_m.name,seller_m.company as seller_name');
        $this->db->from('item_inventory_new');
        $this->db->join('items_m', 'items_m.id = item_inventory_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory_new.seller_id');
        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get();


        if ($query->num_rows() > 0) {
            // echo '<pre>';
            //   print_r( $query->result());
            //   echo '</pre>';
            //   exit();
            return $query->result();
        }
    }
    
     public function UpdateExpireDate($data = array(), $tid = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->where('id', $tid);
        $this->db->update('item_inventory_new', $data);
        ///echo $this->db->last_query(); die;
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

    
    public function find1() {

        $this->db->where('access_fm', 'Y');
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('customer');

        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function filter($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no = null, $storage_id, $data = array()) {

        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('item_inventory_new.id,item_inventory_new.item_sku,item_inventory_new.shelve_no,item_inventory_new.stock_location , items_m.sku , item_inventory_new.quantity,item_inventory_new.update_date,item_inventory_new.expity_date,item_inventory_new.expiry , items_m.name,seller_m.company as seller_name,items_m.description as item_description,seller_m.id as sid,item_inventory_new.wh_id,item_inventory_new.seller_id,items_m.item_path');
        $this->db->from('item_inventory_new');
        $this->db->join('items_m', 'items_m.id = item_inventory_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory_new.seller_id');
        //$this->db->join('warehouse_category', 'warehouse_category.id = item_inventory_new.wh_id');
        // if ($this->session->userdata('user_details')['user_type'] != 1) {
        //     $this->db->where('item_inventory_new.wh_id', $this->session->userdata('user_details')['wh_id']);
        // }

        if (!empty($exact)) {
            $date = date("Y-m-d", strtotime($exact));
            $this->db->where('DATE(item_inventory_new.update_date)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $date = date("Y-m-d", strtotime($from));
            $date = date("Y-m-d", strtotime($to));
            $where = "DATE(item_inventory_new.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


        //echo $quantity;

        if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory_new.quantity', $quantity);
        }

        if (!empty($shelve_no)) {
            $this->db->where('item_inventory_new.shelve_no', $shelve_no);
        }

        if (!empty($storage_id)) {
            $this->db->where('items_m.storage_id', $storage_id);
        }
        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory_new.stock_location', $data['stock_location']);
        }

        if (!empty($data['wh_id'])) {
            $this->db->where('item_inventory_new.wh_id', $data['wh_id']);
        }

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory_new.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory_new.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory_new.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory_new.expiry', $data['expiry']);
        }


        $this->db->order_by('item_inventory_new.id', 'DESC');

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //  echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount($quantity, $sku, $seller, $to, $from, $exact, $data);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount($quantity, $sku, $seller, $to, $from, $exact, $data = array()) {

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        // if ($this->session->userdata('user_details')['user_type'] != 1) {
        //     $this->db->where('item_inventory.wh_id', $this->session->userdata('user_details')['wh_id']);
        // }
        $this->db->select('COUNT(item_inventory_new.id) as idCount');
        $this->db->from('item_inventory_new');
        $this->db->join('items_m', 'items_m.id = item_inventory_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory_new.seller_id');
        // $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');

        if (!empty($exact)) {
            $this->db->where('DATE(item_inventory_new.update_date)', $exact);
        }

        if (!empty($data['wh_id'])) {
            $this->db->where('item_inventory_new.wh_id', $data['wh_id']);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(item_inventory_new.update_date) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }


         if ($quantity || $quantity == '0') {
            $this->db->where('item_inventory_new.quantity', $quantity);
        }

       

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }
        if (!empty($data['stock_location'])) {
            $this->db->where('item_inventory_new.stock_location', $data['stock_location']);
        }

       

        if (!empty($data['item_description'])) {
            $this->db->where('items_m.description', $data['item_description']);
        }

        if (!empty($data['update_date'])) {
            $date = date("Y-m-d", strtotime($data['update_date']));
            //$this->db->where("item_inventory_new.update_date like '".$date."%'"); 
            $this->db->where('DATE(item_inventory_new.update_date)', $data['update_date']);
        }

        if (!empty($data['expity_date'])) {
            $expity_date = date("Y-m-d", strtotime($data['expity_date']));
            $this->db->where('DATE(item_inventory_new.expity_date)', $expity_date);
        }

        if (!empty($data['expiry'])) {
            $this->db->where('item_inventory_new.expiry', $data['expiry']);
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

    public function StockInventoryExport($filter) {

        $this->load->dbutil();
        $limit = 2000;
        if (empty($filter['filterData']['exportlimit'])) {
            $start = 0;
        } else {
            $start = $filter['filterData']['exportlimit'] - $limit;
        }
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }

        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);

        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('seller_m.id', $filter['filterData']['seller']);
        }
        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('items_m.sku', $filter['filterData']['sku']);
        }
        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('item_inventory_new.quantity', $filter['filterData']['quantity']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['from'])) {
            $this->db->where('DATE(item_inventory_new.update_date) >=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['to']) && !empty($filter['filterData']['to'])) {
            $this->db->where('DATE(item_inventory_new.update_date) <=', $filter['filterData']['to']);
        }

        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(item_inventory_new.update_date)', $filter['filterData']['exact']);
        }
        if (isset($filter['filterData']['storage_id']) && !empty($filter['filterData']['storage_id'])) {
            $this->db->where('items_m.storage_id', $filter['filterData']['storage_id']);
        }
        if (isset($filter['filterData']['shelve_no']) && !empty($filter['filterData']['shelve_no'])) {
            $this->db->where('item_inventory_new.shelve_no', $filter['filterData']['shelve_no']);
        }
        if (isset($filter['filterData']['stock_location']) && !empty($filter['filterData']['stock_location'])) {
            $this->db->where('item_inventory_new.stock_location', $filter['filterData']['stock_location']);
        }


        // if (isset($filter['filterData']['wh_name']) && !empty($filter['filterData']['wh_name'])) {
        //     $this->db->where('warehouse_category.name', $filter['filterData']['wh_name']);
        // }

        if (isset($filter['filterData']['item_description']) && !empty($filter['filterData']['item_description'])) {
            $this->db->where('items_m.description', $filter['filterData']['item_description']);
        }
        if (isset($filter['filterData']['update_date']) && !empty($filter['filterData']['update_date'])) {
            $this->db->where('DATE(item_inventory_new.update_date)', $filter['filterData']['update_date']);
        }
        if (isset($filter['filterData']['expity_date']) && !empty($filter['filterData']['expity_date'])) {
            $this->db->where('item_inventory_new.expity_date', $filter['filterData']['expity_date']);
        }


        $selectQry = array();
        if (isset($filter['listData2']['name']) && !empty($filter['listData2']['name'])) {
            $selectQry[] = " (select name from items_m where items_m.id=item_inventory_new.item_sku) AS Name";
        }
        if (isset($filter['listData2']['sku']) && !empty($filter['listData2']['sku'])) {
            $selectQry[] = " (select sku from items_m where items_m.id=item_inventory_new.item_sku) AS ItemSku";
        }
        if (isset($filter['listData2']['item_type']) && !empty($filter['listData2']['item_type'])) {
            $selectQry[] = "(select type from items_m where items_m.id=item_inventory_new.item_sku) AS ItemType";
        }
        if (isset($filter['listData2']['storage_id']) && !empty($filter['listData2']['storage_id'])) {
            $selectQry[] = " (select storage_type from storage_table where storage_table.id=items_m.storage_id) AS StorageType";
        }
        if (isset($filter['listData2']['stock_location']) && !empty($filter['listData2']['stock_location'])) {
            $selectQry[] = " item_inventory_new.stock_location AS StockLocation";
        }
        if (isset($filter['listData2']['shelve_no']) && !empty($filter['listData2']['shelve_no'])) {
            $selectQry[] = " item_inventory_new.shelve_no AS Shelve NO";
        }
        // if (isset($filter['listData2']['wh_name']) && !empty($filter['listData2']['wh_name'])) {
        //     $selectQry[] = " (select name from warehouse_category where warehouse_category.id=item_inventory.wh_id) AS Warehouse";
        // }
        if (isset($filter['listData2']['quantity']) && !empty($filter['listData2']['quantity'])) {
            $selectQry[] = " item_inventory_new.quantity AS QUANTITY";
        }
        if (isset($filter['listData2']['seller_name']) && !empty($filter['listData2']['seller_name'])) {
            //$selectQry[] = " (select name from customer where customer.id=item_inventory.seller_id) AS SellerName";
            $selectQry[] = " (select company from customer where customer.id=item_inventory_new.seller_id) AS SellerName";
        }
        if (isset($filter['listData2']['item_description']) && !empty($filter['listData2']['item_description'])) {
            $selectQry[] = " (select description from items_m where items_m.id=item_inventory_new.item_sku) AS Description,";
        }
        if (isset($filter['listData2']['update_date']) && !empty($filter['listData2']['update_date'])) {
            $selectQry[] = "item_inventory_new.update_date as UpdateDate";
        }
        if (isset($filter['listData2']['expity_date']) && !empty($filter['listData2']['expity_date'])) {
            $selectQry[] = "item_inventory_new.expity_date as ExpityDate";
        }
        if (isset($filter['listData2']['expiry']) && !empty($filter['listData2']['expiry'])) {
            $selectQry[] = "item_inventory_new.expiry as Expity";
        }

        $select_str = implode(',', $selectQry);

        $this->db->select($select_str);

        $this->db->from('item_inventory_new');
        $this->db->join('items_m', 'items_m.id = item_inventory_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = item_inventory_new.seller_id');

        //$this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');
        //  $this->db->join('warehouse_category', 'warehouse_category.id = item_inventory.wh_id');

        $this->db->order_by('item_inventory_new.id', 'DESC');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function GetcheckvalidPalletNo($shelv_no = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id');
        $this->db->from('warehous_shelve_no_fm');
        $this->db->where('shelv_no', $shelv_no);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return true;
        } else
            return false;
    }

    public function GetcheckPalletInventry($shelve_no = null, $seller_id = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,seller_id');
        $this->db->from('item_inventory_new');
        $this->db->where('shelve_no', $shelve_no);

        $query = $this->db->get();
        //echo  $this->db->last_query();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
    }

    public function UpdateInventoryPallet($data = array(), $tid = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->where('id', $tid);
        return $this->db->update('item_inventory_new', $data);
        // echo $this->db->last_query(); die;
    }

    public function UpdateInventoryMissing($data = array(), $tid = null) {
        $this->db->where('id', $tid);
        return $this->db->update('item_inventory_new', $data);
        //echo $this->db->last_query(); die;  
    }

    public function filter_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no, $type) {



        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('inventory_activity_user.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('inventory_activity_user.id,inventory_activity_user.p_qty,inventory_activity_user.type,items_m.sku , inventory_activity_user.qty,inventory_activity_user.qty_used,inventory_activity_user.entrydate,items_m.name,seller_m.company as seller_name,items_m.description as item_description,users.username,inventory_activity_user.awb_no,users.id,inventory_activity_user.user_id as iuser_id,items_m.item_path,inventory_activity_user.comment');
        $this->db->from('inventory_activity_user');
        $this->db->join('items_m', 'items_m.id = inventory_activity_user.item_sku', 'left');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity_user.seller_id');
        $this->db->join('user as users', 'users.id = inventory_activity_user.user_id', 'left');

        // $this->db->where("qty_used>0");
        if (!empty($type)) {
            $this->db->where('inventory_activity_user.type', $type);
        }
        if (!empty($slip_no)) {
            $this->db->where('inventory_activity_user.awb_no', $slip_no);
        }
        if (!empty($exact)) {
            $this->db->where('DATE(inventory_activity_user.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(inventory_activity_user.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('inventory_activity_user.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        //  $this->db->group_by(array("inventory_activity_user.awb_no", "inventory_activity_user.type","inventory_activity_user.item_sku","inventory_activity_user.p_qty","inventory_activity_user.entrydate"));

        $this->db->order_by('inventory_activity_user.id', 'DESC');

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no) {



        $this->db->where('inventory_activity_user.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(inventory_activity_user.id) as idCount');
        $this->db->from('inventory_activity_user');
        $this->db->join('items_m', 'items_m.id = inventory_activity_user.item_sku', 'left');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity_user.seller_id');
        $this->db->join('user as users', 'users.id = inventory_activity_user.user_id', 'left');

        if (!empty($exact)) {
            $this->db->where('DATE(inventory_activity_user.entrydate)', $exact);
        }
        if (!empty($slip_no)) {
            $this->db->where('inventory_activity_user.awb_no', $slip_no);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(inventory_activity_user.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('inventory_activity_user.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }
        // $this->db->group_by(array("inventory_activity.awb_no", "inventory_activity.type","inventory_activity.item_sku","inventory_activity.p_qty"));
        $query = $this->db->get();
        //$this->db->group_by(array("inventory_activity.awb_no", "inventory_activity.type","inventory_activity.item_sku","inventory_activity.p_qty"));
        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->row_array()['idCount'];
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    function historyViewExport($filter) {

        $this->load->dbutil();

        $limit = 2000;
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }
        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('im.sku', $filter['filterData']['sku']);
        }
        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('sm.id', $filter['filterData']['seller']);
        }
        if (isset($filter['filterData']['slip_no']) && !empty($filter['filterData']['slip_no'])) {
            $this->db->where('ia.awb_no', $filter['filterData']['slip_no']);
        }
        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('ia.qty', $filter['filterData']['quantity']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['from'])) {
            $this->db->where('DATE(ia.entrydate)>=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['to'])) {
            $where1 = "DATE(ia.entrydate) BETWEEN '" . $filter['filterData']['from'] . "' AND '" . $filter['filterData']['to'] . "'";
            $this->db->where($where1);
            // $this->db->where('DATE(ia.entrydate)<=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(ia.entrydate)', $filter['filterData']['exact']);
        }
        if (isset($filter['filterData']['status']) && !empty($filter['filterData']['status'])) {
            $this->db->where('ia.type', $filter['filterData']['status']);
        }

        $selectQry = array();
        if (isset($filter['listData2']['sku']) && !empty($filter['listData2']['sku'])) {
            $selectQry[] = "im.sku as Sku";
        }
        if (isset($filter['listData2']['qty_used']) && !empty($filter['listData2']['qty_used'])) {
            $selectQry[] = "ia.qty_used as QuantityUsed";
        }
        if (isset($filter['listData2']['p_qty']) && !empty($filter['listData2']['p_qty'])) {
            $selectQry[] = "ia.p_qty as PreviousQuantity";
        }
        if (isset($filter['listData2']['qty']) && !empty($filter['listData2']['qty'])) {
            $selectQry[] = "ia.qty as NewQuantity";
        }
        if (isset($filter['listData2']['seller_name']) && !empty($filter['listData2']['seller_name'])) {
            $selectQry[] = "sm.name as SellerName";
        }
        if (isset($filter['listData2']['username']) && !empty($filter['listData2']['username'])) {
            $selectQry[] = "u.username as Username";
        }
        if (isset($filter['listData2']['entrydate']) && !empty($filter['listData2']['entrydate'])) {
            $selectQry[] = "ia.entrydate as Entrydate";
        }
        if (isset($filter['listData2']['type']) && !empty($filter['listData2']['type'])) {
            $selectQry[] = "ia.type as Status";
        }
        // if (isset($filter['listData2']['st_location']) && !empty($filter['listData2']['st_location'])) {
        //     $selectQry[] = "ia.st_location as StockLocation";
        // }
        if (isset($filter['listData2']['awb_no']) && !empty($filter['listData2']['awb_no'])) {
            $selectQry[] = "ia.awb_no as AWB";
        }
        $selectQry[] = "ia.comment as Comment";
        $selectQry = implode(',', $selectQry);

        $this->db->where('ia.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select($selectQry);
        $this->db->from('inventory_activity_user ia');
        $this->db->join('items_m im', 'im.id = ia.item_sku');
        $this->db->join('customer as sm', 'sm.id = ia.seller_id');
        $this->db->join('user as u', 'u.id = ia.user_id', 'left');
        //  $this->db->group_by(array("ia.awb_no", "ia.type","ia.item_sku","ia.p_qty","ia.entrydate"));
        $this->db->limit($limit);
        $query = $this->db->get();

        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function filter_recieve($quantity, $sku, $seller, $page_no) {



        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('receive_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('receive_inventory.id,receive_inventory.sku,receive_inventory.qty,items_m.sku ,receive_inventory.last_update_date,items_m.name,seller_m.company as seller_name,items_m.description as item_description, items_m.item_path');
        $this->db->from('receive_inventory');
        $this->db->join('customer as seller_m', 'seller_m.id = receive_inventory.seller_id');
        $this->db->join('items_m', 'items_m.sku = receive_inventory.sku');

        if (!empty($quantity)) {
            $this->db->where('receive_inventory.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        //  $this->db->group_by(array("inventory_activity_user.awb_no", "inventory_activity_user.type","inventory_activity_user.item_sku","inventory_activity_user.p_qty","inventory_activity_user.entrydate"));

        $this->db->order_by('receive_inventory.id', 'DESC');

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_recieve($quantity, $sku, $seller, $page_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_recieve($quantity, $sku, $seller, $page_no) {



        $this->db->where('receive_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(receive_inventory.id) as idCount');
        $this->db->from('receive_inventory');
        $this->db->join('items_m', 'items_m.sku = receive_inventory.sku');
        $this->db->join('customer as seller_m', 'seller_m.id = receive_inventory.seller_id');

        if (!empty($quantity)) {
            $this->db->where('receive_inventory.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        $query = $this->db->get();

        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->row_array()['idCount'];
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    public function filterexcelhistinventory($filterArr = array()) {
        $page_no;
        $limit = 2000;
        $start = $filterArr['exportlimit'] - $limit;

        $this->db->where('receive_inventory.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('receive_inventory.id,receive_inventory.sku,receive_inventory.qty,items_m.sku ,receive_inventory.last_update_date,items_m.name,seller_m.company as seller_name,items_m.description as item_description, items_m.item_path');
        $this->db->from('receive_inventory');
        $this->db->join('customer as seller_m', 'seller_m.id = receive_inventory.seller_id');
        $this->db->join('items_m', 'items_m.sku = receive_inventory.sku');

        $this->db->order_by('receive_inventory.id', 'DESC');

        $this->db->limit($limit, $start);
        $tempdb = clone $this->db;
        //now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();



        $query = $this->db->get();

        //echo  $this->db->last_query(); die;   
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();

            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            return $data;
        }
    }

    public function filter_activity($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no, $type) {



        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        $this->db->where('inventory_activity_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('inventory_activity_new.id,inventory_activity_new.p_qty,inventory_activity_new.type,items_m.sku , inventory_activity_new.qty,inventory_activity_new.qty_used,inventory_activity_new.entrydate,items_m.name,seller_m.company as seller_name,items_m.description as item_description,users.username,inventory_activity_new.awb_no,users.id,inventory_activity_new.user_id as iuser_id,inventory_activity_new.st_location,items_m.item_path,inventory_activity_new.shelve_no,inventory_activity_new.comment');
        $this->db->from('inventory_activity_new');
        $this->db->join('items_m', 'items_m.id = inventory_activity_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity_new.seller_id');
        $this->db->join('user as users', 'users.id = inventory_activity_new.user_id', 'left');

        $this->db->where("qty_used>0");
        if (!empty($type)) {
            $this->db->where('inventory_activity_new.type', $type);
        }
        if (!empty($slip_no)) {
            $this->db->where('inventory_activity_new.awb_no', $slip_no);
        }
        if (!empty($exact)) {
            $this->db->where('DATE(inventory_activity_new.entrydate)', $exact);
        }


        if (!empty($from) && !empty($to)) {
            $where = "DATE(inventory_activity_new.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('inventory_activity_new.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }

        //  $this->db->group_by(array("inventory_activity.awb_no", "inventory_activity.type","inventory_activity.item_sku","inventory_activity.p_qty","inventory_activity.entrydate"));

        $this->db->order_by('inventory_activity_new.id', 'DESC');

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        // echo $this->db->last_query(); die;    
        if ($query->num_rows() > 0) {

            $data['result'] = $query->result_array();
            $data['count'] = $this->filterCount_activity($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function filterCount_activity($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no) {



        $this->db->where('inventory_activity_new.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('COUNT(inventory_activity_new.id) as idCount');
        $this->db->from('inventory_activity_new');
        $this->db->join('items_m', 'items_m.id = inventory_activity_new.item_sku');
        $this->db->join('customer as seller_m', 'seller_m.id = inventory_activity_new.seller_id');
        $this->db->join('user as users', 'users.id = inventory_activity_new.user_id', 'left');

        if (!empty($exact)) {
            $this->db->where('DATE(inventory_activity_new.entrydate)', $exact);
        }
        if (!empty($slip_no)) {
            $this->db->where('inventory_activity_new.awb_no', $slip_no);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(inventory_activity_new.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";
            $this->db->where($where);
        }




        if (!empty($quantity)) {
            $this->db->where('inventory_activity_new.qty', $quantity);
        }

        if (!empty($sku)) {
            $this->db->where('items_m.sku', $sku);
        }

        if (!empty($seller)) {
            $this->db->where('seller_m.id', $seller);
        }
        // $this->db->group_by(array("inventory_activity.awb_no", "inventory_activity.type","inventory_activity.item_sku","inventory_activity.p_qty"));
        $query = $this->db->get();
        //$this->db->group_by(array("inventory_activity.awb_no", "inventory_activity.type","inventory_activity.item_sku","inventory_activity.p_qty"));
        //return $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->row_array()['idCount'];
            // return $page_no.$this->db->last_query();
        } else {
            return 0;
        }
    }

    function activityViewExport($filter) {

        $this->load->dbutil();

        $limit = 2000;
        if (isset($filter['filterData']['exportlimit']) && !empty($filter['filterData']['exportlimit'])) {
            $limit = $filter['filterData']['exportlimit'];
        }
        if (isset($filter['filterData']['sku']) && !empty($filter['filterData']['sku'])) {
            $this->db->where('im.sku', $filter['filterData']['sku']);
        }
        if (isset($filter['filterData']['seller']) && !empty($filter['filterData']['seller'])) {
            $this->db->where('sm.id', $filter['filterData']['seller']);
        }
        if (isset($filter['filterData']['slip_no']) && !empty($filter['filterData']['slip_no'])) {
            $this->db->where('ia.awb_no', $filter['filterData']['slip_no']);
        }
        if (isset($filter['filterData']['quantity']) && !empty($filter['filterData']['quantity'])) {
            $this->db->where('ia.qty', $filter['filterData']['quantity']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['from'])) {
            $this->db->where('DATE(ia.entrydate)>=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['from']) && !empty($filter['filterData']['to'])) {
            $where1 = "DATE(ia.entrydate) BETWEEN '" . $filter['filterData']['from'] . "' AND '" . $filter['filterData']['to'] . "'";
            $this->db->where($where1);
            // $this->db->where('DATE(ia.entrydate)<=', $filter['filterData']['from']);
        }
        if (isset($filter['filterData']['exact']) && !empty($filter['filterData']['exact'])) {
            $this->db->where('DATE(ia.entrydate)', $filter['filterData']['exact']);
        }
        if (isset($filter['filterData']['status']) && !empty($filter['filterData']['status'])) {
            $this->db->where('ia.type', $filter['filterData']['status']);
        }

        $selectQry = array();
        if (isset($filter['listData2']['sku']) && !empty($filter['listData2']['sku'])) {
            $selectQry[] = "im.sku as Sku";
        }
        if (isset($filter['listData2']['qty_used']) && !empty($filter['listData2']['qty_used'])) {
            $selectQry[] = "ia.qty_used as QuantityUsed";
        }
        if (isset($filter['listData2']['p_qty']) && !empty($filter['listData2']['p_qty'])) {
            $selectQry[] = "ia.p_qty as PreviousQuantity";
        }
        if (isset($filter['listData2']['qty']) && !empty($filter['listData2']['qty'])) {
            $selectQry[] = "ia.qty as NewQuantity";
        }
        if (isset($filter['listData2']['seller_name']) && !empty($filter['listData2']['seller_name'])) {
            $selectQry[] = "sm.name as SellerName";
        }
        if (isset($filter['listData2']['username']) && !empty($filter['listData2']['username'])) {
            $selectQry[] = "u.username as Username";
        }
        if (isset($filter['listData2']['entrydate']) && !empty($filter['listData2']['entrydate'])) {
            $selectQry[] = "ia.entrydate as Entrydate";
        }
        if (isset($filter['listData2']['type']) && !empty($filter['listData2']['type'])) {
            $selectQry[] = "ia.type as Status";
        }
        if (isset($filter['listData2']['st_location']) && !empty($filter['listData2']['st_location'])) {
            $selectQry[] = "ia.st_location as StockLocation";
        }
        if (isset($filter['listData2']['awb_no']) && !empty($filter['listData2']['awb_no'])) {
            $selectQry[] = "ia.awb_no as AWB";
        }
        $selectQry[] = "ia.comment as Comment";
        $selectQry = implode(',', $selectQry);

        $this->db->where('ia.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select($selectQry);
        $this->db->from('inventory_activity_new ia');
        $this->db->join('items_m im', 'im.id = ia.item_sku');
        $this->db->join('customer as sm', 'sm.id = ia.seller_id');
        $this->db->join('user as u', 'u.id = ia.user_id', 'left');
        //  $this->db->group_by(array("ia.awb_no", "ia.type","ia.item_sku","ia.p_qty","ia.entrydate"));
        $this->db->limit($limit);
        $query = $this->db->get();

        $delimiter = ",";
        $newline = "\r\n";

        return $data = chr(239) . chr(187) . chr(191) . $this->dbutil->csv_from_result($query, $delimiter, $newline);
    }

    public function UpdateStockLocation($data = array(), $id = null) {
        $this->db->where("item_inventory_new.quantity=0");
        $this->db->where('item_inventory_new.super_id', $this->session->userdata('user_details')['super_id']);
        return $this->db->update("item_inventory_new", $data, array("id" => $id));
    }

}
