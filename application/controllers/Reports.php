<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Reports_model');
        $this->load->model('Shipment_model');
        $this->load->model('Ccompany_model');
        $this->load->helper('utility');

        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function client_report() {


        $this->load->view('reports/client_report');
    }

    public function GetClientOrderReports() {

        $postData = json_decode(file_get_contents('php://input'), true);

        $shipments = $this->Reports_model->GetClientReportDispatchQry($postData);
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
        foreach ($shipments['result'] as $rdata) {



            $shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            // $shiparray[$ii]['sku_details'] = getdestinationfieldshow($rdata['origin'], 'city');
            $shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['wh_id'] = Getwarehouse_categoryfield($rdata['wh_id'], 'name');
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['frwd_company_id'], 'company');

            $shiparray[$ii]['wh_ids'] = $rdata['wh_id'];

            $shiparray[$ii]['deducted_shelve_no'] = $this->Reports_model->GetdimationDetails($rdata['slip_no']);

            //$shiparray='rith';
            $ii++;
        }


        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['dropshort'] = $pageShortArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function performance_details_3pl($frwd_throw = null, $status = null, $from = null, $to = null) {

        $data['Urldata'] = array('frwd_throw' => $frwd_throw, 'status' => $status, 'from' => $from, 'to' => $to);
        //$data['DetailsArr'] = $this->Reports_model->GetallperformationDetailsQry_3pl($frwd_throw,$status,$from,$to);

        $this->load->view('reports/performance_details_3pl', $data);
    }

    public function performance_3pl() {

        $data['postData'] = $this->input->post();

        if ($data['postData']['clfilter'] == 1) {
            $data['postData'] = array();
        }
        $data['sellers'] = $this->Reports_model->all_3pl($data['postData']);

        $this->load->view('reports/performance_3pl', $data);
    }

    public function performance_details_filter() {


        $filterArr = json_decode(file_get_contents('php://input'), true);

        $dataArray = $this->Reports_model->GetallperformationDetailsQry_filter($filterArr);

        echo json_encode($dataArray);
    }

    public function view_damage_inventory() {
        $this->load->model('ItemInventory_model');

        $sellers = $this->Seller_model->find1();

        $bulk = array('sellers' => $sellers);
        $this->load->view('reports/view_iteminventory_damage', $bulk);
    }

    public function filter_damage() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $items = $this->Reports_model->filter_damage($_POST);
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


            $ItemArray[$kk]['update_date'] = date("d-m-Y H:i:s", strtotime($rdata['update_date']));
            $ItemArray[$kk]['item_type'] = $rdata['type'];
            $ItemArray[$kk]['sku_size'] = $rdata['sku_size'];
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield($rdata['storage_id'], 'storage_type');
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

    public function storage_report() {


        $this->load->view('reports/storage_report');
    }

    public function Getstorage_report_client() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $page_no = $postData['page_no'];
        $monthid = $postData['monthid']; //1011
        $seller_id = $postData['seller_id']; //42
        $years = $postData['years']; //42
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $monthid, $years);
        $monthdaysArray = array();
        $company = site_configTable('company_name');
        $seller_arr = GetSinglesellerdata($seller_id, $this->session->userdata('user_details')['super_id']);
        $storage_arr = Getallstorage_drop_default();
        $toady_date = date("Y-m-d");
        for ($x = 1; $x <= $totalDays; $x++) {
            $time = mktime(12, 0, 0, $monthid, $x, $years);

            $monthdaysArray[$x]['date'] = date('Y-m-d', $time);
            foreach ($storage_arr as $val) {
                $total_cahrge = GetStorageReportCount($seller_id, date('Y-m-d', $time), $val['id']);
                if (!empty($total_cahrge)) {
                    $monthdaysArray[$x]['pallet_total_' . $val['id']] = $total_cahrge;
                } else {
                    $monthdaysArray[$x]['pallet_total_' . $val['id']] = 0;
                }
            }
            $monthdaysArray[$x]['seller_name'] = $seller_arr['company'];
            $monthdaysArray[$x]['company'] = $company;
        }

        echo json_encode(array('result' => $monthdaysArray, 'storage_type' => $storage_arr));
    }

    public function courierHealthReport() {

        if ($this->session->userdata('user_details')) {
            $month = '';
            //print "<pre>"; print_r($this->input->get());die;
            //$year= $this->input->post('year'); 
            //echo $this->input->get('month');
            if (!empty($this->input->post('month'))) {
                $month = $this->input->post('month');
            } else {
                $month = date('m');
            }

            if (!empty($this->input->post('clfilter'))) {
                $from_date = '';
                $to_date = '';
                $single_date = '';
            } else {
                $from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');
                $single_date = $this->input->post('single_date');
            }
            // $from_date = $this->input->post('from_date');
            // $to_date = $this->input->post('to_date');
            // $single_date = $this->input->post('single_date');


            $monthlyshipment = $this->Shipment_model->GetalltotalchartmonthShipment($month);
            //print "<pre>"; print_r($monthlyshipment);die;
            $todaysshipment = $this->Shipment_model->GetalltotalchartTodayShipment($from_date, $to_date, $single_date);
            $this->load->view('reports/ccompany_health_report', [
                'monthlyshipment' => $monthlyshipment,
                'selected_month' => $month,
                'todaysshipment' => $todaysshipment,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'single_date' => $single_date
            ]);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function damage_inventory_history() {

        $this->load->model('ItemInventory_model');

        $sellers = $this->Seller_model->find1();

        $bulk = array('sellers' => $sellers);
        $this->load->view('reports/iteminventory_damage_history', $bulk);
    }

    public function filter_damage_history() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $items = $this->Reports_model->filter_damage_history($_POST);
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


            $ItemArray[$kk]['update_date'] = date("d-m-Y H:i:s", strtotime($rdata['update_date']));
            $ItemArray[$kk]['item_type'] = $rdata['type'];
            $ItemArray[$kk]['sku_size'] = $rdata['sku_size'];
            $ItemArray[$kk]['storage_id'] = Getallstoragetablefield($rdata['storage_id'], 'storage_type');
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

    public function packing_serial() {

        if (menuIdExitsInPrivilageArray(256) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        
        $this->load->view('reports/packing_serial');
    }

    public function getSkuDetails() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $page_no = $postData['page_no'];
        $slip_no = $postData['slip_no'];
        $fromdate = $postData['from_date'];
        $todate = $postData['to_date'];

        if (!empty($postData['limit'])) {
            $limit = $postData['limit'] + 1;
        } else {
            $limit = 0;
        }


        $otherfilter = array('fromdate' => $fromdate, 'todate' => $todate);
        $QueryData = $this->Reports_model->GetallPackagingQuery($page_no, $slip_no, $otherfilter);
        $returnArray = $QueryData['result'];
        //print "<pre>"; print_r($QueryData);die;
        $tolalShip = $QueryData['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        $ii = 0;
        $usernameArr = array();
        foreach ($returnArray as $rdata) {
            
            $serial_noArr=json_decode($rdata['serial_no'],true);
            if (empty($usernameArr[$rdata['updated_by']])) {
                $usernameArr[$rdata['updated_by']] = getUserNameById($rdata['updated_by'], 'username');
            }
            $returnArray[$ii]['updated_by'] = $usernameArr[$rdata['updated_by']];
             $returnArray[$ii]['serial_no'] = implode(',',$serial_noArr);

            $ii++;
        }
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $returnArray;
        $dataArray['count'] = $QueryData['count'];
        echo json_encode($dataArray);
    }
    
    
      public function getPackagingExcelDetails() {
        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Reports_model->getPackagingExcelReport($request);
        $file_name = 'Serial_Packaging_report.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
        //print "<pre>"; print_r($request);die;
    }

}

?>