<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mergestock_new extends MY_Controller {

    function __construct() {
        parent::__construct();
        // if (menuIdExitsInPrivilageArray(17) == 'N') {
        //     redirect(base_url() . 'notfound');
        //     die;
        // }
        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Merge_new_model');
        $this->load->model('Seller_model');
       

        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index() {

        $quantity = $this->Merge_new_model->count_all();
        $sellers = $this->Seller_model->find1();
        $StorageType = $this->Merge_new_model->GetstorageTypes();
        // $data['items'] = $this->ItemInventory_model->all();
        $condition = null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }


        //$items = $this->ItemInventory_model->all();
        $bulk = array(
            //'items'=>$items,
            'sellers' => $sellers,
            'quantity' => $quantity,
            'condition' => $condition,
            'StorageType' => $StorageType
        );
        $this->load->view('merge/search_new', $bulk);
    }

    public function filter() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST); die;
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $storage_id = $_POST['storage_id'];

        $items = $this->Merge_new_model->filter($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no, $storage_id, $_POST);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
      //  $jj = 0;

//        $tolalShip = $items['count'];
//        $downlaoadData = 2000;
//        $j = 0;
//        for ($k = 0; $k < $tolalShip;) {
//            $k = $k + $downlaoadData;
//            if ($k > 0) {
//                $expoertdropArr[] = array('j' => $j, 'k' => $k);
//            }
//            $j = $k;
//        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            //$ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));
            $expity_date = $rdata['expity_date'];
            // echo $currentDate.">=".$expity_date."ttttt".$rdata['stock_location']."<br>";


//            if ($currentDate == $ExitsDate)
//                $ItemArray[$kk]['checkreQty'] = 'Y';
//            else
//                $ItemArray[$kk]['checkreQty'] = 'N';

            if ($expity_date == '0000-00-00') {
                $expity_date_status = "No";
            } else {
                if ($currentDate <= $expity_date) {
                    $expity_date_status = "No";
                } else {
                    $expity_date_status = "Yes";
                }
            }
            $ItemArray[$kk]['expity_date_status'] = $expity_date_status;
            $ItemArray[$kk]['item_type'] = getalldataitemtables($rdata['item_sku'], 'type');
            $ItemArray[$kk]['sku_size'] = getalldataitemtables($rdata['item_sku'], 'sku_size');
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield(getalldataitemtables($rdata['item_sku'], 'storage_id'), 'storage_type');
            //if($rdata['shelve_no']!='')

            $ItemArray[$kk]['wh_name'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($ItemArray);die;
       // $returnArray['query'] = $items['query'];
        $returnArray['count'] = $items['count'];
      //  $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        echo json_encode($returnArray);
    }

    public function GetallStockLocation_new() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        $sku = $_POST['sku'];
        $seller_id = $_POST['seller'];
        $newstockLocation = $_POST['newstockLocation'];
        $param = array('seller_id' => $seller_id, 'sku' => $sku, 'stock_location' => $newstockLocation);

        $result = $this->Merge_new_model->check($param);
        if (count($result) > 0) {
            echo json_encode($result);
            exit;
        } elseif (count($result) == 0) {
            $result = $this->Merge_new_model->Getallstocklocationdata_viewpage($seller_id, $newstockLocation);
            if (count($result) > 0) {
                echo json_encode($result);
                exit;
            } else {
                echo json_encode(false);
                exit;
            }
        } else {
            echo json_encode(false);
            exit;
        }
    }

    public function GetMergeReadyStock() {
        $data = json_decode(file_get_contents('php://input'), true);
       //  echo "<pre>";
       // print_r($data);die;
        
        

        //$filterArr = $data['filter'];
        $filterArr = $data['filter'];
        $mainUpArr = $data['main'][0];
        $stocklocationArr = $data['stocklocation'];

        if (!empty($mainUpArr)) {

            if ($mainUpArr['lastrow_q'] > 0) {
                $next_stock_qty = $mainUpArr['FL_updating_qty'] - $mainUpArr['lastrow_q'];
                $next_used = $filterArr['fill_total_qty'];
            } else {
                $next_stock_qty = $mainUpArr['FL_updating_qty'];
                $next_used = $next_stock_qty - $filterArr['total_qty'];
            }
            if ($mainUpArr['In_id'] > 0) {
                $key = 1;
                
                
                //=============update stock==================//
                $updateFirstStock[0]['id'] = $mainUpArr['In_id'];
                $updateFirstStock[0]['quantity'] = $next_stock_qty;
                $updateFirstStock[0]['seller_id'] = $filterArr['seller'];
                $updateFirstStock[0]['item_sku'] = $filterArr['item_sku'];
                //================inventory history============//
                $inventoryhistory[0]['st_location'] = $filterArr['sel_location'];
                $inventoryhistory[0]['item_sku'] = $filterArr['item_sku'];
                $inventoryhistory[0]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $inventoryhistory[0]['seller_id'] = $filterArr['seller'];
                $inventoryhistory[0]['qty'] = $next_stock_qty;
                $inventoryhistory[0]['p_qty'] = $filterArr['total_qty'];
                $inventoryhistory[0]['qty_used'] = $next_used;
                $inventoryhistory[0]['type'] = 'Update';
                $inventoryhistory[0]['entrydate'] = date("Y-m-d h:i:s");
                $inventoryhistory[0]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $inventoryhistory[0]['shelve_no'] = isset($filterArr['shelve_no']) ? $filterArr['shelve_no'] : "";
                $inventoryhistory[0]['comment'] = "Merge Stock";
            } else {
                $key = 0;
                //================add stock[=====================//
                $insertData = array(
                    'item_sku' => $filterArr['item_sku'],
                    'quantity' => $next_stock_qty,
                    'update_date' => date("Y-m-d H:i:s"),
                    'seller_id' => $filterArr['seller'],
                    'shelve_no' => isset($filterArr['shelve_no']) ? $filterArr['shelve_no'] : "",
                    'stock_location' => $filterArr['sel_location'],
                    'itype' => 'B2C',
                    'wh_id' => $filterArr['item_sku'],
                    'super_id' => $this->session->userdata('user_details')['super_id']
                );
                //=======================inventory history====================//
                $activitiesArr = array('st_location' => $filterArr['sel_location'], 'item_sku' => $filterArr['item_sku'], 'user_id' => $this->session->userdata('user_details')['user_id'], 'seller_id' => $filterArr['seller'], 'qty' => $next_stock_qty, 'p_qty' => 0, 'qty_used' => $next_stock_qty, 'type' => 'Add', 'entrydate' => date("Y-m-d h:i:s"), 'super_id' => $this->session->userdata('user_details')['super_id'], 'shelve_no' => isset($filterArr['shelve_no']) ? $filterArr['shelve_no'] : "", 'comment' => 'Merge Stock');
                //==========================================================//
            }


            foreach ($stocklocationArr as $st_row) {

                if ($st_row['check'] > 0) {
                    $up_qty = $st_row['check'];
                } else {

                    $up_qty = 0;
                }
                //==================update stock==================//
                $updateFirstStock[$key]['id'] = $st_row['t_id'];
                $updateFirstStock[$key]['quantity'] = $up_qty;

                //===============inventory history================//
                $usedqty = $st_row['quantity'] - $up_qty;
                $inventoryhistory[$key]['st_location'] = $st_row['stock_location'];
                $inventoryhistory[$key]['item_sku'] = $filterArr['item_sku'];
                $inventoryhistory[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $inventoryhistory[$key]['seller_id'] = $filterArr['seller'];
                $inventoryhistory[$key]['qty'] = $up_qty;
                $inventoryhistory[$key]['p_qty'] = $st_row['quantity'];
                $inventoryhistory[$key]['qty_used'] = $usedqty;
                $inventoryhistory[$key]['type'] = 'Deducted';
                $inventoryhistory[$key]['entrydate'] = date("Y-m-d h:i:s");
                $inventoryhistory[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $inventoryhistory[$key]['shelve_no'] = isset($filterArr['shelve_no']) ? $filterArr['shelve_no'] : "";
                $inventoryhistory[$key]['comment'] = "Merge Stock";
                //==================================================//
                $key++;
            }

            if (!empty($updateFirstStock)) {
                $this->Merge_new_model->updatingData($updateFirstStock);
                //echo $this->db->last_query(); 
                $this->Merge_new_model->inserthistoryData($inventoryhistory);
            }
            if (!empty($insertData)) {
               // $this->Merge_new_model->insertstockData($insertData);
               // GetAddInventoryActivities($activitiesArr);
            }
            $return = true;
        } else {
            $return = false;
        }
        /// print_r($updateFirstStock);
        echo json_encode($return);
        exit;
    }

}

?>
