<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Backorder_new extends CI_Controller {

    function __construct() {
        parent::__construct();
        // error_reporting(0);
        $this->load->model('Backorder_new_model');
        $this->load->model('Seller_model');
        
        
    }

    public function pageshortDropData($maxval = 0) {
        //echo $maxval; die;

        $min = 100;
        $max = $maxval; // Just chenge this val;
        $s_val = array();
        if ($max <= 100) {
            $sval = array('100');
        } elseif ($max > 100 && $max <= 200) {
            $sval = array('0' => '100', '100' => 200);
        } elseif ($max > 200 && $max <= 500) {
            $sval = array('0' => 100, '100' => '200', '200' => '500');
        } elseif ($max > 500 && $max <= 1000) {
            $sval = array('0' => 100, '100' => '200', '200' => '500', '500' => 1000);
        } elseif ($max > 1000) {
            $repeat = round(($max - 1000) / 500);

            $l = 1000;
            $sval = array('0' => 100, '100' => '200', '200' => '500', '500' => 1000);
            for ($i = 1; $i <= $repeat; $i++) {
                $l = $l + 500;
                $sval[$l - 500] = $l;
            }
        }
        return $sval;
    }

    public function index() {
        
        $sellers = $this->Seller_model->find2();
        $data['sellers'] = $sellers;
        $this->load->view('ShipmentM/show_backorder_list_new', $data);
    }

    public function checkBackOrders($super_id) {
        //echo json_encode('super_id:'. $this->session->userdata('user_details')['super_id']=$super_id );
        // exit;
        $returnlist = $this->Backorder_new_model->allOgOrders($super_id);
        //echo '<pre>';
        //echo json_encode( $returnlist); //exit;
        $total_stock = array();
        $backOrdersSlip = array();
        $backOrdersDia = array();
        foreach ($returnlist as $key => $val) {



            $sku_details = getSkuListbyAwb($val['slip_no'], $super_id);
            $key_color = null;
            foreach ($sku_details as $key_s => $rows) {
                if (empty($total_stock[$rows['sku']]))
                    $total_stock[$rows['sku']] = $this->Backorder_new_model->getcheckSku($rows['sku'], $val['cust_id'], $super_id);

                // print_r( $total_stock[$rows['sku']]);

                if (empty($total_stock[$rows['sku']]['id'])) {
                    // echo '<br>'.$rows['sku'].'sku not available';
                    $this->Backorder_new_model->updateDiaBatch(array('slip_no' => $val['slip_no'], 'sku' => $rows['sku'], 'back_reason' => 'Sku Not Available'), $super_id);

                    array_push($backOrdersSlip, array('slip_no' => $val['slip_no'], 'backorder' => '1'));
                } else {
                    if ($total_stock[$rows['sku']]['tqty'] < $rows['piece']) {
                        // echo '<br>'.$rows['sku'].'Out Of Stock';
                        $this->Backorder_new_model->updateDiaBatch(array('slip_no' => $val['slip_no'], 'sku' => $rows['sku'], 'back_reason' => 'Out Of Stock'), $super_id);

                        array_push($backOrdersSlip, array('slip_no' => $val['slip_no'], 'backorder' => '1'));
                    } else {
                        // echo '<br>'.$rows['sku'].'In stock';  
                        $this->Backorder_new_model->updateDiaBatch(array('slip_no' => $val['slip_no'], 'sku' => $rows['sku'], 'back_reason' => ''), $super_id);
                        // $total_stock[$rows['sku']]['tqty']= $total_stock[$rows['sku']]['tqty']-$rows['piece'];
                        if (!in_array(array('slip_no' => $val['slip_no'], 'backorder' => '1'), $backOrdersSlip)) {

                            array_push($backOrdersSlip, array('slip_no' => $val['slip_no'], 'backorder' => '0'));
                            array_push($backOrdersDia, $val['slip_no']);
                        }
                    }
                }
            }
        }
        if (!empty($backOrdersSlip)) {
            foreach ($backOrdersSlip as $key => $value) {
                if (in_array(array('slip_no' => $value['slip_no'], 'backorder' => '0'), $backOrdersSlip) && in_array(array('slip_no' => $value['slip_no'], 'backorder' => '1'), $backOrdersSlip)) {
                    //echo '<br>'. $value['slip_no'];
                    if ($value['backorder'] == 0) {
                        // $this->Backorder_new_model->updateDiaBatch(array('slip_no'=>$value['slip_no'],'sku'=>$rows['sku'],'back_reason'=>'' ),$super_id);
                        unset($backOrdersSlip[$key]);
                        unset($backOrdersDia[$key]);
                    }
                }
            }

            array_values($backOrdersSlip);
            $this->Backorder_new_model->updateShipBatch($backOrdersSlip, $super_id);
            //$this->Backorder_model->updateShipBatch_dimension($backOrdersDia,$super_id); 
            //  $this->Backorder_model->updateDiaBatch($backOrdersDia,$super_id);  
        }

        // print_r( $total_stock);
        //print_r( $backOrdersSlip);
    }

    public function showbackorderList() {


        $_POST = json_decode(file_get_contents("php://input"), true);
        $postArray = $_POST;

        $stock_filter = "#f8d7da"; //$postArray['stock_filter'];
        $return = $this->Backorder_model->filter($postArray);
        $tolalShip = $return['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }


        $returnlist = $return['result'];

        $finalArr = array();
        $tolalShip_color = $return['count'];
        $out_of_stock_slip = array();
        $instock = array();
        foreach ($returnlist as $key => $val) {


            // $returnlist[$key]['origin'] = getdestinationfieldshow($val['origin'],'city');
            // $returnlist[$key]['destination'] = getdestinationfieldshow($val['destination'],'city');
            // $returnlist[$key]['dest_country'] = getdestinationfieldshow($val['destination'],'country');
            //  $returnlist[$key]['wh_id'] = Getwarehouse_categoryfield($val['wh_id'], 'name');
            //   $returnlist[$key]['seller_name'] = getallsellerdatabyID($val['cust_id'], 'name');

            $sku_details = Getallskudatadetails_new($val['slip_no']);
            $key_color = null;
            foreach ($sku_details as $key_s => $rows) {

                $total_stock = $this->showbackorderList->getcheckStock($rows['itmSku'], $val['cust_id']);
                if ($total_stock >= $rows['piece']) {
                    $stockvalid = 'green';
                } else {
                    $stockvalid = '#f8d7da';
                    array_push($out_of_stock_slip, $val['slip_no']);
                }
                if (checkvalidskuno($rows['sku']) == 0) {
                    $bg_color = "red";
                    $color = "white";
                } else {
                    $bg_color = "";
                    $color = "";
                }

                $sku_details[$key_s]['sku_valid'] == checkvalidskuno($rows['sku']);
                $sku_details[$key_s]['total_stock'] = $total_stock;
                $sku_details[$key_s]['stock_valid'] = $stockvalid;
                $sku_details[$key_s]['sku'] = $rows['sku'];
                $sku_details[$key_s]['bg_color'] = $bg_color;
                $sku_details[$key_s]['color'] = $color;
            }

            //if (!empty($stock_filter)) 
            {
                $key_color = array_search($stock_filter, array_column($sku_details, 'stock_valid'));

                if (array_key_exists($key_color, $sku_details)) {

                    $returnlist[$key]['sku_details'] = $sku_details;
                    array_push($finalArr, $returnlist[$key]);
                } else {
                    // echo $tolalShip_color."old<br>";
                    $tolalShip_color = $tolalShip_color - 1;
                    // echo $tolalShip_color."new<br>";
                }
            }
//            else {
//                $returnlist[$key]['sku_details'] = $sku_details;
//                array_push($finalArr, $returnlist[$key]);
//            }
        }
        if (!empty($out_of_stock_slip)) {
            $stock_update = array("out_of_stock" => 1);
            $this->showbackorderList->UpdateShipment($stock_update, $out_of_stock_slip);
        }


        $shipments = $this->showbackorderList->filter_new($postArray);

        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }



        $pageShortArr = $this->pageshortDropData($tolalShip);
        // print_r($pageShortArr); die;
        foreach ($shipments['result'] as $rdata) {
            //print "<pre>"; print_r($rdata);die;
            //$shiparray[$ii]['order_type']="";
            //if($expire_data[$ii]['sku']==$rdata['sku'])

            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['dest_country'] = getdestinationfieldshow($rdata['destination'], 'country');
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $shiparray[$ii]['seller_name'] = getallsellerdatabyID($rdata['cust_id'], 'company');

            $transaction_date = '';
            if (!empty($rdata['3pl_close_date'])) {
                $pickup_date = new DateTime($rdata['3pl_pickup_date']);
                $closed_date = new DateTime($rdata['3pl_close_date']);
                $interval = $pickup_date->diff($closed_date);
                $transaction_date = $interval->format('%a days');
            }

            $shiparray[$ii]['transaction_date'] = $transaction_date;

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            $sku_details = Getallskudatadetails_new($rdata['slip_no']);
            $key_color = null;
            foreach ($sku_details as $key_s => $rows) {

                $total_stock = $this->showbackorderList->getcheckStock($rows['itmSku'], $val['cust_id']);
                if ($total_stock >= $rows['piece']) {
                    $stockvalid = 'green';
                } else {
                    $stockvalid = '#f8d7da';
                    array_push($out_of_stock_slip, $val['slip_no']);
                }
                if (checkvalidskuno($rows['sku']) == 0) {
                    $bg_color = "red";
                    $color = "white";
                } else {
                    $bg_color = "";
                    $color = "";
                }

                $sku_details[$key_s]['sku_valid'] == checkvalidskuno($rows['sku']);
                $sku_details[$key_s]['total_stock'] = $total_stock;
                $sku_details[$key_s]['stock_valid'] = $stockvalid;
                $sku_details[$key_s]['sku'] = $rows['sku'];
                $sku_details[$key_s]['bg_color'] = $bg_color;
                $sku_details[$key_s]['color'] = $color;
            }

            //if (!empty($stock_filter)) 
            {
                $key_color = array_search($stock_filter, array_column($sku_details, 'stock_valid'));

                if (array_key_exists($key_color, $sku_details)) {

                    $shiparray[$ii]['sku_details'] = $sku_details;
                    //array_push($finalArr, $shiparray[$key]);
                } else {
                    array_push($instock, $rdata['slip_no']);
                }
            }


            //$shiparray='rith';
            $ii++;
        }

        if (!empty($instock)) {
            $stock_update = array("out_of_stock" => 0);
            $this->showbackorderList->UpdateShipment($stock_update, $instock);
        }


        //echo '<pre>';
        //print_r($shiparray);
        //echo json_encode($shiparray);
        // die;
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

}
