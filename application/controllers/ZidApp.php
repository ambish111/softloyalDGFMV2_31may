<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ZidApp extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(215) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('ZidApp_model');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->helper('utility');
        $this->load->helper('salla');
        //$this->load->helper('form');
        //error_reporting(0);
    }

    // public function index() {
    //     //echo $this->session->userdata('user_details')['profile_pic']; die;	
    //     $data['usersrows'] = $this->ZidApp_model->all();
    //     $this->load->view('sallaApp/view_users', $data);
    // }



    public function new_request() {

        if (menuIdExitsInPrivilageArray(216) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('zidApp/new_request');
    }

    public function rejected_request() {

        if (menuIdExitsInPrivilageArray(217) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->view('zidApp/rejected_request');
    }

    public function accepted_request() {
        if (menuIdExitsInPrivilageArray(218) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('zidApp/accepted_request');
    }

    public function active_update($id = null, $status = null) {
        if ($id > 0 && ($status == 'Y' || $status == 'N')) {

            $updateArr = array('status' => $status);
            $this->ZidApp_model->GetUpdateUsers($id, $updateArr);

            if ($status == 'Y')
                $this->session->set_flashdata('msg', 'Active updated successfully!');
            else
                $this->session->set_flashdata('msg', 'Inactive updated successfully!');
        } else {
            $this->session->set_flashdata('err_msg', 'Try again');
        }
        redirect(base_url() . 'zidApp/accepted_request');
    }

    public function sallactiveview($id = null, $status = null) {
        $array = array('status' => $status);
        $this->ZidApp_model->UpdateQry($array, $id);
        if ($status == 'Y')
            $this->session->set_flashdata('succ_mess', 'Successfully Active Updated');
        else
            $this->session->set_flashdata('succ_mess', 'Successfully Inactive Updated');
        redirect('new_request');
        die;
    }

    public function saveassigntosalla() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        //print "<pre>"; print_r($postdata);die;
        if (!empty($postdata['customer_id']) && !empty($postdata['mid'])) {
            $customerID = $postdata['customer_id'];
            $sallaID = $postdata['mid'];
            //$salla_shipping_cost = $postdata['salla_shipping_cost'];
            $this->ZidApp_model->UpdateLinkToSalla($customerID, $sallaID);
            $return = array('status' => "succ");
            echo json_encode($return);
        }
    }

    public function sallaStatusUpdate() {
        $postdata = $_REQUEST;
        $this->ZidApp_model->updateSallaStatus($postdata['id'], $postdata['type']);
        $return = array('status' => "succ");
        echo json_encode($return);
    }

    
    public function sallaStatusUpdate_new() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        
       // echo "<pre>";
        //print_r($postdata); die;
        $main_data = $postdata['main'];
        $email = $main_data['email'];
        $check_customer = $this->ZidApp_model->getcheckcustomerData($email);
        if(!empty($email) && !empty($main_data['name']) && !empty($main_data['mobile']) && !empty($main_data['password']))
        {
        if ($check_customer['id'] > 0) {

                 $return = array('status' => "faield",'mess' => "this email id already exists");

                // $updateArr['zid_sid'] = $main_data['store_id'];
                // $updateArr['manager_token'] = $main_data['access_token'];
                // $updateArr['zid_expires_in'] = $main_data['expires_in'];
                // $updateArr['zid_authorization'] = $main_data['authorization'];
                // $updateArr['zid_refresh_token'] = $main_data['refresh_token'];
                // $updateArr['zid_access'] ='FM';
                // $updateArr['zid_active'] ='Y';
            
            
                // $this->ZidApp_model->UpdateCustomer($updateArr,$check_customer['id']);
          
        } else {
            $unique_acc_mp = time() . rand(10, 100);

            if(!empty($main_data['password']))
            {
                $pass=$main_data['password'];
            }
            else
            {
                  $pass = md5(123465);
            }

            // $pass = md5(123465);
            $secret_key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));

            $sync_product= $this->ZidApp_model->zid_data($main_data['store_id'],'sync_product');
            $dispatch_orders= $this->ZidApp_model->zid_data($main_data['store_id'],'dispatch_orders');
            
            if($dispatch_orders['dispatch_orders']=='Y')
            {
                $zid_status ='new';      
            }
            else
            {
                $zid_status ='ready';      
            }            

            $data = array(
                "uniqueid" => $unique_acc_mp,
                "secret_key" => $secret_key,
                "email" => $email,
                "password" => $pass,
                "name" => !empty($main_data['name'])?$main_data['name']:"",
                "company" => !empty($main_data['name'])?$main_data['name']:"",
                "phone" => !empty($main_data['mobile'])?$main_data['mobile']:"",
                "super_id" => $this->session->userdata('user_details')['super_id'],
                'access_fm' => 'Y',
                "zid_active" => "Y",
                "zid_access" => "FM",
                "zid_authorization"=>$main_data['authorization'],
                "manager_token" => $main_data['access_token'],
                "zid_refresh_token" => $main_data['refresh_token'],
                "zid_sid" => $main_data['store_id'],
                "zid_expires_in" => $main_data['expires_in'],
                "stock_update_auto_salla"=>"N",
                "stock_update_auto_zid"=>"N",
                "entrydate"=>date("Y-m-d"),
                "zid_status"=>$zid_status,
                "sync_product_zid"=>$sync_product['sync_product']
            );
            
            $this->ZidApp_model->addNewcustomer($data);
        }
       // echo "<pre>";
       // print_r($updateArr);
        // print_r($data);
       // die;
        $this->ZidApp_model->updateSallaStatus($main_data['id'], "L",$check_customer['id']);
        $return = array('status' => "succ");
        }
        else
        {
            $return = array('status' => "faield");
        }
        echo json_encode($return);
    }
    
    

    
    public function showasallatemplatelist() {
     
      
        $postdata = json_decode(file_get_contents('php://input'), true);
        $mainArray['customer'] = $this->ZidApp_model->sellerdata();

        $results = $this->ZidApp_model->showasallatemplatelistQry($postdata);

        $listData = $results;
        //print "<pre>"; print_r($listData);die;
        foreach ($listData as $key => $val) {

            $listData[$key]['log'] = json_decode($val['log']);
           
        }
        // print "<pre>"; print_r($listData);die;
        $mainArray['listdata'] = $listData;
        echo json_encode($mainArray);
    }

    
    public function getupdatepickupimagedata() {
        //$_POST = json_decode(file_get_contents('php://file'), true);
        // echo json_encode($_POST); die;

        $manifestid = $this->input->post('manifestid');
        if (!empty($manifestid) && !empty($_FILES['imagepath']['name'])) {
            if (!empty($_FILES['imagepath']['name'])) {
                $config['upload_path'] = 'assets/pickupfile/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['logo_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('imagepath')) {
                    $uploadData = $this->upload->data();
                    $small_img = $config['upload_path'] . '' . $uploadData['file_name'];
                }
            }
            $updateArray = array('code' => 'PU', 'pstatus' => 5, 'pickimg' => $small_img);
            $result = $this->ZidApp_model->getpickedupupdatestatus($updateArray, $manifestid);
        }
        echo json_encode($result);
    }

    public function GetallskuDetailsByOneGroup() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        $mid = $postdata['mid'];
        $returnresult = $this->ZidApp_model->GetallskuDetailsByOneGroupQry($mid);
        echo json_encode($returnresult);
    }

    public function GetreturnCourierDropShow() {
        $this->load->model('ZidApp_model');
        $PostData = json_decode(file_get_contents('php://input'), true);
        $assignuser = $this->ZidApp_model->userDropval(9);
        $courierData = GetCourierCompanyDrop();
        $return = array("assignuser" => $assignuser, "courierData" => $courierData);
        echo json_encode($return);
    }

    public function GetStaffListDrop() {
        $return = GetUserDropDownShowArr();
        echo json_encode($return);
    }

    public function GetUpdateStaffAssign() {
        $postdata = json_decode(file_get_contents('php://input'), true);

        if (!empty($postdata['staff_id'])) {
            $uniqueid = $postdata['mid'];
            $updateArr = array("staff_id" => $postdata['staff_id'], 'assign_date' => date("Y-m-d H:i:s"));

            // print_r($updateArr);
            $return = $this->ZidApp_model->GetUpdateStaffAssignQry($updateArr, $uniqueid);
        }
        // print_r($postdata);
        echo json_encode($return);
    }

}

?>