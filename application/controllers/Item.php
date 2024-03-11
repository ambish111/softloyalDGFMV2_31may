<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (menuIdExitsInPrivilageArray(3) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        //$this->load->library('pagination');
        $this->load->helper('zid_helper');
        $this->load->model('Item_model');
        $this->load->model('ItemInventory_model');
        //$this->load->model('ItemCategory_model');
        //$this->load->model('Attribute_model');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index() {


        //$data["items"] =$this->Item_model->all();
        //$data["all_categories"]=$this->ItemCategory_model->all();
        $data['StorageType'] = $this->ItemInventory_model->GetstorageTypes();

        $this->load->view('ItemM/view_items', $data);
    }

    public function bulk_print_barcode() {


        //$data["items"] =$this->Item_model->all();
        //$data["all_categories"]=$this->ItemCategory_model->all();

        $this->load->view('ItemM/bulk_print_barcode');
    }

    public function showiteminventoryexport() {
        $dataArray = $this->Item_model->all();
        $slip_data = array();
        $file_name = 'Items_Inventory' . date('Ymdhis') . '.xls';

        echo json_encode($this->exportiteminventoryexport($dataArray, $file_name));
    }

    function exportiteminventoryexport($dataEx, $file_name) {
        $dataArray = array();
        $i = 0;
        foreach ($dataEx as $data) {
            $dataArray[$i]['name'] = $data->name;
            $dataArray[$i]['sku'] = $data->sku;
            $dataArray[$i]['sku_size'] = $data->sku_size;
            $dataArray[$i]['description'] = $data->description;

            $i++;
        }
        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Name')
                ->setCellValue('B1', 'Item Sku')
                ->setCellValue('C1', 'Storage Capacity')
                ->setCellValue('D1', 'Description');
        $objWriter = PHPExcel_IOFactory::createWriter($doc, 'Excel5');
        ob_start();
        $objWriter->save("php://output");
        $objWriter->save('packexcel/' . $file_name);
        $xlsData = ob_get_contents();
        ob_end_clean();

        return $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );
    }

    public function add_view() {



        if (($this->session->userdata('user_details') != '')) {

            //$data["all_attributes"] =$this->Attribute_model->allAttributes();
            //$data["main_categories"]=$this->ItemCategory_model->allMain();
            //$data["sub_categories"]=$this->ItemCategory_model->allSub();
            //$data["attributes"]=$this->Attribute_model->all();
            $data["StorageArray"] = $this->Item_model->GetAllStorageTypes();
            //print_r($data["StorageArray"]);
            $this->load->view('ItemM/add_item', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function add_bulk_view() {
        $this->load->view('ItemM/add_bulk');
    }

    public function add_bulk_weight_view() {
        $this->load->view('ItemM/add_weight');
    }

    function sku_validation($str) {
        $field_value = $str; //this is redundant, but it's to show you how
        //the content of the fields gets automatically passed to the method

        if ($this->Item_model->GetchekskuDuplicate_new($field_value)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function ean_validation($str) {
        $field_value = $str; //this is redundant, but it's to show you how
        //the content of the fields gets automatically passed to the method

        if ($this->Item_model->GetchekskuDuplicate_new_ean($field_value)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function add() {
        $this->load->helper('security');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('wh_id', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('storage_id', 'Select Storage Type', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules("sku", 'SKU', 'trim|required|callback_sku_validation');
        if (menuIdExitsInPrivilageArray(230) == 'Y') {
           // $this->form_validation->set_rules("ean_no", 'EAN NO.', 'trim|required|callback_ean_validation');
        }
        $this->form_validation->set_rules("sku_size", 'Capacity', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->add_view();
        } else {

            // print_r($_FILES);

            if (!empty($_FILES['item_path']['name'])) {

                $config['upload_path'] = 'assets/item_uploads/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name'] = $_FILES['item_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('item_path')) {

                    $uploadData = $this->upload->data();

                    $small_img = $config['upload_path'] . '' . $uploadData['file_name'];

                    $uploadedImage = $uploadData['file_name'];
                    $source_path = $config['upload_path'] . $uploadedImage;
                    $thumb_path = $config['upload_path'];
                    $thumb_width = 120;
                    $thumb_height = 120;

                    // Image resize config 
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $source_path;
                    $config['new_image'] = $thumb_path;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = $thumb_width;
                    $config['height'] = $thumb_height;

                    // Load and initialize image_lib library 
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                } else {

                    $small_img = "";
                }
            } else {
                $small_img = "";
            }
            //  echo $small_img;  
            // die;
            //$errors= $this->upload->display_errors();



            if (empty($this->input->post('alert_day'))) {
                $alert_day = 0;
            } else {
                $alert_day = $this->input->post('alert_day');
            }

            $data2 = array(
                'type' => 'B2C', //$this->input->post('type')
                'storage_id' => $this->input->post('storage_id'),
                'expire_block' => $this->input->post('expire_block'),
                'name' => $this->input->post('name'),
                'sku' => $this->input->post('sku'),
                'sku_size' => $this->input->post('sku_size'),
                'description' => $this->input->post('description'),
                'less_qty' => $this->input->post('less_qty'),
                'alert_day' => $alert_day,
                'color' => $this->input->post('color'),
                'length' => $this->input->post('length'),
                'width' => $this->input->post('width'),
                'height' => $this->input->post('height'),
                'weight' => $this->input->post('weight'),
                'wh_id' => $this->input->post('wh_id'),
                'item_path' => $small_img,
                'entry_date' => date('Y-m-d H:i:s'),
                //'ean_no' => $this->input->post('ean_no'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                    //'item_subcategory'=>$sub_category,
                    //'attributes_values'=>$attributes_values,
            );
            if (menuIdExitsInPrivilageArray(230) == 'Y') {
                $data2['ean_no'] = $this->input->post('ean_no');
            }
            // echo "<pre>"; print_r($data2);
            // die;

            $item_id = $this->Item_model->add($data2);
            $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
            redirect('Item');
        }
    }

    public function edit_view($id) {

        $data = $this->Item_model->edit_view($id);
        //print_r($data);
        $data["StorageArray"] = $this->Item_model->GetAllStorageTypes();
        $this->load->view('ItemM/item_detail', $data);
    }

    public function edit($item_id) {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('wh_id', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('storage_id', 'Select Storage Type', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        // $this->form_validation->set_rules("sku", 'SKU', 'trim|required');
        $this->form_validation->set_rules("sku_size", 'Capacity', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_view($item_id);
        } else {
            // echo $this->input->post('sku');
            $old_data = $this->Item_model->edit_view($item_id);

            $old_sku = $old_data['item']->sku;
            $check_item = true; //$this->Item_model->GetchekskuDuplicate_edit(trim($this->input->post('sku')), $old_sku);
            //echo $check_item; die;

            if ($check_item == true) {
                if (!empty($_FILES['item_path']['name'])) {

                    $config['upload_path'] = 'assets/item_uploads/';
                    $config['overwrite'] = TRUE;
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['file_name'] = $_FILES['item_path']['name'];
                    $config['file_name'] = time();
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('item_path')) {

                        $uploadData = $this->upload->data();

                        $small_img = $config['upload_path'] . '' . $uploadData['file_name'];

                        $uploadedImage = $uploadData['file_name'];
                        $source_path = $config['upload_path'] . $uploadedImage;
                        $thumb_path = $config['upload_path'];
                        $thumb_width = 120;
                        $thumb_height = 120;

                        // Image resize config 
                        $config['image_library'] = 'gd2';
                        $config['source_image'] = $source_path;
                        $config['new_image'] = $thumb_path;
                        $config['maintain_ratio'] = FALSE;
                        $config['width'] = $thumb_width;
                        $config['height'] = $thumb_height;

                        // Load and initialize image_lib library 
                        $this->load->library('image_lib', $config);
                        $this->image_lib->resize();
                    } else {

                        $small_img = $this->input->post('old_item_path');
                    }
                } else {
                    $small_img = $this->input->post('old_item_path');
                }
                $data = array(
                    'storage_id' => $this->input->post('storage_id'),
                    'name' => $this->input->post('name'),
                    'expire_block' => $this->input->post('expire_block'),
                    // 'sku' => trim($this->input->post('sku')),
                    'sku_size' => $this->input->post('sku_size'),
                    'description' => $this->input->post('description'),
                    'less_qty' => $this->input->post('less_qty'),
                    'alert_day' => $this->input->post('alert_day'),
                    'color' => $this->input->post('color'),
                    'length' => $this->input->post('length'),
                    'width' => $this->input->post('width'),
                    'height' => $this->input->post('height'),
                    'weight' => $this->input->post('weight'),
                    'ean_no' => $this->input->post('ean_no'),
                    'item_path' => $small_img,
                    'wh_id' => $this->input->post('wh_id')
                );

                $this->Item_model->edit($item_id, $data);

                //$this->Item_model->edit($item_id,$data);


                $this->session->set_flashdata('msg', 'id#' . $item_id . ' has been updated successfully');
            } else {
                $this->session->set_flashdata('error', $this->input->post('sku') . ' sku is already exists.');
            }
            redirect('Item');
        }

        // }
    }

    public function filter() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $result = $this->Item_model->filter($_POST);

        $newArray = $result['result'];
        foreach ($newArray as $key => $val) {
            $newArray[$key]['storage_type'] = Getallstoragetablefield($val['storage_id'], 'storage_type');
            $newArray[$key]['wh_name'] = Getwarehouse_categoryfield($val['wh_id'], 'name');

            $newArray[$key]['seller_name'] = GetallCutomerBysellerId($val['added_by'], 'name');
            if (empty($newArray[$key]['seller_name']))
                $newArray[$key]['seller_name'] = "Admin";
        }
        $dataArray['result'] = $newArray;
        $dataArray['count'] = $result['count'];
        echo json_encode($dataArray);
    }

    public function Getallsellersdata() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = Getallsellerdata();
        echo json_encode($returnArray);
    }

    public function GetgenrateSkubarcodes() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $seller_id = $_POST['seller_id'];
        $sqty = $_POST['sqty'];
        $sku = $_POST['sku'];
        $chargeprint = getalluserfinanceRates($seller_id, 16, 'rate');
        $finalcharge = $chargeprint * $sqty;
        $addedarray = array('seller_id' => $seller_id, 'sqty' => $sqty, 'entrydate' => date("Y-m-d H:i:sa"), 'rate' => $finalcharge, 'super_id' => $this->session->userdata('user_details')['super_id'],);
        $this->Item_model->Getallskubarcodeadddata($addedarray);
        $returnarray = array();
        for ($ii = 0; $ii < $sqty; $ii++) {
            $returnarray[$ii]['barcode'] = barcodeRuntime($sku);
            $returnarray[$ii]['rate'] = $finalcharge;
            $returnarray[$ii]['sku'] = $sku;
        }

        echo json_encode($returnarray);
    }

    public function BulkPrintSKU() {
        $this->load->library('M_pdf');
        $postData = $this->input->post();
        $seller_id = $postData['seller_id'];
        $sqty = $postData['sku_qty'];
        $skuIds = explode("\n", $postData['sku_barcode']);
        $skuLoopArray = array_unique($skuIds);
        if (!empty($seller_id) && !empty($sqty) && !empty($skuLoopArray)) {
            // print_r($skuLoopArray); die;


            $chargeprint = getalluserfinanceRates($seller_id, 16, 'rate');
            $finalcharge = $chargeprint * $sqty;
            // $addedarray = array('seller_id' => $seller_id, 'sqty' => $sqty, 'entrydate' => date("Y-m-d H:i:sa"), 'rate' => $finalcharge, 'super_id' => $this->session->userdata('user_details')['super_id'],);
            //  $this->Item_model->Getallskubarcodeadddata($addedarray);
            $response = array();

            //echo '<pre>';
            // $dfdf =  getallitemskubyid($skuLoopArray);
            $trimmed_array = array_map('trim', $skuLoopArray);

            $ExitsArray = GetskuDetailsForPrint($trimmed_array);

            if (!empty($ExitsArray)) {

                foreach ($ExitsArray as $sku_rows) {
                    $chargeprint = getalluserfinanceRates($seller_id, 16, 'rate');
                    $finalcharge = $chargeprint * $sqty;
                    $addedarray = array('seller_id' => $seller_id, 'sqty' => $sqty, 'entrydate' => date("Y-m-d H:i:sa"), 'rate' => $finalcharge, 'super_id' => $this->session->userdata('user_details')['super_id'],);
                    $this->Item_model->Getallskubarcodeadddata($addedarray);
                    for ($ii = 0; $ii < $sqty; $ii++) {
                        $counter = $ii + 1;
                        // echo $sku_rows['sku'];

                        $body .= '<tr>
                
                <td>' . $counter . '</td>
                 <td><img src="' . barcodeRuntime($sku_rows['sku']) . '"/><br>
                 ' . $sku_rows['sku'] . '</td>
                
                
                
                 
                </tr>';
                    }

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
                    $mpdf->Output('sku_barcode' . date('Y-m-d H:i:s') . '.pdf', 'D');
                    redirect(base_url('bulk_print_barcode'));
                }
            } else {
                echo "hh";
                die;
                $this->session->set_flashdata('something', 'Please Enter Valid Sku');
                redirect(base_url('bulk_print_barcode'));
            }
            // die;
        } else {
            $this->session->set_flashdata('something', 'all field are required!');
            redirect(base_url('bulk_print_barcode'));
        }


        //  echo json_encode($returnarray);
    }

    public function GetprintBarcode() {
        $this->load->library('M_pdf');
        $skus = $this->input->post('skus');
        // print_r($skus);
        $html .= '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"></head><body>'
                . '<table style="width:100%">
                <tbody>';

        foreach ($skus as $key => $val) {

            $html .= '<tr><td style="text-align:center;"><img src="' . barcodeRuntime($val) . '"><br>' . $val . '</td></tr>';
        }



        $html .= '</tbody>
                </table>';
        $html .= '</body></html>';
        //  echo $html;die;
        $mpdf = new mPDF('utf-8', array(50, 30), 0, '', 0, 0, 0, 0, 0, 0);
        $mpdf->WriteHTML($html);
        //$mpdf->SetDisplayMode('fullpage'); 
        //$mpdf->Output();
        $mpdf->Output('sku_barcode' . date('Y-m-d H:i:s') . '.pdf', 'I');
    }

    public function updateZid_product($seller_id = null) {


        $sync_product_zid = GetallCutomerBysellerId($seller_id, 'sync_product_zid');

        if ($sync_product_zid != 'Y') {
            $this->session->set_flashdata('error', "Stock Update not active from zid");
            redirect(base_url() . 'Item');
            die;
        }

        $ziDAllArr = GetAllQtyforSeller_new($seller_id);

        // print_r($ziDAllArr); die;

        foreach ($ziDAllArr as $key => $zidReqArr) {
            $quantity = $zidReqArr['quantity'];
            $pid = $zidReqArr['zid_pid'];
            $token = $zidReqArr['manager_token'];
            $storeID = $zidReqArr['zid_sid'];
            $sku = $zidReqArr['sku'];
            update_zid_product($quantity, $pid, $token, $storeID, $seller_id, $sku);
        }
        $this->session->set_flashdata('msg', 'has been updated successfully');
        redirect('Seller');
    }

    public function updateZid_product_new_pages($seller_id = null) {

        $ziDAllArr = GetAllQtyforSeller_new_zid_count($seller_id);

        echo 'pages  ';
        echo ceil($ziDAllArr / 500);
    }

    public function updateZid_product_new($seller_id = null, $page = 1) {


        $sync_product_zid = GetallCutomerBysellerId($seller_id, 'sync_product_zid');

        if ($sync_product_zid != 'Y') {
            $this->session->set_flashdata('error', "Stock Update not active from zid");
            redirect(base_url() . 'Item');
            die;
        }

        $ziDAllArr = GetAllQtyforSeller_new_zid($seller_id, $page);

        // print_r($ziDAllArr); die;


        foreach ($ziDAllArr as $key => $zidReqArr) {
            $quantity = $zidReqArr['quantity'];
            $pid = $zidReqArr['zid_pid'];
            $token = $zidReqArr['manager_token'];
            $storeID = $zidReqArr['zid_sid'];
            $sku = $zidReqArr['sku'];
            update_zid_product($quantity, $pid, $token, $storeID, $seller_id, $sku);
        }
        $this->session->set_flashdata('msg', 'has been updated successfully');
        redirect('Seller');
    }

    public function updateSAlla_product($seller_id = null, $item_sku = null) {


        if ($seller_id == '214') {
            $ziDAllArr = $this->Item_model->GetTempStockData($seller_id, $item_sku);
        } else {
            $ziDAllArr = GetAllQtyforSellerSalla_new($seller_id);
        }
        //  echo "<pre>";
        //  print_r($ziDAllArr);die;



        foreach ($ziDAllArr as $key => $zidReqArr) {
            $quantity = $zidReqArr['quantity'];
            $uniqueid = $zidReqArr['uniqueid'];
            $sku = $zidReqArr['sku'];

            if ($zidReqArr['salla_athentication'] != NULL) {
                $token = $zidReqArr['salla_athentication'];
                // echo $token; die;
                $pid = $zidReqArr['sku'];
                update_salla_qty_product_new($quantity, $pid, $token, $seller_id);
            } else {

                if (!empty($ziDAllArr)) {
                    salla_provider_qty_update($quantity, $uniqueid, $sku, $seller_id);
                    $token = '$2y$04$rncDoc3yqrue9Fc6Ey29JOs1Qws4J6yVr9UbF2kDMKWv//xAhJ72y';
                }
            }

            //exit;
        }
       // die;

        $this->session->set_flashdata('msg', 'has been updated successfully');
        redirect('Seller');
    }

    public function updateSAlla_product2($seller_id = null) {


        if ($this->session->userdata('user_details')['super_id'] == 175 && $seller_id == 214) {
            if ($seller_id > 0) {
                $seller_id = $seller_id;
            } else {
                $seller_id = 214;
            }

            $ziDAllArr = GetAllQtyforSellerSalla_new($seller_id);

            //print_r($ziDAllArr); exit;


            $this->db->query("TRUNCATE `stocks`");

            foreach ($ziDAllArr as $key => $zidReqArr) {
                $quantity = $zidReqArr['quantity'];
                $uniqueid = $zidReqArr['uniqueid'];
                $sku = $zidReqArr['sku'];

                $this->Item_model->getupdateTempstock($quantity, $seller_id, $sku, 175);
                //exit;
            }
            echo "success";
            // die;
        }

        // $this->session->set_flashdata('msg', 'has been updated successfully');
        // redirect('Seller');
    }

    public function bulk_update() {

        $this->load->view('ItemM/bulk_update_item');
    }

}

?>
