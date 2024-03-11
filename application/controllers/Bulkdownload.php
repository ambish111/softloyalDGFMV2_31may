<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bulkdownload extends MY_Controller {

    function __construct() {
        parent::__construct();
        // if (menuIdExitsInPrivilageArray(154) == 'N') {
        //     redirect(base_url() . 'notfound');
        //     die;
        // }
        $this->load->model('Bulkdownload_model');
    }

    public function filter() {
        //        ini_set('display_errors', '1');
//        ini_set('display_startup_errors', '1');
//        error_reporting(E_ALL);
        $postData = json_decode(file_get_contents('php://input'), true);
        $error = 0;
        if (!empty($postData['slip_no'])) {
            $SlipNos = preg_replace('/\s+/', ',', trim($postData['slip_no']));
            $slip_arr = explode(",", $SlipNos);
            $slipData = array_unique($slip_arr);

            if (count($slipData) > 50) {
                $error = 1;
            }
        }
        if ($error == 0) {
            $count = $this->Bulkdownload_model->shipmCount($postData);

            $tolalShip = $count;
            $downlaoadData = 50000;
            $j = 0;
            for ($i = 0; $i < $tolalShip;) {
                $i = $i + $downlaoadData;
                if ($i > 0) {
                    $expoertdropArr[] = array('j' => $j, 'i' => $i);
                }
                $j = $i;
            }
            $dataArray['search_limit_error'] = 0;
        } else {
            $dataArray['search_limit_error'] = count($slipData);
        }
        $dataArray['dropexport'] = $expoertdropArr;

        $dataArray['count'] = $count;

        echo json_encode($dataArray);
    }

    public function index() {
        //die('hello Manish Verma');


        $this->load->model('Shipment_model');
        $this->load->model('Seller_model');
        $this->load->model('Status_model');
        $sellers = $this->Seller_model->find2();
        $status = $this->Status_model->allstatus();
        $data = $this->Shipment_model->getStatusIDByName('3PL Updates');

        $result = array();
        if (!empty($data)) {
            $result = getallstatusbyid($data[0]['id']);
        }
        $bulk = array(
            'sellers' => $sellers,
            'status' => $status,
            'status_3pl' => $result,
        );

        $this->load->view('reports/bulkdownload', $bulk);
    }

    public function export() {

//die;
//        ini_set('display_errors', '1');
//        ini_set('display_startup_errors', '1');
//        error_reporting(E_ALL);
        //echo $this->input->post('searchval'); die;
        $postData = json_decode($this->input->post('searchval'), true);
        // echo print_r($postData);  
        // die;


        if ($postData['exportlimit'] > 0) {
            $limit = 50000;
            if (!empty($postData['exportlimit'])) {
                $start = $postData['exportlimit'] - $limit;
            } else {
                $start = 0;
            }


            $super_id = $this->session->userdata('user_details')['super_id'];

            ## bof:: saving log activity
            $filter_json = json_encode($postData);

            $actdetails = "Bulk Report Download";
            $logstattus = "Report";
            $s_type = "RP";
            $logArray = array('user_id' => $this->session->userdata('user_details')['user_id'], 'details' => $actdetails, 'status' => $logstattus, 'ip_address' => $_SERVER['REMOTE_ADDR'], 'super_id' => $super_id,'s_type'=>$s_type,'log_details'=>$filter_json,'entrydate'=>date('Y-m-d H:i:s'));
            $this->db->insert('activities_log', $logArray);

            ## eof:: saving log activity



            ini_set('memory_limit', '1024M');
            ini_set('max_execution_time', 60000); //increase max_execution_time to 10 min if data set is very large
            //create a file
            $filename = "Bulk Shipment Report " . date("Y.m.d") . ".csv";
            $csv_file = fopen('php://output', 'w');
            // header('Content-Encoding: UTF-8');
            //  header('Content-Type: application/vnd.ms-excel');
            fputs($csv_file, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!
            header('Content-Encoding: UTF-8');
            header("Content-Type: text/csv");
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            if (!empty($postData['seller'])) {

                $this->db->where_in('shipment_fm.cust_id', $postData['seller']);
            }
            if (!empty($postData['slip_no'])) {
                $SlipNos = preg_replace('/\s+/', ',', trim($postData['slip_no']));
                $slip_arr = explode(",", $SlipNos);
                $slipData = array_unique($slip_arr);
                if (count($slipData) < 50) {
                    $this->db->where_in('shipment_fm.slip_no', $slipData);
                }
            }

            if (isset($postData['on_hold'])) {
                $this->db->where('shipment_fm.on_hold', $postData['on_hold']);
            }
            if (!empty($postData['sku']) || !empty($postData['piece']) || $postData['backorder'] == 'Yes') {
               // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
                $this->db->where('diamention_fm.super_id', $super_id);
                if (!empty($postData['sku'])) {
                    $this->db->where('diamention_fm.sku', $postData['sku']);
                }
                if (!empty($postData['piece'])) {
                    $this->db->where('diamention_fm.piece', $postData['piece']);
                }
            }
            if (!empty($postData['mode'])) {

                $this->db->where_in('shipment_fm.mode', $postData['mode']);
            }
            if (!empty($postData['audit_status'])) {

                $this->db->where('shipment_fm.audit_status', $postData['audit_status']);
            }
            if (!empty($postData['from']) && !empty($postData['to'])) {
                $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $postData['from'] . "' AND '" . $postData['to'] . "'";
                $this->db->where($where);
            }
            if (!empty($postData['f_from']) && !empty($postData['f_to'])) {

                $where1 = "DATE(shipment_fm.frwd_date) BETWEEN '" . $postData['f_from'] . "' AND '" . $postData['f_to'] . "'";

                $this->db->where($where1);
            }

            if (!empty($postData['cod_received_date_f']) && !empty($postData['cod_received_date_t'])) {
                $where = "DATE(shipment_fm.cod_received_date) BETWEEN '" . $postData['cod_received_date_f'] . "' AND '" . $postData['cod_received_date_t'] . "'";

                $this->db->where($where);
            }
            if (!empty($postData['dispatch_date_from']) && !empty($postData['dispatch_date_to'])) {
                $where = "DATE(shipment_fm.dispatch_date) BETWEEN '" . $postData['dispatch_date_from'] . "' AND '" . $postData['dispatch_date_to'] . "'";

                $this->db->where($where);
            }

            if (!empty($postData['from_c']) && !empty($postData['to_c'])) {
                $where = "DATE(shipment_fm.close_date) BETWEEN '" . $postData['from_c'] . "' AND '" . $postData['to_c'] . "'";

                $this->db->where($where);
            }
            if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

            if ($postData['product_invoice'] == 'Yes') {
                $this->db->where('shipment_fm.product_invoice is NOT NULL', NULL, FALSE);
            }
            if ($postData['product_invoice'] == 'No') {
                $this->db->where('shipment_fm.product_invoice is NULL', NULL, FALSE);
            }
            if (!empty($postData['destination'])) {
                $this->db->where_in('shipment_fm.destination', $postData['destination']);
            }

            if (!empty($postData['cc_id'])) {


                $this->db->where_in('shipment_fm.frwd_company_id', $postData['cc_id']);
            }
            $columns = "";
            if ($postData['backorder'] == 'Yes') {
                $this->db->where('shipment_fm.backorder', 1);
                $this->db->where('shipment_fm.code', 'OG');
            } else {
                $this->db->where('shipment_fm.backorder', 0);
                if (!empty($postData['status'])) {
                    // echo "sssssss"; die;
                    $this->db->where_in('shipment_fm.code', $postData['status']);
                }
            }
            if ($postData['show_sku'] == 'Yes' && $postData['backorder'] != 'Yes') {
                $columns = "
            `diamention_fm`.`sku`,
            `diamention_fm`.`piece`,
            `diamention_fm`.`wieght` as `wt`,
            `diamention_fm`.`cod`,
            `diamention_fm`.`deducted_shelve`,";
            }

            if ($postData['backorder'] == 'Yes') {
                $columns = "
            `diamention_fm`.`sku`,
            `diamention_fm`.`piece`,
            `diamention_fm`.`wieght` as `wt`,
            `diamention_fm`.`cod`,
            `diamention_fm`.`deducted_shelve`,
             `diamention_fm`.`back_reason`,";
            }

            //if(menuIdExitsInPrivilageArray(230) == 'Y')
            {
                $columns_type = "`shipment_fm`.`typeship`,`shipment_fm`.`sap_order_number`,";
            }

            // echo "rrr";
            // die;
            // $this->db->where("shipment_fm.slip_no","DGF19270953993");
            // `diamention_fm`.`sku`,
            //`diamention_fm`.`piece`,
            //`diamention_fm`.`wieght` as `wt`,
            //`diamention_fm`.`description`,
            //`diamention_fm`.`cod`,'
            //`diamention_fm`.`deducted_shelve`,`shipment_fm`.`sender_name`,

            $this->db->select("`shipment_fm`.`id`,
 `shipment_fm`.`service_id`,
 `shipment_fm`.`booking_id`,
 $columns
 `shipment_fm`.`slip_no`,
`status_main_cat_fm`.`main_status`,
`customer`.`name`,
 `customer`.`company`,
 `customer`.`seller_id`,
 `customer`.`uniqueid`,
 `shipment_fm`.`entrydate`,
(select city from country where country.id=shipment_fm.origin) AS origin,
(select country from country where country.id=shipment_fm.origin) AS originCountry,
  (select city from country where country.id=shipment_fm.destination) AS destination,
  (select country from country where country.id=shipment_fm.destination) AS destinationCountry,
  (select company from courier_company where courier_company.cc_id=shipment_fm.frwd_company_id AND  courier_company.deleted = 'N' AND courier_company.super_id= " . $super_id . " limit 1) AS ForwardedCompany,
 `shipment_fm`.`reciever_name`,
 `shipment_fm`.`reciever_address`,
 `shipment_fm`.`reciever_phone`,
 `shipment_fm`.`sender_address`,
 `shipment_fm`.`sender_phone`,
 `shipment_fm`.`order_type`,
 `shipment_fm`.`sender_email`,
 `shipment_fm`.`mode`,
 `shipment_fm`.`total_cod_amt`,
 `shipment_fm`.`weight`,
 `shipment_fm`.`pieces`,
 `shipment_fm`.`cust_id`,
 `shipment_fm`.`shippers_ac_no`,
 `shipment_fm`.`frwd_company_awb`,
 shipment_fm.sender_name,
shipment_fm.shippers_ref_no,
 customer.uniqueid,
 IF (shipment_fm.cust_id ='154',  shipment_fm.sender_name,customer.company) AS SENDER_NAME,
 (select company from customer where customer.id=shipment_fm.cust_id) AS STORE_NAME,
 
 shipment_fm.status_describtion,
 (select sub_status from status_category_fm where status_category_fm.code=shipment_fm.code) AS threePLSTATUS,
 shipment_fm.pay_invoice_no,
 shipment_fm.pay_invoice_status,
 shipment_fm.rec_invoice_status,
 date(shipment_fm.entrydate) AS ENTRY_DATE,
 time(shipment_fm.entrydate) AS entry_TIME,
 shipment_fm.3pl_pickup_date,
 shipment_fm.frwd_date,
 shipment_fm.no_of_attempt,
 shipment_fm.close_date,
 shipment_fm.laststatus_first,
 shipment_fm.cod_received_3pl,
 shipment_fm.cod_received_date,
 shipment_fm.fd1_date,
 shipment_fm.laststatus_second,
 shipment_fm.fd2_date,
  IF (shipment_fm.reverse_type='1','Reverse order','Fullfillment order') AS ShipmentType ,
 shipment_fm.laststatus_last,
 shipment_fm.fd3_date,
 shipment_fm.3pl_close_date,
  shipment_fm.dispatch_date,
 IF (shipment_fm.product_invoice is not null, 'Yes','No') AS Invoice,
 IF (shipment_fm.delivered='19', (select Details from status_fm where status_fm.slip_no=shipment_fm.slip_no order by status_fm.id desc limit 1),'') AS LastStatus ,
 IFNULL(DATEDIFF(close_date, 3pl_pickup_date) , DATEDIFF(CURRENT_TIMESTAMP() , 3pl_pickup_date)  )  AS transaction_days,
 (select name from warehouse_m where warehouse_m.id=shipment_fm.wh_id) as warehouse,
 $columns_type
shipment_fm.frwd_company_awb,shipment_fm.audit_status");
            $this->db->from('shipment_fm');
            $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
            if ($columns != '')
                $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
            $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
            $fulfillment = 'Y';
            $deleted = 'N';
            $this->db->where('shipment_fm.fulfillment', $fulfillment);
            $this->db->where('shipment_fm.deleted', $deleted);
            //  $this->db->where('shipment_fm.backorder', '0');
            $this->db->where('shipment_fm.super_id', $super_id);

            $this->db->limit($limit, $start);
            $query = $this->db->get();
            //or
            //echo $this->db->last_query(); die;


            $results = $query->result_array();

            // The column headings of your .csv file


            if ($postData['backorder'] == 'Yes' || $columns != '') {
                $header_row = array("AWB_NO", "ENTRY_DATE", "entry_TIME", "REFRENCE No", "SHIPPER REF No", "ORIGIN", "ForwardedCompany", "DESTINATION", "SENDER_NAME", "SENDER ADDRESS", "SENDER PHONE", "RECEIVER NAME", "RECEIVER ADDRESS", "RECEIVER PHONE", "INVOICE PAID", "INVOICE NUMBER", "INVOICE PAYMENT RECEIVED", "RECEIVER MODE", "MAINSTATUS", "3PLSTATUS", "LastStatus", "COD AMOUN", "UNIQUE_ID", "ON PIECES", "ON WEIGHT", "DESCRIPTION", "FORWARD AWB No.", "3PL Pickup Date", "3PL Closed Date", "CLOSE DATE", "No Of Attempt", "Transaction Number", "ShipmentType", "transaction_days", "FD First Status", "FD1 Date", "FD Second Status", "FD2 Date", "FD Last Status", "FD3 Date", "Invoice", 'SKU', 'Qunatity', 'Weight', 'Deducted Shelve', 'Audit Status', 'DESTINATION COUNTRY', 'STORE NAME', 'COD_Received_3pl', 'COD_Received_DATE', 'Dispatch Date', 'back_reason');
            } else {
                $header_row = array("AWB_NO", "ENTRY_DATE", "entry_TIME", "REFRENCE No", "SHIPPER REF No", "ORIGIN", "ForwardedCompany", "DESTINATION", "SENDER_NAME", "SENDER ADDRESS", "SENDER PHONE", "RECEIVER NAME", "RECEIVER ADDRESS", "RECEIVER PHONE", "INVOICE PAID", "INVOICE NUMBER", "INVOICE PAYMENT RECEIVED", "RECEIVER MODE", "MAINSTATUS", "3PLSTATUS", "LastStatus", "COD AMOUN", "UNIQUE_ID", "ON PIECES", "ON WEIGHT", "DESCRIPTION", "FORWARD AWB No.", "3PL Pickup Date", "3PL Closed Date", "CLOSE DATE", "No Of Attempt", "Transaction Number", "ShipmentType", "transaction_days", "FD First Status", "FD1 Date", "FD Second Status", "FD2 Date", "FD Last Status", "FD3 Date", "Invoice", 'Audit Status', 'DESTINATION COUNTRY', 'STORE NAME', 'COD_Received_3pl', 'COD_Received_DATE', 'Dispatch Date');
            }
            //if (menuIdExitsInPrivilageArray(230) == 'Y')
            {
                $header_row[] = 'Type';
                $header_row[] = 'SAP No.';
                $header_row[] = 'Warehouse';
            }
            //  $header_row = array("Ref. No.", "AWB NO", "SHIPPER REF No", "Origin Country", "Origin", "Destination Country", "Destination", "Sender Name", "Sender Address", "Sender Phone", "Receiver Name", "Receiver Address", "Receiver Phone", "Status", "Seller", "Entry Date", "SKU", "Qty", "COD", "Weight", "Deducted Shelve NO", "ForwardedCompany", "ForwardedCompany AWB", "Total COD", "Payment Mode", "Shipper Account No.", "UID Account", "DESCRIPTION", "3PLSTATUS", "INVOICE NUMBER", "INVOICE PAID", "INVOICE PAYMENT RECEIVED", "3PL Pickup Date", "3PL_FORWORD_DATE", "Transaction Days", "No of Attempt", "Close Date", "Failed 1st Status", "Failed 2nd Status", "Failed Last Status");
            fputcsv($csv_file, $header_row, ',', '"');

            // Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column


            foreach ($results as $result) {
                // Array indexes correspond to the field names in your db table(s)
                if ($postData['backorder'] == 'Yes' || $columns != '') {
                    $row = array(
                        $result['slip_no'],
                        $result['ENTRY_DATE'],
                        $result['entry_TIME'],
                        $result['booking_id'],
                        $result['shippers_ref_no'],
                        //$result['originCountry'],
                        $result['origin'],
                        $result['ForwardedCompany'],
                        // $result['destinationCountry'],
                        $result['destination'],
                        $result['SENDER_NAME'],
                        $result['sender_address'],
                        $result['sender_phone'],
                        $result['reciever_name'],
                        $result['reciever_address'],
                        $result['reciever_phone'],
                        $result['pay_invoice_status'],
                        $result['pay_invoice_no'],
                        $result['rec_invoice_status'],
                        $result['mode'],
                        $result['main_status'],
                        $result['threePLSTATUS'],
                        $result['LastStatus'],
                        $result['total_cod_amt'],
                        $result['uniqueid'],
                        $result['pieces'],
                        $result['weight'],
                        $result['status_describtion'],
                        $result['frwd_company_awb'],
                        $result['3pl_pickup_date'],
                        $result['3pl_close_date'],
                        $result['close_date'],
                        $result['no_of_attempt'],
                        $result['pay_invoice_no'],
                        $result['ShipmentType'],
                        $result['transaction_days'],
                        $result['laststatus_first'],
                        $result['fd1_date'],
                        $result['laststatus_second'],
                        $result['fd2_date'],
                        $result['laststatus_last'],
                        $result['fd3_date'],
                        $result['Invoice'],
                        $result['sku'],
                        $result['piece'],
                        $result['deducted_shelve'],
                        $result['wt'],
                        $result['audit_status'],
                        $result['destinationCountry'],
                        $result['STORE_NAME'],
                        $result['cod_received_3pl'],
                        $result['cod_received_date'],
                        $result['dispatch_date'],
                        $result['back_reason'],
                        $result['typeship'],
                        $result['sap_order_number'],
                         $result['warehouse'],
                    );
                } else {
                    $row = array(
                        $result['slip_no'],
                        $result['ENTRY_DATE'],
                        $result['entry_TIME'],
                        $result['booking_id'],
                        $result['shippers_ref_no'],
                        //$result['originCountry'],
                        $result['origin'],
                        $result['ForwardedCompany'],
                        // $result['destinationCountry'],
                        $result['destination'],
                        $result['SENDER_NAME'],
                        $result['sender_address'],
                        $result['sender_phone'],
                        $result['reciever_name'],
                        $result['reciever_address'],
                        $result['reciever_phone'],
                        $result['pay_invoice_status'],
                        $result['pay_invoice_no'],
                        $result['rec_invoice_status'],
                        $result['mode'],
                        $result['main_status'],
                        $result['threePLSTATUS'],
                        $result['LastStatus'],
                        $result['total_cod_amt'],
                        $result['uniqueid'],
                        $result['pieces'],
                        $result['weight'],
                        $result['status_describtion'],
                        $result['frwd_company_awb'],
                        $result['3pl_pickup_date'],
                        $result['3pl_close_date'],
                        $result['close_date'],
                        $result['no_of_attempt'],
                        $result['pay_invoice_no'],
                        $result['ShipmentType'],
                        $result['transaction_days'],
                        $result['laststatus_first'],
                        $result['fd1_date'],
                        $result['laststatus_second'],
                        $result['fd2_date'],
                        $result['laststatus_last'],
                        $result['fd3_date'],
                        $result['Invoice'],
                        $result['audit_status'],
                        $result['destinationCountry'],
                        $result['STORE_NAME'],
                        $result['cod_received_3pl'],
                        $result['cod_received_date'],
                        $result['dispatch_date'],
                        $result['typeship'],
                        $result['sap_order_number'],
                          $result['warehouse'],
                        
                    );
                }

                fputcsv($csv_file, $row, ',', '"');
            }

            fclose($csv_file);

            //reditect(base_url());
        }
    }

    public function getexceldata() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataAray = $this->Bulkdownload_model->alllistexcelData($_POST);

        $file_name = 'shipments.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

}
