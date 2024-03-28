<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(4) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');
        $this->load->model('ItemInventory_model');
        $this->load->library('form_validation');
        $this->load->model('CourierSeller_model');
        $this->load->model('Storage_model');
        $this->load->helper('zid_helper');
    }

    public function index() {
        $data['sellers'] = $this->Seller_model->all();

        $this->load->view('SellerM/view_sellers', $data);
    }

    public function add_view() {

        if (($this->session->userdata('user_details') != '')) {
            $data['customers'] = $this->Seller_model->customers();
            $data['city_drp'] = $this->Seller_model->fetch_all_cities();

            $this->load->view('SellerM/add_seller', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function active_seller($id = null, $status = null) {

        if (($this->session->userdata('user_details') != '')) {

            if ($id > 0 && ($status == 'Y' || $status == 'N')) {
                $updateArr = array('status' => $status);
                $this->Seller_model->edit($id, $updateArr);

                if ($status == 'Y') {
                    $this->session->set_flashdata('msg', 'has been updated Active successfully');
                } else {
                    $this->session->set_flashdata('msg', 'has been updated Inactive successfully');
                }
            } else {
                $this->session->set_flashdata('errmsg', 'try again');
            }

            redirect('Seller');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function active_wallet($id = null, $status = null) {

        if (($this->session->userdata('user_details') != '')) {

            if ($id > 0 && ($status == 'Y' || $status == 'N')) {
                $updateArr = array('wallet' => $status);
                $this->Seller_model->edit($id, $updateArr);

                if ($status == 'Y') {
                    $this->session->set_flashdata('msg', 'has been updated Active successfully');
                } else {
                    $this->session->set_flashdata('msg', 'has been updated Inactive successfully');
                }
            } else {
                $this->session->set_flashdata('errmsg', 'try again');
            }

            redirect('Seller');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function autoactive_seller($id = null, $status = null) {

        if (($this->session->userdata('user_details') != '')) {

            if ($id > 0 && ($status == 'Y' || $status == 'N')) {
                $updateArr = array('autoorder' => $status);
                $this->Seller_model->edit($id, $updateArr);

                if ($status == 'Y') {
                    $this->session->set_flashdata('msg', 'has been updated Active successfully');
                } else {
                    $this->session->set_flashdata('msg', 'has been updated Inactive successfully');
                }
            } else {
                $this->session->set_flashdata('errmsg', 'try again');
            }

            redirect('Seller');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function add() {

        $this->form_validation->set_rules("email", 'Email Address', 'trim|required|is_unique[customer.email]');
        $this->form_validation->set_rules("password", 'Password ', 'trim|required|min_length[6]');
        $this->form_validation->set_rules("city_drop", 'City', 'trim|required');
        $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|matches[password]');

        //die(print_r($this->input->post()));
        if ($this->input->post('zid_active') == 'Y') {
            $this->form_validation->set_rules("manager_token", 'X-MANAGER-TOKEN', 'required');
            $this->form_validation->set_rules('user_Agent', 'User-Agent', 'required');
            $this->form_validation->set_rules('zid_sid', 'Zid Store ID', 'required');
        }
        if ($this->input->post('salla_active') == 'Y') {
            $this->form_validation->set_rules("salla_manager_token", 'X-MANAGER-TOKEN ', 'required');
        }
        if ($this->form_validation->run() == FALSE) {

            $this->add_view();
        } else {
            if (!empty($this->input->post('zid_active'))) {
                $zid_active = 'Y';
            } else {
                $zid_active = 'N';
            }

            if (!empty($this->input->post('salla_active'))) {
                $salla_active = 'Y';
            } else {
                $salla_active = 'N';
            }

            //echo "sssss"; die;
            // print_r($_POST); die;
            $unique_acc_mp = time() . rand(10, 100);

            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'location' => $this->input->post('address'),
                'phone' => $this->input->post('phone1'),
                'account_no' => $unique_acc_mp,
                'phone2' => $this->input->post('phone2'));

            //print_r($data); die;
            // 'warehousing_charge'=>$this->input->post('warehousing_charge'),
            // 'fulfillment_charge'=>$this->input->post('fulfillment_charge'),
            // 'cbm_no'=>$this->input->post('cbm_no'));
            // print_r($data);
            // print_r($data);exit;
            // $seller_id = $this->Seller_model->add($data);
            // $password1=$this->input->post('password');
            // $conf_password=$this->input->post('conf_password');
            // echo "first1".$password1."1".$conf_password;
            if (!empty($this->input->post('password'))) {

                if ($this->input->post('password') != $this->input->post('conf_password')) {
                    $errors = "Confirm password mismatch";
                } else
                    $pass = md5($_REQUEST['password']);
            } else {
                $pass = " ";
            }


            if (!empty($_FILES['upload_cr']['name'])) {
                $config['upload_path'] = '../fs_files/cust_upload/';
                $upload_path = 'cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['upload_cr']['name'];
                $config['file_name'] = time() . 'cr';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('upload_cr')) {
                    $uploadData = $this->upload->data();
                    $path_upload_cr = $upload_path . '' . $uploadData['file_name'];
                }
            } else
                $path_upload_cr = "";

            if (!empty($_FILES['upload_id']['name'])) {
                $config['upload_path'] = '../fs_files/cust_upload/';
                $upload_path = 'cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['upload_id']['name'];
                $config['file_name'] = time() . 'upid';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('upload_id')) {
                    $uploadData = $this->upload->data();
                    $path_upload_id = $upload_path . '' . $uploadData['file_name'];
                }
            } else
                $path_upload_id = "";
            if (!empty($_FILES['upload_contact']['name'])) {
                $config['upload_path'] = '../fs_files/cust_upload/';
                $upload_path = 'cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['upload_contact']['name'];
                $config['file_name'] = time() . 'ctc';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('upload_contact')) {
                    $uploadData = $this->upload->data();
                    $path_upload_contact = $upload_path . '' . $uploadData['file_name'];
                }
            } else {
                $path_upload_contact = "";
            }

            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = '../fs_files/cust_upload/';
                $upload_path = 'cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name'] = $_FILES['image']['name'];
                $config['file_name'] = time() . 'img';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('image')) {
                    $uploadData = $this->upload->data();
                    $path_image = $upload_path . '' . $uploadData['file_name'];
                }
            } else {
                $path_image = "";
            }

            if (empty($this->input->post('from'))) {
                $salla_from_date = "";
            } else {
                $salla_from_date = $this->input->post('from');
            }

            if (empty($this->input->post('order_status'))) {
                $order_status = "";
            } else {
                $order_status = $this->input->post('order_status');
            }

            $discount = $this->input->post('discount');
            $discount_f = $this->input->post('discount_f');
            $discount_to = $this->input->post('discount_to');
            if ($discount == 1)
                $discount = 1;
            else
                $discount = 0;
            $u_type = $this->input->post('name');
            if ($u_type == 'B2B') {
                $u_type = "B2B";
            } else {
                $u_type = "B2C";
            }
            $secret_key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6)) . $seller_id;
            $customer_info = array(
                'u_type' => $u_type,
                'name' => $this->input->post('name'),
                'uniqueid' => $unique_acc_mp,
                'seller_id' => 0,
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'account_number' => $this->input->post('account_number'),
                'phone' => $this->input->post('phone1'),
                'fax' => $this->input->post('phone2'),
                'iban_number' => $this->input->post('iban_number'),
                'password' => $pass,
                'entrydate' => $this->input->post('entrydate'),
                'managerMobileNo' => $this->input->post('managerMobileNo'),
                'managerEmail' => $this->input->post('managerEmail'),
                'iban_number' => $this->input->post('iban_number'),
                'bank_fees' => $this->input->post('bankfee'),
                'vat_no' => $this->input->post('vat_no'),
                'upload_cr' => $path_upload_cr,
                'upload_id' => $path_upload_id,
                'upload_contact' => $path_upload_contact,
                'account_manager' => $this->input->post('account_manager'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city_drop'),
                'store_link' => $this->input->post('store_link'),
                'access_fm' => 'Y',
                'secret_key' => $secret_key,
                'bank_name' => $this->input->post('bank_name'),
                // 'manager_token' => $this->input->post('manager_token'),
                // 'salla_athentication' => $this->input->post('salla_manager_token'),
                'user_Agent' => $this->input->post('user_Agent'),
                'access_lm' => $this->input->post('access_lm'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                //'salla_active' => $salla_active,
                'auto_forward' => $this->input->post('auto_forward'),
                //'zid_active' => $zid_active,
                // 'salla_from_date' => $salla_from_date,
                'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $this->input->post('first_out'),
                'discount' => $discount,
                'discount_f' => $discount_f,
                'discount_to' => $discount_to,
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'area' => $this->input->post('area'),
                'image' => $path_image,
                'tracking_webhook' => $this->input->post('tracking_webhook'),
                'agent_id' => $this->input->post('agent_id'),
                'label_info_from' => $this->input->post('label_info'),
                'hide_email' => $this->input->post('hide_email'),
                    //'zid_sid' => $this->input->post('zid_sid'),
                    //'zid_status' => $this->input->post('zid_status'),
            );

            // $this->Seller_model->customer($seller_id,$customer_id);
            if (empty($errors)) {

                $customer_id = $this->Seller_model->add_customer($customer_info);
//                if($this->session->userdata('user_details')['super_id']==175)
//                {
//                    $n_process=array("cust_id"=>$customer_id,"super_id"=>$this->session->userdata('user_details')['super_id']);
//                    $this->Seller_model->new_process_customer($n_process);
//                    
//                }
                //echo $this->db->last_query();  
                //  die;
                // $this->Seller_model->update_seller_id($seller_id, $customer_id);
                //// echo  $customer_id.'//'. $seller_id;     exit();  
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
            } else {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   Customer adding is failed');
            }

            // die;

            redirect('Seller');
        }
    }

    Public function add_courier_company($id = Null) {
        $data['id'] = $id;
        $this->load->view('SellerM/add_courier_company', $data);
    }

    public function updateCourier() {
        $dataArray = $this->input->post();
        $idArray = $dataArray['id'];
        $data = array();
        foreach ($idArray as $id) {
            array_push($data, array('id' => $id, 'priority' => $dataArray['priority'][$id], 'status' => $dataArray['status'][$id]));
        }
        if ($data) {
            $this->CourierSeller_model->updateCourier($data);
            $this->session->set_flashdata('msg', 'has been Courier Set successfully');
        }


        redirect(base_url() . 'Seller');
        // print_r($data); die();
    }

    public function set_courier($id) {
        $data['fullfilment_drp'] = $this->CourierSeller_model->getSellerAddCourier($id);
        //echo "<pre>"; print_r($data);  die; 
        $this->load->view('SellerM/set_courier', $data);
    }

    public function storage_charges($id) {
        $data['fullfilment_drp'] = $this->Storage_model->getSellerStorageCharges($id);
        // echo "<pre>"; print_r($data);  die; 
        $this->load->view('SellerM/storage_charges', $data);
    }

    public function add_storagecharges($id = null) {
        $view['editid'] = $id;
        // $view['editdata']=$this->Storage_model->editviewquery($id); 
        $this->load->view('SellerM/add_storagecharges', $view);
    }

    public function edit_view($id) {
        // $id = $this->input->get('id');
        $data['seller'] = $this->Seller_model->edit_view($id);
        //$data['city_drp'] = $this->Seller_model->fetch_all_cities();
        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);

        $this->load->view('SellerM/seller_detail', $data);
    }

    public function edit($id) {
        //$id=$this->input->post('id');
        //echo "<pre>";print_r($this->input->post());exit;


        if (!empty($_FILES['upload_cr']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_cr']['name'];
            $config['file_name'] = time() . 'cr';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_cr')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/' . $this->input->post('upload_cr_old'));
                $path_upload_cr = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_cr = $this->input->post('upload_cr_old');

        if (!empty($_FILES['upload_id']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_id']['name'];
            $config['file_name'] = time() . 'upid';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_id')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/' . $this->input->post('upload_id_old'));
                $path_upload_id = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_id = $this->input->post('upload_id_old');


        if (!empty($_FILES['upload_contact']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_contact']['name'];
            $config['file_name'] = time() . 'ctc';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_contact')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/' . $this->input->post('upload_contact_old'));
                $path_upload_contact = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_contact = $this->input->post('upload_contact_old');

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name'] = $_FILES['image']['name'];
            $config['file_name'] = time() . 'img';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('image')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/cust_upload/' . $this->input->post('image'));
                $path_image = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_image = $this->input->post('image_old');

        if ($this->input->post('zid_active') == 'Y') {
            $zid_access = 'FM';
        }


        //echo $path_upload_contact; die;
        $u_type = $this->input->post('u_type');
        if ($u_type == 'B2B') {
            $u_type = "B2B";
        } else {
            $u_type = "B2C";
        }
        $first_out = $this->input->post('first_out');

        $discount = $this->input->post('discount');
        if ($discount == 1)
            $discount = 1;
        else
            $discount = 0;


        $discount_f = $this->input->post('discount_f');
        $discount_to = $this->input->post('discount_to');

        if (!empty($this->input->post('password'))) {
            $customer_info = array(
                'u_type' => $u_type,
                'name' => $this->input->post('name'),
                'account_number' => $this->input->post('account_number'),
                'phone' => $this->input->post('phone1'),
                'fax' => $this->input->post('phone2'),
                'iban_number' => $this->input->post('iban_number'),
                'company' => $this->input->post('company'),
                'entrydate' => $this->input->post('entrydate'),
                'password' => md5($this->input->post('password')),
                'managerMobileNo' => $this->input->post('managerMobileNo'),
                'managerEmail' => $this->input->post('managerEmail'),
                'iban_number' => $this->input->post('iban_number'),
                'bank_fees' => $this->input->post('bankfee'),
                'vat_no' => $this->input->post('vat_no'),
                'upload_cr' => $path_upload_cr,
                'upload_id' => $path_upload_id,
                'upload_contact' => $path_upload_contact,
                'account_manager' => $this->input->post('account_manager'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city_drop'),
                'store_link' => $this->input->post('store_link'),
                'access_lm' => $this->input->post('access_lm'),
                //'bank_name' => $this->input->post('bank_name'),
                //'zid_active' => $this->input->post('zid_active'),
                //'manager_token' => $this->input->post('manager_token'),
                //'user_Agent' => $this->input->post('user_Agent'),
                'auto_forward' => $this->input->post('auto_forward'),
                //'salla_athentication' => $this->input->post('salla_manager_token'),
                // 'salla_from_date' => $this->input->post('from'),
                // 'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $first_out,
                //'zid_access' => $zid_access,
                'discount' => $discount,
                'discount_f' => $discount_f,
                'discount_to' => $discount_to,
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'image' => $path_image,
                'area' => $this->input->post('area'),
                'tracking_webhook' => $this->input->post('tracking_webhook'),
                'label_info_from' => $this->input->post('label_info'),
                'hide_email' => $this->input->post('hide_email'),
                    // 'zid_sid' => $this->input->post('zid_sid'),
                    // 'zid_status' => $this->input->post('zid_status'),
            );
        } else {
            $customer_info = array(
                'u_type' => $u_type,
                'name' => $this->input->post('name'),
                'account_number' => $this->input->post('account_number'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1'),
                'fax' => $this->input->post('phone2'),
                'iban_number' => $this->input->post('iban_number'),
                'managerMobileNo' => $this->input->post('managerMobileNo'),
                'entrydate' => $this->input->post('entrydate'),
                'managerEmail' => $this->input->post('managerEmail'),
                'iban_number' => $this->input->post('iban_number'),
                'bank_fees' => $this->input->post('bankfee'),
                'vat_no' => $this->input->post('vat_no'),
                'upload_cr' => $path_upload_cr,
                'upload_id' => $path_upload_id,
                'upload_contact' => $path_upload_contact,
                'account_manager' => $this->input->post('account_manager'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city_drop'),
                'store_link' => $this->input->post('store_link'),
                'access_lm' => $this->input->post('access_lm'),
                'bank_name' => $this->input->post('bank_name'),
                //'zid_active' => $this->input->post('zid_active'),
                // 'manager_token' => $this->input->post('manager_token'),
                // 'user_Agent' => $this->input->post('user_Agent'),
                'auto_forward' => $this->input->post('auto_forward'),
                //'salla_athentication' => $this->input->post('salla_manager_token'),
                //'salla_from_date' => $this->input->post('from'),
                //'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $first_out,
                //'zid_access' => $zid_access,
                'discount' => $discount,
                'discount_f' => $discount_f,
                'discount_to' => $discount_to,
                'lat' => $this->input->post('lat'),
                'lng' => $this->input->post('lng'),
                'area' => $this->input->post('area'),
                'image' => $path_image,
                'tracking_webhook' => $this->input->post('tracking_webhook'),
                'label_info_from' => $this->input->post('label_info'),
                'hide_email' => $this->input->post('hide_email'),
                    //'zid_sid' => $this->input->post('zid_sid'),
                    //'zid_status' => $this->input->post('zid_status'),
            );
        }

        // echo "<pre>";print_r($customer_info);exit();
        // $this->Seller_model->edit($id, $data);
        $this->Seller_model->edit_custimer($id, $customer_info);
        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        redirect('Seller');
    }

    public function report_view($id = null) {


//error_reporting(E_ALL);
//ini_set('display_errors', '1');
        $data['status'] = $this->Shipment_model->allstatus();
        $data['total_inventory_items'] = $this->ItemInventory_model->count_find($id);
        $data['seller_info'] = $this->Seller_model->find($id);
        $data['customer_info'] = $this->Seller_model->find_customer($id);

        // print_r($data['seller_info']);
        // exit();
        $data['seller_shipments'] = $this->Shipment_model->find_by_seller($id);

        if ($data['seller_shipments'] != Null) {
            // 	print('<pre>');
            // print_r($data['seller_shipments']);
            // 	print('</pre>');
            // exit();
            // for($i=0;$i<count($data['seller_shipments']);$i++)
            // {
            $array = array(
                'item_inventory.seller_id' => $id,
                    //'item_sku'=>$data['seller_shipments'][$i]->sku
            );
            // print_r($data['seller_shipments'][$i]);
            // exit();

            $data['item_inventory'] = $this->ItemInventory_model->find_by_seller($array);

            //}
            // print('<pre>');
            // print_r($data['item_inventory']);
            // print('</pre>');
            // exit();
            // print_r($data['seller_shipments']);
            //   exit();
            ///////////////////////////////////////////////////////////////////////////////////
            // for($i=0;$i<count($data['total_inventory_items']);$i++)
            // {
            // 	$array= array(
            // 		'seller_id' =>$id,  
            // 	);
            // 	$item_inventory_all[$i]=$this->ItemInventory_model->find_by_seller($array);
            // }
            // $data['items']=$this->Item_model->all();
            /////////////////////////////////////////////////////////////////////////////////////
            // print('<pre>');
            // print_r($item_inventory_all);
            // print('</pre>');
            // exit();
            // print_r($data['seller_shipments']);
            // print_r($item_inventory);
            //  exit();
            //print_r($data['status']);
            //print_r($data['item_inventory']);
            //print_r($data['total_inventory_items']);
            //print_r($data['seller_shipments']);
            //print_r($data['seller_info']);
            //exit();
            //$info=array(
            // 'item_inventory'=>$item_inventory,
            // 'item_inventory_all'=>$item_inventory_all,
            //'data'=>$data
            //);
            // print_r($item_inventory);
            // exit();
            // print_r($data['seller_shipments'][0]->sku);
            // exit();
            $this->load->view('SellerM/seller_report', $data);
        } elseif ($data['seller_shipments'] == Null) {
            // for($i=0;$i<count($data['total_inventory_items']);$i++)
            // 		{
            // 			$array= array(
            // 				'seller_id' =>$id,  
            // 			);
            // 			$item_inventory_all[$i]=$this->ItemInventory_model->find_by_seller($array);
            // 		}
            // 		$data['items']=$this->Item_model->all();
            // 	$info=array(
            // 	'item_inventory_all'=>$item_inventory_all,
            // 	'data'=>$data
            // );
            // print_r($data['seller_shipments']);
            // exit();
            $this->load->view('SellerM/seller_report', $data);
        }
    }

    public function ZidProducts_bk_17_05_2023($id) {

        $sync_product_zid = GetallCutomerBysellerId($id, 'sync_product_zid');
        if ($sync_product_zid == 'Y') {

            $data['zidproducts'] = $this->Seller_model->zidproduct($id);
            $storeID = $data['zidproducts'];
            $token = GetallCutomerBysellerId($id, 'manager_token');
            $store_link = "https://api.zid.sa/v1/products";
            $bearer = site_configTable('zid_provider_token');
            $ZidProductRT = ZidPcURL($storeID, $store_link, $bearer, $token);

            // print_r( $ZidProductRT); exit;
            $ZidProductArr_total = json_decode($ZidProductRT, true);
            //  echo '<pre>';
            $total_pages = 1;
            if ($ZidProductArr_total['count'] > 100) {
                $total_pages = ceil($ZidProductArr_total['count'] / 100);
            }
            if (empty($this->input->post('i'))) {
                $i = 1;
            } else {
                $i = $this->input->post('i');
            }
            $results = array();
            $results2 = array();
            $p = 0;
            $s = 0;

            $storlink_page = "https://api.zid.sa/v1/products?page=" . $i . "&page_size=100";
            $ZidProductArr = ZidPcURL($storeID, $storlink_page, $bearer, $token);
            $ZidProductArr = json_decode($ZidProductArr, true);

            if (isset($ZidProductArr['results'])) {
                foreach ($ZidProductArr['results'] as $key => $products) {

                    if (isset($products['structure']) && $products['structure'] == 'parent') {
                        $product_link = $store_link . '/' . $products['id'] . '/';

                        $product = json_decode(ZidPcURL($storeID, $product_link, $bearer, $token), true);

                        if (!empty($product)) {
                            if (count($product['variants']) > 0) {

                                foreach ($product['variants'] as $key => $variant) {
                                    $variant['images'] = $product['images'];
                                    $results[] = $variant;
                                }
                            } else {

                                $results[] = $product;
                            }
                        }
                    } else {

                        $results2[] = $products;
                    }
                }
            }

            $final_Arr = array_merge($results, $results2);
            // echo '<pre>';
            // print_r( $results); exit;

            $ZidProducts['products'] = $final_Arr;
            $ZidProducts['total_pages'] = $total_pages;
            $ZidProducts['current_page'] = $i;
            $ZidProducts['seller_id'] = $id;

            $this->load->view('SellerM/view_zidp', $ZidProducts);
        } else {
            $this->session->set_flashdata('msg', "product list not active from zid");
            redirect(base_url() . 'Seller');
            die;
        }
    }

    public function ZidProducts($id) {

        $target_url = basename($_SERVER['REQUEST_URI']);

        if (empty($this->input->post('i'))) {
            $i = 1;
        } else {
            $i = $this->input->post('i');
        }

        $search_zid_id = $this->input->post('search_zid_id');
        // if(!empty($sku)){die("Hi");
        //     $sku = urlencode($sku);
        // }
        $exist = $this->input->post('exist');

        if (!empty($exist)) {
            $i = $this->input->post('pageno');
        }
        if (empty($exist)) {
            $exist = $this->input->post('aleradyexist');
        }

        $per_page = 50;

        $data['zidproducts'] = $this->Seller_model->zidproduct($id);
        $storeID = $data['zidproducts'];
        $zid_authorization = GetallCutomerBysellerId($id, 'zid_authorization');
        $token = GetallCutomerBysellerId($id, 'manager_token');
        // $store_link = "https://api.zid.sa/v1/products";
        if ($zid_authorization != NULL) {
            $bearer = $zid_authorization;
        } else {
            $bearer = site_configTable('zid_provider_token');
        }
        //page_size=$per_page&
        if (!empty($search_zid_id)) {
            $store_link = "https://api.zid.sa/v1/products/$search_zid_id";
        } else {
            $store_link = "https://api.zid.sa/v1/products/?page_size=$per_page&page=$i";
        }
        //  echo $bearer; die;
        // echo $store_link; die;
        $store_link_s = "https://api.zid.sa/v1/products";
        // $store_link = "https://api.zid.sa/v1/products?search=".$sku;
        //    echo $store_link;die;


        if (empty($search_zid_id)) {

            $ZidProductRT = ZidPcURL($storeID, $store_link, $bearer, $token);
        }
        // print_r($ZidProductRT); die;
        $ZidProductArr = json_decode($ZidProductRT, true);
        //echo "<pre>"; print_r($ZidProductArr); die;

        $total_pages = ceil($ZidProductArr['count'] / 50);
//echo $total_pages; die;
        $results = array();
        $results2 = array();
        $p = 0;
        $s = 0;
        if (!empty($search_zid_id)) {
            $product = json_decode(ZidPcURL($storeID, $store_link, $bearer, $token), true);
            if (!empty($product)) {
                if (count($product['variants']) > 0) {

                    foreach ($product['variants'] as $key => $variant) {
                        $variant['images'] = $product['images'];
                        $results[] = $variant;
                    }
                } else {
                    $results[] = $product;
                }
            }
        }
        if (isset($ZidProductArr['results'])) {
            foreach ($ZidProductArr['results'] as $key => $products) {

                if (isset($products['structure']) && $products['structure'] == 'parent') {
                    $product_link = $store_link_s . '/' . $products['id'] . '/';

                    // echo $product_link."<br>";
                    $product = json_decode(ZidPcURL($storeID, $product_link, $bearer, $token), true);
                    // print_r($product); 
                    if (!empty($product)) {
                        if (count($product['variants']) > 0) {

                            foreach ($product['variants'] as $key => $variant) {
                                $variant['images'] = $product['images'];
                                $results[] = $variant;
                            }
                        } else {
                            $results[] = $product;
                        }
                    }
                } else {
                    $results2[] = $products;
                }
            }
        }


        $final_Arr = array_merge($results, $results2);
        if ($exist == 'Yes') {
            foreach ($final_Arr as $key => $val) {
                $is_exist = exist_zidsku_id($val['sku'], $this->session->userdata('user_details')['super_id']);

                if (!empty($is_exist)) {
                    $existArr[$key] = $val;
                }
            }
        }
        if ($exist == 'No') {
            foreach ($final_Arr as $key => $val) {
                $is_exist = exist_zidsku_id($val['sku'], $this->session->userdata('user_details')['super_id']);
                if (empty($is_exist)) {
                    $existArr[$key] = $val;
                }
            }
        }

        $count = $ZidProductArr['count'];
        $perPage = 50;
        if (!empty($exist)) {
            $ZidProducts['products'] = $existArr;
        } else {
            $ZidProducts['products'] = $final_Arr;
        }

        //  print "<pre>"; print_r($ZidProducts);die;

        // $ZidProducts['products'] = $final_Arr;
        $ZidProducts['total_pages'] = $total_pages;
        $ZidProducts['current_page'] = $i;
        $ZidProducts['seller_id'] = $id;
        $ZidProducts['perPage'] = $perPage;
        $ZidProducts['page'] = $i;
        $ZidProducts['count'] = $count;
        $ZidProducts['target_url'] = $target_url;
        $ZidProducts['totalproducts'] = $ZidProductArr['total_products_count'];
        $ZidProducts['aleradyexist'] = $exist;
        //  print "<pre>"; print_r($ZidProducts);die;

        $this->load->view('SellerM/view_zidp', $ZidProducts);
    }

    public function magentoProducts($id) {
        /// echo "ttt";die;
        $target_url = basename($_SERVER['REQUEST_URI']);
        $exist = $this->input->post('exist');
        $token = GetallCutomerBysellerId($id, 'magento_auth');

        if(empty($token))
        {
            $this->session->set_flashdata('error', "The consumer isn't authorized to access resources.");
            redirect(base_url() . 'Seller');
        }
        $search_sku = $this->input->post('search_sku');

        $total_items = $this->getProductListTotal($token, 1, 1, $search_sku);

        $per_page = 100;
        if (empty($this->input->post('i'))) {
            $page_no = 1;
        } else {
            $page_no = $this->input->post('i');
        }

        $total_pages = ceil($total_items['total_count'] / $per_page);

        $productsItems = $this->getProductList($token, $page_no, $per_page, $search_sku);
        // echo "<pre>"; print_r($productsItems); die;
        $ZidProducts['products'] = $productsItems;
        $ZidProducts['total_pages'] = $total_pages;
        $ZidProducts['current_page'] = $page_no;
        $ZidProducts['seller_id'] = $id;
        $ZidProducts['perPage'] = $per_page;
        $ZidProducts['page'] = $page_no;
        $ZidProducts['count'] = $total_items['total_count'];
        $ZidProducts['target_url'] = $target_url;
        $ZidProducts['totalproducts'] = $total_items['total_count'];
        $ZidProducts['aleradyexist'] = $exist;
        //  print "<pre>"; print_r($ZidProducts);die;

        $this->load->view('SellerM/view_magentop', $ZidProducts);
    }

    private function getProductListTotal($auth = null, $page_no = 1, $page_size = 50, $search_sku = null) {

        if (!empty($search_sku)) {
            $url = 'https://edumalls.com/sa_en/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=1&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[filter_groups][1][filters][0][field]=type_id&searchCriteria[filter_groups][1][filters][0][value]=simple&fields=items[id,name,sku,custom_attributes[description,image],qty,weight],total_count&searchCriteria[filter_groups][0][filters][0][field]=sku&searchCriteria[filter_groups][0][filters][0][value]=' . $search_sku . '&searchCriteria[filter_groups][0][filters][0][condition_type]=like';
        } else {
            $url = 'https://edumalls.com/sa_en/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=1&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[filter_groups][1][filters][0][field]=type_id&searchCriteria[filter_groups][1][filters][0][value]=simple&&searchCriteria[pageSize]=' . $page_size . '&fields=items[id,name,sku,custom_attributes[description,image],qty,weight],total_count';
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $auth
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    private function getProductList($auth = null, $page_no = 1, $page_size = 50, $search_sku = null) {

        if (!empty($search_sku)) {
            $url = 'https://edumalls.com/sa_en/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=1&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[filter_groups][1][filters][0][field]=type_id&searchCriteria[filter_groups][1][filters][0][value]=simple&fields=items[id,name,sku,custom_attributes[description,image],qty,weight],total_count&searchCriteria[filter_groups][0][filters][0][field]=sku&searchCriteria[filter_groups][0][filters][0][value]=' . $search_sku . '&searchCriteria[filter_groups][0][filters][0][condition_type]=like';
        } else {
            $url = 'https://edumalls.com/sa_en/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=1&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[filter_groups][1][filters][0][field]=type_id&searchCriteria[filter_groups][1][filters][0][value]=simple&searchCriteria[currentPage]=' . $page_no . '&searchCriteria[pageSize]=' . $page_size . '&fields=items[id,name,sku,custom_attributes[description,image],qty,weight],total_count';
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $auth
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    public function ZidProductsNew($id) {

        $sync_product_zid = GetallCutomerBysellerId($id, 'sync_product_zid');
        if ($sync_product_zid == 'Y') {

            $target_url = basename($_SERVER['REQUEST_URI']);

            if (empty($this->input->post('i'))) {
                $i = 1;
            } else {
                $i = $this->input->post('i');
            }

            $sku = $this->input->post('sku');
            // if(!empty($sku)){die("Hi");
            //     $sku = urlencode($sku);
            // }
            $exist = $this->input->post('exist');

            if (!empty($exist)) {
                $i = $this->input->post('pageno');
            }
            if (empty($exist)) {
                $exist = $this->input->post('aleradyexist');
            }

            // print "<pre>"; print_r($this->input->post());die;


            $data['zidproducts'] = $this->Seller_model->zidproduct($id);
            $storeID = $data['zidproducts'];
            $token = GetallCutomerBysellerId($id, 'manager_token');
            $bearer = site_configTable('zid_provider_token');
            $store_link = "https://api.zid.sa/v1/products/?page=" . $i;

            // $store_link = "https://api.zid.sa/v1/products?search=".$sku;
            //    echo $store_link;die;

            $ZidProductRT = ZidPcURL($storeID, $store_link, $bearer, $token);

            $ZidProductArr = json_decode($ZidProductRT, true);

            $per_page = 10;
            //  print "<pre>"; print_r($ZidProductArr);die;

            $total_pages = ceil($ZidProductArr['count'] / $per_page);
            // echo $total_pages;die;
            $results = array();
            $results2 = array();
            $p = 0;
            $s = 0;

            if (isset($ZidProductArr['results'])) {
                foreach ($ZidProductArr['results'] as $key => $products) {

                    //if (isset($products['structure']) && $products['structure'] == 'parent') {
                    // $product_link = $store_link . '/' . $products['id'] . '/';
                    // $product = json_decode(ZidPcURL($storeID, $product_link, $bearer, $token), true);
                    // if (!empty($product)) {
                    //     if (count($product['variants']) > 0) {
                    //         foreach ($product['variants'] as $key => $variant) {
                    //             $variant['images'] = $product['images'];
                    //             $results[] = $variant;
                    //         }
                    //     } else {
                    //         $results[] = $product;
                    //     }
                    // }
                    // } else {
                    $results2[] = $products;
                    // }
                }
            }

            $final_Arr = array_merge($results, $results2);
            if ($exist == 'Yes') {
                foreach ($final_Arr as $key => $val) {
                    $is_exist = exist_zidsku_id($val['sku'], $this->session->userdata('user_details')['super_id']);

                    if (!empty($is_exist)) {
                        $existArr[$key] = $val;
                    }
                }
            }
            if ($exist == 'No') {
                foreach ($final_Arr as $key => $val) {
                    $is_exist = exist_zidsku_id($val['sku'], $this->session->userdata('user_details')['super_id']);
                    if (empty($is_exist)) {
                        $existArr[$key] = $val;
                    }
                }
            }

            // print "<pre>"; print_r($final_Arr);die;
            // $count = $ZidProductArr['count'];
            // $perPage = 60;
            if (!empty($exist)) {
                $ZidProducts['products'] = $existArr;
            } else {
                $ZidProducts['products'] = $final_Arr;
            }


            // $ZidProducts['products'] = $final_Arr;
            $ZidProducts['total_pages'] = $total_pages;
            $ZidProducts['current_page'] = $i;
            $ZidProducts['seller_id'] = $id;
            $ZidProducts['perPage'] = $per_page;
            $ZidProducts['page'] = $i;
            // $ZidProducts['count'] = $count;
            $ZidProducts['target_url'] = $target_url;
            $ZidProducts['totalproducts'] = $ZidProductArr['count'];
            $ZidProducts['aleradyexist'] = $exist;
            //  print "<pre>"; print_r($ZidProducts);die;

            $this->load->view('SellerM/view_zidp_new', $ZidProducts);
        } else {

            $this->session->set_flashdata('msg', "product list not active from zid");
            redirect(base_url() . 'Seller');
            die;
        }
    }

    public function SaveMagentoProducts() {

        try {
            foreach ($this->input->post('selsku') as $value) {
                $skuarray = array();
                $editData = array();
                //echo $this->input->post('image')[$value]; exit;
                file_put_contents('assets/item_uploads/' . $this->input->post('sku')[$value] . '.jpg', file_get_contents($this->input->post('image')[$value]));
                $skuarray = array(
                    'sku' => $this->input->post('sku')[$value],
                    'name' => $this->input->post('skuname')[$value],
                    'magento_id' => $this->input->post('magento_id')[$value],
                    'super_id' => $this->session->userdata('user_details')['super_id'],
                    'description' => $this->input->post('description')[$value],
                    'type' => 'B2C',
                    'storage_id' => $this->input->post('storageid'),
                    'wh_id' => $this->input->post('warehouseid'),
                    'sku_size' => $this->input->post('sku_size'),
                    'added_by' => $this->input->post('seller_id'),
                    'entry_date' => date("Y-m-d H:i:s"),
                    'weight'=> isset($this->input->post('weight')[$value])?$this->input->post('weight')[$value]:"",
                    'item_path' => 'assets/item_uploads/' . $this->input->post('sku')[$value] . '.jpg'
                );
                //  'name' => $this->input->post('skuname')[$value],
                
//                $editData = array(
//                    'item_path' => 'assets/item_uploads/' . $this->input->post('sku')[$value] . '.jpg',
//                   // 'storage_id' => $this->input->post('storageid'),
//                    'weight'=> isset($this->input->post('weight')[$value])?$this->input->post('weight')[$value]:"",
//                    'magento_id' => $this->input->post('magento_id')[$value],
//                    'wh_id' => $this->input->post('warehouseid'),
//                   // 'sku_size' => $this->input->post('sku_size')
//                );
              //echo "<pre>";  print_r($skuarray);
              // die;

                $exist_zidsku_id = exist_zidsku_id($this->input->post('sku')[$value], $this->session->userdata('user_details')['super_id']);
                if ($exist_zidsku_id > 0) {
                   // $this->Item_model->edit($exist_zidsku_id, $editData);
                } else {
                    AddSKUfromZid($skuarray);
                }
            }

            $this->session->set_flashdata('msg', "Successfully Updated!");
        } catch (Exception $e) {
            $this->session->set_flashdata('error', $e->getMessage());
        }
        redirect($_SERVER['HTTP_REFERER']);
        //redirect('Item');
    }

    public function SaveZidProducts() {

        // print "<pre>"; print_r($this->input->post('selsku'));die;
        foreach ($this->input->post('selsku') as $value) {


            $skuarray = array();
            $editData = array();
            //echo $this->input->post('image')[$value]; exit;
            file_put_contents('assets/item_uploads/' . $this->input->post('sku')[$value] . '.jpg', file_get_contents($this->input->post('image')[$value]));
            $skuarray = array(
                'sku' => $this->input->post('sku')[$value],
                'zid_pid' => $this->input->post('pid')[$value],
                'name' => $this->input->post('skuname')[$value],
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'description' => $this->input->post('description')[$value],
                'type' => 'B2C',
                'storage_id' => $this->input->post('storageid'),
                'wh_id' => $this->input->post('warehouseid'),
                'sku_size' => $this->input->post('sku_size'),
                'added_by' => $this->input->post('seller_id'),
                'entry_date' => date("Y-m-d H:i:s"),
                'item_path' => 'assets/item_uploads/' . $this->input->post('sku')[$value] . '.jpg'
            );
            
            //  'name' => $this->input->post('skuname')[$value],
            $editData = array(
                'zid_pid' => $this->input->post('pid')[$value],
                'item_path' => 'assets/item_uploads/' . $this->input->post('sku')[$value] . '.jpg',
                'storage_id' => $this->input->post('storageid'),
                'wh_id' => $this->input->post('warehouseid'),
                'sku_size' => $this->input->post('sku_size')
            );

            $exist_zidsku_id = exist_zidsku_id($this->input->post('sku')[$value], $this->session->userdata('user_details')['super_id']);
            if ($exist_zidsku_id > 0) {
                $this->Item_model->edit($exist_zidsku_id, $editData);
            } else {
                AddSKUfromZid($skuarray);
            }
        }
        $this->session->set_flashdata('msg', "Selected Sku has been Added Successfully");
        redirect($_SERVER['HTTP_REFERER']);
        //redirect('Item');
    }

    public function zidconfig($id) {
        $this->load->view('SellerM/seller_zid', $data);
    }

    public function zidCities_1() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/settings/cities/by-country-id/184",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        //echo "<pre>";
        // print_r($response);
        echo "<table>";
        foreach ($response['cities'] as $val) {
            echo '<tr><td>' . $val['id'] . '</td>
                <td>' . $val['national_id'] . '</td>
                    <td>' . $val['name'] . '</td>
                        <td>' . $val['priority'] . '</td>
                            <td>' . $val['country_id'] . '</td>
                                <td>' . $val['country_name'] . '</td>
                                    <td>' . $val['country_code'] . '</td>
                                         <td>' . $val['ar_name'] . '</td>
                                              <td>' . $val['en_name'] . '</td>
                    </tr>';
        }
        echo "</table>";

        //return $response['cities'];
    }

    public function zidCities() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/settings/cities/by-country-id/184",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        return $response['cities'];
    }

    public function updateZidConfig($id, $edit_id = null) {

        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);
        $ListArr = $this->zidCities(); //$this->Seller_model->zidCities();
        $data['delivery_options'] = $this->Seller_model->deliverOptions($id);
        if ($edit_id != null) {
            $data['delivery_option_edit'] = $this->Seller_model->deliverOptionsByid($edit_id);
        }

        // echo '<pre>';
        //         print_r(  $ListArr);  exit;
        $pre = array();
        if (!empty($data['delivery_options'])) {
            $listcity = explode(',', $data['delivery_options'][0]['zid_city']);

            $keys = array();
            foreach ($listcity as $citie) {
                $key = array_search($citie, array_column($ListArr, 'id'));

                if ($key != -1) {

                    array_push($pre, $ListArr[$key]);  // cities add in next array
                    array_push($keys, $key); // selected cities remove from first list box
                }
            }

            foreach ($keys as $removecity) {
                unset($ListArr[$removecity]);    // remove selected cities from left side panel 
            }
        }

        $data['ListArr'] = $ListArr;
        $data['pre'] = $pre;

        if ($this->input->post('updatezid')) {

            if ($this->input->post('zid_active') == 'Y') {
                $zid_access = 'FM';
            }
            $update_data = array(
                'manager_token' => $this->input->post('manager_token'),
                'zid_sid' => $this->input->post('zid_sid'),
                'zid_status' => $this->input->post('zid_status'),
                'zid_active' => $this->input->post('zid_active'),
                'zid_access' => $zid_access,
            );

            $user = $this->Seller_model->update_zid($id, $update_data);

//            if ($user > 0) {
//                
//                if(  $this->zidWebhookSubscriptionDelete( $data['customer']))
//                    {
//                    $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
//                    $this->zidWebhookSubscriptionCreate( $data['customer']);  
//                    }
//                        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
//                            redirect('Seller/updateZidConfig/'.$id);
//            }
        }
        $this->load->view('SellerM/seller_zidconfig', $data);
    }

    public function updateSallaConfig($id) {
        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);

        if ($this->input->post('updatesalla')) {



            $update_data = array(
                'salla_athentication' => $this->input->post('salla_manager_token'),
                'salla_from_date' => $this->input->post('from'),
                'salla_active' => $this->input->post('salla_active'),
                'salla_status' => $this->input->post('salla_status'),
                'salla_webhook_subscribed' => $this->input->post('salla_active'),
                'salla_shipping_cost' => $this->input->post('salla_shipping_cost')
            );

            if ($this->Seller_model->update_salla($id, $update_data)) {
                $customer = $this->Seller_model->edit_view_customerdata($id);
                if ($this->input->post('salla_active')) {

                    $this->sallaWebhookSubscriptionDelete($customer);
                    $this->sallaWebhookSubscriptionCreate($customer);
                } else {
                    $this->sallaWebhookSubscriptionDelete($customer);
                }

                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller/updateSallaConfig/' . $id);
            }
        }

        $this->load->view('SellerM/seller_sallaconfig', $data);
    }

    /**
     * @param type $id
     * #description This method is used for zid webhook subscription
     */
    public function zidWebhookSubscribe($id) {



        if (!empty($this->input->post('zid_webhook_subscribed'))) {

            $deliver_id = $this->input->post('zid_delivery_name');
            $customer = $this->Seller_model->edit_view_customerdata($id);
            if ($customer['manager_token'] !== "" && $customer['zid_active'] == 'Y') {

                if ($this->input->post('zid_webhook_subscribed') == 'Y') {
                    $this->zidWebhookSubscriptionCreate($customer, $deliver_id);
                } else {
                    $this->zidWebhookSubscriptionDelete($customer, $deliver_id);
                }

                $update_data = array(
                    'zid_webhook_subscribed' => $this->input->post('zid_webhook_subscribed')
                );
                if ($this->Seller_model->update_zid($id, $update_data)) {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                    redirect('Seller/updateZidConfig/' . $id);
                }
            }
            redirect('Seller/updateZidConfig/' . $id);
        }
    }

    private function zidWebhookSubscriptionCreate($customer, $deliver_id) {

        /* check zid status and if status is new then order create other wise update webhook */
        $delivery_options = $this->Seller_model->deliverOptionsByid($deliver_id);
        // $delivery_options[0]['id']
        if ($customer['zid_status'] == 'new') {
            $event = "order.create";
            $condition = array('status' => 'new', 'delivery_option_id' => $delivery_options['delivery_id']);
        } else {
            $event = "order.status.update";
            $condition = array('status' => 'ready', 'delivery_option_id' => $delivery_options['delivery_id']);
        }
        // echo  $subscribe = site_configTable('company_name'); die; 
        if ($customer['zid_active'] == 'Y') {
            $subscribe = site_configTable('company_name');
            $arr = json_encode(array(
                "event" => $event,
                "target_url" => $this->config->item('zid_order_target_url') . '/' . $customer['uniqueid'] . '/' . $delivery_options['delivery_id'],
                "original_id" => $customer['uniqueid'],
                "subscriber" => $subscribe,
                "conditions" => $condition
            ));

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $arr,
                CURLOPT_HTTPHEADER => array(
                    "Accept: en",
                    "Accept-Language: en",
                    "X-MANAGER-TOKEN: " . $customer['manager_token'],
                    "Authorization:Bearer " . site_configTable('zid_provider_token'),
                    "Content-Type: application/json"
                ),
            ));

            $response = json_decode(curl_exec($curl));
            //  print_r($response); die;
            curl_close($curl);
            if ($response->status != "validation_error" || $response->status == "object") {
                $this->Seller_model->DeliveryOptionUpdate($deliver_id, 'Y', $response->conditions->shipping_method_id);
                return true;
            } else {
                return false;
            }
        }
    }

    private function zidWebhookSubscriptionDelete($customer, $deliver_id) {
        $subscribe = site_configTable('company_name');
        $curl = curl_init();

        // echo $deliver_id; die;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks?subscriber=" . $subscribe . "&original_id=" . $customer['uniqueid'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $customer['manager_token'],
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
                "User-Agent: Fastcoo/1.00.00 (web)"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        // print_r($response); die;
        curl_close($curl);
        if ($response->status == "success") {
            $this->Seller_model->DeliveryOptionUpdateNew($customer['id']);
            return true;
        }
        return false;
    }

    public function getZidWebHooks() {
        $id = $this->input->post('cust_id');
        $customer = $this->Seller_model->edit_view_customerdata($id);
        $curl = curl_init();
        //echo site_configTable('zid_provider_token'); exit;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $customer['manager_token'],
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
                "User-Agent: Fastcoo/1.00.00 (web)"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        exit();
    }

    /**
     * @param type $id
     * #Description webhook subscription for salla
     */
    public function sallaWebhookSubscribe($id) {

        if ($this->input->post('salla_webhook_subscribed')) {

            $customer = $this->Seller_model->edit_view_customerdata($id);

            if ($customer['salla_athentication'] !== "" && $customer['salla_active'] == 'Y') {

                if ($this->input->post('salla_webhook_subscribed') == 'Y') {
                    $this->sallaWebhookSubscriptionCreate($customer);
                } else {
                    $this->sallaWebhookSubscriptionDelete($customer);
                }

                $update_data = array(
                    'salla_webhook_subscribed' => $this->input->post('salla_webhook_subscribed')
                );

                if ($this->Seller_model->update_salla($id, $update_data)) {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                    redirect('Seller/updateSallaConfig/' . $id);
                }
            }

            redirect('Seller/updateSallaConfig/' . $id);
        }
    }

    private function sallaWebhookSubscriptionCreate($customer) {



        if ($customer['salla_active'] == 'Y') {


            $event = "order." . $customer['salla_status'];
            $request = array(
                "name" => "Salla Update " . $customer['uniqueid'],
                "event" => $event,
                "url" => "https://api.diggipacks.com/API/sallaOrder/" . $customer['uniqueid'],
                "headers" => array(
                    array(
                        "key" => "X-EVENT-TYPE",
                        "value" => "order.updated.diggipacks"
                    )
                ),
            );
            //$this->config->item('salla_order_target_url') . '/' . $customer['uniqueid'],
            $request = json_encode($request);
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.salla.dev/admin/v2/webhooks/subscribe",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $customer['salla_athentication'],
                    "Accept-Language: AR",
                    "Content-Type: application/json"
                ],
            ]);

            $result = curl_exec($curl);

            $response = json_decode(curl_exec($curl));

            curl_close($curl);
        }
    }

    private function sallaWebhookSubscriptionDelete($customer) {


        $request = array(
            "url" => "https://api.diggipacks.com/API/sallaOrder/" . $customer['uniqueid'],
        );
        //$this->config->item('salla_order_target_url') . '/' . $customer['uniqueid'],
        $request = json_encode($request);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.salla.dev/admin/v2/webhooks/unsubscribe",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $customer['salla_athentication'],
                "Accept-Language: AR",
                "Content-Type: application/json"
            ])
        );

        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        if ($response->status == "success") {
            return true;
        }
        return false;
    }

    public function getsallaWebHooks() {
        $id = $this->input->post('cust_id');
        $customer = $this->Seller_model->edit_view_customerdata($id);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.salla.dev/admin/v2/webhooks",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $customer['salla_athentication']
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

        exit();
    }

    public function deleteDeliveryOption($cust_id, $id) {

        $this->Seller_model->deleteDeliveryOption($id);

        $this->session->set_flashdata('msg', 'Delivery Option Has been Deleted successfully');
        redirect('Seller/updateZidConfig/' . $cust_id);
    }

    public function zidDeliveryOptionAdd() {

        if ($this->input->post('deliver_option')) {
            $cust_id = $this->input->post('id');

            $rdata = array(
                'name' => $this->input->post('zid_delivery_name'),
                'cost' => $this->input->post('zid_delivery_cost'),
                'cod_enabled' => $this->input->post('zid_cod_enabled'),
                'cod_fee' => $this->input->post('zid_cod_fee'),
                'cities' => $this->input->post('zid_city'),
                'delivery_estimated_time_ar' => $this->input->post('delivery_estimated_time_ar'),
                'delivery_estimated_time_en' => $this->input->post('delivery_estimated_time_en'),
            );
            //  ECHO "<PRE>";print_r($rdata);die;
            $customer = $this->Seller_model->edit_view_customerdata($cust_id);
            $request = json_encode($rdata);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.zid.sa/v1/managers/store/delivery-options/add",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => array(
                    //"Accept: en",
                    "Accept-Language: en",
                    "X-MANAGER-TOKEN: " . $customer['manager_token'],
                    "Authorization:Bearer " . site_configTable('zid_provider_token'),
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $response = json_decode($response);
            if ($response->status == "object") {
                $deliver_id = $response->delivery_option->id;
                $data = array(
                    'cust_id' => $cust_id,
                    'zid_delivery_name' => $this->input->post('zid_delivery_name'),
                    'zid_delivery_cost' => $this->input->post('zid_delivery_cost'),
                    'zid_cod_enabled' => $this->input->post('zid_cod_enabled'),
                    'zid_cod_fee' => $this->input->post('zid_cod_fee'),
                    'zid_city' => implode(',', $this->input->post('zid_city')),
                    'delivery_estimated_time_ar' => $this->input->post('delivery_estimated_time_ar'),
                    'delivery_estimated_time_en' => $this->input->post('delivery_estimated_time_en'),
                    'delivery_id' => $deliver_id,
                    'shipping_method_id' => !empty($response->conditions->shipping_method_id) ? $response->conditions->shipping_method_id : ""
                );

                $this->Seller_model->zidDeliveryOptionUpdate($data);
                $this->session->set_flashdata('msg', 'Data been updated successfully');
            } else {

                $this->session->set_flashdata('error', $response->message->description);
            }





            redirect('Seller');
        }
    }

    public function getZidDeliveryOptions($id) {
        $customer = $this->Seller_model->edit_view_customerdata($id);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/store/delivery-options",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                //"Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $customer['manager_token'],
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);
        echo "<pre>";
        print_r($response);
    }

    //Salla functions starts 
    public function SallaProductsCurl($url, $customer) {
        //echo $url;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            //CURLOPT_URL => 'https://api.salla.dev/admin/v2/products?page=1&per_page=60&keyword=%D8%B0%D8%A7%20%D9%81%D9%8A%D8%B1%D8%B3%D8%AA',
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $customer['salla_athentication'],
                'Cookie: __cflb=02DiuFQBGqUnMaSnBxhrokvr8dehAf8CsB5EBvm8xdq2U'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $SallaProductArr_total = json_decode($response, true);
        // print "<pre>"; print_r($customer);die;
        // $curl = curl_init();
        // curl_setopt_array($curl, [
        //     CURLOPT_URL => $url,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "GET",
        //     CURLOPT_HTTPHEADER => [
        //         "Authorization: Bearer " . $customer['salla_athentication'],
        //         "Content-Type: application/json"
        //     ],
        // ]);
        // $response = curl_exec($curl);
        // $err = curl_error($curl);
        // //echo $response;die;
        // curl_close($curl);
        // return $SallaProductArr_total = json_decode($response, true);
    }

    public function SallaProducts($id = null, $i = null) {

        $target_url = basename($_SERVER['REQUEST_URI']);

        if (empty($this->input->post('i'))) {
            $i = 1;
        } else {
            $i = $this->input->post('i');
        }

        //print "<pre>"; print_r($this->input->post());die;
        $sku = $this->input->post('sku');
        if (!empty($sku)) {
            $sku = urlencode($sku);
        }
        $exist = $this->input->post('exist');

        if (!empty($exist)) {
            $i = $this->input->post('pageno');
        }
        if (empty($exist)) {
            $exist = $this->input->post('aleradyexist');
        }
        // else{
        //   $exist=$this->input->post('aleradyexist');
        // }

        $per_page = 60;

        // $store_link = "https://api.salla.dev/admin/v2/products?page=" . $i;
        //echo $sku;die;
        $store_link = "https://api.salla.dev/admin/v2/products?page=" . $i . "&per_page=" . $per_page . "&keyword=" . $sku;
        //echo $store_link;die;
        $link = "https://api.salla.dev/admin/v2/products?page=";
        // echo $id;die;
        $customer = $this->Seller_model->edit_view_customerdata($id);
        // echo $store_link;die;
        $SallaProductArr_total = $this->SallaProductsCurl($store_link, $customer);
//  echo '<pre>';
//    print_r($SallaProductArr_total); die;

        if (!empty($SallaProductArr_total['data']) && ($SallaProductArr_total['success'] == 1)) {
            $sallaarray = array();
            foreach ($SallaProductArr_total['data'] as $key => $products) {
                //echo "<pre>"; echo json_encode($products['skus']); 
                // if(!empty($products['sku']))
                // {
                //     $sallaarray[] =  array(
                //         'sku' => $products['sku'],
                //         'name' => $products['name'] ,
                //         'id' => $products['id'],
                //         'description'=>$products['description'],
                //         'image'=>$products['images'][0]['url'],
                // );
                // }
                // else
                // {
                if (!empty($products['skus'][0]['sku'])) {
                    foreach ($products['skus'] as $subproducts) {
                        if (!empty($subproducts['sku'])) {
                            $sallaarray[] = array(
                                'sku' => $subproducts['sku'],
                                'name' => $products['name'],
                                'id' => $subproducts['id'],
                                'description' => $products['description'],
                                'image' => $products['images'][0]['url'],
                            );
                            // }
                        }
                    }
                } else {
                    $sallaarray[] = array(
                        'sku' => $products['sku'],
                        'name' => $products['name'],
                        'id' => $products['id'],
                        'description' => $products['description'],
                        'image' => $products['images'][0]['url'],
                    );
                }
            }
        }


        if ($exist == 'Yes') {
            foreach ($sallaarray as $key => $val) {
                $is_exist = exist_zidsku_id($val['sku'], $this->session->userdata('user_details')['super_id']);

                if (!empty($is_exist)) {
                    // $existArr = array();
                    $existArr[$key] = $val;
                }
            }
        }

        if ($exist == 'No') {
            // print "<pre>"; print_r($sallaarray);die;
            foreach ($sallaarray as $key => $val) {
                $is_exist = exist_zidsku_id($val['sku'], $this->session->userdata('user_details')['super_id']);
                // print_r($is_exist);die;
                if (empty($is_exist)) {
                    // $existArr = array();
                    $existArr[$key] = $val;
                }
            }
        }

        // print "<pre>"; print_r($existArr);die;
        $total_pages = $SallaProductArr_total['pagination']['totalPages'];
        $count = $SallaProductArr_total['pagination']['count'];
        $perPage = $SallaProductArr_total['pagination']['perPage'];
        $currentPage = $SallaProductArr_total['pagination']['currentPage'];
        if (!empty($exist)) {
            $SallaProducts['products'] = $existArr;
        } else {
            $SallaProducts['products'] = $sallaarray;
        }
        //$SallaProducts['products'] = $sallaarray;
        $SallaProducts['total_pages'] = ceil($total_pages);
        $SallaProducts['current_page'] = $currentPage;
        $SallaProducts['perPage'] = $perPage;
        $SallaProducts['seller_id'] = $id;
        $SallaProducts['page'] = $i;
        $SallaProducts['count'] = $count;
        $SallaProducts['target_url'] = $target_url;
        $SallaProducts['totalproducts'] = $SallaProductArr_total['pagination']['total'];
        $SallaProducts['aleradyexist'] = $exist;

        // echo "<pre>"; print_r( $SallaProducts);   die; 
        if (count($sallaarray) > 60) {
            // echo "test";die;
            $this->load->view('SellerM/view_sallaproduct', $SallaProducts);
        } else {
            $this->load->view('SellerM/view_sallaproducts', $SallaProducts);
        }

        //$this->load->view('SellerM/view_sallaproducts', $SallaProducts);
        //$this->load->view('SellerM/view_sallaproducts');
    }

    public function SaveSallaProducts() {

        foreach ($this->input->post('selsku') as $value) {
            file_put_contents('assets/item_uploads/' . $this->input->post('pid')[$value] . $this->input->post('sku')[$value] . '.jpg', file_get_contents($this->input->post('image')[$value]));

            $skuarray = array();
            $skuarray = array(
                'sku' => $this->input->post('sku')[$value],
                'salla_pid' => $this->input->post('pid')[$value],
                'name' => $this->input->post('skuname')[$value],
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'description' => $this->input->post('sku')[$value],
                'type' => 'B2C',
                'storage_id' => $this->input->post('storageid'),
                'wh_id' => $this->input->post('warehouseid'),
                'sku_size' => $this->input->post('sku_size'),
                'entry_date' => date("Y-m-d H:i:s"),
                'item_path' => 'assets/item_uploads/' . $this->input->post('pid')[$value] . $this->input->post('sku')[$value] . '.jpg'
            );
            $editData = array(
                'salla_pid' => $this->input->post('pid')[$value],
                'item_path' => 'assets/item_uploads/' . $this->input->post('pid')[$value] . $this->input->post('sku')[$value] . '.jpg'
            );
            $exist_zidsku_id = exist_zidsku_id($this->input->post('sku')[$value], $this->session->userdata('user_details')['super_id']);
            if ($exist_zidsku_id != '' || $exist_zidsku_id != 0) {
                $this->Item_model->edit($exist_zidsku_id, $editData);
            } else {
                AddSKUfromZid($skuarray);
            }
        }
        $this->session->set_flashdata('msg', "Selected Sku has been Added Successfully");
        redirect('Item');
    }

    public function updateShopify($id) {

        if ($this->input->post('updateshopify')) {
            $update_data = array(
                'shopify_url' => $this->input->post('shopify_url'),
                'shopify_tag' => $this->input->post('shopify_tag'),
                'location_id' => $this->input->post('location_id'),
                'is_shopify_active' => $this->input->post('is_shopify_active'),
                'shopify_fulfill' => $this->input->post('shopify_fulfill'),
            );

            if ($this->Seller_model->update_shopify($id, $update_data)) {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller');
            }
        }

        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);

        $this->load->view('SellerM/shopify_config', $data);
    }

    public function updateWoocommerce($id) {

        if ($this->input->post('updatewoocommerce')) {
            $update_data = array(
                'wc_consumer_key' => $this->input->post('consumer_key'),
                'wc_secreat_key' => $this->input->post('consumer_secreat_key'),
                'wc_store_url' => $this->input->post('consumer_store_url'),
                'wc_active' => ($this->input->post('consumer_active')) ? $this->input->post('consumer_active') : 0,
            );
            if ($this->Seller_model->update_Woocommerce($id, $update_data)) {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller');
            }
        }

        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);

        $this->load->view('SellerM/woocommerce_config', $data);
    }

    public function update_webhook($id) {

        if ($this->input->post('updatewebhook')) {
            $update_data = array(
                'name' => $this->input->post('name'),
                'url' => $this->input->post('url'),
                'customer_id' => $id,
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'subscribe' => $this->input->post('subscribe'),
                'auth' => $this->input->post('auth'),
                'update_date' => date("Y-m-d H:i:s"),
                'auth_token' => $this->input->post('auth_token'),
            );
            $webhook_Arr = $this->Seller_model->webhook_settings_check($id);
            //  print_r($update_data); die;
            if ($webhook_Arr['id'] > 0) {
                $this->Seller_model->webhook_update($update_data, $webhook_Arr['id']);
            } else {
                $this->Seller_model->webhook_insert($update_data);
            }
            $this->session->set_flashdata('msg', 'has been updated successfully');
            redirect('Seller');
        }
        $data['cust_id'] = $id;
        $data['customer'] = $this->Seller_model->webhook_settings_check($id);
        $data['customer_in'] = $this->Seller_model->webhook_settings_check_in($id);

        // $data['seller'] = $this->Seller_model->edit_view($id);

        $this->load->view('SellerM/webhook_config', $data);
    }

    public function update_webhook_inventory($id) {

        if ($this->input->post('updatewebhook')) {
            $update_data = array(
                'name' => $this->input->post('name'),
                'url' => $this->input->post('url'),
                'customer_id' => $id,
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'subscribe' => $this->input->post('subscribe'),
                //'auth' => $this->input->post('auth'),
                'update_date' => date("Y-m-d H:i:s"),
                    //'auth_token' => $this->input->post('auth_token'),
            );
            $webhook_Arr = $this->Seller_model->webhook_settings_check_in($id);
            //  print_r($update_data); die;
            if ($webhook_Arr['id'] > 0) {
                $this->Seller_model->webhook_update_in($update_data, $webhook_Arr['id']);
            } else {
                $this->Seller_model->webhook_insert_in($update_data);
            }
            $this->session->set_flashdata('msg', 'has been updated successfully');
            redirect('Seller');
        }
        $data['cust_id'] = $id;
        $data['customer_in'] = $this->Seller_model->webhook_settings_check_in($id);
        //print_r($data); die;
        //$data['seller_in'] = $this->Seller_model->edit_view($id);

        $this->load->view('SellerM/webhook_config', $data);
    }

    public function GetcustmerData() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $cust_id = $postData['id'];

        $sellerArr = $this->Seller_model->edit_view($cust_id);
        $wc_statues = json_decode($sellerArr['wc_statues']);

        if (empty($wc_statues)) {

            $wc_statues = $this->Seller_model->mainStatusCatArr();
            foreach ($wc_statues as $key => $val) {
                $wc_statues[$key]['wc_status'] = "";
            }
        }



        $returnArray['systam_cat'] = $wc_statues;
        $returnArray['WC_statusArr'] = $WC_statusArr;
        echo json_encode($returnArray);
    }

    private function GetWoocommerceStatus($data = array()) {
        $data_array = array('customer_key' => $data['wc_consumer_key'], 'customer_secret' => $data['wc_secreat_key'], 'store_url' => $data['wc_store_url']);
        $dataJson = json_encode($data_array);
        $headers = array(
            "Content-type: application/json",
        );
        $url = "https://api.diggipacks.com/WooCommerce_Api/statusRequest";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        return json_decode($response);
    }

    public function GetshowStatusList_WC() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $cust_id = $postData['id'];

        $sellerArr = $this->Seller_model->edit_view($cust_id);
        $WC_statusArr = $this->GetWoocommerceStatus($sellerArr);
        $returnArray['WC_statusArr'] = $WC_statusArr;
        echo json_encode($returnArray);
    }

    public function GetUpdateStatusFinal() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $data = array('wc_statues' => json_encode($postData['updates']));
        $return = $this->Seller_model->edit($postData['id'], $data);

        echo json_encode($return);
    }

    public function zidpendingOrder() {
        $data['sellers'] = $this->Seller_model->all_zid_sellers();
        $this->load->view('SellerM/pending_zid', $data);
    }

    public function GetcheckZidPendigOrders() {
        $postData = json_decode(file_get_contents('php://input'), true);
        $seller_id = $postData['seller'];
        $order_no = $postData['order_no'];
        $zid_provider_token = site_configTable('zid_provider_token');
        $manager_token = getallsellerdatabyID($seller_id, 'manager_token');
        $uniqueid = getallsellerdatabyID($seller_id, 'uniqueid');
        $zid_authorization = getallsellerdatabyID($seller_id, 'zid_authorization');
        if ($zid_authorization != NULL) {
            $bearer = $zid_authorization;
        } else {
            $bearer = $zid_provider_token;
        }
        if (!empty($seller_id) && !empty($order_no)) {

            $resutl = $this->GetHttprequest($bearer, $manager_token, $order_no);
            $return['req'] = $resutl;
            // $return=array("status" => "error", "req" => $resutl);
        } else {

            $return['emptyErr'] = array("status" => "emp", "req" => null, "mess" => "All Field Are required");
        }

        echo json_encode($return);
    }

    public function GetCreateProcessOrder() {
        $postData = json_decode(file_get_contents('php://input'), true);
//        print "<<pre>"; print_r($postData);die;
        $orderdata = $postData['order'];
        $seller_id = $postData['filterArr']['seller'];
        
        $uniqueid = getallsellerdatabyID($seller_id, 'uniqueid');
        

        $return = $this->CreateOrderrequest($orderdata, $uniqueid);
        //echo "<pre>";
        //print_r($postData);
        //die;

        echo json_encode($return);
    }

    private function CreateOrderrequest($data = array(), $cust_id = null) {
        $data_json = json_encode($data);
        // echo "ssss". $data_json; die;
        $curl = curl_init();
        $url = "https://fm.diggipacks.com/zid/getOrder/$cust_id";
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // $result = json_decode($response, true);
        return $response;
    }

    private function GetHttprequest($zid_provider_token = 0, $manager_token = 0, $order_no = 0) {

        
        
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/store/orders/$order_no/view",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                //"Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $manager_token,
                "Authorization:Bearer " . $zid_provider_token,
                "Content-Type: application/json"
            ),
        ));

         $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response, true);
        return $result;
    }

    ##bof:: salla app configuration

    public function updateSallaConfigApp($id = null) {

        if (menuIdExitsInPrivilageArray(239) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);
        // print "<pre>"; print_r($data);die;
        if ($this->input->post('updatesalla')) {

            $update_data = array(
                'client_id_salla' => $this->input->post('client_id_salla'),
                'client_secret_key_salla' => $this->input->post('client_secret_key_salla'),
            );

            if ($this->Seller_model->update_salla($id, $update_data)) {

                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller/updateSallaConfigApp/' . $id);
            }
        }

        $this->load->view('SellerM/seller_sallaconfig_app', $data);
    }

    ##eof:: salla app configuration
}
