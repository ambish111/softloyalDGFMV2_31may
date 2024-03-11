<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_status extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {

        
         die("stop");
        $this->db->select('slip_no,code,invoice_check,weight');
        $this->db->from("shipment_fm");
        $this->db->where("weight", 0);
        // $this->db->where("slip_no", 'AWS8078828199');
        $this->db->where_in('super_id', array(301));
       // $this->db->limit(200);
        $query = $this->db->get();
        // echo $this->db->last_query(); die;
        $result = $query->result_array();
        // $slip_noArr = array_column($result, 'slip_no');
        ///echo "<pre>"; print_r($slip_noArr); die;
        $shipArray = array();
        foreach ($result as $key => $data) {

            $this->db->where_in('diamention_fm.super_id', array(301));
            $this->db->where_in('items_m.super_id', array(301));
            $this->db->select('diamention_fm.sku,slip_no,diamention_fm.piece,items_m.id as sku_id,items_m.weight');
            $this->db->from('diamention_fm');
            $this->db->join('items_m', 'diamention_fm.sku = items_m.sku', 'LEFT');
            $this->db->where('diamention_fm.slip_no', $data['slip_no']);
             $this->db->where("items_m.weight>0");
            $query_1 = $this->db->get();
            //echo $this->db->last_query(); die;
            $result_1 = $query_1->result_array();
            $totalweight = 0;
            $data['weight'] = 0;
            //$newArr=array();
            foreach ($result_1 as $key => $val) {
                $weightcount = $val['weight'];
                $totalweight = $totalweight + ($weightcount * $val['piece']);
                $data['weight'] = $totalweight;
                $update_array[] = array("weight" => $data['weight']);
            }
            //$shipArray[$key]['weight'] = $data['weight'];

            $sql = "update shipment_fm set weight='" . $data['weight'] . "',volumetric_weight='" . $data['weight'] . "' where slip_no='" . $data['slip_no'] . "'";
            //$this->db->query($sql);
            $sql1 = "update dynamic_invoice set weight='" . $data['weight'] . "' where slip_no='" . $data['slip_no'] . "' and (`weight` IS NULL || `weight`=0)";
            //$this->db->query($sql1);
            // echo $sql1."<br>";
        }
        
        

        //echo "<pre>"; print_r($shipArray); die;
    }

}
