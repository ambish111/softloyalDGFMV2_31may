<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diggistores extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(4) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');
        $this->load->model('Diggistores_model');
        $this->load->library('form_validation');
    }

    public function connectSellerToStore($id = null){

        $data['id'] = $id;
        
        $data['sellers'] = $this->Diggistores_model->custList();
        $data['country'] = $this->Diggistores_model->CountryList();
        
        if ($id != null) {
            $data['EditData'] = $this->Diggistores_model->get_customer_details($id);
            
            $precityData = $data['EditData'][0]->city_name;
            $precity = json_decode($precityData);

            // print "<pre>";  print_r( $precity); exit;

            $data['EditData'][0]->cust_name = getallsellerdatabyID($data['EditData'][0]->cust_id, 'company', $data['EditData'][0]->super_id);

            //cust_name=
            // print "<pre>"; print_r($data); exit;

            $keyArray = array();
            $preArray = array();
          
           
              
            $masterCity = $this->Diggistores_model->get_cities_by_cc_city();
                 
            
            // print "<pre>"; print_r($masterCity); die;
            if (!empty($masterCity)) {
                foreach ($precity as $key => $val) {
                    // array_map($masterCity);
                    $key1 = array_search($val, array_column($masterCity, 'city'));

                    // echo '<br>'. $key1.'//' .  $val['city']; 
                    if (!empty($key1) || $key1 == 0) {

                        if (!in_array($key1, $keyArray)) {

                            array_push($preArray, $masterCity[$key1]);
                            array_push($keyArray, $key1);
                            // $data['pre'][]=$masterCity[$key];
                        }
                        $key1 = null;
                    }
                }
                foreach ($keyArray as $k1) {
                    //echo '<pre>xx'.$k1 .print_r($masterCity[$k1]);
                    unset($masterCity[$k1]);
                }
                array_values($masterCity);
            }
        }

        //  print "<pre>"; print_r($masterCity);die;
        $data['ListArr'] = $masterCity;
        $data['pre'] = $preArray;
        $this->load->view('Diggistores/connect_seller', $data);
    }

    public function filter_by_country() {


        $c_name = $this->input->post('c_name');
        
        if (!empty($c_name)) {
            
            $masterCity = $this->Diggistores_model->get_cities_by_country($c_name);
                
            if (!empty($masterCity)) {
                $response = json_encode(array("status" => "true", "data" => $masterCity));
            } else {
                $response = json_encode(array("status" => "false", "message" => "City Not Found"));
            }
        } else {
            $response = json_encode(array("status" => "false", "message" => "City Not Found"));
        }


        echo $response;
    }

    public function add_diggistores(){
        // print "<pre>"; print_r($this->input->post());die;
        $this->form_validation->set_rules("cust_id", 'Seller', 'trim|required');
        $this->form_validation->set_rules("city_id[]", 'City', 'trim|required');
        if ($this->form_validation->run() == FALSE) {

            $this->connectSellerToStore();
        } else {
            
            $data = array(
                'cust_id' => $this->input->post('cust_id'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'city_name' => json_encode($this->input->post('city_id')),
                'created_at' => date('Y-m-d H:i:s'),
            );
            // print "<pre>"; print_r($data);die;
           
            if (empty($errors)) {

                  $reutn = $this->Diggistores_model->add_stores_customer($data);
                  $this->Diggistores_model->update_store_chanel( $this->input->post('cust_id'));
                  $this->session->set_flashdata('msg', 'Stores has been added successfully');
                  
                 
               
            } else {
                $this->session->set_flashdata('msg', ' adding is failed');
            }

//die;

            redirect('connect_seller');
        }

    }

    public function edit_diggistores($id=null){
        if ($id > 0) {
            
            
            $data = array(
                'city_name' => json_encode($this->input->post('city_id')),
            );
            // print "<pre>"; print_r($data);die;
            
            $this->Diggistores_model->UpdateConnectedCustomer($data, $id);
            $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        } else
            $this->session->set_flashdata('msg', 'try again');

        redirect('viewStoreSeller');

    }

    public function viewStoreSeller(){

        $sellers = $this->Diggistores_model->store_customer();
        

        foreach ($sellers as $key => $val) {
            $sellers[$key]->cust_name = getallsellerdatabyID($sellers[$key]->cust_id, 'company', $sellers[$key]->super_id);
            //cust_name=
        }
        $data['sellers'] = $sellers;
        // print "<pre>"; print_r($data);die;
        $this->load->view('Diggistores/list_view_customer', $data);

    }

    

}
