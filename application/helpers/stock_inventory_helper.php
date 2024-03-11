<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getalldataitemtables_stock')) {

    function getalldataitemtables_stock($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT $field FROM items_m where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }
    if (!function_exists('Getallstoragetablefield_stock')) {

        function Getallstoragetablefield_stock($id = null, $field = null) {
            $ci = & get_instance();
            $ci->load->database();
            $sql = "SELECT $field FROM storage_table where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
            $query = $ci->db->query($sql);
            $result = $query->row_array();
            return $result[$field];
        }
    
    }

    if (!function_exists('Getwarehouse_categoryfield_stock')) {

        function Getwarehouse_categoryfield_stock($id = null, $field = null) {
            $ci = & get_instance();
            $ci->load->database();
            $sql = "SELECT $field FROM warehouse_category where id='$id' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "'";
            $query = $ci->db->query($sql);
            $result = $query->row_array();
            return $result[$field];
        }
    
    }
    if (!function_exists('GetAddInventoryActivities_stock')) {

        function GetAddInventoryActivities_stock($data = array()) {
            $ci = & get_instance();
            $ci->load->database();
            $ci->db->insert('inventory_activity', $data);
            // echo $ci->db->last_query();
        }
    
    }

}