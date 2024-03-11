<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


if (!function_exists('GetstockID_n')) {

    function GetstockID_n() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT lastno FROM item_inventory_new where   super_id='" . $ci->session->userdata('user_details')['super_id'] . "' order by id desc";
        $query = $ci->db->query($sql);

        $row = $query->row_array();
        if (!empty($row))
            return $row['lastno'];
        else
            return 0;
    }

}

if (!function_exists('singleSkuDetails')) {

    function singleSkuDetails($sku = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT * FROM items_m where sku='".trim($sku)."' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result;
    }

}

if (!function_exists('GetAddInventoryActivities_new')) {

    function GetAddInventoryActivities_new($data = array()) {
        $ci = & get_instance();
        $ci->load->database();
        $ci->db->insert('inventory_activity_new', $data);
        // echo $ci->db->last_query();
    }

}
if (!function_exists('GetAddInventoryActivities_user')) {

    function GetAddInventoryActivities_user($data = array()) {
        $ci = & get_instance();
        $ci->load->database();
        $ci->db->insert('inventory_activity_user', $data);
        // echo $ci->db->last_query();
    }

}


function GetcheckMainStock($sku = null, $seller_id = null) {
    $ci = & get_instance();
    $ci->load->database();
    $sql = "SELECT id,qty FROM receive_inventory where sku='$sku' and seller_id='$seller_id' and  super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
    $query = $ci->db->query($sql);

    $row = $query->row_array();
    // echo  $ci->db->last_query(); die;
    if (!empty($row)) {
        return $row;
    } else {
        return array();
    }
}

if (!function_exists('ordergenstock_check')) {

    function ordergenstock_check($seller_id = null, $sku = null, $qty = 0, $slip_no = null, $custmoerID = null) {
        if ($qty > 0) {
            $ci = & get_instance();
            $ci->load->database();

            $inventory_dataqry = "select receive_inventory.id,receive_inventory.qty,receive_inventory.sku,items_m.id as item_sku,items_m.wh_id from receive_inventory left join items_m on receive_inventory.sku=items_m.sku where receive_inventory.seller_id='" . $seller_id . "' and items_m.sku='" . trim($sku) . "' and receive_inventory.super_id='" . $ci->session->userdata('user_details')['super_id'] . "'  and items_m.super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and receive_inventory.qty>0";
            $query = $ci->db->query($inventory_dataqry);

            if ($query->num_rows() > 0) {
                $stock_data = $query->row_array();
                /// print_r($stock_data);
                $totalqty = $stock_data['qty'];

                if ($qty <= $totalqty) {

                    $new_stock = $totalqty - $qty;
                    //echo $new_stock."==".$sku."====".$slip_no."<br>"; 
                    $returnarray = array(
                        "sku" => $stock_data['sku'],
                        "item_sku" => $stock_data['item_sku'],
                        "qty" => $new_stock,
                        "in_stock" => $totalqty,
                        "use_piece" => $qty,
                        "wh_id" => $stock_data['wh_id'],
                        "slip_no" => $slip_no,
                        "tableid" => $stock_data['id'],
                        "seller_id" => $custmoerID
                    );

                    //$totalqty=$new_stock;
                    return array('succ' => 1, 'stArray' => $returnarray);
                } else {
                    return 'less_stock';
                }
            } else {
                return 'invalid_stock';
            }
        } else {
            return 'invalid_qty';
        }
    }

}

if (!function_exists('UpdateStockorderGen')) {

    function UpdateStockorderGen($data = array(),$weight=null) {
        $ci = & get_instance();
        $ci->load->database();

        //  echo '<pre>';
        // print_r($data);
        // die;
        foreach ($data as $rdata) {
             $updatesQry = "update receive_inventory set qty='" . $rdata['qty'] . "' where id='" . $rdata['tableid'] . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
             $ci->db->query($updatesQry);

            if ($rdata['slip_no'] != '') {
                $user_history = array(
                    "user_id" => $ci->session->userdata('user_details')['user_id'],
                    "seller_id" => $rdata['seller_id'],
                    "qty" => $rdata['qty'],
                    "p_qty" => $rdata['in_stock'],
                    "qty_used" => $rdata['use_piece'],
                    "item_sku" => $rdata['item_sku'],
                    "type" => "deducted",
                    "entrydate" => date('Y-m-d H:i:s'),
                    "awb_no" => $rdata['slip_no'],
                    "super_id" => $ci->session->userdata('user_details')['super_id']
                );
                $ci->db->insert("inventory_activity_user", $user_history);
                
                
                
                $StatusArray['slip_no'] = $rdata['slip_no'];
                $StatusArray['new_status'] = 1;
                $StatusArray['pickup_time'] = date("H:i:s");
                $StatusArray['pickup_date'] = date('Y-m-d H:i:s');
                $StatusArray['Details'] = "Order Created";
                $StatusArray['Activites'] = "Order Created";
                $StatusArray['entry_date'] = date('Y-m-d H:i:s');
                $StatusArray['user_id'] = $ci->session->userdata('user_details')['user_id'];
                $StatusArray['user_type'] = 'fulfillment';
                $StatusArray['code'] = 'OC';
                $StatusArray['super_id'] = $ci->session->userdata('user_details')['super_id'];
                
               
                
               // $shipArray['slip_no'] = $rdata['slip_no'];
                $shipArray['backorder'] = 0;
                $shipArray['delivered'] = 1;
                $shipArray['wh_id'] = $rdata['wh_id'];;
                $shipArray['code'] = 'OC';
                $shipArray['weight'] = $weight;
                $shipArray['created_at'] = date('Y-m-d H:i:s');
               
                 $temProcess['status']='Y';
                 $temProcess['deleted']='Y';
            }
            
            


            //echo $ci->db->last_query();
        }
        
          $ci->db->insert("status_fm", $StatusArray);
          $ci->db->update("shipment_fm", $shipArray,array("slip_no"=>$data[0]['slip_no'],"super_id"=>$ci->session->userdata('user_details')['super_id']));
          $ci->db->update("temporder_process_new", $temProcess,array("slip_no"=>$data[0]['slip_no'],"super_id"=>$ci->session->userdata('user_details')['super_id']));
    }

}


