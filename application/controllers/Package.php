<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Package extends MY_Controller {

    function __construct() {
        parent::__construct();
        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Package_model');
    }

    public function add() {
        // echo "ss"; die;
        if (menuIdExitsInPrivilageArray(234) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('package/add_form');
    }

    public function package_assign() {
        // echo "ss"; die;
        if (menuIdExitsInPrivilageArray(236) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $data['packageArr'] = $this->Package_model->package_list();
        $this->load->view('package/assign_customer', $data);
    }

    public function view_list() {
        if (menuIdExitsInPrivilageArray(235) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('package/view_list');
    }

    public function assigned_list() {
        if (menuIdExitsInPrivilageArray(237) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $data['packageArr'] = $this->Package_model->package_list();
        $this->load->view('package/assign_list', $data);
    }

    public function wallet_history() {
        if (menuIdExitsInPrivilageArray(238) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $data['packageArr'] = $this->Package_model->package_list();
        $this->load->view('package/assign_list_history', $data);
    }

    public function add_submit() {
        if (menuIdExitsInPrivilageArray(234) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('no_of_orders', 'Order Limit', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules("validity_days", 'Validity Days', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->add();
        } else {

            $date = date("Y-m-d H:i:s");
            $data = array(
                'name' => addslashes($this->input->post('name')),
                'no_of_orders' => $this->input->post('no_of_orders'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'details' => addslashes($this->input->post('details')),
                'created_at' => $date,
                'price' => $this->input->post('price'),
                'validity_days' => $this->input->post('validity_days'),
                'updated_at' => $date,
            );

            $req = $this->Package_model->add($data);
            $package_history['p_id'] = $req;
            $package_history['type'] = 'add';
            $package_history['user_id'] = $this->session->userdata('user_details')['user_id'];
            $package_history['super_id'] = $this->session->userdata('user_details')['super_id'];
            $package_history['entry_date'] = $date;
            $package_history['details'] = json_encode($data);
            $this->Package_model->add_history($package_history);
            if ($req == true) {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
            } else {
                $this->session->set_flashdata('error', 'try again');
            }
            redirect(base_url() . 'Package/view_list');
        }
    }

    public function assign_custmer_submit() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('seller_id', 'Customer', 'trim|required');
        $this->form_validation->set_rules('p_id', 'Package', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->package_assign();
        } else {

            $date = date("Y-m-d H:i:s");
            $current_date = date("Y-m-d");
            $p_id = $this->input->post('p_id');
            $seller_id = $this->input->post('seller_id');
            $returnPackage = $this->Package_model->package_list($p_id);
            $check_old_package = $this->Package_model->checkPackageActive($seller_id);
            if ($check_old_package['id'] > 0) {
                $start_date = $check_old_package['expiry'];
                $status = "N";
            } else {
                $status = "Y";
                $start_date = $current_date;
            }
            $packageArr = $returnPackage[0];
            if ($packageArr['validity_days'] == 1) {
                $expiry = date('Y-m-d', strtotime($start_date . ' +' . $packageArr['validity_days'] . ' days'));
            } else {
                $expiry = date('Y-m-d', strtotime($start_date . ' +' . $packageArr['validity_days'] . ' day'));
            }

            $package_assign['cust_id'] = $seller_id;
            $package_assign['p_id'] = $p_id;
            $package_assign['no_of_orders'] = $packageArr['no_of_orders'];
            $package_assign['order_limit'] = $packageArr['no_of_orders'];
            $package_assign['price'] = $packageArr['price'];
            $package_assign['super_id'] = $this->session->userdata('user_details')['super_id'];
            $package_assign['validity_days'] = $packageArr['validity_days'];
            $package_assign['start_date'] = $current_date;
            $package_assign['expiry'] = $expiry;
            $package_assign['entry_date'] = $date;
            $package_assign['status'] = $status;

            $package_history['cust_id'] = $seller_id;
            $package_history['p_id'] = $p_id;
            $package_history['super_id'] = $this->session->userdata('user_details')['super_id'];
            $package_history['added_by'] = $this->session->userdata('user_details')['user_id'];
            $package_history['cust_id'] = $seller_id;
            $package_history['p_qty'] = 0;
            $package_history['new_qty'] = $packageArr['no_of_orders'];
            $package_history['order_from'] = 'admin';
            $package_history['comment'] = 'Assign Package';
            $package_history['type'] = 'add';
            $package_history['entry_date'] = $date;

            //print_r($package_history); die;
            if ($this->Package_model->assign_customer($package_assign)) {
                $this->Package_model->package_assign_history($package_history);
                $this->session->set_flashdata('msg', ' successfully Updated!');
            } else {
                $this->session->set_flashdata('error', 'try again');
            }
            redirect(base_url() . 'Package/assigned_list');
        }
    }

    public function filter() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Package_model->filter($postData);
        $total_package = $listArr['count'];
        $dataArray['result'] = $listArr['result'];
        $dataArray['count'] = $total_package;
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function filter_assign() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Package_model->filter_assign($postData);
        $total_package = $listArr['count'];
        $mainArr = $listArr['result'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $total_package;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        foreach ($mainArr as $key => $val) {
            $mainArr[$key]['cust_name'] = GetallCutomerBysellerId($val['cust_id'], 'company');
            $mainArr[$key]['package_name'] = package_details_by_field($val['p_id'], 'name');
        }
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $mainArr;
        $dataArray['count'] = $total_package;
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function filter_wallet() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Package_model->filter_wallet($postData);
        $total_package = $listArr['count'];
        $mainArr = $listArr['result'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $total_package;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        foreach ($mainArr as $key => $val) {
            $mainArr[$key]['cust_name'] = GetallCutomerBysellerId($val['cust_id'], 'company');
            $mainArr[$key]['package_name'] = package_details_by_field($val['p_id'], 'name');
            $mainArr[$key]['username'] = getUserNameById_field($val['added_by'], 'company');
        }
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $mainArr;
        $dataArray['count'] = $total_package;
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    public function getexceldata() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataAray = $this->Package_model->alllistexcelData($_POST);

        $file_name = 'Assigned List.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

    public function getexceldata_wallet() {

        // echo "sssss"; die;
        $_POST = json_decode(file_get_contents('php://input'), true);

        $dataAray = $this->Package_model->alllistexcelData_wallet($_POST);

        $file_name = 'Wallet History.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($dataAray)
        );
        echo json_encode($response);
    }

    public function getSyncpackage() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $activePlans = $this->Package_model->activeplans($postData);
        
       // print_r($activePlans); die;
        
        if (count($activePlans) == 0) {
            $results = $this->Package_model->getSyncpackage($postData);
            if(!empty($results))
            {
            $data=array("status"=>'Y');
            $data_w=array("id"=>$results['id']);
            $this->Package_model->updateplan($data,$data_w);
           
            $sql="update assign_package set status='N' where cust_id='".$postData['seller_id']."' and order_limit=0 and super_id='".$this->session->userdata('user_details')['super_id']."'";
             
            
             $this->Package_model->updatepackage($sql);
            $return=array('status'=>"succ","mess"=>"Successfully Updated");
            }
            else
            {
               $return=array('status'=>"succ","mess"=>"Please assign package");  
            }
        }
        else
        {
            
            $return=array('status'=>"error","mess"=>"This user's package has not expired. please try again later");
            
        }
        echo json_encode($return);
    }

}

?>