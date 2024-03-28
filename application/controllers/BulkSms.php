<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class BulkSms extends MY_Controller {

    function __construct() {
        parent::__construct();
        // // error_reporting(0);
        $this->load->model('Bulksms_model');
        // $this->load->model('Shipment_model');
        // $this->load->model('Seller_model');
        // $this->load->model('Item_model');
        // $this->load->model('Status_model');
        // $this->load->model('Pickup_model');
        // $this->load->helper('zid');
        // $this->load->helper('utility');
        // $this->load->model('User_model');
        //  $this->load->model('ItemInventory_model');
    }


    
    public function bulksms() {
        $this->load->view('bulksms/bulksms');
    }

    
    public function CompanyDetails() {
        
       
        if ($this->session->userdata('user_details')) {
            
            $zonelist = $this->zoneList();
            //print "<pre>"; print_r($list);die;
            $data['EditData'] = $this->General_model->GetallcompanyDetails();
            $data['TimeZone'] = $zonelist;
            $this->load->view('generalsetting/companydetails', $data);

            //$this->load->view('home');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function Smssend() {
        $AWB_NO = $this->input->post('show_awb_no');
        $AWB_array = explode("\n", $AWB_NO); 


        $AWB_array = array_map('trim', $AWB_array);
        $SMS = $this->input->post('show_SMS');

        $SlipNoArr = $this->Bulksms_model->shipmentdetail($AWB_array);
        // echo "<pre>" ; print_r($SlipNoArr);die;
        if(!empty($SlipNoArr)){
            foreach ($SlipNoArr as $key => $val) {
                $recievermessage = SEND_SMS($val['reciever_phone'],$SMS);
                $this->Bulksms_model->bulksmselog(trim($val['reciever_phone']), $val['slip_no'], $SMS );
            }
            $this->session->set_flashdata('msg', 'send message succesfully');
        }else{
            $this->session->set_flashdata('error', 'Shipments Not found !');
        }
       
   

        
 
    redirect(base_url('bulksms'));
    }

}
