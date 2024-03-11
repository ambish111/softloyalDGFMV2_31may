<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SallaApp extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(162) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('SallaApp_model');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->helper('utility');
        $this->load->helper('salla');
        //$this->load->helper('form');
        //error_reporting(0);
    }

    // public function index() {
    //     //echo $this->session->userdata('user_details')['profile_pic']; die;	
    //     $data['usersrows'] = $this->SallaApp_model->all();
    //     $this->load->view('sallaApp/view_users', $data);
    // }



    public function new_request() {

        if (menuIdExitsInPrivilageArray(240) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }


        $this->load->view('sallaApp/new_request');
    }

    public function rejected_request() {

        if (menuIdExitsInPrivilageArray(241) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $this->load->view('sallaApp/rejected_request');
    }

    public function accepted_request() {
        if (menuIdExitsInPrivilageArray(242) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->view('sallaApp/accepted_request');
    }

    public function active_update($id = null, $status = null) {
        if ($id > 0 && ($status == 'Y' || $status == 'N')) {

            $updateArr = array('status' => $status);
            $this->SallaApp_model->GetUpdateUsers($id, $updateArr);

            if ($status == 'Y')
                $this->session->set_flashdata('msg', 'Active updated successfully!');
            else
                $this->session->set_flashdata('msg', 'Inactive updated successfully!');
        } else {
            $this->session->set_flashdata('err_msg', 'Try again');
        }
        redirect(base_url() . 'sallaApp/accepted_request');
    }

    public function sallactiveview($id = null, $status = null) {
        $array = array('status' => $status);
        $this->SallaApp_model->UpdateQry($array, $id);
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
            $app_mode = $postdata['app_mode'];
            if($app_mode=='Custom')
           {
               $salla_new=0;
           }
           else
           {
               $salla_new=1; 
           }
            $salla_shipping_cost = $postdata['salla_shipping_cost'];
            $this->SallaApp_model->UpdateLinkToSalla($customerID, $sallaID, $salla_shipping_cost,$salla_new);
            $return = array('status' => "succ");
            echo json_encode($return);
        }
    }

    public function sallaStatusUpdate() {
        $postdata = $_REQUEST;
        $this->SallaApp_model->updateSallaStatus($postdata['id'], $postdata['type']);
        $return = array('status' => "succ");
        echo json_encode($return);
    }

    
    public function sallaStatusUpdate_new() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        $main_data = $postdata['main'];
        $email = $main_data['email'];
        $check_customer = $this->SallaApp_model->getcheckcustomerData($email,$main_data['merchant_id']);
        if(!empty($email) && !empty($main_data['name']) && !empty($main_data['mobile']) && !empty($main_data['salla_shipping_cost']) && !empty($main_data['password']))
        {
            if ($check_customer['id'] > 0) {
                $return = array('status' => "faield",'mess' => "this email id already exists");
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
            
                $secret_key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
            $app_mode= $main_data['app_mode'];
            if($app_mode=='Custom')
            {
                $salla_new=0;
            }
            else
            {
                $salla_new=1; 
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
                    "salla_athentication" => $main_data['token'],
                    "salla_active" => "Y",
                    "salla_access" => "FM",
                    "salla_merchant_id"=>$main_data['merchant_id'],
                    "sala_refresh_token" => $main_data['salla_refresh_token'],
                    "stock_update_auto_salla"=>"N",
                    "stock_update_auto_zid"=>"N",
                    "sala_token_expiry" => $main_data['salla_expiry'],
                    "entrydate" => date("Y-m-d"),
                    "salla_new"=>$salla_new,
                    "salla_shipping_cost" => !empty($main_data['salla_shipping_cost']) ? $main_data['salla_shipping_cost'] : ""
                );
                
                $this->SallaApp_model->addNewcustomer($data);
                $this->SallaApp_model->updateSallaStatus($main_data['id'], "L");
                $return = array('status' => "succ",'mess'=>"Status Updated Successfully");

            }
       
        }
        else
        {
            $return = array('status' => "faield",'mess' => "all field are required!");
            
        }
        echo json_encode($return);
    }
    
    

    public function showasallatemplatelist() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        $mainArray['customer'] = $this->SallaApp_model->sellerdata();

        $results = $this->SallaApp_model->showasallatemplatelistQry($postdata);

        $listData = $results;
        //print "<pre>"; print_r($listData);die;
        foreach ($listData as $key => $val) {

            $listData[$key]['log'] = json_decode($val['log']);
            if ($val['status'] == 'L') {
                $listData[$key]['salla_shipping_cost'] = GetSallaShippingCost($val['merchant_id']);
            } else {
                $listData[$key]['salla_shipping_cost'] = '';
            }
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
            $result = $this->SallaApp_model->getpickedupupdatestatus($updateArray, $manifestid);
        }
        echo json_encode($result);
    }

    public function GetallskuDetailsByOneGroup() {
        $postdata = json_decode(file_get_contents('php://input'), true);
        $mid = $postdata['mid'];
        $returnresult = $this->SallaApp_model->GetallskuDetailsByOneGroupQry($mid);
        echo json_encode($returnresult);
    }

    public function GetreturnCourierDropShow() {
        $this->load->model('SallaApp_model');
        $PostData = json_decode(file_get_contents('php://input'), true);
        $assignuser = $this->SallaApp_model->userDropval(9);
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
            $return = $this->SallaApp_model->GetUpdateStaffAssignQry($updateArr, $uniqueid);
        }
        // print_r($postdata);
        echo json_encode($return);
    }

}

?>