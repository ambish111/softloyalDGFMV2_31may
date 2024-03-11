<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Access_model');
      
    }

    public function getcheckAccess()
    {
        
       //$this->Access_model->GetCheckpages();
     //  $picking_count=$this->Get_page_access();
       if($picking_count>1)
       {
          $this->session->set_userdata(array("packing_access"=>'N'));
       }
       {
        $this->session->set_userdata(array("packing_access"=>'Y'));
       }
    }


}

?>