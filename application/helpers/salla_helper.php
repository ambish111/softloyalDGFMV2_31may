<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('GetcheckPrmocodeQry')) {
    function GetcheckPrmocodeQry($promocode = null, $seller_id = null) {
        $ci = & get_instance();
        $ci->load->database(); //`new_location`
        $nowtime = date("Y-m-d");
        $siteQry = "select main_item,qty from promo_tbl where  status='Y' and promocode='$promocode' and  seller_id='" . $seller_id . "' and expire_date>='$nowtime'";
        $query0 = $ci->db->query($siteQry);
        $statusData = $query0->result_array();
        if (!empty($statusData)) {
            //echo 'ssssss';
            return $statusData;
        } else
            return array();
    }

}

if (!function_exists('GetSallaShippingCost')) {
    function GetSallaShippingCost($merchant_id= null){
      //  if($merchant_id)
        $ci = & get_instance();
        $ci->load->database(); //`new_location`
       
         $siteQry = "select salla_shipping_cost from customer where  salla_merchant_id=".$merchant_id;
        // echo 
        $query = $ci->db->query($siteQry);
        $statusData = $query->result_array();
        return $statusData[0]['salla_shipping_cost'];
    }
}

if (!function_exists('GetSallaallCustomers')) {

    function GetSallaallCustomers() {
        $ci = & get_instance();
        $ci->load->database();
        $sql = "SELECT uniqueid,name,salla_access FROM customer where salla_active='Y' and deleted='N' and salla_athentication !='' and super_id='" . $ci->session->userdata('user_details')['super_id'] . "' ";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

}