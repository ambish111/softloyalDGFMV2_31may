<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salla_orders_new extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
         $this->load->model('Salla_orders_model');
         $this->load->model('Shipment_model');
         $this->load->helper('salla');
    }

    public function check_page() {
        $this->load->view('ShipmentM/check_page');
    }

    public function index() {

        if (menuIdExitsInPrivilageArray(250) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('ShipmentM/view_salla_orders_new');
    }

    public function filter() {

        $post_data = json_decode(file_get_contents('php://input'), true);
        
        
        $uniqueid=$post_data['cus_id'];
         $from_date=$post_data['from_date'];
          $to_date=$post_data['to_date'];
        $order_id=$post_data['order_id'];
        $sallacustomer = $this->Shipment_model->getZidCustomer($uniqueid);
        $systemToken = $sallacustomer['salla_athentication'];
        $customer_id = $sallacustomer['id'];
        $cc_id = $sallacustomer['id'];

        if (!empty($post_data['currentPage'])) {
            $page_no = $post_data['currentPage'];
        } else {
            $page_no = 1;
        }
        //
        if(!empty($order_id))
        {
             $url ="https://api.salla.dev/admin/v2/orders?expanded=1&reference_id=".$order_id;
        }
        else
        {
            if(!empty($from_date) && !empty($to_date))
            {
            $url="https://api.salla.dev/admin/v2/orders?query=2A&expanded=1&from_date=$from_date&to_date=$to_date&page=" . $page_no;
            }
            else
            {
                $url='https://api.salla.dev/admin/v2/orders?query=2A&expanded=1&page=' . $page_no;
            }
        }
      // echo $url; die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $systemToken
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response1 = curl_exec($curl); //exit;
        curl_close($curl);
        $bulk = json_decode($response1, true);
        $SallaData = $bulk['data'];
        foreach ($SallaData as $key => $val) {
            $slip_no = $this->Salla_orders_model->BookingIdCheck_cust_fm($val['reference_id'], $customer_id);
            if (!empty($slip_no)) {
                $SallaData[$key]['fs_awb'] = $slip_no;
            } else {
                $SallaData[$key]['fs_awb'] = 'NO';
            }
        }

        // print_r($bulk11); die;
        // array_push($datatest, $bulk11->data);



        $totalpage = $bulk['pagination']['totalPages'];
        $total = $bulk['pagination']['total'];
        $data['salla_data'] = $SallaData;
        $data['total'] = $total;
        $data['totalpage'] = $totalpage;
        $data['cc_id'] = $cc_id;

       // $data['salla'] = $booking_id;

        echo json_encode($data);
    }

}

?>