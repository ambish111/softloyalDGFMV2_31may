<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $site_data;
    public $system_type;
     public $ZidSystemType;

    function __construct() {
        parent::__construct();
        $url = $_SERVER['HTTP_HOST'];
        //$url = ltrim($url, 'fm.'); 
        $neeUrlArr = explode('.', $url);
        $url = $neeUrlArr[1] . '.' . $neeUrlArr[2];

        $this->system_type = Getsite_configData_field('system_type');
        if ($this->session->userdata('langCheck') == 'AR') {

            $this->config->set_item('language', 'arabic');
            $this->lang->load("arabic_main", "arabic");
        } else {
            $this->config->set_item('language', 'english');
            //echo $this->config->item('language');	
            $this->lang->load("english_main", "english");
        }




        if ('diggipacks.com' != $url) {


            $this->site_data = site_config($url);
             $this->site_data->checkSystemType="Other";
            //print_r($this->site_data  ); die();
            $this->site_data->newlogo = 'https://super.diggipacks.com/'.$this->site_data->logo; 
            //echo "<script> var string =JSON.stringify(" . json_encode($this->site_data) . ") localStorage.setItem('site_data', JSON.stringify(string)); </script>";
        } else {

           
            $this->site_data = site_config_default();
             $this->site_data->checkSystemType="DG";
            $this->site_data->newlogo = 'https://lm.diggipacks.com/clientLogo/dgpk.png';
        }
        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {

            if ($this->router->class != 'Login') {
                if ($this->router->class != 'CourierCompany') {

                    redirect(base_url());
                }
            }
        }
        
    }
    

    
    public function GetCheckpages() {
        $this->db->where('user_id', $this->session->userdata('user_details')['user_id']);
        $this->db->select('id,picking_count');
        $this->db->from('tabs_count');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $co_row=$query->row_array();
            $new_count=$co_row['picking_count']+1;
            $this->db->update("tabs_count", array("picking_count" =>$new_count, 'entry_date' => date("Y-m-d H:i:s")), array("user_id" => $this->session->userdata('user_details')['user_id']));
           
        } else {
            $this->db->insert("tabs_count", array("picking_count" => 1,"user_id" => $this->session->userdata('user_details')['user_id'],"super_id" => $this->session->userdata('user_details')['super_id'], 'entry_date' => date("Y-m-d H:i:s")));
        }
       // return true;
    }

}
