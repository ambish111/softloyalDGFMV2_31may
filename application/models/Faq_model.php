<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Faq_model extends CI_Model {
public $FULFIL;
public $LMDB;
    function __construct() {
        parent::__construct();
        /*$this->FULFIL=$this->load->database('fulfil_db',TRUE);
        $this->LMDB=$this->load->database('lastmile_id',TRUE);
        $this->LMDB=$this->load->database('default',TRUE);*/  
    }

    public function add_faq($data,$id) {
       if($id>0)
       {
       return  $this->db->update('faq_fm', $data,array('id'=>$id));
       }
       else
       {
          return  $this->db->insert('faq_fm', $data); 
       }
       
    }

    public function add($data){
		$this->db->insert('faq_fm',$data);
		//echo $this->db->last_query(); die;
		return $this->db->insert_id();
	}
    
    public function filter(array $data){
        
        $page_no;
           $limit = ROWLIMIT;
         if(empty($data['page_no'])){
             $start = 0;
         }else{
             $start = ($data['page_no']-1)*$limit;
         }    
       
         $this->db->select('*');
         $this->db->from('faq_fm');
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
         $this->db->order_by('faq_fm.id', 'DESC');
       
        /* if(!empty($data['client_id']))
          $this->db->where('client_id',$data['client_id']);*/
       // if(!empty($data['phone']))
 //       $this->db->where('phone',$data['phone']);
        //  $this->db->where('deleted','N');
        
          $this->db->limit($limit, $start);
             
         $query = $this->db->get();
        
//echo $this->db->last_query(); die;    
         if($query->num_rows()>0){
          
           $data['result']=$query->result_array();
          $data['count']=$this->filterCount($data); 
             return $data;
          // return $page_no.$this->db->last_query();
 
         }
         else{
           $data['result']='';
          $data['count']=0; 
             return $data;
         }
      
 
         
       }

       public function UpdateQry(array $data,$id=null){  
        return  $this->db->update('faq_fm',$data,array('id'=>$id));
       }
    

       public function ClientEditDataQry($id=null){
		
        $this->db->where('id' , $id);
        $query = $this->db->get('faq_fm');
        //echo $this->db->last_query();
        if($query->num_rows()>0){
            return $query->row_array();
          
          }
    
      }	


       public function filterCount(array $data){
        
      
        $this->db->select('COUNT(id) as idCount');
        $this->db->from('faq_fm'); 
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
		// $this->db->where('usertype',$data['type']);
		// $this->db->where('deleted','N');
        $query = $this->db->get();
      //return $this->db->last_query(); die;
        if($query->num_rows()>0){
            return $query->row_array()['idCount'];
         // return $page_no.$this->db->last_query();
        }
        else{
         return 0;
        }
     

        
      }

}
