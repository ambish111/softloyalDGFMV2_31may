<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Logs_model');
    }

    public function index() {

        if (menuIdExitsInPrivilageArray(165) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->view('logs/booked_qty_logs');
    }

    public function filter() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $shipments = $this->Logs_model->filter($postData);
        $shiparray = $shipments['result'];
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];

        echo json_encode($dataArray);
    }

}

?>