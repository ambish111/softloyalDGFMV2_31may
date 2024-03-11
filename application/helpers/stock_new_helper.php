<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



if (!function_exists('CheckStockBackorder_ordergen_bulk')) {

    function CheckStockBackorder_ordergen_bulk($ship_arr = array(), $sku_arr = array()) {
        $ci = & get_instance();
        $ci->load->database();

        $seller_id = $ship_arr['cust_id'];
        $sku = trim($sku_arr['sku']);
        $pieces = $sku_arr['piece'];
        if ($pieces > 0) {
            $slip_no = $ship_arr['slip_no'];
            $sku_id = $sku_arr['sku_id'];
            $wh_id = $ship_arr['wh_id'];
            $expire_block = $sku_arr['expire_block'];
            if ($expire_block == 'Y') {
                $current_date = date("Y-m-d");
                $conditionCheck = " expiry='N' and expity_date>='$current_date'";
                $ci->db->where($conditionCheck);
            }
            $ci->db->select("item_inventory.*,items_m.sku");
            $ci->db->from('item_inventory');
            $ci->db->join('items_m', 'items_m.id = item_inventory.item_sku', 'LEFT');
            $ci->db->where('item_inventory.seller_id', $seller_id);
            $ci->db->where('item_inventory.wh_id', $wh_id);
            $ci->db->where('items_m.sku', $sku);
            $ci->db->where('items_m.super_id', $ci->session->userdata('user_details')['super_id']);
            $ci->db->where('item_inventory.super_id', $ci->session->userdata('user_details')['super_id']);
            $ci->db->where("`stock_location` IS NOT NULL and stock_location!=''");
            $ci->db->where("item_inventory.quantity>0");
            $ci->db->order_by('item_inventory.id', 'asc');
            $query = $ci->db->get();
           // echo $ci->db->last_query()."<br>"; 

            if ($query->num_rows() > 0) {
                $inventory_data = $query->result_array();
                $returnarray = array();
                $totalqty = 0;
                $totalqty1 = 0;
                $stock_location_new = array();
                $locationarray = array();
                $error_array = array();
                $countInventry = count($inventory_data) - 1;
                $finalLoopArray = array();
                $werehouseArr = array();

                foreach ($inventory_data as $key11 => $rdata) {
                    if ($totalqty < $pieces) {
                        if ($key11 == 0)
                            array_push($werehouseArr, $rdata['wh_id']);

                        if (in_array($rdata['wh_id'], $werehouseArr)) {
                            $totalqty += $rdata['quantity'];

                            array_push($finalLoopArray, $rdata);
                        }
                        if ($key11 == $countInventry) {
                            if ($totalqty < $pieces) {
                                array_push($error_array, $rdata);
                            }
                        }
                    }
                }
                // print_r($error_array); die;

                if (empty($error_array)) {
                    if ($pieces <= $totalqty) {
                        $newpcs = $pieces;
                        $ii = 0;
                        $palletArrayCeck = array();
                        $shelveno = "";
                        $pCount = sizeof($finalLoopArray) - 1;
                        foreach ($finalLoopArray as $rdata) {

                            array_push($palletArrayCeck, $rdata['shelve_no']);
                            if ($pCount == $ii)
                                $newPalletArr = array_unique($palletArrayCeck);

                            $shelveno = implode(',', $newPalletArr);
                            if ($pieces >= $rdata['quantity']) {


                                $returnarray[$ii]['upqty'] = 0;
                                $oldPeice = $pieces;
                                $pieces = $pieces - $rdata['quantity'];

                                $returnarray[$ii]['tableid'] = $rdata['id'];
                                $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                                $returnarray[$ii]['quantity'] = $rdata['quantity'];
                                $returnarray[$ii]['sku'] = $rdata['sku'];
                                $returnarray[$ii]['slip_no'] = $slip_no;
                                $returnarray[$ii]['seller_id'] = $seller_id;
                                $returnarray[$ii]['shelve_no'] = $shelveno;
                                $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                                $returnarray[$ii]['expity_date'] = $rdata['expity_date'];
                                $returnarray[$ii]['totalqty'] = $totalqty;
                                $returnarray[$ii]['pieces'] = $pieces;
                                $returnarray[$ii]['oldPeice'] = $oldPeice;
                                $returnarray[$ii]['st_location'] = $rdata['stock_location'];

                                array_push($stock_location_new, array('slip_no' => $slip_no, 'sku' => $rdata['sku'], 'stock_location' => $rdata['stock_location'], 'shelve_no' => $shelveno));
                            } else {
                                if ($pieces > 0) {
                                    $oldPeice = $pieces;
                                    $returnarray[$ii]['upqty'] = $rdata['quantity'] - $pieces;
                                    $returnarray[$ii]['tableid'] = $rdata['id'];
                                    $returnarray[$ii]['skuid'] = $rdata['item_sku'];
                                    $returnarray[$ii]['quantity'] = $rdata['quantity'];
                                    $returnarray[$ii]['sku'] = $rdata['sku'];
                                    $returnarray[$ii]['slip_no'] = $slip_no;
                                    $returnarray[$ii]['seller_id'] = $seller_id;
                                    $returnarray[$ii]['shelve_no'] = $shelveno;
                                    $returnarray[$ii]['wh_id'] = $rdata['wh_id'];
                                    $returnarray[$ii]['expity_date'] = $rdata['expity_date'];
                                    $returnarray[$ii]['totalqty'] = $totalqty;
                                    $returnarray[$ii]['pieces'] = $pieces;
                                    $returnarray[$ii]['oldPeice'] = $oldPeice;
                                    $returnarray[$ii]['st_location'] = $rdata['stock_location'];

                                    array_push($stock_location_new, array('slip_no' => $slip_no, 'sku' => $rdata['sku'], 'stock_location' => $rdata['stock_location'], 'shelve_no' => $shelveno));

                                    $pieces = 0;
                                } else {

                                    

                                    //echo $ii;
                                    //$returnarray[$ii]['upqty']=$rdata['quantity']; 
                                    // $returnarray[$ii]['tableid']=$rdata['id'];
                                    // $returnarray[$ii]['skuid']=$rdata['item_sku'];
                                    //$returnarray[$ii]['quantity']=$rdata['quantity'];
                                    // $returnarray[$ii]['wh_id']=$rdata['wh_id'];
                                    // $returnarray[$ii]['st_location'] = $rdata['stock_location'];
                                }
                            }

                            $ii++;
                        }
                        return array('succ' => 1, 'stArray' => $returnarray, 'StockLocation' => $stock_location_new);
                    } else {
                        return 'less_stock';
                    }
                } else {
                    return 'warehouse_diffrent';
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

    function UpdateStockorderGen($data = array(), $weight = null) {
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
                $shipArray['wh_id'] = $rdata['wh_id'];

                $shipArray['code'] = 'OC';
                if ($ci->session->userdata('user_details')['super_id'] == 1005) {
                    $shipArray['weight'] = $weight;
                }
                $shipArray['update_date'] = date('Y-m-d H:i:s');
            }




            //echo $ci->db->last_query();
        }

        $ci->db->insert("status_fm", $StatusArray);
        $ci->db->update("shipment_fm", $shipArray, array("slip_no" => $data[0]['slip_no'], "super_id" => $ci->session->userdata('user_details')['super_id']));
        // echo $ci->db->last_query();
    }

}

if (!function_exists('UpdateStockorderGen_bulk')) {

    function UpdateStockorderGen_bulk($data = array(), $weight = null,$slip_no=null) {
        $ci = & get_instance();
        $ci->load->database();


        
         //echo '<pre>';
         //print_r($data);  die;
       
        foreach ($data as $rdata) {
            foreach ($rdata as $finaldata) {

                $updates = "update item_inventory set quantity='" . $finaldata['upqty'] . "' where id='" . $finaldata['tableid'] . "' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                $ci->db->query($updates);

                if ($finaldata['slip_no'] != '') {
                    if ($finaldata['oldPeice'] >= $finaldata['quantity']) {
                        $p_qty = $finaldata['quantity'];
                        $qty = 0;
                        $qty_used = $finaldata['quantity'];
                    } else {

                        $p_qty = $finaldata['quantity'];
                        $qty = $finaldata['quantity'] - $finaldata['pieces'];
                        $qty_used = $finaldata['pieces'];
                    }

                    $slip_no = $finaldata['slip_no'];
                    $sku = $finaldata['sku'];
                    $stock_location = $finaldata['st_location'];
                    $shelve_no = $finaldata['shelve_no'];
                    $expire_date = $finaldata['expity_date'];
                    if(empty($expire_date))
                      $expire_date="0000-00-00";
                    $stocklocation = "insert into locationDetails (slip_no,sku,stock_location,shelve_no,expire_date) values('" . $slip_no . "','" . $sku . "','" . $stock_location . "','$shelve_no','$expire_date')";
                    $ci->db->query($stocklocation);

                    


                    //echo '<br>'. 
                    $insertdata = "insert into inventory_activity (user_id,seller_id,qty,p_qty,qty_used,item_sku,type,entrydate,awb_no,st_location,super_id,shelve_no,comment) values('" . $ci->session->userdata('user_details')['user_id'] . "','" . $finaldata['seller_id'] . "','" . $qty . "','" . $p_qty . "','" . $qty_used . "','" . $finaldata['skuid'] . "','deducted','" . date('Y-m-d H:i:s') . "','" . $finaldata['slip_no'] . "','" . $finaldata['st_location'] . "','" . $ci->session->userdata('user_details')['super_id'] . "','" . $finaldata['shelve_no'] . "','From Bulk')";
                    $ci->db->query($insertdata);
                }
                if (!empty($finaldata['shelve_no'])) {
                    $updates_dimation = "update diamention_fm set deducted_shelve='" . $finaldata['shelve_no'] . "' where slip_no='" . $finaldata['slip_no'] . "' and sku='" . $finaldata['sku'] . "' and deducted_shelve='' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
                    $ci->db->query($updates_dimation);
                }
                
                $StatusArray['slip_no'] = $finaldata['slip_no'];
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
                
                $shipArray['backorder'] = 0;
                $shipArray['delivered'] = 1;
                $shipArray['wh_id'] = $finaldata['wh_id'];
                $shipArray['code'] = 'OC';
                $shipArray['weight'] = $weight;
               //$shipArray['update_date'] = date('Y-m-d H:i:s');
                
                //echo $ci->db->last_query();
            }
            
        }
        
        
        if(!empty($StatusArray) && !empty($shipArray) && !empty($slip_no))
        {
            
         $ci->db->insert("status_fm", $StatusArray);
         $ci->db->where("code",'OG');
         $ci->db->update("shipment_fm", $shipArray, array("slip_no" => $slip_no, "super_id" => $ci->session->userdata('user_details')['super_id']));
        }
              
    }

}




