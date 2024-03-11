<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zones extends MY_Controller {

    function __construct() {

        parent::__construct();
        if (menuIdExitsInPrivilageArray(23) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('Zones_model');
        $this->load->model('Ccompany_model');



        $this->load->library('form_validation');
    }

  

    public function list_view_customer() {
        $sellers = $this->Zones_model->all_customer();

        foreach ($sellers as $key => $val) {
            $sellers[$key]->cust_name = getallsellerdatabyID($sellers[$key]->cust_id, 'company', $sellers[$key]->super_id);
            //cust_name=
        }
        $data['sellers'] = $sellers;
        //print "<pre>"; print_r($data);die;
        $this->load->view('Zones/list_view_customer', $data);
    }

  

    public function editZoneUpdateCustomer($id = null) {
        if ($id > 0) {
            $cust_id= $this->input->post('cust_id');
            $Zones_data=$this->Zones_model->Checkcustomer_data($cust_id);
            $data = array(
                'name' => $this->input->post('name'),
               // 'capacity' => $this->input->post('capacity'),
               // 'cc_id' => $this->input->post('c_id'),
                'city_id' => json_encode($this->input->post('city_id')),
                'max_weight' => $this->input->post('max_weight'),
                'flat_price' => $this->input->post('flat_price'),
                'price' => $this->input->post('price'),
                'r_max_weight' => $this->input->post('r_max_weight'),
                'r_flat_price' => $this->input->post('r_flat_price'),
                'r_price' => $this->input->post('r_price'),
            );
            
            
           // echo "<pre>";print_r($_POST); die;
            $this->Zones_model->UpdateZoneCompanyLIstCustomer($data, $id);
            $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        } else
            $this->session->set_flashdata('msg', 'try again');
        redirect('viewZoneclient');
    }

    public function add_view_customer($id = null) {

        if (($this->session->userdata('user_details') != '')) {


            $data['id'] = $id;
            $data['customers'] = $this->Zones_model->ZoneCustomer();

            $data['sellers'] = $this->Zones_model->custList();

            //$data['company'] = $this->Ccompany_model->all();


            // $masterCity = $this->Zones_model->fetch_all_cities_new();
            //print_r($masterCity); exit;
            if ($id != null) {
                $data['EditData'] = $this->Zones_model->find_customer_sellerm_cust($id);
                //print "<pre>"; print_r($data['EditData']);die;
                $precityData = $this->Zones_model->previousCity_customer($id);
                $precity = json_decode($precityData['city_id']);

                //print "<pre>";  print_r( $precity); exit;

                $data['EditData'][0]->cust_name = getallsellerdatabyID($data['EditData'][0]->cust_id, 'company', $data['EditData'][0]->super_id);

                //cust_name=
                //print_r($precity); exit;

                $keyArray = array();
                $preArray = array();
              
               
                  
                    $masterCity = $this->Zones_model->get_cities_by_cc_city($cityColumn);
                     
                
                //print_r($masterCity); die;
                if (!empty($masterCity)) {
                    foreach ($precity as $key => $val) {
                        // array_map($masterCity);
                        $key1 = array_search($val, array_column($masterCity, 'id'));

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

             // print_r($preArray); exit;

            $data['ListArr'] = $masterCity;

            $data['pre'] = $preArray;


            $this->load->view('Zones/add_view_customer', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function add_zone_customer() {
       
        // $this->form_validation->set_rules("capacity", 'capacity', 'trim|required');
         //$this->form_validation->set_rules("city_id", 'Seller', 'trim|required');
        $this->form_validation->set_rules("cust_id", 'Seller', 'trim|required');
        $this->form_validation->set_rules("city_id[]", 'City', 'trim|required');
        if ($this->form_validation->run() == FALSE) {

            $this->add_view_customer();
        } else {
            
            $data = array(
                'name' => $this->input->post('name'),
                //'capacity' => $this->input->post('capacity'),
                // 'cc_id' => $this->input->post('c_id'),
                'cust_id' => $this->input->post('cust_id'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'city_id' => json_encode($this->input->post('city_id')),
                'max_weight' => $this->input->post('max_weight'),
                'flat_price' => $this->input->post('flat_price'),
                'price' => $this->input->post('price'),
                'r_max_weight' => $this->input->post('r_max_weight'),
                'r_flat_price' => $this->input->post('r_flat_price'),
                'r_price' => $this->input->post('r_price'),
            );
            
           
            if (empty($errors)) {
                 // $Zones_data=$this->Zones_model->Checkcustomer_data($this->input->post('cust_id'));
                  
                  $reutn=$this->Zones_model->add_company_customer($data);
                  $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
                  
                 
               
            } else {
                $this->session->set_flashdata('msg', $this->input->post('name') . '    adding is failed');
            }

//die;

            redirect('viewZoneclient');
        }
    }
    

    public function filter_zone_by_cc() {
        $cc_id = $this->input->post('cc_id');

        $cityColumn = 'city';


        if (!empty($cityColumn)) {

            $masterCity = $this->Zones_model->get_cities_by_cc_city($cityColumn);
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

}

?>