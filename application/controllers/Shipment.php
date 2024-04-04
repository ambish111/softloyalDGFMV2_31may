<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipment extends MY_Controller {

    function __construct() {
        parent::__construct();
    
        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Seller_model');
        $this->load->model('Item_model');
        $this->load->model('Status_model');
        $this->load->model('Pickup_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
        $this->load->model('User_model');
        $this->load->model('ItemInventory_model');
        $this->load->model('Ccompany_model');
        $this->load->model('Ccompany_model');
        $this->load->model('Torodcompany_model');
        

        // $this->user_id = isset($this->sess
        // 
        // ion->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index() {
        $data = GetCourierCompanyDrop();
        if (menuIdExitsInPrivilageArray(1) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        //echo "sssssss"; die;
        // $status=$this->Shipment_model->allstatus();
        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        // 	$shipments = $this->Shipment_model->all();

        $search = $this->input->post('tracking_numbers');
//    echo $search;exit;
        if (!empty($search)) {
            $condition = strtoupper($search);
        } else {
            $condition = null;
        }
        //     if($shipments!=Null){
        // for($i=0;$i<count($shipments);$i++){
        // 	$sellers[$i]=$this->Seller_model->find_customer_sellerm($shipments[$i]->cust_id);
        // 	// $items_by_shipments[$i]=$this->Item_model->findItemsByShipment($shipments[$i]->slip_no);
        // }
        // $condition=null;
        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }

        // for($i=0;$i<count($sellers);$i++){
        // 	$items[$i]=$this->Item_model->find($shipments[$i]->sku);
        // }

        $status_name = $_POST['status_name'];

        $data = $this->Shipment_model->getStatusIDByName('3PL Updates');

        $result = array();
        if (!empty($data)) {
            $result = getallstatusbyid($data[0]['id']);
        }
        //print "<pre>"; print_r($result);die;
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
            'status_3pl' => $result,
                //'items'=>$items,
                //'sellers'=>$sellers
        );
        // print_r($bulk);exit;
        // print("here");
        // print_r($shipments);
        // exit();
        // print_r($bulk);
        // exit();
        //print_r($bulk);
        //exit();
        // print_r($condition,);
        $this->load->view('ShipmentM/view_shipments', $bulk);
        // }else{
        // 	$this->load->view('ShipmentM/view_shipments');
        // }
        //$this->load->view('ShipmentM/view_shipments');
    }

    public function ForwardtoDeliveryStationNew() {
        $this->load->view('ShipmentM/forward_client_new', $bulk);
    }

    public function runshellForward() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $param = array('super_id' => $this->session->userdata('user_details')['super_id'],'param'=>$postData);

        $paramJsone=json_encode($param);
        // echo $paramJsone; 

        // exec('/usr/bin/php /var/www/html/diggipacks/fullfillment/autobulk_assign_newfm.php ' . escapeshellarg(serialize($param)) . '  2>&1 &/dev/null 2>&1 & ',$output);
        //exec('/usr/bin/php /var/www/html/diggipack_new/demofulfillment/autobulk_assign_newfm.php ' . escapeshellarg(serialize($param)) . '  /dev/null & ');
        //    print_r($output);
        shell_exec('/usr/bin/php /var/www/html/diggipack_new/demofulfillment/autobulk_assign_newfm.php ' . escapeshellarg(serialize($param)) .' > /dev/null 2>/dev/null &');
    }

    public function ForwardtoDeliveryStation() {
        $this->load->view('ShipmentM/forward_client', $bulk);
    }

    public function update_destination() {
        $this->load->view('ShipmentM/update_destination');
    }

    public function ReversetoDeliveryStation() {
        if (menuIdExitsInPrivilageArray(144) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/reverse_client');
    }

    public function ViewReverseShipment() {
        if (menuIdExitsInPrivilageArray(146) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/view_reverse_shipments');
    }

    // Bulk Shipment Upload By  Manish Chaudhary
    public function bulkUploadShipment() {
        
        $this->load->view('ShipmentM/add_bulk_shipment');
    }

    public function forward_report() {
        $data = GetCourierCompanyDrop();
        if (menuIdExitsInPrivilageArray(1) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();


        $search = $this->input->post('tracking_numbers');

        if (!empty($search)) {
            $condition = strtoupper($search);
        } else {
            $condition = null;
        }

        if ($this->session->flashdata('condition')) {
            $condition = $this->session->flashdata('condition');
        }


        $status_name = $_POST['status_name'];

        $data = $this->Shipment_model->getStatusIDByName('3PL Updates');

        $result = array();
        if (!empty($data)) {
            $result = getallstatusbyid($data[0]['id']);
        }

        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
            'status_3pl' => $result,
                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('ShipmentM/forward_report', $bulk);
    }

    public function runshellbackorder() {

        $url = base_url('Backorder/checkBackOrders/' . $this->session->userdata('user_details')['super_id']);
      echo  file_get_contents( $url );
        // die();
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'GET',
        // ));

        // echo $response = curl_exec($curl);

        // curl_close($curl);
    }

    public function runshell() {
        
        $param = array('super_id' => $this->session->userdata('user_details')['super_id']);

        $paramJsone = json_encode($param);
        //print_r($param); die; 

        exec('/usr/bin/php /var/www/html/diggipack_new/fs_files/auto_assign_fm.php ' . escapeshellarg(serialize($param)) . '  2>&1 & ', $output);

        print_r($output);
        
     
    }

    public function runshell_tracking() {

        exec("php /var/www/html/diggipack_new/fs_files/Aramex_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Clex_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Makhdoom_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Esnad_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Labaih_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Aymakan_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Zajil_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Barq_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/naqel_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Saee_track.php > /dev/null 2>&1 &");
        exec("php /var/www/html/diggipack_new/fs_files/Ajeek_track.php > /dev/null 2>&1 &");
        return true;
    }

    public function getallbackordersview() {
        if (menuIdExitsInPrivilageArray(23) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();

        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );
        $this->load->view('ShipmentM/backorder', $bulk);
    }
    public function bulkaudit() {
        if (menuIdExitsInPrivilageArray(157) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/bulk_audit');
    }


    public function ordergeneratedView() {
        if (menuIdExitsInPrivilageArray(80) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $sellers = $this->Seller_model->find2();
        $data['sellers'] = $sellers;
        $this->load->view('ShipmentM/orderGen', $data);
    }

    public function manifestView($id = null) {
        if (menuIdExitsInPrivilageArray(103) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $data['m_id'] = $id;
        //echo $pickUpId;
        $this->load->view('ShipmentM/manifestView', $data);
    }

    public function delivery_manifest() {
        if (menuIdExitsInPrivilageArray(103) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('ShipmentM/delivery_manifest', $bulk);
    }

    public function BulkPrintPage() {
        $this->load->view('ShipmentM/bulk_print', $bulk);
    }

    public function GetreadyprintShow() {
        $show_awb_no = $this->input->post('show_awb_no');
        $print_ready = $this->input->post('print_ready');
        //print_r($print_ready); die();


        if ($print_ready == '3PL') {
            if ($show_awb_no != '') {

                $a = trim(' ', $show_awb_no);

                if ($a != '') {
                    if (strpos($a, PHP_EOL) !== '') {

                        $slipData = explode(PHP_EOL, $show_awb_no);
                    } elseif (strpos($a, ',') !== '') {

                        $slipData = explode(",", $show_awb_no);
                    }
                }

                $awbData = array();
                foreach ($slipData as $sliploop) {
                    if (trim($sliploop) != '')
                        array_push($awbData, "'" . trim($sliploop) . "'");
                }
                //print_r($awbData); exit;
                Print_getall3plfm($awbData);
            }
        } elseif ($print_ready == '3PL SLS') {
            if ($show_awb_no != '') {

                $a = trim(' ', $show_awb_no);

                if ($a != '') {
                    if (strpos($a, PHP_EOL) !== '') {

                        $slipData = explode(PHP_EOL, $show_awb_no);
                    } elseif (strpos($a, ',') !== '') {

                        $slipData = explode(",", $show_awb_no);
                    }
                }

                $awbData = array();
                foreach ($slipData as $sliploop) {
                    if (trim($sliploop) != '')
                        array_push($awbData, "'" . trim($sliploop) . "'");
                }
                //print_r($awbData); exit;
                Print_getall3plfm($awbData, $type = 'moovo');
            }
        } elseif ($print_ready == '3PL MOOVO') {
            if ($show_awb_no != '') {

                $a = trim(' ', $show_awb_no);

                if ($a != '') {
                    if (strpos($a, PHP_EOL) !== '') {

                        $slipData = explode(PHP_EOL, $show_awb_no);
                    } elseif (strpos($a, ',') !== '') {

                        $slipData = explode(",", $show_awb_no);
                    }
                }

                $awbData = array();
                foreach ($slipData as $sliploop) {
                    if (trim($sliploop) != '')
                        array_push($awbData, "'" . trim($sliploop) . "'");
                }
                //print_r($awbData); exit;
                Print_getall3plfm($awbData, $type = 'moovo');
            }
        } elseif ($print_ready == 'SMSA') {
            $CURRENT_TIME = date('H:i:s');
            $CURRENT_DATE = date('Y-m-d H:i:s');
            if ($show_awb_no != '') {

                $a = trim(' ', $show_awb_no);

                if ($a != '') {
                    if (strpos($a, PHP_EOL) !== '') {

                        $slipData = explode(PHP_EOL, $show_awb_no);
                    } elseif (strpos($a, ',') !== '') {

                        $slipData = explode(",", $show_awb_no);
                    }
                }

                $awbData = array();
                foreach ($slipData as $sliploop) {
                    if (trim($sliploop) != '')
                        array_push($awbData, "'" . trim($sliploop) . "'");
                }
                //print_r($awbData); exit;
                print_shipment_smsa($awbData);
            }
        } elseif ($print_ready == 'ARAMEX') {
            $CURRENT_TIME = date('H:i:s');

            $CURRENT_DATE = date('Y-m-d H:i:s');
            //	print_r($_POST); exit;
            if ($show_awb_no != '') {

                $a = trim(' ', $show_awb_no);

                if ($a != '') {
                    if (strpos($a, PHP_EOL) !== '') {

                        $slipData = explode(PHP_EOL, $show_awb_no);
                    } elseif (strpos($a, ',') !== '') {

                        $slipData = explode(",", $show_awb_no);
                    }
                }

                $awbData = array();
                foreach ($slipData as $sliploop) {
                    if (trim($sliploop) != '')
                        array_push($awbData, "'" . trim($sliploop) . "'");
                }
                // print_r($awbData); exit;
                print_shipment_aramex($awbData);
                // print_r($awbData);exit;
            }
        }
    }

    public function GetDeliveryStationClient() {

        echo "working.........";
        die;
        $awb_no = $this->input->post('awb_no');
        if (!empty($awb_no)) {
            $SlipNos = preg_replace('/\s+/', ',', $awb_no);
            $slip_arr = explode(",", $SlipNos);
            $slipData = array_unique($slip_arr);



            if ($slipData != '') {
                $client_id = $this->input->post('client_name');
                $comment = $this->input->post('comment');

                if (!empty($client_id)) {
                    $ClientArr = GetcuriertableData($client_id);
                    $DataArray = array();
                    $notAvailable = array();
                    $thirdParty = array();
                    $returned_array = array();
                    $delivered_array = array();
                    foreach ($slipData as $key1 => $val1) {
                        $shipmentData = $this->Shipment_model->GetForwardToclientShipDataQry($slipData[$key1]);

                        if (!empty($shipmentData)) {
                            $receiver_name = $shipmentData[0]['reciever_name'];
                            $receiver_email = $shipmentData[0]['reciever_email'];
                            $receiver_phone = $shipmentData[0]['reciever_phone'];
                            $receiver_address = $shipmentData[0]['reciever_address'];

                            //$declared_charge = $shipmentData[0]['declared_charge'];
                            $declared_charge = $shipmentData[0]['total_cod_amt'];
                            $type = 'Domestic';
                            $item_description = $shipmentData[0]['status_describtion'];
                            $pieces = $shipmentData[0]['pieces'];
                            $booking_id = $shipmentData[0]['booking_id'];
                            $sender_name = $shipmentData[0]['sender_name'];
                            $s_address = $shipmentData[0]['sender_address'];
                            $s_zip = $shipmentData[0]['sender_zip'];
                            $s_phone = $shipmentData[0]['sender_phone'];
                            $save_sender_origin = $shipmentData[0]['origin'];
                            $save_receiver_destination = $shipmentData[0]['destination'];
                            $sender_city = getdestinationfieldshow($shipmentData[0]['origin'], 'city');
                            $currency = site_configTable("default_currency");
                            $pay_mode = $shipmentData[0]['mode'];
                            $cod_amount = $shipmentData[0]['total_cod_amt'];
                            $awb_no = $shipmentData[0]['slip_no'];
                            $weight = $shipmentData[0]['weight'];
                            $sender_email = $shipmentData[0]['sender_email'];

                            if ($ClientArr['company'] == 'USTUL') {
                                $sender_email = $ClientArr['email'];     //provided by company  :  (column name: password || date 
                                $password = $ClientArr['password'];
                                $url = $ClientArr['api_url'];

                                $Receiver_name = $shipmentData[0]['reciever_name'];
                                $Receiver_email = $shipmentData[0]['reciever_email'];
                                $Receiver_phone = $shipmentData[0]['reciever_phone'];
                                $Receiver_address = $shipmentData[0]['reciever_address'];
                                if (empty($Receiver_address))
                                    $Receiver_address = 'N/A';
                                $Reciever_city = getdestinationfieldshow($shipmentData[0]['destination'], 'city');
                                $product_type = 'Parcel'; //beone ka database
                                $service = '2'; // beone wali
                                $description = $shipmentData[0]['status_describtion'];
                                if (empty($description))
                                    $description = 'N/A';
                                // this is prodect name (column name: status_describtion 

                                $ajoul_booking_id = $shipmentData[0]['booking_id'];
                                $s_name = $shipmentData[0]['sender_name'];
                                $s_address = $shipmentData[0]['sender_address'];
                                $s_zip = $shipmentData[0]['sender_zip'];
                                $s_phone = $shipmentData[0]['sender_phone'];
                                $s_city = getdestinationfieldshow($shipmentData[0]['origin'], 'city');


                                $pay_mode = $shipmentData[0]['mode']; //paymode either CASH or COD:(column name: mode || date 
                                $codValue = $shipmentData[0]['total_cod_amt'];               //COD charges.  :  (column name: 	total_cod_amt || date type: 
                                $product_price = $shipmentData[0]['declared_charge'];   //(column name: declared_charge || date type: int || value: 11)
                                $booking_id = $shipmentData[0]['slip_no']; // send awb number ajoul
                                $shipper_refer_number = $shipmentData[0]['booking_id']; // ajoul ki booking id 
                                $weight = $shipmentData[0]['weight'];
                                if ($weight == 0)
                                    $weight = 1;
                                //weight should be in kg.:(column name: weight || date type
                                $NumberOfParcel = $shipmentData[0]['pieces']; //(column name: pieces || date type: int || value: 5)

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

                                $response = curl_exec($ch);
                                //print_r($ch);
                                curl_close($ch);
                                $responseArray = json_decode($response, true);
                                if (!empty($responseArray['awb'])) {

                                    //$generated_pdf=file_get_contents($responseArray['awb_print_url']);
                                    //	$encoded=base64_decode($generated_pdf);
                                    //header('Content-Type: application/pdf');
                                    //file_put_contents("ustul_labels/$awb_no.pdf",$generated_pdf);
                                    $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $client_id, 'frwd_company_awb' => trim($responseArray['awb']), 'frwd_company_label' => $responseArray['awb_print_url'], 'delivered' => 5, 'code' => 'DL');
                                    $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $awb_no);

                                    $CURRENT_TIME = date("H:i:s");
                                    $details = 'Forwarded to ' . $ClientArr['company'];
                                    $statusArr = array(
                                        'slip_no' => $awb_no,
                                        'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                        'new_status' => 5,
                                        'pickup_time' => $CURRENT_TIME,
                                        'pickup_date' => $CURRENT_DATE,
                                        'Activites' => 'Forward',
                                        'Details' => $details,
                                        'entry_date' => $CURRENT_DATE,
                                        'user_id' => $this->session->userdata('user_details')['user_id'],
                                        'user_type' => 'user',
                                        'comment' => $comment,
                                        'code' => 'DL',
                                    );
                                    $this->Shipment_model->GetstatuInsert_forward($statusArr);

                                    array_push($DataArray, $awb_no);
                                    $returnArr['successAbw'][] = $awb_no;
                                    send_message($awb_no);
                                } else {
                                    //array_push($error_array,  $awb_no.':'.$response);
                                    $returnArr['responseError'][] = $awb_no . ':' . $response;
                                }
                            } elseif ($ClientArr['company'] == 'AYMAKAN') {

                                $entry_date = date('Y-m-d H:i:s');
                                $pickup_date = date("Y-m-d", strtotime($entry_date));
                                $receiver_address = stripslashes($receiver_address);
                                $receiver_address = str_replace('/', '', $receiver_address);
                                // $receiver_address = $functions->seo_friendly_url($receiver_address);
                                if ($pay_mode == 'COD') {
                                    $price_set = 113;
                                    $is_cod = 1;
                                } else {
                                    $is_cod = 0;
                                    $price_set = 364;
                                }
                                $reciever_city = getdestinationfieldshow($shipmentData[0]['origin'], 'aymakan_city');
                                $sender_city = getdestinationfieldshow($shipmentData[0]['destination'], 'aymakan_city');

                                // $api_key_test="8cd0e5ed8602fec137c18e4b8102aa8a-8c770b19-be29-476f-ad90-937a5851b364-dda076d553e8453e32d945c5fb99188f/a19c18540df40398b018b32e4b6099bd/3eb9e950-a6e7-4ce1-b902-30df17194a37";
                                $api_key = "ece4e852ce8c682966662902f2e33d03-80075b70-8485-4fef-a0a8-623b701e14a3-8431435b3454d9df80c759ac5b339e3f/4f045bbcd76bbf0b47913539393aa7db/d95e8656-3080-44ae-aaeb-979a0e3ca849";

                                $all_param_data = array(
                                    "requested_by" => $sender_name,
                                    "declared_value" => $declared_charge,
                                    "declared_value_currency" => $currency,
                                    "price_set" => $price_set,
                                    "reference" => $awb_no,
                                    "is_cod" => $is_cod,
                                    "cod_amount" => $cod_amount,
                                    "currency" => $currency,
                                    "delivery_name" => $receiver_name,
                                    "delivery_email" => $receiver_email,
                                    "delivery_city" => $reciever_city,
                                    "delivery_address" => $receiver_address,
                                    "delivery_country" => 'SA',
                                    "delivery_phone" => $receiver_phone,
                                    "delivery_description" => $item_description,
                                    "collection_name" => $sender_name,
                                    "collection_address" => $s_address,
                                    "collection_email" => $sender_email,
                                    "collection_city" => $sender_city,
                                    "collection_postcode" => $s_zip,
                                    "collection_country" => 'SA',
                                    "collection_phone" => $s_phone,
                                    "pickup_date" => $pickup_date,
                                    "weight" => $weight,
                                    "pieces" => $pieces);
                                // print_r($all_param_data);exit;
                                $json_final_date = json_encode($all_param_data);
                                // print_r($json_final_date);exit;
                                $headers = array(
                                    "Accept:application/json",
                                    "Authorization: ece4e852ce8c682966662902f2e33d03-80075b70-8485-4fef-a0a8-623b701e14a3-8431435b3454d9df80c759ac5b339e3f/4f045bbcd76bbf0b47913539393aa7db/d95e8656-3080-44ae-aaeb-979a0e3ca849");
                                // print_r($headers);exit;
                                // $test_create_url="https://dev.aymakan.com.sa/api/v2/shipping/create";
                                $live_url = "https://aymakan.com.sa/api/v2/shipping/create";
                                // $url="https://aymakan.com.sa/api/v2/price-sets";
                                // $url="https://dev.aymakan.com.sa/api/v2/shipping/track/261923";
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $live_url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
                                $response = curl_exec($ch);
                                curl_close($ch);
                                $responseArray = json_decode($response, true);
                                // echo "<pre>";	print_r($responseArray);exit;
                                if (empty($responseArray['errors'])) {
                                    $aymakan_awb = $responseArray['data']['shipping']['tracking_number'];
                                    $mediaData = $responseArray['data']['shipping']['pdf_label'];
                                    if (!empty($mediaData)) {
                                        $CURRENT_DATE = date("Y-m-d H:i:s");
                                        $CURRENT_TIME = date("H:i:s");
                                        $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $client_id, 'frwd_company_awb' => $responseArray['data']['shipping']['tracking_number'], 'frwd_company_label' => $responseArray['data']['shipping']['pdf_label'], 'delivered' => 5, 'code' => 'DL');
                                        $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $awb_no);


                                        $details = 'Forwarded to ' . $ClientArr['company'];
                                        $statusArr = array(
                                            'slip_no' => $awb_no,
                                            'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                            'new_status' => 5,
                                            'pickup_time' => $CURRENT_TIME,
                                            'pickup_date' => $CURRENT_DATE,
                                            'Activites' => 'Forward',
                                            'Details' => $details,
                                            'entry_date' => $CURRENT_DATE,
                                            'user_id' => $this->session->userdata('user_details')['user_id'],
                                            'user_type' => 'user',
                                            'comment' => $comment,
                                            'code' => 'DL',
                                        );
                                        $this->Shipment_model->GetstatuInsert_forward($statusArr);
                                        array_push($DataArray, $awb_no);
                                        $returnArr['successAbw'][] = $awb_no;
                                        send_message($awb_no);
                                    }
                                } else {

                                    $returnArr['responseError'][] = $awb_no . ':' . $response;
                                }
                            } elseif ($ClientArr['company'] == 'ARAMEX') {


                                $sku_data = $this->Shipment_model->Getskudetails_forward($awb_no);
                                $sku_all_names = array();
                                $sku_total = 0;
                                foreach ($sku_data as $key => $val) {
                                    $skunames_quantity = $sku_data[$key]['sku'] . "*" . $sku_data[$key]['piece'];
                                    $sku_total = $sku_total + $sku_data[$key]['piece'];
                                    array_push($sku_all_names, $skunames_quantity);
                                }
                                $sku_all_names = implode(",", $sku_all_names);
                                if ($sku_total != 0) {
                                    $complete_sku = $sku_all_names . ",sku_total=" . $sku_total;
                                } else {
                                    $complete_sku = $sku_all_names;
                                }
                                if ($pay_mode == 'COD') {

                                    $pay_mode = 'P';
                                    $CashOnDeliveryAmount = array("Value" => $cod_amount,
                                        "CurrencyCode" => site_configTable("default_currency"));
                                    $services = 'CODS';
                                } elseif ($pay_mode == 'CC') {

                                    $pay_mode = 'P';
                                    $CashOnDeliveryAmount = NULL;
                                    $services = '';
                                }
                                $reciever_city = getdestinationfieldshow($shipmentData[0]['origin'], 'aramex_city');
                                $sender_city = getdestinationfieldshow($shipmentData[0]['destination'], 'aramex_city');

                                $date = (int) microtime(true) * 1000;

                                $params = array(
                                    'ClientInfo' =>
                                    array(
                                        'UserName' => 'salem@track.com.sa',
                                        'Password' => 'A!123123123b',
                                        'Version' => 'v1',
                                        'AccountNumber' => '60498164',
                                        'AccountPin' => '664165',
                                        'AccountEntity' => 'RUH',
                                        'AccountCountryCode' => 'SA'
                                    ),
                                    'LabelInfo' => array("ReportID" => 9729, "ReportType" => "URL"),
                                    'Shipments' =>
                                    array(
                                        0 =>
                                        array(
                                            'Reference1' => '',
                                            'Reference2' => '',
                                            'Reference3' => '',
                                            'Shipper' =>
                                            array(
                                                'Reference1' => $booking_id,
                                                'Reference2' => '',
                                                'AccountNumber' => '60498164',
                                                'PartyAddress' =>
                                                array(
                                                    'Line1' => $s_address,
                                                    'Line2' => '',
                                                    'Line3' => '',
                                                    'City' => 'Riyadh',
                                                    'StateOrProvinceCode' => '',
                                                    'PostCode' => '0000',
                                                    'CountryCode' => 'SA',
                                                    'Longitude' => 0,
                                                    'Latitude' => 0,
                                                    'BuildingNumber' => NULL,
                                                    'BuildingName' => NULL,
                                                    'Floor' => NULL,
                                                    'Apartment' => NULL,
                                                    'POBox' => NULL,
                                                    'Description' => NULL,
                                                ),
                                                'Contact' =>
                                                array(
                                                    'Department' => '',
                                                    'PersonName' => $sender_name,
                                                    'Title' => '',
                                                    'CompanyName' => $sender_name,
                                                    'PhoneNumber1' => $s_phone,
                                                    'PhoneNumber1Ext' => '',
                                                    'PhoneNumber2' => '',
                                                    'PhoneNumber2Ext' => '',
                                                    'FaxNumber' => '',
                                                    'CellPhone' => $s_phone,
                                                    'EmailAddress' => 'support@track.com',
                                                    'Type' => '',
                                                ),
                                            ),
                                            'Consignee' =>
                                            array(
                                                'Reference1' => '',
                                                'Reference2' => '',
                                                'AccountNumber' => '',
                                                'PartyAddress' =>
                                                array(
                                                    'Line1' => $receiver_address,
                                                    'Line2' => '',
                                                    'Line3' => '',
                                                    'City' => $receiver_city,
                                                    'StateOrProvinceCode' => '',
                                                    'PostCode' => '0000',
                                                    'CountryCode' => 'SA',
                                                    'Longitude' => 0,
                                                    'Latitude' => 0,
                                                    'BuildingNumber' => '',
                                                    'BuildingName' => '',
                                                    'Floor' => '',
                                                    'Apartment' => '',
                                                    'POBox' => NULL,
                                                    'Description' => '',
                                                ),
                                                'Contact' =>
                                                array(
                                                    'Department' => '',
                                                    'PersonName' => $receiver_name,
                                                    'Title' => '',
                                                    'CompanyName' => $receiver_name,
                                                    'PhoneNumber1' => $receiver_phone,
                                                    'PhoneNumber1Ext' => '',
                                                    'PhoneNumber2' => '',
                                                    'PhoneNumber2Ext' => '',
                                                    'FaxNumber' => '',
                                                    'CellPhone' => $receiver_phone,
                                                    'EmailAddress' => 'support@track.com',
                                                    'Type' => '',
                                                ),
                                            ),
                                            'ThirdParty' =>
                                            array(
                                                'Reference1' => '',
                                                'Reference2' => '',
                                                'AccountNumber' => '',
                                                'PartyAddress' =>
                                                array(
                                                    'Line1' => '',
                                                    'Line2' => '',
                                                    'Line3' => '',
                                                    'City' => '',
                                                    'StateOrProvinceCode' => '',
                                                    'PostCode' => '',
                                                    'CountryCode' => '',
                                                    'Longitude' => 0,
                                                    'Latitude' => 0,
                                                    'BuildingNumber' => NULL,
                                                    'BuildingName' => NULL,
                                                    'Floor' => NULL,
                                                    'Apartment' => NULL,
                                                    'POBox' => NULL,
                                                    'Description' => NULL,
                                                ),
                                                'Contact' =>
                                                array(
                                                    'Department' => '',
                                                    'PersonName' => '',
                                                    'Title' => '',
                                                    'CompanyName' => '',
                                                    'PhoneNumber1' => '',
                                                    'PhoneNumber1Ext' => '',
                                                    'PhoneNumber2' => '',
                                                    'PhoneNumber2Ext' => '',
                                                    'FaxNumber' => '',
                                                    'CellPhone' => '',
                                                    'EmailAddress' => '',
                                                    'Type' => '',
                                                ),
                                            ),
                                            'ShippingDateTime' => "/Date(" . $date . ")/",
                                            'DueDate' => "/Date(" . $date . ")/",
                                            'Comments' => '',
                                            'PickupLocation' => '',
                                            'OperationsInstructions' => '',
                                            'AccountingInstrcutions' => '',
                                            'Details' =>
                                            array(
                                                'Dimensions' => NULL,
                                                'ActualWeight' =>
                                                array(
                                                    'Unit' => 'KG',
                                                    'Value' => $weight,
                                                ),
                                                'ChargeableWeight' => NULL,
                                                'DescriptionOfGoods' => $complete_sku,
                                                'GoodsOriginCountry' => 'SA',
                                                'NumberOfPieces' => 1,
                                                'ProductGroup' => 'DOM',
                                                'ProductType' => 'ONP',
                                                'PaymentType' => $pay_mode,
                                                'PaymentOptions' => "",
                                                'CustomsValueAmount' => NULL,
                                                'CashOnDeliveryAmount' => $CashOnDeliveryAmount,
                                                'InsuranceAmount' => NULL,
                                                'CashAdditionalAmount' => NULL,
                                                'CashAdditionalAmountDescription' => '',
                                                'CollectAmount' => NULL,
                                                'Services' => $services,
                                                'Items' =>
                                                array(
                                                ),
                                            ),
                                            'Attachments' =>
                                            array(),
                                            'ForeignHAWB' => $awb_no,
                                            'TransportType ' => 0,
                                            'PickupGUID' => '',
                                            'Number' => NULL,
                                            'ScheduledDelivery' => NULL,
                                        ),
                                    ),
                                    'Transaction' =>
                                    array(
                                        'Reference1' => '',
                                        'Reference2' => '',
                                        'Reference3' => '',
                                        'Reference4' => '',
                                        'Reference5' => '',
                                    )
                                );

                                // $params['Shipments']['Shipment']['Details']['Items'][] = array(
                                // 	'PackageType' 	=> 'Box',
                                // 	'Quantity'		=> 1,
                                // 	'Weight'		=> array(
                                // 			'Value'		=> 0.5,
                                // 			'Unit'		=> 'Kg',		
                                // 	),
                                // 	'Comments'		=> 'Docs',
                                // 	'Reference'		=> ''
                                // );
                                $dataJson = json_encode($params);
                                // echo "<pre>";print_r($dataJson);echo "<br>";echo "<br>";exit;
                                $headers = array(
                                    "Content-type:application/json");
                                // print_r($final_array);exit;
                                // $url = "https://ws.dev.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments";
                                $url = "https://ws.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc/json/CreateShipments";
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
                                $response = curl_exec($ch);
                                curl_close($ch);
                                $xml2 = new SimpleXMLElement($response);

                                $awb_array = json_decode(json_encode((array) $xml2), TRUE);
                                // echo "<pre>";print_r ($awb_array);echo "<br>";echo "<br>";echo "<br>";exit;
                                $check_error = $awb_array['HasErrors'];
                                if ($check_error == 'true') {
                                    if (empty($awb_array['Shipments'])) {
                                        $error_response = $awb_array['Notifications']['Notification'];
                                        $error_response = json_encode($error_response);
                                        array_push($error_array, $awb_no . ':' . $error_response);
                                        $returnArr['responseError'][] = $awb_no . ':' . $error_response;
                                    } else {


                                        $error_response = $awb_array['Shipments']['ProcessedShipment']['Notifications']['Notification'];
                                        $error_response = json_encode($error_response);
                                        array_push($error_array, $awb_no . ':' . $error_response);
                                        $returnArr['responseError'][] = $awb_no . ':' . $error_response;
                                    }
                                } else {
                                    $main_result = $awb_array['Shipments']['ProcessedShipment'];
                                    // $Check_inner_error=$main_result['HasErrors'];
                                    $Check_inner_error = $main_result['HasErrors'];
                                    if ($Check_inner_error == 'false') {
                                        $client_awb = $main_result['ID'];
                                        $awb_label = $main_result['ShipmentLabel']['LabelURL'];



                                        $CURRENT_DATE = date("Y-m-d H:i:s");
                                        $CURRENT_TIME = date("H:i:s");
                                        $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $client_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $awb_label, 'delivered' => 5, 'code' => 'DL');
                                        $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $awb_no);




                                        $details = 'Forwarded to ' . $ClientArr['company'];
                                        $statusArr = array(
                                            'slip_no' => $awb_no,
                                            'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                            'new_status' => 5,
                                            'pickup_time' => $CURRENT_TIME,
                                            'pickup_date' => $CURRENT_DATE,
                                            'Activites' => 'Forward',
                                            'Details' => $details,
                                            'entry_date' => $CURRENT_DATE,
                                            'user_id' => $this->session->userdata('user_details')['user_id'],
                                            'user_type' => 'user',
                                            'comment' => $comment,
                                            'code' => 'DL',
                                        );
                                        $this->Shipment_model->GetstatuInsert_forward($statusArr);
                                        send_message($awb_no);
                                        $returnArr['successAbw'][] = $awb_no;

                                        array_push($DataArray, $awb_no);
                                        // print_r($main_result);exit;
                                    }
                                }


                                // $Whole_Date=$awb_array['Shipments']['HasErrors'];
                                // echo "<pre>"; print_r($error_response);
                            } elseif ($ClientArr['company'] == 'MAKHDOOM') {
                                // exit("sadafasdf");
                                // $api_key_test="eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ0cmFja3Rlc3RAc2hpcG94LmNvbSIsInVzZXJJZCI6NjQwNTMxMDYzLCJleHAiOjE1ODA2MzQ3MDR9.32IuuDhsuV8Q108X3z_bIJY1kMaQHFMZx3Zq0tJCNN0Jpw-DNjsLXc6mo8NguUzXIGNDiFW0d4boIr2HCnJYow";
                                $api_key = "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJpbmZvQHRyYWNrLmNvbS5zYSIsInVzZXJJZCI6NjQwMDA1NDQ5LCJleHAiOjE1ODM1MDczNTJ9.h6mPdQPh8V0AQszk8ZTfesqOfqj5rTwHSw22b2mFm-2kSV6R2G33bbj_Tyz6h6mFt3JtazEZk0KC6TECbeVIZw";

                                $credentials_info = array(
                                    "username" => "info@track.com.sa",
                                    "password" => "Track1234",
                                    "remember_me" => true);
                                if ($pay_mode == 'CC') {
                                    $charge_type = 0;
                                    $cod_amount = 0;
                                    $payment_type = 'credit_balance';
                                } elseif ($pay_mode == 'COD') {
                                    $charge_type = 'cod';
                                    $payment_type = 'cash';
                                }


                                $sku_data = $this->Shipment_model->Getskudetails_forward($awb_no);
                                // print_r($sku_data);exit;
                                $sku_all_names = array();
                                $sku_total = 0;
                                foreach ($sku_data as $key => $val) {
                                    $skunames_quantity = $sku_data[$key]['sku'] . "*" . $sku_data[$key]['piece'];
                                    $sku_total = $sku_total + $sku_data[$key]['piece'];
                                    array_push($sku_all_names, $skunames_quantity);
                                }
                                $sku_all_names = implode(",", $sku_all_names);
                                if ($sku_total != 0) {
                                    $complete_sku = $sku_all_names . ",sku_total=" . $sku_total;
                                } else {
                                    $complete_sku = $sku_all_names;
                                }
                                $sender_city = getdestinationfieldshow($shipmentData[0]['origin'], 'makhdoom_city');
                                $receiver_city = getdestinationfieldshow($shipmentData[0]['destination'], 'makhdoom_city');


                                $main_request_array = array(
                                    'sender_data' =>
                                    array(
                                        'address_type' => 'residential',
                                        'name' => $sender_name,
                                        'email' => $sender_email,
                                        'apartment' => '',
                                        'building' => '',
                                        'street' => $s_address,
                                        'city' =>
                                        array(
                                            'code' => $sender_city,
                                        ),
                                        'country' =>
                                        array(
                                            'id' => 191,
                                        ),
                                        'phone' => $s_phone,
                                    ),
                                    'recipient_data' =>
                                    array(
                                        'address_type' => 'residential',
                                        'name' => $receiver_name,
                                        'apartment' => '',
                                        'building' => '',
                                        'street' => $receiver_address,
                                        'city' =>
                                        array(
                                            'code' => $receiver_city,
                                        ),
                                        'country' =>
                                        array(
                                            'id' => 191,
                                        ),
                                        'phone' => $receiver_phone,
                                        'landmark' => '',
                                    ),
                                    'dimensions' =>
                                    array(
                                        'weight' => $weight,
                                        'width' => 0,
                                        'length' => 0,
                                        'height' => 0,
                                        'unit' => '',
                                        'domestic' => TRUE,
                                    ),
                                    'package_type' =>
                                    array(
                                        'courier_type' => 'EXPRESS_DELIVERY',
                                    ),
                                    'charge_items' =>
                                    array(
                                        0 =>
                                        array(
                                            'paid' => false,
                                            'charge' => $cod_amount,
                                            'charge_type' => $charge_type,
                                            'payer' => 'sender'
                                        ),
                                    ),
                                    'recipient_not_available' => 'do_not_deliver',
                                    'payment_type' => $payment_type,
                                    'payer' => 'sender',
                                    'parcel_value' => $cod_amount,
                                    'fragile' => FALSE,
                                    'note' => $complete_sku,
                                    'piece_count' => $pieces,
                                    'force_create' => true
                                );

                                $data = json_encode($main_request_array);
                                // echo "<pre>";print_r($data);echo "<br/>";	exit;
                                $ch = curl_init();
                                $headers = array(
                                    'Accept: application/json',
                                    'Content-Type: application/json',
                                    'Authorization: Bearer ' . "$api_key"
                                );

                                $live_url = "https://prodapi.shipox.com/api/v2/customer/order";
                                // $test_url="https://ksa.my.shipox.com/api/v2/customer/order";;
                                curl_setopt($ch, CURLOPT_URL, $live_url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                                curl_setopt($ch, CURLOPT_POST, TRUE);
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                $response = curl_exec($ch);
                                curl_close($ch);

                                $responseArray = json_decode($response, true);
                                // echo "<pre>";print_r($responseArray);echo "</pre>"; exit;
                                $check_status = $responseArray['status'];
                                if ($check_status == 'success') {
                                    array_push($DataArray, $awb_no);
                                    $client_awb = $responseArray['data']['order_number'];
                                    $client_id = $responseArray['data']['id'];


                                    $ch = curl_init();

                                    // $url="https://prodapi.shipox.com/api/v1/service_types";
                                    $url = "https://prodapi.shipox.com/api/v1/customer/orders/airwaybill_mini?order_numbers=$client_awb";
                                    // echo $url;
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                        "Content-Type: application/json",
                                        "Accept: application/json",
                                        'Authorization: Bearer ' . "$api_key"
                                    ));

                                    $response_label = curl_exec($ch);
                                    curl_close($ch);


                                    $decoded_response = json_decode($response_label, true);
                                    $check_status_label = $decoded_response['status'];
                                    if ($check_status_label == 'success') {
                                        $label_url = $decoded_response['data']['value'];
                                        //file_put_contents("makhdoom_label/$awb_no.pdf",file_get_contents($label_url));
                                    }
                                    $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $CURRENT_TIME = date("H:i:s");
                                    $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $this->input->post('client_name'), 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $label_url, 'delivered' => 5, 'code' => 'DL');
                                    $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $awb_no);


                                    $details = 'Forwarded to ' . $ClientArr['company'];
                                    $statusArr = array(
                                        'slip_no' => $awb_no,
                                        'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                        'new_status' => 5,
                                        'pickup_time' => $CURRENT_TIME,
                                        'pickup_date' => $CURRENT_DATE,
                                        'Activites' => 'Forward',
                                        'Details' => $details,
                                        'entry_date' => $CURRENT_DATE,
                                        'user_id' => $this->session->userdata('user_details')['user_id'],
                                        'user_type' => 'user',
                                        'comment' => $comment,
                                        'code' => 'DL',
                                    );
                                    $this->Shipment_model->GetstatuInsert_forward($statusArr);
                                    send_message($awb_no);
                                    $returnArr['successAbw'][] = $awb_no;
                                } else {
                                    $error_response = $responseArray['message'];
                                    array_push($error_array, $awb_no . ":" . $error_response);
                                    $returnArr['responseError'][] = $awb_no . ":" . $error_response;
                                }
                            } elseif ($ClientArr['company'] == 'SLS') {
                                $sku_data = $this->Shipment_model->Getskudetails_forward($awb_no);
                                // print_r($sku_data);exit;
                                if (!empty($sku_data)) {
                                    $sku_all_names = array();

                                    $sku_total = 0;
                                    foreach ($sku_data as $key => $val) {
                                        $skunames_quantity = $sku_data[$key]['sku'] . "*" . $sku_data[$key]['piece'];
                                        array_push($sku_all_names, $skunames_quantity);
                                        $skunames_quantity = "";
                                        $sku_total = $sku_total + $sku_data[$key]['piece'];
                                    }
                                    $sku_all_names = implode(",", $sku_all_names);
                                    $sku_name = $sku_all_names;
                                    if (empty($sku_total)) {
                                        $sku_total = $pieces;
                                    }
                                    if (empty($sku_all_names)) {
                                        $sku_name = $item_description;
                                    }
                                } else {
                                    $sku_name = $item_description;
                                }
                                $receiver_address = stripslashes($receiver_address);
                                // echo $shipmentData[0]['destination'];
                                $receiver_city = getdestinationfieldshow($shipmentData[0]['destination'], 'sls_city');

                                // print_r($receiver_city);exit;

                                $receiver_address = str_replace('/', '', $receiver_address);

                                // $receiver_address = $functions->seo_friendly_url($receiver_address);
                                // print_r($receiver_address);exit;
                                $CURRENT_DATE_ONLY = date("Y-m-d");
                                $CURRENT_TIME_ONLY = date("H:i:s");
                                $CURRENT_DATE_TIME = date("Y-m-d H:i:s");
                                // $esnad_awb_number=$functions->Get_esnad_awb();



                                $default_currency = site_configTable("default_currency");
                                if ($receiver_city == 'Riyadh') {

                                    if ($pay_mode == 'CC') {
                                        $price_set = "Inside Riyadh - 25 " . $default_currency . " - 10 KG 2 " . $default_currency . "";
                                    } elseif ($pay_mode == 'COD') {
                                        $price_set = "Inside Riyadh - 25 " . $default_currency . " - 10 KG 2 " . $default_currency . " - COD 5 " . $default_currency . "";
                                    }
                                } else {
                                    if ($pay_mode == 'CC') {
                                        $price_set = "Outside Riyadh - 30 " . $default_currency . " - 10 KG 2 " . $default_currency . "";
                                    } elseif ($pay_mode == 'COD') {
                                        $price_set = "Outside Riyadh - 30 " . $default_currency . " - 10 KG 2 " . $default_currency . " - COD 5 " . $default_currency . " ";
                                    }
                                }
                                $param = array(
                                    "account_number" => "S-TRACK",
                                    "requested_by" => "TRACK",
                                    "collection_name" => "TRACK",
                                    "collection_contact" => $sender_name,
                                    "collection_street1" => $s_address,
                                    "collection_phone" => $s_phone,
                                    "collection_email" => $sender_email,
                                    "collection_city" => $sender_city,
                                    "collection_country" => "Saudi Arabia",
                                    "api_token" => "30ab3de96f1e4fe6b32c240a5651fb92",
                                    "price_set_name" => $price_set,
                                    "description" => $sku_name,
                                    "quantity" => $pieces,
                                    "weight" => $weight,
                                    "delivery_name" => $receiver_name,
                                    "delivery_street1" => $receiver_address,
                                    "delivery_contact" => $receiver_name,
                                    "delivery_city" => $receiver_city,
                                    "delivery_postal_code" => "none",
                                    "delivery_country" => "Saudi Arabia",
                                    "delivery_phone" => $receiver_phone,
                                    "delivery_email" => "receiver_email", //return address
                                    "cod_amount" => $cod_amount,
                                    "declared_value" => $declared_charge,
                                    "order_id" => $awb_no
                                );


                                $dataJson = json_encode($param);
                                // print_r($dataJson);exit;
                                $headers = array(
                                    "Content-Type: application/json"
                                );
                                // $headers = array(
                                // 	"Content-Type: application/json",
                                // 	"token: ubreem123"
                                // 	);

                                $url = "http://www.sls-express.com/api/custom/v1/order/create";
                                // $url="http://testapi.esnad.com.cn/orderInfo/uploadOrderInfo";
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);

                                $response = curl_exec($ch);

                                curl_close($ch);
                                $responseArray = json_decode($response, true);
                                $status = $responseArray['status'];
                                if ($status == '1') {
                                    $response_message = $responseArray['message'];
                                    $client_awb = $responseArray['tracking_number'];
                                    if ($status == '1') {
                                        $CURRENT_DATE = date("Y-m-d H:i:s");
                                        $CURRENT_TIME = date("H:i:s");
                                        $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $this->input->post('client_name'), 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $label_url, 'delivered' => 5, 'code' => 'DL');
                                        $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $awb_no);
                                        $details = 'Forwarded to ' . $ClientArr['company'];
                                        $statusArr = array(
                                            'slip_no' => $awb_no,
                                            'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                            'new_status' => 5,
                                            'pickup_time' => $CURRENT_TIME,
                                            'pickup_date' => $CURRENT_DATE,
                                            'Activites' => 'Forward',
                                            'Details' => $details,
                                            'entry_date' => $CURRENT_DATE,
                                            'user_id' => $this->session->userdata('user_details')['user_id'],
                                            'user_type' => 'user',
                                            'comment' => $comment,
                                            'code' => 'DL',
                                        );
                                        $this->Shipment_model->GetstatuInsert_forward($statusArr);
                                        send_message($awb_no);
                                        $returnArr['successAbw'][] = $awb_no;
                                    }
                                } else {
                                    array_push($error_array, $awb_no . ':' . $response);
                                    $returnArr['responseError'][] = $awb_no . ":" . $response;
                                }
                            } elseif ($ClientArr['company'] == 'UBREEM') {

                                //beone ke username or password
                                $password = 'track@*11@@';     //provided by company  :  (column name: password || date 
                                $sender_email = 'track@gmail.com';
                                $url = 'https://ubreem.fastcoo-solutions.com/shipmentBookingApi_ajoul.php';

                                $Receiver_name = $shipmentData[0]['reciever_name'];
                                $Receiver_email = $shipmentData[0]['reciever_email'];
                                $Receiver_phone = $shipmentData[0]['reciever_phone'];
                                $Receiver_address = $shipmentData[0]['reciever_address'];
                                if (empty($Receiver_address))
                                    $Receiver_address = 'N/A';
                                $Reciever_city = getdestinationfieldshow($shipmentData[0]['destination'], 'city');

                                $product_type = 'Parcel'; //beone ka database
                                $service = '2'; // beone wali
                                $description = $shipmentData[0]['status_describtion'];
                                if (empty($description))
                                    $description = 'N/A';
                                // this is prodect name (column name: status_describtion 

                                $ajoul_booking_id = $shipmentData[0]['booking_id'];
                                $s_name = $shipmentData[0]['sender_name'];
                                $s_address = $shipmentData[0]['sender_address'];
                                $s_zip = $shipmentData[0]['sender_zip'];
                                $s_phone = $shipmentData[0]['sender_phone'];
                                $s_city = getdestinationfieldshow($shipmentData[0]['origin'], 'city');

                                $pay_mode = $shipmentData[0]['mode']; //paymode either CASH or COD:(column name: mode || date 
                                $codValue = $shipmentData[0]['total_cod_amt'];               //COD charges.  :  (column name: 	total_cod_amt || date type: 
                                $product_price = $shipmentData[0]['declared_charge'];   //(column name: declared_charge || date type: int || value: 11)
                                $booking_id = $shipmentData[0]['slip_no']; // send awb number ajoul
                                $shipper_refer_number = $shipmentData[0]['booking_id']; // ajoul ki booking id 
                                $weight = $shipmentData[0]['weight'];
                                if ($weight == 0)
                                    $weight = 1;
                                //weight should be in kg.:(column name: weight || date type
                                $NumberOfParcel = $shipmentData[0]['pieces']; //(column name: pieces || date type: int || value: 5)

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_POST, 1);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

                                $response = curl_exec($ch);

                                curl_close($ch);
                                $responseArray = json_decode($response, true);
                                if (!empty($responseArray['awb'])) {
                                    $label_url = $responseArray['awb_print_url'];
                                    $CURRENT_DATE = date("Y-m-d H:i:s");
                                    $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $this->input->post('client_name'), 'frwd_company_awb' => trim($responseArray['awb']), 'frwd_company_label' => $label_url, 'delivered' => 5, 'code' => 'DL');
                                    $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $booking_id);



                                    $CURRENT_TIME = date("H:i:s");
                                    $details = 'Forwarded to ' . $ClientArr['company'];
                                    $statusArr = array(
                                        'slip_no' => $booking_id,
                                        'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                        'new_status' => 5,
                                        'pickup_time' => $CURRENT_TIME,
                                        'pickup_date' => $CURRENT_DATE,
                                        'Activites' => 'Forward',
                                        'Details' => $details,
                                        'entry_date' => $CURRENT_DATE,
                                        'user_id' => $this->session->userdata('user_details')['user_id'],
                                        'user_type' => 'user',
                                        'comment' => $comment,
                                        'code' => 'DL',
                                    );
                                    $this->Shipment_model->GetstatuInsert_forward($statusArr);
                                    send_message($booking_id);
                                    $returnArr['successAbw'][] = $booking_id;

                                    $this->session->set_flashdata('msg', 'Successfully updated!');
                                } else {
                                    array_push($error_array, $booking_id . ':' . $response);
                                    $returnArr['responseError'][] = $booking_id . ":" . $response;
                                }
                            } elseif ($ClientArr['company'] == 'SAMSA') {
                                // exit("sdfghjk");
                                //$url='http://track.smsaexpress.com/SECOM/SMSAwebService.asmx';

                                $Receiver_name = $shipmentData[0]['reciever_name'];
                                $Receiver_email = $shipmentData[0]['reciever_email'];
                                $Receiver_phone = $shipmentData[0]['reciever_phone'];
                                $Receiver_address = $shipmentData[0]['reciever_address'];
                                $Reciever_city = getdestinationfieldshow($shipmentData[0]['destination'], 'samsa_city');

                                $product_type = 'Parcel'; //beone ka database
                                $service = '2'; // beone wali
                                $description = !empty($shipmentData[0]['status_describtion']) ? $shipmentData[0]['status_describtion'] : $comment; // this is prodect name (column name: status_describtion 

                                $smsa_booking_id = $shipmentData[0]['booking_id'];
                                $s_name = $shipmentData[0]['sender_name'];
                                $s_address = $shipmentData[0]['sender_address'];
                                $s_zip = $shipmentData[0]['sender_zip'];
                                $s_phone = $shipmentData[0]['sender_phone'];
                                if (empty($s_phone)) {

                                    $s_phone = GetallCutomerBysellerId($shipmentData[0]['cust_id'], 'phone');
                                }
                                $s_city = getdestinationfieldshow($shipmentData[0]['origin'], 'samsa_city');



                                $pay_mode = $shipmentData[0]['mode']; //paymode either CASH or COD:(column name: mode || date 
                                $codValue = $shipmentData[0]['total_cod_amt'];               //COD charges.  :  (column name: 	total_cod_amt || date type: 
                                $product_price = $shipmentData[0]['declared_charge'];   //(column name: declared_charge || date type: int || value: 11)
                                $booking_id = $shipmentData[0]['slip_no']; // send awb number ajoul
                                $shipper_refer_number = $shipmentData[0]['booking_id']; // ajoul ki booking id 
                                $weight = $shipmentData[0]['weight'];
                                if ($weight == 0)
                                    $weight = 1;
                                $NumberOfParcel = $shipmentData[0]['pieces']; //(column name: pieces || date type: int || value: 5)

                                $refrence = rand(10000, 99999);

                                $xml = '
                                    	<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                                      <soap:Body>
                                        <addShip xmlns="http://track.smsaexpress.com/secom/">
                                          <passKey>TkE@2233</passKey>
                                          <refNo>' . $booking_id . '</refNo>
                                          <sentDate>' . date('d/m/Y') . '</sentDate>
                                          <idNo>' . $smsa_booking_id . '</idNo>
                                          <cName>' . $Receiver_name . '</cName>
                                          <cntry>KSA</cntry>
                                          <cCity>' . $Reciever_city . '</cCity>
                                          <cZip>' . $s_zip . '</cZip>
                                          <cPOBox>45</cPOBox>
                                          <cMobile>' . $Receiver_phone . '</cMobile>
                                          <cTel1>' . $Receiver_phone . '</cTel1>
                                          <cTel2>' . $Receiver_phone . '</cTel2>
                                          <cAddr1>' . htmlentities(strip_tags($Receiver_address)) . '</cAddr1>
                                          <cAddr2>' . htmlentities(strip_tags($Receiver_address)) . '</cAddr2>
                                          <shipType>DLV</shipType>
                                          <PCs>' . $NumberOfParcel . '</PCs>
                                          <cEmail>' . $Receiver_email . '</cEmail>
                                          <carrValue>2</carrValue>
                                          <carrCurr>2</carrCurr>
                                          <codAmt>' . $codValue . '</codAmt>
                                          <weight>' . $weight . '</weight>
                                          <custVal>2</custVal>
                                          <custCurr>3</custCurr>
                                          <insrAmt>34</insrAmt>
                                          <insrCurr>3</insrCurr>
                                          <itemDesc>' . htmlentities(strip_tags($description)) . '</itemDesc>
                                          <sName>' . htmlentities(strip_tags($s_name)) . '</sName>
                                          <sContact>' . $s_phone . '</sContact>
                                          <sAddr1>' . htmlentities(strip_tags($s_address)) . '</sAddr1>
                                          <sAddr2>' . htmlentities(strip_tags($s_address)) . '</sAddr2>
                                          <sCity>' . $s_city . '</sCity>
                                          <sPhone>' . $s_phone . '</sPhone>
                                          <sCntry>KSA</sCntry>
                                          <prefDelvDate>20/02/2019</prefDelvDate>
                                          <gpsPoints>2</gpsPoints>
                                        </addShip>
                                    	 <getPDF xmlns="http://track.smsaexpress.com/secom/">
                                          <awbNo>' . $pdfawb . '</awbNo>
                                          <passKey>TkE@2233</passKey>
                                        </getPDF>
                                      </soap:Body>
                                    </soap:Envelope>
                                    	';
                                // echo $xml; exit();					


                                $url = "http://track.smsaexpress.com/SECOM/SMSAwebService.asmx";

                                $headers = array(
                                    "Content-type: text/xml;charset=utf-8",
                                    "Accept: application/xml",
                                    "Cache-Control: no-cache",
                                    "Pragma: no-cache",
                                    "SOAPAction: http://track.smsaexpress.com/secom/addShip",
                                    "Content-length: " . strlen($xml),
                                );
                                $cookiePath = tempnam('/tmp', 'cookie');

                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


                                $response = curl_exec($ch);
                                //$smsaawb=$response;
                                $check = $response;

                                $respon = trim($check);
                                $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>")
                                        , "", $response);
                                // echo "<pre>";print_r($respon);echo "</pre>";exit;
                                if ($respon != 'Bad Request') {
                                    $xml2 = new SimpleXMLElement($respon);
                                    echo "<pre>";

                                    $again = $xml2;


                                    $a = array("qwb" => $again);

                                    $complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
                                    $abc = array("qwber" => $complicated);

                                    $db = (implode(" ", $abc));

                                    $newRes = explode('#', $db);

                                    curl_close($ch);
                                    if (!empty($newRes[1])) {
                                        $db = trim($newRes[1]);
                                    }

                                    $xml = '<?xml version="1.0" encoding="utf-8"?>
                                        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                                          <soap:Body>
                                            <getPDF xmlns="http://track.smsaexpress.com/secom/">
                                              <awbNo>' . $db . '</awbNo>
                                              <passKey>TkE@2233</passKey>
                                            </getPDF>
                                          </soap:Body>
                                        </soap:Envelope>';
                                    $headers = array(
                                        "Content-type: text/xml;charset=utf-8",
                                        "Accept: application/xml",
                                        "Cache-Control: no-cache",
                                        "Pragma: no-cache",
                                        "SOAPAction: http://track.smsaexpress.com/secom/getPDF",
                                        "Content-length: " . strlen($xml),
                                    );


                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                                    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                                    $response = trim(curl_exec($ch));
                                    $xml_data = new SimpleXMLElement(str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-16\"?>")
                                                    , "", $response));
                                    $mediaData = $xml_data->Body->getPDFResponse->getPDFResult[0];
                                    header('Content-Type: application/pdf');
                                    $img = base64_decode($mediaData);

                                    if (!empty($mediaData)) {
                                        $savefolder = $img;

                                        $CURRENT_DATE = date("Y-m-d H:i:s");
                                        $CURRENT_TIME = date("H:i:s");
                                        $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $this->input->post('client_name'), 'frwd_company_awb' => $db, 'frwd_company_label' => $savefolder, 'delivered' => 5, 'code' => 'DL');
                                        $this->Shipment_model->GetshipmentUpdate_forward($updateArr, $booking_id);


                                        $details = 'Forwarded to ' . $ClientArr['company'];
                                        $statusArr = array(
                                            'slip_no' => $booking_id,
                                            'new_location' => $this->session->userdata('user_details')['adminbranchlocation'],
                                            'new_status' => 5,
                                            'pickup_time' => $CURRENT_TIME,
                                            'pickup_date' => $CURRENT_DATE,
                                            'Activites' => 'Forward',
                                            'Details' => $details,
                                            'entry_date' => $CURRENT_DATE,
                                            'user_id' => $this->session->userdata('user_details')['user_id'],
                                            'user_type' => 'user',
                                            'comment' => $comment,
                                            'code' => 'DL',
                                        );
                                        $this->Shipment_model->GetstatuInsert_forward($statusArr);
                                        send_message($booking_id);
                                        $returnArr['successAbw'][] = $booking_id;
                                    } else {
                                        array_push($error_array, $booking_id . ':' . $db);
                                        $returnArr['responseError'][] = $booking_id . ":" . $db;
                                    }
                                } else {
                                    array_push($error_array, $booking_id . ':' . $respon);
                                    $returnArr['responseError'][] = $booking_id . ":" . $respon;
                                    $newRes = explode('#', $respon);
                                    print_r($newRes);
                                    exit;
                                }
                            }
                        } else {
                            $returnArr['invalid'][] = $slipData[$key1];
                        }
                    }
                } else {
                    $this->session->set_flashdata('errmess', 'please select Clinet Name');
                }
            } else {
                $this->session->set_flashdata('errmess', 'please enter AWB No');
            }
        } else {
            $this->session->set_flashdata('errmess', 'please enter AWB No');
        }
        $this->session->set_flashdata('errorloop', $returnArr);
        redirect(base_url('Forward_Delivery_Station'));
    }

    public function validateUpdate() {

        $_POST = json_decode(file_get_contents('php://input'), true);

        //echo json_encode($_POST); exit;
        $status = $_POST['status'];
        $shipments = $this->Shipment_model->shipmetsInAwb($_POST['awbArray']);
        $valid = array();
        $invalid = array();
        $error_check = array();
        //print_r($shipments['result']['code']);exit;
        // print_r($shipments['result']);
        if(!empty($shipments['result']))
        {
        foreach ($shipments['result'] as $data) {
            array_push($error_check, $data['slip_no']);
            if ($status == 2) {
                if (trim($data['code']) == 'OC' && $data['on_hold']=='No') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
            if ($status == 3) {
                if (trim($data['code']) == 'PG' && $data['on_hold']=='No') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }

            if ($status == 4) {
                if (trim($data['code']) == 'AP' || trim($data['code']) == 'ROP' && $data['on_hold']=='No') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
            if ($status == 5) {
                if (trim($data['code']) == 'AP' && $data['on_hold']=='No') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
            if ($status == 8) {
                  if ($data['code'] == 'DL' || $data['code'] == 'RPOD' || $data['code'] == 'D3PL'  || $data['code'] == 'ROP' || $data['code'] == 'OFD' || $data['code'] == 'DOP' || $data['code'] == 'FD' || $data['code'] == 'PC' || $data['delivered'] == '16' || $data['code'] == 'IT' || $data['code'] == 'DLM' || $data['code'] == 'OP' || $data['code'] == 'RI' || $data['code'] == 'OH' || $data['code'] == 'OU' || $data['code'] == 'UAR' || $data['code'] == 'RSD' || $data['code'] == 'UK' || $data['code'] == 'CANC' || $data['code'] == 'PDD' || $data['code'] == 'RPC' || $data['code'] == 'ROG' || $data['code'] == 'ROFD' || $data['code'] == 'RPOD' || $data['code'] == 'RIT' || $data['code'] == 'RPUC') {

                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
        }
          
        }
        else
        {
            
            foreach ($_POST['awbArray'] as $e_val) {
                array_push($invalid, array('slip_no' => $e_val,'check'=>0));
                // array_push($invalid, $DataArray);
            }
            //array_push($invalid, $_POST['awbArray']);  
        }
        
        
        foreach ($_POST['awbArray'] as $e_val) {
            if (!in_array($e_val, $error_check)) {
                array_push($invalid, array('slip_no' => $e_val,'check'=>1));
            }
        }
        
        // echo json_encode($valid); die;

        /*    $invalid_new=array();
          foreach($_POST['awbArray'] as $val)
          {
          if(!in_array($val,$valid))
          {
          array_push($invalid_new,$val);
          }

          } */
        $returnData['valid'] = $valid;
        $returnData['invalid'] = $invalid;
        echo json_encode($returnData);
    }

    public function filterdetail() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $this->Shipment_model->Getskudetails_ship($_POST['id']);
        echo json_encode($dataArray);
    }

    public function fwdupload() {
        $this->load->view('ItemM/fwd_upload', $data);
    }

    public function GetStockLocation() {
        // error_reporting(-1);
        // ini_set('display_errors', 1);
        $_POST = json_decode(file_get_contents('php://input'), true);
        //echo "<br><pre>"; print_r($_POST); die;     
        $dataArray = $this->Shipment_model->stocklocation_details($_POST['id']);
        //echo "<br><pre>"; print_r($dataArray);// die;
        if (!empty($dataArray)) {
            //echo " Sku no  = ".$dataArray[0]['sku'];
            foreach ($dataArray as $key => $stck) {

                $skuno = trim($stck['sku']);
                $shelve_no = $stck['deducted_shelve'];
                $seller_id = $stck['cust_id'];
                $slip_no = $stck['slip_no'];
                $piece = $stck['piece'];
                $piece_new = $piece;
                $sku_size = $this->Shipment_model->skuSize($skuno);
                $skuId = $this->Shipment_model->skuId($skuno);

                 $dataArray[$key]['sku_size'] = $sku_size;
               
                if ($sku_size >= $piece)
                    $dataArray[$key]['stock_need'] = 1;
                else
                {
                     $locationLimit1 = $piece / $sku_size;
                     $dataArray[$key]['stock_need'] = ceil($locationLimit1);
                }
                
            
                $preId = array();
               
                for ($i = 0; $i < $dataArray[$key]['stock_need']; $i++) {



                    if ($piece_new > $sku_size) {
                        $sendPiece = $sku_size;
                        $piece_new = $piece_new - $sku_size;
                    } else {
                        $sendPiece = $piece_new;
                        $piece_new = 0;
                    }

                   // echo $skuno."sno".$shelve_no."id".$seller_id."sd".$sendPiece."<br>";

                    $locArray = $this->Shipment_model->stockInventory($skuno, $shelve_no, $seller_id, $sendPiece, $preId);
                    //echo "<pre>";print_r($locArray); 
                    $dataArray[$key]['local'][][] = array('qty' => $sendPiece, 'location' => $locArray[0]['id'], 'stock_location' => $locArray[0]['stock_location'], 'stock_location_old' => $locArray[0]['stock_location'], 'shelve_no' => $locArray[0]['shelve_no'], 'super_id' => $this->session->userdata('user_details')['super_id'], 'seller_id' => $seller_id, 'item_sku' => $skuId, 'sku_size' => $sku_size);

                    $preId[] = $locArray[0]['id'];
                    

                    // $datastock[]= $locArray;  
                }

                //$dataArray[$key]['local_test'] = $datastock;   
            }
            echo json_encode($dataArray);
            // echo "<br><pre>"; print_r($datastock); die; 
            //$ers = $datastock['ers'];
        }
    }

    public function save_details() {
        $postData = json_decode(file_get_contents('php://input'), true);
        //echo '<pre>';
        //print_r($postData);
        // exit;
        $stock_location = $postData['id'];
        $slip_no = $postData[0]['slip_no'];
        $check_slipNo = $this->Shipment_model->getallshipmentdatashow($slip_no);
        $custmoerID = $check_slipNo['cust_id'];
        $uniqueid = GetallCutomerBysellerId($check_slipNo['cust_id'], 'uniqueid');
        $token = GetallCutomerBysellerId($custmoerID, 'manager_token');
        $zid_store_id = GetallCutomerBysellerId($custmoerID, 'zid_sid');
        $salatoken = GetallCutomerBysellerId($custmoerID, 'salla_athentication');
        if ($check_slipNo['code'] != 'OG') {
            foreach ($postData as $SaveStock) {
                $qty_n=$SaveStock['piece'];
                foreach ($SaveStock['local'] as $fzArray) {
                    foreach ($fzArray as $fArray) {
                        if (empty($fArray['shelve_no']))
                            $fArray['shelve_no'] = "";
                        $wh_id = getalldataitemtablesSKU($SaveStock['sku'], 'wh_id');
                        if (!empty($fArray['location'])) {
                            $updateLoc[] = array(
                                'id' => $fArray['location'],
                                'quantity' => $fArray['qty'],
                                'stock_location' => $fArray['stock_location'],
                                'stock_location_old' => $fArray['stock_location_old'],
                                'shelve_no' => $fArray['shelve_no'],
                                'slip_no' => $slip_no,
                                'seller_id' => $check_slipNo['cust_id'],
                                'item_sku' => $fArray['item_sku'],
                                'shelve_no' => $fArray['shelve_no'],
                                'wh_id' => $wh_id
                            );
                        } else {
                            $addLoc[] = array(
                                'quantity' => $fArray['qty'],
                                'stock_location' => $fArray['stock_location'],
                                'shelve_no' => $fArray['shelve_no'],
                                'super_id' => $fArray['super_id'],
                                'seller_id' => $fArray['seller_id'],
                                'item_sku' => $fArray['item_sku'],
                                'wh_id' => $wh_id
                            );

                            $activitiesArr[] = array(
                                'exp_date' => $rdata['expity_date'],
                                'st_location' => $fArray['stock_location'],
                                'item_sku' => $fArray['item_sku'],
                                'user_id' => $this->session->userdata('user_details')['user_id'],
                                'seller_id' => $fArray['seller_id'],
                                'qty' => $fArray['qty'],
                                'p_qty' => 0,
                                'qty_used' => $fArray['qty'],
                                'type' => 'return',
                                'entrydate' => date("Y-m-d h:i:s"),
                                'awb_no' => $slip_no,
                                'super_id' => $this->session->userdata('user_details')['super_id'],
                                'shelve_no' => $fArray['shelve_no'],
                                'comment' => 'Open Order');
                        }
                    }

                    if (!empty($token)) {
                        $zidReqArr = GetAllQtyforSellerby_ID($fArray['item_sku'], $custmoerID);
                        
                        $quantity = $zidReqArr['quantity'] + $fArray['qty'];
                        $pid = $zidReqArr['zid_pid'];
                        $token = $token;
                        $storeID = $zid_store_id;
                        update_zid_product($quantity, $pid, $token, $storeID,$custmoerID,$zidReqArr['sku']);
                    }

                    //==========update salla quantity===============//

                    if($custmoerID=='214' || $custmoerID=='31')
                        {
                        $this->load->model('Manifest_model');
                             $this->Manifest_model->getupdateTempstock($qty_n,$custmoerID,$SaveStock['sku']);  
                           $temp_stock=$this->Manifest_model->GetTempStockData($SaveStock['sku'],$custmoerID);
                           //$sall_update_qty=$temp_stock['qty']+$skuqtyval['totalqty'];
                           salla_provider_qty_update($temp_stock['qty'], $uniqueid, $SaveStock['sku'],$custmoerID,$slip_no,$qty_n);
                         }
                    else if (!empty($salatoken)) {
                      
                        $sallaReqArr = GetAllQtyforSellerby_ID($fArray['item_sku'], $custmoerID);
                        $quantity = $sallaReqArr['quantity'] + $fArray['qty']; //+$fArray['qty'];
                        $pid = $sallaReqArr['sku'];
                        $sallatoken = $salatoken;
                        // echo "<pre>"; print_r($sallaReqArr);
                        $reszid = update_salla_qty_product($quantity, $pid, $sallatoken, $custmoerID);
                          
                    }
                }
            }


            $statusvalue[0]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[0]['user_type'] = 'fulfillment';
            $statusvalue[0]['slip_no'] = $postData[0]['slip_no'];
            $statusvalue[0]['new_status'] = 11;
            $statusvalue[0]['code'] = 'OG';
            $statusvalue[0]['Activites'] = 'Order Generated';
            $statusvalue[0]['Details'] = 'Order Opened By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvalue[0]['entry_date'] = date('Y-m-d H:i:s');
            $statusvalue[0]['super_id'] = $this->session->userdata('user_details')['super_id'];
            //=================remove stock location ===================//
            $removeStockLocation[0]['slip_no'] = $postData[0]['slip_no'];
            $removeStockLocation[0]['deleted'] = 'Y';
            //==========================================================//


            $shipData = array();
            $updateArray = array('code' => 'OG', 'delivered' => 11);
            $shipData['where_in'] = $postData[0]['slip_no'];
            $shipData['update'] = $updateArray;

            if ($this->Status_model->insertStatus($statusvalue)) {
                $this->Shipment_model->updateStatus($shipData);
                //echo json_encode($shipData) ;
                $this->Shipment_model->stockdeletepicklistFM($slip_no);
            }
            if (!empty($updateLoc)) {
                $this->Shipment_model->stockSaveShipmentFM($updateLoc, 'OPO');
            }

            if (!empty($addLoc)) {
                $this->Shipment_model->addInventory($addLoc, $activitiesArr);
            }
            if (!empty($removeStockLocation)) {
                $this->Shipment_model->RemoveStockLocation($removeStockLocation);
            }


            $error_status = array('status' => true);
            echo json_encode($error_status);
        }//endif 
        else {

            $error_status = array('status' => false);
            echo json_encode($error_status);
        }
    }

    public function updateData() {
        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataArray = $_POST;
        ignore_user_abort();
         if (!file_exists('picklock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'])) {
            mkdir('picklock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'], 0777, true);
        }
        $file = fopen('picklock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'] . '/' . $this->session->userdata('user_details')['super_id'] . '.lock', "w+");

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            // echo "Unable to obtain lock, the previous process is still going on."; 
            $stockarray = array('status' => 205, "Unable to obtain lock, the previous process is still going on.");
        } else {

        if ($dataArray['status'] == 4) {

            
            //die("stoped");


            $shippingArr = array();
            $slip_data = array();
            $file_name = date('Ymdhis') . '.xls';
            $key = 0;
            $errorArray = array();
            $Pickingcharge = array();
            foreach ($dataArray['awbArray'] as $data) {

                $shipment = $this->Shipment_model->shipmetsInAwb($data);
                $shipments = $shipment['result'];
                if (!empty($shipments)) {
                    array_push($shippingArr, array('slip_no' => $data));
                    array_push($slip_data, $data);
                    $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $statusvalue[$key]['user_type'] = 'fulfillment';
                    $statusvalue[$key]['slip_no'] = $data;
                    $statusvalue[$key]['new_status'] = $dataArray['status'];
                    $statusvalue[$key]['code'] = 'PK';
                    $statusvalue[$key]['Activites'] = 'Order Packed';
                    $statusvalue[$key]['Details'] = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                    $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                    $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                    /* -------------/Status Array----------- */
                    $picklistValue[$key]['slip_no'] = $data;
                    $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
                    $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
                    $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
                    $picklistValue[$key]['pickup_status'] = 'Y';
                    $picklistValue[$key]['packFile'] = $file_name;
                    $picklistValue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                    //==================packing and packging chages=================//    
                    $getallskuArray = $this->Pickup_model->GetallskuDataDetails($data);
                    $totalPieces = $getallskuArray['pieces'];
                    $seller_id = $getallskuArray['cust_id'];
                    $PackagingCharge = getalluserfinanceRates($seller_id, 12, 'rate');
                    $PinckingCharge = getalluserfinanceRates($seller_id, 13, 'rate');
                    $totalpackaging = $PackagingCharge * 1;
                    $totalpacking = $PinckingCharge * 1;
                    $Pickingcharge[$key]['seller_id'] = $seller_id;
                    $Pickingcharge[$key]['slip_no'] = $data;
                    $Pickingcharge[$key]['packaging_charge'] = $totalpackaging;
                    $Pickingcharge[$key]['picking_charge'] = $totalpacking;
                    $Pickingcharge[$key]['entrydate'] = date("Y-m-d H:i:sa");
                    $Pickingcharge[$key]['pieces'] = $totalPieces;
                    $Pickingcharge[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                    //===================================================//
                    //return array('seller_id'=>$seller_id,'slip_no'=>$slip_no,'packaging_charge'=>$totalpackaging,'picking_charge'=>$totalpacking,'entrydate'=>date("Y-m-d H:i:sa"));
                    // $Pickingcharge[$key]['']

                    $key++;
                } else {
                    array_push($errorArray, $data);
                }
            }



            $shipData = array();
            $updateArray = array('code' => 'PK', 'delivered' => $dataArray['status']);
            $shipData['where_in'] = $slip_data;
            $shipData['update'] = $updateArray;

            if ($this->Pickup_model->packOrder($picklistValue)) {
                // GetrequestShippongCompany($shippingArr);
                $this->Pickup_model->GetallDatapickingChargeAdded($Pickingcharge);
                //echo  print_r($this->Status_model->insertStatus($statusvalue)); exit;
                if ($this->Status_model->insertStatus($statusvalue)) {
                    //print_r($statusvalue);
                    $this->Shipment_model->updateStatus($shipData);
                    //echo json_encode($shipData) ;
                }
            }
        }
        if ($dataArray['status'] == 2) {
            $uid = strtoupper(uniqid());
            $picklistValue = array();
            $statusvalue = array();
            $errorArray = array();
            $slip_data = array();
            $key = 0;
            foreach ($dataArray['awbArray'] as $data1) {
                $shipment = $this->Shipment_model->shipmetsInAwb_picklist($data1);
                $data = $shipment['result'];
                //echo json_encode($data); exit;
                if (!empty($data)) {
                    if ($data['code'] == 'OC' && $data['on_hold']=='No') {


                        $skuData = $this->Shipment_model->GetpicklistGenrateSkuDetails($data['slip_no']);

                        
                        /* -------------Picklist Array----------- */
                        array_push($slip_data, $data['slip_no']);
                        $picklistValue[$key]['pickupId'] = $uid;
                        $picklistValue[$key]['slip_no'] = $data['slip_no'];
                        $picklistValue[$key]['destination'] = getdestinationfieldshow($data['destination'], 'city');
                        $picklistValue[$key]['origin'] = getdestinationfieldshow($data['origin'], 'city');
                        $picklistValue[$key]['reciever_name'] = $data['reciever_name'];
                        $picklistValue[$key]['reciever_address'] = $data['reciever_address'];
                        $picklistValue[$key]['reciever_phone'] = $data['reciever_phone'];
                        $picklistValue[$key]['sender_address'] = $data['sender_address'];
                        $picklistValue[$key]['sender_name'] = $data['sender_name'];
                        $picklistValue[$key]['total_cod_amt'] = $data['total_cod_amt'];
                        $picklistValue[$key]['sender_phone'] = $data['sender_phone'];
                        $picklistValue[$key]['mode'] = $data['mode'];
                        $picklistValue[$key]['weight'] = $data['weight'];
                        if (!empty($data['frwd_company_label'])) {
                            $picklistValue[$key]['print_url'] = $data['frwd_company_label'];
                        } else {
                            $picklistValue[$key]['print_url'] = base_url() . 'PrintPacking/' . $data['slip_no'];
                        }
                        $picklistValue[$key]['exp_details'] = "";
                        if (!empty($data['wh_id'])) {
                            $picklistValue[$key]['wh_id'] = $data['wh_id'];
                        } else {
                            $picklistValue[$key]['wh_id'] = 0;
                        }
                        


                        $picklistValue[$key]['sku'] = json_encode($skuData);
                        $picklistValue[$key]['piece'] = $data['pieces'];
                        $picklistValue[$key]['entrydate'] = date('Y-m-d H:i');
                        $picklistValue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                        /* -------------/Picklist Array----------- */
                        //`user_id`, `user_type`, `slip_no`, `new_location`, `city_code`, `new_status`, `code`, `pickup_time`, `pickup_date`, `Activites`, `Details`, `comment`, `entry_date`,
                        /* -------------Status Array----------- */
                        $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $statusvalue[$key]['user_type'] = 'fulfillment';
                        $statusvalue[$key]['slip_no'] = $data1;
                        $statusvalue[$key]['new_status'] = '2';
                        $statusvalue[$key]['code'] = 'PG';
                        $statusvalue[$key]['Activites'] = 'Pick List Generated';
                        $statusvalue[$key]['Details'] = 'Pick List Generated';
                        $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                        $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                        /* -------------/Status Array----------- */
                        $key++;
                    } else {
                        array_push($errorArray, $data1);
                    }
                } else {
                    array_push($errorArray, $data1);
                }
            }
            // echo json_encode($picklistValue) ; die;
            $shipData = array();
            if (!empty($slip_data)) {
                $updateArray = array('code' => 'PG', 'delivered' => 2);

                $shipData['where_in'] = $slip_data;
                $shipData['update'] = $updateArray;
            }
            //  print_r($shipData); die;
            // echo $this->Pickup_model->generatePicup($picklistValue); exit();
            if ($this->Pickup_model->generatePicup($picklistValue)) {
                if ($this->Status_model->insertStatus($statusvalue)) {
                    $this->Shipment_model->updateStatus($shipData);
                }
            }
        }
        if ($dataArray['status'] == 8) {
            
            $statusvalue = array();
            $errorArray = array();
            $slip_data = array();
            $key = 0;
            foreach ($dataArray['awbArray'] as $data1) {
                $shipment = $this->Shipment_model->shipmetsInAwb($data1);
                $data = $shipment['result'][0];
              
                //echo json_encode($data); exit;
                if (!empty($data)) {
                  if ($data['code'] == 'DL' || $data['code'] == 'RPOD' || $data['code'] == 'D3PL'  || $data['code'] == 'ROP' || $data['code'] == 'OFD' || $data['code'] == 'DOP' || $data['code'] == 'FD' || $data['code'] == 'PC' || $data['delivered'] == '16' || $data['code'] == 'IT' || $data['code'] == 'DLM' || $data['code'] == 'OP' || $data['code'] == 'RI' || $data['code'] == 'OH' || $data['code'] == 'OU' || $data['code'] == 'UAR' || $data['code'] == 'RSD' || $data['code'] == 'UK' || $data['code'] == 'CANC' || $data['code'] == 'PDD' || $data['code'] == 'RPC' || $data['code'] == 'ROG' || $data['code'] == 'ROFD' || $data['code'] == 'RPOD' || $data['code'] == 'RIT' || $data['code'] == 'RPUC') {
                      
                            array_push($slip_data, $data1);
                        
                        /* -------------Status Array----------- */
                        $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $statusvalue[$key]['user_type'] = 'fulfillment';
                        $statusvalue[$key]['slip_no'] = $data['slip_no'];
                        $statusvalue[$key]['new_status'] = '8';
                        $statusvalue[$key]['code'] = 'RTC';
                        $statusvalue[$key]['Activites'] = 'Return';
                        $statusvalue[$key]['Details'] = 'Order Return, Update By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                        $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                        $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];


                        $salatoken = GetallCutomerBysellerId($data['cust_id'], 'salla_athentication');
                        $salla_return_status = GetallCutomerBysellerId($data['cust_id'], 'salla_return_status');
                        if (($salla_return_status == null || $salla_return_status == '' ) && !empty($salatoken)) {
        
                            $salla_return_status = getSallaStatus($salatoken, $data['cust_id']);
                        }
                        if (!empty($salatoken)) {
                    
                            $sallaStatus = $salla_return_status;
                            
                            $note = "مسترجع";
                            $salla_new_update = array("status" => "returned");
                            update_status_salla($sallaStatus, $note, $salatoken, $data['shippers_ref_no'], $data['cust_id'], $data['slip_no'], '', '', $salla_new_update);
                        }

                        /* -------------/Status Array----------- */
                        $key++;
                    } else {
                        array_push($errorArray, $data1);
                    }
                } else {
                    array_push($errorArray, $data1);
                }
            }
            // echo json_encode($picklistValue) ; die;
            $shipData = array();
            if (!empty($slip_data)) {
                 $updateArray = array('code' => 'RTC', 'delivered' => 8,'close_date'=>date('Y-m-d H:i:s'));

                $shipData['where_in'] = $slip_data;
                $shipData['update'] = $updateArray;
            }
          
            
            
                if ($this->Status_model->insertStatus($statusvalue)) {
                    $this->Shipment_model->updateStatus($shipData);
                }
            
        }
        
        if ($dataArray['status'] == 40) {
            
            $statusvalue = array();
            $errorArray = array();
            $slip_data = array();
            $key = 0;
            foreach ($dataArray['awbArray'] as $data1) {
                $shipment = $this->Shipment_model->shipmetsInAwb($data1);
                $data = $shipment['result'][0];
              
                //echo json_encode($data); exit;
                if (!empty($data)) {
                  if ($data['code'] == 'DL' || $data['code'] == 'RPOD' || $data['code'] == 'D3PL'  || $data['code'] == 'ROP' || $data['code'] == 'OFD' || $data['code'] == 'DOP' || $data['code'] == 'FD' || $data['code'] == 'PC' || $data['delivered'] == '16' || $data['code'] == 'IT' || $data['code'] == 'DLM' || $data['code'] == 'OP' || $data['code'] == 'RI' || $data['code'] == 'OH' || $data['code'] == 'OU' || $data['code'] == 'UAR' || $data['code'] == 'RSD' || $data['code'] == 'UK' || $data['code'] == 'CANC' || $data['code'] == 'PDD' || $data['code'] == 'RPC' || $data['code'] == 'ROG' || $data['code'] == 'ROFD' || $data['code'] == 'RPOD' || $data['code'] == 'RIT' || $data['code'] == 'RPUC') {
                      
                            array_push($slip_data, $data1);
                        
                        /* -------------Status Array----------- */
                        $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $statusvalue[$key]['user_type'] = 'fulfillment';
                        $statusvalue[$key]['slip_no'] = $data['slip_no'];
                        $statusvalue[$key]['new_status'] = '40';
                        $statusvalue[$key]['code'] = 'LSD';
                        $statusvalue[$key]['Activites'] = 'Lost/Damage';
                        $statusvalue[$key]['Details'] = 'Lost/Damage, Update By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
                        $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                        $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                        /* -------------/Status Array----------- */
                        $key++;
                    } else {
                        array_push($errorArray, $data1);
                    }
                } else {
                    array_push($errorArray, $data1);
                }
            }
            // echo json_encode($picklistValue) ; die;
            $shipData = array();
            if (!empty($slip_data)) {
                 $updateArray = array('code' => 'LSD', 'delivered' =>40,'close_date'=>date('Y-m-d H:i:s'));

                $shipData['where_in'] = $slip_data;
                $shipData['update'] = $updateArray;
            }
          
            
            
                if ($this->Status_model->insertStatus($statusvalue)) {
                    $this->Shipment_model->updateStatus($shipData);
                }
            
        }

         flock($file, LOCK_UN);
    }
         fclose($file);
        $lastArray['error'] = $errorArray;
        $lastArray['success'] = $slip_data;
        echo json_encode($lastArray);
    }

    public function getshipmenttrackingresult() {
        
        //echo  "sssss"; die;
        // $status=$this->Shipment_model->allstatus();
        // 	$shipments = $this->Shipment_model->all();
//echo "sssssss"; die;
        $searchids = $this->input->post('tracking_numbers');

        if (!empty($searchids)) {
            $data['traking_awb_no'] = preg_split('/\s+/', trim($searchids));
            // print_r($data['traking_awb_no']);die;
            $top_search_type=$this->input->post('top_search_type');
        $_SESSION['top_search_type']=$top_search_type;
            $data['shipmentdata'] = $this->Shipment_model->getawbdataquery_tracking($data['traking_awb_no'],$top_search_type);
            //print_r($data['shipmentdata']); die;
        }
        $this->load->view('ShipmentM/trackingresult', $data);
    }

    public function bulk_forward_remove() {
        // "sssss"; die;
        // $status=$this->Shipment_model->allstatus();
        // 	$shipments = $this->Shipment_model->all();
//echo "sssssss"; die;
        $searchids = $this->input->post('tracking_numbers');


        $data['traking_awb_no'] = preg_split('/\s+/', trim($searchids));
        // print_r($data['traking_awb_no']); exit;
        if (!empty($data['traking_awb_no'])) {
            $shipmentdata = $this->Shipment_model->getawbdataquery($data['traking_awb_no']);
            $slipRArray = array();
            foreach ($shipmentdata as $shData) {
                $slipRArray[] = $shData['slip_no'];
                $StatusArray[$key]['user_type'] = 'fulfillment';
                $StatusArray[$key]['slip_no'] = $shData['slip_no'];
                $StatusArray[$key]['new_status'] = $shData['delivered'];
                $StatusArray[$key]['pickup_time'] = date("H:i:s");
                $StatusArray[$key]['pickup_date'] = date("Y-m-d H:i:s");
                $StatusArray[$key]['Details'] = "Removed by " . getUserNameById($this->session->userdata('user_details')['user_id']);
                $StatusArray[$key]['Activites'] = "Forwarding Removed";
                $StatusArray[$key]['entry_date'] = date("Y-m-d H:i:s");
                $StatusArray[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $StatusArray[$key]['user_type'] = 'fulfillment';
                $StatusArray[$key]['code'] = $shData['code'];
                $StatusArray[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                unlink("assets/all_labels/" . $shData['slip_no'] . ".pdf");
            }
            
            if (!empty($slipRArray)) {

                $this->Shipment_model->DeleteUpdateShipmentData($StatusArray);
                $shipmentdata = $this->Shipment_model->removeForwarding($slipRArray);
                // print_r( $slipRArray); exit; 
                $this->session->set_flashdata('msg', 'Forwarding Removed successfully');
                redirect(base_url() . 'remove_forward');
            } else {
                $this->session->set_flashdata('error', 'Not valid Numbers');
                redirect(base_url() . 'remove_forward');
            }
        } else {
            $this->session->set_flashdata('error', 'Not valid Numbers');
            redirect(base_url() . 'remove_forward');
        }
    }

    public function getshipmenttrackingresultexport() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;

        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "K1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Order No')
                ->setCellValue('B1', 'Origin')
                ->setCellValue('C1', 'Destination')
                ->setCellValue('D1', 'Receiver Name')
                ->setCellValue('E1', 'Receiver Address')
                ->setCellValue('F1', 'Receiver Phone')
                ->setCellValue('G1', 'SKU')
                ->setCellValue('H1', 'Status')
                ->setCellValue('I1', 'Qty')
                ->setCellValue('J1', 'Seller')
                ->setCellValue('K1', 'Entry Date');


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
        // "sssss"; die;
        // $status=$this->Shipment_model->allstatus();
        // 	$shipments = $this->Shipment_model->all();
        //$searchids=$this->input->post('tracking_numbers'); 
        //$data['traking_awb_no'] = preg_split('/\s+/', trim($searchids));
        // $data['shipmentdata']=$this->Shipment_model->getawbdataquery($data['traking_awb_no']);
        //print_r($data['shipmentdata']); die;
    }

    public function getshipmentdetailshow($shipmentId = null) {

        //$data['AWBNO']=getallsratusshipmentid($shipmentId,'slip_no');
        //$data['Shipmentinfo'] = $this->Shipment_model->getallshipmentdatashow($shipmentId);
        $data['Shipmentinfo'] = $this->Shipment_model->getShipmentDetails($shipmentId);
//        print "<pre>"; print_r($data['Shipmentinfo']);die;

        $courierCompany = GetCourCompanynameId($data['Shipmentinfo']['frwd_company_id'], 'company');
        
        if($courierCompany == 'Aymakan'){
            $status_array = array('7','8','9','1','2','4','3','11');
            
            if(!in_array($data['Shipmentinfo']['delivered'],$status_array)){
                
                $this->load->helper('aymakantrack');
                $shipData = $data['Shipmentinfo'];
                
                $track_result = updateTrackingStatus($shipData);
                //$data['Shipmentinfo'] = $this->Shipment_model->getallshipmentdatashow($shipmentId);
                $data['Shipmentinfo'] = $this->Shipment_model->getShipmentDetails($shipmentId);
            }
        }

        //print "<pre>"; print_r($data['Shipmentinfo']);die;
        $transaction_date = '';
        if ($data['Shipmentinfo']['3pl_close_date'] != '0000-00-00 00:00:00' && $data['Shipmentinfo']['3pl_close_date'] != '0000-00-00 00:00:00') {
            $pickup_date = new DateTime($data['Shipmentinfo']['3pl_pickup_date']);
            $closed_date = new DateTime($data['Shipmentinfo']['3pl_close_date']);
            $interval = $pickup_date->diff($closed_date);
            $transaction_date = $interval->format('%a days');
        }
        $data['Shipmentinfo']['pl3_pickup_date'] = $data['Shipmentinfo']['3pl_pickup_date'];
        $data['Shipmentinfo']['pl3_closed_date'] = $data['Shipmentinfo']['3pl_close_date'];
        $data['Shipmentinfo']['transaction_date'] = $transaction_date;

        $status = getStatusByCode_fm($data['Shipmentinfo']['code']);

        if (!empty($status)) {
            $data['Shipmentinfo']['status_fm'] = $status;
        } else {


            $data['Shipmentinfo']['status_fm'] = getallmaincatstatus($data['Shipmentinfo']['delivered'], 'main_status');
        }

        $sku_data = $this->Ccompany_model->Getskudetails_forward($data['Shipmentinfo']['slip_no']);
        //print "<pre>"; print_r($sku_data);die;


        $total_weight = 0;
        foreach ($sku_data as $key => $val) {
            $total_weight += ($sku_data[$key]['weight'] * $sku_data[$key]['piece']);
        }
        if ($total_weight > 0) {
            $weight = $total_weight;
        } else {
            $weight = 1;
        }
        $skuData = $this->Shipment_model->GetDiamationDetailsBYslipNo_og($data['Shipmentinfo']['slip_no']);
        $data['Shipmentinfo']['weight'] = $weight;
        $data['Shipmentinfo']['sku_data'] = $skuData;

        ////echo $data['Shipmentinfo']['slip_no']; die;
        //print "<pre>"; print_r($data['Shipmentinfo']);die;
        $data['THData'] = $this->Shipment_model->getalltravelhistorydata($data['Shipmentinfo']['slip_no']);
        $this->load->view('ShipmentM/trackingdetails', $data);
    }

    public function dispatched() {
        if (menuIdExitsInPrivilageArray(16) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('ShipmentM/dispatched', $bulk);
    }

    public function delivered_view() {
        if (menuIdExitsInPrivilageArray(104) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('ShipmentM/delivered', $bulk);
    }

    public function returned_view() {
        if (menuIdExitsInPrivilageArray(105) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );

        $this->load->view('ShipmentM/returned', $bulk);
    }

    public function packed() {
        if (menuIdExitsInPrivilageArray(15) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );
        $this->load->view('ShipmentM/packed', $bulk);
    }

    public function outbound() {
        redirect(base_url() . 'notfound');
        die;
        die;
        if (menuIdExitsInPrivilageArray(15) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );
        $this->load->view('ShipmentM/outbound', $bulk);
    }

    public function orderCreated() {
        if (menuIdExitsInPrivilageArray(13) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $bulk = array(
            // 'status'=>$status,
            //		'shipments'=>$shipments,
            'sellers' => $sellers,
            'condition' => $condition,
            'status' => $status,
                //'items'=>$items,
                //'sellers'=>$sellers
        );
        $this->load->view('ShipmentM/orderCreated', $bulk);
    }

    /* public function list_view(){

      $shipments = $this->Shipment_model->all_json();
      $bulk=array(

      'shipments'=>$shipments,

      );
      $this->load->view('ShipmentM/view_shipments',$bulk);
      } */

//	public function add_view(){
//
//		$data = $this->Shipment_model->add_view();
//		$this->load->view('ShipmentM/add_shipment' , $data);
//	}


    /* public function add(){

      $data = array(
      'awb_no'=>$this->input->post('awb_no'),
      'item_sku' => $this->input->post('item_sku'),
      //'cartoon_sku' => $this->input->post('cartoon_sku'),
      'seller' => $this->input->post('seller'),
      'status'=>$this->input->post('status'),
      'item_quantity' => $this->input->post('item_quantity'),
      'cartoon_quantity' => $this->input->post('cartoon_quantity'),
      'date'=>date("Y/m/d h:i:sa"),
      'comment'=>$this->input->post('comment')
      );

      if($this->Shipment_model->add($data))
      {
      $this->session->set_flashdata('msg',' Data  has been added successfully');
      redirect(base_url('Shipment'));

      }
      else{
      $this->session->set_flashdata('msg','No Such Data exist in Item inventory. Add Shipment failed');
      redirect(base_url('Shipment'));
      }
      } */

    public function bulk_update_view() {
        if (menuIdExitsInPrivilageArray(1) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $status = $this->Status_model->BulkStatus();
        $data['status'] = $status;
        $this->load->view('ShipmentM/bulk_update', $data);
    }

    public function bulk_update() {

        $add;
        $error = "";
        $first = 0;
        $awb_no = explode("\r\n", $this->input->post('awb_no'));
        $status = $this->input->post('status');
        $comment = $this->input->post('comment');

        for ($i = 0; $i < count($awb_no); $i++) {
            $previous_detail = $this->Shipment_model->find_by_slip_no1($awb_no[$i]);
            $previous_status = $previous_detail[0]->delivered;

            if ($previous_status == 19) {

                $add[$i] = $awb_no[$i];
            } else {

                $add[$i] = $this->Shipment_model->bulk_update(trim($awb_no[$i]), $comment, $status, $previous_detail[0]->cust_id);
            }
        }

        for ($i = 0; $i < count($add); $i++) {
            if ($add[$i] == 1) {
                
            } else {
                if ($first == 0) {
                    $error = $add[$i];
                    $first++;
                } else {
                    $error = $error . ", " . $add[$i];
                }
            }
        }


        if (!empty($error)) {
            $this->session->set_flashdata('something', 'Not Updated');
            $this->session->set_flashdata('error', $error);
            redirect(base_url('Shipment'));
        } else {
            $this->session->set_flashdata('msg', 'Bulk updated successfully');
            redirect(base_url('Shipment'));
        }
    }

    public function edit_view($id) {

        $data['shipment'] = $this->Shipment_model->edit_view($id);

        if (!empty($data['shipment'])) {
            $origin = $data['shipment'][0]->origin;
            $destination = $data['shipment'][0]->destination;
            $data['hub_name_o'] = getdestinationfieldshow_name($origin, 'country', 'id');
            $data['hub_name_d'] = getdestinationfieldshow_name($destination, 'country', 'id');
            $data['city'] = []; //$this->Shipment_model->countryList();
            $this->load->view('ShipmentM/shipment_detail', $data);
        } else {
            $this->session->set_flashdata('error', 'Something went wrong. Try again');
            redirect('Shipment');
        }
    }

    public function edit($id = null) {

        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('sender_name', 'Sender Name', 'trim|required');
        $this->form_validation->set_rules('sender_address', 'Sender Address', 'trim|required');
        $this->form_validation->set_rules('sender_phone', 'Sender Phone', 'trim|required');
        $this->form_validation->set_rules("reciever_name", 'Receiver Name', 'trim|required');
        $this->form_validation->set_rules('reciever_address', 'Receiver Address', 'trim|required');
        $this->form_validation->set_rules('reciever_phone', 'Receiver Phonoe', 'trim|required');
        $this->form_validation->set_rules('origin', 'Origin', 'trim|required');
        $this->form_validation->set_rules('destination', 'Destination', 'trim|required');
        $this->form_validation->set_rules('booking_mode', 'Booking Mode', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            $this->edit_view($id);
        } else {
            $sender_name = $this->input->post('sender_name');
            $sender_address = $this->input->post('sender_address');
            $sender_phone = $this->input->post('sender_phone');
            $reciever_name = $this->input->post('reciever_name');
            $reciever_address = $this->input->post('reciever_address');

            $reciever_phone = $this->input->post('reciever_phone');
            $origin = $this->input->post('origin');
            $mode = $this->input->post('booking_mode');
            $destination = $this->input->post('destination');
            $comment = $this->input->post('comment');
            if (empty($comment)) {
                $comment = "";
            }

            $oldShipData = $this->Shipment_model->edit_view($id);
            $statusActivites = "";
            if ($oldShipData[0]->sender_name != $sender_name) {
                $statusActivites .= $sender_name . " Sender Name changed from " . $oldShipData[0]->sender_name . "<br>";
            }
            if ($oldShipData[0]->sender_address != $sender_address) {
                $statusActivites .= $sender_address . " Sender Address changed from " . $oldShipData[0]->sender_address . "<br>";
            }
            if ($oldShipData[0]->sender_phone != $sender_phone) {
                $statusActivites .= $sender_phone . " Sender Phone changed from " . $oldShipData[0]->sender_phone . "<br>";
            }
            if ($oldShipData[0]->reciever_name != $reciever_name) {
                $statusActivites .= $reciever_name . " Receiver Name changed from " . $oldShipData[0]->reciever_name . "<br>";
            }
            if ($oldShipData[0]->reciever_address != $reciever_address) {
                $statusActivites .= $reciever_address . " Receiver Address changed from " . $oldShipData[0]->reciever_address . "<br>";
            }
            if ($oldShipData[0]->reciever_phone != $reciever_phone) {
                $statusActivites .= $reciever_phone . " Receiver Phone changed from " . $oldShipData[0]->reciever_phone . "<br>";
            }
            if ($oldShipData[0]->destination != $destination) {
                $statusActivites .= getdestinationfieldshow($destination, 'city') . " Destination changed from " . getdestinationfieldshow($oldShipData[0]->destination, 'city') . "<br>";
            }
            if ($oldShipData[0]->origin != $origin) {
                $statusActivites .= getdestinationfieldshow($origin, 'city') . " Origin changed from " . getdestinationfieldshow($oldShipData[0]->origin, 'city') . "<br>";
            }
            if ($oldShipData[0]->mode != $mode) {
                $statusActivites .= $mode . " booking mode changed from " . $oldShipData[0]->mode . "<br>";
            }
            if (!empty($statusActivites)) {

                $key88 = 0;
                $StatusArray[$key88]['slip_no'] = $oldShipData[0]->slip_no;
                $StatusArray[$key88]['new_status'] = $oldShipData[0]->delivered;
                $StatusArray[$key88]['pickup_time'] = date("H:i:s");
                $StatusArray[$key88]['pickup_date'] = date("Y-m-d");
                $StatusArray[$key88]['Details'] = addslashes($statusActivites);
                $StatusArray[$key88]['Activites'] = addslashes($statusActivites);
                $StatusArray[$key88]['entry_date'] = date("Y-m-d H:i:s");
                $StatusArray[$key88]['comment'] = addslashes($comment);
                $StatusArray[$key88]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $StatusArray[$key88]['user_type'] = 'fulfillment';
                $StatusArray[$key88]['code'] = $oldShipData[0]->code;
                $StatusArray[$key88]['super_id'] = $this->session->userdata('user_details')['super_id'];
            }


            $data = array(
                'sender_name' => addslashes($sender_name),
                'sender_address' => addslashes($sender_address),
                'sender_phone' => $sender_phone,
                'reciever_name' => addslashes($reciever_name),
                'reciever_address' => addslashes($reciever_address),
                'reciever_phone' => $reciever_phone,
                'origin' => $origin,
                'destination' => $destination,
                'mode' => $mode
            );

            $this->Status_model->insertStatus($StatusArray);
            $this->Shipment_model->edit($id, $data);
            $this->session->set_flashdata('msg', 'Shipment ' . $oldShipData[0]->slip_no . ' has been updated successfully');
            redirect('Shipment');
        }
    }

    /* public function edit($id){
      $seller=$this->Seller_model->find($this->input->post('seller'));
      $cust_id=$seller->customer;
      // print("quantity: ");
      // print($this->input->post('item_quantity'));
      // print(" ");
      // print("status: ");
      // print($this->input->post('status'));
      // print(" ");
      // print("sku: ");
      // print($this->input->post('item_sku'));
      // print(" ");
      // print("seller: ");
      // print($this->input->post('seller'));
      // print(" ");
      // print("cust: ");
      // print($cust_id);
      // exit();
      $data = array(

      'pieces' => $this->input->post('item_quantity'),
      'delivered' => $this->input->post('status'),
      'sku'=>$this->input->post('item_sku'),
      'cust_id' =>$cust_id
      );

      $this->Shipment_model->edit($id,$data);
      $this->session->set_flashdata('msg','Shipment id has been updated successfully');
      redirect('Shipment');

      } */

    /* public function report_view($id){
      $data['shipment']=$this->Shipment_model->find($id);
      $data['sku_per_shipment']=$this->Shipment_model->find_by_slip_no($data['shipment'][0]->slip_no);
      // print_r($data['shipment']);
      // exit();
      for($i=0;$i<count($data['sku_per_shipment']);$i++){
      $sku[$i]=$data['sku_per_shipment'][$i]->sku;
      $piece[$i]=$data['sku_per_shipment'][$i]->piece;
      }


      $data['seller']=$this->Seller_model->find_customer_sellerm($data['shipment'][0]->cust_id);
      $data['status']=$this->Status_model->find($data['shipment'][0]->delivered);
      $this->load->view('ShipmentM/shipment_report',['data'=>$data,'sku'=>$sku,'piece'=>$piece]);

      } */

    public function filter_orderCreated() {

        // error_reporting(-1);
		// ini_set('display_errors', 1);
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);


        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'REF')
            $refsno = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'MOBL')
            $mobileno = $_POST['s_type_val'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $wh_id = $_POST['wh_id'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $booking_id = $_POST['booking_id'];
        if (!empty($_POST['limit']))
            $limit = $_POST['limit'] + 1;
        else
            $limit = 0;
        // echo json_encode($_POST);
        // print($exact);
        // print($awb);
        // print($sku);  
        // print($from);
        // print($to);
        // print($delivered);  
        // print($seller);
        // exit();

        $shipments = $this->Shipment_model->filter_orderCreated($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $limit, $refsno, $mobileno, $wh_id, $_POST);


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
        $SiteConfingData = Getsite_configData();
        // print_r($SiteConfingData);
        //echo $SiteConfingData['e_city'];
        $e_city = explode(',', $SiteConfingData['e_city']);

        $picker = $this->User_model->userDropval(4);
        $destinationArray = array();
        $originArray = array();
        $whArray = array();
        $ccNameArray = array();
        $customerArray=array();
        $destinationCountry=array();
        foreach ($shipments['result'] as $rdata) {

           if(empty( $customerArray[$rdata['cust_id']]))
           {
           $customerArray[$rdata['cust_id']]= GetSinglesellerdata($rdata['cust_id'],$rdata['super_id']);
           //print_r($customerArray[$rdata['cust_id']]);exit;
           }

           $shiparray[$ii]['name']=$customerArray[$rdata['cust_id']]['name'];
           $shiparray[$ii]['company']=$customerArray[$rdata['cust_id']]['company'];
            // $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
            if (empty($rdata['order_type'])) {
//                $itemID = getallitemskubyid($rdata['sku']);
//                $itemtypes = getalldataitemtables($itemID, 'type');
                $shiparray[$ii]['order_type'] = 'B2C';

                //$shiparray[$ii]['order_type']="";
            } else
                $shiparray[$ii]['order_type'] = $rdata['order_type'];

            if (in_array($rdata['destination'], $e_city) || $rdata['frwd_company_id'] == '') {
                $shiparray[$ii]['generateButton'] = 'Y';
            } else {
                $shiparray[$ii]['generateButton'] = 'N';
            }
            if (empty($originArray[$rdata['origin']])) {
                $originArray[$rdata['origin']] = getdestinationfieldshow($rdata['origin'], 'city');
            }
            $shiparray[$ii]['origin'] = $originArray[$rdata['origin']]; // getdestinationfieldshow($rdata['origin'], 'city');

            if (empty($destinationArray[$rdata['destination']])) {
                $destinationArray[$rdata['destination']] = getdestinationfieldshow($rdata['destination'], 'city');
            }
            $shiparray[$ii]['destination'] = $destinationArray[$rdata['destination']]; //getdestinationfieldshow($rdata['destination'], 'city');

            if(empty($destinationCountry[$rdata['destination']]))
            {
                $destinationCountry[$rdata['destination']]=getdestinationfieldshow($rdata['destination'], 'country');
            }
            $shiparray[$ii]['country_name'] = $destinationCountry[$rdata['destination']];
            // if (empty($whArray[$rdata['wh_id']])) {
            //     $whArray[$rdata['wh_id']] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            // }
            $shiparray[$ii]['wh_id'] = $whArray[$rdata['wh_id']]; //Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            //$shiparray[$ii]['expire_details'] = $expire_data;
            // $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            // $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            // $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            
            // $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
             $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            if( empty($whArray[$rdata['wh_id']]))
            {
                $whArray[$rdata['wh_id']]= Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }
            
            if ($rdata['wh_id'] > 0) {
                $shiparray[$ii]['wh_id'] = $whArray[$rdata['wh_id']];
            } else {
                $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }
            

            // $companyName = GetCourCompanynameId($rdata['frwd_company_id'], 'company');
            if (empty($ccNameArray[$rdata['frwd_company_id']])) {
                $ccNameArray[$rdata['frwd_company_id']] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');
            }
            $shiparray[$ii]['cc_name'] = $ccNameArray[$rdata['frwd_company_id']]; //GetCourCompanynameId($rdata['frwd_company_id'], 'company');

            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
        }




        // echo '<pre>';
        // print_r($ $customerArray); die();
        //echo json_encode($shiparray);
        // die;
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        $dataArray['picker'] = $picker;


        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function pageshortDropData($maxval = 0) {
        //echo $maxval; die;

        $min = 100;
        $max = 100;//$maxval; // Just chenge this val;
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

    public function filter() {
        // print("heelo"); 
        // exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print "<pre>"; print_r($_POST);die;

        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'REF')
            $refsno = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'MOBL')
            $mobileno = $_POST['s_type_val'];

        if ($_POST['s_type'] == ''){
            $awb = $_POST['s_type_val'];
        }

        $from = $_POST['from'];
        $to = $_POST['to'];
        $wh_id = $_POST['wh_id'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $booking_id = $_POST['booking_id'];
        $cc_id = $_POST['cc_id'];


        $is_menifest = isset($_POST['is_manifest']) ? $_POST['is_manifest'] : null;
        $shipments = $this->Shipment_model->filter($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id, $is_menifest, $refsno, $mobileno, $wh_id, $_POST);


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



        $pageShortArr = $this->pageshortDropData(100);
        // print_r($pageShortArr); die;
        $destinationArray = array();
        $originArray = array();
        $whArray = array();
        $ccNameArray = array();
        $ccUrlArray = array();
        $destinationCountry=array();
        foreach ($shipments['result'] as $rdata) {
            //print "<pre>"; print_r($rdata);die;
            //$expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
            //$itemtypes = getalldataitemtablesSKU(trim($rdata['sku']), 'type');
            if (!empty($rdata['order_type']))
                $shiparray[$ii]['order_type'] = $rdata['order_type'];
            else {
                $shiparray[$ii]['order_type'] = 'B2C';
            }
            if(!empty($rdata['pay_invoice_no']))
            $shiparray[$ii]['pay_invoice_no'] = "Y";
            else
             $shiparray[$ii]['pay_invoice_no'] = "N";
            
            //$shiparray[$ii]['order_type']="";
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray[$ii]['expire_details'] = $expire_data;
            if (empty($originArray[$rdata['origin']])) {
                $originArray[$rdata['origin']] = getdestinationfieldshow($rdata['origin'], 'city');
            }
            $shiparray[$ii]['origin'] = $originArray[$rdata['origin']]; // getdestinationfieldshow($rdata['origin'], 'city');

            if (empty($destinationArray[$rdata['destination']])) {
                $destinationArray[$rdata['destination']] = getdestinationfieldshow($rdata['destination'], 'city');
            }
            $shiparray[$ii]['destination'] = $destinationArray[$rdata['destination']]; //getdestinationfieldshow($rdata['destination'], 'city');

            if(empty($destinationCountry[$rdata['destination']]))
            {
                $destinationCountry[$rdata['destination']]=getdestinationfieldshow($rdata['destination'], 'country');
            }
            $shiparray[$ii]['country_name'] =  $destinationCountry[$rdata['destination']];

            // if (empty($whArray[$rdata['wh_id']])) {
            //    // $whArray[$rdata['wh_id']] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            // }
            // $shiparray[$ii]['wh_id'] = $whArray[$rdata['wh_id']]; //Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            if( empty($whArray[$rdata['wh_id']]))
            {
                $whArray[$rdata['wh_id']]= Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }
            
            if ($rdata['wh_id'] > 0) {
                $shiparray[$ii]['wh_id'] = $whArray[$rdata['wh_id']];
            } else {
                $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }

            $shiparray[$ii]['DispatchDate'] = $rdata['dispatch_date']; //GetStatusFmTableCodes($rdata['slip_no'], 'DL');

            $status = getStatusByCode_fm($rdata['code']);
            if (!empty($status)) {
                $shiparray[$ii]['status'] = $status;
            } else {
                $shiparray[$ii]['status'] = $rdata['main_status'];
            }

            if ($shiparray[$ii]['code'] == 'POD' && $rdata['frwd_company_id'] == 0) {
                $shiparray[$ii]['cc_name'] = "Diggipacks";
            } else {

                if (empty($ccNameArray[$rdata['frwd_company_id']])) {
                    $ccNameArray[$rdata['frwd_company_id']] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');
                    $ccUrlArray[$rdata['frwd_company_id']]=GetCourCompanynameId($rdata['frwd_company_id'], 'company_url');
                }
                $shiparray[$ii]['cc_name'] = $ccNameArray[$rdata['frwd_company_id']]; //GetCourCompanynameId($rdata['frwd_company_id'], 'company');
            }



            $shiparray[$ii]['pl3_pickup_date'] = $rdata['3pl_pickup_date'];
            $shiparray[$ii]['pl3_closed_date'] = $rdata['3pl_close_date'];

            $transaction_date = '';
            if (!empty($rdata['3pl_close_date'])) {
                $pickup_date = new DateTime($rdata['3pl_pickup_date']);
                $closed_date = new DateTime($rdata['3pl_close_date']);
                $interval = $pickup_date->diff($closed_date);
                $transaction_date = $interval->format('%a days');
            }

            $shiparray[$ii]['transaction_date'] = $transaction_date;

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            if ($rdata['frwd_company_awb'] != '') {
                $track_url = $ccUrlArray[$rdata['frwd_company_id']];
                if (!empty($track_url)) {
                    $shiparray[$ii]['frwd_link'] = $track_url . $rdata['frwd_company_awb'];
                } else {
                    $shiparray[$ii]['frwd_link'] = '#';
                }
            } else {
                $shiparray[$ii]['frwd_link'] = "#";
            }


           // $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
        }
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function forward_report_data() {

        $_POST = json_decode(file_get_contents('php://input'), true);


        $exact = $_POST['exact'];
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'REF')
            $refsno = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'MOBL')
            $mobileno = $_POST['s_type_val'];

        $from = $_POST['from'];
        $to = $_POST['to'];
        $wh_id = $_POST['wh_id'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $booking_id = $_POST['booking_id'];
        $cc_id = $_POST['cc_id'];


        $is_menifest = isset($_POST['is_manifest']) ? $_POST['is_manifest'] : null;


        $shipments = $this->Shipment_model->filter_fwdReport($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id, $is_menifest, $refsno, $mobileno, $wh_id, $_POST);


        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];

        $dataArray['result'] = $shiparray;

        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function filterReverse() {
        // print("heelo"); 
        // exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print "<pre>"; print_r($_POST);die;

        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if (isset($_POST['s_type_val']))
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'REF')
            $refsno = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'MOBL')
            $mobileno = $_POST['s_type_val'];

        $from = $_POST['from'];
        $to = $_POST['to'];
        $wh_id = $_POST['wh_id'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        $booking_id = $_POST['booking_id'];
        $cc_id = $_POST['cc_id'];


        $is_menifest = isset($_POST['is_manifest']) ? $_POST['is_manifest'] : null;

        $shipments = $this->Shipment_model->filterViewReverse($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id, $is_menifest, $refsno, $mobileno, $wh_id, $_POST);


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

        $whArray =array();

        $pageShortArr = $this->pageshortDropData($tolalShip);
        // print_r($pageShortArr); die;
        foreach ($shipments['result'] as $rdata) {
            //print "<pre>"; print_r($rdata);die;
            $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);

            $itemtypes = getalldataitemtablesSKU(trim($rdata['sku']), 'type');
            $shiparray[$ii]['order_type'] = $itemtypes;

            //$shiparray[$ii]['order_type']="";
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray[$ii]['expire_details'] = $expire_data;
            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            // $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');

            if( empty($whArray[$rdata['wh_id']]))
            {
                $whArray[$rdata['wh_id']]= Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }
            
            if ($rdata['wh_id'] > 0) {
                $shiparray[$ii]['wh_id'] = $whArray[$rdata['wh_id']];
            } else {
                $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }

            $shiparray[$ii]['DispatchDate'] = GetStatusFmTableCodes($rdata['slip_no'], 'DL');





            $status = getStatusByCode_fm($rdata['code']);
            if (!empty($status)) {
                $shiparray[$ii]['status'] = $status;
            } else {
                $shiparray[$ii]['status'] = $rdata['main_status'];
            }

            if ($shiparray[$ii]['code'] == 'POD' && $rdata['frwd_company_id'] == 0) {
                $shiparray[$ii]['cc_name'] = "Diggipacks";
            } else {
                $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');
            }



            $shiparray[$ii]['pl3_pickup_date'] = $rdata['3pl_pickup_date'];
            $shiparray[$ii]['pl3_closed_date'] = $rdata['3pl_close_date'];

            $transaction_date = '';
            if (!empty($rdata['3pl_close_date'])) {
                $pickup_date = new DateTime($rdata['3pl_pickup_date']);
                $closed_date = new DateTime($rdata['3pl_close_date']);
                $interval = $pickup_date->diff($closed_date);
                $transaction_date = $interval->format('%a days');
            }

            $shiparray[$ii]['transaction_date'] = $transaction_date;

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];
            if ($rdata['frwd_company_awb'] != '') {
                $track_url = GetCourCompanynameId($rdata['frwd_company_id'], 'company_url');
                if (!empty($track_url)) {
                    $shiparray[$ii]['frwd_link'] = $track_url . $rdata['frwd_company_awb'];
                } else {
                    $shiparray[$ii]['frwd_link'] = '#';
                }
            } else {
                $shiparray[$ii]['frwd_link'] = "#";
            }


            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
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

    public function filter_backorder() {
        // print("heelo");
        // exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        //echo json_encode($_POST);
        // print($exact);
        // print($awb);
        // print($sku);
        // print($from);
        // print($to);
        // print($delivered);
        // print($seller);
        // exit();

        $shipments = $this->Shipment_model->filter_backorder($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination);

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = count($shipments);
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        foreach ($shipments['result'] as $rdata) {

            $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);

            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray[$ii]['expire_details'] = $expire_data;
            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            //$shiparray='rith';
            $ii++;
        }
        //echo '<pre>';
        //print_r($shiparray);
        //echo json_encode($shiparray);
        // die;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function filter_orderGen() {
        // print("heelo");
        // exit();
        
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);

        $shipments = $this->Shipment_model->filter_orderGen($_POST);

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        // echo "<pre>" ;
        // print_r($_POST);  die;

        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = count($shipments);
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }

        $tolalShip1 = count($shipments);
        if ($tolalShip1 <= 100) {
            $downlaoadData1 = 10;
            $m = 1;
            for ($im = 0; $im < $tolalShip1;) {
                $im = $im + $downlaoadData1;
                if ($i > 1) {
                    $expoertdropArr1[] = array('j' => $m, 'i' => $im);
                }
                $m = $im;
            }
        }



        $pageShortArr = $this->pageshortDropData($tolalShip);
      
        $originArray=array();
        $destinationArray=array();
        $destinationCountry=array();
        $ccNameArray=array();
        $customerArray=array();
        $whArray = array();
        
        foreach ($shipments['result'] as $rdata) {


            if(empty( $customerArray[$rdata['cust_id']]))
            {
            $customerArray[$rdata['cust_id']]= GetSinglesellerdata($rdata['cust_id'],$rdata['super_id']);
            //print_r($customerArray[$rdata['cust_id']]);exit;
            }
            
            $shiparray[$ii]['seller_id']=$customerArray[$rdata['cust_id']]['id'];
            $shiparray[$ii]['name']=$customerArray[$rdata['cust_id']]['name'];
            $shiparray[$ii]['company']=$customerArray[$rdata['cust_id']]['company'];
            $shiparray[$ii]['uniqueid']=$customerArray[$rdata['cust_id']]['uniqueid'];
            if (empty($ccNameArray[$rdata['frwd_company_id']]) && $rdata['frwd_company_id']>0 ) {
                $ccNameArray[$rdata['frwd_company_id']] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');
            }

            if( empty($originArray[$rdata['origin']]))
    {
        $originArray[$rdata['origin']]= getdestinationfieldshow($rdata['origin'], 'city');
    }
    if( empty($destinationArray[$rdata['destination']]))
    {
        $destinationArray[$rdata['destination']]= getdestinationfieldshow($rdata['destination'], 'city');
    }
    if( empty($destinationCountry[$rdata['destination']]))
    {
        $destinationCountry[$rdata['destination']]= getdestinationfieldshow($rdata['destination'], 'country');
    }

            //$expire_data=$this->Shipment_model->GetallexpredataQuery($rdata['seller_id'],$rdata['sku']);
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            //$shiparray[$ii]['expire_details']=$expire_data;
            if (!empty($rdata['order_type'])) {
                $shiparray[$ii]['order_type'] = $rdata['order_type'];
            } else {
                $shiparray[$ii]['order_type'] = 'B2C';
            }
            $shiparray[$ii]['sku_id'] = getalldataitemtablesBySku($rdata['sku'], 'id');
            //$shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['origin_valid'] = $rdata['origin'];
            $shiparray[$ii]['destination_valid'] = $rdata['destination'];
            if ($rdata['origin'] > 0) {
                $shiparray[$ii]['origin'] = $originArray[$rdata['origin']];
            } else {
                $shiparray[$ii]['origin'] = GetErrorShowShipment($rdata['slip_no'], $rdata['booking_id'], 'origin');
            }
            if ($rdata['destination'] > 0) {
                $shiparray[$ii]['destination'] = $destinationArray[$rdata['destination']];
            } else {
                $shiparray[$ii]['destination'] = GetErrorShowShipment($rdata['slip_no'], $rdata['booking_id'], 'destination');
            }
            if(empty($destinationCountry[$rdata['destination']]))
            {
                $destinationCountry[$rdata['destination']]=$destinationCountry[$rdata['destination']];
            }

            if( empty($whArray[$rdata['wh_id']]))
            {
                $whArray[$rdata['wh_id']]= Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }

            if ($rdata['wh_id'] > 0) {
                $shiparray[$ii]['wh_id'] = $whArray[$rdata['wh_id']];
            } else {
                $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            }

            $shipArray[$ii]['main_status']='Order Generated';
            $shiparray[$ii]['destination_id'] = $rdata['destination'];
            $shiparray[$ii]['sender_name'] = $rdata['sender_name']; //getallsellerdatabyID($rdata['cust_id'],'company',$rdata['super_id']);
            $shiparray[$ii]['total_cod_amt'] = $rdata['total_cod_amt'];
            $shiparray[$ii]['whid'] = $rdata['wh_id'];
            $shiparray[$ii]['cc_name'] =  $ccNameArray[$rdata['frwd_company_id']];
            $shiparray[$ii]['country_name'] = $destinationCountry[$rdata['destination']];
            // $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);
            // $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            //$shiparray='rith';
            $ii++;
        }


        $dataArray['dropexport_checkbox'] = $expoertdropArr1;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];


        echo json_encode($dataArray);
    }

    public function filter_outbound() {
        // print("heelo");
        // exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exact = $_POST['exact']; //date('Y-m-d 00:00:00',strtotime($this->input->post('exact'))); 
        // $exact2 =$this->input->post('exact');//date('Y-m-d 23:59:59',strtotime($this->input->post('exact'))); 
        if ($_POST['s_type'] == 'AWB')
            $awb = $_POST['s_type_val'];
        if ($_POST['s_type'] == 'SKU')
            $sku = $_POST['s_type_val'];
        $from = $_POST['from'];
        $to = $_POST['to'];
        $delivered = $_POST['status'];
        $seller = $_POST['seller'];
        $page_no = $_POST['page_no'];
        $destination = $_POST['destination'];
        //echo json_encode($_POST);
        // print($exact);
        // print($awb);
        // print($sku);
        // print($from);
        // print($to);
        // print($delivered);
        // print($seller);
        // exit();

        $shipments = $this->Shipment_model->filter_outbound($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination);

        //echo json_encode($shipments);exit();
        //getdestinationfieldshow();
        $shiparray = array();
        //echo json_encode($shipments); die;
        $ii = 0;
        foreach ($shipments['result'] as $rdata) {
            $skuid = getallitemskubyid($rdata['sku']);
            $item_type = getalldataitemtables($skuid, 'type');
            if ($item_type == 'B2B') {

                $shiparray[$ii]['stocklcount'] = $rdata['stocklcount'];
                $shiparray[$ii]['sku'] = $rdata['sku'];
                $shiparray[$ii]['piece'] = $rdata['piece'];
                $shiparray[$ii]['cod'] = $rdata['cod'];
                $shiparray[$ii]['item_type'] = $item_type;
                $shiparray[$ii]['slip_no'] = $rdata['slip_no'];
                $shiparray[$ii]['booking_id'] = $rdata['booking_id'];
                $shiparray[$ii]['entrydate'] = $rdata['entrydate'];
                $shiparray[$ii]['name'] = $rdata['name'];
                $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
                //if($expire_data[$ii]['sku']==$rdata['sku'])
                $shiparray[$ii]['expire_details'] = $expire_data;
                $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
                $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            }

            //$shiparray='rith';
            $ii++;
        }
        //echo '<pre>';
        $shiparray1 = array_values($shiparray);
        //print_r($shiparray1);
        //echo json_encode($shiparray);
        // die;

        $dataArray['result'] = $shiparray1;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    function createBackorder() {
        $this->load->model('Pickup_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        //echo '<pre>';
        //print_r($dataArray); die;
        $picklistValue = array();
        $statusvalue = array();
        $errorReturn = array();
        $ReturnstockArray = array();
        $shipArray = array();
        $key = 0;
        foreach ($dataArray['listData'] as $data) {
            foreach ($data['skuData'] as $skuDetails) {

                $stock_check = CheckStockBackorder($data['seller_id'], $skuDetails['sku'], $skuDetails['piece'], $data['slip_no']);
                //$ReturnstockArray['invalid'][]=$data['sku'];
                if ($stock_check['succ'] == 1) {
                    //array_push($picklistValue,$stock_check['stArray']);
                    $ReturnstockArray['valid'][] = $stock_check['stArray'];
                    $shipArray[$key]['slip_no'] = $data['slip_no'];
                    $shipArray[$key]['backorder'] = 0;
                } else {
                    $ReturnstockArray['invalid'][] = array('sku' => $skuDetails['sku'], 'data' => $data['slip_no'], 'qty' => $skuDetails['piece']);
                }
            }
            $key++;
        }
        //echo '<pre>';
//print_r($ReturnstockArray); die;
        if (empty($ReturnstockArray['invalid'])) {

            UpdateStockBackorder($ReturnstockArray['valid']);

            // die;
            //UpdateStockBackorder($ReturnstockArray['valid']);
            $this->Shipment_model->updateStatusBatch($shipArray);
        }
        echo json_encode($ReturnstockArray['invalid']);
    }

    function GetcheckOrderDeleteStatus() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $slip_no = $_POST['slip_no'];
        if (!empty($slip_no)) {
            $data = array('deleted' => 'Y');
            $data_w = array('slip_no' => $slip_no);
            $key = 0;

            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $slip_no;
            $statusvalue[$key]['new_status'] = 9;
            $statusvalue[$key]['code'] = 'C';
            $statusvalue[$key]['Activites'] = 'Shipment Deleted';
            $statusvalue[$key]['deleted'] = 'Y';
            $statusvalue[$key]['Details'] = 'Shipment Deleted';
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            $request = $this->Shipment_model->GetcheckOrderDeleteStatusQry($data, $data_w);
            $this->Shipment_model->DeleteUpdateShipmentData($statusvalue);
            if ($request == true)
                $return = "succ";
            else
                $return = "error";
        }
        echo json_encode($return);
    }

    function GetremoveMultipleOrders() {
        $postData = json_decode(file_get_contents('php://input'), true);

        // print_r($postData); die;
        $page_check = $postData['page_check'];

        if (!empty($postData)) {

            $shipMentArr = $this->Shipment_model->GetCheckDeleteProceesShipment($postData['slipData']);
            //print_r($shipMentArr);
            $CheckOtherLocation = array();

            foreach ($shipMentArr as $key => $val) {
                //=========shipment update===================//
                $shipupdateAray[$key]['deleted'] = 'Y';
                $shipupdateAray[$key]['code'] = 'C';
                $shipupdateAray[$key]['delivered'] = '9';
                $shipupdateAray[$key]['slip_no'] = $val['slip_no'];
                //======================status update====================//

                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $val['slip_no'];
                $statusvalue[$key]['new_status'] = 9;
                $statusvalue[$key]['code'] = 'C';
                $statusvalue[$key]['Activites'] = 'Shipment Deleted';
                $statusvalue[$key]['deleted'] = 'Y';
                $statusvalue[$key]['Details'] = 'Shipment Deleted';
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                //===============stock history====================//
                //  $inventoryHistory[$key]['']
                //===================remove charge================//

                if ($page_check == 'ship_view') {

                    if ($val['code'] != 'OG') {

                        $removeExtraCharges[$key]['slip_no'] = $val['slip_no'];
                        $removeExtraCharges[$key]['packaging_charge'] = 0;
                        $removeExtraCharges[$key]['picking_charge'] = 0;
                        $removeExtraCharges[$key]['special_packing_charge'] = 0;
                        $removeExtraCharges[$key]['box_charge'] = 0;

                        //=================remove stock location ===================//
                        $removeStockLocation[$key]['slip_no'] = $val['slip_no'];
                        $removeStockLocation[$key]['deleted'] = 'Y';
                        //==========================================================//
                        //=============inventory add===================//

                        $SkuArrData = $this->Shipment_model->diamention_fmDeleteProcessQry($val['slip_no']);
                        // print_r($SkuArrData);
                        $expdate = "0000-00-00";
                        foreach ($SkuArrData as $newkey => $skudata) {
                            $qty = $skudata['piece'];
                            $sku_size = $skudata['sku_size'];
                            $item_type = $skudata['type'];
                            $SkuID = $skudata['id'];
                            $wh_id = $skudata['wh_id'];




                            if ($qty > 0) {
                                if ($sku_size >= $qty)
                                    $locationLimit = 1;
                                else {
                                    $locationLimit1 = $qty / $sku_size;
                                    $locationLimit = ceil($locationLimit1);
                                }
                                $StockArray = $this->ItemInventory_model->Getallstocklocationdata($val['cust_id'], $locationLimit, $CheckOtherLocation);

                                $updateaty = $qty;
                                $AddQty = 0;
                                for ($ii = 0; $ii < $locationLimit; $ii++) {
                                    if ($sku_size <= $updateaty) {
                                        $AddQty = $sku_size;
                                        $updateaty = $updateaty - $sku_size;
                                    } else {
                                        $AddQty = $updateaty;
                                        $updateaty = $updateaty;
                                    }
                                    // $stocklocation=$_POST['location_st'];
                                    $addinventory[] = array(
                                        'itype' => $item_type,
                                        'item_sku' => $SkuID,
                                        'seller_id' => $val['cust_id'],
                                        'quantity' => $AddQty,
                                        'update_date' => date("Y/m/d h:i:sa"),
                                        'stock_location' => $StockArray[$newkey]->stock_location,
                                        'expity_date' => $expdate,
                                        'awb_no' => $val['slip_no'],
                                        'status_type' => 'delete',
                                        'wh_id' => $wh_id,
                                        'super_id' => $this->session->userdata('user_details')['super_id']
                                    );
                                    array_push($CheckOtherLocation, $StockArray[$ii]->stock_location);
                                }
                            }
                        }
                    }
                }
            }
            // print_r($addinventory); die;
            if (!empty($shipupdateAray)) {
                $request = $this->Shipment_model->updateStatusBatch($shipupdateAray);
                $this->Shipment_model->DeleteUpdateShipmentData($statusvalue);

                if (!empty($addinventory)) {
                    $this->ItemInventory_model->add($addinventory, 'delete');
                }

                if (!empty($removeExtraCharges)) {
                    $this->Shipment_model->RemmoveChargesDeleteOrder($removeExtraCharges);
                }


                if (!empty($removeStockLocation)) {
                    $this->Shipment_model->RemoveStockLocation($removeStockLocation);
                }
            }
            if ($request == true) {
                $return = "succ";
            } else {
                $return = "error";
            }
        } else {
            $return = "error";
        }
        echo json_encode($return);
    }

    function GetRemoveDimationSku() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        //print_r($_POST);
        // die;
        if (!empty($_POST) && !empty($_POST['d_id'])) {
            // echo "sssssss"; die;
            $key = 0;
            $updateArray = array('deleted' => 'Y');
            $updateArray_w = array('id' => $_POST['d_id']);
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $StatusArray[$key]['slip_no'] = $_POST['slip_no'];
            $StatusArray[$key]['new_status'] = 11;
            $StatusArray[$key]['pickup_time'] = date("H:i:s");
            $StatusArray[$key]['pickup_date'] = date("Y-m-d");
            $StatusArray[$key]['Details'] = "Removed SKU " . $_POST['sku'];
            $StatusArray[$key]['Activites'] = "Removed SKU " . $_POST['sku'];
            $StatusArray[$key]['entry_date'] = date("Y-m-d H:i:s");
            $StatusArray[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $StatusArray[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
            $StatusArray[$key]['user_type'] = 'fulfillment';
            $StatusArray[$key]['code'] = 'OG';
            // print_r($StatusArray);die;
            $request = $this->Shipment_model->GetcheckOrderDeleteStatusQry_dimation($updateArray, $updateArray_w);
            $this->Status_model->insertStatus($StatusArray);
        }
    }

    function GetUpdateShipmentDataPage() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        //echo '<pre>';
        //print_r($dataArray); die;
        $checkBookingID = BookingIdCheck_cust($dataArray['booking_id'], $dataArray['cust_id'], $dataArray['id']);
        if (empty($dataArray['destination_id']))
            $return['emptyval'] = "destination_id";
        if (empty($dataArray['booking_id']))
            $return['emptyval'] = "booking_id";
        if (empty($dataArray['reciever_name']))
            $return['emptyval'] = "reciever_name";
        if (empty($dataArray['reciever_phone']))
            $return['emptyval'] = "reciever_phone";
        if (empty($dataArray['reciever_address']))
            $return['emptyval'] = "reciever_address";
       // if (empty($dataArray['total_cod_amt']))
           // $return['emptyval'] = "total COD Amt";
        if (empty($dataArray['whid']))
            $return['emptyval'] = "whid";
        if (empty($error['emptyval'])) {
            if (empty($checkBookingID)) {
                $totalPices = 0;
                $totalCod = 0;
                $skuTempArray = array();
                $ActiviesArray = array();

                $oldShipData = GetshipmentRowsDetailsPage($dataArray['slip_no']);
                //$oldSlipArray=$this->Shipment_model->GetDiamationDetailsBYslipNo($dataArray['slip_no']);
                $statusActivites = "";
                if ($oldShipData['booking_id'] != $dataArray['booking_id']) {
                    $statusActivites .= $dataArray['booking_id'] . " Booking Id changed from " . $oldShipData['booking_id'] . "<br>";
                }
                if ($oldShipData['reciever_name'] != $dataArray['reciever_name']) {
                    $statusActivites .= $dataArray['reciever_name'] . " Reciver Name changed from " . $oldShipData['reciever_name'] . "<br>";
                }
                if ($oldShipData['reciever_phone'] != $dataArray['reciever_phone']) {
                    $statusActivites .= $dataArray['reciever_phone'] . " Reciver Phone changed from " . $oldShipData['reciever_phone'] . "<br>";
                }
                ///echo $oldShipData['reciever_address']."==".$dataArray['reciever_phone'];
                if ($oldShipData['reciever_address'] != $dataArray['reciever_address']) {
                    $statusActivites .= $dataArray['reciever_address'] . " Reciver Address changed from " . $oldShipData['reciever_address'] . "<br>";
                }
                 if ($oldShipData['total_cod_amt'] != $dataArray['total_cod_amt']) {
                    $statusActivites .= $dataArray['total_cod_amt'] . " COD changed from " . $oldShipData['total_cod_amt'] . "<br>";
                }
                //echo $oldShipData['destination']."==".$dataArray['destination_id']; 
                if ($oldShipData['destination'] != $dataArray['destination_id']) {
                    $statusActivites .= $dataArray['destination'] . " Destination changed from " . getdestinationfieldshow($oldShipData['destination'], 'city') . "<br>";
                }
                if ($oldShipData['wh_id'] != $dataArray['whid']) {
                    $statusActivites .= Getwarehouse_categoryfield($dataArray['whid'], 'name') . " Warehouse changed from " . $dataArray['wh_id'] . "<br>";
                }



                //$DeletedArray=array_diff_assoc($oldSlipArray,$dataArray['skuData']);
                //print_r($DeletedArray); die;;
                //echo '<pre>';
                //print_r($dataArray['skuData']);
                //print_r($dataArray['skuData']);
                //$uniqueSku=array_unique($dataArray['skuData']);
                $newSkuArray = array();
                $totalCod = $dataArray['total_cod_amt'];


                // foreach ($dataArray['skuData'] as $key => $skuval) {
                //     $checkSku = getallitemskubyid($skuval['sku']);
                //     if ($checkSku > 0) {
                //         $DimationOldArray = GetdiaMationTableDataFind($dataArray['slip_no'], $skuval['sku']);
                //         //echo "sssss";
                //         $totalPices += $skuval['piece'];
                //         // $totalCod += $skuval['cod'];
                //         if (!empty($DimationOldArray)) {
                //             if ($DimationOldArray['cod'] != $skuval['cod'])
                //                 $statusActivites .= $skuval['cod'] . " COD changed from " . $DimationOldArray['cod'] . " " . $skuval['sku'] . " <br>";
                //             if ($DimationOldArray['piece'] != $skuval['piece'])
                //                 $statusActivites .= $skuval['piece'] . " Pieces changed from " . $DimationOldArray['piece'] . " " . $skuval['sku'] . " <br>";
                //             if ($DimationOldArray['sku'] != $skuval['sku'])
                //                 $statusActivites .= $skuval['sku'] . " SKU changed from " . $DimationOldArray['sku'] . " " . $skuval['sku'] . " <br>";
                //             $dimationArray[$key]['cod'] = $skuval['cod'];
                //             $dimationArray[$key]['piece'] = $skuval['piece'];
                //             $dimationArray[$key]['sku'] = $skuval['sku'];
                //             $dimationArray[$key]['id'] = $skuval['d_id'];
                //         } else {
                //             $statusActivites .= "New SKU Added " . $skuval['sku'] . "<br>";
                //             $dimationArray_added[] = array('slip_no' => $dataArray['slip_no'], 'sku' => $skuval['sku'], 'description' => addslashes(getalldataitemtablesBySku($skuval['sku'], 'description')), 'booking_id' => $dataArray['booking_id'], 'cod' => $skuval['cod'], 'piece' => $skuval['piece'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                //         }
                //     } else
                //         array_push($newSkuArray, $skuval['sku']);
                // }
                //echo $statusActivites; die;
                //echo '<pre>';
                //print_r($dimationArray); die;

                if (!empty($statusActivites)) {

                    $key88 = 0;
                    $statusvalue[$key88]['user_type'] = 'fulfillment';
                    $StatusArray[$key88]['slip_no'] = $dataArray['slip_no'];
                    $StatusArray[$key88]['new_status'] = 11;
                    $StatusArray[$key88]['pickup_time'] = date("H:i:s");
                    $StatusArray[$key88]['pickup_date'] = date("Y-m-d");
                    $StatusArray[$key88]['Details'] = addslashes($statusActivites);
                    $StatusArray[$key88]['Activites'] = addslashes($statusActivites);
                    $StatusArray[$key88]['entry_date'] = date("Y-m-d H:i:s");
                    $StatusArray[$key88]['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $StatusArray[$key88]['user_type'] = 'fulfillment';
                    $StatusArray[$key88]['code'] = 'OG';
                    $StatusArray[$key88]['super_id'] = $this->session->userdata('user_details')['super_id'];
                }


                $dimationArray_w = array('slip_no' => $dataArray['slip_no']);
                $shipemntArr = array('destination' => $dataArray['destination_id'], 'booking_id' => $dataArray['booking_id'], 'reciever_name' => $dataArray['reciever_name'], 'reciever_phone' => $dataArray['reciever_phone'], 'reciever_address' => $dataArray['reciever_address'], 'reciever_pincode' => $dataArray['reciever_pincode'], 'wh_id' => $dataArray['whid'],'total_cod_amt'=>$dataArray['total_cod_amt'],);
                $shipemntArr_w = array('id' => $dataArray['id'], 'slip_no' => $dataArray['slip_no']);
                ///print_r($StatusArray);
                //print_r($shipemntArr);
                //print_r($shipemntArr_w);

                $this->Shipment_model->GetUpdateDiamationQry($dimationArray, $dimationArray_w);
                $this->Shipment_model->GetUpdateShipmentEdit($shipemntArr, $shipemntArr_w);
                $this->Status_model->insertStatus($StatusArray);
                if (!empty($dimationArray_added)) {
                    $this->Shipment_model->GetDimationDatansertQry($dimationArray_added);
                }
                $return['status'] = 'succ';
            } else
                $return['status'] = 'booking_id';
        }
        echo json_encode($return);
    }

    function GetestinationDropData() {

        $city = getAllDestination();
        $warehouse = Getwarehouse_Dropdata();
        $return = array('city' => $city, 'warehouse' => $warehouse);
        echo json_encode($return);
    }

    function CreateGenratedOrderCheck() {

        $this->load->model('Pickup_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;
        // echo '<pre>';
        // print_r($dataArray);die;
        
         ignore_user_abort();
        if (!file_exists('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'])) {
            mkdir('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'], 0777, true);
        }
        $file = fopen('oclock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'] . '/'. ".lock", "w+");
        
         // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            // echo "Unable to obtain lock, the previous process is still going on."; 
            $stockarray = array('status' => 205, "Unable to obtain lock, the previous process is still going on.");

            
        }
        else
        {
        $picklistValue = array();
        $statusvalue = array();
        $errorReturn = array();

        $shipArray = array();

        $skuArray = array();
        $wbh_array = array();
        $key = 0;
        $entrydate = date("Y-m-d H:i:s");
        $time = date("H:i:s");

        $arar = $dataArray['slipData'];
        $ayte['listData'] = $this->Shipment_model->ShipData($arar);


        //  echo"<br><pre>"; 
        //    print_r($ayte['listData']); 
        //    die; 
        // foreach ($dataArray['listData'] as $data) {
        $SlipUpdates = array();
        $wbh_array_in = array();
        foreach ($ayte['listData'] as $data) {
            
            
           $cityNamenew= getdestinationfieldshow($data['destination'],'city');
            if ($data['origin'] > 0 && $data['destination'] > 0 && $data['wh_id'] > 0 && $data['backorder']==0 && $data['code'] == 'OG' && $data['pieces']>0  && !empty($cityNamenew) && $data['skubtnDs'] != 'Y') {


                $data['skuData'] = $this->Shipment_model->GetDiamationDetailsBYslipNo_og($data['slip_no']);


                $custmoerID = $data['cust_id'];
                $uniqueid = GetallCutomerBysellerId($custmoerID, 'uniqueid');
                $token = GetallCutomerBysellerId($custmoerID, 'manager_token');
                $salatoken = GetallCutomerBysellerId($custmoerID, 'salla_athentication');
                $WB_Confing_in = webhook_settingsTable_in($custmoerID);
                
                $stockarray = array();
                $ReturnstockArray = array();
                //  print_r($data['skuData']); die;
                if (!empty($data['skuData'])) {
                    $totalweight = 0;
                    $data['weight'] = 0;
                    foreach ($data['skuData'] as $new_key => $skuDetails) {
                        //      echo"<br><pre>"; 
                        //   print_r($data);
                        //die; 
                        $sku_main_arr=getalldataitemtablesSKU_single($skuDetails['sku']);
                        $skuDetails['sku_id']=$sku_main_arr['sku_id'];
                        $skuDetails['weight']=$sku_main_arr['weight'];
                        if($skuDetails['sku_id']>0 && $skuDetails['piece']>0)
                        {
                        $stock_check = CheckStockBackorder_ordergen($data['cust_id'], trim($skuDetails['sku']), $skuDetails['piece'], $data['slip_no'], $skuDetails['sku_id'], $data['wh_id']);

                        if ($stock_check['succ'] == 1) {
                            //array_push($ReturnstockArray,$stock_check['stArray']);
                            $ReturnstockArray[] = $stock_check['stArray'];

                            $newStocklocation[] = $stock_check['StockLocation'];
                            //echo $ReturnstockArray[0][0]['weight'] ;
                            $weightcount = $skuDetails['weight'];//$stock_check['stArray'][0]['weight'];
                            $totalweight = $totalweight + ($weightcount * $skuDetails['piece']);
                            $data['weight'] = $totalweight;


                            //die; 
                            //==========update zid stock===============//                     
                            if (!empty($token)) {
                                $zidReqArr = GetAllQtyforSeller($skuDetails['sku'], $custmoerID);

                                $quantity = $zidReqArr['quantity'] - $skuDetails['piece'];
                                $pid = $zidReqArr['zid_pid'];
                                $token = $token;
                                $storeID = $data['zid_store_id'];
                                update_zid_product($quantity, $pid, $token, $storeID, $custmoerID, $zidReqArr['sku']);
                            }

                            //==========update salla quantity===============//                        
                            if (!empty($salatoken)) {
                                 if($custmoerID!='214')
                                 {
                                $sallaReqArr = GetAllQtyforSeller($skuDetails['sku'], $custmoerID);
                                $quantity = $sallaReqArr['quantity'] - $skuDetails['piece']; //+$fArray['qty'];
                                $pid = $sallaReqArr['sku'];
                                $sallatoken = $salatoken;
                                // echo "<pre>"; print_r($sallaReqArr);
                                $reszid = update_salla_qty_product($quantity, $pid, $sallatoken, $custmoerID);
                                 }
                            }
                            //$custmoerID==214 || 
//                             if ($custmoerID==215) {
//                                $sallaReqArr = GetAllQtyforSeller($skuDetails['sku'], $custmoerID);
//                                $quantity = $sallaReqArr['quantity'] - $skuDetails['piece']; //+$fArray['qty'];
//                                $pid = $sallaReqArr['sku'];
//                                $sallatoken = $salatoken;
//                                // echo "<pre>"; print_r($sallaReqArr);
//                                $reszid = salla_provider_qty_update($quantity, $uniqueid,$pid,$custmoerID);
//                            }
                            //=========================================//
                             if ($WB_Confing_in['subscribe'] == 'Y') {
                               // $wbh_array = array();
                                $wb_request_in = array(
                                    'datetime' => date('Y-m-d H:i:s'),
                                    "sku" => $skuDetails['sku_id'],
                                    "cust_id" => $custmoerID,
                                    'sku_name'=>$skuDetails['sku'],
                                    "order_from" => "OC",
                                    "WB_Confing" => $WB_Confing_in,
                                    'comment'=>$data['slip_no']
                                );
                                array_push($wbh_array_in, $wb_request_in);
                            }
                            
                        } else {
                            // $newStocklocation=array();

                            $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku']);
                            array_push($stockarray, $errorReturnArray);
                        }
                    }
                    else
                    {
                            $errorReturnArray = array("slip_no" => $data['slip_no'], "sku" => $skuDetails['sku']);
                            array_push($stockarray, $errorReturnArray);   
                    }
                    }

                    // echo "<pre>"; print_r($data);
                    // die; 

                    if (empty($stockarray)) {

                        $WB_Confing = webhook_settingsTable($data['cust_id']);
                        if ($WB_Confing['subscribe'] == 'Y') {
                            $wb_request = array(
                                'datetime' => $entrydate,
                                "code" => 'OC',
                                "status" => 'Order Created',
                                "cc_name" => GetCourCompanynameId($data['frwd_company_id'], 'company'),
                                "cc_awb" => $data['frwd_company_awb'],
                                "cc_status" => null,
                                "cc_status_details" => null,
                                "slip_no" => $data['slip_no'],
                                "booking_id" => $data['booking_id'],
                                "cust_id" => $data['cust_id'],
                                "WB_Confing" => $WB_Confing
                            );
                            array_push($wbh_array, $wb_request);
                        }
                        
                        array_push($SlipUpdates, $data['slip_no']);
                        $statusvalue[$key]['user_type'] = 'fulfillment';
                        $StatusArray[$key]['slip_no'] = $data['slip_no'];
                        $StatusArray[$key]['new_status'] = 1;
                        $StatusArray[$key]['pickup_time'] = $time;
                        $StatusArray[$key]['pickup_date'] = $entrydate;
                        $StatusArray[$key]['Details'] = "Order Created";
                        $StatusArray[$key]['Activites'] = "Order Created";
                        $StatusArray[$key]['entry_date'] = $entrydate;
                        $StatusArray[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                        $StatusArray[$key]['user_type'] = 'fulfillment';
                        $StatusArray[$key]['code'] = 'OC';
                        $StatusArray[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                        $shipArray[$key]['slip_no'] = $data['slip_no'];
                        $shipArray[$key]['backorder'] = 0;
                        $shipArray[$key]['delivered'] = 1;
                        $shipArray[$key]['code'] = 'OC';
                        $shipArray[$key]['weight'] = $data['weight'];
                        $shipArray[$key]['created_at'] = $entrydate;

                        //$ReturnstockArray=  array_values(array_unique($ReturnstockArray));
                        UpdateStockBackorder_orderGen($ReturnstockArray, $newStocklocation);
                    }
                } else {
                    //  echo $key;
                    $invalidSkuArr = $this->Shipment_model->GetinVliadSkulist($data['slip_no']);
                    // print_r($invalidSkuArr);
                    $errorReturnArray = array("slip_no" => $data['slip_no'], "sku_invalid" => implode(',', $invalidSkuArr));
                    array_push($stockarray, $errorReturnArray);
                }



                $key++;
            }
        }
        
         flock($file,LOCK_UN);  
    }
    fclose($file);
        //    // die;
        //  echo '<pre>';
        //  print_r($ReturnstockArray);
        //  print_r($errorReturnArray);
        //   die;




        if (!empty($statusvalue) && !empty($shipArray)) {


            // die;

            $this->Shipment_model->updateStatusBatch($shipArray);
            $this->Status_model->insertStatus($StatusArray);
            $this->Shipment_model->AddtempProcess($SlipUpdates);

            if (!empty($wbh_array)) {
                $this->session->set_userdata(array('webhook_status' => $wbh_array));
            }
              if (!empty($wbh_array_in)) {
          
            $this->session->set_userdata(array('webhook_stock_arr' => $wbh_array_in));
             
        }
        }
        // die;

        echo json_encode($stockarray);
    }
    

    //    // die;
    //     echo '<pre>';
    //      print_r($shipArray);
    //      print_r($StatusArray);
    //      die;




    public function GetUpdatePalletNoData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $updatearray = array('stocklcount' => $_POST['pallet']);
        $return = $this->Shipment_model->GetupdatepalletnoShipment($updatearray, $_POST['slip_no']);
        echo json_encode($return);
    }

    function exportdispatchForLm() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exportlimit = $_POST['exportlimit'];
        $shipmentsexcel = $this->Shipment_model->filterexcel1($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;
        array_unshift($dataArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();

        $doc->getActiveSheet()->fromArray($dataArray);
        $from = "A1"; // or any value
        $to = "U1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Order No')
                ->setCellValue('B1', 'Awb No')
                ->setCellValue('C1', 'SKU')
                ->setCellValue('D1', 'Status')
                ->setCellValue('E1', 'Qty')
                ->setCellValue('F1', 'COD Amount')
                ->setCellValue('G1', 'Seller')
                ->setCellValue('H1', 'Entry Date')
                ->setCellValue('I1', 'Origin')
                ->setCellValue('J1', 'Destination')
                ->setCellValue('K1', 'Receiver Name')
                ->setCellValue('L1', 'Receiver Address')
                ->setCellValue('M1', 'Receiver Phone')
                ->setCellValue('N1', 'Sender Name')
                ->setCellValue('O1', 'Sender Address')
                ->setCellValue('P1', 'Sender Phone')
                ->setCellValue('Q1', 'Sender Email')
                ->setCellValue('R1', 'Payment Mode')
                ->setCellValue('S1', 'Total Cod Amount')
                ->setCellValue('T1', 'Weight')
                ->setCellValue('U1', 'Pieces');




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

    function exportPackedExcel() {
        //   echo 'ssss'; die;
        // error_reporting(-1);
		// ini_set('display_errors', 1);
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        //  print_r($_POST); die;
        $exportlimit = $_POST['exportlimit']; 
        $shipmentsexcel = $this->Shipment_model->filterexcelshipment($_POST);
        //die;
        $shiparray1 = $shipmentsexcel['result'];
       // echo json_encode($shiparray1); die;
        $ii = 0;
        $jj = 0;

        $this->load->library("excel");
        $doc = new PHPExcel();


        $from = "A1"; // or any value
        $to = "P1"; // or any value
        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);

        $doc->setActiveSheetIndex(0)
                ->setCellValueExplicit('A1', 'AWB No')
                ->setCellValueExplicit('B1', 'Ref. No.')
                ->setCellValueExplicit('C1', 'Origin')
                ->setCellValueExplicit('D1', 'Destination')
                ->setCellValueExplicit('E1', 'Receiver Name')
                ->setCellValueExplicit('F1', 'Receiver Address')
                ->setCellValueExplicit('G1', 'Receiver Phone')
                ->setCellValueExplicit('H1', 'Status')
                ->setCellValueExplicit('I1', 'Seller')
                ->setCellValueExplicit('J1', 'Entry Date')
                ->setCellValueExplicit('K1', 'SKU')
                ->setCellValueExplicit('L1', 'Qty')
                ->setCellValueExplicit('M1', 'COD')
                ->setCellValueExplicit('N1', 'Weight')
                ->setCellValueExplicit('O1', 'Deducted Shelve NO')
                ->setCellValueExplicit('P1', 'Country Name')
                  ->setCellValueExplicit('Q1', 'type');
        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        $originArray=array();
        $destinationArray=array();
        $destinationCountry=array();
        foreach ($dataArray as $data) {

            $skuDetails = $this->Shipment_model->get_deducted_shelve_no_details($data['slip_no']);

            if( empty($originArray[$data['origin']]))
            {
                $originArray[$data['origin']]= getdestinationfieldshow($data['origin'], 'city');
            }
            if( empty($destinationArray[$data['destination']]))
            {
                $destinationArray[$data['destination']]= getdestinationfieldshow($data['destination'], 'city');
            }
            if( empty($destinationCountry[$data['destination']]))
            {
                $destinationCountry[$data['destination']]= getdestinationfieldshow($data['destination'], 'country');
            }
            /* $DatafileArray[$i]['slip_no']=$data['slip_no'];
              $DatafileArray[$i]['Ref']=$data['booking_id'];
              $DatafileArray[$i]['origin']=$data['origin'];
              $DatafileArray[$i]['destination']=$data['destination'];
              $DatafileArray[$i]['reciever_name']=$data['reciever_name'];
              $DatafileArray[$i]['reciever_address']=$data['reciever_address'];
              $DatafileArray[$i]['reciever_phone']=$data['reciever_phone'];



              $DatafileArray[$i]['main_status']=$data['main_status'];

              $DatafileArray[$i]['name']=$data['name'];
              $DatafileArray[$i]['entrydate']=$data['entrydate']; */

            $jj = 0;
            foreach ($skuDetails as $val) {
                //echo $data['slip_no']."////".$jj."j<br>";
                //echo $data['slip_no']."////".$i."i<br>";

                $counter = $i + 2;

                $DatafileArray[$i]['slip_no'] = $data['slip_no'];
                $DatafileArray[$i]['Ref'] = $data['booking_id'];
                $DatafileArray[$i]['origin'] =  $originArray[$data['origin']];
                $DatafileArray[$i]['destination'] =  $destinationArray[$data['destination']];
                $DatafileArray[$i]['reciever_name'] = $data['reciever_name'];
                $DatafileArray[$i]['reciever_address'] = $data['reciever_address'];
                $DatafileArray[$i]['reciever_phone'] = $data['reciever_phone'];



                $DatafileArray[$i]['main_status'] = 'Created';

                //$DatafileArray[$i]['name'] = $data['name'];
                $DatafileArray[$i]['name'] = $data['company'];
                $DatafileArray[$i]['entrydate'] = $data['entrydate'];
                $DatafileArray[$i]['sku'] = $val['sku'];
                $DatafileArray[$i]['piece'] = $val['piece'];
                $DatafileArray[$i]['cod'] = $val['cod'];
                $DatafileArray[$i]['weight'] = $val['wieght'];
                $DatafileArray[$i]['deducted_shelve'] = $val['deducted_shelve'];
                $DatafileArray[$i]['country_name'] = $destinationCountry[$data['destination']];
                $DatafileArray[$i]['typeship'] = $val['typeship'];


                if ($jj > 0) {

                    //$doc->setActiveSheetIndex(0)->mergeCells('A'.$counter.':J'.$counter.'');
                }
                $jj++;
                $i++;
            }
        }

        array_unshift($DatafileArray, '');


        $doc->getActiveSheet()->fromArray($DatafileArray);

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

    function exportdispatchExcel() {
        //   echo "sssssssss"; die;
        // print_r($this->input->post()); die;
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        //  print_r($_POST);
        $exportlimit = $_POST['exportlimit'];
        $delivered = $_POST['status'];

        $shipmentsexcel = $this->Shipment_model->filterexceldispatch($_POST);
        // print_r($shipmentsexcel); die;
        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $destinationArray = array();
        $originArray = array();
        foreach ($shipmentsexcel['result'] as $rdata) {

            $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
            //if($rdata['order_type']=='')
            //{
            $itemID = getallitemskubyid($rdata['sku']);
            $itemtypes = getalldataitemtables($itemID, 'type');
            $shiparray1[$ii]['order_type'] = $itemtypes;

            //$shiparray[$ii]['order_type']="";
            //}
            //else
            //$shiparray[$ii]['order_type']=$rdata['order_type'];
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray1[$ii]['expire_details'] = $expire_data;
            if (empty($originArray[$rdata['origin']])) {
                $originArray[$rdata['origin']] = getdestinationfieldshow($rdata['origin'], 'city');
            }
            $shiparray1[$ii]['origin'] = $originArray[$rdata['origin']]; //getdestinationfieldshow($rdata['origin'], 'city');
            if (empty($destinationArray[$rdata['destination']])) {
                $destinationArray[$rdata['destination']] = getdestinationfieldshow($rdata['destination'], 'city');
            }
            $shiparray1[$ii]['destination'] = $destinationArray[$rdata['destination']]; //getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);
            $shiparray1[$ii]['country_name'] = getdestinationfieldshow($rdata['destination'], 'country');
            //$shiparray1[$ii]['company']=getcompanyfieldshow($rdata['frwd_company_id'],'company');     
            //$shiparray1[$ii]['filfilment_partner_id']=get_fulfillment_partner_name($rdata['filfilment_partner_id']);     
            //$shiparray='rith';
            $ii++;
        }

        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {
            $DatafileArray[$i]['slip_no'] = $data['slip_no'];
            $DatafileArray[$i]['Ref'] = $data['booking_id'];
            $DatafileArray[$i]['origin'] = $data['origin'];
            $DatafileArray[$i]['destination'] = $data['destination'];
            $DatafileArray[$i]['reciever_name'] = $data['reciever_name'];
            $DatafileArray[$i]['reciever_address'] = $data['reciever_address'];
            $DatafileArray[$i]['reciever_phone'] = $data['reciever_phone'];
            $DatafileArray[$i]['sku'] = $data['sku'];
            $DatafileArray[$i]['piece'] = $data['piece'];
            $DatafileArray[$i]['cod'] = $data['cod'];
            $DatafileArray[$i]['wt'] = $data['wt'];
            $DatafileArray[$i]['deducted_shelve'] = $data['deducted_shelve'];

            $DatafileArray[$i]['main_status'] = $data['main_status'];

            $DatafileArray[$i]['name'] = $data['company'];
            $DatafileArray[$i]['entrydate'] = $data['entrydate'];
            $DatafileArray[$i]['country_name'] = $data['country_name'];
            $DatafileArray[$i]['typeship'] = $data['typeship'];
            /* $skuDetails= $this->Shipment_model->get_deducted_shelve_no($data['slip_no']); 
              foreach($skuDetails as $key1=>$val)
              {
              $DatafileArray[$i]['sku']=$val->sku;
              }
             */

            $i++;
        }

        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();



        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "P1"; // or any value
        //	$objPHPExcel->getActiveSheet()->setTitle('Name of Sheet 1');

        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'AWB No')
                ->setCellValue('B1', 'Ref. No.')
                ->setCellValue('C1', 'Origin')
                ->setCellValue('D1', 'Destination')
                ->setCellValue('E1', 'Receiver Name')
                ->setCellValue('F1', 'Receiver Address')
                ->setCellValue('G1', 'Receiver Phone')
                ->setCellValue('H1', 'SKU')
                ->setCellValue('I1', 'Qty')
                ->setCellValue('J1', 'COD')
                ->setCellValue('K1', 'Weight')
                ->setCellValue('L1', 'Deducted Shelve NO')
                ->setCellValue('M1', 'Status')
                ->setCellValue('N1', 'Seller')
                ->setCellValue('O1', 'Entry Date')
                ->setCellValue('P1', 'Destination Country')
                ->setCellValue('Q1', 'Type');
        ///	->mergeCells('P1:Q1','Sku Details');



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

    function exportExcel() {
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 12000);
        $_POST = json_decode(file_get_contents('php://input'), true);
        $exportlimit = $_POST['exportlimit'];
        $delivered = $_POST['status'];


        $shipmentsexcel = $this->Shipment_model->filterexcel1($_POST);

        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;


        foreach ($shipmentsexcel['result'] as $rdata) {

            $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
            //if($rdata['order_type']=='')
            //{
            $itemID = getallitemskubyid($rdata['sku']);
            $itemtypes = getalldataitemtables($itemID, 'type');
            $shiparray1[$ii]['order_type'] = $itemtypes;

            //$shiparray[$ii]['order_type']="";
            //}
            //else
            //$shiparray[$ii]['order_type']=$rdata['order_type'];
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray1[$ii]['expire_details'] = $expire_data;
            $shiparray1[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray1[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            //$shiparray1[$ii]['company']=getcompanyfieldshow($rdata['frwd_company_id'],'company');     
            //$shiparray1[$ii]['filfilment_partner_id']=get_fulfillment_partner_name($rdata['filfilment_partner_id']);     
            //$shiparray='rith';
            $ii++;
        }

        $dataArray['result'] = $shiparray1;
        echo json_encode($dataArray);
    }

    function backoredrexcel() {
        ini_set('memory_limit', '5000000M');
        ini_set('max_execution_time', 1200);
        $_POST = json_decode(file_get_contents('php://input'), true);
        //  print_r($_POST);
        $exportlimit = $_POST['exportlimit'];
        $delivered = $_POST['status'];

        $shipmentsexcel = $this->Shipment_model->backoredrexcel($_POST);
        // print_r($shipmentsexcel); die;
        $shiparray1 = $shipmentsexcel['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;


        foreach ($shipmentsexcel['result'] as $rdata) {

            $expire_data = $this->Shipment_model->GetallexpredataQuery($rdata['seller_id'], $rdata['sku']);
            //if($rdata['order_type']=='')
            //{
            $itemID = getallitemskubyid($rdata['sku']);
            $itemtypes = getalldataitemtables($itemID, 'type');
            $shiparray1[$ii]['order_type'] = $itemtypes;

            //$shiparray[$ii]['order_type']="";
            //}
            //else
            //$shiparray[$ii]['order_type']=$rdata['order_type'];
            //if($expire_data[$ii]['sku']==$rdata['sku'])
            $shiparray1[$ii]['expire_details'] = $expire_data;
            $shiparray1[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray1[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            //  $shiparray[$ii]['deducted_shelve_no'] = $this->Shipment_model->get_deducted_shelve_no($rdata['slip_no']);
            //$shiparray1[$ii]['company']=getcompanyfieldshow($rdata['frwd_company_id'],'company');     
            //$shiparray1[$ii]['filfilment_partner_id']=get_fulfillment_partner_name($rdata['filfilment_partner_id']);     
            //$shiparray='rith';
            $ii++;
        }

        $dataArray = $shiparray1;

        $DatafileArray = array();
        $i = 0;
        foreach ($dataArray as $data) {
            $DatafileArray[$i]['slip_no'] = $data['slip_no'];
            $DatafileArray[$i]['Ref'] = $data['booking_id'];
            $DatafileArray[$i]['origin'] = $data['origin'];
            $DatafileArray[$i]['destination'] = $data['destination'];
            $DatafileArray[$i]['reciever_name'] = $data['reciever_name'];
            $DatafileArray[$i]['reciever_address'] = $data['reciever_address'];
            $DatafileArray[$i]['reciever_phone'] = $data['reciever_phone'];
            $DatafileArray[$i]['sku'] = $data['sku'];
            $DatafileArray[$i]['piece'] = $data['piece'];
            $DatafileArray[$i]['cod'] = $data['cod'];
            $DatafileArray[$i]['wt'] = $data['wt'];
            $DatafileArray[$i]['back_reason'] = $data['back_reason'];

            $DatafileArray[$i]['main_status'] = $data['main_status'];

            $DatafileArray[$i]['name'] = $data['company'];
            $DatafileArray[$i]['entrydate'] = $data['entrydate'];
             $DatafileArray[$i]['typeship'] = $data['typeship'];
            /* $skuDetails= $this->Shipment_model->get_deducted_shelve_no($data['slip_no']); 
              foreach($skuDetails as $key1=>$val)
              {
              $DatafileArray[$i]['sku']=$val->sku;
              }
             */

            $i++;
        }

        array_unshift($DatafileArray, '');
        $this->load->library("excel");
        $doc = new PHPExcel();



        $doc->getActiveSheet()->fromArray($DatafileArray);
        $from = "A1"; // or any value
        $to = "O1"; // or any value
        //	$objPHPExcel->getActiveSheet()->setTitle('Name of Sheet 1');

        $doc->getActiveSheet()->getStyle("$from:$to")->getFont()->setBold(true);
        $doc->setActiveSheetIndex(0)
                ->setCellValue('A1', 'AWB No')
                ->setCellValue('B1', 'Ref. No.')
                ->setCellValue('C1', 'Origin')
                ->setCellValue('D1', 'Destination')
                ->setCellValue('E1', 'Receiver Name')
                ->setCellValue('F1', 'Receiver Address')
                ->setCellValue('G1', 'Receiver Phone')
                ->setCellValue('H1', 'SKU')
                ->setCellValue('I1', 'Qty')
                ->setCellValue('J1', 'COD')
                ->setCellValue('K1', 'Weight')
                ->setCellValue('L1', 'Back Reason')
                ->setCellValue('M1', 'Status')
                ->setCellValue('N1', 'Seller')
                ->setCellValue('O1', 'Entry Date')
                ->setCellValue('P1', 'Type');
        ///	->mergeCells('P1:Q1','Sku Details');



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

    function generatePickup() {
        // echo "ddd"; die;
        $this->load->model('Pickup_model');
        $_POST = json_decode(file_get_contents('php://input'), true);
//print_r($_POST); die;
//echo json_encode($response); die;
        $dataArray = $_POST;
        $picker_id = $dataArray['picker'];
        $uid = strtoupper(uniqid());
        $picklistValue = array();
        $statusvalue = array();
        $wbh_array = array();
        $key = 0;
        
        ignore_user_abort();
        if (!file_exists('picklock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'])) {
            mkdir('picklock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'], 0777, true);
        }
        $file = fopen('picklock/' . date('Y-m-d') . '/' . $this->session->userdata('user_details')['super_id'] . '/' . $this->session->userdata('user_details')['super_id'] . '.lock', "w+");

        // exclusive lock, LOCK_NB serves as a bitmask to prevent flock() to block the code to run while the file is locked.
        // without the LOCK_NB, it won't go inside the if block to echo the string
        if (!flock($file, LOCK_EX | LOCK_NB)) {
            // echo "Unable to obtain lock, the previous process is still going on."; 
            $stockarray = array('status' => 205, "Unable to obtain lock, the previous process is still going on.");
        } 
        else
        {
            
        $in_valid = array();
        $valid = array();
            
//        print "<pre>"; print_r($dataArray['listData']);die;
        $super_id = $this->session->userdata('user_details')['super_id'];
        
        foreach ($dataArray['listData'] as $data) {
            
            if($super_id == 333){
                
                $check_valid = GetCheckDeliveredStatus($data['slip_no']);
                if(!empty($check_valid)){

                            $check_exist = GetCheckPickupStatus($data['slip_no']);
                            if (empty($check_exist) && $data['on_hold']=='No') {


                                array_push($valid, $data['slip_no']);
                                $skuData = $this->Shipment_model->GetpicklistGenrateSkuDetails(trim($data['slip_no']));
                                /* -------------Picklist Array----------- */
                                $picklistValue[$key]['pickupId'] = $uid;
                                $picklistValue[$key]['slip_no'] = $data['slip_no'];
                                $picklistValue[$key]['destination'] = $data['destination'];
                                $picklistValue[$key]['origin'] = $data['origin'];
                                $picklistValue[$key]['reciever_name'] = $data['reciever_name'];
                                $picklistValue[$key]['reciever_address'] = $data['reciever_address'];
                                $picklistValue[$key]['reciever_phone'] = $data['reciever_phone'];


                                $picklistValue[$key]['sender_address'] = $data['sender_address'];
                                $picklistValue[$key]['sender_name'] = $data['sender_name'];
                                $picklistValue[$key]['total_cod_amt'] = $data['total_cod_amt'];
                                $picklistValue[$key]['sender_phone'] = $data['sender_phone'];
                                $picklistValue[$key]['mode'] = $data['mode'];
                                $picklistValue[$key]['weight'] = $data['weight'];
                                if (!empty($picker_id)) {
                                    $picklistValue[$key]['assigned_to'] = $picker_id;
                                }
                                if (!empty($data['frwd_company_label'])) {
                                    $picklistValue[$key]['print_url'] = $data['frwd_company_label'];
                                } else {
                                    $picklistValue[$key]['print_url'] = base_url() . 'PrintPacking/' . $data['slip_no'];
                                }
                                $picklistValue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                                if (!empty($data['wh_ids']))
                                    $picklistValue[$key]['wh_id'] = $data['wh_ids'];
                                else
                                    $picklistValue[$key]['wh_id'] = 0;

                                $picklistValue[$key]['exp_details'] = "";
                                $picklistValue[$key]['sku'] = json_encode($skuData);
                                $picklistValue[$key]['piece'] = $data['piece'];
                                $picklistValue[$key]['entrydate'] = date('Y-m-d H:i');
                                /* -------------/Picklist Array----------- */
                                //`user_id`, `user_type`, `slip_no`, `new_location`, `city_code`, `new_status`, `code`, `pickup_time`, `pickup_date`, `Activites`, `Details`, `comment`, `entry_date`,
                                /* -------------Status Array----------- */
                                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                                $statusvalue[$key]['user_type'] = 'fulfillment';
                                $statusvalue[$key]['slip_no'] = $data['slip_no'];
                                if (!empty($picker_id)) {
                                    $status_w = 'Assigning To Picker';
                                    $code_w = 'AP';
                                    $statusvalue[$key]['new_status'] = 3;
                                    $statusvalue[$key]['code'] = 'AP';

                                    $statusvalue[$key]['Activites'] = 'Assigning To Picker : ' . getUserNameById($picker_id);
                                    $statusvalue[$key]['Details'] = 'Assigning To Picker : ' . getUserNameById($picker_id);
                                } else {
                                    $status_w = 'Picklist Generated';
                                    $code_w = 'PG';
                                    $statusvalue[$key]['new_status'] = 2;
                                    $statusvalue[$key]['code'] = 'PG';

                                    $statusvalue[$key]['Activites'] = 'Pick List Generated';
                                    $statusvalue[$key]['Details'] = 'Pick List Generated';
                                }
                                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                                /* -------------/Status Array----------- */

                                $WB_Confing = webhook_settingsTable($data['cust_id']);
                                if ($WB_Confing['subscribe'] == 'Y') {
                                    $wb_request = array(
                                        'datetime' => date('Y-m-d H:i'),
                                        "code" => $code_w,
                                        "status" => $status_w,
                                        "cc_name" => $data['cc_name'],
                                        "cc_awb" => $data['frwd_company_awb'],
                                        "cc_status" => null,
                                        "cc_status_details" => null,
                                        "slip_no" => $data['slip_no'],
                                        "booking_id" => $data['booking_id'],
                                        "cust_id" => $data['cust_id'],
                                        "WB_Confing" => $WB_Confing
                                    );
                                    array_push($wbh_array, $wb_request);
                                }



                                $key++;
                            }else{
                                array_push($in_valid, $data['slip_no']);
                            }
                }else{
                    array_push($in_valid, $data['slip_no']);
                }
                
                
            }else{
                if (empty($check_exist) && $data['on_hold']=='No') {

                            $skuData = $this->Shipment_model->GetpicklistGenrateSkuDetails(trim($data['slip_no']));
                            /* -------------Picklist Array----------- */
                            $picklistValue[$key]['pickupId'] = $uid;
                            $picklistValue[$key]['slip_no'] = $data['slip_no'];
                            $picklistValue[$key]['destination'] = $data['destination'];
                            $picklistValue[$key]['origin'] = $data['origin'];
                            $picklistValue[$key]['reciever_name'] = $data['reciever_name'];
                            $picklistValue[$key]['reciever_address'] = $data['reciever_address'];
                            $picklistValue[$key]['reciever_phone'] = $data['reciever_phone'];


                            $picklistValue[$key]['sender_address'] = $data['sender_address'];
                            $picklistValue[$key]['sender_name'] = $data['sender_name'];
                            $picklistValue[$key]['total_cod_amt'] = $data['total_cod_amt'];
                            $picklistValue[$key]['sender_phone'] = $data['sender_phone'];
                            $picklistValue[$key]['mode'] = $data['mode'];
                            $picklistValue[$key]['weight'] = $data['weight'];
                            if (!empty($picker_id)) {
                                $picklistValue[$key]['assigned_to'] = $picker_id;
                            }
                            if (!empty($data['frwd_company_label'])) {
                                $picklistValue[$key]['print_url'] = $data['frwd_company_label'];
                            } else {
                                $picklistValue[$key]['print_url'] = base_url() . 'PrintPacking/' . $data['slip_no'];
                            }
                            $picklistValue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

                            if (!empty($data['wh_ids']))
                                $picklistValue[$key]['wh_id'] = $data['wh_ids'];
                            else
                                $picklistValue[$key]['wh_id'] = 0;

                            $picklistValue[$key]['exp_details'] = "";
                            $picklistValue[$key]['sku'] = json_encode($skuData);
                            $picklistValue[$key]['piece'] = $data['piece'];
                            $picklistValue[$key]['entrydate'] = date('Y-m-d H:i');
                            /* -------------/Picklist Array----------- */
                            //`user_id`, `user_type`, `slip_no`, `new_location`, `city_code`, `new_status`, `code`, `pickup_time`, `pickup_date`, `Activites`, `Details`, `comment`, `entry_date`,
                            /* -------------Status Array----------- */
                            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                            $statusvalue[$key]['user_type'] = 'fulfillment';
                            $statusvalue[$key]['slip_no'] = $data['slip_no'];
                            if (!empty($picker_id)) {
                                $status_w = 'Assigning To Picker';
                                $code_w = 'AP';
                                $statusvalue[$key]['new_status'] = 3;
                                $statusvalue[$key]['code'] = 'AP';

                                $statusvalue[$key]['Activites'] = 'Assigning To Picker : ' . getUserNameById($picker_id);
                                $statusvalue[$key]['Details'] = 'Assigning To Picker : ' . getUserNameById($picker_id);
                            } else {
                                $status_w = 'Picklist Generated';
                                $code_w = 'PG';
                                $statusvalue[$key]['new_status'] = 2;
                                $statusvalue[$key]['code'] = 'PG';

                                $statusvalue[$key]['Activites'] = 'Pick List Generated';
                                $statusvalue[$key]['Details'] = 'Pick List Generated';
                            }
                            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
                            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                            /* -------------/Status Array----------- */

                            $WB_Confing = webhook_settingsTable($data['cust_id']);
                            if ($WB_Confing['subscribe'] == 'Y') {
                                $wb_request = array(
                                    'datetime' => date('Y-m-d H:i'),
                                    "code" => $code_w,
                                    "status" => $status_w,
                                    "cc_name" => $data['cc_name'],
                                    "cc_awb" => $data['frwd_company_awb'],
                                    "cc_status" => null,
                                    "cc_status_details" => null,
                                    "slip_no" => $data['slip_no'],
                                    "booking_id" => $data['booking_id'],
                                    "cust_id" => $data['cust_id'],
                                    "WB_Confing" => $WB_Confing
                                );
                                array_push($wbh_array, $wb_request);
                            }



                            $key++;
                        }
            }
            
                        
            
        }

        
        $shipData = array();
        if (!empty($picker_id)) {
            $updateArray = array(
                'code' => 'AP',
                'delivered' => 3
            );
        } else {
            $updateArray = array(
                'code' => 'PG',
                'delivered' => 2
            );
        }

        if(!empty($valid) && $super_id == 333){
            $shipData['where_in'] = $valid;//$dataArray['slipData'];
            $shipData['update'] = $updateArray;
    //print_r($picklistValue); die;
            // echo $this->Pickup_model->generatePicup($picklistValue); exit();
            if ($this->Pickup_model->generatePicup($picklistValue)) {
                if ($this->Status_model->insertStatus($statusvalue)) {
                    $this->Shipment_model->updateStatus($shipData);
                    if (!empty($wbh_array)) {
                        $this->session->set_userdata(array('webhook_status' => $wbh_array));

                        }
                }
            }
        }else{
            $shipData['where_in'] = $dataArray['slipData'];
            $shipData['update'] = $updateArray;
            if ($this->Pickup_model->generatePicup($picklistValue)) {
                if ($this->Status_model->insertStatus($statusvalue)) {
                    $this->Shipment_model->updateStatus($shipData);
                    if (!empty($wbh_array)) {
                        $this->session->set_userdata(array('webhook_status' => $wbh_array));

                        }
                }
            }
            
        }
        
        
        flock($file, LOCK_UN);
        
    }
    fclose($file);
    echo json_encode($stockarray);
    }

    public function getlivechartallshipment() {

        $site_id = $this->input->post('id');

        $sql = "SELECT * FROM shipment where deleted='N' limit 50";
        $query = $this->db->query($sql);
        if ($query->result_array()) {
            $date1 = "";
            //date_default_timezone_set('Romania/Bucharest');
            foreach ($query->result_array() as $rowdata) {
                $date = strtotime($rowdata['entrydate']) * 1;
                $total_time = time();


                $return["All"][] = array("0" => $date, "1" => $total_time, "color" => "#4CD185", "yy" => date('Y', $date) * 1, "mm" => date('m', $date) - 1, "dd" => date('d', $date) * 1, "hh" => date('H', $date) * 1, "ii" => date('i', $date) * 1, "ss" => date('s', $date) * 1);
            }


            echo json_encode($return);
        }
    }

    public function menifest_create() {

        $post = json_decode(file_get_contents('php://input'), true);
        $slip_array = $post;
        if ($post) {



            $shipmentCOmpanyData = $this->Shipment_model->GetmatchmanifestData_companydata($slip_array);

            if (!empty($shipmentCOmpanyData)) {
                $selectedIds = array();
                foreach ($shipmentCOmpanyData as $key1 => $val) {
                    // $manifest_id = strtoupper(uniqid());
                    array_push($selectedIds, $val['frwd_company_id']);
                }

                foreach ($selectedIds as $key => $cc_id) {
                    $manifest_id = strtoupper(uniqid());
                    $shipmentData = $this->Shipment_model->GetmatchmanifestData($cc_id, $slip_array);
                    foreach ($shipmentData as $key99 => $data) {
                        $in_data[$key99]['m_id'] = $manifest_id;
                        $in_data[$key99]['slip_no'] = $data['slip_no'];
                        $in_data[$key99]['fwd_awb'] = $data['frwd_company_awb'];
                        $in_data[$key99]['fwd_company'] = $data['frwd_company_id'];
                        $in_data[$key99]['origin'] = $data['destination'];
                        $in_data[$key99]['destination'] = $data['destination'];
                        $in_data[$key99]['wh_id'] = $data['wh_id'];
                        $in_data[$key99]['super_id'] = $this->session->userdata('user_details')['super_id'];

                        $udata[$key99]['is_menifest'] = 1;
                        $udata[$key99]['menifest_date'] = date('Y-m-d H:i:s');
                        $udata[$key99]['slip_no'] = $data['slip_no'];
                    }
                }
                // echo print_r($selectedIds);
                //print_r($in_data);
                // die;
                //print_r($in_data); die;
                if (!empty($in_data) && !empty($udata)) {
                    $this->Shipment_model->createManifest($in_data);
                    $this->Shipment_model->updateStatusBatch($udata);
                    $return = 'success';
                }
            } else {
                $return = 'fail';
            }
        } else {
            $return = 'fail';
        }

        echo json_encode($return);
    }

    public function manifest_filter() {
        // print("heelo"); 
        // exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;
        //       error_reporting(E_ALL);
//ini_set('display_errors', '1');
        $post = json_decode(file_get_contents('php://input'), true);

        $page_no = $post['page_no'];


        $shipments = $this->Shipment_model->manifestFilter($post, $page_no);

        $shiparray = $shipments['result'];

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
        foreach ($shipments['result'] as $rdata) {

            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['fwd_company'], 'company');
            $ii++;
        }


        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];

        echo json_encode($dataArray);
    }

    public function manifestListFilter() {
//ini_set('display_errors', '1');
        //      ini_set('display_startup_errors', '1');
        //    error_reporting(E_ALL);
        $post = json_decode(file_get_contents('php://input'), true);

        $page_no = $post['page_no'];
        $filter_data = array();
        if (isset($post['s_type']) && $post['s_type'] == 'AWB') {
            $post['slip_no'] = $post['s_type_val'];
        }


        if (isset($post['s_type']) && $post['s_type'] == 'SKU') {
            $post['sku'] = $post['s_type_val'];
        }
        unset($post['s_type_val']);
        unset($post['s_type']);

        $shipments = $this->Shipment_model->manifestListFilter($post, $page_no);

        $shiparray = $shipments['result'];
        $ii = 0;
        foreach ($shipments['result'] as $rdata) {
            $shiparray[$ii]['booking_id'] = GetshpmentDataByawb($rdata['slip_no'], 'booking_id');

            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');

            $shiparray[$ii]['sku'] = $this->Shipment_model->getSkuDataByAwb($rdata['slip_no']);

            $ii++;
        }

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function manifestPrint($id) {

        $this->load->library('M_pdf');



        $shipment = $this->Shipment_model->getDeliveryManifest($id);


        $datap['shipment'] = $shipment;

        $html = $this->load->view('ShipmentM/manifestPrint', $datap, true);

        //echo $html;die;
        //$mpdf = new mPDF('utf-8', array(101, 152), 0, '', 0, 0, 0, 0, 0, 0);
        $mpdf = new mPDF('utf-8', 'A4');
        $mpdf->WriteHTML($html);
        //$mpdf->SetDisplayMode('fullpage'); 
        //$mpdf->Output();
        $mpdf->Output('Manifest_print.pdf', 'I');
    }

    public function getexceldataOrderCreated() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);
        //$columns = implode(",",$_POST );
        $data = $_POST['listData2'];
        $data1 = $_POST['filterData'];
        //    foreach ($_POST['listData2'] as $name => $val)
        //    {
        //        array_push($data,$name); 
        //    }

        $dataAray = $this->Shipment_model->alllistexcelDataOrderCreated($data, $_POST['filterData']);

        $file_name = 'Order Generated.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

    public function getexceldataOrderReturned() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);
        //$columns = implode(",",$_POST );
        $data = $_POST['listData2'];
        $data1 = $_POST['filterData'];
        //    foreach ($_POST['listData2'] as $name => $val)  
        //    {
        //        array_push($data,$name);    
        //    }

        $dataAray = $this->Shipment_model->alllistexcelDataOrderReturned($data, $_POST['filterData'], $_POST['status']);

        $file_name = 'Shipment Details.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

    public function getexceldataOrderPacked() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);
        //$columns = implode(",",$_POST );
        $data = $_POST['listData2'];
        $data1 = $_POST['filterData'];
        //    foreach ($_POST['listData2'] as $name => $val)  
        //    {
        //        array_push($data,$name);    
        //    }

        $dataAray = $this->Shipment_model->alllistexcelDataOrderPacked($data, $_POST['filterData'], $_POST['status']);

        $file_name = 'Order Packed Details.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

    public function getexceldata() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);
        //$columns = implode(",",$_POST );
        $data = $_POST['listData2'];
        $data1 = $_POST['filterData'];
        //    foreach ($_POST['listData2'] as $name => $val)
        //    {
        //        array_push($data,$name); 
        //    }

        $dataAray = $this->Shipment_model->alllistexcelData($data, $_POST['filterData']);

        $file_name = 'shipments.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

    public function GetProcessOpenOrder() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $slip_no = $postData['slip_no'];
        if (!empty($slip_no)) {
            $key = 0;
            $dataAray = $this->Shipment_model->GetopenOrderProcessCheckQry($slip_no);
            if ($dataAray['code'] == 'PK') {

                
                //===================remove charge================//
                $removeExtraCharges[$key]['slip_no'] = $dataAray['slip_no'];
                $removeExtraCharges[$key]['packaging_charge'] = 0;
                $removeExtraCharges[$key]['picking_charge'] = 0;
                $removeExtraCharges[$key]['special_packing_charge'] = 0;
                $removeExtraCharges[$key]['box_charge'] = 0;
            }

              $picked_status=GetcheckPickingOrder($dataAray['slip_no']);

            //=========shipment update===================//

            $shipupdateAray[$key]['code'] = 'OC';
            if($picked_status=='Y')
            {
            $shipupdateAray[$key]['open_stock'] ='1';
            }
            $shipupdateAray[$key]['delivered'] = '1';
            $shipupdateAray[$key]['slip_no'] = $dataAray['slip_no'];
            //======================status update====================//

            $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
            $statusvalue[$key]['user_type'] = 'fulfillment';
            $statusvalue[$key]['slip_no'] = $dataAray['slip_no'];
            $statusvalue[$key]['new_status'] = 1;
            $statusvalue[$key]['code'] = 'OC';
            $statusvalue[$key]['Activites'] = 'Shipment Removed From Picklist';
            $statusvalue[$key]['Details'] = 'Shipment Removed by ' . getUserNameById($this->session->userdata('user_details')['user_id']);
            $statusvalue[$key]['entry_date'] = date('Y-m-d H:i');
            $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];

            //=================remove stock location ===================//
            //$removeStockLocation[$key]['slip_no'] = $dataAray['slip_no'];
            // $removeStockLocation[$key]['deleted'] = 'N';
            //==========================================================//
            //===============stock history====================//
            //  $inventoryHistory[$key]['']
            //=============inventory add===================//


            if (!empty($shipupdateAray)) {
                $request = $this->Shipment_model->updateStatusBatch($shipupdateAray);
                $this->Shipment_model->DeleteUpdateShipmentData($statusvalue);
                if ($dataAray['code'] == 'PG' || $dataAray['code'] == 'AP' || $dataAray['code'] == 'PK') {
                    $this->Shipment_model->DeletePickListProcess($dataAray['slip_no']);
                }
                if (!empty($removeExtraCharges)) {
                    $this->Shipment_model->RemmoveChargesDeleteOrder($removeExtraCharges);
                }
//                 if(!empty($addinventory))
//                 {
//                  $this->ItemInventory_model->add($addinventory, 'Add');
//                 }
//                 if(!empty($removeStockLocation))
//                 {
//                 $this->Shipment_model->RemoveStockLocation($removeStockLocation);
//                 }
            }
        }
        echo json_encode($postData);
    }

    public function forward_remove() {
        if (menuIdExitsInPrivilageArray(143) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/bulk_forward_remove', $bulk);
    }

    public function bulk_tracking() {
        // print "<pre>"; print_r($LeftFmenuArr);die;
        if (menuIdExitsInPrivilageArray(137) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/bulk_tracking', $bulk);
    }

    public function BulkTrackingsbt() {



        $show_awb_no = trim($this->input->post('show_awb_no'));
        if (!empty($show_awb_no)) {
            //echo $show_awb_no;die;  
            $SlipNos = preg_replace('/\s+/', ',', $show_awb_no);
            $slip_arr = explode(",", $SlipNos);
            $slipData = array_unique($slip_arr);
            $data['traking_awb_no'] = $slipData;
            $data['shipmentdata'] = $this->Shipment_model->getawbdataquery($slipData);
        }
        if (!empty($data['shipmentdata'])) {
            //print_r($data['shipmentdata']); die;
            $this->load->view('ShipmentM/trackingresult', $data);
        } else {
            $this->session->set_flashdata('error', 'please enter valid order no.');
            redirect(base_url() . 'bulk_tracking');
        }
        // print_r($slipData); die;
    }

    public function getexceldatatracking() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        // print_r($_POST); die;
        //$columns = implode(",",$_POST );
        $data = $_POST['listData2'];
        $SlipNos = $_POST['slip_nos'];
        $dataAray = $this->Shipment_model->trackinglistexcelData($data, $SlipNos);

        $file_name = 'tracking_shipments.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($dataAray)
        );

        $actdetails = "Tracking Export";
        $logstattus = "Tracking";
        $s_type = "FM";
        $filter_json = json_encode($_POST);
        $logArray = array('user_id' => $this->session->userdata('user_details')['user_id'], 'details' => $actdetails, 'status' => $logstattus, 'ip_address' => $_SERVER['REMOTE_ADDR'], 'super_id' => $this->session->userdata('user_details')['super_id'],'s_type'=>$s_type,'log_details'=>$filter_json,'entrydate'=>date('Y-m-d H:i:s'));
        $this->db->insert('activities_log', $logArray);


        echo json_encode($response);
    }

    public function ViewShipmentMapping() {
        if (menuIdExitsInPrivilageArray(145) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/view_shipment_mapping');
    }

    public function filterMapping() {

        $_POST = json_decode(file_get_contents('php://input'), true);



        $page_no = $_POST['page_no'];
        $cc_id = $_POST['cc_id'];

        $mappingData = $this->Shipment_model->filterViewMApping($page_no, $cc_id, $_POST);


        $maparray = array();
        $i = 0;
        foreach ($mappingData['result'] as $rdata) {
            $maparray[$i] = $rdata;
            $maparray[$i]['cc_name'] = GetCourCompanynameId($rdata['cc_id'], 'company');
            $maparray[$i]['status'] = ($rdata['status'] == 1) ? 'Active' : 'De-Active';
            $i++;
        }

        $dataArray['result'] = $maparray;
        $dataArray['count'] = $mappingData['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function addNewMapping() {
        $this->load->view('ShipmentM/add_shipment_mapping');
    }

    public function saveMapping() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $returnArr = array();
        if (empty($postData['cc_id'])) {
            $returnArr['responseError'][] = 'Please select Company Name';
        }
        if (empty($postData['map_data'])) {
            $returnArr['responseError'][] = 'Please enter Mapping Data';
        }
        if (!empty($returnArr)) {
            echo json_encode($returnArr);
            exit;
        }
        $dataCount = $this->Shipment_model->checkMappingCompany($postData);
        if ($dataCount > 0) {
            $returnArr['responseError'][] = 'This Company already have mapping';
            echo json_encode($returnArr);
            exit;
        }

        $this->Shipment_model->saveMappingData($postData);
        $returnArr = array('status' => "succ", "Success_msg" => 'Mapping saved successfully');
        echo json_encode($returnArr);
        exit;
    }

    public function updateMapping() {

        $postData = $_REQUEST;
        if (!empty($postData['cc_id']) && $postData['map_data']) {
            $id = $postData['id'];
            unset($postData['id']);
            $this->Shipment_model->updateMappingData($postData, $id);
            redirect(base_url() . 'shipment_mapping');
        }
    }

    public function edit_mapping_view($id) {

        $data['mapdata'] = $this->Shipment_model->getMappingData($id);
        $this->load->view('ShipmentM/edit_shipment_mapping', $data);
    }
    public function bulkauditupdate() {
        $searchids = $this->input->post('show_awb_no');
        $status = $this->input->post('status');
        
        if(!empty($status)){
            if (!empty($searchids)) {
                $data['traking_awb_no'] = preg_split('/\s+/', trim($searchids));
                $data['shipmentdata'] = $this->Shipment_model->getawbdataquery($data['traking_awb_no']);
                if(!empty($data['shipmentdata'])){
                    foreach($data['shipmentdata'] as $slipData){
                        
                        $this->Shipment_model->bulk_audit_update($status, $slipData['slip_no']);
                    }
                    $this->session->set_flashdata('msg', 'Shipment status updated successfully');
                    redirect(base_url() . 'bulk-audit'); 
                    
                }else{
                    $this->session->set_flashdata('error', 'Invalid Shipment');
                    redirect(base_url() . 'bulk-audit');        
                }
            }
        }else{
            $this->session->set_flashdata('error', 'Please select Status');
            redirect(base_url() . 'bulk-audit');
        }
        
    }

    public function Update_tracking_status(){
        $slipNo = $this->input->post('slip_nos');
        
        if(!empty($slipNo)){ 
            $slipArr = explode(",",$slipNo);
            foreach($slipArr as $slip_no){
                
                $shipdata = $this->Shipment_model->getSlipDataForTrack($slip_no);
                
                if(!empty($shipdata)){
                    $companyData = $this->Ccompany_model->GetdeliveryCompanyUpdateQry($shipdata['frwd_company_id'],$shipdata['cust_id'],$shipdata['super_id']);
                    

                    if($companyData['company_type'] == 'F'){
                        $this->load->helper('fastcootrack');                  
                        updateTrackingStatus($shipdata, $companyData);
                    }else{
                        if($companyData['company'] == 'Aymakan'){
                            $this->load->helper('aymakantrack');                  
                            updateTrackingStatus($shipdata, $companyData);
                        }else if($companyData['company'] == 'Aramex' || $companyData['company'] == 'Aramex International'){
                            $this->load->helper('aramextrack');
                            updateTrackingStatus($shipdata, $companyData);
                        }else if($companyData['company'] =='IMile'){
                            $this->load->helper('imile');
                            updateTrackingStatus($shipdata, $companyData);
                        }else if($companyData['company'] =='Barqfleet'){
                            $this->load->helper('barqfleet');
                            updateTrackingStatus($shipdata, $companyData);
                        }else if($companyData['company'] =='J&T' || $companyData['company'] =='J&T EG'){
                            $this->load->helper('jttrack');
                            updateTrackingStatus($shipdata, $companyData);
                        }else if($companyData['company'] == 'Bosta V2'){
                            $this->load->helper('bostatrack');
                            updateTrackingStatus($shipdata, $companyData);
                        }else if($companyData['company'] == 'Labaih'){
                            $this->load->helper('labaihtrack'); 
                            updateTrackingStatus($shipdata, $companyData);                            
                        }else if($companyData['company'] == 'Smsa'){
                            $this->load->helper('smsatrack'); 
                            updateTrackingStatus($shipdata, $companyData);                            
                        }
                        
                    }

                    
                }


            }
            

        }

        echo "true";
        

    }


    public function bulk_update_weight() {
        
        $this->load->view('ShipmentM/bulk_shipment_weight');
    }

    public function edit_wherehouse_address($id){
        
        $this->load->view('ShipmentM/edit_wherehouse_address');
        
    }

    public function torod_order_create(){
        $this->load->view('ShipmentM/order_create_torod');
    }

    public function GenerateTorodWherehouse(){
        $this->load->view('ShipmentM/create_wherehouse_address_torod');
    }
    public function Torodforwardshipments(){
        // echo "test";die;
        $this->load->view("ShipmentM/torod_forward_shipments");
    }
    public function add_address(){
        $this->load->view('ShipmentM/add_wherehouse_add');
    }
    public function torod_shipment_view(){
        
        $this->load->view('ShipmentM/view_torod_shipments');
    }

    public function bulk_forward_torod(){
        $this->load->view('ShipmentM/forward_torod_client');
    }
    public function Create_Generate_address(){
        $postData = json_decode(file_get_contents('php://input'), true);
        // print_r($postData);die;
        $CourierArr=$this->Ccompany_model->GetCompanylistDropQryTorod();
        // echo "<pre>"; print_r($CourierArr); die;
        $this->load->helper('torod');
        // print_r($postData);die;
        $token=TorodgetToken($CourierArr);
        // print_r($token);die;
        $dataArr= array(
            'warehouse_name' => $postData['warehouse_name'],
            'warehouse' => $postData['warehouse'],
            'contact_name' => $postData['contact_name'],
            'phone_number' => $postData['phone_number'],
            'email' => $postData['email'],
            'zip_code' => $postData['zip_code'],
            'type' => $postData['type'],
            'locate_address' => $postData['locate_address']
            );
        
        // echo "<pre>"; print_r($dataArr); die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://demo.stage.torod.co/en/api/create/address',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $dataArr,
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $resultdata=json_decode($response,true);
        
        if($resultdata['status'] == true){
            $status = "Add Success";
           
        }else{
            $status = "Add Failed";
        }
        $param = json_encode($dataArr);
        $result = $this->Torodcompany_model->TorodLog($status,$response,$param);
        // echo "<pre>"; print_r($resultdata); die;
        echo $response;
        
    }

    public function Showlist_address_wherehouse(){
        $CourierArr=$this->Ccompany_model->GetCompanylistDropQryTorod();
        // echo "<pre>"; print_r($CourierArr); die;
        $this->load->helper('torod');
        $token = TorodgetToken($CourierArr);
        //  echo "<pre>"; print_r($token); die;
        $total_result = array();
        for($i=1; $i<=5; $i++){
            $api_url = $CourierArr['api_url_t'].'address/list?page='.$i;
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer '.$token
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result = json_decode($response,true);
            
            $data = $result['data'];
            if(!empty($data)){
                array_push($total_result,$data);
            }
            
        }
        
        // echo "<pre>"; print_r($total_result); die;
        echo  json_encode($total_result);
    }
    public function update_sh_address(){
        $postData = $this->input->post(); 
        // print "<pre>"; print_r($postData);die;
        // print_r(base_url());die;
        if(!empty($postData)){

            $CourierArr=$this->Ccompany_model->GetCompanylistDropQryTorod();
            // print_r($CourierArr);die;
            $this->load->helper('torod');
            $token = TorodgetToken($CourierArr);
            //  echo "<pre>"; print_r($token); die;
            $api_url = $CourierArr['api_url_t'].'update/address/'. $postData['whid'];

            $param = array(
                    'warehouse_name' => $postData['wname'],
                    'warehouse' => $postData['wh_'],
                    'contact_name' => $postData['cname_'],
                    'phone_number' => $postData['pnumber_'],
                    'email' => $postData['email_'],
                    'zip_code' => $postData['zip_'],
                    'type' => 'address',
                    'locate_address' => $postData['address_']
                );
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $api_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $param,
              CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer '.$token
              ),
            ));
            
            $response = curl_exec($curl);
            curl_close($curl);
            $responseData = json_decode($response,true);

            if($responseData['status'] === true){
                $this->session->set_flashdata('msg', $responseData['message']);
                $status = 'Update Success';
            }else{
                $this->session->set_flashdata('error', $responseData['message']);
                $status = 'Update Failed';

            }

            $param_data = json_encode($param);
            $result = $this->Torodcompany_model->TorodLog($status,$response,$param_data);

            redirect(base_url('GenerateTorodWherehouse'));
        }
        
    }
    public function torodOrderUpdate($data = array()){
        $CourierArr=$this->Ccompany_model->GetCompanylistDropQryTorod();
        // echo "<pre>"; print_r($data); die;
        $this->load->helper('torod');
        $token=TorodgetToken($CourierArr);
        // echo $token; die;
        $api_url = $CourierArr['api_url_t'].'order/update';
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer '.$token
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response,true);
        // echo "<pre>"; print_r($result); die;
        if($result['status'] == true){
            // echo "<pre>"; print_r($result); die;
            return true;
        }else{
            return false;
        }

    }

    public function remove_reverse_order() {
        if (menuIdExitsInPrivilageArray(257) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/bulk_reverse_remove', $bulk);
    }

    public function reverse_orders_remove() {
        $searchids = $this->input->post('tracking_numbers');

        $data['traking_awb_no'] = preg_split('/\s+/', trim($searchids));
        $invalid_slip = array(); $valid_slip = array(); $booking_id = array();

        if (!empty($data['traking_awb_no'])) {
            $fileds = "id,slip_no,booking_id,code,delivered,reverse_type";
            $shipmentdata = $this->Shipment_model->getValidShpemtsDetails($data['traking_awb_no'],$fileds);
            $slipRArray = array();
            foreach ($shipmentdata as $key=>$shData) {

                if($shData['code'] == 'ROG' || $shData['code'] == 'FD' ){
                    $valid_slip[] = $shData['slip_no'];
                    $booking_id[]=  $shData['booking_id'];
                    $StatusArray[$key]['user_type'] = 'fulfillment';
                    $StatusArray[$key]['slip_no'] = $shData['slip_no'];
                    $StatusArray[$key]['new_status'] = $shData['delivered'];
                    $StatusArray[$key]['pickup_time'] = date("H:i:s");
                    $StatusArray[$key]['pickup_date'] = date("Y-m-d H:i:s");
                    $StatusArray[$key]['Details'] = "Removed by " . getUserNameById($this->session->userdata('user_details')['user_id']);
                    $StatusArray[$key]['Activites'] = "Removed Reverse Shipment";
                    $StatusArray[$key]['entry_date'] = date("Y-m-d H:i:s");
                    $StatusArray[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                    $StatusArray[$key]['user_type'] = 'fulfillment';
                    $StatusArray[$key]['code'] = $shData['code'];
                    $StatusArray[$key]['comment'] = "Removed ROG  ".$shData['booking_id']."  ( ".$shData['slip_no']." )";
                    $StatusArray[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                    if(file_exists("assets/all_labels/" . $shData['booking_id'] . ".pdf")){
                        unlink("assets/all_labels/" . $shData['booking_id'] . ".pdf");
                    }
                }else{
                    $invalid_slip[] = $shData['slip_no'];
                }

                
            }

            //   print "<pre>"; print_r($valid_slip);die;
            
            if (!empty($valid_slip)) {

                 $this->Shipment_model->DeleteUpdateShipmentData($StatusArray);
                 $shipmentdata = $this->Shipment_model->removeReverse($booking_id);
                 $reverseshipmentdata = $this->Shipment_model->deleteReverseOrders($valid_slip);
                // // print_r( $valid_slip); exit; 
                $this->session->set_flashdata('msg', $valid_slip);
                redirect(base_url() . 'remove_reverse_order');
            } else {
                
                $this->session->set_flashdata('error', $invalid_slip);
                // print "ssss<pre>"; print_r($this->session->flashdata('error'));die;
                redirect(base_url() . 'remove_reverse_order');
            }
        } else {
            $this->session->set_flashdata('error', array('Not valid Numbers'));
            redirect(base_url() . 'remove_reverse_order');
        }
    }
    
    public function pushSallaOrder_new() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $slip_no = $postData['list_awb'];

        $uniqueid =  $postData['cus_id'];

        $sallacustomer = $this->Shipment_model->getZidCustomer($uniqueid);
        $systemToken = $sallacustomer['salla_athentication'];
        foreach ($slip_no as $slip) {
            $data = $this->sallaOrders($sallacustomer, $slip);
        }

        echo json_encode("order Created successfully !");
    }
    private function sallaOrders($customers, $oId) {


        $athentication = $customers['salla_athentication'];

        $SallaTotalOrders = $this->SallacURL($athentication, $oId);
        $secKey = $customers['secret_key'];
        $customerId = $customers['uniqueid'];
        if ($SallaTotalOrders['status'] == 200) {

            $orders = $SallaTotalOrders['data'][0];
            $jsonData = json_encode($orders);
            
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.diggipacks.com/API/sallaOrder/' . $customerId . '/YES',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));

            echo $response = curl_exec($curl);

            curl_close($curl);
        } else {
            echo json_encode($SallaTotalOrders);
        }
    }
    
    private function SallacURL($athentication, $oId) {

        // echo $
        $url = "https://api.salla.dev/admin/v2/orders?expanded=1&reference_id=" . $oId;

        //ECHO $athentication; EXIT;

        $headers = array("Authorization:Bearer $athentication");

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
        //echo $response;
    }

}

?>