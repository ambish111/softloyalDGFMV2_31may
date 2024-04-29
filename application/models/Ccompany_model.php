<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ccompany_model extends CI_Model { 

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    
    public function ccNamebiid($ccid=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        
        if(!empty($ccid))
        {
        $this->db->where('id', $ccid);
        }
        $this->db->where('deleted', 'N');
        $this->db->order_by('company');
        $this->db->select('company');
        $query = $this->db->get('courier_company');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            $result= $query->row_array();
             return  $result['company'];
        }
    }


    public function all($data=array()) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        
        if(!empty($data['cc_id']))
        {
            $this->db->where('id', $data['cc_id']);
        }
        $this->db->where('fp_flag', 'N');
        $this->db->where('deleted', 'N');
        $this->db->order_by('company');
        $this->db->select('*');
        $query = $this->db->get('courier_company');
        //echo $this->db->last_query(); die;
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }


 

    public function GetUpdateDeliveryCOmpany($data = array(), $data_w) {
        return $this->db->update('courier_company', $data, $data_w);
        //echo $this->db->last_query();
        //die;
    }

    public function GetUpdateDeliveryCOmpanySeller($data = array(), $data_w) {
        return $this->db->update('courier_company_seller', $data, $data_w);
        //echo $this->db->last_query();
        //die;
    }

   
    public  function Warehouse_field($id = null) {
           
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,name,city_id,wh_address');
        $this->db->from('warehouse_m');
        $this->db->where('id', $id);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query->row_array();
        }
    
   
    

    public function GetWarehouselistDrop() {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,name,city_id,wh_address');
        $this->db->from('warehouse_m');
        $this->db->where('deleted', 'N');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }



    public function GetCompanylistDropQry() {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,cc_id,company');
        $this->db->from('courier_company');
        $this->db->where('deleted', 'N');
        //$this->db->where('reverse_type', 'N');
        $this->db->where("(api_url!='' or api_url_t!='')");
        $this->db->where('status', 'Y');

        $this->db->order_by("company");
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function GetSlipNoDetailsQry($slip_no = null,$super_id=null) {

        $this->db->where('super_id', $super_id);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('slip_no', $slip_no);
        $this->db->where('deleted', 'N');
        $this->db->where('frwd_company_id', 0);
        $this->db->where('forwarded', 0);
        $this->db->where('code !=', 'LSD');
        // $this->db->where('status', 'Y');
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function GetSlipNoDetailsReverse($slip_no = null,$super_id=null) {
        $codearr = array('POD','LSD');

        $this->db->where('super_id', $super_id);
        $this->db->select('*');
        $this->db->from('shipment_fm');
        $this->db->where('slip_no', $slip_no);
        $this->db->where('booking_id!=',$slip_no);
        $this->db->where('deleted', 'N');
    
        $this->db->where_in('code', $codearr);
        // $this->db->where('code', 'POD');
        $this->db->where('reverse_forwarded', 0);
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query->row_array();
    }


   

    public function GetdeliveryCompanyUpdateQry($cc_id = null,$ShipArr_custid = null,$super_id=null,$fp_status=null) {

        if($fp_status == 'Y'){
            $this->db->where('super_id', $super_id);
            $this->db->where('cc_id', $cc_id);
            $this->db->select('*');
            $this->db->from('courier_company');
            $this->db->where('deleted', 'N');
            $this->db->where('status', 'Y');
            $this->db->where('fp_flag', 'Y');
           // $this->db->where('api_url != ', '');
            $this->db->order_by("company");
            $query = $this->db->get();
//            echo $this->db->last_query();exit;
            $result =  $query->row_array();
        }
        else
        {
            $this->db->where('super_id', $super_id);
            $this->db->where('cc_id', $cc_id);
            $this->db->select('*');
            $this->db->from('courier_company_seller');
            $this->db->where('deleted', 'N');
            $this->db->where('status', 'Y');
            $this->db->where('cust_id', $ShipArr_custid);
            //$this->db->where('api_url != ', '');
            $this->db->order_by("company");
            $query = $this->db->get();
        // echo $this->db->last_query();exit;

            if ($query->num_rows()> 0)
            {
                //echo "num rows = ".$query->num_rows(); 
                // echo $this->db->last_query();exit;
                return $query->row_array();
            }
            else 
            {

                $result = array();
            
                if(empty($result)){
                    $this->db->where('super_id', $super_id);
                    $this->db->where('cc_id', $cc_id);
                    $this->db->select('*');
                    $this->db->from('courier_company');
                    $this->db->where('deleted', 'N');
                    $this->db->where('status', 'Y');
                // $this->db->where('api_url != ', '');
                    $this->db->order_by("company");
                    $query = $this->db->get();
                // echo $this->db->last_query();exit;
                $result =  $query->row_array();
                }
                

            }
        }
         return $result;

    }

    
     public function GetMidDetailsQry($mid = null,$super_id) {

        //$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('super_id', $super_id);
        $this->db->select('*');
        $this->db->from('pickup_request');
        $this->db->where('uniqueid ', $mid);
        $query = $this->db->get();
      //  echo $this->db->last_query(); exit;
        return $query->row_array();
    }
    

    public function getdestinationfieldshow($id = null, $field = null) {
        $ci = & get_instance();
        $ci->load->database();

        $sql = "SELECT $field FROM country where id='$id' and super_id='" . $this->session->userdata('user_details')['super_id'] . "'";
        $query = $ci->db->query($sql);
        $result = $query->row_array();
        return $result[$field];
    }

//  public function getdestinationfieldshow_auto_array($id=null,$field=null,$super_id){
//	
//                
//     $sql ="SELECT $field FROM country where id='$id' and super_id='".$super_id."'";
//       $query = $this->db->query($sql);
//
//      
//       $result=$query->row_array();
//       return $result[$field];
//   }

 
   public function Generate_awb_number_new_fm($super_id = null) {
    $ci = & get_instance();
    $ci->load->helper('utility');
    $random_chars2 = mt_rand(1000000000, 9999999999);
    $default_format = site_configTable('default_awb_char_fm', $super_id);
    //echo "d=".$default_format;die;
    if (empty($default_format)) {
        $default_format = "FSL";
    }
    $generate_awb_no = $default_format . strtoupper($random_chars2);
    
    $check = checkAwbNumberExits_fm($generate_awb_no);
    if ($check == 1) {
        Generate_awb_number_new_fm();
    } else {
        return $generate_awb_no;
    }
}
   






   public function Insert_Reverse_Shipment($sku_data = null, $ShipArr= null, $slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null, $barq_order_id= null) 
   {
      $updateArr = array(); 
      if ($company == 'Esnad')
      {
           $label_type = 1;          
      }
      elseif ($company == 'Barqfleet')
      {  
             $label_type = 0;
             $barq_order_id = $barq_order_id;                 
      }      
      else
      {
           $label_type = 0;
           $barq_order_id = 0;
         
      }
       $super_id = $this->session->userdata('user_details')['super_id'];


      $wh_id =  GetwarehouseData_super_id();
       
       $ReverseShipmentArr = array(
           'user_id' => $super_id,
           'shippers_ac_no' => $ShipArr['shippers_ac_no'],
           'booking_id' =>  trim($ShipArr['old_slip_no']),
           'shippers_ref_no' =>  trim($ShipArr['old_slip_no']),
           'nrd' => 'Parcel',
           'slip_no' => $ShipArr['slip_no'],
           'origin' => $ShipArr['origin'],
           'destination' => $ShipArr['destination'],
           'pieces' => ($ShipArr['pieces']>0)?$ShipArr['pieces']:1,
           'weight' => trim($data['weight']),
           'volumetric_weight' => trim($data['weight']),
           'sender_name' => trim($ShipArr['sender_name']),
           'sender_address' => trim($ShipArr['sender_address']),
           'sender_phone' => trim($ShipArr['sender_phone']),
           //'sender_city' =>$ShipArr['origin'],
           'sender_email' => $ShipArr['sender_email'],
           'reciever_name' => $ShipArr['reciever_name'],
           'reciever_address' => addslashes($ShipArr['reciever_address']),
           'reciever_phone' => $ShipArr['reciever_phone'],
           //'reciever_city' =>  $ShipArr['destination'],
           'reciever_email' => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
           'status_describtion' => addslashes($ShipArr['status_describtion']),
           'entrydate' => $CURRENT_DATE,
           'mode' => $ShipArr['pay_mode'],
           'delivered' => '21',
           'cust_id' => $ShipArr['cust_id'],
           //'total_cod_amt' => $data['cod'],
           //'TotalCOD' => '0',
           'service_id' => ($ShipArr['service_id']>0)?$ShipArr['service_id']:1,
           'sku' => $ShipArr['sku'],
           //'CURRENT_TIME' => $CURRENT_TIME,
           //'user_type' => 'customer',
           'fulfillment' => 'Y',   
           'super_id' => $super_id,
           'barq_order_id' => $barq_order_id,
           'reverse_forwarded' => 1,
           'frwd_date' => $CURRENT_DATE, 
           'frwd_company_id' => $c_id, 
           'frwd_company_awb' => trim($client_awb), 
           'frwd_company_label' => $fastcoolabel, 
           'forwarded' => 1, 
           'code' => 'RPC', 
           'label_type' => $label_type,
           'wh_id'=>$wh_id
       );
       

     //   print "<pre>"; print_r($ReverseShipmentArr);die;
    //    print "<pre>"; print_r($ShipArr);
       
    //    die;
       $this->GetshipmentAdd_reverse($ReverseShipmentArr);

       $details = 'Forwarded to ' . $company;
       $statusArr = array(
           'slip_no' => $ShipArr['slip_no'],           
           'new_status' => 21,
           'pickup_time' => $CURRENT_TIME,
           'pickup_date' => $CURRENT_DATE,
           'Activites' => 'Forward to Delivery Station',
           'Details' => $details,
           'entry_date' => $CURRENT_DATE,
           'user_id' => $this->session->userdata('user_details')['super_id'],
           'user_type' => 'fulfillment',
           'comment' => $comment,
           'code' => 'RPC',
           'super_id' => $super_id,
       );
       $this->GetstatuInsert_reverse($statusArr);

       $sku_data = $ShipArr['sku_data'];
       foreach ($sku_data as $key => $val) {
        
        $diamentionArr[] = array(
            'sku'=>$sku_data[$key]['sku'],
            'description'=>$sku_data[$key]['description'],
            'booking_id'=>$ShipArr['booking_id'],
            'slip_no'=>$ShipArr['slip_no'],
            'cod'=> 0,
            'piece'=> $sku_data[$key]['piece'],
            'super_id'=> $this->session->userdata('user_details')['super_id'],
            'cust_id'=>$ShipArr['cust_id'],
            'entry_date'=>date('Y-m-d H:i:s'),
            );
        }
    $this->DiamentionalInsert_reverse($diamentionArr);
    //    return true;
   }

   public function GetshipmentAdd_reverse(array $data) {
     
       $this->db->insert('shipment_fm', $data);
    // echo  $this->db->last_query();
   }
   public function DiamentionalInsert_reverse(array $data) {
     
       $this->db->insert_batch('diamention_fm', $data);
       //echo $this->db->last_query(); 
   }

   public function GetstatuInsert_reverse(array $data) {

       $this->db->insert('status_fm', $data);
     // echo $this->db->last_query();
   }


    public function Update_Manifest_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null, $barq_order_id= null) 
    {  

       if ($company == 'Esnad'){
            $label_type = 1;
            $updateArr = array('3pl_date' => $CURRENT_DATE, '3pl_name' => $c_id, '3pl_awb' => trim($client_awb), '3pl_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type,'code' => 'AT', 'pstatus' => 6);
        }
        elseif ($company == 'Barqfleet')
        { 
                $label_type = 0;
                $updateArr = array('3pl_date' => $CURRENT_DATE, '3pl_name' => $c_id, '3pl_awb' => trim($client_awb), '3pl_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type,'code' => 'AT', 'pstatus' => 6);   
                  
        }
        else
        {
            $label_type = 0;
            $updateArr = array('3pl_date' => $CURRENT_DATE, '3pl_name' => $c_id, '3pl_awb' => trim($client_awb), '3pl_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type,'code' => 'AT', 'pstatus' => 6);
        }
        //print "<pre>"; print_r($updateArr);die;
        $this->GetmanifestUpdate_forward($updateArr, $slipNo);
        return true;
    }

    public function GetSkuData($itemData = array(),$sellerID= array()){
        $sql = "SELECT items_m.sku, inventory_damage.quantity  FROM `inventory_damage` JOIN items_m ON items_m.id=inventory_damage.item_sku WHERE inventory_damage.id  IN (".implode(',',$itemData).") and inventory_damage.seller_id=".$sellerID." and inventory_damage.return_update='N' and items_m.super_id=".$this->session->userdata('user_details')['super_id']." ";
        $ci = & get_instance();
        $ci->load->database();
        $query = $ci->db->query($sql);
        //echo $this->db->last_query(); die;
        $result = $query->result_array();    
        return $result;
    }
    
    public function get_damage_sku_details($itemID,$sellerID){
        $sql = "SELECT items_m.sku, inventory_damage.quantity FROM `inventory_damage` JOIN items_m ON items_m.id=inventory_damage.item_sku WHERE inventory_damage.id=".$itemID."   and inventory_damage.seller_id=".$sellerID." and inventory_damage.return_update='N' and items_m.super_id=".$this->session->userdata('user_details')['super_id']." and inventory_damage.status_type='Damage' ";
        $ci = & get_instance();
        $ci->load->database();
        $query = $ci->db->query($sql);
        return  $query->result_array();
    }
    
    
    public function Update_Manifest_Return_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null,$dataArray,$shiparray,$itemData, $super_id){
        
        switch($company){
            case 'Esnad': $label_type = 1; break;
            case 'Barqfleet': $label_type = 0; break;
            default: $label_type = 0; break;
        }
      //  print "<pre>"; print_r($this->session->userdata);die;
        
        if(!empty($itemData) && count($itemData)>0){
            foreach($itemData as $itemid){
                
                $result = $this->Ccompany_model->get_damage_sku_details($itemid,$dataArray['sellerid']);
                //print "<pre>"; print_r($result);
                if(is_array($result) && count($result)>0){
                    $updateArr = array(
                        'uniqueid'=>$slipNo,
                        'seller_id'=>$dataArray['sellerid'],
                        'sku'=>$result[0]['sku'],
                        'qty'=>$result[0]['quantity'],
                        'user_id'=>$this->session->userdata('user_details')['user_id'],
                        'user_type'=>$this->session->userdata('user_details')['user_type'],
                        'super_id'=>$this->session->userdata('user_details')['super_id'],
                        'address'=>$shiparray['reciever_address'],
                        'city'=>$shiparray['destination'],
                        'boxes'=>$dataArray['boxes'],
                        'pack_type'=>$dataArray['pack_type'],
                        'return_type'=>'Y',
                        'r_3pl_date' => $CURRENT_DATE,
                        'r_3pl_name' => $c_id,
                        'r_3pl_awb' => trim($client_awb),
                        'r_3pl_label' => $fastcoolabel,
                        'label_type' => $label_type,
                        'code' => 'AT',
                        'pstatus' => 6
                    );
                    
                    $this->db->insert('pickup_request', $updateArr);
                    //echo $this->db->last_query();die;
                    $data = array('return_update'=>'Y');
                    $this->db->where('super_id',$super_id);
                    $this->db->update('inventory_damage', $data, array('id' => $itemid));
                }
            }
        }
        return true;
    }

    public function GetmanifestUpdate_forward(array $data, $awb = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('pickup_request', $data, array('uniqueid' => $awb));
     //echo $this->db->last_query();die;
    }

    public function Getskudetails_forward($slip_no=null)    {
        $this->db->where('diamention_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('items_m.super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('diamention_fm.sku,diamention_fm.description,diamention_fm.piece,diamention_fm.cod,items_m.name,items_m.weight');
        $this->db->from('diamention_fm');
        $this->db->join('items_m', 'items_m.sku=diamention_fm.sku');
        $this->db->where('diamention_fm.slip_no',$slip_no);
        
        $query = $this->db->get();
        //echo $this->db->last_query(); ;die;
        return $query->result_array();
        }

    public function GetshipmentUpdate_forward(array $data, $awb = null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('shipment_fm', $data, array('slip_no' => $awb));
         $this->db->last_query(); 
    }

    public function GetstatuInsert_forward(array $data) {

        $this->db->insert('status_fm', $data);
        //echo $this->db->last_query();
    }
    
   

    public function AramexArrayAdvance($sellername = null, array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$box_pieces1= null, $totalcustomerAmt=null,$super_id = null )
    {    
       
        //$sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'aramex_city', $super_id);
        //$sender_name =  $ShipArr['sender_name'];
        $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'aramex_country_code',$super_id);

        
        $label_info_from =  GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sender_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sender_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sender_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
        }        
     
        $reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'aramex_city',$super_id);       
        $C_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'aramex_country_code',$super_id);
        $ic_no = getdestinationfieldshow_auto_array($ShipArr['destination'], 'ic_no',$super_id);
        
        if($sender_country_code=='EG')
       { $entity='CAI';}
        else
       { $entity='RUH';}
        
        $date =  (int)microtime(true) * 1000;
        $default_currency = site_configTable("default_currency");

        if($totalcustomerAmt== NULL){
            $totalcustomerAmt= $ShipArr['total_cod_amt'];
        }
        


        if ($pay_mode == 'COD') {
            $cod_amount= $totalcustomerAmt;
            $shipment_value = $ShipArr['total_cod_amt'];
            $pay_mode = $counrierArr['aramex_payment_type'];
            //$CashOnDeliveryAmount = array("Value" => $cod_amount,                "CurrencyCode" => $currency);            $services = 'CODS';
        } elseif ($pay_mode == 'CC') {
            $cod_amount= "1.00"; 
            $pay_mode = $counrierArr['aramex_payment_type'];
            $CashOnDeliveryAmount = NULL;
            $services = '';
           // $shipment_value = $ShipArr['shipment_value'];  
            $shipment_value = "1.00";   
        }

        if ($C_code != $sender_country_code && $C_code != '') {
            $reciever_country = $C_code;
                $ProductGroup = $counrierArr['ProductGroup'];
              $ProductType = $counrierArr['ProductType'];
            $currency = getdestinationfieldshow($ShipArr['destination'], 'currency'); //"USD";
            if($currency=='BHD' || $currency=='QAR' || $currency=='EGP')
            {
                $currency='USD';
            }
            if($C_code == 'EG'){
                $currency='USD';
            }

            if ($ShipArr['mode'] == 'COD') {
                $cod_amount=$cod_amount;
                $CashOnDeliveryAmount = array("Value" => $totalcustomerAmt,
                    "CurrencyCode" => $currency);
            }else{
               $CashOnDeliveryAmount = NULL;
   
            }

            


            if(!empty($shipment_value))
            {
                 $CustomsValueAmount = array("Value" => $shipment_value,
                "CurrencyCode" => $currency); 
            }
            else
            {
                 $CustomsValueAmount = array("Value" => $totalcustomerAmt,
                "CurrencyCode" => $currency); 
            }
          //  echo $currency; die;
            

        } else {
            $reciever_country = $C_code;
            $ProductGroup = 'DOM';
            $currency = $default_currency;
            $ProductType = 'CDS';
            $CustomsValueAmount = array("Value" => 0,
                "CurrencyCode" =>$default_currency);
        }

        if(empty($box_pieces1)){
            $box_pieces = 1;
        }
        else { 
             $box_pieces = $box_pieces1 ; }

             if ($ShipArr['weight'] >= 0  && $ShipArr['weight'] <= 0.49) {
                $weight = 0.50;
            }else 
            {   
                    $weight = round($ShipArr['weight']);
            }
        
        $complete_sku = $this->stripInvalidXml($complete_sku);
        $complete_sku=	mb_substr($complete_sku,0,100,"UTF-8"); 
        //$complete_sku = $this->stripInvalidXml($complete_sku);
        $params = array(
                                    'ClientInfo' =>
                                    array(
                                        'UserName' => $counrierArr['user_name'],
                                        'Password' => $counrierArr['password'],
                                        'Version' => 'v1',
                                        'AccountNumber' => $counrierArr['courier_account_no'],
                                        'AccountPin' => $counrierArr['courier_pin_no'],
                                        'AccountEntity' => $entity,
                                        'AccountCountryCode' => $sender_country_code
                                    ),
                                    'LabelInfo' => array("ReportID" => 9729, "ReportType" => "URL"),
                                    'Shipments' =>
                                    array(
                                        0 =>
                                        array(
                                            'Reference1' => $ShipArr['slip_no'],
                                            'Reference2' => '',
                                            'Reference3' => '',
                                            'Shipper' =>
                                            array(
                                                'Reference1' => $ShipArr['slip_no'],
                                                'Reference2' => '',
                                                'AccountNumber' => $counrierArr['courier_account_no'],
                                                'PartyAddress' =>
                                                array(
                                                    'Line1' => $sender_address,
                                                    'Line2' => '',
                                                    'Line3' => '',
                                                    'City' => $sender_city,
                                                    'StateOrProvinceCode' => '',
                                                    'PostCode' => '0000',
                                                    'CountryCode' => $sender_country_code,
                                                    'Longitude' => 0,
                                                    'Latitude' => 0,
                                                    'BuildingNumber' => NULL,
                                                    'BuildingName' => NULL,
                                                    'Floor' => NULL,
                                                    'Apartment' => NULL,
                                                    'POBox' => NULL,
                                                    'Description' => NULL,
                                                ),
                                                'Contact' =>
                                                array(
                                                    'Department' => '',
                                                    'PersonName' => $sender_name,
                                                    'Title' => '',
                                                    'CompanyName' => $sender_name,
                                                    'PhoneNumber1' => $sender_phone,//$ShipArr['sender_phone'],
                                                    'PhoneNumber1Ext' => '',
                                                    'PhoneNumber2' => '',
                                                    'PhoneNumber2Ext' => '',
                                                    'FaxNumber' => '',
                                                    'CellPhone' => $sender_phone,//$ShipArr['sender_phone'],
                                                    'EmailAddress' => $sender_email,//'support@diggipacks.com',
                                                    'Type' => '',
                                                ),
                                            ),
                                            'Consignee' =>
                                            array(
                                                'Reference1' => '',
                                                'Reference2' => '',
                                                'AccountNumber' => '',
                                                'PartyAddress' =>
                                                array(
                                                    'Line1' => $ShipArr['reciever_address'],
                                                    'Line2' => '',
                                                    'Line3' => '',
                                                    'City' => $reciever_city,
                                                    'StateOrProvinceCode' => '',
                                                    'PostCode' => !empty($ShipArr['reciever_pincode'])?$ShipArr['reciever_pincode']:'0000',
                                                    'CountryCode' => $reciever_country,
                                                    'Longitude' => 0,
                                                    'Latitude' => 0,
                                                    'BuildingNumber' => '',
                                                    'BuildingName' => '',
                                                    'Floor' => '',
                                                    'Apartment' => '',
                                                    'POBox' => NULL,
                                                    'Description' => '',
                                                ),
                                                'Contact' =>
                                                array(
                                                    'Department' => '',
                                                    'PersonName' => $ShipArr['reciever_name'],
                                                    'Title' => '',
                                                    'CompanyName' => $ShipArr['reciever_name'],
                                                    'PhoneNumber1' => $ShipArr['reciever_phone'],
                                                    'PhoneNumber1Ext' => '',
                                                    'PhoneNumber2' => '',
                                                    'PhoneNumber2Ext' => '',
                                                    'FaxNumber' => '',
                                                    'CellPhone' => $ShipArr['reciever_phone'],
                                                    'EmailAddress' =>  !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                                                    'Type' => '',
                                                ),
                                            ),
                                            'ThirdParty' => array(
                                                'Reference1' => '',
                                                'Reference2' => '',
                                                'AccountNumber' => $counrierArr['courier_account_no'],
                                                'PartyAddress' => array(
                                                    'Line1' => $sender_address,
                                                    'Line2' => '',
                                                    'Line3' => '', 
                                                    'City' => $sender_city,
                                                    'StateOrProvinceCode' => '',
                                                    'PostCode' => '0000',
                                                    'CountryCode' =>$sender_country_code,
                                                    'Longitude' => 0,
                                                    'Latitude' => 0,
                                                    'BuildingNumber' => NULL,
                                                    'BuildingName' => NULL,
                                                    'Floor' => NULL,
                                                    'Apartment' => NULL,
                                                    'POBox' => NULL,
                                                    'Description' => NULL,
                                                ),
                                                'Contact' => array(
                                                    'Department' => '',
                                                    'PersonName' => $sender_name,
                                                    'Title' => '',
                                                    'CompanyName' => $sender_name,
                                                    'PhoneNumber1' => $sender_phone,//$ShipArr['sender_phone'],
                                                    'PhoneNumber1Ext' => '',
                                                    'PhoneNumber2' => '',
                                                    'PhoneNumber2Ext' => '',
                                                    'FaxNumber' => '',
                                                    'CellPhone' => $sender_phone,//$ShipArr['sender_phone'],
                                                    'EmailAddress' => $sender_email,//'support@diggipacks.com',
                                                    'Type' => '',
                                                ),
                                            ),
                                            'ShippingDateTime' => "/Date(" . $date . ")/",
                                            'DueDate' => "/Date(" . $date . ")/",
                                            'Comments' => '',
                                            'PickupLocation' => $sender_city,
                                            'OperationsInstructions' => '',
                                            'AccountingInstrcutions' => '',
                                            'Details' =>
                                            array(
                                                'Dimensions' => NULL,
                                                'ActualWeight' =>
                                                array(
                                                    'Unit' => 'KG',
                                                    'Value' => $weight,
                                                ),
                                                'ChargeableWeight' => NULL,
                                                'DescriptionOfGoods' => $complete_sku,
                                                'GoodsOriginCountry' => $sender_country_code,
                                                'NumberOfPieces' => $box_pieces,
                                                'ProductGroup' => $ProductGroup,
                                                'ProductType' => $ProductType,
                                                'PaymentType' => $pay_mode,
                                                'PaymentOptions' => "",
                                                'CustomsValueAmount' => $CustomsValueAmount,
                                                'CashOnDeliveryAmount' => $CashOnDeliveryAmount,
                                                'InsuranceAmount' => NULL,
                                                'CashAdditionalAmount' => NULL,
                                                'CashAdditionalAmountDescription' => '',
                                                'CollectAmount' => NULL,
                                                'Services' => $services,
                                                'Items' =>
                                                array(
                                                ),
                                            ),
                                            'Attachments' =>
                                            array(),
                                            'ForeignHAWB' =>'',// $ShipArr['slip_no'],
                                            'TransportType ' => 0,
                                            'PickupGUID' => '',
                                            'Number' => NULL,
                                            'ScheduledDelivery' => NULL,
                                        ),
                                    ),
                                    'Transaction' =>
                                    array(
                                        'Reference1' => '',
                                        'Reference2' => '',
                                        'Reference3' => '',
                                        'Reference4' => '',
                                        'Reference5' => '',
                                    )
                                );
          //echo "<pre> sdfsdfsd = "; print_r(($params));  die; 
        return $params; 
    }

    public function AramexArray($sellername = null, array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null,$box_pieces1= null,$super_id = null ) 
    {
        
        $siteData=Getsite_configData();
      // print_r($ShipArr); exit;
        //$sender_default_city= Getselletdetails_new($super_id);
        //$sender_address = $sender_default_city['0']['address'];
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);

        $complete_sku=	mb_substr($complete_sku,0,100,"UTF-8"); 
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'aramex_city', $super_id);


        //$sender_address = $ShipArr['sender_address'];
        
        //$sender_name =  $ShipArr['sender_name'];

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $seller_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $seller_name = $seller_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $seller_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $seller_name = $seller_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $seller_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $seller_name = $seller_name ." - ". site_configTable('company_name'); 
            }
        }
        
       

        $reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'aramex_city',$super_id);
        $date =  (int)microtime(true) * 1000;
        
        if ($pay_mode == 'COD') {
            $pay_mode = '3';
           
    } elseif ($pay_mode == 'CC') {
            $pay_mode = '3';
            
    }
        if(empty($box_pieces1)){
            $box_pieces = 1;
        }
        else { 
             $box_pieces = $box_pieces1 ; }

            if ($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                  $weight = 1;
             }else {
                  $weight = $ShipArr['weight'];
             }




        $params = array(
            'ClientInfo' => array(
                'UserName' => $counrierArr['user_name'],
                'Password' => $counrierArr['password'],
                'Version' => 'v1',
                'AccountNumber' => $counrierArr['courier_account_no'],
                'AccountPin' => $counrierArr['courier_pin_no'],
                'AccountEntity' => 'RUH',
                'AccountCountryCode' => 'SA'
            ),
            'LabelInfo' => array("ReportID" => 9729, "ReportType" => "URL"),
            'Shipments' => array(
                0 => array(
                    'Reference1' => '',
                    'Reference2' => '',
                    'Reference3' => '',
                    'Shipper' => array(
                        'Reference1' => $ShipArr['slip_no'],
                        'Reference2' => '',
                        'AccountNumber' => $counrierArr['courier_account_no'],
                        'PartyAddress' => array(
                            'Line1' => $sender_address,
                            'Line2' => '',
                            'Line3' => '',
                            'City' => $sender_city,
                            'StateOrProvinceCode' => '',
                            'PostCode' => '0000',
                            'CountryCode' => 'SA',
                            'Longitude' => 0,
                            'Latitude' => 0,
                            'BuildingNumber' => NULL,
                            'BuildingName' => NULL,
                            'Floor' => NULL,
                            'Apartment' => NULL,
                            'POBox' => NULL,
                            'Description' => NULL,
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' =>$seller_name,//$ShipArr['sender_name'],
                            'Title' => '',
                            'CompanyName' =>$seller_name,//$ShipArr['store_name'] ,
                            'PhoneNumber1' => $sender_phone,//$ShipArr['sender_phone'],
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $sender_phone,//$ShipArr['sender_phone'],
                            'EmailAddress' => $sender_email,//$ShipArr['sender_email'],
                            'Type' => '',
                        ),
                    ),
                    'Consignee' => array(
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => '',
                        'PartyAddress' => array(
                            'Line1' => $ShipArr['reciever_address'],
                            'Line2' => '',
                            'Line3' => '',
                            'City' => $reciever_city,
                            'StateOrProvinceCode' => '',
                            'PostCode' => !empty($ShipArr['reciever_pincode'])?$ShipArr['reciever_pincode']:'0000',
                            'CountryCode' => 'SA',
                            'Longitude' => 0,
                            'Latitude' => 0,
                            'BuildingNumber' => '',
                            'BuildingName' => '',
                            'Floor' => '',
                            'Apartment' => '',
                            'POBox' => NULL,
                            'Description' => '',
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $ShipArr['reciever_name'],
                            'Title' => '',
                            'CompanyName' => $ShipArr['reciever_name'],
                            'PhoneNumber1' => $ShipArr['reciever_phone'],
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $ShipArr['reciever_phone'],
                            'EmailAddress' =>  !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                            'Type' => '',
                        ),
                    ),
                    'ThirdParty' => array(
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => $counrierArr['courier_account_no'],
                        'PartyAddress' => array(
                            'Line1' => $sender_address,//$siteData['company_address'],
                            'Line2' => '',
                            'Line3' => '',
                            'City' => $sender_city,
                            'StateOrProvinceCode' => '',
                            'PostCode' => '0000',
                            'CountryCode' => 'SA',
                            'Longitude' => 0,
                            'Latitude' => 0,
                            'BuildingNumber' => NULL,
                            'BuildingName' => NULL,
                            'Floor' => NULL,
                            'Apartment' => NULL,
                            'POBox' => NULL,
                            'Description' => NULL,
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $seller_name,//$siteData['company_name'],
                            'Title' => '',
                            'CompanyName' =>$seller_name,//$siteData['company_name'],
                            'PhoneNumber1' => $sender_phone,//$siteData['phone'],
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' =>$sender_phone,//$siteData['phone'],
                            'EmailAddress' => $sender_email,//$siteData['email'],
                            'Type' => '',
                        ),
                    ),
                    'ShippingDateTime' => "/Date(" . $date . ")/",
                    'DueDate' => "/Date(" . $date . ")/",
                    'Comments' => '',
                    'PickupLocation' => '',
                    'OperationsInstructions' => '',
                    'AccountingInstrcutions' => '',
                    'Details' => array(
                        'Dimensions' => NULL,
                        'ActualWeight' => array(
                            'Unit' => 'KG',
                            'Value' => $weight,
                            //'Value' => '1',
                        ),
                        'ChargeableWeight' => NULL,
                        'DescriptionOfGoods' => $complete_sku,
                        'GoodsOriginCountry' => 'SA',
                        'NumberOfPieces' => $box_pieces,
                        'ProductGroup' => 'DOM',
                        'ProductType' => 'CDS',
                        'PaymentType' => $pay_mode,
                        'PaymentOptions' => "",
                        'CustomsValueAmount' => NULL,
                        'CashOnDeliveryAmount' => $CashOnDeliveryAmount,
                        'InsuranceAmount' => NULL,
                        'CashAdditionalAmount' => NULL,
                        'CashAdditionalAmountDescription' => '',
                        'CollectAmount' => NULL,
                        'Services' => $services,
                        'Items' => array(),
                    ),
                    'Attachments' => array(),
                    'ForeignHAWB' => $ShipArr['slip_no'],
                    'TransportType ' => 0,
                    'PickupGUID' => '',
                    'Number' => NULL,
                    'ScheduledDelivery' => NULL,
                ),
            ),
            'Transaction' => array(
                'Reference1' => '',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            )
        );
      //echo "<pre>"; print_r($params); exit; 
        return $params;
    }

    public function AxamexCurl($url = null, array $headers, $dataJson = null, $c_id = null, array $ShipArr) {
       
    // echo    $url; die; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
         $response = curl_exec($ch);
       
       curl_close($ch);
        $xml2 = new SimpleXMLElement($response);
        $awb_array = json_decode(json_encode((array) $xml2), TRUE);

       // echo  $response; 

        //print_r($awb_array);
        
        
        //die; 
        $logresponse =   json_encode($awb_array);
        $successres = $awb_array['HasErrors'];
       
            if($successres == 'true') 
            {
                $successstatus  = "Fail";
            }else {
                $successstatus  = "Success";
            }
        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }    
        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);

        return $awb_array;
    }


    public function KwickBoxLabel($awb_no=null, $counrierArr=array(), $auth_token=null, $api_url=null){
        $curl = curl_init();
        $api_url = $api_url."printLabel";
        // echo $auth_token; die;
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "Numbers": [
            "'.$awb_no.'"
            ]
            }',
          CURLOPT_HTTPHEADER => array(
            'ApiKey:'.$auth_token,
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $responseData = json_decode($response,TRUE);
        // print "<pre>"; print_r($responseData);die;
        $media_data = $responseData['location'];
        return $media_data;
    }




    public function Update_Shipment_Status($slipNo = null, $client_awb = null, $CURRENT_TIME = null, $CURRENT_DATE = null, $company = null, $comment = null, $fastcoolabel = null, $c_id = null, $barq_order_id= null,$qty=null,$bosta_label_id=null, $cc_fp_flag=null,$fp_partner_type=null,$torod_order_id=null) 
    {
        $updateArr = array(); 
       if ($company == 'Esnad'){
            $label_type = 1;
            $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id,'bosta_label_id'=>$bosta_label_id,'FP_forwarded_by'=>$cc_fp_flag,'fp_partner_type'=>$fp_partner_type);         

        }
        elseif ($company == 'Bosta V2')
          {  $label_type = 0;
              $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' =>$barq_order_id,'bosta_label_id'=>$bosta_label_id,'FP_forwarded_by'=>$cc_fp_flag,'fp_partner_type'=>$fp_partner_type);         
          }
          elseif ($company == 'Barqfleet')
          {  $label_type = 0;
              $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' =>$barq_order_id,'bosta_label_id'=>$bosta_label_id,'FP_forwarded_by'=>$cc_fp_flag,'fp_partner_type'=>$fp_partner_type);         
          }
        elseif ($company == 'Saudi Post')
        {  $label_type = 0;
            $updateArr = array('pieces'=>$qty,'frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id,'bosta_label_id'=>$bosta_label_id,'FP_forwarded_by'=>$cc_fp_flag,'fp_partner_type'=>$fp_partner_type);         
        }elseif ($company == 'Torod'){  
            $label_type = 0;
            $updateArr = array('pieces'=>$qty,'frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id,'bosta_label_id'=>$bosta_label_id,'torod_order_id'=>trim($torod_order_id));   
            if(!empty($client_awb)){
                $updateArr = array('pieces'=>$qty,'frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type, 'barq_order_id' => $barq_order_id,'bosta_label_id'=>$bosta_label_id,'torod_order_id'=>trim($torod_order_id),'torod_automation_frwd'=>'Y');
            }
        }
      else
        {
            $label_type = 0;
            $updateArr = array('frwd_date' => $CURRENT_DATE, 'frwd_company_id' => $c_id, 'frwd_company_awb' => trim($client_awb), 'frwd_company_label' => $fastcoolabel, 'forwarded' => 1, 'label_type' => $label_type,'FP_forwarded_by'=>$cc_fp_flag,'fp_partner_type'=>$fp_partner_type);
        }
  
        $this->GetshipmentUpdate_forward($updateArr, $slipNo);

        if($company == 'Torod'){
            $details = 'Order Created in  ' . $company .' SlipNo: '.$slipNo.' : Torod Order ID: '.$client_awb;
        }else{
            $details = 'Forwarded to ' . $company .' SlipNo: '.$slipNo.' : Frwd AWB: '.$client_awb;
        }
        if($cc_fp_flag == 'Y' && $fp_partner_type=='FP'){
            $comment = 'Forwarded By Fastcoo Partner';
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

    public function CapacityUpdate($zone_cust_id=null, $zone_id=null,$super_id = null)
    {
       if(empty($zone_cust_id)){
            $this->db->query("UPDATE zone_list_fm SET todayCount = todayCount+1 WHERE id = ".$zone_id ." and super_id = ". $super_id);
            //     echo  $this->db->last_query();
            //  die; 
        }
        else{
            $this->db->query("UPDATE zone_list_customer_fm SET todayCount = todayCount+1 WHERE id = ".$zone_id." and super_id = ".$super_id);
            //     echo  $this->db->last_query();
            //  die; 
        }

       
    }

    public function SafeArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1=null,$super_id = null) {
        
        //$sellername =  $ShipArr['sender_name'];
        //$sender_address = $ShipArr['sender_address'];

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
           
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'safe_arrival',$super_id);
        
        $API_URL = $counrierArr['api_url'];
        
       
       if(empty($box_pieces1))
       { $box_pieces = 1;  }
         else { $box_pieces = $box_pieces1 ; }

         if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }

        if($ShipArr['pay_mode'] == "COD"){
            $pay_mode = "cash";
            $paid = 0;
        }
        else {
            $pay_mode = "credit_balance";
            $paid = 1;
        }

        $sender_data = array(
            "address_type" => "residential",
            "name" => $sellername,
            "email" => $sender_email,//$ShipArr['sender_email'],
            "street" => $sender_address,
            "city" => array(
                "id" =>$sender_city
            ),
            "country" => array(
                "id" => 191
            ),
           "phone" =>$sender_phone,//$ShipArr['sender_phone'],
        );
        
        
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "street" => $ShipArr['reciever_address'],
            "city" => array(
                "id" => $receiver_city
            ),

            "phone" => $ShipArr['reciever_phone'],
        );
        $dimensions = array(
            "weight" => $weight
        );
        $package_type = array(
            "courier_type" => 'IN_5_DAYS'
        );
        $charge_items = array(
            array(
                "paid" => $paid,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode']
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "credit_balance",
            "payer" => "recipient",
            "parcel_value" => $ShipArr['total_cod_amt'],
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );

       // echo "<br/><pre>";print_r($param);die;


        $header = array(
            "Authorization" => "Bearer ".$Auth_token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );

        $dataJson = json_encode($param);
        
        $response = send_data_to_safe_curl($dataJson, $Auth_token, $API_URL); 
        $safe_response = json_decode($response,TRUE);
        
        $logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
            //echo "<pre>"; print_r($response)   ;    //die;
       
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $dataJson);

        return $response;
    }

    public function ThabitArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1=null,$super_id = null) 
    {
      
        
        //$sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'thabit_city', $super_id);
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'thabit_city', $super_id);


        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];

        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }
              

        //echo $receiver_city; die;
        

       $API_URL = $counrierArr['api_url'];
       
        if(empty($box_pieces1))
       { $box_pieces = 1;  }
         else { $box_pieces = $box_pieces1 ; }

         if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
              $weight = 1;
         }else {
              $weight = $ShipArr['weight'];
         }

        if($ShipArr['pay_mode'] == "COD"){
            $pay_mode = "cash";
            $paid = 0;
        }
        else {
            $pay_mode = "credit_balance";
            $paid = 1;
        }

        $sender_data = array(
            "address_type" => "residential",
            "name" => $sellername,
            "email" => $sender_email,//$ShipArr['sender_email'],
            "street" => html_entity_decode($sender_address),
            "city" => array(
                "name" =>strtolower($sender_city)
            ),
            "country" => array(
                "id" => 191
            ),
           "phone" =>$sender_phone,//$ShipArr['sender_phone'],
        );
        
        
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "street" => html_entity_decode($ShipArr['reciever_address']),
            "city" => array(
                "name" => $receiver_city
            ),
            "country" => array(
                "id" => 191
            ),

            "phone" => $ShipArr['reciever_phone'],
        );
        $dimensions = array(
            "weight" => $weight
        );
        $package_type = array(
            "courier_type" => 'Next_Day_Delivery'
        );
        $charge_items = array(
            array(
                "paid" => $paid,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode']
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => $pay_mode, 
            "payer" => "recipient",
            "parcel_value" => $ShipArr['total_cod_amt'],
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
        
        $header = array(
            "Authorization" => "Bearer ".$Auth_token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        );

        $dataJson = json_encode($param);
        $response = send_data_to_thabit_curl($dataJson, $Auth_token, $API_URL);
        $safe_response =   json_decode($response, TRUE);
        
        $logresponse =   json_encode($response);  
        $successres = $safe_response['status'];
       
       
        if($successres == "success") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }
        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);

        return $safe_response;
    }

    public function EsnadArray($sellername=null, array $ShipArr, array $counrierArr, $esnad_awb_number = null, $complete_sku = null, $Auth_token = null,$c_id=null,$box_pieces1=null,$super_id) {
        
        
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'esnad_city',$super_id);     
        //$sender_default_city= Getselletdetails_new($super_id);
        //$sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        //$sender_name = $ShipArr['sender_name'];

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sender_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        
        }else{
            $sender_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sender_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
        }

        

        $receiver_cityID = getdestinationfieldshow_auto_array($ShipArr['destination'], 'esnad_city_code',$super_id);

        $declared_charge = $ShipArr['total_cod_amt'];
        $iscod = false;
        $cod_amount = $ShipArr['total_cod_amt'];
        
        if ($ShipArr['pay_mode'] == 'COD') {
            $pay_mode = "COD";
            //$declared_charge = 0;
            $iscod = true;
        }
        else {
            $pay_mode = "PP";
            $cod_amount = 0;
            $iscod = false;
            if($ShipArr['total_cod_amt']>0)
                 $declared_charge = $ShipArr['total_cod_amt'];
            else
                 $declared_charge = 1;
        }

        $comp_api_url = $counrierArr['api_url'];          
        $Auth_token = $counrierArr['auth_token'];     
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }

        $currency = site_configTable("default_currency");    
        
        $pckglist =  array();

        for($i=0;$i<$box_pieces; $i++)
        {
            $pckglist_pieces = array(
                "packageCode"=> $complete_sku,
                "packageHeight"=> 0,
                "packageLength"=>0,
                "packageVolume"=> $box_pieces,
                "packageWeight"=> (float)$weight,
                "packageWidth"=>0
            );
            array_push($pckglist, $pckglist_pieces);
        } 
    
        $param = array(
                "codAmount"=>"$cod_amount",
                "currency"=>$currency,
                "customerCode"=> $counrierArr['user_name'], //customer code requirement Kedan
                "customerNo"=> $ShipArr['slip_no'],
                "trackingNo"=> $ShipArr['slip_no'],
                "ifpickup"=> false,
                "token"=>$Auth_token,
                "isCod"=> $iscod,
                "orderAmount" =>$declared_charge,
                "packageList"=> $pckglist, 
                "receiver"=>array(
                    "address"=>html_entity_decode($ShipArr['reciever_address']) ,
                    "cityId"=> $receiver_cityID,//$ShipArr['destination'],
                    "cityName"=> $receiver_city,
                    "countryId"=> 1876,
                    "countryName"=> "Saudi Arabia",
                    "name"=> $ShipArr['reciever_name'],
                    "phone"=> $ShipArr['reciever_phone']
                ),
                "sender"=>array(
                    "address"=> html_entity_decode($sender_address),
                    "cityName"=> $sender_city,
                    "countryId"=> 1876,
                    "countryName"=> "Saudi Arabia",
                    "name"=>$sender_name,
                    "phone"=> $sender_phone,//$ShipArr['sender_phone']
                ),
                
                "totalInnerCount"=>1,
                "totalPackageCount"=>$box_pieces,
                "totalWeight"=>$weight,
                "totalVolume"=>$box_pieces,
                
            
        );

        
          $dataJson = json_encode($param); 
  
          //echo $dataJson;die;
        $headers = array(
            "Content-Type: application/json",
            "token: $Auth_token"
        );
        $response = send_data_to_curl($dataJson, $comp_api_url, $headers);
          // echo"<pre>";  print_r($response);    
        
        $logresponse =   json_encode($response); 
        $responseArray = json_decode($response, true);
        $successres = $responseArray['code'];
        $status = $responseArray['success'];
        if($successres  == "1000" || $status == true) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }


        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }
        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        return $response;
}


    public function LabaihArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id,$super_id) {
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $ShipArr['sender_address'];
        //$sender_name = $ShipArr['sender_name'];

          

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sender_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sender_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sender_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
        }        


        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'labaih',$super_id);
        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $declared_charge = $ShipArr['total_cod_amt'];
        $api_url = $counrierArr['api_url'];
        $senderData= Getsite_configData();
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['pay_mode'] === 'COD') {
            $cod_collection_mode = 'COD';
        } else {
            $cod_collection_mode = 'PREPAID';
            $cod_amount = 0;
        }
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        
        if(!empty($receiver_city ))
        {

        

        $comp_api_url = $counrierArr['api_url']."order/create";
        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));
        if(!empty($lat)&& !empty($lang))
       $latlang= $lat . ',' . $lang;
       else
       $latlang="";

       if(!empty($ShipArr['dest_lat']) && !empty($ShipArr['dest_lng']))
             $lattitute= $ShipArr['dest_lat'] . ',' . $ShipArr['dest_lng'];
       else
          $lattitute="";

      
        $apikey= $api_url."order/pickupPoints?api_key=".$counrierArr['auth_token'];
        $pickuppointarray = json_decode(file_get_contents($apikey),true);
        $pickuppoint = $pickuppointarray['data'][0]['pickupPointName'];


        $Data_array = array(
            'api_key' => $counrierArr['auth_token'],            
            'customerOrderNo' => $ShipArr['slip_no'], 
            'noOfPieces' => $box_pieces,
            'weightKgs' => $weight,
            'itemDescription' => $complete_sku,
            'paymentMethod' => $cod_collection_mode,
            'paymentAmount' => $cod_amount,
            'consigneeName' => $ShipArr['reciever_name'], 
            'consigneeEmail' => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            'consigneeMobile' => $ShipArr['reciever_phone'],
            'consigneePhone' => $ShipArr['reciever_phone'], 
            'consigneeCity' => $receiver_city, 
            'consigneeAddress' => $ShipArr['reciever_address'],
            'consigneeFlatFloor' => '',
            'consigneeLatLong' => $lattitute,
            'consigneeSplInstructions' => $ShipArr['status_describtion'],
            'store' => $sender_name, 
            'shipperName' => $sender_name,//$senderData['company_name'],
            'shipperMobile' => $sender_phone,//$senderData['phone'],
            'shipperEmail' => $sender_email,//$senderData['email'],
            'shipperCity' => $sender_city,
            'shipperDistrict' => $sender_city,
            'shipperAddress' => $sender_address,
            'shipperLatLong' =>  $latlang,
            'pickuppoint_id'=> $pickuppoint,
        );
        

        $headers = array(
            "Content-Type: application/x-www-form-urlencoded",
            "cache-control: no-cache"
        );

        $dataJson = http_build_query($Data_array); 

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);       
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);        
        $response = curl_exec($ch); 
        curl_close($ch);
        $response_array = json_decode($response, true);   
        
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['status'];

        if($successres == 200) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);

        return $response_array;
        }
        else
        {
            $logresponse = "Receiver city empty";
            $successstatus  = "Fail";

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],'');
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],'');
            }
            //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
            return $response_array=array('message'=>'receiver city empty');
        }



    }
    public function ClexArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id,$super_id) {
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'clex',$super_id);
        
        $comp_api_url = $counrierArr['api_url'];
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['pay_mode'] == 'COD') {
            $billing_type = 'COD';
            // $cod_amount=0;
        } else {
            $billing_type = 'PREPAID';
            $cod_amount = 0;
        }
              
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }

       
        $pieces_array =array();
        for($i=1;$i<=$box_pieces;$i++){
            $pieces_array[] = array(
                'pieceNo' =>$i,
                'weight' =>$weight,
                'width'=>1,
                'height'=>1,
                'depth'=>1,
                'volumetricWeight'=>$weight,
               );
           }

        $request_data = array(
            'shipment_reference_number' => $ShipArr['slip_no'],
            'shipment_type' => 'delivery',
            'billing_type' => $billing_type,
            'collect_amount' => $cod_amount,
            'primary_service' => 'delivery',
            'secondary_service' => '',
            'item_value' => '',
            'consignor' => $sellername,
            'consignor_email' => $ShipArr['sender_email'],
            'origin_city' => $sender_city,
            'origin_area_new' => '',
            'consignor_street_name' => $sender_address,
            'consignor_building_name' => '',
            'consignor_address_house_appartment' => '',
            'consignor_address_landmark' => '',
            'consignor_country_code' => '+966',
            'consignor_phone' => remove_phone_format($ShipArr['sender_phone']),
            'consignor_alternate_country_code' => '',
            'consignor_alternate_phone' => '',
            'consignee' => $ShipArr['reciever_name'],
            'consignee_email' => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            'destination_city' => $receiver_city,
            'destination_area_new' => '',
            'consignee_street_name' => $ShipArr['reciever_address'],
            'consignee_building_name' => '',
            'consignee_address_house_appartment' => '',
            'consignee_address_landmark' => '',
            'consignee_country_code' => '+966',
            'consignee_phone' => $ShipArr['reciever_phone'],
            'consignee_alternate_country_code' => '',
            'consignee_alternate_phone' => '',
            'pieces_count' => $box_pieces,
            'order_date' => date('d-m-Y'),
            'commodity_description' => $complete_sku,
            'pieces' => $pieces_array
            // array(array(
            //         'weight_actual' => $weight,
            //         'volumetric_width' => '',
            //         'volumetric_height' =>'',
            //         'volumetric_depth' =>'',
            //     ))
        );
        $dataJson = json_encode($request_data);
       
        $access_token = $counrierArr['auth_token'];

        $headers = array(
            "Content-type:application/json",
            "Access-token:$access_token");

        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);

        $response_array = json_decode($response, true);
        //print "<pre>"; print_r($response_array);die;
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['message'];
        $error = isset($response_array['error'])?$response_array['error']:true;

        if($successres == 'Succesfully added.' || $error == false) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        return $response_array;
    }


    public function GetClexToken($courierArr = array()){
        
        $data_array = array('username'=>$courierArr['user_name'],'password'=>$courierArr['password']);
        $json_data = json_encode($data_array);

        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $courierArr['api_url']."login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_data,
        CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
            ));

        $response = curl_exec($curl);

        curl_close($curl);
        $responseArr = json_decode($response,true);
        if(isset($responseArr['token']) && !empty($responseArr['token'])){
            return $responseArr['token'];
        }
        return '';
        
    }
    
    public function ClexArrayVer1($sellername=null ,$ShipArr = array(), $counrierArr = array(), $complete_sku = null ,$box_pieces1 = null ,$c_id = null , $super_id=null,$auth_token = null){
        
        

        //$store_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'clex', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'clex',$super_id);

        $receiver_city_code = getdestinationfieldshow($ShipArr['destination'], 'clex_city_code', $super_id);
        $sender_city_code = getdestinationfieldshow($ShipArr['origin'], 'clex_city_code', $super_id);


         
        
        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        //print "<pre>";print_r($ShipArr);die;
        
        //$senderemail = $ShipArr['sender_email'];
        //$senderphone = $ShipArr['sender_phone'];
       // echo $senderphone;die;
        $senderphone  =  $senderphone; 
        $senderphone = ltrim($senderphone, '966 ');
        $senderphone = ltrim($senderphone, '0');
        $senderphone = '966' . $senderphone;
        $senderphone = str_replace(' ', '', $senderphone);

        $receiverphone  =  $ShipArr['reciever_phone'] ; 
        $receiverphone = ltrim($receiverphone, '966 ');
        $receiverphone = ltrim($receiverphone, '0');
        $receiverphone = '966' . $receiverphone;
       

        $comp_api_url = $counrierArr['api_url'].'consignments';
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if ($ShipArr['mode'] == 'COD') {
            $billing_type = 'COD';
            $service = 1;
            $paymentType = 1;
        } else {
            $billing_type = 'PREPAID';
            $cod_amount = 0;
            $service = '';
            $paymentType = 0;
        }
              
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }
       

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }


        $pieces_array =array();
        for($i=1;$i<=$box_pieces;$i++){
            $pieces_array[] = array(
                'pieceNo' =>$i,
                'weight' =>$weight,
                'width'=>1,
                'height'=>1,
                'depth'=>1,
                'volumetricWeight'=>$weight,
               );
           }
       
        $request_data = array(
            'paymentType' => $paymentType,
            'type' => 'delivery',
            'primaryService' => 0,
            //'services' => array($service),
            'consignorName'=>$sellername,
            'consignorEmail'=>$senderemail,
            'consignorLocation'=>$sender_city_code,
            'consignorPhone'=>$senderphone,
            'consignorAlternatePhone'=>'',
            'consignorArea'=>$sender_city,
            'consignorStreetName'=>$store_address,
            'consignorBuildingName'=>'',
            'consignorBuildingNo'=>'',
            'consignorLandmark'=>'',
            'consigneeName'=> $ShipArr['reciever_name'],
            'consigneeEmail'=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            'consigneeLocation'=>$receiver_city_code,
            'consigneeArea'=> $ShipArr['reciever_address'],
            
            'consigneeStreetName'=>'',
            'consigneeBuildingName'=>'',
            'consigneeBuildingNo'=>'',
            'consigneeLandmark'=>'',
            'consigneePhone'=>$receiverphone,
            'consigneeAlternatePhone'=>'',
            'shipmentReferenceNo'=>$ShipArr['slip_no'],
            'orderDate'=>date('Y-m-d'),
            'collectAmount'=>$cod_amount,
            'commodityDescription'=>$complete_sku,
            'shipmentValue'=>$cod_amount,
            'consignmentPieces'=>$pieces_array
            // array(array(
            //     'pieceNo' =>$box_pieces,
            //     'weight' =>$weight,
            //     'width'=>1,
            //     'height'=>1,
            //     'depth'=>1,
            //     'volumetricWeight'=>$weight,
            //  )
            // )
          
        );
        
        if ($ShipArr['mode'] == 'COD') {
            $service = 8; // cod 
            $request_data['services'][]= $service;
        }
         $dataJson = json_encode($request_data);
                       //echo $dataJson; die; 
        $headers = array(
            "Content-type:application/json",
            "Authorization: Bearer ".$auth_token);

        if(empty($receiver_city_code) || empty($receiver_city))  {
            $response = array('message'=>'Receiver City OR Reciever code Empty');
             $response =   json_encode($response);  
          
        }else {
                $ch = curl_init($comp_api_url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $response = curl_exec($ch);
                curl_close($ch);
        }

        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['success'];

        if($successres === true) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }
        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'], $dataJson);
        return $response_array;
        
        
    }
    
    
    public function getLable($auth_token = null, $client_awb = null ,$method = null,$label_api_url = null){
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $label_api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $method,
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$auth_token
          ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response, true);
        if($response['success'] === true){
            return $response['data'];
        }else{
            return '';
        }
        
    }

    public function AjeekArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku, $box_pieces1, $c_id,$super_id) {
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'ajeek_city',$super_id);
        $latitude = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $Longitude = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $api_key = $counrierArr['auth_token'];
        $vendor_id = $counrierArr['courier_pin_no'];
        $user_id = $counrierArr['courier_account_no'];
        
        $branch_id = $counrierArr['password'];
        $comp_api_url = $counrierArr['api_url'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if ($ShipArr['pay_mode'] == 'COD') {
            $billing_type = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } else {
            $billing_type = 2;
            $cod_amount = 0;
        }

               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
              $weight = 1;
         }else {
              $weight = $ShipArr['weight'];
         }
      
        $items_detail = array(
                array(
                    "description" => $complete_sku,
                    "length" => '',
                    "width" => '',
                    "height" => ''
                )
        );
        $number = $ShipArr['reciever_phone'];
        $number = ltrim($number, '966');
        $number = ltrim($number, '0');
        $number = '00966' . $number;
        $number = str_replace(' ', '', $number);


        
        $request_data = array(
                "user_id" => $user_id,
                "cust_first_name" => $ShipArr['reciever_name'],
                "cust_last_name" => " ",
                "cust_mobil" =>(string)$number,
                "vendor_id" => $vendor_id,
                "branch_id" => $branch_id,
                "payment_type_id" => 1,
                "cords" => $Longitude.','.$latitude,
                "address" => 'KSA '.$receiver_city,
                "bill_amount" => $cod_amount,
                "preorder" => "false",
                "bill_reference_no " => $ShipArr['slip_no'],
                "pieces" => $box_pieces,
                "total_weight" => $weight,
                "order_items_detail" => $items_detail,
                "api_key" => $api_key,
        );
      
        $dataJson = json_encode($request_data);
       // echo $dataJson;die;
        
        $headers = array (
            "Content-Type: application/json"
        );
        $ch = curl_init($comp_api_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['description'];
      
        if($successres =="Done") 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse, $successstatus, $ShipArr['slip_no'],$dataJson);


        return $response_array;
    }

    public function shipmentLog($c_id = null,$description= null,$status= null,$slip_no= null,$request=null){
        $CURRENT_DATE = date("Y-m-d H:i:s");

        $logarr  = array(
            'slip_no' => $slip_no, 
            'cc_id' => $c_id, 
            'log' => $description, 
            'status' =>$status,
            'request'=>$request, 
            'super_id' => $this->session->userdata('user_details')['super_id'], 
            'entry_date' =>$CURRENT_DATE, 
        );       

        $retr = $this->GetlogInsert($logarr); 

    }
    public function GetlogInsert($data = array()) {

            $this->db->insert('frwd_shipment_log', $data);
           // echo $this->db->last_query(); die;
        }

    public function forwardshfilter($awb, $warehouse, $origin, $destination, $forwarded_type, $mode, $sku, $booking_id, $page_no,$seller_id=null,$typeship=null) {
        $page_no;
        $limit = ROWLIMIT;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }

        /* if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered', $delivered);
          } */

        $fulfillment = 'Y';
        $deleted = 'N';
        $e_city = Getsite_configData();
        $e_City = explode(",", $e_city['e_city']);


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            //$this->db->where('shipment_fm.wh_id',$this->session->userdata('user_details')['wh_id']);
        }
        //$this->db->where_in('shipment_fm.destination', $e_City);
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->where_not_in('shipment_fm.code', 'RTC', 'DL', 'POD', 'C');
        $this->db->where('shipment_fm.forwarded', $forwarded_type);
        $this->db->select('shipment_fm.id,shipment_fm.sku,shipment_fm.mode, shipment_fm.booking_id, shipment_fm.slip_no, shipment_fm.origin, shipment_fm.destination, shipment_fm.wh_id, shipment_fm.entrydate, shipment_fm.frwd_company_awb, shipment_fm.frwd_company_label, shipment_fm.frwd_company_id, customer.company, customer.seller_id, customer.uniqueid,shipment_fm.suggest_company,shipment_fm.typeship');
        $this->db->from('shipment_fm');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        if ($forwarded_type == 1) {
            $this->db->join('courier_company', 'courier_company.id=shipment_fm.frwd_company_id');
        }



        // echo $delivered;

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }
        if (!empty($warehouse)) {
            $warehouse = array_filter($warehouse);

            $this->db->where_in('shipment_fm.wh_id', $warehouse);
        }
        

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }
        if (!empty($typeship)) {
            $this->db->where('shipment_fm.typeship', $typeship);
        }
        if (!empty($booking_id)) {
            $this->db->where_in('booking_id', explode(' ', $booking_id));
        }

        if (!empty($mode)) {
            $this->db->where('shipment_fm.mode', $mode);
        }
        if (!empty($sku)) {
            $this->db->where('shipment_fm.sku', $sku);
        }

        if (!empty($origin)) {

            $this->db->where('diamention_fm.origin', $origin);
        }
        if (!empty($seller_id)) {
            if (sizeof($seller_id) > 0) {
                $seller = array_filter($seller_id);
                $this->db->where_in('shipment_fm.cust_id', $seller_id);
            }
        }



        $this->db->order_by('id', 'desc');

        $tempdb = clone $this->db;
        //now we run the count method on this copy
        // $num_rows = $tempdb->from('shipment_fm')->count_all_results();

        $this->db->limit($limit, $start);

        $query = $this->db->get();

        //  echo $this->db->last_query();    

        if ($query->num_rows() > 0) {


            //$data['excelresult']=$this->filterexcel($awb,$sku,$delivered,$seller,$to,$from,$exact,$page_no,$destination,$booking_id); 
            $data['result'] = $query->result_array();
            $data['count'] = $this->shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id, $forwarded_type);
            return $data;
            // return $page_no.$this->db->last_query();
        } else {
            $data['result'] = '';
            $data['count'] = 0;
            return $data;
        }
    }

    public function shipmCount($awb, $sku, $delivered, $seller, $to, $from, $exact, $page_no, $destination, $booking_id, $cc_id = null, $forwarded_type) {


        if ($this->session->userdata('user_details')['user_type'] != 1) {
            $this->db->where('shipment_fm.wh_id', $this->session->userdata('user_details')['wh_id']);
        }

        if (!empty($cc_id)) {
            $cc_id = array_filter($cc_id);

            $this->db->where_in('shipment_fm.frwd_company_id', $cc_id);
        }
        $this->db->where('shipment_fm.super_id', $this->session->userdata('user_details')['super_id']);
        $fulfillment = 'Y';
        $deleted = 'N';
        $this->db->where('shipment_fm.forwarded', $forwarded_type);
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
        $this->db->where('shipment_fm.deleted', $deleted);
        $this->db->select('COUNT(shipment_fm.id) as sh_count');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm', 'status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer', 'customer.id=shipment_fm.cust_id');
        if ($status_dashbord == 1) {
            $this->db->join('status_fm', 'status_fm.slip_no=shipment_fm.slip_no');
        }
        if ($forwarded_type == 1) {
            $this->db->join('courier_company', 'courier_company.id=shipment_fm.frwd_company_id');
        }


        if (!empty($exact)) {
            if ($status_dashbord == 1) {
                $this->db->where('DATE(status_fm.entry_date)', $exact);
            } else {
                $this->db->where('DATE(shipment_fm.entrydate)', $exact);
            }
        }


        if ($backorder == 'back')
            $this->db->where('shipment_fm.backorder', 1);
        else {


            $this->db->where('shipment_fm.backorder', 0);
            // $this->db->where('shipment.reverse_pickup', 0);
        }

        if (!empty($from) && !empty($to)) {
            $where = "DATE(shipment_fm.entrydate) BETWEEN '" . $from . "' AND '" . $to . "'";


            $this->db->where($where);
        }



        if (!empty($delivered)) {
            if ($status_dashbord == 1) {

                $this->db->where_in('status_fm.new_status', $delivered);
            }
            if ($status_dashbord != 1) {
                // print_r($delivered);
                if ($delivered == '1' || $delivered == '4' || $delivered == '5' || $delivered == '2') {
                    if (array_key_exists(0, $delivered))
                        $delivered = array_filter(0, $delivered);
                } else {
                    if ($delivered[0] == '')
                        $delivered = "";
                    $delivered = array_filter($delivered);
                }

                $this->db->where_in('shipment_fm.delivered', $delivered);
            }
        }

        if (!empty($destination)) {
            $destination = array_filter($destination);

            $this->db->where_in('shipment_fm.destination', $destination);
        }

        if (!empty($awb)) {
            $this->db->where('shipment_fm.slip_no', $awb);
        }

        /* if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);
          } */

        if (!empty($seller)) {
            $seller = array_filter($seller);
            $this->db->where_in('shipment_fm.cust_id', $seller);
        }

        if (!empty($booking_id)) {

            $this->db->where('shipment_fm.booking_id', $booking_id);
        }


        $this->db->order_by('shipment_fm.id', 'desc');

        $query = $this->db->get();

        // echo $this->db->last_query(); 
        if ($query->num_rows() > 0) {

            $data = $query->result_array();
            return $data[0]['sh_count'];
            // return $page_no.$this->db->last_query();
        }
        return 0;
    }

    public function BarqfleethArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $pay_mode = null, $CashOnDeliveryAmount = null, $services = null, $c_id = null,$box_pieces1=null,$super_id) {
       
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city',$super_id);
        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $declared_charge = $ShipArr['total_cod_amt'];

        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }

        $payment_type = '';

        if ($ShipArr['pay_mode'] === 'COD') {
            $cod_collection_mode = 'COD';

            $payment_type =  1;
        } else {
            $cod_collection_mode = 'PREPAID';
            $payment_type = 0;
        }
        
        $comp_api_url = $counrierArr['api_url'];

        $pickupDate = date("Y-m-d");
        $deliveryDate = date('Y-m-d', strtotime($pickupDate . '+ 2 days'));

        $params = array(
            "email" => $counrierArr['user_name'],
            "password" => $counrierArr['password']
        );

        $data = json_encode($params);
        //$request_url = $counrierArr['api_url'];
        $request_url = $counrierArr['api_url']."/login";
        $firstheader = array(
            "Authorization: " . $counrierArr['auth_token'],
            "Content-Type: application/json",
            "Accept: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $firstheader);
        $response = curl_exec($ch);
        curl_close($ch);

        $response2 = json_decode($response, true);
        $Authorization = $response2['token'];

        $params = array(
            "invoice_total" => $ShipArr['total_cod_amt'],
            "payment_type" => $payment_type,
            "shipment_type" => "instant_delivery",
            "hub_id" => $counrierArr['service_code'],
            "hub_code" => $counrierArr['account_entity_code'],
            "merchant_order_id" => $ShipArr['slip_no'],
            "customer_details" => array(
                "first_name" =>$ShipArr['reciever_name'],
                "last_name" => "",
                "country" => "Saudi Arabia",
                "city" => $receiver_city,
                "mobile" => $ShipArr['reciever_phone'],
                "address" => $ShipArr['reciever_address']
            ),
            "products" => array(
                array(
                    "serial_no" => $sku_name,
                    "qty" =>  $box_pieces,
                    "sku" => '',
                    "color" => '',
                    "brand" => '',
                    "name" => '',
                    "price" => ''
                )
            ),
            "destination" => array(
                "latitude" => '',
                "longitude" => ''
            )
        );

        $dataJson = json_encode($params);
        // echo $dataJson;die;
        //   echo "<pre>"; print_r($params); 
        // die; 

        $headers = array("Content-type:application/json");
        $url = $counrierArr['api_url']."/orders";
        $firstheaderr = array(
            "Authorization: " . $Authorization,
            "Content-Type: application/json",
            "Accept: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $firstheaderr);
        $response_ww = curl_exec($ch);
       curl_close($ch);
        $logresponse =   json_encode($response_ww);  
        
        $response_array = json_decode($response_ww, TRUE); 
        $successres = $response_array['code'];
    
        if($successres != '') 
        {
            $successstatus  = "Fail";
        }else {
            $successstatus  = "Success";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        return $response_ww;
    }
    public function ZajilArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1,$super_id) {
        
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $sender_default_city['0']['address'];
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
        
        //$sender_address = $ShipArr['sender_address'];

        

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }



        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);

        
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'zajil',$super_id);
        
        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
       
        if ($ShipArr['pay_mode'] === 'COD') {
            $cod_collection_mode = 'CASH';
            
        } else {
            $cod_collection_mode = '';
            $cod_amount = 0;
        }
        $comp_api_url = $counrierArr['api_url'];
        $data_request = array(
            'consignments' => array(
                array(
                    'customer_code' => $counrierArr['user_name'],
                    'reference_number' => '',
                    'load_type' => 'NON-DOCUMENT',
                    'description' => $complete_sku,
                    'service_type_id' => 'B2C',
                    'cod_favor_of' => '',
                    'dimension_unit' => 'cm',
                    'length' => '',
                    'width' => '',
                    'height' => '',
                    'weight_unit' => 'kg',
                    'weight' => $weight,
                    'declared_value' => $declared_charge,
                    'declared_price' => '',
                    'cod_amount' => $cod_amount,
                    'cod_collection_mode' => $cod_collection_mode,
                    'prepaid_amount' => '',
                    'num_pieces' => $box_pieces,
                    'customer_reference_number' => $ShipArr['slip_no'],
                    'is_risk_surcharge_applicable' => true,
                    'origin_details' =>
                            array(
                                'name' => $sellername,
                                'phone' => $sender_phone,//$ShipArr['sender_phone'],
                                'alternate_phone' => '',
                                'address_line_1' => $sender_address,
                                'address_line_2' => '',
                                'city' => $sender_city,
                                'state' => ''
                            ),
                    'destination_details' =>
                            array(
                                'name' => $ShipArr['reciever_name'],
                                'phone' => $ShipArr['reciever_phone'],
                                'alternate_phone' => '',
                                'address_line_1' => $ShipArr['reciever_address'],
                                'address_line_2' => '',
                                'city' => $receiver_city,
                                'state' => ''
                            ),
                    'pieces_detail' =>
                            array(
                                'description' => $description,
                                'declared_value' => $declared_charge,
                                'weight' => $weight,
                                'height' => '',
                                'length' => '',
                                'width' => ''
                            )
         )));
        $comp_auth_token = $counrierArr['auth_token'];
        $headers = array(
            "Content-type:application/json",
            "api-key:$comp_auth_token");

        $dataJson = json_encode($data_request);
        $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $comp_api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
            $response = curl_exec($ch);
            curl_close($ch);

        $response_array = json_decode($response, true);
        $logresponse =   json_encode($response_array);  
        $successres = $response_array['data'][0]['success'];        
        if($response_array['status'] == 'OK' && $successres == true) 
        {
            $successstatus  = "Success";
        } else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        return $response_array;
    }

   public function MakdoonArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1=null,$super_id) {
       
        $sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'makhdoom',$super_id);

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        
        $API_URL = $counrierArr['api_url'];
        
        $create_order_url = $counrierArr['create_order_url'];
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
       

        $sender_data = array(
            "address_type" => "residential",
            "name" => $sellername,
            "email" => $sender_email,//$ShipArr['sender_email'],
            'apartment' => '',
            'building' => '',
            "street" =>$sender_address,
            "city" => array(
                'code' => $sender_city
            ),
            'country' => array(
                'id' => 191,
            ),
            "phone" => $sender_phone
        );
        $recipient_data = array(
            "address_type" => "residential",
            "name" => $ShipArr['reciever_name'],
            "email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "street" => $ShipArr['reciever_address'],
            "city" => array(
                'code' => $receiver_city
            ),
            'country' => array(
                'id' => 191,
            ),
            "phone" => $ShipArr['reciever_phone'],
            'landmark' => '',
        );
        $dimensions = array(
            "weight" => $weight,
            'width' => 0,
            'length' => 0,
            'height' => 0,
            'unit' => '',
            'domestic' => true,
        );
        $package_type = array(
            "courier_type" => 'EXPRESS_DELIVERY'
        );
        $charge_items = array(
            array(
                "paid" => false,
                "charge" => $ShipArr['total_cod_amt'],
                "charge_type" => $ShipArr['mode'],
                'payer' => 'sender',
            ),
            array(
                "paid" => false,
                "charge" => 0,
                "charge_type" => 'service_custom'
            )
        );

        $param = array(
            "sender_data" => $sender_data,
            "recipient_data" => $recipient_data,
            "dimensions" => $dimensions,
            "package_type" => $package_type,
            "charge_items" => $charge_items,
            "recipient_not_available" => "do_not_deliver",
            "payment_type" => "cash",
            "payer" => "recipient",
            //"parcel_value" => 100,
            "fragile" => true,
            "note" => $complete_sku,
            "piece_count" => $box_pieces,
            "force_create" => true,
            "reference_id" => $ShipArr['slip_no']
        );
       
        $dataJson = json_encode($param);
        //echo $dataJson;die;
        $response = send_data_to_makdoom_curl($dataJson, $Auth_token, $create_order_url);
        
        $logresponse =   json_encode($response);  

        $responseData = json_decode($response, TRUE);
        //print "<pre>"; print_r($responseData);die;

        $successres = $responseData['status'];
        
        if($successres == 'success') 
        {
            $successstatus  = "success";
        } else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        return $response;
    }

    public function SaeeArray($sellername=null ,array $ShipArr, array $counrierArr, $Auth_token = null,$c_id,$box_pieces1,$super_id) {
        
//        $sender_default_city= Getselletdetails_new($super_id);
//        $sender_address = $sender_default_city['0']['address'];
//        $sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sender_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sender_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }
        if(!empty($ShipArr['label_sender_name'])){
            $sender_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sender_name = $sender_name ." - ". site_configTable('company_name'); 
            }
        }        

        
        //$sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        //$sender_name = $ShipArr['sender_name'];
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'saee_city',$super_id);
        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $API_URL = $counrierArr['api_url'];
        $Secretkey = $counrierArr['auth_token'];
         $ShipArr['cust_id'];
         $store = getallsellerdatabyID($ShipArr['cust_id'], 'company');   

        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        
        if ($ShipArr['pay_mode'] == 'COD') {
            $BookingMode = 'COD';
            $codValue = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['pay_mode'] == 'CC') {
            $BookingMode = 'CC';
            $codValue = 0;
        }

 
        $param = array(
            "secret" => $Secretkey,
            "ordernumber" => $ShipArr['slip_no'],
            "cashondelivery" => $codValue,
            "name" => $ShipArr['reciever_name'],
            "mobile" => $ShipArr['reciever_phone'],
            "mobile2" => '',
            "streetaddress" => $ShipArr['reciever_address'],
            "streetaddress2" => '',
            "district" => '',
            "city" => $receiver_city,
            "state" => '',
            "zipcode" => $ShipArr['reciever_zip'],
            "custom_value" => '',
            "hs_code" => 'FASTCOO',
            "category_id" => '',
            "weight" => $weight,
            "quantity" => $box_pieces,
            "description" => "",
            "email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "pickup_address_id" => '',
            "Pickup_address_code" => '',
            "sendername" =>$sender_name,
            "sendermail" => $sender_email,//$ShipArr['sender_email'],
            "senderphone" => $sender_phone,//$ShipArr['sender_phone'],
            "senderaddress" =>$sender_address,
            "sendercity" => $sender_city,
            'sendercountry' => '',
            "sender_hub" => '',
            "latitude" => $lat,
            "longitude" => $lang,
        );
        $all_param_data = json_encode($param);
        //echo $all_param_data;die;
        $live_url = $API_URL."/new?secret=$Secretkey";
              //  $live_url = $API_URL."/new";    
        $headers = array("Content-type:application/json");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $live_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        $response = curl_exec($ch); 
        curl_close($ch);      

        $response = json_decode($response, true);
        $logresponse =   json_encode($response);  
        $successres = $response['success']; 

        if($successres == 'true' || $successres == true) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$all_param_data);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$all_param_data);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$all_param_data);
        return $response;
    }
    public function AymakanArray($sellername = null ,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null,$box_pieces1,$complete_sku=null,$super_id) {

        //print "<pre>"; print_r($ShipArr);die;
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
       // $sender_address = $sender_default_city['0']['address'];
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
        $sender_address = $ShipArr['sender_address'];
        $s_zip = $ShipArr['sender_zip'];
        $store = getallsellerdatabyID($ShipArr['cust_id'], 'company',$super_id);



        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            $s_zip = GetallCutomerBysellerId($ShipArr['cust_id'],'pincode');

        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
            $s_zip = '';
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'aymakan',$super_id);

        
        
        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));

           $API_URL = $counrierArr['api_url']."create";
        //echo $API_URL;die;
          $api_key = $counrierArr['auth_token'];

        $currency = site_configTable("default_currency");//"SAR";  

         if(empty($box_pieces1))
         {
            $box_pieces = 1;
         }
         else
         { 
            $box_pieces = $box_pieces1 ; 
         }
 
         if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }      

        if ($ShipArr['pay_mode'] == 'COD') {
            $price_set = 113;
            $is_cod = 1;
            $cod_amount = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['pay_mode'] == 'CC') {
            $is_cod = 0;
            $price_set = 364;
            $cod_amount = 0;
        }
        $all_param_data = array(
           "requested_by" => $ShipArr['sender_name'],
           "fulfilment_customer_name" => $store,
            "declared_value" => $ShipArr['total_cod_amt'],
            "declared_value_currency" => $currency,
            "price_set" => $price_set,
            "reference" => $ShipArr['slip_no'],
            "is_cod" => $is_cod,
            "cod_amount" => $cod_amount,
            "currency" => $currency,
            "delivery_name" => $ShipArr['reciever_name'],
            "delivery_email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "delivery_city" => $receiver_city,
            "delivery_address" => $ShipArr['reciever_address'],
            "delivery_country" => 'SA',
            "delivery_phone" => $ShipArr['reciever_phone'],
            "delivery_description" =>$complete_sku,
            "collection_name" => $sellername,
            "collection_address" => $sender_address,
            "collection_email" => empty(trim($sender_email))?'Support@diggipacks.com':$sender_email,
            "collection_city" => $sender_city,
            "collection_postcode" => $s_zip,
            "collection_country" => 'SA',
            "collection_phone" => $sender_phone,//$ShipArr['sender_phone'],
            "pickup_date" => $pickup_date,
            "weight" => $weight,
            "pieces" => $box_pieces
        );  

        $json_final_date = json_encode($all_param_data);  
     //  echo "<pre>"; print_r($all_param_data); die; 
     //   echo $json_final_date;die;

        $headers = array(
            "Accept:application/json",
            "Authorization: $api_key");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        $response = curl_exec($ch);
        //echo $response;die;
        //echo "error=".curl_error($ch);die;
        curl_close($ch);
        $responseArray = json_decode($response, true);
        //print "<pre>"; print_r($responseArray);die;
        $logresponse =   json_encode($response);  
        $successres = $responseArray['message'];
        $successreserror = $responseArray['error'];

        //if(empty($successres) && ($successreserror !=true))
        if ($responseArray['success']) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }
        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
        }
        return $response;
    }

    public function shipmentLogReverse($c_id = null,$description= null,$status= null,$frwd_slip_no=null, $slip_no= null, $request=null){

        $insertData  = array(
            'slip_no' => $slip_no,
            'frwd_slip_no' =>$frwd_slip_no,
            'cc_id' => $c_id, 
            'log' => $description, 
            'status' =>$status,
            'request'=>$request, 
            'super_id' => $this->session->userdata('user_details')['super_id'], 
            'entry_date' =>date("Y-m-d H:i:s"), 
        );       

        $this->db->insert('frwd_reverse_ship_log', $insertData);

    }
    
    public function Aymakan_tracking($client_awb= null, $tracking_url= null,$auth_token=null)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
              CURLOPT_URL => $tracking_url.$client_awb,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: '.$auth_token
              ),
            ));

            $response = curl_exec($curl);
             
            curl_close($curl);
            return $response;
    }


    function stripInvalidXml($value)
    {
        return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $value);
    }

    public function SMSAArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id) {
        
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $sender_default_city['0']['address'];
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
        
        $sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code',$super_id);
        
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'samsa_city',$super_id);
        $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code',$super_id);
        $currency_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency',$super_id);
       // $store = site_configTable('company_name'); //getallsellerdatabyID($ShipArr['cust_id'], 'company');
        //$seller__name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');

         

         if($super_id = 20)
        { 
            $sellername = $seller__name; 

        }else {
            $sellername = $sellername;
        }

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            $sender_zipcode = GetallCutomerBysellerId($ShipArr['cust_id'],'pincode');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store = $ShipArr['sender_name'];
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
            $sender_zipcode = '';
        }

        $number  = $sender_phone; 
        $number = ltrim($number, '966');
        $number = ltrim($number, '+966');
        $number = ltrim($number, '0');
        $number = '966' . $number;
        $sender_phone = str_replace(' ', '', $number); 

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }       

        $declared_charge = $ShipArr['total_cod_amt'];
        $cod_amount = $ShipArr['total_cod_amt'];

        if($sender_country_code=='SA' || $sender_country_code=='')
        {
            $countryCode=='KSA';
        }
        else
        {
        $countryCode= $sender_country_code;
        }

        if ($ShipArr['pay_mode'] == 'COD') {
            $codValue = $cod_amount;
        } else {
            $codValue = 0;
        }
        if ($complete_sku == '') {
            $complete_sku = 'Goods';
        }
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        {  $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        
        $comp_api_url = $counrierArr['api_url'];
        $receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';

        $rec_name = $this->stripInvalidXml($ShipArr['reciever_name']);
        $rec_addres = $this->stripInvalidXml($ShipArr['reciever_address']);
        $complete_sku = $this->stripInvalidXml($complete_sku);
      
        $number  = $ShipArr['reciever_phone']; 
        $number = ltrim($number, '966');
        $number = ltrim($number, '0');
        $number = '966' . $number;
        $reciever_phone = str_replace(' ', '', $number); 

        $SMSAXML = '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        
            <soap:Body>
                <addShipMPS xmlns="http://track.smsaexpress.com/secom/">
                  <passKey>' . $counrierArr['auth_token'] . '</passKey>
                  <refNo>' . $ShipArr['slip_no'] . '</refNo>
                  <sentDate>' . date('d/m/Y') . '</sentDate>
                  <idNo>' . $ShipArr['booking_id'] . '</idNo>
                  <cName>' . $rec_name . '</cName>
                  <cntry>'.$receiver_country_code.'</cntry>
                  <cCity>' . $receiver_city . '</cCity>
                  <cZip>' . $ShipArr['reciever_pincode'] . '</cZip>
                  <cPOBox>45</cPOBox>
                  <cMobile>' . $reciever_phone  . '</cMobile>
                  <cTel1>' . $reciever_phone . '</cTel1>
                  <cTel2>' . $reciever_phone . '</cTel2>
                  <cAddr1>' . $rec_addres . '</cAddr1>
                  <cAddr2></cAddr2>
                  <shipType>DLV</shipType>
                  <PCs>' . $box_pieces . '</PCs>
                  <cEmail>' . $receiver_email . '</cEmail>
                  <carrValue>2</carrValue>
                  <carrCurr>2</carrCurr>
                  <codAmt>' . $codValue . '</codAmt>
                  <weight>' . $weight. '</weight>
                  <custVal>2</custVal>
                  <custCurr>'.$currency_code.'</custCurr>
                  <insrAmt>34</insrAmt>
                  <insrCurr>3</insrCurr>
                  <itemDesc>' .  $complete_sku . '</itemDesc>
                  <sName>' .$sellername . '</sName>
                  <sContact>' .$sellername . '</sContact>
                  <sAddr1>' . $sender_address . '</sAddr1>
                  <sAddr2></sAddr2>
                  <sCity>' . $sender_city . '</sCity>
                  <sPhone>' . $sender_phone . '</sPhone>
                  <sCntry>'.$countryCode.'</sCntry>
                  <prefDelvDate>'.date('d/m/Y').'</prefDelvDate>
                  <gpsPoints>2</gpsPoints>
                </addShipMPS>
            </soap:Body>
        </soap:Envelope>';

        //   echo '<pre>';
        // header('Content-type: text/xml;charset=utf-8');
        //  echo  $SMSAXML; die;
        $headers = array(
            "Content-type: text/xml;charset=utf-8",
            "Accept: application/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://track.smsaexpress.com/secom/addShipMPS",
            "Content-length: " . strlen($SMSAXML),
        );
        $cookiePath = tempnam('/tmp', 'cookie');

        //echo $comp_api_url;die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $comp_api_url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $SMSAXML);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        //echo $response;die;
        curl_close($ch);
        $check = $response;
        $respon = trim($check);
        $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $response);
        $xml2 = new SimpleXMLElement($respon);
        $again = $xml2;
        $a = array("qwb" => $again);

        //$complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
        $complicated = ($a['qwb']->Body->addShipMPSResponse->addShipMPSResult[0]);
       // echo $complicated;die;
        if (preg_match('/\bFailed\b/', $complicated)) {
            $ret = $complicated;

        } 
            if (empty($ret)) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$SMSAXML);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$SMSAXML);
        }


       // $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$SMSAXML);


        return $respon;
    }
    

    public function PrintLabel($SMSAAWB, $Passkey, $url) {
        $xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
            <soap:Body>
                <getPDF xmlns="http://track.smsaexpress.com/secom/">
                    <awbNo>' . $SMSAAWB . '</awbNo>
                    <passKey>' . $Passkey . '</passKey>
                </getPDF>
            </soap:Body>
        </soap:Envelope>';
        $headers = array(
            "Content-type: text/xml;charset=utf-8",
            "Accept: application/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://track.smsaexpress.com/secom/getPDF",
            "Content-length: " . strlen($xml),
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = trim(curl_exec($ch));
        return $response;
    }
    public function GetdeliveryCompanyUpdateQryById($cc_id = null, $custid= null) {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('cc_id', $cc_id);
        $this->db->select('*');
        $this->db->from('courier_company_seller');
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where('cust_id', $custid);
        $this->db->order_by("company");
        $query = $this->db->get();
        //echo $this->db->last_query();exit;

        if ($query->num_rows()> 0)
        {
            return $query->row_array();
        }
        else 
        {
        
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('cc_id', $cc_id);
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
    public function GetallperformationDetailsQry($frwd_throw = null, $status = null, $from = null, $to = null) {
     if ($frwd_throw != 0)
            $condition .= " and shipment_fm.frwd_company_id='" . $frwd_throw . "'";
        //$objSmarty->assign("frwd_throw", $_REQUEST['frwd_throw']);

        $from_date = $from;
        $to_date = $to;
        if ($from_date != 0 && $to_date != 0) {
            $condition .= " and DATE(shipment_fm.entrydate) BETWEEN '" . $from_date . "' AND '" . $to_date . "'";
        }

        $delivered = $status;
        if ($delivered == 'running') {
            $condition .= " and  delivered in(1,2,3,4,5)";
        } else {
            $condition .= " and delivered='$delivered'";
        }

        $query = $this->db->query("SELECT courier_company.company,shipment_fm.* FROM shipment_fm join courier_company on shipment_fm.frwd_company_id= courier_company.id WHERE  shipment_fm.deleted='N' and shipment_fm.status='Y' and shipment_fm.super_id='".$this->session->userdata('user_details')['super_id']."'   $condition");
        // echo $this->db->last_query(); 
        return $query->result_array();
    }

    //Naqel Start

 public function NaqelArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $box_pieces1 = null, $Auth_token = null, $c_id = null,$super_id) 
 {  
           //$sender_address = $ShipArr['sender_address'];
//     print "<pre>"; print_r($ShipArr);die;
        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        if($ShipArr['cust_id'] == 305){
            $sellername = $ShipArr['shipment_seller_name']." - ".site_configTableSuper_id("company_name",$super_id);
        }

           $reciever_address = $ShipArr['reciever_address'];
           $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'naqel_city_code', $super_id);
           $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'naqel_city_code',$super_id);
           $destination_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city',$super_id);
           $receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
        
            if ($ShipArr['pay_mode'] == 'CC') {
                    $BillingType = 1;
                } elseif ($ShipArr['pay_mode'] == "COD") {
                    $BillingType = 5;
                }
            if(empty($box_pieces1))
                {
                    $box_pieces = 1;
                }
                else
                { 
                     $box_pieces = $box_pieces1 ; 
                }
        
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }
               
             $API_URL = $counrierArr['api_url'];    
             $user_name = $counrierArr['user_name'];    
             $password = $counrierArr['password'];
             $xml_new = '<?xml version="1.0" encoding="utf-8"?>
                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
                        <soapenv:Header/>
                        <soapenv:Body>
                            <tem:CreateWaybill>
                                <tem:_ManifestShipmentDetails>
                                    <tem:ClientInfo>
                                    <tem:ClientAddress>
                                        <tem:PhoneNumber>'.$sender_phone.'</tem:PhoneNumber>
                                        <tem:POBox></tem:POBox>
                                        <tem:ZipCode></tem:ZipCode>
                                        <tem:Fax></tem:Fax>
                                        <tem:FirstAddress>'.$sender_address.'</tem:FirstAddress>
                                        <tem:Location>' . $sender_city . '</tem:Location>
                                        <tem:CountryCode>KSA</tem:CountryCode>
                                        <tem:CityCode>' . $sender_city . '</tem:CityCode>
                                    </tem:ClientAddress>

                                    <tem:ClientContact>
                                        <tem:Name>' . $sellername . '</tem:Name>
                                        <tem:Email>' . $sender_email . '</tem:Email>
                                        <tem:PhoneNumber>'.$sender_phone . '</tem:PhoneNumber>
                                        <tem:MobileNo>' . $sender_phone . '</tem:MobileNo>
                                    </tem:ClientContact>

                                    <tem:ClientID>'.$user_name.'</tem:ClientID>
                                    <tem:Password>'.$password.'</tem:Password>
                                    <tem:Version>9.0</tem:Version>
                                    </tem:ClientInfo>

                                    <tem:ConsigneeInfo>
                                    <tem:ConsigneeName>' .$ShipArr['reciever_name'].'</tem:ConsigneeName>
                                    <tem:Email>' . $receiver_email . '</tem:Email>
                                    <tem:Mobile>' . $ShipArr['reciever_phone'] . '</tem:Mobile>
                                    <tem:PhoneNumber>' . $ShipArr['reciever_phone'] . '</tem:PhoneNumber>
                                    <tem:Address>' .$reciever_address.'</tem:Address>
                                    <tem:CountryCode>KSA</tem:CountryCode>
                                    <tem:CityCode>' . $receiver_city .'</tem:CityCode>
                                    </tem:ConsigneeInfo>

                                    <tem:BillingType>' . $BillingType . '</tem:BillingType>
                                    <tem:PicesCount>' . $box_pieces . '</tem:PicesCount>
                                    <tem:Weight>' . $weight. '</tem:Weight>
                                    <tem:DeliveryInstruction> </tem:DeliveryInstruction>
                                    <tem:CODCharge>' . $ShipArr['total_cod_amt'] . '</tem:CODCharge>
                                    <tem:CreateBooking>false</tem:CreateBooking>
                                    <tem:isRTO>false</tem:isRTO>
                                    <tem:GeneratePiecesBarCodes>false</tem:GeneratePiecesBarCodes>
                                    <tem:LoadTypeID>36</tem:LoadTypeID>
                                    <tem:DeclareValue>0</tem:DeclareValue>
                                    <tem:GoodDesc>' . htmlspecialchars($complete_sku, ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</tem:GoodDesc>
                                    <tem:RefNo>' .  $ShipArr['slip_no'] . '</tem:RefNo>
                                    <tem:InsuredValue>0</tem:InsuredValue>
                                    <tem:GoodsVATAmount>0</tem:GoodsVATAmount>
                                    <tem:Reference1>'.$ShipArr['booking_id'].'</tem:Reference1>
                                    <tem:IsCustomDutyPayByConsignee>false</tem:IsCustomDutyPayByConsignee>
                                </tem:_ManifestShipmentDetails>
                            </tem:CreateWaybill>
                        </soapenv:Body>
                        </soapenv:Envelope>';   
               
                $headers = array(
                    "Content-type: text/xml; charset=utf-8",
                    "Content-length: ".strlen($xml_new),
                );
            //     header('Content-type: text/xml');
            //    echo $xml_new; exit;
                $url = $API_URL;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_new);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               $response = curl_exec($ch); 
                $check = $response;
                $respon = trim($check);
                if(!empty($respon))
                {
                $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $respon);
                $xml2 = new SimpleXMLElement($respon);  
                $again = $xml2;
                $a = array("qwb" => $again);

                $complicated_awb = ($a['qwb']->Body->CreateWaybillResponse->CreateWaybillResult);
                }
                curl_close($ch);

                $awb_array = json_decode(json_encode((array) $complicated_awb), TRUE);
                $logresponse =   json_encode($awb_array);  
                $successres = $awb_array['HasError'];
                
                //if($successres!== true) 
                if($successres == "false" || $successres == false) 
                {
                    $successstatus  = "Success";
                } else {
                    $successstatus  = "Fail";
                }

                if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$xml_new);
                }else{
                    
                    $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$xml_new);
                }

                //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$xml_new);
                           

                return $awb_array;
            
            

    }
    public function ShipsyArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $box_pieces1 = null, $c_id = null,$super_id) {
        //print_r($ShipArr);exit;
        
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $sender_default_city['0']['address'];
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
        
        //$sender_address = $ShipArr['sender_address'];

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsy_city',$super_id);

        
            if ($ShipArr['pay_mode'] == 'COD') {
                    $total_cod_amt = $ShipArr['total_cod_amt'];
                } elseif ($ShipArr['pay_mode'] == "CC") {
                    $total_cod_amt = 0;
                }
				       
                if(empty($box_pieces1))
                {
                    $box_pieces = 1;
                }
                else
                { 
                     $box_pieces = $box_pieces1 ; 
                }
        
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }
               
                $consignments[] = Array
                                (
                                     "customer_code" => "FASTCOO",
                                            "reference_number" => '',
                                            "service_type_id" => "PREMIUM",
                                            "load_type" => "NON-DOCUMENT",
                                            "description" => "",
                                            "inco_terms" => "",
                                            "shipment_purpose" => "",
                                            "product_code" => "",
                                            "cod_favor_of" => "",
                                            "cod_collection_mode" => "",
                                            "dimension_unit" => "",
                                            "length" => "",
                                            "width" => "",
                                            "height" => "",
                                            "weight_unit" => "kg",
                                            "weight" => $weight,
                                            "declared_value" =>"", 
                                            "cod_amount" => $total_cod_amt,
                                            "num_pieces" => $box_pieces,
                                            "customer_reference_number" => $ShipArr['slip_no'],
                                            "is_risk_surcharge_applicable" => 1,
                                            "origin_details" => Array
                                                (
                                                    "name" => $sellername,
                                                    "phone" => $sender_phone,//$ShipArr['sender_phone'],
                                                    "alternate_phone" => '',
                                                    "address_line_1" =>$sender_address,
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" => $sender_city,
                                                    "state" => '',
                                                    "email" => $sender_email,//$ShipArr['sender_email'],
													
                                                ),

                                            "destination_details" => Array
                                                (
                                                    "name" => $ShipArr['reciever_name'],
                                                    "phone" => $ShipArr['reciever_phone'],
                                                    "alternate_phone" => "",
                                                    "address_line_1" => $ShipArr['reciever_address'],
                                                    "address_line_2" => "",
                                                    "pincode" => '',
                                                    "city" =>$receiver_city,
                                                    "state" => '',
                                                    "email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                                                ),

                                            "pieces_detail" => Array
                                                (
                                                    [0] => Array
                                                        (
                                                            "description" => $complete_sku,
                                                            "declared_value" => $total_cod_amt,
                                                            "weight" => $weight,
                                                            "height" => '',
                                                            "length" =>'',
                                                            "width" => ''
                                                        )

                                                )

                                       
                                );
        $all_param_array = Array(
                            "is_international" => '',
                            "consignments" => $consignments

                        );
        $param = json_encode($all_param_array);
       // echo $param;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'api-key:'.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        
        $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        $successres = $response_array['data'][0]['success'];
        //echo "<pre>"; print_r($logresponse)   ;    die;
        if($successres==1) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }


        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$param);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$param);
        }
        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$param);

        curl_close($curl);
        return $response;
        //exit;
    }
    
    public function ShipsyLabelcURL(array $counrierArr, $client_awb = null) {
        $url = str_replace('softdata', 'shippinglabel/link?reference_number=', $counrierArr['api_url']);
        $url = $url.$client_awb;
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
            'api-key:'.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $response = json_decode($response, true);
        
        $labelURL = $response['data']['url'];
        $labelURL = str_replace('isSmall=false', 'isSmall=true', $labelURL);
        
        return $labelURL;
        
    }
    
    public function ShipadeliveryArray($sellername = null ,array $ShipArr, array $counrierArr, $auth_token = null, $c_id = null,$super_id=null) {

        if(  empty($ShipArr['reciever_email']))
        $ShipArr['reciever_email']='no@no.com';
        
        
         //$sender_default_city = Getselletdetails_new($super_id);         
        //  $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');  
         //$sender_address = $sender_default_city['0']['address'];
         //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'shipsa_city', $super_id);
        //$sender_address = $ShipArr['sender_address'];

       

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];

        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        
        
        
         $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'shipsa_city',$super_id);

            if ($ShipArr['pay_mode'] == 'COD') {
                $total_cod_amt = $ShipArr['total_cod_amt'];
                $paymentMethod = 'CashOnDelivery';
            }elseif ($ShipArr['pay_mode'] == "CC") {
                $total_cod_amt = 0;
                $paymentMethod = 'Prepaid';
            }
            $description =  $complete_sku;

            if($description==''){
                $description = 'GOODS';
            }
                
            
        
        $number  =  $ShipArr['reciever_phone']; 
        $number = ltrim($number, '966 ');
        $number = ltrim($number, '0');
        $number = '0' . $number;
        $number = str_replace(' ', '', $number);
        
        $Sender = array(
            'name' =>  $sellername,
            'address' => $sender_address,
            'phone' => $sender_phone,//$ShipArr['sender_phone'],
            'email' => $sender_email,//$ShipArr['sender_email'],
        );
        
        $Recipient = array(
            'name' => $ShipArr['reciever_name'],
            'address' => $ShipArr['reciever_address'],
            'phone' => $number,
            'email' => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            'city' => $receiver_city,
        );
        $param[] = array(
            'id' => $ShipArr['slip_no'],
            'amount' => (float)$total_cod_amt,
            'paymentMethod' => $paymentMethod,
            'orderCategory' => 'NEXTDAY',
            'description' => $description,
            'typeDelivery' => 'forward',
            'sender' => $Sender,
            'recipient' => $Recipient
        );
        
        
    // echo "<pre>"; print_r($param); die;
     
        $paramArray = json_encode($param);  
        
       //echo "<pre>"; print_r($paramArray); die;
       
        if (empty($param[0]['recipient']['city']))
        {
            
            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $response = $this->shipmentLogReverse($c_id, 'receiver city empty ','Fail', $ShipArr['slip_no'],$ShipArr['old_slip_no'],$paramArray);
            }else{
                
                $response = $this->shipmentLog($c_id, 'receiver city empty ','Fail', $ShipArr['slip_no'],$paramArray);
            }
            
            //$response = $this->shipmentLog($c_id,'receiver city empty ','Fail', $ShipArr['slip_no'],$paramArray);
            return $response;
        }
        else {

            // echo $param[0]['recipient']['city'];   
            // echo "auth = ".$counrierArr['api_url'];//  die; 
              $curl = curl_init();        
                  curl_setopt_array($curl, array(
                  CURLOPT_URL => $counrierArr['api_url']."?apikey=".$counrierArr['auth_token'],
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$paramArray,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'x-api-key:'.$counrierArr['auth_token'],
                    'Accept: application/json'
                  ),
                ));

                $response = curl_exec($curl); 
            
                curl_close($curl);
                $logresponse =   json_encode($response);                   
                $response_array = json_decode($response, true);
                $successres = $response_array[0]['code'];
                if($successres==0) 
                {
                    $successstatus  = "Success";
                }
                else 
                {
                    $successstatus  = "Fail";
                }

                if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$paramArray);
                }else{
                    
                    $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$paramArray);
                }

                //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$paramArray);
                return $response;
        }


        
      
    }
    
    public function ShipaDelupdatecURL($sellername=null, array $counrierArr,array $ShipArr,$client_awb = null,$box_pieces1=null) {
           
        //print_r( $counrierArr); 
       // echo $counrierArr['api_url'].'/'.$client_awb."?apikey=".$counrierArr['auth_token']; exit;
        
        if ($ShipArr['pay_mode'] == 'COD') {
                        $total_cod_amt = $ShipArr['total_cod_amt'];
                        $paymentMethod = 'CashOnDelivery';
                    }
                    elseif ($ShipArr['pay_mode'] == "CC") {
                        $total_cod_amt = 0;
                        $paymentMethod = 'Prepaid';
                    }
                         
                    if(empty($box_pieces1))
                    {
                        $box_pieces = 1;
                    }
                    else
                    { 
                        $box_pieces = $box_pieces1 ; 
                    }

                    if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                        $weight = 1;
                   }else {
                        $weight = $ShipArr['weight'];
                   }
                
                    $valpiecesarray =  array (
                          'ready' => true,
                          'weight' => (float)$weight,
                          'quantity' => $box_pieces                     
                        );

                    $valpieces =  json_encode($valpiecesarray);                       
                    $curl = curl_init();
                    $client_awb = trim($client_awb); 
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $counrierArr['api_url'].'/'.$client_awb."?apikey=".$counrierArr['auth_token'],
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'PATCH',
                          CURLOPT_POSTFIELDS => $valpieces,
                          CURLOPT_HTTPHEADER => array(
                            'Accept: application/json',
                            'Content-Type: application/json',
                            'x-api-key:'. $counrierArr['auth_token']
                          ),
                        ));
                         $responsepieces = curl_exec($curl);

                            curl_close($curl);
                       return  $responsepieces; 

    }
    public function ShipaDelLabelcURL(array $counrierArr, $client_awb = null) 
    {

        $cURL12 = $counrierArr['api_url'].'/'.$client_awb."/pdf?apikey=".$counrierArr['auth_token']."&template=sticker-6x4&copies=1";
      
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $cURL12,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
                            
    public function SPArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null,$Auth_token=null, $c_id = null,$box_pieces1=null,$super_id){
        
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $sender_default_city['0']['address'];
        //$sender_city = getdestinationfieldshow_auto_array($sender_default_city['0']['branch_location'], 'city', $super_id);
        
       // $sender_address = $ShipArr['sender_address'];

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){

            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);

        
         $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'saudipost_id',$super_id);

        $username = $counrierArr['user_name'];
        $password = $counrierArr['password'];
        $authdata = 'grant_type=password&UserName='.$username.'&password='.$password;

       // print_r( $ShipArr['sku_data']); exit;
        $api_url = $counrierArr['api_url'];
        
        $curl = curl_init();
       
        curl_setopt_array($curl, array(
          CURLOPT_URL =>  $api_url.'token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $authdata,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $tokenArray = json_decode($response, true);
        $token=$tokenArray['access_token'];
        
               
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
       
        
        if ($ShipArr['pay_mode'] == "COD") {
            $PaymentType = 2;
            $total_cod_amt = $ShipArr['total_cod_amt'];
        }else{
            $PaymentType = 1;
            $total_cod_amt = 0;
        }

    
            $itemArray=array();
            for($ii=1;$ii<$box_pieces;$ii++)
            {
            $peiceArray=array(

                "PieceWeight"=> (float)$weight,
                "PiecePrice"=> 0,
                "PieceDescription"=> $complete_sku

            );
            array_push($itemArray,$peiceArray);

            }



        $param = array(
            "CRMAccountId" => $counrierArr['courier_account_no'],
            "BranchId"=> 0,
            "PickupType"=> 1,
            "RequestTypeId"=> 1,
            "CustomerName"=> $ShipArr['reciever_name'],
            "CustomerMobileNumber"=> $ShipArr['reciever_phone'],
            "SenderName"=>$sellername,
            "SenderMobileNumber"=> $sender_phone,//$ShipArr['sender_phone'],
            "Items"=> array(array(
                "ReferenceId"=> $ShipArr['slip_no'],
                "Barcode"=> null,
                "PaymentType"=> $PaymentType,
                "ContentPrice"=> 0,
                "ContentDescription"=> $complete_sku,
                "Weight"=> $weight,
                "BoxLength"=> '',
                "BoxWidth"=> '',
                "BoxHeight"=> '',
                "ContentPriceVAT"=> 0,
                "DeliveryCost"=> 0,
                "DeliveryCostVAT"=> 0,
                "TotalAmount"=> $total_cod_amt,
                "CustomerVAT"=> 0,
                "SaudiPostVAT"=> 0,
                "PiecesCount"=> $box_pieces,
                "ItemPieces"=>$itemArray,
            
                "SenderAddressDetail"=> array(
                    "AddressTypeID"=> "6",
                    "AddressLine1"=> $sender_address,
                    "AddressLine2"=> "SP",
                    "LocationID"=> $sender_city
                ),
                "ReceiverAddressDetail"=> array(
                    "AddressTypeID"=> "6",
                    "AddressLine1"=> $ShipArr['reciever_address'],
                    "AddressLine2"=> "SP",
                    "LocationID"=> $receiver_city
                )
                
            )
            )
        );

        $param = json_encode($param); 
    //  echo '<br>'. $counrierArr['api_url'].'api/CreditSale/AddUPDSPickupDelivery';
    //    echo 'copy : https://gateway-minasa.sp.com.sa/APIGateway/api/CreditSale/AddUPDSPickupDelivery'; exit;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $counrierArr['api_url'].'api/CreditSale/AddUPDSPickupDelivery',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$param,
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer '.$token,
            'Content-Type: application/json',
          ),
        ));
       $response = curl_exec($curl);
        curl_close($curl);
         $logresponse =   json_encode($response);  
         $response_array = json_decode($response, true);
        //$successres = $response_array['Items'][0]['Message'];
        $successres = $response_array['Message'];
        if($successres=='Success') 
        {
            $successstatus  = "Success";
        } else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$param);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$param);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$param);
        return $response;
    }


//Naqel ends 
    public function EmdadArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1,$super_id) 
    { 
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'emdad_city',$super_id);
     
        $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
        $password = $counrierArr['password'];
        $url = $counrierArr['api_url'];
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
       
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
        $Receiver_phone = $ShipArr['reciever_phone'];
        $Receiver_address = $ShipArr['reciever_address'];
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }
        
        $Reciever_city = $receiver_city;
        $product_type = 'Parcel'; //beone ka database
        $service = '2'; // beone wali
        $description = $ShipArr['status_describtion'];
        if (empty($description)) {
            $description = 'N/A';
        }

        // this is prodect name (column name: status_describtion

        $ajoul_booking_id = $ShipArr['booking_id'];
        $s_name =$sellername;
        $s_address = $sender_address;
        $s_zip = $ShipArr['sender_zip'];
        $s_phone = $ShipArr['sender_phone'];
        $s_city = $sender_city;

        $pay_mode = $ShipArr['mode']; //paymode either CASH or COD:(column name: mode || date
        $codValue = $ShipArr['total_cod_amt']; //COD charges.  :  (column name:     total_cod_amt || date type:
        $product_price = $ShipArr['declared_charge']; //(column name: declared_charge || date type: int || value: 11)
        $booking_id = $ShipArr['slip_no']; // send awb number ajoul
        $shipper_refer_number = $ShipArr['booking_id']; // ajoul ki booking id
        $weight = $weight;
       

        //weight should be in kg.:(column name: weight || date type
        $NumberOfParcel = $box_pieces; //(column name: pieces || date type: int || value: 5)
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

        $response = curl_exec($ch);

        curl_close($ch);
        $responseData = json_decode($response, TRUE);
        $logresponse =   json_encode($response);  
        
        $successres = $responseData['error'];
        $labelUrl = $responseData['awb_print_url'];
     
        if(!empty($labelUrl)) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],"productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],"productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");
        }
       // $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],"productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");
        
        return $response;
    }

    public function Ejack($sellername = null ,$ShipArr, $counrierArr, $complete_sku, $c_id,$box_pieces1,$super_id) 
    {

        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city',$super_id);

        $sender_email = $counrierArr['user_name']; //provided by company  :  (column name: password || date
        $password = $counrierArr['password'];
        $url = $counrierArr['api_url'];
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
             $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
       
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
        $Receiver_phone = $ShipArr['reciever_phone'];
        $Receiver_address = $ShipArr['reciever_address'];
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }

        $Reciever_city = $receiver_city;
        
        $product_type = 'Parcel'; //beone ka database
        $service = '2'; // beone wali
        $description = $ShipArr['status_describtion'];
        if (empty($description)) {
            $description = 'N/A';
        }

        // this is prodect name (column name: status_describtion

        $ajoul_booking_id = $ShipArr['booking_id'];
        $s_name = $sellername;
        $s_address = $sender_address;
        $s_zip = $ShipArr['sender_zip'];
        $s_phone = $ShipArr['sender_phone'];
        $s_city = $sender_city;

        $pay_mode = $ShipArr['mode']; //paymode either CASH or COD:(column name: mode || date
        $codValue = $ShipArr['total_cod_amt']; //COD charges.  :  (column name:     total_cod_amt || date type:
        $product_price = $ShipArr['declared_charge']; //(column name: declared_charge || date type: int || value: 11)
        $booking_id = $ShipArr['slip_no']; // send awb number ajoul
        $shipper_refer_number = $ShipArr['booking_id']; // ajoul ki booking id
        $weight = $weight;
       

        //weight should be in kg.:(column name: weight || date type
        $NumberOfParcel = $box_pieces; //(column name: pieces || date type: int || value: 5)

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");

        $responsedata = curl_exec($ch);

        curl_close($ch);
        $logresponse =   json_encode($responsedata);  
        
        $response = json_decode($responsedata,TRUE);
        $successres = $response['error'];
     
        if($successres == '') 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }


        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],"productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],"productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");
        }
        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],"productType=$product_type&service=$service&password=$password&sender_email=$sender_email&sender_name=$s_name&sender_city=$s_city&sender_phone=$s_phone&sender_address=$s_address&Receiver_name=$Receiver_name&Receiver_email=$Receiver_email&Receiver_address=$Receiver_address&Receiver_phone=$Receiver_phone&Reciever_city=$Reciever_city&Weight=$weight&Description=$description&NumberOfParcel=$NumberOfParcel&BookingMode=$pay_mode&codValue=$codValue&refrence_id=$booking_id&product_price=$product_price&shippers_ref_no=$shipper_refer_number");
        
        return $responsedata;
    }

    public function fastcooArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null, $c_id = null,$box_pieces1 = null,$super_id=null) {
      
        //$sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city',$super_id);
        //$customer_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

        $entry_date = date('Y-m-d H:i:s');
        $pickup_date = date("Y-m-d", strtotime($entry_date));
   
        $url =  $counrierArr['api_url']; 
        $secKey = $counrierArr['auth_token']; 
        $customerId =$counrierArr['courier_account_no']; 
        $formate    = "json";
        $method     = "createOrder";
        $signMethod = "md5";

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $customer_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $customer_email = $ShipArr['sender_email'];
        }
        
        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }
        
        

    

        if($ShipArr['cust_id'] == 305){
            $sellername = $ShipArr['shipment_seller_name']." - ".site_configTableSuper_id("company_name",$super_id);
        }
        
        if($super_id == 333){
            $customer_email = "";
            $sender_phone = $ShipArr['sender_phone'];
        }
        // echo $sellername;die;
    
        if ($ShipArr['pay_mode'] == 'COD') {
            $cod_amount = $ShipArr['total_cod_amt'];
    
        } elseif ($ShipArr['pay_mode'] == 'CC') {         
            $cod_amount = 0;
        }
        if(empty($box_pieces1))
            {
                $box_pieces = 1;
            }
            else
            { 
                 $box_pieces = $box_pieces1 ; 
            }
    
            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }
    
        if (empty($receiver_city)){
            $resp = array('msg' => 'receiver city empty');
            $response = json_encode($resp);
            return $response ;
        }
        else {
            $skudetails = array(array(
                "piece"=>  $box_pieces,
                "weight"=> $weight,
                "BookingMode"=> $ShipArr['mode'],
                ));
            $alldata = array(
                    "customerId" => $customerId ,
                    "secret_key" => $secKey, 
                    "BookingMode" => $ShipArr['mode'],
                    "codValue" => $cod_amount,
                    "reference_id" => $ShipArr['slip_no'],
                    "origin" => $sender_city,
                    "destination" => $receiver_city,
                    "service" => 3,
                    "sender_name" => $sellername,
                    "sender_address" =>$sender_address,
                    "sender_phone" =>  $sender_phone,//$ShipArr['sender_phone'],
                    "sender_email" =>  $customer_email, //$ShipArr['sender_email'],
                    "receiver_name" => $ShipArr['reciever_name'],
                    "receiver_address" => $ShipArr['reciever_address'],
                    "receiver_phone" => $ShipArr['reciever_phone'],
                    "receiver_email" =>  !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                    "latitude" => isset($ShipArr['latitude']) ? $ShipArr['latitude'] : '',
                    "longitude" => isset($ShipArr['longitude']) ? $ShipArr['longitude'] : '',
                    "description" => $complete_sku,
                    "pieces"=>  $box_pieces,
                    "weight"=> $weight,
                    "skudetails" => $skudetails,                    
                );
            // echo "<pre>"; print_r($alldata); die;  
                $sign = create_sign($alldata, $secKey, $customerId, $formate, $method, $signMethod);
                $data_array = array(
                  "sign"       => $sign,
                  "format"     => $formate,
                  "signMethod" => $signMethod,
                  "param"      => $alldata,
                  "method"     => $method,
                  "customerId" => $customerId,
                 );
         
            $dataJson = json_encode($data_array); 
            // echo $dataJson;die;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "Accept: application/json"
            ));
            $responsedata = curl_exec($ch);
    
            curl_close($ch);
       

            $response = json_decode($responsedata,TRUE); 


        $successres = $response['status'];
     
        if($successres ==200) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }

        // if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
        //     $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$dataJson);
        // }
        // else{
            
        //     $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$dataJson);
        // }
        $log = $this->shipmentLog($c_id, $responsedata,$successstatus, $ShipArr['slip_no'],$dataJson);
        
        return $responsedata;
        }
    }
    
	public function all_ccSeller($id= null )
    {
		$this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
         $this->db->where('cust_id', $id);
	     $this->db->order_by('id', 'desc');	
         $this->db->order_by('deleted', 'N');		  
        $query = $this->db->get('courier_company_seller');
        //echo $this->db->last_query(); die;
        if($query->num_rows()>0){
                return $query->result();
            }
        else {
           
           if($this->getsellerCC($id))
           {
                $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
                $this->db->where('cust_id', $id);
                $this->db->order_by('id', 'desc');	
                $this->db->order_by('deleted', 'N');		  
                $query = $this->db->get('courier_company_seller');
                //echo $this->db->last_query(); die;
                if($query->num_rows()>0){
                        return $query->result();
                    }
           }
        }

    }	

    public function getsellerCC($cust_id = null)
    {
        $ccdata = $this->all();
        foreach($ccdata as $couSel){
            $sellearray[] = array(
                'image' =>$couSel ->image,
                'user_name' =>'',
                'company_url' => '',
                'password' =>'',
                'courier_account_no' => '',
                'courier_pin_no' => '',
                'start_awb_sequence' =>'',
                'end_awb_sequence' => '',
                'company' =>  $couSel ->company,
                'status' => 'N',
                'deleted' => 'N',
                'entrydate' =>  $CURRENT_DATE,
                'auth_token' => '',
                'api_url' => '',
                'user_name_t' => '',
                'password_t' =>'',
                'courier_account_no_t' =>'',
                'courier_pin_no_t' => '',
                'start_awb_sequence_t' => '',
                'end_awb_sequence_t' =>'',
                'auth_token_t' =>'',
                'api_url_t' =>'',
                'type' => 'test',
                'super_id' => $couSel ->super_id,
                'cc_id' => $couSel ->cc_id,
                'company_type' => $couSel ->company_type,
                'capacity' => $couSel ->capacity,
                'n_column' => $couSel ->n_column,
                'delivery_days' => $couSel ->delivery_days,
                'cust_id' => $cust_id, 

            );    
        }

       $output =   $this->db->insert_batch('courier_company_seller', $sellearray);
          return $output; 
       // die; 
    }
    
   public function BeezArray($sellername=null,array $ShipArr, array $counrierArr, $complete_sku = null, $c_id = null,$box_pieces1 = null,$sku_data = null,$super_id)
    {
               
        //echo $complete_sku;die;
       // print "<pre>"; print_r($ShipArr);die;
        $apiUrl = $counrierArr['api_url']."Orders/PostOrder";
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = $ShipArr['reciever_email'];
        $Receiver_phone = $ShipArr['reciever_phone'];
        if(!empty($Receiver_phone)) { 
            if(strpos($Receiver_phone, '+') !== false){
                $Receiver_phone = str_replace("+","",$Receiver_phone);
            }
        }

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }
        
        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        $Receiver_address = $ShipArr['reciever_address'];
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }
    
        $Reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'beez_city',$super_id);
        
        if(empty($Reciever_city)){
            
            $successstatus = "Failed";
            $logresponse = "Reciver city is empty.";

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],'');
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],'');
            }
            //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
            $responseArray['Message'] = $logresponse;
            return $responseArray;
        }
        
        //print "<pre>"; print_r($lineItemsArray);die;
        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
        
        if(empty($box_pieces1)){ 
            $box_pieces = 1;
        }else{ 
             $box_pieces = $box_pieces1 ; 
        }
        
        $itemsArray = array();
        
        foreach ($sku_data as $key => $val) {
                $itemsArray[] = array("ProductName"=>$val['name'],'Quantity'=>$box_pieces,'SKU'=>$val['sku'],'UPC'=>'','Description'=>$complete_sku);
        }
        if ($ShipArr['pay_mode'] == "COD") {
            $total_cod_amt = $ShipArr['total_cod_amt'];
        }else{
            $total_cod_amt = 0;
        }
        $requestArray = array(
                "LineItems"=>$itemsArray,
                "Edit"=>false, // if new order forwarding than Edit= false else True
                "Payment"=> false, // if we use beez payment gateway to get payment than Payment = True else False
                "PaymentAmount"=>  $total_cod_amt, // if we use beez payment gateway to get payment than PaymentAmount >0  else 0.00
                "TrackingNumber"=> "",
                "AccountNumber"=> $counrierArr['courier_account_no'],
                "ApiKey"=> $counrierArr['auth_token'],
                "RequestedBy"=> $sellername,
                "OrderNumber"=> $ShipArr['slip_no'],
                "Shipping"=> true,
                "ShipmentType"=> "C", //Types of delivery: �D� �Dry, �C� � Cold 
                "CustomerNote"=> "",
                "Description"=> "",
                "COD"=> $total_cod_amt,
                "PickupLocation"=>"24.630062,46.8400283", //Should Register PickupLocation in Beez System before using it in API
                "BillingAddress"=>array(
                    array(
                        "CustomerFirstname"=> $Receiver_name,
                        "CustomerLastname"=>'',
                        "CustomerPhone1"=> "+".$Receiver_phone,
                        "CustomerPhone2"=> "+".$Receiver_phone,
                        "Lat"=> $lat,
                        "Lng"=> $lang,
                        "Line1"=> $Receiver_address,
                        "Line2"=> "",
                        "District"=> $Reciever_city,
                        "City"=> $Reciever_city,
                        "Province"=>"",
                        "PostCode"=> "",
                        "Country"=> $country,
                      )  
                ),
                "ShippingAddress"=>array(
                    array(
                        "CustomerFirstname"=> $Receiver_name,
                        "CustomerLastname"=> '',
                        "CustomerPhone1"=> "+".$Receiver_phone,
                        "CustomerPhone2"=> "+".$Receiver_phone,
                        "Lat"=>$lat,
                        "Lng"=> $lang,
                        "Line1"=>$Receiver_address,
                        "Line2"=> "",
                        "District"=> $Reciever_city,
                        "City"=> $Reciever_city,
                        "Province"=>"",
                        "PostCode"=> "",
                        "Country"=> $country,
                    )
                )
            );
        $params = json_encode($requestArray);
       // echo $params;die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json"
        ));
       $response = curl_exec($ch); 
    
        curl_close($ch);
        if(empty($response)){
            $responseArray['Message'] = "Response not found.Please contact with clients.";
            return $responseArray;
        }
        $responseArray = json_decode($response,true);
        //print "<pre>"; print_r($responseArray);die;
        $logresponse = json_encode($response);
        if(isset($responseArray['Message']) && !empty($responseArray['Message'])){
            $successstatus = "Failed";
        }else{
            $successstatus = "Success";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$params);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$params);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$params);
        
        return $responseArray;
    }
    public function GLTArray($sellername = null ,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $complete_sku = null,$super_id = null) 
    {
            // $sender_default_city = Getselletdetails_new($super_id);
            // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            //$sender_address = $ShipArr['sender_address'];

            

            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = $ShipArr['sender_address'];
                $sender_phone = $ShipArr['sender_phone'];
            }

            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }

            $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'GLT',$super_id);

            $API_URL = $counrierArr['api_url'] . "api/create/order";
            $api_key = $counrierArr['auth_token'];
            //print_r($api_key);die; 
            $currency = site_configTable("default_currency");//"SAR";
            
            if (empty($box_pieces1)) {
            $box_pieces = 1;
            } else {
            $box_pieces = $box_pieces1;
            }
            
            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }
            
            if ($ShipArr['pay_mode'] == 'COD') {
            $cod_amount = $ShipArr['total_cod_amt'];
            } elseif ($ShipArr['pay_mode'] == 'CC') {
            $cod_amount = 0;
            }
            
            $senderdata = array('city' => array(
            'name' => $sender_city),
            'address' => $sender_address,
            'contactNumber' =>$sender_phone // $ShipArr['sender_phone']
            );
            $receiverdata = array(
            'name' => $ShipArr['reciever_name'],
            'customerAddresses' => array(
            'city' => array(
            'id' => $ShipArr['destination'],
            'name' => $receiver_city),
            'address' => $ShipArr['reciever_address'],
            'areaName' => $ShipArr['reciever_address']),
            'mobile1' => $ShipArr['reciever_phone']
            );
            $details[] = array(
            'referenceNumber' => $ShipArr['slip_no'],
            'pieces' => $box_pieces,
            'description' => $complete_sku,
            'codAmount' => $cod_amount,
            'paymentType' => $ShipArr['mode'],
            'clientComments' => 'none',
            'sender' => $sellername,
            "senderInformation" => $senderdata,
            'value' => $cod_amount,
            'customer' => $receiverdata,
            'weight' => $weight,
            );
            
            $all_param_data = array('orders' => $details);
            
            $json_final_date = json_encode($all_param_data);
            
            if(!empty($receiver_city))
            { 
                    $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                    'Content-Type:application/json',
                    'Authorization:'. $api_key),
                    ));
                    $response = curl_exec($curl);
                    
                    curl_close($curl);
            }
            else {
               
                $response = array('data'=> 
                                array('orders'=> array('0' => array('msg'=> 'reciever city empty')))
                            ); 
                  $response = json_encode($response);         
                // print_r($response); die;            
            }
            $responseArray = json_decode($response, true);            
            $logresponse = json_encode($response);

            $successres = $responseArray['data']['orders'][0]['status'];

            if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }

    

    public function GLT_label($client_awb = null,$counrierArr= null, $auth_token=null) 
        {
            $label_header = array(
                "Authorization:".$auth_token,
                "Content-Type:application/json"
            );
        ;
            $get_label_url = $counrierArr['api_url']."api/get/awb?orderid=".$client_awb;
            $ch = curl_init();
            
            curl_setopt_array($ch, array(
                CURLOPT_URL => $get_label_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => $label_header
            ));
            
            $label_response = curl_exec($ch);

            $info = curl_getinfo($ch);
            curl_close($ch);
            return  $label_response;
        }

   //KwickBox Array 
    public function KwickBoxArray($sellername = null ,array $ShipArr, array $counrierArr, $c_id = null, $box_pieces1 = null, $complete_sku = null,$super_id = null) 
    {
        
        // print "<pre>"; print_r($ShipArr);die;
            
            $sender_city =  getdestinationfieldshow_auto_array($ShipArr['origin'], 'kwickBox_city', $super_id); 
            
            // echo "city=".$ShipArr['origin'];die;
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'kwickBox_city',$super_id);          
            $sender_country = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country',$super_id);
            $receiver_country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);

           // $store_address = $ShipArr['sender_address'];              
           // $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            //$senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');


            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){

                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $store_address = $ShipArr['sender_address'];
                $sender_phone = $ShipArr['sender_phone'];
                $senderemail = $ShipArr['sender_email'];
               
            }

            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }
            
        //    echo $box_pieces; die;

            $API_URL = $counrierArr['api_url'] . "shipments";
            $api_key = $counrierArr['auth_token'];
           
            $currency = site_configTable("default_currency");//"SAR";            
            
            if (empty($box_pieces1)) {
            $box_pieces = 1;
            } else {
            $box_pieces = $box_pieces1;
            }

    //    echo $box_pieces1. " <br> box_pieces = ".$box_pieces; die;
            if(empty($complete_sku)){
                $complete_sku = "Qty:". $box_pieces;
            }else {$complete_sku = $complete_sku; }

            
            
            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }
            
            if ($ShipArr['pay_mode'] == 'COD') {
                $cod_amount = $ShipArr['total_cod_amt'];
            } elseif ($ShipArr['pay_mode'] == 'CC') {
                $cod_amount = 0;
            }          

            $Shipper = array(
                'Reference' => '',
                'Contact' => array(
                    'Name' => $sellername,
                    'Email' => $senderemail,
                    'Phone' => $senderphone,
                    'SecondPhone' => ''
                ),
                'Address' => array(
                    'Reference' => '',
                    'Country' => $sender_country,
                    'State' => '',
                    'PostCode' => '',
                    'City' => $sender_city,
                    'AddressLine1' => $store_address,
                    'AddressLine2' => '',
                    'AddressLine3' => '',
                )
            );

            // $receiver_address = substr($ShipArr['reciever_address'], 0,30);
            // $receiver_address = $this->stripInvalidXml($receiver_address);
            // $receiver_address=	mb_substr($receiver_address,0,30,"UTF-8"); 
            // $receiver_address = str_replace(","," ",$receiver_address);

            // echo "receiver address <br> ".$ShipArr['reciever_address']; 
            $receiver_address =  json_encode($ShipArr['reciever_address'], JSON_UNESCAPED_UNICODE); 
            $reciever_phone=$ShipArr['reciever_phone'];
            $reciever_phone = ltrim($reciever_phone, '+');
            $reciever_phone = ltrim($reciever_phone, '966 ');
            $reciever_phone = ltrim($reciever_phone, '966');
    
            $reciever_phone = ltrim($reciever_phone, '0');
            $reciever_phone = '0' . $reciever_phone;

            $Consignee = array(
                'Reference' => '',
                'Contact' => array(
                    'Name' => $ShipArr['reciever_name'],
                    'Email' => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                    'Phone' => $reciever_phone,
                    'SecondPhone' => ''
                ),
                'Address' => array(
                    'Reference' => '',
                    'Country' => $receiver_country,
                    'State' => '',
                    'PostCode' => '',
                    'City' => $receiver_city,
                    'AddressLine1' => $receiver_address,//substr($ShipArr['reciever_address'], 0,100),
                    'AddressLine2' => '',
                    'AddressLine3' => '',
                )
            );
            $complete_sku = str_replace(","," ",$complete_sku);
            $complete_sku = $this->stripInvalidXml($complete_sku);
            $complete_sku=	mb_substr($complete_sku,0,100,"UTF-8");             
            $Commodity = array(
                'Reference' => '',
                'Weight' => $weight,
                'Dimensions' => '',
                'Description' => $complete_sku,
                'COD' => $cod_amount,
                'NumberOfPieces' => $box_pieces,
                );  

                
            $details[] = array(
            'UniqueReference' => $ShipArr['slip_no'],
            'withoutShipmentLabel'=> true,
            // 'SinglePagePdf'=> true,
            'Shipper' => $Shipper,
            'Consignee' => $Consignee,
            'Commodity' => $Commodity,
            'Notes' => '',           
            );
                  // print "<pre>"; print_r($details);die;   
            
            $json_final_date = json_encode($details);
                //  echo "data=".$json_final_date;die;

            if(!empty($receiver_city))
            { 
                    $curl = curl_init();                    
                    curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                    'Content-Type:application/json',
                    'ApiKey:'. $api_key),
                    ));
                    $response = curl_exec($curl);                    
                    curl_close($curl);                   
            }
            else {
                $error = 'reciever city empty';
                $responseArray['field.'][0] = $error;

                if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $error,'Fail', $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
                }else{
                    
                    $this->shipmentLog($c_id, $error,'Fail', $ShipArr['slip_no'],$json_final_date);
                }

                //$log = $this->shipmentLog($c_id, $error,'Fail', $ShipArr['slip_no'],$json_final_date);
                return $responseArray;
            }
            $responseArray = json_decode($response, true);            
            $logresponse = json_encode($response);


            $successres = $responseArray['number'];

            if (!empty($successres)) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }

public function DhlJonesArray($sellername = null, array $ShipArr, array $counrierArr,$token= null, $complete_sku = null, $box_pieces1=null,$c_id=null,$super_id=null)
    { 
            date_default_timezone_set('Asia/Kolkata');
            $API_URL = $counrierArr['api_url'].'ShipmentRequest';            
            $sender_default_city = Getselletdetails_new($super_id);  

            //$senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            //$senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            //$senderAddress = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderCompany = GetallCutomerBysellerId($ShipArr['cust_id'],'company');   

          

            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
            
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $senderAddress = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
               
                $senderAddress = $ShipArr['sender_address'];
                $senderphone = $ShipArr['sender_phone'];
                $senderemail = $ShipArr['sender_email'];
            
            }
            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }
            


            $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'dhl_jones_city', $super_id);
            $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'dhl_jones_city',$super_id);           
            $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code',$super_id);           

            // reciver city only belongs to AE Country like dubai            
            if(empty($receiver_city)){
                 $logresponse = "Receiver city empty";
                $successstatus  = "Fail";
                $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
                return array("error"=>true,"data"=>array('message'=>'receiver city empty'));
            }

            if (empty($box_pieces1)) {
                $box_pieces = 1;
            } else {
                $box_pieces = $box_pieces1;
            }

            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }
            
            
            if($ShipArr['pay_mode'] == "COD"){
                $cod_amount = $ShipArr['total_cod_amt'];
            }
            elseif ($ShipArr['pay_mode'] == 'CC'){
                $cod_amount = 1;
            }
            
            if(empty($complete_sku)){
             $complete_sku = $ShipArr['status_describtion'];

            }else {
                $complete_sku =  $complete_sku;
            }
        
        $shipDate =  date('Y-m-d')."T".date('H:i:s') ."GMT+03:00";
        
        $request_params_array = array(
            "ShipmentRequest"=>array(
                "RequestedShipment"=>array(
                    "ShipmentInfo"=>array(
                        "DropOffType"=> "REGULAR_PICKUP",
                        "ServiceType"=> "P",
                        "Account"=> $counrierArr['courier_account_no'],
                        "Currency"=> "SAR",
                        "UnitOfMeasurement"=> "SI",
                        "LabelOptions"=>array(
                             "RequestWaybillDocument"=> "Y",
                             "RequestDHLCustomsInvoice"=> "Y"
                        ),
                        "PaperlessTradeEnabled"=> "true",
                        "SpecialServices"=>array(
                            "Service"=>array(
                                "ServiceType"=>"WY"
                            )
                        ),
                    ),
                    //"ShipTimestamp"=> "2021-07-20T12:00:00GMT+03:00",
                    "ShipTimestamp"=> $shipDate,
                    "PaymentInfo"=> "DAP",
                    "InternationalDetail"=>array(
                        "Commodities"=>array(
                            "NumberOfPieces"=> $ShipArr['pieces'],
                            "Description"=> $complete_sku,
                            "CountryOfManufacture"=>"SA",
                            "Quantity"=> $box_pieces,
                            "UnitPrice"=> $cod_amount,
                            "CustomsValue"=> $cod_amount
                        ),
                        "Content"=>"NON_DOCUMENTS",
                        "ExportDeclaration"=>array(
                            "DestinationPort"=> "DXB Port",
                            "ExporterCode"=> "14212",
                            "InvoiceDate"=> date('Y-m-d'),
                            "InvoiceNumber"=> $ShipArr['slip_no'],
                            "ExporterID"=> "124567433",
                            "ExportLicense"=> "332453",
                            "ExportReason"=> substr($complete_sku, 0,30),
                            "ImportLicense"=> "11335323",
                            "PackageMarks"=> $complete_sku,
                            "PayerGSTVAT"=> "123986",
                            "RecipientReference"=> "",
                            "ExportLineItems"=>array(
                                "ExportLineItem"=>array(

                                    array(
                                        "ItemNumber"=> $box_pieces,
                                        "Quantity"=> $box_pieces,
                                        "QuantityUnitOfMeasurement"=> "",
                                        "ItemDescription"=> $complete_sku,
                                        "UnitPrice"=> $cod_amount,
                                        "NetWeight"=> $weight,
                                        "GrossWeight"=> $weight,
                                        "ManufacturingCountryCode"=> "SA"
                                    )
                                )
                            )
                        )
                    ),
                    "Ship"=>array(
                        "Shipper"=>array(
                            "Contact"=>array(
                                "PersonName"=>$sellername,
                                "CompanyName"=>$senderCompany,
                                "PhoneNumber"=> $senderphone,
                                "EmailAddress"=> $senderemail
                            ),
                            "Address"=>array(
                                "StreetLines"=> substr($senderAddress, 0,50),
                                "StreetLines2"=> $sender_city,
                                "City"=> $sender_city,
                                "PostalCode"=>"",
                                "CountryCode"=> $sender_country_code
                            )
                        ),
                        "Recipient"=>array(
                            "Contact"=>array(
                                "PersonName"=> $ShipArr['reciever_name'],
                                "CompanyName"=> $ShipArr['reciever_name'],
                                "PhoneNumber"=> $ShipArr['reciever_phone'],
                                "EmailAddress"=> !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
                            ),
                            "Address"=>array(
                                "StreetLines"=> substr($ShipArr['reciever_address'], 0,50),
                                "StreetLines2"=> $receiver_city,
                                "City"=> $receiver_city,
                                "StateOrProvinceCode"=>"",
                                "PostalCode"=> "",
                                "CountryCode"=> $receiver_country_code
                            )
                        )
                    ),
                    "Packages"=>array(
                        "RequestedPackages"=>array(
                            array(
                                "@number"=>"1",
                                "Weight"=>$weight,
                                "Dimensions"=>array(
                                    "Length"=> 1,
                                    "Width"=> 1,
                                    "Height"=> 1
                                ),
                                "CustomerReferences"=> $ShipArr['slip_no']
                            )
                        )
                    )
                )
            )
        );        
        
        //echo '<Pre>'; print_r($request_params_array); die;
        
        $json_params = json_encode($request_params_array);
        //echo $json_params;die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $API_URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$json_params,
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic '.$counrierArr['auth_token'].' ' 
          ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $responseData = json_decode($response,TRUE);
        //print "<pre>"; print_r($responseData);die;
        $logresponse =   json_encode($response);  
        $errorFlag = TRUE;
        if(isset($responseData['ShipmentResponse']) && !empty($responseData['ShipmentResponse'])){
            if(isset($responseData['ShipmentResponse']['ShipmentIdentificationNumber']) && !empty($responseData['ShipmentResponse']['ShipmentIdentificationNumber'])){
                $errorFlag = FALSE;
            }
        }
        
        
        if ($errorFlag === FALSE){
            $successstatus = "Success";
        }else{
            $successstatus = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_params);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_params);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_params);
        
        return array("error"=>$errorFlag,'message'=>'','data'=>$responseData);       
    }


    public function WadhaArray($sellername = null ,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,$super_id=null) 
        {
               // $sender_default_city = Getselletdetails_new($super_id);
                // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                //$sender_address = $ShipArr['sender_address'];

                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){

                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');

                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                
                    $sender_address = $ShipArr['sender_address'];
                    $sender_phone = $ShipArr['sender_phone'];
                    $sender_email = $ShipArr['sender_email'];
                }
                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }

                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
                $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'Wadha',$super_id);

                
                    $currency = site_configTable("default_currency");//"SAR";
                    
                    if (empty($box_pieces1)) {
                    $box_pieces = 1;
                    } else {
                    $box_pieces = $box_pieces1;
                    }
                    
                    if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                        $weight = 1;
                   }else {
                        $weight = $ShipArr['weight'];
                   }
                    
                    if($ShipArr['pay_mode'] == "COD"){
                        $pay_mode = "credit_balance";
                        $cod_amount = $ShipArr['total_cod_amt'];
                        $paid = FALSE;
                    }
                    elseif ($ShipArr['pay_mode'] == 'CC'){
                        $pay_mode = "credit_balance";
                        $paid = TRUE;
                        $cod_amount = 0;
                    }
        
                $sender_data = array(
                    'address_type' => 'residential',
                    'name' => $sellername,
                    'email' => $sender_email,//$ShipArr['sender_email'],
                    'apartment'=> 221,
                    'building' => 'B',
                    'street' => $sender_address,
                    "city" => array(
                        "name" =>$sender_city
                    ),
                    "country" => array(
                        "id" => 191
                    ),
                    
        
                        'phone' =>$sender_phone,//$ShipArr['sender_phone'],
                        );
                    
        
                    $receiverdata = array(
                    'address_type' => 'residential',
                    'street' => $ShipArr['reciever_address'],
                    'name'=> $ShipArr['reciever_name'],
                    'city' => array(
                            'name' => $receiver_city
                        ),
                        'phone' => $ShipArr['reciever_phone'],
                        'landmark' => $ShipArr['reciever_address']);
        
        
                $dimensions = array(
                    'weight' => $weight,
                    'width' =>  '',
                    'length' => '',
                    'height' =>'' ,
                    'unit' => '',
                    'domestic' => true
                );
                $package_type = array(
                    'courier_type' => '01'
                );
    
                $charge_items[] = array(
                    'paid' => $paid,
                    'charge' => $cod_amount,
                    'charge_type' => "COD"               
                );
        
                $details = array(
                    'sender_data' => $sender_data,
                    'recipient_data' => $receiverdata,
                    'dimensions' => $dimensions,
                    'package_type' => $package_type,
                    'charge_items' => $charge_items,
                    'recipient_not_available' => 'do_not_deliver',
                    'payment_type' => $pay_mode,
                    'payer' => 'sender',
                    'parcel_value' => $cod_amount,
                    'fragile' => true,
                    'note' => 'mobile phone',
                    'piece_count' => $box_pieces,
                    'force_create' => true
                );
        
                    
            
                    $json_final_date = json_encode($details);
            //   print_r($json_final_date); //die;
                
                        
                if (empty($receiver_city))
                {
                
                    $response = array('message'=> 'Receiver city empty' ); 
                    $response = json_encode($response);

                }
                else {
                    $curl = curl_init();    
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $API_URL,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $json_final_date,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Accept: application/json',
                            'Authorization:Bearer ' .$Auth_token),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                }

                $responseArray = json_decode($response, true);
        
                $logresponse =   json_encode($response);  
                
                $successres = $responseArray['status'];
                //print_r($successres);die;
        
                if ($successres == 'success') 
                {
                        $successstatus = "Success";
                } else {
                        $successstatus = "Fail";
                }

                if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
                }else{
                    
                    $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
                }

                //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
                return $responseArray;
        }
        
            
            
    public function Wadha_label($client_awb = null,$counrierArr= null, $Auth_token=null) 
        {
        
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url']."v1/customer/orders/airwaybill_mini?ids=&order_numbers=".$client_awb,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));



            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
        }
        
    public function Wadha_auth($user_name=null,$password=null,$api_url=null)
        {
        
            $param= array(  'username'=>$user_name,
                            'password'=>  $password,
                            'remember_me'=>true
                        );
            $dataJson =json_encode($param);
            $curl = curl_init();
        
                curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url."v1/customer/authenticate",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$dataJson,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json'
                ),
                ));
        
                $Auth_response = curl_exec($curl);
        
                curl_close($curl);
                $responseArray = json_decode($Auth_response, true);
        
                $Auth_token = $responseArray['data']['id_token'];
                //print_r($Auth_token);die;
                return $Auth_token;
        
        }

   


        public function MMCCO_auth($user_name=null,$password=null,$api_url=null)
    {
      
        $param= array(  'username'=>$user_name,
                        'password'=>  $password,
                        'remember_me'=>true
                    );
        $dataJson =json_encode($param);
       // echo $dataJson; die;
        $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url."v1/customer/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$dataJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
            ));
    
            $Auth_response = curl_exec($curl);
            
            curl_close($curl);
           
            $responseArray = json_decode($Auth_response, true);
    
            $Auth_token = $responseArray['data']['id_token'];
           // print_r($Auth_token);die;
             return $Auth_token;
    
    }
        public function MMCCOArray($sellername=null, array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $super_id= null, $complete_sku= null) 
    {
                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);


                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $sellerphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $selleremail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $sender_address = $ShipArr['sender_address'];
                    $sellerphone = $ShipArr['sender_phone'];
                    $selleremail = $ShipArr['sender_email'];
                }
                
                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }                     
                
                $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'MMCCO_city',$super_id);
              
                $API_URL = $counrierArr['api_url'] . "v2/customer/order";
               
                $currency = "SAR";
                
                if (empty($box_pieces1)) {
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }

               // print_r($ShipArr);die;
                if($ShipArr['pay_mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $paid = FALSE;
                    $cod_amount = $ShipArr['total_cod_amt'];
                    
                }
                elseif ($ShipArr['pay_mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

               
    
            $sender_data = array(
                    'address_type' => 'business',
                    'name' => $sellername,
                    'email' => $selleremail,
                    'apartment'=> '',
                    'building' => '',
                    'street' => $sender_address,
                    "city" => array(
                        "name" =>$sender_city
                    ),
                    "country" => array(
                        "id" => 191
                    ),
                    
        
                        'phone' =>$sellerphone,
                        );
                    
        
                    $receiverdata = array(
                    'address_type' => 'business',
                    'street' => $ShipArr['reciever_address'],
                    'name'=> $ShipArr['reciever_name'],
                    'city' => array(
                            'name' => $receiver_city
                        ),
                        'phone' => $ShipArr['reciever_phone'],
                        'landmark' => $ShipArr['reciever_address']);
        
        
                $dimensions = array(
                    'weight' => $weight,
                    'width' =>  '',
                    'length' => '',
                    'height' =>'' ,
                    'unit' => '',
                    'domestic' => true
                );
                $package_type = array(
                    //'courier_type' => 'PRIORITY'
                    'courier_type' => 'BCD'
                );
    
                $charge_items[] = array(
                    'paid' => $paid,
                    'charge' => $cod_amount,
                    'charge_type' => $ShipArr['pay_mode']             
                );
        
                $details = array(
                    'sender_data' => $sender_data,
                    'recipient_data' => $receiverdata,
                    'dimensions' => $dimensions,
                    'package_type' => $package_type,
                    'charge_items' => $charge_items,
                    'recipient_not_available' => 'do_not_deliver',
                    'payment_type' => $pay_mode,
                    'payer' => 'recipient',
                    'parcel_value' => $cod_amount,
                    'fragile' => true,
                    'note' => $complete_sku,
                    'piece_count' => $box_pieces,
                    'force_create' => true,
                    "reference_id" => $ShipArr['slip_no']
                );
        
                    

            $json_final_date = json_encode($details);
             //echo "<pre>";  print_r($details);  die;
              
                     
            if (empty($receiver_city))
            {
                 $response = array('message'=> 'Receiver city empty' ); 
                 $response = json_encode($response);
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
             if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }
    
        
        
    public function MMCCO_label($client_awb = null,$counrierArr= null, $Auth_token=null) 
    {
        
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url']."v1/customer/orders/airwaybill_mini?ids=&order_numbers=".$client_awb,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
    
    
    
            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
    }
        public function iMileToken(array $counrierArr)
    {
        $apiUrl = $counrierArr['api_url'] ."auth/accessToken/grant";
        $customerID = $counrierArr['courier_account_no'];
        $sign = $counrierArr['auth_token'];
        
        $timestamp =  strtotime(date("Y-m-d H:i:s")) * 1000;
        $requestParams = array(
           "customerId"=>$customerID,
           "format"=>"json",
           "signMethod"=>"SimpleKey",
           "version"=>"1.0.0",
           "timestamp"=>$timestamp,//strtotime(date('Y-m-d :i:s')),
           "timeZone"=>"+3",
           "Sign"=>$sign,
           "param"=>array(
             "grantType"=> "clientCredential"  
           ) 
        );
        $params = json_encode($requestParams);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json"
        ));
        $response = curl_exec($ch); 
        
        curl_close($ch);
        $responseArray = json_decode($response,TRUE);
        //print "<pre>"; print_r($responseArray);die;
        if($responseArray['code'] == 200  && $responseArray['message'] == 'success'){
            $token = $responseArray['data']['accessToken'];
        }else{
            $token = '';
        }
        return $token;
    }
    
    public function iMileArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $c_id = null,$box_pieces1 = null,$auth_token = null,$super_id = null,$imileordertype= null ) 
    {   
       // $sender_default_city = Getselletdetails($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address = $ShipArr['sender_address'];
        
        $apiUrl = $counrierArr['api_url']."client/order/createB2cOrder";
        $customerID = $counrierArr['courier_account_no'];
        $timestamp =  strtotime(date("Y-m-d H:i:s")) * 1000;
        $sign = $counrierArr['auth_token'];
        $userName = $ShipArr['sender_name'];
        $storeName = $ShipArr['store_name']; 
        
        $Receiver_name = $ShipArr['reciever_name'];
        $Receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
        $Receiver_phone = $ShipArr['reciever_phone'];
        $Receiver_address = $ShipArr['reciever_address']; 
        if (empty($Receiver_address)) {
            $Receiver_address = 'N/A';
        }
        
        $Reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'imile_city',$super_id);
        
        //print "<pre>"; print_r($lineItemsArray);die;
        $lat = getdestinationfieldshow_auto_array($ShipArr['destination'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['destination'], 'longitute',$super_id);
        $country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
        
        //print "<pre>";print_r($ShipArr);die;
        // $sender_address = $ShipArr['sender_address'];
        // $selleremail = $ShipArr['sender_email'];
        // $sellerphone = $ShipArr['sender_phone'];




        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $pickup_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sellerphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $selleremail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $pickup_address = $ShipArr['sender_address'];
            $sellerphone = $ShipArr['sender_phone'];
            $selleremail = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        //echo $sender_address;die;
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'imile_city', $super_id);        
        $sender_country = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country',$super_id);
        $sender_lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $sender_lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);


        //$pickup_address = site_configTable("pickup_address");
        $pickup_area = site_configTable("pickup_area");
        $pickup_lat = site_configTable("latitude");
        $pickup_lang = site_configTable("longitude");

       
        
        if(empty($box_pieces1)){
            $box_pieces = 1;
        }else{ 
             $box_pieces = $box_pieces1 ; 
        }
        
        //100: PPD(Prepaid) 200: COD (Cash On Delivery)
        
        if ($ShipArr['pay_mode'] == "COD") {
            $total_cod_amt = $ShipArr['total_cod_amt'];
            $payMethod = "200";
            $collecting_amt = $total_cod_amt;
        }else{
            $total_cod_amt = 1;
            $payMethod = "100";
            $collecting_amt = 0;
        }
        
        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        
        if(empty($complete_sku)) { $complete_sku = 'test sku';}

        if($super_id == 54){            
            $complete_sku=	$complete_sku;
        }
        else{
            $complete_sku=	mb_substr($complete_sku,0,50,"UTF-8"); 
        }

        $requestParams = array(
           "customerId"=>$customerID,
           "accessToken"=>$auth_token, 
           "format"=>"json",
           "signMethod"=>"SimpleKey",
           "version"=>"1.0.0",
           "timestamp"=>$timestamp,//strtotime(date('Y-m-d :i:s')),
           "timeZone"=>"+4",
           "Sign"=>$sign,
           "param"=>array(
              "orderCode"=>$ShipArr['slip_no'],
              "orderType"=> $imileordertype, // 100: Delivery order 200: return order 400: Refund order 500: B2B order 800: Forward order
              "oldExpressNo"=> "",
              "deliveryType"=> "Self",
              //"consignor"=>  $sellername.'-'.$storeName,
              "consignor"=>  $sellername,
              "consignorContact"=> $sellername,
              "consignorPhone"=> $sellerphone,
              "consignorMobile"=> "",
              "consignorCountry"=> 'KSA',//$sender_country,
              "consignorProvince"=> "",
              "consignorCity"=> $sender_city,
              "consignorArea"=> $pickup_area,
              "consignorAddress"=> $pickup_address,
              "consignorLongitude"=>$pickup_lat,
              "consignorLatitude"=> $pickup_lang,
              "consignee"=>$Receiver_name, //receiver name
              "consigneeContact"=>$Receiver_name,
              "consigneeMobile"=>"",
              "consigneePhone"=>$Receiver_phone,
              "consigneeEmail"=>$Receiver_email,
              "consigneeCountry"=>!empty($country)?$country:'SA',
              "consigneeCity"=>$Reciever_city,
              "consigneeArea"=>$Reciever_city,
              "consigneeAddress"=>$Receiver_address,
              "consigneeLatitude"=>$lat,
              "consigneeLongitude"=>$lang,
              "goodsValue"=>$total_cod_amt,
              "collectingMoney"=>$collecting_amt,
              "paymentMethod"=>$payMethod, 
              "totalCount"=>$box_pieces,
              "totalWeight"=>$weight,
              "totalVolume"=>$box_pieces,
              "skuTotal"=>$box_pieces,
              "skuName"=>$complete_sku,
              "skuZh"=> $complete_sku,
              "deliveryRequirements"=> $ShipArr['promise_deliver_date'],
              "orderDescription"=> $complete_sku. " - Promise_deliver_date : ". $ShipArr['promise_deliver_date'],
              "buyerId"=>"",
              "platform"=>"",
              "isInsurance"=> 0,
              "pickDate"=>"",
              "pickType"=>"0",
              "batterType"=>null,
              "currency"=>"Local" //Local-local currency USD-U.S. dollar
           ) 
        );
        
        $params = json_encode($requestParams);
        // echo $params;die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json"
        ));
        $response = curl_exec($ch);
        //echo $response;die;
        curl_close($ch);
    
        $responseArray = json_decode($response,true);
        //print "<pre>"; print_r($responseArray);die;
        $logresponse = json_encode($response);
        if($responseArray['code'] == 200  && $responseArray['message'] == 'success'){
            $successstatus = "Success";
        }else{
            $successstatus = "Failed";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$params);
        }else{
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$params);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$params);
        
        return $responseArray;
        
    }
    public function fetchrArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null , $c_id = null,$box_pieces1,$super_id = null) 
    {
       // $sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        $sender_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'fetchr_city',$super_id);
       
        $country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
        $API_URL = $counrierArr['api_url'];
        $token = $counrierArr['auth_token'];
        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);

        

        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        { 
                $box_pieces = $box_pieces1 ; 
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
    

        if ($ShipArr['pay_mode'] == 'COD') {
            $BookingMode = 'COD';
            $codValue = $ShipArr['total_cod_amt'];
           
        } elseif ($ShipArr['pay_mode'] == 'CC') {
            $BookingMode = 'Credit Card';
                $codValue = 1;
           
        }
        
            $item_array =  array(
                array(
                    "description"=> $complete_sku,
                    "sku"=> $complete_sku,
                    "hs_code"=> "",
                    "quantity"=> $box_pieces,
                    "order_value_per_unit"=> 0 
                )  
            );
            $data_array = array(
                    array(
                        "order_reference"=> $ShipArr['slip_no'],
                        "name"=> $ShipArr['reciever_name'],
                        "email"=> !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                        "phone_number"=>$ShipArr['reciever_phone'],
                        "address"=> $ShipArr['reciever_address'],
                        "receiver_country"=> $country, //"Saudi Arabia",
                        "receiver_city"=> $receiver_city,
                        "area"=> $ShipArr['reciever_address'],
                        "payment_type"=> $BookingMode,
                        "bag_count"=> $box_pieces,
                        "weight"=> $weight,
                        "description"=> $complete_sku,
                        "comments"=>$complete_sku,
                        "order_package_type"=> "",
                        "total_amount"=> $codValue,
                        "latitude"=> $lat,
                        "longitude"=> $lang,
                        "items"=>$item_array
                    ) 
            );
            $param = array(
            'client_address_id'=>"ADDR12182_1463",
            'data'=>$data_array,
            );
      
        $params = json_encode($param);
       // echo "<br>"; print_r($data_array); die;
       
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $API_URL.'order',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $params,
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '. $token,
            'Content-Type: application/json',
          ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        
        $responseData = json_decode($response,TRUE);
        
      //  echo "<br>"; print_r($responseData); die;

        $successres = $responseData['data'][0]['status'];
        
        if($successres == 'success') 
        {
            $successstatus  = "success";
        } else {
            $successstatus  = "Fail";
        }


        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$params);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$params);
        }

        //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$params);
        return $responseData;
    }

    public function fetchrLabel($auth_token= null,$client_awb =null ,array $counrierArr)
    {

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$auth_token
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url'].'/awb/'.$client_awb.'?type=6x4',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
        )
        );

        
            $response = curl_exec($curl);

            curl_close($curl);
            $labellink =   json_decode($response,true);
            return $labellink['data'];
            



    }

    public function tamexArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null, $Auth_token = null,$c_id=null,$box_pieces1=null,$super_id=null) 
    {

        // $sender_default_city = Getselletdetails_new($super_id);
        // $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
        //$sender_address =$ShipArr['sender_address'];


        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        


        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'tamex_city',$super_id);

        if(!empty($receiver_city))
        {
            $API_URL = $counrierArr['api_url'].'create';
            if ($ShipArr['pay_mode'] == 'COD') {
                $codValue = $ShipArr['total_cod_amt'];
            } else {
                $codValue = 0;
            }
        

            if(empty($box_pieces1))
            {
                $box_pieces = 1;
            }
            else
            { 
                $box_pieces = $box_pieces1 ; 
            }

            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }

        $currency = site_configTable("default_currency");    
        $param = array(
            "apikey" => $counrierArr['auth_token'],          
            "pack_type" => 1,
            "pack_awb" => $ShipArr['slip_no'],
            "pack_vendor_id" => "Diggipacks",
            "pack_reciver_name" => $ShipArr['reciever_name'],
            "pack_reciver_phone" => $ShipArr['reciever_phone'],
            "pack_reciver_country" => "SA",
            "pack_reciver_city" => $receiver_city,
            "pack_reciver_dist" => '',
            "pack_desc" => $complete_sku,
            "pack_num_pcs" => $box_pieces,
            "pack_weight" => $weight,
            "pack_cod_amount" => $codValue,
            "pack_currency_code" => $currency,
            "pack_extra_note" => "OK",
            "pack_live_time" => "4",
            "pack_sender_name" => $sellername,//"Diggipacks",
            "pack_sender_phone" => $ShipArr['reciever_phone'],
            "pack_sender_email" =>$sender_email,//$ShipArr['sender_email'],
            "pack_send_country" => "SA",
            "pack_send_city" =>  $sender_city,
            "pack_sender_dist" =>  $sender_city,
            "pack_sender_street" => $sender_address,
            "pack_sender_zipcode" => "",
            "pack_sender_building" => "NA",
            "pack_sender_extra" => "NA",
            "pack_sender_extar_address" => "NA",
            "pack_sender_longitude" => "NA",
            "pack_sender_latitude" => "NA",
            "pack_reciver_email" => !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
            "pack_reciver_street" => $ShipArr['reciever_address'],
            "pack_reciver_zipcode" => "",
            "pack_reciver_building" => "",
            "pack_reciver_extra" => "NA",
            "pack_reciver_extar_address" => "NA",
            "pack_reciver_longitude" => "",
            "pack_reciver_latitude" => "",
            "pack_dimention" => "",
            "pack_invoice_no" => "",
        );
        $all_param_data = json_encode($param);
        //echo $all_param_data;die;
        $headers = array(
            "Accept:application/json"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $all_param_data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $responseArray = json_decode($response, true);     
        $logresponse =   json_encode($response);  
    

        if ($responseArray['code'] == 0) {
            $successstatus  = "Success";
        
        } elseif ($response['code'] != 0 || empty($response)) {
            $successstatus  = "Fail";
        }else{
            $successstatus  = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$all_param_data);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$all_param_data);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$all_param_data);
        return $response;
        }else{ 
            $responseArray = array('data'=> 'Reciver City Empty', 'code'=>1) ;
            return json_encode($responseArray);
        }
    }
    
    function Tamex_label($tamex_slip_no, $apikey, $url) {

        $param_data = array(
            "apikey" => $apikey,
            "pack_awb" => $tamex_slip_no,
        );
        $all_param_data = json_encode($param_data);
        
        $headers = array(
            "Accept:application/json"
        );
  
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
            CURLOPT_SSL_VERIFYHOST=> 0,
            CURLOPT_SSL_VERIFYPEER=>0,
            CURLOPT_POSTFIELDS => $all_param_data,
            CURLOPT_HTTPHEADER => $headers,
        ));
  
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
  }

    public function SLSArray($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null,$box_pieces1 = null,$c_id=null,$super_id=null) 
    {

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $sender_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $sender_address = $ShipArr['sender_address'];
            $sender_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }
        
        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'sls',$super_id);

        $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
        $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
        $api_url = trim($counrierArr['api_url'])."create";
        $api_key = $counrierArr['auth_token'];
        $sender_city = $receiver_city = 'Riyadh';
            
            if (empty($box_pieces1)) {
                $box_pieces = 1;
            } else {
                $box_pieces = $box_pieces1;
            }
            
            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }
            
            if ($ShipArr['pay_mode'] == 'COD') {
                $cod_amount = $ShipArr['total_cod_amt'];
            } elseif ($ShipArr['pay_mode'] == 'CC') {
                $cod_amount = 0;
            }
            if(empty($complete_sku)){
                $complete_sku = $ShipArr['status_description'];

            }else {
                $complete_sku =  $complete_sku;
            }
            
           
            $details = array(
                'account_number'=>$sellername,
                'requested_by'=>$sellername,
                'collection_name'=> $sellername,//$ShipArr['sender_name'],
                'collection_contact'=>'Majd',
                'collection_street1'=> $sender_address,
                'collection_street2'=> $sender_address,
                'collection_city'=>$sender_city,
                'collection_country'=> 'Saudi Arabia',
                'collection_phone'=> $sender_phone,//$ShipArr['sender_phone'],
                'collection_email'=> $sender_email,//$ShipArr['sender_email'],
                'api_token'=>$api_key,
                'price_set_name'=>$ShipArr['mode'],
                'description'=>$complete_sku,
                'comments'=>'',
                'quantity'=>$box_pieces,
                'declared_value'=>$cod_amount,
                'weight'=>$weight,
                'delivery_name'=>$ShipArr['reciever_name'],
                'delivery_contact'=>$ShipArr['reciever_name'],
                'delivery_street1'=>$ShipArr['reciever_address'],
                'delivery_street2'=>$ShipArr['reciever_address'],
                'delivery_city'=>$receiver_city,
                'delivery_postal_code'=>'0',
                'delivery_country'=>'SA',
                'delivery_phone'=>$Receiver_phone = $ShipArr['reciever_phone'],
                'delivery_email'=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                'delivery_location_w3w'=>'bikers.exam.boots',
                'delivery_location_lat'=>$lat,
                'delivery_location_lng'=>$lang,
                'cod_amount'=>$cod_amount,
                'order_id'=>$ShipArr['slip_no'],
                'order_type'=>'forward',
                'reference_number'=>$ShipArr['slip_no']

                );
                $json_final_data = json_encode($details);
            
        if(!empty($receiver_city)){
              $curl = curl_init();

              curl_setopt_array($curl, array(
              CURLOPT_URL => $api_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $json_final_data,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: XSRF-TOKEN=eyJpdiI6InZraHl5QmJqZ1dFY1lzVis0S2hBVmc9PSIsInZhbHVlIjoiXC90UzlpdGxMdk16NjR6V0dqT2lcL2JBdHphb01Vbm55Z3ZtRFBJWjVjY1wvS0FhWmhGQXZaeDBmZWtwUkxpUTJpTnBPYkpzeW52ZjRNU0YweU9VMGFhanc9PSIsIm1hYyI6ImJhZjY4ZThiZmEyMjNkMWQzYjE0NTA4NDExZTZlODg1ODYwMDUwMGE5NzA4MzQyM2NlNjNiY2Q4MjZiYWZlNmQifQ%3D%3D; laravel_session=4a8d823e4c952a4c3e4a95e0ca2c9b5e4a1b2fd9'
            ),));

            $response = curl_exec($curl);
            curl_close($curl);
        }
        else 
        {
            $response = array('message'=> 
                             array('0' => 'The collection city field is required.')
                        ); 
            $response = json_encode($response);  
        }                            
            // echo "response ". $response; 
        $responseArray = json_decode($response, true); 
        $logresponse = json_encode($response);
        $successres = $responseArray['status'];
         
        if ($successres == 1) 
        {
            $successstatus = "Success";
        } else {
            $successstatus = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_data);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_data);
        }

       // $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_data);
        return $responseArray;
    }

    public function SLS_label($client_awb = null,$counrierArr= null) {
        $api_url=$counrierArr['api_url']."waybill?api_token=".$counrierArr['auth_token'];
        $param= array( 'api_token'=>  $counrierArr['auth_token'],
                     'tracking_number'=>$client_awb,
                    );
        $dataJson =json_encode($param);

        $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL =>$api_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_POSTFIELDS =>$dataJson,
                 CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: XSRF-TOKEN=eyJpdiI6InZraHl5QmJqZ1dFY1lzVis0S2hBVmc9PSIsInZhbHVlIjoiXC90UzlpdGxMdk16NjR6V0dqT2lcL2JBdHphb01Vbm55Z3ZtRFBJWjVjY1wvS0FhWmhGQXZaeDBmZWtwUkxpUTJpTnBPYkpzeW52ZjRNU0YweU9VMGFhanc9PSIsIm1hYyI6ImJhZjY4ZThiZmEyMjNkMWQzYjE0NTA4NDExZTZlODg1ODYwMDUwMGE5NzA4MzQyM2NlNjNiY2Q4MjZiYWZlNmQifQ%3D%3D; laravel_session=4a8d823e4c952a4c3e4a95e0ca2c9b5e4a1b2fd9'
            ),
        ));

            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
    }
    public function FedEX($sellername = null ,array $ShipArr, array $counrierArr, $complete_sku = null,$box_pieces1 = null,$c_id=null,$super_id=null){
        
            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = $ShipArr['sender_address'];
                $senderphone = $ShipArr['sender_phone'];
                $senderemail = $ShipArr['sender_email'];
            }
            
            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }



            $sender_city_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'fedex_city_code', $super_id);
            $sendercity = getdestinationfieldshow_auto_array($ShipArr['origin'], 'fedex_city', $super_id);
            $receiver_city_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'fedex_city_code',$super_id);
            $receivercity = getdestinationfieldshow_auto_array($ShipArr['destination'], 'city',$super_id);
            $currency = site_configTable("default_currency");//"EGP"; 
            
            $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);
            
            $api_url = ($counrierArr['api_url'])."CreateAirwayBill";
           
            if (empty($box_pieces1)) {
                $box_pieces = 1;
            } else {
                $box_pieces = $box_pieces1;
            }
            
            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }
            
            if ($ShipArr['pay_mode'] == 'COD') {
                $cod_amount = $ShipArr['total_cod_amt'];
            } elseif ($ShipArr['pay_mode'] == 'CC') {
                $cod_amount = 0;
            }
            
                $details= array(
                "UserName"=>$counrierArr['user_name'],
                "Password"=> $counrierArr['password'],
                "AccountNo"=> $counrierArr['courier_account_no'],
                "AirwayBillData"=> array(
                "AirWayBillCreatedBy"=>$sellername,
                "CODAmount" =>$cod_amount ,
                "CODCurrency"=>$currency,
                "Destination"=>$receiver_city_code,
                "DutyConsigneePay" =>0,
                "GoodsDescription"=>$complete_sku,
                "NumberofPeices" =>$box_pieces,
                "Origin"=>$sender_city_code,
                "ProductType"=>"FRE",
                "ReceiversAddress1"=>$ShipArr['reciever_address'],
                "ReceiversAddress2"=>$ShipArr['reciever_address'],
                "ReceiversCity"=>$receivercity,
                "ReceiversCompany"=>$ShipArr['reciever_name'],
                "ReceiversContactPerson"=>$ShipArr['reciever_name'],
                "ReceiversCountry"=>'Egypt',
                "ReceiversEmail"=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                "ReceiversGeoLocation"=>"",
                "ReceiversMobile"=>$ShipArr['reciever_phone'],
                "ReceiversPhone"=>$ShipArr['reciever_phone'],
                "ReceiversPinCode"=>"",
                "ReceiversProvince"=>"",
                "ReceiversSubCity"=>"",
                "SendersAddress1"=>$sender_address,
                "SendersAddress2"=>$sender_address,
                "SendersCity"=>$sendercity,
                "SendersCompany"=>$sellername,
                "SendersContactPerson"=>$sellername,
                "SendersCountry"=>'Egypt',
                "SendersEmail"=>$senderemail,
                "SendersGeoLocation"=>"",
                "SendersMobile"=>$senderphone,
                "SendersPhone"=>$senderphone,
                "SendersPinCode"=>"",
                "SendersSubCity"=>$sender_country_code,//$sender_city,
                "ServiceType"=>"FRG",
                "ShipmentDimension"=>"",
                "ShipmentInvoiceCurrency"=>$currency,
                "ShipmentInvoiceValue" =>0,
                "ShipperReference"=>$ShipArr['slip_no'],
                "ShipperVatAccount"=>"",
                "SpecialInstruction"=>"",
                "Weight" =>$weight
                ));
               $details_encode= json_encode($details);
               //echo $details_encode;die;
               //print_r($details);die;
               if(!empty($receiver_city_code)){
                

                $url =  "http://fastcoo.net/fastcoo-tech/fs_files/fedex_api.php";
                $Allarra = array('api_url' => $api_url,'data'=>$details_encode,"action"=>"shipment");
                $dataJson = json_encode($Allarra);
                
                $headers = array("Content-type: application/json");
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
                
                $response = curl_exec($ch);
                //echo $response;die;
                curl_close($ch);

               
                }
        else 
        {
           
            $response = array('Description'=> 'The Receivers City field is required.'); 
            $response = json_encode($response);  
        }      
                  
                
                $responseArray = json_decode($response, true);
                //print_r($responseArray);die;
                $logresponse = json_encode($response);
                $successres = $responseArray['Code'];

                if ($successres == 1) 
                {
                    $successstatus = "Success";
                } else {
                    $successstatus = "Fail";
                }

                if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$details_encode);
                }else{
                    
                    $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$details_encode);
                }

                return $responseArray;
            }
            
        public function FedEX_label($client_awb = null,$counrierArr= null,$ShipArr=null){
            
            $api_url = ($counrierArr['api_url'])."AirwayBillPDFFormat";
            
            $details= array(
                
                        "AccountNo"=>$counrierArr['courier_account_no'],
                        "AirwayBillNumber"=> $client_awb,
                        "Country"=>"SA",
                        "Password"=> $counrierArr['password'],
                        "RequestUser"=>$ShipArr['sender_name'],
                        "UserName"=>$counrierArr['user_name']
                
            );
            $label_details = json_encode($details);

            $url =  "http://fastcoo.net/fastcoo-tech/fs_files/fedex_api.php";
            $Allarra = array('api_url' => $api_url,'data'=>$label_details,"action"=>"label");
            $dataJson = json_encode($Allarra);
            
            $headers = array("Content-type: application/json");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
            
            $response = curl_exec($ch);
            //echo $response;die;
            curl_close($ch);
            $response_label = json_decode($response, true);
            return  $response_label;


                // $curl = curl_init();

                // curl_setopt_array($curl, array(
                //   CURLOPT_URL => $api_url,
                //   CURLOPT_RETURNTRANSFER => true,
                //   CURLOPT_ENCODING => '',
                //   CURLOPT_MAXREDIRS => 10,
                //   CURLOPT_TIMEOUT => 0,
                //   CURLOPT_FOLLOWLOCATION => true,
                //   CURLOPT_SSL_VERIFYHOST =>false,
                //   CURLOPT_SSL_VERIFYPEER => false,
                //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //   CURLOPT_CUSTOMREQUEST => 'POST',
                //   CURLOPT_POSTFIELDS =>$label_details,
                //   CURLOPT_HTTPHEADER => array(
                //     'Content-Type: application/json'
                //   ),
                // ));

                // $response = curl_exec($curl);
                

                // curl_close($curl);
                // $response_label = json_decode($response, true);
                // return  $response_label;
    }
    public function Moments_auth($counrierArr=null){
      
        $param= array(  
                        "client_secret"=>$counrierArr['password'],
                        "client_id"=> $counrierArr['courier_account_no'],
                        "username"=>$counrierArr['user_name'],
                        "password"=>$counrierArr['password'] 
                    );
        $dataJson =json_encode($param);
      
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $counrierArr['api_url']."authorize",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$dataJson,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));

            $Auth_response = curl_exec($curl);
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
            //print_r($responseArray);die;
    
            $Auth_token = $responseArray['access_token'];
            //print_r($Auth_token);die;
             return $Auth_token;
    
    }
     public function MomentsArray($sellername = null ,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,$complete_sku=null,$super_id) 
    {
      
            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = $ShipArr['sender_address'];
                $senderphone = $ShipArr['sender_phone'];
                $senderemail = $ShipArr['sender_email'];
            }
            
            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }            
           
           
            $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city_code', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'momentsKsa_city',$super_id);
            $API_URL = $counrierArr['api_url'] . "shipment/create";
            $currency = "SAR";
                
                if (empty($box_pieces1)) {
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }

                if($ShipArr['pay_mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['pay_mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }
                

    
            $sender_data = array(
                        "name"=>$sellername,
                        "country_code"=> "SA",
                        "city_code"=> $sender_city,
                        "address"=>$sender_address,
                        "phone"=>$senderphone,
                        "email"=> $senderemail
                    );
                  
    
                $receiver_data = array(
                        "name"=>$ShipArr['reciever_name'],
                        "country_code"=> "SA",
                        "city_code"=> $receiver_city,
                        "address"=>  $ShipArr['reciever_address'],
                        "zip_code"=>$ShipArr['reciever_zip'],
                        "phone"=> $ShipArr['reciever_phone'],
                        "email"=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
                    );
    
    
           
            $details = array(
                'receiver' => $receiver_data,
                'sender' => $sender_data,
                "reference"=>  $ShipArr['slip_no'],
                "pick_date"=> "",
                "pickup_time"=> "",
                "product_type"=> "104",
                "payment_mode"=> $ShipArr['mode'],
                "parcel_quantity"=> $box_pieces,
                "parcel_weight"=> $weight,
                "service_id"=> "1",
                "description"=> $complete_sku,
                "sku"=> $complete_sku,
                "weight_total"=> $weight,
                "total_cod_amount"=> $cod_amount
            );

          $json_final_date = json_encode($details);
          //echo $json_final_date;die;
             //print_r($json_final_date);  die;
               $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $API_URL ,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$json_final_date,
                  CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer ' .$Auth_token,
                    'Content-Type: application/json'
                  ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            
            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;
            $logresponse =   json_encode($response);  
            $successres = $responseArray['errors'];
            
            if (empty($successres)) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }
    public function Postagexp_auth($counrierArr=null){
      
        $param= array(  
                        "client_secret"=>$counrierArr['password'],
                        "client_id"=> $counrierArr['courier_account_no'],
                        "username"=>$counrierArr['user_name'],
                        "password"=>$counrierArr['password'] 
                    );
        $dataJson =json_encode($param);
      
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $counrierArr['api_url']."authorize",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$dataJson,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));

            $Auth_response = curl_exec($curl);
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
           // print_r($responseArray);die;
    
            $Auth_token = $responseArray['access_token'];
            //print_r($Auth_token);die;
             return $Auth_token;
    
    }
     public function PostagexpArray($sellername = null ,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,$complete_sku=null,$super_id= null) 
    {
            $sender_default_city = Getselletdetails_new($super_id);
 
            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = $ShipArr['sender_address'];
                $senderphone = $ShipArr['sender_phone'];
                $senderemail = $ShipArr['sender_email'];
            }
            
            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }            
         
            $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city_code', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'Postagexp_city',$super_id);
            $API_URL = $counrierArr['api_url'] . "shipment/create";
              
                if (empty($box_pieces1)) {
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }

                if($ShipArr['pay_mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['pay_mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

                if(empty($complete_sku)){
                 $complete_sku = $ShipArr['status_description'];

                }else {
                    $complete_sku =  $complete_sku;
                }
    
            $sender_data = array(
                        "name"=>$sellername,
                        "country_code"=> "SA",
                        "city_code"=> $sender_city,
                        "address"=>  $sender_address,
                        "phone"=> $senderphone,
                        "email"=>  $senderemail
                    );
                  
    
                $receiver_data = array(
                        "name"=>$ShipArr['reciever_name'],
                        "country_code"=> "SA",
                        "city_code"=> $receiver_city,
                        "address"=>  $ShipArr['reciever_address'],
                        "zip_code"=>$ShipArr['reciever_zip'],
                        "phone"=> $ShipArr['reciever_phone'],
                        "email"=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
                    );
    
    
           
            $details = array(
                'receiver' => $receiver_data,
                'sender' => $sender_data,
                "reference"=>  $ShipArr['slip_no'],
                "pick_date"=> "",
                "pickup_time"=> "",
                "product_type"=> "104",
                "payment_mode"=> $ShipArr['mode'],
                "parcel_quantity"=> $box_pieces,
                "parcel_weight"=> $weight,
                "service_id"=> "2",
                "description"=> $complete_sku,
                "sku"=> $complete_sku,
                "weight_total"=> $weight,
                "total_cod_amount"=> $cod_amount
            );

            $json_final_date = json_encode($details);
            // print_r($json_final_date);  die;
               $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => $API_URL ,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$json_final_date,
                  CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Authorization: Bearer ' .$Auth_token,
                    'Content-Type: application/json'
                  ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
            
            $responseArray = json_decode($response, TRUE);
            //print "<pre>"; print_r($responseArray);die;
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['errors'];
            //print_r($successres);die;
    
             if (empty($successres)) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }
    public function SMSAEgyptArray($sellername = null ,$ShipArr, $counrierArr, $complete_sku,$box_pieces1,$c_id,$super_id) {
           
            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
                $sender_address = $ShipArr['sender_address'];
                $senderphone = $ShipArr['sender_phone'];
                $senderemail = $ShipArr['sender_email'];
            }
            
            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }            

            $country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
            $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'smsa_egypt_city',$super_id);
            $currency_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'currency',$super_id);
        
            $declared_charge = $ShipArr['total_cod_amt'];
            $cod_amount = $ShipArr['total_cod_amt'];

            $rcountry_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code',$super_id);  
            $scountry_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code',$super_id);

        if ($ShipArr['pay_mode'] == 'COD') {
            $codValue = $cod_amount;
        } else {
            $codValue = 0;
        }
        if ($complete_sku == '') {
            $complete_sku = 'Goods';
        }
               
        if(empty($box_pieces1))
        {
            $box_pieces = 1;
        }
        else
        {  $box_pieces = $box_pieces1 ; 
        }

        if ($ShipArr['weight'] >= 0  && $ShipArr['weight'] <= 0.49) {
            $weight = 0.50;
        }else 
        {   
                $weight = round($ShipArr['weight']);
        }
       
        $api_url = $counrierArr['api_url'];

        $receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';
       
    $SMSAXML = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <addShip xmlns="http://track.smsaexpress.com/secom/SMSAWebserviceIntl">
                                        <passKey>' . $counrierArr['auth_token'] . '</passKey>
                                        <refNo>' . $ShipArr['slip_no'] . '</refNo>
                                        <sentDate>' . date('d/m/Y') . '</sentDate>
                                        <idNo>' . $ShipArr['booking_id'] . '</idNo>
                                        <cName>' . $ShipArr['reciever_name'] . '</cName>
                                        <cName>' . $ShipArr['reciever_name'] . '</cName>
                                        <cntry>' . $rcountry_code . '</cntry>
                                        <cCity>' . $receiver_city . '</cCity>
                                        <cZip>' . $ShipArr['sender_zip'] . '</cZip>
                                        <cPOBox>' . $box_pieces . '</cPOBox>
                                        <cMobile>' . $ShipArr['reciever_phone'] . '</cMobile>
                                        <cTel1>' . $ShipArr['reciever_phone'] . '</cTel1>
                                        <cTel2>' . $ShipArr['reciever_phone'] . '</cTel2>
                                        <cAddr1>' . $ShipArr['reciever_address'] . '</cAddr1>
                                        <cAddr2></cAddr2>
                                        <shipType>DLV</shipType>
                                        <PCs>' . $box_pieces . '</PCs>
                                        <cEmail>' . $receiver_email . '</cEmail>
                                        <carrValue>2</carrValue>
                                        <carrCurr>2</carrCurr>
                                        <codAmt>' . $codValue . '</codAmt>
                                        <weight>' . $weight. '</weight>
                                        <custVal></custVal>
                                        <custCurr>'.$currency_code .'</custCurr>
                                        <insrAmt></insrAmt>
                                        <insrCurr></insrCurr>
                                        <itemDesc>' .  $complete_sku . '</itemDesc>
                                        <sName>' .$sellername. '</sName>
                                        <sContact>' . $sellername . '</sContact>
                                        <sAddr1>' .$sender_address . '</sAddr1>
                                        <sAddr2>' .$sender_address . '</sAddr2>
                                        <sCity>' . $sender_city . '</sCity>
                                        <sPhone>' .$senderphone . '</sPhone>
                                        <sCntry>' . $scountry_code . '</sCntry>
                                        <prefDelvDate>'.date('d/m/Y').'</prefDelvDate>
                                        <gpsPoints></gpsPoints>
                                        <vatValue>0</vatValue>
                                        <harmCode></harmCode>
                                        </addShip>
                                        </soap:Body>
                                        </soap:Envelope>';
    
//echo $SMSAXML;die;

        $headers = array(
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: http://track.smsaexpress.com/secom/SMSAWebserviceIntl/addShip',
            "Content-length: " . strlen($SMSAXML),
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL =>$api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$SMSAXML,
          CURLOPT_HTTPHEADER => $headers
          ));

        $response = curl_exec($curl);
        curl_close($curl);
        $check = $response;
        $respon = trim($check);
        $respon = str_ireplace(array("soap:", "<?xml version=\"1.0\" encoding=\"utf-8\"?>"), "", $response);
        $xml2 = new SimpleXMLElement($respon);
        $again = $xml2;
        $a = array("qwb" => $again);

        //$complicated = ($a['qwb']->Body->addShipResponse->addShipResult[0]);
        $complicated = ($a['qwb']->Body->addShipMPSResponse->addShipMPSResult[0]);

        if (preg_match('/\bFailed\b/', $complicated)) {
            $ret = $complicated;

        } 
            if (empty($ret)) 
        {
            $successstatus  = "Success";
        }else {
            $successstatus  = "Fail";
        }


        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$SMSAXML);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$SMSAXML);
        }

        //$this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$SMSAXML);


        return $respon;
    }
    

    public function SamsaPrintLabel($SMSAAWB, $Passkey, $url) {
                            $xml = '<?xml version="1.0" encoding="utf-8"?>
                        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                          <soap:Body>
                          <getPDF xmlns="http://track.smsaexpress.com/secom/SMSAWebserviceIntl">
                          <awbNo>' .$SMSAAWB. '</awbNo>
                          <passKey>' .$Passkey. '</passKey>
                        </getPDF>
                      </soap:Body>
                    </soap:Envelope>';
        
        $headers = array(
            'SOAPAction: http://track.smsaexpress.com/secom/SMSAWebserviceIntl/getPDF',
            'Content-Type:  text/xml; charset=utf-8',
            'Content-length: ' . strlen($xml),
        );

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$xml,
              CURLOPT_HTTPHEADER =>$headers ));
        $response = trim(curl_exec($curl));
        return $response;
    }
    
    
    public function ccNamebYccid($cc_id=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        
             $this->db->where('cc_id', $cc_id);
        
        $this->db->where('deleted', 'N');
        $this->db->order_by('company');
        $this->db->select('company,company_type');
        $this->db->limit(1);
        $query = $this->db->get('courier_company');
       // echo $this->db->last_query();
        if ($query->num_rows() > 0) {

            $result= $query->row_array();
             return  $result;
        }
    }

    
    public function Bosta_token_api($courierData =array()){
        $apiUrl = $courierData['api_url'].'users/login';
        
        $curl = curl_init();
        
        $request_data = array(
            "email"=>$courierData['user_name'],
            "password"=>$courierData['password']
        );
        
        $json_request_params = json_encode($request_data);
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$json_request_params,
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responseData = json_decode($response,TRUE);
        return $responseData;
    }
    
    public function BostaArray($sellername=null, array $ShipArr, array $counrierArr,$token= null, $complete_sku = null, $box_pieces1=null,$c_id=null,$super_id=null){
        
            $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
            if($label_info_from == '1'){
                $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }else{
                $sellername =  $ShipArr['sender_name'];
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }
            
            if(!empty($ShipArr['label_sender_name'])){
                $sellername =  $ShipArr['label_sender_name'];    
                if($counrierArr['wharehouse_flag'] =='Y'){
                    $sellername = $sellername ." - ". site_configTable('company_name'); 
                }
            }



            $API_URL = $counrierArr['api_url'].'deliveries';
            //print "<pre>"; print_r($ShipArr);die;
            $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'bosta_city',$super_id);
            if(empty($receiver_city)){
                 $logresponse = "Receiver city empty";
                $successstatus  = "Fail";
                $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no']);
                return array("error"=>true,"data"=>array('message'=>'receiver city empty'));
            }

            if (empty($box_pieces1)) {
                $box_pieces = 1;
            } else {
                $box_pieces = $box_pieces1;
            }

            if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                $weight = 1;
           }else {
                $weight = $ShipArr['weight'];
           }


            if($ShipArr['pay_mode'] == "COD"){
                $cod_amount = $ShipArr['total_cod_amt'];
            }
            elseif ($ShipArr['pay_mode'] == 'CC'){
                $cod_amount = 0;
            }

            if(empty($complete_sku)){
             $complete_sku = $ShipArr['status_description'];

            }else {
                $complete_sku =  $complete_sku;
            }
        
        
        $reciver_phone_number = str_replace("+", "", $ShipArr['reciever_phone']);
        
        $request_params_array = array(
            "type"=> 10, //10: Delivery that has two endpoints (pickup and drop off), 15 : Delivery that has one endpoint (cash pickup point).
            "specs"=> array( 
                    //"size"=>"SMALL", 
                    "weight"=>$weight,
                    "packageDetails"=> array(
                            "itemsCount"=> $box_pieces, 
                            "document"=>"Small Box", 
                            "description"=> !empty($complete_sku)?$complete_sku:"test" 
                    ) 
            ), 
            "notes"=> $sellername,
            "cod"=> $cod_amount, 
            "dropOffAddress"=> array(
                "city"=> $receiver_city, 
                "zone"=> "", 
                "district"=> "", 
                "firstLine"=> $ShipArr['reciever_address'],
                "secondLine"=> "",
                "buildingNumber"=> "", 
                "floor"=>"", 
                "apartment"=> "" 
            ), 
            "businessReference"=> $ShipArr['slip_no'],
            "receiver"=> array(
                    "firstName"=> $ShipArr['reciever_name'],
                    "lastName"=> "",
                    "phone"=> "01".remove_phone_format($reciver_phone_number), // use  less then 13 chanracter for phone string
                    "email"=> !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
            )
        );
        
        $json_params = json_encode($request_params_array);
         //   echo $json_params;die;
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $API_URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$json_params,
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: '.$token.' ' 
          ),
        ));

        $response = curl_exec($curl);
//        /echo $response;die;
        curl_close($curl);
        $responseData = json_decode($response,TRUE);
        
        $logresponse =   json_encode($response);  
        
        $successres = $responseData['trackingNumber'];
        //print_r($successres);die;
        $errorFlag = false;
        if (!empty($successres)){
            $successstatus = "Success";
        }else{
            $successstatus = "Fail";
            $errorFlag = TRUE;
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_params);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_params);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_params);
        
        return array("error"=>$errorFlag,'message'=>'','data'=>$responseData);
    }
    
    
    public function Bosta_Label_api(array $counrierArr, $token = null ,$trackingNumber=null){
        $API_URL = $counrierArr['api_url']."deliveries/awb?ids=".$trackingNumber;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $API_URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: '.$token.' ' 
          ),
        ));
        
        $response = curl_exec($curl);

        curl_close($curl);
        $responseData = json_decode($response,TRUE);
        return $responseData;

    }

    public function FDA_auth($counrierArr=array())
    {
        $param= array(  'username'=> $counrierArr['user_name'],
                        'password'=> $counrierArr['password'],
                        'remember_me'=>true
                    );
        $dataJson =json_encode($param);
        $api_url = $counrierArr['api_url'];
        $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url."v1/customer/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$dataJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
            ));
    
            $Auth_response = curl_exec($curl);
    
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
    
            $Auth_token = $responseArray['data']['id_token'];
            //print_r($Auth_token);die;
             return $Auth_token;
    
    }
    public function FDAArray($sellername = null ,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $super_id=null, $complete_sku = NULL) 
    {
        //$store_address = $ShipArr['sender_address'];
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'FDA_city', $super_id);
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'FDA_city',$super_id);
        $currency = site_configTable("default_currency");//"SAR";
       // $senderemail = $ShipArr['sender_email'];
        //$senderphone = $ShipArr['sender_phone'];

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }
        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        
      
        $API_URL = $counrierArr['api_url'] . "v2/customer/order";               
        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }
        
        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }


        if($ShipArr['pay_mode'] == "COD"){
            $pay_mode = "cash";
            $paid = FALSE;
            $cod_amount = $ShipArr['total_cod_amt'];
        }
        elseif ($ShipArr['pay_mode'] == 'CC'){
            $pay_mode = "credit_balance";
            $paid = TRUE;
            $cod_amount = 0;
        }               

        $sender_data = array(
            'address_type' => 'residential',
            'name' =>$sellername,
            'email' => $senderemail,
            'apartment'=> '',
            'building' => '',
            'street' => $store_address,
            "city" => array(
                "name" =>$sender_city
            ),
            "country" => array(
                "id" => 191
            ),                

            'phone' =>$senderphone,
        );
          
    
        $receiverdata = array(
        'address_type' => 'residential',
        'name'=> $ShipArr['reciever_name'],
        'street' => $ShipArr['reciever_address'],
        'city' => array(
                'name' => $receiver_city
            ),
            'phone' => $ShipArr['reciever_phone'],
            'landmark' => $ShipArr['reciever_address']);

        $dimensions = array(
            'weight' => $weight,
            'width' =>  '',
            'length' => '',
            'height' =>'' ,
            'unit' => '',
            'domestic' => true
        );

        $package_type = array(
            'courier_type' => 'EXPRESS_DELIVERY'
        );

        $charge_items[] = array(
            'paid' => $paid,
            'charge' => $cod_amount,
            'charge_type' => $ShipArr['mode']              
        );
    
        $details = array( 
            'sender_data' => $sender_data,
            'recipient_data' => $receiverdata,
            'dimensions' => $dimensions,
            'package_type' => $package_type,
            'charge_items' => $charge_items,
            'recipient_not_available' => 'do_not_deliver',
            'payment_type' => $pay_mode,
            'payer' => 'recipient',
            'parcel_value' => $cod_amount,
            'fragile' => true,
            'note' => $complete_sku,
            'piece_count' => $box_pieces,
            'force_create' => true,
            "reference_id" => $ShipArr['slip_no']
        );

        $json_final_date = json_encode($details);
           //echo $json_final_date;  die;
                 
        if (empty($receiver_city))
        {
             $response = array('message'=> 'Receiver city empty' ); 
             $response = json_encode($response);
        }
        else {
            $curl = curl_init();    
            curl_setopt_array($curl, array(
                CURLOPT_URL => $API_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $json_final_date,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'Authorization:Bearer ' .$Auth_token),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
        }

        $responseArray = json_decode($response, true);
        //print_r($responseArray);die;
   
        $logresponse =   json_encode($response);  
        
        $successres = $responseArray['status'];
        //print_r($successres);die;

        if ($successres == 'success') 
        {
            $successstatus = "Success";
        } else {
            $successstatus = "Fail";
        }


        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
        }else{
            
            $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
        }

        //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
        return $responseArray;
    }   
        
        
    public function FDA_label($client_awb = null,$counrierArr= null, $Auth_token=null) 
    {
        
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url']."v1/customer/orders/airwaybill_mini?ids=&order_numbers=".$client_awb,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
    
    
    
            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
    }

        public function MICGO_AUTH(array $counrierArr)
    {
        $client_id = $counrierArr['user_name'];
        $client_secret = $counrierArr['password'];

        $ch = curl_init();
        
        if($counrierArr['type'] == 'test'){
            $api_url = "https://auth-dev.525k.io/oauth2/token";
        }else{
            $api_url = "https://auth-prod.525k.io/oauth2/token";
        }

        curl_setopt_array($ch, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYHOST =>false,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=".$client_id."&client_secret=".$client_secret,
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
          ),
        ));
        $Auth_response = curl_exec($ch);
        curl_close($ch);
        $responseArray = json_decode($Auth_response, true);
        //print_r($responseArray);die;
        $Auth_token = $responseArray['access_token'];
        // print_r($Auth_token);die;
        return $Auth_token;
    
    }
    public function MICGOarray($sellername = null, array $ShipArr, array $counrierArr, $complete_sku = null ,$c_id= null,$box_pieces1=null, $Auth_token= null,$super_id = null) 
    {   
                

                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $sender_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $sender_address = $ShipArr['sender_address'];
                    $senderphone = $ShipArr['sender_phone'];
                    $senderemail = $ShipArr['sender_email'];
                }
                
                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }

                
                $country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
                $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'MICGO_city',$super_id);
                
                $lat_D = getdestinationfieldshow_auto_array($ShipArr['destination'], 'latitute',$super_id);
                
                $lang_D= getdestinationfieldshow_auto_array($ShipArr['destination'], 'longitute',$super_id);
                $lat = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
                $lang = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
                $senderCountry = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country',$super_id);
                
                
                $currency = site_configTable('default_currency');
                $xapikey = $counrierArr['courier_pin_no'];
                $timestamp =  strtotime(date("Y-m-d H:i:s"));
                $shipperId = $counrierArr['courier_account_no'];
                $API_URL = $counrierArr['api_url'] .'shipments';
//                if($counrierArr['type'] == 'test'){
//                    $API_URL = $counrierArr['api_url'] .'shipments';
//                    
//                }else{
//                    //$API_URL = $counrierArr['api_url'] .'shipments/v2';
//                    $API_URL = $counrierArr['api_url'] .'shipments';
//                    //$API_URL = 'https://api.525k.io/api/shipments';
//                }
                
               
                
                if (empty($box_pieces1)) {
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }


                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "cash";
                    $paid = FALSE;
                    $cod_amount = $ShipArr['total_cod_amt'];
                    
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }
                
               
                $Receiver= array(
                        'address'=> $ShipArr['reciever_address'],
                        'address2'=> $ShipArr['reciever_address'],
                        'instructions'=>'notes for delivery 1',
                        'arrivalWindow'=> array(
                            'begin'=> $timestamp,
                            'end'=> $timestamp,
                            'excludeBegin'=> true,
                            'excludeEnd'=> true
                        ),
                        'city'=> $receiver_city,
                        'coordinate'=> array(
                            'latitude'=> $lat_D,
                            'longitude'=> $lang_D 
                        ),
                        'country'=> $country,
                        'email'=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                        'name'=>$ShipArr['reciever_name'],
                        'phone'=>$ShipArr['reciever_phone']
                        );
                
                        $sender=array(
                                'address'=>$sender_address,//$ShipArr['sender_address'],
                                'address2'=> '',
                                'arrivalWindow'=> array(
                                    'begin'=> $timestamp,
                                    'end'=> $timestamp,
                                    'excludeBegin'=> true,
                                    'excludeEnd'=> true
                            ),
                            'coordinate'=> array(
                                'latitude'=> $lat,
                                'longitude'=> $lang
                            ),
                            'city'=> $sender_city,
                            'country'=>$senderCountry,
                            'email'=> $senderemail,
                            'name'=> $sellername,
                            'phone'=> $senderphone
                        );
    
                        $details= array(
                        'autoAssignDrivers'=> false,
                        'requestedBy'=> $shipperId,
                        'shipments'=> array(
                        array(
                            'fullTruck'=> false,
                            'loadDescription'=>$complete_sku,
                            'codValue'=> array(
                            'amount'=> $cod_amount,
                            'currency'=> $currency
                        ),
                        'delivery'=> $Receiver,
                        'refId'=> $ShipArr['slip_no'],
                        'items'=> array(
                        array(
                            'dimensions'=> array(
                                'height'=> '',
                                'length'=> '',
                                'width'=> ''
                            ),
                        'refId'=>$ShipArr['slip_no'],
                        'weight'=> $weight,
                        'quantity'=> $box_pieces,
                        'stackable'=> true,
                        'type'=> !empty($ShipArr['pack_type'])?$ShipArr['pack_type']:'PALLET'
                        )
                        ),
                        'pickup'=> $sender,
                        'shipmentValue'=> array(
                        'amount'=> $cod_amount,
                        'currency'=>$currency 
                        )
                        )
                        ),
                        'shipperId'=>$shipperId
                        );
                        $json_final_date = json_encode($details);
                        //echo $json_final_date;  die;
              
            if (empty($receiver_city))
            {
                 $response = array('error'=>true,'message'=> 'Receiver city empty' ); 
                 $responsejson = json_encode($response);

                 if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $responsejson,"Fail", $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
                }else{
                    
                    $this->shipmentLog($c_id, $responsejson,"Fail", $ShipArr['slip_no'],$json_final_date);
                }
                 //$log = $this->shipmentLog($c_id, $responsejson ,"Fail", $ShipArr['slip_no'],$json_final_date);
                 return $response;
            }
            else {
                
                $curl = curl_init();
                
                curl_setopt_array($curl, array(
                  CURLOPT_URL =>$API_URL,
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => '',
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => 'POST',
                  CURLOPT_POSTFIELDS =>$json_final_date,
                  CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'x-api-key:' .$xapikey,
                    'Authorization:Bearer ' .$Auth_token),
                ));

                $response = curl_exec($curl);
                curl_close($curl);
            }
            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;
            $logresponse =   json_encode($response);  
            $successres = $responseArray['error'];
            //print_r($successres);die;
            if (empty($successres)) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }
    
    
    public function DOTSarray($sellername = null, $ShipArr = array(), $counrierArr = array(), $complete_sku= null,$c_id= null,$box_pieces1= null, $super_id = null){
        
                


                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = $ShipArr['sender_address'];
                    $senderphone = $ShipArr['sender_phone'];
                    $senderemail = $ShipArr['sender_email'];
                }
                
                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }                

                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'dots_city', $super_id);
                $sender_country_code = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country',$super_id);
                $sender_latitute = getdestinationfieldshow_auto_array($ShipArr['origin'], 'latitute',$super_id);
                $sender_longitute = getdestinationfieldshow_auto_array($ShipArr['origin'], 'longitute',$super_id);
                
                
                $receiver_country_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country',$super_id);
                $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'dots_city',$super_id);
                $receiver_latitute = getdestinationfieldshow_auto_array($ShipArr['destination'], 'latitute',$super_id);
                $receiver_longitute = getdestinationfieldshow_auto_array($ShipArr['destination'], 'longitute',$super_id);
                
                $currency = site_configTable('default_currency');        
        
                $API_URL = $counrierArr['api_url'] . "create_order/";
                
                if (empty($box_pieces1)) {
                    $box_pieces = 1;
                } else {
                    $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }


                if($ShipArr['pay_mode'] == "COD"){
                    $pay_mode = "cash";
                    $paid = FALSE;
                    $cod_amount = $ShipArr['total_cod_amt'];
                    
                }
                elseif ($ShipArr['pay_mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

               
                
            $sender_data = array(
                    'name'=>$sellername,
                    'country'=>$sender_country_code,
                    'city'=>$sender_city,
                    'region'=>'',
                    'street'=>$store_address,
                    'building'=>'',
                    'level'=>'',
                    'apartment_number'=>'',
                    'zip_code'=>'',
                    'latitude'=>$sender_latitute,
                    'longitude'=>$sender_longitute,
                    'phone'=>$senderphone,
                    'email'=>$senderemail,
                    'address_type'=>'',
                    'preferred_time'=>''
            );
            $receiverdata = array(
                    'name'=>$ShipArr['reciever_name'],
                    'country'=>$receiver_country_code,
                    'city'=>$receiver_city,
                    'region'=>'',
                    'street'=>$ShipArr['reciever_address'],
                    'building'=>'',
                    'level'=>'',
                    'apartment_number'=>'',
                    'zip_code'=>'',
                    'latitude'=>$receiver_latitute,
                    'longitude'=>$receiver_longitute,
                    'phone'=>$ShipArr['reciever_phone'],
                    'email'=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com',
                    'address_type'=>'residential',
                    'preferred_time'=>''
            );      
    
           
            
            $package_details = array(
                'type' => 'EXPRESS_DELIVERY',
                "collection_type"=> "VENDOR_WAREHOUSE",
                "collection_point"=> $sender_city,
                "expected_delivery_date"=> date('Y-m-d H:i:s'),
                "items_description"=> $complete_sku,
                "weight"=> $weight,
                "reference_no"=>$ShipArr['booking_id'],
                "shipping_note"=> $complete_sku,
                "number_packages"=> $box_pieces
            );
           
            $payment = array(
                'currency' => $currency,
                'cod_amount' => $cod_amount,
                'item_value' => $cod_amount,
                'freight_cost' => '0.00',
                'tax' => '0.00',
            );
    
            $details = array(
                'awb'=>$ShipArr['slip_no'],
                'from_address' => $sender_data,
                'to_address' => $receiverdata,
                'package_details' => $package_details,
                'payment' => $payment,
            );

            $json_final_date = json_encode($details);
            //echo $json_final_date;  die;
              
                      
            if (empty($receiver_city))
            {  
                $response = array('reason'=> 'Receiver city empty' ); 
                $logresponse = json_encode($response);

                if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                    $this->shipmentLogReverse($c_id, $logresponse,'Fail', $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
                }else{
                    
                    $this->shipmentLog($c_id, $logresponse,'Fail', $ShipArr['slip_no'],$json_final_date);
                }

                 //$this->shipmentLog($c_id, $logresponse,'Fail', $ShipArr['slip_no'],$json_final_date);
                 return $response;
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_HTTPAUTH=> CURLAUTH_BASIC,
                    CURLOPT_USERPWD=> "".$counrierArr['user_name'].":".$counrierArr['password']."",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        //'Accept: application/json',
                        //'Authorization:Bearer ' .$Auth_token
                        ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
            if ($successres == 'OK' && $responseArray['code'] == '200'){
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

           // $log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }
    
    public function BAWANI_AUTH($counrierArr = array()){

      
        $param= array(  'username'=>$counrierArr['user_name'],
                        'password'=>$counrierArr['password'],
                        'remember_me'=>true
                    );
        $dataJson =json_encode($param);
      
        $curl = curl_init();
        
            curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url']."v1/customer/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$dataJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
            ));
    
            $Auth_response = curl_exec($curl);
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
            $Auth_token = $responseArray['data']['id_token'];
            return $Auth_token;
    
    }

    public function BAWANIArray($sellername = null,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,  $complete_sku = null, $super_id= null) 
    {

                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'BAWANI_city', $super_id);
                $receiver_city= getdestinationfieldshow_auto_array($ShipArr['destination'], 'BAWANI_city', $super_id);                
                
                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = $ShipArr['sender_address'];
                    $senderphone = $ShipArr['sender_phone'];
                    $senderemail = $ShipArr['sender_email'];
                }
                
                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }                
                
                $API_URL = $counrierArr['api_url'] . "v2/customer/order";
                
                if (empty($box_pieces1)) {

                    $box_pieces = 1;
                } else {
                    $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }


               if($receiver_city == 'Riyadh'){
                   $courier_type= "SAME_DAY";
                }
                else{
                    $courier_type= "NEXT_DAY";
                }
                if($ShipArr['mode'] == "COD"){

                    //$pay_mode = "cash";
                    $pay_mode = "credit_balance"; //"cash";

                    $paid = FALSE;
                    $cod_amount = $ShipArr['total_cod_amt'];
                    
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    //$pay_mode = "cash";
                    $pay_mode = "credit_balance"; //"cash";

                    $paid = TRUE;
                    $cod_amount = 0;
                }

                $receiverdata = array(
                    'address_type' => 'business',
                    'name'=> $ShipArr['reciever_name'],
                    'street' => $ShipArr['reciever_address'],
                    'city' => array(
                            'name' => $receiver_city
                        ),
                    'phone' => $ShipArr['reciever_phone'],
                    'landmark' => $ShipArr['reciever_address']
                );
    

    
            $sender_data = array(
                    'address_type' => 'business',
                    'name' =>$sellername,
                    'email' => $senderemail,
                    'apartment'=> '',
                    'building' => '',
                    'street' => $store_address,
                    "city" => array(
                        "name" =>$sender_city
                    ),
                    "country" => array(
                        "id" => 191
                    ),
                        'phone' =>$senderphone,
                );
                  
    
               
            $dimensions = array(
                'weight' => $weight,
                'width' =>  '',
                'length' => '',
                'height' =>'' ,
                'unit' => '',
                'domestic' => true
            );


        $package_type = array(
                'courier_type' =>$courier_type

        );
        $charge_items[] = array(
            'payer' => 'recipient',
            'charge' => 0,
            'charge_type' => 'service_custom'
        );

        $details = array(
            'sender_data' => $sender_data,
            'recipient_data' => $receiverdata,
            'dimensions' => $dimensions,
            'package_type' => $package_type,
            'charge_items' => $charge_items,
            'recipient_not_available' => 'do_not_deliver',
            'payment_type' => $pay_mode,
            'payer' => 'recipient',
            'parcel_value' => $cod_amount,
            'fragile' => true,
            'note' => $complete_sku,
            'piece_count' => $box_pieces,
            'force_create' => true,
            "reference_id" => $ShipArr['slip_no']
        );

            $json_final_date = json_encode($details);
            //echo $json_final_date;  die;
              
                     
            if (empty($receiver_city))
            {
                 $response = array('message'=> 'Receiver city empty' ); 
                 $response = json_encode($response);
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
             if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
    }

    public function BAWANI_label($client_awb = null,$counrierArr= null, $Auth_token=null) {
        
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url']."v1/customer/orders/airwaybill_mini?ids=&order_numbers=".$client_awb,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
    
    
    
            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
    }
    
    public function shipox_auth(array $counrierArr)
    { 
        $user_name = $counrierArr['user_name'] ;
        $password =  $counrierArr['password'] ;
        $api_url = $counrierArr['api_url'];
        
        $param= array(  'username'=>$user_name,
                        'password'=>  $password,
                        'remember_me'=>true
                    );
         $dataJson =json_encode($param);
         
        $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $api_url."v1/customer/authenticate",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$dataJson,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
            ));
    
            $Auth_response = curl_exec($curl);
            
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
            $Auth_token = $responseArray['data']['id_token'];
            return $Auth_token;
    }
    
    public function lastpointArray($sellername= null, array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $super_id=null) 
    {
        
                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'lastpoint_city', $super_id);
                $receiver_city= getdestinationfieldshow_auto_array($ShipArr['destination'], 'lastpoint_city', $super_id);
                
                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = $ShipArr['sender_address'];
                    $senderphone = $ShipArr['sender_phone'];
                    $senderemail = $ShipArr['sender_email'];
                }
                
                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }                

                
                $API_URL = $counrierArr['api_url'] . "v2/customer/order";               
                $currency = "SAR";
                
                if (empty($box_pieces1)){
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }


                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

               
    
            $sender_data = array(
                'address_type' => 'residential',
                'name' =>$sellername,
                'email' => $senderemail,
                'apartment'=> 221,
                'building' => 'B',
                'street' => $store_address,
                "city" => array(
                    "name" =>$sender_city
                ),
                "country" => array(
                    "id" => 191
                ),
                
    
                    'phone' =>$senderphone,
                    );
                  
    
                $receiverdata = array(
                'address_type' => 'residential',
				'name'=> $ShipArr['reciever_name'],
                'street' => $ShipArr['reciever_address'],
                'city' => array(
                        'name' => $receiver_city
                    ),
                    'phone' => $ShipArr['reciever_phone'],
                    'landmark' => $ShipArr['reciever_address']);
    
    
            $dimensions = array(
                'weight' => $weight,
                'width' =>  '',
                'length' => '',
                'height' =>'' ,
                'unit' => '',
                'domestic' => true
            );
            $package_type = array(
                'courier_type' => 'NEXT_DAY_DELIVERY'
            );

            $charge_items[] = array(
                'paid' => $paid,
                'charge' => $cod_amount,
                'charge_type' => "COD"               
            );
    
            $details = array(
                'sender_data' => $sender_data,
                'recipient_data' => $receiverdata,
                'dimensions' => $dimensions,
                'package_type' => $package_type,
                'charge_items' => $charge_items,
                'recipient_not_available' => 'do_not_deliver',
                'payment_type' => $pay_mode,
                'payer' => 'sender',
                'parcel_value' => $cod_amount,
                'fragile' => true,
                'note' => 'mobile phone',
                'piece_count' => $box_pieces,
                'force_create' => true,
                "reference_id" => $ShipArr['slip_no']
            );

            $json_final_date = json_encode($details);
            //  echo "<pre>";  print_r($json_final_date);  die;
              
                     
            if (empty($receiver_city))
            {
                 $response = array('message'=> 'Receiver city empty' ); 
                 $response = json_encode($response);
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
             if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date );
            return $responseArray;
    }
    
        
        
    public function shipox_label($client_awb = null,$counrierArr= null, $Auth_token=null) 
    {
        
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => $counrierArr['api_url']."v1/customer/orders/airwaybill_mini?ids=&order_numbers=".$client_awb,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
    
    
    
            $label_response = curl_exec($curl);
            
            curl_close($curl);
            return  $label_response;
    } 
    
    public function LAFASTA_AUTH($username=null,$password=null, $apiurl=null){
       
        $apiurl = $apiurl."Login/GetAccessToken";
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiurl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'UserName='.$username.'&Password='.$password.'',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $responseArray = json_decode($response,TRUE);
        $token = '';
        if($responseArray['isSuccess']){
            $token = $responseArray['resultData']['access_token'];
        }
    
        return $token;
    }
    
    public function LAFASTA_Array($sellername= null, $ShipArr = array(), $counrierArr = array(), $Auth_token=null, $c_id=null, $box_pieces1=null, $complete_sku=null, $sku_data=array(), $super_id= null){
        
        $api_url = $counrierArr['api_url'].'V3/Store/Shipment';
        $receiver_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'lafasta_city',$super_id);
        $receiver_country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country', $super_id);
        
        $API_URL = $counrierArr['api_url'] . "create";
        $item_array = array();
        foreach ($sku_data as $key => $val) {
                $item_array[] = array('SKU'=>$sku_data[$key]['name'], 'Quantity'=>$sku_data[$key]['piece']);
                //$item_array[] = array('SKU'=>"K156", 'Quantity'=>$sku_data[$key]['piece']);
        }
        
        
        $box_pieces = empty($box_pieces)?1:$box_pieces;
        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        if(empty($complete_sku)){
            $complete_sku = "QTY-".$box_pieces; 

        }else {
            $complete_sku = $complete_sku;
        }
        if ($ShipArr['mode'] == 'COD') {
            $codValue = $ShipArr['total_cod_amt'];
            $pay_method = 4;
        } elseif ($ShipArr['mode'] == 'CC') {
            $codValue = 0;
            $pay_method = 1;
        }
        
        
        
        $json_array = array(
                        //"SandboxMode"=>false,
                        "CustomerName"=>$ShipArr['reciever_name'],
                        "CustomerPhoneNumber"=>$ShipArr['reciever_phone'],
                        "PreferredReceiptTime"=>"",
                        "PreferredDeliveryTime"=>date("Y/m/d h:m"),
                        "NotesFromStore"=>$complete_sku,
                        "PaymentMethodId"=>$pay_method,  // Prepaid =1 and Cash on delivery =4
                        "ShipmentContentValueSAR"=>$codValue,
                        "ShipmentContentTypeId"=>5, // Food =1, Clothes=2, Electonic Device=3 , Breakable Item=4 and Other = 5
                        "AddedServicesIds"=>array(6), // Packaging= 6 , Insurance=7, Heat preserve box=8 , Express shipping service= 13, Print shipping label service= 14
                        "API_Call_Source"=>"Diggpacks_MDoCzP4eB0coB6erN457DxmPssFivm7",
                        "CustomerLocation"=>array(
                            "Desciption"=>$ShipArr['reciever_address'],
                            //"Longitude"=>"name",
                            //"Latitude"=> "46.6067715",
                            //"GoogleMapsFullLink"=> "https://www.google.com/maps/place/",
                            //"CountryId"=> 1, // 1= SA
                            "CityAsString"=> $receiver_city,
                            //"CityId"=> 1 //Riyadh
                        ),
                         "ExternalStoreShipmentIdRef"=> $ShipArr['slip_no'],
                         "Currency"=>"SAR",
                         "UseQuickInventory"=>array(
                             "Items"=>$item_array
//                                 array(
//                                        "SKU"=>'K156',
//                                        "Quantity"=>$box_pieces
//                                      )  
                                 
                         )
                        
                    );
        
        if(!empty($receiver_city)){
            $json_string = json_encode($json_array);
            //echo $json_string;die;
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $api_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$json_string,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$Auth_token
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $responseData = json_decode($response,TRUE);
            if($responseData['isSuccess']){
                $successstatus = 'Success';
            }else{
                $successstatus = 'Fail';
            }
        }else{
            $successstatus = 'Fail';
            $responseData = array('isSuccess'=>'false','messageEn'=>'Receiver City Not Found.');
            $response = json_encode($responseData);
        }
        
        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_string);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        }

        //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        return $responseData;
        
    }
    
    
    public function LAFASTA_Label($frwd_awb = null, $auth_token = null, $api_url = null){
        
        $api_url = $api_url.'V3/Store/Shipment/ShippingLabel/PDF/Url/'.$frwd_awb;
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$auth_token
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $responseData = json_decode($response,TRUE);
        //print "<pre>"; print_r($responseData);die;
        return $responseData;
        
        
    }
    
    public function SMB_Array($sellername=null, $ShipArr = array(), $counrierArr = array(), $c_id=null, $box_pieces1=null, $complete_sku=null, $super_id = null,$service_type=null){
         
        $api_url = $counrierArr['api_url'].'shipments';

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $store_name = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $store_name = $store_name ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $store_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $store_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $store_name =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $store_name = $store_name ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $store_phone = $ShipArr['sender_phone'];
            $store_email = $ShipArr['sender_email'];
        }
        
        if(!empty($ShipArr['label_sender_name'])){
            $store_name =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $store_name = $store_name ." - ". site_configTable('company_name'); 
            }
        }        
        
        $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'city', $super_id);
        
        $sender_country = getdestinationfieldshow_auto_array($ShipArr['origin'], 'country_code', $super_id);
        
        $sender_array = array('street'=>$store_address,'name'=>$store_name,'email'=>$store_email,'city'=>$sender_city,'country'=>$sender_country,'phone'=>$store_phone); 
        
        $sender_address_id = $this->get_smb_address_id($sender_array,$counrierArr);
        
        $reciever_city = getdestinationfieldshow_auto_array($ShipArr['destination'], 'smb_city', $super_id);
        
        $receiver_country = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code', $super_id);
        $reciever_name = $ShipArr['reciever_name']; 
        $reciever_phone = $ShipArr['reciever_phone'];
        $reciever_street = $ShipArr['reciever_address'];
        $receiver_email = !empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com';        
        
        
        $receiver_array = array('street'=>$reciever_street,'name'=>$reciever_name,'email'=>$receiver_email,'city'=>$reciever_city,'country'=>$receiver_country,'phone'=>$reciever_phone);
    //  echo "<pre>";    print_r($receiver_array);  die; 
         $receiver_address_id = $this->get_smb_address_id($receiver_array, $counrierArr);

        // echo "<pre> edfsdfs ".$receiver_address_id ;    print_r($receiver_address_id);  die; 
        
        
        $box_pieces = empty($box_pieces1)?1:$box_pieces1;
        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        if(empty($complete_sku)){
            $complete_sku = "QTY-".$box_pieces;     

        }else {
            $complete_sku = $complete_sku;
        }
        if ($ShipArr['mode'] == 'COD') {
            $codValue = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['mode'] == 'CC') {
            $codValue = $ShipArr['total_cod_amt']; //1;
        }
        $parcel_array = array();
        for($i=1;$i<=$box_pieces;$i++){
            $parcel_array[] = array(
                "weight"=>$weight,
                "qty" => $i
            );
        }
        
        $data_array = array(
                            'reference'=>$ShipArr['slip_no'],
                            'from_address'=>$sender_address_id,
                            'to_address'=>$receiver_address_id,
                            'src_location'=>$sender_city,
                            'dest_location'=>$reciever_city,
                            'package_type'=>'PRC',
                            'currency'=>'SAR',
                            'cod'=>round($codValue),
                            'cod_currency'=>'SAR',
                            'content'=>$complete_sku,
                            'parcels'=>$parcel_array,//array(array('weight'=>$weight)),
                            'service' => $service_type,
                            'invoice_no'=>$ShipArr['slip_no'],
                            'status'=>'',
                            'order_date'=>date('Y-m-d h:i'),
                            'origin_country'=>$sender_country
                        );
                        //  echo "<pre> edfsdfs " ;   
                        //  print_r($data_array);  die; 
        // echo $reciever_city;
        
        if(!empty($reciever_city)){
            $json_string = json_encode($data_array);
            // echo "bdfjhgsjhdf"; 
            // echo $json_string;  
            // echo "Api url = ". $api_url; //die ;
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $api_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$json_string,
              CURLOPT_HTTPHEADER => array(
                'X-API-AUTH-TOKEN: '.$counrierArr['auth_token'],
                'Content-Type: application/json'
              ),
            ));  

              $response = curl_exec($curl);
       
            curl_close($curl);

            $responseData = json_decode($response,TRUE);
            
            if(!empty($responseData['data']['id'])){
                $successstatus = 'Success';
                $responseData = array('isSuccess'=>'true','messageEn'=>'','orderID'=>$responseData['data']['id']);
            }else{
                $responseData = array('isSuccess'=>'false','messageEn'=>json_encode($responseData['error']));
                $successstatus = 'Fail';
            }
        }else{
            $successstatus = 'Fail';
            $responseData = array('isSuccess'=>'false','messageEn'=>'Receiver City Not Found.');
            $response = json_encode($responseData);
        }
        
        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_string);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        }

       // $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        return $responseData;
        
    }
    
    public function get_smb_address_id($address_arr = array(), $counrierArr = array()){
        
        $api_url = $counrierArr['api_url'].'addresses';
        $address_details  = array(
                              'name'=>$address_arr['name'],
                              'email'=>$address_arr['email'],
                              'city'=>$address_arr['city'],
                              'country'=>$address_arr['country'],
                              'phone'=>$address_arr['phone'],
                              'street'=>$address_arr['street']
                            );
        return $address_details;                            
        $json_string = json_encode($address_details);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$json_string,
          CURLOPT_HTTPHEADER => array(
            'X-API-AUTH-TOKEN: '.$counrierArr['auth_token'],
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);  

        curl_close($curl);
        $response_array = json_decode($response,true);
        // print_r('Response from API : '.print_r($response)); die; 
        // echo $response_array['data']['id']; die; 
        if(!empty($response_array['data'])){
            return $response_array['data']['id'];
        }
        // return  $response_array['data']['id'];
        return 0;
        
    }
    
    public function SMB_confirm($orderID = null, $counrierArr= array(),$slip_no=null,$c_id=null){
         $api_url = $counrierArr['api_url'].'shipments/'.$orderID.'/confirm';
        $curl = curl_init();
        $json_string = json_encode( $api_url);
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'X-API-AUTH-TOKEN: '.$counrierArr['auth_token'],
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $responseArray = json_decode($response,TRUE);
        if(!empty($responseArray['data']['barcode'])){
            $successstatus = "Confirm Success";
        }else{
            $successstatus = "Confirm Fail";
        }
        $this->shipmentLog($c_id, $response,$successstatus, $slip_no,$json_string);
        return $responseArray;
    }
    public function SMB_Label($orderID = null, $counrierArr= array()){

          $api_url = $counrierArr['api_url'].'web/media/shipments/'.$orderID.'/print_label'; 
    //   echo $counrierArr['auth_token'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'X-API-AUTH-TOKEN: '.$counrierArr['auth_token'],
          ),
        ));

          $response = curl_exec($curl);  
        //   echo  $response ; die; 

        curl_close($curl);
        
        return $response;
    }
    
     public function AJA_AUTH($user_name= null,$password= null,$api_url= null){
            $api_url = $api_url.'account/Authenticate';
            $param= array(  'UsernameOrEmailAddress'=>$user_name,
                            'Password'=>  $password,
                        );
            
            $dataJson =json_encode($param);
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $api_url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$dataJson,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: Abp.Localization.CultureName=ar-EG'
              ),
            ));

            $Auth_response = curl_exec($curl);
            curl_close($curl);
            $responseArray = json_decode($Auth_response, true);
            return $responseArray;        
        
    }
    
    public function AJAArray($sellername= null,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null,  $complete_sku = null, $super_id=null){
        
                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'AJA_city', $super_id);
                $receiver_city= getdestinationfieldshow_auto_array($ShipArr['destination'], 'AJA_city', $super_id);
                
                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                }else{
                    $senderphone = $ShipArr['sender_phone'];
                }                

                $API_URL = $counrierArr['api_url'] . "account/SendShipment";


                if (empty($box_pieces1)) {
                    $box_pieces = 1;
                } else {
                    $box_pieces = $box_pieces1;
                }

                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }

               
                if($ShipArr['mode'] == "COD"){
                    $tranceportationsMode = 4;
                    $cod_amount = $ShipArr['total_cod_amt'];

                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $tranceportationsMode = 2;
                    $cod_amount = 0;
                }

                

            $details = array(
                'SenderCityId' => $sender_city, // Riyadh: 2
                'SenderTelephone' => $senderphone,
                'SenderMobile' => $senderphone,
                'RecipiantName' => $ShipArr['reciever_name'],
                'ReceiverCode' => "",
                'CityId' => $receiver_city, // Riyadh:2
                'ShipmentDate' => date('Y-m-d h:i'),
                'RecipiantTelephone' => $ShipArr['reciever_phone'],
                'RecipiantMobile' => $ShipArr['reciever_phone'],
                'RecipiantCompanyName' => "",
                'RecipiantAddress' => $ShipArr['reciever_address'],
                'RecipiantEmail' => $ShipArr['reciever_email'],
                'ServiceId' => 65,  // for Riyadh= 65
                "Weight" => $weight,
                "Pieces" => $box_pieces,
                "ShipmentDescription" => $complete_sku,
                "TotalValue" => $cod_amount,
                "TranceportationsMode" => $tranceportationsMode,
                "TaxesPercent" => "0",
                "Taxes" => "0",
                "Net" => "0",
                "CodValue" => $cod_amount,
                
            );

            $json_final_date = json_encode($details);
            //echo $json_final_date;  die;


            if (empty($receiver_city))
            {
                 $response = array('message'=> 'Receiver city empty' ); 
                 $response = json_encode($response);
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
            //print_r($responseArray);die;

            $logresponse =   json_encode($response);  

            $successres = $responseArray['success'];
            //print_r($successres);die;

             if ($successres) 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$json_final_date);
            return $responseArray;
        
    }

    public function flamingoArray($sellername =null, array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $super_id =null) 
    {
        
                $sender_city = getdestinationfieldshow_auto_array($ShipArr['origin'], 'flamingo_city', $super_id);
                $receiver_city= getdestinationfieldshow_auto_array($ShipArr['destination'], 'flamingo_city', $super_id);


                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellersellername_name =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = $ShipArr['sender_address'];
                    $senderphone = $ShipArr['sender_phone'];
                    $senderemail = $ShipArr['sender_email'];
                }

                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }


                $API_URL = $counrierArr['api_url'] . "v2/customer/order";
                
                $coutry_code = getdestinationfieldshow_auto_array($ShipArr['destination'], 'country_code', $super_id);
                $allowed_city = array("Riyadh","RIYADH", "Ad Diriyah", "Diriyah");
                
                $courier_type = 'E_COMMERCE_DELIVERY';
                
                $currency = "SAR";
                
                if (empty($box_pieces1)){
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }


                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

               
    
            $sender_data = array(
                'address_type' => 'business',
                'name' =>$sellername,
                'email' => $senderemail,
                'apartment'=> 221,
                'building' => 'B',
                'street' => $store_address,
                "city" => array(
                    "name" =>$sender_city
                ),
                "country" => array(
                    "id" => 191
                ),
                
    
                    'phone' =>$senderphone,
                    );
                  
    
                $receiverdata = array(
                'address_type' => 'residential',
				'name'=> $ShipArr['reciever_name'],
                'street' => $ShipArr['reciever_address'],
                'city' => array(
                        'name' => $receiver_city
                    ),
                    'phone' => $ShipArr['reciever_phone'],
                    'landmark' => $ShipArr['reciever_address']);
    
    
            $dimensions = array(
                'weight' => $weight,
                'width' =>  '',
                'length' => '',
                'height' =>'' ,
                'unit' => '',
                'domestic' => true
            );
            $package_type = array(
                'courier_type' => $courier_type
            );

            $charge_items[] = array(
                'paid' => $paid,
                'charge' => $cod_amount,
                'charge_type' => "COD"               
            );
    
            $details = array(
                'sender_data' => $sender_data,
                'recipient_data' => $receiverdata,
                'dimensions' => $dimensions,
                'package_type' => $package_type,
                'charge_items' => $charge_items,
                'recipient_not_available' => 'do_not_deliver',
                'payment_type' => $pay_mode,
                'payer' => 'recipient',
                'parcel_value' => $cod_amount,
                'fragile' => true,
                'note' => 'mobile phone',
                'piece_count' => $box_pieces,
                'force_create' => true,
                "reference_id" => $ShipArr['slip_no']
            );

            $json_final_date = json_encode($details);
             // echo "<pre>";  print_r($json_final_date);  die;
            //echo $json_final_date;die;
                     
            if (empty($receiver_city) || empty($coutry_code))
            {
                 $response = array('message'=> 'Receiver city or country code empty'); 
                 $response = json_encode($response);
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
             if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
            }else{
                
                $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_final_date);
            }

            //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_date);
            return $responseArray;
    }

    public function AJOUL_AUTH($sellername =null, $courierArr = array(),$ShipArr = array(), $c_id= null, $box_pieces1= null, $complete_sku= null, $super_id = null){


        
        $sender_city_code = getdestinationfieldshow($ShipArr['origin'], 'ajoul_city_code', $super_id);
        $sender_country_code = getdestinationfieldshow($ShipArr['origin'], 'country_code', $super_id);
        

        $receiver_city_code = getdestinationfieldshow($ShipArr['destination'], 'ajoul_city_code', $super_id);
        $receiver_country_code = getdestinationfieldshow($ShipArr['destination'], 'country_code' , $super_id);
        

        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }
        
        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }
        


        $API_URL = $courierArr['api_url'] . "shipment/create";


        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }

        
        if($ShipArr['mode'] == "COD"){
            $cod_amount = $ShipArr['total_cod_amt'];

        }
        elseif ($ShipArr['mode'] == 'CC'){
            $cod_amount = 0;
        }
        
        $date_time = strtotime(date('Y-m-d h:i:s'));                              
       
        $CURRENT_DATE = date('Y-m-d',$date_time);
        $CURRENT_TIME = date('H:i',$date_time);
      
        $details = array(
            'receiver'=>array(
                'name'=>$ShipArr['reciever_name'],
                'country_code'=>$receiver_country_code,
                'city_code'=>$receiver_city_code,
                'address'=>$ShipArr['reciever_address'],
                'zip_code'=>'',
                'phone'=>$ShipArr['reciever_phone'],
                'email'=>!empty($ShipArr['reciever_email'])?$ShipArr['reciever_email']:'no@no.com'
            ),
            'sender'=>array(
                'name'=>$sellername,
                'country_code'=>$sender_country_code,
                'city_code'=>$sender_city_code,
                'address'=>$store_address,
                'zip_code'=>'',
                'phone'=>$senderphone,
                'email'=>$senderemail
            ),
            "reference"=> $ShipArr['slip_no'],
            "pick_date"=>$CURRENT_DATE,//"2018-08-06",
            "pickup_time"=> $CURRENT_TIME,//"12:49",
            "product_type"=> "104",
            "payment_mode"=> $ShipArr['mode'],
            "parcel_quantity"=> $box_pieces,
            "parcel_weight"=> $weight,
            "service_id"=> "1",
            "description"=> $complete_sku,
            "sku"=> $complete_sku,
            "weight_total"=> $weight,
            "total_cod_amount"=> $cod_amount
        );    

        if(empty($receiver_city_code)){
            $responseArray = array('errors'=>'Receiver City Code is empty');

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, json_encode($responseArray),'Fail', $ShipArr['slip_no'],$ShipArr['old_slip_no'],'');
            }else{
                
                $this->shipmentLog($c_id, json_encode($responseArray),'Fail', $ShipArr['slip_no'], '');
            }
            
            return $responseArray;
        }

        $json_final_date = json_encode($details);
        //echo $json_final_date;die;

        $url =  "http://fastcoo.net/fastcoo-tech/fs_files/ajoul_api.php";
        $token_api_url = $courierArr['api_url']."authorize?client_secret=".$courierArr['courier_pin_no']."&client_id=".$courierArr['courier_account_no']."&username=".$courierArr['user_name']."&password=".$courierArr['password'];
        $Allarra = array('token_api_url' => $token_api_url,'action'=>'get_token','ship_url'=>$API_URL,'data'=>$json_final_date);

        

        $dataJson = json_encode($Allarra);
        
        $headers = array("Content-type: application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJson);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
        $response = curl_exec($ch);
        
        curl_close($ch);
        $responseArray = json_decode($response,true);
        if (isset($responseArray['Shipment']) && !empty($responseArray['Shipment'])) 
        {
                $successstatus = "Success";
        } else {
                $successstatus = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_final_date);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_date);
        }

        //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_date);
        return $responseArray;
    }


    public function ShipsyDataArray($sellername =null, $ShipArr =array(), $counrierArr =array(), $c_id=null, $box_pieces1=null, $complete_sku=null, $super_id=null){

        // echo "<pre>"; 
        //   print_r($ShipArr);die;
        $api_url = $counrierArr['api_url']."softdata";
         
        $xapi = $counrierArr['auth_token'];
        
        $customer_code = $counrierArr['courier_pin_no'];
        
        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
        }

        // echo " store_address = ".  $store_address. $label_info_from; die; 
        //  echo "<pre>"; 
        //   print_r($ShipArr);die;
        





        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        switch($counrierArr['company']){
            case 'FLOW' :       
                $city_column = 'flow_city'; 
                $service_type_id = 'DELIVERY';
                break;
            case 'Mahmool' :    
                $city_column = 'mahmool_city'; 
                $service_type_id = 'Dry';
                break;
            case 'Flow (Installation)' :       
                $city_column = 'flow_city'; 
                $service_type_id = 'DELIVERY&ASSEMBLY';
                break;
            default:            
                $city_column = '';       
                $service_type_id = '';         
                break;
        }
    
        $sender_city = getdestinationfieldshow($ShipArr['origin'], $city_column,$super_id);
        $receiver_city= getdestinationfieldshow($ShipArr['destination'], $city_column,$super_id);
       

       if (empty($box_pieces1)) {
           $box_pieces = 1;
       } else {
           $box_pieces = $box_pieces1;
       }

       if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
        $weight = 1;
   }else {
        $weight = $ShipArr['weight'];
   }

       $pay_mode=$ShipArr['mode'];
       if($ShipArr['mode'] == "COD"){
           $cod_amount = $ShipArr['total_cod_amt'];
           $pay_mode = "cash";
       }
       elseif ($ShipArr['mode'] == 'CC'){
           $cod_amount = 0;
           $pay_mode = "cc";
       }
       
       if(empty( $receiver_city)){
           $logresponse = "Receiver city empty";
           $successstatus  = "Fail";

            if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
                $this->shipmentLogReverse($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],'');
            }else{
                
                $this->shipmentLog($c_id, $logresponse,$successstatus, $ShipArr['slip_no'],'');
            }

           
           return array('data'=>array(array('message'=>'receiver city empty')));
       }

       for($i=1;$i<=$box_pieces;$i++){
        $piecec_array[] = array(
            "description"=> $complete_sku,
            "declared_value"=> $cod_amount,
            "weight"=>$weight,
            "height"=> "",
            "length"=> "",
            "width"=> "",
            "quantity"=> 1,
            "product_code"=> "",
            "volume"=>"",
            "volume_unit"=> "",
            "dimension_unit"=>"",
            "weight_unit"=> "kg"                            
           );
       }
       //print "<pre>"; print_r($piecec_array);die;

       $data_array = array(
           'consignments'=>array(
               array(
                   "customer_code"=> $customer_code,
                   "customer_reference_number"=> $ShipArr['slip_no'],
                   "service_type_id" => $service_type_id,//"DELIVERY",
                   "load_type" => "NON-DOCUMENT",
                   "description" => $complete_sku,
                   "cod_favor_of"=> "",
                   "cod_collection_mode"=> $pay_mode,
                   "dimension_unit"=>"",
                   "length"=> "",
                   "width"=>"",
                   "height"=> "",
                   "weight_unit"=> "kg",
                   "weight"=> $weight,
                   "declared_value"=> $cod_amount,
                   "cod_amount"=>$cod_amount,
                   "num_pieces"=>$box_pieces,
                   "is_risk_surcharge_applicable"=>"",
                   "origin_details"=>array(

                       "name" => $sellername,
                       "phone"=> $senderphone,
                       "alternate_phone"=> "",
                       "address_line_1"=> $store_address,
                       "address_line_2"=> "",
                       "pincode"=> $sender_city,
                       "city"=> $sender_city,
                       "state"=> ""
                   ),
                   "destination_details"=>array(
                       "name" => $ShipArr['reciever_name'],
                       "phone"=> $ShipArr['reciever_phone'],
                       "alternate_phone" => "",
                       "address_line_1" => $ShipArr['reciever_address'],
                       "address_line_2" => "",
                       "pincode" => $receiver_city,
                       "city" => $receiver_city,
                       "state" => ""
                   ),
                   "return_details"=>array(
                       "name" => null,
                       "phone"=> null,
                       "alternate_phone"=> null,
                       "address_line_1"=> null,
                       "address_line_2"=> null,
                       "pincode"=> null,
                       "city"=> null,
                       "state" => null
                   ),
                   "exceptional_return_details"=>array(
                       "name" => null,
                       "phone" => null,
                       "alternate_phone" => null,
                       "address_line_1" => null,
                       "address_line_2" => null,
                       "pincode" => null,
                       "city" => null,
                       "state" => null
                   ),
                   "type_of_delivery"=>null,
                   "pieces_detail"=>$piecec_array,
                   "eway_bill"=>null,
                   "invoice_number" => null,
                   "invoice_date" => null,
                   "commodity_name" => null,
                   "consignment_type" => null,
                   "rescheduled_date" => ""
               )
           )
       );

       $json_string= json_encode($data_array);
       //echo $json_string;die;
       $curl = curl_init();

       curl_setopt_array($curl, array(
       CURLOPT_URL => $api_url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_ENCODING => '',
       CURLOPT_MAXREDIRS => 10,
       CURLOPT_TIMEOUT => 0,
       CURLOPT_FOLLOWLOCATION => true,
       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
       CURLOPT_CUSTOMREQUEST => 'POST',
       CURLOPT_POSTFIELDS =>$json_string,
       CURLOPT_HTTPHEADER => array(
           'api-key: '.$xapi,
           'Content-Type: application/json'
         ),
       ));
       
       $response = curl_exec($curl);
       curl_close($curl);
       //echo $response;die;
       $response_array = json_decode($response,true);
       if($response_array['data'][0]['success'] == true){
           $successstatus  = "Success";
       }else{
           $successstatus  = "Fail";
       }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_string);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        }
       
       //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
       return $response_array;

    }

    public function ShipsyLabel($counrierArr= array(), $client_awb =null){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $counrierArr['api_url'].'shippinglabel/stream?reference_number='.$client_awb,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'api-key: '.$counrierArr['auth_token'],
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }


    public function UPSLabel($client_awb, $courierArr = array()){
        $api_url = $courierArr['api_url']."/labels";

        $data_array = array(
            "LabelRecoveryRequest"=>array(
                "LabelSpecification"=>array(
                    "HTTPUserAgent"=>"",
                    "LabelImageFormat"=>array(
                        "Code"=>"PDF"
                    )
                ),
                "Translate"=>array(
                    "LanguageCode"=>"eng",
                    "DialectCode"=>"US",
                    "Code"=>"01"
                ),
                "LabelDelivery"=>array(
                    "LabelLinkIndicator"=>"",
                    "ResendEMailIndicator"=>"",
                    "EMailMessage"=>array(
                        "EMailAddress"=>""
                    )
                ),
                "TrackingNumber"=>$client_awb
            )

        );
        $username = $courierArr['user_name'];
        $password = $courierArr['password'];
        $AccessLicenseNumber = $courierArr['courier_account_no'];
        $transactionSrc = $courierArr['courier_pin_no'];
        

        $data_json = json_encode($data_array);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$data_json,
        CURLOPT_HTTPHEADER => array(
            'AccessLicenseNumber: '.$AccessLicenseNumber,
            'Password: '.$password,
            'transactionSrc: '.$transactionSrc,
            'Username: '.$username,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response_array =  json_decode($response, TRUE); 
        return $response_array;

    }
    public function UPSArray($sellername= null, array $ShipArr, array $counrierArr, $c_id = null, $box_pieces1 = null,  $complete_sku = null, $super_id = null){


        //print "<pre>"; print_r($counrierArr);die;
        $api_url = $counrierArr['api_url'];
        $username = $counrierArr['user_name'];
        $password = $counrierArr['password'];
        $AccessLicenseNumber = $counrierArr['courier_account_no'];
        $transactionSrc = $counrierArr['courier_pin_no'];
        //$transId = $counrierArr['auth_token'];
        $account_number = $counrierArr['account_entity_code'];
        $service_code= $counrierArr['service_code'];
        //print "<pre>"; print_r($counrierArr);die;



        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
            $senderzipcode = GetallCutomerBysellerId($ShipArr['cust_id'],'pincode');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $senderphone = $ShipArr['sender_phone'];
            $senderemail = $ShipArr['sender_email'];
            $senderzipcode = "";
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }

        
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'ups_city',$super_id);
        $sender_country_code = getdestinationfieldshow($ShipArr['origin'], 'country_code',$super_id);
        
        
        $receiver_city= getdestinationfieldshow($ShipArr['destination'], 'ups_city',$super_id);
        
        
        $receiver_country_code = getdestinationfieldshow($ShipArr['destination'], 'country_code',$super_id);

        $currency = getdestinationfieldshow($ShipArr['destination'], 'currency',$super_id); //"USD";


        if (empty($box_pieces1)) {
            $box_pieces = 1;
        } else {
            $box_pieces = $box_pieces1;
        }

        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }

        $pay_mode=$ShipArr['mode'];
        $total_amount_to_pay ='';
        if($ShipArr['mode'] == "COD"){
            $cod_amount = $ShipArr['total_cod_amt'];
            $total_amount_to_pay = "COD ".$currency." ".$cod_amount." ".$currency;

        }
        elseif ($ShipArr['mode'] == 'CC'){
            $cod_amount = 0;
            $total_amount_to_pay = "CC ".$currency." ".$cod_amount." ".$currency;
        }

        if(empty($complete_sku))  {
            $complete_sku = 'Goods';
        }

        $post_array = array(
            "ShipmentRequest"=>array(
                "Shipment"=>array(
                    "Description"=>$complete_sku,
                    "Ref1"=>$pay_mode,
                    "Ref2"=>$cod_amount,
                    "ReferenceNumber"=>array(
                        array(
                            "Value"=>$ShipArr['slip_no'],
                            "Value"=>$total_amount_to_pay,
                        )
                    ),
                    // "ReferenceNumber"=>array(
                    //     "BarCodeIndicator"=>$ShipArr['slip_no'],
                    //     "Code"=>"",
                    //     "Value"=>$ShipArr['slip_no']
                    // ),
                    "NumOfPiecesInShipment"=>$box_pieces,
                    "Shipper"=>array(
                        "Name"=>$sellername,
                        "AttentionName"=>$sellername,
                        "CompanyDisplayableName"=>$sellername,
                        "TaxIdentificationNumber"=>"",
                        "Phone"=>array(
                            "Number"=>$senderphone
                        ),
                        "ShipperNumber"=>$account_number,
                        "EMailAddress"=>$senderemail,
                        "Address"=>array(
                            "AddressLine"=>$store_address,
                            "City"=>$sender_city,
                            "StateProvinceCode"=>$sender_country_code,
                            "PostalCode"=>"",
                            "CountryCode"=>$sender_country_code,
                        )
                    ),
                    "ShipTo"=>array(
                        "Name"=>$ShipArr['reciever_name'],
                        "AttentionName"=>$ShipArr['reciever_name'],
                        "Phone"=>array(
                            "Number"=>$ShipArr['reciever_phone']
                        ),
                        "FaxNumber"=>"",
                        "TaxIdentificationNumber"=>"",
                        "Address"=>array(
                            "AddressLine"=>$ShipArr['reciever_address'],
                            "City"=>$receiver_city,
                            "StateProvinceCode"=>$receiver_country_code,
                            "PostalCode"=>"",
                            "CountryCode"=>$receiver_country_code
                        )
                    ),
                    "ShipFrom"=>array(
                        "Name"=>$sellername,
                        "AttentionName"=>$sellername,
                        "Phone"=>array(
                            "Number"=>$senderphone
                        ),
                        "FaxNumber"=>"",
                        "TaxIdentificationNumber"=>"",
                        "Address"=>array(
                            "AddressLine"=>$store_address,
                            "City"=>$sender_city,
                            "StateProvinceCode"=>$sender_country_code,
                            "PostalCode"=>"",
                            "CountryCode"=>$sender_country_code
                        )
                    ),
                    "PaymentInformation"=>array(
                        "ShipmentCharge"=>array(
                            "Type"=>"01",
                            "BillShipper"=>array(
                                "AccountNumber"=>$account_number
                            )
                        )
                    ),
                    "Service"=>array(
                        "Code"=>$service_code,
                        "Description"=>""
                    ),
                    "Package"=>array(
                            "Description"=>$complete_sku,
                            "Packaging"=>array(
                                "Code"=>"02"
                            ),
                            "PackageWeight"=>array(
                                "UnitOfMeasurement"=>array(
                                    "Code"=>"KGS"
                                ),
                                "Weight"=>(string)$weight
                            ),
                            "PackageServiceOptions"=>""
                        ),
                    "ItemizedChargesRequestedIndicator"=>"",
                    "RatingMethodRequestedIndicator"=>"",
                    "TaxInformationIndicator"=>"",
                    "ShipmentRatingOptions"=>array(
                        "NegotiatedRatesIndicator"=>""
                    )
                    

                ),
                "LabelSpecification"=>array(
                    "LabelImageFormat"=>array(
                        "Code"=>"PNG"
                    )
                )
            )
        );

        $json_string = json_encode($post_array);
        //echo $json_string;die;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json_string,
        CURLOPT_HTTPHEADER => array(
            'AccessLicenseNumber: '.$AccessLicenseNumber,
            'Password: '.$password,
            //'transId: '.$transId,
            'transactionSrc: '.$transactionSrc,
            'Username: '.$username,
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $responseArray = json_decode($response,TRUE);

        curl_close($curl);

        if (isset($responseArray['ShipmentResponse']['Response']['ResponseStatus']) && $responseArray['ShipmentResponse']['Response']['ResponseStatus']['Code'] == 1) 
        {
                $successstatus = "Success";
        } else {
                $successstatus = "Fail";
        }

        if(isset($ShipArr['old_slip_no']) && !empty($ShipArr['old_slip_no'])){
            $this->shipmentLogReverse($c_id, $response,$successstatus, $ShipArr['slip_no'],$ShipArr['old_slip_no'],$json_string);
        }else{
            
            $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'],$json_string);
        }

        //$log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_string);
        return $responseArray;
    
    }

    public function shipoxDataArray($sellername =null,array $ShipArr, array $counrierArr, $Auth_token = null, $c_id = null, $box_pieces1 = null, $complete_sku=null, $super_id= null) 
    {

                $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
                if($label_info_from == '1'){
                    $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
                    $senderphone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
                    $senderemail = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
                }else{
                    $sellername =  $ShipArr['sender_name'];
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                    $store_address = $ShipArr['sender_address'];
                    $senderphone = $ShipArr['sender_phone'];
                    $senderemail = $ShipArr['sender_email'];
                }

                if(!empty($ShipArr['label_sender_name'])){
                    $sellername =  $ShipArr['label_sender_name'];    
                    if($counrierArr['wharehouse_flag'] =='Y'){
                        $sellername = $sellername ." - ". site_configTable('company_name'); 
                    }
                }

                
                
                $API_URL = $counrierArr['api_url'] . "v2/customer/order";
                
                switch($counrierArr['company']){
                    case 'Kudhha': 
                            $city_column = 'kudhha_city';
                        break;
                    default: 
                            $city_column = 'kudhha_city';
                        break;
                }

                $sender_city = getdestinationfieldshow($ShipArr['origin'], $city_column, $super_id);        
                
                $coutry_code = getdestinationfieldshow($ShipArr['destination'], 'country_code', $super_id);

                $receiver_city= getdestinationfieldshow($ShipArr['destination'], $city_column, $super_id);

                
                $allowed_city = array("Riyadh","RIYADH", "Ad Diriyah", "Diriyah");
                
                if(in_array($receiver_city,$allowed_city)){
                    switch($counrierArr['company']){
                        case 'Kudhha': 
                                $courier_type = 'INSIDE_RIYADH'; 
                            break;
                        default: $courier_type = 'NEXT_DAY_DELIVERY'; 
                            break;
                    }
                }else if($coutry_code != "SA" ){
                    $courier_type = 'INTERNATIONAL_DELIVERY';
                }else{
                    $courier_type = 'DOMESTIC_DELIVERY';                    
                }

                //echo "city=".$courier_type;die;

                
                
                //echo $courier_type;die;
                
                $currency = "SAR";
                
                if (empty($box_pieces1)){
                $box_pieces = 1;
                } else {
                $box_pieces = $box_pieces1;
                }
                
                if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
                    $weight = 1;
               }else {
                    $weight = $ShipArr['weight'];
               }


                if($ShipArr['mode'] == "COD"){
                    $pay_mode = "credit_balance";
                    $cod_amount = $ShipArr['total_cod_amt'];
                    $paid = FALSE;
                }
                elseif ($ShipArr['mode'] == 'CC'){
                    $pay_mode = "credit_balance";
                    $paid = TRUE;
                    $cod_amount = 0;
                }

               
    
            $sender_data = array(
                'address_type' => 'residential',
                'name' =>$sellername,
                'email' => $senderemail,
                'apartment'=> "",
                'building' => '',
                'street' => $store_address,
                "city" => array(
                    "name" =>$sender_city
                ),
                "country" => array(
                    "id" => 191
                ),
                
    
                    'phone' =>$senderphone,
                    );
                  
    
                $receiverdata = array(
                'address_type' => 'residential',
				'name'=> $ShipArr['reciever_name'],
                'street' => $ShipArr['reciever_address'],
                'city' => array(
                        'name' => $receiver_city
                    ),
                    'phone' => $ShipArr['reciever_phone'],
                    'landmark' => $ShipArr['reciever_address']);
    
    
            $dimensions = array(
                'weight' => $weight,
                'width' =>  '',
                'length' => '',
                'height' =>'' ,
                'unit' => '',
                'domestic' => true
            );
            $package_type = array(
                'courier_type' => $courier_type
            );

            $charge_items[] = array(
                'paid' => $paid,
                'charge' => $cod_amount,
                'charge_type' => "COD"               
            );
    
            $details = array(
                'sender_data' => $sender_data,
                'recipient_data' => $receiverdata,
                'dimensions' => $dimensions,
                'package_type' => $package_type,
                'charge_items' => $charge_items,
                'recipient_not_available' => 'do_not_deliver',
                'payment_type' => $pay_mode,
                'payer' => 'sender',
                'parcel_value' => $cod_amount,
                'fragile' => true,
                'note' => $complete_sku,
                'piece_count' => $box_pieces,
                'force_create' => true,
                "reference_id" => $ShipArr['slip_no']
            );

            $json_final_date = json_encode($details);
            //echo $json_final_date;die;
            //  echo "<pre>";  print_r($json_final_date);  die;
              
                     
            if (empty($receiver_city) || empty($coutry_code))
            {
                $successstatus = 'Fail';
                $responseArray = array('message'=> 'Receiver city or country code empty','status'=>$successstatus); 
                $response = json_encode($response);
                $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_date);
                return $responseArray;
            }
            else {
                $curl = curl_init();    
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $API_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $json_final_date,
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Accept: application/json',
                        'Authorization:Bearer ' .$Auth_token),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
            }

            $responseArray = json_decode($response, true);
            //print "<pre>"; print_r($responseArray);die;
       
            $logresponse =   json_encode($response);  
            
            $successres = $responseArray['status'];
            //print_r($successres);die;
    
            if ($successres == 'success') 
            {
                    $successstatus = "Success";
            } else {
                    $successstatus = "Fail";
            }
            $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], $json_final_date);
            return $responseArray;
    }

    public function MakdoonArrayVer1($sellername =null,$ShipArr = array(), $counrierArr= array(), $complete_sku = null,$c_id = null,$box_pieces = null, $super_id=null)
    {

        //$store_address = $ShipArr['sender_address'];
        //$store_phone = $ShipArr['sender_phone'];


        
        $label_info_from = GetallCutomerBysellerId($ShipArr['cust_id'],'label_info_from');
        if($label_info_from == '1'){
        
            $sellername = GetallCutomerBysellerId($ShipArr['cust_id'],'company');
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = GetallCutomerBysellerId($ShipArr['cust_id'],'address');
            $store_phone = GetallCutomerBysellerId($ShipArr['cust_id'],'phone');
            $sender_email = GetallCutomerBysellerId($ShipArr['cust_id'],'email');
        }else{
            $sellername =  $ShipArr['sender_name'];
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
            $store_address = $ShipArr['sender_address'];
            $store_phone = $ShipArr['sender_phone'];
            $sender_email = $ShipArr['sender_email'];
        }

        if(!empty($ShipArr['label_sender_name'])){
            $sellername =  $ShipArr['label_sender_name'];    
            if($counrierArr['wharehouse_flag'] =='Y'){
                $sellername = $sellername ." - ". site_configTable('company_name'); 
            }
        }        

        $receiver_city = getdestinationfieldshow($ShipArr['destination'],'makhdoom',$super_id);
        
        $receiver_country = getdestinationfieldshow($ShipArr['destination'], 'country',$super_id);
        $receiver_city_code = getdestinationfieldshow($ShipArr['destination'], 'makhdoom_city_code',$super_id);

        
        $sender_city = getdestinationfieldshow($ShipArr['origin'], 'makhdoom',$super_id);
        $sender_country = getdestinationfieldshow($ShipArr['origin'],'country',$super_id);
        $sender_city_code = getdestinationfieldshow($ShipArr['origin'], 'makhdoom_city_code',$super_id);

        $API_URL = $counrierArr['api_url'] . "create";
        $Auth_token = $counrierArr['auth_token']; 
        
        
        $box_pieces = empty($box_pieces)?1:$box_pieces;
        if($ShipArr['weight'] >= 0 && $ShipArr['weight'] <= 0.99) {
            $weight = 1;
       }else {
            $weight = $ShipArr['weight'];
       }
        

        if(empty($complete_sku)){
            $complete_sku = "QTY-".$box_pieces; 

        }else {
            $complete_sku = $complete_sku;
        }
        if ($ShipArr['mode'] == 'COD') {
            $codValue = $ShipArr['total_cod_amt'];
        } elseif ($ShipArr['mode'] == 'CC') {
            $codValue = 0;
        }

        $postArray = array(
            'cod' => $codValue,
            'reference_id' => $ShipArr['slip_no'],
            'remarks' => $complete_sku,
            'pickup[sender_name]' => $sellername,
            'pickup[sender_phone]' => $store_phone,
            'pickup[city]' => $sender_city,//'JEDDAH',
            'pickup[city_code]' => $sender_city_code,//'JED',
            'pickup[district]' => $store_address,//'ALMRWAH',
            'pickup[country]' => $sender_country,//'SAUDI ARABIA',
            'pickup[street]' => " - ",
            'pickup[building_number]' => '',
            'pickup[description]' => $complete_sku,
            'dorp_off[recipient_name]' => $ShipArr['reciever_name'],
            'dorp_off[recipient_phone]' => $ShipArr['reciever_phone'],
            'dorp_off[country]' => $receiver_country,//'SAUDI ARABIA',
            'dorp_off[city]' => $receiver_city,//'JEDDAH',
            'dorp_off[city_code]' => $receiver_city_code,//'JED',
            'dorp_off[district]' => $ShipArr['reciever_address'],//'ALSAFA',
            'dorp_off[street]' =>  " - ",
            'dorp_off[building_number]' => '',
            'dorp_off[description]' => $complete_sku,
        );

        foreach($ShipArr['sku_data'] as $i=>$vald){
            $postArray['items['.$i.'][title]'] =substr($vald['name'], 0, 90);
            $postArray['items['.$i.'][quantity]'] =$vald['piece'];
            $postArray['items['.$i.'][weight]'] =$vald['weight'];
            $postArray['items['.$i.'][value]'] =$vald['cod'];
        }
        
        
        
        $successstatus = 'Fail';
        if(!empty($receiver_city)){
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $API_URL,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_SSL_VERIFYHOST => false,
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => $postArray,
              CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                "Authorization: Bearer ".$Auth_token,  
              ),
            ));

            $response = curl_exec($curl); //die; 
            //echo "error=".curl_error($curl);die;
            // echo "Response=".$response;die;
            curl_close($curl);
            $responseData = json_decode($response,TRUE);
            // print "<pre>"; print_r($responseData['message']);die;
            $successFlag = TRUE;

            if(empty($responseData)){
                $successFlag = false;
                $response = 'No Response Found';
            }elseif($responseData['message']=='Unauthenticated.')
            {
                $successFlag = false;
                $responseData = array('status'=>'false','errors'=>'Unauthenticated.! credentials error');
            }
            else{
                if(isset($responseData['errors']) && !empty($responseData['errors'])){
                    $successFlag = false;
                }
            }
            
            //echo var_dump($successFlag);die;
           
            if($successFlag){
                $successstatus = 'Success';
            }else{
                $successstatus = 'Fail';
            }
                    
        }else{
            $responseData = array('status'=>'false','errors'=>'Receiver City Not Found.');
            $response = json_encode($responseData);
        }
        // print_r( $responseData); die; 

        $log = $this->shipmentLog($c_id, $response,$successstatus, $ShipArr['slip_no'], json_encode($postArray));
        return $responseData;
    }

    public function iMileLabel($client_awb=null, $auth_token=null, $counrierArr=array()){
        $customerID = $counrierArr['courier_account_no'];
        $sign = $counrierArr['auth_token'];
        $timestamp =  strtotime(date("Y-m-d H:i:s")) * 1000;
        $url=$counrierArr['api_url']."client/order/reprintOrder"; 
        $requestParams = array(
            "customerId"=>$customerID,
            "accessToken"=>$auth_token, 
            "format"=>"json",
            "signMethod"=>"SimpleKey",
            "version"=>"1.0.0",
            "timestamp"=>$timestamp,//strtotime(date('Y-m-d :i:s')),
            "timeZone"=>"+3",
            "Sign"=>$sign,
            "param"=>array(
                'orderCode'=> $client_awb,
                'orderCodeType'=>"2"
            ) 
         );
        $params = json_encode($requestParams);
        // echo $params;die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Accept: application/json"
        ));
        $response = curl_exec($ch);                                   
        curl_close($ch);
        $responseArray = json_decode($response,true);
        $pdf_encoded_base64 = $responseArray['data']['imileAwb'];
        $pdf_file = base64_decode($pdf_encoded_base64);
        return  $pdf_file;
    }

    public function FlowLabel($counrierArr= array(), $client_awb =null){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $counrierArr['api_url'].'shippinglabel/stream?reference_number='.$client_awb,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'api-key: '.$counrierArr['auth_token'],
            'cache-control: no-cache'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;

    }

    public function Kassib_label($client_awb = null,$courierArr = array()){
        $api_url = $courierArr['api_url'].'orders/label/'.$client_awb.'/ar/A4';
        
        $auth_token = $courierArr['auth_token'];
        $Referer = $courierArr['account_entity_code'];

        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: '.$auth_token,
            'Content-Type: application/json',
            'Referer: '.$Referer
        ),
        ));

        $response = curl_exec($curl); 

        curl_close($curl);
        return $response;

    }

    public function getSaeeLabel($token=null, $label_api_url=null){		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => $label_api_url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/html',
			'secret: '.$token
		),
		));

		$response = curl_exec($curl);
		
		curl_close($curl);
		return $response;
		
	}

    public function SpeedAF_Label($client_awb = null, $courierArr = array()){

        $timeline = getCurrentTimestamp();
        $api_url = "https://apis.speedaf.com/open-api/express/order/print?timestamp=".$timeline."&appCode=".$courierArr['courier_account_no']."";
        
        //$api_url = "http://47.241.40.42:8480/open-api/express/order/print?timestamp=".$timeline."&appCode=".$courierArr['courier_account_no']."";
        //$api_url = "https://apis.test.speedaf.com/open-api/express/order/print?timestamp=".$timeline."&appCode=TT660026";
        $data_array = array(
                'waybillNoList'=>array($client_awb),
                'labelType'=>3 // 1=3sheets 2=2sheetsnologo 3=2sheets
        );

        //$json_data = json_encode($data_array);
        $secret_key = $courierArr['auth_token'];
        
        $encryption_algorithm = 'des-cbc';
        $initilization_vector =  null;

        $ivArray = array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
            $iv = null;
            foreach($ivArray as $element){ $iv .= CHR($element); }
            $initilization_vector = $iv;
        
        $encrypted_data = encrypt_data($data_array, $timeline,$secret_key,$encryption_algorithm, $initilization_vector);
        
        
        $header = array('Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);    //Set the URl
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  //Do not verify the HTTPS certificate
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  //Do not verify HTTPS Host
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // Get data Return
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);    // When CURLOPT_RETURNTRANSFER is enabled, the data will be obtained and returned
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encrypted_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch); //Execution
        
        
        curl_close($ch);
        
        $result_in_array_form =  json_decode($result, true);
        $result_data = decrypt_data($result_in_array_form,$secret_key,$encryption_algorithm, $initilization_vector);
        return $result_data;
        
    }

    public function UPSLabelVer1($client_awb, $courierArr = array()){

        //$api_url = "https://api.eirad.info/api/v2/shipment/get/pdf";
        $api_url = $courierArr['api_url']."/v1/shipment/get/pdf";
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_SSL_VERIFYHOST=>false,
        CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{
            "tracking_ids":[
                "'.$client_awb.'"
            ]
        }',
        CURLOPT_HTTPHEADER => array(
            'x-client-id: '.$courierArr['courier_pin_no'],
            'x-client-secret: '.$courierArr['auth_token'],
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        // print_r($error);die;
        curl_close($curl);
        
        return json_decode($response,TRUE);
    }

    public function GetCompanylistDropQryFPFlag() {

        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->select('id,cc_id,company,fp_flag');
        $this->db->from('courier_company');
        $this->db->where('deleted', 'N');
        $this->db->where('fp_flag', 'Y');
        //$this->db->where('reverse_type', 'N');
        $this->db->where("(api_url!='' or api_url_t!='')");
        $this->db->where('status', 'Y');

        $this->db->order_by("company");
        $this->db->group_by("company");
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    public function GetCompanylistDropQryTorod() {
        
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);

        $this->db->select('*');
        $this->db->from('courier_company');
        $this->db->where('deleted', 'N');
        $this->db->where('cc_id', '114');
        $this->db->where("(api_url!='' or api_url_t!='')");
        $this->db->where('status', 'Y');
        $this->db->where('reverse_type', 'N');

        $this->db->order_by("company");
        $query = $this->db->get();
        // echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function SMB_label_data($slip_no = null)
    {
        $ci = &get_instance();
        $ci->load->database();
        $sql = "SELECT log FROM frwd_shipment_log where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and  slip_no='" . $slip_no . "' and status ='Confirm Success'";
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        $data = json_decode($result[0]['log'], true);
        //  echo "<pre>"; print_r($data); die;
        return $data['data']['public_pdf_label_url'];
    }

    public function ajex_label($slip_no = null)
    {
        //          echo 111;
        //    die;
        $ci = &get_instance();
        $ci->load->database();
        $sql = "SELECT log FROM frwd_shipment_log where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and  slip_no='" . $slip_no . "' and status ='Success' order by id desc";
        //   print_r($sql);
        //   die;
        $query = $ci->db->query($sql);
        $result = $query->result_array();

     
        $response = $result[0]['log'];
        // $response = str_replace("\n","",$response);
        // echo "<pre>"; print_r($result);die;
        //   echo $response;die;
        // $data = json_decode($response, true);
        $data = json_decode($response, true);
        //    echo "<pre>"; print_r($data);die;
        $label =  $data['waybillFileUrl'];
        return $label; 
        // return $data['data']['ShipmentResponse'];

    }


    public function WebhookCourierlist($cc_id,$page_no, $data = array())
    {
        $page_no;
        $limit = 10;
        if (empty($page_no)) {
            $start = 0;
        } else {
            $start = ($page_no - 1) * $limit;
        }
        if ($cc_id) {
            $this->db->select('id,company,webhook_url,webhook_status,cc_id');
            $this->db->from('courier_company');
            $this->db->where('deleted', 'N');
            $this->db->where("webhook_url!=", '');
            $this->db->where('status', 'Y');
            $this->db->where('cc_id', $cc_id);
            $this->db->group_by("company");
           
        } else {
            $this->db->select('id,company,webhook_url,webhook_status,cc_id');
            $this->db->from('courier_company');
            $this->db->where('deleted', 'N');
            $this->db->where("webhook_url!=", '');
            $this->db->where('status', 'Y');
            $this->db->group_by("company");
           
        }
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatewebhookurlqury($cc_name = null, $wehbookurl = null)
    {
      
        $data = array(
            'webhook_url' => $wehbookurl
        );
        $this->db->where('company', $cc_name);
        return $this->db->update('courier_company', $data);
        //  echo $this->db->last_query();die;
    }

    public function webhookstatus_Insert($id, $status)
    {
        $this->db->select('*');
        $this->db->where('cc_id', $id);
        // $this->db->where('status',$status);
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $query = $this->db->get('webhook_active_log');
        $result_array = $query->result_array();

        if (!empty($result_array)) {
            $lastupdatedate = $result_array[0]['last_updated_date'];
            $current_update_date = date("Y-m-d H:i:s");
            $data1 = array(
                'status' => $status,
                'last_updated_date' => $lastupdatedate,
                'current_updated_date' => $current_update_date,
            );
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->where('cc_id', $id);
            $this->db->update('webhook_active_log', $data1);

        } else {
            $entry_date = date("Y-m-d H:i:s");
            $lastupdated_date = date("Y-m-d H:i:s");
            $data2 = array(
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'cc_id' => $id,
                'status' => $status,
                'entry_date' => $entry_date,
                'current_updated_date' => $entry_date,
                'last_Updated_date' => $lastupdated_date
            );
            $this->db->insert('webhook_active_log', $data2);
        }
    }
    
     public function HajerV2Label($slip_no = null,$cc_id=null)
    {
        $ci = &get_instance();
        $ci->load->database();
        $sql = "SELECT log FROM frwd_shipment_log where super_id='" . $ci->session->userdata('user_details')['super_id'] . "' and  slip_no='" . $slip_no . "' and cc_id='".$cc_id."' AND status='Success' ORDER BY ID DESC LIMIT 1";
//        echo $sql;die;
        $query = $ci->db->query($sql);
        $result = $query->result_array();
        $data = json_decode($result[0]['log'], true);
//          echo "<pre>"; print_r($data); die;
        return $data['printLable'];
    }


}
