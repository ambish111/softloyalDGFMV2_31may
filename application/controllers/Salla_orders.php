<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Salla_orders extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Salla_orders_model');
        $this->load->helper('salla');
    }

    public function index() {
        if (menuIdExitsInPrivilageArray(1) == 'N') {
            //  redirect(base_url() . 'notfound');
            //die;
        }

        $this->load->view('ShipmentM/view_shipments_salla');
    }

    public function filter() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Salla_orders_model->filter($_POST);
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;


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

            //$shiparray[$ii]['origin'] = getdestinationfieldshow($rdata['origin'], 'city');
            //$shiparray[$ii]['destination'] = getdestinationfieldshow($rdata['destination'], 'city');
            $shiparray[$ii]['created_at'] = date('Y-m-d h:i A', strtotime($rdata['created_at']));
            $cust_id = $this->Salla_orders_model->Getcustomerdata($rdata['merchant_id']);
            if (!empty($cust_id['id'])) {
                $shiparray[$ii]['order_verify'] = "Y";
            } else {
                $shiparray[$ii]['order_verify'] = "N";
            }

            $ii++;
        }

        $dataArray['dropexport'] = $expoertdropArr;

        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        echo json_encode($dataArray);
    }

    public function filterdetail() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $this->Salla_orders_model->Getskudetails_ship($_POST['id'], $_POST['bk_id']);
        echo json_encode($dataArray);
    }

    public function GetOrderCreateProcess() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $tid = $postData['tid'];

        if (!empty($tid)) {
            $orderArr = $this->Salla_orders_model->getorderDetails($tid);
//            print "<pre>"; print_r($orderArr);die;
            if (!empty($orderArr)) {
                $cust_data = $this->Salla_orders_model->Getcustomerdata($orderArr['merchant_id']);
//                print "<pre>"; print_r($cust_data);die;
                $seller_id = $cust_data['id'];
                if (!empty($cust_data)) {

                    $bookingExits = $this->Salla_orders_model->BookingIdCheck_cust_fm($orderArr['booking_id'], $cust_data['id']);
                    if (empty($bookingExits)) {
                        
                       $return=$this->GetOrderrequest($orderArr['data_s'],$orderArr['merchant_id']);
                    }
                    else
                    {
                      $return= array('error'=>'this Shipment already exist');   
                    }
                }
                else
                    {
                      $return= array('error'=>'salla access not set for the customer...');   
                    }
            }
            else
                    {
                      $return= array('error'=>'salla access not set for the customer..');   
                    }
        }


        echo json_encode($return);
    }

    private function GetOrderrequest($data,$merchant_id=null) {

        $data = array("data"=>json_decode($data),"merchant_id"=>$merchant_id);
        $request = json_encode($data);
//        echo $request ;die;
        
        $curl = curl_init();

        
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.diggipacks.com/SallaNew/sallaApi",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>$request,
        CURLOPT_HTTPHEADER => array( "cache-control: no-cache", "content-type: application/json"),
        ));

         $response = curl_exec($curl);
        $err = curl_error($curl);
//        echo $response;
        curl_close($curl);exit;
        
        if ($err) {
           return array('error'=>$err);
           
        } else {
            return json_decode($response);
        }
        
        
    }

}

?>