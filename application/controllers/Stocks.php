<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stocks extends MY_Controller {

    function __construct() {
        parent::__construct();
        //$this->load->library('pagination');
        $this->load->model('Stock_model');
        $this->load->helper('utility');
        $this->load->helper('stock');

        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function generateStockLocation() {
        if (menuIdExitsInPrivilageArray(1) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->view('stocks/generateStockLocation');
    }

    public function generateStock() {



        $this->load->library('form_validation');
        $this->form_validation->set_rules('stockCount', 'Number Of Stock Location', 'trim|required');
        $this->form_validation->set_rules('charname', 'Location Latters', 'trim|required|max_length[4]');

        if ($this->form_validation->run() == FALSE) {
            $this->generateStockLocation();
        } else {
            $numbers = $this->input->post('stockCount');
            $charname = $this->input->post('charname');
            $insertData = array();

            $old_no = GetstockID_n();
            $jj = 1;
            for ($i = 0; $i < $numbers; $i++) {
                $lastno = $old_no + $jj;
                $locno = sprintf("%'06d", $old_no + $jj);
                $newlocno = $this->session->userdata('user_details')['super_id'] . $locno;

                //$insertData[$i]['stock_location2'] = $charname."-".$this->generateRandomString(8);
                // $insertData[$i]['stock_location'] = strtoupper($charname . '-' . abs(crc32(uniqid())));
                $insertData[$i]['stock_location'] = strtoupper($charname . '-' . $newlocno);
                $insertData[$i]['seller_id'] = $seller;
                $insertData[$i]['super_id'] = $this->session->userdata('user_details')['super_id'];
                $insertData[$i]['lastno'] = $lastno;

                $jj++;
            }
            //  echo '<pre>';
            // print_r($insertData);
            //  die;
            $this->Stock_model->insertStockLocation($insertData);
            redirect(base_url('showStocklocation'));
        }
    }

    public function showStocklocation($type = null) {
        $bulk = array(
            'type' => $type,
            'type1' => $type
        );

        $this->load->view('stocks/showStocklocation', $bulk);
    }

    public function stockLocationFilter() {



        $_POST = json_decode(file_get_contents('php://input'), true);

        $shipments = $this->Stock_model->stockLocationFilter($_POST);

        $shiparray = $shipments['result'];
        $ii = 0;
        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;

        $j = 0;
        for ($k = 0; $k < $tolalShip;) {
            $k = $k + $downlaoadData;
            if ($k > 0) {
                $expoertdropArr[] = array('j' => $j, 'k' => $k);
            }
            $j = $k;
        }
        foreach ($shipments['result'] as $rdata) {

            $shiparray[$ii]['barcode'] = barcodeRuntime($rdata['stock_location']);

            $ii++;
        }

        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function updateManifest($uniqueid = null) {

        // echo $uniqueid;
        $data = $this->Stock_model->filterUpdate(1, array("manifestid" => $uniqueid));

        $this->load->view('manifest/updateManifest_new', $data);
    }

    public function GetSkulistForUpdateInventory() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $data = $this->Stock_model->filterUpdate(1, array("manifestid" => $_POST['uid']));
        echo json_encode($data);
    }

    public function GetUpdateManifestStockLocation() {

        // error_reporting(-1);
        // ini_set('display_errors', 1);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $uid = $_POST['list']['uid']; //'5E0DB8692B7A6';
        $sid = $_POST['list']['sid']; //3;//
        $sku = $_POST['list']['sku'];

        $SkuData = $this->Stock_model->GetallmanifestskuData_new($uid, $sid, $sku);

        foreach ($SkuData as $rdata) {


            $totalmissing = $rdata['missing_qty'] + $rdata['damage_qty'];

            // $totalqty=$rdata['qty'];
            $expire_date = $rdata['expire_date'];
            if (empty($expire_date)) {
                $expdate = "0000-00-00";
            } else {
                $expdate = $expire_date;
            }
            $totalqty = $rdata['received_qty']; //GetManifestInventroyUpdateQty($uid, $sid, $rdata['sku']);
            $totalqty_new = $rdata['qty'];
            $totalqty_n = $rdata['received_qty'];
            $skuid = $rdata['item_sku'];
            $totalsku_size = $rdata['sku_size'];
            $storage_type = $rdata['storage_type'];
            $wh_id = $rdata['wh_id'];
            $warehouse_name = Getwarehouse_categoryfield($wh_id, 'name');

            // $shelveNo = getshelveNobyid($skuid);
            if ($totalsku_size >= $totalqty) {
                $locationLimit = 1;
            } else {
                $locationLimit1 = $totalqty / $totalsku_size; // 11/3 
                $locationLimit = ceil($locationLimit1);
            }

            $update_qty = 0;
            $diffrentQTY = 0;
            $AddQty = 0;
            $updateaty = $totalqty;
            for ($ii = 0; $ii < $locationLimit; $ii++) {


                if ($totalqty > $totalsku_size) {
                    //  echo "dddddd";
                    $update_qty = $totalsku_size;
                    $diffrentQTY = $totalqty - $totalsku_size;
                    $totalqty = $totalqty - $totalsku_size;
                } else {
                    //  echo "rrr";
                    $update_qty = $diffrentQTY;
                    $totalqty = 0;
                }

                if ($totalsku_size <= $updateaty) {
                    $AddQty = $totalsku_size;
                    $updateaty = $updateaty - $totalsku_size;
                } else {
                    $AddQty = $updateaty;
                    $updateaty = $updateaty;
                }

                //    echo $AddQty;
                $reurnarray[] = array(
                    "skuid" => $skuid,
                    "uid" => $uid,
                    "sid" => $sid,
                    "filled" => $AddQty,
                    "storage_type" => $rdata['storage_type'],
                    "stockLocation" => "",
                    "sku" => $rdata['sku'],
                    "missing_qty" => $rdata['missing_qty'],
                    "damage_qty" => $rdata['damage_qty'],
                    "storage_type" => $storage_type,
                    "capacity" => $totalsku_size,
                    "shelveNo" => "",
                    "storage_type" => $rdata['storage_type'],
                    "boxes" => $locationLimit,
                    "totalqty" => $totalqty_n,
                    "totalqty_new" => $totalqty_new,
                    "warehouse_name" => $warehouse_name,
                    "wh_id" => $wh_id,
                    "expire_date" => $expdate
                );
            }
        }


        $return = array("result" => $reurnarray);
        echo json_encode($return);
    }

    public function GetcheckStockLocation() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $stockData = $postData['list'];
        $fillStockLocations = $postData['fillStockLocations'];
        $capacity = $stockData['capacity'];
        $boxes = $stockData['boxes'];
        $sku = $stockData['sku'];
        $skuid = $stockData['skuid'];
        $stockLocation = $stockData['stockLocation'];
        $totalqty = $stockData['totalqty'];
        $sid = $stockData['sid'];
        $skuid = $stockData['skuid'];
        $update_qty = $stockData['filled'];
        // echo "<pre>";
        //print_r($stockData);

        if (!in_array($stockLocation, $fillStockLocations)) {
            $inventory_arr = $this->Stock_model->GetcheckLocation($stockData);
            // print_r($inventory_arr);
            if (!empty($inventory_arr)) {

                if ($inventory_arr['quantity'] == 0 && $inventory_arr['seller_id'] == 0 && $inventory_arr['item_sku'] == 0) {
                    $return = array("error" => "valid", "location" => $stockLocation);
                } else {
                    if ($sid == $inventory_arr['seller_id']) {
                        if ($skuid == $inventory_arr['item_sku']) {
                            $old_qty = $inventory_arr['quantity'];
                            $totalQTY_size_in = $old_qty + $update_qty;
                            if ($totalQTY_size_in <= $capacity) {
                                $return = array("error" => "valid", "location" => $stockLocation);
                            } else {
                                $return = array("error" => "capacity_full", "location" => $stockLocation);
                            }
                        } else {
                            $return = array("error" => "invalid_location", "location" => $stockLocation);
                        }
                    } else {
                        $return = array("error" => "invalid_location", "location" => $stockLocation);
                    }
                }
            } else {
                $return = array("error" => "invalid_location", "location" => $stockLocation);
            }
        } else {
            $return = array("error" => "invalid_location", "location" => $stockLocation);
        }

        echo json_encode($return);
    }

    public function GetcheckshelvekLocation() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $stockData = $postData['list'];
        $shelveNo = $stockData['shelveNo'];
        $shelveArr = $this->Stock_model->GetcheckshelvekLocation($stockData);
        if (!empty($shelveArr)) {
            $return = array("error" => "valid", "location" => $shelveNo);
        } else {
            $return = array("error" => "invalid_location", "location" => $shelveNo);
        }
        echo json_encode($return);
    }

    public function GetSaveInventoryManifest() {


        $postData = json_decode(file_get_contents('php://input'), true);

        $locations = $postData['locations'];
        $otherArr = $postData['otherData'];
        $this->load->helper('zid');
        $this->load->model('Manifest_model');

        if ($otherArr['c_number'] == count($locations)) {
            $temp = array_unique(array_column($locations, 'stockLocation'));
            $table_location = $this->Stock_model->GetcheckLocationData($temp);
            // print_r($temp);`
            // echo $table_location."=====".count($locations);
            if ($table_location == count($locations)) {
                $seller_id = trim($locations[0]['sid']);
                $sku = trim($locations[0]['sku']);
                $chargeQty = $locations[0]['totalqty'];
                $totalsku_size = $locations[0]['capacity'];

                $super_id = $this->session->userdata('user_details')['super_id'];
                $seller_data = GetSinglesellerdata($seller_id, $super_id);
                $uid = trim($locations[0]['uid']);
                $token = $seller_data['manager_token'];
                $salatoken = $seller_data['salla_athentication'];
                $item_type = getalldataitemtables($locations[0]['skuid'], 'type');

                if ($totalsku_size >= $chargeQty) {
                    $locationLimit = 1;
                } else {
                    $locationLimit1 = $chargeQty / $totalsku_size; // 11/3 
                    $locationLimit = ceil($locationLimit1);
                }
                foreach ($locations as $key => $val) {
                    $skuid = $val['skuid'];
                    $expire_date = $val['expire_date'];
                    $wh_id = $val['wh_id'];
                    $stockLocation = trim($val['stockLocation']);
                    $shelveNo = trim($val['shelveNo']);
                    $sid = $val['sid'];
                    if (empty($expire_date)) {
                        $expdate = "0000-00-00";
                    } else {
                        $expdate = $expire_date;
                    }


                    $updateQry = array(
                        "item_sku" => $skuid,
                        // "quantity" => quantity+$val['filled'],
                        "seller_id" => $sid,
                        "shelve_no" => !empty($shelveNo) ? $shelveNo : "",
                        // "stock_location" => $stockLocation,
                        "expity_date" => $expire_date,
                        "wh_id" => $wh_id,
                        "itype" => $item_type
                    );

                    $in_data = $this->Stock_model->GetcheckLocation($val);
                    $newQty = ($val['filled'] + $in_data['quantity']);
                    $activitiesArr = array(
                        'exp_date' => $expdate,
                        'st_location' => $stockLocation,
                        'item_sku' => $skuid,
                        'user_id' => $this->session->userdata('user_details')['user_id'],
                        'seller_id' => $sid,
                        'qty' => $newQty,
                        'p_qty' => $in_data['quantity'],
                        'qty_used' => $val['filled'],
                        'type' => 'Add',
                        'entrydate' => date("Y-m-d h:i:s"),
                        'super_id' => $super_id,
                        'shelve_no' => !empty($shelveNo) ? $shelveNo : "",
                        "comment" => "Add Manifest"
                    );

                    $updateQry_w = array('stock_location' => $stockLocation, 'super_id' => $super_id);
                    $this->Stock_model->GetUpdateInventory($updateQry, $updateQry_w, $val['filled']);
                    $this->Stock_model->AddInventoryHistory($activitiesArr);

                    $chargeArr = array(
                        'uid' => $uid,
                        'sid' => $seller_id
                    );

                    if ($item_type == 'B2B') {
                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($chargeArr, $locationLimit, $chargeQty, $locations[0]['skuid']);
                    } else {
                        $result12 = $this->Manifest_model->GetUpdatePickupchargeInvocie($chargeArr, $val['filled'], $chargeQty, $locations[0]['skuid']);
                    }
                }

                $manifestUpdate = array(
                    'confirmO' => 'Y',
                    'on_hold' => 'N'
                );
                $mainStock = GetcheckMainStock($sku, $seller_id);
                if (empty($mainStock)) {
                    $ins_data = array('sku' => $sku, "qty" => $chargeQty, "seller_id" => $seller_id, "super_id" => $super_id);
                    $this->Stock_model->addcustomerInventory($ins_data);
                    $type_data = "Add";
                    $act_qty = $chargeQty;
                } else {
                    $oldqty = $mainStock['qty'] + $chargeQty;
                    $up_data = array("qty" => $oldqty);
                    $this->Stock_model->upcustomerInventory($up_data, $mainStock);
                    $type_data = "Update";
                    $act_qty = $oldqty;
                }

                $inventory_activity_user = array(
                    'item_sku' => $skuid,
                    'user_id' => $this->session->userdata('user_details')['user_id'],
                    'seller_id' => $sid,
                    'qty' => $act_qty,
                    'p_qty' => !empty($mainStock['qty']) ? $mainStock['qty'] : 0,
                    'qty_used' => $chargeQty,
                    'type' => $type_data,
                    'entrydate' => date("Y-m-d h:i:s"),
                    'super_id' => $super_id,
                    "comment" => "Add Manifest"
                );
                $this->Stock_model->inventory_activity_user($inventory_activity_user);
                $this->Stock_model->getupdateconfirmstatus_new($uid, $manifestUpdate, $sku);

                if (!empty($token)) {
                    //==========update zid stock===============//

                    $zidReqArr = GetAllQtyforSeller($sku, $seller_id);
                    // print_r($zidReqArr);                      
                    $quantity = $zidReqArr['quantity']; //+$fArray['qty'];
                    $pid = $zidReqArr['zid_pid'];
                    $token = $token;
                    $storeID = $seller_data['zid_sid'];
                    $reszid = update_zid_product($quantity, $pid, $token, $storeID, $seller_id, $zidReqArr['sku']);
                }
                if (!empty($salatoken)) {
                    $sallaReqArr = GetAllQtyforSeller($sku, $seller_id);
                    $quantity = $sallaReqArr['quantity']; //+$fArray['qty'];
                    $pid = $sallaReqArr['sku'];
                    $sallatoken = $salatoken;
                    // echo "<pre>"; print_r($sallaReqArr);
                    $reszid = update_salla_qty_product($quantity, $pid, $sallatoken, $seller_id);
                }
            }
        }



        echo json_encode(true);
    }

    public function bulk_print_stocklocation() {


        //$data["items"] =$this->Item_model->all();
        //$data["all_categories"]=$this->ItemCategory_model->all();

        $this->load->view('stocks/bulk_print_stlocation');
    }

    public function BulkPrintst() {
        $this->load->library('M_pdf');
        $postData = $this->input->post();
        $st_location = explode("\n", $postData['st_location']);
        $st_location_LoopArray = array_unique($st_location);
        if (!empty($st_location_LoopArray)) {

            $trimmed_array = array_map('trim', $st_location_LoopArray);
            // print_r($trimmed_array);
            // die;
            $ExitsArray = $this->Stock_model->GetcheckLocationData_print($trimmed_array);

            if (!empty($ExitsArray)) {
                foreach ($ExitsArray as $key => $row) {

                    $counter = $key + 1;
                    $body .= '<tr>
                
                    <td>' . $counter . '</td>
                 <td align="center" style="padding: 12px;"><img src="' . barcodeRuntime($row['stock_location']) . '"/><br><span align="center">
                 ' . $row['stock_location'] . '</span></td>
                 </tr>';

                    // print_r($sku_id);
                }
                if (!empty($body)) {
                    $html .= '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"></head><body>'
                            . '<table style="width:100%" border="1" >
                <thead>
                  <tr>
                    <th>Sr.No.</th>
                    <th>Barcode</th></tr>
                </thead><tbody>';

                    $html .= '' . $body . '</tbody>
                </table>';
                    $html .= '</body></html>';
                    //echo $html;die;

                    $mpdf = new mPDF('utf-8', array(110, 170), 0, '', 0, 0, 0, 0, 0, 0);
                    $mpdf->WriteHTML($html);
                    //$mpdf->SetDisplayMode('fullpage'); 
                    //$mpdf->Output();
                    $mpdf->Output('Stock Locations' . date('Y-m-d H:i:s') . '.pdf', 'D');
                    redirect(base_url('print_stlocation'));
                }
            } else {
                //echo "hh";
                // die;
                $this->session->set_flashdata('something', 'Please Enter Valid Stock Location');
                redirect(base_url('print_stlocation'));
            }
            // die;
        } else {
            //echo "z";
            // die;
            $this->session->set_flashdata('something', 'all field are required!');
            redirect(base_url('print_stlocation'));
        }


        //  echo json_encode($returnarray);
    }

    public function exportlocation() {


        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Stock_model->exportlocation($request);
        $file_name = 'Stock Location.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function bulk_upload() {

        '..';
        die;
        $this->load->view('stocks/add_bulk');
    }

    function Inventory_bulk_format() {
        $this->load->library("excel");
        $object = new PHPExcel();

        $object->setActiveSheetIndex(0);

        $table_columns = array(
            "Item SKU",
            "Seller Account No",
            "Quantity",
            "Expire Date(DD-MM-YYYY) ",
            "Shelve No ",
            "Warehouse",
            "Stock Location"
        );

        $column = 0;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Item Inventory Upload New.xls"');
        $object_writer->save('php://output');
    }

    function add_ItemInventory_bulk() {


        //  die;
        if (isset($_FILES["file"]["name"])) {
            $path = $_FILES["file"]["tmp_name"];
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            // echo '<pre>';
            $this->load->model('ItemInventory_model');
            foreach ($object->getWorksheetIterator() as $worksheet) {

                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $i = 0;

                $palletsA_error = array();
                $anyErrorShow = 1;

                // echo $highestRow;
                for ($row = 2; $row <= $highestRow; $row++) {


                    $itemskusku = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                    $selleraccountid = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                    $itemQty = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                    $expiredate = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCellByColumnAndRow(3, $row)->getValue())));
                    if (empty($worksheet->getCellByColumnAndRow(3, $row)->getValue()))
                        $expiredate = "0000-00-00";
                    else {
                        $expiredate = date("Y-m-d", strtotime($expiredate));
                    }
                    $palletno = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                    $warehouseName = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                    $stock_location = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                    if (empty($itemskusku)) {
                        $errordata['emptysku'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        $validskuid = GetallitemcheckDuplicate($itemskusku);

                        if ($validskuid == 0) {
                            $anyErrorShow = 1;
                            $errordata['errorsku'][] = $itemskusku;
                            $palletsA_error[] = $row;
                        }
                    }

                    if (empty($warehouseName)) {
                        $errordata['emptywarehouseName'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        $warehouse = Getwarehouse_categoryfield_name($warehouseName, 'id');
                        if ($warehouse == 0) {
                            $anyErrorShow = 1;
                            $errordata['warehouse'][] = $warehouseName;
                            $palletsA_error[] = $row;
                        }
                    }
                    if (empty($selleraccountid)) {
                        $errordata['emptyselleraccountid'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        $seller_id = GetallaccountidBysellerID($selleraccountid);
                        if ($seller_id == 0) {
                            $anyErrorShow = 1;
                            $errordata['seller_id'][] = $selleraccountid;
                            $palletsA_error[] = $row;
                        }
                    }


                    if (empty($stock_location)) {
                        $errordata['empty_stocation'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        //$sku_data = singleSkuDetails($itemskusku);
                        //  $ItemskuID = $sku_data['id'];

                        $in_data = $this->Stock_model->GetcheckLocationData_add($stock_location);
                        // echo "sssssss";
                        if (empty($in_data)) {
                            $anyErrorShow = 1;
                            $errordata['invalid_location'][] = $stock_location;
                            $palletsA_error[] = $row;
                        }
                    }
                    if (empty($itemQty)) {
                        $anyErrorShow = 1;  
                        $errordata['emptyqty'][] = $row;
                        $palletsA_error[] = $row;
                    }









                    if (empty($palletno)) {

                        $errordata['emptypallets'][] = $row;
                        $palletsA_error[] = $row;
                    } else {
                        //echo $palletno."<br>";
                        $PalletsCheck = $this->ItemInventory_model->GetcheckvalidPalletNo($palletno);
                        if ($PalletsCheck == false) {
                            $anyErrorShow = 1;
                            $errordata['pallets'][] = $row;
                            $palletsA_error[] = $row;
                        }
                    }

                    $i++;
                }


                if (empty($palletsA_error)) {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $itemskusku = trim($worksheet->getCellByColumnAndRow(0, $row)->getValue());
                        $selleraccountid = trim($worksheet->getCellByColumnAndRow(1, $row)->getValue());
                        $itemQty = trim($worksheet->getCellByColumnAndRow(2, $row)->getValue());

                        $expiredate = date('d-m-Y', PHPExcel_Shared_Date::ExcelToPHP(trim($worksheet->getCellByColumnAndRow(3, $row)->getValue())));
                        if (empty($worksheet->getCellByColumnAndRow(3, $row)->getValue()))
                            $expiredate = "0000-00-00";
                        else {
                            $expiredate = date("Y-m-d", strtotime($expiredate));
                        }
                        $palletno = trim($worksheet->getCellByColumnAndRow(4, $row)->getValue());
                        $warehouseName = trim($worksheet->getCellByColumnAndRow(5, $row)->getValue());
                        $stock_location = trim($worksheet->getCellByColumnAndRow(6, $row)->getValue());

                        $seller_id = GetallaccountidBysellerID($selleraccountid);

                        $warehouse = Getwarehouse_categoryfield_name($warehouseName, 'id');

                        $palletsA = $palletno;

                        $chargeQty = $itemQty;
                        $sku_data = singleSkuDetails($itemskusku);
                        $ItemskuID = $sku_data['id'];
                        $sku_size = $sku_data['sku_size'];
                        $itype = $sku_data['type'];

                        $expiredate = $expiredate;
                        $val = $this->Stock_model->GetcheckLocationData_add($stock_location);

                        $qty = $itemQty;

                        if (!empty($val)) {
                            $check = $qty + $val['quantity'];
                            $shelve_no = $val['shelve_no'];
                            if (empty($shelve_no)) {
                                $shelve_no = $palletsA;
                            }
                            if ($check <= $sku_size) {
                                $lastQtyUp = $val['quantity'];
                                $activitiesArr = array(
                                    'exp_date' => $expiredate,
                                    'st_location' => $stock_location,
                                    'item_sku' => $ItemskuID,
                                    'user_id' => $this->session->userdata('user_details')['user_id'],
                                    'seller_id' => $seller_id,
                                    'qty' => $check,
                                    'p_qty' => $lastQtyUp,
                                    'qty_used' => $qty,
                                    'type' => 'Update',
                                    'entrydate' => date("Y-m-d h:i:s"),
                                    'super_id' => $this->session->userdata('user_details')['super_id'],
                                    'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                                    "comment" => "Add Bulk"
                                );

                                $new_inventory = array(
                                    'item_sku' => $ItemskuID,
                                    'quantity' => $check,
                                    'seller_id' => $seller_id,
                                    'shelve_no' => !empty($shelve_no) ? $shelve_no : "",
                                    'expity_date' => $expiredate,
                                    'itype' => $itype,
                                    'wh_id' => $warehouse
                                );

                                $req = $this->Stock_model->GetUpdateInventory_bulk($new_inventory, $stock_location, $val['id']);
                                if ($req == true) {

                                    $mainStock = GetcheckMainStock(trim($itemskusku), $seller_id);
                                    if (empty($mainStock)) {
                                        $ins_data = array('sku' => $itemskusku, "qty" => $chargeQty, "seller_id" => $seller_id, "super_id" => $this->session->userdata('user_details')['user_id']);
                                        $this->Stock_model->addcustomerInventory($ins_data);
                                        $type_data = "Add";
                                        $act_qty = $chargeQty;
                                    } else {
                                        $oldqty = $mainStock['qty'] + $chargeQty;
                                        $up_data = array("qty" => $oldqty);
                                        $this->Stock_model->upcustomerInventory($up_data, $mainStock);
                                        $type_data = "Update";
                                        $act_qty = $oldqty;
                                    }
                                    $inventory_activity_user = array(
                                        'item_sku' => $ItemskuID,
                                        'user_id' => $this->session->userdata('user_details')['user_id'],
                                        'seller_id' => $seller_id,
                                        'qty' => $act_qty,
                                        'p_qty' => !empty($mainStock['qty']) ? $mainStock['qty'] : 0,
                                        'qty_used' => $chargeQty,
                                        'type' => $type_data,
                                        'entrydate' => date("Y-m-d h:i:s"),
                                        'super_id' => $this->session->userdata('user_details')['user_id'],
                                        "comment" => "Add Bulk"
                                    );
                                    $this->Stock_model->inventory_activity_user($inventory_activity_user);

                                    $this->Stock_model->AddInventoryHistory($activitiesArr);
                                    $errordata['validsku'][] = $itemskusku;
                                }
                            }
                        } else {
                            $errordata['invalid_data'][] = $row;
                        }



                        $i++;
                    }
                }
            }
            //  echo $row;
            //  echo "<pre>";
            // print_r($palletsA_error);
            // print_r($errordata);
            //print_r($data);
            // die;

            if ($Status == TRUE) {

                $this->session->set_flashdata('messarray', $errordata);
                redirect('stockInventory');
            } else {

                $this->session->set_flashdata('messarray', $errordata);
                redirect('stockInventory');
            }
        } else {
            $this->session->set_flashdata('error', 'file error Bulk add failed');
            redirect('stockInventory');
        }
    }
    
    public function inventory_check() {

        $this->load->view('stocks/inventory_check');
    }
    
     public function inventoryCheck() {
        $PostData = json_decode(file_get_contents('php://input'), true);
        $detailsArr = $this->Stock_model->inventoryCheckQry($PostData);
        echo json_encode($detailsArr);
    }



}

?>
