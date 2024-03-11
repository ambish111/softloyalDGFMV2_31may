<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item_zid extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('zid_helper');
      
       
    }

    
    public function updateZid_product_new($seller_id = null, $super_id = null,$limit=200,$item=null) {

      
        $total_pages = GetAllQtyforSeller_new_zid_count($seller_id, $super_id,$item);
        $PageCount = $total_pages / $limit;
        $PageCount = ceil($PageCount);
         // echo "ttt".$PageCount; die;
        if ($PageCount > 0) {
            
            for ($i = 1; $i <= $PageCount; $i++) {
              
                $ziDAllArr = GetAllQtyforSeller_new_zid($seller_id, $i, $super_id,$limit,$item);
              //  print_r($ziDAllArr); die;
                foreach ($ziDAllArr as $key => $zidReqArr) {
                    $quantity = $zidReqArr['quantity'];
                    $pid = $zidReqArr['zid_pid'];
                    $token = $zidReqArr['manager_token'];
                    $storeID = $zidReqArr['zid_sid'];
                    $sku = $zidReqArr['sku'];
                    update_zid_product_cron($quantity, $pid, $token, $storeID, $seller_id, $sku,$super_id);
                }
                sleep(1);
            }
        }
    }

}

?>
