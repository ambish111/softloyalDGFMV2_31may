<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Onhold extends MY_Controller {

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
        $this->load->model('Onhold_model');
    }

    public function validate() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST['awbArray'];
        $type = $_POST['type'];
        $shipments = $this->Onhold_model->shipmetsInAwb_new($DataArray, $type);

        $valid = array();
        $invalid = array();
        $invalidpallet = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {
                if (trim($data['code']) == 'OC' || trim($data['code']) == 'OG') {
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

    public function updateOnHold() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $type = $_POST['type'];
        $shipments = $this->Onhold_model->updateOnHold($_POST['awbArray'], $type);
        //print_r($shipments);
       // die;
       
         if ($type == 'Yes') {
            $details = 'Order On Hold By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
        } else {
            $details = 'Order On Removed By ' . getUserNameById($this->session->userdata('user_details')['user_id']);
        }

        $slip_data = array();
        $key = 0;

        $req_awb = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {
                if ($data['code'] == 'OG') {
                    $activity = "Order Generated";
                } else {
                    $activity = "Order Created";
                }
                array_push($req_awb, $data['slip_no']);
                $statusvalue[$key]['user_id'] = $this->session->userdata('user_details')['user_id'];
                $statusvalue[$key]['user_type'] = 'fulfillment';
                $statusvalue[$key]['slip_no'] = $data['slip_no'];
                $statusvalue[$key]['new_status'] = $data['delivered'];
                $statusvalue[$key]['code'] = $data['code'];
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

                if ($type == 'Yes') {
                    $slip_data[$key] ['slip_no'] = $data['slip_no'];
                    $slip_data[$key]['on_hold_date'] = date('Y-m-d H:i:s');
                    $slip_data[$key]['on_hold'] = 'Yes';
                } else {
                    $slip_data[$key] ['slip_no'] = $data['slip_no'];
                    $slip_data[$key]['on_hold_date'] = NULL;
                    $slip_data[$key]['on_hold'] = 'No';
                }

                $key++;
            }

            //print_r($slip_data);
           // die;
            if (!empty($statusvalue) && !empty($slip_data)) {
                $this->Status_model->insertStatus($statusvalue);
                $this->Shipment_model->updateStatusBatch($slip_data);
            }
        } else {
            $error_data[0]['AWB'] = $_POST['awbArray'];
            $error_data[0]['error'] = "not found";
        }

        echo json_encode($error_data);
    }

    public function index() {
        if (menuIdExitsInPrivilageArray(232) == 'N') {
             redirect(base_url() . 'notfound');
             die;
        }


        $this->load->view('pickup/onhold');
    }

}

?>