<?php

defined('BASEPATH') OR exit('No direct script access allowed'); 

class Torodcompany_model extends CI_Model {
   
    function __construct() {
        parent::__construct();
        
        //$this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }


    public function GetTorodDetailsQry($slipNo = null) {
        
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('slip_no', $slipNo);
        $this->db->where('deleted', 'N');
        // $this->db->where('forwarded', 0);
        // $this->db->where('delivered!=', '9');
        $this->db->where('reverse_type!=', '1');
       // $this->db->where_not_in('shipment_fm.code', 'RTC', 'SM', 'POD', 'C');
       // $this->db->where('frwd_company_id', 0);
        // $this->db->where('frwd_company_awb', '');
        // $this->db->where('status', 'Y');
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        // print "<pre>"; print_r($query->row_array());die;
        return $query->row_array();
    }

    public function GetdeliveryCompanyUpdateQry($super_id=null,$company = null) {

        $this->db->where('super_id', $super_id);
        $this->db->where('company', $company);
        $this->db->select('*');
        $this->db->from('courier_company_seller');
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->order_by("company");
        $query = $this->db->get();
        // echo $this->db->last_query();exit;

        if ($query->num_rows()> 0)
        {
            return $query->row_array();
        }
        else 
        {
        
            $this->db->where('super_id', $super_id);
            $this->db->where('company', $company);
            $this->db->select('*');
            $this->db->from('courier_company');
            $this->db->where('deleted', 'N');
            $this->db->where('status', 'Y');
            $this->db->order_by("company");
            $query = $this->db->get();
            //echo  $this->db->last_query(); die; 
            return $query->row_array();
        }
    }

    public function Update_Shipment_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null, $super_id=null, $barq_order_id= null,$qty=null,$bosta_label_id=null,$torod_order_id=null) 
    {
        $updateArr = array(); 
       if ($company == 'Esnad'){
            $label_type = 1;
            $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id,'bosta_label_id'=>$bosta_label_id);         

        }
        elseif ($company == 'Bosta V2')
          {  $label_type = 0;
              $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' =>$barq_order_id,'bosta_label_id'=>$bosta_label_id);         
          }
          elseif ($company == 'Barqfleet')
          {  $label_type = 0;
              $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' =>$barq_order_id,'bosta_label_id'=>$bosta_label_id);         
          }
        elseif ($company == 'Saudi Post'){  $label_type = 0;
            $updateArr = array('pieces'=>$qty,'frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id,'bosta_label_id'=>$bosta_label_id);         
        }
      else
        {
            $label_type = 0;
            $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type);
        }
  
        $this->GetshipmentUpdate_forward($updateArr, $slipNo);
        if($company == 'Torod'){
            $details = 'Order Created in  ' . $company .' SlipNo: '.$slipNo.' : Torod Order ID: '.$client_awb;
        }else{
            $details = 'Forwarded to ' . $company .' SlipNo: '.$slipNo.' : Frwd AWB: '.$client_awb;
        }
       
        $statusArr = array(
            'slip_no' => $slipNo,
            'new_status' => 10,
            'pickup_time' => $CURRENT_TIME,
            'pickup_date' => $CURRENT_DATE,
            'Activites' => 'Forward to Delivery Station',
            'Details' => $details,
            'entry_date' => $CURRENT_DATE,
            'user_id' => $this->session->userdata('user_details')['super_id'],
            'user_type' => 'fulfillment',
            'comment' => $comment,
            'code' => 'FWD',
            'super_id' => $this->session->userdata('user_details')['super_id'],
        );
        $this->GetstatuInsert_forward($statusArr);
        //send_message($slipNo);

        return true;
    }

    public function GetshipmentUpdate_forward(array $data, $awb = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('shipment_fm', $data, array('slip_no' => $awb));
        //  echo $this->db->last_query(); die;
    }

    public function GetstatuInsert_forward(array $data) {

        $this->db->insert('status_fm', $data);
        //echo $this->db->last_query();

    }

    public function TorodLog($status=null,$response=null,$param=null){

        $data = array(
            'super_id' => $this->session->userdata('user_details')['super_id'],
            'status' => $status,
            'log' => $response,
            'request' => $param
        );

        $this->db->insert('torod_warehouse_log', $data);
    }

    public function warehouseTorodLogview($status) {

        $page_no;
            $limit = ROWLIMIT;
            if (empty($page_no)) {
                $start = 0;
            } else {
                $start = ($page_no - 1) * $limit;
            }
            $this->db->select('*');
            $this->db->from('torod_warehouse_log');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            if ($status != '')
                // $this->db->where('status', $status);
                $this->db->where("status like '%".addslashes($status)."%'");
                $this->db->order_by('id','DESC'); 
                $this->db->limit($limit, $start);
              
            $query = $this->db->get();
        //    echo  $this->db->last_query();exit;
            if ($query->num_rows() > 0) {
    
    
                //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
                $data['result'] = $query->result_array();
                $data['count'] = $this->warehouseLogCount($status);
                return $data;
                // return $page_no.$this->db->last_query();
            } else {
                $data['result'] = '';
                $data['count'] = 0;
                return $data;
            }
        }

        public function warehouseLogCount($status) {
    
            
            $this->db->select('COUNT(id) as sh_count');
            $this->db->from('torod_warehouse_log');
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            if ($status != '')
            $this->db->where("status like '%".addslashes($status)."%'");
                $this->db->order_by('id','DESC'); 
                
            $query = $this->db->get();
       
            if ($query->num_rows() > 0) {

                $data = $query->result_array();
                return $data[0]['sh_count'];
                // return $page_no.$this->db->last_query();
            }
            return 0;
        }

        public function Update_Automation_Torod($val){
            $data = array(
                'torod_automation_flag' => $val
            );
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->update('site_config', $data);
            return true;
        }

        

  

}
    
