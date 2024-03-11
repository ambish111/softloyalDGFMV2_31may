<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OpenShipment extends MY_Controller {

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

    public function validateDispatch() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST;
        $shipments = $this->Deliver_model->shipmetsInAwb_new($DataArray);

        $valid = array();
        $invalid = array();
        $invalidpallet = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {


                if (trim($data['code']) == 'POD') {
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

    public function validateDispatch_cancel() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST;
        $shipments = $this->Deliver_model->shipmetsInAwb_cancel($DataArray);

        $valid = array();
        $invalid = array();
        $invalidpallet = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {


                if (trim($data['code']) == 'RPC' || trim($data['code']) == 'FD' || trim($data['code']) == 'ROG' || trim($data['code']) == 'ROFD' || trim($data['code']) == 'RPOD' || trim($data['code']) == 'RIT' || trim($data['code']) == 'RPUC') {
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

    public function validateDispatch_og() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $DataArray = $_POST;
        $shipments = $this->Deliver_model->shipmetsInAwb_og($DataArray);

        $valid = array();
        $invalid = array();
        $invalidpallet = array();

        if (!empty($shipments['result'])) {
            foreach ($shipments['result'] as $data) {


                if (trim($data['code']) == 'UNDR') {
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
        $shipments = $this->Deliver_model->shipmetsInAwbAll_rop($_POST['awbArray']);
        //print_r($shipments); die;
        $code = 'ROP';
        $new_status = 18;

        $activity = "Return In Process";
        $details = 'Order Return In Process By ' . getUserNameById($this->session->userdata('user_details')['user_id']);

        $slip_data = array();
        $OutboundArray = array();
        $key = 0;
        $key1 = 0;
        $req_awb = array();
        foreach ($shipments['result'] as $data) {
            $responseData['status'] = 200;
            $responseData['awb'] = "";

            if ($responseData['status'] == 200) {
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

                $key++;
            } else {
                //echo print_r($responseData) ; exit;
                $error_data[$key1]['slip_no'] = $data['slip_no'];
                $error_data[$key1]['error'] = $responseData['error'];

                $key1++;
            }
        }

//print_r($OutboundArray);
//die;
        if (!empty($statusvalue) && !empty($slip_data)) {
            $this->Status_model->insertStatus($statusvalue);
            $this->Shipment_model->updateStatusBatch($slip_data);
        }

        echo json_encode($error_data);
    }

    public function cancelorderProcess() {


        $_POST = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Deliver_model->shipmetsInAwbAll_cancel($_POST['awbArray']);
        //print_r($shipments); die;
        $code = 'C';
        $new_status = 9;

        $activity = "Order Canceled";
        $details = 'Order Canceled By ' . getUserNameById($this->session->userdata('user_details')['user_id']);

        $slip_data = array();
        $OutboundArray = array();
        $key = 0;
        $key1 = 0;
        $req_awb = array();
        foreach ($shipments['result'] as $data) {
            $responseData['status'] = 200;
            $responseData['awb'] = "";

            if ($responseData['status'] == 200) {
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
                $slip_data[$key]['close_date'] = date('Y-m-d');

                $key++;
            } else {
                //echo print_r($responseData) ; exit;
                $error_data[$key1]['slip_no'] = $data['slip_no'];
                $error_data[$key1]['error'] = $responseData['error'];

                $key1++;
            }
        }

//print_r($OutboundArray);
//die;
        if (!empty($statusvalue) && !empty($slip_data)) {
            $this->Status_model->insertStatus($statusvalue);
            $this->Shipment_model->updateStatusBatch($slip_data);
        }

        echo json_encode($error_data);
    }

    public function openorderStatusProcess_og() {




        $_POST = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Deliver_model->shipmetsInAwbAll_og($_POST['awbArray']);
        //print_r($shipments); die;
        $code = 'OG';
        $new_status = 11;

        $activity = "Order Generated";
        $details = 'Order open By ' . getUserNameById($this->session->userdata('user_details')['user_id']);

        $slip_data = array();

        $key = 0;
        $key1 = 0;
        $req_awb = array();
        foreach ($shipments['result'] as $data) {
            $responseData['status'] = 200;
            $responseData['awb'] = "";

            if ($responseData['status'] == 200) {
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

                $key++;
            } else {
                //echo print_r($responseData) ; exit;
                $error_data[$key1]['slip_no'] = $data['slip_no'];
                $error_data[$key1]['error'] = $responseData['error'];

                $key1++;
            }
        }

//print_r($OutboundArray);
//die;
        if (!empty($statusvalue) && !empty($slip_data)) {
            $this->Status_model->insertStatus($statusvalue);
            $this->Shipment_model->updateStatusBatch($slip_data);
        }

        echo json_encode($error_data);
    }

    public function index() {
        if (menuIdExitsInPrivilageArray(155) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/openshipment');
    }

    public function open_og() {
        if (menuIdExitsInPrivilageArray(169) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('pickup/openshipment_og');
    }

    public function cancelReverseOrder() {
        if (menuIdExitsInPrivilageArray(229) == 'N') {
            redirect(base_url() . 'notfound');
             die;
        }


        $this->load->view('pickup/cancel_reverse');
    }

}

?>