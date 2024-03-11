<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class StockInventory extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('StockInventory_model');
        $this->load->helper('stock_inventory');
    }

    public function index() {

        $quantity = $this->StockInventory_model->count_all();
        
        $sellers = $this->StockInventory_model->find1();
        $StorageType = $this->StockInventory_model->GetstorageTypes();
        //print_r($sellers); die;
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
        $this->load->view('ItemI/view_stockiteminventory', $bulk);
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



        $items = $this->StockInventory_model->filter($quantity, $sku, $seller, $to, $from, $exact, $page_no, $shelve_no, $storage_id, $_POST);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));
            $expity_date = $rdata['expity_date'];
            // echo $currentDate.">=".$expity_date."ttttt".$rdata['stock_location']."<br>";


            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';

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
            $ItemArray[$kk]['item_type'] = getalldataitemtables_stock($rdata['item_sku'], 'type');
            $ItemArray[$kk]['sku_size'] = getalldataitemtables_stock($rdata['item_sku'], 'sku_size');
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield_stock(getalldataitemtables_stock($rdata['item_sku'], 'storage_id'), 'storage_type');
            //if($rdata['shelve_no']!='')

            $ItemArray[$kk]['wh_name'] = Getwarehouse_categoryfield_stock($rdata['wh_id'], 'name');

            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($ItemArray);die;
         $returnArray['query'] = $items['query'];
         $returnArray['count'] = $items['count'];
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        echo json_encode($returnArray);
    }

    public function StockInventoryExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->StockInventory_model->StockInventoryExport($request);
        $file_name = 'StockInventory.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function GetUpdatePalletNoData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $palletno = $_POST['palletno']; //'A3/S0/2';//;
        $tid = $_POST['tid']; //'519';//
        $sid = $_POST['sid']; //'12';//
        $PalletsCheck = $this->StockInventory_model->GetcheckvalidPalletNo($palletno);
        if ($PalletsCheck == true) {
            $PalletArrayI = $this->StockInventory_model->GetcheckPalletInventry($palletno, $sid);
            // print_r($PalletArrayI);
            if (!empty($PalletArrayI)) {

                if ($sid == $PalletArrayI['seller_id']) {
                    $updateArray = array('shelve_no' => $palletno);
                    $PalletArrayI = $this->StockInventory_model->UpdateInventoryPallet($updateArray, $tid);
                    $return = $PalletArrayI;
                } else {
                    $return = 301;
                }
            } else {
                $updateArray = array('shelve_no' => $palletno);
                $PalletArrayI = $this->StockInventory_model->UpdateInventoryPallet($updateArray, $tid);
                $return = $PalletArrayI;
            }
        } else {
            $return = 302;
        }


        echo json_encode($return);
    }

    public function GetUpdateMissingOrDamgeQty() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST);
        $QtyUp = $_POST['quantity'] - $_POST['upquantity'];
        $inventoryUpdateArr = array(
            'quantity' => $QtyUp,
            'update_date' => date("Y/m/d h:i:sa"));
        //'updated_by'=>$this->session->userdata('user_details')['user_id']);

        $inventoryHistoryUodate = array(
            'seller_id' => $_POST['sid'],
            'qty' => $QtyUp,
            'item_sku' => $_POST['item_sku'],
            'p_qty' => $_POST['quantity'],
            'type' => $_POST['updateType'],
            'exp_date' => $_POST['expity_date'],
            'st_location' => $_POST['stock_location'],
            'entrydate' => date("Y/m/d h:i:s"),
            'qty_used' => $_POST['upquantity'],
            'super_id'=>$this->session->userdata('user_details')['super_id'],
            //'awb_no'=>$_POST['awb_no'],
            //'update_date' => date("Y/m/d h:i:sa"),
            'user_id' => $this->session->userdata('user_details')['user_id']);
        //print_r($inventoryHistoryUodate); die;
        GetAddInventoryActivities_stock($inventoryHistoryUodate);
        $return = $this->StockInventory_model->UpdateInventoryMissing($inventoryUpdateArr, $_POST['id']);
        echo json_encode($return);
    }

    public function stockhistory($item_sku = null, $seller_id = null) {
        $quantity = $this->StockInventory_model->count_all();
        $sellers = $this->StockInventory_model->find1();
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
            'item_sku' => $item_sku,
            'seller_id' => $seller_id
        );
        // print_r($bulk); die;
        $this->load->view('ItemI/inventory_history_stock', $bulk);
    }

    public function recieveinventory() {
        $quantity = $this->StockInventory_model->count_all();
        $sellers = $this->StockInventory_model->find1();
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
            'item_sku' => $item_sku,
            'seller_id' => $seller_id
        );
        // print_r($bulk); die;
        $this->load->view('ItemI/inventory_recieve', $bulk);
    }

    public function filter_history() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $slip_no = $_POST['slip_no'];
        $type = $_POST['status'];



        $items = $this->StockInventory_model->filter_history($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no, $type);
        $ItemArray = $items['result'];
        // print_r($ItemArray); die;
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables_stock($rdata['item_sku'], 'type');
            //if($rdata['shelve_no']!='')
            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        $returnArray['count'] = $items['count'];
        echo json_encode($returnArray);
    }

    public function historyViewExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->StockInventory_model->historyViewExport($request);

        $file_name = 'StockHistoryReport.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function filter_recieve() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];



        $items = $this->StockInventory_model->filter_recieve($quantity, $sku, $seller, $page_no);
        $ItemArray = $items['result'];
        // print_r($ItemArray); die;
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables_stock($rdata['item_sku'], 'type');
            //if($rdata['shelve_no']!='')
            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        $returnArray['count'] = $items['count'];
        echo json_encode($returnArray);
    }


    function exportexcelhistoinventory() {
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exportlimit = $_POST['exportlimit'];
        $shipmentsexcel = $this->StockInventory_model->filterexcelhistinventory($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;



        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {

            $DatafileArray[$i]['sku'] = $data['sku'];
            $DatafileArray[$i]['qty'] = $data['qty'];
            $DatafileArray[$i]['seller_name'] = $data['seller_name'];

            $i++;
        }
        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "C1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Item Sku')
                ->setCellValue('B1', 'Quantity')
                ->setCellValue('C1', 'Seller');

        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');

        ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response = array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        die(json_encode($response));
    }

    public function activityhistory($item_sku = null, $seller_id = null) {
        $quantity = $this->StockInventory_model->count_all();
        $sellers = $this->StockInventory_model->find1();
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
            'item_sku' => $item_sku,
            'seller_id' => $seller_id
        );
        // print_r($bulk); die;
        $this->load->view('ItemI/activity_history', $bulk);
    }

    public function filter_activity() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact'];
        $quantity = $_POST['quantity'];
        $sku = $_POST['sku'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $shelve_no = $_POST['shelve_no'];
        $slip_no = $_POST['slip_no'];
        $type = $_POST['status'];



        $items = $this->StockInventory_model->filter_activity($quantity, $sku, $seller, $to, $from, $exact, $page_no, $slip_no, $type);
        $ItemArray = $items['result'];
        //print_r($ItemArray);
        $kk = 0;
        $jj = 0;

        $tolalShip = $items['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        //echo '<pre>';
        $currentDate = date("Y-m-d");
        foreach ($items['result'] as $rdata) {
            //echo $rdata['update_date'];
            $ExitsDate = date("Y-m-d", strtotime($rdata['update_date']));

            if ($currentDate == $ExitsDate)
                $ItemArray[$kk]['checkreQty'] = 'Y';
            else
                $ItemArray[$kk]['checkreQty'] = 'N';
            $ItemArray[$kk]['item_type'] = getalldataitemtables_stock($rdata['item_sku'], 'type');
            //if($rdata['shelve_no']!='')
            //$PData=$this->ItemInventory_model->Getcheckvalidpallet($rdata['shelve_no'],$rdata['sid']);
            //$ItemArray[$kk]['palletArray']=$PData;


            $kk++;
        }
        //echo '<pre>';
        //print_r($palletArray);
        $returnArray['dropexport'] = $expoertdropArr;
        $returnArray['result'] = $ItemArray;
        $returnArray['count'] = $items['count'];
        echo json_encode($returnArray);
    }

    public function activityViewExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->StockInventory_model->activityViewExport($request);

        $file_name = 'ActivityReport.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }
    
    public function GetDeleteInvenntory()
    {
      $request = json_decode(file_get_contents('php://input'), true); 
      $table_id=$request['id'];
      if($table_id>0)
      {
          $data=array("item_sku"=>0,"quantity"=>0,"seller_id"=>0);
          $this->StockInventory_model->UpdateStockLocation($data,$table_id);
         $return=true;
      }
      else
      {
           $return=false;
      }
      echo json_encode($return);
    }
    
    public function update_expire($id=null) {
        
       $view['table_id']=$id;
       $edit_result=$this->StockInventory_model->edit_view($id);
       $view['edit_result']=$edit_result[0];
      // print_r($view); die;
       $this->load->view('ItemI/update_expire_new',$view);
    }
    public function GetUpdateInventoryExpire($id=null){
        if(!empty($id))
        {
            $expity_date=date("Y-m-d",strtotime($this->input->post('expity_date')));
            $updateArray=array('expity_date'=>$expity_date,'expiry'=>'N');
            $this->StockInventory_model->UpdateExpireDate($updateArray,$id);
             $this->session->set_flashdata('msg', 'successfully updated!');
           
        }
        else
        {
             $this->session->set_flashdata('error', 'try again');
        }
         redirect(base_url('stockInventory'));
        
    }

}
