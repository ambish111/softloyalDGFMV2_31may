<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home_qty extends CI_Controller {

    function __construct() {
        parent::__construct();
       
    }

   
    public function getupdatestock() {


        
        
     die('stop');

         $stock_json = file_get_contents('https://fm.diggipacks.com/assets/PhysicalInventory-19-10-2023.json');
         echo "<pre>";
        $stockFileArray = json_decode($stock_json, true);
        $NewStockArray = $stockFileArray['Sheet1'];
       //
    //print_r($stockFileArray); die;
    

        foreach ($NewStockArray as $key => $Val) {
            $location = trim($Val['StockLocation']);
            $SKU = trim($Val['ItemSku']);
            $Qty = trim($Val['QUANTITY']);
            $Shelve_Location= trim($Val['Shelve_NO']);
            $seller_id = 60;
            $super_id = 175;
            $wh_id = 23;
            // $sku_new = array("204000610584");
            // if(in_array($SKU,$sku_new)){
            //     $SKU = "0".$SKU;
            // }
            

            $this->db->select('items_m.id,items_m.sku,items_m.sku_size,wh_id');
            $this->db->from('items_m');
            $this->db->where('items_m.sku', trim($SKU));
            $this->db->where('items_m.super_id', $super_id);

            $query2 = $this->db->get();
           // echo $this->db->last_query()."<br>";
             $result_1 = $query2->row_array();


            if (!empty($result_1)) {
                
            
                $in_sql = "insert into item_inventory(`item_sku`, `quantity`, `update_date`, `seller_id`, `shelve_no`, `stock_location`,`itype`, `wh_id`, `super_id`)values('" . $result_1['id'] . "','$Qty','" . date("Y-m-d H:i:s") . "','$seller_id','$Shelve_Location','$location','B2C','$wh_id','$super_id')";

                //    echo $in_sql."<br>";
                //   $this->db->query($in_sql);
                

                
                echo $result_1['wh_id'].'===='.$SKU . "=========" . $in_sql . "<br>"; //exit;
            } else {
                
                echo $result_1['wh_id'].'===SKU=='.$SKU . "=========wrong" . "<br>";
            }
        }



        // print_r($NewStockArray);
        die;
        //  echo count($result);
    }


}
