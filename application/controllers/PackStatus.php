<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PackStatus extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Status_model');
        $this->load->model('Pickup_model');
        $this->load->model('Deliver_model');
        $this->load->helper('zid');
        $this->load->helper('utility');
    }

    public function validatePack() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST;
        $shipments = $this->Deliver_model->shipmetsInAwbAll_pack($DataArray);

        $valid = array();
        $invalid = array();
        $invalidpallet = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {


                if (trim($data['code']) == 'IT' || trim($data['code']) == 'FD' || trim($data['code']) == 'AP' || trim($data['code']) == 'DL' || trim($data['code']) == 'ROP' || trim($data['code']) == 'DOP') {
                    array_push($valid, $data);
                } else {

                    array_push($invalid, $data);
                }
            }
        } else {

            foreach ($DataArray as $key => $val) {
                $new_invallid = array('slip_no' => $val);
                array_push($invalid, $new_invallid);
            }
            //  print_r($new_invallid);
        }


        $returnData['valid'] = $valid;
        $returnData['invalidpallet'] = $invalidpallet;
        $returnData['invalid'] = $invalid;
        echo json_encode($returnData);
    }

    public function openorderStatusProcess() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Deliver_model->shipmetsInAwbAll_pack($_POST['awbArray']);
        //print_r($shipments); die;
        $code = 'PK';
        $new_status = 4;
       $status_type=$_POST['status_type'];
        

        $activity = "Order Packed Forced";
        $details = 'Order Packed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);

        $slip_data = array();
        
        $key = 0;
        $key1 = 0;
        $req_awb = array();
        $delivery_awb=array();
        foreach ($shipments['result'] as $data) {
            $responseData['status'] = 200;
            $responseData['awb'] = "";

            if ($responseData['status'] == 200) {
                if($status_type=='RDM')
                {
                     array_push($delivery_awb, $data['slip_no']);
                }
                
                array_push($req_awb, $data['slip_no']);
                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_no'];
                $statusvalue[$key]['new_status'] = $new_status;
                $statusvalue[$key]['code'] = $code;
                $statusvalue[$key]['Activites'] = $activity;
                if (!empty($_POST['comments'])) {
                    $statusvalue[$key]['comment'] = $_POST['comments'];
                } else {
                    $statusvalue[$key]['comment'] = "";
                }
                $statusvalue[$key]['Details'] = $details;
                $statusvalue[$key]['entry_date'] = date('Y-m-d H:i:s');
                $statusvalue[$key]['super_id'] = $this->session->userdata('user_details')['super_id'];
                /* -------------/Status Array----------- */


                $slip_data[$key] ['slip_no'] = $data['slip_no'];
                $slip_data[$key]['code'] = $code;
                $slip_data[$key]['delivered'] = $new_status;
                
                
               if($data['code']=='AP')
               {
                    $picklistValue[$key]['slip_no'] = $data['slip_no'];
                    $picklistValue[$key]['packedBy'] = $this->session->userdata('user_details')['user_id'];
                    $picklistValue[$key]['packDate'] = date('Y-m-d H:i:s');
                    $picklistValue[$key]['pickupDate'] = date('Y-m-d H:i:s');
                    $picklistValue[$key]['pickup_status'] = 'Y';
                    $picklistValue[$key]['picked_status'] = 'Y';
                    $picklistValue[$key]['pickedDate'] = date('Y-m-d H:i:s');
                   
               }


                $key++;
            } else {
                //echo print_r($responseData) ; exit;
                $error_data[$key1]['slip_no'] = $data['slip_no'];
                $error_data[$key1]['error'] = $responseData['error'];

                $key1++;
            }
        }

//print_r($slip_data);
//die;
        
        if (!empty($statusvalue) && !empty($slip_data)) {
            $this->Status_model->insertStatus($statusvalue);
            $this->Shipment_model->updateStatusBatch($slip_data);
            $this->Pickup_model->packOrder($picklistValue);
            
            if(!empty($delivery_awb))
            {
                $this->Deliver_model->removeDeliverymanifest($delivery_awb);
            }
        }

        echo json_encode($error_data);
    }

    public function index() {
        if (menuIdExitsInPrivilageArray(162) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/pack_force');
    }
    
    
    public function GetUpdatedamageStatus($id=null)
    {
        if($id>0)
        {
             $update=array("return_date"=>date("Y-m-d H:i:s"),'return_status'=>'Y');
             $req=$this->Deliver_model->update_damage_return_status($update,$id);
             if($req==true)
             {
             $this->session->set_flashdata('msg', 'Successfully updated!');
             }
             else
             {
                $this->session->set_flashdata('error', 'try again.');  
             }
        }
        else
        {
            $this->session->set_flashdata('error', 'something went wrong.');
             
        }
         redirect(base_url() . 'view_damage_inventory');
    }
    
    
       public function returnordersStockconfirm() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $ids=$postData['checkList'];
        if(!empty($ids))
        {
            $update=array("return_date"=>date("Y-m-d H:i:s"),'return_status'=>'Y');
            $this->Deliver_model->update_damage_return_status_multiple($update,$ids);
           $this->session->set_flashdata('msg', 'Successfully updated!'); 
        }
        else
        {
           $this->session->set_flashdata('error', 'something went wrong.'); 
        }
        
        echo json_encode(true);
       }

}

?>